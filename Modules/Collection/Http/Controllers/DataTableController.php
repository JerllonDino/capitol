<?php

namespace Modules\Collection\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Yajra\Datatables\Datatables;
use Modules\Collection\Library\dataTable;

class DataTableController extends Controller
{
     public function set_datatables(Request $request){
        $dataTable = new dataTable;
        if(method_exists ( $dataTable, $request->input('dataTables') )){
            $vars = $request->all();
            $method = $request->input('dataTables');
            $datatable = $dataTable->$method($vars);
            return Datatables::of($datatable)->make(true);
        }
        return json_encode('errors');
    }
}
