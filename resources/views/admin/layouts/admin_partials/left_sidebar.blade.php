@php
  /**
   * Each section: 'group_label_key' => [ ['route', 'label_key', 'icon', 'permission'], ... ]
   * Items are hidden automatically when the user lacks the required permission.
   */
  $sections = [
    'main' => [
      ['admin.dashboard', 'common.dashboard', 'bi-house-door', null],
    ],
    'organization' => [
      ['admin.companies.index', 'modules.companies', 'bi-building',  'companies.view'],
      ['admin.branches.index',  'modules.branches',  'bi-shop',      'branches.view'],
      ['admin.users.index',     'modules.users',     'bi-people',    'users.view'],
      ['admin.roles.index',     'modules.roles',     'bi-shield-lock','roles.view'],
      ['admin.permissions.index','modules.permissions','bi-key',     'permissions.view'],
    ],
    'crm' => [
      ['admin.customers.index',       'modules.customers',       'bi-person-vcard',  'customers.view'],
      ['admin.leads.index',           'modules.leads',           'bi-megaphone',     'leads.view'],
      ['admin.lead-activities.index', 'modules.lead_activities', 'bi-chat-square-text','lead_activities.view'],
    ],
    'property' => [
      ['admin.projects.index',           'modules.projects',           'bi-diagram-3',     'projects.view'],
      ['admin.project-phases.index',     'modules.project_phases',     'bi-bar-chart-steps','project_phases.view'],
      ['admin.property-types.index',     'modules.property_types',     'bi-tags',           'property_types.view'],
      ['admin.properties.index',         'modules.properties',         'bi-house',          'properties.view'],
      ['admin.property-images.index',    'modules.property_images',    'bi-images',         'property_images.view'],
      ['admin.property-documents.index', 'modules.property_documents', 'bi-file-earmark-text','property_documents.view'],
      ['admin.land-parcels.index',       'modules.land_parcels',       'bi-geo-alt',        'land_parcels.view'],
    ],
    'sale' => [
      ['admin.bookings.index',               'modules.bookings',               'bi-bookmark-check', 'bookings.view'],
      ['admin.sale-contracts.index',         'modules.sale_contracts',         'bi-file-earmark-ruled','sale_contracts.view'],
      ['admin.sale-contract-items.index',    'modules.sale_contract_items',    'bi-list-ul',        'sale_contract_items.view'],
      ['admin.installment-schedules.index',  'modules.installment_schedules',  'bi-calendar3',      'installment_schedules.view'],
    ],
    'invoice' => [
      ['admin.payment-methods.index',     'modules.payment_methods',     'bi-credit-card',  'payment_methods.view'],
      ['admin.invoices.index',            'modules.invoices',            'bi-receipt',      'invoices.view'],
      ['admin.invoice-items.index',       'modules.invoice_items',       'bi-receipt-cutoff','invoice_items.view'],
      ['admin.payments.index',            'modules.payments',            'bi-cash-coin',    'payments.view'],
      ['admin.payment-allocations.index', 'modules.payment_allocations', 'bi-diagram-2',    'payment_allocations.view'],
      ['admin.refunds.index',             'modules.refunds',             'bi-arrow-counterclockwise','refunds.view'],
    ],
    'rental' => [
      ['admin.rental-contracts.index', 'modules.rental_contracts', 'bi-file-earmark-medical','rental_contracts.view'],
      ['admin.rental-invoices.index',  'modules.rental_invoices',  'bi-cash-stack',         'rental_invoices.view'],
    ],
    'sales_team' => [
      ['admin.sales-teams.index',        'modules.sales_teams',        'bi-people-fill','sales_teams.view'],
      ['admin.sales-team-members.index', 'modules.sales_team_members', 'bi-person-plus','sales_team_members.view'],
      ['admin.commissions.index',        'modules.commissions',        'bi-percent',    'commissions.view'],
    ],
    'document' => [
      ['admin.documents.index', 'modules.documents', 'bi-folder', 'documents.view'],
    ],
    'finance' => [
      ['admin.chart-of-accounts.index',  'modules.chart_of_accounts',  'bi-bookmarks',         'chart_of_accounts.view'],
      ['admin.expense-categories.index', 'modules.expense_categories', 'bi-tags-fill',         'expense_categories.view'],
      ['admin.expenses.index',           'modules.expenses',           'bi-currency-exchange', 'expenses.view'],
      ['admin.journal-entries.index',    'modules.journal_entries',    'bi-journal-text',      'journal_entries.view'],
      ['admin.journal-entry-items.index','modules.journal_entry_items','bi-list-columns',      'journal_entry_items.view'],
    ],
    'hr_asset' => [
      ['admin.departments.index',      'modules.departments',      'bi-diagram-2-fill','departments.view'],
      ['admin.employees.index',        'modules.employees',        'bi-person-badge',  'employees.view'],
      ['admin.asset-categories.index', 'modules.asset_categories', 'bi-box-seam',      'asset_categories.view'],
      ['admin.assets.index',           'modules.assets',           'bi-pc-display',    'assets.view'],
    ],
    'approval' => [
      ['admin.approval-requests.index', 'modules.approval_requests', 'bi-check2-circle','approval_requests.view'],
      ['admin.approval-steps.index',    'modules.approval_steps',    'bi-list-check',   'approval_steps.view'],
      ['admin.tasks.index',             'modules.tasks',             'bi-check2-square','tasks.view'],
    ],
    'system' => [
      ['admin.notifications.index',   'modules.notifications',   'bi-bell',            'notifications.view'],
      ['admin.audit-logs.index',      'modules.audit_logs',      'bi-shield-check',    'audit_logs.view'],
      ['admin.login-histories.index', 'modules.login_histories', 'bi-box-arrow-in-right','login_histories.view'],
      ['admin.settings.index',        'modules.settings',        'bi-gear',            'settings.view'],
      ['admin.code-sequences.index',  'modules.code_sequences',  'bi-123',             'code_sequences.view'],
    ],
  ];

  $user = auth()->user();
@endphp

<aside class="sidebar-wrapper">
  <div class="sidebar-header">
    <div>
      <span class="logo-icon-circle d-inline-flex align-items-center justify-content-center bg-primary text-white"
            style="width:36px;height:36px;border-radius:8px;font-weight:600;">RE</span>
    </div>
    <div>
      <h4 class="logo-text mb-0">{{ __('messages.app.name') }}</h4>
    </div>
    <div class="toggle-icon ms-auto"><i class="bi bi-chevron-double-left"></i></div>
  </div>

  <ul class="metismenu" id="menu">
    @foreach($sections as $sectionKey => $items)
      @php
        $visible = collect($items)->filter(function ($item) use ($user) {
          [$route, $label, $icon, $perm] = $item;
          return ! $perm || ($user && $user->hasPermission($perm));
        });
      @endphp
      @if($visible->isNotEmpty())
        <li class="menu-label">{{ __('messages.sidebar.'.$sectionKey) }}</li>
        @foreach($visible as $item)
          @php [$route, $label, $icon, $perm] = $item; @endphp
          <li class="{{ request()->routeIs($route) ? 'mm-active' : '' }}">
            <a href="{{ Route::has($route) ? route($route) : '#' }}">
              <div class="parent-icon"><i class="bi {{ $icon }}"></i></div>
              <div class="menu-title">{{ __('messages.'.$label) }}</div>
            </a>
          </li>
        @endforeach
      @endif
    @endforeach
  </ul>
</aside>
