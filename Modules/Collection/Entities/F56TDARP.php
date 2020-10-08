<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;

class F56TDARP extends Model
{
    public $timestamps = false;
protected $table = 'col_f56_tdarp';
    protected $fillable = [ 'tdarpno' ];
    
    public function F56Detail()
    {
        return $this->belongsTo('Modules\Collection\Entities\F56Detail', 'col_f56_detail_id');
    }

    public function barangay_name()
    {
        return $this->belongsTo('Modules\Collection\Entities\Barangay', 'barangay');
    }

    public function previousTaxType()
    {
        return $this->hasOne('Modules\Collection\Entities\PreviousTaxType', 'id','previous_tax_type_id');
    }

  public static function getTableName()
{
    return with(new static)->getTable();
}
}
