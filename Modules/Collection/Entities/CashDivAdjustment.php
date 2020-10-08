<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;

class CashDivAdjustment extends Model
{
	protected $table = 'col_cashdiv_adjustments';
    protected $fillable = ['month', 'year', 'type', 'amount'];
}
