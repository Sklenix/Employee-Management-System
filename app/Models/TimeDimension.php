<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeDimension extends Model
{
    use HasFactory;
    protected $table = 'time_dimension';
    public $timestamps = false;

    protected $fillable = [
        'time_id','day','month','quarter','year'
    ];
}
