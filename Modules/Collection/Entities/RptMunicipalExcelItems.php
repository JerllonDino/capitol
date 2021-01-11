<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;

class RptMunicipalExcelItems extends Model
{
    protected $fillable = [
        'collection_date',
        'tax_payor_name',
        'period_covered',
        'or_number',
        'tdarp_number',
        'barangay_id',
        'classification',
        'basic_adv_gross',
        'basic_adv_discount',
        'basic_current_gross',
        'basic_current_discount',
        'basic_immediate_year',
        'basic_prior_1992',
        'basic_prior_1991',
        'basic_penalty_current',
        'basic_penalty_immediate',
        'basic_penalty_1992',
        'basic_penalty_1991',
        'basic_subtotal_gross',
        'basic_subtotal_net',
        'sef_adv_gross',
        'sef_adv_discount',
        'sef_current_gross',
        'sef_current_discount',
        'sef_immediate',
        'sef_prior_1992',
        'sef_prior_1991',
        'sef_penalty_current',
        'sef_penalty_immediate',
        'sef_penalty_1992',
        'sef_penalty_1991',
        'sef_subtotal_gross',
        'sef_subtotal_net',
        'grand_total_gross',
        'grand_total_net',
        'col_rpt_municipal_excel_id'
    ];

    public $timestamps = false;

    protected $table = 'col_rpt_municipal_excel_items';

    public function excel()
    {
        return $this->belongsTo('Modules\Collection\Entities\RptMunicipalExcel', 'col_rpt_municipal_excel_id');
    }
}
