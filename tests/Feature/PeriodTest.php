<?php

namespace Tests\Feature;

use App\Models\Period;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PeriodTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private string|Period $model = Period::class;
    private string $endPoint = '/api/period';

    /**
     * Test storing a new period record.
     *
     * @return void
     */
    public function testStoreNew(): void
    {
        $this->login();
        $name = $this->faker->name();

        $response = $this->postJson($this->endPoint, [
            'name' => $name,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas($this->model, ['name' => $name]);
    }

    /**
     * Test retrieving a specific period record.
     *
     * @return void
     */
    public function testShow(): void
    {
        /* @var Period $period  */
        $period = $this->model::factory()->create();

        $this->login();

        $response = $this->getJson("$this->endPoint/$period->id");

        $response->assertOk();
        $response->assertJson([
            'id'    => $period->id,
            'name'  => $period->name,
        ]);
    }

    /**
     * Test updating a period record.
     *
     * @return void
     */
    public function testUpdate(): void
    {
        /* @var Period $period  */
        $period = $this->model::factory()->create();

        /* @var Teacher $teacher  */
        $teacher = Teacher::factory()->create();

        $updatedValues = [
            'name'          => $this->faker->name(),
            'teacher_id'    => $teacher->id,
        ];

        $this->login();
        $response = $this->putJson("$this->endPoint/$period->id", $updatedValues);

        $response->assertOk();
        $this->assertDatabaseHas($this->model, [
            'id'            => $period->id,
            'name'          => $updatedValues['name'],
            'teacher_id'    => $teacher->id
        ]);
    }

    /**
     * Test deleting a period record.
     *
     * @return void
     */
    public function testDestroy(): void
    {
        /* @var Period $period */
        $period = $this->model::factory()->create();

        $this->login();

        $response = $this->deleteJson("$this->endPoint/$period->id");

        $response->assertOk();
        $this->assertDatabaseMissing($this->model, ['id' => $period->id]);
    }

    /**
     * Test retrieving period records by teacher.
     *
     * @return void
     */
    public function testGetByTeacher(): void
    {
        /* @var Teacher $teacher  */
        $teacher = Teacher::factory()->create();

        /* @var Teacher $teacherSecond  */
        $teacherSecond = Teacher::factory()->create();

        Period::factory()->count(3)->create(['teacher_id' => $teacher->id]);
        Period::factory()->count(3)->create(['teacher_id' => $teacherSecond->id]);

        $this->login();

        $response = $this->getJson("$this->endPoint/teacher/$teacher->id");

        $response->assertOk();
        $response->assertJsonCount(3);
    }

    /**
     * Simulate teacher login for testing purposes.
     *
     * @return void
     */
    public function login(): void
    {
        $teacher = Teacher::factory()->create();
        $this->actingAs($teacher, 'sanctum');
    }
}
