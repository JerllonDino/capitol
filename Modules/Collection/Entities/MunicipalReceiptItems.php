<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

class MunicipalReceiptItems extends Model
{
    use Auditable;
    use SoftDeletes;
	protected $table = 'col_mncpal_receipt_items';
    protected $fillable = [
    	'col_mncpal_receipt_id',
    	'col_acct_title_id',
    	'col_acct_subtitle_id',
    	'value',
    	'nature',
        'col_collection_rate_id',
        'item_qty',
        'sched_is_perunit',
        'sched_unit',
        'sched_label',
        'deleted_at'
    ];

    public function getRcpt() {
    	return $this->belongsTo('Modules\Collection\Entities\MunicipalReceipt', 'col_mncpal_receipt_id');
    }

    public function getAccount() {
        return $this->belongsTo('Modules\Collection\Entities\AccountTitle', 'col_acct_title_id');
    }

    public function getSubAccount() {
        return $this->belongsTo('Modules\Collection\Entities\AccountSubtitle', 'col_acct_subtitle_id');
    }

    public function getCollectRate() {
        return $this->belongsTo('Modules\Collection\Entities\CollectionRate', 'col_collection_rate_id');   
    }
}
