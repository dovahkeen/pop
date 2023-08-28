<?php

namespace Tests\Feature;

use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeacherTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     */
    public function testStoreNew(): void
    {
        $username = $this->faker->unique()->userName;

        $response = $this->postJson('/api/teacher', [
            'full_name' => $this->faker->name(),
            'email'     => $this->faker->email(),
            'username'  => $username,
            'password'  => $this->faker->password(6, 8),
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas(Teacher::class, ['username' => $username]);
    }
}
