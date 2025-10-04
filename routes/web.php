<?php

use App\Http\Controllers\ProfileController;
use App\Models\ProductCategory;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('vnwa.dashboard');
});

Route::get('/test', function () {
    $category = ProductCategory::find(9);
    return view('pages.test', ['category' => $category]);
});
Route::get('/render-uiblock/{name}', function ($name) {
    return view('components.'.$name)->render();
});



require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
