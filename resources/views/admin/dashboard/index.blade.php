@extends('admin.layouts.admin_layout')

@section('pageTitle', __('messages.common.dashboard'))
@section('breadcrumbTitle', __('messages.common.dashboard'))

@section('content')
@php
  $cards = [
    ['key' => 'properties',     'icon' => 'bi-house',           'class' => 'bg-primary'],
    ['key' => 'customers',      'icon' => 'bi-person-vcard',    'class' => 'bg-success'],
    ['key' => 'leads',          'icon' => 'bi-megaphone',       'class' => 'bg-info'],
    ['key' => 'bookings',       'icon' => 'bi-bookmark-check',  'class' => 'bg-warning'],
    ['key' => 'sale_contracts', 'icon' => 'bi-file-earmark-ruled','class' => 'bg-dark'],
    ['key' => 'invoices',       'icon' => 'bi-receipt',         'class' => 'bg-danger'],
    ['key' => 'users',          'icon' => 'bi-people',          'class' => 'bg-secondary'],
    ['key' => 'branches',       'icon' => 'bi-shop',            'class' => 'bg-primary'],
    ['key' => 'companies',      'icon' => 'bi-building',        'class' => 'bg-success'],
  ];
@endphp

<div class="row g-3 mb-4">
  @foreach($cards as $card)
    <div class="col-md-6 col-lg-4 col-xl-3">
      <div class="card shadow-sm border-0">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="rounded-circle d-flex align-items-center justify-content-center {{ $card['class'] }} text-white"
               style="width:48px;height:48px;font-size:1.4rem;">
            <i class="bi {{ $card['icon'] }}"></i>
          </div>
          <div class="flex-grow-1">
            <div class="small text-muted text-uppercase">{{ __('messages.modules.'.$card['key']) }}</div>
            <div class="h4 mb-0">{{ number_format($stats[$card['key']]) }}</div>
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>

<div class="row g-3">
  <div class="col-lg-5">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-header">
        <i class="bi bi-receipt me-1"></i> {{ __('messages.modules.invoices') }} — {{ __('messages.common.status') }}
      </div>
      <div class="card-body p-0">
        <table class="table table-sm mb-0">
          <thead>
            <tr>
              <th>{{ __('messages.common.status') }}</th>
              <th class="text-end">{{ __('messages.common.amount') }}</th>
              <th class="text-end">#</th>
            </tr>
          </thead>
          <tbody>
            @forelse($invoicesByStatus as $r)
              <tr>
                <td><span class="status-pill {{ $r->status }}">{{ $r->status }}</span></td>
                <td class="text-end">{{ number_format((float) $r->amount, 2) }}</td>
                <td class="text-end">{{ $r->total }}</td>
              </tr>
            @empty
              <tr><td colspan="3" class="text-center text-muted py-3">{{ __('messages.common.no_records') }}</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-lg-7">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-header">
        <i class="bi bi-bookmark-check me-1"></i> {{ __('messages.modules.bookings') }}
      </div>
      <div class="card-body p-0">
        <table class="table table-sm mb-0">
          <thead>
            <tr>
              <th>#</th>
              <th>{{ __('messages.common.code') }}</th>
              <th>{{ __('messages.modules.customers') }}</th>
              <th>{{ __('messages.modules.properties') }}</th>
              <th>{{ __('messages.common.amount') }}</th>
              <th>{{ __('messages.common.status') }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentBookings as $b)
              <tr>
                <td>{{ $b->id }}</td>
                <td>{{ $b->booking_no }}</td>
                <td>{{ optional($b->customer)->name ?? '—' }}</td>
                <td>{{ optional($b->property)->title ?? '—' }}</td>
                <td>{{ number_format((float) $b->booking_amount, 2) }} {{ $b->currency }}</td>
                <td><span class="status-pill {{ $b->status }}">{{ $b->status }}</span></td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center text-muted py-3">{{ __('messages.common.no_records') }}</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
