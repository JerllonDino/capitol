<?php

namespace Modules\Collection\Entities;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;

class SerialSGtype extends Model
{
    use Auditable;
    protected $table = 'col_serial_sg_type';
    protected $fillable = [
        'type_name',
    ];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }
    

}
