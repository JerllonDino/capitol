<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbsController;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use Modules\Collection\Entities\WeekdayHoliday;

class WeekdayHolidayController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'Holiday Settings';
        
        $this->base['months'] = array();
        for ($m=1; $m<=12; $m++) {
            $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
            array_push($this->base['months'], $month);
        }
    }
    
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('collection::holiday.index')->with('base', $this->base);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $this->base['current_month_days'] = date('t');
        $this->base['sub_header'] = 'Add';
        return view('collection::holiday.create')->with('base', $this->base);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'year' => 'required|numeric',
            'month' => 'required|numeric',
        ]);
        
        $rec = WeekdayHoliday::where('year', $request['year'])
            ->where('month', $request['month'])
            ->first();
        
        if ($validator->fails()) {
            return redirect()->route('holiday_settings.index')
                ->withErrors($validator);
        } elseif (!is_null($rec)) {
            $validator->getMessageBag()
                ->add('serial', 'Record already exists for query: '. $this->base['months'][$request['month']] .' '. $request['year']);
            return redirect()->route('holiday_settings.index')
                ->withErrors($validator);
        }
        
        $year = $request['year'];
        $month = $request['month'];
        foreach ($request['holiday_date'] as $hd) {
            WeekdayHoliday::create([
                'year' => $year,
                'month' => $month,
                'day' => $hd,
                'date' => date('Y-m-d', strtotime($year .'-'. $month .'-'. $hd)),
            ]);
        }
        
        Session::flash('info', ['Successfully added holiday settings']);
        return redirect()->route('holiday_settings.index');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('collection::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($year, $month)
    {
        $this->base['current_month_days'] = date('t', strtotime($year.'-'.$month.'-1'));
        $this->base['year'] = $year;
        $this->base['month'] = $month;
        $weekday_holidays = WeekdayHoliday::select('day')
            ->where('year', $year)
            ->where('month', $month)
            ->get();
        $this->base['days'] = array();
        foreach ($weekday_holidays as $wh) {
            array_push($this->base['days'], $wh->day);
        }
        return view('collection::holiday.edit')->with('base', $this->base);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $year, $month)
    {
        $year = $year;
        $month = $month;
        
        $weekday_holidays = WeekdayHoliday::where('year', $year)
            ->where('month', $month)
            ->get();
        $this->base['days'] = array();
        foreach ($weekday_holidays as $wh) {
            $wh->delete();
        }
        
        foreach ($request['holiday_date'] as $hd) {
            WeekdayHoliday::create([
                'year' => $year,
                'month' => $month,
                'day' => $hd,
                'date' => date('Y-m-d', strtotime($year .'-'. $month .'-'. $hd)),
            ]);
        }
        
        Session::flash('info', ['Successfully updated holiday settings']);
        return redirect()->route('holiday_settings.index');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
