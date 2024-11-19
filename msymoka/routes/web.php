<?php

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/hash', function () {
    $number = '123546';
    $hashed = Hash::make($number);
    return response()->json(['hashed' => $hashed]);
});


Route::get('index', [TestController::class,"index"]);
Route::get('testBin', [TestController::class,"testBin"]);