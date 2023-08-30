<?php

namespace Tests\Feature;

use App\Models\Period;
use App\Models\PeriodStudent;
use App\Models\Student;
use App\Models\StudentPeriod;
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
        $this->assertDatabaseHas($this->model, ['username' => $username]);
    }

    public function testShow()
    {
        /* @var Student $student  */
        $student = $this->model::factory()->create();

        $this->login();

        $response = $this->getJson("$this->endPoint/$student->id");

        $response->assertOk();
        $response->assertJson([
            'id'        => $student->id,
            'username'  => $student->username,
            'fullName'  => $student->full_name,
            'grade'     => $student->grade
        ]);
    }

    public function testIndex(): void
    {
        $this->login();
        $response = $this->getJson($this->endPoint);

        $response->assertOk();
        $response->assertJson([]);
    }

    public function testUpdate(): void
    {
        /* @var Student $student  */
        $student = $this->model::factory()->create();

        $updatedValues = [
            'full_name' => $this->faker->name(),
            'grade'     => $this->faker->numberBetween(0, 12)
        ];

        $this->login();
        $response = $this->putJson("$this->endPoint/$student->id", $updatedValues);

        $response->assertOk();
        $this->assertDatabaseHas($this->model, [
            'id'        => $student->id,
            'full_name' => $updatedValues['full_name'],
            'grade'     => $updatedValues['grade'],
        ]);
    }

    public function testDestroy(): void
    {
        /* @var Student $student */
        $student = $this->model::factory()->create();

        $this->login();

        $response = $this->deleteJson("$this->endPoint/$student->id");

        $response->assertOk();
        $this->assertDatabaseMissing($this->model, ['id' => $student->id]);
    }

    public function testAddToPeriod()
    {
        /* @var Student $student */
        $student = Student::factory()->create();

        /* @var Period $period */
        $period = Period::factory()->create();

        $this->login();

        $response = $this->putJson("$this->endPoint/$student->id/period/$period->id");

        // Assertions
        $response->assertOk();
        $this->assertDatabaseHas(PeriodStudent::class, [
            'student_id'    => $student->id,
            'period_id'     => $period->id,
        ]);
    }

    public function testRemoveFromPeriod()
    {
        /* @var Student $student */
        $student = Student::factory()->create();

        /* @var Period $period */
        $period = Period::factory()->create();

        $student->periods()->attach($period);

        $this->login();

        $response = $this->deleteJson("$this->endPoint/$student->id/period/$period->id");

        // Assertions
        $response->assertOk();
        $this->assertDatabaseMissing(PeriodStudent::class, [
            'student_id' => $student->id,
            'period_id' => $period->id,
        ]);
    }

    public function testLogin(): void
    {
        /* @var Student $student */
        $student = $this->model::factory()->create(['password'  => bcrypt('password')]);

        $response = $this->postJson("$this->endPoint/login", [
            'username' => $student->username,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }

    public function login(): void
    {
        $student = $this->model::factory()->create();
        $this->actingAs($student, 'sanctum');
    }
}
