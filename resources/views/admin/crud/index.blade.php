@extends('admin.layouts.admin_layout')

@php
  $title = __('messages.'.$cfg['label_key']);
  $perm  = $cfg['permission_key'];
  $route = $cfg['route'];
  $user  = auth()->user();
  $canCreate = $user && $user->hasPermission("$perm.create");
  $readOnly  = $cfg['read_only'] ?? false;
@endphp

@section('pageTitle', $title)
@section('breadcrumbTitle', $title)
@section('breadcrumb')
  <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
@endsection

@section('breadcrumbActions')
  @if($canCreate && !$readOnly)
    <a href="{{ route('admin.'.$route.'.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg me-1"></i> {{ __('messages.common.create') }}
    </a>
  @endif
@endsection

@section('content')
<div class="card shadow-sm border-0">
  <div class="card-header d-flex align-items-center justify-content-between">
    <div>
      <i class="bi {{ $cfg['icon'] }} me-2"></i>{{ $title }}
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="dt-{{ $cfg['route'] }}" class="table table-hover table-sm w-100">
        <thead>
          <tr>
            <th>#</th>
            @foreach($cfg['fields'] as $f)
              @php [$name, $type, $labelKey] = $f; @endphp
              @if(!in_array($type, ['json','password','text']))
                <th>{{ __('messages.'.$labelKey) }}</th>
              @endif
            @endforeach
            <th>{{ __('messages.common.created_at') }}</th>
            <th class="text-end" style="width:130px;">{{ __('messages.common.actions') }}</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

@push('scripts')
@php
  $columnDefs = [];
  foreach ($cfg['fields'] as $f) {
      $columnDefs[] = [
          'name'  => $f[0],
          'type'  => $f[1],
          'label' => __('messages.'.$f[2]),
      ];
  }
@endphp
<script>
  (function () {
    const cfg = @json($columnDefs);

    const columns = [{ data: 'id', name: 'id', width: 50 }];
    cfg.forEach((f) => {
      if (['json','password','text'].includes(f.type)) return;
      columns.push({ data: f.name, name: f.name });
    });
    columns.push({ data: 'created_at', name: 'created_at', width: 130 });
    columns.push({ data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end' });

    document.addEventListener('DOMContentLoaded', function () {
      window.makeServerSideDataTable('#dt-{{ $cfg['route'] }}', '{{ $datatableUrl }}', columns);
    });
  })();
</script>
@endpush
@endsection
