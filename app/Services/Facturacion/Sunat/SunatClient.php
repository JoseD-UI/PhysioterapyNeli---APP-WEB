<?php
namespace App\Services\Facturacion\Sunat;

class SunatClient
{
    public function enviar(string $xml): object
    {
        // Aquí va SOAP + certificado
        return (object)[
            'estado'   => 'ACEPTADO',
            'mensaje'  => 'Documento aceptado por SUNAT',
            'cdrXml'   => '<cdr>OK</cdr>',
        ];
    }
}
