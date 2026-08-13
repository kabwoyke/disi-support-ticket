<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::livewire("/help" , "pages::help-page")->name('help-page');

Route::livewire("/auth/login" , "pages::auth.login")->name('login');
Route::post("/auth/logout", [AuthController::class , 'logout'] )->name('logout');
Route::livewire("/tickets/create" , "pages::tickets.create")->name("create-ticket")->middleware('auth');
Route::livewire("/tickets/get-tickets" , "pages::view-tickets")->name("get-tickets");

// support
Route::livewire("/support/dashboard" , "pages::ict.dashboard")->name("ict-dashboard")->middleware("auth:support");
Route::livewire("/support/manage-desk" , 'pages::ict.manage-desk')->name('manage-desk');
Route::livewire("/support/manage-assets" , "pages::ict.assets")->name('manage-asset');
Route::livewire("/support/auth/login" , "pages::ict.auth.login")->name('ict-login');
Route::post("/support/auth/logout" , [AuthController::class , 'support_logout'])->name('support-logout');
