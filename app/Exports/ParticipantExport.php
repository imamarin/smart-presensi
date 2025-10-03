<?php

namespace App\Exports;

use App\Models\Participant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ParticipantExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnWidths, WithEvents
{


    public function collection()
    {
        return Participant::select('no_induk', 'nama', 'id_kartu', 'no_hp', 'alamat')->get();
    }

    public function headings(): array
    {
        return ['No Induk', 'Nama', 'ID Kartu', 'Nomor HP', 'Alamat'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Gaya untuk header
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ],
        ];
    }

    public function title(): string
    {
        return 'Data Peserta';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 25,
            'C' => 20,
            'D' => 20,
            'E' => 60,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $dataRowCount = Participant::count() + 1;

                // Border all cells
                $sheet->getStyle("A1:E{$dataRowCount}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                // Background warna header
                $sheet->getStyle('A1:E1')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFDBCC8F'], // warna emas lembut
                    ],
                    'font' => [
                        'color' => ['argb' => 'FF000000'],
                        'bold' => true,
                    ],
                ]);

                // Warna baris selang-seling (striped rows)
                for ($row = 2; $row <= $dataRowCount; $row++) {
                    if ($row % 2 === 0) {
                        $sheet->getStyle("A{$row}:E{$row}")->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['argb' => 'FFF2F2F2'], // abu muda
                            ],
                        ]);
                    }
                }
            },
        ];
    }
}
