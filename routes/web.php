<?php

use Illuminate\Support\Facades\Route;

Route::view('/','welcome')->name('home');
Route::view('/browse','browse')->name('browse');
Route::view('/quote','quotes')->name('quote');
