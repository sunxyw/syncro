<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currencies extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
        'symbol',
    ];
}
