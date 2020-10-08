<?php

namespace App\Models;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    use Auditable;
    
    protected $table = 'dnlx_backups';
    protected $fillable = array('date_of_entry', 'remark', 'location');
    public $timestamps = false;
}
