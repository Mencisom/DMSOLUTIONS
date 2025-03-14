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


Route::view('/welcome','welcome')->name('home');
Route::get('/browse',[ProjectController::class,'index'])->name('browse');

Route::get('/products',[ProductController::class,'index'])->name('products');

Route::get('/providers',[ProviderController::class,'index'])->name('providers');

//login
Route::view('/','login')->name('login');
Route::post('/', [UserController::class, 'login'])->name('login_validation');

//detalle cotización
Route::get('/quote/{id}',[QueueProductsController::class,'index'])->name('quote-detail');
Route::get('/quote/detailed/{id}',[QueueProductsController::class,'consult'])->name('quote-detail-work');

//Cotización
Route::get('/quote',[QuoteController::class,'index'])->name('quote');
Route::post('/quote',[QuoteController::class,'store'])->name('quote-save');
Route::get('/quote/export/{quote}',[QuoteController::class,'export'])->name('quote-export');

//Cliente

Route::get('/clients',[ClientController::class,'index'])->name('clients');
Route::post('clients/create',[ClientController::class,'store'])->name('client-save');
Route::delete('clients/{client}',[ClientController::class,'destroy'])->name('client-delete');
//
