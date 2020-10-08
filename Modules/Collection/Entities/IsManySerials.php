<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;

class IsManySerials extends Model
{
    protected $table = 'col_many_receipt';
    protected $fillable = [];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }
}
