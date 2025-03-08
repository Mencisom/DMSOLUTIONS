<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueueProductsController
{
    public function index(Request $request){

        $detail = DB::table('quote_materials')->where('quote_id',$request->id)->get();
        return $detail;
    }
}
