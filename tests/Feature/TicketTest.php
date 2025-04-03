<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Label;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TicketTest extends TestCase
{

    use DatabaseMigrations;

    public function test_createTicket_success(): void
    {
        Label::factory()->create();
        Category::factory()->create();
        User::factory()->create();

        $testData = [
            'title' => 'ticket title',
            'description' => 'ticket description',
            'priority' => 1,
            'user_id' => 1,
            'category_id' => 1,
            'label_id' => 1,
        ];

        $response = $this->postJson('api/tickets', $testData);

        $response->assertStatus(201)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success']);
            });

        $this->assertDatabaseHas('tickets', $testData);
    }

    public function test_createTicket_fail(): void
    {
        User::factory()->create();
        Label::factory()->create();

        $testData = [
            'description' => 'ticket description',
            'priority' => 'one',
            'user_id' => 1,
            'category_id' => 1,
            'label_id' => 1,
        ];

        $response = $this->postJson('api/tickets', $testData);

        $response->assertStatus(422)
            ->assertInvalid([
                'title' => 'The title field is required',
                'priority' => 'The priority field must be an integer',
                'category_id' => 'The selected category id is invalid',
            ]);

        $this->assertDatabaseMissing('tickets', [
            'name' => 'ticket name',
        ]);
    }


}
