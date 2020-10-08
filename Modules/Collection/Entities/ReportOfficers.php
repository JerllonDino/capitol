<?php

namespace Modules\Collection\Entities;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;

class ReportOfficers extends Model
{
    use Auditable;

    protected $table = 'col_report_officers';
    protected $fillable = [
        'id',
        'name',
        'value',
    ];

    public $timestamps = true;
        public static function getTableName() {
            return with(new static)->getTable();
    }

    // public function officer(){
    //     return $this->hasMany('Modules\Collection\Entities\ReportOfficerNew','officer');
    // }
}