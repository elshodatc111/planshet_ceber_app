<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Planshet extends Model
{
    protected $fillable = [
        'name',
        'user',
        'json',
    ];
}