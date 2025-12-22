<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\CustomerController;
Route::resource('/customers', CustomerController::class);

use App\Http\Controllers\TeacherController;
Route::resource('/teachers', TeacherController::class);

use App\Http\Controllers\AppointmentController;
Route::resource('/appointments', AppointmentController::class);

use App\Http\Controllers\ServiceController;
Route::resource('/services', ServiceController::class);
