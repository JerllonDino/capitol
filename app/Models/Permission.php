<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'dnlx_permission';
    public $timestamps = false;
    
    public function permissioncategory() {
        return $this->belongsTo('App\Models\Permissioncategory');
    }
    
    public function group_permissions() {
        return $this->hasMany('App\Models\GroupPermission');
    }
    
    public function navigation() {
        return $this->hasOne('App\Models\Navigation');
    }

    public static function getTableName() {
        return with(new static)->getTable();
    }
}
