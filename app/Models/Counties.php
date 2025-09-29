<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Counties extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];

    public function towns()
    {
        return $this->hasMany(Town::class);
    }
}
