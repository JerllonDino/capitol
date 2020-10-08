<?php

namespace Modules\Collection\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\BreadcrumbsController;
use App\Http\Controllers\Controller;

use Modules\Collection\Entities\AccountTitle;
use Modules\Collection\Entities\AccountCategory;
use Modules\Collection\Entities\AccountSubtitle;
use Modules\Collection\Entities\MonthlyProvincialIncome;
use Modules\Collection\Entities\Receipt;
use Carbon\Carbon;
class MonthlyProvincialIncomeController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'Monthly Provincial Income';

        $this->base['months'] = array();
        for ($m=1; $m<=12; $m++) {
            $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
            array_push($this->base['months'], $month);
        }
    }

    public function index()
    {
       $this->base['month_e'] = MonthlyProvincialIncome::where('year', date('Y'))
            ->select('month')
            ->groupBy('month')
            ->get();

        return view('collection::monthly_provincial_income.index')->with('base', $this->base);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data['category'] = AccountCategory::all();
        $data['title'] = AccountTitle::where('show_in_monthly', 1)
			->orderBy('name')
			->get();

        $data['subtitle'] = AccountSubtitle::where('show_in_monthly', 1)
			->orderBy('name')
			->get();
        $this->base['sub_header'] = 'Add';
        return view('collection::monthly_provincial_income.create', compact('data'))->with('base', $this->base);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'month' => 'required|numeric',
            'year' => 'required|numeric',
            'title_value*' => 'numeric',
            'subtitle_value*' => 'numeric',
        ]);

        $record = MonthlyProvincialIncome::where('month', $request['month'])
            ->where('year', $request['year'])
            ->first();

        if ($validator->fails()) {
            return redirect()->route('monthly_provincial_income.create')
                ->withErrors($validator)
                ->withInput();
        } elseif (!empty($record)) {
            Session::flash('error', ['A record already exists for the same month and year.']);
            return redirect()->route('monthly_provincial_income.create')
                ->withInput();
        }

        $input = $data = [];
        foreach ($request['title_value'] as $i => $title_value) {
            $input['year'] = $request['year'];
            $input['month'] = $request['month'];
            $input['value'] = $title_value;
            $input['col_acct_title_id'] = $request['title_id'][$i];
            $input['col_acct_subtitle_id'] = null;
            array_push($data, $input);
        }
        MonthlyProvincialIncome::insert($data);

        $input = $data = [];
        foreach ($request['subtitle_value'] as $i => $subtitle_value) {
            $input['year'] = $request['year'];
            $input['month'] = $request['month'];
            $input['value'] = $subtitle_value;
            $input['col_acct_title_id'] = null;
            $input['col_acct_subtitle_id'] = $request['subtitle_id'][$i];
            array_push($data, $input);
        }
        MonthlyProvincialIncome::insert($data);

        Session::flash('info', ['Monthly Provincial Income record has been added.']);
        return redirect()->route('monthly_provincial_income.index');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($year, $month)
    {
        $data['category'] = AccountCategory::all();
        $data['title'] = AccountTitle::orderBy('name')->get();
        $data['subtitle'] = AccountSubtitle::orderBy('name')->get();

        $this->base['budget'] = MonthlyProvincialIncome::where('year', $year)
            ->where('month', $month)
            ->get();
        $this->base['year'] = $year;
        $this->base['month'] = $month;
        return view('collection::monthly_provincial_income.view', compact('data'))->with('base', $this->base);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($year, $month)
    {
        $data['category'] = AccountCategory::all();
        $data['title'] = AccountTitle::orderBy('name')->get();
        $data['subtitle'] = AccountSubtitle::orderBy('name')->get();

        $this->base['budget'] = MonthlyProvincialIncome::where('year', $year)
            ->where('month', $month)
            ->get();
        $this->base['month'] = $month;
        $this->base['year'] = $year;
        return view('collection::monthly_provincial_income.edit', compact('data'))->with('base', $this->base);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($year, $month, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'month' => 'required|numeric',
            'year' => 'required|numeric',
            'title_value*' => 'numeric',
            'subtitle_value*' => 'numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->route('monthly_provincial_income.edit', ['year' => $year, 'month' => $month])
                ->withErrors($validator)
                ->withInput();
        }


        for( $x = 0 ; $x < count($request['title_id']) ; $x++){
                     $bb = MonthlyProvincialIncome::where('year', '=', $year)
                                        ->where('month', '=', $month)
                                        ->where( 'col_acct_title_id', '=', $request['title_id'][$x] )
                                        ->first();
                       if($bb){
                             $bb->value = $request['title_value'][$x];
                             $bb->reconciliation_value = $request['title_reconciliation'][$x];
                             $bb->total_value = $request['title_reconciliation'][$x] + $request['title_value'][$x];
                              $bb->save();
                        }else{
                             $bb = new MonthlyProvincialIncome;
                             $bb->year = $year;
                             $bb->month = $month;
                              $bb->col_acct_title_id = $request['title_id'][$x];
                              $bb->value = $request['title_value'][$x];
                              $bb->reconciliation_value = $request['title_reconciliation'][$x];
                             $bb->total_value = $request['title_reconciliation'][$x] + $request['title_value'][$x];
                              $bb->save();
                        }

            }
             for( $x = 0 ; $x< count($request['subtitle_id']) ; $x++){
                    $sbb = MonthlyProvincialIncome::where('year', '=', $year)
                                        ->where('month', '=', $month)
                                        ->where( 'col_acct_subtitle_id', '=', $request['subtitle_id'][$x] )
                                        ->first();

                    if($sbb){
                              $sbb->value = $request['subtitle_value'][$x];
                              $sbb->reconciliation_value = $request['subtitle_reconciliation'][$x];
                              $sbb->total_value = $request['subtitle_reconciliation'][$x] + $request['subtitle_value'][$x];
                             $sbb->save();
                        }else{
                             $sbb = new MonthlyProvincialIncome;
                             $sbb->year = $year;
                             $sbb->month = $month;
                              $sbb->col_acct_subtitle_id = $request['subtitle_id'][$x];
                              $sbb->value = $request['subtitle_value'][$x];
                              $sbb->reconciliation_value = $request['subtitle_reconciliation'][$x];
                              $sbb->total_value = $request['subtitle_reconciliation'][$x] + $request['subtitle_value'][$x];
                              $sbb->save();
                        }
        }

        Session::flash('info', ['Record updated.']);
        return redirect()->route('monthly_provincial_income.index');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }

    public function auto_regen_delete(Request $request){
      $month = $request->input('month_ex');
      $year = $request->input('year');
      // MonthlyProvincialIncome::where('month', $month )
      //                           ->where('year', $year)
      //                           ->delete();


      return redirect()->route('monthly_provincial_income.index');

    }

    
    public function aut_regen(Request $request){
      $month = $request->input('month_ex');
      $year = $request->input('year');
       // MonthlyProvincialIncome::where('month', $month )
       //                          ->where('year', $year)
       //                          ->delete();

      $this->aut_genx($request);
      return redirect()->route('monthly_provincial_income.index');             

    }

    public function aut_gen(Request $request){
      $this->aut_genx($request);
      return redirect()->route('monthly_provincial_income.index');
    }

    public function aut_genx($request){
      $year = $request->input('year');
      if(($request->input('month_e'))){
        $month = date_parse($request->input('month_e'));
        $month = Carbon::createFromDate($year, ($month['month']), 1, 'Asia/Manila');
      }else{
        $month = $request->input('month_ex');
        $month = Carbon::createFromDate($year, ($month), 1, 'Asia/Manila');
      }
      
     
      
      $categories = AccountCategory::get();
       $data = [];
      foreach ($categories as $category){
            foreach ($category->group as $group){

                        foreach ($group->title as $title){
                           $title_value = 0;
                             if ($title->show_in_monthly == 1){

                              if( $title->id == '55'){
                                $title_gets = Receipt::whereMonth('col_receipt.report_date','=',$month->startOfMonth()->format('m'))
                                            ->whereYear('col_receipt.report_date','=',$month->endOfMonth()->format('Y'))
                                              ->where('is_printed', '=', 1)
                                              ->where('col_receipt.is_cancelled', '<>', 1)
                                              ->where('af_type', '=', 2)
                                              ->orderBy('serial_no', 'ASC')
                                              ->get();
                                        foreach ($title_gets as $title_get) {
                                          if (isset($title_get->F56Detail)) {
                                                $title_value += ( $title_get->F56Detail->basic_current + $title_get->F56Detail->basic_previous + $title_get->F56Detail->basic_penalty_current + $title_get->F56Detail->basic_penalty_previous )  - $title_get->F56Detail->basic_discount  ;
                                              }
                                        }

                                        $title_value = $title_value * 0.50;
                              }else{
                                $title_get = $title->receipt()->where('col_receipt.report_date','>=',$month->startOfMonth()->format('Y-m-d'))
                                      ->where('col_receipt.report_date','<=',$month->endOfMonth()->format('Y-m-d'))
                                      ->where('col_receipt.is_cancelled', '<>', 1)
                                      ->where('col_receipt.is_printed', '=', 1)
                                      ->get();
                                        foreach ($title_get as $title_gets) {
                                                $title_value += $title_gets->share_provincial;
                                        }

                                        $cash_div = $title->cash_div()->where('col_cash_division.date_of_entry','>=',$month->startOfMonth()->format('Y-m-d'))
                                                                ->where('col_cash_division.date_of_entry','<=',$month->endOfMonth()
                                                                ->format('Y-m-d'))->get();
                                   
                                    foreach ($cash_div as $cash_divs) {
                                                $title_value += $cash_divs->value;
                                        }
                              }
                                     

                                          $title_record = MonthlyProvincialIncome::where('month', $month->format('m') )
                                                            ->where('year', $year)
                                                            ->where('col_acct_title_id', $title->id)
                                                            ->first();




                                          if($title_record){
                                              $title_record->value = $title_value;
                                          }else{
                                              $title_record = new MonthlyProvincialIncome;
                                              $title_record->year = $year;
                                              $title_record->month = $month->format('m');
                                               $title_record->value = $title_value;
                                               $title_record->col_acct_title_id = $title->id;
                                                $title_record->auto_generated = '1';
                                          }

                                          $title_record->save();

                              }
                              if(count($title->subs) == 0 ){

                                  foreach ($title->subs as $subs){
                                     $subtitle_value = 0;
                                        if ($subs->show_in_monthly == 1){
                                              $subtitle_get = $subs->receipt()->where('col_receipt.report_date','>=',$month->startOfMonth()->format('Y-m-d'))
                                      ->where('col_receipt.report_date','<=',$month->endOfMonth()->format('Y-m-d'))
                                      ->where('col_receipt.is_cancelled', '<>', 1)
                                      ->get();
                                          foreach ($subtitle_get as $subtitle_gets) {
                                                  $subtitle_value += $subtitle_gets->value;
                                        }

                                           $subcash_div = $subs->cash_div()->where('col_cash_division.date_of_entry','>=',$month->startOfMonth()->format('Y-m-d'))
                                                                ->where('col_cash_division.date_of_entry','<=',$month->endOfMonth()
                                                                ->format('Y-m-d'))->get();

                                    foreach ($subcash_div as $subcash_divs) {
                                                $title_value += $subcash_divs->value;
                                        }

                                        $subtitle_record = MonthlyProvincialIncome::where('month', $month->format('m') )
                                                            ->where('year', $year)
                                                            ->where('col_acct_title_id', $title->id)
                                                            ->first();
                                          if($subtitle_record){
                                              $subtitle_record->value = $subtitle_value;
                                          }else{
                                              $subtitle_record = new MonthlyProvincialIncome;
                                              $subtitle_record->year = $year;
                                              $subtitle_record->month = $month->format('m');
                                              $subtitle_record->value = $subtitle_value;
                                              $subtitle_record->col_acct_subtitle_id = $subs->id;
                                              $subtitle_record->auto_generated = '1';
                                          }
                                          $subtitle_record->save();

                                        }
                                  }
                              }
                        }
            }
      }


      

    }


}
