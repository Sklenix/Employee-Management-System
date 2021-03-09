<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AbsenceReason
 *
 * @property int $reason_id
 * @property int $reason_value
 * @property string|null $reason_description
 * @method static \Illuminate\Database\Eloquent\Builder|AbsenceReason newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AbsenceReason newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AbsenceReason query()
 * @method static \Illuminate\Database\Eloquent\Builder|AbsenceReason whereReasonDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AbsenceReason whereReasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AbsenceReason whereReasonValue($value)
 * @mixin \Eloquent
 */
class AbsenceReason extends Model
{
    use HasFactory;

    protected $primaryKey = 'reason_id';
    protected $table = 'table_absence_reasons';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reason_description','reason_value'
    ];
}
