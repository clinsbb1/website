<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_routes_require_authentication(): void
    {
        $this->get(route('admin.overview'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.products.index'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.articles.index'))->assertRedirect(route('admin.login'));
    }

    public function test_valid_credentials_log_the_admin_in(): void
    {
        $user = User::factory()->create(['password' => bcrypt('correct-password')]);

        $response = $this->post(route('admin.login.store'), [
            'email' => $user->email,
            'password' => 'correct-password',
        ]);

        $response->assertRedirect(route('admin.overview'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        $user = User::factory()->create(['password' => bcrypt('correct-password')]);

        $response = $this->post(route('admin.login.store'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_can_log_out(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.logout'));

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }
}
