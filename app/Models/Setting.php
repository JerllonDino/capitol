<?php

namespace App\Models;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use Auditable;

    protected $table = 'dnlx_settings';
    protected $fillable = [
      'name',
      'value'
    ];

    public $timestamps = false;
    public static function getTableName() {
        return with(new static)->getTable();
    }
}
