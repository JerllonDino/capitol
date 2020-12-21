<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\Controller;
use DOMDocument;
use Illuminate\Http\{Request,Response};
use Illuminate\Support\Facades\{Session,Validator};
use Illuminate\Support\Arr;
use PDF,Excel,Carbon\Carbon,DB,Datatables;
use Modules\Collection\Entities\{
                                AccountTitle,AccountCategory,AccountSubtitle,CashDivision,CashDivisionItems,Barangay,BudgetEstimate,F56Type,
                                F56Detail,F56TDARP,Municipality,Receipt,ReceiptItems,Serial,TransactionType,RcptCertificate,RcptCertificateType,
                                ReportOfficers,MonthlyProvincialIncome,OtherFeesCharges,ReportOfficerNew,AccountGroup,RptSefAdjustments,RptSefAdjustmentsItems,RptBasicAdjustmentsItems
                            };

class PdfController extends Controller
{
    public function __construct(Request $request)
    {
        $this->base['page_title'] = 'PDF Report';
    }

    private function ordinal($number) {
        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        if ((($number % 100) >= 11) && (($number%100) <= 13))
            return $number. 'th';
        else
            return $number. $ends[$number % 10];
    }

    public function certificate($id, $gov, $ppr_size, $height=0, $width=0) {
        $receipt = Receipt::whereId($id)->where('is_cancelled','=',0)->get();
        if($receipt[0]->is_many){
             $receipt = Receipt::where('is_many','=',$receipt[0]->is_many)->where('is_cancelled','=',0)->orderBy('serial_no','asc')->get();
        }
        $this->base['receipts'] =  $receipt ;
        $this->base['current_receipt'] = Receipt::find($id);

        $this->base['cert'] = RcptCertificate::where('col_receipt_id', '=', $id)->first(); 
        $this->base['OtherFeesCharges'] =OtherFeesCharges::where('receipt_id', '=', $id)->get();
        $day = ltrim(date('d', strtotime($this->base['cert']->date_of_entry)), '0');
        $this->base['ordinal_date'] = $this->ordinal($day);

        $type = $this->base['cert']->col_rcpt_certificate_type_id;
        $this->base['user'] = Session::get('user');

        # Get initial sof user
        $initials = '';
        $name = explode(' ', $this->base['cert']->user);
        foreach ($name as $n) {
            $initials .= $n[0];
        }
        $this->base['initials'] = strtoupper($initials);

        $this->base['trans_tax_forin'] = ReportOfficers::where('name', 'trans_tax_forin')->first();
        $this->base['trans_tax_forin_position'] = ReportOfficers::where('name', 'trans_tax_forin_position')->first();
        $this->base['prov_gov'] = ReportOfficerNew::where('id', $gov)->first();

        $pdf = new PDF;
        switch ($type) {
            case 1:
                # provincial permit
                $pdf = PDF::loadView('collection::pdf/cert_provincialpermit', $this->base);
                
                break;
            case 2:
                # transfer tax
                $pdf = PDF::loadView('collection::pdf/cert_transfertax', $this->base);
                break;
            case 3:
                # sand & gravel
                $pdf = PDF::loadView('collection::pdf/cert_sand_gravel', $this->base);
                break;
            case 4:
                # sand & gravel tax certification
                $latest_cert_fee = Receipt::join('col_receipt_items', 'col_receipt.id', '=', 'col_receipt_items.col_receipt_id')
                    ->where('col_customer_id','=',$receipt[0]->col_customer_id)
                    ->where('is_cancelled','=',0)
                    ->where('is_printed', '=', 1)
                    ->whereDate('report_date','<=', $receipt[0]->report_date)
                    ->where('nature', 'Certification Fee')
                    ->first();

                if(!is_null($latest_cert_fee)) {
                    if(!isset($this->base['cert']->include_from) && !isset($this->base['cert']->include_to)) {
                        $transactions = Receipt::with('items')
                            ->where('col_customer_id','=',$receipt[0]->col_customer_id)
                            ->where('is_cancelled','=',0)
                            ->whereDate('report_date', '<=', $receipt[0]->report_date)
                            ->whereDate('report_date', '>=', $latest_cert_fee->report_date)
                            ->orderBy('report_date','asc')
                            ->get();
                    } else {
                        $transactions = Receipt::with('items')
                            ->where('col_customer_id','=',$receipt[0]->col_customer_id)
                            ->where('is_cancelled','=',0)
                            // ->whereDate('report_date', '<=', $receipt[0]->report_date)
                            // ->whereDate('report_date', '>=', $latest_cert_fee->report_date)
                            ->whereDate('report_date', '>=', $this->base['cert']->include_from)
                            ->whereDate('report_date', '<=', $this->base['cert']->include_to)
                            ->orderBy('report_date','asc')
                            ->get();
                    }
                } else {
                    if(!isset($this->base['cert']->include_from) && !isset($this->base['cert']->include_to)) {
                        $transactions = Receipt::with('items')
                            ->where('col_customer_id','=',$receipt[0]->col_customer_id)
                            ->where('is_cancelled','=',0)
                            ->whereDate('report_date', '<=', $receipt[0]->report_date)
                            ->orderBy('report_date','asc')
                            ->get();
                    } else {
                        $transactions = Receipt::with('items')
                            ->where('col_customer_id','=',$receipt[0]->col_customer_id)
                            ->where('is_cancelled','=',0)
                            ->whereDate('report_date', '<=', $receipt[0]->report_date)
                            ->whereDate('report_date', '>=', $this->base['cert']->include_from)
                            ->whereDate('report_date', '<=', $this->base['cert']->include_to)
                            ->orderBy('report_date','asc')
                            ->get();
                    }
                }      
  
                $other_fees = OtherFeesCharges::whereDate('fees_date', '<=', $receipt[0]->report_date)
                    ->where('receipt_id', '=', $id)
                    ->orderBy('fees_date')
                    ->get();

                // $transactions = Receipt::with(['items' => function($query) {
                //     $query->where('nature', '!=', 'Certification Fee');
                // }])
                // ->where('col_customer_id','=',$receipt[0]->col_customer_id)
                // ->where('is_cancelled','=',0)
                // ->whereDate('report_date','<=', $receipt[0]->report_date)
                // ->orderBy('report_date','asc')
                // ->get();

                $cert_receipt = [];
                $not_crt = [];
                $sg_taxes = [];
                $permit_pd = false;
                foreach ($transactions as $key => $value) {
                    foreach($value['items'] as $k => $v){
                        if($v->col_acct_title_id == 18) { // permit fees
                            $permit_pd = true;
                        }
                        if(strpos($v->nature,"Certification Fee") !== false){ 
                            array_push($cert_receipt,$value);
                            array_push($not_crt,$value->id);
                        } 
                        if(strpos($v->nature, ' Sand and Gravel Tax') !== false) {
                            array_push($sg_taxes, $value->serial_no);
                        }
                    }
                } 

                // foreach ($this->base['OtherFeesCharges'] as $key => $other) {
                //     array_push($cert_receipt, $other);
                //     array_push($not_crt, $other->receipt_id);
                // }

                // array_push($cert_receipt, $cert_fee_latest);
                rsort($cert_receipt);

                // if (count($cert_receipt) > 1) {
                //     $date1 = \Carbon\Carbon::parse($cert_receipt[count($cert_receipt)-1]->date_of_entry)->format('Y-m-d'); // to
                //     $date2 = \Carbon\Carbon::parse($cert_receipt[0]->date_of_entry); // from
                //     $transactions = Receipt::with('items')->where('col_customer_id','=',$receipt[0]->col_customer_id)->where('is_cancelled','=',0)->whereBetween('date_of_entry',[$date1,$date2])->orderBy('report_date','asc')->whereNotIn('id',$not_crt)->get();
                // } else {
                //     $date1 = \Carbon\Carbon::parse($cert_receipt[0]->date_of_entry)->format('Y-m-d');
                //     $transactions = Receipt::with('items')->where('col_customer_id','=',$receipt[0]->col_customer_id)->where('is_cancelled','=',0)->where('date_of_entry',$date1)->orderBy('report_date','asc')->whereNotIn('id',$not_crt)->get();
                // }
                
                // $transactions = Receipt::with('items')->where('col_customer_id','=',$receipt[0]->col_customer_id)->where('is_cancelled','=',0)->whereBetween('date_of_entry',[$date1,$date2])->orderBy('report_date','asc')->whereNotIn('id',$not_crt)->get();

                // die;
                $this->base['transactions'] =  $transactions;
                $this->base['cert_receipt'] = $cert_receipt;
                $this->base['not_crt'] = $not_crt;
                $this->base['sg_taxes'] = $sg_taxes;
                $this->base['cert_or'] = Receipt::with('RcptCertificate')->find($id);
                $this->base['permit_pd'] = $permit_pd;
                $this->base['include_from'] = $this->base['cert']->include_from;
                $this->base['include_to'] = $this->base['cert']->include_to;

                $pdf = PDF::loadView('collection::pdf/cert_sand_gravel_tax', $this->base);
                break;
        }
        // $pdf->setPaper('A4', 'portrait');
        if($ppr_size == 'a4') {
            $pdf->setPaper('A4', 'portrait');
        } else if($ppr_size == 'letter') {
            $pdf->setPaper('letter', 'portrait');
        } else if($ppr_size == 'legal') {
            $pdf->setPaper(array(0,0,612,936), 'portrait');
        } else if($ppr_size == 'custom') {
            if($height > 0 && $width > 0) {
                $pdf_h = $height*72;
                $pdf_w = $width*72;
                $pdf->setPaper(array(0,0,$pdf_w,$pdf_h), 'portrait');
            } else {
                Session::flash('info', ['Please set customer paper\'s height and width']);
                return redirect()->back();
            }
        }
        return @$pdf->stream();

    }

    public function land_tax_collection($id){
        $this->base['receipt'] = Receipt::find($id);
        $this->base['receipt']->is_printed = 1;
        $this->base['receipt']->save();
        $this->base['total_words'] = 0;
        $items = $this->base['receipt']->items;
        foreach ($items as $item) {
            $this->base['total_words'] += $item->value;
        }
        $this->base['total_words'] = convert_number_to_words(number_format($this->base['total_words'], 2, '.', ''));
        $this->base['acctble_officer_name'] = ReportOfficers::whereId(10)->first();
        $this->base['acctble_officer_position'] = ReportOfficers::whereId(11)->first();
        $pdf = new PDF;
        $customPaper = array(0,0,456,960);
        $pdf = PDF::loadView('collection::form56/new_form_56',$this->base)
            ->setPaper($customPaper,'landscape');
        return @$pdf->stream();
    }

    public function real_property(Request $request)
    {
        $form_56 = 2;
        $date_start = date('Y-m-d', strtotime($request['start_date']));
        $date_end = date('Y-m-d', strtotime($request['end_date']));
        $report_date = date('F d, Y', strtotime($request['report_date']));

        $receipts = Receipt::with('items')
            ->where('report_date','>=', $date_start)
            ->where('report_date','<=', $date_end)
            ->where('is_printed', '=', 1)
            ->where('af_type', '=', $form_56)
            ->where('col_municipality_id', '=', $request['municipality'])
            ->where('remarks', 'not like', '%paid under protest%')
            ->where('remarks', 'not like', '%held in trust%')
            ->where('bank_remark', 'not like', '%paid under protest%')
            ->where('bank_remark', 'not like', '%held in trust%')
            // ->whereHas('F56Detailmny', function($query) {
            //     $query->where('period_covered', 'not like', Carbon::now()->addYear()->format('Y'))
            //         ->where('period_covered', 'not like', '%advance%');
            // })
            ->orderBy('serial_no', 'ASC')
            ->get();

        // if (count($receipts) == 0) {
        //      No existing transaction
        //     Session::flash('error', ['No transaction for '. date('F d, Y', strtotime($date_start)) .' to '. date('F d, Y', strtotime($date_end)) .'.']);
        //     return redirect()->route('report.real_property');
        // }

        $class_amt = array();
        $classes = F56Type::get();
        foreach ($classes as $class) {
            $class_amt[$class->id] = array(
                'basic_current' => 0,
                'basic_discount' => 0,
                'basic_previous' => 0,
                'basic_penalty_current' => 0,
                'basic_penalty_previous' => 0,
                'basic_adv' => 0,
                'basic_adv_discount' => 0,
                'basic_prior_1991' => 0,
                'basic_prior_penalty_1991' => 0,
                'basic_prior_1992' => 0,
                'basic_prior_penalty_1992' => 0,
            );
        }
        
        // $prior_start = Carbon::now()->subYears(2)->format('Y');
        // $preceeding = Carbon::now()->subYear()->format('Y');
        // $advance_yr = Carbon::now()->addYear()->format('Y');
        // $current = Carbon::now()->format('Y');

        $current = Carbon::parse($request['start_date'])->format('Y');
        $prior_start = Carbon::parse($request['start_date'])->subYears(2)->format('Y');
        $preceeding = Carbon::parse($request['start_date'])->subYear()->format('Y');
        $advance_yr = Carbon::parse($request['start_date'])->addYear()->format('Y');

        $this->base['prior_start'] = $prior_start;
        $this->base['preceeding'] = $preceeding;
        $this->base['advance_yr'] = $advance_yr;
        $this->base['current'] = $current;

        foreach ($receipts as $rcpt_index => $receipt) {
            if ($receipt->is_cancelled) {
                continue;
            }

            if ( $receipt->F56Detailmny()->count() > 0 ) {
                foreach ($receipt->F56Detailmny as $f56_detail){
                    // if($f56_detail->period_covered < Carbon::now()->addYear()->format('Y') || strpos('advance', $f56_detail->period_covered) != false) {
                        $index = $f56_detail->col_f56_type_id;
                        if($f56_detail->period_covered == $current) {
                            $class_amt[$index]['basic_current'] += round(floatval($f56_detail->basic_current), 2);
                            $class_amt[$index]['basic_discount'] += round(floatval($f56_detail->basic_discount), 2);
                            $class_amt[$index]['basic_penalty_current'] += round(floatval($f56_detail->basic_penalty_current), 2);
                        }

                        if($f56_detail->period_covered == $preceeding) {
                            $class_amt[$index]['basic_previous'] += round(floatval($f56_detail->basic_previous), 2);
                            $class_amt[$index]['basic_penalty_previous'] += round(floatval($f56_detail->basic_penalty_previous), 2);
                        }
                        
                        if($f56_detail->period_covered >= $advance_yr) {
                            // $class_amt[$index]['basic_adv'] += number_format(($f56_detail->tdrp_assedvalue*.01), 2);
                            // $class_amt[$index]['basic_adv_discount'] += number_format((($f56_detail->tdrp_assedvalue*.01)*.10), 2);

                            $class_amt[$index]['basic_adv'] += round(floatval($f56_detail->basic_current), 2);
                            $class_amt[$index]['basic_adv_discount'] += round(floatval($f56_detail->basic_discount), 2);
                        }

                        if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered >= 1992) {
                            $class_amt[$index]['basic_prior_1992'] += round(floatval($f56_detail->basic_previous), 2);
                            $class_amt[$index]['basic_prior_penalty_1992'] += round(floatval($f56_detail->basic_penalty_previous), 2);
                        }

                        if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered <= 1991) {
                            $class_amt[$index]['basic_prior_1991'] += round(floatval($f56_detail->basic_previous), 2);
                            $class_amt[$index]['basic_prior_penalty_1991'] += round(floatval($f56_detail->basic_penalty_previous), 2);
                        }
                    // }
                }
            }
        }

        $date_end = new Carbon($date_end);
        $this->base['f56_type'] = F56Type::get();
        $this->base['date_range'] = date('F d, Y', strtotime($date_start)) .' to '. $date_end->format('F d, Y') .'.';
        $this->base['municipality'] = Municipality::whereId($request['municipality'])->first();
        $this->base['receipts'] = $receipts;
        $this->base['class_amt'] = $class_amt;
        $this->base['report_date'] = $report_date;
        // return view('collection::pdf/real_property', $this->base)->with('base', $this->base);

        $pdf = new PDF;
        if(isset($request["button"])){
            if (count($receipts) == 0) {
                # No existing transaction
                Session::flash('error', ['No transaction for '. date('F d, Y', strtotime($date_start)) .' to '. date('F d, Y', strtotime($date_end)) .'.']);
                return redirect()->route('report.real_property');
            }
            // $pdf = PDF::loadView('collection::pdf/real_property', $this->base)
            // ->setPaper('legal', 'landscape');

            $pdf = PDF::loadView('collection::pdf/new_rpt_abstract/real_property', $this->base)
            ->setPaper(array(0,0,612,936), 'landscape');
        }elseif (isset($request["rpt_mun_report_collections"])) {
            if (count($receipts) == 0) {
                # No existing transaction
                Session::flash('error', ['No transaction for '. date('F d, Y', strtotime($date_start)) .' to '. date('F d, Y', strtotime($date_end)) .'.']);
                return redirect()->route('report.real_property');
            }
            // $pdf = PDF::loadView('collection::pdf/real_property_collections', $this->base)
            // ->setPaper('legal', 'landscape');
            
            $pdf = PDF::loadView('collection::pdf/new_rpt_abstract/real_property_collections', $this->base)
            ->setPaper('legal', 'landscape');
        }elseif (isset($request["rpt_mun_report_summary_disposition"])) {
            if (count($receipts) == 0) {
                # No existing transaction
                Session::flash('error', ['No transaction for '. date('F d, Y', strtotime($date_start)) .' to '. date('F d, Y', strtotime($date_end)) .'.']);
                return redirect()->route('report.real_property');
            }
            // $pdf = PDF::loadView('collection::pdf/real_property_summary_disposition', $this->base)
            // ->setPaper('legal', 'landscape');

            $pdf = PDF::loadView('collection::pdf/new_rpt_abstract/real_property_summ_dispo', $this->base)
            ->setPaper(array(0,0,612,936), 'landscape');
        } elseif(isset($request['rpt_mun_report_advanced'])) {
            // $receipts_adv = Receipt::select('col_receipt.*', 'col_f56_detail.period_covered', 'col_f56_detail.tdrp_assedvalue', 'col_f56_type.name as class', 'col_f56_detail.owner_name', 'col_barangay.name as brgy_name')
            //     ->where('report_date','>=', $date_start)
            //     ->join('col_f56_detail', 'col_f56_detail.col_receipt_id', '=', 'col_receipt.id')
            //     ->join('col_f56_tdarp', 'col_f56_detail.id', '=', 'col_f56_tdarp.col_f56_detail_id')
            //     ->join('col_barangay', 'col_barangay.id', '=','col_f56_tdarp.barangay')
            //     ->join('col_f56_type', 'col_f56_type.id', '=', 'col_f56_tdarp.f56_type')
            //     ->with('items')
            //     ->with('F56Detailmny')
            //     // ->with('barangay')
            //     ->where('report_date','<=', $date_end)
            //     ->where('is_printed', '=', 1)
            //     ->where('af_type', '=', $form_56)
            //     ->where('col_f56_tdarp.municipality', '=', $request['municipality'])
            //     ->where(function($query) {
            //         $query->where('col_f56_detail.period_covered', '=', Carbon::now()->addYear()->format('Y'))
            //             ->orWhere('col_f56_detail.period_covered', 'like', '%advance%');
            //     })
            //     ->orderBy('serial_no', 'ASC')
            //     ->get();

            $receipts_adv = Receipt::select('*')
                ->with('items')
                ->with('F56Detailmny')
                ->with('F56Detailmny.TDARP')
                ->where('report_date','>=', $date_start)
                ->where('report_date','<=', $date_end)
                ->where('af_type', '=', $form_56)
                ->where('is_printed', '=', 1)
                ->whereHas('F56Detailmny', function($query) {
                    $query->where(function($q) {
                        $q->where('period_covered', '=', Carbon::now()->addYear()->format('Y'))
                        ->orWhere('period_covered', 'like', '%advance%');
                    });
                })
                ->whereHas('F56Detailmny.TDARP', function($query) use($request) {
                    $query->where('municipality', '=', $request['municipality']);
                })
                ->orderBy('serial_no', 'ASC')
                ->get();
            
            if (count($receipts_adv) == 0) {
                # No existing transaction
                Session::flash('error', ['No transaction for '. date('F d, Y', strtotime($date_start)) .' to '. date('F d, Y', strtotime($date_end)) .'.']);
                return redirect()->route('report.real_property');
            }
            $this->base['receipts'] = $receipts_adv;
            $pdf = PDF::loadView('collection::pdf/real_property_advanced', $this->base)
            ->setPaper(array(0,0,612,936), 'landscape');
        } elseif(isset($request['rpt_mun_report_advanced_col'])) {
            // $receipts_adv = Receipt::select('col_receipt.*', 'col_f56_detail.period_covered', 'col_f56_detail.tdrp_assedvalue', 'col_barangay.name as brgy_name', 'col_f56_type.name as class', 'col_f56_detail.owner_name')
            //     ->where('report_date','>=', $date_start)
            //     ->join('col_f56_detail', 'col_f56_detail.col_receipt_id', '=', 'col_receipt.id')
            //     ->join('col_f56_tdarp', 'col_f56_detail.id', '=', 'col_f56_tdarp.col_f56_detail_id')
            //     ->join('col_barangay', 'col_barangay.id', '=','col_f56_tdarp.barangay')
            //     ->join('col_f56_type', 'col_f56_type.id', '=', 'col_f56_tdarp.f56_type')
            //     ->with('items')
            //     ->with('F56Detailmny')
            //     ->where('report_date','<=', $date_end)
            //     ->where('is_printed', '=', 1)
            //     ->where('af_type', '=', $form_56)
            //     ->where('col_municipality_id', '=', $request['municipality'])
            //     ->where(function($query) {
            //         $query->where('col_f56_detail.period_covered', '=', Carbon::now()->addYear()->format('Y'))
            //             ->orWhere('col_f56_detail.period_covered', 'like', '%advance%');
            //     })
            //     ->orderBy('serial_no', 'ASC')
            //     ->get();

            $receipts_adv = Receipt::select('*')
                ->with('items')
                ->with('F56Detailmny')
                ->with('F56Detailmny.TDARP')
                ->where('report_date','>=', $date_start)
                ->where('report_date','<=', $date_end)
                ->where('af_type', '=', $form_56)
                ->where('is_printed', '=', 1)
                ->whereHas('F56Detailmny', function($query) {
                    $query->where(function($q) {
                        $q->where('period_covered', '=', Carbon::now()->addYear()->format('Y'))
                        ->orWhere('period_covered', 'like', '%advance%');
                    });
                })
                ->whereHas('F56Detailmny.TDARP', function($query) use($request) {
                    $query->where('municipality', '=', $request['municipality']);
                })
                ->orderBy('serial_no', 'ASC')
                ->get();

            if (count($receipts_adv) == 0) {
                # No existing transaction
                Session::flash('error', ['No transaction for '. date('F d, Y', strtotime($date_start)) .' to '. date('F d, Y', strtotime($date_end)) .'.']);
                return redirect()->route('report.real_property');
            }
            $this->base['receipts'] = $receipts_adv;
            $pdf = PDF::loadView('collection::pdf/rpt_advance_collections', $this->base)
            ->setPaper('legal', 'landscape');
        } elseif(isset($request['rpt_mun_report_advanced_sd'])) {
            // $receipts_adv = Receipt::select('col_receipt.*', 'col_f56_detail.period_covered', 'col_f56_detail.tdrp_assedvalue', 'col_barangay.name as brgy_name', 'col_f56_type.name as class', 'col_f56_detail.owner_name')
            //     ->where('report_date','>=', $date_start)
            //     ->join('col_f56_detail', 'col_f56_detail.col_receipt_id', '=', 'col_receipt.id')
            //     ->join('col_f56_tdarp', 'col_f56_detail.id', '=', 'col_f56_tdarp.col_f56_detail_id')
            //     ->join('col_barangay', 'col_barangay.id', '=','col_f56_tdarp.barangay')
            //     ->join('col_f56_type', 'col_f56_type.id', '=', 'col_f56_tdarp.f56_type')
            //     ->with('items')
            //     ->with('F56Detailmny')
            //     ->where('report_date','<=', $date_end)
            //     ->where('is_printed', '=', 1)
            //     ->where('af_type', '=', $form_56)
            //     ->where('col_municipality_id', '=', $request['municipality'])
            //     ->where(function($query) {
            //         $query->where('col_f56_detail.period_covered', '=', Carbon::now()->addYear()->format('Y'))
            //             ->orWhere('col_f56_detail.period_covered', 'like', '%advance%');
            //     })
            //     ->orderBy('serial_no', 'ASC')
            //     ->get();

            $receipts_adv = Receipt::select('*')
                ->with('items')
                ->with('F56Detailmny')
                ->with('F56Detailmny.TDARP')
                ->where('report_date','>=', $date_start)
                ->where('report_date','<=', $date_end)
                ->where('af_type', '=', $form_56)
                ->where('is_printed', '=', 1)
                ->whereHas('F56Detailmny', function($query) {
                    $query->where(function($q) {
                        $q->where('period_covered', '=', Carbon::now()->addYear()->format('Y'))
                        ->orWhere('period_covered', 'like', '%advance%');
                    });
                })
                ->whereHas('F56Detailmny.TDARP', function($query) use($request) {
                    $query->where('municipality', '=', $request['municipality']);
                })
                ->orderBy('serial_no', 'ASC')
                ->get();
                
            if (count($receipts_adv) == 0) {
                # No existing transaction
                Session::flash('error', ['No transaction for '. date('F d, Y', strtotime($date_start)) .' to '. date('F d, Y', strtotime($date_end)) .'.']);
                return redirect()->route('report.real_property');
            }
            $this->base['receipts'] = $receipts_adv;
            $pdf = PDF::loadView('collection::pdf/rpt_advance_sd', $this->base)
            ->setPaper('legal', 'landscape');
        } elseif(isset($request['rpt_mun_report_protest'])) {
            $receipts_protest = Receipt::where('report_date','>=', $date_start)
                ->with('items')
                ->where('report_date','<=', $date_end)
                ->where('is_printed', '=', 1)
                ->where('af_type', '=', $form_56)
                ->where('col_municipality_id', '=', $request['municipality'])
                ->where(function($query) {
                    $query->where('remarks', 'like', '%paid under protest%')
                        ->orWhere('remarks', 'like', '%held in trust%')
                        ->orWhere('bank_remark', 'like', '%paid under protest%')
                        ->orWhere('bank_remark', 'like', '%held in trust%');
                })
                ->whereHas('F56Detailmny', function($query) {
                    $query->where('period_covered', 'not like', Carbon::now()->addYear()->format('Y'))
                        ->where('period_covered', 'not like', '%advance%');
                })
                ->orderBy('serial_no', 'ASC')
                ->get();

            if(count($receipts_protest) == 0) {
                # No existing transaction
                Session::flash('error', ['No transaction for '. date('F d, Y', strtotime($date_start)) .' to '. date('F d, Y', strtotime($date_end)) .'.']);
                return redirect()->route('report.real_property');
            }

            $this->base['receipts'] = $receipts_protest;
            // $pdf = PDF::loadView('collection::pdf/real_property', $this->base)
            // ->setPaper('legal', 'landscape');

            $pdf = PDF::loadView('collection::pdf/new_rpt_abstract/real_property', $this->base)
            ->setPaper(array(0,0,612,936), 'landscape');
        } elseif(isset($request['rpt_mun_report_protest_col'])) {
            $receipts_protest = Receipt::where('report_date','>=', $date_start)
                ->with('items')
                ->where('report_date','<=', $date_end)
                ->where('is_printed', '=', 1)
                ->where('af_type', '=', $form_56)
                ->where('col_municipality_id', '=', $request['municipality'])
                ->where(function($query) {
                    $query->where('remarks', 'like', '%paid under protest%')
                        ->orWhere('remarks', 'like', '%held in trust%')
                        ->orWhere('bank_remark', 'like', '%paid under protest%')
                        ->orWhere('bank_remark', 'like', '%held in trust%');
                })
                ->whereHas('F56Detailmny', function($query) {
                    $query->where('period_covered', 'not like', Carbon::now()->addYear()->format('Y'))
                        ->where('period_covered', 'not like', '%advance%');
                })
                ->orderBy('serial_no', 'ASC')
                ->get();

            if(count($receipts_protest) == 0) {
                # No existing transaction
                Session::flash('error', ['No transaction for '. date('F d, Y', strtotime($date_start)) .' to '. date('F d, Y', strtotime($date_end)) .'.']);
                return redirect()->route('report.real_property');
            }

            $this->base['receipts'] = $receipts_protest;
            // $pdf = PDF::loadView('collection::pdf/real_property_collections', $this->base)
            // ->setPaper('legal', 'landscape');

            $pdf = PDF::loadView('collection::pdf/new_rpt_abstract/real_property_collections', $this->base)
            ->setPaper(array(0,0,612,936), 'landscape');
        } elseif(isset($request['rpt_mun_report_protest_sd'])) {
            $receipts_protest = Receipt::where('report_date','>=', $date_start)
                ->with('items')
                ->where('report_date','<=', $date_end)
                ->where('is_printed', '=', 1)
                ->where('af_type', '=', $form_56)
                ->where('col_municipality_id', '=', $request['municipality'])
                ->where(function($query) {
                    $query->where('remarks', 'like', '%paid under protest%')
                        ->orWhere('remarks', 'like', '%held in trust%')
                        ->orWhere('bank_remark', 'like', '%paid under protest%')
                        ->orWhere('bank_remark', 'like', '%held in trust%');
                })
                ->whereHas('F56Detailmny', function($query) {
                    $query->where('period_covered', 'not like', Carbon::now()->addYear()->format('Y'))
                        ->where('period_covered', 'not like', '%advance%');
                })
                ->orderBy('serial_no', 'ASC')
                ->get();

            if(count($receipts_protest) == 0) {
                # No existing transaction
                Session::flash('error', ['No transaction for '. date('F d, Y', strtotime($date_start)) .' to '. date('F d, Y', strtotime($date_end)) .'.']);
                return redirect()->route('report.real_property');
            }

            $this->base['receipts'] = $receipts_protest;
            // $pdf = PDF::loadView('collection::pdf/real_property_summary_disposition', $this->base)
            // ->setPaper('legal', 'landscape');

            $pdf = PDF::loadView('collection::pdf/new_rpt_abstract/real_property_summ_dispo', $this->base)
            ->setPaper(array(0,0,612,936), 'landscape');
        }

        
        return @$pdf->stream();
    }

    public function real_property_consolidated(Request $request)
    {
        $form_56 = 2;
        $date_start = date('Y-m-d', strtotime($request['start_date']));
        $date_end = date('Y-m-d', strtotime($request['end_date']));
        $report_date = date('F d, Y', strtotime($request['report_date']));
        $this->base['report_date'] = $report_date;
        $receipts = Receipt::where('report_date','>=', $date_start)
            ->where('report_date','<=', $date_end)
            ->where('is_printed', '=', 1)
            ->where('is_cancelled', '<>', 1)
            ->where('af_type', '=', $form_56)
            ->orderBy('serial_no', 'ASC')
            ->get();

        if (count($receipts) == 0) {
            # No existing transaction
            Session::flash('error', ['No transaction for '. date('F d, Y', strtotime($date_start)) .' to '. date('F d, Y', strtotime($date_end)) .'.']);
            return redirect()->route('report.real_property');
        }

        $class_amt = array();
        $classes = F56Type::geT();
        foreach ($classes as $class) {
            $class_amt[$class->id] = array(
                'basic_current' => 0,
                'basic_discount' => 0,
                'basic_previous' => 0,
                'basic_penalty_current' => 0,
                'basic_penalty_previous' => 0,
                'basic_adv' => 0,
                'basic_adv_discount' => 0,
                'basic_prior_1991' => 0,
                'basic_prior_penalty_1991' => 0,
                'basic_prior_1992' => 0,
                'basic_prior_penalty_1992' => 0,
            );
        }

        $prior_start = Carbon::now()->subYears(2)->format('Y');
        $preceeding = Carbon::now()->subYear()->format('Y');
        $advance_yr = Carbon::now()->addYear()->format('Y');
        $current = Carbon::now()->format('Y');

        $this->base['prior_start'] = $prior_start;
        $this->base['preceeding'] = $preceeding;
        $this->base['advance_yr'] = $advance_yr;
        $this->base['current'] = $current;

        foreach ($receipts as $rcpt_index => $receipt) {
            if ($receipt->is_cancelled) {
                continue;
            }

            if (isset($receipt->F56Detail)) {
                $index = $receipt->F56Detail->col_f56_type_id;
                if($receipt->F56Detail->period_covered == $current) {
                    $class_amt[$index]['basic_current'] += $receipt->F56Detail->basic_current;
                    $class_amt[$index]['basic_discount'] += $receipt->F56Detail->basic_discount;
                    $class_amt[$index]['basic_penalty_current'] += $receipt->F56Detail->basic_penalty_current;
                }

                if($receipt->F56Detail->period_covered == $preceeding) {
                    $class_amt[$index]['basic_previous'] += $receipt->F56Detail->basic_previous;
                    $class_amt[$index]['basic_penalty_previous'] += $receipt->F56Detail->basic_penalty_previous;
                }
                
                if($receipt->F56Detail->period_covered == $advance_yr) {
                    $class_amt[$index]['basic_adv'] += $receipt->F56Detail->tdrp_assedvalue*.01;
                    $class_amt[$index]['basic_adv_discount'] += ($receipt->F56Detail->tdrp_assedvalue*.01)*.10;
                }
                
                if($receipt->F56Detail->period_covered <= $prior_start && $receipt->F56Detail->period_covered >= 1992) {
                    $class_amt[$index]['basic_prior_1992'] += $receipt->F56Detail->basic_previous;
                    $class_amt[$index]['basic_prior_penalty_1992'] += $receipt->F56Detail->basic_penalty_previous;
                }

                if($receipt->F56Detail->period_covered <= $prior_start && $receipt->F56Detail->period_covered <= 1991) {
                    $class_amt[$index]['basic_prior_1991'] += $receipt->F56Detail->basic_previous;
                    $class_amt[$index]['basic_prior_penalty_1991'] += $receipt->F56Detail->basic_penalty_previous;
                }
            }
        }

        $this->base['f56_type'] = F56Type::get();
        $this->base['date_range'] = date('F d, Y', strtotime($date_start)) .' to '. date('F d, Y', strtotime($date_end)) .'.';
        $this->base['receipts'] = $receipts;
        $this->base['class_amt'] = $class_amt;

        $merged = array_merge($this->base, $request->all());

        $this->base['merged'] = $merged;

        $pdf = new PDF;
        if(isset($request["button"])){
            // $pdf = PDF::loadView('collection::pdf/real_property_consolidated', $this->base)
            // ->setPaper('legal', 'landscape');

            $pdf = PDF::loadView('collection::pdf/new_rpt_abstract/real_property', $this->base)
            ->setPaper(array(0,0,612,936), 'landscape');
        }elseif (isset($request["rpt_mun_report_collections"])) {
            //  $pdf = PDF::loadView('collection::pdf/real_property_consolidated_collections', $this->base)
            // ->setPaper('legal', 'landscape');
            
            $pdf = PDF::loadView('collection::pdf/new_rpt_abstract/real_property_collections', $this->base)
            ->setPaper(array(0,0,612,936), 'landscape');
        }elseif (isset($request["rpt_mun_report_summary_disposition"])) {
            //  $pdf = PDF::loadView('collection::pdf/real_property_consolidated_summary_disposition', $this->base)
            // ->setPaper('legal', 'landscape');

            $pdf = PDF::loadView('collection::pdf/new_rpt_abstract/real_property_summ_dispo', $this->base)
            ->setPaper(array(0,0,612,936), 'landscape');
        }
        
        return @$pdf->stream();
    }

    public function real_property_p2(Request $request)
    {
        $form_56 = 2;
        $date_start = date('Y-m-d', strtotime($request['start_date']));
        $date_end = date('Y-m-d', strtotime($request['end_date']));
        $report_date = date('F d, Y', strtotime($request['report_date']));
        $this->base['report_date'] = $report_date;
        $receipts = Receipt::where('report_date','>=', $date_start)
            ->where('report_date','<=', $date_end)
            ->where('is_printed', '=', 1)
            ->where('is_cancelled', '=', 0)
            ->where('af_type', '=', $form_56)
            ->orderBy('serial_no', 'ASC')
            ->get();

            // dd($receipts);
        if (count($receipts) == 0) {
            # No existing transaction
            Session::flash('error', ['No transaction for '. date('F d, Y', strtotime($date_start)) .' to '. date('F d, Y', strtotime($date_end)) .'.']);
            return redirect()->route('report.real_property');
        }

        # get list of transaction types
        $trantypes = [];
        $transaction_types = TransactionType::get();
        foreach($transaction_types as $type) {
            $trantypes[$type->id] = ['name' => $type->name, 'total' => 0];
        }
        # for remittances/deposits
        $remdep = array();
        $municipalities = Municipality::get();
        foreach ($municipalities as $mun) {
            $remdep[$mun->id] = ['name' => $mun->name, 'value' => 0];
        }

        $rcpt_acct_af = array();
        $bank = array();
        $total_paymt = 0;

        foreach ($receipts as $rcpt_index => $receipt) {
            if (!isset($rcpt_acct_af[$receipt->col_serial_id])) {
                $rcpt_acct_af[$receipt->col_serial_id]['serials'] = [];
            }

            array_push($rcpt_acct_af[$receipt->col_serial_id]['serials'], $receipt->serial_no);

            if ($receipt->is_cancelled) {
                continue;
            }

            $items = $receipt->items;
            foreach($items as $item) {
                $bank_row = array();
                if (isset($bank[$receipt->serial_no])){
                    $bank[$receipt->serial_no]['amt'] += $item->value;
                } elseif (in_array($receipt->transaction_type, [2,3])) {
                    $bank_row['bank'] = $receipt->bank_name;
                    $bank_row['check_no'] = $receipt->bank_number;
                    $bank_row['payee'] = $receipt->customer->name;

                    // if (isset($receipt->F56Detailmny)) {
                    //     $bank_row['amt'] = 0;
                    //     foreach ($receipt->F56Detailmny as $f56item) {
                    //         $bank_gross = $f56item->basic_current + $f56item->basic_previous + $f56item->basic_penalty_current + $f56item->basic_penalty_previous;
                    //         $bank_net = $bank_gross - $f56item->basic_discount;
                    //         $bank_row['amt'] += $bank_net;
                    //     }
                    //      $bank_row['amt'] += $bank_net;
                    // }

                    $bank_row['amt'] = $item->value;

                    $bank[$receipt->serial_no] = $bank_row;
                }
            }
            
        }
        $rcpt_acct = $this->format_sort_af($form_56, $rcpt_acct_af, $date_start, $date_end);
        # This part for remittances/deposits
        $mun_rcpts = Receipt::where('report_date','>=', $date_start)
            ->where('report_date','<=', $date_end)
            ->where('is_printed', '=', 1)
            ->where('af_type', '=', $form_56)
            ->where('is_cancelled', '=', 0)
            ->orderBy('serial_no', 'ASC')
            // ->groupby('bank_number')
            ->get();
            $checker= [];
        foreach ($mun_rcpts as $mun_rcptx) {
            if (isset($mun_rcptx->F56Detailmny)) {
               foreach ($mun_rcptx->F56Detailmny as $mun_rcpt) {
                    $sub_gross = $mun_rcpt->basic_current + $mun_rcpt->basic_previous + $mun_rcpt->basic_penalty_current + $mun_rcpt->basic_penalty_previous;
                    $sub_net = $sub_gross - $mun_rcpt->basic_discount;
                    $remdep[$mun_rcptx->col_municipality_id]['value'] += ($sub_net * 2);
                    $checker[$mun_rcptx->col_municipality_id][] = $mun_rcpt->serial_no;
                    # not ADA
                    if ($mun_rcptx->transaction_type != 4) {
                        $total_paymt += ($sub_net * 2);
                    }
                    $trantypes[$mun_rcptx->transaction_type]['total'] += ($sub_net * 2);
               }
            }
        }

        if ($date_start == $date_end) {
            $this->base['date_range'] = date('F d, Y', strtotime($date_start));
        } else {
            $this->base['date_range'] = date('F d', strtotime($date_start)) .' - '. date('d, Y', strtotime($date_end));
        }
        $this->base['report_start'] = date('F d, Y', strtotime($date_start));
        $this->base['report_no'] = $request->report_no;

        $this->base['officer_name'] = ReportOfficers::where('name', 'rpt_name')->first();
        $this->base['officer_position'] = ReportOfficers::where('name', 'rpt_position')->first();
        $this->base['receipts'] = $receipts;
        $this->base['rcpt_acct'] = $rcpt_acct;
        $this->base['bank'] = $bank;
        $this->base['trantypes'] = $trantypes;
        $this->base['remdep'] = $remdep;
        $this->base['acctble_officer_name'] = ReportOfficers::whereId(10)->first();
        $this->base['acctble_officer_position'] = ReportOfficers::whereId(11)->first();
        $this->base['total_paymt'] = $total_paymt;
        // $this->base['total_in_words'] = $this->convert_number_to_words($total_paymt);
        $this->base['total_in_words'] = $this->convert_number_to_words(number_format($total_paymt, 2, '.', ''));
        $pdf = new PDF;
        // dd($this->base);
        $pdf = PDF::loadView('collection::pdf/real_property_p2', $this->base)->setPaper('legal', 'landscape');
        return @$pdf->stream();
    }

    public function provincial_income(Request $request)
    {
        $this->base['year'] = $request->year;
        $this->base['month'] = $request->month;
        $this->base['year_x'] = Carbon::createFromDate($request->year, 1, 1, 'Asia/Manila');
        $this->base['month_x'] = Carbon::createFromDate($request->year, 1, 1, 'Asia/Manila');
        $this->base['month_end'] = Carbon::createFromDate($request->year, ($request->month), 1, 'Asia/Manila');

        $get_categories = AccountCategory::get();
        $categories = [];
        foreach($get_categories as $categ) {
            if($categ->id == 2) { // remove BTS..
                continue;
            } else {
                $categories[$categ->id] = $categ->name;
            }
        }

        $this->base['categories'] = AccountCategory::get();
        $this->base['categories_wo_bts'] = $categories;
        $this->base['acct_groups'] = AccountGroup::get();

        $rpt_basic = 0;
        $rpt_basic_penalty = 0;
        $rpt_sef = 0;
        $rpt_sef_penalty = 0;

        // prev months provincial income
        $prev_rpt_basic = 0;
        $prev_rpt_basic_penalty = 0;
        $prev_rpt_sef = 0;
        $prev_rpt_sef_penalty = 0;

        $pprv_crnt_ammount = 0;
        $total_basic_current = 0;
        $total_basic_discount = 0;
        $total_basic_previous = 0;
        $total_basic_penalty_current = 0;
        $total_basic_penalty_previous = 0;
        $pprv_crnt_discount = 0;
        $pprv_prvious_ammount = 0;
        $pprv_pnalties_crnt = 0;
        $pprv_pnalties_prvious = 0;
        $xtotal_basic_current = 0;
        $xtotal_basic_discount = 0;
        $xtotal_basic_previous = 0;
        $xtotal_basic_penalty_current = 0;
        $xtotal_basic_penalty_previous = 0;

        $prev_pprv_crnt_ammount = 0;
        $prev_total_basic_current = 0;
        $prev_total_basic_discount = 0;
        $prev_total_basic_previous = 0;
        $prev_total_basic_penalty_current = 0;
        $prev_total_basic_penalty_previous = 0;
        $prev_pprv_crnt_discount = 0;
        $prev_pprv_prvious_ammount = 0;
        $prev_pprv_pnalties_crnt = 0;
        $prev_pprv_pnalties_prvious = 0;
        $prev_xtotal_basic_current = 0;
        $prev_xtotal_basic_discount = 0;
        $prev_xtotal_basic_previous = 0;
        $prev_xtotal_basic_penalty_current = 0;
        $prev_xtotal_basic_penalty_previous = 0;
        for($i = 1; $i <= 14; $i++) { // for all municipalities
            $receipts = Receipt::whereMonth('report_date','=', $request->month)
                ->whereYear('report_date','=', $request->year)
                ->where('is_printed', '=', 1)
                ->where('is_cancelled', '=', 0)
                ->where('af_type', '=', 2)
                ->where('col_municipality_id', '=', $i)
                ->orderBy('serial_no', 'ASC')
                ->get();
            // $pprv_crnt_ammount = 0;
            // $total_basic_current = 0;
            // $total_basic_discount = 0;
            // $total_basic_previous = 0;
            // $total_basic_penalty_current = 0;
            // $total_basic_penalty_previous = 0;
            foreach ($receipts as $receipt) {
                foreach ($receipt->F56Detailmny as $f56_detail) {
                    // prov basic current amount
                    $total_basic_current += $f56_detail->basic_current;
                    $munshare_basic_current = round($total_basic_current * .4,2);
                    $brgyshare_basic_current = round($total_basic_current * .25,2);
                    $prv_crnt_ammount = ($total_basic_current - ($munshare_basic_current + $brgyshare_basic_current));
                    $pprv_crnt_ammount = round($prv_crnt_ammount,2,PHP_ROUND_HALF_UP);   
                    // prov basic current discount
                    $total_basic_discount += $f56_detail->basic_discount;
                    $brgyshare_basic_discount = round($total_basic_discount * .25,2);
                    $munshare_basic_discount = round($total_basic_discount * .4,2);
                    $prv_crnt_discount = ($total_basic_discount - ($munshare_basic_discount + $brgyshare_basic_discount));
                    $pprv_crnt_discount = round($prv_crnt_discount,2,PHP_ROUND_HALF_DOWN);
                    // prov basic previous amount
                    $total_basic_previous += $f56_detail->basic_previous;
                    $brgyshare_basic_previous = round($total_basic_previous * .25,2);
                    $munshare_basic_previous = round($total_basic_previous * .4,2);
                    $prv_prvious_ammount = ($total_basic_previous - ($munshare_basic_previous + $brgyshare_basic_previous));
                    $pprv_prvious_ammount = round($prv_prvious_ammount,2,PHP_ROUND_HALF_UP);
                    // prov basic penalties current
                    $total_basic_penalty_current += $f56_detail->basic_penalty_current;
                    $brgyshare_basic_penalty_current = round($total_basic_penalty_current * .25,2);
                    $munshare_basic_penalty_current = round($total_basic_penalty_current * .4,2);
                    $prv_pnalties_crnt = $total_basic_penalty_current - ($munshare_basic_penalty_current + $brgyshare_basic_penalty_current);
                    $pprv_pnalties_crnt =  round($prv_pnalties_crnt,2,PHP_ROUND_HALF_UP);
                    // prov basic penalties previous
                    $total_basic_penalty_previous += $f56_detail->basic_penalty_previous;
                    $brgyshare_basic_penalty_previous = round($total_basic_penalty_previous * .25,2);
                    $munshare_basic_penalty_previous = round($total_basic_penalty_previous * .4,2);
                    $prv_pnalties_prvious = $total_basic_penalty_previous - ($munshare_basic_penalty_previous + $brgyshare_basic_penalty_previous);
                    $pprv_pnalties_prvious = round($prv_pnalties_prvious,2,PHP_ROUND_HALF_UP);

                    // prov sef current amount
                    $bbrgyshare_basic_current = round($brgyshare_basic_current,2,PHP_ROUND_HALF_DOWN);
                    $total_basic_current = $pprv_crnt_ammount + $munshare_basic_current + $bbrgyshare_basic_current;
                    $xtotal_basic_current = round($total_basic_current * .5,2,PHP_ROUND_HALF_UP);
                    // prov sef current discount
                    $xtotal_basic_discount = round($total_basic_discount*.5,2,PHP_ROUND_HALF_DOWN);
                    // prov sef previous amount
                    $xtotal_basic_previous = $total_basic_previous * .5;
                    // prov sef penalties current
                    $xtotal_basic_penalty_current = $total_basic_penalty_current * .5;
                    // prov sef penalties previous
                    $xtotal_basic_penalty_previous = $total_basic_penalty_previous * .5;
                }
            }
            $rpt_basic += $pprv_crnt_ammount - $pprv_crnt_discount + $pprv_prvious_ammount;
            $rpt_basic_penalty += $pprv_pnalties_crnt + $pprv_pnalties_prvious;
            $rpt_sef += $xtotal_basic_current - $xtotal_basic_discount + $xtotal_basic_previous;
            $rpt_sef_penalty += $xtotal_basic_penalty_current + $xtotal_basic_penalty_previous;

            // previous rpt
            if($request['month'] == 12) {
                $rcpts_prev = Receipt::whereYear('report_date', '=', ($request['year']-1))
                    ->where('is_printed', '=', 1)
                    ->where('is_cancelled', '=', 0)
                    ->where('af_type', '=', 2)
                    ->where('col_municipality_id', '=', $i)
                    ->orderBy('serial_no', 'ASC')
                    ->get();
            } else {
                $rcpts_prev = Receipt::whereMonth('report_date', '<', $request->month)
                    ->whereYear('report_date' ,'=', $request['year'])
                    ->where('is_printed', '=', 1)
                    ->where('is_cancelled', '=', 0)
                    ->where('af_type', '=', 2)
                    ->where('col_municipality_id', '=', $i)
                    ->orderBy('serial_no', 'ASC')
                    ->get();
            }
            
            foreach ($rcpts_prev as $prev_rcpt) {
                foreach ($prev_rcpt->F56Detailmny as $f56_detail) {
                    // prov basic current amount
                    $prev_total_basic_current += $f56_detail->basic_current;
                    $prev_munshare_basic_current = round($prev_total_basic_current * .4,2);
                    $prev_brgyshare_basic_current = round($prev_total_basic_current * .25,2);
                    $prev_prv_crnt_ammount = ($prev_total_basic_current - ($prev_munshare_basic_current + $prev_brgyshare_basic_current));
                    $prev_pprv_crnt_ammount = round($prev_prv_crnt_ammount,2,PHP_ROUND_HALF_UP);   

                    // prov basic current discount
                    $prev_total_basic_discount += $f56_detail->basic_discount;
                    $prev_brgyshare_basic_discount = round($prev_total_basic_discount * .25,2);
                    $prev_munshare_basic_discount = round($prev_total_basic_discount * .4,2);
                    $prev_prv_crnt_discount = ($prev_total_basic_discount - ($prev_munshare_basic_discount + $prev_brgyshare_basic_discount));
                    $prev_pprv_crnt_discount = round($prev_prv_crnt_discount,2,PHP_ROUND_HALF_DOWN);

                    // prov basic previous amount
                    $prev_total_basic_previous += $f56_detail->basic_previous;
                    $prev_brgyshare_basic_previous = round($prev_total_basic_previous * .25,2);
                    $prev_munshare_basic_previous = round($prev_total_basic_previous * .4,2);
                    $prev_prv_prvious_ammount = ($prev_total_basic_previous - ($prev_munshare_basic_previous + $prev_brgyshare_basic_previous));
                    $prev_pprv_prvious_ammount = round($prev_prv_prvious_ammount,2,PHP_ROUND_HALF_UP);

                    // prov basic penalties current
                    $prev_total_basic_penalty_current += $f56_detail->basic_penalty_current;
                    $prev_brgyshare_basic_penalty_current = round($prev_total_basic_penalty_current * .25,2);
                    $prev_munshare_basic_penalty_current = round($prev_total_basic_penalty_current * .4,2);
                    $prev_prv_pnalties_crnt = $prev_total_basic_penalty_current - ($prev_munshare_basic_penalty_current + $prev_brgyshare_basic_penalty_current);
                    $prev_pprv_pnalties_crnt =  round($prev_prv_pnalties_crnt,2,PHP_ROUND_HALF_UP);

                    // prov basic penalties previous
                    $prev_total_basic_penalty_previous += $f56_detail->basic_penalty_previous;
                    $prev_brgyshare_basic_penalty_previous = round($prev_total_basic_penalty_previous * .25,2);
                    $prev_munshare_basic_penalty_previous = round($prev_total_basic_penalty_previous * .4,2);
                    $prev_prv_pnalties_prvious = $prev_total_basic_penalty_previous - ($prev_munshare_basic_penalty_previous + $prev_brgyshare_basic_penalty_previous);
                    $prev_pprv_pnalties_prvious = round($prev_prv_pnalties_prvious,2,PHP_ROUND_HALF_UP);

                    // prov sef current amount
                    $prev_bbrgyshare_basic_current = round($prev_brgyshare_basic_current,2,PHP_ROUND_HALF_DOWN);
                    $prev_prev_total_basic_current = $prev_pprv_crnt_ammount + $prev_munshare_basic_current + $prev_bbrgyshare_basic_current;
                    $prev_xtotal_basic_current = round($prev_prev_total_basic_current * .5,2,PHP_ROUND_HALF_UP);
                    // prov sef current discount
                    $prev_xtotal_basic_discount = round($prev_total_basic_discount*.5,2,PHP_ROUND_HALF_DOWN);
                    // prov sef previous amount
                    $prev_xtotal_basic_previous = $prev_total_basic_previous * .5;
                    // prov sef penalties current
                    $prev_xtotal_basic_penalty_current = $prev_total_basic_penalty_current * .5;
                    // prov sef penalties previous
                    $prev_xtotal_basic_penalty_previous = $prev_total_basic_penalty_previous * .5;
                }
            }
            $prev_rpt_basic += $prev_pprv_crnt_ammount - $prev_pprv_crnt_discount + $prev_pprv_prvious_ammount;
            $prev_rpt_basic_penalty += $prev_pprv_pnalties_crnt + $prev_pprv_pnalties_prvious;
            $prev_rpt_sef += $prev_xtotal_basic_current - $prev_xtotal_basic_discount + $prev_xtotal_basic_previous;
            $prev_rpt_sef_penalty += $prev_xtotal_basic_penalty_current + $prev_xtotal_basic_penalty_previous;
        }

        // BTS
        $bts = ReceiptItems::select('col_acct_title_id', 'col_acct_title.name', DB::raw('sum(share_provincial) as total')) 
        ->join('col_acct_title', 'col_receipt_items.col_acct_title_id', '=', 'col_acct_title.id')
        ->join('col_receipt', 'col_receipt.id', '=', 'col_receipt_items.col_receipt_id')
        ->join('col_acct_group', 'col_acct_title.acct_group_id', '=', 'col_acct_group.id')
        ->join('col_acct_category', 'col_acct_category.id', '=', 'col_acct_group.acct_category_id')
        // ->where(DB::raw('month(report_date)'),'=', $request->month)
        // ->where(DB::raw('year(report_date)'),'=', $request->year)
        ->whereMonth('report_date','=', $request->month)
        ->whereYear('report_date','=', $request->year)
        ->where('is_printed', 1)
        ->where('is_cancelled', 0)
        ->where('col_acct_group.acct_category_id', 2)
        // ->where(function($accts) {
        //     $accts->where('col_acct_category.id', 2)
        //     ->orWhere('col_acct_title_id', 33)
        //     ->orWhere('col_acct_title_id', 34);
        // })
        // ->where('col_acct_category.id', 2)
        // ->where('col_acct_title_id', 33)
        ->groupBy('col_receipt_items.col_acct_title_id')
        ->get();

        $bts_prev = ReceiptItems::select('col_acct_title_id', 'col_acct_title.name', DB::raw('sum(share_provincial) as total')) 
            ->join('col_acct_title', 'col_receipt_items.col_acct_title_id', '=', 'col_acct_title.id')
            ->join('col_receipt', 'col_receipt.id', '=', 'col_receipt_items.col_receipt_id')
            ->join('col_acct_group', 'col_acct_title.acct_group_id', '=', 'col_acct_group.id')
            ->join('col_acct_category', 'col_acct_category.id', '=', 'col_acct_group.acct_category_id')
            ->whereMonth('report_date','<', $request->month)
            ->whereYear('report_date','=', $request->year)
            ->where('is_printed', 1)
            ->where('is_cancelled', 0)
            ->where('col_acct_group.acct_category_id', 2)
            ->groupBy('col_receipt_items.col_acct_title_id')
            ->get();

        $bts_arr = [];
        foreach ($bts as $b) {
            $bts_arr[$b->col_acct_title_id] = $b->total;
        }

        $bts_arr_prev = [];
        foreach ($bts_prev as $b) {
            $bts_arr_prev[$b->col_acct_title_id] = $b->total;
        }

        $this->base['rpt_basic'] = $rpt_basic;
        $this->base['rpt_basic_penalty'] = $rpt_basic_penalty;
        $this->base['rpt_sef'] = $rpt_sef;
        $this->base['rpt_sef_penalty'] = $rpt_sef_penalty;
        $this->base['bts'] = $bts_arr;

        // computation per category
        $month_x = Carbon::createFromDate($request->year, 1, 1, 'Asia/Manila');
        $start_month = 1;
        $end_month = $request->month - 1;
        $month_p = [];
        $d_month = 0;
        for($x=0; $x<$end_month; $x++){
            $month_x =$month_x->addMonths($d_month);
            $month_p[$x]['y-m-d'] = $month_x->format('Y-m-d');
            $month_p[$x]['m'] = $month_x->format('m');
            $d_month = 1;
        }

        // compute START .. 
        $start_month = 1;
        $end_month = $request->month - 1;
        $month_p = [];
        $d_month = 0;
        for($x = 0; $x < $end_month; $x++){
            $month_x = $month_x->addMonths($d_month);
            $month_p[$x]['y-m-d'] = $month_x->format('Y-m-d');
            $month_p[$x]['m'] = $month_x->format('m');
            $d_month = 1;
        }
        $per_account = [];
        $total_per_category = [];
        foreach($this->base['categories'] as $category) {
            // per group .. 
            if(!isset($total_per_category[$category->id])) {
                $total_per_category[$category->id]['budget_estimate'] = 0;
                $total_per_category[$category->id]['actual_coll'] = 0;
                $total_per_category[$category->id]['total'] = 0;
                $total_per_category[$category->id]['percent_coll'] = 0;
                $total_per_category[$category->id]['past_month'] = 0;
            }
            foreach ($category->group as $group) {
                // per title ..
                if(!isset($total_per_category[$category->id][$group->id])) {
                    $total_per_category[$category->id][$group->id]['budget_estimate'] = 0;
                    $total_per_category[$category->id][$group->id]['actual_coll'] = 0;
                    $total_per_category[$category->id][$group->id]['total'] = 0;
                    $total_per_category[$category->id][$group->id]['percent_coll'] = 0;
                    $total_per_category[$category->id][$group->id]['past_month'] = 0;

                    if($group->name == 'Tax Revenue' && $category->id == 1) {
                        if(!isset($total_per_category[$category->id][$group->id]['tax_revenue'])) {
                            $total_per_category[$category->id][$group->id]['tax_revenue']['budget_estimate'] = 0;
                            $total_per_category[$category->id][$group->id]['tax_revenue']['actual_coll'] = 0;
                            $total_per_category[$category->id][$group->id]['tax_revenue']['total'] = 0;
                            $total_per_category[$category->id][$group->id]['tax_revenue']['percent_coll'] = 0;
                            $total_per_category[$category->id][$group->id]['tax_revenue']['past_month'] = 0;
                        }

                        if(!isset($total_per_category[$category->id]['tax_revenue'])) {
                            $total_per_category[$category->id]['tax_revenue']['budget_estimate'] = 0;
                            $total_per_category[$category->id]['tax_revenue']['actual_coll'] = 0;
                            $total_per_category[$category->id]['tax_revenue']['total'] = 0;
                            $total_per_category[$category->id]['tax_revenue']['percent_coll'] = 0;
                            $total_per_category[$category->id]['tax_revenue']['past_month'] = 0;
                        }
                    }
                }

                foreach($group->title as $title) {
                    if ($title->show_in_monthly == 1 && array_search($title->id, [34,36,37,39]) == false) {
                        $per_account[$category->id][$group->id]['group'][$title->id]['budget_estimate'] = $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] = $per_account[$category->id][$group->id]['group'][$title->id]['total'] = $per_account[$category->id][$group->id]['group'][$title->id]['percent_coll'] = $per_account[$category->id][$group->id]['group'][$title->id]['past_month'] = 0;

                        $per_account[$category->id][$group->id]['group_name'] = $group->name;
                        $per_account[$category->id][$group->id]['group'][$title->id]['account_title'] = $title->name;
                        $per_account[$category->id][$group->id]['group'][$title->id]['account_code'] = $title->code;
                        if(isset($title->budget()->where('year','=',$request->year)->first()->value))
                            $per_account[$category->id][$group->id]['group'][$title->id]['budget_estimate'] = $title->budget()->where('year','=',$request->year)->first()->value;
                        else
                            $per_account[$category->id][$group->id]['group'][$title->id]['budget_estimate'] = 0;

                        $this_month = 0;
                        $this_monthx = 0;
                        $cash_div_value_curr = 0;

                        $mnthly_pix = $title->mnhtly_prov_income()->where('year','=',$request->year)->where('month','=',$request->month)->first();
                        if($mnthly_pix){
                            if($mnthly_pix->total_value != '0.00' || $mnthly_pix->total_value != '' ) {
                                $this_month =  $mnthly_pix->total_value;
                            }else{
                                $this_month =  $mnthly_pix->value;
                            }
                            $this_monthx = $mnthly_pix->value;
                        }
                        $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] = $this_monthx;

                        // $get_cashdiv_current = CashDivision::select('col_cash_division_items.col_acct_title_id', DB::raw('sum(value) as total'))
                        //     ->join('col_cash_division_items', 'col_cash_division.id', '=', 'col_cash_division_items.col_cash_division_id')
                        //     ->join('col_acct_title', 'col_cash_division_items.col_acct_title_id', '=', 'col_acct_title.id')
                        //     ->join('col_acct_group', 'col_acct_title.acct_group_id', '=', 'col_acct_group.id')
                        //     ->join('col_acct_category', 'col_acct_category.id', '=', 'col_acct_group.acct_category_id')
                        //     ->whereYear('date_of_entry', '=', $request->year)
                        //     ->whereMonth('date_of_entry', '=', $request->month)
                        //     ->where('col_acct_group.acct_category_id', '=', $category->id)
                        //     ->where('col_acct_title.acct_group_id', '=', $group->id)
                        //     ->where('col_cash_division_items.col_acct_title_id', '=', $title->id)
                        //     ->where('col_cash_division.deleted_at', null)
                        //     ->groupBy('col_cash_division_items.col_acct_title_id')
                        //     ->first();

                        if($category->id == 1 || $category->id == 2) {
                            if($category->id == 1) {
                                if($title->id == 2) {
                                    $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] = $rpt_basic;
                                    // if(!empty($get_cashdiv_current))
                                    //     $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] += $get_cashdiv_current->total;
                                } elseif($title->id == 54 || $title->id == 55) {
                                    $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] = $rpt_basic_penalty;
                                    // if(!empty($get_cashdiv_current))
                                    //     $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] += $get_cashdiv_current->total;
                                } elseif($category->id == 2) {
                                    if(isset($bts[$title->id])) {
                                        $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] = $bts[$title->id];
                                    } else {
                                        $per_account[$category->id][$group->id][$title->id]['actual_coll'] = $this_monthx;
                                    }
                                    // if(!empty($get_cashdiv_current))
                                    //     $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] += $get_cashdiv_current->total;
                                } else {                                    
                                    // $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] = $this_monthx;
                                    $mnthly_income = Receipt::join('col_receipt_items', 'col_receipt.id', '=', 'col_receipt_items.col_receipt_id')
                                        ->where('af_type', 1)
                                        ->whereYear('report_date', '=', $request['year'])
                                        ->whereMonth('report_date', '=', $request['month'])
                                        ->where('col_acct_title_id', '=', $title->id)
                                        ->where('is_cancelled', 0)
                                        ->where('is_printed', 1)
                                        ->sum('share_provincial');

                                    $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] = $mnthly_income;
                                    // if(!empty($get_cashdiv_current))
                                    //     $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] += $get_cashdiv_current->total;
                                }
                            } elseif($category->id == 2) {
                                if(isset($bts_arr[$title->id])) {
                                    $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] = $bts_arr[$title->id];
                                    // if(!empty($get_cashdiv_current))
                                    //     $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] += $get_cashdiv_current->total;
                                } else {

                                }
                            }
                        } elseif($category->id == 4) {
                            if($title->id == 49) {
                                $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] = $rpt_sef;
                                // if(!empty($get_cashdiv_current))
                                //     $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] += $get_cashdiv_current->total;
                            } elseif($title->id == 54 || $title->id == 55) {
                                $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] = $rpt_sef_penalty;
                                // if(!empty($get_cashdiv_current))
                                //     $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] += $get_cashdiv_current->total;
                            } else {
                                $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] = $this_monthx;
                                // if(!empty($get_cashdiv_current))
                                //     $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] += $get_cashdiv_current->total;
                            }
                        }

                        // $cash_div_value = 0;
                        $cash_div_value = $cash_div_value_curr;
                        $mnthly_pi_value = 0;
                        $per_acct_total = 0;
                        $per_acct_total_percent = 0;
                        for($x=0; $x<$end_month; $x++){
                            $mnthly_pi = $title->mnhtly_prov_income()->where('year','=',$request->year)->where('month','=',$month_p[$x]['m'])->first();
                            if($mnthly_pi){
                                $mnthly_pi_value += $mnthly_pi->value;
                            }
                        }
                        
                        // if($request->month == 12) {
                        //     $get_cashdiv_prev = CashDivision::select('col_cash_division_items.col_acct_title_id', DB::raw('sum(value) as total'))
                        //         ->join('col_cash_division_items', 'col_cash_division.id', '=', 'col_cash_division_items.col_cash_division_id')
                        //         ->join('col_acct_title', 'col_cash_division_items.col_acct_title_id', '=', 'col_acct_title.id')
                        //         ->join('col_acct_group', 'col_acct_title.acct_group_id', '=', 'col_acct_group.id')
                        //         ->join('col_acct_category', 'col_acct_category.id', '=', 'col_acct_group.acct_category_id')
                        //         ->whereYear('date_of_entry', '=', $request->year)
                        //         ->where('col_acct_group.acct_category_id', '=', $category->id)
                        //         ->where('col_acct_title.acct_group_id', '=', $group->id)
                        //         ->where('col_cash_division_items.col_acct_title_id', '=', $title->id)
                        //         ->where('col_cash_division.deleted_at', null)
                        //         ->groupBy('col_cash_division_items.col_acct_title_id')
                        //         ->first();
                        // } else {
                        //     $get_cashdiv_prev = CashDivision::select('col_cash_division_items.col_acct_title_id', DB::raw('sum(value) as total'))
                        //         ->join('col_cash_division_items', 'col_cash_division.id', '=', 'col_cash_division_items.col_cash_division_id')
                        //         ->join('col_acct_title', 'col_cash_division_items.col_acct_title_id', '=', 'col_acct_title.id')
                        //         ->join('col_acct_group', 'col_acct_title.acct_group_id', '=', 'col_acct_group.id')
                        //         ->join('col_acct_category', 'col_acct_category.id', '=', 'col_acct_group.acct_category_id')
                        //         ->whereYear('date_of_entry', '=', $request->year)
                        //         ->whereMonth('date_of_entry', '<', $request->month)
                        //         ->where('col_acct_group.acct_category_id', '=', $category->id)
                        //         ->where('col_acct_title.acct_group_id', '=', $group->id)
                        //         ->where('col_cash_division_items.col_acct_title_id', '=', $title->id)
                        //         ->where('col_cash_division.deleted_at', null)
                        //         ->groupBy('col_cash_division_items.col_acct_title_id')
                        //         ->first();
                        // }

                        // PAST MONTH ACTUAL COLLECTION
                        if($category->id == 1 || $category->id == 2) {
                            if($category->id == 1) {
                                if($title->id == 2) {
                                    $mnthly_income_pastmnth = $prev_rpt_basic;
                                } elseif($title->id == 54 || $title->id == 55) {
                                    $mnthly_income_pastmnth = $prev_rpt_basic_penalty;
                                } elseif($category->id == 2) {
                                    if(isset($bts[$title->id])) {
                                        $mnthly_income_pastmnth = $bts_prev[$title->id];
                                    } else {
                                        $mnthly_income_pastmnth = $this_monthx;
                                    }
                                } else {                                    
                                    // $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] = $this_monthx;

                                    if($request['month'] == 12) {
                                        $pastmonths_income = Receipt::join('col_receipt_items', 'col_receipt.id', '=', 'col_receipt_items.col_receipt_id')
                                            ->where('af_type', 1)
                                            ->whereYear('report_date', '=', ($request['year']-1))
                                            ->where('col_acct_title_id', '=', $title->id)
                                            ->where('is_cancelled', '=', 0)
                                            ->where('is_printed', '=', 1)
                                            ->sum('share_provincial');
                                    } else {
                                        $pastmonths_income = Receipt::join('col_receipt_items', 'col_receipt.id', '=', 'col_receipt_items.col_receipt_id')
                                            ->where('af_type', 1)
                                            ->whereYear('report_date', '=', $request['year'])
                                            ->whereMonth('report_date', '<', $request['month'])
                                            ->where('col_acct_title_id', '=', $title->id)
                                            ->where('is_cancelled', '=', 0)
                                            ->where('is_printed', '=', 1)
                                            ->sum('share_provincial');
                                    }
                                    $mnthly_income_pastmnth = $pastmonths_income;
                                }
                            } elseif($category->id == 2) {                               
                                if(isset($bts_arr_prev[$title->id])) {
                                    $mnthly_income_pastmnth = $bts_arr_prev[$title->id];
                                } else {
                                    $mnthly_income_pastmnth = 0;
                                }
                            }
                        } elseif($category->id == 4) {
                            if($title->id == 49) {
                                $mnthly_income_pastmnth = $prev_rpt_sef;
                            } elseif($title->id == 54 || $title->id == 55) {
                                $mnthly_income_pastmnth = $prev_rpt_sef_penalty;
                            } else {
                                if($request['month'] == 12) {
                                    $mnthly_income_pastmnth = Receipt::where('af_type', 1)
                                        ->join('col_receipt_items', 'col_receipt.id', '=', 'col_receipt_items.col_receipt_id')
                                        ->whereYear('report_date', '=', ($request['year']-1))
                                        ->where('col_acct_title_id', '=', $title->id)
                                        ->where('is_cancelled', '=', 0)
                                        ->where('is_printed', '=', 1)
                                        ->sum('share_provincial');
                                } else {
                                    $mnthly_income_pastmnth = Receipt::where('af_type', 1)
                                        ->join('col_receipt_items', 'col_receipt.id', '=', 'col_receipt_items.col_receipt_id')
                                        ->whereYear('report_date', '=', $request['year'])
                                        ->whereMonth('report_date', '<', $request['month'])
                                        ->where('col_acct_title_id', '=', $title->id)
                                        ->where('is_cancelled', '=', 0)
                                        ->where('is_printed', '=', 1)
                                        ->sum('share_provincial');
                                }
                            }
                        }
                        
                        $per_acct_total = $cash_div_value + $mnthly_pi_value + $this_monthx;
                        // if(!empty($get_cashdiv_current))
                        //     $per_acct_total += $get_cashdiv_current->total;
                        if($per_account[$category->id][$group->id]['group'][$title->id]['budget_estimate'] > 0){
                            // $per_acct_total_percent = ( $per_acct_total / $per_account[$category->id][$group->id]['group'][$title->id]['budget_estimate'] ) * 100;
                            $per_acct_total_percent = ( ($per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] + $mnthly_income_pastmnth) / $per_account[$category->id][$group->id]['group'][$title->id]['budget_estimate'] ) * 100;

                            // if(!empty($get_cashdiv_current)) {
                            //     $per_acct_total_percent = ( ($cash_div_value + $mnthly_pi_value + $this_month + $get_cashdiv_current->total) / $per_account[$category->id][$group->id]['group'][$title->id]['budget_estimate'] ) * 100;
                            // } else {
                                // $per_acct_total_percent = ( ($cash_div_value + $mnthly_pi_value + $this_month) / $per_account[$category->id][$group->id]['group'][$title->id]['budget_estimate'] ) * 100;
                            // }
                        }
                        // $per_account[$category->id][$group->id]['group'][$title->id]['total'] = $per_acct_total;
                        $per_account[$category->id][$group->id]['group'][$title->id]['total'] = $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] + $mnthly_income_pastmnth;

                        $per_account[$category->id][$group->id]['group'][$title->id]['percent_coll'] = $per_acct_total_percent;
                        // $per_account[$category->id][$group->id]['group'][$title->id]['past_month'] = $cash_div_value + $mnthly_pi_value;
                        // $per_account[$category->id][$group->id]['group'][$title->id]['past_month'] = $cash_div_value + $mnthly_income_pastmnth;
                        // $per_account[$category->id][$group->id]['group'][$title->id]['past_month'] = $cash_div_value + $mnthly_income_pastmnth;
                        $per_account[$category->id][$group->id]['group'][$title->id]['past_month'] = $mnthly_income_pastmnth;
                        // if(!empty($get_cashdiv_prev))
                        //     $per_account[$category->id][$group->id]['group'][$title->id]['past_month'] += $get_cashdiv_prev->total;

                        // TOTALS
                        $total_per_category[$category->id][$group->id]['budget_estimate'] += $per_account[$category->id][$group->id]['group'][$title->id]['budget_estimate'];
                        $total_per_category[$category->id][$group->id]['actual_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'];
                        // if(!empty($get_cashdiv_current))
                        //     $total_per_category[$category->id][$group->id]['actual_coll'] += $get_cashdiv_current->total;

                        $total_per_category[$category->id][$group->id]['total'] += $per_account[$category->id][$group->id]['group'][$title->id]['total'];
                        // if(!empty($get_cashdiv_current))
                        //     $total_per_category[$category->id][$group->id]['total'] += $get_cashdiv_current->total;

                        $total_per_category[$category->id][$group->id]['past_month'] += $per_account[$category->id][$group->id]['group'][$title->id]['past_month'];
                        // if(!empty($get_cashdiv_prev))
                        //     $total_per_category[$category->id][$group->id]['past_month'] += $get_cashdiv_prev->total;

                        // $total_per_category[$category->id][$group->id]['percent_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['percent_coll'];

                        $total_per_category[$category->id]['budget_estimate'] += $per_account[$category->id][$group->id]['group'][$title->id]['budget_estimate'];
                        $total_per_category[$category->id]['actual_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'];
                        // if(!empty($get_cashdiv_current))
                        //     $total_per_category[$category->id]['actual_coll'] += $get_cashdiv_current->total;

                        $total_per_category[$category->id]['total'] += $per_account[$category->id][$group->id]['group'][$title->id]['total'];
                        // if(!empty($get_cashdiv_current))
                        //     $total_per_category[$category->id]['total'] += $get_cashdiv_current->total;

                        $total_per_category[$category->id]['past_month'] += $per_account[$category->id][$group->id]['group'][$title->id]['past_month'];
                        // if(!empty($get_cashdiv_prev))
                        //     $total_per_category[$category->id]['total'] += $get_cashdiv_prev->total;

                        // $total_per_category[$category->id]['percent_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['percent_coll'];

                        // FOR TAX REVENUE TOTALS
                        if($group->name == 'Tax Revenue' && $category->id == 1) {
                            $total_per_category[$category->id][$group->id]['tax_revenue']['budget_estimate'] += $per_account[$category->id][$group->id]['group'][$title->id]['budget_estimate'];

                            $total_per_category[$category->id][$group->id]['tax_revenue']['actual_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'];
                            // if(!empty($get_cashdiv_current))
                            //     $total_per_category[$category->id][$group->id]['tax_revenue']['actual_coll'] += $get_cashdiv_current->total;

                            $total_per_category[$category->id][$group->id]['tax_revenue']['total'] += $per_account[$category->id][$group->id]['group'][$title->id]['total'];
                            // if(!empty($get_cashdiv_current))
                            //     $total_per_category[$category->id][$group->id]['tax_revenue']['total'] += $get_cashdiv_current->total;

                            $total_per_category[$category->id][$group->id]['tax_revenue']['past_month'] += $per_account[$category->id][$group->id]['group'][$title->id]['past_month'];
                            // if(!empty($get_cashdiv_prev))
                            //     $total_per_category[$category->id][$group->id]['tax_revenue']['past_month'] += $get_cashdiv_prev->total;

                            // $total_per_category[$category->id][$group->id]['tax_revenue']['percent_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['percent_coll'];

                            $total_per_category[$category->id]['tax_revenue']['budget_estimate'] += $per_account[$category->id][$group->id]['group'][$title->id]['budget_estimate'];

                            $total_per_category[$category->id]['tax_revenue']['actual_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'];
                            // if(!empty($get_cashdiv_current))
                            //     $total_per_category[$category->id]['tax_revenue']['actual_coll'] += $get_cashdiv_current->total;

                            $total_per_category[$category->id]['tax_revenue']['total'] += $per_account[$category->id][$group->id]['group'][$title->id]['total'];
                            // if(!empty($get_cashdiv_current))
                            //     $total_per_category[$category->id]['tax_revenue']['total'] += $get_cashdiv_current->total;

                            $total_per_category[$category->id]['tax_revenue']['past_month'] += $per_account[$category->id][$group->id]['group'][$title->id]['past_month'];
                            // if(!empty($get_cashdiv_prev))
                            //     $total_per_category[$category->id]['tax_revenue']['past_month'] += $get_cashdiv_prev->total;

                            // $total_per_category[$category->id]['tax_revenue']['percent_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['percent_coll'];
                        }
                    }

                    // for sub titles ..
                    foreach ($title->subs as $subs) {
                        if ($subs->show_in_monthly == 1 && array_search($title->id, [34,36,37,39]) == false) {
                            $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['budget_estimate'] = $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['actual_collection'] = $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['total'] = $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['percent_coll'] = $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['past_month'] = 0;

                            $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['sub_title'] = $subs->name;

                            if(isset($subs->budget()->where('year','=',$request->year)->first()->value)) {
                                $sub_budget_est = $subs->budget()->where('year','=',$request->year)->first()->value;
                            } else {
                                $sub_budget_est = 0;
                            }
                            $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['budget_estimate'] = $sub_budget_est;

                            // $get_cashdiv_current_sub = CashDivision::select('col_cash_division_items.col_acct_title_id', DB::raw('sum(value) as total'))
                            //     ->join('col_cash_division_items', 'col_cash_division.id', '=', 'col_cash_division_items.col_cash_division_id')
                            //     ->join('col_acct_title', 'col_cash_division_items.col_acct_title_id', '=', 'col_acct_title.id')
                            //     ->join('col_acct_group', 'col_acct_title.acct_group_id', '=', 'col_acct_group.id')
                            //     ->join('col_acct_category', 'col_acct_category.id', '=', 'col_acct_group.acct_category_id')
                            //     ->whereYear('date_of_entry', '=', $request->year)
                            //     ->whereMonth('date_of_entry', '=', $request->month)
                            //     ->where('col_acct_group.acct_category_id', '=', $category->id)
                            //     ->where('col_acct_title.acct_group_id', '=', $group->id)
                            //     ->where('col_cash_division_items.col_acct_title_id', '=', $title->id)
                            //     ->where('col_cash_division_items.col_acct_subtitle_id', '=', $subs->id)
                            //     ->where('col_cash_division.deleted_at', null)
                            //     ->groupBy('col_cash_division_items.col_acct_subtitle_id')
                            //     ->first();

                            // if($request->month > 1) {
                            //     $get_cashdiv_prev_sub = CashDivision::select('col_cash_division_items.col_acct_title_id', DB::raw('sum(value) as total'))
                            //         ->join('col_cash_division_items', 'col_cash_division.id', '=', 'col_cash_division_items.col_cash_division_id')
                            //         ->join('col_acct_title', 'col_cash_division_items.col_acct_title_id', '=', 'col_acct_title.id')
                            //         ->join('col_acct_group', 'col_acct_title.acct_group_id', '=', 'col_acct_group.id')
                            //         ->join('col_acct_category', 'col_acct_category.id', '=', 'col_acct_group.acct_category_id')
                            //         ->whereYear('date_of_entry', '=', $request->year)
                            //         ->whereMonth('date_of_entry', '=', $request->month)
                            //         ->where('col_acct_group.acct_category_id', '=', $category->id)
                            //         ->where('col_acct_title.acct_group_id', '=', $group->id)
                            //         ->where('col_cash_division_items.col_acct_title_id', '=', $title->id)
                            //         ->where('col_cash_division_items.col_acct_subtitle_id', '=', $subs->id)
                            //         ->where('col_cash_division.deleted_at', null)
                            //         ->groupBy('col_cash_division_items.col_acct_subtitle_id')
                            //         ->first();
                            // }

                            // $subscash_div_value = 0;
                            // $subscash_div_value = $get_cashdiv_current_sub;
                            $subsmnthly_pi_value = 0;
                            $this_submonth = 0;
                            $this_submonthx = 0;
                            $sub_actual_coll = 0;
                            $sub_total = 0;
                            $percent_collection = 0;

                            for($x=0; $x<$end_month; $x++){
                                $subsmnthly_pi = $subs->mnhtly_prov_income()->where('year','=',$request->year)->where('month','=',$month_p[$x]['m'])->first();
                                if($subsmnthly_pi){
                                    $subsmnthly_pi_value += $subsmnthly_pi->value;
                                }
                            }
                            if(isset($mnthly_pixx)){
                                if($mnthly_pixx->total_value != '0.00' || $mnthly_pixx->total_value != '' ){
                                    $this_submonth =  $mnthly_pixx->total_value;
                                }else{
                                    $this_submonth =  $mnthly_pixx->value;
                                }
                                $this_submonthx =  $mnthly_pixx->value;
                            }
                            $sub_actual_coll = $this_submonthx;
                            // $sub_total = $subscash_div_value + $subsmnthly_pi_value + $this_submonth;
                            $sub_total = $subsmnthly_pi_value + $this_submonth;
                            if($per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['budget_estimate'] > 0) {
                                $percent_collection = ( ($subsmnthly_pi_value + $this_submonth) / $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['budget_estimate'] ) * 100;

                                // if(!empty($get_cashdiv_current_sub)) {
                                //     $percent_collection = ( ($subscash_div_value + $subsmnthly_pi_value + $this_submonth + $get_cashdiv_current_sub->total) / $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['budget_estimate'] ) * 100;
                                // } else {
                                //     $percent_collection = ( ($subscash_div_value + $subsmnthly_pi_value + $this_submonth) / $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['budget_estimate'] ) * 100;
                                // }
                            }

                            // $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['actual_collection'] = $sub_actual_coll;
                            // // if(!empty($get_cashdiv_current_sub)) 
                            // //     $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['actual_collection'] += $get_cashdiv_current_sub->total;

                            // $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['total'] = $sub_total + $this_submonthx;
                            // // if(!empty($get_cashdiv_current_sub)) 
                            // //     $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['total'] += $get_cashdiv_current_sub->total;

                            // $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['percent_coll'] = $percent_collection;
                            // $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['past_month'] = $this_submonthx;
                            // // if(!empty($get_cashdiv_prev_sub)) 
                            // //     $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['past_month'] += $get_cashdiv_prev_sub->total;

                            // NEW 
                            $mnthly_income = Receipt::join('col_receipt_items', 'col_receipt.id', '=', 'col_receipt_items.col_receipt_id')
                                ->where('af_type', 1)
                                ->whereYear('report_date', '=', $request['year'])
                                ->whereMonth('report_date', '=', $request['month'])
                                ->where('col_acct_subtitle_id', '=', $subs->id)
                                ->where('is_cancelled', 0)
                                ->where('is_printed', 1)
                                ->sum('share_provincial');
                            $mnthly_income_pastmnth = Receipt::where('af_type', 1)
                                ->join('col_receipt_items', 'col_receipt.id', '=', 'col_receipt_items.col_receipt_id')
                                ->whereYear('report_date', '=', $request['year'])
                                ->whereMonth('report_date', '<', $request['month'])
                                ->where('col_acct_subtitle_id', '=', $subs->id)
                                ->where('is_cancelled', '=', 0)
                                ->where('is_printed', '=', 1)
                                ->sum('share_provincial');
                            $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['actual_collection'] = $mnthly_income;
                            $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['total'] = $mnthly_income + $mnthly_income_pastmnth;
                            $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['past_month'] = $mnthly_income_pastmnth;
                            $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['percent_coll'] = $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['budget_estimate'] > 0 ? ($per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['actual_collection'] / $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['budget_estimate']) * 100 : 0;

                            if($group->name == 'Tax Revenue' && $category->id == 1) {
                                $total_per_category[$category->id][$group->id]['tax_revenue']['budget_estimate'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['budget_estimate'];
                                $total_per_category[$category->id][$group->id]['tax_revenue']['actual_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['actual_collection'];
                                // if(!empty($get_cashdiv_current_sub)) 
                                //     $total_per_category[$category->id][$group->id]['tax_revenue']['actual_coll'] += $get_cashdiv_current_sub->total;

                                $total_per_category[$category->id][$group->id]['tax_revenue']['total'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['total'];
                                // if(!empty($get_cashdiv_current_sub)) 
                                //     $total_per_category[$category->id][$group->id]['tax_revenue']['total'] += $get_cashdiv_current_sub->total;

                                $total_per_category[$category->id][$group->id]['tax_revenue']['past_month'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['past_month'];
                                // if(!empty($get_cashdiv_prev_sub)) 
                                //     $total_per_category[$category->id][$group->id]['tax_revenue']['past_month'] += $get_cashdiv_prev_sub->total;

                                // $total_per_category[$category->id][$group->id]['tax_revenue']['percent_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['percent_coll'];

                                $total_per_category[$category->id]['tax_revenue']['budget_estimate'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['budget_estimate'];
                                $total_per_category[$category->id]['tax_revenue']['actual_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['actual_collection'];
                                // if(!empty($get_cashdiv_current_sub)) 
                                //     $total_per_category[$category->id]['tax_revenue']['actual_coll'] += $get_cashdiv_current_sub->total;

                                $total_per_category[$category->id]['tax_revenue']['total'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['total'];
                                // if(!empty($get_cashdiv_current_sub)) 
                                //     $total_per_category[$category->id]['tax_revenue']['total'] += $get_cashdiv_current_sub->total;

                                $total_per_category[$category->id]['tax_revenue']['past_month'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['past_month'];
                                // if(!empty($get_cashdiv_prev_sub)) 
                                //     $total_per_category[$category->id]['tax_revenue']['past_month'] += $get_cashdiv_prev_sub->total;

                                // $total_per_category[$category->id]['tax_revenue']['percent_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['percent_coll'];
                            }
                            $total_per_category[$category->id][$group->id]['budget_estimate'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['budget_estimate'];
                            $total_per_category[$category->id][$group->id]['actual_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['actual_collection'];
                            // if(!empty($get_cashdiv_current_sub)) 
                            //     $total_per_category[$category->id][$group->id]['actual_coll'] += $get_cashdiv_current_sub->total;

                            $total_per_category[$category->id][$group->id]['total'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['total'];
                            // if(!empty($get_cashdiv_current_sub)) 
                            //     $total_per_category[$category->id][$group->id]['total'] += $get_cashdiv_current_sub->total;

                            $total_per_category[$category->id][$group->id]['past_month'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['past_month'];
                            // if(!empty($get_cashdiv_prev_sub)) 
                            //     $total_per_category[$category->id][$group->id]['total'] += $get_cashdiv_prev_sub->total;
                            // $total_per_category[$category->id][$group->id]['percent_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['percent_coll'];

                            $total_per_category[$category->id]['budget_estimate'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['budget_estimate'];
                            $total_per_category[$category->id]['actual_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['actual_collection'];
                            // if(!empty($get_cashdiv_current_sub)) 
                            //     $total_per_category[$category->id]['actual_coll'] += $get_cashdiv_current_sub->total;

                            $total_per_category[$category->id]['total'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['total'];
                            // if(!empty($get_cashdiv_current_sub)) 
                            //     $total_per_category[$category->id]['total'] += $get_cashdiv_current_sub->total;

                            $total_per_category[$category->id]['past_month'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['past_month'];
                            // if(!empty($get_cashdiv_prev_sub)) 
                            //     $total_per_category[$category->id]['total'] += $get_cashdiv_prev_sub->total;
                            // $total_per_category[$category->id]['percent_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['percent_coll'];
                        }
                    }
                    // end subtitles
                }
                // end titles

                $percentx = 0;
                if($group->name == 'Tax Revenue' && $category->id == 1) {
                    if($total_per_category[$category->id][$group->id]['budget_estimate'] > 0) {
                        $percentx = ($total_per_category[$category->id][$group->id]['tax_revenue']['actual_coll'] / $total_per_category[$category->id][$group->id]['tax_revenue']['budget_estimate']) * 100;
                    }
                    $total_per_category[$category->id][$group->id]['tax_revenue']['percent_coll'] = $percentx;
                    // $total_per_category[$category->id]['tax_revenue']['percent_coll'] = $percentx;
                } else {
                    if($total_per_category[$category->id][$group->id]['budget_estimate'] > 0) {
                        $percentx = ($total_per_category[$category->id][$group->id]['actual_coll'] / $total_per_category[$category->id][$group->id]['budget_estimate']) * 100;
                    }
                    $total_per_category[$category->id][$group->id]['percent_coll'] = $percentx;
                    // $total_per_category[$category->id]['percent_coll'] = $percentx;
                }
            }
        }

        // combine BTS and Gen. Fund w/ same groups
        foreach($per_account as $category => $data1) {
            if($category == 1) {
                foreach($data1 as $group => $data2) {
                    foreach($per_account[2] as $group2 => $cat2_data) {
                        // if($data2['group_name'] == $cat2_data['group_name']) {
                        if(strcasecmp($data2['group_name'], $cat2_data['group_name']) == 0) {
                            foreach($cat2_data['group'] as $title => $deets) {
                                $per_account[1][$group]['group'][$title]['past_month'] = $deets['past_month'];
                                $per_account[1][$group]['group'][$title]['percent_coll'] = $deets['percent_coll'];
                                $per_account[1][$group]['group'][$title]['total'] = $deets['total'];
                                $per_account[1][$group]['group'][$title]['actual_coll'] = $deets['actual_coll'];
                                $per_account[1][$group]['group'][$title]['budget_estimate'] = $deets['budget_estimate'];
                                $per_account[1][$group]['group'][$title]['account_title'] = $deets['account_title'];
                                $per_account[1][$group]['group'][$title]['account_code'] = $deets['account_code'];
                                $per_account[1][$group]['group'][$title]['is_bts'] = true;
                                // if(isset($deets['subs'])) {
                                //     $per_account[1][$group]['group'][$title]['subs'] = $deets['subs'];
                                // }
                                // $duplicate_title = false;
                                // $get_acct_title = AccountTitle::find($title);
                                // foreach($data2['group'] as $title2 => $search) {
                                //     if(strcasecmp($get_acct_title->name, $search['account_title'])) {
                                //         $duplicate_title = true;
                                //         break;
                                //     }
                                // }
                            }
                            unset($per_account[2][$group2]);
                        } 

                        if (array_search($cat2_data['group_name'], array_column($per_account[1], 'group_name')) == false) {
                            $per_account[1][$group2]['group_name'] = $cat2_data['group_name'];
                            foreach($cat2_data['group'] as $title => $deets) {
                                $per_account[1][$group2]['group'][$title]['past_month'] = $deets['past_month'];
                                $per_account[1][$group2]['group'][$title]['percent_coll'] = $deets['percent_coll'];
                                $per_account[1][$group2]['group'][$title]['total'] = $deets['total'];
                                $per_account[1][$group2]['group'][$title]['actual_coll'] = $deets['actual_coll'];
                                $per_account[1][$group2]['group'][$title]['budget_estimate'] = $deets['budget_estimate'];
                                $per_account[1][$group2]['group'][$title]['account_title'] = $deets['account_title'];
                                $per_account[1][$group2]['group'][$title]['account_code'] = $deets['account_code'];
                                $per_account[1][$group2]['group'][$title]['is_bts'] = true;
                            }
                            unset($per_account[2][$group2]);
                        }  
                    }
                }
            }
        }

        // $col_per_account = collect($per_account);
        // dd($col_per_account);
        // per_account['acct_category']['group']['title']
        // end compute

        // sort
        $account_codes = [];
        foreach($per_account as $category => $data1) {
            foreach($data1 as $group => $data2) {
                $sort_accts = collect($data2['group'])->sortBy('account_code');
                $per_account[$category][$group]['group'] = [];
                $per_account[$category][$group]['group'] = $sort_accts;
            }
        }
        $col_per_account = collect($per_account)->sortBy('account_code');
        $this->base['per_account'] = $per_account;
        $this->base['per_account'] = $col_per_account;
        $this->base['total_per_category'] = $total_per_category;

        $data = $this->base;
        if(isset($request->button_excel)){
            Excel::create('PROVINCIAL INCOME', function($excel) use($data) {
                $excel->sheet('Provincial Income', function($sheet) use($data) {
                    $sheet->loadView('collection::report.excel_provincial_income', $data);
                });
            })->export('xls');
        } else {
            $pdf = new PDF;
            $pdf = PDF::loadView('collection::pdf/provincial_income_new', $this->base)
                ->setPaper('legal');
            return @$pdf->stream();
        }
    }

    public function provincial_income_new2(Request $request)
    {
        $this->base['year'] = $request->year;
        $this->base['month'] = $request->month;
        $this->base['year_x'] = Carbon::createFromDate($request->year, 1, 1, 'Asia/Manila');
        $this->base['month_x'] = Carbon::createFromDate($request->year, 1, 1, 'Asia/Manila');
        $this->base['month_end'] = Carbon::createFromDate($request->year, ($request->month), 1, 'Asia/Manila');

        $current = Carbon::parse($this->base['year_x'])->format('Y');
        $prior_start = Carbon::parse($this->base['year_x'])->subYears(2)->format('Y');
        $preceeding = Carbon::parse($this->base['year_x'])->subYear()->format('Y');
        $advance_yr = Carbon::parse($this->base['year_x'])->addYear()->format('Y');

        $this->base['prior_start'] = $prior_start;
        $this->base['preceeding'] = $preceeding;
        $this->base['advance_yr'] = $advance_yr;
        $this->base['current'] = $current;

        $rpt_basic = $rpt_basic_penalty = $rpt_basic_discount = $rpt_basic_prev = $rpt_basic_prev_penalty = $rpt_basic_adv = $rpt_basic_adv_discount = $rpt_basic_1991 = $rpt_basic_1991_penalty = $rpt_basic_1992 = $rpt_basic_1992_penalty = $rpt_basic_total = [];
        $rpt_sef = $rpt_sef_penalty = $rpt_sef_discount = $rpt_sef_prev = $rpt_sef_prev_penalty = $rpt_sef_adv = $rpt_sef_adv_discount = $rpt_sef_1991 = $rpt_sef_1991_penalty = $rpt_sef_1992 = $rpt_sef_1992_penalty =  $rpt_sef_total = [];
        $rpt_basic_cd = $rpt_basic_penalty_cd = $rpt_basic_discount_cd = $rpt_basic_prev_cd = $rpt_basic_prev_penalty_cd = $rpt_basic_adv_cd = $rpt_basic_adv_discount_cd = $rpt_basic_1991_cd = $rpt_basic_1991_penalty_cd = $rpt_basic_1992_cd = $rpt_basic_1992_penalty_cd = $rpt_basic_total_cd = [];
        
        $rpt_sef_cd = $rpt_sef_penalty_cd = $rpt_sef_discount_cd = $rpt_sef_prev_cd = $rpt_sef_prev_penalty_cd = $rpt_sef_adv_cd = $rpt_sef_adv_discount_cd = $rpt_sef_1991_cd = $rpt_sef_1991_penalty_cd = $rpt_sef_1992_cd = $rpt_sef_1992_penalty_cd = $rpt_sef_total_cd = [];


        for($i = 1; $i <= 13; $i++) { // for all municipalities
            $pprv_crnt_ammount = 0;
            $total_basic_current = 0;
            $total_basic_discount = 0;
            $total_basic_previous = 0;
            $total_basic_penalty_current = 0;
            $total_basic_penalty_previous = 0;
            $pprv_crnt_discount = 0;
            $pprv_prvious_ammount = 0;
            $pprv_pnalties_crnt = 0;
            $pprv_pnalties_prvious = 0;
            $xtotal_basic_current = 0;
            $xtotal_basic_discount = 0;
            $xtotal_basic_previous = 0;
            $xtotal_basic_penalty_current = 0;
            $xtotal_basic_penalty_previous = 0;

            $prev_pprv_crnt_ammount = 0;
            $prev_total_basic_current = 0;
            $prev_total_basic_discount = 0;
            $prev_total_basic_previous = 0;
            $prev_total_basic_penalty_current = 0;
            $prev_total_basic_penalty_previous = 0;
            $prev_pprv_crnt_discount = 0;
            $prev_pprv_prvious_ammount = 0;
            $prev_pprv_pnalties_crnt = 0;
            $prev_pprv_pnalties_prvious = 0;
            $prev_xtotal_basic_current = 0;
            $prev_xtotal_basic_discount = 0;
            $prev_xtotal_basic_previous = 0;
            $prev_xtotal_basic_penalty_current = 0;
            $prev_xtotal_basic_penalty_previous = 0;

            $total_basic_previous_1992 = 0;
            $brgyshare_basic_previous_1992 = 0;
            $munshare_basic_previous_1992 = 0;
            $prv_prvious_ammount_1992 = 0;
            $pprv_prvious_ammount_1992 = 0;
            $total_basic_penalty_previous_1992 = 0;
            $brgyshare_basic_penalty_previous_1992 = 0;
            $munshare_basic_penalty_previous_1992 = 0;
            $prv_pnalties_prvious_1992 = 0;
            $pprv_pnalties_prvious_1992 = 0;

            $total_basic_previous_1991 = 0;
            $brgyshare_basic_previous_1991 = 0;
            $munshare_basic_previous_1991 = 0;
            $prv_prvious_ammount_1991 = 0;
            $pprv_prvious_ammount_1991 = 0;
            $total_basic_penalty_previous_1991 = 0;
            $brgyshare_basic_penalty_previous_1991 = 0;
            $munshare_basic_penalty_previous_1991 = 0;
            $prv_pnalties_prvious_1991 = 0;
            $pprv_pnalties_prvious_1991 = 0;

            $total_basic_advance = 0;
            $munshare_basic_advance = 0;
            $brgyshare_basic_advance = 0;
            $prv_adv_ammount = 0;
            $pprv_adv_ammount = 0; 
            $total_basic_discount_adv = 0;
            $brgyshare_basic_discount_adv = 0;
            $munshare_basic_discount_adv = 0;
            $prv_adv_discount = 0;
            $pprv_adv_discount = 0;

            $bbrgyshare_basic_advance = 0;
            $total_basic_advance = 0;
            $xtotal_basic_advance = 0;
            $xtotal_basic_discount_adv = 0;

            $xtotal_basic_previous_1992 = 0;
            $xtotal_basic_penalty_previous_1992 = 0;
            $xtotal_basic_previous_1991 = 0;
            $xtotal_basic_penalty_previous_1991 = 0;

            // cashdiv
            $cd_current_basic = 0;
            $cd_current_basic_penalty = 0;
            $cd_current_basic_discount = 0;
            $cd_prev_basic = 0;
            $cd_prev_basic_penalty = 0;
            $cd_adv_basic = 0;
            $cd_adv_basic_discount = 0;
            $cd_1991_basic = 0;
            $cd_1991_basic_penalty = 0;
            $cd_1992_basic = 0;
            $cd_1992_basic_penalty = 0;
            $cd_current_sef = 0;
            $cd_current_sef_penalty = 0;
            $cd_current_sef_discount = 0;
            $cd_prev_sef = 0;
            $cd_prev_sef_penalty = 0;
            $cd_adv_sef = 0;
            $cd_adv_sef_discount = 0;
            $cd_1991_sef = 0;
            $cd_1991_sef_penalty = 0;
            $cd_1992_sef = 0;
            $cd_1992_sef_penalty = 0;

            $receipts = Receipt::whereMonth('report_date','=', $request->month)
                ->whereYear('report_date','=', $request->year)
                ->where('is_printed', '=', 1)
                ->where('is_cancelled', '=', 0)
                ->where('af_type', '=', 2)
                ->where('col_municipality_id', '=', $i)
                ->orderBy('serial_no', 'ASC')
                ->get();
            foreach ($receipts as $receipt) {
                foreach ($receipt->F56Detailmny as $f56_detail) {
                    // prov basic current amount
                    if($f56_detail->period_covered == $current) {
                        $total_basic_current += $f56_detail->basic_current;
                        $munshare_basic_current = round($total_basic_current * .4,2);
                        $brgyshare_basic_current = round($total_basic_current * .25,2);
                        $prv_crnt_ammount = ($total_basic_current - ($munshare_basic_current + $brgyshare_basic_current));
                        $pprv_crnt_ammount = round($prv_crnt_ammount,2,PHP_ROUND_HALF_UP);   

                        // prov basic current discount
                        $total_basic_discount += $f56_detail->basic_discount;
                        $brgyshare_basic_discount = round($total_basic_discount * .25,2);
                        $munshare_basic_discount = round($total_basic_discount * .4,2);
                        $prv_crnt_discount = ($total_basic_discount - ($munshare_basic_discount + $brgyshare_basic_discount));
                        $pprv_crnt_discount = round($prv_crnt_discount,2,PHP_ROUND_HALF_DOWN);

                        // prov basic penalties current
                        $total_basic_penalty_current += $f56_detail->basic_penalty_current;
                        $brgyshare_basic_penalty_current = round($total_basic_penalty_current * .25,2);
                        $munshare_basic_penalty_current = round($total_basic_penalty_current * .4,2);
                        $prv_pnalties_crnt = $total_basic_penalty_current - ($munshare_basic_penalty_current + $brgyshare_basic_penalty_current);
                        $pprv_pnalties_crnt =  round($prv_pnalties_crnt,2,PHP_ROUND_HALF_UP);
                    }

                    if($f56_detail->period_covered == $preceeding) {
                        // prov basic previous amount
                        $total_basic_previous += $f56_detail->basic_previous;
                        $brgyshare_basic_previous = round($total_basic_previous * .25,2);
                        $munshare_basic_previous = round($total_basic_previous * .4,2);
                        $prv_prvious_ammount = ($total_basic_previous - ($munshare_basic_previous + $brgyshare_basic_previous));
                        $pprv_prvious_ammount = round($prv_prvious_ammount,2,PHP_ROUND_HALF_UP);
                        
                        // prov basic penalties previous
                        $total_basic_penalty_previous += $f56_detail->basic_penalty_previous;
                        $brgyshare_basic_penalty_previous = round($total_basic_penalty_previous * .25,2);
                        $munshare_basic_penalty_previous = round($total_basic_penalty_previous * .4,2);
                        $prv_pnalties_prvious = $total_basic_penalty_previous - ($munshare_basic_penalty_previous + $brgyshare_basic_penalty_previous);
                        $pprv_pnalties_prvious = round($prv_pnalties_prvious,2,PHP_ROUND_HALF_UP);
                    }
                    
                    if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered >= 1992) {
                        // prov basic previous amount
                        $total_basic_previous_1992 += $f56_detail->basic_previous;
                        $brgyshare_basic_previous_1992 = round($total_basic_previous_1992 * .25,2);
                        $munshare_basic_previous_1992 = round($total_basic_previous_1992 * .4,2);
                        $prv_prvious_ammount_1992 = ($total_basic_previous_1992 - ($munshare_basic_previous_1992 + $brgyshare_basic_previous_1992));
                        $pprv_prvious_ammount_1992 = round($prv_prvious_ammount_1992,2,PHP_ROUND_HALF_UP);
                        
                        // prov basic penalties previous
                        $total_basic_penalty_previous_1992 += $f56_detail->basic_penalty_previous;
                        $brgyshare_basic_penalty_previous_1992 = round($total_basic_penalty_previous_1992 * .25,2);
                        $munshare_basic_penalty_previous_1992 = round($total_basic_penalty_previous_1992 * .4,2);
                        $prv_pnalties_prvious_1992 = $total_basic_penalty_previous_1992 - ($munshare_basic_penalty_previous_1992 + $brgyshare_basic_penalty_previous_1992);
                        $pprv_pnalties_prvious_1992 = round($prv_pnalties_prvious_1992,2,PHP_ROUND_HALF_UP);
                    }
                    if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered <= 1991) {
                        // prov basic previous amount
                        $total_basic_previous_1991 += $f56_detail->basic_previous;
                        $brgyshare_basic_previous_1991 = round($total_basic_previous_1991 * .25,2);
                        $munshare_basic_previous_1991 = round($total_basic_previous_1991 * .4,2);
                        $prv_prvious_ammount_1991 = ($total_basic_previous_1991 - ($munshare_basic_previous_1991 + $brgyshare_basic_previous_1991));
                        $pprv_prvious_ammount_1991 = round($prv_prvious_ammount_1991,2,PHP_ROUND_HALF_UP);
                        
                        // prov basic penalties previous
                        $total_basic_penalty_previous_1991 += $f56_detail->basic_penalty_previous;
                        $brgyshare_basic_penalty_previous_1991 = round($total_basic_penalty_previous_1991 * .25,2);
                        $munshare_basic_penalty_previous_1991 = round($total_basic_penalty_previous_1991 * .4,2);
                        $prv_pnalties_prvious_1991 = $total_basic_penalty_previous_1991 - ($munshare_basic_penalty_previous_1991 + $brgyshare_basic_penalty_previous_1991);
                        $pprv_pnalties_prvious_1991 = round($prv_pnalties_prvious_1991,2,PHP_ROUND_HALF_UP);
                    }

                    if($f56_detail->period_covered >= $advance_yr) {
                        $total_basic_advance += $f56_detail->basic_current;
                        $munshare_basic_advance = round($total_basic_advance * .4,2);
                        $brgyshare_basic_advance = round($total_basic_advance * .25,2);
                        $prv_adv_ammount = ($total_basic_advance - ($munshare_basic_advance + $brgyshare_basic_advance));
                        $pprv_adv_ammount = round($prv_adv_ammount,2,PHP_ROUND_HALF_UP);   

                        // prov basic current discount
                        $total_basic_discount_adv += $f56_detail->basic_discount;
                        $brgyshare_basic_discount_adv = round($total_basic_discount_adv * .25,2);
                        $munshare_basic_discount_adv = round($total_basic_discount_adv * .4,2);
                        $prv_adv_discount = ($total_basic_discount_adv - ($munshare_basic_discount_adv + $brgyshare_basic_discount_adv));
                        $pprv_adv_discount = round($prv_adv_discount,2,PHP_ROUND_HALF_DOWN);
                    }

                    // prov sef current amount
                    if($f56_detail->period_covered == $current) {
                        $bbrgyshare_basic_current = round($brgyshare_basic_current,2,PHP_ROUND_HALF_DOWN);
                        $total_basic_current = $pprv_crnt_ammount + $munshare_basic_current + $bbrgyshare_basic_current;
                        $xtotal_basic_current = round($total_basic_current * .5,2,PHP_ROUND_HALF_UP);

                        // prov sef current discount
                        $xtotal_basic_discount = round($total_basic_discount*.5,2,PHP_ROUND_HALF_DOWN);

                        // prov sef penalties current
                        $xtotal_basic_penalty_current = $total_basic_penalty_current * .5;
                    }
                    
                    if($f56_detail->period_covered == $preceeding) {
                        // prov sef previous amount
                        $xtotal_basic_previous = $total_basic_previous * .5;
                        
                        // prov sef penalties previous
                        $xtotal_basic_penalty_previous = $total_basic_penalty_previous * .5;
                    }
                    
                    if($f56_detail->period_covered >= $advance_yr) {
                        $bbrgyshare_basic_advance = round($brgyshare_basic_advance,2,PHP_ROUND_HALF_DOWN);
                        $total_basic_advance = $pprv_adv_ammount + $munshare_basic_advance + $bbrgyshare_basic_advance;
                        $xtotal_basic_advance = round($total_basic_advance * .5,2,PHP_ROUND_HALF_UP);

                        // prov sef current discount
                        $xtotal_basic_discount_adv = round($total_basic_discount_adv*.5,2,PHP_ROUND_HALF_DOWN);
                    }

                    if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered >= 1992) {
                        // prov sef previous amount
                        $xtotal_basic_previous_1992 = $total_basic_previous_1992 * .5;
                        
                        // prov sef penalties previous
                        $xtotal_basic_penalty_previous_1992 = $total_basic_penalty_previous_1992 * .5;
                    }
                    if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered <= 1991) {
                        // prov sef previous amount
                        $xtotal_basic_previous_1991 = $total_basic_previous_1991 * .5;
                        
                        // prov sef penalties previous
                        $xtotal_basic_penalty_previous_1991 = $total_basic_penalty_previous_1991 * .5;
                    }
                }
            }
            // basic current
            if(isset($rpt_basic[$i]))
                $rpt_basic[$i] += $pprv_crnt_ammount;
            else
                $rpt_basic[$i] = $pprv_crnt_ammount;
            // basic current penalty
            if(isset($rpt_basic_penalty[$i]))
                $rpt_basic_penalty[$i] += $pprv_pnalties_crnt;
            else
                $rpt_basic_penalty[$i] = $pprv_pnalties_crnt;
            // basic current discount
            if(isset($rpt_basic_discount[$i]))
                $rpt_basic_discount[$i] += $pprv_crnt_discount;
            else
                $rpt_basic_discount[$i] = $pprv_crnt_discount;
            // basic preceeding
            if(isset($rpt_basic_prev[$i]))
                $rpt_basic_prev[$i] += $pprv_prvious_ammount;
            else
                $rpt_basic_prev[$i] = $pprv_prvious_ammount;
            // basic preceeding penalty
            if(isset($rpt_basic_prev_penalty[$i]))
                $rpt_basic_prev_penalty[$i] += $pprv_pnalties_prvious;
            else
                $rpt_basic_prev_penalty[$i] = $pprv_pnalties_prvious;
            // basic advance
            if(isset($rpt_basic_adv[$i]))
                $rpt_basic_adv[$i] += $pprv_adv_ammount;
            else
                $rpt_basic_adv[$i] = $pprv_adv_ammount;
            // basic advance discount
            if(isset($rpt_basic_adv_discount[$i]))
                $rpt_basic_adv_discount[$i] += $pprv_adv_discount;
            else
                $rpt_basic_adv_discount[$i] = $pprv_adv_discount;
            // basic basic 1991
            if(isset($rpt_basic_1991[$i]))
                $rpt_basic_1991[$i] += $pprv_prvious_ammount_1991;
            else
                $rpt_basic_1991[$i] = $pprv_prvious_ammount_1991;
            // basic basic 1991 penalty
            if(isset($rpt_basic_1991_penalty[$i]))
                $rpt_basic_1991_penalty[$i] += $pprv_pnalties_prvious_1991;
            else
                $rpt_basic_1991_penalty[$i] = $pprv_pnalties_prvious_1991;
            // basic basic 1992
            if(isset($rpt_basic_1992[$i]))
                $rpt_basic_1992[$i] += $pprv_prvious_ammount_1992;
            else
                $rpt_basic_1992[$i] = $pprv_prvious_ammount_1992;
            // basic basic 1992 penalty
            if(isset($rpt_basic_1992_penalty[$i]))
                $rpt_basic_1992_penalty[$i] += $pprv_pnalties_prvious_1992;
            else
                $rpt_basic_1992_penalty[$i] = $pprv_pnalties_prvious_1992;
            $rpt_basic_total[$i] = ($rpt_basic[$i] + $rpt_basic_prev[$i] + $rpt_basic_adv[$i] + $rpt_basic_1991[$i] + $rpt_basic_1992[$i]) + ($rpt_basic_penalty[$i] + $rpt_basic_prev_penalty[$i] + $rpt_basic_1991_penalty[$i] + $rpt_basic_1992_penalty[$i]) - ($rpt_basic_discount[$i] + $rpt_basic_adv_discount[$i]);

            // sef current
            if(isset($rpt_sef[$i]))
                $rpt_sef[$i] += $xtotal_basic_current;
            else
                $rpt_sef[$i] = $xtotal_basic_current;
            // sef current penalty
            if(isset($rpt_sef_penalty[$i]))
                $rpt_sef_penalty[$i] += $xtotal_basic_penalty_current;
            else
                $rpt_sef_penalty[$i] = $xtotal_basic_penalty_current;
            // sef current advance
            if(isset($rpt_sef_discount[$i]))
                $rpt_sef_discount[$i] += $xtotal_basic_discount;
            else
                $rpt_sef_discount[$i] = $xtotal_basic_discount;
            // sef preceeding
            if(isset($rpt_sef_prev[$i]))
                $rpt_sef_prev[$i] += $xtotal_basic_previous;
            else
                $rpt_sef_prev[$i] = $xtotal_basic_previous;
            // sef preceeding penalty
            if(isset($rpt_sef_prev_penalty[$i]))
                $rpt_sef_prev_penalty[$i] += $xtotal_basic_penalty_previous;
            else
                $rpt_sef_prev_penalty[$i] = $xtotal_basic_penalty_previous;
            // sef advance
            if(isset($rpt_sef_adv[$i]))
                $rpt_sef_adv[$i] += $xtotal_basic_advance;
            else
                $rpt_sef_adv[$i] = $xtotal_basic_advance;
            // sef advance discount
            if(isset($rpt_sef_adv_discount[$i]))
                $rpt_sef_adv_discount[$i] += $xtotal_basic_discount_adv;
            else
                $rpt_sef_adv_discount[$i] = $xtotal_basic_discount_adv;
            // sef 1991
            if(isset($rpt_sef_1991[$i]))
                $rpt_sef_1991[$i] += $xtotal_basic_previous_1991;
            else
                $rpt_sef_1991[$i] = $xtotal_basic_previous_1991;
            // sef 1991 penalty
            if(isset($rpt_sef_1991_penalty[$i]))
                $rpt_sef_1991_penalty[$i] += $xtotal_basic_penalty_previous_1991;
            else
                $rpt_sef_1991_penalty[$i] = $xtotal_basic_penalty_previous_1991;
            // sef 1992
            if(isset($rpt_sef_1992[$i]))
                $rpt_sef_1992[$i] += $xtotal_basic_previous_1992;
            else
                $rpt_sef_1992[$i] = $xtotal_basic_previous_1992;
            // sef 1992 penalty
            if(isset($rpt_sef_1992_penalty[$i]))
                $rpt_sef_1992_penalty[$i] += $xtotal_basic_penalty_previous_1992;
            else
                $rpt_sef_1992_penalty[$i] = $xtotal_basic_penalty_previous_1992;
            $rpt_sef_total[$i] = ($rpt_sef[$i] + $rpt_sef_prev[$i] + $rpt_sef_adv[$i] + $rpt_sef_1991[$i] + $rpt_sef_1992[$i]) + ($rpt_sef_penalty[$i] + $rpt_sef_prev_penalty[$i] + $rpt_sef_1991_penalty[$i] + $rpt_sef_1992_penalty[$i]) - ($rpt_sef_discount[$i] + $rpt_sef_adv_discount[$i]); 

            // CASH DIV COLLECTIONS
            $cd_current_basic = 0;
            $cd_prev_basic = 0;
            $cd_adv_basic = 0;
            $cd_1991_basic = 0;
            $cd_1992_basic = 0;
            $cd_current_basic_penalty = 0;
            $cd_prev_basic_penalty = 0;
            $cd_1991_basic_penalty = 0;
            $cd_1992_basic_penalty = 0;
            $cd_current_basic_discount = 0;
            $cd_adv_basic_discount = 0;
            $cd_current_sef = 0;
            $cd_prev_sef = 0;
            $cd_adv_sef = 0;
            $cd_1991_sef = 0;
            $cd_1992_sef = 0;
            $cd_current_sef_penalty = 0;
            $cd_prev_sef_penalty = 0;
            $cd_1991_sef_penalty = 0;
            $cd_1992_sef_penalty = 0;
            $cd_current_sef_discount = 0;
            $cd_adv_sef_discount = 0;
            $cashdiv = CashDivision::whereMonth('date_of_entry','=', $request->month)
                ->whereYear('date_of_entry','=', $request->year)
                ->where('deleted_at', '=', null)
                ->where('col_municipality_id', '=', $i)
                ->get();
            foreach($cashdiv as $cd) {
                $entry_yr = Carbon::parse($cd->date_of_entry)->format('Y');
                foreach($cd->items as $item) {        
                    // basic
                    if($item->col_acct_title_id == 3) {
                        if($entry_yr == $current)
                            $cd_current_basic += $item->value*.35;
                        if($entry_yr == $preceeding)
                            $cd_prev_basic += $item->value*.35;
                        if($entry_yr >= $advance_yr)
                            $cd_adv_basic += $item->value*.35;
                        if($entry_yr <= $prior_start && $entry_yr <= 1991)
                            $cd_1991_basic += $item->value*.35;
                        if($entry_yr <= $prior_start && $entry_yr >= 1992)
                            $cd_1992_basic += $item->value*.35;
                    }
                    // basic penalties
                    if($item->col_acct_title_id == 54) {
                        if($entry_yr == $current)
                            $cd_current_basic_penalty += $item->value*.35;
                        if($entry_yr == $preceeding)
                            $cd_prev_basic_penalty += $item->value*.35;
                        if($entry_yr <= $prior_start && $entry_yr <= 1991)
                            $cd_1991_basic_penalty += $item->value*.35;
                        if($entry_yr <= $prior_start && $entry_yr >= 1992)
                            $cd_1992_basic_penalty += $item->value*.35;
                    }
                    // basic discount
                    if($item->col_acct_title_id == 2) {
                        if($entry_yr == $current)
                            $cd_current_basic_discount += $item->value*.35;
                        if($entry_yr >= $advance_yr)
                            $cd_adv_basic_discount += $item->value*.35;
                    }
                    // sef
                    if($item->col_acct_title_id == 49) {
                        if($entry_yr == $current)
                            $cd_current_sef += $item->value*.35;
                        if($entry_yr == $preceeding)
                            $cd_prev_sef += $item->value*.35;
                        if($entry_yr >= $advance_yr)
                            $cd_adv_sef += $item->value*.35;
                        if($entry_yr <= $prior_start && $entry_yr <= 1991)
                            $cd_1991_sef += $item->value*.35;
                        if($entry_yr <= $prior_start && $entry_yr >= 1992)
                            $cd_1992_sef += $item->value*.35;
                    }
                    // sef penalty
                    if($item->col_acct_title_id == 55) {
                        if($entry_yr == $current)
                            $cd_current_sef_penalty += $item->value*.35;
                        if($entry_yr == $preceeding)
                            $cd_prev_sef_penalty += $item->value*.35;
                        if($entry_yr <= $prior_start && $entry_yr <= 1991)
                            $cd_1991_sef_penalty += $item->value*.35;
                        if($entry_yr <= $prior_start && $entry_yr >= 1992)
                            $cd_1992_sef_penalty += $item->value*.35;
                    }
                    // sef discount
                    $cd_current_sef_discount = 0;
                    $cd_adv_sef_discount = 0;
                }
            }
            $rpt_basic_cd[$i] = $cd_current_basic;
            $rpt_basic_penalty_cd[$i] = $cd_current_basic_penalty;
            $rpt_basic_discount_cd[$i] = $cd_current_basic_discount;
            $rpt_basic_prev_cd[$i] = $cd_prev_basic;
            $rpt_basic_prev_penalty_cd[$i] = $cd_prev_basic_penalty;
            $rpt_basic_adv_cd[$i] = $cd_adv_basic;
            $rpt_basic_adv_discount_cd[$i] = $cd_adv_basic_discount;
            $rpt_basic_1991_cd[$i] = $cd_1991_basic;
            $rpt_basic_1991_penalty_cd[$i] = $cd_1991_basic_penalty;
            $rpt_basic_1992_cd[$i] = $cd_1992_basic;
            $rpt_basic_1992_penalty_cd[$i] = $cd_1992_basic_penalty;
            $rpt_basic_total_cd[$i] = ($rpt_basic_cd[$i] + $rpt_basic_prev_cd[$i] + $rpt_basic_adv_cd[$i] + $rpt_basic_1991_cd[$i] + $rpt_basic_1992_cd[$i]) + ($rpt_basic_penalty_cd[$i] + $rpt_basic_prev_penalty_cd[$i] + $rpt_basic_1991_penalty_cd[$i] + $rpt_basic_1992_penalty_cd[$i]) - ($rpt_basic_discount_cd[$i] + $rpt_basic_adv_discount_cd[$i]);

            $rpt_sef_cd[$i] = $cd_current_sef;
            $rpt_sef_penalty_cd[$i] = $cd_current_sef_penalty;
            $rpt_sef_discount_cd[$i] = $cd_current_sef_discount;
            $rpt_sef_prev_cd[$i] = $cd_prev_sef;
            $rpt_sef_prev_penalty_cd[$i] = $cd_prev_sef_penalty;
            $rpt_sef_adv_cd[$i] = $cd_adv_sef;
            $rpt_sef_adv_discount_cd[$i] = $cd_adv_sef_discount;
            $rpt_sef_1991_cd[$i] = $cd_1991_sef;
            $rpt_sef_1991_penalty_cd[$i] = $cd_1991_sef_penalty;
            $rpt_sef_1992_cd[$i] = $cd_1992_sef;
            $rpt_sef_1992_penalty_cd[$i] = $cd_1992_sef_penalty;
            $rpt_sef_total_cd[$i] = ($rpt_sef_cd[$i] + $rpt_sef_prev_cd[$i] + $rpt_sef_adv_cd[$i] + $rpt_sef_1991_cd[$i] + $rpt_sef_1992_cd[$i]) + ($rpt_sef_penalty_cd[$i] + $rpt_sef_prev_penalty_cd[$i] + $rpt_sef_1991_penalty_cd[$i] + $rpt_sef_1992_penalty_cd[$i]) - ($rpt_sef_discount_cd[$i] + $rpt_sef_adv_discount_cd[$i]);
        }
        // rpt
        $this->base['rpt_basic'] = $rpt_basic;
        $this->base['rpt_basic_penalty'] = $rpt_basic_penalty;
        $this->base['rpt_basic_discount'] = $rpt_basic_discount;
        $this->base['rpt_basic_prev'] = $rpt_basic_prev;
        $this->base['rpt_basic_prev_penalty'] = $rpt_basic_prev_penalty;
        $this->base['rpt_basic_adv'] = $rpt_basic_adv;
        $this->base['rpt_basic_adv_discount'] = $rpt_basic_adv_discount;
        $this->base['rpt_basic_1991'] = $rpt_basic_1991;
        $this->base['rpt_basic_1991_penalty'] = $rpt_basic_1991_penalty;
        $this->base['rpt_basic_1992'] = $rpt_basic_1992;
        $this->base['rpt_basic_1992_penalty'] = $rpt_basic_1992_penalty;
        $this->base['rpt_basic_total'] = $rpt_basic_total;

        $this->base['rpt_sef'] = $rpt_sef;
        $this->base['rpt_sef_penalty'] = $rpt_sef_penalty;
        $this->base['rpt_sef_discount'] = $rpt_sef_discount;
        $this->base['rpt_sef_prev'] = $rpt_sef_prev;
        $this->base['rpt_sef_prev_penalty'] = $rpt_sef_prev_penalty;
        $this->base['rpt_sef_adv'] = $rpt_sef_adv;
        $this->base['rpt_sef_adv_discount'] = $rpt_sef_adv_discount;
        $this->base['rpt_sef_1991'] = $rpt_sef_1991;
        $this->base['rpt_sef_1991_penalty'] = $rpt_sef_1991_penalty;
        $this->base['rpt_sef_1992'] = $rpt_sef_1992;
        $this->base['rpt_sef_1992_penalty'] = $rpt_sef_1992_penalty;
        $this->base['rpt_sef_total'] = $rpt_sef_total;

        // cashdiv
        $this->base['rpt_basic_cd'] = $rpt_basic_cd;
        $this->base['rpt_basic_penalty_cd'] = $rpt_basic_penalty_cd;
        $this->base['rpt_basic_discount_cd'] = $rpt_basic_discount_cd;
        $this->base['rpt_basic_prev_cd'] = $rpt_basic_prev_cd;
        $this->base['rpt_basic_prev_penalty_cd'] = $rpt_basic_prev_penalty_cd;
        $this->base['rpt_basic_adv_cd'] = $rpt_basic_adv_cd;
        $this->base['rpt_basic_adv_discount_cd'] = $rpt_basic_adv_discount_cd;
        $this->base['rpt_basic_1991_cd'] = $rpt_basic_1991_cd;
        $this->base['rpt_basic_1991_penalty_cd'] = $rpt_basic_1991_penalty_cd;
        $this->base['rpt_basic_1992_cd'] = $rpt_basic_1992_cd;
        $this->base['rpt_basic_1992_penalty_cd'] = $rpt_basic_1992_penalty_cd;
        $this->base['rpt_basic_total_cd'] = $rpt_basic_total_cd;

        $this->base['rpt_sef_cd'] = $rpt_sef_cd;
        $this->base['rpt_sef_penalty_cd'] = $rpt_sef_penalty_cd;
        $this->base['rpt_sef_discount_cd'] = $rpt_sef_discount_cd;
        $this->base['rpt_sef_prev_cd'] = $rpt_sef_prev_cd;
        $this->base['rpt_sef_prev_penalty_cd'] = $rpt_sef_prev_penalty_cd;
        $this->base['rpt_sef_adv_cd'] = $rpt_sef_adv_cd;
        $this->base['rpt_sef_adv_discount_cd'] = $rpt_sef_adv_discount_cd;
        $this->base['rpt_sef_1991_cd'] = $rpt_sef_1991_cd;
        $this->base['rpt_sef_1991_penalty_cd'] = $rpt_sef_1991_penalty_cd;
        $this->base['rpt_sef_1992_cd'] = $rpt_sef_1992_cd;
        $this->base['rpt_sef_1992_penalty_cd'] = $rpt_sef_1992_penalty_cd;
        $this->base['rpt_sef_total_cd'] = $rpt_sef_total_cd;

        $this->base['munics'] = Municipality::all();

        $data = $this->base;
        if(isset($request->button_excel)){
            Excel::create('PROVINCIAL INCOME', function($excel) use($data) {
                $excel->sheet('Provincial Income', function($sheet) use($data) {
                    $sheet->loadView('collection::report.excel_provincial_income', $data);
                });
            })->export('xls');
        } else {
            $pdf = new PDF;
            // $pdf = PDF::loadView('collection::pdf/provincial_income_new', $this->base)
                // ->setPaper('legal');
            $pdf = PDF::loadView('collection::pdf/provincial_income_new2', $this->base)
                ->setPaper('legal', 'landscape');
            return @$pdf->stream();
        }
    }

    // FROM MODULE2 - OLDER CODE
    // public function provincial_income(Request $request)
    // {
    //     $this->base['year'] = $request->year;
    //     $this->base['month'] = $request->month;
    //     $this->base['year_x'] = Carbon::createFromDate($request->year, 1, 1, 'Asia/Manila');
    //     $this->base['month_x'] = Carbon::createFromDate($request->year, 1, 1, 'Asia/Manila');
    //     $this->base['month_end'] = Carbon::createFromDate($request->year, ($request->month), 1, 'Asia/Manila');

    //     $get_categories = AccountCategory::get();
    //     $categories = [];
    //     foreach($get_categories as $categ) {
    //         if($categ->id == 2) { // remove BTS..
    //             continue;
    //         } else {
    //             $categories[$categ->id] = $categ->name;
    //         }
    //     }

    //     $this->base['categories'] = AccountCategory::get();
    //     $this->base['categories_wo_bts'] = $categories;
    //     $this->base['acct_groups'] = AccountGroup::get();

    //     $rpt_basic = 0;
    //     $rpt_basic_penalty = 0;
    //     $rpt_sef = 0;
    //     $rpt_sef_penalty = 0;

    //     $pprv_crnt_ammount = 0;
    //     $total_basic_current = 0;
    //     $total_basic_discount = 0;
    //     $total_basic_previous = 0;
    //     $total_basic_penalty_current = 0;
    //     $total_basic_penalty_previous = 0;
    //     $pprv_crnt_discount = 0;
    //     $pprv_prvious_ammount = 0;
    //     $pprv_pnalties_crnt = 0;
    //     $pprv_pnalties_prvious = 0;
    //     $xtotal_basic_current = 0;
    //     $xtotal_basic_discount = 0;
    //     $xtotal_basic_previous = 0;
    //     $xtotal_basic_penalty_current = 0;
    //     $xtotal_basic_penalty_previous = 0;
    //     for($i = 1; $i <= 14; $i++) { // for all municipalities
    //         $receipts = Receipt::where(DB::raw('month(report_date)'),'=', $request->month)
    //             ->where(DB::raw('year(report_date)'),'=', $request->year)
    //             ->where('is_printed', '=', 1)
    //             ->where('af_type', '=', 2)
    //             ->where('col_municipality_id', '=', $i)
    //             ->orderBy('serial_no', 'ASC')
    //             ->get();
    //         // $pprv_crnt_ammount = 0;
    //         // $total_basic_current = 0;
    //         // $total_basic_discount = 0;
    //         // $total_basic_previous = 0;
    //         // $total_basic_penalty_current = 0;
    //         // $total_basic_penalty_previous = 0;
    //         foreach ($receipts as $receipt) {
    //             foreach ($receipt->F56Detailmny as $f56_detail) {
    //                 // prov basic current amount
    //                 $total_basic_current += $f56_detail->basic_current;
    //                 $munshare_basic_current = round($total_basic_current * .4,2);
    //                 $brgyshare_basic_current = round($total_basic_current * .25,2);
    //                 $prv_crnt_ammount = ($total_basic_current - ($munshare_basic_current + $brgyshare_basic_current));
    //                 $pprv_crnt_ammount = round($prv_crnt_ammount,2,PHP_ROUND_HALF_UP);   
    //                 // prov basic current discount
    //                 $total_basic_discount += $f56_detail->basic_discount;
    //                 $brgyshare_basic_discount = round($total_basic_discount * .25,2);
    //                 $munshare_basic_discount = round($total_basic_discount * .4,2);
    //                 $prv_crnt_discount = ($total_basic_discount - ($munshare_basic_discount + $brgyshare_basic_discount));
    //                 $pprv_crnt_discount = round($prv_crnt_discount,2,PHP_ROUND_HALF_DOWN);
    //                 // prov basic previous amount
    //                 $total_basic_previous += $f56_detail->basic_previous;
    //                 $brgyshare_basic_previous = round($total_basic_previous * .25,2);
    //                 $munshare_basic_previous = round($total_basic_previous * .4,2);
    //                 $prv_prvious_ammount = ($total_basic_previous - ($munshare_basic_previous + $brgyshare_basic_previous));
    //                 $pprv_prvious_ammount = round($prv_prvious_ammount,2,PHP_ROUND_HALF_UP);
    //                 // prov basic penalties current
    //                 $total_basic_penalty_current += $f56_detail->basic_penalty_current;
    //                 $brgyshare_basic_penalty_current = round($total_basic_penalty_current * .25,2);
    //                 $munshare_basic_penalty_current = round($total_basic_penalty_current * .4,2);
    //                 $prv_pnalties_crnt = $total_basic_penalty_current - ($munshare_basic_penalty_current + $brgyshare_basic_penalty_current);
    //                 $pprv_pnalties_crnt =  round($prv_pnalties_crnt,2,PHP_ROUND_HALF_UP);
    //                 // prov basic penalties previous
    //                 $total_basic_penalty_previous += $f56_detail->basic_penalty_previous;
    //                 $brgyshare_basic_penalty_previous = round($total_basic_penalty_previous * .25,2);
    //                 $munshare_basic_penalty_previous = round($total_basic_penalty_previous * .4,2);
    //                 $prv_pnalties_prvious = $total_basic_penalty_previous - ($munshare_basic_penalty_previous + $brgyshare_basic_penalty_previous);
    //                 $pprv_pnalties_prvious = round($prv_pnalties_prvious,2,PHP_ROUND_HALF_UP);

    //                 // prov sef current amount
    //                 $bbrgyshare_basic_current = round($brgyshare_basic_current,2,PHP_ROUND_HALF_DOWN);
    //                 $total_basic_current = $pprv_crnt_ammount + $munshare_basic_current + $bbrgyshare_basic_current;
    //                 $xtotal_basic_current = round($total_basic_current * .5,2,PHP_ROUND_HALF_UP);
    //                 // prov sef current discount
    //                 $xtotal_basic_discount = round($total_basic_discount*.5,2,PHP_ROUND_HALF_DOWN);
    //                 // prov sef previous amount
    //                 $xtotal_basic_previous = $total_basic_previous * .5;
    //                 // prov sef penalties current
    //                 $xtotal_basic_penalty_current = $total_basic_penalty_current * .5;
    //                 // prov sef penalties previous
    //                 $xtotal_basic_penalty_previous = $total_basic_penalty_previous * .5;
    //             }
    //         }
    //         $rpt_basic += $pprv_crnt_ammount - $pprv_crnt_discount + $pprv_prvious_ammount;
    //         $rpt_basic_penalty += $pprv_pnalties_crnt + $pprv_pnalties_prvious;
    //         $rpt_sef += $xtotal_basic_current - $xtotal_basic_discount + $xtotal_basic_previous;
    //         $rpt_sef_penalty += $xtotal_basic_penalty_current + $xtotal_basic_penalty_previous;
    //     }

    //     // BTS
    //     $bts = ReceiptItems::select('col_acct_title_id', 'col_acct_title.name', DB::raw('sum(value) as total')) 
    //     ->join('col_acct_title', 'col_receipt_items.col_acct_title_id', '=', 'col_acct_title.id')
    //     ->join('col_receipt', 'col_receipt.id', '=', 'col_receipt_items.col_receipt_id')
    //     ->join('col_acct_group', 'col_acct_title.acct_group_id', '=', 'col_acct_group.id')
    //     ->join('col_acct_category', 'col_acct_category.id', '=', 'col_acct_group.acct_category_id')
    //     ->where(DB::raw('month(report_date)'),'=', $request->month)
    //     ->where(DB::raw('year(report_date)'),'=', $request->year)
    //     ->where('is_printed', 1)
    //     ->where('col_acct_group.acct_category_id', 2)
    //     // ->where(function($accts) {
    //     //     $accts->where('col_acct_category.id', 2)
    //     //     ->orWhere('col_acct_title_id', 33)
    //     //     ->orWhere('col_acct_title_id', 34);
    //     // })
    //     // ->where('col_acct_category.id', 2)
    //     // ->where('col_acct_title_id', 33)
    //     ->groupBy('col_receipt_items.col_acct_title_id')
    //     ->get();

    //     $bts_arr = [];
    //     foreach ($bts as $b) {
    //         $bts_arr[$b->col_acct_title_id] = $b->total;
    //     }

    //     $this->base['rpt_basic'] = $rpt_basic;
    //     $this->base['rpt_basic_penalty'] = $rpt_basic_penalty;
    //     $this->base['rpt_sef'] = $rpt_sef;
    //     $this->base['rpt_sef_penalty'] = $rpt_sef_penalty;
    //     $this->base['bts'] = $bts_arr;

    //     // computation per category
    //     $month_x = Carbon::createFromDate($request->year, 1, 1, 'Asia/Manila');
    //     $start_month = 1;
    //     $end_month = $request->month - 1;
    //     $month_p = [];
    //     $d_month = 0;
    //     for($x=0; $x<$end_month; $x++){
    //         $month_x =$month_x->addMonths($d_month);
    //         $month_p[$x]['y-m-d'] = $month_x->format('Y-m-d');
    //         $month_p[$x]['m'] = $month_x->format('m');
    //         $d_month = 1;
    //     }

    //     // compute START .. 
    //     $start_month = 1;
    //     $end_month = $request->month - 1;
    //     $month_p = [];
    //     $d_month = 0;
    //     for($x = 0; $x < $end_month; $x++){
    //         $month_x = $month_x->addMonths($d_month);
    //         $month_p[$x]['y-m-d'] = $month_x->format('Y-m-d');
    //         $month_p[$x]['m'] = $month_x->format('m');
    //         $d_month = 1;
    //     }
    //     $per_account = [];
    //     $total_per_category = [];
    //     foreach($this->base['categories'] as $category) {
    //         // per group .. 
    //         if(!isset($total_per_category[$category->id])) {
    //             $total_per_category[$category->id]['budget_estimate'] = 0;
    //             $total_per_category[$category->id]['actual_coll'] = 0;
    //             $total_per_category[$category->id]['total'] = 0;
    //             $total_per_category[$category->id]['percent_coll'] = 0;
    //             $total_per_category[$category->id]['past_month'] = 0;
    //         }
    //         foreach ($category->group as $group) {
    //             // per title ..
    //             if(!isset($total_per_category[$category->id][$group->id])) {
    //                 $total_per_category[$category->id][$group->id]['budget_estimate'] = 0;
    //                 $total_per_category[$category->id][$group->id]['actual_coll'] = 0;
    //                 $total_per_category[$category->id][$group->id]['total'] = 0;
    //                 $total_per_category[$category->id][$group->id]['percent_coll'] = 0;
    //                 $total_per_category[$category->id][$group->id]['past_month'] = 0;

    //                 if($group->name == 'Tax Revenue' && $category->id == 1) {
    //                     if(!isset($total_per_category[$category->id][$group->id]['tax_revenue'])) {
    //                         $total_per_category[$category->id][$group->id]['tax_revenue']['budget_estimate'] = 0;
    //                         $total_per_category[$category->id][$group->id]['tax_revenue']['actual_coll'] = 0;
    //                         $total_per_category[$category->id][$group->id]['tax_revenue']['total'] = 0;
    //                         $total_per_category[$category->id][$group->id]['tax_revenue']['percent_coll'] = 0;
    //                         $total_per_category[$category->id][$group->id]['tax_revenue']['past_month'] = 0;
    //                     }

    //                     if(!isset($total_per_category[$category->id]['tax_revenue'])) {
    //                         $total_per_category[$category->id]['tax_revenue']['budget_estimate'] = 0;
    //                         $total_per_category[$category->id]['tax_revenue']['actual_coll'] = 0;
    //                         $total_per_category[$category->id]['tax_revenue']['total'] = 0;
    //                         $total_per_category[$category->id]['tax_revenue']['percent_coll'] = 0;
    //                         $total_per_category[$category->id]['tax_revenue']['past_month'] = 0;
    //                     }
    //                 }
    //             }

    //             foreach($group->title as $title) {
    //                 if ($title->show_in_monthly == 1 && array_search($title->id, [34,36,37,39]) == false) {
    //                     $per_account[$category->id][$group->id]['group'][$title->id]['budget_estimate'] = $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] = $per_account[$category->id][$group->id]['group'][$title->id]['total'] = $per_account[$category->id][$group->id]['group'][$title->id]['percent_coll'] = $per_account[$category->id][$group->id]['group'][$title->id]['past_month'] = 0;

    //                     $per_account[$category->id][$group->id]['group_name'] = $group->name;
    //                     $per_account[$category->id][$group->id]['group'][$title->id]['account_title'] = $title->name;
    //                     $per_account[$category->id][$group->id]['group'][$title->id]['account_code'] = $title->code;
    //                     if(isset($title->budget()->where('year','=',$request->year)->first()->value))
    //                         $per_account[$category->id][$group->id]['group'][$title->id]['budget_estimate'] = $title->budget()->where('year','=',$request->year)->first()->value;
    //                     else
    //                         $per_account[$category->id][$group->id]['group'][$title->id]['budget_estimate'] = 0;

    //                     $this_month = 0;
    //                     $this_monthx = 0;
    //                     $mnthly_pix = $title->mnhtly_prov_income()->where('year','=',$request->year)->where('month','=',$request->month)->first();
    //                     if($mnthly_pix){
    //                         if($mnthly_pix->total_value != '0.00' || $mnthly_pix->total_value != '' ) {
    //                             $this_month =  $mnthly_pix->total_value;
    //                         }else{
    //                             $this_month =  $mnthly_pix->value;
    //                         }
    //                         $this_monthx = $mnthly_pix->value;
    //                     }
    //                     $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] = $this_monthx;
    //                     if($category->id == 1 || $category->id == 2) {
    //                         if($category->id == 1) {
    //                             if($title->id == 2) {
    //                                 $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] = $rpt_basic;
    //                             } elseif($title->id == 54 || $title->id == 55) {
    //                                 $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] = $rpt_basic_penalty;
    //                             } elseif($category->id == 2) {
    //                                 if(isset($bts[$title->id])) {
    //                                     $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] = $bts[$title->id];
    //                                 } else {
    //                                     $per_account[$category->id][$group->id][$title->id]['actual_coll'] = $this_monthx;
    //                                 }
    //                             } else {
    //                                 $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] = $this_monthx;
    //                             }
    //                         } elseif($category->id == 2) {
    //                             if(isset($bts_arr[$title->id])) {
    //                                 $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] = $bts_arr[$title->id];
    //                             } else {

    //                             }
    //                         }
    //                     } elseif($category->id == 4) {
    //                         if($title->id == 49) {
    //                             $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] = $rpt_sef;
    //                         } elseif($title->id == 54 || $title->id == 55) {
    //                             $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] = $rpt_sef_penalty;
    //                         } else {
    //                             $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'] = $this_monthx;
    //                         }
    //                     }

    //                     $cash_div_value = 0;
    //                     $mnthly_pi_value = 0;
    //                     $per_acct_total = 0;
    //                     $per_acct_total_percent = 0;
    //                     for($x=0; $x<$end_month; $x++){
    //                         $mnthly_pi = $title->mnhtly_prov_income()->where('year','=',$request->year)->where('month','=',$month_p[$x]['m'])->first();
    //                         if($mnthly_pi){
    //                             $mnthly_pi_value += $mnthly_pi->value;
    //                         }
    //                     }
    //                     $per_acct_total = $cash_div_value + $mnthly_pi_value + $this_monthx;
    //                     if($per_account[$category->id][$group->id]['group'][$title->id]['budget_estimate'] > 0){
    //                         $per_acct_total_percent = ( ($cash_div_value + $mnthly_pi_value + $this_month) / $per_account[$category->id][$group->id]['group'][$title->id]['budget_estimate'] ) * 100;
    //                     }
    //                     $per_account[$category->id][$group->id]['group'][$title->id]['total'] = $per_acct_total;
    //                     $per_account[$category->id][$group->id]['group'][$title->id]['percent_coll'] = $per_acct_total_percent;
    //                     $per_account[$category->id][$group->id]['group'][$title->id]['past_month'] = $cash_div_value + $mnthly_pi_value;

    //                     // TOTALS
    //                     $total_per_category[$category->id][$group->id]['budget_estimate'] += $per_account[$category->id][$group->id]['group'][$title->id]['budget_estimate'];
    //                     $total_per_category[$category->id][$group->id]['actual_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'];
    //                     $total_per_category[$category->id][$group->id]['total'] += $per_account[$category->id][$group->id]['group'][$title->id]['total'];
    //                     $total_per_category[$category->id][$group->id]['past_month'] += $per_account[$category->id][$group->id]['group'][$title->id]['past_month'];
    //                     // $total_per_category[$category->id][$group->id]['percent_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['percent_coll'];

    //                     $total_per_category[$category->id]['budget_estimate'] += $per_account[$category->id][$group->id]['group'][$title->id]['budget_estimate'];
    //                     $total_per_category[$category->id]['actual_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'];
    //                     $total_per_category[$category->id]['total'] += $per_account[$category->id][$group->id]['group'][$title->id]['total'];
    //                     $total_per_category[$category->id]['past_month'] += $per_account[$category->id][$group->id]['group'][$title->id]['past_month'];
    //                     // $total_per_category[$category->id]['percent_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['percent_coll'];
    //                     // FOR TAX REVENUE TOTALS
    //                     if($group->name == 'Tax Revenue' && $category->id == 1) {
    //                         $total_per_category[$category->id][$group->id]['tax_revenue']['budget_estimate'] += $per_account[$category->id][$group->id]['group'][$title->id]['budget_estimate'];
    //                         $total_per_category[$category->id][$group->id]['tax_revenue']['actual_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'];
    //                         $total_per_category[$category->id][$group->id]['tax_revenue']['total'] += $per_account[$category->id][$group->id]['group'][$title->id]['total'];
    //                         $total_per_category[$category->id][$group->id]['tax_revenue']['past_month'] += $per_account[$category->id][$group->id]['group'][$title->id]['past_month'];
    //                         // $total_per_category[$category->id][$group->id]['tax_revenue']['percent_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['percent_coll'];

    //                         $total_per_category[$category->id]['tax_revenue']['budget_estimate'] += $per_account[$category->id][$group->id]['group'][$title->id]['budget_estimate'];
    //                         $total_per_category[$category->id]['tax_revenue']['actual_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['actual_coll'];
    //                         $total_per_category[$category->id]['tax_revenue']['total'] += $per_account[$category->id][$group->id]['group'][$title->id]['total'];
    //                         $total_per_category[$category->id]['tax_revenue']['past_month'] += $per_account[$category->id][$group->id]['group'][$title->id]['past_month'];
    //                         // $total_per_category[$category->id]['tax_revenue']['percent_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['percent_coll'];
    //                     }
    //                 }

    //                 // for sub titles ..
    //                 foreach ($title->subs as $subs) {
    //                     if ($subs->show_in_monthly == 1 && array_search($title->id, [34,36,37,39]) == false) {
    //                         $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['budget_estimate'] = $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['actual_collection'] = $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['total'] = $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['percent_coll'] = $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['past_month'] = 0;

    //                         $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['sub_title'] = $subs->name;

    //                         if(isset($subs->budget()->where('year','=',$request->year)->first()->value)) {
    //                           $sub_budget_est = $subs->budget()->where('year','=',$request->year)->first()->value;
    //                         } else {
    //                           $sub_budget_est = 0;
    //                         }
    //                         $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['budget_estimate'] = $sub_budget_est;

    //                         $subscash_div_value = 0;
    //                         $subsmnthly_pi_value = 0;
    //                         $this_submonth = 0;
    //                         $this_submonthx = 0;
    //                         $sub_actual_coll = 0;
    //                         $sub_total = 0;
    //                         $percent_collection = 0;
    //                         for($x=0; $x<$end_month; $x++){
    //                             $subsmnthly_pi = $subs->mnhtly_prov_income()->where('year','=',$request->year)->where('month','=',$month_p[$x]['m'])->first();
    //                             if($subsmnthly_pi){
    //                                 $subsmnthly_pi_value += $subsmnthly_pi->value;
    //                             }
    //                         }
    //                         if(isset($mnthly_pixx)){
    //                             if($mnthly_pixx->total_value != '0.00' || $mnthly_pixx->total_value != '' ){
    //                                 $this_submonth =  $mnthly_pixx->total_value;
    //                             }else{
    //                                 $this_submonth =  $mnthly_pixx->value;
    //                             }
    //                             $this_submonthx =  $mnthly_pixx->value;
    //                         }
    //                         $sub_actual_coll = $this_submonthx;
    //                         $sub_total = $subscash_div_value + $subsmnthly_pi_value + $this_submonth;
    //                         if($per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['budget_estimate'] > 0) {
    //                             $percent_collection = ( ($subscash_div_value + $subsmnthly_pi_value + $this_submonth) / $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['budget_estimate'] ) * 100;
    //                         }
    //                         $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['actual_collection'] = $sub_actual_coll;
    //                         $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['total'] = $sub_total;
    //                         $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['percent_coll'] = $percent_collection;
    //                         $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['past_month'] = $subscash_div_value + $this_submonthx;

    //                         if($group->name == 'Tax Revenue' && $category->id == 1) {
    //                             $total_per_category[$category->id][$group->id]['tax_revenue']['budget_estimate'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['budget_estimate'];
    //                             $total_per_category[$category->id][$group->id]['tax_revenue']['actual_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['actual_collection'];
    //                             $total_per_category[$category->id][$group->id]['tax_revenue']['total'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['total'];
    //                             $total_per_category[$category->id][$group->id]['tax_revenue']['past_month'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['past_month'];
    //                             // $total_per_category[$category->id][$group->id]['tax_revenue']['percent_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['percent_coll'];

    //                             $total_per_category[$category->id]['tax_revenue']['budget_estimate'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['budget_estimate'];
    //                             $total_per_category[$category->id]['tax_revenue']['actual_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['actual_collection'];
    //                             $total_per_category[$category->id]['tax_revenue']['total'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['total'];
    //                             $total_per_category[$category->id]['tax_revenue']['past_month'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['past_month'];
    //                             // $total_per_category[$category->id]['tax_revenue']['percent_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['percent_coll'];
    //                         }
    //                         $total_per_category[$category->id][$group->id]['budget_estimate'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['budget_estimate'];
    //                         $total_per_category[$category->id][$group->id]['actual_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['actual_collection'];
    //                         $total_per_category[$category->id][$group->id]['total'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['total'];
    //                         $total_per_category[$category->id][$group->id]['past_month'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['past_month'];
    //                         // $total_per_category[$category->id][$group->id]['percent_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['percent_coll'];

    //                         $total_per_category[$category->id]['budget_estimate'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['budget_estimate'];
    //                         $total_per_category[$category->id]['actual_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['actual_collection'];
    //                         $total_per_category[$category->id]['total'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['total'];
    //                         $total_per_category[$category->id]['past_month'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['past_month'];
    //                         // $total_per_category[$category->id]['percent_coll'] += $per_account[$category->id][$group->id]['group'][$title->id]['subs'][$subs->id]['percent_coll'];
    //                     }
    //                 }
    //                 // end subtitles
    //             }
    //             // end titles

    //             $percentx = 0;
    //             if($group->name == 'Tax Revenue' && $category->id == 1) {
    //                 if($total_per_category[$category->id][$group->id]['budget_estimate'] > 0) {
    //                     $percentx = ($total_per_category[$category->id][$group->id]['tax_revenue']['actual_coll'] / $total_per_category[$category->id][$group->id]['tax_revenue']['budget_estimate']) * 100;
    //                 }
    //                 $total_per_category[$category->id][$group->id]['tax_revenue']['percent_coll'] = $percentx;
    //                 // $total_per_category[$category->id]['tax_revenue']['percent_coll'] = $percentx;
    //             } else {
    //                 if($total_per_category[$category->id][$group->id]['budget_estimate'] > 0) {
    //                     $percentx = ($total_per_category[$category->id][$group->id]['actual_coll'] / $total_per_category[$category->id][$group->id]['budget_estimate']) * 100;
    //                 }
    //                 $total_per_category[$category->id][$group->id]['percent_coll'] = $percentx;
    //                 // $total_per_category[$category->id]['percent_coll'] = $percentx;
    //             }
    //         }
    //     }
    //     // combine BTS and Gen. Fund w/ same groups
    //     foreach($per_account as $category => $data1) {
    //         if($category == 1) {
    //             foreach($data1 as $group => $data2) {
    //                 foreach($per_account[2] as $group2 => $cat2_data) {
    //                     // if($data2['group_name'] == $cat2_data['group_name']) {
    //                     if(strcasecmp($data2['group_name'], $cat2_data['group_name']) == 0) {
    //                         foreach($cat2_data['group'] as $title => $deets) {
    //                             $per_account[1][$group]['group'][$title]['percent_coll'] = $deets['percent_coll'];
    //                             $per_account[1][$group]['group'][$title]['total'] = $deets['total'];
    //                             $per_account[1][$group]['group'][$title]['actual_coll'] = $deets['actual_coll'];
    //                             $per_account[1][$group]['group'][$title]['budget_estimate'] = $deets['budget_estimate'];
    //                             $per_account[1][$group]['group'][$title]['account_title'] = $deets['account_title'];
    //                             $per_account[1][$group]['group'][$title]['account_code'] = $deets['account_code'];
    //                             $per_account[1][$group]['group'][$title]['is_bts'] = true;
    //                             // if(isset($deets['subs'])) {
    //                             //     $per_account[1][$group]['group'][$title]['subs'] = $deets['subs'];
    //                             // }
    //                             // $duplicate_title = false;
    //                             // $get_acct_title = AccountTitle::find($title);
    //                             // foreach($data2['group'] as $title2 => $search) {
    //                             //     if(strcasecmp($get_acct_title->name, $search['account_title'])) {
    //                             //         $duplicate_title = true;
    //                             //         break;
    //                             //     }
    //                             // }
    //                         }
    //                         unset($per_account[2][$group2]);
    //                     } 

    //                     if (array_search($cat2_data['group_name'], array_column($per_account[1], 'group_name')) == false) {
    //                         $per_account[1][$group2]['group_name'] = $cat2_data['group_name'];
    //                         foreach($cat2_data['group'] as $title => $deets) {
    //                             $per_account[1][$group2]['group'][$title]['percent_coll'] = $deets['percent_coll'];
    //                             $per_account[1][$group2]['group'][$title]['total'] = $deets['total'];
    //                             $per_account[1][$group2]['group'][$title]['actual_coll'] = $deets['actual_coll'];
    //                             $per_account[1][$group2]['group'][$title]['budget_estimate'] = $deets['budget_estimate'];
    //                             $per_account[1][$group2]['group'][$title]['account_title'] = $deets['account_title'];
    //                             $per_account[1][$group2]['group'][$title]['account_code'] = $deets['account_code'];
    //                             $per_account[1][$group2]['group'][$title]['is_bts'] = true;
    //                         }
    //                         unset($per_account[2][$group2]);
    //                     }  
    //                 }
    //             }
    //         }
    //     }

    //     // $col_per_account = collect($per_account);
    //     // dd($col_per_account);
    //     // per_account['acct_category']['group']['title']
    //     // end compute

    //     // sort
    //     $account_codes = [];
    //     foreach($per_account as $category => $data1) {
    //         foreach($data1 as $group => $data2) {
    //             $sort_accts = collect($data2['group'])->sortBy('account_code');
    //             $per_account[$category][$group]['group'] = [];
    //             $per_account[$category][$group]['group'] = $sort_accts;
    //         }
    //     }
    //     $col_per_account = collect($per_account)->sortBy('account_code');
    //     $this->base['per_account'] = $per_account;
    //     $this->base['per_account'] = $col_per_account;
    //     $this->base['total_per_category'] = $total_per_category;

    //     $data = $this->base;

    //     if(isset($request->button_excel)){
    //         Excel::create('PROVINCIAL INCOME', function($excel) use($data) {
    //             $excel->sheet('Provincial Income', function($sheet) use($data) {
    //                 $sheet->loadView('collection::report.excel_provincial_income', $data);
    //             });
    //         })->export('xls');
    //     } else {
    //         $pdf = new PDF;
    //         $pdf = PDF::loadView('collection::pdf/provincial_income_new', $this->base)
    //         // $pdf = PDF::loadView('collection::pdf/provincial_income_new-bak02142020', $this->base)
    //             ->setPaper('legal');
    //         return @$pdf->stream();
    //          // return view('collection::pdf/provincial_income_new', $this->base);
    //     }
        
    // }

    public function provincial_income_old(Request $request)
    {
        $insurance_premium = 42;
        $misc_bts = 39;
        $validator = Validator::make($request->all(), [
            'month' => 'required',
            'year' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->route('report.provincial_income')
                ->withErrors($validator);
        }

        $this->base['provincial_name'] = ReportOfficers::where('name', 'prov_name')->first();
        $this->base['provincial_position'] = ReportOfficers::where('name', 'prov_position')->first();

        $year = $request['year'];
        $month = $request['month'];
        $this->base['year'] = $year;
        $this->base['categories'] = AccountCategory::all();
        $this->base['start_date'] = date('Y-m-d', strtotime($year.'-'.$month.'-01'));
        $days_in_month = date('t', strtotime($this->base['start_date']));
        $this->base['end_date'] = date('Y-m-d', strtotime($year.'-'.$month.'-'.$days_in_month));
        $this->base['end_prev_month'] = date('Y-m-d', strtotime('-1 day', strtotime($this->base['start_date'])));
        $this->base['date_prevmonth'] = date('M', strtotime($this->base['end_prev_month']));

        $this->base['budget_estimate'] = BudgetEstimate::where('year', '=', $year)->get();

        # No existing budget estimate
        if (!count($this->base['budget_estimate'])) {
            Session::flash('error', ['Please set the budget estimate for the year '. $year .'.']);
            return redirect()->route('report.provincial_income');
        }

        # Gets budget estimate for query year
        $this->base['titles'] = $this->base['subtitles'] = array();
        foreach ($this->base['budget_estimate'] as $be) {
            if ($be->col_acct_title_id != null) {
                $this->base['titles'][$be->col_acct_title_id]['estimate'] = $be->value;
                $this->base['titles'][$be->col_acct_title_id]['startyear_prevmonth'] = 0;
                $this->base['titles'][$be->col_acct_title_id]['total_value'] = 0;
                $this->base['titles'][$be->col_acct_title_id]['actual_value'] = 0;
                $this->base['titles'][$be->col_acct_title_id]['pct_collection'] = 0;
            } elseif($be->col_acct_subtitle_id != null) {
                $this->base['subtitles'][$be->col_acct_subtitle_id]['estimate'] = $be->value;
                $this->base['subtitles'][$be->col_acct_subtitle_id]['startyear_prevmonth'] = 0;
                $this->base['subtitles'][$be->col_acct_subtitle_id]['total_value'] = 0;
                $this->base['subtitles'][$be->col_acct_subtitle_id]['actual_value'] = 0;
                $this->base['subtitles'][$be->col_acct_subtitle_id]['pct_collection'] = 0;
            }elseif($be->col_acct_subtitleitems_id != null){
                $this->base['subtitleitems'][$be->col_acct_subtitleitems_id]['estimate'] = $be->value;
                $this->base['subtitleitems'][$be->col_acct_subtitleitems_id]['startyear_prevmonth'] = 0;
                $this->base['subtitleitems'][$be->col_acct_subtitleitems_id]['total_value'] = 0;
                $this->base['subtitleitems'][$be->col_acct_subtitleitems_id]['actual_value'] = 0;
                $this->base['subtitleitems'][$be->col_acct_subtitleitems_id]['pct_collection'] = 0;
            }
        }

        $year_start = date('Y-m-d', strtotime($year.'-01-01'));
        $year_end = date('Y-m-d', strtotime($year.'-12-31'));
        $this->base['date_startyear'] = date('M', strtotime($year_start));

        # Get actual value for Monthly Provincial Income
        $monthly_provincial_income = MonthlyProvincialIncome::where('year', $year)->get();

        foreach ($monthly_provincial_income as $mpi) {
            if ($mpi->col_acct_title_id != null) {
                $type = 'titles';
                $id = $mpi->col_acct_title_id;
            } else {
                $type = 'subtitles';
                $id = $mpi->col_acct_subtitle_id;
            }

            if ($mpi->month == $month) {
                $this->base[$type][$id]['actual_value'] += $mpi->value;
            }

            if (isset($this->base[$type][$id])) {
                $this->base[$type][$id]['total_value'] += $mpi->value;
                $this->base[$type][$id]['startyear_prevmonth'] += $mpi->value;
            }
        }
        #for f56 computation
        $basic_current = $basic_previous = $basic_discount = 0;
        $penalty_current = $penalty_previous = 0;

        # Get start year to previous month values for receipts
        $receipts = Receipt::whereMonth('report_date','=',$month)
            ->where('is_cancelled', 0)
            ->where('is_printed', 1)
            ->get();
        foreach ($receipts as $receipt) {
            if ($receipt->af_type == 1) {
                foreach ($receipt->items as $item) {
                    $type = ($item->col_acct_title_id != 0) ? 'titles' : 'subtitles';
                    $id = ($item->col_acct_title_id != 0) ? $item->col_acct_title_id : $item->col_acct_subtitle_id;

                    # for insurance premium
                    if ($id == $insurance_premium) {
                        $this->base[$type][$misc_bts]['startyear_prevmonth'] += 15;
                    }

                    if (isset($this->base[$type][$id])) {
                        $this->base[$type][$id]['startyear_prevmonth'] += $item->share_provincial;
                    }
                }
            } else {
                if (!isset($receipt->F56Detail)) {
                    continue;
                }
                $basic_current += $receipt->F56Detail->basic_current;
                $basic_previous += $receipt->F56Detail->basic_previous;
                $basic_discount += $receipt->F56Detail->basic_discount;
                $penalty_current += $receipt->F56Detail->basic_penalty_current;
                $penalty_previous += $receipt->F56Detail->basic_penalty_previous;
            }
        }
        $basic_current_mun = round($basic_current * .4, 2);
        $basic_current_brgy = round($basic_current * .25, 2);
        $basic_previous_mun = round($basic_previous * .4, 2);
        $basic_previous_brgy = round($basic_previous * .25, 2);
        $basic_discount_mun = round($basic_discount * .4, 2);
        $basic_discount_brgy = round($basic_discount * .25, 2);
        $penalty_current_mun = round($penalty_current * .4, 2);
        $penalty_current_brgy = round($penalty_current * .25, 2);
        $penalty_previous_mun = round($penalty_previous * .4, 2);
        $penalty_previous_brgy = round($penalty_previous * .25, 2);

        $this->base['titles'][2]['startyear_prevmonth'] =
            ($basic_current - ($basic_current_mun + $basic_current_brgy)) +
            ($basic_previous - ($basic_previous_mun + $basic_previous_brgy)) -
            ($basic_discount - ($basic_discount_mun + $basic_discount_brgy));

        $this->base['subtitles'][1]['startyear_prevmonth'] =
            ($penalty_current - ($penalty_current_mun + $penalty_current_brgy)) +
            ($penalty_previous - ($penalty_previous_mun + $penalty_previous_brgy));

        $this->base['titles'][49]['startyear_prevmonth'] =
            round($basic_current * .5, 2) +
            round($basic_previous * .5, 2) -
            round($basic_discount * .5, 2);
        $this->base['titles'][50]['startyear_prevmonth'] =
            round($penalty_current *.5 , 2) +
            round($penalty_previous *.5 , 2);

        $basic_current = $basic_previous = $basic_discount = 0;
        $penalty_current = $penalty_previous = 0;

        # Get actual values for cash division
        $addtl = CashDivision::whereMonth('date_of_entry','=',$month)
            ->get();

        foreach ($addtl as $addt) {
            foreach ($addt->items as $item) {
                $id =  '';
                $type = '';
                if($item->col_acct_title_id != 0){
                    $type = 'titles';
                     $id =  $item->col_acct_title_id;
                }elseif($item->col_acct_subtitle_id != 0){
                     $type = 'subtitles';
                      $id =  $item->col_acct_subtitle_id;
                }
                if( $id !=  '' &&  $type != ''){
                      $this->base[$type][$id]['startyear_prevmonth'] += $item->share_provincial;
                }
            }
        }


        # Get actual values for receipts
        $receipts = Receipt::whereMonth('report_date','=',$month)
            ->where('is_cancelled', 0)
            ->where('is_printed', 1)
            ->get();
        foreach ($receipts as $receipt) {
            if ($receipt->af_type == 1) {
                foreach ($receipt->items as $item) {
                    $type = ($item->col_acct_title_id != 0) ? 'titles' : 'subtitles';
                    $id = ($item->col_acct_title_id != 0) ? $item->col_acct_title_id : $item->col_acct_subtitle_id;

                    # for insurance premium
                    if ($id == $insurance_premium) {
                        $this->base[$type][$misc_bts]['actual_value'] += 15;
                    }

                    if (isset($this->base[$type][$id])) {
                        $this->base[$type][$id]['actual_value'] += $item->share_provincial;
                    }
                }
            } else {
                if (!isset($receipt->F56Detail)) {
                    continue;
                }
                $basic_current += $receipt->F56Detail->basic_current;
                $basic_previous += $receipt->F56Detail->basic_previous;
                $basic_discount += $receipt->F56Detail->basic_discount;
                $penalty_current += $receipt->F56Detail->basic_penalty_current;
                $penalty_previous += $receipt->F56Detail->basic_penalty_previous;
            }
        }
        $basic_current_mun = round($basic_current * .4, 2);
        $basic_current_brgy = round($basic_current * .25, 2);
        $basic_previous_mun = round($basic_previous * .4, 2);
        $basic_previous_brgy = round($basic_previous * .25, 2);
        $basic_discount_mun = round($basic_discount * .4, 2);
        $basic_discount_brgy = round($basic_discount * .25, 2);
        $penalty_current_mun = round($penalty_current * .4, 2);
        $penalty_current_brgy = round($penalty_current * .25, 2);
        $penalty_previous_mun = round($penalty_previous * .4, 2);
        $penalty_previous_brgy = round($penalty_previous * .25, 2);

        $this->base['titles'][2]['actual_value'] =
            ($basic_current - ($basic_current_mun + $basic_current_brgy)) +
            ($basic_previous - ($basic_previous_mun + $basic_previous_brgy)) -
            ($basic_discount - ($basic_discount_mun + $basic_discount_brgy));

        $this->base['subtitles'][1]['actual_value'] =
            ($penalty_current - ($penalty_current_mun + $penalty_current_brgy)) +
            ($penalty_previous - ($penalty_previous_mun + $penalty_previous_brgy));

        $this->base['titles'][49]['actual_value'] =
            round($basic_current * .5, 2) +
            round($basic_previous * .5, 2) -
            round($basic_discount * .5, 2);
        $this->base['titles'][50]['actual_value'] =
            round($penalty_current *.5 , 2) +
            round($penalty_previous *.5 , 2);

        $basic_current = $basic_previous = $basic_discount = 0;
        $penalty_current = $penalty_previous = 0;
        # Get actual values for cash division
        $addtl = CashDivision::whereMonth('date_of_entry','=',$month)
            ->get();
        foreach ($addtl as $addt) {
            foreach ($addt->items as $item) {
                $type = ($item->col_acct_title_id != 0) ? 'titles' : 'subtitles';
                $id = ($item->col_acct_title_id != 0) ? $item->col_acct_title_id : $item->col_acct_subtitle_id;
                $this->base[$type][$id]['actual_value'] += $item->share_provincial;
            }
        }

        # Get total collection for receipt
        $receipts = Receipt::where('report_date','>=', $year_start)
            ->where('report_date','<=', $year_end)
            ->where('is_cancelled', 0)
            ->where('is_printed', 1)
            ->get();
        foreach ($receipts as $receipt) {
            if ($receipt->af_type == 1) {
                foreach ($receipt->items as $item) {
                    $type = ($item->col_acct_title_id != 0) ? 'titles' : 'subtitles';
                    $id = ($item->col_acct_title_id != 0) ? $item->col_acct_title_id : $item->col_acct_subtitle_id;

                    # for insurance premium
                    if ($id == $insurance_premium) {
                        $this->base[$type][$misc_bts]['total_value'] += 15;
                    }

                    if (isset($this->base[$type][$id])) {
                        $this->base[$type][$id]['total_value'] += $item->share_provincial;
                    }

                }
            } else {
                if (!isset($receipt->F56Detail)) {
                    continue;
                }
                $basic_current += $receipt->F56Detail->basic_current;
                $basic_previous += $receipt->F56Detail->basic_previous;
                $basic_discount += $receipt->F56Detail->basic_discount;
                $penalty_current += $receipt->F56Detail->basic_penalty_current;
                $penalty_previous += $receipt->F56Detail->basic_penalty_previous;
            }
        }
        $basic_current_mun = round($basic_current * .4, 2);
        $basic_current_brgy = round($basic_current * .25, 2);
        $basic_previous_mun = round($basic_previous * .4, 2);
        $basic_previous_brgy = round($basic_previous * .25, 2);
        $basic_discount_mun = round($basic_discount * .4, 2);
        $basic_discount_brgy = round($basic_discount * .25, 2);
        $penalty_current_mun = round($penalty_current * .4, 2);
        $penalty_current_brgy = round($penalty_current * .25, 2);
        $penalty_previous_mun = round($penalty_previous * .4, 2);
        $penalty_previous_brgy = round($penalty_previous * .25, 2);

        $this->base['titles'][2]['total_value'] =
            ($basic_current - ($basic_current_mun + $basic_current_brgy)) +
            ($basic_previous - ($basic_previous_mun + $basic_previous_brgy)) -
            ($basic_discount - ($basic_discount_mun + $basic_discount_brgy));

        $this->base['subtitles'][1]['total_value'] =
            ($penalty_current - ($penalty_current_mun + $penalty_current_brgy)) +
            ($penalty_previous - ($penalty_previous_mun + $penalty_previous_brgy));

        $this->base['titles'][49]['total_value'] =
            round($basic_current * .5, 2) +
            round($basic_previous * .5, 2) -
            round($basic_discount * .5, 2);
        $this->base['titles'][50]['total_value'] =
            round($penalty_current *.5 , 2) +
            round($penalty_previous *.5 , 2);

        # Get total collection for cash division
        $addtl = CashDivision::where('date_of_entry','>=', $year_start)
            ->get();
        foreach ($addtl as $addt) {
            foreach ($addt->items as $item) {
                $type = ($item->col_acct_title_id != 0) ? 'titles' : 'subtitles';
                $id = ($item->col_acct_title_id != 0) ? $item->col_acct_title_id : $item->col_acct_subtitle_id;
                $this->base[$type][$id]['total_value'] += $item->share_provincial;
            }
        }

        # Compute % of collection
        foreach($this->base['titles'] as $i => $title) {
            if ($this->base['titles'][$i]['estimate'] != 0) {
                $this->base['titles'][$i]['pct_collection'] = ($title['total_value'] / $title['estimate']) * 100;
            }
        }

        # Compute % of collection
        foreach($this->base['subtitles'] as $i => $subtitle) {
            if(isset($this->base['subtitles'][$i]['estimate'])){
                if ($this->base['subtitles'][$i]['estimate'] != 0) {
                    $this->base['subtitles'][$i]['pct_collection'] = ($subtitle['total_value'] / $subtitle['estimate']) * 100;
                }
            }

        }

        $pdf = new PDF;
        $pdf = PDF::loadView('collection::pdf/provincial_income', $this->base)
            ->setPaper('legal');
        return @$pdf->stream();
    }

    private function add_titles($category, $titles) {
        foreach ($titles as $title) { 
            // exclude titles w/ these IDs
            if(array_search($title->id, [34,36,37,39]) == false) {
                $category['titles'][$title->name] = array(
                    'id' => $title->id,
                    'abbrv' => $title->abv,
                    'count' => 0,
                    'total' => 0,
                );
                $subtitles = $title->subs;
                foreach ($subtitles as $sub) {
                    if (!$sub->name) { continue; }
                    $category['subtitles'][$sub->name] = array(
                        'id' => $sub->id,
                        'abbrv' => $sub->abv,
                        'count' => 0,
                        'total' => 0,
                    );
                }
            }
        }
        return $category;
    }

    public function collections_deposits(Request $request)
    {
        
        $form_51 = 1;
        $insurance_premium = 42;
        $amusement = 6;
        $date_start = date('Y-m-d', strtotime($request['start_date']));
        $date_end = date('Y-m-d', strtotime($request['end_date']));
        $report_date = date('Y-m-d', strtotime($request['report_date']));
        
        $receipts = Receipt::where('report_date','>=', $date_start)
            ->where('report_date','<=', $date_end)
            ->where('is_printed', '=', 1)
            ->where('af_type', $form_51)
            // ->where('is_cancelled', 0)
            ->orderBy('serial_no', 'ASC')
            ->orderBy('updated_at', 'DESC')
            ->get();

        $unique = [];
        foreach($receipts as $r) {
            if(!isset($count_repeat[$r->serial_no]['cust_id']))
                $count_repeat[$r->serial_no]['cust_id'] = [];

            if(!isset($count_repeat[$r->serial_no]['count'])) {
                $count_repeat[$r->serial_no]['count'] = 0;
            }

            if($count_repeat[$r->serial_no]['count'] <= 1) {
                $count_repeat[$r->serial_no]['count']++;
                array_push($count_repeat[$r->serial_no]['cust_id'], $r->col_customer_id);
            }
        }

        foreach($receipts as $r) {
            if(!isset($repeat_ctr[$r->serial_no]))
                $repeat_ctr[$r->serial_no] = 0;

            if(isset($count_repeat[$r->serial_no])) {
                if($count_repeat[$r->serial_no]['count'] <= 1 && $repeat_ctr[$r->serial_no] < 1) {
                    array_push($unique, $r);
                    $repeat_ctr[$r->serial_no]++;
                } else {
                    $unique_cust = array_unique($count_repeat[$r->serial_no]['cust_id']);
                    if($r->is_cancelled == 0 && $repeat_ctr[$r->serial_no] < 1) {
                        array_push($unique, $r);
                        $repeat_ctr[$r->serial_no]++;
                    }
                    else if($r->is_cancelled == 0 && count($unique_cust) > 1) {
                        array_push($unique, $r);
                        $repeat_ctr[$r->serial_no]++;
                    }
                }
            }
        }
        
        $receipts = collect($unique);

        foreach ($receipts as $i => $r) {
            if(!isset($r->serial)){
                 Session::flash('error', ['A REFERENCE SERIAL HAS BEEN DELETED! PLS. CALL ADMIN.']);
                  return redirect()->route('report.collections_deposits');
            }
            if(isset($_GET['type'])) {
                if ($r->serial->acct_cat_id != $_GET['type']) {
                    if($_GET['type'] == 1) {
                        if($r->serial->acct_cat_id == 1 || $r->serial->acct_cat_id == 2) {
                            // combine BTS and General Fund
                        } else {
                            unset($receipts[$i]);
                        }
                    } else {
                        unset($receipts[$i]);
                    }
                }
            } else {
                // here
                Session::flash('error', ['Please select type']);
                return redirect()->back();
            }
            
        }

        if (count($receipts) == 0) {
            # No existing transaction
            Session::flash('error', ['No transaction for '. date('F d, Y', strtotime($date_start)) .' to '. date('F d, Y', strtotime($date_end)) .'.']);
            return redirect()->route('report.collections_deposits');
        }

        # get officer
        $acctble_officer_name = ReportOfficers::whereId(10)->first();
        $acctble_officer_position = ReportOfficers::whereId(11)->first();

        # get list of transaction types
        $total_in_words = 0;
        $trantypes = [];
        $transaction_types = TransactionType::get();
        foreach($transaction_types as $type) {
            $trantypes[$type->id] = ['name' => $type->name, 'total' => 0];
        }

        # get list of accounts
        $accounts = [];
        $receipts_total = [];
        if($request['type'] == 1) {
            $category = AccountCategory::where(function($query) {
                $query->whereId(1)->orWhere('id', 2);
            })->get(); // combine records for general fund and BTS, effective 2020
        } else {
            $category = AccountCategory::whereId($request['type'])->first();
        }

        if($request['type'] == 1) {
            foreach($category as $cat) {
                $accounts[$cat->name] = array(
                    'id' => $cat->id,
                    'count' => 0,
                    'titles' => [],
                    'subtitles' => [],
                );
                $accounts[$cat->name] = $this->add_titles($accounts[$cat->name], $cat->group_title);
            }
        } else {
            $accounts[$category->name] = array(
                'id' => $category->id,
                'count' => 0,
                'titles' => [],
                'subtitles' => [],
            );

            $accounts[$category->name] = $this->add_titles($accounts[$category->name], $category->group_title);
        }

        // orig
        // $accounts[$category->name] = array(
        //     'id' => $category->id,
        //     'count' => 0,
        //     'titles' => [],
        //     'subtitles' => [],
        // );
        // $accounts[$category->name] = $this->add_titles($accounts[$category->name], $category->group_title);

        # get list of municipalities and barangays
        $amusement_shares = [];
        $shares = [];
        $municipalities = Municipality::get();
        
        foreach ($municipalities as $mun) {
            $shares[$mun->id] = array(
                'name' => $mun->name,
                'count' => 0,
                'total_share' => 0,
                'barangays' => [],
            );

            $amusement_shares[$mun->id] = array(
                'name' => $mun->name,
                'total_share' => 0,
            );
            $barangays = $mun->barangays;
            foreach ($barangays as $brgy) {
                $shares[$mun->id]['barangays'][$brgy->id] = array(
                    'name' => $brgy->name,
                    'count' => 0,
                    'total_share' => 0,
                );
            }
        }

        # variables for receipt accountability
        $rcpt_acct_af = array();
        $bank = array();
        
        foreach ($receipts as $rcpt_index => $receipt) {
            if ($receipt->serial->acct_cat_id != $_GET['type']) {
                if($_GET['type'] == 1) {
                    if($receipt->serial->acct_cat_id == 1 || $receipt->serial->acct_cat_id == 2) {
                        // combine BTS and Gen. Fund
                    } else {
                        unset($receipts[$rcpt_index]);
                        continue;
                    }
                } else {
                    unset($receipts[$rcpt_index]);
                    continue;
                }
            }

            if (!isset($rcpt_acct_af[$receipt->col_serial_id])) {
                $rcpt_acct_af[$receipt->col_serial_id]['serials'] = [];
            }
            array_push($rcpt_acct_af[$receipt->col_serial_id]['serials'], $receipt->serial_no);

            if ($receipt->is_cancelled) {
                continue;
            }
            $total = 0;
            $total_share_mun = 0;
            $total_share_brgy = 0;
            $category = [];

            foreach($receipt->items as $item) {
                // $category = null;
                if ($item->col_acct_title_id !== 0) {
                    # title
                    $category = $item->acct_title->group->category;
                    $type = 'titles';
                    $name = $item->acct_title->name;
                } else {
                    # subtitle
                    $category = (isset($item->acct_subtitle->title->group->category)) ? $item->acct_subtitle->title->group->category : '';
                    $type = 'subtitles';
                    // $name = $item->acct_subtitle->name;
                    $name = (isset($item->acct_subtitle->name)) ? $item->acct_subtitle->name : '';
                }

                if($category){
                    if (isset($accounts[$category->name])) {
                        if (!isset($shares[$receipt->col_municipality_id][$receipt->serial_no])) {
                            if ($receipt->col_municipality_id !== 0) {
                                $shares[$receipt->col_municipality_id][$receipt->serial_no] = 0;
                                $shares[$receipt->col_municipality_id]['barangays'][$receipt->col_barangay_id][$receipt->serial_no] = 0;
                            }

                            if ($receipt->col_barangay_id != 0) {
                                $shares[$receipt->col_municipality_id]['barangays'][$receipt->col_barangay_id]['count'] += 1;
                                $shares[$receipt->col_municipality_id]['count'] += 1;
                            }
                        }

                        #amusement shares
                        if ($item->col_acct_title_id == $amusement) {
                            if($receipt->col_municipality_id != 0){
                                $amusement_shares[$receipt->col_municipality_id]['total_share'] += $item->share_municipal;
                            }
                        }

                        # for processing receipts paid via bank
                        $bank_row = array();
                        if (isset($bank[$receipt->serial_no])){
                            $bank[$receipt->serial_no]['amt'] += $item->value;
                        } else {
                            if (in_array($receipt->transaction_type, [2,3])) {
                                $bank_row['bank'] = $receipt->bank_name;
                                $bank_row['check_no'] = $receipt->bank_number;
                                $bank_row['payee'] = $receipt->customer->name;
                                $bank_row['amt'] = $item->value;
                                $bank[$receipt->serial_no] = $bank_row;
                            }
                        }

                        if ($receipt->col_municipality_id !== 0 && $receipt->col_municipality_id != 14) {
                            $shares[$receipt->col_municipality_id]['total_share'] += $item->share_municipal;
                            if(!isset($shares[$receipt->col_municipality_id]['barangays'][$receipt->col_barangay_id]['total_share'])){
                               continue;
                            }
                            $shares[$receipt->col_municipality_id]['barangays'][$receipt->col_barangay_id]['total_share'] += $item->share_barangay;
                            $shares[$receipt->col_municipality_id][$receipt->serial_no] += $item->share_municipal;
                            $shares[$receipt->col_municipality_id]['barangays'][$receipt->col_barangay_id][$receipt->serial_no] += $item->share_barangay;
                        }

                        # for BTS misc from insurance premium
                        $for_misc = 0;
                        if(isset($accounts[$category->name][$type][$name])) { //
                            if ($accounts[$category->name][$type][$name]['id'] == $insurance_premium) {
                                $for_misc = 15;
                                
                                // if(isset($accounts[$category->name][$type]['Miscellaneous Income'])) { //
                                    if (!isset($accounts[$category->name][$type]['Miscellaneous Income'][$item->receipt->serial_no])) {
                                        $accounts[$category->name][$type]['Miscellaneous Income'][$item->receipt->serial_no] = 15;
                                    } else {
                                        $accounts[$category->name][$type]['Miscellaneous Income'][$item->receipt->serial_no] += 15;
                                    }
                                    $accounts[$category->name][$type]['Miscellaneous Income']['total'] += 15;
                                    $accounts[$category->name][$type]['Miscellaneous Income']['count'] += 1;
                                // } 
                            }
                        }
                            
                            $total += $item->value;
                            $accounts[$category->name]['count'] += 1;
                            if(isset($accounts[$category->name][$type][$name])) //
                                $accounts[$category->name][$type][$name]['count'] += 1; 

                            if (!isset($accounts[$category->name][$type][$name][$item->receipt->serial_no])) {
                                $accounts[$category->name][$type][$name][$item->receipt->serial_no] = $item->share_provincial - $for_misc;
                                // $accounts[$category->name][$type][$name][$item->receipt->serial_no] = $item->value - $for_misc;
                            } else {
                                $accounts[$category->name][$type][$name][$item->receipt->serial_no] += $item->share_provincial;
                                // $accounts[$category->name][$type][$name][$item->receipt->serial_no] += $item->value;
                            }

                            if ($receipt->is_cancelled !== 1) {
                                if(isset($accounts[$category->name][$type][$name]['total'])) 
                                    $accounts[$category->name][$type][$name]['total'] += $item->share_provincial - $for_misc;
                                    // $accounts[$category->name][$type][$name]['total'] += $item->value - $for_misc;
                                if(isset($trantypes[$receipt->transaction_type]['total']))
                                    $trantypes[$receipt->transaction_type]['total'] += $item->value;

                                if ($receipt->transaction_type != 4) {
                                    $total_in_words += $item->value;
                                }
                            }
                        // }
                    }
                }
            }

            if ($receipt->is_cancelled !== 1) {
                $receipts_total[$receipt->serial_no] = $total;
            }
        }
        $rcpt_acct = $this->format_sort_af($form_51, $rcpt_acct_af, $date_start, $date_end, $_GET['type']);

        // $rcpt_acct = $this->format_sort_af($form_56, $rcpt_acct_af, $date_start, $date_end);
        // $rcpt_acct = $this->bms_format($form_51, $rcpt_acct_af, $date_start, $date_end);
        // dd($rcpt_acct);
        $share_columns = 0;
        $total_columns = 2;

        # remove unused accounts
        foreach ($accounts as $i => $account) {
            // if ($account['count'] == 0) {
            //     unset($accounts[$i]);
            // }
            # removes unused titles
            foreach ($account['titles'] as $j => $titles) { 
                if(isset($titles['count'])) { //
                    if ($titles['count'] == 0) {
                        unset($accounts[$i]['titles'][$j]);
                    } else {
                        $total_columns += 1;
                    }
                } else {
                    $total_columns += 1;
                }
            }

            # removes unused subtitles
            foreach ($account['subtitles'] as $j => $subtitles) {
                if ($subtitles['count'] == 0) {
                    unset($accounts[$i]['subtitles'][$j]);
                } else {
                    $total_columns += 1;
                }
            }
        }

        # remove unused shares
        foreach ($shares as $i => $share) {
            if ($share['count'] == 0 || $share['total_share'] == 0) {
                unset($shares[$i]);
            } else {
                $total_columns += 1;
                $share_columns += 1;
            }

            foreach ($share['barangays'] as $j => $brgy) {
                if(isset($brgy['count'])){
                    if ($brgy['count'] == 0 || $brgy['total_share'] == 0) {
                        unset($shares[$i]['barangays'][$j]);
                    } else {
                        $share_columns += 1;
                        $total_columns += 1;
                    }
                }

            }
        }


        #remove unused amusement shares
        foreach ($amusement_shares as $i => $as) {
            if ($as['total_share'] == 0) {
                unset($amusement_shares[$i]);
            }
        }


        if ($date_start == $date_end) {
            $this->base['date_range'] = date('F d, Y', strtotime($date_start));
        } else {
            $date_end = new Carbon($date_end);
            $this->base['date_range'] = date('F d', strtotime($date_start)) .' - '. $date_end->format('F d, Y') ;
        }
        
        $this->base['report_start'] = date('F d, Y', strtotime($date_start));
        $this->base['report_date'] = date('F d, Y', strtotime($report_date));

        $this->base['bac_type_1'] = 0;
        $this->base['bac_type_2'] = 0;
        $this->base['bac_type_3'] = 0;

        // $this->base['officer_name'] = ReportOfficers::where('name', 'coldep_name')->first();
        // $this->base['officer_position'] = ReportOfficers::where('name', 'coldep_position')->first();
        // $this->base['trust_fund_officer_name'] = ReportOfficers::where('name', 'trust_fund_officer_name')->first();
        // $this->base['trustfund_officer_position'] = ReportOfficers::where('name', 'trustfund_officer_position')->first();
        // $this->base['bts_officer_name'] = ReportOfficers::where('name', 'bts_officer_name')->first();
        // $this->base['bts_report_officer_position'] = ReportOfficers::where('name', 'bts_report_officer_position')->first();
        // $this->base['bese_officer_name'] = ReportOfficers::where('name', 'bts_officer_name')->first();
        // $this->base['bese_report_officer'] = ReportOfficers::where('name', 'bts_report_officer')->first();
        // $this->base['bts_report_officer'] = ReportOfficers::where('name', 'bts_report_officer')->first();
        $this->base['bese_report_officer_position'] = ReportOfficers::where('name', 'bts_report_officer_position')->first();
        $this->base['short_name_lcro'] = ReportOfficers::where('name', 'short_name_lcro')->first();
        $this->base['total_in_words'] = $this->convert_number_to_words($total_in_words - $trantypes[5]['total']); // subtract bank deposit/transfer total from total collection
        $this->base['date'] = date('F d, Y');
        $this->base['accounts'] = $accounts;
        $this->base['shares'] = $shares;
        $this->base['receipts'] = $receipts;
        $this->base['receipts_total'] = $receipts_total;
        $this->base['total_columns'] = $total_columns;
        $this->base['share_columns'] = $share_columns;
        $this->base['rcpt_acct'] = $rcpt_acct;
        $this->base['trantypes'] = $trantypes;
        $this->base['bank'] = $bank;
        $this->base['fund'] = AccountCategory::whereId($_GET['type'])->first();
        if(!is_null($request['report_officer']) && $request['report_officer'] != " " && $request['report_officer'] != "") {
            $split = explode("_", $request['report_officer']);
            $officer = ReportOfficerNew::find($split[0]);
            $report_officer = $officer->officer_name;
            $report_officer_pos = $split[2];
            if ($_GET['type'] == 5) {
                $this->base['trust_fund_officer_name'] = $report_officer;
                $this->base['trustfund_officer_position'] = $report_officer_pos;
            } else if($_GET['type'] == 2) {
                $this->base['bts_officer_name'] = $report_officer;
                $this->base['bts_report_officer'] = $report_officer;
                $this->base['bts_report_officer_position'] = $report_officer_pos;
            } elseif($_GET['type'] == 3) {
                $this->base['bese_officer_name'] = $report_officer;
                $this->base['bese_report_officer'] = $report_officer;
            } else {
                $this->base['officer_name'] = $report_officer;
                $this->base['officer_position'] = $report_officer_pos;
            }
        } else {
            $this->base['officer_name'] = ReportOfficers::where('name', 'coldep_name')->first();
            $this->base['officer_position'] = ReportOfficers::where('name', 'coldep_position')->first();
            $this->base['trust_fund_officer_name'] = ReportOfficers::where('name', 'trust_fund_officer_name')->first();
            $this->base['trustfund_officer_position'] = ReportOfficers::where('name', 'trustfund_officer_position')->first();
            $this->base['bts_officer_name'] = ReportOfficers::where('name', 'bts_officer_name')->first();
            $this->base['bts_report_officer'] = ReportOfficers::where('name', 'bts_report_officer')->first();
            $this->base['bts_report_officer_position'] = ReportOfficers::where('name', 'bts_report_officer_position')->first();
            $this->base['bese_officer_name'] = ReportOfficers::where('name', 'bts_officer_name')->first();
            $this->base['bese_report_officer'] = ReportOfficers::where('name', 'bts_report_officer')->first();
        }
        $this->base['acctble_officer_name'] = $acctble_officer_name;
        $this->base['acctble_officer_position'] = $acctble_officer_position;
        $this->base['amusement_shares'] = $amusement_shares;
        $this->base['papr_size'] = $request['paper_size'];

        $convert_height = round(floatval($request['custom_height']) * 72, 2);
        $convert_width = round(floatval($request['custom_width']) * 72, 2);
        $this->base['papr_size_custom_h'] = $convert_height;
        $this->base['papr_size_custom_w'] = $convert_width;
        
        if(isset($request['button_pdf'])){
            $paper_size = $this->set_paper_size($total_columns);
                // dd($total_columns);

            if(isset($request['paper_size']) && $request['paper_size'] != "") {
                if($request['paper_size'] == 'legal') {
                    if($request['type'] == 1){ // combine general fund and BTS
                        $pdf = PDF::loadView('collection::pdf.collections_deposits_rows', $this->base)
                            ->setPaper("legal", 'Landscape');
                    }else{
                        $pdf = PDF::loadView('collection::pdf.collections_deposits_rows_bts', $this->base)
                            ->setPaper("legal", 'Landscape');
                    }
                } else if($request['paper_size'] == 'A3') {
                    if($request['type'] == 1){ // combine general fund and BTS
                        $pdf = PDF::loadView('collection::pdf.collections_deposits_rows', $this->base)
                            ->setPaper("A3", 'Landscape');
                    }else{
                        $pdf = PDF::loadView('collection::pdf.collections_deposits_rows_bts', $this->base)
                            ->setPaper("A3", 'Landscape');
                    }
                } elseif($request['paper_size'] == 'custom' && $request['custom_height'] > 0 && $request['custom_width'] > 0) {
                    // convert to pt
                    // $convert_height = $request['custom_height'] * 72;
                    // $convert_width = $request['custom_width'] * 72;

                    if($request['type'] == 1){ // combine general fund and BTS
                        $pdf = PDF::loadView('collection::pdf.collections_deposits_rows', $this->base)
                            ->setPaper(array(0,0,$convert_width,$convert_height), 'Landscape');
                    }else{
                        $pdf = PDF::loadView('collection::pdf.collections_deposits_rows_bts', $this->base)
                            ->setPaper(array(0,0,$convert_width,$convert_height), 'Landscape');
                    }
                } else {
                    if($request['type'] == 1){ // combine general fund and BTS
                        $pdf = PDF::loadView('collection::pdf.collections_deposits_rows', $this->base)
                            ->setPaper("A3", 'Landscape');
                    }else{
                        $pdf = PDF::loadView('collection::pdf.collections_deposits_rows_bts', $this->base)
                            ->setPaper("letter", 'Landscape');
                    }
                }
            } else {
                if($request['type'] == 1){ // combine general fund and BTS
                    $pdf = PDF::loadView('collection::pdf.collections_deposits_rows', $this->base)
                        ->setPaper("legal", 'Landscape');
                }else{
                    $pdf = PDF::loadView('collection::pdf.collections_deposits_rows_bts', $this->base)
                        ->setPaper("letter", 'Landscape');
                }
            }
            

            return $pdf->stream('sample.pdf');

        }elseif(isset($request['button_html'])){
            return view('collection::html.collections_deposits',$this->base)->with('base', $this->base);
        }elseif(isset($request['button_pdf_type'])){
            $this->base['typex'] = $request['button_pdf_type'];
            $typex = $request['button_pdf_type'];
            $paper_size = $this->set_paper_size($total_columns);

            if(isset($request['paper_size']) && $request['paper_size'] != "") {
                if($request['paper_size'] == 'legal') {
                    if($request['type'] == 2 && (is_null($typex) || $typex == "")){
                        $pdf = PDF::loadView('collection::pdf.collections_deposits_rows_bts', $this->base)
                        ->setPaper("legal", 'portrait');
                    }else{
                        if($typex === 'type_A'){
                            $pdf = PDF::loadView('collection::pdf.collections_deposits_per_A', $this->base)
                        // ->setPaper("legal", 'Landscape');
                            ->setPaper("legal", 'Landscape');
                        }elseif ($typex === 'type_B') {
                            $pdf = PDF::loadView('collection::pdf.collections_deposits_per_B', $this->base)
                        // ->setPaper("legal", 'Landscape');
                            ->setPaper("legal", 'Landscape');
                        }elseif ($typex === 'type_C' || $typex === 'type_D') {
                            $pdf = PDF::loadView('collection::pdf.collections_deposits_per_CD', $this->base)
                        // ->setPaper("legal", 'Landscape');
                            ->setPaper("legal", 'Landscape');
                        }elseif ($typex === 'type_html_A' ) {
                            return view('collection::html.collections_deposits_A',$this->base)->with('base', $this->base);
                        }
                    }
                } else if($request['paper_size'] == 'A3') {
                    if($request['type'] == 2 && (is_null($typex) || $typex == "")){
                        $pdf = PDF::loadView('collection::pdf.collections_deposits_rows_bts', $this->base)
                        ->setPaper("A3", 'portrait');
                    }else{
                        if($typex === 'type_A'){
                            $pdf = PDF::loadView('collection::pdf.collections_deposits_per_A', $this->base)
                        // ->setPaper("legal", 'Landscape');
                            ->setPaper("A3", 'Landscape');
                        }elseif ($typex === 'type_B') {
                            $pdf = PDF::loadView('collection::pdf.collections_deposits_per_B', $this->base)
                        // ->setPaper("legal", 'Landscape');
                            ->setPaper("A3", 'Landscape');
                        }elseif ($typex === 'type_C' || $typex === 'type_D') {
                            $pdf = PDF::loadView('collection::pdf.collections_deposits_per_CD', $this->base)
                        // ->setPaper("legal", 'Landscape');
                            ->setPaper("A3", 'Landscape');
                        }elseif ($typex === 'type_html_A' ) {
                            return view('collection::html.collections_deposits_A',$this->base)->with('base', $this->base);
                        }
                    }
                } else if($request['paper_size'] == 'custom' && $request['customer_height'] > 0 && $request['customer_width'] > 0) {
                    $convert_height = $request['customer_height'] * 72;
                    $convert_width = $request['customer_height'] * 72;

                    if($request['type'] == 2 && (is_null($typex) || $typex == "")){
                        $pdf = PDF::loadView('collection::pdf.collections_deposits_rows_bts', $this->base)
                        ->setPaper(array(0,0,$convert_width,$convert_height), 'portrait');
                    }else{
                        if($typex === 'type_A'){
                            $pdf = PDF::loadView('collection::pdf.collections_deposits_per_A', $this->base)
                        // ->setPaper("legal", 'Landscape');
                            ->setPaper(array(0,0,$convert_width,$convert_height), 'Landscape');
                        }elseif ($typex === 'type_B') {
                            $pdf = PDF::loadView('collection::pdf.collections_deposits_per_B', $this->base)
                        // ->setPaper("legal", 'Landscape');
                            ->setPaper(array(0,0,$convert_width,$convert_height), 'Landscape');
                        }elseif ($typex === 'type_C' || $typex === 'type_D') {
                            $pdf = PDF::loadView('collection::pdf.collections_deposits_per_CD', $this->base)
                        // ->setPaper("legal", 'Landscape');
                            ->setPaper(array(0,0,$convert_width,$convert_height), 'Landscape');
                        }elseif ($typex === 'type_html_A' ) {
                            return view('collection::html.collections_deposits_A',$this->base)->with('base', $this->base);
                        }
                    }
                }
            } else {
                if($request['type'] == 2 && (is_null($typex) || $typex == "")){
                    $pdf = PDF::loadView('collection::pdf.collections_deposits_rows_bts', $this->base)
                    ->setPaper("$paper_size", 'portrait');
                }else{
                    if($typex === 'type_A'){
                        $pdf = PDF::loadView('collection::pdf.collections_deposits_per_A', $this->base)
                    // ->setPaper("legal", 'Landscape');
                        ->setPaper("A3", 'Landscape');
                    }elseif ($typex === 'type_B') {
                        $pdf = PDF::loadView('collection::pdf.collections_deposits_per_B', $this->base)
                    // ->setPaper("legal", 'Landscape');
                        ->setPaper("A3", 'Landscape');
                    }elseif ($typex === 'type_C' || $typex === 'type_D') {
                        $pdf = PDF::loadView('collection::pdf.collections_deposits_per_CD', $this->base)
                    // ->setPaper("legal", 'Landscape');
                        ->setPaper("A3", 'Landscape');
                    }elseif ($typex === 'type_html_A' ) {
                        return view('collection::html.collections_deposits_A',$this->base)->with('base', $this->base);
                    }
                }
            }
            
            return $pdf->stream();
        }else{
             $test_c = '';

                Excel::create('DAILY COLLECTIONS REPORT', function($excel) use($test_c) {
                        $excel->sheet('COLLECTIONS REPORT', function($sheet) use($test_c) {
                            $sheet->loadView('collection::excel.collections_deposits_excelx', $this->base);
                            $sheet->setFreeze('C13');
                            $sheet->mergeCells('A1:'.$this->letters($this->base['total_columns']+1).'1');
                            $sheet->mergeCells('A2:'.$this->letters($this->base['total_columns']+1).'2');
                            $sheet->mergeCells('A3:'.$this->letters($this->base['total_columns']+1).'3');
                            $sheet->mergeCells('B4:J4');
                            $sheet->setHeight(1, 16);
                            $sheet->setHeight(2, 16);
                            $sheet->setHeight(3, 16);
                            $sheet->setHeight(4, 16);
                            $sheet->setHeight(5, 16);

                            $sheet->cells('A5', function($cell) {
                                $cell->setValue('Name of Accountable Officer: '.$this->base['acctble_officer_name']->value.' - '.$this->base['acctble_officer_position']->value.'');
                                $cell->setFontSize(14);
                                $cell->setFontFamily('Arial');
                                $cell->setFontWeight('bold');
                            });

                            $sheet->cells('A1:'.$this->letters($this->base['total_columns']+1).'3', function($cells) {
                                $cells->setAlignment('center');
                            });

                            $sheet->cells('A1:'.$this->letters($this->base['total_columns']+1).'1', function($cells) {
                                $cells->setFontSize(14);
                                $cells->setFontFamily('Roman');
                                $cells->setFontWeight('bold');
                            });

                            $sheet->cells('A2:'.$this->letters($this->base['total_columns']+1).'2', function($cells) {
                                $cells->setFontSize(14);
                                $cells->setFontFamily('Times New Roman');
                                $cells->setFontWeight('bold');
                            });
                            $sheet->cells('A3:'.$this->letters($this->base['total_columns']+1).'3', function($cells) {
                                $cells->setFontSize(14);
                                $cells->setFontFamily('Times New Roman');
                                $cells->setFontWeight('bold');
                            });

                            $sheet->cells('A4:'.$this->letters($this->base['total_columns']+1).'4', function($cells) {
                                $cells->setFontSize(14);
                                $cells->setFontFamily('Arial');
                                $cells->setFontWeight('bold');
                            });

                            $sheet->cells('C10:'.$this->letters($this->base['total_columns']+1).'11', function($cells) {
                                $cells->setAlignment('center');
                            });

                            $sheet->setWidth('A', 15);
                            $sheet->setWidth('B', 30);
                            /* END HEADER*/

                        });
                    })->export('xls');
        }
    }

    public function receipt(Request $request, $id)
    {
        /*
        PLEASE NOTE PAPER CONFIGURATION FOR FORM 51
        1. Print button
        2. Select Printer
        3. Properties
        4. User Defined Paper tab
        5. Form 51 <NEW NAME>
        6. Paper Size
            Width: 4.80 inches
            Height: 8.50 inches
            NO MARGIN
        */

        /*
        PLEASE NOTE PAPER CONFIGURATION FOR FORM 56
        1. Print button
        2. Select Printer
        3. Properties
        4. User Defined Paper tab
        5. Form 56 <NEW NAME>
        6. Paper Size
            Width: 5.10 inches
            Height: 10.30 inches
            NO MARGIN
        */
        $this->base['receipt'] = Receipt::whereId($id)->first();

        # Update printed flag
        $this->base['receipt']->is_printed = 1;
        $this->base['receipt']->save();

        $this->base['total_words'] = 0;
        $items = $this->base['receipt']->items;
        foreach ($items as $item) {
            $this->base['total_words'] += $item->value;
        }
        $this->base['total_words'] = convert_number_to_words(number_format($this->base['total_words'], 2, '.', ''));
        $pdf = new PDF;
        if ($this->base['receipt']->af_type == 1) {
            $customPaper = array(0,0,350,600);
            $pdf = PDF::loadView('collection::pdf/form_51', $this->base)
                ->setPaper($customPaper);
        } else {
            $customPaper = array(0,0,370,690);
            $pdf = PDF::loadView('collection::pdf/form_56', $this->base)
                ->setPaper($customPaper);
        }

        return @$pdf->stream();
    }

    private function convert_number_to_words($number) {
        $num = $this->convert($number);
        $num = (strstr($num, 'pesos')) ? $num : $num . ' pesos';
        return $num .= ' only';
    }

    private function convert($number) {
        $hyphen      = '-';
        $conjunction = ' ';
        $separator   = ' ';
        $negative    = 'negative ';
        $decimal     = ' pesos and ';
        $dictionary  = array(
            '00'                => 'zero',
            '01'                => 'one',
            '02'                => 'two',
            '03'                => 'three',
            '04'                => 'four',
            '05'                => 'five',
            '06'                => 'six',
            '07'                => 'seven',
            '08'                => 'eight',
            '09'                => 'nine',
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'forty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . $this->convert(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
            $fraction = str_pad($fraction,  2, 0);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                // dd(number_format($number, 2));
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convert($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convert($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convert($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction) && $fraction != 0) {
            $string .= $decimal;
            $string .= $this->convert($fraction) . ' centavos';
        }

        return $string;
    }

    private function format_sort_af($form, $rcpt_acct_af, $date_start, $date_end, $fund=null) {
        // orig, working met, partially...
        // $existing_rcpt = Serial::where('serial_current', '<>', 0)
        //     ->where('acctble_form_id', '=', $form)
        //     ->where('acct_cat_id', '=', $fund)
        //     ->get();

        if($fund == 1) {
            // combine BTS and Gen. Fund
            // in Gen. Fund report
            $existing_rcpt = Serial::where('acctble_form_id', '=', $form)
                ->where(function($q) {
                    $q->where('acct_cat_id', '=', 1)
                        ->orWhere('acct_cat_id', '=', 2);
                })
                ->get();
        } else {
            $existing_rcpt = Serial::where('acctble_form_id', '=', $form)
                ->where('acct_cat_id', '=', $fund)
                ->get();
        }

        # list of accountable forms unused
        $rcpt_list = array();
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

                // if(!empty($after_zero_currnt)) {
                //     if(($er->serial_current == 0 && Carbon::parse($after_zero_currnt->date_of_entry)->format('Y-m-d') > Carbon::parse($date_end)->format('Y-m-d'))) {
                //         continue;
                //     }
                // } else if(empty($after_zero_currnt)) {
                //     continue;
                // } else if($er->serial_current <> 0) {
                //     continue;
                // } else {
                //     break;
                // }

                if (!array_key_exists($er->id, $rcpt_acct_af)) {
                    //last receipt
                    // $last_receipt = Receipt::where('report_date','<', $date_start)
                    //     ->where('col_serial_id','=', $er->id)
                    //     ->where('is_printed', '=', 1)
                    //     // ->where('af_type', $rcpt_acct_af)
                    //     ->orderBy('id','DESC')
                    //     ->first();

                    $receipt_before = Receipt::select(db::raw('MAX(serial_no) as last_mnth_issued'))
                        // ->whereMonth('report_date','<', Carbon::parse($date_start)->format('m'))
                        ->where('report_date','<', Carbon::parse($date_start))
                        ->where('col_serial_id','=', $er->id)
                        ->where('is_printed', '=', 1)
                        ->where('af_type', $form)
                        ->orderBy('serial_no', 'ASC')
                        ->first();

                    // $serial_end = $last_receipt ? $last_receipt->serial_no + 1 : $er->serial_current;

                    // old
                    // $beginning_qty = $ending_qty = ($er->serial_end - $serial_end) + 1;
                    // $beginning_first = $ending_first = $serial_end;
                    // $beginning_last = $ending_last = $er->serial_end;

                    $beginning_first = $ending_first = count($receipt_before) > 0 && $receipt_before->last_mnth_issued !== null ? $receipt_before->last_mnth_issued + 1 : $er->serial_begin;
                    // $beginning_first = $ending_first = $er->serial_begin;                        
                    $beginning_last = $ending_last = $er->serial_end;
                    // $beginning_qty = $ending_qty = count($receipt_before) > 0 && $receipt_before->last_mnth_issued != null ? ($er->serial_end - $receipt_before->last_mnth_issued) + 1 : ($er->serial_end - $er->serial_begin) + 1;
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

            $src = (isset($serial->municipality)) ? $serial->municipality->name : '';
            sort($rcpt['serials']);

            $receipt_first = '';
            $receipt_last = '';
            $receipt_qty = '';

            $receipt_before = Receipt::select(db::raw('MAX(serial_no) as last_mnth_issued'))
                // ->whereMonth('report_date','<', Carbon::parse($date_start)->format('m'))
                ->where('report_date','<', Carbon::parse($date_start))
                ->where('col_serial_id','=', $serial->id)
                ->where('is_printed', '=', 1)
                ->where('af_type', $form)
                ->orderBy('serial_no', 'ASC')
                ->first();

            $receipts_min = Receipt::select(db::raw('MIN(serial_no) as min_serial_no'))
                ->where('report_date','>=', $date_start)
                ->where('report_date','<=', $date_end)
                ->where('col_serial_id','=', $serial->id)
                ->where('is_printed', '=', 1)
                ->where('af_type', $form)
                ->orderBy('serial_no', 'ASC')
                ->get();

            $receipts_max = Receipt::select(db::raw('MAX(serial_no) as max_serial_no'))
                ->where('report_date','>=', $date_start)
                ->where('report_date','<=', $date_end)
                ->where('col_serial_id','=', $serial->id)
                ->where('is_printed', '=', 1)
                ->where('af_type', $form)
                ->orderBy('serial_no', 'ASC')
                ->get();

            $issued_first = $receipts_min[0]->min_serial_no;
            $issued_last = $receipts_max[0]->max_serial_no;
            $issued_qty = ($issued_last - $issued_first) + 1 ;

            $gt_et_start = true;
            $lt_et_end = (strtotime($serial->date_added) == strtotime($date_end));
            if ($form == 2) {
                # For Form 56
                $idx = $serial->municipality->name . $serial->serial_begin;
            } else {
                # Form 51
                $idx = $serial->serial_begin;
            }

            // $beginning_first = $issued_first;
            // $beginning_last = $serial->serial_end;
            // $beginning_qty = ($beginning_last - $beginning_first) + 1;

            $beginning_first = $ending_first = count($receipt_before) > 0 && $receipt_before->last_mnth_issued !== null ? $receipt_before->last_mnth_issued + 1 : $serial->serial_begin;
            // $beginning_first = $ending_first = $serial->serial_begin;   
            $beginning_last = $ending_last = $serial->serial_end;
            $beginning_qty = ($beginning_last - $beginning_first) + 1;

            if ($gt_et_start && $lt_et_end) {
                $receipt_first = $serial->serial_begin;
                $receipt_last = $serial->serial_end;
                $receipt_qty = ($receipt_last - $receipt_first) + 1;

                // $beginning_first = '';
                // $beginning_last = '';
                // $beginning_qty = '';
            }

            // $ending_first = '';
            // $ending_last = '';
            // $ending_qty = '';

            // if ($serial->serial_current != 0 || ($serial->serial_current == 0 && $serial->updated_at <= $date_start && $serial->updated_at <= $date_end)) {
            if ($serial->serial_current != 0 || $issued_last != 0) { // 5996650
                // old
                // $ending_first = $issued_last == $serial->serial_end ? $issued_last : $issued_last + 1;
                // $ending_last = $serial->serial_end;
                // $ending_qty = $issued_last == $serial->serial_end ? '0' : ($ending_last - $ending_first) + 1;

                $beginning_first = count($receipt_before) > 0 && $receipt_before->last_mnth_issued !== null ? intval($receipt_before->last_mnth_issued) + 1 : $serial->serial_begin;
                $beginning_qty = (intval($beginning_last) - intval($beginning_first)) + 1;
                // $issued_last = $serial->serial_end == $issued_last ? $issued_last : intval($issued_last) + 1;
                $ending_last = $serial->serial_end;
                // $ending_first = $issued_qty > 0 && $issued_qty != '' ? ($issued_last == $serial->serial_end ? $issued_last : $issued_last + 1) : (count($receipt_before) > 0 && $receipt_before->last_mnth_issued !== null ? $receipt_before->last_mnth_issued + 1 : $serial->serial_begin);
                $ending_first = $issued_last == $serial->serial_end ? $issued_last : $issued_last + 1;
            } 
            $ending_qty = $ending_last == $issued_last ? 0 : ($ending_last - $ending_first) + 1;

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
                'end_qty' => $ending_qty,
                'end_from' => $ending_first,
                'end_to' => $ending_last,
            );
        }
        ksort($rcpt_acct);
        return $rcpt_acct;
    }

    public function set_paper_size($total_columns){
        $paper_size = 'letter';
        if($total_columns >= 14 ){
            $paper_size = 'legal';
        }
        return $paper_size;
    }

    public function letters($letter = null){
            $letters = [
                        1 => "A",
                        2 => "B",
                        3 => "C",
                        4 => "D",
                        5 => "E",
                        6 => "F",
                        7 => "G",
                        8 => "H",
                        9 => "I",
                        10 => "G",
                        11 => "K",
                        12 => "L",
                        13 => "M",
                        14 => "N",
                        15 => "O",
                        16 => "P",
                        17 => "Q",
                        18 => "R",
                        19 => "S",
                        20 => "T",
                        21 => "U",
                        22 => "V",
                        23 => "W",
                        24 => "X",
                        25 => "Y",
                        26 => "Z"
                      ];

            if($letter<=26){
                $l = $letters[$letter];
            }else{
                $bl = round( $letter/26 , 0) ;
                if($letter%26 == 0){
                    $bl = $bl-1;
                }
                $lb = $letters[$bl];
                $ll = ( $letter%26 ) == 0 ? 26 : ( $letter%26 ) ;
                $lll = $letters[$ll];
                $l = $lb.$lll;
            }
            return $l;
    }

    public function rpr_report_edit(Request $request) {
        $form_56 = 2;
        $date_start = date('Y-m-d', strtotime($request['start_date']));
        $date_end = date('Y-m-d', strtotime($request['end_date']));
        $report_date = date('F d, Y', strtotime($request['report_date']));

        $receipts = Receipt::with('items')
            ->with('F56Detailmny')
            ->with('F56Detailmny.TDARP')
            ->with('F56Detailmny.TDARPX') 
            ->with('F56Detailmny.TDARPX.barangay_name')
            ->with('F56Detailmny.F56Type') 
            ->where('report_date','>=', $date_start)
            ->where('report_date','<=', $date_end)
            ->where('is_printed', '=', 1)
            ->where('af_type', '=', $form_56)
            ->where('col_municipality_id', '=', $request['municipality'])
            ->where('remarks', 'not like', '%paid under protest%')
            ->where('remarks', 'not like', '%held in trust%')
            ->where('bank_remark', 'not like', '%paid under protest%')
            ->where('bank_remark', 'not like', '%held in trust%')
            ->orderBy('serial_no', 'ASC')
            ->get();

        $class_amt = array();
        $classes = F56Type::get();
        foreach ($classes as $class) {
            $class_amt[$class->id] = array(
                'basic_current' => 0,
                'basic_discount' => 0,
                'basic_previous' => 0,
                'basic_penalty_current' => 0,
                'basic_penalty_previous' => 0,
                'basic_adv' => 0,
                'basic_adv_discount' => 0,
                'basic_prior_1991' => 0,
                'basic_prior_penalty_1991' => 0,
                'basic_prior_1992' => 0,
                'basic_prior_penalty_1992' => 0,
            );
        }

        $current = Carbon::parse($request['start_date'])->format('Y');
        $prior_start = Carbon::parse($request['start_date'])->subYears(2)->format('Y');
        $preceeding = Carbon::parse($request['start_date'])->subYear()->format('Y');
        $advance_yr = Carbon::parse($request['start_date'])->addYear()->format('Y');

        $this->base['prior_start'] = $prior_start;
        $this->base['preceeding'] = $preceeding;
        $this->base['advance_yr'] = $advance_yr;
        $this->base['current'] = $current;
        $this->base['municipality'] = Municipality::whereId($request['municipality'])->first();

        $report_exist = RptSefAdjustments::where('municipality', '=', $request['municipality'])
            ->where('report_no', '=', $request['report_no'])
            ->with('report_sef_items')
            ->with('report_basic_items')
            ->get();
            
        if($request['isEdit'] == 0 and count($report_exist) > 0){
            return 'Report '.(isset($request['report_no']) ? $request['report_no'] : "").' of '.( isset($this->base['municipality']->name) ? $this->base['municipality']->name : '').' already exist!';
        }

        $this->base['sef_exist'] = $report_exist;

        foreach ($receipts as $rcpt_index => $receipt) {
            if ($receipt->is_cancelled) {
                continue;
            }

            if ($receipt->F56Detailmny()->count() > 0) {
                foreach ($receipt->F56Detailmny as $f56_detail){
                    $index = $f56_detail->col_f56_type_id;
                    if($f56_detail->period_covered == $current) {
                        $class_amt[$index]['basic_current'] += round(floatval($f56_detail->basic_current), 2);
                        $class_amt[$index]['basic_discount'] += round(floatval($f56_detail->basic_discount), 2);
                        $class_amt[$index]['basic_penalty_current'] += round(floatval($f56_detail->basic_penalty_current), 2);
                    }

                    if($f56_detail->period_covered == $preceeding) {
                        $class_amt[$index]['basic_previous'] += round(floatval($f56_detail->basic_previous), 2);
                        $class_amt[$index]['basic_penalty_previous'] += round(floatval($f56_detail->basic_penalty_previous), 2);
                    }
                    
                    if($f56_detail->period_covered >= $advance_yr) {
                        // $class_amt[$index]['basic_adv'] += number_format(($f56_detail->tdrp_assedvalue*.01), 2);
                        // $class_amt[$index]['basic_adv_discount'] += number_format((($f56_detail->tdrp_assedvalue*.01)*.10), 2);

                        $class_amt[$index]['basic_adv'] += round(floatval($f56_detail->basic_current), 2);
                        $class_amt[$index]['basic_adv_discount'] += round(floatval($f56_detail->basic_discount), 2);
                    }

                    if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered >= 1992) {
                        $class_amt[$index]['basic_prior_1992'] += round(floatval($f56_detail->basic_previous), 2);
                        $class_amt[$index]['basic_prior_penalty_1992'] += round(floatval($f56_detail->basic_penalty_previous), 2);
                    }

                    if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered <= 1991) {
                        $class_amt[$index]['basic_prior_1991'] += round(floatval($f56_detail->basic_previous), 2);
                        $class_amt[$index]['basic_prior_penalty_1991'] += round(floatval($f56_detail->basic_penalty_previous), 2);
                    }
                }
            }
        }

        $date_end = new Carbon($date_end);
        $this->base['f56_type'] = F56Type::get();
        $this->base['date_range'] = date('F d, Y', strtotime($date_start)) .' to '. $date_end->format('F d, Y') .'.';
        $this->base['receipts'] = $receipts;
        $this->base['class_amt'] = $class_amt;
        $this->base['report_date'] = $report_date;
        $this->base['report_no'] = $request['report_no'];

        // empty receipts
        if(count($receipts) == 0)
            return 'No transaction for '. date('F d, Y', strtotime($date_start)) .' to '. date('F d, Y', strtotime($date_end)) .'.';
        // process 

        $total_basic_current = 0;
        $total_basic_discount = 0;
        $total_basic_previous = 0;
        $total_basic_penalty_current = 0;
        $total_basic_penalty_previous = 0;
        $total_basic_gross = 0;
        $total_basic_net = 0;
        $gt_gross = 0;
        $gt_net = 0;
        $counter = 0;

        // immediate preceeding year
        $total_preceed = 0;

        // prior years
        $total_prior_1992 = 0; // for 1992 and above
        $total_prior_1991 = 0; // for 1991 and below
        $total_penalty_prior_1992 = 0;
        $total_penalty_prior_1991 = 0;

        // advance
        $total_adv = 0;
        $total_adv_discount = 0;

        foreach($receipts as $receipt) {
            $rcpt_done = 0;
            $entry_date = \Carbon\Carbon::parse($receipt->date_of_entry);
        }
        
        return $this->base;
    }

    public function rpt_report_submit(Request $req) {
        $request = new Request($req->all());
        $data = $this->rpr_report_edit($request);
        $this->base['mun'] = Municipality::find($req->municipality);
        if(isset($req['save_report'])){
          $equivalents = [
            'report_no' => $req->report_no,
            'municipality' => $req->municipality
          ];
          $values = [
            'start_date' => Carbon::parse($req->start_date)->format('Y-m-d'),
            'end_date' => Carbon::parse($req->end_date)->format('Y-m-d'),
            'report_date' => Carbon::parse($req->report_date)->format('Y-m-d')
          ];
          if(isset($req->report_id) || $req->report_id != ""){
              $equivalents = [ 'id' => $req->report_id ];
              $values['report_no'] = $req->report_no;
              $values['municipality'] = $req->municipality;
          }
          
        $insert = RptSefAdjustments::updateOrCreate($equivalents, $values);

        RptSefAdjustmentsItems::updateOrCreate(
            [
                'col_rpt_sef_adjustments_id' => $insert->id,
            ],
            [
                'prv_adv_amt' => $req->sef_prv_adv_amt,
                'prv_adv_discount' => $req->sef_prv_adv_discount,
                'prv_curr_amt' => $req->sef_prv_amt,
                'prv_curr_discount' => $req->sef_prv_discount,
                'prv_prev_amt' => $req->sef_prev_prv_amt,
                'prv_1992_amt' => $req->sef_prv_prior_1992_amt,
                'prv_1991_amt' => $req->sef_prv_prior_1991_amt,
                'prv_penalty_curr' => $req->sef_prv_penalty,
                'prv_penalty_prev' => $req->sef_prev_prv_penalty,
                'prv_penalty_1992' => $req->sef_prv_prior_1992_penalties,
                'prv_penalty_1991' => $req->sef_prv_prior_1991_penalties,
                'mnc_adv_amt' => $req->sef_mnc_adv_amt,
                'mnc_adv_discount' => $req->sef_mnc_adv_discount,
                'mnc_curr_amt' => $req->sef_mncpl_crnt,
                'mnc_curr_discount' => $req->sef_mncpl_dscnt,
                'mnc_prev_amt' => $req->sef_mncpl_prev,
                'mnc_1992_amt' => $req->sef_mnc_prior_1992_amt,
                'mnc_1991_amt' => $req->sef_mnc_prior_1991_amt,
                'mnc_penalty_curr' => $req->sef_mncpl_pen_crnt,
                'mnc_penalty_prev' => $req->sef_mncpl_pen_crnt_prev,
                'mnc_penalty_1992' => $req->sef_mnc_prior_1992_penalties,
                'mnc_penalty_1991' => $req->sef_mnc_prior_1991_penalties,
            ]
        );

        RptBasicAdjustmentsItems::updateOrCreate(
            [
                'col_rpt_sef_adjustments_id' => $insert->id,
            ],
            [
                'prv_adv_amt' => $req->prv_adv_ammount,
                'prv_adv_discount' => $req->prv_adv_discount,
                'prv_curr_amt' => $req->prv_crnt_ammount,
                'prv_curr_discount' => $req->prv_crnt_discount,
                'prv_prev_amt' => $req->prv_prvious_ammount,
                'prv_1992_amt' => $req->prv_prior_1992_amt,
                'prv_1991_amt' => $req->prv_prior_1991_amt,
                'prv_penalty_curr' => $req->prv_pnalties_crnt,
                'prv_penalty_prev' => $req->prv_pnalties_prvious,
                'prv_penalty_1992' => $req->prv_prior_1992_penalties,
                'prv_penalty_1991' => $req->prv_prior_1991_penalties,
                'mnc_adv_amt' => $req->mnc_adv_ammount,
                'mnc_adv_discount' => $req->mnc_adv_discount,
                'mnc_curr_amt' => $req->munshare_basic_current,
                'mnc_curr_discount' => $req->munshare_basic_discount,
                'mnc_prev_amt' => $req->munshare_basic_previous,
                'mnc_1992_amt' => $req->mnc_prior_1992_amt,
                'mnc_1991_amt' => $req->mnc_prior_1991_amt,
                'mnc_penalty_curr' => $req->munshare_basic_penalty_current,
                'mnc_penalty_prev' => $req->munshare_basic_penalty_previous,
                'mnc_penalty_1992' => $req->mnc_prior_1992_penalties,
                'mnc_penalty_1991' => $req->mnc_prior_1991_penalties,
                'brgy_adv_amt' => $req->brgy_adv_ammount,
                'brgy_adv_discount' => $req->brgy_adv_discount,
                'brgy_curr_amt' => $req->brgyshare_basic_current,
                'brgy_curr_discount' => $req->brgyshare_basic_discount,
                'brgy_prev_amt' => $req->brgyshare_basic_previous,
                'brgy_1992_amt' => $req->brgy_prior_1992_amt,
                'brgy_1991_amt' => $req->brgy_prior_1991_amt,
                'brgy_penalty_curr' => $req->brgyshare_basic_penalty_current,
                'brgy_penalty_prev' => $req->brgyshare_basic_penalty_previous,
                'brgy_penalty_1992' => $req->brgy_prior_1992_penalties,
                'brgy_penalty_1991' => $req->brgy_prior_1991_penalties,
            ]
        );
        
        return redirect()->route('report.real_property')->with('isSaved', 'Report '.$req->report_no.' of '.$this->base['mun']->name.' succesfully saved!');
        }
        $merged = is_array($data) ? array_merge($data, $req->all()) : $req->all();

        $this->base['merged'] = $merged;

        if($req['btn_pdf'] == 'button' || $req['btn'] == 'rpt_mun_report_protest') {
            $pdf = PDF::loadView('collection::pdf.new_rpt_abstract.real_property', $this->base)->setPaper(array(0,0,612,936), 'landscape');
            
            return $pdf->stream();
            // $html = view('collection::docx.rpt_test', $this->base)->render();
            // $phpWord = new \PhpOffice\PhpWord\PhpWord();
            // $doc= new DOMDocument();
            // $doc->loadHTML($html);

            // $section = $phpWord->addSection([
            //     'pageSizeH' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(14),
            //     'pageSizeW' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(8.5),
            //     'orientation' => 'landscape',
            // ]);
            
            // \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
            // \PhpOffice\PhpWord\Shared\Html::addHtml($section, $doc->saveXML(),true);

            // // Saving the document as OOXML file...
            // $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            // $objWriter->save(storage_path($req->report_no.'.docx'));
            // return response()->download(storage_path($req->report_no.'.docx'));
        } else if($req['btn_pdf'] == 'rpt_mun_report_collections' || $req['btn'] == 'rpt_mun_report_protest_col') {
            
            $pdf = PDF::loadView('collection::pdf.new_rpt_abstract.real_property_collections', $this->base)->setPaper(array(0,0,612,936), 'landscape');
            return $pdf->stream();
        } else if($req['btn_pdf'] == 'rpt_mun_report_summary_disposition' || $req['btn'] == 'rpt_mun_report_protest_sd') {
            $pdf = PDF::loadView('collection::pdf.new_rpt_abstract.real_property_summ_dispo', $this->base)->setPaper(array(0,0,612,936), 'landscape');
            return $pdf->stream();
        }
    }


    public function rpt_report_search($report_num, $municipality)
    {
        $report = RptSefAdjustments::where('report_no', '=', $report_num)
            ->where('municipality', '=', $municipality)
            ->get();
        if(count($report) == 0){
            return response()->json('Report Not Found', 500);
        }
        return $report;

    }

    public function getReportsByReportNumber(Request $request)
    {
        $year = isset($request['year']) ? $request['year'] : date('Y');
        $reports = RptSefAdjustments::whereYear('report_date', '=', $year)
                                        ->join('col_municipality', 'col_rpt_sef_adjustments.municipality',  '=', 'col_municipality.id')
                                        ->select('col_rpt_sef_adjustments.*', 'col_municipality.name as municipality_name')
                                        ->get();
        return Datatables::of(collect($reports))->make(true);
    }

}