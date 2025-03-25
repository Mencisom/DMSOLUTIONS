<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Quote;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class QuoteController
{
    public function index()
    {
        $quotes = DB::table('quote_client')->get();
        return view('quotes', ['quotes' => $quotes]);
    }

//    public function store(Request $request)
//    {
//        try {
//            $client = DB::table('clients')->where('client_identification', $request->input('clientId'))->first();
//
//            if (!$client) {
//
//                $newClient = new Client();
//                $newClient->client_name = $request->input('clientName');
//                $newClient->client_ph = $request->input('phone');
//                $newClient->client_email = $request->input('email');
//                $newClient->client_identification = $request->input('clientId');
//                $newClient->client_address = $request->input('address');
//                $newClient->save();
//
//
//                $client = DB::table('clients')
//                    ->where('client_identification', $request->input('clientId'))
//                    ->first();
//            }
//        }catch(QueryException $e) {
//            if ($e->errorInfo[1] == 1062) { // Código 1062 = entrada duplicada
//                preg_match("/Duplicate entry '(.+?)' for key '(.+?)'/", $e->getMessage(), $matches);
//                $valorDuplicado = $matches[1] ?? 'Valor desconocido';
//                $campoDuplicado = $matches[2] ?? 'Campo desconocido';
//
//                return back()->with('error', "Error: El valor '$valorDuplicado' ya existe en el campo '$campoDuplicado'.");
//            } return back()->with('error', 'Error al guardar el cliente: ' . $e->getMessage());
//        }
//            $quote = new Quote();
//            $quote->quote_client_id = $client->id;
//
//
//            $quote->quote_estimated_time = $request->input('estimatedHours');
//            $quote->quote_helpers = $request->input('numAssistants');
//            $quote->quote_helper_payday = $request->input('assistantSalary');
//            $quote->quote_supervisor_payday = $request->input('supervisorFee');
//            $quote->quote_other_costs = $request->input('otherCosts');
//
//
//            $quote->quote_work_total =
//                (($quote->quote_helper_payday / 8) * $quote->quote_estimated_time) +
//                (($quote->quote_supervisor_payday / 8) * $quote->quote_estimated_time);
//
//
//            $quote->quote_material_total = 0;
//            $quote->quote_total = 0;
//            $quote->quote_expiration_date = "2026-02-17";
//            $quote->save();
//
//
//            $productsJson = $request->input('products');
//            $products = json_decode($productsJson, true);
//
//            $materialTotal = 0;
//            foreach ($products as $p) {
//
//                $materialTotal += ($p['quantity'] * $p['price']);
//            }
//
//
//            $quote->quote_material_total = $materialTotal;
//            $quote->quote_total = $quote->quote_work_total + $quote->quote_other_costs + $quote->quote_material_total;
//            $quote->save();
//
//
//            foreach ($products as $p) {
//                $materialName = $p['id'];
//
//
//                $material = DB::table('products')
//                    ->where('prod_name', $materialName)
//                    ->first();
//
//                if (!$material) {
//                    abort(404, "El material '{$materialName}' NOT FOUND.");
//
//                } else {
//                    $materialId = $material->id;
//                }
//
//                DB::table('quote_materials')->insert([
//                    'quote_id' => $quote->id,
//                    'product_id' => $materialId, // ID real de la tabla
//                    'quantity' => $p['quantity'],
//                    'total_price' => $p['quantity'] * $p['price'],
//                    'created_at' => now(),
//                    'updated_at' => now()
//                ]);
//            }
//
//
//        return redirect()->back();
//    }
    public function store(Request $request)
    {
        try {
            // Verificar si el cliente ya existe
            $client = DB::table('clients')->where('client_identification', $request->input('clientId'))->first();

            if (!$client) {
                // Crear nuevo cliente
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

            // Crear cotización
            $quote = new Quote();
            $quote->quote_client_id = $client->id;
            $quote->quote_estimated_time = $request->input('estimatedHours');
            $quote->quote_helpers = $request->input('numAssistants');
            $quote->quote_helper_payday = $request->input('assistantSalary');
            $quote->quote_supervisor_payday = $request->input('supervisorFee');
            $quote->quote_other_costs = $request->input('otherCosts');

            // Calcular costos de trabajo
            $quote->quote_work_total =
                (($quote->quote_helper_payday / 8) * $quote->quote_estimated_time) +
                (($quote->quote_supervisor_payday / 8) * $quote->quote_estimated_time);

            $quote->quote_material_total = 0;
            $quote->quote_total = 0;
            $quote->quote_expiration_date = "2026-02-17";
            $quote->save();

            // Manejo de productos
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
                    return back()->with('error', "El material '{$materialName}' NO EXISTE. Verifica los datos.");
                } else {
                    $materialId = $material->id;
                }

                DB::table('quote_materials')->insert([
                    'quote_id' => $quote->id,
                    'product_id' => $materialId,
                    'quantity' => $p['quantity'],
                    'total_price' => $p['quantity'] * $p['price'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            return redirect()->back()->with('success', 'Cotización creada correctamente.');

        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                preg_match("/Duplicate entry '(.+?)' for key '(.+?)'/", $e->getMessage(), $matches);
                $valorDuplicado = $matches[1] ?? 'Valor desconocido';
                return back()->with('error', "Error: El valor '$valorDuplicado' ya existe");
            }

            return back()->with('error', 'Error en la base de datos: ' . $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', 'Error inesperado: ' . $e->getMessage());
        }
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
