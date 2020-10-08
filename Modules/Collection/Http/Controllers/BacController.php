<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbsController;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use Modules\Collection\Entities\Bac;

class BacController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'BAC Collections';
    }
    
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $this->base['user'] = Session::get('user');
        return view('collection::bac.index')->with('base', $this->base);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('collection::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $filter = [
            'user_id' => 'required|numeric',
            'date' => 'required|date',
        ];
        
        $bac = Bac::where('date_of_entry', date('Y-m-d', strtotime($request['date'])))->first();
        
        $validator = Validator::make($request->all(), $filter);
        if ($validator->fails()) {
            return redirect()->route('bac.index')
                ->withErrors($validator);
        } elseif (count($bac) != 0) {
            $validator->getMessageBag()
                ->add('bac', 'Record for '. $request['date'] .' already exists.');
            return redirect()->route('bac.index')
                ->withErrors($validator);
        }
        
        # Success
        $data = array();
        foreach ($request['bac_val'] as $i => $val) {
            $row['dnlx_user_id'] = $request['user_id'];
            $row['type'] = $request['bac_type'][$i];
            $row['value'] = $val;
            $row['date_of_entry'] = date('Y-m-d', strtotime($request['date']));
            array_push($data, $row);
        }
        Bac::insert($data);
        
        Session::flash('info', ['Successfully inserted BAC collection record']);
        return redirect()->route('bac.index');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($date)
    {
        $this->base['bac'] = Bac::where('date_of_entry', $date)->get();
        return view('collection::bac.view')->with('base', $this->base);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($date)
    {
        $this->base['date'] = $date;
        $this->base['user'] = Session::get('user');
        $this->base['bac'] = Bac::where('date_of_entry', $date)->get();
        return view('collection::bac.edit')->with('base', $this->base);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $date)
    {
        $bac = Bac::where('date_of_entry', $date)->get();
        
        $filter = [
            'user_id' => 'required|numeric',
            'date' => 'required|date',
        ];
        
        $bac_existing_date = Bac::where('date_of_entry', date('Y-m-d', strtotime($request['date'])))
            ->where('date_of_entry', '<>', $bac[0]->date_of_entry)
            ->orderBy('type', 'ASC')
            ->get();
        
        $validator = Validator::make($request->all(), $filter);
        if ($validator->fails()) {
            return redirect()->route('bac.edit', ['bac' => $date])
                ->withErrors($validator);
        } elseif (count($bac_existing_date) != 0) {
            $validator->getMessageBag()
                ->add('bac', 'Record for '. $request['date'] .' already exists.');
            return redirect()->route('bac.edit', ['bac' => $date])
                ->withErrors($validator);
        }
        
        # Successful validation
        $data = array();
        foreach ($bac as $i => $b) {
            $b->dnlx_user_id = $request['user_id'];
            $b->value = $request['bac_val'][$i];
            $b->date_of_entry = date('Y-m-d', strtotime($request['date']));
            $b->save();
        }
        
        Session::flash('info', ['Successfully updated BAC collection record']);
        return redirect()->route('bac.index');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
