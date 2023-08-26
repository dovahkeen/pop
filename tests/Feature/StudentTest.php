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

        $existingStudent = Student::factory()->create();

        $response = $this->postJson('/api/student', [
            'username' => $username,
            // ... other required data ...
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('students', ['username' => $username]);
    }
}
