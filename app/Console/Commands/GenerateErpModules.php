<?php

namespace App\Console\Commands;

use App\Support\ModuleManifest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * One-shot generator that emits per-module Models, Controllers, separate
 * Blade files (index/create/edit/show/_form), and the admin routes file.
 *
 * Idempotent — re-running overwrites the generated files but preserves any
 * manually-edited models listed in $protectedModels.
 */
class GenerateErpModules extends Command
{
    protected $signature = 'erp:generate {--force : Overwrite without prompts}';

    protected $description = 'Generate models, controllers and blade files for every ERP module';

    /** Models we hand-wrote with extra traits/relations — generator must NOT overwrite. */
    protected array $protectedModels = ['User', 'Role', 'Permission', 'Company', 'Branch', 'LoginHistory'];

    public function handle(): int
    {
        $modules = ModuleManifest::all();
        $force = (bool) $this->option('force');

        $this->generateModels($modules, $force);
        $this->generateControllers($modules, $force);
        $this->generateBlades($modules, $force);
        $this->generateRoutes($modules);

        $this->info(sprintf('Generated %d modules.', count($modules)));

        return self::SUCCESS;
    }

    private function generateModels(array $modules, bool $force): void
    {
        $dir = app_path('Models');
        File::ensureDirectoryExists($dir);

        foreach ($modules as $key => $cfg) {
            $modelName = $cfg['model'];
            if (in_array($modelName, $this->protectedModels, true)) {
                continue;
            }

            $belongsTo = $cfg['belongs_to'] ?? [];
            $useSoftDeletes = ($cfg['soft_deletes'] ?? false);
            $uuidPk = ($cfg['uuid_pk'] ?? false);
            $readOnly = ($cfg['read_only'] ?? false);
            $table = $cfg['table'];

            $fillable = collect($cfg['fields'])->pluck(0)
                ->reject(fn ($n) => in_array($n, ['id'], true))
                ->reject(function ($n) use ($cfg) {
                    // Drop json-pivot pseudo-fields like 'roles'
                    foreach ($cfg['fields'] as $f) {
                        if ($f[0] === $n && ($f[1] ?? null) === 'json' && ! empty(($f[3] ?? [])['multi_select_model'])) {
                            return true;
                        }
                    }

                    return false;
                })->values()->all();

            $casts = [];
            foreach ($cfg['fields'] as $f) {
                [$name, $type] = $f;
                if ($type === 'date') {
                    $casts[$name] = 'date';
                } elseif ($type === 'datetime') {
                    $casts[$name] = 'datetime';
                } elseif ($type === 'decimal') {
                    $casts[$name] = 'decimal:2';
                } elseif ($type === 'integer' || $type === 'foreign') {
                    $casts[$name] = 'integer';
                } elseif ($type === 'bool') {
                    $casts[$name] = 'boolean';
                }
            }

            // belongs_to method bodies
            $belongs = '';
            foreach ($belongsTo as $rel => $def) {
                $modelFqn = is_array($def) ? $def[0] : $def;
                $foreignKey = is_array($def) ? ($def[1] ?? null) : null;
                $belongs .= "    public function {$rel}(): BelongsTo\n    {\n";
                $belongs .= "        return \$this->belongsTo(\\App\\Models\\{$modelFqn}::class".
                    ($foreignKey ? ", '{$foreignKey}'" : '').");\n";
                $belongs .= "    }\n\n";
            }

            // pivot many-to-many for roles/permissions on User/Role (handled elsewhere)
            $manyToMany = '';
            foreach ($cfg['fields'] as $f) {
                [$name, $type, , $opts] = array_pad($f, 4, []);
                $opts = $opts ?? [];
                if ($type === 'json' && ! empty($opts['multi_select_model'])) {
                    $other = $opts['multi_select_model'];
                    $pivot = $opts['pivot'] ?? null;
                    if (! $pivot) {
                        continue;
                    }
                    $manyToMany .= "    public function {$name}(): BelongsToMany\n    {\n";
                    $manyToMany .= "        return \$this->belongsToMany(\\App\\Models\\{$other}::class, '{$pivot}');\n";
                    $manyToMany .= "    }\n\n";
                }
            }

            $uses = ['use Illuminate\Database\Eloquent\Model;'];
            if ($useSoftDeletes) {
                $uses[] = 'use Illuminate\Database\Eloquent\SoftDeletes;';
            }
            if (! empty($belongsTo)) {
                $uses[] = 'use Illuminate\Database\Eloquent\Relations\BelongsTo;';
            }
            if ($manyToMany) {
                $uses[] = 'use Illuminate\Database\Eloquent\Relations\BelongsToMany;';
            }
            $uses = implode("\n", array_unique($uses));

            $traits = [];
            if ($useSoftDeletes) {
                $traits[] = 'use SoftDeletes;';
            }
            $traitsBlock = $traits ? '    '.implode("\n    ", $traits)."\n\n" : '';

            $uuidBlock = '';
            if ($uuidPk) {
                $uuidBlock = "    public \$incrementing = false;\n".
                             "    protected \$keyType = 'string';\n\n";
            }

            $tableLine = "    protected \$table = '{$table}';\n\n";

            $fillableArr = '['.implode(', ', array_map(fn ($n) => "'{$n}'", $fillable)).']';
            $castsArr = '['.implode(', ', array_map(fn ($k, $v) => "'{$k}' => '{$v}'", array_keys($casts), array_values($casts))).']';
            $fillableLine = "    protected \$fillable = {$fillableArr};\n\n";
            $castsLine = "    protected function casts(): array { return {$castsArr}; }\n\n";

            $timestampsLine = '';
            if ($table === 'audit_logs' || $table === 'login_histories') {
                $timestampsLine = "    public \$timestamps = false;\n\n";
            }

            $code = <<<PHP
<?php

namespace App\Models;

{$uses}

class {$modelName} extends Model
{
{$traitsBlock}{$tableLine}{$timestampsLine}{$uuidBlock}{$fillableLine}{$castsLine}{$belongs}{$manyToMany}}

PHP;

            $path = "$dir/$modelName.php";
            if (! $force && File::exists($path)) {
                $existing = File::get($path);
                if (str_contains($existing, '// CUSTOM-PRESERVE')) {
                    continue; // user-protected
                }
            }
            File::put($path, $code);
        }
    }

    private function generateControllers(array $modules, bool $force): void
    {
        $dir = app_path('Http/Controllers/Admin');
        File::ensureDirectoryExists($dir);

        foreach ($modules as $key => $cfg) {
            $className = Str::studly($key).'Controller';
            $code = <<<PHP
<?php

namespace App\Http\Controllers\Admin;

class {$className} extends BaseCrudController
{
    protected string \$moduleKey = '{$key}';
}

PHP;
            File::put("$dir/$className.php", $code);
        }
    }

    private function generateBlades(array $modules, bool $force): void
    {
        $base = resource_path('views/admin');
        foreach ($modules as $key => $cfg) {
            $folder = "$base/$key";
            File::ensureDirectoryExists($folder);

            $route = $cfg['route'];

            // index.blade.php — module-specific list page
            File::put("$folder/index.blade.php", <<<'BLADE'
@extends('admin.crud.index', ['cfg' => $cfg, 'datatableUrl' => $datatableUrl])

BLADE);

            File::put("$folder/create.blade.php", <<<'BLADE'
@extends('admin.crud.create', ['cfg' => $cfg, 'options' => $options])

BLADE);

            File::put("$folder/edit.blade.php", <<<'BLADE'
@extends('admin.crud.edit', ['cfg' => $cfg, 'options' => $options, 'row' => $row])

BLADE);

            File::put("$folder/show.blade.php", <<<'BLADE'
@extends('admin.crud.show', ['cfg' => $cfg, 'row' => $row])

BLADE);

            File::put("$folder/_form.blade.php", <<<BLADE
{{-- Module form partial for "$key" — wraps the shared partial so each module
     has its own dedicated blade file as requested. --}}
@include('admin.crud._form', ['cfg' => \$cfg, 'options' => \$options ?? [], 'row' => \$row ?? null])

BLADE);
        }
    }

    private function generateRoutes(array $modules): void
    {
        $lines = [
            '<?php',
            '',
            '/**',
            ' * Auto-generated admin routes — DO NOT EDIT BY HAND.',
            ' * Re-run `php artisan erp:generate` to regenerate.',
            ' */',
            '',
            'use Illuminate\Support\Facades\Route;',
            '',
        ];

        foreach ($modules as $key => $cfg) {
            $controller = '\\App\\Http\\Controllers\\Admin\\'.Str::studly($key).'Controller';
            $route = $cfg['route'];
            $perm = $cfg['permission_key'];
            $readOnly = $cfg['read_only'] ?? false;

            $lines[] = "// === {$key} ===";
            $lines[] = "Route::middleware('permission:{$perm}.view')->group(function () {";
            $lines[] = "    Route::get('{$route}', [{$controller}::class, 'index'])->name('{$route}.index');";
            $lines[] = "    Route::get('{$route}/datatable', [{$controller}::class, 'datatable'])->name('{$route}.datatable');";
            $lines[] = "    Route::get('{$route}/{id}', [{$controller}::class, 'show'])->whereNumber('id')->name('{$route}.show');";
            $lines[] = '});';

            if (! $readOnly) {
                $lines[] = "Route::middleware('permission:{$perm}.create')->group(function () {";
                $lines[] = "    Route::get('{$route}/create', [{$controller}::class, 'create'])->name('{$route}.create');";
                $lines[] = "    Route::post('{$route}', [{$controller}::class, 'store'])->name('{$route}.store');";
                $lines[] = '});';

                $lines[] = "Route::middleware('permission:{$perm}.edit')->group(function () {";
                $lines[] = "    Route::get('{$route}/{id}/edit', [{$controller}::class, 'edit'])->whereNumber('id')->name('{$route}.edit');";
                $lines[] = "    Route::put('{$route}/{id}', [{$controller}::class, 'update'])->whereNumber('id')->name('{$route}.update');";
                $lines[] = '});';

                $lines[] = "Route::middleware('permission:{$perm}.delete')->group(function () {";
                $lines[] = "    Route::delete('{$route}/{id}', [{$controller}::class, 'destroy'])->whereNumber('id')->name('{$route}.destroy');";
                $lines[] = '});';
            }
            $lines[] = '';
        }

        File::put(base_path('routes/admin_modules.php'), implode("\n", $lines));
    }
}
