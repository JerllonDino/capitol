<?php

namespace App\Models;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Setting;

class User extends Model
{
    use Auditable;
    use SoftDeletes;
    
    protected $table = 'dnlx_user';
    protected $fillable = array('realname', 'username', 'position', 'password', 'email', 'group_id');
	protected $dontKeepAuditOf = array('password');

    public function group() {
        return $this->belongsTo('App\Models\Group','group_id');
    }
    
    public static function getTableName() {
        return with(new static)->getTable();
    }
    
    public static function getSettings() {
        $settings = new Setting();
        $setting = $settings->where('name', 'audit_life')->first();
        if($setting->value==0) {
            $dontKeepAuditOf = array('id', 'realname', 'username', 'password', 'email', 'group_id');
        }
    }
}
