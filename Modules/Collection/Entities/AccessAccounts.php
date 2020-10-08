<?php

namespace Modules\Collection\Entities;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;

class AccessAccounts extends Model
{
     use Auditable;


     protected $table = 'col_acctount_access';
       protected $fillable = ['col_acct_title_id','col_acct_subtitle_id','show_in_landtax','show_in_fieldlandtax','show_in_cashdivision','show_in_form51','show_in_form56'];
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
}
