<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    protected $fillable = ['date','tax_payor','period_covered','or_no', 'tdarp_no','brgy','classification'];
}
