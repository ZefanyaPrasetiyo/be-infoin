<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Location extends Model
{
    use SoftDeletes, HasUlids;

    protected $fillable = [
        'nama_lokasi', 
        'latitude', 
        'longitude',
        'radius_km'
    ];
}