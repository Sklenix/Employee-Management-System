<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDimension extends Model
{
    use HasFactory;
    protected $table = 'company_dimension';
    public $timestamps = false;

    protected $fillable = [
        'company_id','company_name','company_city','company_street','company_user_name','company_user_surname'
    ];
}
