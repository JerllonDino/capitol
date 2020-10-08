<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;


class F56Detail extends Model
{
    use SoftDeletes;
        use Auditable;
    public $timestamps = false;
    protected $table = 'col_f56_detail';
    protected $fillable = [
        'col_receipt_id',
        'col_f56_type_id',
        'owner_name',
        'tdrp_assedvalue',
        'period_covered',
        'full_partial',
        'basic_current',
        'basic_discount',
        'basic_previous',
        'basic_penalty_current',
        'basic_penalty_previous',
        'manual_tax_due',
        'ref_num',
    ];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function receipt()
    {
        return $this->belongsTo('Modules\Collection\Entities\Receipt', 'col_receipt_id');
    }

    public function F56Type()
    {
        return $this->belongsTo('Modules\Collection\Entities\F56Type', 'col_f56_type_id');
    }

     public function TDARPX() {
        return $this->hasOne('Modules\Collection\Entities\F56TDARP', 'col_f56_detail_id');
    }

    public function TDARP() {
        return $this->hasMany('Modules\Collection\Entities\F56TDARP', 'col_f56_detail_id');
    }


}
