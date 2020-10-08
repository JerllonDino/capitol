<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;

class RptSefAdjustmentsItems extends Model
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
		'created_at',
    	'updated_at'
    ];

    protected $table = 'col_rpt_sef_adjustments_items';

    public function report() {
    	return $this->belongsTo('Modules\Collection\Entities\RptSefAdjustments', 'col_rpt_sef_adjustments_id');
    }
}
