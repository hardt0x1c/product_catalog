<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/api', fn (): array => ['Laravel' => app()->version()]);
