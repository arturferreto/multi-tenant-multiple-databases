<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_without_tenant_are_redirected_to_choose_tenant_route(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(RouteServiceProvider::HOME);

        $response->assertRedirect('/company/choose');
    }

    public function test_users_with_one_tenant_are_redirected_to_dashboard(): void
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create();
        $user->setting()->create();
        $user->tenants()->attach($tenant);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_users_when_trying_to_access_choose_tenant_route_are_redirected_to_dashboard(): void
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create();
        $user->setting()->create();
        $user->tenants()->attach($tenant);

        $response = $this
            ->actingAs($user)
            ->get('/company/choose');

        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_users_with_two_tenants_are_redirected_to_choose_tenant_route(): void
    {
        $user = User::factory()->create();

        $user->tenants()->sync([
            Tenant::factory()->create()->id,
            Tenant::factory()->create()->id,
        ]);

        $user->setting()->create();

        $response = $this
            ->actingAs($user)
            ->get(RouteServiceProvider::HOME);

        $this->assertAuthenticated();

        $response->assertRedirect('/company/choose');
    }

    public function test_users_with_two_tenants_and_fav_tenant_id_are_redirected_to_dashboard(): void
    {
        $user = User::factory()->create();

        $user->tenants()->sync([
            Tenant::factory()->create()->id,
            Tenant::factory()->create()->id,
        ]);

        $user->setting()->create([
            'fav_tenant_id' => $user->tenants->first()->id,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $user->refresh();

        $this->assertAuthenticated();

        $this->assertEquals($user->setting->fav_tenant_id, $user->current_tenant_id);

        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_choose_tenant_and_connect_to_it(): void
    {
        $user = User::factory()->create();

        $user->tenants()->sync([
            Tenant::factory()->create()->id,
            Tenant::factory()->create()->id,
        ]);

        $user->setting()->create([
            'fav_tenant_id' => $user->tenants->first()->id,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $user->refresh();

        $this->assertAuthenticated();

        $this->assertEquals($user->setting->fav_tenant_id, $user->current_tenant_id);

        $response->assertRedirect(RouteServiceProvider::HOME);

        $response = $this
            ->actingAs($user)
            ->get('/company/choose');

        $response->assertOk();

        $response = $this
            ->actingAs($user)
            ->post('/company/choose', [
                'tenant_id' => $user->tenants->last()->id,
            ]);

        $response->assertRedirect(RouteServiceProvider::HOME);

        $user->refresh();

        $this->assertEquals($user->tenants->last()->id, $user->current_tenant_id);
    }

    public function test_user_connect_to_deleted_tenant_and_is_redirected_to_choose_tenant(): void
    {
        $user = User::factory()->create();
        $tenant = Tenant::factory()->create(['deleted_at' => now()]);

        $user->tenants()->attach($tenant->id);
        $user->setting()->create(['fav_tenant_id' => $tenant->id]);

        $response = $this->actingAs($user)->get('/test/dashboard');

        $response->assertRedirect('/company/choose');
    }
}
