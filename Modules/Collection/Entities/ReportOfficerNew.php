<?php

namespace Modules\Collection\Entities;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportOfficerNew extends Model
{
	use Auditable;
    use SoftDeletes;

    protected $table = 'col_new_report_officers';
    protected $fillable = [
        'id',
        'officer_name',
        'position_name',
    ];
    
    public $timestamps = true;
	    public static function getTableName() {
	        return with(new static)->getTable();
	}

    public function position(){
        return $this->belongsTo('Modules\Collection\Entities\ReportOfficersPostion','position_name');
    }
}