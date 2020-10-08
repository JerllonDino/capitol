<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permissioncategory extends Model
{
    protected $table = 'dnlx_permission_category';
    public $timestamps = false;
    
    public function permissions() {
        return $this->hasMany('App\Models\Permission');
    }

    public static function getTableName() {
        return with(new static)->getTable();
    }
}
