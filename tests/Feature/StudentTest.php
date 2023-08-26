<?php

namespace Tests\Feature;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testStoreNewStudentWithUniqueUsername()
    {
        $username = $this->faker->unique()->userName;

        $response = $this->postJson('/api/student', [
            'full_name' => $this->faker->name,
            'grade'     => $this->faker->numberBetween(0, 12),
            'username'  => $this->faker->userName,
            'password'  => $this->faker->password(6, 8),
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('students', ['username' => $username]);
    }
}
