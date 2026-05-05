<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\UserController;

// method; path or endpoints
Route::get("/sample", function () {
    // format ng data; data structure
    return response()->json([
        "message" => "This is a sample API endpoint.",
        "token" => "xxxxx",
        "user" => [
            "name" => "Juan",
            "age" => 19
        ]
    ]);
});

Route::post("/login", [AuthController::class, "login"]);
Route::get("/home", [UserController::class,"index"]);
Route::get("/new-record", [UserController::class,"store"]);

// Public plant endpoints (read-only)
Route::get('/plants', [PlantController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('plants', PlantController::class, ['except' => ['index']]);
    Route::apiResource('users', UserController::class);
});