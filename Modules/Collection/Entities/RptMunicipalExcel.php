<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;

class RptMunicipalExcel extends Model
{
    protected $fillable = [
        'municipal',
        'report_month',
        'report_year'
    ];

    protected $table = 'col_rpt_municipal_excel';

    public static function getTableName()
    {
        return with(new static)->getTable();
    }
    
    public function excelItems()
    {
        return $this->hasMany('Modules\Collection\Entities\RptMunicipalExcelItems', 'col_rpt_municipal_excel_id');
    }

    public function municipal()
    {
        return $this->belongsTo('Modules\Collection\Entities\Municipality', 'municipal');
    }
}
