<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Ticket;

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
    public function createTicket()
    {
        //
    }

    /**
     * Get all instances of the specified resource.
     */
    public function getAllTickets()
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
    public function getTicket(int $ticketId)
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
