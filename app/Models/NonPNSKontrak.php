<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NonPNSKontrak extends Model
{
    protected $connection = 'arsip';
    protected $table = 'tbkontrak';
    public $timestamps = false;
    
    protected $fillable = [
        'NIP',
        'NAMA',
        'LEMARI',
        'LACI',
        'KODEBERKAS'
    ];
}