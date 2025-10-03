<?php

namespace App\Exports;

use App\Models\Presensi;
use App\Models\Group;
use App\Models\WaktuLibur;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PresensiGroupExport implements FromCollection, WithColumnFormatting, WithHeadings, WithTitle, WithStyles, WithEvents, WithColumnWidths
{
    protected $groupId;
    protected $groupName;

    public function __construct($groupId, $groupName)
    {
        $this->groupId = $groupId;
        $this->groupName = $groupName;
    }

    public function collection()
    {
        $group = Group::find($this->groupId);

        if(!$group){
            return response()->json('Group not found', 404);
        }


        $presensi = Presensi::with('participant', 'shift')
        ->whereHas('participant.groupParticipants.group', function($query) use ($group) {
            $query->where('id_group', $group->id);
        })
        ->whereHas('shift', function($query) {
            $query->whereColumn('tanggal_mulai', '<=', 'presensis.waktu_masuk');
        })
        ->get();

        $shift = $presensi->pluck('shift.tanggal_mulai');
        $today = Carbon::today();
        $totalHari = $shift->map(function ($date) use($today) {
            $tanggalMulai = Carbon::parse($date);
            $totalDay = 0;

             // Buat periode tanggal dari mulai sampai hari ini
            $periode = CarbonPeriod::create($tanggalMulai, $today);

            foreach ($periode as $day) {
                // Cek apakah bukan hari Minggu
                if (!$day->isSunday()) {
                    $totalDay++;
                }
            }
            return $totalDay;
        });
        $totalLibur = 0;
        $WaktuLiburGroup = WaktuLibur::whereHas('groupLibur', function ($query) use($group) {
            $query->where('id_group', $group->id);
        })->get();
        // return response()->json($WaktuLiburGroup);

        foreach ($WaktuLiburGroup as $waktuLibur) {
            $tanggalMulai = Carbon::parse($waktuLibur->tanggal_mulai);
            $tanggalAkhir = Carbon::parse($waktuLibur->tanggal_akhir);

            $diffDays = $tanggalMulai->diffInDays($tanggalAkhir) + 1;
            $totalLibur += $diffDays;
        }


        $presensi_participants = (object) $presensi->pluck('participant')->unique('id');
        // dd($presensi_participants);
        $dataPresensi = $presensi_participants->map(function($data, $index) use($totalHari, $presensi, $totalLibur) {
            $totalMasuk = $presensi->where('id_participant', $data->id)->count();
            $totalTelat = $presensi->where('id_participant', $data->id)->where('status_terlambat', true)->count();
            $totalTidakCO = $presensi->where('id_participant', $data->id)->where('status_check_out', false)->count();
            $JamKerja = $presensi->where('id_participant', $data->id)->map(function($dataPresensi) {
                $waktuMasuk = $dataPresensi->waktu_masuk;
                $waktuKeluar = $dataPresensi->waktu_keluar;
                return [
                    'waktu_masuk' => $waktuMasuk,
                    'waktu_keluar' => $waktuKeluar
                ];
            });
            $totalJamKerja = 0;
            foreach($JamKerja as $jam) {
                $jamMasuk = Carbon::parse($jam['waktu_masuk']);
                $jamKeluar = Carbon::parse($jam['waktu_keluar']);

                $diffMinutes = $jamMasuk->diffInMinutes($jamKeluar) / 60;

                $totalJamKerja += $diffMinutes;
            }


            return [
                "participant" => $data->nama,
                "TotalHari" => $totalHari[$index],
                "totalLibur" => $totalLibur,
                "totalJamKerja" => $totalJamKerja,
                "TotalMasuk" => $totalMasuk,
                "totalTelat" => $totalTelat,
                "totalTidakMasuk" => $totalHari[$index] - $totalMasuk - $totalLibur,
                "totalTidakCheckOut" => $totalTidakCO,
            ];
        })->values();

        return new Collection($dataPresensi);
    }

    public function headings(): array
    {
        return [
            ['Recap Presensi Grup ' . $this->groupName], // Judul
            [], // baris kosong setelah judul
            ['Nama Peserta', 'Total Hari', 'Total Libur', 'Total Jam Kerja', 'Total Masuk', 'Total Terlambat', 'Total Tidak Masuk', 'Total Tidak Check-Out']
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER,
            'C' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function title(): string
    {
        return 'Rekap Grup ' . $this->groupName;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 16]], // Judul
            3 => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']], // Header tabel
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 20,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Merge untuk judul
                $event->sheet->mergeCells('A1:H1');

                // Border semua data mulai dari baris ke-4 (data dimulai dari row 4)
                $highestRow = $event->sheet->getHighestRow();
                $event->sheet->getStyle("A3:H$highestRow")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                    ],
                ]);

                // Styling header background
                $event->sheet->getStyle("A3:H3")->applyFromArray([
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => [
                            'rgb' => 'D9E1F2',
                        ],
                    ],
                ]);
            },
        ];
    }
}
