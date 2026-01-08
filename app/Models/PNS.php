<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PNS extends Model
{
    protected $connection = 'arsip';
    protected $table = 'tbpersonal';
    public $timestamps = false;

    protected $primaryKey = 'id';
    
    protected $fillable = [
        'NIP',
        'NAMA',
        'LEMARI',
        'HAMBALAN',
        'NOMORBERKAS'
    ];
}