<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\QueueProductsController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\UserController;
use App\Models\Project;
use App\Models\Quote;
use Illuminate\Support\Facades\Route;

Route::view('/','login')->name('login');
Route::post('/', [UserController::class, 'login'])->name('login_validation');
Route::view('/welcome','welcome')->name('home');
Route::get('/browse',[ProjectController::class,'index'])->name('browse');
Route::get('/quote',[QuoteController::class,'index'])->name('quote');
Route::get('/products',[ProductController::class,'index'])->name('products');
Route::get('/clients',[ClientController::class,'index'])->name('clients');
Route::get('/providers',[ProviderController::class,'index'])->name('providers');

//detalle cotización
Route::get('/quote/{id}',[QueueProductsController::class,'index'])->name('quote-detail');
Route::get('/quote/detailed/{id}',[QueueProductsController::class,'consult'])->name('quote-detail-work');

