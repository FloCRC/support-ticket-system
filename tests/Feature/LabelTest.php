<?php

namespace Tests\Feature;

use App\Models\Label;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LabelTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * A basic feature test example.
     */
    public function test_createLabel_success(): void
    {
        Label::factory()->create();

        $testData = [
            'name' => 'label name',
        ];

        $response = $this->postJson('api/labels', $testData);

        $response->assertStatus(201)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success']);
            });

        $this->assertDatabaseHas('labels', $testData);
    }

    public function test_createLabel_fail(): void
    {
        Label::factory()->create();

        $testData = [];

        $response = $this->postJson('api/labels', $testData);

        $response->assertStatus(422)
            ->assertInvalid([
                'name' => 'The name field is required',
            ]);

        $this->assertDatabaseMissing('labels', [
            'name' => 'label name',
        ]);
    }

}
