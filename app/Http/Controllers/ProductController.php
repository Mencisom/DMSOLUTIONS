<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ProductController
{
    public function index()
    {
        $products = DB::table('products')->get();
        return $products;
    }
    public function show(){
        $product = DB::table('products')->get();
        return $product;
    }
public function consult()
{
    try {
        $products = DB::table('products')->select('prod_name', 'prod_price_sales')->get();
        if ($products->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron productos'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $products
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener los productos',
            'error' => $e->getMessage()
        ], 500);
    }
}

}
