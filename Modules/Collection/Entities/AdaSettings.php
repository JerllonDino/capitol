<?php

namespace Modules\Collection\Entities;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;

class AdaSettings extends Model
{
    use Auditable;
    
    protected $table = 'col_ada_settings';
    protected $fillable = [
        'label',
        'vale',
    ];
    
    public $timestamps = false;
    public static function getTableName() {
        return with(new static)->getTable();
    }
}
