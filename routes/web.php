<?php

use Illuminate\Support\Facades\Route;

Route::prefix('dosen')->group(function(){

    Route::prefix('dashboard')->group(function(){
        Route::view('/', 'dosen.views.dashboard')->name('dosen.dashboard');
    });

    Route::prefix('jadwal')->group(function(){
        Route::view('/read', 'dosen.jadwal.views.read')->name('dosen.jadwal.read');
        
    });

});