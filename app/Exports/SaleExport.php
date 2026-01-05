<?php

namespace App\Exports;

use App\Models\Sale;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;

class SaleExport
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function download($filename = 'riwayat-penjualan.xlsx')
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $headers = [
            'Tanggal',
            'Produk',
            'Kategori',
            'Qty',
            'Harga Jual',
            'Revenue',
            'Profit',
            'Margin %'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        // Data
        $sales = $this->query->get();
        $row = 2;

        foreach ($sales as $sale) {
            $profitMargin = $sale->total_revenue > 0 ? ($sale->profit / $sale->total_revenue * 100) : 0;

            $sheet->setCellValue('A' . $row, $sale->sale_date->format('d/m/Y'));
            $sheet->setCellValue('B' . $row, $sale->hppCalculation->product->name);
            $sheet->setCellValue('C' . $row, $sale->hppCalculation->product->category->name);
            $sheet->setCellValue('D' . $row, $sale->quantity_sold);
            $sheet->setCellValue('E' . $row, 'Rp ' . number_format($sale->selling_price_used, 0, ',', '.'));
            $sheet->setCellValue('F' . $row, 'Rp ' . number_format($sale->total_revenue, 0, ',', '.'));
            $sheet->setCellValue('G' . $row, 'Rp ' . number_format($sale->profit, 0, ',', '.'));
            $sheet->setCellValue('H' . $row, number_format($profitMargin, 1) . '%');
            $row++;
        }

        // Create writer and download
        $writer = new Xlsx($spreadsheet);

        $temp_file = tempnam(sys_get_temp_dir(), 'sale');
        $writer->save($temp_file);

        return Response::download($temp_file, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
