<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CategoryTest extends TestCase
{

    use DatabaseMigrations;

    public function test_createCategory_success(): void
    {
        $testData = [
            'name' => 'category name',
        ];

        $response = $this->postJson('api/categories', $testData);

        $response->assertStatus(201)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success']);
            });

        $this->assertDatabaseHas('categories', $testData);
    }

    public function test_createCategory_fail(): void
    {
        $testData = [];

        $response = $this->postJson('api/categories', $testData);

        $response->assertStatus(422)
            ->assertInvalid([
                'name' => 'The name field is required',
            ]);

        $this->assertDatabaseMissing('categories', [
            'name' => 'category name',
        ]);
    }

    public function test_editCategory_success(): void
    {
        Category::factory()->create();

        $testData = [
            'name' => 'category name',
        ];

        $response = $this->putJson('api/categories/1', $testData);

        $response->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success']);
            });

        $this->assertDatabaseHas('categories', [
            'name' => 'category name',
        ]);
    }

    public function test_editCategory_fail(): void
    {
        $category = Category::factory()->create();

        $testData = [];

        $response = $this->putJson('api/categories/1', $testData);

        $response->assertStatus(422)
            ->assertInvalid([
                'name' => 'The name field is required',
            ]);

        $this->assertDatabaseHas('categories', [
            'name' => $category->name,
        ]);
    }

    public function test_deleteCategory_success(): void
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson('api/categories/1');

        $response->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success']);
            });

        $this->assertDatabaseMissing('categories', [
            'name' => $category->name,
        ]);
    }

    public function test_deleteCategory_fail(): void
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson('api/categories/2');

        $response->assertStatus(404)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success']);
            });

        $this->assertDatabaseHas('categories', [
            'name' => $category->name,
        ]);
    }

    public function test_getAllCategories_success(): void
    {
        Category::factory()->count(5)->create();

        $response = $this->getJson('api/categories');

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

    public function test_getCategory_success(): void
    {
        Category::factory()->create();

        $response = $this->getJson('api/categories/1');

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

    public function test_getCategory_fail(): void
    {
        Category::factory()->create();

        $response = $this->getJson('api/categories/2');

        $response->assertStatus(404)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success']);
            });
    }

}
