<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbsController;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Modules\Collection\Entities\AccountTitle;
use Modules\Collection\Entities\AccountSubtitle;
use Modules\Collection\Entities\BudgetEstimate;
use Modules\Collection\Entities\AccountCategory;
use Modules\Collection\Entities\AllowedMonths;
use Modules\Collection\Entities\Municipality;

use Modules\Collection\Entities\CashDivision;
use Modules\Collection\Entities\CashDivisionItems;
use Modules\Collection\Entities\Receipt;
use Modules\Collection\Entities\ReceiptItems;
use Modules\Collection\Entities\Customer;

use PDF;
use Carbon\Carbon;
use Excel;

class AccountsFormsReportController extends Controller
{
   public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'Accounts Daily Report';
    }

    public function index(){
        $this->base['months'] = array();
        for ($month = 1; $month <= 12; $month++) {
            $this->base['months'][$month] = date('F', mktime(0,0,0,$month));
        }
        
                $this->base['titles'] = AccountTitle::all();
                $this->base['sub_titles'] = AccountSubtitle::all();
            return view('collection::accounts.accountableforms', $this->base)->with('base', $this->base);
    }

    public function getSubtitles(Request $request){

        $subtitles = null;

        if(isset($request->account)){
            $title = AccountTitle::find($request->account);
            $subtitles = $title->subs;
        }

        return response()->json($subtitles);
    }

    public function report(Request $request){
                $data['start_datex'] = new Carbon($request->input('start_date'));
                $data['end_datex'] = new Carbon($request->input('end_date'));

                $data['start_date'] = new Carbon($request->input('start_date'));
                $data['end_date'] = new Carbon($request->input('end_date'));
                $data['diff'] = $data['start_date']->diffInDays($data['end_date']);
                if($data['diff']>31 && ( !isset($request['button_pdf_professional_tax']) AND !isset($request['button_pdf_other_services'])) ){
                 Session::flash('error', ['MAX Number of  Days is 31']);
                 return back();
                }

            $this->base['data'] = $data;
            $this->base['categories'] = AccountCategory::get();
            if(isset($request['button_excel'])  ){
                $test_c = '';
                Excel::create('ACCOUNTS COLLECTIONS REPORT', function($excel) use($test_c) {
                        $excel->sheet('COLLECTIONS REPORT', function($sheet) use($test_c) {
                            $sheet->loadView('collection::excel.account_report') ->with('base', $this->base);
                        });
                    })->export('xls');

              
            }else{
                  $pdf = new PDF;
                    if(isset($request['button_pdf'])  ){
                        $pdf = PDF::loadView('collection::pdf/account_report_v2', $this->base);
                    }elseif (isset($request['button_pdf_province'])) {
                        $pdf = PDF::loadView('collection::pdf/account_report_province_v2', $this->base);
                    }elseif (isset($request['button_pdf_municapal'])) {
                        $pdf = PDF::loadView('collection::pdf/account_report_municapal_single', $this->base);
                    }elseif(isset($request['button_pdf_brgy'])){
                        $pdf = PDF::loadView('collection::pdf/account_report_brgy', $this->base);
                    }elseif(isset($request['button_pdf_shared_bac'])){
                        if($data['diff']>15){
                             Session::flash('error', ['MAX Number of  Days is 5 FOR SHARED and BAC']);
                             return back();
                        }
                        $this->base['shares'] =     $this->get_shared_bac_report($request,$data);
                        $pdf = new PDF;

                        $pdf = PDF::loadView('collection::pdf/bac_shared_daily', $this->base);
                        $pdf->setPaper('letter', 'landscape');
                        return @$pdf->stream();
                        // return view('collection::pdf/bac_shared_daily')->with('base', $this->base);
                    }elseif(isset($request['button_pdf_professional_tax'])){
                         $receipts = Receipt::join('col_receipt_items','col_receipt_items.col_receipt_id' ,'=','col_receipt.id')
                            ->where('col_receipt.report_date','>=' , $data['start_date']->format('Y-m-d') )
                            ->where('col_receipt.report_date','<=' , $data['end_date']->format('Y-m-d') )
                            ->where('col_receipt.is_printed', '=', '1')
                            ->where('col_receipt_items.col_acct_title_id', '=', '1')
                            ->get();
                        $this->base['receipts'] = $receipts;
                        $pdf = PDF::loadView('collection::pdf/professional_tax', $this->base);
                    }elseif(isset($request['button_pdf_other_services'])){
                         $receipts = Receipt::join('col_receipt_items','col_receipt_items.col_receipt_id' ,'=','col_receipt.id')
                            ->where('col_receipt.report_date','>=' , $data['start_date']->format('Y-m-d') )
                            ->where('col_receipt.report_date','<=' , $data['end_date']->format('Y-m-d') )
                            ->where('col_receipt.is_printed', '=', '1')
                            ->where('col_receipt_items.col_acct_title_id', '=', '22')
                            ->get();
                        $this->base['receipts'] = $receipts;
                        $pdf = PDF::loadView('collection::pdf/other_services_income', $this->base);
                    }


                    if($data['diff'] > 4  && (!isset($request['button_pdf_professional_tax']) AND !isset($request['button_pdf_other_services'])) ){
                        if($data['diff'] > 20){
                             $pdf->setPaper('A2', 'landscape');
                         }else{
                            $pdf->setPaper('legal', 'landscape');
                         }
                    }elseif ( isset($request['button_pdf_professional_tax']) OR isset($request['button_pdf_other_services']) ) {
                        $pdf->setPaper('legal', 'landscape');
                    }else{
                             $pdf->setPaper('legal', 'portrait');
                    }

                    return @$pdf->stream();
                
            }
           
    }

    public function per_acct_report( Request $request ){
        $data['start_date'] = new Carbon($request->input('start_date'));
        $data['end_date'] = new Carbon($request->input('end_date'));
        $this->base['data'] = $data;
        $this->base['account'] = $request->input('account');
        $this->base['title'] = AccountTitle::find($request->input('account'));
        $this->base['customers'] = Customer::all();
        $pdf = new PDF;
        $receipts = Receipt::join('col_receipt_items','col_receipt_items.col_receipt_id' ,'=','col_receipt.id')
                            ->where('col_receipt.report_date','>=' , $data['start_date']->format('Y-m-d') )
                            ->where('col_receipt.report_date','<=' , $data['end_date']->format('Y-m-d') )
                            ->where('col_receipt.is_printed', '=', '1')
                            ->where('col_receipt_items.col_acct_title_id', '=', $request->input('account'))
                            ->get();

       
        $this->base['receipts'] = $receipts;

    
            $pdf = PDF::loadView('collection::pdf/per_account_report', $this->base);
        
        $pdf->setPaper('legal', 'landscape');
        return @$pdf->stream();
    }

    public function per_acct_report2( Request $request ){
        $data['start_date'] = new Carbon($request->input('start_date'));
        $data['end_date'] = new Carbon($request->input('end_date'));
        $subtitle = $request->input('subtitle');
        $this->base['data'] = $data;
        $this->base['account'] = $request->input('account');
        $this->base['title'] = AccountTitle::find($request->input('account'));
        $this->base['sub_title_cnt'] =count( $this->base['title']->subs );
        $this->base['customers'] = Customer::all();
        $pdf = new PDF;

       if(isset($subtitle) && $subtitle !== '' && $subtitle !== 0){
            if($subtitle == 'all'){
                $subs = AccountTitle::find($request->input('account'))->subs->pluck('id')->toArray();
            }else{
                $subs = array($subtitle);
            }
            $receipts = Receipt::join('col_receipt_items','col_receipt_items.col_receipt_id' ,'=','col_receipt.id')
                            ->where('col_receipt.report_date','>=' , $data['start_date']->format('Y-m-d') )
                            ->where('col_receipt.report_date','<=' , $data['end_date']->format('Y-m-d') )
                            ->where('col_receipt.is_printed', '=', '1')
                            ->where('col_receipt.is_cancelled', '=', '0')
                            ->whereIn('col_receipt_items.col_acct_subtitle_id', $subs)
                            ->groupby('col_receipt.serial_no')
                            ->get();

            $cash_div = CashDivision::join('col_cash_division_items','col_cash_division_items.col_cash_division_id' ,'=','col_cash_division.id')
                            ->select('col_cash_division.id')
                            ->where('col_cash_division.date_of_entry','>=' , $data['start_date']->format('Y-m-d') )
                            ->where('col_cash_division.date_of_entry','<=' , $data['end_date']->format('Y-m-d') )
                            ->whereIn('col_cash_division_items.col_acct_subtitle_id', $subs)
                            ->get();
       }else{
            $receipts = Receipt::join('col_receipt_items','col_receipt_items.col_receipt_id' ,'=','col_receipt.id')
                            ->where('col_receipt.report_date','>=' , $data['start_date']->format('Y-m-d') )
                            ->where('col_receipt.report_date','<=' , $data['end_date']->format('Y-m-d') )
                            ->where('col_receipt.is_printed', '=', '1')
                            ->where('col_receipt.is_cancelled', '=', '0')
                            ->where('col_receipt_items.col_acct_title_id', '=', $request->input('account'))
                            ->groupby('col_receipt.serial_no')
                            ->get();

            $cash_div = CashDivision::join('col_cash_division_items','col_cash_division_items.col_cash_division_id' ,'=','col_cash_division.id')
                            ->select('col_cash_division.id')
                            ->where('col_cash_division.date_of_entry','>=' , $data['start_date']->format('Y-m-d') )
                            ->where('col_cash_division.date_of_entry','<=' , $data['end_date']->format('Y-m-d') )
                            ->where('col_cash_division_items.col_acct_title_id', '=', $request->input('account'))
                            ->get();
       }

        // $this->base['receipts'] = $receipts;

            $receiptss = [];
            foreach ($receipts as $rkey => $receipt) {
                 $receiptss[]  = Receipt::where('serial_no','=',$receipt->serial_no)
                                    ->first();
            }

            $cash_divs = [];
            foreach ($cash_div as $ckey => $cashdiv) {
                
                 $cash_divs[]  = CashDivision::where('id','=',$cashdiv->id)
                                    ->first();
            }

            //return dd($request);

            $this->base['cash_divs'] = $cash_divs;

            $this->base['receiptss'] = $receiptss;

             // return view('collection::pdf.per_account_report_v2_html' , $this->base);
        if(isset($request['button_pdf_professional_tax'] )){
             $pdf = PDF::loadView('collection::pdf/per_account_report_v2_html', $this->base);
            $pdf->setPaper('legal', 'landscape');
            return @$pdf->stream();
        }else{
            return view('collection::html.per_account_report_v2_html',$this->base)->with('base', $this->base);
        }
       
    }

    public function allow_mnths( Request $request){

        $validator = Validator::make($request->all(), [
            'monthly_allowed_prvncial' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }
         Session::flash('info', ['Update Succesfull']);
         $AllowedMonths = AllowedMonths::where('a_year',$request->input('monthly_allowed_prvncial_year'));
     if($AllowedMonths->get()->count() > 0){
        $AllowedMonths->delete();
        Session::flash('info', ['Update Succesfull']);

        foreach ($request->input('monthly_allowed_prvncial') as $key => $value) {
            $data[] = [
                        'a_year' => $request->input('monthly_allowed_prvncial_year'),
                        'a_month' => $value,
                    ];
        }
        AllowedMonths::insert($data);
    }else{
        Session::flash('info', ['Insert Succesfull']);

        foreach ($request->input('monthly_allowed_prvncial') as $key => $value) {
            $data[] = [
                        'a_year' => $request->input('monthly_allowed_prvncial_year'),
                        'a_month' => $value,
                    ];
        }
        AllowedMonths::insert($data);

    }
    return back();

    }

    public function get_shared_bac_report($params,$data){
        $municipalities = Municipality::all();
        $municipalityx=[];
        $day = 0;
        $start_date = new Carbon($data['start_date']->format('Y-m-d'));
        for($x=0;$x<=$data['diff'];$x++ ){
                    $date = $start_date->addDays($day);
                    $asdate[$x]['y-m-d'] =$date->format('Y-m-d');
                    $asdate[$x]['j'] =$date->format('m/d');
                    $asdate[$x]['D'] =$date->format('D');
                    $day = 1;
        }
            foreach ($municipalities as $municipality) {
                if($municipality->id != 14){
                        $params['municipality'] = $municipality->id;
                        foreach ($asdate as $key => $value) {
                           if( $asdate[$key]['D'] != 'Sun'  && $asdate[$key]['D'] != 'Sat' ){
                            $municipalityx[$municipality->name][$asdate[$key]['j']]= $this->get_shared_bac_reportx($params,$asdate[$key]['y-m-d']);
                        }  
                    }
                }
            }

            foreach ($asdate as $key => $value) {
                if( $asdate[$key]['D'] != 'Sun'  && $asdate[$key]['D'] != 'Sat' ){
                    $bac_infra[$asdate[$key]['j']]= $this->get_shared_bac_report_bacinfra($params,$asdate[$key]['y-m-d']);
                    ksort($bac_infra[$asdate[$key]['j']]);
                }
            } 

            $result['bac_infra'] = $bac_infra;
            $result['dates'] = $asdate;
            $result['shares_mncpal'] = $municipalityx;
            $result['municipalities'] = $municipalities;
            return $result;
    }

    public function get_shared_bac_report_bacinfra($params,$date){


        $insurance_premium = 42;
        $resx = [];

        # ADD VALUES NOT IN MONTHLY REPORT
        $receipts = Receipt::where('report_date','=' , $date )
                            ->where('is_printed', '=', 1)
                                    ->get();

        foreach ($receipts as $receipt) {
            if($receipt->is_cancelled == 0){
                  foreach ($receipt->items as $item) {
                    $ref = ($item->col_acct_title_id != 0) ? 'acct_title' : 'acct_subtitle';
                    if ($item->{$ref}->show_in_monthly == 1) {
                        continue;
                    }
                    $value = ($item->$ref->id == $insurance_premium) ? $item->value - 15 : $item->value;
                    if (!isset($resx[$item->{$ref}->id])) {
                        $cat = '';
                        if ($item->col_acct_title_id != 0) {
                            $cat = $item->$ref->group->category->name;
                        } else {
                            $cat = $item->$ref->title->group->category->name;
                        }
                        $resx[$item->$ref->id] = [
                            'name' => $item->$ref->name.' ('.$cat.')',
                            'value' => $value
                        ];
                    } else {
                        $resx[$item->$ref->id]['value'] += $value;
                    }
                }
            }
        }

      
        return $resx;
    }

    protected function get_shared_bac_reportx($params,$date) {
        $insurance_premium = 42;
        $date_start = $params['year'].'-'.$params['month'].'-01';
        $date_end = date('Y-m-t', strtotime($date_start));

        $municipality = Municipality::whereId($params['municipality'])->first();
        $municipality = $municipality->name;
        $res = [];

        # ADD VALUES NOT IN MONTHLY REPORT
        $receipts = Receipt::where('report_date','=' , $date )
                                    ->where('is_printed', '=', 1)
                                    ->get();

        foreach ($receipts as $receipt) {

            
        }
        $res[$municipality] = [];

            $receipts = Receipt::where('report_date','=' , $date)
                            ->where('col_municipality_id', '=', $params['municipality'])
                            ->where('is_printed', '=', 1)
                            ->get();

        foreach($receipts as $receipt) {
            if (!isset($res[$receipt->barangay->name])) {
                $res[$receipt->barangay->name] = array();
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
                        $basic_municipal = (round($receipt->F56Detail->basic_current * .40, 2) + round($receipt->F56Detail->basic_previous * .40, 2)) - round($receipt->F56Detail->basic_discount * .40, 2);
                        $basicpenalty_municipal = round($receipt->F56Detail->basic_penalty_current * .40, 2) + round($receipt->F56Detail->basic_penalty_previous * .40, 2);
                        $basic_barangay = (round($receipt->F56Detail->basic_current * .25, 2) + round($receipt->F56Detail->basic_previous * .25, 2)) - round($receipt->F56Detail->basic_discount * .25, 2);
                        $basicpenalty_barangay = round($receipt->F56Detail->basic_penalty_current * .25, 2) + round($receipt->F56Detail->basic_penalty_previous * .25, 2);

                        $sef_municipal = (bcdiv($receipt->F56Detail->basic_current * .5, 1, 2) + bcdiv($receipt->F56Detail->basic_previous * .5, 1, 2)) - bcdiv($receipt->F56Detail->basic_discount * .5, 1, 2);
                        $sefpenalty_municipal = bcdiv($receipt->F56Detail->basic_penalty_current * .5, 1, 2) + bcdiv($receipt->F56Detail->basic_penalty_previous * .5, 1, 2);



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

                         if (!isset($res[$receipt->barangay->name]['title2'])) {
                            $res[$receipt->barangay->name]['title2'] = [ 'name' => 'Real Property Tax-Basic (Net of Discount)', 'value' => $basic_barangay ];
                            $res[$receipt->barangay->name]['subtitle1'] = [ 'name' => 'Tax Revenue - Fines & Penalties - Real Property Taxes', 'value' => $basicpenalty_barangay ];
                        } else {
                            $res[$receipt->barangay->name]['title2']['value'] += $basic_barangay;
                            $res[$receipt->barangay->name]['subtitle1']['value'] += $basicpenalty_barangay;
                        }
                }
            }
        }

         $cashdivs = CashDivision::where('date_of_entry','=' , $date)
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
