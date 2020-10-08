<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;

class ReceiptItems extends Model
{
        use Auditable;
    protected $table = 'col_receipt_items';
    protected $fillable = [
        'col_receipt_id',
        'nature',
        'col_acct_title_id',
        'col_acct_subtitle_id',
        'value',
        'share_provincial',
        'share_municipal',
        'share_barangay',
    ];
    // public $timestamps = false;

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function receipt()
    {
        return $this->belongsTo('Modules\Collection\Entities\Receipt', 'col_receipt_id');
    }

    public function acct_title()
    {
        return $this->belongsTo('Modules\Collection\Entities\AccountTitle', 'col_acct_title_id');
    }

    public function acct_subtitle()
    {
        return $this->belongsTo('Modules\Collection\Entities\AccountSubtitle', 'col_acct_subtitle_id');
    }

    public function detail()
    {
        return $this->hasOne('Modules\Collection\Entities\ReceiptItemDetail', 'col_receipt_item_id');
    }
}
