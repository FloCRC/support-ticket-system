<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Create a new ticket.
     */
    public function createTicket(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:50',
            'description' => 'required|string|max:500',
            'priority' => 'required|integer|between:1,3',
            'user_id' => 'required|integer|exists:users,id',
            'category_id' => 'required|integer|exists:categories,id',
            'label_id' => 'required|integer|exists:labels,id',
        ]);

        $ticket = new Ticket;
        $ticket->title = $request->title;
        $ticket->description = $request->description;
        $ticket->priority = $request->priority;
        $ticket->status = 1;
        $ticket->user_id = $request->user_id;
        $ticket->category_id = $request->category_id;
        $ticket->label_id = $request->label_id;

        if ($ticket->save()) {
            return response()->json([
                'message' => 'Ticket successfully created.',
                'success' => true,
            ], 201);
        }

        return response()->json([
            'message' => 'An error occurred. Ticket not created.',
            'success' => false,
        ], 500);
    }

    /**
     * Get all tickets.
     */
    public function getAllTickets(): JsonResponse
    {
        $tickets = $this->ticket->all();

        if ($tickets->isEmpty()) {
            return response()->json([
                'message' => 'No tickets found.',
                'success' => false,
            ], 404);
        }

        return response()->json([
            'message' => 'Tickets retrieved successfully.',
            'success' => true,
            'data' => $tickets
        ]);
    }

    /**
     * Get the specified ticket.
     */
    public function getTicket(int $ticketId): JsonResponse
    {
        $ticket = $this->ticket->find($ticketId);

        if (!$ticket) {
            return response()->json([
                'message' => 'Ticket not found.',
                'success' => false,
            ], 404);
        }

        return response()->json([
            'message' => 'Ticket retrieved successfully.',
            'success' => true,
            'data' => $ticket
        ]);
    }

    /**
     * Edit the specified ticket.
     */
    public function editTicket(int $ticketId, Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'string|max:50',
            'description' => 'string|max:500',
            'priority' => 'integer|between:1,3',
            'status' => 'integer|between:1,3',
            'category_id' => 'integer|exists:categories,id',
            'label_id' => 'integer|exists:labels,id',
        ]);

        $ticket = $this->ticket->find($ticketId);

        if (!$ticket) {
            return response()->json([
                'message' => 'Ticket not found.',
                'success' => false,
            ], 404);
        }

        $ticket->title = $request->title ?? $ticket->title;
        $ticket->description = $request->description ?? $ticket->description;
        $ticket->priority = $request->priority ?? $ticket->priority;
        $ticket->status = $request->status ?? $ticket->status;
        $ticket->category_id = $request->category_id ?? $ticket->category_id;
        $ticket->label_id = $request->label_id ?? $ticket->label_id;

        if ($ticket->save()) {
            return response()->json([
                'message' => 'Ticket successfully updated.',
                'success' => true,
            ]);
        }

        return response()->json([
            'message' => 'An error occurred. Ticket not updated.',
            'success' => false,
        ], 500);
    }

    public function deleteTicket(int $ticketId): JsonResponse
    {
        $ticket = $this->ticket->find($ticketId);

        if (!$ticket) {
            return response()->json([
                'message' => 'Ticket not found.',
                'success' => false,
            ], 404);
        }

        if ($ticket->delete()) {
            return response()->json([
                'message' => 'Ticket successfully deleted.',
                'success' => true,
            ]);
        }

        return response()->json([
            'message' => 'An error occurred. Ticket not deleted.',
            'success' => false,
        ], 500);
    }
}
