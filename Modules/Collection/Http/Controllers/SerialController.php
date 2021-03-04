<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbsController;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use Modules\Collection\Entities\AccountCategory;
use Modules\Collection\Entities\Serial;
use Modules\Collection\Entities\Form;
use Modules\Collection\Entities\Municipality;
use Modules\Collection\Entities\ReportOfficerNew;

class SerialController extends Controller
{
    protected $serial;

	public function __construct(Request $request, Serial $serial)
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
        $this->base['municipality'] = Municipality::orderBy('name', 'ASC')->get();
        $this->base['accountable_officer'] = ReportOfficerNew::all();
        $res = Form::all();
        return view('collection::serial.index', compact('res'))->with('base', $this->base);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {


       if($request['form'] == 1){
             $validator = Validator::make($request->all(), [
                            'start' => 'required|numeric',
                            'end' => 'required|numeric',
                            'acct_cat_id' => 'required',
                            'form' => 'required',
                            'date' => 'required|date',
                            ]);
         }else{
            $validator = Validator::make($request->all(), [
                            'start' => 'required|numeric',
                            'end' => 'required|numeric',
                            'form' => 'required',
                            'date' => 'required|date',
                            'municipality' => 'required',
                            ]);
         }



        if ($validator->fails()) {
            return redirect()->route('serial.index')
                ->withErrors($validator);
        } elseif ($request['start'] > $request['end']) {
            $validator->getMessageBag()
                ->add('serial', 'Start of serial must be lesser or equal to End of serial.');
            return redirect()->route('serial.index')
                ->withErrors($validator);
        }

        $unit = ($request['form'] == 1) ? $request['unit'] : null;
        $acct_cat_id = ($request['form'] == 1) ? $request['acct_cat_id'] : null;
        $municipality = ($request['form'] == 2) ? $request['municipality'] : null;

        $serial_check = Serial::where('serial_end','>=',$request['start'])
                            ->where('serial_begin','<=',$request['end'])
                            ->where('acctble_form_id',$request['form'])
                            ->first();
        if(!$serial_check){
            Serial::create([
                            'acctble_form_id' => $request['form'],
                            'serial_begin' => $request['start'],
                            'serial_end' => $request['end'],
                            'date_added' => date('Y-m-d', strtotime($request['date'])),
                            'unit' => $unit,
                            'acct_cat_id' => $acct_cat_id,
                            'municipality_id' => $municipality,
                            'serial_current' => $request['start'],
                            'accountable_officer' => $request['accountable_officer']
                        ]);
            Session::flash('info', ['Successfully created serial '.$request['start'].' - '.$request['end']]);
            return redirect()->route('serial.index');
        }else{
            Session::flash('error', ['
                                        The serial your trying to add is within an existing serial please check and try again. <br />
                                        serial start : '.$serial_check->serial_begin.' serial end : '.$serial_check->serial_end.' serial current : '.$serial_check->serial_current.'
                                    ']);
            return redirect()->route('serial.index');
        }
        

        
    }

    public function check_serial_exist($serial_begin,$serial_end,$aftype){
        


    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $res = Form::all();
        $this->base['sub_header'] = 'Edit';
        $this->base['serial'] = Serial::findOrFail($id);
        $this->base['municipality'] = Municipality::orderBy('name', 'ASC')->get();
        return view('collection::serial.edit', compact('res'))->with('base', $this->base);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {

        $serial = Serial::where('id', $id)->first();

        $validator = Validator::make($request->all(), [
            'start' => 'required|numeric',
            'end' => 'required|numeric',
            'current' => 'required|min:0|max:'.$request->end,
            'form' => 'required',
            'date' => 'required|date',
        ]);

        # Validation
        if ($validator->fails()) {
            return redirect()->route('serial.edit', $id)
                ->withErrors($validator);
        } elseif ($request['start'] > $request['end']) {
            $validator->getMessageBag()
                ->add('serial', 'Start of serial must be lesser or equal to End of serial.');
            return redirect()->route('serial.edit', $id)
                ->withErrors($validator);
        } elseif ($serial->serial_current != $serial->serial_begin && session::get('user')->position != 'Administrator' ) {
            $validator->getMessageBag()
                ->add('serial', 'The serial is already in use. Unable to edit.');
            return redirect()->route('serial.edit', $id)
                ->withErrors($validator);
        }elseif( $request->current < $request->start && $request->current !=0  ){
            Session::flash('error', ['Serial Current is lessthan Serial Start']);  
            return redirect()->route('serial.index');   
        }
        # Update
        $serial->acctble_form_id = $request['form'];
        $serial->serial_begin = $request['start'];
        $serial->serial_end = $request['end'];
        $serial->serial_current = $request['current'];
        $serial->date_added = date('Y-m-d', strtotime($request['date']));
        $serial->unit = ($request['form'] == 1) ? $request['unit'] : null;
        $serial->acct_cat_id = ($request['form'] == 1) ? $request['acct_cat_id'] : null;
        $serial->municipality_id = ($request['form'] == 2) ? $request['municipality'] : null;
        
        $serial->save();

        Session::flash('info', ['Serial has been updated.']);
        return redirect()->route('serial.index');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        $serial = $this->serial->where('id', $id)->first();
        $serial->delete();
        Session::flash('info', ['Serial has been deleted.']);
        return redirect()->route('serial.index');
    }

    public function show($id)
    {
        $this->base['sub_header'] = 'View';
        $this->base['serial'] = Serial::where('id', $id)->first();
        return view('collection::serial.view')->with('base', $this->base);
    }

    public function get_serial_current(Request $request){
            return Serial::find($request->input('serial_id'))->serial_current ?? [null];
    }
}
