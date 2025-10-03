<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function device()
    {
        return $this->belongsTo(Device::class, 'id_device');
    }

    public function shift(){
        return $this->belongsTo(Shift::class, 'id_shift');
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class, 'id_participant');
    }
}
