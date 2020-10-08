<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    protected $fillable = [];
    protected $table = 'col_municipality';

    public function barangays()
    {
        return $this->hasMany('Modules\Collection\Entities\Barangay', 'municipality_id');
    }

    public static function getTableName()
    {
        return with(new static)->getTable();
    }
}
