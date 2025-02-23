<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class QuoteController
{
    public function index()
    {
        $quotes = DB::table('quote')->get();
        return view('quotes', ['quotes' => $quotes]);
    }
}
