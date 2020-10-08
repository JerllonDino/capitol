<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoutePermission extends Model
{
    protected $table = 'dnlx_routepermission';
    protected $fillable = array('route', 'permission_id');
    
    public function permission() {
        return $this->belongsTo('App\Models\Permission','permission_id');
    }
}
