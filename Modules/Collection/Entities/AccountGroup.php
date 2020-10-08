<?php

namespace Modules\Collection\Entities;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountGroup extends Model
{
    use Auditable;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = ['name', 'acct_category_id'];
    protected $table = 'col_acct_group';
    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo('Modules\Collection\Entities\AccountCategory', 'acct_category_id');
    }

    public function title()
    {
        return $this->hasMany('Modules\Collection\Entities\AccountTitle', 'acct_group_id')->orderBy('code')->orderBy('name');
    }

    public function deleteChild()
    {
        $this->title()->delete();
        return parent::delete();
    }

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

}
