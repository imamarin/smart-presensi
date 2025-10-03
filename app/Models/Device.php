<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function presensi(){
        return $this->hasMany(Presensi::class, 'id_device');
    }

    
}
