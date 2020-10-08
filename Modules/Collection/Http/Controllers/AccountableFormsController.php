<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use DB;
use PDF;
use Excel;
use Carbon\Carbon;

use Modules\Collection\Entities\AccountTitle;
use Modules\Collection\Entities\AccountCategory;
use Modules\Collection\Entities\AccountSubtitle;
use Modules\Collection\Entities\CashDivision;
use Modules\Collection\Entities\CashDivisionItems;
use Modules\Collection\Entities\Barangay;
use Modules\Collection\Entities\BudgetEstimate;
use Modules\Collection\Entities\F56Type;
use Modules\Collection\Entities\F56Detail;
use Modules\Collection\Entities\Municipality;
use Modules\Collection\Entities\Receipt;
use Modules\Collection\Entities\ReceiptItems;
use Modules\Collection\Entities\Serial;
use Modules\Collection\Entities\TransactionType;
use Modules\Collection\Entities\RcptCertificate;
use Modules\Collection\Entities\RcptCertificateType;
use Modules\Collection\Entities\ReportOfficers;
use Modules\Collection\Entities\MonthlyProvincialIncome;
use Modules\Collection\Entities\OtherFeesCharges;

class AccountableFormsController extends Controller
{
    public function __construct(Request $request)
    {
         parent::__construct($request);
        $this->base['page_title'] = 'PDF Report Accountable Forms';
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

    public function show_monthly(Request $request)
    {
        $date_start = date('Y-m-d', strtotime($request['start_date']));
        $date_end = date('Y-m-d', strtotime($request['end_date']));
        $category = AccountCategory::all();
        $this->base['category'] = $category; 

        $rcpt_acct51 = [];
        foreach ($category as $key => $value) {
            $receipts51 = Receipt::where('report_date','>=', $date_start)
                ->where('report_date','<=', $date_end)
                ->where('is_printed', '=', 1)
                ->where('af_type', 1)
                ->orderBy('serial_no', 'ASC')
                ->get();

            $rcpt_acct_af51 = array();

            foreach ($receipts51 as $rcpt_index => $receipt) {
                if (!isset($rcpt_acct_af51[$receipt->col_serial_id])) {
                    // $serial = Serial::where('id', $receipt->serial_no)->first();
                    // if(!is_null($serial)) {
                    //     if($serial->acct_cat_id == $value->id) {
                            $rcpt_acct_af51[$receipt->col_serial_id]['serials'] = [];
                    //     } else {
                    //         continue;
                    //     }
                    // } else {
                    //     continue;
                    // }                   
                }
                array_push($rcpt_acct_af51[$receipt->col_serial_id]['serials'], $receipt->serial_no);
            }
            // if($value->id == 2) { // combine BTS and Gen. Fund
            //     array_push($rcpt_acct51['General Fund-Proper'], $this->format_sort_af(1, $rcpt_acct_af51 , $date_start, $date_end, $value->id));
            //     dd($rcpt_acct51);
            // } else {
                $rcpt_acct51[$value->name] = $this->format_sort_af(1, $rcpt_acct_af51 , $date_start, $date_end, $value->id); 
            // }
        }

        $total = [];
        $total_beg_qty = 0;
        $total_rec_qty = 0;
        $total_iss_qty = 0;
        $total_end_qty = 0;

        $lower_beg_from = 0;
        $lower_beg_to = 0;
        $lower_rec_from = 0;
        $lower_rec_to = 0;
        $lower_iss_from = 0;
        $lower_iss_to = 0;
        $lower_end_from = 0;
        $lower_end_to = 0;

        // foreach ($category as $key => $value) {
        //     $total_beg_qty = 0;
        //     $total_rec_qty = 0;
        //     $total_iss_qty = 0;
        //     $total_end_qty = 0;
        //     foreach($rcpt_acct51 as $r) {
        //         foreach ($r as $val) {
        //             $total_beg_qty += $val['beg_qty'] ? $val['beg_qty']:0;
        //             $total_rec_qty += $val['rec_qty'] ? $val['rec_qty']:0;
        //             $total_iss_qty += $val['iss_qty'] ? $val['iss_qty']:0;
        //             $total_end_qty += $val['end_qty'] ? $val['end_qty']:0;
        //         }
        //         $total[$value->name]['total_beg_qty'] = $total_beg_qty; 
        //         $total[$value->name]['total_rec_qty'] = $total_rec_qty;
        //         $total[$value->name]['total_iss_qty'] = $total_iss_qty;
        //         $total[$value->name]['total_end_qty'] = $total_end_qty;

        //         $frst = reset($r);
        //         $last = end($r);
        //         $lower_beg_from = $frst['beg_from'];
        //         $lower_beg_to  = $last['beg_to'];
        //         $lower_rec_from = $frst['rec_from'];
        //         $lower_rec_to = $last['rec_to'];
        //         $lower_iss_from = $frst['iss_from'];
        //         $lower_iss_to = $last['iss_to'];
        //         $lower_end_from = $frst['end_from'];
        //         $lower_end_to = $last['end_to'];

        //         $total[$value->name]['lower_beg_from'] = $lower_beg_from;
        //         $total[$value->name]['lower_beg_to'] = $lower_beg_to;
        //         $total[$value->name]['lower_rec_from'] = $lower_rec_from;
        //         $total[$value->name]['lower_rec_to'] = $lower_rec_to;
        //         $total[$value->name]['lower_iss_from'] = $lower_iss_from;
        //         $total[$value->name]['lower_iss_to'] = $lower_iss_to;
        //         $total[$value->name]['lower_end_from'] = $lower_end_from;
        //         $total[$value->name]['lower_end_to'] = $lower_end_to;
        //     } 
        // }
        // dd($rcpt_acct51);
        // combine BTS and Gen. Fund
        foreach($rcpt_acct51['Benguet Technical School (BTS)'] as $serial => $data) {
            $rcpt_acct51['General Fund-Proper'][$serial] = $data;
        }
        ksort($rcpt_acct51['General Fund-Proper']);

        /*end f51*/

        /*start f56*/

        $receipts56 = Receipt::where('report_date','>=', $date_start)
            ->where('report_date','<=', $date_end)
            ->where('is_printed', '=', 1)
            ->where('af_type', 2)
            ->orderBy('serial_no', 'ASC')
            ->get();

        $rcpt_acct_af56 = array();

        foreach ($receipts56 as $rcpt_index => $receipt) {
            if (!isset($rcpt_acct_af56[$receipt->col_serial_id])) {
                $rcpt_acct_af56[$receipt->col_serial_id]['serials'] = [];
            }
            array_push($rcpt_acct_af56[$receipt->col_serial_id]['serials'], $receipt->serial_no);
        }
        $rcpt_acct56 = $this->format_sort_af(2, $rcpt_acct_af56 , $date_start, $date_end, null);
        $this->base['rcpt_acct51'] = $rcpt_acct51;
        $this->base['rcpt_acct56'] = $rcpt_acct56;
        $this->base['date'] = $date_start;
        $this->base['accountable_officer'] = ReportOfficers::whereId(10)->first();
        $this->base['acctble_officer_position'] = ReportOfficers::whereId(11)->first();
        // return view('collection::pdf/accountable_forms', $this->base)->with('base',$this->base);
        $pdf = new PDF;
        $pdf = PDF::loadView('collection::pdf/accountable_forms', $this->base)
            ->setPaper('legal', 'landscape');
            return @$pdf->stream();
    }

    private function bms_format_sort_af_all($form, $rcpt_acct_af, $date_start, $date_end, $fund=null){
        $existing_rcpt = Serial::where('acctble_form_id', '=', $form)
            ->where('acct_cat_id', '=', $fund)
            ->get();

        # list of accountable forms unused 
        $rcpt_list = array();
        $rcpt_acct = [];
        foreach($existing_rcpt as $er) {
                $ending_qty = $er->serial_begin;
                $ending_first = '';
                $ending_last = $er->serial_end;
                $beginning_qty = $er->serial_begin;
                $beginning_first = '';
                $beginning_last = $er->serial_end;

                $receipt_qty = '';
                $receipt_first = '';
                $receipt_last = '';


            $receipts51min = Receipt::select(db::raw('MIN(serial_no) as min_serial_no'))
                ->where('report_date','>=', $date_start)
                ->where('report_date','<=', $date_end)
                ->where('col_serial_id','=', $er->id)
                ->where('is_printed', '=', 1)
                ->where('af_type', 1)
                ->orderBy('serial_no', 'ASC')
                ->get();

            $receipts51max = Receipt::select(db::raw('MAX(serial_no) as max_serial_no'))
                ->where('report_date','>=', $date_start)
                ->where('report_date','<=', $date_end)
                ->where('col_serial_id','=', $er->id)
                ->where('is_printed', '=', 1)
                ->where('af_type', 1)
                ->orderBy('serial_no', 'ASC')
                ->get();
                

                if ($form == 2) {
                    #form 56
                    $idx = $er->municipality->name . $er->serial_end;
                } else {
                    # form 51
                    $idx = $er->serial_end;
                }
                if( $receipts51max[0]->max_serial_no != null ){
                     $receipt_qty = ($receipts51min[0]->serial_end - $receipts51min[0]->serial_begin) + 1;
                        $rcpt_acct[$idx] =  [
                                        'rec_qty' => $receipt_qty,
                                        'min'               => $receipts51min[0]->min_serial_no,
                                        'max'               => $receipts51max[0]->max_serial_no,
                                        'end'               => $er->serial_end,
                                        'start'             => $er->serial_begin,
                                        'current'           => $er->serial_current,
                                        'use'               => ($receipts51max[0]->max_serial_no) - $receipts51min[0]->min_serial_no,
                                        'beginning_first'   => $receipts51min[0]->min_serial_no,
                                        'beginning_end'     => $er->serial_end,
                                        'beg_qty'           =>  $er->serial_end - ($receipts51min[0]->min_serial_no),
                                        'iss_qty' => $issued_qty,
                                    ];
                }
                
        }
        ksort($rcpt_acct);
        return $rcpt_acct;

        


    }


    private function bms_format_sort_af_current($form, $rcpt_acct_af, $date_start, $date_end, $fund=null){
        $existing_rcpt = Serial::where('serial_current', '!=', 0)
            ->where('acctble_form_id', '=', $form)
            ->where('acct_cat_id', '=', $fund)
            ->get();


        # list of accountable forms unused 
        $rcpt_list = array();
        $rcpt_acct = [];
        foreach($existing_rcpt as $er) {

                $beginning_qty = $ending_qty = ($er->serial_end - $er->serial_current) + 1;
                $beginning_first = $ending_first = $er->serial_current;
                $beginning_last = $ending_last = $er->serial_end;

                $receipt_qty = '';
                $receipt_first = '';
                $receipt_last = '';


             $receipts51 = Receipt::where('report_date','>=', $date_start)
                ->where('report_date','<=', $date_end)
                ->where('col_serial_id','=', $er->id)
                ->where('is_printed', '=', 1)
                ->where('af_type', 1)
                ->orderBy('serial_no', 'ASC')
                ->get();


                if ($form == 2) {
                    #form 56
                    $idx = $er->municipality->name . $er->serial_begin;
                } else {
                    # form 51
                    $idx = $er->serial_begin;
                }
                $rcpt_acct[$idx] = $er->serial_begin + $receipts51->count();
        }
        ksort($rcpt_acct);
        return $rcpt_acct;
    }

    private function format_sort_af($form, $rcpt_acct_af, $date_start, $date_end, $fund) {
        if($form == 2) {
            $existing_rcpt = Serial::where('acctble_form_id', '=', $form)
                // ->where('serial_current', '<>', 0)
                ->where('acct_cat_id', '=', null)
                ->get();
        } else {
            $existing_rcpt = Serial::where('acctble_form_id', '=', $form)
                // ->where('serial_current', '<>', 0)
                ->where('acct_cat_id', '=', $fund)
                ->get();
        }

        # list of accountable forms unused
        $rcpt_acct = [];
        $rcpt_list = [];
        foreach($existing_rcpt as $er) {
            $after_zero_currnt = Receipt::where('serial_no', $er->serial_end)
                ->where('col_serial_id', $er->id)
                ->first();
            $checkCurrent = Receipt::where('serial_no', $er->serial_current)->first();

            if(($er->serial_current == 0 && is_null($after_zero_currnt)) || ($er->serial_current == $er->serial_end && count($after_zero_currnt) > 1) || ($er->serial_current == $er->serial_end && count($checkCurrent) > 0)) {
                continue;
            }
                
            // if($er->serial_current <> 0) {
            if($er->serial_current <> 0 || ($er->serial_current == 0 && Carbon::parse($after_zero_currnt->date_of_entry)->format('Y-m-d') > Carbon::parse($date_end)->format('Y-m-d'))) {    
                if (!array_key_exists($er->id, $rcpt_acct_af)) {
                    $receipt_before = Receipt::select(db::raw('MAX(serial_no) as last_mnth_issued'))
                        // ->whereMonth('report_date','<', Carbon::parse($date_start)->format('m'))
                        ->where('report_date','<', Carbon::parse($date_start))
                        ->where('col_serial_id','=', $er->id)
                        ->where('is_printed', '=', 1)
                        ->where('af_type', $form)
                        ->orderBy('serial_no', 'ASC')
                        ->first();

                    $beginning_first = $ending_first = count($receipt_before) > 0 && $receipt_before->last_mnth_issued !== null ? $receipt_before->last_mnth_issued + 1 : $er->serial_begin;
                    // $beginning_first = $ending_first = $er->serial_begin;  
                    $beginning_last = $ending_last = $er->serial_end;
                    $beginning_qty = ($beginning_last - $beginning_first) + 1;
                    $ending_qty = ($ending_last - $ending_first) + 1;

                    $receipt_qty = '';
                    $receipt_first = '';
                    $receipt_last = '';

                    if ($form == 2) {
                        #form 56
                        $idx = $er->municipality->name . $er->serial_begin;
                    } else {
                        # form 51
                        $idx = $er->serial_begin;
                    }

                    if (strtotime($er->date_added) == strtotime($date_end)) {
                        $receipt_qty = ($er->serial_end - $er->serial_begin) + 1;
                        $receipt_first = $er->serial_begin;
                        $receipt_last = $er->serial_end;

                        // $beginning_qty = '';
                        // $beginning_first = '';
                        // $beginning_last = '';
                    }

                    $issued_qty = '';
                    $issued_first = $issued_last = '';
                    $src = (isset($er->municipality)) ? $er->municipality->name : '';
                    $match = preg_replace('/[0-9]+/', '', $idx);

                    if($src == $match) {
                        $rcpt_acct[$idx] = array(
                            'src' => $src,
                            'beg_qty' => $beginning_qty,
                            'beg_from' => $beginning_first,
                            'beg_to' => $beginning_last,
                            'rec_qty' => $receipt_qty,
                            'rec_from' => $receipt_first,
                            'rec_to' => $receipt_last,
                            'iss_qty' => $issued_qty,
                            'iss_from' => $issued_first,
                            'iss_to' => $issued_last,
                            // 'end_qty' => $ending_qty,
                            // 'end_from' => $ending_first,
                            // 'end_to' => $ending_last,
                            'end_qty' => $ending_qty,
                            'end_from' => $issued_qty > 0 && $issued_qty != '' ? $issued_last + 1 : $ending_first,
                            'end_to' => $ending_last,
                        );
                    }
                }
            }
        }
        
        # list of accountable forms used
        foreach($rcpt_acct_af as $i => $rcpt) {
            $serial = Serial::whereId($i)->first();

            $checkCurrent = Receipt::where('serial_no', $serial->serial_current)->first();
            if($serial->serial_current == $serial->serial_end && count($checkCurrent) > 0) {
                continue;
            }
            
            if($serial->acct_cat_id == $fund) {
                $src = (isset($serial->municipality)) ? $serial->municipality->name : '';
                sort($rcpt['serials']);
                $issued_first = $rcpt['serials'][0];
                $issued_last = $rcpt['serials'][count($rcpt['serials']) - 1];
                // $issued_qty = count($rcpt['serials']);
                $issued_qty = ($issued_last - $issued_first) + 1;

                $receipt_before = Receipt::select(db::raw('MAX(serial_no) as last_mnth_issued'))
                    // ->whereMonth('report_date','<', Carbon::parse($date_start)->format('m'))
                    ->where('report_date','<', Carbon::parse($date_start))
                    ->where('col_serial_id','=', $serial->id)
                    ->where('is_printed', '=', 1)
                    ->where('af_type', $form)
                    ->orderBy('serial_no', 'ASC')
                    ->first();

                $receipt_first = '';
                $receipt_last = '';
                $receipt_qty = '';

                $gt_et_start = true;
                $lt_et_end = (strtotime($serial->date_added) == strtotime($date_end));
                if ($form == 2) {
                    # For Form 56
                    $idx = $serial->municipality->name . $serial->serial_begin;
                } else {
                    # Form 51
                    $idx = $serial->serial_begin;
                }

                // old
                // $beginning_first = $issued_first;
                // $beginning_last = $serial->serial_end;
                // $beginning_qty = ($beginning_last - $beginning_first) + 1;

                // $beginning_first = $serial->serial_begin;
                // $beginning_last = $serial->serial_end;
                // $beginning_qty = ($beginning_last - $beginning_first) + 1;

                $beginning_first = $ending_first = count($receipt_before) > 0 && $receipt_before->last_mnth_issued !== null ? $receipt_before->last_mnth_issued + 1 : $serial->serial_begin;
                // $beginning_first = $ending_first = $er->serial_begin;  
                $beginning_last = $ending_last = $serial->serial_end;
                $beginning_qty = ($beginning_last - $beginning_first) + 1;

                if ($gt_et_start && $lt_et_end) {
                    $receipt_first = $serial->serial_begin;
                    $receipt_last = $serial->serial_end;
                    $receipt_qty = ($receipt_last - $receipt_first) + 1;

                    $beginning_first = '';
                    $beginning_last = '';
                    $beginning_qty = '';
                }

                // $ending_first = '';
                // $ending_last = '';
                // $ending_qty = '';
                if ($serial->serial_current != 0 || $issued_last != 0) {
                    // old
                    // $ending_first = $issued_last + 1;

                    // $ending_first = $serial->serial_begin;
                    // $ending_last = $serial->serial_end;
                    // $ending_qty = ($ending_last - $ending_first) + 1;

                    $beginning_first = count($receipt_before) > 0 && $receipt_before->last_mnth_issued !== null ? $receipt_before->last_mnth_issued + 1 : $serial->serial_begin;
                    $beginning_qty = (intval($beginning_last) - intval($beginning_first)) + 1;
                    // $issued_last = $serial->serial_end ? $issued_last : $issued_last + 1;
                    $ending_last = $serial->serial_end;
                    $ending_first = $issued_last == $serial->serial_end ? $issued_last : $issued_last + 1;
                }
                $ending_qty = $ending_last == $issued_last ? 0 : ($ending_last - $ending_first) + 1;
                // magic here....
                // if($issued_first == $receipt_before->last_mnth_issued) {
                //     $beginning_first = $issued_first;
                //     $beginning_qty = (intval($beginning_last) - intval($beginning_first)) + 1;
                // }  

                $rcpt_acct[$idx] = array(
                    'src' => $src,
                    'beg_qty' => $beginning_qty,
                    'beg_from' => $beginning_first,
                    'beg_to' => $beginning_last,
                    'rec_qty' => $receipt_qty,
                    'rec_from' => $receipt_first,
                    'rec_to' => $receipt_last,
                    'iss_qty' => $issued_qty,
                    'iss_from' => $issued_first,
                    'iss_to' => $issued_last,
                    // 'end_qty' => $issued_qty > 0 ? $beginning_qty - $issued_qty : $ending_qty,
                    // 'end_from' => $issued_last > 0 ? $issued_last + 1 : $ending_first,
                    'end_qty' => $ending_qty,
                    'end_from' => $ending_first,
                    'end_to' => $ending_last,
                );
            }
        }
        ksort($rcpt_acct);
        return $rcpt_acct;
    }
}
