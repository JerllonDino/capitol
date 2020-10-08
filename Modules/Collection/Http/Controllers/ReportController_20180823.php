<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\{Controller,BreadcrumbsController};
use Illuminate\Http\{Request,Response};
use Illuminate\Support\Facades\{DB,Session};

use Modules\Collection\Entities\{
        AllowedMonths,AccountTitle,AccountCategory,AccountSubtitle,CashDivision,CashDivisionItems,Barangay,BudgetEstimate,
        F56Type,F56Detail,Municipality,Receipt,ReceiptItems,Serial,TransactionType,RcptCertificate,RcptCertificateType,ReportOfficers,
        MonthlyProvincialIncome,OtherFeesCharges,SandGravelTypes
    };

use Excel,PDF,Carbon\Carbon;



class ReportController extends Controller
{

	public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function shared()
    {
        $this->base['categories'] = AccountCategory::get();
        $this->base['municipalities'] = Municipality::orderBy('name', 'ASC')->get();
        $this->base['page_title'] = 'Report on Shared Accounts';
        $this->base['months'] = array();
        for ($month = 1; $month <= 12; $month++) {
            $this->base['months'][$month] = date('F', mktime(0,0,0,$month));
        }
        return view('collection::report.shared')->with('base', $this->base);
    }

    public function provincial_income()
	{
        $this->base['page_title'] = 'Provincial Income';
        #$this->base['months'] = $this->base['months'] =AllowedMonths::where('a_year',date('Y'))->get();

        $months = array();
        for ($month = 1; $month <= 12; $month++) {
            $months[$month] = date('F', mktime(0,0,0,$month));
        }
         $this->base['months'] = $months;

		return view('collection::report.provincial')->with('base', $this->base);
	}

    public function collections_deposits()
	{
        $this->base['categories'] = AccountCategory::get();
        $this->base['page_title'] = 'Collections & Deposits';
        $this->base['months'] = array();
        for ($month = 1; $month <= 12; $month++) {
            $this->base['months'][$month] = date('F', mktime(0,0,0,$month));
        }
		return view('collection::report.collections_deposits')->with('base', $this->base);
	}

    public function real_property()
    {
        $this->base['municipalities'] = Municipality::orderBy('name', 'ASC')->get();
        $this->base['page_title'] = 'Real Property Tax Collections';
        $this->base['months'] = array();
        for ($month = 1; $month <= 12; $month++) {
            $this->base['months'][$month] = date('F', mktime(0,0,0,$month));
        }
        return view('collection::report.real_property')->with('base', $this->base);
    }


    public function sandgravel_report_municpality(){
        $this->base['page_title'] = 'SAND and GRAVEL Monthly Report';
        $this->base['months'] = array();
        for ($month = 1; $month <= 12; $month++) {
            $this->base['months'][$month] = date('F', mktime(0,0,0,$month));
        }

        return view('collection::sandgravel_report.index')->with('base', $this->base);
    }

    public function sandgravel_report_municpality_generate(Request $request){
        $date = Carbon::createFromDate($request->year, $request->month, 1, 'Asia/Manila');
        $date_mnth = Carbon::createFromDate($request->year, $request->month, 1, 'Asia/Manila');
        $date_mnthx = Carbon::createFromDate($request->year, $request->month, 1, 'Asia/Manila');
        $this->base['month'] = $request->month;
        $this->base['datex'] = $date;
        $this->base['year'] = $request->year;
        $this->base['date_mnth'] = $date_mnth;
        $days = Carbon::createFromDate($request->year, $request->month, 1, 'Asia/Manila');
        $end_mnth = $date->endOfMonth()->format('d') ;
        $start_mnth = $date->startOfMonth()->format('d') ;
        $municpality = Municipality::all()->toarray();
        $d = 0;
        $provincial_share = 0;
        for($x=1; $x<=$end_mnth ; $x++){
              $month_x =$days->addDays($d);
              $month_p[$x]['y-m-d'] = $month_x->format('Y-m-d');
              $month_px[$x]['y-m-d'] = $month_x->format('d');
              $d = 1;
                foreach ($municpality as $key => $value) {
                    $total_v = 0;

                        $sand_gravel_fieldlandtax = DB::table('col_receipt')
                                                        ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                                                        ->select(db::raw('col_receipt.date_of_entry ,SUM(col_receipt_items.value) as value, SUM(col_receipt_items.share_provincial) as share_provincial'))
                                                        ->where('col_receipt.report_date','=',$month_p[$x]['y-m-d'])
                                                        ->where('col_receipt_items.col_acct_title_id','=','4')
                                                        ->where('col_receipt.col_municipality_id','=',$value['id'])
                                                        ->where('col_receipt.is_printed','=','1')
                                                        ->where('col_receipt.is_cancelled','=','0')
                                                        ->get();
                        $provincial_share += $sand_gravel_fieldlandtax[0]->share_provincial;
                        $mun_rpt[$month_px[$x]['y-m-d']][$value['name']]['sand_gravel_fieldlandtax'] = $sand_gravel_fieldlandtax;
                        $total_v +=  $sand_gravel_fieldlandtax[0]->value - $sand_gravel_fieldlandtax[0]->share_provincial;
                        $mun_rpt[$month_px[$x]['y-m-d']][$value['name']]['total_v'] = $total_v;

                }
        }
        $provincial_sharex = 0;
        $past_month = [];
        $date_mnthxx = $date_mnthx->format('Y').'-'.$date_mnthx->subMonth()->format('m').'-'.$date_mnthx->endOfMonth()->format('d') ;
        $date_mnthxxx = $date_mnthx->format('Y').'-01-01' ;
        foreach ($municpality as $key => $value) {
            $sand_gravel_fieldlandtaxx = DB::table('col_receipt')
                                                        ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                                                        ->select(db::raw('col_receipt.date_of_entry ,SUM(col_receipt_items.value) as value, SUM(col_receipt_items.share_provincial) as share_provincial'))
                                                        ->where('col_receipt.report_date','>=',$date_mnthxxx)
                                                        ->where('col_receipt.report_date','<=',$date_mnthxx)
                                                        ->where('col_receipt_items.col_acct_title_id','=','4')
                                                        ->where('col_receipt.col_municipality_id','=',$value['id'])
                                                        ->where('col_receipt.is_printed','=','1')
                                                        ->where('col_receipt.is_cancelled','=','0')
                                                        ->get();
                    $past_month[$value['name']] = $sand_gravel_fieldlandtaxx[0]->value - $sand_gravel_fieldlandtaxx[0]->share_provincial;
                    $provincial_sharex +=  $sand_gravel_fieldlandtaxx[0]->share_provincial;
        }
        $this->base['provincial_sharex'] = $provincial_sharex;
        $this->base['provincial_share'] = $provincial_share;
        $this->base['past_month'] = $past_month;
        $this->base['month_px'] = $month_px;
        $this->base['month_p'] = $month_p;
        $this->base['mun_rpt'] = $mun_rpt;
        $this->base['municipality'] = $municpality;
         if(isset($request['button_pdf'])){
            $pdf = new PDF;
            if($request->month == '1'){
                $pdf = PDF::loadView('collection::pdf/mncpal_collection_jan', $this->base);
            }else{
                $pdf = PDF::loadView('collection::pdf/mncpal_collection', $this->base);
            }

            $pdf->setPaper('legal', 'landscape');
            return @$pdf->stream();

         }elseif(isset($request['button_taxsharing'])){

            $this->base['officer'] = ReportOfficers::find(10);
            $this->base['position'] = ReportOfficers::find(11);
                    foreach ($municpality as $key => $mun) {
                            $landtaxsharing[$mun['name']] = [];
                            $data = DB::table('col_receipt')
                                ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                                ->select(db::raw('col_receipt.col_municipality_id,SUM(col_receipt_items.share_municipal) as value,SUM(col_receipt_items.share_provincial) as share_provincial_value'))
                                ->whereMonth('col_receipt.report_date','=',$request->month)
                                ->whereYear('col_receipt.report_date','=',$request->year)
                                ->where('col_receipt_items.col_acct_title_id','=','4')
                                ->where('col_receipt.is_printed','=','1')
                                ->where('col_receipt.is_cancelled','=','0')
                                ->where('col_receipt.col_municipality_id','=',$mun['id'])
                                ->get();

                            $datac = DB::table('col_cash_division')
                                    ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                                    ->select(db::raw('col_cash_division.col_municipality_id,SUM(col_cash_division_items.share_municipal) as value,SUM(col_cash_division_items.share_provincial) as share_provincial_value'))
                                    ->whereMonth('col_cash_division.date_of_entry','=',$request->month)
                                    ->whereYear('col_cash_division.date_of_entry','=',$request->year)
                                    ->where('col_cash_division_items.col_acct_title_id','=','4')
                                    ->whereNull('col_cash_division.deleted_at')
                                    ->get();

                            $landtaxsharing[$mun['name']]['value'] = $data[0]->value  + $datac[0]->value;
                            $landtaxsharing[$mun['name']]['provincial_value'] = $data[0]->share_provincial_value  + $datac[0]->share_provincial_value;

                            foreach ( Municipality::where('id','=',$mun['id'])->first()->barangays as $key => $brgy){
                                $datax = DB::table('col_receipt')
                                    ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                                    ->select(db::raw('col_receipt.col_barangay_id ,SUM(col_receipt_items.share_barangay) as value'))
                                    ->whereMonth('col_receipt.report_date','=',$request->month)
                                    ->whereYear('col_receipt.report_date','=',$request->year)
                                    ->where('col_receipt_items.col_acct_title_id','=','4')
                                    ->where('col_receipt.is_printed','=','1')
                                    ->where('col_receipt.is_printed','=','1')
                                    ->where('col_receipt.is_cancelled','=','0')
                                    ->where('col_receipt.col_barangay_id','=',$brgy->id)
                                    ->get();

                                 $dataxc = DB::table('col_cash_division')
                                    ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                                    ->select(db::raw('col_cash_division.col_barangay_id ,SUM(col_cash_division_items.share_barangay) as value'))
                                    ->whereMonth('col_cash_division.date_of_entry','=',$request->month)
                                    ->whereYear('col_cash_division.date_of_entry','=',$request->year)
                                    ->where('col_cash_division_items.col_acct_title_id','=','4')
                                    ->whereNull('col_cash_division.deleted_at')
                                    ->get();

                                if($datax[0]->value > 0 || $dataxc[0]->value > 0){
                                    $landtaxsharing[$mun['name']]['brgy'][$brgy->name] = $datax[0]->value +  $dataxc[0]->value;
                                }
                            }
                    }

                    $this->base['landtaxsharing'] = $landtaxsharing;

                    $pdf = PDF::loadView('collection::pdf/sand_gravel_taxsharing', $this->base);
                    $pdf->setPaper('legal', 'portrait');
                    return @$pdf->stream();

         }elseif(isset($request['button_taxcollected'])){


            $taxcollected = [];
            foreach ($municpality as $key => $mun) {
                $data = DB::table('col_receipt')
                    ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                    ->select(db::raw('SUM(col_receipt_items.share_municipal) as municipal_value, SUM(col_receipt_items.share_barangay) as barangay_value, SUM(col_receipt_items.share_provincial) as provincial_value'))
                    ->whereMonth('col_receipt.report_date','=',$request->month)
                    ->whereYear('col_receipt.report_date','=',$request->year)
                    ->where('col_receipt_items.col_acct_title_id','=','4')
                    ->where('col_receipt.is_printed','=','1')
                    ->where('col_receipt.is_cancelled','=','0')
                    ->where('col_receipt.col_municipality_id','=',$mun['id'])
                    ->first();

                $taxcollected[$mun['name']] = $data;

            }

            //$additional = array_pop($taxcollected);
            $this->base['taxcollected'] = $taxcollected;

            $sandgraveltypes = SandGravelTypes::findMany([1,2,3,4,5,6]);
            $types = [];
            foreach ($sandgraveltypes as $key => $type) {
                $data = DB::table('col_receipt')
                    ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                    ->select(db::raw('SUM(col_receipt_items.value) as value'))
                    ->whereMonth('col_receipt.report_date','=',$request->month)
                    ->whereYear('col_receipt.report_date','=',$request->year)
                    ->where('col_receipt_items.col_acct_title_id','=','4')
                    ->where('col_receipt.is_printed','=','1')
                    ->where('col_receipt.is_cancelled','=','0')
                    ->where('col_receipt.client_type','=',$type['id'])
                    ->first();

                $types[$type['description']] = $data;
            }

            $this->base['graveltypes'] = $types;

            //return $types;

            $pdf = PDF::loadView('collection::pdf/sand_gravel_taxcollected_perclient', $this->base);
            $pdf->setPaper('legal', 'portrait');
            return @$pdf->stream();

         }else{
             Excel::create('SAND and GRAVEL PENALTIES and COLLECTIONS', function($excel) use($mun_rpt) {
                        $excel->sheet('PENALTIES and COLLECTIONS', function($sheet) use($mun_rpt) {
                            $sheet->loadView('collection::sandgravel_report.excel_mncpal_collection') ->with('base', $this->base);
                        });
                    })->export('xls');
         }

    }


    public function shared_pdf(Request $request){

            $this->base['month'] = Carbon::createFromDate(date('Y'), $request['month'], 1, 'Asia/Manila');;
            if( $request->input('button_pdf_sef') === 'button_pdf_sef' ){
                $this->base['municipalities'] = Municipality::all();
                $this->base['sef'] =     $this->get_sef_municaplity($request);
                 $pdf = PDF::loadView('collection::pdf/sef_monthly_pdf', $this->base);
                    $pdf->setPaper('letter', 'portrait');
                    return @$pdf->stream();
            }else{
                $this->base['shares'] =     $this->get_shared_bac_report($request);
                    $pdf = new PDF;
                    if( Session::has('error') ){
                        // Session::flash('error', ['PLEASE CHECK SERIAL : '.$receipt->serial_no.' NO BARANGAY has been set']);
                        return redirect()->route('report.shared');
                    }
                    return view('collection::pdf/bac_shared', $this->base);

                    // $pdf = PDF::loadView('collection::pdf/bac_shared', $this->base);
                    // $pdf->setPaper('letter', 'portrait');
                    // return @$pdf->stream();
            }
    }

    public function get_sef_municaplity($params){
         $municipalities = Municipality::all();
        $municipalityx=[];
            foreach ($municipalities as $municipality) {
                if($municipality->id != 14){
                    $params['municipality'] = $municipality->id;
                    $municipalityx[$municipality->name]= $this->real_property_consolidated($params);
                }
            }

            return $municipalityx;
    }

    public function real_property_consolidated($params)
    {
        $form_56 = 2;
        $receipts = Receipt::whereMonth('report_date','=', $params['month'])
            ->where('is_printed', '=', 1)
            ->where('af_type', '=', $form_56)
            ->where('col_municipality_id', '=', $params['municipality'])
            ->orderBy('serial_no', 'ASC')
            ->get();

        $class_amt = array();
        $class_amtx = array(
                'basic_current' => 0,
                'basic_discount' => 0,
                'basic_previous' => 0,
                'basic_penalty_current' => 0,
                'basic_penalty_previous' => 0,
            );
        $classes = F56Type::geT();
        foreach ($classes as $class) {
            $class_amt[$class->id] = array(
                'basic_current' => 0,
                'basic_discount' => 0,
                'basic_previous' => 0,
                'basic_penalty_current' => 0,
                'basic_penalty_previous' => 0,
            );
        }

        foreach ($receipts as $rcpt_index => $receipt) {

            if ($receipt->is_cancelled) {
                continue;
            }

            if (isset($receipt->F56Detail)) {
                $index = $receipt->F56Detail->col_f56_type_id;
                $class_amt[$index]['basic_current'] += $receipt->F56Detail->basic_current;
                $class_amt[$index]['basic_discount'] += $receipt->F56Detail->basic_discount;
                $class_amt[$index]['basic_previous'] += $receipt->F56Detail->basic_previous;
                $class_amt[$index]['basic_penalty_current'] += $receipt->F56Detail->basic_penalty_current;
                $class_amt[$index]['basic_penalty_previous'] += $receipt->F56Detail->basic_penalty_previous;
            }
            $rcpt_done = 0;
            if (isset($receipt->F56Detail)) {
            foreach ($receipt->f56detail->TDARP as $tan){
                if ($rcpt_done == 0){
                    $rcpt_done = 1;
                            $class_amtx['basic_current'] += $receipt->F56Detail->basic_current;
                            $class_amtx['basic_discount'] += $receipt->F56Detail->basic_discount;
                            $class_amtx['basic_previous'] += $receipt->F56Detail->basic_previous;
                            $class_amtx['basic_penalty_current'] += $receipt->F56Detail->basic_penalty_current;
                            $class_amtx['basic_penalty_previous'] += $receipt->F56Detail->basic_penalty_previous;
                        }
                }

            }

        }

        $data[$params['municipality']]['f56_type'] = F56Type::get();
        $data[$params['municipality']]['receipts'] = $receipts;
        $data[$params['municipality']]['class_amt'] = $class_amt;
        $data[$params['municipality']]['class_amtx'] = $class_amtx;

        return $data;

    }


    public function get_shared_bac_report($params){
        $municipalities = Municipality::all();
        $municipalityx=[];
            foreach ($municipalities as $municipality) {
                if($municipality->id != 14){
                    $params['municipality'] = $municipality->id;
                    $municipalityx[$municipality->name]= $this->get_shared_bac_reportx($params);
                }
            }

            return $municipalityx;
    }

    protected function get_shared_bac_reportx($params) {
        $insurance_premium = 42;
        $date_start = $params['year'].'-'.$params['month'].'-01';
        $date_end = date('Y-m-t', strtotime($date_start));

        $municipality = Municipality::whereId($params['municipality'])->first();
        $municipality = $municipality->name;
        $res = [];
        # ADD VALUES NOT IN MONTHLY REPORT
        $receipts = Receipt::where('report_date','>=' , $date_start)
                                    ->where('report_date','<=' , $date_end )
                                    ->where('is_cancelled','=','0')
                                    ->get();

        foreach ($receipts as $receipt) {

            foreach ($receipt->items as $item) {
                $ref = ($item->col_acct_title_id != 0) ? 'acct_title' : 'acct_subtitle';
                if ($item->{$ref}->show_in_monthly == 1) {
                    continue;
                }

                $naturex = trim($item->nature);
                $value = ($item->$ref->id == $insurance_premium) ? $item->value - 15 : $item->value;
                if (!isset($res[$item->{$ref}->id])) {
                    $cat = '';
                    if ($item->col_acct_title_id != 0) {
                        $cat = $item->$ref->group->category->name;
                    } else {
                        $cat = $item->$ref->title->group->category->name;
                    }


                    if( $ref == 'acct_title' && $item->$ref->id == 16 ){
                        $res[$item->$ref->id][ $naturex ] = [
                            'name' => $item->$ref->name.' ('.$cat.')',
                            'value' => $value,
                        ];
                    }else{
                        $res[$item->$ref->id] = [
                            'name' => $item->$ref->name.' ('.$cat.')',
                            'value' => $value
                        ];
                    }



                }elseif ( (!isset($res[$item->{$ref}->id][ $naturex ])) && $item->$ref->id == 16 && $ref == 'acct_title') {
                    $cat = '';
                    if ($item->col_acct_title_id != 0) {
                        $cat = $item->$ref->group->category->name;
                    } else {
                        $cat = $item->$ref->title->group->category->name;
                    }
                        $res[$item->$ref->id][ $naturex ] = [
                            'name' => $item->$ref->name.' ('.$cat.')',
                            'value' => $value,
                        ];


                } else {
                    if($item->$ref->id == 16 && $ref == 'acct_title' ){

                         $res[$item->$ref->id][ $naturex ]['value'] += $value;
                    }else{

                        $res[$item->$ref->id]['value'] += $value;
                    }

                }

            }
        }
        $res[$municipality] = [];
            $receipts = Receipt::where('report_date','>=' , $date_start)
                            ->where('report_date','<=' , $date_end )
                            ->where('col_municipality_id', '=', $params['municipality'])
                            ->where('is_cancelled','=','0')
                            ->get();





        foreach($receipts as $receipt) {

            if(!$receipt->items->whereIn('col_acct_title_id',[2])->isEmpty()){
                foreach ($receipt->F56Detailmny as $key => $fdetail) {
                    if (!isset($res[$fdetail->TDARPX->barangay_name->name])) {
                        $res[$fdetail->TDARPX->barangay_name->name] = array();
                    }
                }

            }else{
                if( !isset($receipt->barangay->name) ){
                    Session::flash('error', ['PLEASE CHECK SERIAL : '.$receipt->serial_no.' NO BARANGAY has been set']);
                    return redirect()->route('report.shared');
                }
            }




            if ($receipt->af_type == 1) {
                foreach($receipt->items as $item) {
                    $type = ($item->col_acct_title_id != 0) ? 'title' : 'subtitle';
                    $ref = ($item->col_acct_title_id != 0) ? 'acct_title' : 'acct_subtitle';
                    $id = ($item->col_acct_title_id != 0) ? $item->col_acct_title_id : $item->col_acct_subtitle_id;

                    if (isset($item->$ref->rate)) {
                        if ($item->$ref->rate->is_shared != 1) {
                            # not shared
                            continue;
                        }
                    } else {
                        # no rate
                        continue;
                    }

                    if (!isset($res[$receipt->barangay->name][$type.$id])) {
                        $res[$receipt->barangay->name][$type.$id] = [
                            'name' => $item->$ref->name,
                            'value' => $item->share_barangay,
                        ];
                    } else {
                        $res[$receipt->barangay->name][$type.$id]['value'] += $item->share_barangay;
                    }

                    if (!isset($res[$municipality][$type.$id])) {
                        $res[$municipality][$type.$id] = [
                            'name' => $item->$ref->name,
                            'value' => $item->share_municipal,
                        ];
                    } else {
                        $res[$municipality][$type.$id]['value'] += $item->share_municipal;
                    }
                }
            } else {
                # compute amt for shared
                if($receipt->F56Detail){
                     foreach ($receipt->F56Detailmny as $key => $fdetail) {
                        $basic_municipal = (round($fdetail->basic_current * .40, 2) + round($fdetail->basic_previous * .40, 2)) - round($fdetail->basic_discount * .40, 2);
                        $basicpenalty_municipal = round($fdetail->basic_penalty_current * .40, 2) + round($fdetail->basic_penalty_previous * .40, 2);
                        $basic_barangay = (round($fdetail->basic_current * .25, 2) + round($fdetail->basic_previous * .25, 2)) - round($fdetail->basic_discount * .25, 2);
                        $basicpenalty_barangay = round($fdetail->basic_penalty_current * .25, 2) + round($fdetail->basic_penalty_previous * .25, 2);

                        $sef_municipal = (bcdiv($fdetail->basic_current * .5, 1, 2) + bcdiv($fdetail->basic_previous * .5, 1, 2)) - bcdiv($fdetail->basic_discount * .5, 1, 2);
                        $sefpenalty_municipal = bcdiv($fdetail->basic_penalty_current * .5, 1, 2) + bcdiv($fdetail->basic_penalty_previous * .5, 1, 2);



                        if (!isset($res[$municipality]['title2'])) {
                            $res[$municipality]['title2'] = [ 'name' => 'Real Property Tax-Basic (Net of Discount)', 'value' => $basic_municipal ];
                            $res[$municipality]['subtitle1'] = [ 'name' => 'Tax Revenue - Fines & Penalties - Real Property Taxes', 'value' => $basicpenalty_municipal ];
                            $res[$municipality]['title47'] = [ 'name' => 'Special Education Tax', 'value' => $sef_municipal ];
                            $res[$municipality]['title48'] = [ 'name' => 'Tax Revenue - Fines & Penalties - Property Tax', 'value' => $sefpenalty_municipal ];
                        } else {
                            $res[$municipality]['title2']['value'] += $basic_municipal;
                            $res[$municipality]['subtitle1']['value'] += $basicpenalty_municipal;
                            $res[$municipality]['title47']['value'] += $sef_municipal;
                            $res[$municipality]['title48']['value'] += $sefpenalty_municipal;
                        }

                         if (!isset($res[$fdetail->TDARPX->barangay_name->name]['title2'])) {
                            $res[$fdetail->TDARPX->barangay_name->name]['title2'] = [ 'name' => 'Real Property Tax-Basic (Net of Discount)', 'value' => $basic_barangay ];
                            $res[$fdetail->TDARPX->barangay_name->name]['subtitle1'] = [ 'name' => 'Tax Revenue - Fines & Penalties - Real Property Taxes', 'value' => $basicpenalty_barangay ];
                        } else {
                            $res[$fdetail->TDARPX->barangay_name->name]['title2']['value'] += $basic_barangay;
                            $res[$fdetail->TDARPX->barangay_name->name]['subtitle1']['value'] += $basicpenalty_barangay;
                        }
                    }
                }
            }
        }

         $cashdivs = CashDivision::where('date_of_entry','>=' , $date_start)
                            ->where('date_of_entry','<=' , $date_end )
                            ->where('col_municipality_id', '=', $params['municipality'])
                            ->get();

         foreach($cashdivs as $cashdiv) {


                    foreach($cashdiv->items as $item) {
                            $type = ($item->col_acct_title_id != 0) ? 'title' : 'subtitle';
                            $ref = ($item->col_acct_title_id != 0) ? 'acct_title' : 'acct_subtitle';
                            $id = ($item->col_acct_title_id != 0) ? $item->col_acct_title_id : $item->col_acct_subtitle_id;

                            if (isset($item->$ref->rate)) {
                                if ($item->$ref->rate->is_shared != 1) {
                                    # not shared
                                    continue;
                                }
                            } else {
                                # no rate
                                continue;
                            }
                            if(!$cashdiv->barangay){
                                dd($cashdiv);
                            }
                            if (!isset($res[$cashdiv->barangay->name][$type.$id])) {
                                $res[$cashdiv->barangay->name][$type.$id] = [
                                    'name' => $item->$ref->name,
                                    'value' => $item->share_barangay,
                                ];
                            } else {
                                $res[$cashdiv->barangay->name][$type.$id]['value'] += $item->share_barangay;
                            }

                            if (!isset($res[$municipality][$type.$id])) {
                                $res[$municipality][$type.$id] = [
                                    'name' => $item->$ref->name,
                                    'value' => $item->share_municipal,
                                ];
                            } else {
                                $res[$municipality][$type.$id]['value'] += $item->share_municipal;
                            }
                        }
        }

        return $res;
    }
}
