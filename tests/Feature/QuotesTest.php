<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Quote;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuotesTest extends TestCase
{
    use RefreshDatabase; // Limpia la base de datos después de cada prueba

    /** @test */
    public function la_vista_de_cotizaciones_se_renderiza_correctamente()
    {
        $response = $this->get('/status'); // Ruta de la vista

        $response->assertStatus(200);
    }

}
