<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Volt::route('/', 'dashboard.index')->name('dashboard');;
    Volt::route('/users', 'users.index');
    Volt::route('/profile', 'profile.index');
});

require __DIR__ . '/auth.php';
