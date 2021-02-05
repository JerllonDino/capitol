<?php

namespace Modules\Collection\Entities;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;

class AdjustmentItems extends Model
{
                use Auditable;
    protected $table = 'col_adjustment_items';
    protected $fillable = [
        'col_adjustments_id',
        'col_acct_title_id',
        'col_acct_subtitle_id',
        'value',
        'share_provincial',
        'share_municipal',
        'share_barangay',
        'nature',
        
    ];
    // public $timestamps = false;
    
    public static function getTableName()
    {
        return with(new static)->getTable();
    }
    
    public function adjustments()
    {
        return $this->belongsTo('Modules\Collection\Entities\Adjustments', 'col_adjustments_id');
    }
    
    public function acct_title()
    {
        return $this->belongsTo('Modules\Collection\Entities\AccountTitle', 'col_acct_title_id');
    }
    
    public function acct_subtitle()
    {
        return $this->belongsTo('Modules\Collection\Entities\AccountSubtitle', 'col_acct_subtitle_id');
    }
}
