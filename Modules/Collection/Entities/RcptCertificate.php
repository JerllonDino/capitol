<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;

class RcptCertificate extends Model
{
    use Auditable;
    // public $timestamps = false;
    protected $table = 'col_rcpt_certificate';
    protected $fillable = [
        'col_receipt_id',
        'col_mncpal_receipt_id',
        'col_rcpt_certificate_type_id',
        'recipient',
        'address',
        'detail',
        'date_of_entry',
        'provincial_governor',
        'actingprovincial_governor',
        'asstprovincial_treasurer_position',
        'provincial_treasurer',
        'asstprovincial_treasurer',
        'user',
        'provincial_note',
        'provincial_clearance_number',
        'provincial_type',
        'provincial_bidding',
        'transfer_notary_public',
        'transfer_ptr_number',
        'transfer_doc_number',
        'transfer_page_number',
        'transfer_book_number',
        'transfer_series',
        'transfer_prepare_name',
        'transfer_prepare_position',
        'sand_requestor',
        'sand_requestor_addr',
        'sand_requestor_sex',
        'sand_type',
        'sand_sandgravelprocessed',
        'sand_abc',
        'sand_sandgravel',
        'sand_boulders',
        'include_from',
        'include_to,',
        'transfer_ref_num',
        'col_mncpal_receipt_id',
    ];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function receipt()
    {
        return $this->belongsTo('Modules\Collection\Entities\Receipt', 'col_receipt_id');
    }

    public function rcptCertificate()
    {
        return $this->belongsTo('Modules\Collection\Entities\RcptCertificate', 'col_rcpt_certificate_type_id');
    }

    public function getMncpalReceipt() {
        return $this->belongsTo('Modules\Collection\Entities\MunicipalReceipt', 'col_mncpal_receipt_id');
    }
}
