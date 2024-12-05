<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taskboard extends Model
{
    public function stages()
    {
        return $this->hasMany(Stage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }   
}
