<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $table = 'col_acctble_form';
    protected $fillable = ['name'];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

}
