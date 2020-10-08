<?php

namespace Modules\Collection\Entities;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportOfficersPostion extends Model
{
    use Auditable;
    use SoftDeletes;

    protected $table = 'col_report_officer_position';
    protected $fillable = [
        'id',
        'position',
    ];

    public $timestamps = true;
        public static function getTableName() {
            return with(new static)->getTable();
    }

    public function officer(){
        return $this->hasMany('Modules\Collection\Entities\ReportOfficerNew','officer');
    }
}