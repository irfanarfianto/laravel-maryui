<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Volt::route('/', 'pages.dashboard.index')->name('dashboard');;
    Volt::route('/users', 'pages.users.index');
    Volt::route('/profile', 'pages.profile.index');
});

require __DIR__ . '/auth.php';
