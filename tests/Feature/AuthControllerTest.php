<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use WithFaker, RefreshDatabase;

    /**
     * Teste que l'enregistrement d'un utilisateur échoue lorsque des données insuffisantes sont fournies.
     *
     * Ce test vérifie que lorsqu'une requête POST est envoyée à l'endpoint `api/register` sans fournir de données,
     * l'enregistrement de l'utilisateur échoue avec un statut HTTP 422 (Unprocessable Entity).
     *
     * @return void
     * @internal Cette méthode teste un scénario où des données insuffisantes sont fournies lors de l'enregistrement d'un utilisateur.
     * 
     */

    public function test_user_can_not_be_register_without_data()
    {
        $response = $this->post('api/register', []);
        $response->assertStatus(422);
    }


    /**
     * Teste l'enregistrement réussi d'un utilisateur via l'API.
     *
     * Ce test vérifie que lorsqu'une requête POST est envoyée à l'endpoint `api/register` avec des données complètes,
     * l'enregistrement de l'utilisateur est effectué avec succès, renvoyant un statut HTTP 200 (OK).
     *
     * @return void
     * @internal Cette méthode teste un scénario où un utilisateur est enregistré avec des données valides via l'API.
     */
    public function test_successful_user_registration()
    {

        $response = $this->post('api/register', [
            'name' => 'EFISFSJ',
            'firstName' => 'dlf',
            'residence' => 'djsf',
            'password' => Hash::make('password'),
            'email' => $this->faker->unique()->safeEmail,
            'profilePicture' => 'sdfjslfjsfnslfsfskfsjj.png',
            'levelOfStudy' => 'Licence 2',
            'status' => 'user'
        ]);
        $response->assertStatus(200);
      
    }


    /**
     * Teste si l'adresse e-mail est unique lors de l'enregistrement d'un utilisateur via l'API.
     *
     * Ce test vérifie que lorsqu'une adresse e-mail existante est utilisée pour l'enregistrement d'un utilisateur,
     * cela échoue avec un statut HTTP 409 (Conflict), indiquant une violation de l'unicité de l'adresse e-mail.
     *
     * @return void
     * @internal Cette méthode teste un scénario où une adresse e-mail existante est utilisée pour l'enregistrement.
     */

    public function test_if_email_is_unique_using_toto_mail()
    {
        $existingEmail = 'toto@gmail.com';

        $response = $this->post('api/register', [
            'name' => $this->faker->name,
            'firstName' => $this->faker->firstName,
            'residence' => $this->faker->city,
            'password' => Hash::make('password'),
            'email' => $existingEmail,
            // 'profilePicture' => 'sOMETHING WHEN WRONG',
            'levelOfStudy' => 'Master 1',
            'status' => 'user'

        ]);

        $response = $this->post('api/register', [
            'name' => $this->faker->name,
            'firstName' => $this->faker->firstName,
            'residence' => $this->faker->city,
            'password' => Hash::make('password'),
            'email' => $existingEmail,
            // 'profilePicture' => 'sOMETHING WHEN WRONG',
            'levelOfStudy' => 'Master 1',
            'status' => 'user'

        ]);

        $response->assertStatus(409);
        // dd(User::all());
    }

    /**
     * Teste l'échec de la connexion avec des informations d'identification incorrectes.
     *
     * Ce test vérifie que lorsqu'une requête POST est envoyée à l'endpoint `api/login` avec des informations d'identification incorrectes,
     * la connexion échoue avec un statut HTTP 401 (Unauthorized).
     *
     * @return void
     * @internal Cette méthode teste un scénario où des informations d'identification incorrectes sont utilisées pour la connexion.
     */
    public function test_get_wrong_login_information()
    {
        $response = $this->post('api/login', [

            'password' => 'passwordxx',
            'email' => 'admin@admin.com',
        ]);
        $response->assertStatus(401);
    }

    /**
     * Teste la réussite de la connexion d'un utilisateur via une route et des données valides.
     *
     * Ce test vérifie que lorsqu'une requête POST est envoyée à l'endpoint `api/login` avec des informations d'identification correctes,
     * la connexion de l'utilisateur est effectuée avec succès, renvoyant un statut HTTP 200 (OK).
     *
     * @return void
     * @internal Cette méthode teste un scénario où un utilisateur se connecte avec des informations d'identification valides via l'API.
     */
    public function test_user_can_be_loged_using_route_and_datas()
    {
        User::factory()->admin()->create();
        $response = $this->post('api/login', [
            'password' => 'password',
            'email' => 'admin@admin.com',
        ]);
        // dd($response);
        $response->assertStatus(200);
    }
}
