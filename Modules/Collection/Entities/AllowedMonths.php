<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;

class AllowedMonths extends Model
{
    public $timestamps = false;
    protected $table = 'col_allowed_months';
    protected $fillable = ['*'];
}
