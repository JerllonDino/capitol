<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SGbooklet extends Model
{
    protected $table = 'col_sandgravel_booklet_release';
    protected $fillable = ['*'];
    use SoftDeletes;

    public function receipt()
    {
        return $this->belongsTo('Modules\Collection\Entities\receipt', 'col_receipt_id');
    }
}
