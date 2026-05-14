<?php

/**
 * Auto-generated admin routes — DO NOT EDIT BY HAND.
 * Re-run `php artisan erp:generate` to regenerate.
 */

use App\Http\Controllers\Admin\ApprovalRequestsController;
use App\Http\Controllers\Admin\ApprovalStepsController;
use App\Http\Controllers\Admin\AssetCategoriesController;
use App\Http\Controllers\Admin\AssetsController;
use App\Http\Controllers\Admin\AuditLogsController;
use App\Http\Controllers\Admin\BookingsController;
use App\Http\Controllers\Admin\BranchesController;
use App\Http\Controllers\Admin\ChartOfAccountsController;
use App\Http\Controllers\Admin\CodeSequencesController;
use App\Http\Controllers\Admin\CommissionsController;
use App\Http\Controllers\Admin\CompaniesController;
use App\Http\Controllers\Admin\CustomersController;
use App\Http\Controllers\Admin\DepartmentsController;
use App\Http\Controllers\Admin\DocumentsController;
use App\Http\Controllers\Admin\EmployeesController;
use App\Http\Controllers\Admin\ExpenseCategoriesController;
use App\Http\Controllers\Admin\ExpensesController;
use App\Http\Controllers\Admin\InstallmentSchedulesController;
use App\Http\Controllers\Admin\InvoiceItemsController;
use App\Http\Controllers\Admin\InvoicesController;
use App\Http\Controllers\Admin\JournalEntriesController;
use App\Http\Controllers\Admin\JournalEntryItemsController;
use App\Http\Controllers\Admin\LandParcelsController;
use App\Http\Controllers\Admin\LeadActivitiesController;
use App\Http\Controllers\Admin\LeadsController;
use App\Http\Controllers\Admin\LoginHistoriesController;
use App\Http\Controllers\Admin\NotificationsController;
use App\Http\Controllers\Admin\PaymentAllocationsController;
use App\Http\Controllers\Admin\PaymentMethodsController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\ProjectPhasesController;
use App\Http\Controllers\Admin\ProjectsController;
use App\Http\Controllers\Admin\PropertiesController;
use App\Http\Controllers\Admin\PropertyDocumentsController;
use App\Http\Controllers\Admin\PropertyImagesController;
use App\Http\Controllers\Admin\PropertyTypesController;
use App\Http\Controllers\Admin\RefundsController;
use App\Http\Controllers\Admin\RentalContractsController;
use App\Http\Controllers\Admin\RentalInvoicesController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\SaleContractItemsController;
use App\Http\Controllers\Admin\SaleContractsController;
use App\Http\Controllers\Admin\SalesTeamMembersController;
use App\Http\Controllers\Admin\SalesTeamsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TasksController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Route;

// === companies ===
Route::middleware('permission:companies.view')->group(function () {
    Route::get('companies', [CompaniesController::class, 'index'])->name('companies.index');
    Route::get('companies/datatable', [CompaniesController::class, 'datatable'])->name('companies.datatable');
    Route::get('companies/{id}', [CompaniesController::class, 'show'])->whereNumber('id')->name('companies.show');
});
Route::middleware('permission:companies.create')->group(function () {
    Route::get('companies/create', [CompaniesController::class, 'create'])->name('companies.create');
    Route::post('companies', [CompaniesController::class, 'store'])->name('companies.store');
});
Route::middleware('permission:companies.edit')->group(function () {
    Route::get('companies/{id}/edit', [CompaniesController::class, 'edit'])->whereNumber('id')->name('companies.edit');
    Route::put('companies/{id}', [CompaniesController::class, 'update'])->whereNumber('id')->name('companies.update');
});
Route::middleware('permission:companies.delete')->group(function () {
    Route::delete('companies/{id}', [CompaniesController::class, 'destroy'])->whereNumber('id')->name('companies.destroy');
});

// === branches ===
Route::middleware('permission:branches.view')->group(function () {
    Route::get('branches', [BranchesController::class, 'index'])->name('branches.index');
    Route::get('branches/datatable', [BranchesController::class, 'datatable'])->name('branches.datatable');
    Route::get('branches/{id}', [BranchesController::class, 'show'])->whereNumber('id')->name('branches.show');
});
Route::middleware('permission:branches.create')->group(function () {
    Route::get('branches/create', [BranchesController::class, 'create'])->name('branches.create');
    Route::post('branches', [BranchesController::class, 'store'])->name('branches.store');
});
Route::middleware('permission:branches.edit')->group(function () {
    Route::get('branches/{id}/edit', [BranchesController::class, 'edit'])->whereNumber('id')->name('branches.edit');
    Route::put('branches/{id}', [BranchesController::class, 'update'])->whereNumber('id')->name('branches.update');
});
Route::middleware('permission:branches.delete')->group(function () {
    Route::delete('branches/{id}', [BranchesController::class, 'destroy'])->whereNumber('id')->name('branches.destroy');
});

// === users ===
Route::middleware('permission:users.view')->group(function () {
    Route::get('users', [UsersController::class, 'index'])->name('users.index');
    Route::get('users/datatable', [UsersController::class, 'datatable'])->name('users.datatable');
    Route::get('users/{id}', [UsersController::class, 'show'])->whereNumber('id')->name('users.show');
});
Route::middleware('permission:users.create')->group(function () {
    Route::get('users/create', [UsersController::class, 'create'])->name('users.create');
    Route::post('users', [UsersController::class, 'store'])->name('users.store');
});
Route::middleware('permission:users.edit')->group(function () {
    Route::get('users/{id}/edit', [UsersController::class, 'edit'])->whereNumber('id')->name('users.edit');
    Route::put('users/{id}', [UsersController::class, 'update'])->whereNumber('id')->name('users.update');
});
Route::middleware('permission:users.delete')->group(function () {
    Route::delete('users/{id}', [UsersController::class, 'destroy'])->whereNumber('id')->name('users.destroy');
});

// === roles ===
Route::middleware('permission:roles.view')->group(function () {
    Route::get('roles', [RolesController::class, 'index'])->name('roles.index');
    Route::get('roles/datatable', [RolesController::class, 'datatable'])->name('roles.datatable');
    Route::get('roles/{id}', [RolesController::class, 'show'])->whereNumber('id')->name('roles.show');
});
Route::middleware('permission:roles.create')->group(function () {
    Route::get('roles/create', [RolesController::class, 'create'])->name('roles.create');
    Route::post('roles', [RolesController::class, 'store'])->name('roles.store');
});
Route::middleware('permission:roles.edit')->group(function () {
    Route::get('roles/{id}/edit', [RolesController::class, 'edit'])->whereNumber('id')->name('roles.edit');
    Route::put('roles/{id}', [RolesController::class, 'update'])->whereNumber('id')->name('roles.update');
});
Route::middleware('permission:roles.delete')->group(function () {
    Route::delete('roles/{id}', [RolesController::class, 'destroy'])->whereNumber('id')->name('roles.destroy');
});

// === permissions ===
Route::middleware('permission:permissions.view')->group(function () {
    Route::get('permissions', [PermissionsController::class, 'index'])->name('permissions.index');
    Route::get('permissions/datatable', [PermissionsController::class, 'datatable'])->name('permissions.datatable');
    Route::get('permissions/{id}', [PermissionsController::class, 'show'])->whereNumber('id')->name('permissions.show');
});
Route::middleware('permission:permissions.create')->group(function () {
    Route::get('permissions/create', [PermissionsController::class, 'create'])->name('permissions.create');
    Route::post('permissions', [PermissionsController::class, 'store'])->name('permissions.store');
});
Route::middleware('permission:permissions.edit')->group(function () {
    Route::get('permissions/{id}/edit', [PermissionsController::class, 'edit'])->whereNumber('id')->name('permissions.edit');
    Route::put('permissions/{id}', [PermissionsController::class, 'update'])->whereNumber('id')->name('permissions.update');
});
Route::middleware('permission:permissions.delete')->group(function () {
    Route::delete('permissions/{id}', [PermissionsController::class, 'destroy'])->whereNumber('id')->name('permissions.destroy');
});

// === customers ===
Route::middleware('permission:customers.view')->group(function () {
    Route::get('customers', [CustomersController::class, 'index'])->name('customers.index');
    Route::get('customers/datatable', [CustomersController::class, 'datatable'])->name('customers.datatable');
    Route::get('customers/{id}', [CustomersController::class, 'show'])->whereNumber('id')->name('customers.show');
});
Route::middleware('permission:customers.create')->group(function () {
    Route::get('customers/create', [CustomersController::class, 'create'])->name('customers.create');
    Route::post('customers', [CustomersController::class, 'store'])->name('customers.store');
});
Route::middleware('permission:customers.edit')->group(function () {
    Route::get('customers/{id}/edit', [CustomersController::class, 'edit'])->whereNumber('id')->name('customers.edit');
    Route::put('customers/{id}', [CustomersController::class, 'update'])->whereNumber('id')->name('customers.update');
});
Route::middleware('permission:customers.delete')->group(function () {
    Route::delete('customers/{id}', [CustomersController::class, 'destroy'])->whereNumber('id')->name('customers.destroy');
});

// === leads ===
Route::middleware('permission:leads.view')->group(function () {
    Route::get('leads', [LeadsController::class, 'index'])->name('leads.index');
    Route::get('leads/datatable', [LeadsController::class, 'datatable'])->name('leads.datatable');
    Route::get('leads/{id}', [LeadsController::class, 'show'])->whereNumber('id')->name('leads.show');
});
Route::middleware('permission:leads.create')->group(function () {
    Route::get('leads/create', [LeadsController::class, 'create'])->name('leads.create');
    Route::post('leads', [LeadsController::class, 'store'])->name('leads.store');
});
Route::middleware('permission:leads.edit')->group(function () {
    Route::get('leads/{id}/edit', [LeadsController::class, 'edit'])->whereNumber('id')->name('leads.edit');
    Route::put('leads/{id}', [LeadsController::class, 'update'])->whereNumber('id')->name('leads.update');
});
Route::middleware('permission:leads.delete')->group(function () {
    Route::delete('leads/{id}', [LeadsController::class, 'destroy'])->whereNumber('id')->name('leads.destroy');
});

// === lead_activities ===
Route::middleware('permission:lead_activities.view')->group(function () {
    Route::get('lead-activities', [LeadActivitiesController::class, 'index'])->name('lead-activities.index');
    Route::get('lead-activities/datatable', [LeadActivitiesController::class, 'datatable'])->name('lead-activities.datatable');
    Route::get('lead-activities/{id}', [LeadActivitiesController::class, 'show'])->whereNumber('id')->name('lead-activities.show');
});
Route::middleware('permission:lead_activities.create')->group(function () {
    Route::get('lead-activities/create', [LeadActivitiesController::class, 'create'])->name('lead-activities.create');
    Route::post('lead-activities', [LeadActivitiesController::class, 'store'])->name('lead-activities.store');
});
Route::middleware('permission:lead_activities.edit')->group(function () {
    Route::get('lead-activities/{id}/edit', [LeadActivitiesController::class, 'edit'])->whereNumber('id')->name('lead-activities.edit');
    Route::put('lead-activities/{id}', [LeadActivitiesController::class, 'update'])->whereNumber('id')->name('lead-activities.update');
});
Route::middleware('permission:lead_activities.delete')->group(function () {
    Route::delete('lead-activities/{id}', [LeadActivitiesController::class, 'destroy'])->whereNumber('id')->name('lead-activities.destroy');
});

// === projects ===
Route::middleware('permission:projects.view')->group(function () {
    Route::get('projects', [ProjectsController::class, 'index'])->name('projects.index');
    Route::get('projects/datatable', [ProjectsController::class, 'datatable'])->name('projects.datatable');
    Route::get('projects/{id}', [ProjectsController::class, 'show'])->whereNumber('id')->name('projects.show');
});
Route::middleware('permission:projects.create')->group(function () {
    Route::get('projects/create', [ProjectsController::class, 'create'])->name('projects.create');
    Route::post('projects', [ProjectsController::class, 'store'])->name('projects.store');
});
Route::middleware('permission:projects.edit')->group(function () {
    Route::get('projects/{id}/edit', [ProjectsController::class, 'edit'])->whereNumber('id')->name('projects.edit');
    Route::put('projects/{id}', [ProjectsController::class, 'update'])->whereNumber('id')->name('projects.update');
});
Route::middleware('permission:projects.delete')->group(function () {
    Route::delete('projects/{id}', [ProjectsController::class, 'destroy'])->whereNumber('id')->name('projects.destroy');
});

// === project_phases ===
Route::middleware('permission:project_phases.view')->group(function () {
    Route::get('project-phases', [ProjectPhasesController::class, 'index'])->name('project-phases.index');
    Route::get('project-phases/datatable', [ProjectPhasesController::class, 'datatable'])->name('project-phases.datatable');
    Route::get('project-phases/{id}', [ProjectPhasesController::class, 'show'])->whereNumber('id')->name('project-phases.show');
});
Route::middleware('permission:project_phases.create')->group(function () {
    Route::get('project-phases/create', [ProjectPhasesController::class, 'create'])->name('project-phases.create');
    Route::post('project-phases', [ProjectPhasesController::class, 'store'])->name('project-phases.store');
});
Route::middleware('permission:project_phases.edit')->group(function () {
    Route::get('project-phases/{id}/edit', [ProjectPhasesController::class, 'edit'])->whereNumber('id')->name('project-phases.edit');
    Route::put('project-phases/{id}', [ProjectPhasesController::class, 'update'])->whereNumber('id')->name('project-phases.update');
});
Route::middleware('permission:project_phases.delete')->group(function () {
    Route::delete('project-phases/{id}', [ProjectPhasesController::class, 'destroy'])->whereNumber('id')->name('project-phases.destroy');
});

// === property_types ===
Route::middleware('permission:property_types.view')->group(function () {
    Route::get('property-types', [PropertyTypesController::class, 'index'])->name('property-types.index');
    Route::get('property-types/datatable', [PropertyTypesController::class, 'datatable'])->name('property-types.datatable');
    Route::get('property-types/{id}', [PropertyTypesController::class, 'show'])->whereNumber('id')->name('property-types.show');
});
Route::middleware('permission:property_types.create')->group(function () {
    Route::get('property-types/create', [PropertyTypesController::class, 'create'])->name('property-types.create');
    Route::post('property-types', [PropertyTypesController::class, 'store'])->name('property-types.store');
});
Route::middleware('permission:property_types.edit')->group(function () {
    Route::get('property-types/{id}/edit', [PropertyTypesController::class, 'edit'])->whereNumber('id')->name('property-types.edit');
    Route::put('property-types/{id}', [PropertyTypesController::class, 'update'])->whereNumber('id')->name('property-types.update');
});
Route::middleware('permission:property_types.delete')->group(function () {
    Route::delete('property-types/{id}', [PropertyTypesController::class, 'destroy'])->whereNumber('id')->name('property-types.destroy');
});

// === properties ===
Route::middleware('permission:properties.view')->group(function () {
    Route::get('properties', [PropertiesController::class, 'index'])->name('properties.index');
    Route::get('properties/datatable', [PropertiesController::class, 'datatable'])->name('properties.datatable');
    Route::get('properties/{id}', [PropertiesController::class, 'show'])->whereNumber('id')->name('properties.show');
});
Route::middleware('permission:properties.create')->group(function () {
    Route::get('properties/create', [PropertiesController::class, 'create'])->name('properties.create');
    Route::post('properties', [PropertiesController::class, 'store'])->name('properties.store');
});
Route::middleware('permission:properties.edit')->group(function () {
    Route::get('properties/{id}/edit', [PropertiesController::class, 'edit'])->whereNumber('id')->name('properties.edit');
    Route::put('properties/{id}', [PropertiesController::class, 'update'])->whereNumber('id')->name('properties.update');
});
Route::middleware('permission:properties.delete')->group(function () {
    Route::delete('properties/{id}', [PropertiesController::class, 'destroy'])->whereNumber('id')->name('properties.destroy');
});

// === property_images ===
Route::middleware('permission:property_images.view')->group(function () {
    Route::get('property-images', [PropertyImagesController::class, 'index'])->name('property-images.index');
    Route::get('property-images/datatable', [PropertyImagesController::class, 'datatable'])->name('property-images.datatable');
    Route::get('property-images/{id}', [PropertyImagesController::class, 'show'])->whereNumber('id')->name('property-images.show');
});
Route::middleware('permission:property_images.create')->group(function () {
    Route::get('property-images/create', [PropertyImagesController::class, 'create'])->name('property-images.create');
    Route::post('property-images', [PropertyImagesController::class, 'store'])->name('property-images.store');
});
Route::middleware('permission:property_images.edit')->group(function () {
    Route::get('property-images/{id}/edit', [PropertyImagesController::class, 'edit'])->whereNumber('id')->name('property-images.edit');
    Route::put('property-images/{id}', [PropertyImagesController::class, 'update'])->whereNumber('id')->name('property-images.update');
});
Route::middleware('permission:property_images.delete')->group(function () {
    Route::delete('property-images/{id}', [PropertyImagesController::class, 'destroy'])->whereNumber('id')->name('property-images.destroy');
});

// === property_documents ===
Route::middleware('permission:property_documents.view')->group(function () {
    Route::get('property-documents', [PropertyDocumentsController::class, 'index'])->name('property-documents.index');
    Route::get('property-documents/datatable', [PropertyDocumentsController::class, 'datatable'])->name('property-documents.datatable');
    Route::get('property-documents/{id}', [PropertyDocumentsController::class, 'show'])->whereNumber('id')->name('property-documents.show');
});
Route::middleware('permission:property_documents.create')->group(function () {
    Route::get('property-documents/create', [PropertyDocumentsController::class, 'create'])->name('property-documents.create');
    Route::post('property-documents', [PropertyDocumentsController::class, 'store'])->name('property-documents.store');
});
Route::middleware('permission:property_documents.edit')->group(function () {
    Route::get('property-documents/{id}/edit', [PropertyDocumentsController::class, 'edit'])->whereNumber('id')->name('property-documents.edit');
    Route::put('property-documents/{id}', [PropertyDocumentsController::class, 'update'])->whereNumber('id')->name('property-documents.update');
});
Route::middleware('permission:property_documents.delete')->group(function () {
    Route::delete('property-documents/{id}', [PropertyDocumentsController::class, 'destroy'])->whereNumber('id')->name('property-documents.destroy');
});

// === land_parcels ===
Route::middleware('permission:land_parcels.view')->group(function () {
    Route::get('land-parcels', [LandParcelsController::class, 'index'])->name('land-parcels.index');
    Route::get('land-parcels/datatable', [LandParcelsController::class, 'datatable'])->name('land-parcels.datatable');
    Route::get('land-parcels/{id}', [LandParcelsController::class, 'show'])->whereNumber('id')->name('land-parcels.show');
});
Route::middleware('permission:land_parcels.create')->group(function () {
    Route::get('land-parcels/create', [LandParcelsController::class, 'create'])->name('land-parcels.create');
    Route::post('land-parcels', [LandParcelsController::class, 'store'])->name('land-parcels.store');
});
Route::middleware('permission:land_parcels.edit')->group(function () {
    Route::get('land-parcels/{id}/edit', [LandParcelsController::class, 'edit'])->whereNumber('id')->name('land-parcels.edit');
    Route::put('land-parcels/{id}', [LandParcelsController::class, 'update'])->whereNumber('id')->name('land-parcels.update');
});
Route::middleware('permission:land_parcels.delete')->group(function () {
    Route::delete('land-parcels/{id}', [LandParcelsController::class, 'destroy'])->whereNumber('id')->name('land-parcels.destroy');
});

// === bookings ===
Route::middleware('permission:bookings.view')->group(function () {
    Route::get('bookings', [BookingsController::class, 'index'])->name('bookings.index');
    Route::get('bookings/datatable', [BookingsController::class, 'datatable'])->name('bookings.datatable');
    Route::get('bookings/{id}', [BookingsController::class, 'show'])->whereNumber('id')->name('bookings.show');
});
Route::middleware('permission:bookings.create')->group(function () {
    Route::get('bookings/create', [BookingsController::class, 'create'])->name('bookings.create');
    Route::post('bookings', [BookingsController::class, 'store'])->name('bookings.store');
});
Route::middleware('permission:bookings.edit')->group(function () {
    Route::get('bookings/{id}/edit', [BookingsController::class, 'edit'])->whereNumber('id')->name('bookings.edit');
    Route::put('bookings/{id}', [BookingsController::class, 'update'])->whereNumber('id')->name('bookings.update');
});
Route::middleware('permission:bookings.delete')->group(function () {
    Route::delete('bookings/{id}', [BookingsController::class, 'destroy'])->whereNumber('id')->name('bookings.destroy');
});

// === sale_contracts ===
Route::middleware('permission:sale_contracts.view')->group(function () {
    Route::get('sale-contracts', [SaleContractsController::class, 'index'])->name('sale-contracts.index');
    Route::get('sale-contracts/datatable', [SaleContractsController::class, 'datatable'])->name('sale-contracts.datatable');
    Route::get('sale-contracts/{id}', [SaleContractsController::class, 'show'])->whereNumber('id')->name('sale-contracts.show');
});
Route::middleware('permission:sale_contracts.create')->group(function () {
    Route::get('sale-contracts/create', [SaleContractsController::class, 'create'])->name('sale-contracts.create');
    Route::post('sale-contracts', [SaleContractsController::class, 'store'])->name('sale-contracts.store');
});
Route::middleware('permission:sale_contracts.edit')->group(function () {
    Route::get('sale-contracts/{id}/edit', [SaleContractsController::class, 'edit'])->whereNumber('id')->name('sale-contracts.edit');
    Route::put('sale-contracts/{id}', [SaleContractsController::class, 'update'])->whereNumber('id')->name('sale-contracts.update');
});
Route::middleware('permission:sale_contracts.delete')->group(function () {
    Route::delete('sale-contracts/{id}', [SaleContractsController::class, 'destroy'])->whereNumber('id')->name('sale-contracts.destroy');
});

// === sale_contract_items ===
Route::middleware('permission:sale_contract_items.view')->group(function () {
    Route::get('sale-contract-items', [SaleContractItemsController::class, 'index'])->name('sale-contract-items.index');
    Route::get('sale-contract-items/datatable', [SaleContractItemsController::class, 'datatable'])->name('sale-contract-items.datatable');
    Route::get('sale-contract-items/{id}', [SaleContractItemsController::class, 'show'])->whereNumber('id')->name('sale-contract-items.show');
});
Route::middleware('permission:sale_contract_items.create')->group(function () {
    Route::get('sale-contract-items/create', [SaleContractItemsController::class, 'create'])->name('sale-contract-items.create');
    Route::post('sale-contract-items', [SaleContractItemsController::class, 'store'])->name('sale-contract-items.store');
});
Route::middleware('permission:sale_contract_items.edit')->group(function () {
    Route::get('sale-contract-items/{id}/edit', [SaleContractItemsController::class, 'edit'])->whereNumber('id')->name('sale-contract-items.edit');
    Route::put('sale-contract-items/{id}', [SaleContractItemsController::class, 'update'])->whereNumber('id')->name('sale-contract-items.update');
});
Route::middleware('permission:sale_contract_items.delete')->group(function () {
    Route::delete('sale-contract-items/{id}', [SaleContractItemsController::class, 'destroy'])->whereNumber('id')->name('sale-contract-items.destroy');
});

// === installment_schedules ===
Route::middleware('permission:installment_schedules.view')->group(function () {
    Route::get('installment-schedules', [InstallmentSchedulesController::class, 'index'])->name('installment-schedules.index');
    Route::get('installment-schedules/datatable', [InstallmentSchedulesController::class, 'datatable'])->name('installment-schedules.datatable');
    Route::get('installment-schedules/{id}', [InstallmentSchedulesController::class, 'show'])->whereNumber('id')->name('installment-schedules.show');
});
Route::middleware('permission:installment_schedules.create')->group(function () {
    Route::get('installment-schedules/create', [InstallmentSchedulesController::class, 'create'])->name('installment-schedules.create');
    Route::post('installment-schedules', [InstallmentSchedulesController::class, 'store'])->name('installment-schedules.store');
});
Route::middleware('permission:installment_schedules.edit')->group(function () {
    Route::get('installment-schedules/{id}/edit', [InstallmentSchedulesController::class, 'edit'])->whereNumber('id')->name('installment-schedules.edit');
    Route::put('installment-schedules/{id}', [InstallmentSchedulesController::class, 'update'])->whereNumber('id')->name('installment-schedules.update');
});
Route::middleware('permission:installment_schedules.delete')->group(function () {
    Route::delete('installment-schedules/{id}', [InstallmentSchedulesController::class, 'destroy'])->whereNumber('id')->name('installment-schedules.destroy');
});

// === payment_methods ===
Route::middleware('permission:payment_methods.view')->group(function () {
    Route::get('payment-methods', [PaymentMethodsController::class, 'index'])->name('payment-methods.index');
    Route::get('payment-methods/datatable', [PaymentMethodsController::class, 'datatable'])->name('payment-methods.datatable');
    Route::get('payment-methods/{id}', [PaymentMethodsController::class, 'show'])->whereNumber('id')->name('payment-methods.show');
});
Route::middleware('permission:payment_methods.create')->group(function () {
    Route::get('payment-methods/create', [PaymentMethodsController::class, 'create'])->name('payment-methods.create');
    Route::post('payment-methods', [PaymentMethodsController::class, 'store'])->name('payment-methods.store');
});
Route::middleware('permission:payment_methods.edit')->group(function () {
    Route::get('payment-methods/{id}/edit', [PaymentMethodsController::class, 'edit'])->whereNumber('id')->name('payment-methods.edit');
    Route::put('payment-methods/{id}', [PaymentMethodsController::class, 'update'])->whereNumber('id')->name('payment-methods.update');
});
Route::middleware('permission:payment_methods.delete')->group(function () {
    Route::delete('payment-methods/{id}', [PaymentMethodsController::class, 'destroy'])->whereNumber('id')->name('payment-methods.destroy');
});

// === invoices ===
Route::middleware('permission:invoices.view')->group(function () {
    Route::get('invoices', [InvoicesController::class, 'index'])->name('invoices.index');
    Route::get('invoices/datatable', [InvoicesController::class, 'datatable'])->name('invoices.datatable');
    Route::get('invoices/{id}', [InvoicesController::class, 'show'])->whereNumber('id')->name('invoices.show');
});
Route::middleware('permission:invoices.create')->group(function () {
    Route::get('invoices/create', [InvoicesController::class, 'create'])->name('invoices.create');
    Route::post('invoices', [InvoicesController::class, 'store'])->name('invoices.store');
});
Route::middleware('permission:invoices.edit')->group(function () {
    Route::get('invoices/{id}/edit', [InvoicesController::class, 'edit'])->whereNumber('id')->name('invoices.edit');
    Route::put('invoices/{id}', [InvoicesController::class, 'update'])->whereNumber('id')->name('invoices.update');
});
Route::middleware('permission:invoices.delete')->group(function () {
    Route::delete('invoices/{id}', [InvoicesController::class, 'destroy'])->whereNumber('id')->name('invoices.destroy');
});

// === invoice_items ===
Route::middleware('permission:invoice_items.view')->group(function () {
    Route::get('invoice-items', [InvoiceItemsController::class, 'index'])->name('invoice-items.index');
    Route::get('invoice-items/datatable', [InvoiceItemsController::class, 'datatable'])->name('invoice-items.datatable');
    Route::get('invoice-items/{id}', [InvoiceItemsController::class, 'show'])->whereNumber('id')->name('invoice-items.show');
});
Route::middleware('permission:invoice_items.create')->group(function () {
    Route::get('invoice-items/create', [InvoiceItemsController::class, 'create'])->name('invoice-items.create');
    Route::post('invoice-items', [InvoiceItemsController::class, 'store'])->name('invoice-items.store');
});
Route::middleware('permission:invoice_items.edit')->group(function () {
    Route::get('invoice-items/{id}/edit', [InvoiceItemsController::class, 'edit'])->whereNumber('id')->name('invoice-items.edit');
    Route::put('invoice-items/{id}', [InvoiceItemsController::class, 'update'])->whereNumber('id')->name('invoice-items.update');
});
Route::middleware('permission:invoice_items.delete')->group(function () {
    Route::delete('invoice-items/{id}', [InvoiceItemsController::class, 'destroy'])->whereNumber('id')->name('invoice-items.destroy');
});

// === payments ===
Route::middleware('permission:payments.view')->group(function () {
    Route::get('payments', [PaymentsController::class, 'index'])->name('payments.index');
    Route::get('payments/datatable', [PaymentsController::class, 'datatable'])->name('payments.datatable');
    Route::get('payments/{id}', [PaymentsController::class, 'show'])->whereNumber('id')->name('payments.show');
});
Route::middleware('permission:payments.create')->group(function () {
    Route::get('payments/create', [PaymentsController::class, 'create'])->name('payments.create');
    Route::post('payments', [PaymentsController::class, 'store'])->name('payments.store');
});
Route::middleware('permission:payments.edit')->group(function () {
    Route::get('payments/{id}/edit', [PaymentsController::class, 'edit'])->whereNumber('id')->name('payments.edit');
    Route::put('payments/{id}', [PaymentsController::class, 'update'])->whereNumber('id')->name('payments.update');
});
Route::middleware('permission:payments.delete')->group(function () {
    Route::delete('payments/{id}', [PaymentsController::class, 'destroy'])->whereNumber('id')->name('payments.destroy');
});

// === payment_allocations ===
Route::middleware('permission:payment_allocations.view')->group(function () {
    Route::get('payment-allocations', [PaymentAllocationsController::class, 'index'])->name('payment-allocations.index');
    Route::get('payment-allocations/datatable', [PaymentAllocationsController::class, 'datatable'])->name('payment-allocations.datatable');
    Route::get('payment-allocations/{id}', [PaymentAllocationsController::class, 'show'])->whereNumber('id')->name('payment-allocations.show');
});
Route::middleware('permission:payment_allocations.create')->group(function () {
    Route::get('payment-allocations/create', [PaymentAllocationsController::class, 'create'])->name('payment-allocations.create');
    Route::post('payment-allocations', [PaymentAllocationsController::class, 'store'])->name('payment-allocations.store');
});
Route::middleware('permission:payment_allocations.edit')->group(function () {
    Route::get('payment-allocations/{id}/edit', [PaymentAllocationsController::class, 'edit'])->whereNumber('id')->name('payment-allocations.edit');
    Route::put('payment-allocations/{id}', [PaymentAllocationsController::class, 'update'])->whereNumber('id')->name('payment-allocations.update');
});
Route::middleware('permission:payment_allocations.delete')->group(function () {
    Route::delete('payment-allocations/{id}', [PaymentAllocationsController::class, 'destroy'])->whereNumber('id')->name('payment-allocations.destroy');
});

// === refunds ===
Route::middleware('permission:refunds.view')->group(function () {
    Route::get('refunds', [RefundsController::class, 'index'])->name('refunds.index');
    Route::get('refunds/datatable', [RefundsController::class, 'datatable'])->name('refunds.datatable');
    Route::get('refunds/{id}', [RefundsController::class, 'show'])->whereNumber('id')->name('refunds.show');
});
Route::middleware('permission:refunds.create')->group(function () {
    Route::get('refunds/create', [RefundsController::class, 'create'])->name('refunds.create');
    Route::post('refunds', [RefundsController::class, 'store'])->name('refunds.store');
});
Route::middleware('permission:refunds.edit')->group(function () {
    Route::get('refunds/{id}/edit', [RefundsController::class, 'edit'])->whereNumber('id')->name('refunds.edit');
    Route::put('refunds/{id}', [RefundsController::class, 'update'])->whereNumber('id')->name('refunds.update');
});
Route::middleware('permission:refunds.delete')->group(function () {
    Route::delete('refunds/{id}', [RefundsController::class, 'destroy'])->whereNumber('id')->name('refunds.destroy');
});

// === rental_contracts ===
Route::middleware('permission:rental_contracts.view')->group(function () {
    Route::get('rental-contracts', [RentalContractsController::class, 'index'])->name('rental-contracts.index');
    Route::get('rental-contracts/datatable', [RentalContractsController::class, 'datatable'])->name('rental-contracts.datatable');
    Route::get('rental-contracts/{id}', [RentalContractsController::class, 'show'])->whereNumber('id')->name('rental-contracts.show');
});
Route::middleware('permission:rental_contracts.create')->group(function () {
    Route::get('rental-contracts/create', [RentalContractsController::class, 'create'])->name('rental-contracts.create');
    Route::post('rental-contracts', [RentalContractsController::class, 'store'])->name('rental-contracts.store');
});
Route::middleware('permission:rental_contracts.edit')->group(function () {
    Route::get('rental-contracts/{id}/edit', [RentalContractsController::class, 'edit'])->whereNumber('id')->name('rental-contracts.edit');
    Route::put('rental-contracts/{id}', [RentalContractsController::class, 'update'])->whereNumber('id')->name('rental-contracts.update');
});
Route::middleware('permission:rental_contracts.delete')->group(function () {
    Route::delete('rental-contracts/{id}', [RentalContractsController::class, 'destroy'])->whereNumber('id')->name('rental-contracts.destroy');
});

// === rental_invoices ===
Route::middleware('permission:rental_invoices.view')->group(function () {
    Route::get('rental-invoices', [RentalInvoicesController::class, 'index'])->name('rental-invoices.index');
    Route::get('rental-invoices/datatable', [RentalInvoicesController::class, 'datatable'])->name('rental-invoices.datatable');
    Route::get('rental-invoices/{id}', [RentalInvoicesController::class, 'show'])->whereNumber('id')->name('rental-invoices.show');
});
Route::middleware('permission:rental_invoices.create')->group(function () {
    Route::get('rental-invoices/create', [RentalInvoicesController::class, 'create'])->name('rental-invoices.create');
    Route::post('rental-invoices', [RentalInvoicesController::class, 'store'])->name('rental-invoices.store');
});
Route::middleware('permission:rental_invoices.edit')->group(function () {
    Route::get('rental-invoices/{id}/edit', [RentalInvoicesController::class, 'edit'])->whereNumber('id')->name('rental-invoices.edit');
    Route::put('rental-invoices/{id}', [RentalInvoicesController::class, 'update'])->whereNumber('id')->name('rental-invoices.update');
});
Route::middleware('permission:rental_invoices.delete')->group(function () {
    Route::delete('rental-invoices/{id}', [RentalInvoicesController::class, 'destroy'])->whereNumber('id')->name('rental-invoices.destroy');
});

// === sales_teams ===
Route::middleware('permission:sales_teams.view')->group(function () {
    Route::get('sales-teams', [SalesTeamsController::class, 'index'])->name('sales-teams.index');
    Route::get('sales-teams/datatable', [SalesTeamsController::class, 'datatable'])->name('sales-teams.datatable');
    Route::get('sales-teams/{id}', [SalesTeamsController::class, 'show'])->whereNumber('id')->name('sales-teams.show');
});
Route::middleware('permission:sales_teams.create')->group(function () {
    Route::get('sales-teams/create', [SalesTeamsController::class, 'create'])->name('sales-teams.create');
    Route::post('sales-teams', [SalesTeamsController::class, 'store'])->name('sales-teams.store');
});
Route::middleware('permission:sales_teams.edit')->group(function () {
    Route::get('sales-teams/{id}/edit', [SalesTeamsController::class, 'edit'])->whereNumber('id')->name('sales-teams.edit');
    Route::put('sales-teams/{id}', [SalesTeamsController::class, 'update'])->whereNumber('id')->name('sales-teams.update');
});
Route::middleware('permission:sales_teams.delete')->group(function () {
    Route::delete('sales-teams/{id}', [SalesTeamsController::class, 'destroy'])->whereNumber('id')->name('sales-teams.destroy');
});

// === sales_team_members ===
Route::middleware('permission:sales_team_members.view')->group(function () {
    Route::get('sales-team-members', [SalesTeamMembersController::class, 'index'])->name('sales-team-members.index');
    Route::get('sales-team-members/datatable', [SalesTeamMembersController::class, 'datatable'])->name('sales-team-members.datatable');
    Route::get('sales-team-members/{id}', [SalesTeamMembersController::class, 'show'])->whereNumber('id')->name('sales-team-members.show');
});
Route::middleware('permission:sales_team_members.create')->group(function () {
    Route::get('sales-team-members/create', [SalesTeamMembersController::class, 'create'])->name('sales-team-members.create');
    Route::post('sales-team-members', [SalesTeamMembersController::class, 'store'])->name('sales-team-members.store');
});
Route::middleware('permission:sales_team_members.edit')->group(function () {
    Route::get('sales-team-members/{id}/edit', [SalesTeamMembersController::class, 'edit'])->whereNumber('id')->name('sales-team-members.edit');
    Route::put('sales-team-members/{id}', [SalesTeamMembersController::class, 'update'])->whereNumber('id')->name('sales-team-members.update');
});
Route::middleware('permission:sales_team_members.delete')->group(function () {
    Route::delete('sales-team-members/{id}', [SalesTeamMembersController::class, 'destroy'])->whereNumber('id')->name('sales-team-members.destroy');
});

// === commissions ===
Route::middleware('permission:commissions.view')->group(function () {
    Route::get('commissions', [CommissionsController::class, 'index'])->name('commissions.index');
    Route::get('commissions/datatable', [CommissionsController::class, 'datatable'])->name('commissions.datatable');
    Route::get('commissions/{id}', [CommissionsController::class, 'show'])->whereNumber('id')->name('commissions.show');
});
Route::middleware('permission:commissions.create')->group(function () {
    Route::get('commissions/create', [CommissionsController::class, 'create'])->name('commissions.create');
    Route::post('commissions', [CommissionsController::class, 'store'])->name('commissions.store');
});
Route::middleware('permission:commissions.edit')->group(function () {
    Route::get('commissions/{id}/edit', [CommissionsController::class, 'edit'])->whereNumber('id')->name('commissions.edit');
    Route::put('commissions/{id}', [CommissionsController::class, 'update'])->whereNumber('id')->name('commissions.update');
});
Route::middleware('permission:commissions.delete')->group(function () {
    Route::delete('commissions/{id}', [CommissionsController::class, 'destroy'])->whereNumber('id')->name('commissions.destroy');
});

// === documents ===
Route::middleware('permission:documents.view')->group(function () {
    Route::get('documents', [DocumentsController::class, 'index'])->name('documents.index');
    Route::get('documents/datatable', [DocumentsController::class, 'datatable'])->name('documents.datatable');
    Route::get('documents/{id}', [DocumentsController::class, 'show'])->whereNumber('id')->name('documents.show');
});
Route::middleware('permission:documents.create')->group(function () {
    Route::get('documents/create', [DocumentsController::class, 'create'])->name('documents.create');
    Route::post('documents', [DocumentsController::class, 'store'])->name('documents.store');
});
Route::middleware('permission:documents.edit')->group(function () {
    Route::get('documents/{id}/edit', [DocumentsController::class, 'edit'])->whereNumber('id')->name('documents.edit');
    Route::put('documents/{id}', [DocumentsController::class, 'update'])->whereNumber('id')->name('documents.update');
});
Route::middleware('permission:documents.delete')->group(function () {
    Route::delete('documents/{id}', [DocumentsController::class, 'destroy'])->whereNumber('id')->name('documents.destroy');
});

// === chart_of_accounts ===
Route::middleware('permission:chart_of_accounts.view')->group(function () {
    Route::get('chart-of-accounts', [ChartOfAccountsController::class, 'index'])->name('chart-of-accounts.index');
    Route::get('chart-of-accounts/datatable', [ChartOfAccountsController::class, 'datatable'])->name('chart-of-accounts.datatable');
    Route::get('chart-of-accounts/{id}', [ChartOfAccountsController::class, 'show'])->whereNumber('id')->name('chart-of-accounts.show');
});
Route::middleware('permission:chart_of_accounts.create')->group(function () {
    Route::get('chart-of-accounts/create', [ChartOfAccountsController::class, 'create'])->name('chart-of-accounts.create');
    Route::post('chart-of-accounts', [ChartOfAccountsController::class, 'store'])->name('chart-of-accounts.store');
});
Route::middleware('permission:chart_of_accounts.edit')->group(function () {
    Route::get('chart-of-accounts/{id}/edit', [ChartOfAccountsController::class, 'edit'])->whereNumber('id')->name('chart-of-accounts.edit');
    Route::put('chart-of-accounts/{id}', [ChartOfAccountsController::class, 'update'])->whereNumber('id')->name('chart-of-accounts.update');
});
Route::middleware('permission:chart_of_accounts.delete')->group(function () {
    Route::delete('chart-of-accounts/{id}', [ChartOfAccountsController::class, 'destroy'])->whereNumber('id')->name('chart-of-accounts.destroy');
});

// === expense_categories ===
Route::middleware('permission:expense_categories.view')->group(function () {
    Route::get('expense-categories', [ExpenseCategoriesController::class, 'index'])->name('expense-categories.index');
    Route::get('expense-categories/datatable', [ExpenseCategoriesController::class, 'datatable'])->name('expense-categories.datatable');
    Route::get('expense-categories/{id}', [ExpenseCategoriesController::class, 'show'])->whereNumber('id')->name('expense-categories.show');
});
Route::middleware('permission:expense_categories.create')->group(function () {
    Route::get('expense-categories/create', [ExpenseCategoriesController::class, 'create'])->name('expense-categories.create');
    Route::post('expense-categories', [ExpenseCategoriesController::class, 'store'])->name('expense-categories.store');
});
Route::middleware('permission:expense_categories.edit')->group(function () {
    Route::get('expense-categories/{id}/edit', [ExpenseCategoriesController::class, 'edit'])->whereNumber('id')->name('expense-categories.edit');
    Route::put('expense-categories/{id}', [ExpenseCategoriesController::class, 'update'])->whereNumber('id')->name('expense-categories.update');
});
Route::middleware('permission:expense_categories.delete')->group(function () {
    Route::delete('expense-categories/{id}', [ExpenseCategoriesController::class, 'destroy'])->whereNumber('id')->name('expense-categories.destroy');
});

// === expenses ===
Route::middleware('permission:expenses.view')->group(function () {
    Route::get('expenses', [ExpensesController::class, 'index'])->name('expenses.index');
    Route::get('expenses/datatable', [ExpensesController::class, 'datatable'])->name('expenses.datatable');
    Route::get('expenses/{id}', [ExpensesController::class, 'show'])->whereNumber('id')->name('expenses.show');
});
Route::middleware('permission:expenses.create')->group(function () {
    Route::get('expenses/create', [ExpensesController::class, 'create'])->name('expenses.create');
    Route::post('expenses', [ExpensesController::class, 'store'])->name('expenses.store');
});
Route::middleware('permission:expenses.edit')->group(function () {
    Route::get('expenses/{id}/edit', [ExpensesController::class, 'edit'])->whereNumber('id')->name('expenses.edit');
    Route::put('expenses/{id}', [ExpensesController::class, 'update'])->whereNumber('id')->name('expenses.update');
});
Route::middleware('permission:expenses.delete')->group(function () {
    Route::delete('expenses/{id}', [ExpensesController::class, 'destroy'])->whereNumber('id')->name('expenses.destroy');
});

// === journal_entries ===
Route::middleware('permission:journal_entries.view')->group(function () {
    Route::get('journal-entries', [JournalEntriesController::class, 'index'])->name('journal-entries.index');
    Route::get('journal-entries/datatable', [JournalEntriesController::class, 'datatable'])->name('journal-entries.datatable');
    Route::get('journal-entries/{id}', [JournalEntriesController::class, 'show'])->whereNumber('id')->name('journal-entries.show');
});
Route::middleware('permission:journal_entries.create')->group(function () {
    Route::get('journal-entries/create', [JournalEntriesController::class, 'create'])->name('journal-entries.create');
    Route::post('journal-entries', [JournalEntriesController::class, 'store'])->name('journal-entries.store');
});
Route::middleware('permission:journal_entries.edit')->group(function () {
    Route::get('journal-entries/{id}/edit', [JournalEntriesController::class, 'edit'])->whereNumber('id')->name('journal-entries.edit');
    Route::put('journal-entries/{id}', [JournalEntriesController::class, 'update'])->whereNumber('id')->name('journal-entries.update');
});
Route::middleware('permission:journal_entries.delete')->group(function () {
    Route::delete('journal-entries/{id}', [JournalEntriesController::class, 'destroy'])->whereNumber('id')->name('journal-entries.destroy');
});

// === journal_entry_items ===
Route::middleware('permission:journal_entry_items.view')->group(function () {
    Route::get('journal-entry-items', [JournalEntryItemsController::class, 'index'])->name('journal-entry-items.index');
    Route::get('journal-entry-items/datatable', [JournalEntryItemsController::class, 'datatable'])->name('journal-entry-items.datatable');
    Route::get('journal-entry-items/{id}', [JournalEntryItemsController::class, 'show'])->whereNumber('id')->name('journal-entry-items.show');
});
Route::middleware('permission:journal_entry_items.create')->group(function () {
    Route::get('journal-entry-items/create', [JournalEntryItemsController::class, 'create'])->name('journal-entry-items.create');
    Route::post('journal-entry-items', [JournalEntryItemsController::class, 'store'])->name('journal-entry-items.store');
});
Route::middleware('permission:journal_entry_items.edit')->group(function () {
    Route::get('journal-entry-items/{id}/edit', [JournalEntryItemsController::class, 'edit'])->whereNumber('id')->name('journal-entry-items.edit');
    Route::put('journal-entry-items/{id}', [JournalEntryItemsController::class, 'update'])->whereNumber('id')->name('journal-entry-items.update');
});
Route::middleware('permission:journal_entry_items.delete')->group(function () {
    Route::delete('journal-entry-items/{id}', [JournalEntryItemsController::class, 'destroy'])->whereNumber('id')->name('journal-entry-items.destroy');
});

// === departments ===
Route::middleware('permission:departments.view')->group(function () {
    Route::get('departments', [DepartmentsController::class, 'index'])->name('departments.index');
    Route::get('departments/datatable', [DepartmentsController::class, 'datatable'])->name('departments.datatable');
    Route::get('departments/{id}', [DepartmentsController::class, 'show'])->whereNumber('id')->name('departments.show');
});
Route::middleware('permission:departments.create')->group(function () {
    Route::get('departments/create', [DepartmentsController::class, 'create'])->name('departments.create');
    Route::post('departments', [DepartmentsController::class, 'store'])->name('departments.store');
});
Route::middleware('permission:departments.edit')->group(function () {
    Route::get('departments/{id}/edit', [DepartmentsController::class, 'edit'])->whereNumber('id')->name('departments.edit');
    Route::put('departments/{id}', [DepartmentsController::class, 'update'])->whereNumber('id')->name('departments.update');
});
Route::middleware('permission:departments.delete')->group(function () {
    Route::delete('departments/{id}', [DepartmentsController::class, 'destroy'])->whereNumber('id')->name('departments.destroy');
});

// === employees ===
Route::middleware('permission:employees.view')->group(function () {
    Route::get('employees', [EmployeesController::class, 'index'])->name('employees.index');
    Route::get('employees/datatable', [EmployeesController::class, 'datatable'])->name('employees.datatable');
    Route::get('employees/{id}', [EmployeesController::class, 'show'])->whereNumber('id')->name('employees.show');
});
Route::middleware('permission:employees.create')->group(function () {
    Route::get('employees/create', [EmployeesController::class, 'create'])->name('employees.create');
    Route::post('employees', [EmployeesController::class, 'store'])->name('employees.store');
});
Route::middleware('permission:employees.edit')->group(function () {
    Route::get('employees/{id}/edit', [EmployeesController::class, 'edit'])->whereNumber('id')->name('employees.edit');
    Route::put('employees/{id}', [EmployeesController::class, 'update'])->whereNumber('id')->name('employees.update');
});
Route::middleware('permission:employees.delete')->group(function () {
    Route::delete('employees/{id}', [EmployeesController::class, 'destroy'])->whereNumber('id')->name('employees.destroy');
});

// === asset_categories ===
Route::middleware('permission:asset_categories.view')->group(function () {
    Route::get('asset-categories', [AssetCategoriesController::class, 'index'])->name('asset-categories.index');
    Route::get('asset-categories/datatable', [AssetCategoriesController::class, 'datatable'])->name('asset-categories.datatable');
    Route::get('asset-categories/{id}', [AssetCategoriesController::class, 'show'])->whereNumber('id')->name('asset-categories.show');
});
Route::middleware('permission:asset_categories.create')->group(function () {
    Route::get('asset-categories/create', [AssetCategoriesController::class, 'create'])->name('asset-categories.create');
    Route::post('asset-categories', [AssetCategoriesController::class, 'store'])->name('asset-categories.store');
});
Route::middleware('permission:asset_categories.edit')->group(function () {
    Route::get('asset-categories/{id}/edit', [AssetCategoriesController::class, 'edit'])->whereNumber('id')->name('asset-categories.edit');
    Route::put('asset-categories/{id}', [AssetCategoriesController::class, 'update'])->whereNumber('id')->name('asset-categories.update');
});
Route::middleware('permission:asset_categories.delete')->group(function () {
    Route::delete('asset-categories/{id}', [AssetCategoriesController::class, 'destroy'])->whereNumber('id')->name('asset-categories.destroy');
});

// === assets ===
Route::middleware('permission:assets.view')->group(function () {
    Route::get('assets', [AssetsController::class, 'index'])->name('assets.index');
    Route::get('assets/datatable', [AssetsController::class, 'datatable'])->name('assets.datatable');
    Route::get('assets/{id}', [AssetsController::class, 'show'])->whereNumber('id')->name('assets.show');
});
Route::middleware('permission:assets.create')->group(function () {
    Route::get('assets/create', [AssetsController::class, 'create'])->name('assets.create');
    Route::post('assets', [AssetsController::class, 'store'])->name('assets.store');
});
Route::middleware('permission:assets.edit')->group(function () {
    Route::get('assets/{id}/edit', [AssetsController::class, 'edit'])->whereNumber('id')->name('assets.edit');
    Route::put('assets/{id}', [AssetsController::class, 'update'])->whereNumber('id')->name('assets.update');
});
Route::middleware('permission:assets.delete')->group(function () {
    Route::delete('assets/{id}', [AssetsController::class, 'destroy'])->whereNumber('id')->name('assets.destroy');
});

// === approval_requests ===
Route::middleware('permission:approval_requests.view')->group(function () {
    Route::get('approval-requests', [ApprovalRequestsController::class, 'index'])->name('approval-requests.index');
    Route::get('approval-requests/datatable', [ApprovalRequestsController::class, 'datatable'])->name('approval-requests.datatable');
    Route::get('approval-requests/{id}', [ApprovalRequestsController::class, 'show'])->whereNumber('id')->name('approval-requests.show');
});
Route::middleware('permission:approval_requests.create')->group(function () {
    Route::get('approval-requests/create', [ApprovalRequestsController::class, 'create'])->name('approval-requests.create');
    Route::post('approval-requests', [ApprovalRequestsController::class, 'store'])->name('approval-requests.store');
});
Route::middleware('permission:approval_requests.edit')->group(function () {
    Route::get('approval-requests/{id}/edit', [ApprovalRequestsController::class, 'edit'])->whereNumber('id')->name('approval-requests.edit');
    Route::put('approval-requests/{id}', [ApprovalRequestsController::class, 'update'])->whereNumber('id')->name('approval-requests.update');
});
Route::middleware('permission:approval_requests.delete')->group(function () {
    Route::delete('approval-requests/{id}', [ApprovalRequestsController::class, 'destroy'])->whereNumber('id')->name('approval-requests.destroy');
});

// === approval_steps ===
Route::middleware('permission:approval_steps.view')->group(function () {
    Route::get('approval-steps', [ApprovalStepsController::class, 'index'])->name('approval-steps.index');
    Route::get('approval-steps/datatable', [ApprovalStepsController::class, 'datatable'])->name('approval-steps.datatable');
    Route::get('approval-steps/{id}', [ApprovalStepsController::class, 'show'])->whereNumber('id')->name('approval-steps.show');
});
Route::middleware('permission:approval_steps.create')->group(function () {
    Route::get('approval-steps/create', [ApprovalStepsController::class, 'create'])->name('approval-steps.create');
    Route::post('approval-steps', [ApprovalStepsController::class, 'store'])->name('approval-steps.store');
});
Route::middleware('permission:approval_steps.edit')->group(function () {
    Route::get('approval-steps/{id}/edit', [ApprovalStepsController::class, 'edit'])->whereNumber('id')->name('approval-steps.edit');
    Route::put('approval-steps/{id}', [ApprovalStepsController::class, 'update'])->whereNumber('id')->name('approval-steps.update');
});
Route::middleware('permission:approval_steps.delete')->group(function () {
    Route::delete('approval-steps/{id}', [ApprovalStepsController::class, 'destroy'])->whereNumber('id')->name('approval-steps.destroy');
});

// === tasks ===
Route::middleware('permission:tasks.view')->group(function () {
    Route::get('tasks', [TasksController::class, 'index'])->name('tasks.index');
    Route::get('tasks/datatable', [TasksController::class, 'datatable'])->name('tasks.datatable');
    Route::get('tasks/{id}', [TasksController::class, 'show'])->whereNumber('id')->name('tasks.show');
});
Route::middleware('permission:tasks.create')->group(function () {
    Route::get('tasks/create', [TasksController::class, 'create'])->name('tasks.create');
    Route::post('tasks', [TasksController::class, 'store'])->name('tasks.store');
});
Route::middleware('permission:tasks.edit')->group(function () {
    Route::get('tasks/{id}/edit', [TasksController::class, 'edit'])->whereNumber('id')->name('tasks.edit');
    Route::put('tasks/{id}', [TasksController::class, 'update'])->whereNumber('id')->name('tasks.update');
});
Route::middleware('permission:tasks.delete')->group(function () {
    Route::delete('tasks/{id}', [TasksController::class, 'destroy'])->whereNumber('id')->name('tasks.destroy');
});

// === notifications ===
Route::middleware('permission:notifications.view')->group(function () {
    Route::get('notifications', [NotificationsController::class, 'index'])->name('notifications.index');
    Route::get('notifications/datatable', [NotificationsController::class, 'datatable'])->name('notifications.datatable');
    Route::get('notifications/{id}', [NotificationsController::class, 'show'])->whereNumber('id')->name('notifications.show');
});
Route::middleware('permission:notifications.create')->group(function () {
    Route::get('notifications/create', [NotificationsController::class, 'create'])->name('notifications.create');
    Route::post('notifications', [NotificationsController::class, 'store'])->name('notifications.store');
});
Route::middleware('permission:notifications.edit')->group(function () {
    Route::get('notifications/{id}/edit', [NotificationsController::class, 'edit'])->whereNumber('id')->name('notifications.edit');
    Route::put('notifications/{id}', [NotificationsController::class, 'update'])->whereNumber('id')->name('notifications.update');
});
Route::middleware('permission:notifications.delete')->group(function () {
    Route::delete('notifications/{id}', [NotificationsController::class, 'destroy'])->whereNumber('id')->name('notifications.destroy');
});

// === audit_logs ===
Route::middleware('permission:audit_logs.view')->group(function () {
    Route::get('audit-logs', [AuditLogsController::class, 'index'])->name('audit-logs.index');
    Route::get('audit-logs/datatable', [AuditLogsController::class, 'datatable'])->name('audit-logs.datatable');
    Route::get('audit-logs/{id}', [AuditLogsController::class, 'show'])->whereNumber('id')->name('audit-logs.show');
});

// === login_histories ===
Route::middleware('permission:login_histories.view')->group(function () {
    Route::get('login-histories', [LoginHistoriesController::class, 'index'])->name('login-histories.index');
    Route::get('login-histories/datatable', [LoginHistoriesController::class, 'datatable'])->name('login-histories.datatable');
    Route::get('login-histories/{id}', [LoginHistoriesController::class, 'show'])->whereNumber('id')->name('login-histories.show');
});

// === settings ===
Route::middleware('permission:settings.view')->group(function () {
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('settings/datatable', [SettingsController::class, 'datatable'])->name('settings.datatable');
    Route::get('settings/{id}', [SettingsController::class, 'show'])->whereNumber('id')->name('settings.show');
});
Route::middleware('permission:settings.create')->group(function () {
    Route::get('settings/create', [SettingsController::class, 'create'])->name('settings.create');
    Route::post('settings', [SettingsController::class, 'store'])->name('settings.store');
});
Route::middleware('permission:settings.edit')->group(function () {
    Route::get('settings/{id}/edit', [SettingsController::class, 'edit'])->whereNumber('id')->name('settings.edit');
    Route::put('settings/{id}', [SettingsController::class, 'update'])->whereNumber('id')->name('settings.update');
});
Route::middleware('permission:settings.delete')->group(function () {
    Route::delete('settings/{id}', [SettingsController::class, 'destroy'])->whereNumber('id')->name('settings.destroy');
});

// === code_sequences ===
Route::middleware('permission:code_sequences.view')->group(function () {
    Route::get('code-sequences', [CodeSequencesController::class, 'index'])->name('code-sequences.index');
    Route::get('code-sequences/datatable', [CodeSequencesController::class, 'datatable'])->name('code-sequences.datatable');
    Route::get('code-sequences/{id}', [CodeSequencesController::class, 'show'])->whereNumber('id')->name('code-sequences.show');
});
Route::middleware('permission:code_sequences.create')->group(function () {
    Route::get('code-sequences/create', [CodeSequencesController::class, 'create'])->name('code-sequences.create');
    Route::post('code-sequences', [CodeSequencesController::class, 'store'])->name('code-sequences.store');
});
Route::middleware('permission:code_sequences.edit')->group(function () {
    Route::get('code-sequences/{id}/edit', [CodeSequencesController::class, 'edit'])->whereNumber('id')->name('code-sequences.edit');
    Route::put('code-sequences/{id}', [CodeSequencesController::class, 'update'])->whereNumber('id')->name('code-sequences.update');
});
Route::middleware('permission:code_sequences.delete')->group(function () {
    Route::delete('code-sequences/{id}', [CodeSequencesController::class, 'destroy'])->whereNumber('id')->name('code-sequences.destroy');
});
