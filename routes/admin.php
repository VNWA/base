<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProductBrandController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('vnwa')->name('vnwa.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::prefix('auth')->controller(AuthController::class)->name('auth.')->group(function () {
            Route::get('/login', fn() => Inertia::render('login'))->name('login');
            Route::post('/login', 'login')->name('login');

        });
    });


    Route::middleware('admin')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', fn() => Inertia::render('dashboard'))->name('dashboard');
        Route::prefix('media')->name('media.')->controller(MediaController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/load-data', 'loadData')->name('load-data');
            Route::post('/upload', 'upload')->name('upload');
            Route::post('/delete', 'delete')->name('delete');
            Route::post('/dir/store', 'dirStore')->name('dir.store');
            Route::post('/dir/update', 'dirUpdate')->name('dir.update');
            Route::post('/rename', 'rename')->name('rename');
        });

        Route::prefix('user')->name('user.')->controller(UserController::class)->group(function () {
            Route::get('/', fn() => Inertia::render('dashboard'))->name('list');
        });
        Route::prefix('product')->name('product.')->group(function () {
            Route::prefix('/')->controller(ProductController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/list', 'list')->name('list');
                Route::get('/create', 'create')->name('create');
                Route::get('/edit-{id}', 'edit')->name('edit');
            });
            Route::prefix('category')->name('category.')->controller(ProductCategoryController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('tree/load', 'loadTree')
                    ->name('tree.load');

                Route::post('tree/update', 'updateTree')
                    ->name('tree.update');

                Route::get('detail/{id}', 'detail')
                    ->name('detail');

                Route::post('store', 'store')
                    ->name('store');

                Route::post('update/{id}', 'update')
                    ->name('update');

                Route::post('delete', 'delete')
                    ->name('delete');
                Route::get('mini-option', 'options')->name('mini-option');
            });
            Route::prefix('brand')->name('brand.')->controller(ProductBrandController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('list', 'list')->name('list');
                Route::post('/store', 'store')->name('store');
                Route::get('/detail/{id}', 'detail')->name('detail');
                Route::post('/update/{id}', 'update')->name('update');
                Route::get('mini-option', 'options')->name('mini-option');
            });


        });
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'edit')->name('profile.edit');
            Route::patch('/profile', 'update')->name('profile.update');
            Route::delete('/profile', 'destroy')->name('profile.destroy');
        });
    });
});
