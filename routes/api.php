<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrackingController;


Route::get('/trackings', [TrackingController::class, 'query']);