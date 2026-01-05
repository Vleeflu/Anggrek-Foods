<?php

namespace App\Exports;

use App\Models\HppCalculation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;

class HppExport
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function download($filename = 'riwayat-hpp.xlsx')
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $headers = [
            'Tanggal',
            'Produk',
            'Kategori',
            'Porsi',
            'HPP/Porsi',
            'Total HPP',
            'User'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        // Data
        $calculations = $this->query->get();
        $row = 2;

        foreach ($calculations as $calc) {
            $sheet->setCellValue('A' . $row, $calc->created_at->format('d/m/Y H:i'));
            $sheet->setCellValue('B' . $row, $calc->product->name);
            $sheet->setCellValue('C' . $row, $calc->product->category->name);
            $sheet->setCellValue('D' . $row, $calc->portions);
            $sheet->setCellValue('E' . $row, 'Rp ' . number_format($calc->hpp_per_portion, 0, ',', '.'));
            $sheet->setCellValue('F' . $row, 'Rp ' . number_format($calc->total_hpp, 0, ',', '.'));
            $sheet->setCellValue('G' . $row, $calc->user->name);
            $row++;
        }

        // Create writer and download
        $writer = new Xlsx($spreadsheet);

        $temp_file = tempnam(sys_get_temp_dir(), 'hpp');
        $writer->save($temp_file);

        return Response::download($temp_file, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
