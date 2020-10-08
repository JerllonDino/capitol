<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbsController;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use DB;

use Modules\Collection\Entities\{ReportOfficerNew, ReportOfficers, ReportOfficersPostion};

class ReportOfficersNewController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'Report Officers Settings';
    }
    
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $this->base['settings_report_officers_new'] = ReportOfficerNew::get();
        return view('collection::reportofficersnew')->with('base', $this->base);
    }

    public function show($id)
    {
        $this->base['sub_header'] = 'View';
        $officer = ReportOfficerNew::findOrFail($id);
        $position = ReportOfficers::findOrFail($id);
        return view('collection::showreportofficersnew', compact('officer','position'))->with('base', $this->base);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $officer = ReportOfficerNew::orderBy('officer_name', 'asc')->get();
        $position = ReportOfficersPostion::orderBy('position', 'asc')->get();
        // $position = ReportOfficers::orderBy('position', 'asc')->get(); // old
        $this->base['sub_header'] = 'Add';
        return view('collection::createreportofficersnew', compact('officer','position'))->with('base', $this->base);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'officer_name' => 'required|max:255',
            'position_name' => 'required',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('settings_report_officers_new.create')
                ->withErrors($validator)
                ->withInput();
        }
        
        // $latest = ReportOfficerNew::orderBy('id','desc')->first();
        // $i = $latest->id+1;
        // $flag = ReportOfficerNew::withTrashed()->find($i);
        // if ($flag == null) {
        //     $officer_id = ReportOfficerNew::create([
        //         'id' => $i,
        //         'officer_name' => $request['officer_name'],
        //         'position_name' => $request['position_name'],
        //     ]);
        // } else {
        //     for(; $flag != null; $i++) {
        //         $flag = ReportOfficerNew::withTrashed()->find($i);
        //     }
        //     $officer_id = ReportOfficerNew::create([
        //         'id' => $i,
        //         'officer_name' => $request['officer_name'],
        //         'position_name' => $request['position_name'],
        //     ]);
        // }
        
        $latest2 = ReportOfficersPostion::orderBy('id','desc')->first();
        $i2 = $latest2->id+1;
        $flag2 = ReportOfficersPostion::withTrashed()->find($i2);
        $position = "";
        if ($flag2 == null) {
            $position = ReportOfficersPostion::create([
                'id' => $i2,
                'position' => $request['position_name'],
            ]);
        } else {
            for(; $flag2 != null; $i2++) {
                $flag2 = ReportOfficersPostion::withTrashed()->find($i2);
            }
            $position = ReportOfficersPostion::create([
                'id' => $i2,
                'position' => $request['position_name'],
            ]);
        }
        

        $latest = ReportOfficerNew::orderBy('id','desc')->first();
        $i = $latest->id+1;
        $flag = ReportOfficerNew::withTrashed()->find($i);
        if ($flag == null) {
            $officer_id = ReportOfficerNew::create([
                'id' => $i,
                'officer_name' => $request['officer_name'],
                'position_name' => $i2,
            ]);
        } else {
            for(; $flag != null; $i++) {
                $flag = ReportOfficerNew::withTrashed()->find($i);
            }
            $officer_id = ReportOfficerNew::create([
                'id' => $i,
                'officer_name' => $request['officer_name'],
                'position_name' => $i2,
            ]);
        }

        // $officer_id = ReportOfficerNew::create([
        //     'officer_name'=>$request['officer_name'],
        //     'position_name'=>$request['position_name'],
        // ]);

        Session::flash('info', ['Report Officer has been Created.']);
        return redirect()->route('settings_report_officers.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $this->base['sub_header'] = 'Update';
        $officer = ReportOfficerNew::join('col_report_officer_position', 'col_report_officer_position.id', '=', 'col_new_report_officers.position_name')->where(DB::raw('col_new_report_officers.id'), $id)->first();
        // $position = ReportOfficers::all();
        $position = ReportOfficersPostion::all();
        return view('collection::editreportofficernew', compact('officer','position','id'))->with('base', $this->base);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $officer = ReportOfficerNew::select('col_new_report_officers.*', 'col_report_officer_position.position')->join('col_report_officer_position', 'col_report_officer_position.id', '=', 'col_new_report_officers.position_name')->where(DB::raw('col_new_report_officers.id') ,$request->officer_id)->first();
        $officer_old_name = ReportOfficers::where('value', $officer->officer_name)->get(); 
        $officer_old_position = ReportOfficers::where('value', $officer->position)->get(); 
        
        $validator = Validator::make($request->all(), [
            'officer_name' => 'required|max:255',
            'position_name' => 'required',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('settings_report_officers_new.edit', ['id' => $request->officer_id])
                ->withErrors($validator)
                ->withInput();
        } 

        // $officer->officer_name = $request['officer_name'];
        // $officer->position_name = $request['position_name'];
        // $officer->save();

        ReportOfficerNew::updateOrCreate(
            [ 
                'id' => $officer->id 
            ],
            [
                'officer_name' => $request['officer_name'],
            ]
        );
        ReportOfficersPostion::updateOrCreate(
            [ 
                'id' => $officer->position_name
            ],
            [
                'position' => $request['position_name'],
            ]
        );

        if(!is_null($officer_old_name) || count($officer_old_name) > 0) {
            foreach($officer_old_name as $o) {
                // $o->value = $request['officer_name'];
                // $o->save();
                
                $affect = ReportOfficers::updateOrCreate(
                    [ 'id' => $o->id ],
                    [ 'value' => $request['officer_name'] ]
                );
            }
        }
        if(!is_null($officer_old_position) || count($officer_old_position) > 0) {
            foreach ($officer_old_position as $o) {
                // $o->value = $request['position_name'];
                // $o->save();
                ReportOfficers::updateOrCreate(
                    [ 'id' => $o->id ],
                    [ 'value' => $request['position_name'] ]
                );
            }
        }
        Session::flash('info', ['Report Officer has been updated.']);
        return redirect()->route('settings_report_officers.index');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        /*$officer = ReportOfficerNew::findOrFail($id);
        $ldate = Carbon::createFromDate($request->year, $request->month, 1, 'Asia/Manila');
        $officer->deleted_at = $ldate;
        $officer->save();

        Session::flash('info', ['Report Officer has been deleted.']);
        return redirect()->route('settings_report_officers_new.index');*/
    }

    public function restore($id)
    {
        /*$officer = ReportOfficerNew::findOrFail($id);

        $officer->deleted_at = null;
        $officer->save();

        Session::flash('info', ['Report Officer has been restored.']);
        return redirect()->route('settings_report_officers_new.index');*/
    }

    public function new_destroy(Request $request)
    {
        $officer = ReportOfficerNew::find($request->input('idd'));
        //dd($officer);
        $officer->delete();
        //return response()->json('test');
        return redirect()->route('settings_report_officers_new.index');
    }

    public function new_restore(Request $request)
    {
        $officer = ReportOfficerNew::withTrashed()->find($request->input('idd'));
        //dd($officer);
        $officer->restore();
        //return response()->json('test');
        return redirect()->route('settings_report_officers_new.index');
    }
}
