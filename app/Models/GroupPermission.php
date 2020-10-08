<?php

namespace App\Models;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Setting;
use Illuminate\Support\Arr;

class GroupPermission extends Model
{
    use Auditable;
    
    protected $table = 'dnlx_group_permission';
    protected $fillable = array('value');
    public $timestamps = false;
    
    public function permission() {
        return $this->belongsTo('App\Models\Permission');
    }
    
    public function group() {
        return $this->belongsTo('App\Models\Group');
    }
    
    public static function getTableName() {
        return with(new static)->getTable();
    }
    
    public static function getSettings() {
        $settings = new Setting();
        $setting = $settings->where('name', 'audit_life')->first();
        if($setting->value==0) {
            $dontKeepAuditOf = array('id','group_id', 'permission_id', 'value');
        }
    }
    
    public function transformAudit(array $data) {
        if (Arr::has($data, 'new')) {
            $record = $this::whereId($this->id)->first();
            $group = $record->group['name'];
            $permission = $this->permission['description'];
            
            $new_log['group'] = $group;
            $new_log['permission'] = $permission;
            $new_log['value'] = $data['new']['value'];
            Arr::set($data, 'new', $new_log);
            
            $old_log['group'] = $group;
            $old_log['permission'] = $permission;
            $old_log['value'] = $data['old']['value'];
            Arr::set($data, 'old', $old_log);
        }
        return $data;
    }
}
