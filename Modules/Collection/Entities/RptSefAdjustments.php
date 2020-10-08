<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;

class RptSefAdjustments extends Model
{
    protected $fillable = [
    	'municipality',
    	'start_date',
    	'end_date',
    	'report_no',
    	'report_date',
    	'created_at',
    	'updated_at'
    ];
    protected $table = 'col_rpt_sef_adjustments';

    public function report_sef_items() {
    	return $this->hasMany('Modules\Collection\Entities\RptSefAdjustmentsItems', 'col_rpt_sef_adjustments_id');
    }

    public function report_basic_items() {
        return $this->hasMany('Modules\Collection\Entities\RptBasicAdjustmentsItems', 'col_rpt_sef_adjustments_id');
    }
}
