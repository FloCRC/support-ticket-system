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

    public function test_createLabel_success(): void
    {
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

    public function test_editLabel_success(): void
    {
        Label::factory()->create();

        $testData = [
            'name' => 'label name',
        ];

        $response = $this->putJson('api/labels/1', $testData);

        $response->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success']);
            });

        $this->assertDatabaseHas('labels', [
            'name' => 'label name',
        ]);
    }

    public function test_editLabel_fail(): void
    {
        $label = Label::factory()->create();

        $testData = [];

        $response = $this->putJson('api/labels/1', $testData);

        $response->assertStatus(422)
            ->assertInvalid([
                'name' => 'The name field is required',
            ]);

        $this->assertDatabaseHas('labels', [
            'name' => $label->name,
        ]);
    }

    public function test_deleteLabel_success(): void
    {
        $label = Label::factory()->create();

        $response = $this->deleteJson('api/labels/1');

        $response->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success']);
            });

        $this->assertDatabaseMissing('labels', [
            'name' => $label->name,
        ]);
    }

    public function test_deleteLabel_fail(): void
    {
        $label = Label::factory()->create();

        $response = $this->deleteJson('api/labels/2');

        $response->assertStatus(404)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success']);
            });

        $this->assertDatabaseHas('labels', [
            'name' => $label->name,
        ]);
    }

    public function test_getAllLabels_success(): void
    {
        Label::factory()->count(5)->create();

        $response = $this->getJson('api/labels');

        $response->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success', 'data'])
                    ->has('data', 5, function (AssertableJson $json) {
                        $json->whereAllType([
                            'id' => 'integer',
                            'name' => 'string',
                            'created_at' => 'string',
                            'updated_at' => 'string',
                            ]);
                    });
            });
    }

    public function test_getLabel_success(): void
    {
        Label::factory()->create();

        $response = $this->getJson('api/labels/1');

        $response->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success', 'data'])
                    ->has('data', function (AssertableJson $json) {
                        $json->whereAllType([
                            'id' => 'integer',
                            'name' => 'string',
                            'created_at' => 'string',
                            'updated_at' => 'string',
                        ]);
                    });
            });
    }

    public function test_getLabel_fail(): void
    {
        Label::factory()->create();

        $response = $this->getJson('api/labels/2');

        $response->assertStatus(404)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success']);
            });
    }

}
