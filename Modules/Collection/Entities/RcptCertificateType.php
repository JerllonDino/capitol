<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;

class RcptCertificateType extends Model
{
            use Auditable;
    public $timestamps = false;
    protected $table = 'col_rcpt_certificate_type';
    protected $fillable = [
        'name',
    ];
    
    public static function getTableName()
    {
        return with(new static)->getTable();
    }
    
    public function rcptCertificate() {
        return $this->hasMany('Modules\Collection\Entities\RcptCertificate', 'col_rcpt_certificate_type_id');
    }
}
