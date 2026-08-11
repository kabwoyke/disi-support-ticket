<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::livewire("/help" , "pages::help-page")->name('help-page');

Route::livewire("/auth/login" , "pages::auth.login")->name('login');
Route::post("/auth/logout", [AuthController::class , 'logout'] )->name('logout');
Route::livewire("/tickets/create" , "pages::tickets.create")->name("create-ticket");
