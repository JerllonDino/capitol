<?php

namespace Modules\Collection\Entities;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;

class Bac extends Model
{
    use Auditable;
    protected $table = 'col_bac_collections';
    protected $fillable = [
        'dnlx_user_id',
        'type',
        'value',
        'date_of_entry'
    ];
    public $timestamps = false;
    
    public static function getTableName()
    {
        return with(new static)->getTable();
    }
    
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'dnlx_user_id');
    }
}
