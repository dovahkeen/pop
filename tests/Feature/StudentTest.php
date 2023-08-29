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

    private string|Student $model = Student::class;
    private string $endPoint = '/api/student';

    public function testStoreNew(): void
    {
        $username = $this->faker->unique()->userName;

        $response = $this->postJson($this->endPoint, [
            'full_name' => $this->faker->name(),
            'grade'     => $this->faker->numberBetween(0, 12),
            'username'  => $username,
            'password'  => $this->faker->password(6, 8),
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas(Student::class, ['username' => $username]);
    }

    public function testLogin(): void
    {
        /* @var Student $student */
        $student = Student::factory()->create([
            'username'  => 'student',
            'password'  => bcrypt('password')
        ]);

        $response = $this->postJson("$this->endPoint/login", [
            'username'  => $student->username,
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
