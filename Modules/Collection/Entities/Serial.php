<?php

namespace Modules\Collection\Entities;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;

class Serial extends Model
{
    use Auditable;
    protected $table = 'col_serial';
    protected $fillable = [
        'acctble_form_id',
        'serial_begin',
        'serial_end',
        'date_added',
        'unit',
        'acct_cat_id',
        'municipality_id',
        'serial_current'
    ];
    // public $timestamps = false;

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function fund()
    {
        return $this->belongsTo('Modules\Collection\Entities\AccountCategory', 'acct_cat_id');
    }

    public function formtype()
    {
        return $this->belongsTo('Modules\Collection\Entities\Form', 'acctble_form_id');
    }

    public function receipts()
    {
        return $this->hasMany('Modules\Collection\Entities\Receipt', 'col_serial_id');
    }

    public function municipality()
    {
        return $this->belongsTo('Modules\Collection\Entities\Municipality', 'municipality_id');
    }

   public function pc_receipts()
    {
         return $this->hasOne('Modules\Collection\Entities\PCSettings', 'pc_receipt');
    }

}
