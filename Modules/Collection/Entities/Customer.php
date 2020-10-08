<?php

namespace Modules\Collection\Entities;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use Auditable;
    use SoftDeletes;

    protected $table = 'col_customer';
    protected $fillable = ['name','customer_type_id','address'];
    public function receipts() {
        return $this->hasMany('Modules\Collection\Entities\Receipt', 'col_customer_id');
    }

    public function latest_receipt(){
        return $this->hasMany('Modules\Collection\Entities\Receipt', 'col_customer_id')->latest();
    }

    public function customer_type(){
        return $this->hasOne('Modules\Collection\Entities\SandGravelTypes','id','customer_type_id');
    }

    public function all_receipt(){
        return $this->hasMany('Modules\Collection\Entities\Receipt', 'col_customer_id');
    }

    // public function get_customer($customer){
    //       return DB::table('col_customer')
    //             ->where('id',$customer);
    // }


    public static function getTableName() {
        return with(new static)->getTable();
    }


}
