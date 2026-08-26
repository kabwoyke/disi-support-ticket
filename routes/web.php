<?php

use App\Http\Controllers\AuthController;
use Cloudstudio\Ollama\Facades\Ollama;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $response = Ollama::agent('You are a helpful assistant.')
     ->options(['temperature' => 0.8])
    ->prompt('Explain quantum computing in simple terms')
    ->ask();

    echo $response['response'];

        return view('welcome');
    });

Route::livewire("/help" , "pages::help-page")->name('help-page');

Route::livewire("/auth/login" , "pages::auth.login")->name('login');
Route::post("/auth/logout", [AuthController::class , 'logout'] )->name('logout');
Route::livewire("/tickets/create" , "pages::tickets.create")->name("create-ticket")->middleware('auth');
Route::livewire("/tickets/get-tickets" , "pages::view-tickets")->name("get-tickets");
Route::livewire("/users/chat/v1" , "pages::users.chat")->name("user-chat");

// support
Route::livewire("/support/dashboard" , "pages::ict.dashboard")->name("ict-dashboard")->middleware("auth:support");
Route::livewire("/support/manage-desk" , 'pages::ict.manage-desk');
Route::livewire("/support/manage-assets" , "pages::ict.assets")->name('manage-asset');
Route::livewire("/support/manage-desks" , "pages::ict.features.manage-desk")->name('manage-desk');
Route::livewire("/support/notifications/view" , "pages::ict.features.view-notification")->name('view-notification');
Route::livewire("/support/chat/v1" , "pages::ict.features.chat")->name("support-chat")->middleware("auth:support");
Route::livewire("/support/auth/login" , "pages::ict.auth.login")->name('ict-login');
Route::livewire("/support/my-tickets" , "pages::ict.features.my-tickets")->name("my-tickets");
Route::livewire("/support/system/health" , "pages::ict.settings.health")->name('system-health');
Route::post("/support/auth/logout" , [AuthController::class , 'support_logout'])->name('support-logout');
