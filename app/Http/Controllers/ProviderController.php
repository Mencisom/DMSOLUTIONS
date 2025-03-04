<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ProviderController
{
    public function index()
    {
        $providers = DB::table('providers')->get();
        return view('providers', ['providers' => $providers]);
    }
}
