<?php

namespace Modules\Collection\Entities;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;

class SandandGravelMnthly extends Model
{
    use Auditable;
    protected $table = 'col_sand_gravel_prev_month';
    protected $fillable = [
        'year',
        'month',
        'mcpal_value',
        'municipality'
    ];

    public $timestamps = false;

    public static function getTableName()
    {
        return with(new static)->getTable();
    }
}
