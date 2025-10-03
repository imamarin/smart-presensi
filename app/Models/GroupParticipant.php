<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupParticipant extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function participant()
    {
        return $this->belongsTo(Participant::class, 'id_participant');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'id_group');
    }



}
