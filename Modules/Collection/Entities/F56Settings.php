<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class F56Settings extends Model
{
	use SoftDeletes;
    protected $fillable = [];
    protected $table = 'col_form56_settings';

    public static function getTableName()
    {
        return with(new static)->getTable();
    }
   
}
