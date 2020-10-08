<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;

class WithCert extends Model
{
    protected $table = 'col_transaction_with_cert';
    protected $fillable = ['*'];

     public static function getTableName()
    {
        return with(new static)->getTable();
    }

}
