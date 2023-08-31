<?php

namespace Tests\Feature;

use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeacherTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private string|Teacher $model = Teacher::class;
    private string $endPoint = '/api/teacher';

    /**
     * Test storing a new teacher record.
     *
     * @return void
     */
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
        $this->assertDatabaseHas($this->model, ['username' => $username]);
    }

    /**
     * Test retrieving a specific teacher record.
     *
     * @return void
     */
    public function testShow(): void
    {
        /* @var Teacher $teacher  */
        $teacher = $this->model::factory()->create();

        $this->login();

        $response = $this->getJson("$this->endPoint/$teacher->id");

        $response->assertOk();
        $response->assertJson([
            'id'        => $teacher->id,
            'full_name' => $teacher->full_name,
            'username'  => $teacher->username,
            'email'     => $teacher->email
        ]);
    }

    /**
     * Test retrieving a list of teacher records.
     *
     * @return void
     */
    public function testIndex(): void
    {
        $this->login();
        $response = $this->getJson($this->endPoint);

        $response->assertOk();
        $response->assertJson([]);
    }

    /**
     * Test updating a teacher record.
     *
     * @return void
     */
    public function testUpdate(): void
    {
        /* @var Teacher $teacher  */
        $teacher = $this->model::factory()->create();

        $updatedValues = [
            'full_name' => $this->faker->name(),
            'email'     => $this->faker->email
        ];

        $this->login();
        $response = $this->putJson("$this->endPoint/$teacher->id", $updatedValues);

        $response->assertOk();
        $this->assertDatabaseHas($this->model, [
            'id'        => $teacher->id,
            'full_name' => $updatedValues['full_name'],
            'email'     => $updatedValues['email'],
        ]);
    }

    /**
     * Test deleting a teacher record.
     *
     * @return void
     */
    public function testDestroy(): void
    {
        /* @var Teacher $teacher */
        $teacher = $this->model::factory()->create();

        $this->login();

        $response = $this->deleteJson("$this->endPoint/$teacher->id");

        $response->assertOk();
        $this->assertDatabaseMissing($this->model, ['id' => $teacher->id]);
    }

    /**
     * Test teacher login functionality.
     *
     * @return void
     */
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

    /**
     * Simulate teacher login for testing purposes.
     *
     * @return void
     */
    public function login(): void
    {
        $student = $this->model::factory()->create();
        $this->actingAs($student, 'sanctum');
    }
}
