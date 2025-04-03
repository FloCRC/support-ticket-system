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
            'description' => true,
            'priority' => 'one',
            'user_id' => 6,
            'category_id' => 1,
            'label_id' => 'hi',
        ];

        $response = $this->postJson('api/tickets', $testData);

        $response->assertStatus(422)
            ->assertInvalid([
                'title' => 'The title field is required',
                'description' => 'The description field must be a string',
                'priority' => 'The priority field must be an integer',
                'user_id' => 'The selected user id is invalid',
                'category_id' => 'The selected category id is invalid',
                'label_id' => 'The label id field must be an integer',
            ]);

        $this->assertDatabaseMissing('tickets', [
            'name' => 'ticket name',
        ]);
    }

    public function test_editTicket_success(): void
    {
        Ticket::factory()->create();

        $testData = [
            'title' => 'new ticket title',
        ];

        $response = $this->putJson('api/tickets/1', $testData);

        $response->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success']);
            });

        $this->assertDatabaseHas('tickets', [
            'title' => 'new ticket title',
        ]);
    }

    public function test_editTicket_fail(): void
    {
        $ticket = Ticket::factory()->create();

        $testData = [
            'title' => 2,
            'description' => 3,
            'priority' => 7,
            'user_id' => 2,
            'category_id' => 4,
            'label_id' => 'label one',
        ];

        $response = $this->putJson('api/tickets/1', $testData);

        $response->assertStatus(422)
            ->assertInvalid([
                'title' => 'The title field must be a string',
                'description' => 'The description field must be a string',
                'priority' => 'The priority field must be between 1 and 3',
                'category_id' => 'The selected category id is invalid',
                'label_id' => 'The label id field must be an integer',
            ]);

        $this->assertDatabaseHas('tickets', [
            'category_id' => $ticket->category_id,
        ]);
    }

    public function test_deleteTicket_success(): void
    {
        $ticket = Ticket::factory()->create();

        $response = $this->deleteJson('api/tickets/1');

        $response->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success']);
            });

        $this->assertDatabaseMissing('tickets', [
            'title' => $ticket->title,
        ]);
    }

    public function test_deleteTicket_fail(): void
    {
        $ticket = Ticket::factory()->create();

        $response = $this->deleteJson('api/tickets/2');

        $response->assertStatus(404)
            ->assertJson(function (AssertableJson $json) {
                $json->hasAll(['message', 'success']);
            });

        $this->assertDatabaseHas('tickets', [
            'title' => $ticket->title,
        ]);
    }

}
