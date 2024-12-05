<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    protected $touches = ['stage'];
}
