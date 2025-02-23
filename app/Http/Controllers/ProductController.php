<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ProductController
{
    public function index()
    {
        $products = DB::table('product')->get();
        return view('products', ['products' => $products]);
    }
}
