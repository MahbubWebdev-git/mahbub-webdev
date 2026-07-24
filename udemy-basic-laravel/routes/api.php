<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\SensorController;

// ১. আরডুইনো ডিভাইস এই লিংকে ডেটা পোস্ট করবে (অতিরিক্ত /api কেটে দেওয়া হলো)
Route::post('/sensor-data', [SensorController::class, 'storeDeviceData']);

// ২. রিয়্যাক্ট ফ্রন্টএন্ড ড্যাশবোর্ড এই লিংক থেকে ডেটা রিড করবে
Route::get('/sensor/live-chart', [SensorController::class, 'getLiveChartData']);


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
