<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_fetch_all_users()
    {
        // Create users
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'email',
                ],
            ]);
    }

    public function test_create_user()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/v1/user/create', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user' => [
                    'id',
                    'name',
                    'email',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
        ]);
    }

    public function test_update_user()
    {
        // Create a user
        $user = User::factory()->create();

        $updateData = [
            'name' => 'Updated Name',
            'email' => $user->email,
        ];

        $response = $this->putJson("/api/v1/user/update/{$user->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User updated successfully.',
                'user' => [
                    'id' => $user->id,
                    'name' => 'Updated Name',
                    'email' => $user->email,
                ],
            ]);

        // Ensure the database reflects the update
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_update_nonexistent_user()
    {
        // Attempt to update a user that doesn't exist
        $response = $this->putJson('/api/v1/user/update/999', [
            'name' => 'Non-existent User',
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'User not found',
            ]);
    }
}
