<?php

use Illuminate\Support\Facades\Route;

Route::prefix('dosen')->group(function(){

    Route::prefix('dashboard')->group(function(){
        Route::view('/', 'dosen.views.dashboard')->name('dosen.dashboard');
    });

    Route::prefix('bimbingan')->group(function(){
        Route::get('/list-bimbingan', 'dosen.jadwal.views.read')->name('list-mhs-bimbingan');
        
    });

     Route::prefix('jadwal')->group(function(){
        Route::view('/membuat', 'dosen.jadwal.views.membuat')->name('dosen.jadwal.membuat');
        
    });
    Route::prefix('jadwal')->group(function(){
        Route::view('/melihat', 'dosen.jadwal.views.melihat')->name('dosen.jadwal.melihat');
        
    });
    Route::prefix('jadwal')->group(function(){
        Route::view('/perubahan', 'dosen.jadwal.views.perubahan')->name('dosen.jadwal.perubahan');
        
    });

    //tawaran topik
     Route::prefix('tawaranTopik')->group(function(){
        Route::view('/melihat', 'dosen.tawaranTopik.views.melihat')->name('dosen.tawaranTopik.melihat');
        
    });
    Route::prefix('tawaranTopik')->group(function(){
        Route::view('/mengajukan', 'dosen.tawaranTopik.views.mengajukan')->name('dosen.tawaranTopik.mengajukan');
        
    });

    Route::prefix('tawaranTopik')->group(function(){
        Route::view('/mengubah', 'dosen.tawaranTopik.views.mengubah')->name('dosen.tawaranTopik.mengubah');
        
    });
    Route::prefix('tawaranTopik')->group(function(){
        Route::view('/menghapus', 'dosen.tawaranTopik.views.menghapus')->name('dosen.tawaranTopik.menghapus');
        
    });
    
    //sidang
    Route::prefix('sidang')->group(function(){
        Route::view('/melihat', 'dosen.sidang.views.melihat')->name('dosen.sidang.melihat');
        
    });
    Route::prefix('sidang')->group(function(){
        Route::view('/list', 'dosen.sidang.views.list')->name('dosen.sidang.list');
        
    });
    Route::prefix('sidang')->group(function(){
        Route::view('/nilai', 'dosen.sidang.views.nilai')->name('dosen.sidang.nilai');
        
    });

    //pengumuman
    Route::prefix('pengumuman')->group(function(){
        Route::view('/melihat', 'dosen.pengumuman.views.melihat')->name('dosen.pengumuman.melihat');
        
    });
});