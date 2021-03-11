<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TimeDimension
 *
 * @property int $time_id
 * @property int $day
 * @property int $month
 * @property int|null $quarter
 * @property int $year
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDimension newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDimension newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDimension query()
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDimension whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDimension whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDimension whereQuarter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDimension whereTimeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TimeDimension whereYear($value)
 * @mixin \Eloquent
 */
class TimeDimension extends Model
{
    use HasFactory;
    protected $table = 'time_dimension';
    public $timestamps = false;

    protected $fillable = [
        'time_id','day','month','quarter','year'
    ];
}
