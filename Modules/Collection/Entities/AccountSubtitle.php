<?php

namespace Modules\Collection\Entities;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
class AccountSubtitle extends Model
{
    use Auditable;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = ['name', 'col_acct_title_id', 'show_in_monthly'];
    protected $table = 'col_acct_subtitle';
    public $timestamps = false;

    public function title()
    {
        return $this->belongsTo('Modules\Collection\Entities\AccountTitle', 'col_acct_title_id');
    }

    public function subtitleitems()
    {
        return $this->hasMany('Modules\Collection\Entities\SubTitleItems', 'col_acct_subtitle_id');
    }

   public function receipt()
    {
        return DB::table('col_acct_subtitle')
                ->leftJoin('col_receipt_items','col_receipt_items.col_acct_subtitle_id','=','col_acct_subtitle.id')
                ->leftJoin('col_receipt','col_receipt.id','=','col_receipt_items.col_receipt_id')
                ->where('col_acct_subtitle.id',$this->id)
                ->where('is_printed','1');
    }

     public function cash_div()
    {
        return DB::table('col_acct_subtitle')
                ->leftJoin('col_cash_division_items','col_cash_division_items.col_acct_subtitle_id','=','col_acct_subtitle.id')
                ->leftJoin('col_cash_division','col_cash_division.id','=','col_cash_division_items.col_cash_division_id')
                ->where('col_acct_subtitle.id',$this->id)
                ;
    }


    public function receipt_items()
    {
        return $this->hasMany('Modules\Collection\Entities\ReceiptItems', 'col_acct_subtitle_id');
    }

    public function rate()
    {
        return $this->hasOne('Modules\Collection\Entities\CollectionRate', 'col_acct_subtitle_id');
    }

    public function budget()
    {
        return $this->hasMany('Modules\Collection\Entities\BudgetEstimate', 'col_acct_subtitle_id');
    }

    public function mnhtly_prov_income()
    {
        return $this->hasMany('Modules\Collection\Entities\MonthlyProvincialIncome', 'col_acct_subtitle_id');
    }

    public function acct_access(){
            return $this->hasOne('Modules\Collection\Entities\AccessAccounts', 'col_acct_subtitle_id');
    }

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function mncpal_item() {
        return $this->hasMany('Modules\Collection\Entities\MunicipalReceiptItems', 'col_acct_subtitle_id');
    }
}
