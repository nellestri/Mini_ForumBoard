<?php

use Illuminate\Support\Facades\Route;


// Welcome page - public access
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Debug route (remove in production)
