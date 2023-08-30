<?php

namespace Tests\Feature;

use App\Models\Period;
use App\Models\Student;
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
     * A basic feature test example.
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

    public function testShow()
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

    public function testDestroy(): void
    {
        /* @var Period $period */
        $period = $this->model::factory()->create();

        $this->login();

        $response = $this->deleteJson("$this->endPoint/$period->id");

        $response->assertOk();
        $this->assertDatabaseMissing($this->model, ['id' => $period->id]);
    }

    public function testGetByTeacher()
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

    public function login(): void
    {
        $teacher = Teacher::factory()->create();
        $this->actingAs($teacher, 'sanctum');
    }
}
