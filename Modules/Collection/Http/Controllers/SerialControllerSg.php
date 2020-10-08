<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbsController;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use Modules\Collection\Entities\AccountCategory;
use Modules\Collection\Entities\SerialSG;
use Modules\Collection\Entities\SerialSGtype;
use Modules\Collection\Entities\Form;
use Modules\Collection\Entities\Municipality;

class SerialControllerSg extends Controller
{
    protected $serial;

	public function __construct(Request $request, SerialSG $serial)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'Serial';
        $this->serial = $serial;

        $this->base['acct_cat'] = AccountCategory::get();
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $this->base['sg_type'] = SerialSGtype::all();
        return view('collection::serialsg.index', $this->base)->with('base', $this->base);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        
        $checkx = $request['end'] - $request['start'];
        $checkx = $checkx + $request['start'][-1] ;
        $qty = ( $checkx ) / 50;
        $qty = $qty +1 ;
         $validator = Validator::make($request->all(), [
                            'start' => 'required|numeric',
                            'end' => 'required|numeric',
                            'acct_cat_id' => 'required',
                            'date' => 'required|date'
                            ]);
         if ($validator->fails()) {
            return redirect()->route('serialsg.index')
                ->withErrors($validator);
        } elseif ($request['start'] > $request['end']) {
            $validator->getMessageBag()
                ->add('serial', 'Start of serial must be lesser  to End of serial.');
            return redirect()->route('serialsg.index')
                ->withErrors($validator);
        }



        $serial_check = SerialSG::where('serial_end','>=',$request['start'])
                            ->where('serial_start','<=',$request['end'])
                            ->where('serial_type',$request['acct_cat_id'])
                            ->first();



        if($serial_check ){
            Session::flash('error', ['
                                        The serial your trying to add is within an existing serial please check and try again. <br />
                                        serial start : '.$serial_check->serial_start.' serial end : '.$serial_check->serial_end
                                    ]);
            return redirect()->route('serialsg.index');
            
        }elseif($checkx%50 > 0){
            Session::flash('error', ['
                                        The serial end - serial start = '.$request['end'].' - '.$request['start'].' = '.$checkx.'  must be divisible by 50 without any remainder
                                     '
                                    ]);
            return redirect()->route('serialsg.index');
        }
            SerialSG::create([
                            'serial_type' => $request['acct_cat_id'],
                            'serial_start' => $request['start'],
                            'serial_end' => $request['end'],
                            'serial_date' => date('Y-m-d', strtotime($request['date'])),
                            'serial_type' => $request['acct_cat_id'],
                            'serial_qty' => $qty,
                            
                        ]);
            Session::flash('info', ['Successfully created serial '.$request['start'].' - '.$request['end']]);
            return redirect()->route('serialsg.index');
        

        

        
    }

    public function check_serial_exist($serial_begin,$serial_end,$aftype){
        


    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $this->base['serial'] = SerialSG::findOrFail($id);
         $this->base['sg_type'] = SerialSGtype::all();
        return view('collection::serialsg.edit', $this->base)->with('base', $this->base);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $serial = SerialSg::where('id', $id)->first();
        $checkx = $request['end'] - $request['start'];
        $checkx = $checkx + $request['start'][-1] ;
        $qty = ( $checkx ) / 50;
        $qty = $qty +1 ;
         $validator = Validator::make($request->all(), [
                            'start' => 'required|numeric',
                            'end' => 'required|numeric',
                            'acct_cat_id' => 'required',
                            'date' => 'required|date'
                            ]);
        if ($validator->fails()) {
            return redirect()->route('serialsg.index')
                ->withErrors($validator);
        } elseif ($request['start'] > $request['end']) {
            $validator->getMessageBag()
                ->add('serial', 'Start of serial must be lesser  to End of serial.');
            return redirect()->route('serialsg.index')
                ->withErrors($validator);
        }
        $serial_check = SerialSG::where('serial_end','>=',$request['start'])
                            ->where('serial_start','<=',$request['end'])
                            ->where('serial_type',$request['acct_cat_id'])
                            ->where('id','<>', $id )
                            ->first();

        if($serial_check ){
            Session::flash('error', ['
                                        The serial your trying to add is within an existing serial please check and try again. <br />
                                        serial start : '.$serial_check->serial_start.' serial end : '.$serial_check->serial_end
                                    ]);
            return redirect()->route('serialsg.edit',$id);
            
        }elseif($checkx%50 > 0){
            Session::flash('error', ['
                                        The serial end - serial start = '.$request['end'].' - '.$request['start'].' = '.$checkx.'  must be divisible by 50 without any remainder
                                     '
                                    ]);
            return redirect()->route('serialsg.edit',$id);
        }elseif (!$serial) {
            Session::flash('error', ['
                                       Please Refresh and try again'
                                    ]);
            return redirect()->route('serialsg.edit',$id);
        }

        $serial->serial_date = date('Y-m-d', strtotime($request['date']));
        $serial->serial_start = $request['start'];
        $serial->serial_end = $request['end'];
        $serial->serial_type = $request['acct_cat_id'];
        $serial->serial_qty = $qty;

        $serial->save();

        Session::flash('info', ['Serial has been updated.']);
        return redirect()->route('serialsg.edit',$id);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
       
    }

    public function show($id)
    {
        $this->base['sub_header'] = 'View';
        $this->base['serial'] = SerialSG::where('id', $id)->first();
        return view('collection::serialsg.view')->with('base', $this->base);
    }

    public function get_serial_current(Request $request){
    }
}
