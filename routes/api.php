<?php

use App\Http\Controllers\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('jwt.auth')->post('/upload', [UploadController::class, 'store']);
