<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use \BezhanSalleh\FilamentShield\Traits\HasPageShield;
    protected $fillable = [
        'name',
        'status', 
    ];
}
