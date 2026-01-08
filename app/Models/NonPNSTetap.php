<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NonPNSTetap extends Model
{
    protected $connection = 'arsip';
    protected $table = 'tb_tetap';
    public $timestamps = false;
    
    protected $fillable = [
        'NIP',
        'NAMA',
        'LEMARI',
        'LACI',
        'KODEBERKAS'
    ];
}