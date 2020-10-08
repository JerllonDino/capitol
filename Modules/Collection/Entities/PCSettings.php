<?php

namespace Modules\Collection\Entities;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PCSettings extends Model
{
	use Auditable;
    protected $table = 'col_pc_settings';
    protected $fillable = [];


    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function serials()
    {
         return $this->belongsTo('Modules\Collection\Entities\Serial', 'pc_receipt');
    }

    public function pc_receipt()
    {
         return DB::table('col_pc_settings')
                ->leftJoin('col_serial','col_serial.id','=','col_pc_settings.pc_receipt')
                ->where('col_pc_settings.id',$this->id)
                ;
    }

    public function pc_receipts()
    {
         return DB::table('col_pc_settings')
                ->leftJoin('col_receipt','col_receipt.col_serial_id','=','col_pc_settings.pc_receipt')
                ;
    }

}
