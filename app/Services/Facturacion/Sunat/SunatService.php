<?php

namespace App\Services\Facturacion\Sunat;

use App\Repositories\Facturacion\DocumentoSunatRepository;
use App\Models\Facturacion\FacturacionComprobante;
use Illuminate\Support\Facades\DB;
use Exception;

class SunatService
{
    public function __construct(
        protected DocumentoSunatRepository $repository
    ) {}

    /* =====================================================
     | ENVIAR COMPROBANTE A SUNAT
     ===================================================== */
    public function enviarComprobante(string $comprobanteId)
    {
        return DB::transaction(function () use ($comprobanteId) {

            $comprobante = FacturacionComprobante::findOrFail($comprobanteId);

            // 0️⃣ Validación: ¿ya fue aceptado?
            if ($this->repository->yaAceptado($comprobante->id)) {
                throw new Exception(
                    'El comprobante ya fue aceptado por SUNAT'
                );
            }

            // 1️⃣ Generar XML (UBL 2.1)
            $xml = $this->generarXml($comprobante);

            // 2️⃣ Registrar envío
            $this->repository->marcarEnviado(
                $comprobante->id,
                $xml
            );

            // 3️⃣ Enviar a SUNAT (Client SOAP/REST)
            $respuesta = $this->enviarASunat($xml);

            // 4️⃣ Registrar respuesta SUNAT (CDR)
            return $this->repository->registrarRespuesta(
                $comprobante->id,
                [
                    'raw'     => $respuesta->cdrXml ?? null,
                    'estado'  => $respuesta->estado ?? 'ERROR',
                    'mensaje' => $respuesta->mensaje ?? null,
                ]
            );
        });
    }

    /* =====================================================
     | CONSULTAR ESTADO SUNAT
     ===================================================== */
    public function consultarEstado(string $comprobanteId)
    {
        return $this->repository->buscarPorComprobante(
            $comprobanteId
        );
    }

    /* =====================================================
     | PLACEHOLDERS (YA TIENES BUILDER Y CLIENT)
     ===================================================== */

    private function generarXml(
        FacturacionComprobante $comprobante
    ): string {
        // 🔜 Xml/UblXmlBuilder
        return "<xml>COMPROBANTE {$comprobante->id}</xml>";
    }

    private function enviarASunat(string $xml): object
    {
        // 🔜 Client/SunatClient (SOAP / REST)
        return (object) [
            'estado'  => 'ACEPTADO', // ACEPTADO | RECHAZADO | OBSERVADO
            'mensaje' => 'Documento aceptado por SUNAT',
            'cdrXml'  => '<cdr>OK</cdr>',
        ];
    }
}
