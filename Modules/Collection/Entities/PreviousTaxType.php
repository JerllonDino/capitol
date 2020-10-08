<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;

class PreviousTaxType extends Model
{

    protected $table = 'col_f56_tax_type';

	public static function getTableName()
	    {
	        return with(new static)->getTable();
	    }
}
