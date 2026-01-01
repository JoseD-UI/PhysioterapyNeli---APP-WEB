<?php

namespace App\Http\Resources\Facturacion;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentoSunatResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            /* ===================== IDENTIFICACIÓN ===================== */
            'documento_sunat_id' => $this->documento_sunat_id,
            'comprobante_id'     => $this->comprobante_id,

            /* ===================== COMPROBANTE ===================== */
            'tipo_comprobante'   => $this->tipo_comprobante,
            'serie'              => $this->serie,
            'correlativo'        => str_pad(
                (string) $this->correlativo,
                8,
                '0',
                STR_PAD_LEFT
            ),

            /* ===================== ESTADO SUNAT ===================== */
            'sunat' => [
                'estado'       => $this->cdr_estado,
                'descripcion'  => $this->cdr_descripcion,
            ],

            /* ===================== FECHAS ===================== */
            'fechas' => [
                'envio'      => $this->fecha_envio,
                'respuesta'  => $this->fecha_respuesta,
            ],

            /* ===================== AUDITORÍA ===================== */
            'metadata' => [
                'creado_en'   => $this->created_at,
                'actualizado' => $this->updated_at,
            ],
        ];
    }
}
