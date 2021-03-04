<?php

namespace Modules\Collection\Entities;

use Illuminate\Database\Eloquent\Model;

class Hospitals extends Model
{
    protected $table = "col_hospitals";

    protected $fillable = [
        'name',
    ];
}
