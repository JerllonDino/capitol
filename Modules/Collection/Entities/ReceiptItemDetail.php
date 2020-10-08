<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;

class ReceiptItemDetail extends Model
{
        use Auditable;
    protected $table = 'col_receipt_item_detail';
    protected $fillable = [
        'col_receipt_item_id',
        'col_collection_rate_id',
        'label',
        'value',
        'sched_is_perunit',
        'sched_unit',
    ];
    // public $timestamps = false;
    
    public static function getTableName()
    {
        return with(new static)->getTable();
    }
    
    public function item()
    {
        return $this->belongsTo('Modules\Collection\Entities\ReceiptItems', 'col_receipt_item_id');
    }
    
    public function rate()
    {
        return $this->belongsTo('Modules\Collection\Entities\CollectionRate', 'col_collection_rate_id');
    }
}
