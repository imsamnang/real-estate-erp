@extends('admin.layouts.admin_layout')

@php
  $title = __('messages.'.$cfg['label_key']);
  $route = $cfg['route'];
  $perm  = $cfg['permission_key'];
  $user  = auth()->user();
  $belongsTo = $cfg['belongs_to'] ?? [];
@endphp

@section('pageTitle', __('messages.common.view').' — '.$title)
@section('breadcrumbTitle', $title)
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('admin.'.$route.'.index') }}">{{ $title }}</a></li>
  <li class="breadcrumb-item active" aria-current="page">#{{ $row->getKey() }}</li>
@endsection

@section('breadcrumbActions')
  <a href="{{ route('admin.'.$route.'.index') }}" class="btn btn-light btn-sm me-2">
    <i class="bi bi-arrow-left me-1"></i> {{ __('messages.common.back') }}
  </a>
  @if(($cfg['read_only'] ?? false) === false && $user && $user->hasPermission("$perm.edit"))
    <a href="{{ route('admin.'.$route.'.edit', $row->getKey()) }}" class="btn btn-primary btn-sm">
      <i class="bi bi-pencil me-1"></i> {{ __('messages.common.edit') }}
    </a>
  @endif
@endsection

@section('content')
<div class="card shadow-sm border-0">
  <div class="card-header">
    <i class="bi {{ $cfg['icon'] }} me-2"></i>
    {{ $title }} #{{ $row->getKey() }}
  </div>
  <div class="card-body">
    <dl class="row mb-0">
      @foreach($cfg['fields'] as $f)
        @php
          [$name, $type, $labelKey, $opts] = array_pad($f, 4, []);
          $opts = $opts ?? [];
          if (in_array($type, ['password'])) continue;
          $label = __('messages.'.$labelKey);
          $val = $row->{$name};

          if ($type === 'foreign') {
            foreach ($belongsTo as $rel => $def) {
              $fk = is_array($def) ? ($def[1] ?? $rel.'_id') : ($rel.'_id');
              if ($fk === $name && $row->{$rel}) {
                $disp = $opts['display'] ?? 'name';
                $val = $row->{$rel}->{$disp} ?? $val;
                break;
              }
            }
          } elseif ($type === 'bool') {
            $val = $val ? __('messages.common.yes') : __('messages.common.no');
          } elseif ($type === 'date' && $val) {
            $val = \Carbon\Carbon::parse($val)->format('Y-m-d');
          } elseif ($type === 'datetime' && $val) {
            $val = \Carbon\Carbon::parse($val)->format('Y-m-d H:i');
          } elseif ($type === 'decimal' && $val !== null) {
            $val = number_format((float) $val, 2);
          } elseif ($type === 'json' && ! empty($opts['multi_select_model'])) {
            $relName = $name;
            $val = method_exists($row, $relName)
              ? $row->{$relName}->pluck($opts['display'] ?? 'name')->join(', ')
              : '—';
          }
        @endphp
        @if(!in_array($type, ['password']))
          <dt class="col-sm-3 text-muted">{{ $label }}</dt>
          <dd class="col-sm-9">
            @if($type === 'enum' && $val)
              <span class="status-pill {{ $val }}">{{ $val }}</span>
            @elseif($type === 'json' && empty($opts['multi_select_model']) && $val !== null && $val !== '')
              <pre class="mb-0 small bg-light p-2 rounded">{{ is_array($val) || is_object($val) ? json_encode($val, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $val }}</pre>
            @else
              {{ $val !== null && $val !== '' ? $val : '—' }}
            @endif
          </dd>
        @endif
      @endforeach
      <dt class="col-sm-3 text-muted">{{ __('messages.common.created_at') }}</dt>
      <dd class="col-sm-9">{{ optional($row->created_at)->format('Y-m-d H:i') ?? '—' }}</dd>
      <dt class="col-sm-3 text-muted">{{ __('messages.common.updated_at') }}</dt>
      <dd class="col-sm-9">{{ optional($row->updated_at)->format('Y-m-d H:i') ?? '—' }}</dd>
    </dl>
  </div>
</div>
@endsection
