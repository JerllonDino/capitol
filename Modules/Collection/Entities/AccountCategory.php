<?php

namespace Modules\Collection\Entities;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountCategory extends Model
{
    use Auditable;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = ['name'];
    protected $table = 'col_acct_category';
    public $timestamps = false;

    public function group()
    {
        return $this->hasMany('Modules\Collection\Entities\AccountGroup', 'acct_category_id');
    }

    public function group_title()
    {
        return $this->hasManyThrough('Modules\Collection\Entities\AccountTitle', 'Modules\Collection\Entities\AccountGroup','acct_category_id', 'acct_group_id', 'id');
    }

    public function deleteChild()
    {
        $titles = $this->group_title()->get();
        foreach ($titles as $title) {
            $title->subs()->delete();
        }
        
        $this->group_title()->delete();
        $this->group()->delete();
        return parent::delete();
    }

    public static function getTableName()
    {
        return with(new static)->getTable();
    }
}
