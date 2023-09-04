<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function odds()
    {
        return $this->hasMany(Odd::class);
    }
}
