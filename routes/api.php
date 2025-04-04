<?php

use App\Http\Controllers\LabelController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CategoryController;
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
    Route::delete('/tickets/{ticketId}', 'deleteTicket');
});

Route::controller(LabelController::class)->group(function () {
    Route::get('/labels', 'getAllLabels');
    Route::get('/labels/{labelId}', 'getLabel');
    Route::post('/labels', 'createLabel');
    Route::put('/labels/{labelId}', 'editLabel');
    Route::delete('/labels/{labelId}', 'deleteLabel');
});

Route::controller(CategoryController::class)->group(function () {
    Route::get('/categories', 'getAllCategories');
    Route::get('/categories/{categoryId}', 'getCategory');
    Route::post('/categories', 'createCategory');
    Route::put('/categories/{categoryId}', 'editCategory');
    Route::delete('/categories/{categoryId}', 'deleteCategory');
});
