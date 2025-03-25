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
     * Show the form for creating a new resource.
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
            ]);
        }

        return response()->json([
            'message' => 'Ticket not created.',
            'success' => false,
        ]);
    }

    /**
     * Get all instances of the specified resource.
     */
    public function getAllTickets(): JsonResponse
    {
        $tickets = Ticket::all();

        return response()->json([
            'message' => 'Tickets retrieved successfully.',
            'success' => true,
            'data' => $tickets
        ]);
    }

    /**
     * Get the specified resource.
     */
    public function getTicket(int $ticketId): JsonResponse
    {
        $ticket = $this->ticket->find($ticketId);

        return response()->json([
            'message' => 'Ticket retrieved successfully.',
            'success' => true,
            'data' => $ticket
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editTicket(Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteTicket(Ticket $ticket)
    {
        //
    }
}
