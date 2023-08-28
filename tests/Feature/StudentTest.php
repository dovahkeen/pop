<?php

namespace Tests\Feature;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testStoreNew()
    {
        $username = $this->faker->unique()->userName;

        $response = $this->postJson('/api/student', [
            'full_name' => $this->faker->name(),
            'grade'     => $this->faker->numberBetween(0, 12),
            'username'  => $username,
            'password'  => $this->faker->password(6, 8),
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas(Student::class, ['username' => $username]);
    }

    public function testLogin()
    {
        /* @var Student $student */
        $student = Student::factory()->create([
            'username'  => 'student',
            'password'  => bcrypt('password')
        ]);

        $response = $this->postJson('/api/student/login', [
            'username'  => $student->username,
            'password'  => 'password',
        ]);

        $response->assertStatus(200);
    }
}
