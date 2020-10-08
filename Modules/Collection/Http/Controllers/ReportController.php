<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\{Controller,BreadcrumbsController};
use Illuminate\Http\{Request,Response};
use Illuminate\Support\Facades\{DB,Session};

use Modules\Collection\Entities\{
        AllowedMonths,AccountTitle,AccountCategory,AccountSubtitle,CashDivision,CashDivisionItems,Barangay,BudgetEstimate,
        F56Type,F56Detail,Municipality,Receipt,ReceiptItems,Serial,TransactionType,RcptCertificate,RcptCertificateType,ReportOfficers,
        MonthlyProvincialIncome,OtherFeesCharges,SandGravelTypes,Customer,ReportOfficerNew
    };

use Excel,PDF,Carbon\Carbon;

use PHPExcel_Worksheet_Drawing;

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
        $this->base['report_officers'] = ReportOfficerNew::join('col_report_officer_position', 'col_report_officer_position.id', '=', 'col_new_report_officers.position_name')->select(DB::raw('col_new_report_officers.id as officer_id, col_new_report_officers.position_name as position_id'), 'position', 'officer_name')->where('col_new_report_officers.deleted_at', null)
        ->where('col_report_officer_position.deleted_at', null)
        ->get();
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

        $this->base['acctble_officer_name'] = ReportOfficers::whereId(10)->first();
        $this->base['acctble_officer_position'] = ReportOfficers::whereId(11)->first();

        $d = 0;
        $provincial_share = 0;
        $prev_mun_rpt = [];
        $prev_prov_share_prmun  = [];
        for($x=1; $x<=$end_mnth ; $x++){
            $prov_share_prday = 0;
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
                ->whereIn('client_type', array(1,2,3,4,16,5,6)) //
                ->get();

                $cashdiv = DB::table('col_cash_division')
                ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                ->select(db::raw('col_cash_division.col_municipality_id, SUM(value) as value, SUM(value) as share_provincial'))
                ->where('col_cash_division.date_of_entry','=',$month_p[$x]['y-m-d'])
                ->where('col_cash_division_items.col_acct_title_id','=','4')
                ->where('col_municipality_id', $value['id'])
                ->whereIn('client_type', array(1,2,3,4,5,6,16))
                ->whereNull('col_cash_division.deleted_at')
                ->get();
                // $provincial_share += $sand_gravel_fieldlandtax[0]->share_provincial;
                if(count($cashdiv) > 0) {
                    $provincial_share += (isset($cashdiv[0]->share_provincial) && gettype($cashdiv[0]->share_provincial) != null) ? round(floatval($sand_gravel_fieldlandtax[0]->share_provincial), 2) + round(floatval($cashdiv[0]->share_provincial), 2) : round(floatval($sand_gravel_fieldlandtax[0]->share_provincial), 2);
                } else {
                    $provincial_share += round(floatval($sand_gravel_fieldlandtax[0]->share_provincial), 2);
                }
                // $mun_rpt[$month_px[$x]['y-m-d']][$value['name']]['sand_gravel_fieldlandtax'] = $sand_gravel_fieldlandtax;
                if(count($cashdiv) > 0) {
                    $mun_rpt[$month_px[$x]['y-m-d']][$value['name']]['sand_gravel_fieldlandtax'] = (isset($cashdiv[0]->value) && gettype($cashdiv[0]->value) != null) ? round(floatval($sand_gravel_fieldlandtax[0]->value), 2) + round(floatval($cashdiv[0]->value), 2) : round(floatval($sand_gravel_fieldlandtax[0]->value), 2); 
                } else {
                    $mun_rpt[$month_px[$x]['y-m-d']][$value['name']]['sand_gravel_fieldlandtax'] = round(floatval($sand_gravel_fieldlandtax[0]->value), 2);
                }
                // $total_v +=  $sand_gravel_fieldlandtax[0]->value - $sand_gravel_fieldlandtax[0]->share_provincial;
                // $total_v +=  $sand_gravel_fieldlandtax[0]->value;
                if(count($cashdiv) > 0) {
                    $total_v += (isset($cashdiv[0]->value) && gettype($cashdiv[0]->value) != null) ? round(floatval($cashdiv[0]->value), 2) + round(floatval($sand_gravel_fieldlandtax[0]->value), 2) : round(floatval($sand_gravel_fieldlandtax[0]->value), 2);
                } else {
                    $total_v +=  round(floatval($sand_gravel_fieldlandtax[0]->value), 2);
                }
                $mun_rpt[$month_px[$x]['y-m-d']][$value['name']]['total_v'] = round(floatval($total_v), 2);
                $prov_share_prday += (isset($cashdiv[0]->share_provincial) && gettype($cashdiv[0]->share_provincial) != null) ? round(floatval($sand_gravel_fieldlandtax[0]->share_provincial), 2) + round(floatval($cashdiv[0]->share_provincial), 2) : round(floatval($sand_gravel_fieldlandtax[0]->share_provincial), 2);
                // $mun_rpt[$month_px[$x]['y-m-d']][$value['name']]['prov_share'] = $prov_share_prday;
            }
            $mun_rpt[$month_px[$x]['y-m-d']]['prov_share'] = round(floatval($prov_share_prday), 2);
        }
        $provincial_sharex = 0;
        //$past_month = [];
        $date_mnthxx = $date_mnthx->format('Y').'-'.$date_mnthx->subMonth()->format('m').'-'.$date_mnthx->endOfMonth()->format('d') ;
        $date_mnthxxx = $date_mnthx->format('Y').'-01-01' ;
        foreach ($municpality as $key => $value) {
            $total_v = 0;
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
            //$past_month[$value['name']] = $sand_gravel_fieldlandtaxx[0]->value - $sand_gravel_fieldlandtaxx[0]->share_provincial;
            $provincial_sharex +=  round(floatval($sand_gravel_fieldlandtaxx[0]->share_provincial), 2);

            // for the past month
            $prev_month = $request->month-1;
            $sand_gravel_fieldlandtax_prev = DB::table('col_receipt')
                ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                ->select(db::raw('SUM(col_receipt_items.value) as value, SUM(col_receipt_items.share_provincial) as share_provincial'))
                ->whereMonth('col_receipt.report_date','<=', $prev_month)
                ->whereYear('col_receipt.report_date','=', $request->year)
                ->where('col_receipt_items.col_acct_title_id','=','4')
                ->where('col_municipality_id', $value['id'])
                ->where('col_receipt.is_printed','=','1')
                ->where('col_receipt.is_cancelled','=','0')
                ->whereIn('client_type', array(1,2,3,4,16,5,6)) //
                ->get();
            $cashdiv_prev = DB::table('col_cash_division')
                ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                ->select(db::raw('SUM(value) as value'))
                ->whereMonth('col_cash_division.date_of_entry','<=', $prev_month)
                ->whereYear('col_cash_division.date_of_entry','=', $request->year)
                ->where('col_cash_division_items.col_acct_title_id','=','4')
                ->where('col_municipality_id', $value['id'])
                ->whereIn('client_type', array(1,2,3,4,5,6,16))
                ->whereNull('col_cash_division.deleted_at')
                ->get();

            if(count($cashdiv_prev) > 0) {
                $total_v += (isset($cashdiv_prev[0]->value) && gettype($cashdiv_prev[0]->value) != null) ? round(floatval($cashdiv_prev[0]->value), 2) + round(floatval($sand_gravel_fieldlandtax_prev[0]->value), 2) : round(floatval($sand_gravel_fieldlandtax_prev[0]->value), 2);
            } else {
                $total_v +=  round(floatval($sand_gravel_fieldlandtax_prev[0]->value), 2);
            }

            $prev['mun_rpt'][$value['name']] = round(floatval($total_v), 2);
            // dd($prev_mun_rpt);
            $prev['prov_share_prmun'][$value['name']] =+ (isset($cashdiv_prev[0]->value) && gettype($cashdiv_prev[0]->value) != null) ? round(floatval($sand_gravel_fieldlandtax_prev[0]->share_provincial), 2) + round(floatval($cashdiv_prev[0]->value), 2) : (isset($sand_gravel_fieldlandtax_prev[0]->share_provincial) && gettype($sand_gravel_fieldlandtax_prev[0]->share_provincial) != null ? round(floatval($sand_gravel_fieldlandtax_prev[0]->share_provincial), 2) : 0);
        }

        $this->base['provincial_sharex'] = $provincial_sharex;
        $this->base['provincial_share'] = $provincial_share;
        $this->base['past_month'] = $prev;
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
            $client_types = SandGravelTypes::findMany([1,2,3,4,5,6,16]);
            $sg_types = [];
            $sum_collect_client = [];
            foreach($client_types as $key => $val) {
                $sg_types[$val->id] = $val->description;
            }
            $prev_month_totals = [];
            foreach ($municpality as $key => $mun) {
                $landtaxsharing[$mun['name']] = [];
                $prev_month_totals[$mun['name']] = [];
                $data = DB::table('col_receipt')
                    ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                    ->select(db::raw('col_receipt.col_municipality_id, SUM(col_receipt_items.share_municipal) as value, SUM(col_receipt_items.share_provincial) as share_provincial_value, client_type'))
                    ->whereMonth('col_receipt.report_date','=',$request->month)
                    ->whereYear('col_receipt.report_date','=',$request->year)
                    ->where('col_receipt_items.col_acct_title_id','=','4')
                    ->where('col_receipt.is_printed','=','1')
                    ->where('col_receipt.is_cancelled','=','0')
                    ->where('col_receipt.col_municipality_id','=',$mun['id'])
                    ->whereIn('client_type', array(1,2,3,4,16,5,6)) //
                    // ->groupBy('col_receipt.col_municipality_id')
                    ->get();

                $datac = DB::table('col_cash_division')
                    ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                    ->select(db::raw('col_cash_division.col_municipality_id, SUM(col_cash_division_items.value) as share_provincial_value, client_type'))
                    ->whereMonth('col_cash_division.date_of_entry','=',$request->month)
                    ->whereYear('col_cash_division.date_of_entry','=',$request->year)
                    ->where('col_cash_division_items.col_acct_title_id','=','4')
                    ->where('col_municipality_id','=',$mun['id'])
                    ->whereNull('col_cash_division.deleted_at')
                    ->whereIn('client_type', array(1,2,3,4,5,6,16))
                    // ->groupBy('col_municipality_id')
                    ->get();

                $landtaxsharing[$mun['name']]['value'] = $data[0]->value; // municipal
                $landtaxsharing[$mun['name']]['provincial_value'] = $data[0]->share_provincial_value + $datac[0]->share_provincial_value;
                //$landtaxsharing[$mun['name']]['client_type'] = $data[0]->value; // municipal

                $sum_collect_client[$data[0]->client_type] =+ $data[0]->value + $data[0]->share_provincial_value;
                $sum_collect_client[$datac[0]->client_type] =+ $datac[0]->share_provincial_value;

                foreach (Municipality::where('id','=',$mun['id'])->first()->barangays as $key => $brgy){
                    $datax = DB::table('col_receipt')
                        ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                        ->select(db::raw('col_receipt.col_barangay_id , SUM(col_receipt_items.share_barangay) as value, client_type'))
                        ->whereMonth('col_receipt.report_date','=',$request->month)
                        ->whereYear('col_receipt.report_date','=',$request->year)
                        ->where('col_receipt_items.col_acct_title_id','=','4')
                        ->where('col_receipt.is_printed','=','1')
                        ->where('col_receipt.is_cancelled','=','0')
                        ->where('col_receipt.col_barangay_id','=',$brgy->id)
                        // ->groupBy('col_receipt_items.col_receipt_id')
                        // ->groupBy('col_receipt.col_barangay_id')
                        ->get();


                     // $dataxc = DB::table('col_cash_division')
                     //    ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                     //    ->select(db::raw('col_cash_division.col_barangay_id , SUM(col_cash_division_items.value) as value'))
                     //    ->whereMonth('col_cash_division.date_of_entry','=',$request->month)
                     //    ->whereYear('col_cash_division.date_of_entry','=',$request->year)
                     //    ->where('col_cash_division_items.col_acct_title_id','=','4')
                     //    ->whereNull('col_cash_division.deleted_at')
                     //    ->whereIn('client_type', array(1,2,3,4,5,6,16))
                     //    ->where('col_barangay_id','=',$brgy->id)
                     //    // ->groupBy('col_barangay_id')
                     //    ->get();

                    if(!empty($datax)){
                        if($datax[0]->value > 0 ){
                            $landtaxsharing[$mun['name']]['brgy'][$brgy->name] =+ $datax[0]->value;
                            $sum_collect_client[$datax[0]->client_type] =+ $datax[0]->value;
                        }
                    }

                    // if(!empty($dataxc)){
                    //     if($dataxc[0]->value > 0){
                    //         $landtaxsharing[$mun['name']]['brgy'][$brgy->name] =+ $dataxc[0]->value;
                    //     }
                    // }

                    // $landtaxsharing[$mun['name']]['brgy'][$brgy->name] = isset($datax[0]->value) && !is_null($datax[0]->value) ? 
                    //     (isset($dataxc[0]->value) && !is_null($dataxc[0]->value) ? $datax[0]->value + $dataxc[0]->value : $datax[0]->value) 
                    //     : (isset($dataxc[0]->value) && !is_null($dataxc[0]->value) ? $dataxc[0]->value : null);

                    // $landtaxsharing[$mun['name']]['brgy'][$brgy->name] = $datax[0]->value + $dataxc[0]->value;
                }
            }

            // previous months total's
                if($request->month != 1) {
                    $data_prev = DB::table('col_receipt')
                        ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                        ->select(db::raw('col_receipt.col_municipality_id, SUM(col_receipt_items.share_municipal) as value, SUM(col_receipt_items.share_provincial) as share_provincial_value, client_type'))
                        ->whereMonth('col_receipt.report_date','<',$request->month)
                        ->whereYear('col_receipt.report_date','=',$request->year)
                        ->where('col_receipt_items.col_acct_title_id','=','4')
                        ->where('col_receipt.is_printed','=','1')
                        ->where('col_receipt.is_cancelled','=','0')
                        ->whereIn('client_type', array(1,2,3,4,5,6,16)) //
                        ->get();

                    $datac_prev = DB::table('col_cash_division')
                        ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                        ->select(db::raw('col_cash_division.col_municipality_id, SUM(col_cash_division_items.value) as share_provincial_value, client_type'))
                        ->whereMonth('col_cash_division.date_of_entry','<',$request->month)
                        ->whereYear('col_cash_division.date_of_entry','=',$request->year)
                        ->where('col_cash_division_items.col_acct_title_id','=','4')
                        ->whereNull('col_cash_division.deleted_at')
                        ->whereIn('client_type', array(1,2,3,4,5,6,16))
                        ->get();

                    $data_prev_brgy = DB::table('col_receipt')
                        ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                        ->select(db::raw('col_receipt.col_municipality_id, SUM(col_receipt_items.share_barangay) as share_brgy_value, client_type'))
                        ->whereMonth('col_receipt.report_date','<',$request->month)
                        ->whereYear('col_receipt.report_date','=',$request->year)
                        ->where('col_receipt_items.col_acct_title_id','=','4')
                        ->where('col_receipt.is_printed','=','1')
                        ->where('col_receipt.is_cancelled','=','0')
                        ->get();

                    $prev_month_totals['brgy_value'] =+ $data_prev_brgy[0]->share_brgy_value;
                    $prev_month_totals['municipal_value'] =+ $data_prev[0]->value; // municipal
                    $prev_month_totals['provincial_value'] =+ $data_prev[0]->share_provincial_value + $datac_prev[0]->share_provincial_value;
                }

            // $landtaxsharing[$mun['name']]['brgy'][$brgy->name] =+ $dataxc[0]->value;
            $this->base['landtaxsharing'] = $landtaxsharing;
            $this->base['client_types'] = $sg_types;
            $this->base['summary_per_client'] = $sum_collect_client; 
            $this->base['prev_month'] = $prev_month_totals;

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

         }elseif(isset($request['button_taxcollected_clienttype'])){
            $this->data['year'] = $request->year;
            $this->data['month'] = $request->month;
            $sandgraveltypes = SandGravelTypes::findMany([1,2,3,4,16,5,6]);

            // get provincial share value for current and previous month
            // $municpality = Municipality::all()->toarray();

            // foreach ($municpality as $mun) {
                $data = DB::table('col_receipt')
                    ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                    ->select(db::raw('col_receipt.col_municipality_id, SUM(col_receipt_items.share_provincial) as share_provincial_value'))
                    ->whereMonth('col_receipt.report_date','=',$request->month)
                    ->whereYear('col_receipt.report_date','=',$request->year)
                    ->where('col_receipt_items.col_acct_title_id','=','4')
                    ->where('col_receipt.is_printed','=','1')
                    ->where('col_receipt.is_cancelled','=','0')
                    ->whereIn('client_type', array(1,2,3,4,5,6,16))
                    // ->where('col_receipt.col_municipality_id','=',$mun['id'])
                    ->get();

                $datac = DB::table('col_cash_division')
                    ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                    ->select(db::raw('col_cash_division.col_municipality_id, SUM(col_cash_division_items.value) as share_provincial_value'))
                    ->whereMonth('col_cash_division.date_of_entry','=',$request->month)
                    ->whereYear('col_cash_division.date_of_entry','=',$request->year)
                    ->where('col_cash_division_items.col_acct_title_id','=','4')
                    ->whereNull('col_cash_division.deleted_at')
                    ->whereIn('client_type', array(1,2,3,4,5,6,16))
                    // ->where('col_municipality_id','=',$mun['id'])
                    ->get();

                // for the previous month..
                $data1 = DB::table('col_receipt')
                    ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                    ->select(db::raw('col_receipt.col_municipality_id, SUM(col_receipt_items.share_provincial) as share_provincial_value'))
                    ->whereMonth('col_receipt.report_date','=',$request->month-1)
                    ->whereYear('col_receipt.report_date','=',$request->year)
                    ->where('col_receipt_items.col_acct_title_id','=','4')
                    ->where('col_receipt.is_printed','=','1')
                    ->where('col_receipt.is_cancelled','=','0')
                    ->whereIn('client_type', array(1,2,3,4,5,6,16)) //
                    // ->where('col_receipt.col_municipality_id','=',$mun['id'])
                    ->get();
                $datac1 = DB::table('col_cash_division')
                    ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                    ->select(db::raw('col_cash_division.col_municipality_id, SUM(col_cash_division_items.value) as share_provincial_value'))
                    ->whereMonth('col_cash_division.date_of_entry','=',$request->month-1)
                    ->whereYear('col_cash_division.date_of_entry','=',$request->year)
                    ->where('col_cash_division_items.col_acct_title_id','=','4')
                    ->whereNull('col_cash_division.deleted_at')
                    ->whereIn('client_type', array(1,2,3,4,5,6,16))
                    // ->where('col_municipality_id','=',$mun['id'])
                    ->get();
                $provSharePrevMnth = $data1[0]->share_provincial_value  + $datac1[0]->share_provincial_value;
                // dd($totalProvincialShare);
            // }
            // end provincial share value..
            $dt =  Carbon::createFromDate($request->year,$request->month);

            $dtn = Carbon::createFromDate($request->year,$request->month);

            $dtx = [];

            for ($i=1; $i <= $dtn->endOfMonth()->format('d'); $i++) {
                    $dtx[$i] = Carbon::createFromDate($request->year,$request->month,$i);

                    if(!in_array($dtx[$i]->format('D'), ['Sun','Sat'])){

                    foreach ($sandgraveltypes as $key => $type) {
                        // original, working
                        // $data = DB::table('col_receipt')
                        //     ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                        //     ->select(db::raw('SUM(col_receipt_items.value) as value'))
                        //     ->where('col_receipt.report_date','=',$dtx[$i]->format('Y-m-d'))
                        //     ->where('col_receipt_items.col_acct_title_id','=','4')
                        //     ->where('col_receipt.is_printed','=','1')
                        //     ->where('col_receipt.is_cancelled','=','0')
                        //     ->where('col_receipt.client_type','=',$type['id'])
                        //     ->first();
                        // $datac = DB::table('col_cash_division')
                        //     ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                        //     ->select(db::raw('SUM(value) as value'))
                        //     ->where('col_cash_division.date_of_entry','=',$dtx[$i]->format('Y-m-d'))
                        //     ->where('col_cash_division_items.col_acct_title_id','=','4')
                        //     ->where('col_cash_division.client_type', '=', $type['id'])
                        //     ->whereNull('col_cash_division.deleted_at')
                        //     ->first();

                        $data = DB::table('col_receipt')
                            ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                            ->select('serial_no', DB::raw('sum(value) as value, sum(share_provincial) as prov_share'))
                            ->where('col_receipt.report_date','=',$dtx[$i]->format('Y-m-d'))
                            ->where('col_receipt_items.col_acct_title_id','=','4')
                            ->where('col_receipt.is_printed','=','1')
                            ->where('col_receipt.is_cancelled','=','0')
                            ->where('col_receipt.client_type','=',$type['id'])
                            ->groupBy('serial_no')
                            ->get();
                        $datac = DB::table('col_cash_division')
                            ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                            // ->join('col_receipt', 'col_cash_division.col_customer_id', '=', 'col_receipt.col_customer_id')
                            ->select(DB::raw('sum(value) as value'), 'refno')
                            ->where('col_cash_division.date_of_entry','=',$dtx[$i]->format('Y-m-d'))
                            ->where('col_cash_division_items.col_acct_title_id','=','4')
                            ->where('col_cash_division.client_type', '=', $type['id'])
                            ->whereNull('col_cash_division.deleted_at')
                            ->get();

                        // original
                        // $types[$dtx[$i]->format('j')][$type['id']] = $data;

                        // if(isset($datac->value)) {
                        //     $types[$dtx[$i]->format('j')][$type['id']] = $datac;
                        // } else {
                        //     $types[$dtx[$i]->format('j')][$type['id']] = $data;
                        // }

                        // original, working
                        // if(isset($datac->value) && !is_null($datac->value)) {
                        //     $types[$dtx[$i]->format('j')][$type['id']] = (isset($data->value) && !is_null($data->value)) ? $datac->value + $data->value : $datac->value;
                        // } else {
                        //     $types[$dtx[$i]->format('j')][$type['id']] = $data->value;
                        // }


                        foreach($datac as $dtc) {
                            if(isset($dtc->value) && !is_null($dtc->value)) {
                                // $types[$dtx[$i]->format('j')][$type['id']][$dtc->serial_no] =+ $dtc->value;
                                $or_no = preg_replace("/[^0-9]/", "",$dtc->refno);
                                if(strlen($or_no) > 7) {
                                    $or_no = substr($or_no, 3, 7); // remove year
                                }
                                if(substr($or_no, 0, 2) == '20') {
                                    $or_no = "";
                                }
                                // $types[$dtx[$i]->format('j')][$type['id']][$or_no] =+ $dtc->value; 
                                // $types[$dtx[$i]->format('j')]['prov_share'] = $dtc->value;
                                $types[$dtx[$i]->format('j')][$type['id']][$or_no]['val'] =+ $dtc->value; 
                                $types[$dtx[$i]->format('j')][$type['id']][$or_no]['prov_share'] =+ $dtc->value;
                            }
                        }

                        foreach($data as $dt) {
                            if(isset($dt->value) && !is_null($dt->value)) {
                                // $types[$dtx[$i]->format('j')][$type['id']]['val'] =+ $dt->value; 
                                // $types[$dtx[$i]->format('j')]['prov_share'] =+ $dt->prov_share;
                                $types[$dtx[$i]->format('j')][$type['id']][$dt->serial_no]['val'] =+ $dt->value; 
                                $types[$dtx[$i]->format('j')][$type['id']][$dt->serial_no]['prov_share'] =+ $dt->prov_share;
                            }
                        }
                        // foreach($datac_prev as $dtc) {
                        //     if(isset($dtc->value) && !is_null($dtc->value)) {
                        //         $or_no = preg_replace("/[^0-9]/", "",$dtc->refno);
                        //         if(strlen($or_no) > 7) {
                        //             $or_no = substr($or_no, 3, 7); // remove year
                        //         }
                        //         if(substr($or_no, 0, 2) == '20') {
                        //             $or_no = "";
                        //         }
                        //         $types[$dtx[$i]->format('j')][$type['id']][$or_no] =+ $dtc->value;
                        //     }
                        // }
                        // foreach($data_prev as $dt) {
                        //     if(isset($dt->value) && !is_null($dt->value)) {
                        //         $types[$dtx[$i]->format('j')][$type['id']][$dt->serial_no] =+ $dt->value;
                        //     }
                        // }
                    }
                }
                     // die;
            } 
            $this->base['dailygraveltypes'] = $types;
            
            $dpwh_total = 0;
            // $totalCurr_provShare = 0;
            foreach($types as $key => $val) {
                foreach($val as $ctype => $vals) {
                    if($ctype == 3) {
                        foreach($vals as $key => $val) {
                            $dpwh_total += $val['val'];
                            // $totalCurr_provShare += $val['prov_share'];
                        }
                    }
                }
            }

            $this->base['dpwh_total'] = $dpwh_total;
            // $this->base['current_provshare'] = $totalCurr_provShare;
            // dd($dpwh_total);

            $typesx = [];
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
                $datac = DB::table('col_cash_division')
                    ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                    ->select(db::raw('SUM(value) as value'))
                    ->whereMonth('col_cash_division.date_of_entry','=',$request->month)
                    ->whereYear('col_cash_division.date_of_entry','=',$request->year)
                    ->where('col_cash_division_items.col_acct_title_id','=','4')
                    ->where('client_type', '=', $type['id'])
                    ->whereNull('col_cash_division.deleted_at')
                    ->first();

                $typesx[$type['description']] = $data;

                if(isset($datac->value)) {
                    $typesx[$type['description']] = $datac;
                } else {
                    $typesx[$type['description']] = $data;
                }

                // if(isset($datac->value) && !is_null($datac->value)) {
                //     $typesx[$type['description']] =+ isset($data->value) && !is_null($data->value) ? $datac->value + $data->value : $datac->value;
                // } else {
                //     $typesx[$type['description']] =+ $data->value;
                // }
            }

            // previous months
            $totalProvincialShare = 0;
            $prev_month_totals = [];

            $data_prev = DB::table('col_receipt')
                ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                ->select('client_type', DB::raw('sum(value) as value, sum(share_provincial) as prov_share'))
                ->whereMonth('col_receipt.report_date','<',$request['month'])
                ->whereYear('col_receipt.report_date','=',$request['year'])
                ->where('col_receipt_items.col_acct_title_id','=','4')
                ->whereIn('client_type', [1,2,3,4,5,6,16])
                ->where('col_receipt.is_printed','=','1')
                ->where('col_receipt.is_cancelled','=','0')
                ->groupBy('client_type')
                ->get();
            $datac_prev = DB::table('col_cash_division')
                ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                ->select(DB::raw('sum(value) as value'), 'client_type')
                ->whereMonth('col_cash_division.date_of_entry','<',$request['month'])
                ->whereYear('col_cash_division.date_of_entry','=',$request['year'])
                ->where('col_cash_division_items.col_acct_title_id','=','4')
                ->whereIn('col_cash_division.client_type', [1,2,3,4,5,6,16])
                ->whereNull('col_cash_division.deleted_at')
                ->groupBy('col_cash_division.client_type')
                ->get(); 
                // dd($data_prev);
            // $prev_month_total_col = 0;
            if(count($data_prev) > 0 || count($datac_prev) > 0) {
                $totalProvincialShare = isset($data_prev[0]->prov_share) ? (isset($datac_prev[0]->value) ? $data_prev[0]->prov_share  + $datac_prev[0]->value : $data_prev[0]->prov_share) : (isset($datac_prev[0]->value) ? $datac_prev[0]->value : 0);
                foreach($sandgraveltypes as $key => $type) {
                    // $prev_month_totals[$type->id] = 0;
                    $prev_month_totals[$type->id]['val'] = 0;
                    $prev_month_totals[$type->id]['prov_share'] = 0;
                    foreach($data_prev as $key => $receipt) {
                        // dd($receipt->client_type == $type->id);
                        $prev_month_totals[$type->id]['val'] += ($receipt->client_type == $type->id) ? floatval($receipt->value) : 0;
                        $prev_month_totals[$type->id]['prov_share'] += ($receipt->client_type == $type->id) ? floatval($receipt->prov_share) : 0;
                        // $prev_month_total_col += ($receipt->client_type == $type->id) ? floatval($receipt->value) : 0; 
                    }
                    foreach($datac_prev as $key => $cashdiv) {
                        $prev_month_totals[$type->id]['val'] += ($cashdiv->client_type == $type->id) ? floatval($cashdiv->value) : 0;
                        $prev_month_totals[$type->id]['prov_share'] += ($cashdiv->client_type == $type->id) ? floatval($cashdiv->value) : 0;
                        // $prev_month_total_col += ($cashdiv->client_type == $type->id) ? floatval($cashdiv->value) : 0;
                    }
                }
            } 
            // dd($prev_month_totals);
            // $this->base['prev_total_col'] = $prev_month_total_col;
            $this->base['prev_month_totals'] = $prev_month_totals;
            $this->base['provShare'] = $totalProvincialShare;
            $this->base['graveltypes'] = $typesx;
            $this->base['provSharePrevMnth'] = $provSharePrevMnth;

            // $pdf = PDF::loadView('collection::pdf/sand_gravel_taxcollected_perclient', $this->base);
            $pdf = PDF::loadView('collection::pdf/sand_gravel_taxcollected_perclient_new', $this->base);
            $pdf->setPaper('legal', 'portrait');
            return @$pdf->stream();

         }elseif(isset($request['button_delivery_reciept'])){
            $ds = [];
            $sg = [];

            $sgds = [];
            $sgds2 = [];

            $d = [];
            foreach ($municpality as $key => $mun) {
                $ds[$mun['name']][5] =  DB::table('col_receipt')
                    ->join('col_customer','col_customer.id','=','col_receipt.col_customer_id')
                    ->join('col_sandgravel_booklet_release','col_sandgravel_booklet_release.col_receipt_id','=','col_receipt.id')
                    ->select(db::raw('COUNT(col_sandgravel_booklet_release.id) as count_ds5,col_customer.id as customer_id,col_customer.name as customer_name'))
                    ->whereMonth('col_receipt.report_date','=',$request->month)
                    ->whereYear('col_receipt.report_date','=',$request->year)
                    ->where('col_receipt.is_printed','=','1')
                    ->where('col_receipt.is_cancelled','=','0')
                    ->where('col_receipt.client_type','=',5)
                    ->where('col_receipt.col_municipality_id','=',$mun['id'])
                    ->groupBy('col_receipt.col_customer_id')
                    ->get();

                $ds[$mun['name']][6] =  DB::table('col_receipt')
                    ->join('col_customer','col_customer.id','=','col_receipt.col_customer_id')
                    ->join('col_sandgravel_booklet_release','col_sandgravel_booklet_release.col_receipt_id','=','col_receipt.id')
                    ->select(db::raw('COUNT(col_sandgravel_booklet_release.id) as count_ds6,col_customer.id as customer_id,col_customer.name as customer_name'))
                    ->whereMonth('col_receipt.report_date','=',$request->month)
                    ->whereYear('col_receipt.report_date','=',$request->year)
                    ->where('col_receipt.is_printed','=','1')
                    ->where('col_receipt.is_cancelled','=','0')
                    ->where('col_receipt.client_type','=',6)
                    ->where('col_receipt.col_municipality_id','=',$mun['id'])
                    ->groupBy('col_receipt.col_customer_id')
                    ->get();

                    $sg[$mun['name']][5] =  DB::table('col_receipt')
                    ->join('col_customer','col_customer.id','=','col_receipt.col_customer_id')
                    ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                    ->select(db::raw('SUM(col_receipt_items.value) as value5,col_customer.id as customer_id,col_customer.name as customer_name'))
                    ->whereMonth('col_receipt.report_date','=',$request->month)
                    ->whereYear('col_receipt.report_date','=',$request->year)
                    ->where('col_receipt_items.col_acct_title_id','=','4')
                    ->where('col_receipt.is_printed','=','1')
                    ->where('col_receipt.is_cancelled','=','0')
                    ->where('col_receipt.client_type','=',5)
                    ->where('col_receipt.col_municipality_id','=',$mun['id'])
                    ->groupBy('col_receipt.col_customer_id')
                    ->get();

                    $sg[$mun['name']][6] =  DB::table('col_receipt')
                    ->join('col_customer','col_customer.id','=','col_receipt.col_customer_id')
                    ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                    ->select(db::raw('SUM(col_receipt_items.value) as value6,col_customer.id as customer_id,col_customer.name as customer_name'))
                    ->whereMonth('col_receipt.report_date','=',$request->month)
                    ->whereYear('col_receipt.report_date','=',$request->year)
                    ->where('col_receipt_items.col_acct_title_id','=','4')
                    ->where('col_receipt.is_printed','=','1')
                    ->where('col_receipt.is_cancelled','=','0')
                    ->where('col_receipt.client_type','=',6)
                    ->where('col_receipt.col_municipality_id','=',$mun['id'])
                    ->groupBy('col_receipt.col_customer_id')
                    ->get();

                    $sgds[$mun['name']][5] = array_merge($ds[$mun['name']][5],$sg[$mun['name']][5]);
                    $sgds[$mun['name']][6] = array_merge($ds[$mun['name']][6],$sg[$mun['name']][6]);
                    $sgds2[$mun['name']] = array_merge($sgds[$mun['name']][5],$sgds[$mun['name']][6]);
                    foreach ($sgds2[$mun['name']] as $key => $value) {
                        if(!isset($d[$mun['name']][$value->customer_id])){
                            $d[$mun['name']][$value->customer_id] = collect($value)->toArray();
                        }else{
                            $d[$mun['name']][$value->customer_id] = array_merge($d[$mun['name']][$value->customer_id], collect($value)->toArray() ) ;
                        }
                    }
            }


            $this->base['delivery_reciept'] = $d;



            $pdf = PDF::loadView('collection::pdf/sand_gravel_taxcollected_deliveryrecipt', $this->base);
            $pdf->setPaper('legal', 'portrait');
            return @$pdf->stream();



         }else if(isset($request['button_taxsharing_excel'])) {
            $this->base['officer'] = ReportOfficers::find(10);
            $this->base['position'] = ReportOfficers::find(11);
            foreach ($municpality as $key => $mun) {
                $landtaxsharing[$mun['name']] = [];
                $data = DB::table('col_receipt')
                    ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                    ->select(db::raw('col_receipt.col_municipality_id, SUM(col_receipt_items.share_municipal) as value, SUM(col_receipt_items.share_provincial) as share_provincial_value'))
                    ->whereMonth('col_receipt.report_date','=',$request->month)
                    ->whereYear('col_receipt.report_date','=',$request->year)
                    ->where('col_receipt_items.col_acct_title_id','=','4')
                    ->where('col_receipt.is_printed','=','1')
                    ->where('col_receipt.is_cancelled','=','0')
                    ->where('col_receipt.col_municipality_id','=',$mun['id'])
                    ->get();

                $datac = DB::table('col_cash_division')
                    ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                    ->select(db::raw('col_cash_division.col_municipality_id, SUM(col_cash_division_items.value) as share_provincial_value'))
                    ->whereMonth('col_cash_division.date_of_entry','=',$request->month)
                    ->whereYear('col_cash_division.date_of_entry','=',$request->year)
                    ->where('col_cash_division_items.col_acct_title_id','=','4')
                    ->where('col_municipality_id','=',$mun['id'])
                    ->whereNull('col_cash_division.deleted_at')
                    ->whereIn('client_type', array(1,2,3,4,5,6,16))
                    ->get();

                $landtaxsharing[$mun['name']]['value'] = $data[0]->value; // municipal
                $landtaxsharing[$mun['name']]['provincial_value'] = $data[0]->share_provincial_value + $datac[0]->share_provincial_value;

                foreach (Municipality::where('id','=',$mun['id'])->first()->barangays as $key => $brgy){
                    $datax = DB::table('col_receipt')
                        ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                        ->select(db::raw('col_receipt.col_barangay_id , SUM(col_receipt_items.share_barangay) as value'))
                        ->whereMonth('col_receipt.report_date','=',$request->month)
                        ->whereYear('col_receipt.report_date','=',$request->year)
                        ->where('col_receipt_items.col_acct_title_id','=','4')
                        ->where('col_receipt.is_printed','=','1')
                        ->where('col_receipt.is_cancelled','=','0')
                        ->where('col_receipt.col_barangay_id','=',$brgy->id)
                        ->get();

                    if(!empty($datax)){
                        if($datax[0]->value > 0 ){
                            $landtaxsharing[$mun['name']]['brgy'][$brgy->name] =+ $datax[0]->value;
                        }
                    }

                }
            }
            $this->base['landtaxsharing'] = $landtaxsharing;

            $data = $this->base;

            Excel::create('SAND and GRAVEL TAX AND PENALTIES SHARING', function($excel) use($data) {
                $excel->sheet('PENALTIES and COLLECTIONS', function($sheet) use($data) {
                    $sheet->loadView('collection::sandgravel_report.excel_tax_sharing', $data);
                });
            })->export('xls');
         }else if(isset($request['button_taxcollected_clienttype_excel'])) {
            $this->data['year'] = $request->year;
            $this->data['month'] = $request->month;
            $sandgraveltypes = SandGravelTypes::findMany([1,2,3,4,16,5,6]);

            // get provincial share value for current and previous month
                $data = DB::table('col_receipt')
                    ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                    ->select(db::raw('col_receipt.col_municipality_id, SUM(col_receipt_items.share_provincial) as share_provincial_value'))
                    ->whereMonth('col_receipt.report_date','=',$request->month)
                    ->whereYear('col_receipt.report_date','=',$request->year)
                    ->where('col_receipt_items.col_acct_title_id','=','4')
                    ->where('col_receipt.is_printed','=','1')
                    ->where('col_receipt.is_cancelled','=','0')
                    ->get();

                $datac = DB::table('col_cash_division')
                    ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                    ->select(db::raw('col_cash_division.col_municipality_id, SUM(col_cash_division_items.value) as share_provincial_value'))
                    ->whereMonth('col_cash_division.date_of_entry','=',$request->month)
                    ->whereYear('col_cash_division.date_of_entry','=',$request->year)
                    ->where('col_cash_division_items.col_acct_title_id','=','4')
                    ->whereNull('col_cash_division.deleted_at')
                    ->whereIn('client_type', array(1,2,3,4,5,6,16))
                    ->get();

                // for the previous month..
                $data1 = DB::table('col_receipt')
                    ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                    ->select(db::raw('col_receipt.col_municipality_id, SUM(col_receipt_items.share_provincial) as share_provincial_value'))
                    ->whereMonth('col_receipt.report_date','=',$request->month-1)
                    ->whereYear('col_receipt.report_date','=',$request->year)
                    ->where('col_receipt_items.col_acct_title_id','=','4')
                    ->where('col_receipt.is_printed','=','1')
                    ->where('col_receipt.is_cancelled','=','0')
                    ->get();
                $datac1 = DB::table('col_cash_division')
                    ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                    ->select(db::raw('col_cash_division.col_municipality_id, SUM(col_cash_division_items.value) as share_provincial_value'))
                    ->whereMonth('col_cash_division.date_of_entry','=',$request->month-1)
                    ->whereYear('col_cash_division.date_of_entry','=',$request->year)
                    ->where('col_cash_division_items.col_acct_title_id','=','4')
                    ->whereNull('col_cash_division.deleted_at')
                    ->whereIn('client_type', array(1,2,3,4,5,6,16))
                    ->get();

                $totalProvincialShare = $data[0]->share_provincial_value  + $datac[0]->share_provincial_value;
                $provSharePrevMnth = $data1[0]->share_provincial_value  + $datac1[0]->share_provincial_value;
            // end provincial share value..
            $dt =  Carbon::createFromDate($request->year,$request->month);

            $dtn = Carbon::createFromDate($request->year,$request->month);

            $dtx = [];


            for ($i=1; $i <= $dtn->endOfMonth()->format('d'); $i++) {
                    $dtx[$i] = Carbon::createFromDate($request->year,$request->month,$i);

                    if(!in_array($dtx[$i]->format('D'), ['Sun','Sat'])){

                    foreach ($sandgraveltypes as $key => $type) {
                        $data = DB::table('col_receipt')
                            ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                            ->select('serial_no', DB::raw('sum(value) as value'))
                            ->where('col_receipt.report_date','=',$dtx[$i]->format('Y-m-d'))
                            ->where('col_receipt_items.col_acct_title_id','=','4')
                            ->where('col_receipt.is_printed','=','1')
                            ->where('col_receipt.is_cancelled','=','0')
                            ->where('col_receipt.client_type','=',$type['id'])
                            ->groupBy('serial_no')
                            ->get();
                        $datac = DB::table('col_cash_division')
                            ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                            // ->join('col_receipt', 'col_cash_division.col_customer_id', '=', 'col_receipt.col_customer_id')
                            ->select('value', 'refno')
                            ->where('col_cash_division.date_of_entry','=',$dtx[$i]->format('Y-m-d'))
                            ->where('col_cash_division_items.col_acct_title_id','=','4')
                            ->where('col_cash_division.client_type', '=', $type['id'])
                            ->whereNull('col_cash_division.deleted_at')
                            ->get();


                        foreach($datac as $dtc) {
                            if(isset($dtc->value) && !is_null($dtc->value)) {
                                $or_no = preg_replace("/[^0-9]/", "",$dtc->refno);
                                if(strlen($or_no) > 7) {
                                    $or_no = substr($or_no, 3, 7); // remove year
                                }
                                if(substr($or_no, 0, 2) == '20') {
                                    $or_no = "";
                                }
                                $types[$dtx[$i]->format('j')][$type['id']][$or_no] =+ $dtc->value;
                            }
                        }
                        foreach($data as $dt) {
                            if(isset($dt->value) && !is_null($dt->value)) {
                                $types[$dtx[$i]->format('j')][$type['id']][$dt->serial_no] =+ $dt->value;
                            }
                        }
                    }
                }
            }
            $this->base['dailygraveltypes'] = $types;

            $typesx = [];
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
                $datac = DB::table('col_cash_division')
                    ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                    ->select(db::raw('SUM(value) as value'))
                    ->whereMonth('col_cash_division.date_of_entry','=',$request->month)
                    ->whereYear('col_cash_division.date_of_entry','=',$request->year)
                    ->where('col_cash_division_items.col_acct_title_id','=','4')
                    ->where('client_type', '=', $type['id'])
                    ->whereNull('col_cash_division.deleted_at')
                    ->first();

                $typesx[$type['description']] = $data;

                if(isset($datac->value)) {
                    $typesx[$type['description']] = $datac;
                } else {
                    $typesx[$type['description']] = $data;
                }
            }
            $this->base['graveltypes'] = $typesx;
            $this->base['provShare'] = $totalProvincialShare;
            $this->base['provSharePrevMnth'] = $provSharePrevMnth;

            $data = $this->base;

            Excel::create('SAND and GRAVEL TAX and PENALTIES COLLECTIONS BY CLIENT TYPE', function($excel) use($data) {
                $excel->sheet('PENALTIES and COLLECTIONS', function($sheet) use($data) {
                    $sheet->loadView('collection::sandgravel_report.excel_tax_client_type', $data);
                });
            })->export('xls');
         }else{
            // dd(public_path());
            Excel::create('SAND and GRAVEL PENALTIES and COLLECTIONS', function($excel) use($mun_rpt) {
                $excel->sheet('PENALTIES and COLLECTIONS', function($sheet) use($mun_rpt) {
                    $logo = new PHPExcel_Worksheet_Drawing;
                    $logo->setPath(public_path('asset/images/benguet-logo.png'));
                    $logo->setWidthAndHeight(75,75);
                    $logo->setResizeProportional();
                    $logo->setCoordinates('I1');
                    $logo->setWorksheet($sheet);
                    $sheet->setColumnFormat(array('B:P' => '0.00'));
                    $sheet->loadView('collection::sandgravel_report.excel_mncpal_collection')->with('base', $this->base);
                });
            })->export('xls');
         }

        // for testing
        // $pdf = PDF::loadView('collection::pdf.sand_gravel_taxcollected_perclient_new');
        // $pdf->setPaper('letter', 'portrait');
        // return $pdf->stream();
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

    public function get_client_report(Request $req) {
        // retrieve all customers for specified month
        // $client = Receipt::select('col_customer.id', 'col_customer.name')->join('col_customer', 'col_receipt.col_customer_id', '=', 'col_customer.id')->where('client_type', $req->customer_type)->whereMonth('report_date', '=', $req->month)->whereYear('report_date', '=', $req->year)->distinct()->get();
        $client = Receipt::select('col_acct_title.id', 'col_acct_title.name')->join('col_receipt_items', 'col_receipt.id', '=', 'col_receipt_items.col_receipt_id')->join('col_acct_title', 'col_receipt_items.col_acct_title_id', '=', 'col_acct_title.id')->where('client_type', $req->customer_type)->whereMonth('report_date', '=', $req->month)->whereYear('report_date', '=', $req->year)->distinct()->get();
        return $client;
    }

    public function client_type(){
        $this->base['months'] = array();
        for ($month = 1; $month <= 12; $month++) {
            $this->base['months'][$month] = date('F', mktime(0,0,0,$month));
        }
        $this->base['sandgravel_types'] = SandGravelTypes::all();
        $this->base['page_title'] = 'Client Type Reports';

        return view('collection::report.client_type')->with('base', $this->base);
    }

    public function client_type_generate(Request $request){
        $date = Carbon::createFromDate($request->year, $request->month, 1, 'Asia/Manila');
        $this->base['datex'] = $date;
        $this->base['year'] = $request->year;
        $customer_type = $request->customer_type;
        $this->base['title'] = SandGravelTypes::find($customer_type);

        $this->base['acctble_officer_name'] = ReportOfficers::whereId(10)->first();
        $this->base['acctble_officer_position'] = ReportOfficers::whereId(11)->first();

        // Fetch records based on client type

        $this->base['customers'] = Customer::all();
        $this->base['sharing'] = empty($request->sharing) ? 'value' :  $request->sharing;
        $pdf = new PDF;
        $account_idx = $request->input('account') == '55' ? '2' : $request->input('account');
        if(isset($customer_type) && $customer_type !== '' && $customer_type !== 0){
            $receipts = Receipt::join('col_receipt_items','col_receipt_items.col_receipt_id' ,'=','col_receipt.id')
                ->whereMonth('col_receipt.report_date','=',$request->month)
                ->whereYear('col_receipt.report_date','=',$request->year)
                ->where('col_receipt.is_printed', '=', '1')
                ->where('col_receipt.is_cancelled', '=', '0')
                ->where('col_receipt.client_type', $customer_type)
                ->groupby('col_receipt.serial_no')
                ->orderBy('col_receipt.date_of_entry')
                ->get();

            // if customer type is monitoring
            if($customer_type == 1 && $request->monitoring_type != 0 && isset($request->monitoring_type)) {
                $receipts_monitoring = Receipt::join('col_receipt_items','col_receipt_items.col_receipt_id' ,'=','col_receipt.id')
                    ->whereMonth('col_receipt.report_date','=',$request->month)
                    ->whereYear('col_receipt.report_date','=',$request->year)
                    ->where('col_receipt.is_printed', '=', '1')
                    ->where('col_receipt.is_cancelled', '=', '0')
                    ->where('col_receipt.client_type', $customer_type)
                    ->where('col_acct_title_id', $request->monitoring_type)
                    ->groupby('col_receipt.serial_no')
                    ->orderBy('col_receipt.date_of_entry')
                    ->get();

                $cash_div_monitoring = CashDivision::join('col_cash_division_items','col_cash_division_items.col_cash_division_id' ,'=','col_cash_division.id')
                    ->select('col_cash_division.id')
                    ->whereMonth('col_cash_division.date_of_entry','=', $request->month )
                    ->whereYear('col_cash_division.date_of_entry' ,'=', $request->year )
                    ->where('col_cash_division.client_type', $customer_type)
                    ->where('col_acct_title_id', $request->monitoring_type)
                    ->orderBy('col_cash_division.date_of_entry')
                    ->get();
            }
            if(isset($request->clients) && $request->clients != "" && $customer_type != 1 && $request->clients != 0) {
                // note: if customer type is under account title Permit Fees, show only customers who paid the annual permit fee
                if($request->clients == 1) { // Professional Tax account title only
                    $receipts_per_client = Receipt::join('col_receipt_items','col_receipt_items.col_receipt_id' ,'=','col_receipt.id')
                        ->leftjoin('col_acct_title', 'col_acct_title.id', '=', 'col_receipt_items.col_acct_title_id')
                        ->leftjoin('col_acct_subtitle', 'col_acct_subtitle.id', '=', 'col_receipt_items.col_acct_subtitle_id')
                        ->select('col_receipt.*', 'col_receipt_items.*', 'col_acct_title.name as acct_title', 'col_acct_subtitle.name as acct_subtitle')
                        ->whereMonth('col_receipt.report_date','=',$request->month)
                        ->whereYear('col_receipt.report_date','=',$request->year)
                        ->where('col_receipt.is_printed', '=', '1')
                        ->where('col_receipt.is_cancelled', '=', '0')
                        // ->where('col_receipt.client_type', $customer_type)
                        // ->where('col_receipt_items.col_acct_title_id', $request->clients)
                        ->where(function($q) use($customer_type, $request) {
                            $q->where('col_receipt.client_type', $customer_type)
                                ->orWhere('col_receipt_items.col_acct_title_id', $request->clients);
                        })
                        ->groupby('col_receipt.serial_no')
                        ->orderBy('col_receipt.date_of_entry')
                        ->get();

                    $cash_div_per_client = CashDivision::join('col_cash_division_items','col_cash_division_items.col_cash_division_id' ,'=','col_cash_division.id')
                        ->select('col_cash_division.id')
                        ->whereMonth('col_cash_division.date_of_entry','=', $request->month )
                        ->whereYear('col_cash_division.date_of_entry' ,'=', $request->year )
                        // ->where('col_cash_division.client_type', $customer_type)
                        // ->where('col_acct_title_id', $request->clients)
                        ->where(function($q) use($customer_type, $request) {
                            $q->where('col_cash_division.client_type', $customer_type)
                                ->orWhere('col_acct_title_id', $request->clients);
                        })
                        ->orderBy('col_cash_division.date_of_entry')
                        ->get();
                } else {
                    $receipts_per_client = Receipt::join('col_receipt_items','col_receipt_items.col_receipt_id' ,'=','col_receipt.id')
                        ->leftjoin('col_acct_title', 'col_acct_title.id', '=', 'col_receipt_items.col_acct_title_id')
                        ->leftjoin('col_acct_subtitle', 'col_acct_subtitle.id', '=', 'col_receipt_items.col_acct_subtitle_id')
                        ->select('col_receipt.*', 'col_receipt_items.*', 'col_acct_title.name as acct_title', 'col_acct_subtitle.name as acct_subtitle')
                        ->whereMonth('col_receipt.report_date','=',$request->month)
                        ->whereYear('col_receipt.report_date','=',$request->year)
                        ->where('col_receipt.is_printed', '=', '1')
                        ->where('col_receipt.is_cancelled', '=', '0')
                        ->where('col_receipt.client_type', $customer_type)
                        // ->where('col_customer_id', $request->clients)
                        ->where('col_receipt_items.col_acct_title_id', $request->clients)
                        // ->where('col_receipt_items.col_acct_title_id', '18')
                        ->groupby('col_receipt.serial_no')
                        ->orderBy('col_receipt.date_of_entry')
                        ->get();

                    $cash_div_per_client = CashDivision::join('col_cash_division_items','col_cash_division_items.col_cash_division_id' ,'=','col_cash_division.id')
                        ->select('col_cash_division.id')
                        ->whereMonth('col_cash_division.date_of_entry','=', $request->month )
                        ->whereYear('col_cash_division.date_of_entry' ,'=', $request->year )
                        ->where('col_cash_division.client_type', $customer_type)
                        // ->where('col_customer_id', $request->clients)
                        ->where('col_acct_title_id', $request->clients)
                        ->orderBy('col_cash_division.date_of_entry')
                        ->get();
                }
            } else {
                if($customer_type == 54) {
                    $receipts_per_client = Receipt::join('col_receipt_items','col_receipt_items.col_receipt_id' ,'=','col_receipt.id')
                        ->leftjoin('col_acct_title', 'col_acct_title.id', '=', 'col_receipt_items.col_acct_title_id')
                        ->leftjoin('col_acct_subtitle', 'col_acct_subtitle.id', '=', 'col_receipt_items.col_acct_subtitle_id')
                        ->select('col_receipt.*', 'col_receipt_items.*', 'col_acct_title.name as acct_title', 'col_acct_subtitle.name as acct_subtitle')
                        ->whereMonth('col_receipt.report_date','=',$request->month)
                        ->whereYear('col_receipt.report_date','=',$request->year)
                        ->where('col_receipt.is_printed', '=', '1')
                        ->where('col_receipt.is_cancelled', '=', '0')
                        // ->where('col_receipt.client_type', $customer_type)
                        ->where(function($q) use($customer_type) {
                            $q->where('col_receipt.client_type', $customer_type)
                                ->orWhere('col_receipt_items.col_acct_title_id', 61);
                        })
                        ->groupby('col_receipt.serial_no')
                        ->orderBy('col_receipt.date_of_entry')
                        ->get();

                    $cash_div_per_client = CashDivision::join('col_cash_division_items','col_cash_division_items.col_cash_division_id' ,'=','col_cash_division.id')
                        ->select('col_cash_division.id')
                        ->whereMonth('col_cash_division.date_of_entry','=', $request->month )
                        ->whereYear('col_cash_division.date_of_entry' ,'=', $request->year )
                        // ->where('col_cash_division.client_type', $customer_type)
                        ->where(function($q) use($customer_type) {
                            $q->where('col_cash_division.client_type', $customer_type)
                                ->orWhere('col_cash_division_items.col_acct_title_id', 61);
                        })
                        ->orderBy('col_cash_division.date_of_entry')
                        ->get();
                } else {
                    $receipts_per_client = Receipt::join('col_receipt_items','col_receipt_items.col_receipt_id' ,'=','col_receipt.id')
                        ->leftjoin('col_acct_title', 'col_acct_title.id', '=', 'col_receipt_items.col_acct_title_id')
                        ->leftjoin('col_acct_subtitle', 'col_acct_subtitle.id', '=', 'col_receipt_items.col_acct_subtitle_id')
                        ->select('col_receipt.*', 'col_receipt_items.*', 'col_acct_title.name as acct_title', 'col_acct_subtitle.name as acct_subtitle')
                        ->whereMonth('col_receipt.report_date','=',$request->month)
                        ->whereYear('col_receipt.report_date','=',$request->year)
                        ->where('col_receipt.is_printed', '=', '1')
                        ->where('col_receipt.is_cancelled', '=', '0')
                        ->where('col_receipt.client_type', $customer_type)
                        // ->where(function($q) use($customer_type) {
                        //     $q->where('col_receipt.client_type', $customer_type)
                        //         ->orWhere('col_acct_title.id', 1);
                        // })
                        ->groupby('col_receipt.serial_no')
                        ->orderBy('col_receipt.date_of_entry')
                        ->get();

                    $cash_div_per_client = CashDivision::join('col_cash_division_items','col_cash_division_items.col_cash_division_id' ,'=','col_cash_division.id')
                        ->select('col_cash_division.id')
                        ->whereMonth('col_cash_division.date_of_entry','=', $request->month )
                        ->whereYear('col_cash_division.date_of_entry' ,'=', $request->year )
                        ->where('col_cash_division.client_type', $customer_type)
                        // ->where(function($q) use($customer_type) {
                        //     $q->where('col_cash_division.client_type', $customer_type)
                        //         ->orWhere('col_cash_division_items.col_acct_title_id', 1);
                        // })
                        ->orderBy('col_cash_division.date_of_entry')
                        ->get();
                }
            }
        } else {
            $receipts = Receipt::join('col_receipt_items','col_receipt_items.col_receipt_id' ,'=','col_receipt.id')
                ->whereMonth('col_cash_division.date_of_entry' ,'=', $request->month)
                ->whereYear('col_cash_division.date_of_entry' ,'=', $request->year)
                ->where('col_receipt.client_type', $customer_type)
                ->where('col_receipt.is_printed', '=', '1')
                ->where('col_receipt.is_cancelled', '=', '0')
                ->groupby('col_receipt.serial_no')
                ->orderBy('col_receipt.date_of_entry')
                ->get();

            $cash_div = CashDivision::join('col_cash_division_items','col_cash_division_items.col_cash_division_id' ,'=','col_cash_division.id')
                ->select('col_cash_division.id')
                ->whereMonth('col_cash_division.date_of_entry' ,'=', $request->month )
                ->whereYear('col_cash_division.date_of_entry' ,'=', $request->year )
                ->where('col_cash_division.client_type', $customer_type)
                ->orderBy('col_cash_division.date_of_entry')
                ->get();
       }

        // $this->base['receipts'] = $receipts;

            $receiptss = [];
            if($customer_type == 1 && $request->monitoring_type != 0 && isset($request->monitoring_type)) {
                if(!empty($receipts_monitoring)) {
                    foreach ($receipts_monitoring as $rkey => $receipt) {
                        $receiptss[]  = Receipt::where('serial_no','=',$receipt->serial_no)->first();
                    }
                }
            } else if(isset($request->clients) && $request->clients != "") {
                if(!empty($receipts_per_client)) {
                    foreach ($receipts_per_client as $rkey => $receipt) {
                        $receiptss[]  = Receipt::where('serial_no','=',$receipt->serial_no)->first();
                    }
                }
            } else {
                if(!empty($receipts)) {
                    foreach ($receipts as $rkey => $receipt) {
                        $receiptss[]  = Receipt::where('serial_no','=',$receipt->serial_no)->first();
                    }
                }
            }

            $cash_divs = [];
            if($customer_type == 1 && $request->monitoring_type != 0 && isset($request->monitoring_type)) {
                if(!empty($cash_div_monitoring)) {
                    foreach ($cash_div_monitoring as $ckey => $cashdiv) {
                        $cash_divs[]  = CashDivision::where('id','=',$cashdiv->id)->first();
                    }
                }
            } else if(isset($request->clients) && $request->clients != "") {
                if(!empty($cash_div_per_client)) {
                    foreach ($cash_div_per_client as $ckey => $cashdiv) {
                        $cash_divs[]  = CashDivision::where('id','=',$cashdiv->id)->first();
                    }
                }
            } else {
                if(!empty($cash_div)) {
                    foreach ($cash_div as $ckey => $cashdiv) {
                        $cash_divs[]  = CashDivision::where('id','=',$cashdiv->id)->first();
                    }
                }
            }
            
            // foreach ($receipts2 as $key => $val) {
            //     if($customer_type == $val['client_type']) {
            //         $this->base['receiptss'] = $receipts2;
            //         break;
            //     }
            // }
            // if (!isset($this->base['receiptss'])) {
            //     $this->base['receiptss'] = $receiptss;
            // }

            // if(($customer_type == 1 && $request->monitoring_type != 0 && isset($request->monitoring_type)) || (isset($request->clients) && $request->clients != "")) {
                $this->base['receiptss'] = $receiptss;
            // } else {
            //     foreach ($receipts2 as $key => $val) {
            //         if($customer_type == $val['client_type']) {
            //             $this->base['receiptss'] = $receipts2;
            //             break;
            //         }
            //     }
            // }

            // if (!isset($this->base['receiptss'])) {
            //     $this->base['receiptss'] = $receiptss;
            // }

            $this->base['cash_divs'] = $cash_divs;
            // $this->base['receiptss'] = $receiptss;
        //end
        $pdf = PDF::loadView('collection::pdf/client_type', $this->base);
            $pdf->setPaper('legal', 'landscape');
            return @$pdf->stream();

    }

    public function ctype_count_transac(Request $req) {
        if(isset($req->ctype) && $req->ctype !== '' && $req->ctype !== 0) {
            $receipts = Receipt::whereMonth('col_receipt.report_date','=',$req->month)
                ->whereYear('col_receipt.report_date','=',$req->year)
                ->where('col_receipt.is_printed', '=', '1')
                ->where('col_receipt.is_cancelled', '=', '0')
                ->where('col_receipt.client_type', $req->ctype)
                ->groupby('col_receipt.serial_no')
                ->orderBy('col_receipt.date_of_entry')
                ->get()
                ->toArray();
                // ->count();
            $cash_div = CashDivision::join('col_cash_division_items','col_cash_division_items.col_cash_division_id' ,'=','col_cash_division.id')
                ->whereMonth('col_cash_division.date_of_entry' ,'=', $req->month )
                ->whereYear('col_cash_division.date_of_entry' ,'=', $req->year )
                ->where('col_cash_division.client_type', $req->ctype)
                ->orderBy('col_cash_division.date_of_entry')
                ->get()
                ->toArray();

            // if customer type is monitoring
            if($req->ctype == 1 && $req->mtype != 0 && isset($req->mtype)) {
                $receipts_monitoring = Receipt::join('col_receipt_items', 'col_receipt_items.col_receipt_id', '=', 'col_receipt.id')
                    ->whereMonth('col_receipt.report_date','=',$req->month)
                    ->whereYear('col_receipt.report_date','=',$req->year)
                    ->where('col_receipt.is_printed', '=', '1')
                    ->where('col_receipt.is_cancelled', '=', '0')
                    ->where('col_receipt.client_type', $req->ctype)
                    ->where('col_acct_title_id', $req->mtype)
                    ->groupby('col_receipt.serial_no')
                    ->orderBy('col_receipt.date_of_entry')
                    ->get()
                    ->toArray();
                    // ->count();

                $cash_div_monitoring = CashDivision::join('col_cash_division_items','col_cash_division_items.col_cash_division_id' ,'=','col_cash_division.id')
                    ->whereMonth('col_cash_division.date_of_entry','=', $req->month )
                    ->whereYear('col_cash_division.date_of_entry' ,'=', $req->year )
                    ->where('col_cash_division.client_type', $req->ctype)
                    ->where('col_acct_title_id', $req->mtype)
                    ->orderBy('col_cash_division.date_of_entry')
                    ->get()
                    ->toArray();
            }
            if(isset($req->clients) && $req->clients != "" && $req->ctype != 1 && $req->clients != 0) {
                $receipts_per_client = Receipt::join('col_receipt_items', 'col_receipt.id', '=', 'col_receipt_items.col_receipt_id')
                    ->leftjoin('col_acct_title', 'col_acct_title.id', '=', 'col_receipt_items.col_acct_title_id')
                    ->leftjoin('col_acct_subtitle', 'col_acct_subtitle.id', '=', 'col_receipt_items.col_acct_subtitle_id')
                    ->whereMonth('col_receipt.report_date','=',$req->month)
                    ->whereYear('col_receipt.report_date','=',$req->year)
                    ->where('col_receipt.is_printed', '=', '1')
                    ->where('col_receipt.is_cancelled', '=', '0')
                    ->where('col_receipt.client_type', $req->ctype)
                    ->where('col_receipt_items.col_acct_title_id', $req->clients)
                    ->groupby('col_receipt.serial_no')
                    ->orderBy('col_receipt.date_of_entry')
                    ->get()
                    ->toArray();
                    // ->count();

                $cash_div_per_client = CashDivision::join('col_cash_division_items','col_cash_division_items.col_cash_division_id' ,'=','col_cash_division.id')
                    ->whereMonth('col_cash_division.date_of_entry','=', $req->month )
                    ->whereYear('col_cash_division.date_of_entry' ,'=', $req->year )
                    ->where('col_cash_division.client_type', $req->ctype)
                    ->where('col_acct_title_id', $req->clients)
                    ->orderBy('col_cash_division.date_of_entry')
                    ->get()
                    ->toArray();
            } else {
                // if customer type is under account title Permit Fees, show only customers who paid the annual permit fee     
                $receipts_per_client = Receipt::join('col_receipt_items', 'col_receipt_items.col_receipt_id', '=', 'col_receipt.id')
                    ->leftjoin('col_acct_title', 'col_acct_title.id', '=', 'col_receipt_items.col_acct_title_id')
                    ->leftjoin('col_acct_subtitle', 'col_acct_subtitle.id', '=', 'col_receipt_items.col_acct_subtitle_id')
                    ->whereMonth('col_receipt.report_date','=',$req->month)
                    ->whereYear('col_receipt.report_date','=',$req->year)
                    ->where('col_receipt.is_printed', '=', '1')
                    ->where('col_receipt.is_cancelled', '=', '0')
                    ->where('col_receipt.client_type', $req->ctype)
                    ->groupby('col_receipt.serial_no')
                    ->orderBy('col_receipt.date_of_entry')
                    ->get()
                    ->toArray();
                $cash_div_per_client = CashDivision::join('col_cash_division_items','col_cash_division_items.col_cash_division_id' ,'=','col_cash_division.id')
                    ->whereMonth('col_cash_division.date_of_entry','=', $req->month )
                    ->whereYear('col_cash_division.date_of_entry' ,'=', $req->year )
                    ->where('col_cash_division.client_type', $req->ctype)
                    ->orderBy('col_cash_division.date_of_entry')
                    ->get()
                    ->toArray();
            }
        } else {
            $receipts = Receipt::whereMonth('col_receipt.date_of_entry' ,'=', $req->month)
                ->whereYear('col_receipt.date_of_entry' ,'=', $req->year)
                // ->where('col_receipt.client_type', $req->ctype)
                ->where('col_receipt.is_printed', '=', '1')
                ->where('col_receipt.is_cancelled', '=', '0')
                ->groupby('col_receipt.serial_no')
                ->orderBy('col_receipt.date_of_entry')
                ->get()
                ->toArray();

            $cash_div = CashDivision::join('col_cash_division_items','col_cash_division_items.col_cash_division_id' ,'=','col_cash_division.id')
                ->whereMonth('col_cash_division.date_of_entry' ,'=', $req->month )
                ->whereYear('col_cash_division.date_of_entry' ,'=', $req->year )
                // ->where('col_cash_division.client_type', $req->ctype)
                ->orderBy('col_cash_division.date_of_entry')
                ->get()
                ->toArray();
       }
       $count = 0;
        if($req->ctype == 1 && $req->mtype != 0 && isset($req->mtype)) {
            // foreach($receipts_monitoring as $receipt) {
            //     $count += count($receipt['items']);
            // }
            $count += count($receipts_monitoring);
        } else if(isset($req->ctype) && $req->clients != "") {
            // foreach($receipts_per_client as $receipt) {
            //     $count += count($receipt['items']);
            // }
            $count += count($receipts_per_client);
        } else {
            // foreach($receipts as $receipt) {
            //     $count += count($receipt['items']);
            // }
            $count += count($receipts);
        }

        if($req->ctype == 1 && $req->mtype != 0 && isset($req->mtype)) {
            $count += count($cash_div_monitoring);
        } else if(isset($req->clients) && $req->clients != "") {
            $count += count($cash_div_per_client);
        } else {
            $count += count($cash_div);
        }
        return $count;
    }
}
