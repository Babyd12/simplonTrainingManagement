<?php

namespace Tests\Feature;

use App\Models\Formation;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoutesTest extends TestCase
{
    use RefreshDatabase;



    /**
     * Teste si la valeur de retour de la route "list_formations" est une réponse JSON.
     *
     * @return void
     */

    public function test_route_list_formations(): void
    {
        Formation::factory(100)->create();
        User::factory()->admin()->create();

        $user = User::where('email', 'admin@admin.com')->first();
        $this->actingAs($user, 'api')
            ->post('/api/admin/formation')
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json');
    }

    /**
     * Teste si la valeur de retour de la route "list_candidatures" est une réponse JSON.
     *
     * @return void
     */
    public function test_route_list_candidatures(): void
    {

        User::factory(100)->create();
        User::factory()->admin()->create();
        $user = User::where('email', 'admin@admin.com')->first();
        $this->actingAs($user, 'api')
            ->get('/api/admin/users-cadidature')
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json');
    }

    /**
     * Teste si la valeur de retour de la route "list_candidats" est une réponse JSON.
     *
     * @return void
     */

    public function test_route_list_candidats(): void
    {
        User::factory()->admin()->create();
        $user = User::where('email', 'admin@admin.com')->first();
        $this->actingAs($user, 'api')
            ->post('/api/admin/users-acepted')->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json');
    }
}
