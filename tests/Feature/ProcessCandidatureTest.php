<?php

namespace Tests\Feature;

use App\Models\Formation;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProcessCandidatureTest extends TestCase
{
    use RefreshDatabase;


    /**
     * Génère des données simulées pour un nouvel utilisateur.
     *
     * Cette méthode génère des données fictives pour créer un nouvel utilisateur, notamment un nom, un prénom,
     * une adresse e-mail, une résidence, un niveau d'étude, un statut et un mot de passe haché.
     *
     * @return array 
     * @internal Cette méthode est souvent utilisée dans les tests pour créer des utilisateurs fictifs.
     * @see \App\Models\User
     */
    public function generate_user()
    {
        $status = ['user', 'candidat', 'apprenant', 'admin'];
        $levelOfStudy = ['Bacalaureat', 'Licence 1', 'Master 2', 'Master 1'];
        return [
            'name' => fake()->name(),
            'firstName' => fake()->name(),
            'email' => fake()->email(),
            'email_verified_at' => now(),
            'residence' => fake()->name(),
            'levelOfStudy' => $levelOfStudy[mt_rand(0, 3)],
            'status' => $status[mt_rand(0, 3)],
            'password' => Hash::make('password'),
        ];
    }

    /**
     * Teste l'enregistrement d'un nouvel utilisateur via l'API.
     *
     * Ce test vérifie que lorsqu'une requête POST est envoyée à l'endpoint `api/register`
     * avec les données générées d'un nouvel utilisateur, l'enregistrement est effectué avec succès.
     *
     * @return void
     * @internal Cette méthode utilise la méthode `generate_user()` pour fournir des données simulées.
     * @see \App\Http\Controllers\Auth\RegisterController
     */
    public function test_register_user()
    {
        $this->post('api/register', $this->generate_user())
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json');
    }

    /**
     * Teste si un utilisateur peut envoyer sa candidature à une formation.
     *
     * Ce test vérifie que lorsqu'un utilisateur est connecté et envoie une requête POST à l'endpoint `api/user/candidater/formation`,
     * en spécifiant l'ID d'une formation, la candidature est envoyée avec succès.
     *
     * @return void
     * @internal Cette méthode utilise des données générées par les factories pour simuler l'environnement de l'application.
     * @see \App\Models\Formation
     * @see \App\Models\User
     */
    public function test_user_can_send_her_candidature(): void
    {

        Formation::factory(100)->create();
        $formation =  Formation::find(3);

        User::factory(100)->create();
        $user = User::find(1);
        $this->actingAs($user, 'api')
            ->post('api/user/candidater/formation',  ['formationId' => $formation->id])
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json');
    }


    /**
     * Teste si un administrateur peut accepter une candidature.
     *
     * Ce test vérifie que lorsqu'un administrateur est connecté et envoie une requête PUT à l'endpoint `api/admin/candidater/{candidatureId}`,
     * la candidature correspondante est acceptée avec succès.
     *
     * @return void
     * @internal Cette méthode utilise des données générées par les factories pour simuler l'environnement de l'application.
     * @see \App\Models\Formation
     * @see \App\Models\User
     */
    public function test_admin_can_accepte_candidature(): void
    {
        Formation::factory(100)->create();
        User::factory(100)->create();
        $user = User::find(1);
        $this->actingAs($user, 'api')
            ->put('api/admin/candidater/2')
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json');
    }
}
