<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

class MunicipalReceipt extends Model
{
    use Auditable;
    use SoftDeletes;
	protected $table = 'col_mncpal_receipt';
    protected $fillable = [
    	'rcpt_no',
    	'rcpt_date',
    	'date_of_entry',
    	'col_customer_id',
    	'col_municipality_id',
    	'col_barangay_id',
    	'transaction_type',
    	'drawee_bank',
    	'bank_no',
    	'bank_date',
    	'remarks',
    	'client_type',
    	'dnlx_user_id',
    	'is_cancelled'
    ];

    public function getItems() {
    	return $this->hasMany('Modules\Collection\Entities\MunicipalReceiptItems', 'col_mncpal_receipt_id');
    }

    public function getCustomer() {
    	return $this->belongsTo('Modules\Collection\Entities\Customer', 'col_customer_id');	
    }

    public function getCert() {
        return $this->hasOne('Modules\Collection\Entities\RcptCertificate', 'col_mncpal_receipt_id');
    }
}
