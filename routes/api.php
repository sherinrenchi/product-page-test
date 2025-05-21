<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Group all product routes under auth middleware
Route::middleware('auth:sanctum')->group(function () {
    // Read (already present)
    Route::get('/products/{id}', [ProductController::class, 'show']);

    // Create
    Route::post('/products', [ProductController::class, 'store']);

    // Update
    Route::put('/products/{id}', [ProductController::class, 'update']);

    // Delete
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});
