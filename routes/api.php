<?php

use App\Http\Controllers\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(TicketController::class)->group(function () {
    Route::get('/tickets', 'getAllTickets');
    Route::get('/tickets/{ticketId}', 'getTicket');
    Route::post('/tickets', 'createTicket');
    Route::put('/tickets/{ticketId}', 'editTicket');
});
