<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{
    protected $table = 'col_transaction_type';
    protected $fillable = ['name'];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

}
