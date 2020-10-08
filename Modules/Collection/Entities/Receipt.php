<?php

namespace Modules\Collection\Entities;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use Auditable;
    use SoftDeletes;
    protected $table = 'col_receipt';
    protected $fillable = [
        'serial_no',
        'af_type',
        'col_serial_id',
        'col_municipality_id',
        'col_barangay_id',
        'dnlx_user_id',
        'col_customer_id',
        'sex',
        'report_date',
        'date_of_entry',
        'is_printed',
        'is_cancelled',
        'cancelled_remark',
        'transaction_source',
        'transaction_type',
        'bank_name',
        'bank_number',
        'bank_date',
        'bank_remark',
        'remarks',
        'is_many',
        'client_type'
    ];
    // public $timestamps = false;

    public function get_customer(){
          return DB::table('col_customer')
                ->where('id',$this->id);
    }

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function items() {
        return $this->hasMany('Modules\Collection\Entities\ReceiptItems', 'col_receipt_id');
    }

    public function sgbooklet() {
        return $this->hasMany('Modules\Collection\Entities\SGbooklet', 'col_receipt_id');
    }

    public function RcptCertificate() {
        return $this->hasOne('Modules\Collection\Entities\RcptCertificate', 'col_receipt_id');
    }

    public function WithCert() {
        return $this->hasOne('Modules\Collection\Entities\WithCert', 'trans_id');
    }

    public function F56Detail() {
        return $this->hasOne('Modules\Collection\Entities\F56Detail', 'col_receipt_id');
    }

    public function F56Previuos() {
        return $this->hasOne('Modules\Collection\Entities\F56PreviousReceipt', 'col_receipt_id');
    }

    public function F56Detailmny() {
        // return $this->hasMany('Modules\Collection\Entities\F56Detail', 'col_receipt_id')->orderBy('period_covered', 'DESC');
        return $this->hasMany('Modules\Collection\Entities\F56Detail', 'col_receipt_id');
    }

    public function certificate()
    {
        return $this->hasOne('Modules\Collection\Entities\RcptCertificate', 'col_receipt_id');
    }

    public function municipality()
    {
        return $this->belongsTo('Modules\Collection\Entities\Municipality', 'col_municipality_id');
    }

     public function client_type_desc()
    {
        return $this->belongsTo('Modules\Collection\Entities\SandGravelTypes', 'client_type');
    }

    public function barangay()
    {
        return $this->belongsTo('Modules\Collection\Entities\Barangay', 'col_barangay_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'dnlx_user_id');
    }

    public function customer()
    {
        return $this->belongsTo('Modules\Collection\Entities\Customer', 'col_customer_id');
    }

	public function serial()
    {
        return $this->belongsTo('Modules\Collection\Entities\Serial', 'col_serial_id');
    }

	public function transactiontype()
    {
        return $this->belongsTo('Modules\Collection\Entities\TransactionType', 'transaction_type');
    }

    public function form()
    {
        return $this->belongsTo('Modules\Collection\Entities\Form', 'af_type');
    }
}
