<?php

namespace Modules\Collection\Entities;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;

class CollectionRate extends Model
{
    use Auditable;
    protected $table = 'col_collection_rate';
    protected $fillable = [
        'col_acct_title_id',
        'col_acct_subtitle_id',
        'type',
        'label',
        'value',
        'is_shared',
        'sharepct_provincial',
        'sharepct_municipal',
        'sharepct_barangay',
        'sched_is_perunit',
        'sched_unit',
        'pct_is_sum_given',
        'pct_deadline',
        'pct_deadline_date',
        'pct_rate_per_month'
    ];
    public $timestamps = false;
    
    public function title()
    {
        return $this->belongsTo('Modules\Collection\Entities\AccountTitle', 'col_acct_title_id');
    }

    public function subtitle()
    {
        return $this->belongsTo('Modules\Collection\Entities\AccountSubtitle', 'col_acct_subtitle_id');
    }

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function getMncpalRcptItem() {
        return $this->belongsTo('Modules\Collection\Entities\MunicpalReceiptItems', 'col_collection_rate_id');   
    }
}
