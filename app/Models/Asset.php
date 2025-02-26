<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    //
    protected $fillable = [
        'nomor_asset',
        'nama',
        'quantity',
        'description',
    ];

    public function peminjaman(){
        return $this->hasMany(peminjaman::class ,'Aseet_id','id');
    }
}
