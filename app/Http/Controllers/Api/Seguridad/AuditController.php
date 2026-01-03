<?php

namespace App\Http\Controllers\Api\Seguridad;

use App\Http\Controllers\Controller;
use App\Models\Seguridad\AuditLog;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AuditController extends Controller
{
    /**
     * LISTADO DE AUDITORÍA (PAGINADO + FILTROS)
     */
    public function index(Request $request)
    {
        $query = AuditLog::query();

        // 🔎 Filtros opcionales
        if ($request->filled('tabla')) {
            $query->where('tabla_nombre', $request->tabla);
        }

        if ($request->filled('operacion')) {
            $query->where('operacion', $request->operacion);
        }

        if ($request->filled('usuario_id')) {
            $query->where('usuario_id', $request->usuario_id);
        }

        if ($request->filled('desde')) {
            $query->whereDate('fecha', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('fecha', '<=', $request->hasta);
        }

        return response()->json(
            $query
                ->orderByDesc('fecha')
                ->paginate(20)
        );
    }

    /**
     * EXPORTAR AUDITORÍA A CSV
     */
    public function exportarCsv(Request $request): StreamedResponse
    {
        $fileName = 'auditoria_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($request) {

            $handle = fopen('php://output', 'w');

            // 🧾 Cabecera CSV
            fputcsv($handle, [
                'ID',
                'Esquema',
                'Tabla',
                'Operacion',
                'Registro ID',
                'Usuario ID',
                'Fecha',
                'Descripcion'
            ]);

            $query = AuditLog::query();

            // Reusar filtros
            if ($request->filled('tabla')) {
                $query->where('tabla_nombre', $request->tabla);
            }

            if ($request->filled('operacion')) {
                $query->where('operacion', $request->operacion);
            }

            if ($request->filled('usuario_id')) {
                $query->where('usuario_id', $request->usuario_id);
            }

            $query->orderBy('fecha')
                ->chunk(500, function ($logs) use ($handle) {

                    foreach ($logs as $log) {
                        fputcsv($handle, [
                            $log->audit_id,
                            $log->esquema,
                            $log->tabla_nombre,
                            $log->operacion,
                            $log->registro_id,
                            $log->usuario_id,
                            $log->fecha,
                            $log->descripcion,
                        ]);
                    }
                });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
