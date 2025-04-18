<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('homepage');
});

Route::get('/admin', function () {
    return view('adminPage');
})->name('admin.page');

// create pengumuman
Route::get('/pengumuman', function() {
    return view('createPengumuman');
})->name('pengumuman.page');
