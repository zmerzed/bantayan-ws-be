<?php

use Kolette\Category\Http\Controllers\V1\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('categories', CategoryController::class);
