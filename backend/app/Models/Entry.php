<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    //
    public $timestamps = false;
    protected $fillable = ['first_name', 'last_name', 'arrival_time'];
    
}
