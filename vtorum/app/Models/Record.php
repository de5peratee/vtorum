<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $guarded = false;
    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}