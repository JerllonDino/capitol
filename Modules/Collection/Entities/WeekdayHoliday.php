<?php

namespace Modules\Collection\Entities;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;

class WeekdayHoliday extends Model
{
    use Auditable;
    
    protected $table = 'col_weekday_holiday';
    protected $fillable = array('year', 'month', 'day', 'date');
    public $timestamps = false;
}