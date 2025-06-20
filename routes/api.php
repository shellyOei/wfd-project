<?php

use App\Http\Controllers\ZegoCloudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/zegocloud/token', [ZegoCloudController::class, 'getToken'])->name('zegocloud.token');
