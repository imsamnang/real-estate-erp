# Real-Estate ERP — Developer Guide

> A complete, step-by-step tutorial for working on this project end-to-end —
> from a fresh `git clone` all the way through running, customizing, testing
> and deploying the system for end users.

If you only have 5 minutes, jump to **[§2 Quick Start](#2-quick-start)**.

---

## សេចក្តីផ្តើមជាខ្មែរ (Khmer summary)

ឯកសារនេះគឺជាមេរៀនពេញលេញសម្រាប់ **អ្នកអភិវឌ្ឍន៍ (Developer)** ដើម្បីដំណើរការ
គម្រោង *Real-Estate ERP Management System* នេះ ចាប់ពីការដំឡើងលើ
machine ដំបូងរហូតដល់ការ deploy ទៅ production។

មាតិកាសំខាន់ៗ៖

- ការដំឡើង Laravel 12 ជាមួយ PHP 8.2+ និង Composer / Node
- ការបង្កើតមូលដ្ឋានទិន្នន័យ (SQLite ដោយលំនាំដើម — អាចប្តូរទៅ MySQL/PostgreSQL)
- រចនាសម្ព័ន្ធ Database ៥០ table និងម៉ូឌុល CRUD ៤៨
- RBAC (Role / Permission / User) ដោយធ្វើដោយដៃ មិនប្រើ Spatie
- ការប្តូរភាសា **ខ្មែរ / English** ដោយមិនបាច់ refresh page (Livewire SPA navigate)
- ការប្រើ Yajra Server-Side DataTables, SweetAlert2 confirm delete, PHPFlasher
  toast, Flatpickr datetime, Tom Select dropdown
- ការ generate code ស្វ័យប្រវត្តិ (CUS-000001, BK-000001, …)
- Audit log និង Approval workflow
- ការ run tests, ការ deploy ទៅ production

ផ្នែកបច្ចេកទេសត្រូវបានសរសេរជា **English** ដោយចេតនា ដើម្បីស៊ីគ្នាជាមួយឯកសារ
ផ្លូវការរបស់ Laravel/Livewire/Bootstrap ហើយដើម្បីឱ្យអ្នកអភិវឌ្ឍន៍ search ឯកសារ
ខាងក្រៅងាយស្រួល។ បើអ្នកត្រូវការសរសេរជាខ្មែរ ១០០% សូមប្រាប់។

---

## Table of contents

1. [Project overview](#1-project-overview)
2. [Quick start](#2-quick-start)
3. [Architecture overview](#3-architecture-overview)
4. [Database & models](#4-database--models)
5. [Authentication & RBAC](#5-authentication--rbac)
6. [Multi-language (KH / EN)](#6-multi-language-kh--en)
7. [Admin layout & UI conventions](#7-admin-layout--ui-conventions)
8. [ModuleManifest: the CRUD factory](#8-modulemanifest-the-crud-factory)
9. [The 48 modules in detail](#9-the-48-modules-in-detail)
10. [Server-side DataTables](#10-server-side-datatables)
11. [UI components (SweetAlert2, PHPFlasher, Flatpickr, Tom Select)](#11-ui-components)
12. [Advanced features (audit log, approval, code sequences)](#12-advanced-features)
13. [Seeding & test data](#13-seeding--test-data)
14. [Testing](#14-testing)
15. [Development workflow](#15-development-workflow)
16. [Deployment to production](#16-deployment-to-production)
17. [Troubleshooting](#17-troubleshooting)
18. [Appendix — file & command reference](#18-appendix)

---

## 1. Project overview

**Real-Estate ERP Management System** is a multi-branch ERP for a real-estate
company. It covers the full operational lifecycle of a property business:

```
Lead → Customer → Booking → Sale Contract / Rental Contract
                    ↓
            Invoice → Payment / Refund
                    ↓
        Commission ← Sales Team
                    ↓
        Journal Entry → Chart of Accounts
                    ↓
        Audit Log / Approval / Notification
```

### Functional scope

| Area | Examples |
|---|---|
| **Organization** | Companies, branches, users, roles, permissions |
| **CRM** | Customers, leads, lead activities |
| **Property** | Projects, project phases, property types, properties, property images/documents, land parcels |
| **Sale** | Bookings, sale contracts (+ items), installment schedules |
| **Invoice** | Payment methods, invoices (+ items), payments, allocations, refunds |
| **Rental** | Rental contracts, rental invoices |
| **Sales team** | Teams, members, commissions |
| **Document mgmt** | Generic documents repository |
| **Finance** | Chart of accounts, expense categories, expenses, journal entries (+ items) |
| **HR & Assets** | Departments, employees, asset categories, assets |
| **Approval** | Approval requests, approval steps, tasks |
| **System** | Notifications, audit logs, login histories, settings, code sequences |

Total: **48 CRUD modules** backed by **50 DB tables** (the 2 extras are the
pivot tables `role_user` and `permission_role`).

### Tech stack

| Layer | Technology | Version |
|---|---|---|
| Language | PHP | 8.2+ |
| Framework | Laravel | 12.x |
| Reactive UI | Livewire | 4.x |
| Frontend interactivity | Alpine.js | 3.x (bundled with Livewire) |
| Toasts | PHPFlasher + SweetAlert2 driver | 2.6.x |
| Delete confirm | SweetAlert2 | 11.x |
| Date picker | Flatpickr | 4.x |
| Searchable dropdown | Tom Select | 2.x |
| DataTables (server side) | Yajra DataTables Oracle | 12.7+ |
| CSS framework | Bootstrap | 5.x |
| Database (dev) | SQLite | bundled |
| Database (prod) | MySQL / PostgreSQL | tested portable |

---

## 2. Quick start

### 2.1 Prerequisites

| Tool | Version | Install hint |
|---|---|---|
| PHP | ≥ 8.2 with `pdo_sqlite`, `mbstring`, `xml`, `curl`, `zip`, `bcmath`, `intl` | `apt install php8.2 php8.2-{sqlite3,mbstring,xml,curl,zip,bcmath,intl}` |
| Composer | ≥ 2.6 | https://getcomposer.org/download/ |
| Node.js | ≥ 20 | https://nodejs.org or use `nvm` |
| Git | ≥ 2.30 | `apt install git` |

Verify with:

```bash
php --version          # PHP 8.2.x or higher
composer --version     # 2.6.x or higher
node --version         # v20.x or higher
git --version
```

### 2.2 Clone & install

```bash
git clone https://github.com/imsamnang/real-estate-erp.git
cd real-estate-erp

composer install               # PHP dependencies
cp .env.example .env           # Local config
php artisan key:generate       # Generate APP_KEY

# Database (SQLite by default — zero config)
touch database/database.sqlite
php artisan migrate --seed     # Creates 50 tables + seed data

# Frontend
npm install
npm run build                  # Production build of Vite assets
```

Or simply run the bundled composer script that does all of the above:

```bash
composer run setup
```

### 2.3 Start the dev server

```bash
php artisan serve              # http://127.0.0.1:8000
```

…or, for the full dev experience with hot-reload, queue worker and log
tailer all running concurrently:

```bash
composer run dev
```

This starts (via `concurrently`):

- `php artisan serve` — HTTP server
- `php artisan queue:listen` — queue worker
- `php artisan pail` — live log tailer
- `npm run dev` — Vite HMR

### 2.4 First login

Open <http://127.0.0.1:8000/admin/login> and use any of the seeded accounts:

| Username | Password | Role |
|---|---|---|
| `superadmin` | `password` | Super Admin — sees everything |
| `admin` | `password` | Branch Admin |
| `manager` | `password` | Branch Manager (sales + property + invoicing) |
| `agent` | `password` | Sales Agent (limited: customers, leads, bookings) |

You should land on the dashboard. Try switching the language by clicking the
`EN` / `KM` toggle at the top right — the whole layout (sidebar, breadcrumb,
content) re-renders without a browser reload.

---

## 3. Architecture overview

### 3.1 High-level diagram

```
┌──────────────────────────────────────────────────────────────┐
│                       Browser                               │
│  Bootstrap 5 + Livewire + Alpine + DataTables + SweetAlert2 │
└──────────────┬───────────────────────────────┬───────────────┘
               │ HTTP / Livewire WebSocket     │ AJAX
┌──────────────▼───────────────────────────────▼───────────────┐
│                       Laravel 12                            │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ Routes (web.php → admin_modules.php)                    │ │
│ │ Middleware (SetLocale, AuthenticateAdmin, EnsurePerm)   │ │
│ │ Controllers (BaseCrudController + 48 subclasses)        │ │
│ │ Services (CodeSequenceService, audit log observer)      │ │
│ │ Livewire (LanguageSwitcher)                             │ │
│ │ ModuleManifest ← single source of truth for all modules │ │
│ └──────────────────────────────────────────────────────────┘ │
└──────────────┬───────────────────────────────────────────────┘
               │ Eloquent
┌──────────────▼───────────────────────────────────────────────┐
│           Database (50 tables, SQLite/MySQL/PgSQL)          │
└──────────────────────────────────────────────────────────────┘
```

### 3.2 Folder structure (key files)

```
real-estate-erp/
├── app/
│   ├── Concerns/
│   │   └── HasRoles.php                 ← RBAC trait on User model
│   ├── Console/Commands/
│   │   └── GenerateErpModules.php       ← Code generator
│   ├── Http/
│   │   ├── Controllers/Admin/
│   │   │   ├── BaseCrudController.php   ← Base for all 48 CRUDs
│   │   │   ├── DashboardController.php
│   │   │   ├── LanguageController.php
│   │   │   └── <Module>Controller.php × 48
│   │   ├── Controllers/Auth/LoginController.php
│   │   └── Middleware/
│   │       ├── AuthenticateAdmin.php
│   │       ├── EnsurePermission.php
│   │       ├── RedirectIfAuthenticated.php
│   │       └── SetLocale.php
│   ├── Livewire/
│   │   └── LanguageSwitcher.php         ← KH/EN toggle (SPA navigate)
│   ├── Models/                          ← 48 Eloquent models
│   ├── Services/
│   │   └── CodeSequenceService.php      ← Auto codes (CUS-000001, …)
│   └── Support/
│       └── ModuleManifest.php           ← THE source of truth
│
├── bootstrap/app.php                    ← Middleware/alias registration
│
├── config/                              ← Stock Laravel 12 config
│
├── database/
│   ├── migrations/
│   │   └── 2026_05_14_000000_create_real_estate_erp_management_system_tables.php
│   └── seeders/
│       └── DatabaseSeeder.php           ← Roles, perms, users, sample data
│
├── lang/
│   ├── en/messages.php
│   └── km/messages.php                  ← Khmer translations
│
├── public/
│   └── assets/backend/assets/
│       ├── css/style.css                ← Theme + dropdown polish
│       └── js/app.js                    ← Global JS (delete confirm, …)
│
├── resources/views/
│   ├── admin/
│   │   ├── crud/
│   │   │   ├── _form.blade.php          ← Generic form for all modules
│   │   │   ├── index.blade.php          ← Generic DataTable index
│   │   │   ├── create.blade.php
│   │   │   ├── edit.blade.php
│   │   │   └── show.blade.php
│   │   └── layouts/
│   │       ├── admin_layout.blade.php   ← Master layout
│   │       └── admin_partials/
│   │           ├── head.blade.php
│   │           ├── header.blade.php
│   │           ├── left_sidebar.blade.php
│   │           └── scripts.blade.php
│   └── livewire/
│       └── language-switcher.blade.php
│
├── routes/
│   ├── web.php                          ← Top-level routes
│   └── admin_modules.php                ← Auto-generated, 48 modules
│
└── tests/
    └── Feature/                         ← phpunit tests
```

### 3.3 Request lifecycle (a typical CRUD page)

```
GET /admin/customers
   │
   ▼
[ web middleware: SetLocale ]      ← reads session('locale'), sets app locale
   │
   ▼
[ admin group ]
   │
   ▼
[ auth.admin middleware ]          ← AuthenticateAdmin: redirects to login if guest
   │
   ▼
[ permission:customers.view ]      ← EnsurePermission: aborts 403 if missing
   │
   ▼
CustomersController@index ─────▶ extends BaseCrudController
   │
   ▼
view('admin.crud.index', [
   'cfg'          => ModuleManifest::all()['customers'],
   'moduleKey'    => 'customers',
   'datatableUrl' => route('admin.customers.datatable'),
])
   │
   ▼
admin.layouts.admin_layout
   ├─ head        (CSS: bootstrap, datatables, flatpickr, tomselect, theme)
   ├─ header      (language switcher, user dropdown)
   ├─ left_sidebar (RBAC-filtered menu)
   ├─ breadcrumb
   ├─ content     (DataTable shell)
   └─ scripts     (JS: jQuery, datatables, sweetalert2, flatpickr, tomselect, app.js)
```

The browser then issues an **AJAX request** to `/admin/customers/datatable`
which goes through the same middleware stack and is served by
`CustomersController@datatable` (inherited from `BaseCrudController`).

---

## 4. Database & models

### 4.1 Schema overview

All 50 tables are created by a single migration:

```
database/migrations/2026_05_14_000000_create_real_estate_erp_management_system_tables.php
```

Keeping the schema in **one** file makes it easy to read end-to-end and to
keep table creation order correct (FKs reference parents declared earlier in
the same file).

Tables, grouped by functional area:

| # | Group | Tables |
|---|---|---|
| 1 | **Organization** | `companies`, `branches`, `users`, `roles`, `permissions`, `role_user`*, `permission_role`* |
| 2 | **CRM** | `customers`, `leads`, `lead_activities` |
| 3 | **Property** | `projects`, `project_phases`, `property_types`, `properties`, `property_images`, `property_documents`, `land_parcels` |
| 4 | **Sale** | `bookings`, `sale_contracts`, `sale_contract_items`, `installment_schedules` |
| 5 | **Invoice** | `payment_methods`, `invoices`, `invoice_items`, `payments`, `payment_allocations`, `refunds` |
| 6 | **Rental** | `rental_contracts`, `rental_invoices` |
| 7 | **Sales team** | `sales_teams`, `sales_team_members`, `commissions` |
| 8 | **Document mgmt** | `documents` |
| 9 | **Finance** | `chart_of_accounts`, `expense_categories`, `expenses`, `journal_entries`, `journal_entry_items` |
| 10 | **HR & Assets** | `departments`, `employees`, `asset_categories`, `assets` |
| 11 | **Approval** | `approval_requests`, `approval_steps`, `tasks` |
| 12 | **System** | `notifications`, `audit_logs`, `login_histories`, `settings`, `code_sequences` |

\* `role_user` and `permission_role` are pivot tables (no CRUD module).

### 4.2 Field conventions

Every business table follows the same pattern:

```php
$table->id();
$table->foreignId('company_id')->nullable()->constrained('companies');
$table->foreignId('branch_id')->nullable()->constrained('branches');
// ... business columns ...
$table->enum('status', ['active', 'inactive'])->default('active');
$table->foreignId('created_by')->nullable()->constrained('users');
$table->timestamps();          // created_at, updated_at
$table->softDeletes();         // deleted_at (soft delete by default)
```

System columns that appear on workflow tables (bookings, sale_contracts, …):

| Column | When set | Type |
|---|---|---|
| `created_by` | On insert by `auto_user` opt | FK→users |
| `approved_by` / `approved_at` | When an approval is granted | FK→users / datetime |
| `cancelled_by` / `cancelled_at` | On cancellation | FK→users / datetime |
| `posted_by` / `posted_at` | On posting (finance) | FK→users / datetime |
| `old_values` / `new_values` | Audit log payload | JSON |

These are marked `'read_only' => true` in the `ModuleManifest`, so they are:

- **Hidden** from create/edit forms
- **Shown** on index and show pages (for audit visibility)
- **Never** taken from `$request` input (system code sets them)

### 4.3 Eloquent models

Each table has an Eloquent model in `app/Models/`. All business models share
this skeleton:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id', 'branch_id', 'customer_code', 'name',
        'phone', 'email', 'address', 'national_id',
        'date_of_birth', 'gender', 'occupation',
        'status', 'created_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function company()  { return $this->belongsTo(Company::class); }
    public function branch()   { return $this->belongsTo(Branch::class); }
    public function creator()  { return $this->belongsTo(User::class, 'created_by'); }
    public function bookings() { return $this->hasMany(Booking::class); }
}
```

The `User` model is special — it uses `App\Concerns\HasRoles` for RBAC and
includes `Authenticatable`, `Notifiable` etc.

### 4.4 Adding a new column

1. Add the column to the migration (or add a new migration file)
2. Add to `$fillable` on the model
3. Add the field entry to `ModuleManifest::all()['<module>']['fields']`
4. Run `php artisan migrate` (and `php artisan db:seed --class=DatabaseSeeder`
   if you also seed it)

That's it — index, show, create, edit views all update automatically because
they read from the manifest.

---

## 5. Authentication & RBAC

### 5.1 Login

Login lives at `/admin/login`. Route definition (excerpt):

```php
// routes/web.php
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest.admin')->group(function () {
        Route::get('login',  [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [LoginController::class, 'login'])->name('login.attempt');
    });
});
```

`LoginController@login` authenticates against the `web` guard and writes a
`login_histories` row on success (and on failure, for security).

### 5.2 Middleware aliases

Registered in `bootstrap/app.php`:

```php
$middleware->alias([
    'auth.admin'  => AuthenticateAdmin::class,    // 'is user logged in'
    'guest.admin' => RedirectIfAuthenticated::class,
    'permission'  => EnsurePermission::class,     // 'has permission X'
]);
```

`SetLocale` runs on every web request and sets `app()->setLocale(...)` from
`session('locale')` (defaults to `en`).

### 5.3 Roles & permissions (no Spatie)

RBAC is implemented manually in `app/Concerns/HasRoles.php`:

| Table | Purpose |
|---|---|
| `roles` | Named roles (`super_admin`, `admin`, `branch_manager`, `sales_agent`) |
| `permissions` | `<module>.<action>` (e.g. `customers.view`, `bookings.create`) |
| `permission_role` | Pivot |
| `role_user` | Pivot |

Permission names are auto-generated from `ModuleManifest`:

```php
// database/seeders/DatabaseSeeder.php
$actions = ['view', 'create', 'edit', 'delete'];
foreach (array_keys(ModuleManifest::all()) as $module) {
    foreach ($actions as $action) {
        Permission::firstOrCreate(['name' => "$module.$action"], [...]);
    }
}
```

Total: **48 × 4 = 192 permissions**.

### 5.4 Permission checks

**On routes** (most common):

```php
Route::middleware('permission:customers.view')->group(function () {
    Route::get('customers', [CustomersController::class, 'index'])->name('customers.index');
});
```

**In controllers / services**:

```php
if (! auth()->user()->hasPermission('bookings.approve')) {
    abort(403);
}
```

**In Blade**:

```blade
@if(auth()->user()?->hasPermission('customers.create'))
  <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">+ Add</a>
@endif
```

### 5.5 Super admin bypass

`hasPermission()` short-circuits to `true` when the user has the
`super_admin` role:

```php
public function hasPermission(string $permission): bool
{
    if ($this->hasRole('super_admin')) {
        return true;
    }
    return $this->permissionsCollection()->contains(fn ($p) => $p->name === $permission);
}
```

### 5.6 Branch / company scoping

The base CRUD controller scopes non-super-admin queries to the user's
`company_id` automatically:

```php
// BaseCrudController::applyScope()
if (in_array('company_id', $fields, true) && $user->company_id) {
    $query->where($table.'.company_id', $user->company_id);
}
```

This means a branch-admin in company #1 cannot see records from company #2,
even via the DataTable AJAX endpoint.

---

## 6. Multi-language (KH / EN)

The system supports **Khmer (`km`)** and **English (`en`)** out of the box.
Switching is instant — Livewire SPA-navigates the current URL so the entire
layout re-renders with the new locale, no full page reload.

### 6.1 Translation files

```
lang/
├── en/messages.php
└── km/messages.php
```

Both files are structured identically:

```php
return [
    'app'    => ['name' => 'Real Estate ERP'],
    'common' => [
        'name' => 'Name', 'code' => 'Code', 'email' => 'Email', ...
        'create' => 'Create', 'edit' => 'Edit', 'delete' => 'Delete', ...
    ],
    'modules' => [
        'companies' => 'Companies', 'branches' => 'Branches', ...
    ],
    'sidebar' => ['organization' => 'Organization', 'crm' => 'CRM', ...],
    'fields' => [                  // per-table, per-column overrides
        'bookings' => [
            'booking_date' => 'Booking Date',
            'cancelled_at' => 'Cancelled At',
        ],
    ],
];
```

Use translations in Blade via:

```blade
{{ __('messages.modules.customers') }}
{{ __('messages.common.create') }}
{{ __('messages.fields.bookings.booking_date') }}
```

The `ModuleManifest` references **only translation keys** (`label_key`), not
hardcoded strings, so any module can be rendered in any locale automatically.

### 6.2 SetLocale middleware

```php
// app/Http/Middleware/SetLocale.php
public function handle($request, Closure $next)
{
    $locale = $request->session()->get('locale', config('app.locale'));
    if (in_array($locale, explode(',', env('APP_AVAILABLE_LOCALES', 'en,km')), true)) {
        app()->setLocale($locale);
    }
    return $next($request);
}
```

Registered in `bootstrap/app.php` and appended to the `web` middleware
group, so it runs on every page (and every Livewire AJAX request, too).

### 6.3 Livewire LanguageSwitcher component

The "no-refresh" switching is the core trick. See
`app/Livewire/LanguageSwitcher.php`:

```php
public function switch(string $locale): void
{
    $available = explode(',', env('APP_AVAILABLE_LOCALES', 'en,km'));
    if (! in_array($locale, $available, true)) return;

    session()->put('locale', $locale);
    app()->setLocale($locale);
    $this->locale = $locale;

    $this->dispatch('locale-changed', locale: $locale);

    // Re-fetch the page via Livewire's SPA-style navigate so the entire
    // layout re-renders with the new locale, no full page reload.
    $this->js('Livewire.navigate(window.location.href)');
}
```

Important: we **dispatch as JS** (`$this->js(...)`) because
`url()->current()` server-side returns the Livewire `/livewire/update`
endpoint, not the actual page URL. The `Livewire.navigate(window.location.href)`
call runs **on the browser**, where `window.location.href` is the page URL.

### 6.4 Where the toggle lives

In `resources/views/admin/layouts/admin_partials/header.blade.php`:

```blade
<livewire:language-switcher />
```

This renders a Bootstrap dropdown with the two locales; clicking either one
calls `wire:click="switch('km')"` and the page re-renders.

### 6.5 Listening for locale-changed (DataTables)

DataTables on the page must reload their AJAX data with the new locale's
strings (for enum labels, money formatting, etc.). `public/assets/backend/assets/js/app.js`
handles this:

```js
document.addEventListener('locale-changed', () => {
  document.querySelectorAll('table.dataTable').forEach((t) => {
    if (jQuery.fn.dataTable.isDataTable(t)) jQuery(t).DataTable().ajax.reload(null, false);
  });
});
```

---

## 7. Admin layout & UI conventions

### 7.1 Layout files

```
resources/views/admin/layouts/
├── admin_layout.blade.php         ← Master shell
└── admin_partials/
    ├── head.blade.php             ← CSS/meta
    ├── header.blade.php           ← Top bar (language, user dropdown)
    ├── left_sidebar.blade.php     ← Sidebar with RBAC filtering
    └── scripts.blade.php          ← JS includes (jQuery, DT, SA2, …)
```

### 7.2 Master layout (`admin_layout.blade.php`)

Skeleton:

```blade
@include('admin.layouts.admin_partials.head')

<body>
  <div class="wrapper">
    @include('admin.layouts.admin_partials.header')
    @include('admin.layouts.admin_partials.left_sidebar')

    <main class="page-content">
      {{-- Breadcrumb (auto-rendered from @section('breadcrumb')) --}}
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">@yield('breadcrumbTitle', __('messages.common.dashboard'))</div>
        <nav>...@yield('breadcrumb')...</nav>
        <div class="ms-auto">@yield('breadcrumbActions')</div>
      </div>

      @yield('content')
    </main>

    @include('admin.layouts.admin_partials.scripts')
  </div>
</body>
```

Each page sets:

- `@section('pageTitle', '...')` — browser tab title
- `@section('breadcrumb')` — custom breadcrumb trail
- `@section('breadcrumbActions')` — right-side buttons (e.g. "+ Create")
- `@section('content')` — main body

### 7.3 Sidebar

`left_sidebar.blade.php` declares groups in a PHP array:

```php
$sections = [
    'main' => [
        ['admin.dashboard', 'common.dashboard', 'bi-house-door', null],
    ],
    'organization' => [
        ['admin.companies.index', 'modules.companies', 'bi-building', 'companies.view'],
        ['admin.branches.index',  'modules.branches',  'bi-shop',     'branches.view'],
        // ...
    ],
    // 11 more sections, 48 items total
];
```

Items are **automatically hidden** when the current user lacks the required
permission (4th tuple element). The whole section is also hidden if all its
items are filtered out, so an empty group never shows.

### 7.4 Sidebar scroll behavior

For users with deep menus (50+ items), the sidebar scrolls internally
without scrolling the page. Implemented in `style.css`:

```css
.sidebar-wrapper { display: flex; flex-direction: column; height: 100vh; }
.sidebar-header  { flex: 0 0 auto; }                  /* logo stays pinned */
.metismenu       { flex: 1 1 auto; min-height: 0;
                   overflow-y: auto; overscroll-behavior: contain; }
```

`overscroll-behavior: contain` stops the wheel from "spilling" into the page
body when the sidebar hits its end.

### 7.5 Header (top bar)

`header.blade.php` contains:

1. Sidebar toggle button (for mobile)
2. Search bar (placeholder for future global search)
3. `<livewire:language-switcher />`
4. User profile dropdown — name, role, divider, Profile / Dashboard / Logout

Both dropdowns are styled in `style.css` under the `.top-header` scope:

```css
.top-header .dropdown-menu { border-radius: .75rem; box-shadow: 0 8px 24px rgba(0,0,0,.08); }
.top-header .dropdown-toggle::after { transition: transform .2s ease; }
.top-header .dropdown.show .dropdown-toggle::after { transform: rotate(180deg); }
.top-header .lang-code { background: var(--re-primary); color: #fff; padding: 2px 6px; border-radius: 4px; }
```

### 7.6 Breadcrumb

Every CRUD page declares its breadcrumb:

```blade
@section('breadcrumbTitle', __('messages.modules.customers'))
@section('breadcrumb')
  <li class="breadcrumb-item active">{{ __('messages.modules.customers') }}</li>
@endsection
```

The home icon links to `admin.dashboard` and is always rendered.

---

## 8. ModuleManifest: the CRUD factory

**`app/Support/ModuleManifest.php`** is the single source of truth for every
CRUD module in the system. It declares:

- The DB table
- The Eloquent model class
- The route prefix
- The permission prefix
- The translation `label_key`
- Sidebar group + icon
- Soft-delete flag
- All form fields (name, type, label, options)
- Searchable columns (for Yajra)
- Belongs-to relations (for eager loading + display)
- Has-many relations

Everything else — controllers, routes, permission seeds, sidebar item
visibility, DataTable columns, create/edit/show views — is **derived** from
this manifest.

### 8.1 Anatomy of a module entry

```php
'customers' => [
    'table'          => 'customers',
    'model'          => 'Customer',
    'route'          => 'customers',
    'permission_key' => 'customers',
    'label_key'      => 'modules.customers',
    'group'          => 'crm',
    'icon'           => 'bi-person-vcard',
    'soft_deletes'   => true,

    'fields' => [
        ['company_id',    'foreign', 'common.company', ['model' => 'Company', 'display' => 'name']],
        ['branch_id',     'foreign', 'common.branch',  ['model' => 'Branch',  'display' => 'name']],
        ['customer_code', 'string',  'fields.customers.customer_code', ['unique' => true, 'auto' => true]],
        ['name',          'string',  'common.name',    ['required' => true]],
        ['phone',         'string',  'common.phone'],
        ['email',         'string',  'common.email'],
        ['address',       'text',    'common.address'],
        ['national_id',   'string',  'fields.customers.national_id'],
        ['date_of_birth', 'date',    'fields.customers.date_of_birth'],
        ['gender',        'enum',    'common.gender',  ['options' => ['male','female','other']]],
        ['occupation',    'string',  'common.occupation'],
        ['status',        'enum',    'common.status',  ['options' => ['active','inactive'], 'default' => 'active']],
        ['created_by',    'foreign', 'common.created_by', ['model' => 'User', 'display' => 'name', 'read_only' => true, 'auto_user' => true]],
    ],

    'searchable' => ['name', 'customer_code', 'phone', 'email', 'national_id'],
    'belongs_to' => ['company' => 'Company', 'branch' => 'Branch', 'creator' => ['User', 'created_by']],
],
```

### 8.2 Field tuple format

Each `fields` entry is a 3-or-4 tuple:

```php
[ <name>, <type>, <label_key>, <options=[]> ]
```

**Types**:

| Type | Form input | DB column |
|---|---|---|
| `string` | `<input type="text">` | VARCHAR(255) |
| `text` | `<textarea>` | TEXT |
| `integer` | `<input type="number" step="1">` | INTEGER |
| `decimal` | `<input type="number" step="0.01">` | DECIMAL(15,2) |
| `date` | Flatpickr (date only) | DATE |
| `datetime` | Flatpickr (date + time) | TIMESTAMP |
| `bool` | `<input type="checkbox">` | BOOLEAN |
| `enum` | Tom Select with `options` | ENUM |
| `foreign` | Tom Select fed by FK relation | FOREIGN KEY |
| `password` | `<input type="password">` (hashed on save) | VARCHAR(255) |
| `json` | Multi-select / pivot | JSON |

**Options** (all keyed; all optional unless noted):

| Key | Effect |
|---|---|
| `required` | Adds `required` validation rule + asterisk in form |
| `required_on_create` | Required only on insert (e.g. password) |
| `unique` | Adds `unique:<table>,<col>,<ignore_id>` validation |
| `options` (`enum`) | Allowed values, e.g. `['active','inactive']` |
| `default` (`enum`) | Default value for new records |
| `model` (`foreign`) | Related model class basename, e.g. `'Customer'` |
| `display` (`foreign`) | Column shown in dropdown / table (default `'name'`) |
| `multi_select_model` (`json`) | Renders multi-select that syncs a pivot relation |
| `auto` | Generates code via `CodeSequenceService` on save |
| `auto_user` | Sets value to current user ID on insert |
| `read_only` | Field is system-managed; hidden from create/edit forms |

### 8.3 How rendering works

```
ModuleManifest::all()['customers']
        │
        ├─▶ BaseCrudController::index()      → admin.crud.index
        ├─▶ BaseCrudController::datatable()  → Yajra JSON
        ├─▶ BaseCrudController::create()     → admin.crud.create (uses _form)
        ├─▶ BaseCrudController::store()      → validate + fill + flash + redirect
        ├─▶ BaseCrudController::edit()       → admin.crud.edit (uses _form)
        ├─▶ BaseCrudController::update()     → validate + fill + flash + redirect
        ├─▶ BaseCrudController::show()       → admin.crud.show
        └─▶ BaseCrudController::destroy()    → soft delete + flash + redirect
```

Each `<Module>Controller` typically only sets `$moduleKey`:

```php
class CustomersController extends BaseCrudController
{
    protected string $moduleKey = 'customers';
}
```

…unless you need to override behavior, in which case you override the
specific method (`store`, `update`, etc.).

### 8.4 Adding a new module

1. Add the table to the migration (or a new migration file).
2. Create the Eloquent model in `app/Models/`.
3. Add a `<Module>Controller extends BaseCrudController` that sets `$moduleKey`.
4. Add the module entry to `ModuleManifest::all()`.
5. Run the generator:

   ```bash
   php artisan erp:generate
   ```

   This regenerates `routes/admin_modules.php` from the manifest.

6. Add a sidebar item to `left_sidebar.blade.php`.
7. Re-seed permissions:

   ```bash
   php artisan db:seed --class=DatabaseSeeder
   ```

That's it — index, datatable, create, edit, show and destroy all work
without writing any view code.

---

## 9. The 48 modules in detail

> **Tip:** every module follows the same URL convention:
> `/admin/<route>` (index)
> `/admin/<route>/datatable` (Yajra JSON)
> `/admin/<route>/create` (create form)
> `/admin/<route>` POST (store)
> `/admin/<route>/{id}` (show)
> `/admin/<route>/{id}/edit` (edit form)
> `/admin/<route>/{id}` PUT/PATCH (update)
> `/admin/<route>/{id}` DELETE (destroy)
>
> Permissions follow `<permission_key>.{view,create,edit,delete}`.

### 9.1 Organization

| Module | Route | Permission | Description |
|---|---|---|---|
| **Companies** | `/admin/companies` | `companies.*` | Top-level tenants of the system. Each user belongs to exactly one company. |
| **Branches** | `/admin/branches` | `branches.*` | A company has many branches (e.g. Phnom Penh HQ, Siem Reap office). Used for data scoping. |
| **Users** | `/admin/users` | `users.*` | Staff with login access. Supports avatar, position, staff code, multi-role assignment. |
| **Roles** | `/admin/roles` | `roles.*` | Manually-implemented RBAC roles. Has a many-to-many with permissions. |
| **Permissions** | `/admin/permissions` | `permissions.*` | Read-only registry of all `<module>.<action>` permissions (192 total). |

### 9.2 CRM

| Module | Auto code | Description |
|---|---|---|
| **Customers** | `CUS-XXXXXX` | Buyers / sellers / tenants. Includes KYC fields (national ID, DOB, gender). |
| **Leads** | `LD-XXXXXX` | Pre-customer interest records. Has a status flow (new → contacted → qualified → won / lost). |
| **Lead Activities** | — | A timeline log of touches (call, email, meeting, note) attached to a lead. |

### 9.3 Property

| Module | Auto code | Description |
|---|---|---|
| **Projects** | `PRJ-XXXXXX` | Top-level developments (e.g. "Greenfield Borey"). |
| **Project Phases** | — | Sub-divisions of a project (Phase 1, Phase 2, …). |
| **Property Types** | — | Master list (Villa, Condo, Land Plot, Shophouse, …). |
| **Properties** | `PRP-XXXXXX` | Individual sellable / rentable units. References project, phase, type. Holds price, area, beds/baths, status. |
| **Property Images** | — | Image gallery attached to a property. |
| **Property Documents** | — | Legal docs (title, contract, KYC) attached to a property. |
| **Land Parcels** | `LP-XXXXXX` | Standalone land plots (no building). |

### 9.4 Sale

| Module | Auto code | Description |
|---|---|---|
| **Bookings** | `BK-XXXXXX` | A customer reserves a property with a deposit. Has approve/cancel workflow. |
| **Sale Contracts** | `SC-XXXXXX` | Formal sale agreement converted from a booking. Approval-required. |
| **Sale Contract Items** | — | Line items within a contract (multi-property contracts). |
| **Installment Schedules** | — | Auto-generated installment plan per contract. |

### 9.5 Invoicing & payments

| Module | Auto code | Description |
|---|---|---|
| **Payment Methods** | — | Master list (Cash, Bank Transfer, ABA, Wing, …). |
| **Invoices** | `INV-XXXXXX` | Bills sent to customers. Status: draft → sent → paid → overdue. |
| **Invoice Items** | — | Line items. |
| **Payments** | `PAY-XXXXXX` | Cash receipts. Posted to a payment method. |
| **Payment Allocations** | — | Maps a payment to one or more invoices (supports partial payment). |
| **Refunds** | `RFD-XXXXXX` | Reverses a payment (e.g. cancelled booking). |

### 9.6 Rental

| Module | Auto code | Description |
|---|---|---|
| **Rental Contracts** | `RC-XXXXXX` | Lease agreement with tenant, monthly rent, deposit. |
| **Rental Invoices** | — | Auto-generated monthly invoices for a rental contract. |

### 9.7 Sales team

| Module | Description |
|---|---|
| **Sales Teams** | A named team (e.g. "Phnom Penh Sales") with a leader. |
| **Sales Team Members** | Pivot of user ↔ team. |
| **Commissions** | `CM-XXXXXX` — auto-calculated commission per closed sale. |

### 9.8 Document management

| Module | Description |
|---|---|
| **Documents** | Generic file repository, polymorphic to any model. |

### 9.9 Finance

| Module | Description |
|---|---|
| **Chart of Accounts** | GL accounts (Assets, Liabilities, Equity, Revenue, Expense). |
| **Expense Categories** | Master list (Office, Travel, Marketing, …). |
| **Expenses** | `EXP-XXXXXX` — per-expense records with category, amount, attached receipt. |
| **Journal Entries** | `JE-XXXXXX` — accounting entries with debit/credit lines. |
| **Journal Entry Items** | Debit / credit lines. |

### 9.10 HR & assets

| Module | Description |
|---|---|
| **Departments** | Org chart. |
| **Employees** | `EMP-XXXXXX` — extended user profile (DOB, hire date, salary, dept). |
| **Asset Categories** | Master list (Furniture, IT Equipment, Vehicle, …). |
| **Assets** | `AST-XXXXXX` — individual assets with depreciation tracking. |

### 9.11 Approval workflow

| Module | Auto code | Description |
|---|---|---|
| **Approval Requests** | `AR-XXXXXX` | Generic approval request, polymorphic to any model (booking, contract, refund, …). |
| **Approval Steps** | — | Per-request step log (who approved/rejected, when, comment). |
| **Tasks** | — | Generic to-do items for users. |

### 9.12 System

| Module | Description |
|---|---|
| **Notifications** | In-app notifications. |
| **Audit Logs** | Read-only — automatic CRUD trail with old/new values JSON. |
| **Login Histories** | Read-only — IP, user agent, success/failure for every login attempt. |
| **Settings** | Generic key/value app config. |
| **Code Sequences** | `CodeSequenceService` state per (company, branch, module). |

---

## 10. Server-side DataTables

Every CRUD index uses **server-side** processing via Yajra DataTables —
records are paginated, sorted and filtered on the server, so the page is
fast even with millions of rows.

### 10.1 Endpoint

`BaseCrudController::datatable()` returns Yajra JSON:

```php
public function datatable(Request $request)
{
    $cfg = $this->config();
    $model = new ($this->modelClass());
    $table = $model->getTable();

    $columns = ['id'];
    foreach ($cfg['fields'] as $f) {
        [$name, $type] = $f;
        if (in_array($type, ['json', 'password'], true)) continue;
        $columns[] = $name;
    }
    if ($model->timestamps) $columns[] = 'created_at';

    $query = $this->newQuery()->select(array_map(fn ($c) => "$table.$c", $columns));
    $this->applyScope($query);

    if (! empty($cfg['belongs_to'] ?? [])) {
        $query->with(array_keys($cfg['belongs_to']));
    }

    return DataTables::eloquent($query)
        ->addColumn('action', fn ($row) => /* edit + delete + show buttons */)
        ->editColumn('foreign_id_col', fn ($row) => $row->relation?->name)
        ->editColumn('status', fn ($row) => '<span class="status-pill '.e($row->status).'">'.e($row->status).'</span>')
        ->rawColumns(['action','status'])
        ->toJson();
}
```

Key details:

- **FK columns** are eager-loaded (`->with(...)`) and rendered using the
  related model's `display` column.
- **Enum columns** are rendered as pills via `editColumn` + `rawColumns`.
- **Boolean columns** render `Yes` / `No` translated.
- **Decimal columns** use `number_format(..., 2)`.
- **Date / datetime columns** use Carbon `format()`.
- **Action column** is `rawColumns` and includes Edit + Delete + Show
  buttons gated by the user's permissions.

### 10.2 Client side

`public/assets/backend/assets/js/app.js` exposes `makeServerSideDataTable`:

```js
window.makeServerSideDataTable = function (selector, url, columns, extra = {}) {
  return $(selector).DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    autoWidth: false,
    ajax: { url, type: 'GET' },
    columns,
    order: extra.order || [[0, 'desc']],
    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
    pageLength: extra.pageLength || 10,
    language: { /* search, info, paginate (Bootstrap icons), etc. */ },
    drawCallback: function () {
      // DataTables replaces <tbody> on every draw — re-init flatpickr /
      // Tom Select for any new fields. Delete confirms use document-level
      // delegation so no re-binding is needed.
      initFieldEnhancements();
    },
  });
};
```

The `admin.crud.index.blade.php` view assembles the `columns` array from the
module's manifest and calls this factory on `DOMContentLoaded`.

### 10.3 Search / sort / filter

- **Global search** — uses the `searchable` column list from the manifest.
- **Per-column sort** — clicking the header re-runs the query with the right
  `ORDER BY`.
- **Pagination** — Bootstrap 5 pagination buttons with chevron icons,
  sticky at the bottom of the table.
- **Length menu** — 10 / 25 / 50 / 100 rows per page.

### 10.4 Adding a custom filter

Override the controller's `datatable()` method (or extend it):

```php
public function datatable(Request $request)
{
    // Add ?status=active to the URL to filter
    return parent::datatable(
        $request->merge(['_extra_filter_status' => $request->query('status')])
    );
}
```

(Or override the whole method to add `->whereDate('created_at', ...)` etc.)

---

## 11. UI components

### 11.1 SweetAlert2 — delete confirmation

Every delete form is marked with `data-confirm-delete`. The global JS
intercepts both `click` and `submit` events via **document-level event
delegation**:

```js
// app.js
function bindDeleteConfirmDelegation() {
  document.addEventListener('click', function (e) {
    const trigger = e.target.closest('[data-confirm-delete]');
    if (!trigger) return;
    if (trigger.tagName === 'BUTTON' && trigger.form && trigger.form.hasAttribute('data-confirm-delete')) return;
    e.preventDefault(); e.stopPropagation();
    runDeleteConfirm(trigger).then((r) => { if (r.isConfirmed) /* submit */; });
  }, true);

  document.addEventListener('submit', function (e) {
    const form = e.target.closest('form[data-confirm-delete]');
    if (!form) return;
    if (form.dataset.confirmed) { delete form.dataset.confirmed; return; }
    e.preventDefault(); e.stopPropagation();
    runDeleteConfirm(form).then((r) => { if (r.isConfirmed) submitConfirmedForm(form); });
  }, true);
}
```

**Why document-level?** Yajra server-side DataTables replaces the entire
`<tbody>` on every redraw (pagination, search, sort, ajax reload), so any
listener attached directly to a row's form is lost. Document delegation
survives arbitrary DOM replacement.

### 11.2 PHPFlasher — success toasts

Server-side, controllers inject `Flasher\Prime\FlasherInterface`:

```php
public function store(Request $request, FlasherInterface $flasher)
{
    // ... save ...
    $flasher->addSuccess(__('messages.common.created'));
    return redirect()->route('admin.'.$cfg['route'].'.index');
}
```

The flasher envelopes are transported to the next response via session
storage and rendered automatically using the SweetAlert2 driver
(`php-flasher/flasher-sweetalert-laravel`). The `inject_assets` middleware
that ships with the package handles the JS wiring — no manual
`@flasher_render` directive is required (and explicitly **removed** because
in flasher 2.6.x the directive silently drains envelopes without echoing).

### 11.3 Flatpickr — date / datetime fields

Auto-initialized for any input matching `.flatpickr-input`, `.datepicker`,
`.datetimepicker`:

```js
function initFieldEnhancements(scope = document) {
  if (window.flatpickr) {
    scope.querySelectorAll('.flatpickr-input, .datepicker').forEach((el) => {
      if (el._flatpickr) return;
      flatpickr(el, { dateFormat: 'Y-m-d', allowInput: true });
    });
    scope.querySelectorAll('.datetimepicker').forEach((el) => {
      if (el._flatpickr) return;
      flatpickr(el, { enableTime: true, dateFormat: 'Y-m-d H:i', time_24hr: true });
    });
  }
  // ... TomSelect ...
}
```

`initFieldEnhancements()` is called on:

- `DOMContentLoaded`
- `livewire:navigated` (after a Livewire SPA navigate)
- `livewire:morph.updated` (after a Livewire morph)
- `drawCallback` of every DataTable (new `<tbody>` rendered)

This guarantees Flatpickr is initialized on dynamically-injected fields.

### 11.4 Tom Select — searchable / multi-select

Auto-initialized for `select.tomselect`:

```js
scope.querySelectorAll('select.tomselect').forEach((el) => {
  if (el.tomselect) return;
  new TomSelect(el, {
    create: false,
    plugins: el.multiple ? ['remove_button'] : [],
    allowEmptyOption: true,
  });
});
```

Used for `foreign` and `enum` field types in `_form.blade.php`. Multi-select
mode is enabled by `json` type with `multi_select_model` option.

---

## 12. Advanced features

### 12.1 Code sequences (auto IDs)

`app/Services/CodeSequenceService.php` generates sequential, formatted codes
scoped to `(company_id, branch_id, module)`:

```php
$service->next('customers');  // → "CUS-000005"
$service->next('bookings');   // → "BK-000001"
```

The state lives in the `code_sequences` table. The prefix and padding can
be overridden per row:

```
+----+----+--------+-----------+--------+-------------+---------+
| id |co. | branch | module    | prefix | next_number | padding |
+----+----+--------+-----------+--------+-------------+---------+
|  1 |  1 |    1   | customers | CUS-   |     6       |    6    |
|  2 |  1 |    1   | bookings  | BK-    |     1       |    6    |
+----+----+--------+-----------+--------+-------------+---------+
```

Fields tagged with `'auto' => true` are auto-populated by
`BaseCrudController::fillRow()` if left blank:

```php
foreach ($cfg['fields'] as $f) {
    if (! ($opts['auto'] ?? false)) continue;
    if ($row->{$name}) continue;        // user supplied a value
    $row->{$name} = app(CodeSequenceService::class)->next($cfg['table']);
}
```

To prevent duplicates from manually-seeded sample rows, the seeder primes
`code_sequences` with the correct `next_number`:

```php
// DatabaseSeeder::primeCodeSequences()
['module' => 'customers', 'prefix' => 'CUS-', 'next' => 5]
```

### 12.2 Audit logging

CRUD operations are logged automatically by an observer (registered on each
model):

```php
class AuditableObserver
{
    public function created(Model $m)  { $this->log('create', $m, null,            $m->toArray()); }
    public function updated(Model $m)  { $this->log('update', $m, $m->getOriginal(), $m->getChanges()); }
    public function deleted(Model $m)  { $this->log('delete', $m, $m->toArray(),    null); }

    protected function log(string $action, Model $m, $old, $new)
    {
        AuditLog::create([
            'user_id'        => auth()->id(),
            'action'         => $action,
            'module'         => $m->getTable(),
            'auditable_type' => $m::class,
            'auditable_id'   => $m->getKey(),
            'old_values'     => $old,
            'new_values'     => $new,
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),
        ]);
    }
}
```

The `audit_logs` module is marked `'read_only' => true` in the manifest, so
no user can create / edit / delete audit log entries through the UI. The
show page pretty-prints the JSON blob.

### 12.3 Approval workflow

Workflow-driven records (bookings, sale contracts, refunds, …) use the
`approval_requests` and `approval_steps` tables:

```
Booking created (status=pending)
     │
     ▼
approval_requests (auditable=Booking, status=pending)
     │
     ▼
approval_steps (step=1, approver=user_5, status=pending)
     │  approver hits "Approve"
     ▼
approval_steps (step=1, status=approved, comment="OK")
     ↓
[next step or finalize]
     ↓
approval_requests.status = approved
     ↓
Booking.status = approved, approved_by = approver.id, approved_at = now()
```

Approval-related columns (`approved_by`, `approved_at`, `cancelled_by`,
`cancelled_at`) are marked `'read_only' => true` so they never appear on
create/edit forms — they're set by the workflow code, not by the user.

### 12.4 Multi-branch / multi-tenant

The `applyScope()` method in `BaseCrudController` automatically restricts a
non-super-admin user's queries to their own `company_id`. This means:

- A super-admin sees rows from all companies and branches.
- A branch-admin in company #1 sees only rows where `company_id = 1`.
- The same scoping is applied to DataTable AJAX, show pages and edit forms.

To extend with stricter branch scoping (block branch A from seeing branch B
within the same company), uncomment the branch-scope block in
`BaseCrudController::applyScope()`:

```php
if (in_array('branch_id', $fields, true) && $user->branch_id) {
    $query->where($table.'.branch_id', $user->branch_id);
}
```

---

## 13. Seeding & test data

`DatabaseSeeder` runs the following in order, in a single DB transaction:

```php
public function run(): void
{
    DB::transaction(function () {
        $this->seedPermissions();      // 192 permissions (48 modules × 4 actions)
        $this->seedRoles();            // 4 roles + role↔permission pivots
        $this->seedCompanyBranch();    // 1 company, 2 branches
        $this->seedUsers();            // 4 demo users with passwords
        $this->seedReferenceData();    // Property types, payment methods, etc.
        $this->seedSampleCustomers();  // 4 sample customers (CUS-000001..4)
        $this->seedSampleProperties(); // 6 sample properties
        $this->seedSettings();         // App-level settings
        $this->primeCodeSequences();   // Seed code_sequences for auto-codes
    });
}
```

### 13.1 Seeded users

| Username | Password | Role | Company | Branch |
|---|---|---|---|---|
| `superadmin` | `password` | `super_admin` | ABC | HQ |
| `admin` | `password` | `admin` | ABC | HQ |
| `manager` | `password` | `branch_manager` | ABC | HQ |
| `agent` | `password` | `sales_agent` | ABC | HQ |

### 13.2 Resetting the database

To start over with a clean DB:

```bash
php artisan migrate:fresh --seed
```

This drops everything, re-runs the migration, and re-seeds.

### 13.3 Code sequence priming

If you bulk-import sample records via SQL or factories, **always** update
the `code_sequences.next_number` afterwards or your next user-created record
will collide:

```sql
UPDATE code_sequences
SET next_number = (SELECT MAX(CAST(SUBSTR(customer_code, 5) AS INTEGER)) + 1 FROM customers)
WHERE module = 'customers';
```

---

## 14. Testing

### 14.1 Run the test suite

```bash
composer run test
# or:
php artisan test
```

The bundled tests live in `tests/Feature/` and cover:

- Login (success + failure paths)
- RBAC (super-admin bypass, permission gating, 403 on forbidden routes)
- Module index smoke (all 48 index pages return 200 for super-admin)
- DataTable AJAX endpoint smoke
- Read-only field guard (system fields hidden from create/edit forms)
- Translation key guard (no raw `fields.X.Y` placeholders leak through)

### 14.2 Writing a new test

```php
// tests/Feature/CustomerTest.php
use Tests\TestCase;
use App\Models\User;

class CustomerTest extends TestCase
{
    public function test_super_admin_can_create_customer(): void
    {
        $admin = User::where('username', 'superadmin')->first();

        $this->actingAs($admin)
            ->post(route('admin.customers.store'), [
                'name'    => 'Test Customer',
                'phone'   => '012345678',
                'status'  => 'active',
            ])
            ->assertRedirect(route('admin.customers.index'));

        $this->assertDatabaseHas('customers', ['name' => 'Test Customer']);
    }
}
```

### 14.3 Lint (Pint)

The repo ships Laravel Pint. To check code style:

```bash
vendor/bin/pint --test       # check, exit non-zero on diff
vendor/bin/pint              # auto-fix
```

---

## 15. Development workflow

### 15.1 Day-to-day commands

| Goal | Command |
|---|---|
| Run dev server | `php artisan serve` |
| Full dev stack (server + queue + logs + vite) | `composer run dev` |
| Tail logs only | `php artisan pail` |
| Re-run migration + seed | `php artisan migrate:fresh --seed` |
| Clear caches | `php artisan optimize:clear` |
| Generate IDE helper (if installed) | `php artisan ide-helper:generate` |
| Build production assets | `npm run build` |
| Hot-reload frontend (Vite HMR) | `npm run dev` |

### 15.2 Hot reload in development

`composer run dev` starts Vite in dev mode. Any change to
`resources/css/*` or `resources/js/*` triggers an HMR update without a full
reload. Blade file changes require a browser refresh.

### 15.3 Code generator

`php artisan erp:generate` regenerates `routes/admin_modules.php` from the
manifest. Run this after adding or removing a module entry from
`ModuleManifest::all()`.

### 15.4 Common patterns

| I want to… | …do this |
|---|---|
| Add a field to an existing module | Migration → model `$fillable` → manifest `fields[]` |
| Change a label for one column | Set `'label_key' => 'fields.<table>.<col>'` and add the key to both `lang/en/messages.php` and `lang/km/messages.php` |
| Hide a field from create/edit | Add `'read_only' => true` to its options |
| Mark a field as auto-coded | Add `'auto' => true` to its options + ensure prefix is in `CodeSequenceService::derivePrefix()` |
| Restrict a route to a permission | Wrap in `Route::middleware('permission:<key>.<action>')` |
| Add a sidebar item | Edit `resources/views/admin/layouts/admin_partials/left_sidebar.blade.php`, `$sections` array |

---

## 16. Deployment to production

> **Target**: a Linux VPS / cloud host with PHP 8.2+, Composer, Node 20+,
> Nginx (or Apache), and either MySQL 8 / PostgreSQL 14+ (recommended) or
> SQLite (fine for single-tenant low-traffic deployments).

### 16.1 Recommended `.env` (production)

```ini
APP_NAME="Real Estate ERP"
APP_ENV=production
APP_KEY=base64:GENERATE_WITH_artisan_key:generate
APP_DEBUG=false
APP_URL=https://erp.example.com

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_AVAILABLE_LOCALES=en,km

LOG_CHANNEL=daily
LOG_LEVEL=warning

DB_CONNECTION=mysql                # or pgsql / sqlite
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=real_estate_erp
DB_USERNAME=erp
DB_PASSWORD=*****

SESSION_DRIVER=database
SESSION_LIFETIME=120

QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@example.com
```

### 16.2 First-time deploy

```bash
# 1. Pull code
git clone https://github.com/imsamnang/real-estate-erp.git
cd real-estate-erp

# 2. Install dependencies (no dev deps in production)
composer install --no-dev --optimize-autoloader
npm ci
npm run build

# 3. Environment
cp .env.example .env
# … edit .env to point at your prod DB, set APP_URL, etc.
php artisan key:generate

# 4. Database
php artisan migrate --force
php artisan db:seed --force      # first time only; creates seeded data

# 5. Storage symlink (for uploaded files)
php artisan storage:link

# 6. Cache for speed
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 7. Permissions (Linux)
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### 16.3 Nginx config (excerpt)

```nginx
server {
    listen 80;
    server_name erp.example.com;
    root /var/www/real-estate-erp/public;

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* { deny all; }
}
```

For HTTPS, use Certbot (`certbot --nginx -d erp.example.com`).

### 16.4 Queue worker

The queue handles background jobs (sending emails, generating PDFs, etc.).
Run it under `supervisor`:

```ini
; /etc/supervisor/conf.d/erp-worker.conf
[program:erp-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/real-estate-erp/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/erp-worker.log
```

### 16.5 Scheduler

If you add scheduled tasks (e.g. monthly rental invoice generation), add
this cron line:

```cron
* * * * * cd /var/www/real-estate-erp && php artisan schedule:run >> /dev/null 2>&1
```

### 16.6 Upgrades / re-deploys

```bash
cd /var/www/real-estate-erp
git pull
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache && php artisan route:cache && php artisan view:cache
sudo supervisorctl restart erp-worker:*
```

### 16.7 Backups

At minimum:

- Database — `mysqldump` (or `pg_dump`) nightly, retained 30 days
- Uploaded files — `storage/app/public/` synced off-server (e.g. to S3)

---

## 17. Troubleshooting

| Symptom | Cause | Fix |
|---|---|---|
| `SQLSTATE[HY000] [14] unable to open database file` | SQLite file missing | `touch database/database.sqlite` |
| Blank login page | `APP_KEY` not set | `php artisan key:generate` |
| 419 page expired on POST | CSRF token missing | Ensure `@csrf` is in every form; clear browser cookies |
| Language toggle doesn't switch | `session` driver not writable | `chmod -R 775 storage`; clear browser cookies |
| Language toggle works once then "stuck" | Bootstrap dropdown not re-bound after `livewire:navigated` | Refresh page (F5). A follow-up will re-bind dropdowns after SPA navigate. |
| DataTable shows "No data" but DB has rows | `applyScope()` filtered them out — user has no `company_id` matching | Set user's `company_id` to match the records |
| Delete confirm modal doesn't appear | JS error before `app.js` loaded | Check browser console; verify `@vite` directive renders in `head.blade.php` |
| Flasher toast missing after success | `@flasher_render` accidentally re-added | Remove `@flasher_render` from layout; inject_assets middleware handles it in 2.6.x |
| `ambiguous column id` when editing user | Pivot table pluck not qualified | Use `$rel->getRelated()->getTable().'.id'` (see `_form.blade.php`) |
| `php artisan migrate:fresh` errors on FK | Migration order | Re-check the single migration file; FKs must reference tables already declared above |
| `npm run build` fails with `vite: command not found` | `node_modules` missing | `npm install` |
| All routes 404 | `APP_URL` mismatch or `route:cache` stale | `php artisan route:clear` |
| Permission denied on `storage/logs` | wrong owner | `chown -R www-data:www-data storage bootstrap/cache` |

---

## 18. Appendix

### 18.1 Composer scripts

| Script | What it does |
|---|---|
| `composer run setup` | Full local setup: install deps, copy `.env`, key:generate, migrate, npm install, npm build |
| `composer run dev` | Run server + queue + logs + vite concurrently |
| `composer run test` | `config:clear` then `php artisan test` |

### 18.2 Artisan commands cheat sheet

```bash
# General
php artisan serve --host=127.0.0.1 --port=8000
php artisan optimize:clear

# Database
php artisan migrate
php artisan migrate:fresh --seed
php artisan db:seed --class=DatabaseSeeder

# Code generation
php artisan erp:generate          # regen routes/admin_modules.php

# Auth helpers
php artisan tinker
> User::where('username','superadmin')->first()->hasPermission('customers.view')

# Caching (production)
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### 18.3 Key files reference

| File | Purpose |
|---|---|
| `app/Support/ModuleManifest.php` | Single source of truth for every CRUD module |
| `app/Http/Controllers/Admin/BaseCrudController.php` | Base CRUD logic (index, datatable, create, store, edit, update, show, destroy) |
| `app/Services/CodeSequenceService.php` | Auto-code generation (CUS-, BK-, INV-, …) |
| `app/Concerns/HasRoles.php` | RBAC trait on User model |
| `app/Livewire/LanguageSwitcher.php` | KH/EN switcher with SPA navigate |
| `app/Http/Middleware/SetLocale.php` | Reads `session('locale')` per request |
| `app/Http/Middleware/EnsurePermission.php` | `permission:<key>` middleware |
| `routes/web.php` | Top-level routes (admin prefix, login, language) |
| `routes/admin_modules.php` | Auto-generated CRUD routes (48 modules) |
| `database/migrations/2026_05_14_000000_create_real_estate_erp_management_system_tables.php` | All 50 tables |
| `database/seeders/DatabaseSeeder.php` | Permissions, roles, users, sample data |
| `resources/views/admin/layouts/admin_layout.blade.php` | Master layout |
| `resources/views/admin/layouts/admin_partials/left_sidebar.blade.php` | RBAC-filtered sidebar |
| `resources/views/admin/crud/_form.blade.php` | Shared create/edit form |
| `resources/views/admin/crud/index.blade.php` | Shared DataTable index |
| `resources/views/livewire/language-switcher.blade.php` | Language toggle markup |
| `public/assets/backend/assets/js/app.js` | Global JS (CSRF, delete confirm, flatpickr, TomSelect, DataTable factory) |
| `public/assets/backend/assets/css/style.css` | Theme + dropdown polish + sidebar scroll |
| `lang/en/messages.php` | English translations |
| `lang/km/messages.php` | Khmer translations |

### 18.4 Useful Tinker snippets

```bash
php artisan tinker

# Check a user's permissions
> User::where('username','agent')->first()->permissionsCollection()->pluck('name')

# Reset a password
> $u = User::where('username','superadmin')->first(); $u->password = bcrypt('newpass'); $u->save();

# Trigger an auto-code
> app(\App\Services\CodeSequenceService::class)->next('customers')

# Reset code sequence for one module
> DB::table('code_sequences')->where('module','customers')->update(['next_number' => 1]);
```

### 18.5 References

- Laravel 12 docs — https://laravel.com/docs/12.x
- Livewire 4 — https://livewire.laravel.com/docs
- Yajra DataTables — https://yajrabox.com/docs/laravel-datatables
- PHPFlasher — https://php-flasher.io/
- SweetAlert2 — https://sweetalert2.github.io/
- Flatpickr — https://flatpickr.js.org/
- Tom Select — https://tom-select.js.org/
- Bootstrap 5 — https://getbootstrap.com/docs/5.3/

---

> **Maintainers:** keep this guide in sync with any architectural change.
> If you add a new module, update **§9 The 48 modules in detail**.
> If you add a new top-level convention (a new middleware, a new field
> option, a new UI component), document it here so the next developer
> doesn't have to read all 48 controllers to figure it out.
