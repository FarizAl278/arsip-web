<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BerkasMasuk extends Model
{
    protected $table = 'berkas_masuk';
    protected $guarded = [];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nip', 'nip');
    }
}
