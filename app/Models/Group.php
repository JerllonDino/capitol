<?php

namespace App\Models;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Setting;

class Group extends Model
{
    use Auditable;
    
    protected $table = 'dnlx_group';
    protected $fillable = array('name', 'description');
    public $timestamps = false;

    public function users() {
        return $this->hasMany('App\Models\User');
    }
    
    public function group_permissions() {
        return $this->hasMany('App\Models\GroupPermission');
    }
    
    public static function getTableName() {
        return with(new static)->getTable();
    }
    
    public static function getSettings() {
        $settings = new Setting();
        $setting = $settings->where('name', 'audit_life')->first();
        if($setting->value==0) {
            $dontKeepAuditOf = array('id', 'name', 'description');
        }
    }
}
