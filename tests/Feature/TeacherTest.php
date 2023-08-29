<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeacherTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private string|Student $model = Teacher::class;
    private string $endPoint = '/api/teacher';

    public function testStoreNew(): void
    {
        $this->login();
        $username = $this->faker->unique()->userName;

        $response = $this->postJson($this->endPoint, [
            'full_name' => $this->faker->name(),
            'email'     => $this->faker->email(),
            'username'  => $username,
            'password'  => $this->faker->password(6, 8),
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas(Teacher::class, ['username' => $username]);
    }

    public function testLogin(): void
    {
        /* @var Teacher $teacher */
        $teacher = $this->model::factory()->create([
            'username'  => 'teacher',
            'password'  => bcrypt('password')
        ]);

        $response = $this->postJson("$this->endPoint/login", [
            'username'  => $teacher->username,
            'password'  => 'password',
        ]);

        $response->assertStatus(200);
    }

    public function testIndex(): void
    {
        $this->login();
        $response = $this->getJson($this->endPoint);

        $response->assertOk();
        $response->assertJson([]);
    }

    public function login(): void
    {
        /* @var Student $student */
        $student = $this->model::factory()->create();
        $this->actingAs($student, 'sanctum');
    }
}
