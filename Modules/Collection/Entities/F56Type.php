<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;

class F56Type extends Model
{
    protected $fillable = [];
    protected $table = 'col_f56_type';

    public static function getTableName()
    {
        return with(new static)->getTable();
    }
    
    public function F56Detail() {
        return $this->hasMany('Modules\Collection\Entities\F56Detail', 'col_f56_type_id');
    }
}
