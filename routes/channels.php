<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('App.Models.SupportTeam.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
}, ['guards' => ['support']]);

Broadcast::channel('user-chat', function ($user) {
    // Return true if any authenticated user can join
    return auth()->check();
});
