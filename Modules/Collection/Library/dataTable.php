<?php

namespace Modules\Collection\Library;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Modules\Collection\Entities\PCSettings;


/**
*   Class : CollectionDatatables
*   methods : applicants datatables
*/
class dataTable
{
    public function pc_macs($request){
        $pc_macs = db::table('col_pc_settings')
                        ->leftJoin('col_serial','col_serial.id','=','col_pc_settings.pc_receipt')
                        ->leftJoin('col_acctble_form','col_acctble_form.id','=','col_serial.acctble_form_id')
                        ->leftJoin('col_municipality','col_municipality.id','=','col_serial.municipality_id')
                        ->select(
                                'col_pc_settings.id as pc_mac_id','col_pc_settings.pc_name','col_pc_settings.pc_mac','col_pc_settings.pc_ip','col_pc_settings.process_type','col_pc_settings.form_type',
                                'col_serial.id as serial_id','col_serial.serial_begin','col_serial.serial_end','col_serial.serial_current',
                                'col_municipality.name as mncpal_name',
                                'col_acctble_form.name as form_name'
                                )
                        ->where('col_serial.serial_current', '!=', '0');
        return $pc_macs;

    }


    public function customer_rcpt($request){
        $pc_macs = db::table('col_pc_settings')
                        ->leftJoin('col_serial','col_serial.id','=','col_pc_settings.pc_receipt')
                        ->leftJoin('col_acctble_form','col_acctble_form.id','=','col_serial.acctble_form_id')
                        ->leftJoin('col_municipality','col_municipality.id','=','col_serial.municipality_id')
                        ->select(
                                'col_pc_settings.id as pc_mac_id','col_pc_settings.pc_name','col_pc_settings.pc_mac','col_pc_settings.pc_ip','col_pc_settings.process_type','col_pc_settings.form_type',
                                'col_serial.id as serial_id','col_serial.serial_begin','col_serial.serial_end','col_serial.serial_current',
                                'col_municipality.name as mncpal_name',
                                'col_acctble_form.name as form_name'
                                )
                        ->where('col_serial.serial_current', '!=', '0');
        return $pc_macs;

    }

}