<?php

namespace Modules\Collection\Entities;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class SubTitleItems extends Model
{
        use Auditable;
        use SoftDeletes;
        protected $table = 'col_acct_subtitle_items';
    protected $fillable = ['item_name','col_acct_subtitle_id','show_in_monthly'];
 public $timestamps = false;

    public function subtitle()
    {
        return $this->belongsTo('Modules\Collection\Entities\AccountSubtitle', 'col_acct_subtitle_id');
    }

    
    public function budget()
    {
        return $this->hasMany('Modules\Collection\Entities\BudgetEstimate', 'col_acct_subtitleitems_id');
    }

    public static function getTableName()
    {
        return with(new static)->getTable();
    }
}
