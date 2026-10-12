<?php

use App\Http\Controllers\GoogleDriveController;
use Illuminate\Support\Facades\Route;


Route::get('/test/drive/list-files', [GoogleDriveController::class, 'getFiles']);