<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function jamKerja() {
        return $this->belongsTo(JamKerja::class, 'id_jam_kerja');
    }

    public function detailShifts() {
        return $this->hasMany(DetailShift::class, 'id_shift');
    }

    public function jadwal_participant() {
        return $this->hasMany(JadwalParticipant::class, 'id_shift');
    }

    public function presensi() {
        return $this->hasMany(Presensi::class);
    }
}
