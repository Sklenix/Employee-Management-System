<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ImportancesShifts
 *
 * @property int $importance_id
 * @property int|null $importance_value
 * @property string|null $importance_description
 * @method static \Illuminate\Database\Eloquent\Builder|ImportancesShifts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImportancesShifts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImportancesShifts query()
 * @method static \Illuminate\Database\Eloquent\Builder|ImportancesShifts whereImportanceDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImportancesShifts whereImportanceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImportancesShifts whereImportanceValue($value)
 * @mixin \Eloquent
 */
class ImportancesShifts extends Model
{
    use HasFactory;

    protected $primaryKey = 'importance_id';
    protected $table = 'table_importances_shifts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importance_value', 'importance_description'
    ];
}
