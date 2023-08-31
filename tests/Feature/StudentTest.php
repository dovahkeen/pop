<?php

namespace Tests\Feature;

use App\Models\Period;
use App\Models\PeriodStudent;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private string|Student $model = Student::class;
    private string $endPoint = '/api/student';

    /**
     * Test the creation and storage of a new student record.
     *
     * @return void
     */
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

    /**
     * Test retrieving a student's information.
     *
     * @return void
     */
    public function testShow(): void
    {
        /* @var Student $student  */
        $student = $this->model::factory()->create();

        $this->login();

        $response = $this->getJson("$this->endPoint/$student->id");

        $response->assertOk();
        $response->assertJson([
            'id'        => $student->id,
            'username'  => $student->username,
            'full_name' => $student->full_name,
            'grade'     => $student->grade
        ]);
    }

    /**
     * Test retrieving a list of student records.
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
     * Test retrieving a list of student records by period.
     *
     * @return void
     */
    public function testIndexByPeriod(): void
    {
        // Create a period and attach students
        /* @var Period $period */
        $period = Period::factory()->create();
        $students = $this->model::factory()->count(3)->create();
        $students->each(function ($student) use ($period) {
            $student->periods()->attach($period);
        });

        // Create another period and attach more students
        $periodSecond = Period::factory()->create();
        $studentsSecond = $this->model::factory()->count(3)->create();
        $studentsSecond->each(function ($student) use ($periodSecond) {
            $student->periods()->attach($periodSecond);
        });

        $this->login();
        $response = $this->getJson("$this->endPoint/period/$period->id");

        // Expect only the requested students by the first period
        $response->assertOk();
        $response->assertJsonCount(3);
    }

    /**
     * Test retrieving a list of student records by period and teacher.
     *
     * @return void
     */
    public function testIndexByPeriodAndTeacher(): void
    {
        // Create teacher and attach periods
        /* @var Teacher $teacher */
        $teacher = Teacher::factory()->create();

        // Create a period associated with the teacher and attach students
        /* @var Period $period */
        $period = Period::factory()->create(['teacher_id' => $teacher->id]);
        $students = $this->model::factory()->count(3)->create();
        $students->each(function ($student) use ($period) {
            $student->periods()->attach($period);
        });

        // Create another teacher and attach periods
        /* @var Teacher $teacherSecond */
        $teacherSecond = Teacher::factory()->create();

        // Create another period associated with the other teacher and attach more students
        $periodSecond = Period::factory()->create(['teacher_id' => $teacherSecond->id]);
        $studentsSecond = $this->model::factory()->count(3)->create();
        $studentsSecond->each(function ($student) use ($periodSecond) {
            $student->periods()->attach($periodSecond);
        });

        // Attach the first students to more periods
        $students->each(function ($student) use ($periodSecond) {
            $student->periods()->attach($periodSecond);
        });

        $this->login();
        $response = $this->getJson("$this->endPoint/period/$period->id/teacher/$teacher->id");

        // Expect only the requested students by the first period
        $response->assertOk();
        $response->assertJsonCount(3);
    }

    /**
     * Test updating a student record.
     *
     * @return void
     */
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

    /**
     * Test deleting a student record.
     *
     * @return void
     */
    public function testDestroy(): void
    {
        /* @var Student $student */
        $student = $this->model::factory()->create();

        $this->login();

        $response = $this->deleteJson("$this->endPoint/$student->id");

        $response->assertOk();
        $this->assertDatabaseMissing($this->model, ['id' => $student->id]);
    }

    /**
     * Test adding a student to a period.
     *
     * @return void
     */
    public function testAddToPeriod(): void
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

    /**
     * Test removing a student from a period.
     *
     * @return void
     */
    public function testRemoveFromPeriod(): void
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

    /**
     * Test student login functionality.
     *
     * @return void
     */
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

    /**
     * Simulate student login for testing purposes.
     *
     * @return void
     */
    public function login(): void
    {
        $student = $this->model::factory()->create();
        $this->actingAs($student, 'sanctum');
    }
}
