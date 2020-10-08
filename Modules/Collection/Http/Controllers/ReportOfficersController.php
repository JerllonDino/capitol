<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbsController;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use Modules\Collection\Entities\ReportOfficers;

class ReportOfficersController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'Report Officers Position';
    }
    
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $this->base['settings_report_officers'] = ReportOfficers::get();
        return view('collection::reportofficers')->with('base', $this->base);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $res = ReportOfficers::orderBy('position', 'asc')->get();
        $this->base['sub_header'] = 'Add';
        return view('collection::createreportofficersposition', compact('res'))->with('base', $this->base);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'position' => 'required',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('settings_report_officers.create')
                ->withErrors($validator)
                ->withInput();
        }
        
        $position_id = ReportOfficers::create([
            'position'=>$request['position'],
        ]);

        Session::flash('info', ['Report Officer Position has been Created.']);
        return redirect()->route('settings_report_officers.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $this->base['sub_header'] = 'Update';
        $position = ReportOfficers::whereId($id)->first();
        return view('collection::editreportofficerposition', compact('position'))->with('base', $this->base);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $position = ReportOfficers::whereId($id)->first();
        
        $validator = Validator::make($request->all(), [
            'position' => 'required',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('settings_report_officers.edit', ['id' => $id])
                ->withErrors($validator)
                ->withInput();
        }
        
        $position->position = $request['position'];
        $position->save();

        Session::flash('info', ['Report Officer Position has been updated.']);
        return redirect()->route('settings_report_officers.index');
    }

    public function new_destroy(Request $request)
    {
        $position = ReportOfficers::find($request->input('idd'));
        $position->delete();
        return redirect()->route('settings_report_officers.index');
    }

    public function new_restore(Request $request)
    {
        $position = ReportOfficers::withTrashed()->find($request->input('idd'));
        $position->restore();
        return redirect()->route('settings_report_officers.index');
    }
    
    /*private function updateOfficer($name, $value) {
        $repoff = ReportOfficers::where('name', $name)->first();
        $repoff->value = $value;
        $repoff->save();
        return;
    }*/
}
