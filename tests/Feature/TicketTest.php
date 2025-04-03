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

}
