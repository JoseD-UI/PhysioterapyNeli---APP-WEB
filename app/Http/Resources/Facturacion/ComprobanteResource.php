<?php

namespace App\Http\Resources\Facturacion;

use Illuminate\Http\Resources\Json\JsonResource;

class ComprobanteResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'tipo_comprobante' => $this->tipo_comprobante,
            'serie'            => $this->serie,
            'correlativo'      => str_pad($this->correlativo, 8, '0', STR_PAD_LEFT),

            'cliente' => [
                'cliente_id'       => $this->cliente_id,
                'ruc'              => $this->ruc_cliente,
                'razon_social'     => $this->razon_social,
                'direccion_fiscal' => $this->direccion_fiscal,
            ],

            'totales' => [
                'subtotal' => (float) $this->subtotal,
                'igv'      => (float) $this->igv,
                'total'    => (float) $this->total,
            ],

            'estado' => [
                'interno'          => $this->estado,               // emitida | anulada
                'fecha_anulacion'  => $this->fecha_anulacion,
                'motivo_anulacion' => $this->motivo_anulacion,
            ],

            'sunat' => $this->whenLoaded('documentoSunat', function () {
                return [
                    'estado'        => $this->documentoSunat->cdr_estado,
                    'descripcion'   => $this->documentoSunat->cdr_descripcion,
                    'fecha_envio'   => $this->documentoSunat->fecha_envio,
                    'fecha_respuesta'=> $this->documentoSunat->fecha_respuesta,
                ];
            }),

            'detalles' => ComprobanteDetalleResource::collection(
                $this->whenLoaded('detalles')
            ),

            'pagos' => PagoResource::collection(
                $this->whenLoaded('pagos')
            ),

            'fechas' => [
                'emision' => $this->fecha_emision,
                'creado'  => $this->created_at,
            ],
        ];
    }
}
