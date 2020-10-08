<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    protected $fillable = [];
    protected $table = 'col_barangay';

    public function municipal()
    {
        return $this->belongsTo('Modules\Collection\Entities\Municipality', 'id');
    }

    public static function getTableName()
    {
        return with(new static)->getTable();
    }
}
