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
    protected $date1;
    protected $date2;

    public function __construct($groupId, $groupName, $date1, $date2)
    {
        $this->groupId = $groupId;
        $this->groupName = $groupName;
        $this->date1 = $date1;
        $this->date2 = $date2;
    }

    public function collection()
    {
        $group = Group::find($this->groupId);

        if (!$group) {
            return response()->json('Group not found', 404);
        }

        // Tentukan range waktu dari tanggal input
        $startDate = Carbon::parse($this->date1)->startOfDay();
        $endDate   = Carbon::parse($this->date2)->endOfDay();

        // Ambil semua presensi dalam periode untuk grup terkait
        $presensi = Presensi::with(['participant'])
            ->whereHas('participant.groupParticipants.group', function($query) use ($group) {
                $query->where('id_group', $group->id);
            })
            ->whereBetween('waktu_masuk', [$startDate, $endDate])
            ->get();

        // Hitung total hari kerja dalam range tanggal (tidak termasuk Minggu)
        $totalHari = 0;
        $periode = CarbonPeriod::create($startDate, $endDate);
        foreach ($periode as $day) {
            if (!$day->isSunday()) {
                $totalHari++;
            }
        }

        // Ambil tanggal libur yang berkaitan dengan grup dalam periode ini
        $totalLibur = 0;
        $WaktuLiburGroup = WaktuLibur::whereHas('groupLibur', function ($query) use ($group) {
                $query->where('id_group', $group->id);
            })
            ->where(function($query) use ($startDate, $endDate) {
                // hanya libur yang bersinggungan dengan range tanggal
                $query->whereBetween('tanggal_mulai', [$startDate, $endDate])
                    ->orWhereBetween('tanggal_akhir', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('tanggal_mulai', '<=', $startDate)
                            ->where('tanggal_akhir', '>=', $endDate);
                    });
            })
            ->get();

        // Hitung total hari libur dalam periode
        foreach ($WaktuLiburGroup as $libur) {
            $mulai = Carbon::parse($libur->tanggal_mulai);
            $akhir = Carbon::parse($libur->tanggal_akhir);

            $periodeLibur = CarbonPeriod::create($mulai, $akhir);
            foreach ($periodeLibur as $hari) {
                if ($hari->between($startDate, $endDate) && !$hari->isSunday()) {
                    $totalLibur++;
                }
            }
        }

        // Ambil daftar peserta unik yang memiliki presensi
        $participants = $presensi->pluck('participant')->unique('id')->values();

        // Hitung rekap per peserta
        $dataPresensi = $participants->map(function ($participant) use ($presensi, $totalHari, $totalLibur) {
            $presensiPeserta = $presensi->where('id_participant', $participant->id);

            $totalMasuk = $presensiPeserta->count();
            $totalTelat = $presensiPeserta->where('status_terlambat', true)->count();
            $totalTidakCO = $presensiPeserta->where('status_check_out', false)->count();

            // Hitung total jam kerja (jam_keluar - jam_masuk)
            $totalJamKerja = $presensiPeserta->sum(function ($item) {
                if ($item->waktu_keluar && $item->waktu_masuk) {
                    return Carbon::parse($item->waktu_masuk)->diffInHours(Carbon::parse($item->waktu_keluar));
                }
                return 0;
            });

            return [
                "nip" => $participant->no_induk,
                "participant" => $participant->nama,
                "totalHariKerja" => $totalHari,
                "totalLibur" => $totalLibur,
                "totalJamKerja" => $totalJamKerja,
                "totalMasuk" => $totalMasuk,
                "totalTelat" => $totalTelat,
                "totalTidakMasuk" => max(0, $totalHari - $totalLibur - $totalMasuk),
                "totalTidakCheckOut" => $totalTidakCO,
            ];
        });

        // Kembalikan hasil dalam bentuk Collection
        return new Collection($dataPresensi);
    }

    public function headings(): array
    {
        return [
            ['Recap Presensi Grup ' . $this->groupName], // Judul
            [], // baris kosong setelah judul
            ['NIP','Nama Peserta', 'Total Hari', 'Total Libur', 'Total Jam Kerja', 'Total Masuk', 'Total Terlambat', 'Total Tidak Masuk', 'Total Tidak Check-Out']
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'C' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_NUMBER,
            'I' => NumberFormat::FORMAT_NUMBER,
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
            'I' => 20,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Merge untuk judul
                $event->sheet->mergeCells('A1:I1');

                // Border semua data mulai dari baris ke-4 (data dimulai dari row 4)
                $highestRow = $event->sheet->getHighestRow();
                $event->sheet->getStyle("A3:I$highestRow")->applyFromArray([
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
                $event->sheet->getStyle("A3:I3")->applyFromArray([
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
