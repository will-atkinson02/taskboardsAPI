<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    public function taskboard()
    {
        return $this->belongsTo(Taskboard::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    protected $touches = ['taskboard'];
}
