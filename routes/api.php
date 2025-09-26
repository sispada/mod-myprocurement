<?php

use Illuminate\Support\Facades\Route;
use Module\MyProcurement\Http\Controllers\DashboardController;


Route::get('dashboard', [DashboardController::class, 'index']);