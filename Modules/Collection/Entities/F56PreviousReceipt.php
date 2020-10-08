<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;


class F56PreviousReceipt extends Model
{
    use SoftDeletes;
    use Auditable;
    public $timestamps = false;
    protected $table = 'col_f56_previous_receipt';
    protected $fillable = [
        'col_receipt_id',
        'col_receipt_no',
        'col_receipt_date',
        'col_receipt_year',
        'col_prev_remarks',

    ];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function receipt()
    {
        return $this->belongsTo('Modules\Collection\Entities\Receipt', 'col_receipt_id');
    }

}
