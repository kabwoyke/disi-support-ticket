<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SolveAuthController;
use App\Models\Question;
use App\Models\SolveUser;
use App\Models\User;
use Cloudstudio\Ollama\Facades\Ollama;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use function Termwind\render;

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




// DISI-SOLVES
Route::get("/disi-solves/auth/login" , [SolveAuthController::class , 'render_login_page'] );
Route::post("/disi-solves/auth/login" , [SolveAuthController::class , 'login'] )->name('solves-login');
Route::post("/disi-solves/auth/logout" , [SolveAuthController::class , 'logout'] )->name('solves-logout');
Route::post("/disi-solves/questions/store" , [QuestionController::class , 'store'] )->name('solves-question-store')->middleware('auth:solves');
Route::get("/disi-solves/activity" , function(){
    return Inertia::render("Activity");
})->name('solves-activity')->middleware('auth:solves');

Route::get("/disi-solves/dashboard" , function(){
    $questions = Question::with('author')
            ->orderBy('created_at', 'desc')
            ->get();

        $userCount = SolveUser::all()->count();
        $pendingApprovals = count(Question::where('status' , 'pending')->get());
        return Inertia::render('Dashboard', [
            'questions' => $questions,
            'userCount' => $userCount,
            'pendingApprovals' => $pendingApprovals
        ]);

})->name('solves-dashboard')->middleware("auth:solves");;

