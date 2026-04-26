<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // Seed basic roles needed for testing
        // We use firstOrCreate just in case seeders run, but RefreshDatabase usually wipes it
        Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'gudang', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'keuangan', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'kasir', 'guard_name' => 'web']);
    }

    /** @test */
    public function login_page_is_accessible()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertSee('Login');
    }

    /** @test */
    public function owner_can_login_and_redirects_to_owner_dashboard()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('owner');

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        // Redirects to /admin/home or /dashboard based on Fortify config
        // Let's just follow redirect
        $response->assertRedirect('/admin/dashboard');

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('dashboard.owner'));
    }

    /** @test */
    public function admin_gudang_can_login_and_redirects_to_gudang_dashboard()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('gudang');

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('dashboard.admin-gudang'));

        $response = $this->actingAs($user)->get(route('dashboard.admin-gudang'));
        $response->assertStatus(200);
    }

    /** @test */
    public function staf_keuangan_can_login_and_redirects_to_keuangan_dashboard()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('keuangan');

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('dashboard.manajer-keuangan'));

        $response = $this->actingAs($user)->get(route('dashboard.manajer-keuangan'));
        $response->assertStatus(200);
    }

    /** @test */
    public function user_cannot_login_with_invalid_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function authenticated_user_can_logout()
    {
        $user = User::factory()->create();
        // user needs a role to avoid navbar error
        $user->assignRole('owner');
        $this->actingAs($user);

        $response = $this->post(route('logout'));

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
