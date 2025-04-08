<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvExportService
{
    /**
     * Export solicitations to CSV.
     *
     * @param Collection $solicitations
     * @return StreamedResponse
     */
    public static function export(Collection $solicitations): StreamedResponse
    {
        $filename = 'solicitations_'.formatDateTimeToLocal(now()).'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = [
            'ID',
            'Título',
            'Descrição',
            'Categoria',
            'Data de Criação',
            'Solicitante',
            'Status',
        ];

        $callback = function () use ($solicitations, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($solicitations as $solicitation) {
                fputcsv($file, [
                    $solicitation->id,
                    $solicitation->title,
                    $solicitation->description,
                    $solicitation->category,
                    $solicitation->created_at->format('d/m/Y H:i:s'),
                    $solicitation->user->name,
                    $solicitation->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}
