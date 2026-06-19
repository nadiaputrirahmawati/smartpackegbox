<?php

use App\Models\Package;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('package.{packageId}', function ($user, $packageId) {
    $package = \App\Models\Package::find($packageId);
    
    // Izinkan jika user adalah pemilik paket ATAU jika user adalah Admin (opsional)
    return $package && (int) $user->id === (int) $package->user_id;
});

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
}); 