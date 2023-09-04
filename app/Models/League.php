<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function games()
    {
        return $this->hasMany(Game::class);
    }
}
