<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Report_Importance
 *
 * @property int $importance_report_id
 * @property string|null $importance_report_value
 * @property string|null $importance_report_description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Report_Importance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report_Importance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report_Importance query()
 * @method static \Illuminate\Database\Eloquent\Builder|Report_Importance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report_Importance whereImportanceReportDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report_Importance whereImportanceReportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report_Importance whereImportanceReportValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report_Importance whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Report_Importance extends Model
{
    use HasFactory;

    protected $primaryKey = 'report_id';
    protected $table = 'table_reports_importances';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importance_report_value', 'importance_report_description'
    ];

    public static function getAllReportsImportancesOptions(){
        return DB::table('table_reports_importances')
            ->select('table_reports_importances.importance_report_id', 'table_reports_importances.importance_report_value',
                'table_reports_importances.importance_report_description',)
            ->where('table_reports_importances.importance_report_description', '<>', 'Nespecifikováno')
            ->orderBy('table_reports_importances.importance_report_value', 'asc')
            ->get();
    }

    public static function getAllReportsImportancesOptionsWithUnspecified(){
        return DB::table('table_reports_importances')
            ->select('table_reports_importances.importance_report_id', 'table_reports_importances.importance_report_value',
                'table_reports_importances.importance_report_description',)
            ->orderBy('table_reports_importances.importance_report_value', 'asc')
            ->get();
    }

    public static function getConcreteImportance($importance_id){
        return DB::table('table_reports_importances')
            ->select('table_reports_importances.importance_report_id', 'table_reports_importances.importance_report_value',
                'table_reports_importances.importance_report_description',)
            ->where(['table_reports_importances.importance_report_id' => $importance_id])
            ->first();
    }

}
