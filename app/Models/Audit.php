<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $table = 'dnlx_audits';
    public $incrementing = false;

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    
    public static function getTableName(){
        return with(new static)->getTable();
    }
}
