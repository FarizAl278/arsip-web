<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pensiun extends Model
{
    protected $connection = 'arsip';
    protected $table = 'tbpensiun';
    public $timestamps = false;
    
    protected $fillable = [
        'NIP',
        'NAMA',
        'LEMARI',
        'LACI',
        'KODEBERKAS'
    ];
}