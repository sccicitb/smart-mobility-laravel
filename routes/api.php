<?php

use App\Livewire\Pages\Dashboard;
use App\Livewire\Pages\Login;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmisiController;

Route::get('/emisi', [EmisiController::class, 'getByDate']);

Route::post('/login', [Login::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('/dashboard', [Dashboard::class, 'index']);
});