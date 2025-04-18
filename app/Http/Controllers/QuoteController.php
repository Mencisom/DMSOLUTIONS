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

    public function edit($id)
    {

        $quote = Quote::with('client','products')->find($id);

        if (!$quote) {
            return response()->json(['error' => 'Cotización no encontrada'], 404);
        }

        return response()->json([

            'id' => $quote->id,
            'client_name' => $quote->client,
            'total' => $quote->quote_total ?? 0,
            'quote_estimated_time' => $quote->quote_estimated_time ?? 0,
            'quote_helpers' => $quote->quote_helpers ?? 0,
            'quote_helper_payday' => $quote->quote_helper_payday ?? 0,
            'quote_supervisor_payday' => $quote->quote_supervisor_payday ?? 0,
            'quote_other_costs' => $quote->quote_other_costs ?? 0,
            'products' => $quote->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'prod_name' => $product->prod_name,
                    'prod_reference' => $product->prod_reference,
                    'prod_des' => $product->prod_des,
                    'provider_id' => $product->provider_id,
                    'prod_status' => $product->prod_status,
                    'prod_price_purchase' => $product->prod_price_purchase,
                    'prod_price_sales' => $product->prod_price_sales,
                    'prod_image' => $product->prod_image,
                    'created_at' => $product->created_at,
                    'updated_at' => $product->updated_at,
                    'quantity' => $product->pivot->quantity ?? 0,
                    'total_price' => $product->pivot->total_price ?? 0,
                ];
            }),
        ]);
    }

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
            $quote->quote_subtotal = 0;
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
            $quote->quote_subtotal = $quote->quote_work_total + $quote->quote_other_costs + $quote->quote_material_total;
            $quote->save();

            foreach ($products as $p) {
                $materialName = $p['id'];
                $materialprice= $p['price'];
                $materialprovider = $p['provider'];

                $material = DB::table('products')
                    ->where('prod_name', $materialName)
                    ->where('provider_id', $materialprovider)
                    ->where('prod_price_sales', $materialprice)
                    ->first();

                if (!$material) {
                    return back()->with('error', "El material '{$materialName}' NO EXISTE. Verifica los datos.");
                } else {
                    $materialId = $material->id;
                }

                DB::table('quote_materials')->insert([
                    'quote_id' => $quote->id,
                    'product_id' => $materialId,
                    'unit_price' => $p['price'],
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
    public function update(Request $request)
    {
        try {
            $quote = Quote::find($request->input('quoteId'));

            if (!$quote) {
                return back()->with('error', 'Cotización no encontrada.');
            }


            $quote->quote_estimated_time = $request->input('estimatedHours');
            $quote->quote_helpers = $request->input('numAssistants');
            $quote->quote_helper_payday = $request->input('assistantSalary');
            $quote->quote_supervisor_payday = $request->input('supervisorFee');
            $quote->quote_other_costs = $request->input('otherCosts');


            $quote->quote_work_total =
                (($quote->quote_helper_payday / 8) * $quote->quote_estimated_time) +
                (($quote->quote_supervisor_payday / 8) * $quote->quote_estimated_time);

            $quote->quote_material_total = 0;
            $quote->quote_subtotal = 0;
            $quote->quote_expiration_date = "2026-02-17";
            $quote->save();


            $currentProducts = DB::table('quote_materials')
                ->where('quote_id', $quote->id)
                ->get()
                ->keyBy('product_id'); // Indexamos por ID de producto para acceso rápido

            // Procesar la nueva lista de productos
            $productsJson = $request->input('products');
            $newProducts = json_decode($productsJson, true);

            $materialTotal = 0;
            $newProductIds = [];

            foreach ($newProducts as $p) {
                $materialName = $p['id'];
                $materialprice= $p['price'];
                $materialprovider = $p['provider'];

                $material = DB::table('products')
                    ->where('prod_name', $materialName)
                    ->where('provider_id', $materialprovider)
                    ->where('prod_price_sales', $materialprice)
                    ->first();

                if (!$material) {
                    return back()->with('error', "El material '{$materialName}' NO EXISTE. Verifica los datos.");
                }

                $materialId = $material->id;
                $unitPrice = $p['price'];
                $quantity = $p['quantity'];
                $totalPrice = $quantity * $unitPrice;

                $newProductIds[] = $materialId;
                    /// condicional  para mirar si existe
                if (isset($currentProducts[$materialId])) {
                    DB::table('quote_materials')
                        ->where('quote_id', $quote->id)
                        ->where('product_id', $materialId)
                        ->update([
                            'unit_price' => $unitPrice,
                            'quantity' => $quantity,
                            'total_price' => $totalPrice,
                            'updated_at' => now()
                        ]);
                } else {

                    DB::table('quote_materials')->insert([
                        'quote_id' => $quote->id,
                        'product_id' => $materialId,
                        'unit_price' => $unitPrice,
                        'quantity' => $quantity,
                        'total_price' => $totalPrice,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                $materialTotal += $totalPrice;
            }

            // insertar los nuevos productos
            DB::table('quote_materials')
                ->where('quote_id', $quote->id)
                ->whereNotIn('product_id', $newProductIds)
                ->delete();


            $quote->quote_material_total = $materialTotal;
            $quote->quote_subtotal = $quote->quote_work_total + $quote->quote_other_costs + $quote->quote_material_total;
            $quote->save();

            return redirect()->back()->with('success', 'Cotización actualizada correctamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error inesperado: ' . $e->getMessage());
        }
    }

    public function export(Request $request){

        $quote = DB::table('quote_client')->where('id',$request->quote) ->get();
        $detail = DB::table('quote_detail')->where('quote_id',$request->quote)->get();
        $data = [
            'title' => 'Cotización',
            'content' => 'Este es un ejemplo de cómo generar un PDF con Laravel.',
            'logo'=>public_path('Images/logo/logo.png'),
            'quote' => $quote,
            'detail' => $detail
        ];

        $pdf = PDF::loadView('pdf', $data);

        // Retornar el PDF descargado
       // return $pdf->download("Cotización {$quote[0]->client_name}.pdf");
        return $pdf->stream("Cotización {$quote[0]->client_name}.pdf");

    }
    public function destroy($id)
    {
// Buscar la cotización
        $quote = Quote::find($id);

        // Verificar si la cotización existe
        if (!$quote) {
            return redirect()->route('quote')->with('status', 'Cotización no encontrada.');
        }

        if ($quote->project) {
            return redirect()->route('quote')->with('status', 'No se puede eliminar la cotización porque tiene un proyecto asociado.');
        }

        try {
            // Eliminar registros
            DB::table('quote_materials')->where('quote_id', $quote->id)->delete();

            $quote->delete();

            return redirect()->route('quote')->with('status', 'Cotización eliminada correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('quote')->with('error', 'Error al eliminar la cotización: ' . $e->getMessage());
        }
    }

}
