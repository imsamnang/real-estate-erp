<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\ChartOfAccount;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Department;
use App\Models\PaymentMethod;
use App\Models\Permission;
use App\Models\Project;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use App\Support\ModuleManifest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $this->seedPermissions();
            $this->seedRoles();
            $this->seedCompanyBranch();
            $this->seedUsers();
            $this->seedReferenceData();
            $this->seedSampleCustomers();
            $this->seedSampleProperties();
            $this->seedSettings();
            $this->primeCodeSequences();
        });
    }

    /**
     * Seed the code_sequences table so future auto-generated codes
     * don't collide with codes manually inserted during seeding.
     */
    private function primeCodeSequences(): void
    {
        $rows = [
            ['module' => 'customers',  'prefix' => 'CUS-',  'next' => 5],
            ['module' => 'properties', 'prefix' => 'PRP-',  'next' => 7],
            ['module' => 'projects',   'prefix' => 'PRJ-',  'next' => 2],
            ['module' => 'users',      'prefix' => 'EMP-',  'next' => 5],
        ];
        // Seed for the (company=1, branch=1) scope our demo users sit in, plus
        // a generic global scope (null/null).
        $scopes = [
            ['company_id' => null, 'branch_id' => null],
            ['company_id' => 1,    'branch_id' => 1],
            ['company_id' => 1,    'branch_id' => 2],
            ['company_id' => 1,    'branch_id' => null],
        ];
        foreach ($scopes as $scope) {
            foreach ($rows as $r) {
                DB::table('code_sequences')->updateOrInsert(
                    array_merge($scope, ['module' => $r['module']]),
                    [
                        'prefix' => $r['prefix'],
                        'next_number' => $r['next'],
                        'padding' => 6,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }
    }

    private function seedPermissions(): void
    {
        $actions = ['view', 'create', 'edit', 'delete'];
        $modules = array_keys(ModuleManifest::all());
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(
                    ['name' => "$module.$action"],
                    [
                        'display_name' => ucfirst($action).' '.str_replace('_', ' ', $module),
                        'module' => $module,
                    ]
                );
            }
        }
    }

    private function seedRoles(): void
    {
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin'], [
            'display_name' => 'Super Admin',
            'description' => 'Full access to all features',
            'status' => 'active',
        ]);
        $superAdmin->permissions()->sync(Permission::pluck('id'));

        $admin = Role::firstOrCreate(['name' => 'admin'], [
            'display_name' => 'Branch Admin',
            'description' => 'Manage a single branch',
            'status' => 'active',
        ]);
        $admin->permissions()->sync(Permission::pluck('id'));

        $manager = Role::firstOrCreate(['name' => 'branch_manager'], [
            'display_name' => 'Branch Manager',
            'description' => 'Manage branch operations',
            'status' => 'active',
        ]);
        $managerPerms = Permission::whereIn('module', [
            'properties', 'customers', 'leads', 'bookings', 'sale_contracts',
            'invoices', 'payments', 'employees', 'commissions',
        ])->pluck('id');
        $manager->permissions()->sync($managerPerms);

        $agent = Role::firstOrCreate(['name' => 'sales_agent'], [
            'display_name' => 'Sales Agent',
            'description' => 'Sell properties and manage leads/bookings',
            'status' => 'active',
        ]);
        $agentPerms = Permission::whereIn('name', [
            'properties.view', 'customers.view', 'customers.create', 'customers.edit',
            'leads.view', 'leads.create', 'leads.edit',
            'bookings.view', 'bookings.create', 'bookings.edit',
            'sale_contracts.view', 'sale_contracts.create',
            'invoices.view', 'payments.view',
        ])->pluck('id');
        $agent->permissions()->sync($agentPerms);
    }

    private function seedCompanyBranch(): void
    {
        $company = Company::firstOrCreate(
            ['code' => 'ABC-RE'],
            [
                'name' => 'ABC Real Estate Co., Ltd.',
                'phone' => '+855 12 345 678',
                'email' => 'info@abc-realestate.kh',
                'address' => 'No. 99, Norodom Blvd, Phnom Penh, Cambodia',
                'website' => 'https://abc-realestate.kh',
                'registration_no' => 'CAMREG-100200300',
                'tax_no' => 'KH-100200300',
                'license_no' => 'LIC-2026-001',
                'status' => 'active',
            ]
        );

        Branch::firstOrCreate(
            ['code' => 'PP-HQ'],
            [
                'company_id' => $company->id,
                'name' => 'Phnom Penh — Head Office',
                'phone' => '+855 23 222 333',
                'email' => 'hq@abc-realestate.kh',
                'address' => 'Norodom Blvd, Phnom Penh',
                'status' => 'active',
            ]
        );

        Branch::firstOrCreate(
            ['code' => 'SR-01'],
            [
                'company_id' => $company->id,
                'name' => 'Siem Reap Branch',
                'phone' => '+855 63 555 666',
                'email' => 'siemreap@abc-realestate.kh',
                'address' => 'Pub Street, Siem Reap',
                'status' => 'active',
            ]
        );
    }

    private function seedUsers(): void
    {
        $company = Company::first();
        $hq = Branch::where('code', 'PP-HQ')->first();
        $sr = Branch::where('code', 'SR-01')->first();

        $super = User::firstOrCreate(
            ['username' => 'superadmin'],
            [
                'company_id' => $company->id,
                'branch_id' => $hq->id,
                'name' => 'Super Administrator',
                'email' => 'super@abc-realestate.kh',
                'password' => Hash::make('password'),
                'staff_code' => 'EMP-0001',
                'position' => 'System Administrator',
                'status' => 'active',
            ]
        );
        $super->syncRoles([Role::where('name', 'super_admin')->first()->id]);

        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'company_id' => $company->id,
                'branch_id' => $hq->id,
                'name' => 'Branch Admin',
                'email' => 'admin@abc-realestate.kh',
                'password' => Hash::make('password'),
                'staff_code' => 'EMP-0002',
                'position' => 'Branch Administrator',
                'status' => 'active',
            ]
        );
        $admin->syncRoles([Role::where('name', 'admin')->first()->id]);

        $manager = User::firstOrCreate(
            ['username' => 'manager'],
            [
                'company_id' => $company->id,
                'branch_id' => $sr->id,
                'name' => 'Sok Dara',
                'email' => 'manager@abc-realestate.kh',
                'password' => Hash::make('password'),
                'staff_code' => 'EMP-0003',
                'position' => 'Branch Manager',
                'status' => 'active',
            ]
        );
        $manager->syncRoles([Role::where('name', 'branch_manager')->first()->id]);

        $agent = User::firstOrCreate(
            ['username' => 'agent'],
            [
                'company_id' => $company->id,
                'branch_id' => $hq->id,
                'name' => 'Chan Sopheap',
                'email' => 'agent@abc-realestate.kh',
                'password' => Hash::make('password'),
                'staff_code' => 'EMP-0004',
                'position' => 'Senior Sales Agent',
                'status' => 'active',
            ]
        );
        $agent->syncRoles([Role::where('name', 'sales_agent')->first()->id]);
    }

    private function seedReferenceData(): void
    {
        $company = Company::first();
        $hq = Branch::where('code', 'PP-HQ')->first();
        $superId = User::where('username', 'superadmin')->value('id');

        foreach ([
            ['Villa', 'VILLA', 'Single-family detached house'],
            ['Apartment', 'APT', 'Mid/high-rise residential unit'],
            ['Shophouse', 'SHOP', 'Linked-row mixed-use'],
            ['Condo', 'CONDO', 'Condominium unit'],
            ['Land', 'LAND', 'Vacant plot of land'],
        ] as [$name, $code, $desc]) {
            PropertyType::firstOrCreate(
                ['code' => $code],
                ['name' => $name, 'description' => $desc, 'status' => 'active'],
            );
        }

        foreach ([
            ['Cash', 'CASH'],
            ['Bank Transfer', 'BANK'],
            ['Credit Card', 'CARD'],
            ['Mobile Wallet', 'WALLET'],
        ] as [$name, $code]) {
            PaymentMethod::firstOrCreate(
                ['code' => $code],
                ['name' => $name, 'status' => 'active'],
            );
        }

        foreach ([
            ['1000', 'Assets', 'asset', null],
            ['1100', 'Cash', 'asset', '1000'],
            ['1200', 'Accounts Receivable', 'asset', '1000'],
            ['2000', 'Liabilities', 'liability', null],
            ['2100', 'Accounts Payable', 'liability', '2000'],
            ['3000', 'Equity', 'equity', null],
            ['4000', 'Revenue', 'revenue', null],
            ['4100', 'Sales Revenue', 'revenue', '4000'],
            ['4200', 'Rental Revenue', 'revenue', '4000'],
            ['5000', 'Expenses', 'expense', null],
        ] as [$code, $name, $type, $parentCode]) {
            $parentId = $parentCode
                ? ChartOfAccount::where('account_code', $parentCode)->value('id')
                : null;
            ChartOfAccount::firstOrCreate(
                ['account_code' => $code],
                [
                    'company_id' => $company->id,
                    'account_name' => $name,
                    'account_type' => $type,
                    'parent_id' => $parentId,
                    'status' => 'active',
                ]
            );
        }

        Department::firstOrCreate(
            ['name' => 'Sales', 'company_id' => $company->id, 'branch_id' => $hq->id],
            ['status' => 'active']
        );
        Department::firstOrCreate(
            ['name' => 'Operations', 'company_id' => $company->id, 'branch_id' => $hq->id],
            ['status' => 'active']
        );
    }

    private function seedSampleCustomers(): void
    {
        $company = Company::first();
        $hq = Branch::where('code', 'PP-HQ')->first();
        $samples = [
            ['Kim Soksan',  'male',   '+855 12 111 222', 'soksan@example.com', 'walk_in',  'buyer'],
            ['Chea Mealea', 'female', '+855 12 333 444', 'mealea@example.com', 'referral', 'buyer'],
            ['Heng Sokhom', 'male',   '+855 12 555 666', 'sokhom@example.com', 'facebook', 'investor'],
            ['Pich Sokha',  'female', '+855 12 777 888', 'sokha@example.com',  'website',  'renter'],
        ];
        $i = 1;
        foreach ($samples as [$name, $gender, $phone, $email, $source, $type]) {
            Customer::firstOrCreate(
                ['email' => $email],
                [
                    'company_id' => $company->id,
                    'branch_id' => $hq->id,
                    'customer_code' => sprintf('CUS-%06d', $i++),
                    'name' => $name,
                    'gender' => $gender,
                    'phone' => $phone,
                    'source' => $source,
                    'customer_type' => $type,
                    'status' => 'active',
                ]
            );
        }
    }

    private function seedSampleProperties(): void
    {
        $company = Company::first();
        $hq = Branch::where('code', 'PP-HQ')->first();
        $type = PropertyType::first();
        $superId = User::where('username', 'superadmin')->value('id');

        $project = Project::firstOrCreate(
            ['project_code' => 'PRJ-000001'],
            [
                'company_id' => $company->id,
                'branch_id' => $hq->id,
                'name' => 'Sunrise Residence',
                'project_type' => 'borey',
                'description' => 'Mid-rise residential project in Phnom Penh',
                'status' => 'active',
                'total_units' => 50,
                'created_by' => $superId,
            ]
        );

        for ($i = 1; $i <= 6; $i++) {
            Property::firstOrCreate(
                ['property_code' => sprintf('PRP-%06d', $i)],
                [
                    'company_id' => $company->id,
                    'branch_id' => $hq->id,
                    'project_id' => $project->id,
                    'property_type_id' => $type?->id,
                    'title' => "Sunrise Residence - Unit {$i}",
                    'unit_no' => 'A-'.$i,
                    'floor_no' => (string) ceil($i / 2),
                    'land_area' => 80 + $i * 5,
                    'building_area' => 120 + $i * 8,
                    'bedrooms' => 2 + ($i % 2),
                    'bathrooms' => 2,
                    'base_price' => 80000 + $i * 1500,
                    'sale_price' => 95000 + $i * 1500,
                    'currency' => 'USD',
                    'status' => 'available',
                    'created_by' => $superId,
                ]
            );
        }
    }

    private function seedSettings(): void
    {
        $company = Company::first();
        foreach ([
            ['general', 'site_name',        'Real-Estate ERP', 'text'],
            ['general', 'default_currency', 'USD',             'text'],
            ['general', 'tax_rate',         '10',              'number'],
        ] as [$group, $key, $value, $type]) {
            Setting::firstOrCreate(
                ['group' => $group, 'key' => $key, 'company_id' => $company->id, 'branch_id' => null],
                ['value' => $value, 'type' => $type, 'is_public' => true]
            );
        }
    }
}
