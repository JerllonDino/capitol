<?php

namespace Modules\Collection\Entities;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SandGravelTypes extends Model
{
    use Auditable;
    use SoftDeletes;
    protected $table = 'col_customer_types';
    protected $fillable = ['*'];
    protected $dates = ['deleted_at'];

    public static function getTableName() {
        return with(new static)->getTable();
    }
}
