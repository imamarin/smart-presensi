<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function group()
    {
        return $this->hasMany(Group::class, 'id_participant');
    }
 
    public function jadwalParticipant()
    {
        return $this->hasOne(JadwalParticipant::class, 'id_participant');
    }

    public function groupParticipants()
    {
        return $this->hasMany(GroupParticipant::class, 'id_participant');
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'id_participant');
    }
}
