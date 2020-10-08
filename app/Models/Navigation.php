<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Navigation extends Model
{
    protected $table = 'dnlx_navigation';
    public $timestamps = false;
    
    public function permission() {
        return $this->belongsTo('App\Models\Permission');
    }

    public static function getTableName() {
        return with(new static)->getTable();
    }
}
