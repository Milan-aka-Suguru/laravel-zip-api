<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Towns extends Model
{
    public $timestamps = false;
    protected $fillable = ['county_id', 'name', 'zip_code'];

    public function county()
    {
        return $this->belongsTo(Counties::class);
    }
    
}
