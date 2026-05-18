<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlasherInjectionTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    protected string $seeder = DatabaseSeeder::class;

    public function test_flasher_inline_render_script_is_present_on_html_responses(): void
    {
        $user = User::where('username', 'superadmin')->first();
        $this->assertNotNull($user, 'superadmin must exist in the seeded DB');

        $response = $this->actingAs($user)->get('/admin/dashboard');
        $body = $response->getContent();

        // Proves the `@flasher_render` -> inject_assets double-render bug is fixed:
        // FlasherMiddleware (inject_assets=true) is responsible for injection now, so
        // every HTML response carries exactly one flasher-js script element.
        $this->assertStringContainsString('class="flasher-js"', $body, 'inline flasher-js script must be injected');
        $this->assertSame(1, substr_count($body, 'class="flasher-js"'), 'exactly one flasher-js script tag');
    }

    public function test_toast_message_added_by_controller_survives_redirect_and_renders_on_next_page(): void
    {
        $user = User::where('username', 'superadmin')->first();
        $this->assertNotNull($user, 'superadmin must exist in the seeded DB');

        // First request: POST a new customer (mirrors UI create flow). Controller calls
        // $flasher->addSuccess(...) and then redirects. Notification must be persisted to
        // the session for the next request.
        $this->actingAs($user);

        $response = $this->post('/admin/customers', [
            'company_id' => 1,
            'customer_code' => '',
            'name' => 'E2E Test Customer',
            'phone' => '+85512111222',
            'customer_type' => 'buyer',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.customers.index'));

        // Second request: follow the redirect; FlasherMiddleware must inject the toast.
        $follow = $this->get(route('admin.customers.index'));
        $body = $follow->getContent();

        $this->assertStringContainsString('class="flasher-js"', $body);
        $this->assertMatchesRegularExpression('/"envelopes":\s*\[\s*\{[^}]*"message"\s*:\s*"[^"]*reated[^"]*"/', $body);
    }
}
