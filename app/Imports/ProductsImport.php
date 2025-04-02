<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Provider;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;


class ProductsImport implements ToModel//, WithDrawings
{

    private $errores = [];
    private $imagenes = [];

    public function __construct($filePath)
    {
        // Extraer imágenes antes de procesar los productos
        $this->imagenes = $this->procesarExcelConImagenes($filePath);
    }

    public function model(array $row)
    {
        if ($row[0] == 'Nombre del Producto') return null; // Omitir encabezado

        $nombre = trim($row[0]);
        $referencia = trim($row[1]);
        $descripcion = trim($row[2]);
        $proveedor_id = $this->obtenerProveedorId(trim($row[3]));
        $precio = floatval($row[4]);
        $moneda = strtoupper(trim($row[5]));

        if (!$proveedor_id) {
            $this->errores[] = "El proveedor '$row[3]' no existe.";
            return null;
        }

        if (Product::where('prod_reference', $referencia)->exists()) {
            $this->errores[] = "El producto con referencia '$referencia' ya existe.";
            return null;
        }

        // Conversión de moneda si no es COP
        if ($moneda !== 'COP') {
            try {
                $response = Http::get("https://api.exchangerate-api.com/v4/latest/$moneda")->json();
                $tasa = $response['rates']['COP'] ?? null;

                if ($tasa) {
                    $precio *= $tasa;
                } else {
                    $this->errores[] = "No se pudo obtener la tasa de conversión para '$moneda'.";
                }
            } catch (\Exception $e) {
                $this->errores[] = "Error en API de conversión: " . $e->getMessage();
            }
        }

        // Obtener la imagen de la fila actual


        $product = new Product([
            'prod_name' => $nombre,
            'prod_reference' => $referencia,
            'prod_des' => $descripcion,
            'provider_id' => $proveedor_id,
            'prod_status' => '1',
            'prod_price_purchase' => $precio,
            'prod_price_sales' => $precio * 1.2,
            'prod_image' => null,
        ]);
        $product->save();
        //$rowNumber = request()->input('rowNumber', null); // Puedes obtenerlo del request o mapearlo correctamente
        $imagenRuta = $this->imagenes[$referencia] ?? null;

        if ($imagenRuta) {
            $product->prod_image = $imagenRuta;
            $product->save(); // Actualizamos el producto con la imagen
        }

        return $product;
    }

    private function obtenerProveedorId($nombreProveedor)
    {
        return Provider::where('provider_name', $nombreProveedor)->value('id');
    }

    public function getErrores()
    {
        return $this->errores;
    }


    function procesarExcelConImagenes($filePath)
    {

        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $imagenes = [];

        // Recorrer la colección de dibujos (imágenes) en el archivo Excel
        foreach ($worksheet->getDrawingCollection() as $drawing) {
            if ($drawing instanceof \PhpOffice\PhpSpreadsheet\Worksheet\Drawing) {
                // Obtener la ruta de la imagen temporal
                $imagePath = $drawing->getPath();

                // Extraer la celda de la imagen (Ej: "B3")
                $coordinates = $drawing->getCoordinates();
                $rowNumber = preg_replace('/[^0-9]/', '', $coordinates); // Extraer solo el número de fila
                $referencia = $worksheet->getCell('B' . $rowNumber)->getValue(); // Asumimos que la referencia está en la columna B

                // Nombre único para la imagen
                $imageName = 'producto_' . $referencia . '.' . pathinfo($imagePath, PATHINFO_EXTENSION);

                // Definir la ruta donde se guardará la imagen en el almacenamiento público
                $storagePath = 'public/images/' . $imageName;

                // Guardar la imagen en el almacenamiento público
                Storage::disk('public')->put('images/' . $imageName, file_get_contents($imagePath));

                // Ruta accesible públicamente
                $imageUrl = 'storage/images/' . $imageName;

                // Guardar la relación fila -> imagen en la base de datos
                // Ejemplo de actualización o inserción en la base de datos, asumiendo que tienes un modelo Product
//                $producto = Product::find($rowNumber); // Encuentra el producto por el número de fila
//                if ($producto) {
//                    $producto->prod_image = $imageUrl; // Actualiza el campo de la ruta de la imagen
//                    $producto->save(); // Guarda la actualización
//                }

                // Guardar la relación fila -> imagen
//                $imagenes[$rowNumber] = $imageUrl;
                $imagenes[$referencia] = $imageUrl;
            }
        }

        return $imagenes;
    }
}
