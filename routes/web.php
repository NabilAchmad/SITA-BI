<?php

use Illuminate\Support\Facades\Route;

Route::get('/homepage', function () {
    return view('homepage');
});

Route::get('/adminPage', function () {
    return view('adminPage');
});
