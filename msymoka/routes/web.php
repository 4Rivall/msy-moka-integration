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


Route::get('coPayment', [TestController::class,"index"]);
Route::post('post.CoPayment', [TestController::class,"testCoPayment"])->name('testCoPayment');
Route::get('testBin', [TestController::class,"testBin"]);