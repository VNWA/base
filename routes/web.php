<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('vnwa.dashboard');
});






require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
