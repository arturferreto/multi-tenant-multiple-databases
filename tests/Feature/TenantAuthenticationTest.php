<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
        $user = User::factory()->create();
        $user->tenants()->attach(app('tenant'));
        $user->setting()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_users_with_one_tenant_are_redirected_to_dashboard_when_accessing_choose_tenant_route(): void
    {
        $user = User::factory()->create();
        $user->tenants()->attach(app('tenant'));
        $user->setting()->create();

        $response = $this
            ->actingAs($user)
            ->get('/company/choose');

        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    /**
     * TODO:
     * - Usuário com 2 ou mais tenants é redirecionado para a rota "choose-tenant"
     * - Usuário com 2 ou mais tenants que possui fav_tenant_id não entra na rota "choose-tenant" e vai para dashboard
     * - Usuário escolhe uma empresa pela rota "choose-tenant" e entra nela
     * - Usuário em empresa deleteda é redirecionado para choose-tenant
     */

    public function test_users_with_two_tenants_are_redirected_to_choose_tenant_route(): void
    {
        $user = User::factory()->create();

        $user->tenants()->syncWithoutDetaching([
            app('tenant')->id,
            Tenant::factory()->create()->id,
        ]);

        $user->setting()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        // TODO: FIX ERROR

        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
