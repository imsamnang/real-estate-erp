<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Project;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class I18nLabelPolishTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    protected string $seeder = DatabaseSeeder::class;

    public function test_bookings_show_page_renders_unique_field_labels(): void
    {
        $user = User::where('username', 'superadmin')->first();
        $this->actingAs($user);

        $co = Company::first();
        $br = Branch::first();
        $cust = Customer::firstOrCreate(
            ['customer_code' => 'CUS-TEST'],
            ['company_id' => $co->id, 'branch_id' => $br->id, 'name' => 'Test', 'status' => 'active']
        );
        $pt = PropertyType::firstOrCreate(
            ['code' => 'LAND'],
            ['company_id' => $co->id, 'branch_id' => $br->id, 'name' => 'Land', 'status' => 'active']
        );
        $proj = Project::firstOrCreate(
            ['project_code' => 'TP-001'],
            ['company_id' => $co->id, 'branch_id' => $br->id, 'name' => 'TP', 'status' => 'active', 'created_by' => $user->id]
        );
        $prop = Property::firstOrCreate(
            ['property_code' => 'P-001'],
            ['company_id' => $co->id, 'branch_id' => $br->id, 'project_id' => $proj->id, 'property_type_id' => $pt->id, 'title' => 'P', 'status' => 'available', 'created_by' => $user->id]
        );
        $b = Booking::create([
            'company_id' => $co->id, 'branch_id' => $br->id,
            'customer_id' => $cust->id, 'property_id' => $prop->id,
            'booking_no' => 'BK-TEST', 'booking_date' => '2025-01-01',
            'expiry_date' => '2025-06-01', 'booking_amount' => 1000,
            'status' => 'active', 'created_by' => $user->id,
            'cancelled_by' => $user->id, 'cancelled_at' => '2025-03-01',
        ]);

        $body = $this->get('/admin/bookings/'.$b->id)->getContent();

        // Booking show page must render each colliding column with a distinct EN label
        $this->assertStringContainsString('Booking Date', $body);
        $this->assertStringContainsString('Expiry Date', $body);
        $this->assertStringContainsString('Cancelled At', $body);
        $this->assertStringContainsString('Created By', $body);
        $this->assertStringContainsString('Cancelled By', $body);
    }

    public function test_audit_logs_show_page_renders_unique_field_labels(): void
    {
        $user = User::where('username', 'superadmin')->first();
        $this->actingAs($user);

        $co = Company::first();
        $br = Branch::first();
        $a = AuditLog::create([
            'company_id' => $co->id, 'branch_id' => $br->id,
            'user_id' => $user->id, 'action' => 'create', 'module' => 'bookings',
            'auditable_type' => 'Booking', 'auditable_id' => 1,
            'old_values' => [], 'new_values' => ['a' => 1],
            'ip_address' => '127.0.0.1', 'user_agent' => 'Bot/1.0',
        ]);

        $body = $this->get('/admin/audit-logs/'.$a->id)->getContent();

        $this->assertStringContainsString('Action', $body);
        $this->assertStringContainsString('Module', $body);
        $this->assertStringContainsString('Auditable Type', $body);
        $this->assertStringContainsString('Auditable ID', $body);
        $this->assertStringContainsString('Old Values', $body);
        $this->assertStringContainsString('New Values', $body);
        $this->assertStringContainsString('User Agent', $body);
    }

    public function test_no_translation_keys_are_missing_on_module_index_pages(): void
    {
        $user = User::where('username', 'superadmin')->first();
        $this->actingAs($user);

        // Index pages for known-collision modules should not show raw "fields.X.Y" placeholders
        foreach (['/admin/bookings', '/admin/audit-logs', '/admin/sale-contracts', '/admin/properties', '/admin/companies'] as $url) {
            $body = $this->get($url)->getContent();
            $this->assertDoesNotMatchRegularExpression('/\bfields\.[a-z_]+\.[a-z_]+\b/', $body,
                "Found unresolved translation key on $url");
        }
    }
}
