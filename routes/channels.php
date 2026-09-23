<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

require module_path(
    'Messagings',
    'Routes/channels.php'
);


// =====================================================
// CORE - ROLES
// =====================================================

Broadcast::channel('admin.roles', function ($user) {
    return $user !== null;
});


// =====================================================
// CORE - USERS
// =====================================================

Broadcast::channel('users', function ($user) {
    return $user !== null;
});
