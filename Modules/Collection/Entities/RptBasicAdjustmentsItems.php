<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;

class RptBasicAdjustmentsItems extends Model
{
    protected $fillable = [
    	'col_rpt_sef_adjustments_id',
    	'prv_adv_amt',
		'prv_adv_discount',
		'prv_curr_amt',
		'prv_curr_discount',
		'prv_prev_amt',
		'prv_1992_amt',
		'prv_1991_amt',
		'prv_penalty_curr',
		'prv_penalty_prev',
		'prv_penalty_1992',
		'prv_penalty_1991',
		'mnc_adv_amt',
		'mnc_adv_discount',
		'mnc_curr_amt',
		'mnc_curr_discount',
		'mnc_prev_amt',
		'mnc_1992_amt',
		'mnc_1991_amt',
		'mnc_penalty_curr',
		'mnc_penalty_prev',
		'mnc_penalty_1992',
		'mnc_penalty_1991',
		'brgy_adv_amt',
		'brgy_adv_discount',
		'brgy_curr_amt',
		'brgy_curr_discount',
		'brgy_prev_amt',
		'brgy_1992_amt',
		'brgy_1991_amt',
		'brgy_penalty_curr',
		'brgy_penalty_prev',
		'brgy_penalty_1992',
		'brgy_penalty_1991',
		'created_at',
    	'updated_at'
    ];
    protected $table = 'col_rpt_basic_adjustments_items';

    public function report() {
        return $this->belongsTo('Modules\Collection\Entities\RptBasicAdjustmentsItems', 'col_rpt_sef_adjustments_id');
    }
}
