<?php

use Illuminate\Support\Facades\Route;
use Module\MyProcurement\Http\Controllers\DashboardController;
use Module\MyProcurement\Http\Controllers\MyProcurementAuctionController;
use Module\MyProcurement\Http\Controllers\MyProcurementHistoryController;

Route::get('dashboard', [DashboardController::class, 'index']);
Route::get('fetch-document', [DashboardController::class, 'document']);

Route::put('auction/{myProcurementAuction}/submitted', [MyProcurementAuctionController::class, 'submitted']);
Route::put('auction/{myProcurementAuction}/restore', [MyProcurementAuctionController::class, 'restore']);
Route::delete('auction/{myProcurementAuction}/force', [MyProcurementAuctionController::class, 'forceDelete']);
Route::resource('auction', MyProcurementAuctionController::class)->parameters([
    'auction' => 'myProcurementAuction'
]);

Route::resource('history', MyProcurementHistoryController::class)->parameters([
    'history' => 'myProcurementHistory'
]);
