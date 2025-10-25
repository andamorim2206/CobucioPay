<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/transfer', function () {
    return view('transfer');
});

Route::get('/users', function () {
    return view('users');
});

Route::get('/register', function () {
    return view('register');
});

Route::get('/', function () {
    return view('login');
});

