@extends('admin.layouts.admin_layout')

@php
  $title = __('messages.'.$cfg['label_key']);
  $route = $cfg['route'];
@endphp

@section('pageTitle', __('messages.common.edit').' — '.$title)
@section('breadcrumbTitle', $title)
@section('breadcrumb')
  <li class="breadcrumb-item"><a href="{{ route('admin.'.$route.'.index') }}">{{ $title }}</a></li>
  <li class="breadcrumb-item active" aria-current="page">{{ __('messages.common.edit') }}</li>
@endsection

@section('content')
<div class="card shadow-sm border-0">
  <div class="card-header">
    <i class="bi {{ $cfg['icon'] }} me-2"></i>
    {{ __('messages.common.edit') }} — {{ $title }} #{{ $row->getKey() }}
  </div>
  <div class="card-body">
    <form action="{{ route('admin.'.$route.'.update', $row->getKey()) }}" method="POST" novalidate>
      @csrf
      @method('PUT')
      @include('admin.crud._form', ['cfg' => $cfg, 'options' => $options, 'row' => $row])
    </form>
  </div>
</div>
@endsection
