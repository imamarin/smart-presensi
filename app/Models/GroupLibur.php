<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupLibur extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function waktuLibur(){
        return $this->belongsTo(WaktuLibur::class, 'id_waktu_libur');
    }

    public function group(){
        return $this->belongsTo(Group::class, 'id_group');
    }
}
