<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $guarded = [];

    public function path()
    {
        return "/admin/positions/{$this->id}";
    }
}
