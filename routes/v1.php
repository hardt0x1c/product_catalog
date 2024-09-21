<?php

declare(strict_types=1);

use App\Http\Controllers\V1;
use Illuminate\Support\Facades\Route;

Route::apiResource('/categories', V1\CategoryController::class)->middleware('auth:sanctum');
Route::get('/products/search', [V1\ProductController::class, 'search'])->name('products.search')->middleware('auth:sanctum');
Route::apiResource('/products', V1\ProductController::class)->middleware('auth:sanctum');
Route::post('register', [V1\AuthController::class, 'register']);
Route::post('login', [V1\AuthController::class, 'login'])->name('login');
Route::post('logout', [V1\AuthController::class, 'logout'])
    ->middleware('auth:sanctum');
