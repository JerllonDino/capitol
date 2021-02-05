<?php

namespace Modules\Collection\Entities;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Adjustments extends Model
{
    use Auditable;
    use SoftDeletes;
    protected $table = 'col_adjustments';
    protected $fillable = [
        'col_customer_id',
        'sex',
        'col_municipality_id',
        'col_barangay_id',
        'dnlx_user_id',
        'date_of_entry',
        'refno',
        'client_type',
    ];
    // public $timestamps = false;

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function items() {
        return $this->hasMany('Modules\Collection\Entities\AdjustmentItems', 'col_adjustments_id');
    }

    public function municipality()
    {
        return $this->belongsTo('Modules\Collection\Entities\Municipality', 'col_municipality_id');
    }

    public function customer()
    {
        return $this->belongsTo('Modules\Collection\Entities\Customer', 'col_customer_id');
    }

    public function barangay()
    {
        return $this->belongsTo('Modules\Collection\Entities\Barangay', 'col_barangay_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'dnlx_user_id');
    }
}
