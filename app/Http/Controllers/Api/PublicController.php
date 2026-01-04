<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clinico\TipoServicio;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Listado público de Servicios
     * GET /api/v1/public/services
     */
    public function services()
    {
        $services = TipoServicio::where('activo', 1)
            ->select('tipo_id', 'nombre', 'descripcion', 'precio', 'imagen_url')
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'data' => $services
        ]);
    }
}
