<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Quote extends Model
{
    public function index()
    {
        $quotes = DB::table('quote')->get();
        return view('quotes', ['quotes' => $quotes]);
    }
}
