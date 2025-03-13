<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuoteController
{
    public function index()
    {
        $quotes = DB::table('quote_client')->get();
        return view('quotes', ['quotes' => $quotes]);
    }

    public function store(Request $request)
    {
        $client = new Client();
        $client = DB::table('clients')->where('client_identification',$request->input('clientId'))->get();
        $quote = new Quote();

        if($client == null){
            $client -> id = 0;
            $client -> client_name = $request-> input('clientName');
            $client -> client_ph = $request-> input('phone');
            $client -> client_email = $request-> input('email');
            $client -> client_identification = $request->input('clientId');
            $client -> client_address = $request-> input('address');
            $client -> save();


            $quote -> quote_client_id = db::table('clients')->select('id')
                ->where('client_identification',$request->input('clientId'))->get();

        }
        else {
            $quote -> quote_client_id = $client->id;
        }

        $quote -> id = 0;
        $quote -> quote_material_total =4000000;
        $quote -> quote_estimated_time = $request -> input('estimatedHours');
        $quote -> quote_helpers = $request -> input('numAssistants');
        $quote -> quote_helper_payday = $request -> input('assistantSalary');
        $quote -> quote_supervisor_payday = $request -> input('supervisorFee');
        $quote ->  quote_work_total = (($quote -> quote_helper_payday / 8) * $quote -> quote_estimated_time) +
            (($quote -> quote_supervisor_payday / 8) * $quote -> quote_estimated_time);
        $quote -> quote_other_costs = $request -> input('otherCosts');
        $quote -> quote_total = $quote ->quote_work_total + $quote -> quote_other_costs + $quote -> quote_material_total;
        $quote ->  quote_expiration_date ="2026-02-17";
        $quote -> save();
    }
}
