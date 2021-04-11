<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitiesController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


/*
 * Public Routes
 */

// Register
Route::post('/register', [AuthController::class, 'register']);
// Login
Route::post('/login', [AuthController::class, 'login']);
// City: Index
Route::get('/city', [CitiesController::class, 'index']);
// City: Show
Route::get('/city/{id}', [CitiesController::class, 'show']);

/*
 * Protected Routes
 */

Route::group(['middleware' => ['auth:sanctum']], function(){
    // City: Store
    Route::post('/city', [CitiesController::class, 'store']);
    // City: Update
    Route::put('/city/{id}', [CitiesController::class, 'update']);
    // City: Delete
    Route::delete('/city/{id}', [CitiesController::class, 'destroy']);
});

