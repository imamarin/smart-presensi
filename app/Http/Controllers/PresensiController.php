<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Presensi;
use App\Models\Shift;
use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PresensiGroupExport;
use App\Models\Device;
use App\Models\WaktuLibur;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Crypt;

class PresensiController extends Controller
{
    // Show all presensi records
    public function index(Request $request)
    {
        // $presensis = Presensi::with('participant')->orderBy('created_at', 'desc')->get();
        $groups = Group::all();
        if ($request->group){
            $groupId = $request->input('group');
            $presensis = Presensi::with('participant')
                ->whereHas('participant.groupParticipants', function ($query) use ($groupId) {
                    $query->where('id_group', $groupId);
                })
                ->paginate(10);
        } else if($request->date_filter){
            $date = Carbon::parse($request->date_filter);
            $presensis = Presensi::with('participant')
                ->whereDate('waktu_masuk', $date)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else if ($request->group && $request->date_filter) {
            $groupId = $request->input('group');
            $date = Carbon::parse($request->date_filter);
            $presensis = Presensi::with('participant')
                ->whereHas('participant.groupParticipants', function ($query) use ($groupId) {
                    $query->where('id_group', $groupId);
                })
                ->whereDate('waktu_masuk', $date)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $presensis = Presensi::with('participant')->orderBy('created_at', 'desc')->paginate(10);
        }
        return view('Managment.Presensi.presensis', compact('presensis', 'groups'));
    }

    // Show a single presensi record
    public function show($id)
    {
        $presensi = Presensi::with('participant')->findOrFail($id);
        return view('Managment.Presensi.show', compact('presensi'));
    }

    public function store(Request $request)
    {

        $device = Device::where('device_id', $request->id_device)->first();
        if(!$device || $device->api_key != $request->api_key) {
            return response()->json(['message' => 'Device not found']);
        }

        if($device->status === 'inactive') {
            return response()->json(['message' => 'Device is inactive, please call admin to fix it']);
        }

        $participant = Participant::where('id_kartu', $request->id_kartu)->orWhere('id_fingerprint', $request->id_kartu)->with('jadwalParticipant', 'groupParticipants')->first();

        if (!$participant) {
            return response()->json(['message' => 'Participant not found'], 404);
        }

        // Get the current time and day
        Carbon::setLocale('id');
        $currentTime = Carbon::now();
        $currentDate = $currentTime->translatedFormat(format: 'Y-m-d');
        $currentDay = strtolower($currentTime->translatedFormat('l')); // This will return the day in Bahasa
        $currentHour = $currentTime->translatedFormat(format: 'H:i:s');


        $groupParticipants = $participant->groupParticipants;
        foreach ($groupParticipants as $data) {
            $group = Group::with('groupLiburs')->find($data->id_group);

            foreach ($group->groupLiburs as $dataLibur) {
                $waktuLibur = WaktuLibur::find($dataLibur->id_waktu_libur);

                if ($currentDate >= $waktuLibur->tanggal_mulai && $currentDate <= $waktuLibur->tanggal_akhir) {
                    return response()->json([
                        'message' => 'Hari ini adalah hari libur: ' . $waktuLibur->nama_libur . ', tidak bisa presensi'
                    ], 403);
                }
            }
        }



        $jadwalParticipant = $participant['jadwalParticipant'];
        if (!$jadwalParticipant) {
            return response()->json(['message' => 'Jadwal Participant not found'], 404);
        }

        $shift = $jadwalParticipant->shift;
        if (!$shift) {
            return response()->json(['message' => 'Shift not found'], 404);
        }

        $jamKerja = $shift->jamKerja;
        if (!$jamKerja) {
            return response()->json(['message' => 'Jam Kerja not found'], 404);
        }



        // Check if the participant has already checked in today
        $existingAttendance = $participant->presensi()
            ->whereDate('created_at', Carbon::today())
            ->first();
        // return response()->json($currentHour);
        if ($existingAttendance != null) {
            if($currentHour < $jamKerja->jam_mulai_scan_keluar) {
                return response()->json(['message' => 'Belum masuk jam scan keluar'], 201);
            }

            $participant->presensi()->update([
                'waktu_keluar' => $currentTime,
                'status_check_out' => true,
                'updated_at' => $currentTime, // Set to current time for check-out
            ]);
            return response()->json([
                'message' => 'Presensi Check Out Hari Ini',
                'waktu_keluar' => $currentHour,
                'participant' => $participant->nama,
                'shift' => $jadwalParticipant->shift->nama,
                'updated_at' => $currentTime
            ], 202);
        }else{
            // Check if the current day is in the jadwalParticipant
            $isDayInJadwal = $jadwalParticipant->shift->detailShifts->contains(function ($detailShift) use ($currentDay) {
                return strtolower($detailShift->hari) == $currentDay;
            });
            if (!$isDayInJadwal) {
                return response()->json(['message' => 'Tidak ada jadwal untuk hari ini '.$currentDay], 422);
            }

            if($currentHour < $jamKerja->jam_mulai_scan_masuk) {
                return response()->json(['message' => 'Tidak dalam jam scan'], 422);
            }

            $toleransiMasuk = Carbon::parse($jamKerja->jam_masuk)->addMinutes($jamKerja->toleransi_terlambat)->translatedFormat('H:i:s');
            $jamMasuk = Carbon::parse($jamKerja->jam_masuk);

            if ($currentHour > $toleransiMasuk){
                $participant->presensi()->create([
                    'id_participant' => $participant->id,
                    'waktu_masuk' => $currentTime,
                    'waktu_keluar' => $jamMasuk->addHours($jamKerja->toleransi_check_out),
                    'id_device' => $device->id,
                    'id_shift' => $jadwalParticipant->shift->id,
                    'status_terlambat' => true,
                    'status_check_out' => false
                ]);
            } else {
                $participant->presensi()->create([
                    'id_participant' => $participant->id,
                    'waktu_masuk' => $currentTime,
                    'waktu_keluar' => $jamMasuk->addHours($jamKerja->toleransi_check_out),
                    'id_device' => $device->id,
                    'id_shift' => $jadwalParticipant->shift->id,
                    'status_terlambat' => false,
                    'status_check_out' => false
                ]);
            }

            return response()->json([
                'message' => 'Presensi Presensi Hari Ini '.$toleransiMasuk,
                'waktu_keluar' => $currentHour,
                'participant' => $participant->nama,
                'shift' => $jadwalParticipant->shift->nama,
                'updated_at' => $currentTime
            ], 200);

        }
    }
    
    public function storeFp(Request $request)
    {
        $device = Device::where('device_id', $request->id_device)->first();
    
        if (!$device || $device->api_key != $request->api_key) {
            return response()->json([
                'message' => 'Device not found'
            ], 404);
        }
    
        if ($device->status === 'inactive') {
            return response()->json([
                'message' => 'Device is inactive, please call admin to fix it'
            ], 403);
        }
    
        $results = [];
    
        /*
        |--------------------------------------------------------------------------
        | Timezone
        |--------------------------------------------------------------------------
        | Data dari fingerprint dianggap menggunakan waktu WIB
        */
        $timezone = 'Asia/Jakarta';
    
        Carbon::setLocale('id');
    
        foreach ($request->data as $key => $value) {
    
            try {
    
                /*
                |--------------------------------------------------------------------------
                | Parse timestamp dari fingerprint
                |--------------------------------------------------------------------------
                | Contoh:
                | 2026-08-08T14:18:01
                |
                | Karena fingerprint tidak mengirim timezone,
                | kita anggap timestamp tersebut adalah WIB.
                */
                $currentTime = Carbon::parse(
                    $value['timestamp'],
                    $timezone
                );
    
                /*
                |--------------------------------------------------------------------------
                | Informasi waktu scan
                |--------------------------------------------------------------------------
                */
                $currentDate = $currentTime->format('Y-m-d');
    
                $currentDay = strtolower(
                    $currentTime->translatedFormat('l')
                );
    
                $currentHour = $currentTime->format('H:i:s');
    
    
                /*
                |--------------------------------------------------------------------------
                | Cari participant berdasarkan ID fingerprint
                |--------------------------------------------------------------------------
                */
                $participant = Participant::where(
                    'id_fingerprint',
                    $value['user_id']
                )
                    ->with('jadwalParticipant', 'groupParticipants')
                    ->first();
    
                if (!$participant) {
                    $results[] = [
                        'user_id' => $value['user_id'],
                        'message' => 'Participant not found'
                    ];
    
                    continue;
                }
    
    
                /*
                |--------------------------------------------------------------------------
                | Cek hari libur
                |--------------------------------------------------------------------------
                */
                $isLibur = false;
                $namaLibur = null;
    
                $groupParticipants = $participant->groupParticipants;
    
                foreach ($groupParticipants as $groupParticipant) {
    
                    $group = Group::with('groupLiburs')
                        ->find($groupParticipant->id_group);
    
                    if (!$group) {
                        continue;
                    }
    
                    foreach ($group->groupLiburs as $dataLibur) {
    
                        $waktuLibur = WaktuLibur::find(
                            $dataLibur->id_waktu_libur
                        );
    
                        if (!$waktuLibur) {
                            continue;
                        }
    
                        if (
                            $currentDate >= $waktuLibur->tanggal_mulai &&
                            $currentDate <= $waktuLibur->tanggal_akhir
                        ) {
                            $isLibur = true;
                            $namaLibur = $waktuLibur->nama_libur;
    
                            break 2;
                        }
                    }
                }
    
    
                if ($isLibur) {
    
                    $results[] = [
                        'user_id' => $participant->id_fingerprint,
                        'participant' => $participant->nama,
                        'timestamp' => $currentTime->format('Y-m-d H:i:s'),
                        'message' => 'Hari ini adalah hari libur: '
                            . $namaLibur
                            . ', tidak bisa presensi'
                    ];
    
                    continue;
                }
    
    
                /*
                |--------------------------------------------------------------------------
                | Jadwal Participant
                |--------------------------------------------------------------------------
                */
                $jadwalParticipant = $participant->jadwalParticipant;
    
                if (!$jadwalParticipant) {
    
                    $results[] = [
                        'user_id' => $participant->id_fingerprint,
                        'participant' => $participant->nama,
                        'message' => 'Jadwal Participant not found'
                    ];
    
                    continue;
                }
    
    
                /*
                |--------------------------------------------------------------------------
                | Shift
                |--------------------------------------------------------------------------
                */
                $shift = $jadwalParticipant->shift;
    
                if (!$shift) {
    
                    $results[] = [
                        'user_id' => $participant->id_fingerprint,
                        'participant' => $participant->nama,
                        'message' => 'Shift not found'
                    ];
    
                    continue;
                }
    
    
                /*
                |--------------------------------------------------------------------------
                | Jam Kerja
                |--------------------------------------------------------------------------
                */
                $jamKerja = $shift->jamKerja;
    
                if (!$jamKerja) {
    
                    $results[] = [
                        'user_id' => $participant->id_fingerprint,
                        'participant' => $participant->nama,
                        'message' => 'Jam Kerja not found'
                    ];
    
                    continue;
                }
    
    
                /*
                |--------------------------------------------------------------------------
                | Cek apakah hari ini ada dalam jadwal
                |--------------------------------------------------------------------------
                */
                $isDayInJadwal = $shift->detailShifts->contains(
                    function ($detailShift) use ($currentDay) {
    
                        return strtolower(trim($detailShift->hari))
                            === $currentDay;
                    }
                );
    
                if (!$isDayInJadwal) {
    
                    $results[] = [
                        'user_id' => $participant->id_fingerprint,
                        'participant' => $participant->nama,
                        'timestamp' => $currentTime->format('Y-m-d H:i:s'),
                        'message' => 'Tidak ada jadwal untuk hari ini '
                            . $currentDay
                    ];
    
                    continue;
                }
    
    
                /*
                |--------------------------------------------------------------------------
                | Jam scan masuk
                |--------------------------------------------------------------------------
                */
                $jamMulaiScanMasuk = $jamKerja->jam_mulai_scan_masuk;
                $jamMulaiScanKeluar = $jamKerja->jam_mulai_scan_keluar;
    
                /*
                |--------------------------------------------------------------------------
                | Jam masuk sebenarnya
                |--------------------------------------------------------------------------
                */
                $jamMasuk = Carbon::parse(
                    $currentDate . ' ' . $jamKerja->jam_masuk,
                    $timezone
                );
    
                /*
                |--------------------------------------------------------------------------
                | Toleransi keterlambatan
                |--------------------------------------------------------------------------
                */
                $toleransiMasuk = $jamMasuk->copy()
                    ->addMinutes((int) $jamKerja->toleransi_terlambat);
    
                /*
                |--------------------------------------------------------------------------
                | Cari presensi berdasarkan TANGGAL SCAN
                |--------------------------------------------------------------------------
                |
                | JANGAN menggunakan:
                |
                | whereDate('created_at', Carbon::today())
                |
                | karena data fingerprint bisa merupakan data tanggal sebelumnya.
                |
                */
                $existingAttendance = $participant->presensi()
                    ->whereDate('waktu_masuk', $currentDate)
                    ->first();
    
    
                /*
                |--------------------------------------------------------------------------
                | Jika sudah check-in → proses check-out
                |--------------------------------------------------------------------------
                */
                if ($existingAttendance) {
    
                    if ($currentHour < $jamMulaiScanKeluar) {
    
                        $results[] = [
                            'user_id' => $participant->id_fingerprint,
                            'participant' => $participant->nama,
                            'timestamp' => $currentTime->format('Y-m-d H:i:s'),
                            'message' => 'Belum masuk jam scan keluar'
                        ];
    
                        continue;
                    }
    
    
                    /*
                    |--------------------------------------------------------------------------
                    | Check Out
                    |--------------------------------------------------------------------------
                    */
                    $existingAttendance->waktu_keluar = $currentTime;
                    $existingAttendance->status_check_out = true;
                    $existingAttendance->updated_at = $currentTime;
                    $existingAttendance->save();
    
    
                    $results[] = [
                        'user_id' => $participant->id_fingerprint,
                        'participant' => $participant->nama,
                        'message' => [
                            'message' => 'Presensi Check Out Hari Ini',
                            'waktu_scan' => $currentTime->format('Y-m-d H:i:s'),
                            'waktu_keluar' => $currentTime->format('H:i:s'),
                            'participant' => $participant->nama,
                            'shift' => $shift->nama,
                            'updated_at' => $currentTime->format('Y-m-d H:i:s')
                        ]
                    ];
    
                    continue;
                }
    
    
                /*
                |--------------------------------------------------------------------------
                | Belum ada presensi → Check In
                |--------------------------------------------------------------------------
                */
    
                if ($currentHour < $jamMulaiScanMasuk) {
    
                    $results[] = [
                        'user_id' => $participant->id_fingerprint,
                        'participant' => $participant->nama,
                        'timestamp' => $currentTime->format('Y-m-d H:i:s'),
                        'message' => 'Belum masuk waktu scan'
                    ];
    
                    continue;
                }
    
    
                /*
                |--------------------------------------------------------------------------
                | Tentukan terlambat atau tidak
                |--------------------------------------------------------------------------
                */
                $terlambat = $currentTime->gt($toleransiMasuk);
    
    
                /*
                |--------------------------------------------------------------------------
                | Tentukan estimasi waktu check-out
                |--------------------------------------------------------------------------
                |
                | Catatan:
                | Jika toleransi_check_out adalah MENIT,
                | gunakan addMinutes().
                |
                | Jika field Anda memang menyimpan JAM,
                | gunakan addHours().
                */
                $waktuKeluar = $jamMasuk->copy()
                    ->addHours((int) $jamKerja->toleransi_check_out);
    
    
                /*
                |--------------------------------------------------------------------------
                | Simpan presensi
                |--------------------------------------------------------------------------
                */
                $presensi = $participant->presensi()->create([
                    'id_participant' => $participant->id,
                    'waktu_masuk' => $currentTime,
                    'waktu_keluar' => $waktuKeluar,
                    'id_device' => $device->id,
                    'id_shift' => $shift->id,
                    'status_terlambat' => $terlambat,
                    'status_check_out' => false
                ]);
    
    
                /*
                |--------------------------------------------------------------------------
                | Response
                |--------------------------------------------------------------------------
                */
                $results[] = [
                    'user_id' => $participant->id_fingerprint,
                    'participant' => $participant->nama,
                    'message' => [
                        'message' => 'Presensi Check In Hari Ini',
                        'waktu_scan' => $currentTime->format('Y-m-d H:i:s'),
                        'jam_masuk' => $jamMasuk->format('H:i:s'),
                        'batas_toleransi' => $toleransiMasuk->format('H:i:s'),
                        'terlambat' => $terlambat,
                        'participant' => $participant->nama,
                        'shift' => $shift->nama,
                        'id_presensi' => $presensi->id
                    ]
                ];
    
            } catch (\Throwable $e) {
    
                /*
                |--------------------------------------------------------------------------
                | Jangan hentikan batch jika satu record error
                |--------------------------------------------------------------------------
                */
                $results[] = [
                    'user_id' => $value['user_id'] ?? null,
                    'timestamp' => $value['timestamp'] ?? null,
                    'message' => 'Error processing attendance',
                    'error' => $e->getMessage()
                ];
    
                continue;
            }
        }
    
    
        return response()->json([
            'message' => 'Fingerprint received',
            'data' => $results,
            'status_code' => 200
        ]);
    }

    
    public function storeFp2(Request $request)
    {

        $device = Device::where('device_id', $request->id_device)->first();
        if(!$device || $device->api_key != $request->api_key) {
            return response()->json(['message' => 'Device not found']);
        }

        if($device->status === 'inactive') {
            return response()->json(['message' => 'Device is inactive, please call admin to fix it']);
        }
        
        $results = [];
        foreach ($request->data as $key => $value) {
            # code...
            $waktuScan = date('Y-m-d H:i:s', strtotime($value['timestamp']));
            $participant = Participant::where('id_fingerprint', $value['user_id'])->with('jadwalParticipant', 'groupParticipants')->first();

            if ($participant) {
                // Get the current time and day
                Carbon::setLocale('id');
                $currentTime = Carbon::parse($waktuScan);
                $currentDate = $currentTime->translatedFormat(format: 'Y-m-d');
                $currentDay = strtolower($currentTime->translatedFormat('l')); // This will return the day in Bahasa
                $currentHour = $currentTime->translatedFormat(format: 'H:i:s');


                $isLibur = false;
                $namaLibur = null;

                $groupParticipants = $participant->groupParticipants;
                foreach ($groupParticipants as $data) {
                    $group = Group::with('groupLiburs')->find($data->id_group);

                    foreach ($group->groupLiburs as $dataLibur) {
                        $waktuLibur = WaktuLibur::find($dataLibur->id_waktu_libur);

                        if ($currentDate >= $waktuLibur->tanggal_mulai && $currentDate <= $waktuLibur->tanggal_akhir) {
                            $isLibur = true;
                            $namaLibur = $waktuLibur->nama_libur;
                            break 2; // langsung keluar dari 2 level loop
                        }
                    }
                }

                if ($isLibur) {
                    // simpan hasilnya, tapi tidak hentikan proses batch
                    $results[] = [
                        'user_id' => $participant->id_fingerprint,
                        'participant' => $participant->nama,
                        'message' => 'Hari ini adalah hari libur: ' . $namaLibur . ', tidak bisa presensi'
                    ];
                    continue; // lanjut ke peserta berikutnya
                }


                $jadwalParticipant = $participant['jadwalParticipant'];
                if (!$jadwalParticipant) {
                    // simpan hasilnya, tapi tidak hentikan proses batch
                    $results[] = [
                        'user_id' => $participant->id_fingerprint,
                        'participant' => $participant->nama,
                        'message' => 'Jadwal Participant not found'
                    ];
                    continue; // lanjut ke peserta berikutnya
                }

                $shift = $jadwalParticipant->shift;
                if (!$shift) {
                    // simpan hasilnya, tapi tidak hentikan proses batch
                    $results[] = [
                        'user_id' => $participant->id_fingerprint,
                        'participant' => $participant->nama,
                        'message' => 'Shift not found'
                    ];
                    continue; // lanjut ke peserta berikutnya
                }

                $jamKerja = $shift->jamKerja;
                if (!$jamKerja) {
                     $results[] = [
                        'user_id' => $participant->id_fingerprint,
                        'participant' => $participant->nama,
                        'message' => 'Jam Kerja not found'
                    ];
                    continue; // lanjut ke peserta berikutnya
                }



                // Check if the participant has already checked in today
                $existingAttendance = $participant->presensi()
                    ->whereDate('created_at', Carbon::today())
                    ->first();
                // return response()->json($currentHour);
                if ($existingAttendance != null) {
                    if($currentHour < $jamKerja->jam_mulai_scan_keluar) {
                         $results[] = [
                            'user_id' => $participant->id_fingerprint,
                            'participant' => $participant->nama,
                            'message' => 'Belum masuk jam scan keluar'
                        ];
                        continue;
                    }

                    $existingAttendance->waktu_keluar = $currentTime;
                    $existingAttendance->status_check_out = true;
                    $existingAttendance->updated_at = $currentTime;
                    $existingAttendance->save();
                    
                    // $participant->presensi()->update([
                    //     'waktu_keluar' => $currentTime,
                    //     'status_check_out' => true,
                    //     'updated_at' => $currentTime, // Set to current time for check-out
                    // ])->whereDate('waktu_masuk', Carbon::today()) ;

                    // simpan hasilnya, tapi tidak hentikan proses batch
                    $results[] = [
                        'user_id' => $participant->id_fingerprint,
                        'participant' => $participant->nama,
                        'message' => [
                            'message' => 'Presensi Check Out Hari Ini',
                            'waktu_keluar' => $currentHour,
                            'participant' => $participant->nama,
                            'shift' => $jadwalParticipant->shift->nama,
                            'updated_at' => $currentTime
                        ]
                    ];
                    continue; // lanjut ke peserta berikutnya

                }else{
                    // Check if the current day is in the jadwalParticipant
                    $isDayInJadwal = $jadwalParticipant->shift->detailShifts->contains(function ($detailShift) use ($currentDay) {
                        return strtolower($detailShift->hari) == $currentDay;
                    });

                    if (!$isDayInJadwal) {
                        $results[] = [
                            'user_id' => $participant->id_fingerprint,
                            'participant' => $participant->nama,
                            'message' => 'Tidak ada jadwal untuk hari ini '.$currentDay
                        ];
                        continue; 
                    }

                    if($currentHour < $jamKerja->jam_mulai_scan_masuk) {
                        $results[] = [
                            'user_id' => $participant->id_fingerprint,
                            'participant' => $participant->nama,
                            'message' => 'Tidak dalam jam scan'
                        ];
                        continue;
                    }

                    $toleransiMasuk = Carbon::parse($jamKerja->jam_masuk)->addMinutes($jamKerja->toleransi_terlambat)->translatedFormat('H:i:s');
                    $jamMasuk = Carbon::parse($jamKerja->jam_masuk);

                    if ($currentHour > $toleransiMasuk){
                        $participant->presensi()->create([
                            'id_participant' => $participant->id,
                            'waktu_masuk' => $currentTime,
                            'waktu_keluar' => $jamMasuk->addHours($jamKerja->toleransi_check_out),
                            'id_device' => $device->id,
                            'id_shift' => $jadwalParticipant->shift->id,
                            'status_terlambat' => true,
                            'status_check_out' => false
                        ]);
                    } else {
                        $participant->presensi()->create([
                            'id_participant' => $participant->id,
                            'waktu_masuk' => $currentTime,
                            'waktu_keluar' => $jamMasuk->addHours($jamKerja->toleransi_check_out),
                            'id_device' => $device->id,
                            'id_shift' => $jadwalParticipant->shift->id,
                            'status_terlambat' => false,
                            'status_check_out' => false
                        ]);
                    }

                    // simpan hasilnya, tapi tidak hentikan proses batch
                    $results[] = [
                        'user_id' => $participant->id_fingerprint,
                        'participant' => $participant->nama,
                        'message' => [
                            'message' => 'Presensi Presensi Hari Ini '.$toleransiMasuk,
                            'waktu_keluar' => $currentHour,
                            'participant' => $participant->nama,
                            'shift' => $jadwalParticipant->shift->nama,
                            'updated_at' => $currentTime
                        ]
                    ];

                    continue; // lanjut ke peserta berikutnya

                }
            }
        }
        return response()->json(['message' => 'Fingerprint received', $results, 'status_code' => 200]);
    }

    public function reStoreFp(Request $request)
    {

        $device = Device::where('device_id', $request->id_device)->first();
        if(!$device || $device->api_key != $request->api_key) {
            return response()->json(['message' => 'Device not found']);
        }

        if($device->status === 'inactive') {
            return response()->json(['message' => 'Device is inactive, please call admin to fix it']);
        }
        
        // $sortedData = collect($request->data)
        // ->sortBy('timestamp')
        // ->values(); // reset index
        // return response()->json($request->data);
        $results = [];
        foreach ($request->data as $key => $value) {

            # code...
            $waktuScan = date('Y-m-d H:i:s', strtotime($value['timestamp']));
            $todayDate = date('Y-m-d', strtotime($waktuScan));
            $participant = Participant::where('id_fingerprint', $value['user_id'])->with('jadwalParticipant', 'groupParticipants')->first();

            if ($participant) {
                // Get the current time and day
                Carbon::setLocale('id');
                $currentTime = Carbon::parse($waktuScan);
                $currentDate = $currentTime->translatedFormat(format: 'Y-m-d');
                $currentDay = strtolower($currentTime->translatedFormat('l')); // This will return the day in Bahasa
                $currentHour = $currentTime->translatedFormat(format: 'H:i:s');

                $isLibur = false;
                $namaLibur = null;

                $groupParticipants = $participant->groupParticipants;
                foreach ($groupParticipants as $data) {
                    $group = Group::with('groupLiburs')->find($data->id_group);

                    foreach ($group->groupLiburs as $dataLibur) {
                        $waktuLibur = WaktuLibur::find($dataLibur->id_waktu_libur);

                        if ($currentDate >= $waktuLibur->tanggal_mulai && $currentDate <= $waktuLibur->tanggal_akhir) {
                            $isLibur = true;
                            $namaLibur = $waktuLibur->nama_libur;
                            break 2; // langsung keluar dari 2 level loop
                        }
                    }
                }

                if ($isLibur) {
                    // simpan hasilnya, tapi tidak hentikan proses batch
                    $results[] = [
                        'user_id' => $participant->id_fingerprint,
                        'participant' => $participant->nama,
                        'message' => 'Hari ini adalah hari libur: ' . $namaLibur . ', tidak bisa presensi'
                    ];
                    continue; // lanjut ke peserta berikutnya
                }


                $jadwalParticipant = $participant['jadwalParticipant'];
                if (!$jadwalParticipant) {
                    // simpan hasilnya, tapi tidak hentikan proses batch
                    $results[] = [
                        'user_id' => $participant->id_fingerprint,
                        'participant' => $participant->nama,
                        'message' => 'Jadwal Participant not found'
                    ];
                    continue; // lanjut ke peserta berikutnya
                }

                $shift = $jadwalParticipant->shift;
                if (!$shift) {
                    // simpan hasilnya, tapi tidak hentikan proses batch
                    $results[] = [
                        'user_id' => $participant->id_fingerprint,
                        'participant' => $participant->nama,
                        'message' => 'Shift not found'
                    ];
                    continue; // lanjut ke peserta berikutnya
                }

                $jamKerja = $shift->jamKerja;
                if (!$jamKerja) {
                     $results[] = [
                        'user_id' => $participant->id_fingerprint,
                        'participant' => $participant->nama,
                        'message' => 'Jam Kerja not found'
                    ];
                    continue; // lanjut ke peserta berikutnya
                }



                // Check if the participant has already checked in today
                $existingAttendance = $participant->presensi()
                    ->whereDate('created_at', $currentDate)
                    ->first();
                // return response()->json($currentHour);
                if ($existingAttendance != null) {

                    if($currentHour < $jamKerja->jam_mulai_scan_keluar) {
                         $results[] = [
                            'user_id' => $participant->id_fingerprint,
                            'participant' => $participant->nama,
                            'message' => 'Belum masuk jam scan keluar'
                        ];
                        continue;
                    }

                    $existingAttendance->waktu_keluar = $currentTime;
                    $existingAttendance->status_check_out = true;
                    $existingAttendance->updated_at = $currentTime;
                    $existingAttendance->save();


                    // $participant->presensi()->update([
                    //     'waktu_keluar' => $currentTime,
                    //     'status_check_out' => true,
                    //     'updated_at' => $currentTime, // Set to current time for check-out
                    // ])->whereDate('created_at', $currentDate) ;

                    // simpan hasilnya, tapi tidak hentikan proses batch
                    $results[] = [
                        'user_id' => $participant->id_fingerprint,
                        'participant' => $participant->nama,
                        'message' => [
                            'message' => 'Presensi Check Out Hari Ini',
                            'waktu_keluar' => $currentHour,
                            'participant' => $participant->nama,
                            'shift' => $jadwalParticipant->shift->nama,
                            'updated_at' => $currentTime
                        ]
                    ];

                    continue; // lanjut ke peserta berikutnya

                }else{
                    // Check if the current day is in the jadwalParticipant
                    $isDayInJadwal = $jadwalParticipant->shift->detailShifts->contains(function ($detailShift) use ($currentDay) {
                        return strtolower($detailShift->hari) == $currentDay;
                    });

                    if (!$isDayInJadwal) {
                        $results[] = [
                            'user_id' => $participant->id_fingerprint,
                            'participant' => $participant->nama,
                            'message' => 'Tidak ada jadwal untuk hari ini '.$currentDay
                        ];
                        continue; 
                    }

                    if($currentHour < $jamKerja->jam_mulai_scan_masuk) {
                        $results[] = [
                            'user_id' => $participant->id_fingerprint,
                            'participant' => $participant->nama,
                            'message' => 'Tidak dalam jam scan'
                        ];
                        continue;
                    }

                    $toleransiMasuk = Carbon::parse($jamKerja->jam_masuk)->addMinutes($jamKerja->toleransi_terlambat)->translatedFormat('H:i:s');
                    $jamMasuk = Carbon::parse($todayDate . ' ' . $jamKerja->jam_masuk);

                    if ($currentHour > $toleransiMasuk){
                        $participant->presensi()->create([
                            'id_participant' => $participant->id,
                            'waktu_masuk' => $currentTime,
                            'waktu_keluar' => $jamMasuk->addHours($jamKerja->toleransi_check_out),
                            'id_device' => $device->id,
                            'id_shift' => $jadwalParticipant->shift->id,
                            'status_terlambat' => true,
                            'status_check_out' => false,
                            'created_at' => $waktuScan,
                            'updated_at' => $waktuScan,
                        ]);
                    } else {
                        $participant->presensi()->create([
                            'id_participant' => $participant->id,
                            'waktu_masuk' => $currentTime,
                            'waktu_keluar' => $jamMasuk->addHours($jamKerja->toleransi_check_out),
                            'id_device' => $device->id,
                            'id_shift' => $jadwalParticipant->shift->id,
                            'status_terlambat' => false,
                            'status_check_out' => false,
                            'created_at' => $waktuScan,
                            'updated_at' => $waktuScan,
                        ]);
                    }

                    // simpan hasilnya, tapi tidak hentikan proses batch
                    $results[] = [
                        'user_id' => $participant->id_fingerprint,
                        'participant' => $participant->nama,
                        'message' => [
                            'message' => 'Presensi Presensi Hari Ini '.$toleransiMasuk,
                            'waktu_keluar' => $currentHour,
                            'participant' => $participant->nama,
                            'shift' => $jadwalParticipant->shift->nama,
                            'updated_at' => $currentTime
                        ]
                    ];

                    continue; // lanjut ke peserta berikutnya

                }
            }
        }
        
        return response()->json(['message' => 'Fingerprint received', $results, 'status_code' => 200]);
    }

    // Show the edit form
    public function edit($id)
    {
        $presensi = Presensi::findOrFail($id);
        $participants = Participant::all();
        return view('Managment.Presensi.edit', compact('presensi', 'participants'));
    }

    // Update the presensi record
    public function update(Request $request, $id)
    {
        $presensi = Presensi::findOrFail($id);

        $request->validate([
            'participant_id' => 'required|exists:participants,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'required',
            'jam_pulang' => 'required',
            'status' => 'required'
        ]);

        $presensi->update($request->all());

        return redirect()->route('presensi.index')->with('success', 'Presensi updated successfully.');
    }

    // Delete the presensi record
    public function destroy($id)
    {
        $presensi = Presensi::findOrFail($id);
        $presensi->delete();

        return redirect()->route('presensi.index')->with('success', 'Presensi deleted successfully.');
    }

    public function test(Request $request) {
        $group = Group::find($request->id);

        if(!$group){
            return response()->json('Group not found', 404);
        }


        $presensi = Presensi::with('participant', 'shift')->whereHas('participant.groupParticipants.group', function($query) use ($group) {
            $query->where('id_group', $group->id);
        })->get();
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



        $dataPresensi = $presensi->map(function($data, $index) use($totalHari, $presensi, $totalLibur) {
            $totalMasuk = $presensi->where('id_participant', $data->participant->id)->count();
            $totalTelat = $presensi->where('id_participant', $data->participant->id)->where('status_terlambat', true)->count();
            $totalTidakCO = $presensi->where('id_participant', $data->participant->id)->where('status_check_out')->where('status_check_out', false)->count();
            $JamKerja = $presensi->where('id_participant', $data->participant->id)->map(function($dataPresensi) {
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
                "participant" => $data->participant->nama,
                "TotalHari" => $totalHari[$index],
                "TotalMasuk" => $totalMasuk,
                "totalTelat" => $totalTelat,
                "totalTidakMasuk" => $totalHari[$index] - $totalMasuk - $totalLibur,
                "totalTidakCheckOut" => $totalTidakCO,
                "totalJamKerja" => $totalJamKerja,
                "totalLibur" => $totalLibur
            ];
        });
        // $startDate = $shift->tanggal_mulai;

        return response()->json($dataPresensi);
    }

    public function presensiExport($id, $date1, $date2)
    {
        $groupId = Crypt::decrypt($id);
        $group = Group::find($groupId);

        if(!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }

        return Excel::download(new PresensiGroupExport($group->id, $group->nama, $date1, $date2), 'report_presensi_group_' . $group->nama . '.xlsx');
    }
    public function ping($api_key, $id_device)
    {
        // Cek api_key valid
        $device = Device::where('api_key', $api_key)
                        ->where('device_id', $id_device)
                        ->first();

        if (!$device) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Update waktu terakhir ping
        $device->status_koneksi = now();
        $device->save();

        return response()->json([
            'message' => 'Ping received',
            'status_koneksi' => $device->status_koneksi,
        ]);
    }

    // API Function
    public function getAllPresensi(Request $request) {
        $perPage = $request->query('per_page', 10);
        $data = Presensi::paginate($perPage);

        return response()->json($data);
    }

    public function presensiExportApi($id)
    {
        $group = Group::find($id);

        if(!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }

        return Excel::download(new PresensiGroupExport($group->id, $group->nama), 'report_presensi_group_' . $group->nama . '.xlsx');
    }

}
