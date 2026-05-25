<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CodeSequenceService;
use App\Support\ModuleManifest;
use Carbon\Carbon;
use Flasher\Prime\FlasherInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

abstract class BaseCrudController extends Controller
{
    /** Subclasses must set this to a key registered in ModuleManifest::all(). */
    protected string $moduleKey;

    protected function config(): array
    {
        $modules = ModuleManifest::all();
        if (! isset($modules[$this->moduleKey])) {
            abort(500, "Module '{$this->moduleKey}' is not registered.");
        }

        return $modules[$this->moduleKey];
    }

    /** Model class FQN. */
    protected function modelClass(): string
    {
        return '\\App\\Models\\'.$this->config()['model'];
    }

    /** Build a fresh query instance for the model. */
    protected function newQuery()
    {
        $class = $this->modelClass();

        return $class::query();
    }

    /** Optionally restrict by current user's branch/company (super_admin sees all). */
    protected function applyScope($query)
    {
        $user = Auth::user();
        if (! $user || $user->isSuperAdmin()) {
            return $query;
        }
        $fields = collect($this->config()['fields'])->pluck(0)->all();
        if (in_array('company_id', $fields, true) && $user->company_id) {
            $query->where($query->getModel()->getTable().'.company_id', $user->company_id);
        }
        if (in_array('branch_id', $fields, true) && $user->branch_id && ! method_exists($user, 'isSuperAdmin')) {
            // Branch scoping kept lenient for demo; super_admin already bypassed above.
        }

        return $query;
    }

    public function index()
    {
        return view('admin.crud.index', [
            'cfg' => $this->config(),
            'moduleKey' => $this->moduleKey,
            'datatableUrl' => route('admin.'.$this->config()['route'].'.datatable'),
        ]);
    }

    public function datatable(Request $request)
    {
        $cfg = $this->config();
        $model = new ($this->modelClass());
        $table = $model->getTable();

        $hasTimestamps = $model->timestamps;
        $tsColumn = $hasTimestamps
            ? 'created_at'
            : ($cfg['timestamp_field'] ?? 'created_at');

        $columns = ['id'];
        foreach ($cfg['fields'] as $f) {
            [$name, $type] = $f;
            if (in_array($type, ['json', 'password'], true)) {
                continue;
            }
            $columns[] = $name;
        }
        if ($hasTimestamps && ! in_array('created_at', $columns, true)) {
            $columns[] = 'created_at';
        }

        $query = $this->newQuery()->select(array_map(fn ($c) => "$table.$c", $columns));
        $this->applyScope($query);

        // Eager load belongs_to relations to render display values.
        $belongsTo = $cfg['belongs_to'] ?? [];
        if (! empty($belongsTo)) {
            $query->with(array_keys($belongsTo));
        }

        $dt = DataTables::eloquent($query)
            ->addColumn('action', function ($row) use ($cfg) {
                $route = $cfg['route'];
                $editUrl = route("admin.$route.edit", $row->getKey());
                $deleteUrl = route("admin.$route.destroy", $row->getKey());
                $showUrl = route("admin.$route.show", $row->getKey());
                $perm = $cfg['permission_key'];
                $readOnly = $cfg['read_only'] ?? false;
                $user = auth()->user();
                $canEdit = $user && $user->hasPermission("$perm.edit");
                $canDelete = $user && $user->hasPermission("$perm.delete");
                $csrf = csrf_token();
                $html = '<div class="d-inline-flex gap-1">';
                $html .= '<a href="'.$showUrl.'" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>';
                if (! $readOnly && $canEdit) {
                    $html .= '<a href="'.$editUrl.'" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>';
                }
                if (! $readOnly && $canDelete) {
                    $html .= '<form action="'.$deleteUrl.'" method="POST" class="d-inline" data-confirm-delete data-confirm-message="'.__('messages.common.confirm_delete').'">'
                          .'<input type="hidden" name="_token" value="'.$csrf.'">'
                          .'<input type="hidden" name="_method" value="DELETE">'
                          .'<button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>'
                          .'</form>';
                }
                $html .= '</div>';

                return $html;
            })
            ->filterColumn('id', fn ($q, $k) => $q->where($q->getModel()->getTable().'.id', $k));
        if ($hasTimestamps) {
            $dt->editColumn('created_at', fn ($row) => optional($row->created_at)->format('Y-m-d H:i'));
        }

        $rawColumns = ['action'];

        foreach ($cfg['fields'] as $f) {
            [$name, $type, , $opts] = array_pad($f, 4, []);
            if (in_array($type, ['json', 'password'], true)) {
                continue;
            }
            $opts = $opts ?? [];

            if ($type === 'foreign') {
                $relName = null;
                foreach ($belongsTo as $rel => $def) {
                    $fk = is_array($def) ? ($def[1] ?? $rel.'_id') : ($rel.'_id');
                    if ($fk === $name) {
                        $relName = $rel;
                        break;
                    }
                }
                $disp = $opts['display'] ?? 'name';
                if ($relName) {
                    $dt->editColumn($name, function ($row) use ($relName, $disp, $name) {
                        return ($row->{$relName} && isset($row->{$relName}->{$disp}))
                            ? $row->{$relName}->{$disp}
                            : ($row->{$name} ?? '—');
                    });
                }
            } elseif ($type === 'bool') {
                $dt->editColumn($name, fn ($row) => $row->{$name}
                    ? __('messages.common.yes')
                    : __('messages.common.no'));
            } elseif ($type === 'enum') {
                $dt->editColumn($name, function ($row) use ($name) {
                    $val = $row->{$name};

                    return $val
                        ? '<span class="status-pill '.e($val).'">'.e($val).'</span>'
                        : '—';
                });
                $rawColumns[] = $name;
            } elseif ($type === 'decimal') {
                $dt->editColumn($name, fn ($row) => $row->{$name} !== null
                    ? number_format((float) $row->{$name}, 2)
                    : '—');
            } elseif ($type === 'date') {
                $dt->editColumn($name, fn ($row) => $row->{$name}
                    ? Carbon::parse($row->{$name})->format('Y-m-d')
                    : '—');
            } elseif ($type === 'datetime') {
                $dt->editColumn($name, fn ($row) => $row->{$name}
                    ? Carbon::parse($row->{$name})->format('Y-m-d H:i')
                    : '—');
            }
        }

        return $dt->rawColumns($rawColumns)->toJson();
    }

    public function show($id)
    {
        $cfg = $this->config();
        $row = $this->newQuery()->with(array_keys($cfg['belongs_to'] ?? []))->findOrFail($id);

        return view('admin.crud.show', [
            'cfg' => $cfg,
            'moduleKey' => $this->moduleKey,
            'row' => $row,
        ]);
    }

    public function create()
    {
        $cfg = $this->config();
        if ($cfg['read_only'] ?? false) {
            abort(403);
        }

        return view('admin.crud.create', [
            'cfg' => $cfg,
            'moduleKey' => $this->moduleKey,
            'options' => $this->relationOptions($cfg),
        ]);
    }

    public function store(Request $request, FlasherInterface $flasher)
    {
        $cfg = $this->config();
        if ($cfg['read_only'] ?? false) {
            abort(403);
        }
        $data = $this->validatePayload($request, $cfg, null);

        $modelClass = $this->modelClass();
        $row = new $modelClass;

        DB::transaction(function () use ($cfg, $data, $row, $modelClass) {
            $this->fillRow($row, $cfg, $data, $modelClass, isUpdate: false);
            $row->save();
            $this->syncPivots($row, $cfg, $data);
        });

        $flasher->addSuccess(__('messages.common.created'));

        return redirect()->route('admin.'.$cfg['route'].'.index');
    }

    public function edit($id)
    {
        $cfg = $this->config();
        if ($cfg['read_only'] ?? false) {
            abort(403);
        }
        $row = $this->newQuery()->findOrFail($id);

        return view('admin.crud.edit', [
            'cfg' => $cfg,
            'moduleKey' => $this->moduleKey,
            'options' => $this->relationOptions($cfg, $row),
            'row' => $row,
        ]);
    }

    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $cfg = $this->config();
        if ($cfg['read_only'] ?? false) {
            abort(403);
        }
        $row = $this->newQuery()->findOrFail($id);
        $data = $this->validatePayload($request, $cfg, $row);

        $modelClass = $this->modelClass();

        DB::transaction(function () use ($cfg, $data, $row, $modelClass) {
            $this->fillRow($row, $cfg, $data, $modelClass, isUpdate: true);
            $row->save();
            $this->syncPivots($row, $cfg, $data);
        });

        $flasher->addSuccess(__('messages.common.updated'));

        return redirect()->route('admin.'.$cfg['route'].'.index');
    }

    public function destroy($id, FlasherInterface $flasher)
    {
        $cfg = $this->config();
        if ($cfg['read_only'] ?? false) {
            abort(403);
        }
        $row = $this->newQuery()->findOrFail($id);
        $row->delete();
        $flasher->addSuccess(__('messages.common.deleted'));

        return redirect()->route('admin.'.$cfg['route'].'.index');
    }

    // ---------------------------- helpers ---------------------------------

    protected function fillRow(Model $row, array $cfg, array $data, string $modelClass, bool $isUpdate): void
    {
        foreach ($cfg['fields'] as $f) {
            [$name, $type, , $opts] = array_pad($f, 4, []);
            $opts = $opts ?? [];

            // Read-only fields are never assigned from request data — they're
            // system-set (e.g. cancelled_at, approved_by, audit blobs) and only
            // shown on the index/show pages.
            if (! empty($opts['read_only'])) {
                continue;
            }

            if ($type === 'json' && isset($opts['multi_select_model'])) {
                continue; // pivot, handled later
            }

            if (! array_key_exists($name, $data)) {
                continue;
            }

            $value = $data[$name];

            if ($type === 'password') {
                if (! $value) {
                    continue; // keep existing
                }
                $value = Hash::make($value);
            }

            if ($type === 'bool') {
                $value = (bool) $value;
            }

            $row->{$name} = $value === '' ? null : $value;
        }

        // Auto-code generation for fields with auto=true
        foreach ($cfg['fields'] as $f) {
            [$name, $type, , $opts] = array_pad($f, 4, []);
            $opts = $opts ?? [];
            if (! ($opts['auto'] ?? false)) {
                continue;
            }
            if ($row->{$name}) {
                continue;
            } // already supplied
            $row->{$name} = app(CodeSequenceService::class)->next($cfg['table'], $name);
        }

        // auto_user fields (e.g. created_by)
        foreach ($cfg['fields'] as $f) {
            [$name, , , $opts] = array_pad($f, 4, []);
            $opts = $opts ?? [];
            if (! ($opts['auto_user'] ?? false)) {
                continue;
            }
            if (! $isUpdate && ! $row->{$name}) {
                $row->{$name} = optional(Auth::user())->getKey();
            }
        }
    }

    protected function syncPivots(Model $row, array $cfg, array $data): void
    {
        foreach ($cfg['fields'] as $f) {
            [$name, $type, , $opts] = array_pad($f, 4, []);
            $opts = $opts ?? [];
            if ($type !== 'json' || empty($opts['multi_select_model'])) {
                continue;
            }

            $ids = $data[$name] ?? [];
            $ids = array_values(array_filter(array_map('intval', (array) $ids)));

            $method = method_exists($row, $name) ? $name : null;
            if ($method) {
                $row->$method()->sync($ids);
            }
        }
    }

    protected function validatePayload(Request $request, array $cfg, ?Model $row): array
    {
        $rules = [];
        foreach ($cfg['fields'] as $f) {
            [$name, $type, , $opts] = array_pad($f, 4, []);
            $opts = $opts ?? [];

            // Read-only fields are not validated — they're never submitted by
            // the form (skipped in _form.blade.php).
            if (! empty($opts['read_only'])) {
                continue;
            }

            $rule = [];
            $isAuto = (bool) ($opts['auto'] ?? false);
            $required = (($opts['required'] ?? false) && ! $isAuto)
                || ($row === null && ($opts['required_on_create'] ?? false));
            $rule[] = $required ? 'required' : 'nullable';

            switch ($type) {
                case 'integer':
                case 'foreign':
                    $rule[] = 'integer';
                    break;
                case 'decimal':
                    $rule[] = 'numeric';
                    break;
                case 'date':
                    $rule[] = 'date';
                    break;
                case 'datetime':
                    $rule[] = 'date';
                    break;
                case 'bool':
                    $rule[] = 'boolean';
                    break;
                case 'enum':
                    $rule[] = 'string';
                    if (! empty($opts['options'])) {
                        $rule[] = 'in:'.implode(',', $opts['options']);
                    }
                    break;
                case 'password':
                    if ($row === null) {
                        $rule[] = 'string';
                        $rule[] = 'min:6';
                    } else {
                        // optional on update
                        $rule[0] = 'nullable';
                        $rule[] = 'min:6';
                    }
                    break;
                case 'string':
                    $rule[] = 'string';
                    $rule[] = 'max:255';
                    break;
                case 'text':
                    $rule[] = 'string';
                    break;
                case 'json':
                    $rule[0] = 'nullable';
                    $rule[] = 'array';
                    break;
            }

            if (($opts['unique'] ?? false)) {
                $unique = 'unique:'.$cfg['table'].','.$name;
                if ($row) {
                    $unique .= ','.$row->getKey();
                }
                $rule[] = $unique;
            }

            $rules[$name] = $rule;
        }

        $messages = [];
        $attributes = [];
        foreach ($cfg['fields'] as $f) {
            [$name, , $labelKey] = $f;
            $attributes[$name] = __('messages.'.$labelKey);
        }

        $payload = $request->validate($rules, $messages, $attributes);

        // Booleans default to false if checkbox unchecked
        foreach ($cfg['fields'] as $f) {
            [$name, $type] = $f;
            if ($type === 'bool' && ! $request->has($name)) {
                $payload[$name] = false;
            }
        }

        return $payload;
    }

    /** Build options arrays for foreign-key dropdowns. */
    protected function relationOptions(array $cfg, ?Model $row = null): array
    {
        $out = [];
        foreach ($cfg['fields'] as $f) {
            [$name, $type, , $opts] = array_pad($f, 4, []);
            $opts = $opts ?? [];
            if ($type === 'foreign' && ! empty($opts['model'])) {
                $class = '\\App\\Models\\'.$opts['model'];
                if (class_exists($class)) {
                    $display = $opts['display'] ?? 'name';
                    $rows = $class::query()->orderBy($display)->limit(500)->get();
                    $out[$name] = $rows->mapWithKeys(fn ($r) => [$r->getKey() => $r->{$display}])->all();
                }
            } elseif ($type === 'json' && ! empty($opts['multi_select_model'])) {
                $class = '\\App\\Models\\'.$opts['multi_select_model'];
                if (class_exists($class)) {
                    $display = $opts['display'] ?? 'name';
                    $rows = $class::query()->orderBy($display)->limit(1000)->get();
                    $out[$name] = $rows->mapWithKeys(fn ($r) => [$r->getKey() => $r->{$display}])->all();
                }
            }
        }

        return $out;
    }
}
