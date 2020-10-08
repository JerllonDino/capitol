<?php

namespace Modules\Collection\Entities;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;

class SerialSG extends Model
{
    use Auditable;
    protected $table = 'col_serial_sg';
    protected $fillable = [
        'serial_date',
        'serial_end',
        'serial_start',
        'serial_qty',
        'serial_type',
    ];
    // public $timestamps = false;

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function SGtype()
    {
        return $this->belongsTo('Modules\Collection\Entities\SerialSGtype', 'serial_type');
    }


}
