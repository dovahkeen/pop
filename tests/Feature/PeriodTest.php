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

        $updatedValues = [
            'name' => $this->faker->name(),
        ];

        $this->login();
        $response = $this->putJson("$this->endPoint/$period->id", $updatedValues);

        $response->assertOk();
        $this->assertDatabaseHas($this->model, [
            'id'    => $period->id,
            'name'  => $updatedValues['name']
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

    public function login(): void
    {
        $teacher = Teacher::factory()->create();
        $this->actingAs($teacher, 'sanctum');
    }
}
