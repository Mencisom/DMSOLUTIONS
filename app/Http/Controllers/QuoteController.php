<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Quote;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class QuoteController
{
    public function index()
    {
        $quotes = DB::table('quote_client')->get();
        return view('quotes', ['quotes' => $quotes]);
    }

//    public function store(Request $request)
//    {
//        $client = new Client();
//        $client = DB::table('clients')->where('client_identification',$request->input('clientId'))->get();
//        $quote = new Quote();
//
//        if($client == null){
//            $client -> id = 0;
//            $client -> client_name = $request-> input('clientName');
//            $client -> client_ph = $request-> input('phone');
//            $client -> client_email = $request-> input('email');
//            $client -> client_identification = $request->input('clientId');
//            $client -> client_address = $request-> input('address');
//            $client -> save();
//
//
//            $quote -> quote_client_id = db::table('clients')->select('id')
//                ->where('client_identification',$request->input('clientId'))->get();
//
//        }
//        else {
//            $quote -> quote_client_id = $client->id;
//        }
//
//        $producsJson =$request->input('producs');
//        $producs = json_decode($producsJson);
//
//
//        $quote -> id = 0;
//        $quote -> quote_material_total = 0;
//        $quote -> quote_estimated_time = $request -> input('estimatedHours');
//        $quote -> quote_helpers = $request -> input('numAssistants');
//        $quote -> quote_helper_payday = $request -> input('assistantSalary');
//        $quote -> quote_supervisor_payday = $request -> input('supervisorFee');
//        $quote ->  quote_work_total = (($quote -> quote_helper_payday / 8) * $quote -> quote_estimated_time) +
//            (($quote -> quote_supervisor_payday / 8) * $quote -> quote_estimated_time);
//        $quote -> quote_other_costs = $request -> input('otherCosts');
//        $quote -> quote_total = $quote ->quote_work_total + $quote -> quote_other_costs + $quote -> quote_material_total;
//        $quote ->  quote_expiration_date ="2026-02-17";
//        $quote -> save();
//
//
//
//    }
////    public function store(Request $request)
//    {
//        $producsJson =$request->input('producs');
//        $producs = json_decode($producsJson);
//
//        foreach ($producs as $produc) {
//            $id=$producs['id'];
//            $quantity=$producs['quantity'];
//            $price=$producs['price'];
//        }
//
//        return response()->json([
//            'message' => 'Datos recibidos correctamente',
//            'data' => $request->all()
//        ], 200);
//    }

    public function store(Request $request)
    {

        $client = DB::table('clients')->where('client_identification', $request->input('clientId'))->first();

        if (!$client) {

            $newClient = new Client();
            $newClient->client_name = $request->input('clientName');
            $newClient->client_ph = $request->input('phone');
            $newClient->client_email = $request->input('email');
            $newClient->client_identification = $request->input('clientId');
            $newClient->client_address = $request->input('address');
            $newClient->save();


            $client = DB::table('clients')
                ->where('client_identification', $request->input('clientId'))
                ->first();
        }


        $quote = new Quote();
        $quote->quote_client_id = $client->id;


        $quote->quote_estimated_time = $request->input('estimatedHours');
        $quote->quote_helpers = $request->input('numAssistants');
        $quote->quote_helper_payday = $request->input('assistantSalary');
        $quote->quote_supervisor_payday = $request->input('supervisorFee');
        $quote->quote_other_costs = $request->input('otherCosts');


        $quote->quote_work_total =
            (($quote->quote_helper_payday / 8) * $quote->quote_estimated_time) +
            (($quote->quote_supervisor_payday / 8) * $quote->quote_estimated_time);


        $quote->quote_material_total = 0;
        $quote->quote_total = 0;
        $quote->quote_expiration_date = "2026-02-17";
        $quote->save();


        $productsJson = $request->input('products');
        $products = json_decode($productsJson, true);

        $materialTotal = 0;
        foreach ($products as $p) {

            $materialTotal += ($p['quantity'] * $p['price']);
        }


        $quote->quote_material_total = $materialTotal;
        $quote->quote_total = $quote->quote_work_total + $quote->quote_other_costs + $quote->quote_material_total;
        $quote->save();


        foreach ($products as $p) {
            $materialName = $p['id'];



            $material = DB::table('products')
                ->where('prod_name', $materialName)
                ->first();

            if (!$material) {
                abort(404, "El material '{$materialName}' NOT FOUND.");

            } else {
                $materialId = $material->id;
            }

            DB::table('quote_materials')->insert([
                'quote_id' => $quote->id,
                'product_id' => $materialId, // ID real de la tabla
                'quantity' => $p['quantity'],
                'total_price' => $p['quantity'] * $p['price'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return response()->json([
            'message' => 'Cotización creada correctamente',
            'quote_id' => $quote->id,
            'material_total' => $quote->quote_material_total,
            'quote_total' => $quote->quote_total
        ], 201);
    }


    public function export(Request $request){

        $quote = DB::table('quote_client')->where('id',$request->quote) ->get();
        $detail = DB::table('quote_detail')->where('quote_id',$request->quote)->get();
        echo ($quote);
        $data = [
            'title' => 'Cotización',
            'content' => 'Este es un ejemplo de cómo generar un PDF con Laravel.',
            'logo'=>public_path('Images/logo/logo.png'),
            'quote' => $quote,
            'detail' => $detail
        ];

        // Generar el PDF a partir de la vista
        $pdf = PDF::loadView('pdf', $data);

        // Retornar el PDF directamente al navegador
        return $pdf->stream('archivo_generado.pdf');

        // O guardar el archivo en el sistema de archivos
        // return $pdf->download('archivo_generado.pdf');
    }
}
