<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function getToken(): string
    {
        return $this->getJson('/setup')->json('admin');
    }

    public function test_setup_admin_and_token(): void
    {
        $responce = $this->getJson('/setup');
        $responce->assertStatus(200);
    }

    public function test_get_all_users_api_request(): void
    {
        $token = $this->getToken();

        $response = $this->getJson('/api/users', ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200);
    }

    public function test_get_one_user_by_id_api_request(): void
    {
        $token = $this->getToken();

        $user = User::first();

        $response = $this->getJson('/api/users/' . $user->id, ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200);
    }

    public function test_create_an_user_api_request(): void
    {
        $token = $this->getToken();

        $newUser = User::factory(1)->definition();

        $response = $this->post('/api/users', $newUser, ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(201);
    }

    public function test_delete_an_user_api_request(): void
    {
        $token = $this->getToken();

        $newUser = User::factory(1)->create();

        $newUser = User::first();

        $response = $this->getJson('/api/users/' . $newUser->id, ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200);
        $response->json('id');
    }
}
