<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaktuLibur extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function groupLibur()
    {
        return $this->hasMany(GroupLibur::class, 'id_waktu_libur');
    }
}
