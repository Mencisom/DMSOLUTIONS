<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ClientController
{
    public function index()
    {
        $clients = DB::table('client')->get();
        return view('clients', ['clients' => $clients]);
    }
}
