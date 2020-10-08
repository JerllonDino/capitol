<?php

namespace Modules\Collection\Entities;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class AccountTitle extends Model
{
    use Auditable;
    use SoftDeletes;

    protected $table = 'col_acct_title';
    protected $fillable = ['code', 'name', 'acct_group_id', 'show_in_monthly'];
    public $timestamps = false;

    protected $dates = ['deleted_at'];

    public function subs()
    {
        return $this->hasMany('Modules\Collection\Entities\AccountSubtitle', 'col_acct_title_id');
    }

    public function group()
    {
        return $this->belongsTo('Modules\Collection\Entities\AccountGroup','acct_group_id');
    }

    public function budget()
    {
        return $this->hasMany('Modules\Collection\Entities\BudgetEstimate', 'col_acct_title_id');
    }

    public function mnhtly_prov_income()
    {
        return $this->hasMany('Modules\Collection\Entities\MonthlyProvincialIncome', 'col_acct_title_id');
    }

    public function receipt($id = null)
    {
        if($id == null){
            $id = $this->id;
        }
        return DB::table('col_acct_title')
                ->leftJoin('col_receipt_items','col_receipt_items.col_acct_title_id','=','col_acct_title.id')
                ->leftJoin('col_receipt','col_receipt.id','=','col_receipt_items.col_receipt_id')
                ->where('col_acct_title.id',$id)
                ->where('is_printed','1')
                ->where('is_cancelled','<>','1');

    }

    
    public function cash_div()
    {
        return DB::table('col_acct_title')
                ->leftJoin('col_cash_division_items','col_cash_division_items.col_acct_title_id','=','col_acct_title.id')
                ->leftJoin('col_cash_division','col_cash_division.id','=','col_cash_division_items.col_cash_division_id')
                ->where('col_acct_title.id',$this->id)
                ;
    }

    public function receipt_items()
    {
        return $this->hasMany('Modules\Collection\Entities\ReceiptItems', 'col_acct_title_id');
    }

    public function rate()
    {
        return $this->hasOne('Modules\Collection\Entities\CollectionRate', 'col_acct_title_id');
    }

    public function acct_access(){
            return $this->hasOne('Modules\Collection\Entities\AccessAccounts', 'col_acct_title_id');
    }

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function mncpal_item() {
        return $this->hasMany('Modules\Collection\Entities\MunicipalReceiptItems', 'col_acct_title_id');
    }
}
