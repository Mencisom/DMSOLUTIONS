<?php

use App\Models\Project;
use App\Models\Quote;
use Illuminate\Support\Facades\Route;

Route::view('/','welcome')->name('home');
Route::get('/browse',[Project::class,'index'])->name('browse');
Route::get('/quote',[Quote::class,'index'])->name('quote');
Route::view('/products','products')->name('products');
