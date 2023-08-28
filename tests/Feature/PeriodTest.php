<?php

namespace Tests\Feature;

use App\Models\Period;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PeriodTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     */
    public function testStoreNew(): void
    {
        $name = $this->faker->name();

        $response = $this->postJson('/api/period', [
            'name' => $name,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas(Period::class, ['name' => $name]);
    }
}
