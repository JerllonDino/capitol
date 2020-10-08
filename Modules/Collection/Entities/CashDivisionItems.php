<?php

namespace Modules\Collection\Entities;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;

class CashDivisionItems extends Model
{
                use Auditable;
    protected $table = 'col_cash_division_items';
    protected $fillable = [
        'col_cash_division_id',
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
    
    public function cash_division()
    {
        return $this->belongsTo('Modules\Collection\Entities\cash_division', 'col_cash_division_id');
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
