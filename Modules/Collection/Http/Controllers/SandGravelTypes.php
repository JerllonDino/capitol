<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbsController;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Collection\Entities\SandGravelTypes as sg_types;
use Modules\Collection\Entities\AccountCategory;
use Modules\Collection\Entities\Municipality;
use Modules\Collection\Entities\AllowedMonths;
use Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;


class SandGravelTypes extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function sandgravel_types()
    {
         $sg_types =  sg_types::withTrashed()->get();
        $this->base['page_title'] = 'Customer Types';
        $this->base['sg_types'] = $sg_types;
        return view('collection::cash_division.cashdiv_types')->with('base', $this->base);
    }

    public function save_sandgravel_types_remove(Request $request){
        $sg_types =  sg_types::find( $request['type_id'] );
        $sg_types->delete();
    }

      public function save_sandgravel_types_restore(Request $request){
        $sg_types =  sg_types::withTrashed()
        ->where('id', $request['type_id'])
        ->restore();
        
    }

    

     public function save_sandgravel_types(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'type_desc' => 'required|unique:col_customer_types,description'
        ]);
          if ($validator->fails()) {
            return redirect()->route('sandgravel.types_clients')
                ->withErrors($validator)
                ->withInput();
        }
         $sg_types = new sg_types;
         Session::flash('info', ['Sand and Gravel Types has been created']);
        if($request['sandgravel_type_id'] != ''){
            $sg_types =  sg_types::find( $request['sandgravel_type_id'] );
            Session::flash('info', ['Sand and Gravel Types has been Updated']);
        }

        $sg_types->description = $request['type_desc'];
        $sg_types->save();


        return back();
    }


}
