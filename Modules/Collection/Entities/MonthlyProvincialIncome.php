<?php

namespace Modules\Collection\Entities;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;

class MonthlyProvincialIncome extends Model
{
    use Auditable;
    protected $table = 'col_monthly_provincial_income';
    protected $fillable = [
        'year',
        'month',
        'value',
        'col_acct_title_id',
        'col_acct_subtitle_id'
    ];
    public $timestamps = false;

    public function title()
    {
        return $this->belongsTo('Modules\Collection\Entities\AccountTitle', 'col_acct_title_id');
    }

    public function subtitle()
    {
        return $this->belongsTo('Modules\Collection\Entities\AccountSubtitle', 'col_acct_subtitle_id');
    }

    public static function getTableName()
    {
        return with(new static)->getTable();
    }
}
