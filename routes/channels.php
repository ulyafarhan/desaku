<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Public channels for real-time updates in Desaku.
| These channels broadcast status changes and notifications.
|
*/

// Public channels (no auth required for public broadcasts)
Broadcast::channel('pengajuan', function () {
    return true;
});

Broadcast::channel('mutasi', function () {
    return true;
});

Broadcast::channel('dashboard', function () {
    return true;
});

Broadcast::channel('informasi', function () {
    return true;
});

// Private channel for specific warga notifications
Broadcast::channel('warga.{nik}', function ($user, $nik) {
    return $user->nik === $nik;
});
