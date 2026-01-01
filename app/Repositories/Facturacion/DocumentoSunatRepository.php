<?php

namespace App\Repositories\Facturacion;

use App\Models\Facturacion\DocumentoSunat;

class DocumentoSunatRepository
{
    /* =====================================================
     | UPSERT (IDEMPOTENTE POR COMPROBANTE)
     ===================================================== */
    public function upsertPorComprobante(
        string $comprobanteId,
        array $data
    ): DocumentoSunat {
        return DocumentoSunat::updateOrCreate(
            ['comprobante_id' => $comprobanteId],
            $data
        );
    }

    /* =====================================================
     | MARCAR ENVÍO A SUNAT
     ===================================================== */
    public function marcarEnviado(
        string $comprobanteId,
        string $xml
    ): DocumentoSunat {
        return $this->upsertPorComprobante($comprobanteId, [
            'xml_enviado' => $xml,
            'fecha_envio' => now(),
        ]);
    }

    /* =====================================================
     | REGISTRAR RESPUESTA SUNAT (CDR)
     ===================================================== */
    public function registrarRespuesta(
        string $comprobanteId,
        array $respuesta
    ): DocumentoSunat {
        return $this->upsertPorComprobante($comprobanteId, [
            'xml_respuesta'   => $respuesta['raw'] ?? null,
            'cdr_estado'      => $respuesta['estado'] ?? 'ERROR',
            'cdr_descripcion' => $respuesta['mensaje'] ?? null,
            'fecha_respuesta' => now(),
        ]);
    }

    /* =====================================================
     | CONSULTAS
     ===================================================== */
    public function buscarPorComprobante(
        string $comprobanteId
    ): ?DocumentoSunat {
        return DocumentoSunat::where(
            'comprobante_id',
            $comprobanteId
        )->first();
    }

    public function obtenerPorComprobante(
        string $comprobanteId
    ): DocumentoSunat {
        return DocumentoSunat::where(
            'comprobante_id',
            $comprobanteId
        )->firstOrFail();
    }

    /* =====================================================
     | VALIDACIONES DE ESTADO SUNAT
     ===================================================== */
    public function yaAceptado(string $comprobanteId): bool
    {
        return DocumentoSunat::where('comprobante_id', $comprobanteId)
            ->where('cdr_estado', 'ACEPTADO')
            ->exists();
    }

    public function yaRechazado(string $comprobanteId): bool
    {
        return DocumentoSunat::where('comprobante_id', $comprobanteId)
            ->where('cdr_estado', 'RECHAZADO')
            ->exists();
    }
}
