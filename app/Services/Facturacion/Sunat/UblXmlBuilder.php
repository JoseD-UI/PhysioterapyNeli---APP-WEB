<?php

namespace App\Services\Facturacion\Sunat;

use App\Models\Facturacion\FacturacionComprobante;

class UblXmlBuilder
{
    public function generar(FacturacionComprobante $comprobante): string
    {
        // Aquí irá UBL 2.1 real
        return "<xml>UBL {$comprobante->id}</xml>";
    }
}
