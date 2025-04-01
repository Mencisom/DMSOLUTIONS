<?php

namespace App\Http\Controllers;

use App\Exports\PlantillaProductosExport;
use App\Imports\ProductsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class ProductController
{
    public function index()
    {
        $products = DB::table('products')->get();
        return view('products', ['products' => $products]);
    }

    public function show()
    {
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

    public function descargarPlantilla()
    {
        return Excel::download(new PlantillaProductosExport, 'plantilla_productos.xlsx');
    }

//    public function upload(Request $request)
//    { // Validar que el archivo es un Excel
//        $request->validate([
//            'archivo' => 'required|mimes:xlsx,csv',
//        ]);
//
//        try {
//            // Procesar la importación del archivo
//            $import = new ProductsImport();
//            Excel::import($import, $request->file('archivo'));
//
//            // Obtener los productos no insertados (duplicados)
//            $productosNoInsertados = $import->getErrores();
//
//            return response()->json([
//                'mensaje' => 'Importación completada.',
//                'productos_no_insertados' => $productosNoInsertados,
//            ]);
//        } catch (\Exception $e) {
//            return response()->json([
//                'mensaje' => 'Error al importar el archivo.',
//                'error' => $e->getMessage(),
//            ], 500);
//        }
//    }
    public function upload(Request $request)
    {
        // Validar que el archivo es un Excel
        $request->validate([
            'archivo' => 'required|mimes:xlsx,csv',
        ]);

        try {
            // Obtener el archivo
            $file = $request->file('archivo');

            // Ruta de almacenamiento segura
            $destinationPath = storage_path('app/temp');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Guardar el archivo
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);

            // Ruta final del archivo
            $fullPath = "{$destinationPath}/{$fileName}";

            // Verificar si el archivo realmente existe
            if (!file_exists($fullPath)) {
                throw new \Exception("Error: el archivo no se guardó en {$fullPath}");
            }

            // Procesar la importación del archivo
            $import = new ProductsImport($fullPath);
            Excel::import($import, $fullPath);

            // Eliminar el archivo después de la importación
            unlink($fullPath);

            // Obtener productos no insertados
            $productosNoInsertados = $import->getErrores();

            return response()->json([
                'mensaje' => 'Importación completada.',
                'productos_no_insertados' => $productosNoInsertados,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al importar el archivo.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


}
