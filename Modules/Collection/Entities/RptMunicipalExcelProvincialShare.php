<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;

class RptMunicipalExcelProvincialShare extends Model
{
    protected $fillable = [
        'basic_advance_amount',
        'basic_advance_discount',
        'basic_current_amount',
        'basic_current_discount',
        'basic_immediate_amount',
        'basic_1992_amount',
        'basic_1991_amount',
        'basic_penalty_current',
        'basic_penalty_immediate',
        'basic_penalty_1992',
        'basic_penalty_1991',
        'sef_advance_amount',
        'sef_advance_discount',
        'sef_current_amount',
        'sef_current_discount',
        'sef_immediate_amount',
        'sef_1992_amount',
        'sef_1991_amount',
        'sef_penalty_current',
        'sef_penalty_immediate',
        'sef_penalty_1992',
        'sef_penalty_1991',
        'is_verified',
        'verified_at',
        'col_rpt_municipal_excel_id'
    ];

    public $timestamps = false;

    protected $table = 'col_rpt_municipal_excel_provincial';

    public function municipalExcel()
    {
        return $this->belongsTo('Modules\Collection\Entities\RptMunicipalExcel', 'col_rpt_municipal_excel_id');
    }
}
