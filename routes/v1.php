<?php

declare(strict_types=1);

use App\Http\Controllers\V1;
use Illuminate\Support\Facades\Route;

Route::apiResource('/categories', V1\CategoryController::class);
Route::apiResource('/products', V1\ProductController::class);
