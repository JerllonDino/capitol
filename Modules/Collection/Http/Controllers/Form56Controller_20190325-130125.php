<?php

namespace Modules\Collection\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbsController;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use Modules\Collection\Entities\Customer;
use Modules\Collection\Entities\Form;
use Modules\Collection\Entities\Municipality;
use Modules\Collection\Entities\Barangay;
use Modules\Collection\Entities\Receipt;
use Modules\Collection\Entities\ReceiptItems;
use Modules\Collection\Entities\Serial;
use Modules\Collection\Entities\WeekdayHoliday;
use Modules\Collection\Entities\TransactionType;
use Modules\Collection\Entities\CollectionRate;
use Modules\Collection\Entities\F56Type;
use Modules\Collection\Entities\F56Detail;
use Modules\Collection\Entities\F56TDARP;
use Modules\Collection\Entities\F56PreviousReceipt;
use Modules\Collection\Entities\ReceiptItemDetail;
use Modules\Collection\Entities\AdaSettings;
use Modules\Collection\Entities\SandGravelTypes as sg_types;
use Modules\Collection\Entities\SGbooklet;
use Modules\Collection\Entities\ReportOfficers;
use Carbon\Carbon,PDF;

class Form56Controller extends Controller
{
    protected $receipt;

    public function __construct(Request $request, Receipt $receipt)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'FORM56 Land Tax Collections';
        $this->base['ada_settings'] = AdaSettings::get();
        $this->base['host'] = request()->getHttpHost();
    }


    public function index()
    {
        $this->base['f56_types'] = F56Type::get();
        $this->base['sub_header'] = 'FORM56 Land Tax';
        $this->base['form'] = Form::all();
        $this->base['transaction_type'] = TransactionType::all();
        $this->base['municipalities'] = Municipality::all()->toarray();
        $this->base['user'] = Session::get('user');
        $this->base['sandgravel_types'] = sg_types::all();
        return view('collection::form56.index')->with('base', $this->base);
    }

    public function view($id)
    {

        $this->base['receipt'] = Receipt::find($id);
        $this->base['f56_types'] = F56Type::get();
        $this->base['sub_header'] = 'FORM56 Land Tax';
        $this->base['form'] = Form::all();
        $this->base['transaction_type'] = TransactionType::all();
        $this->base['municipalities'] = Municipality::all()->toarray();

        $this->base['user'] = Session::get('user');
        $this->base['sandgravel_types'] = sg_types::all();

        return view('collection::form56.view')->with('base', $this->base);
    }


     public function edit($id)
    {


        $this->base['f56_types'] = F56Type::get();
        $this->base['sub_header'] = 'FORM56 Land Tax';
        $this->base['form'] = Form::all();
        $this->base['transaction_type'] = TransactionType::all();
        $this->base['municipalities'] = Municipality::all()->toarray();
        $this->base['user'] = Session::get('user');
        $this->base['sandgravel_types'] = sg_types::all();
        $this->base['receipt'] = Receipt::find($id);
        $this->base['receipt_id'] = $id;
        $this->base['barangay'] = Barangay::where('municipality_id','=',$this->base['receipt']->col_municipality_id)->get();


        return view('collection::form56.edit')->with('base', $this->base);
    }



    public function store(Request $request)
    {

        $serial = Serial::whereId($request['serial_id'])->first();
            $filter = [
            'user_id' => 'required|numeric',
            'date' => 'required',
            'serial_id' => 'required|numeric',
            'customer' => 'required',
            'transaction_type' => 'required',
            'municipality' => 'required',
            'amount.*' => 'required|not_in:0',
            ];


        $receipt_checker = Receipt::where('serial_no','=',$serial->serial_current)->first();
        $current = $serial->serial_current;
        if ($receipt_checker) {
             Session::flash('danger', ['This SERIAL is already in use Please contact ADMINISTRATOR: '.$current]);
            return back();
        }


        $validator = Validator::make($request->all(), $filter);
        if ($validator->fails()) {
            return redirect()->route('form56.index')
            ->withErrors($validator);
        } elseif (in_array('', $request['account_id'])) {
            $validator->getMessageBag()
            ->add('account', 'An account field is empty or not identified');
            return redirect()->route('form56.index')
            ->withErrors($validator);
        } elseif ($serial->serial_current == 0) {
            $validator->getMessageBag()
            ->add('serial', 'Series `'.$serial->serial_begin.'-'.$serial->serial_end.'` is finished. Please use another serial.');
            return redirect()->route('form56.index')
            ->withErrors($validator);
        }

        # Add payor if not existing
        $payor_id = 0;
        if (empty($request['customer_id'])) {
            $payor = Customer::withTrashed()->where('name',$request['customer'])->first();
            if (!empty($payor)) {
                if(!$payor->customer_type_id){
                    $payor->update([
                        'customer_type_id' => $request['customer_type'],
                    ]);
                }
                $payor_id = $payor->id;
                $payor->restore();
            } else {
                $payor_id = Customer::create([
                    'name' => $request['customer'],
                    'customer_type_id' => $request['customer_type'],
                    'address' => '',
                    ]);
                $payor_id = $payor_id->id;
            }
        } else {
            $payor_id = $request['customer_id'];
        }
        $is_printed = 1;
        $report_datex = new Carbon($request['date']);

        $dt_3pm = new Carbon($report_datex->format('Y-m-d'));

        if($report_datex->timestamp <= $dt_3pm->addHours(15)->timestamp   ){
            $report_date = $report_datex->format('Y-m-d');
        }else{
            $got_valid_date = false;
            $wh = WeekdayHoliday::where('date', $report_datex->format('Y-m-d'))->first();
            $rday = $report_datex->format('Y-m-d');
                while (!$got_valid_date) {
                    $rday =  $report_datex->addDay();
                    if ( $rday->format('D') == 'Sun' ||  $rday->format('D') == 'Sat' ) {
                        continue;
                    }

                    $wh = WeekdayHoliday::where('date', $rday->format('Y-m-d'))->first();
                    if ($wh != null) {
                        continue;
                    }
                    $got_valid_date = true;
                }
                $report_date = $rday;
        }

        $dtx = new Carbon;
        # Success
        $receipt = Receipt::create([
            'serial_no' => $serial->serial_current,
            'af_type' => $request['form'],
            'col_serial_id' => $request['serial_id'],
            'col_municipality_id' => (!empty($request['municipality'])) ? $request['municipality'] : '',
            'col_barangay_id' => (!empty($request['brgy'])) ? $request['brgy'] : '',
            'dnlx_user_id' => $request['user_id'],
            'ip_addr' => $request->ip(),
            'col_customer_id' => $payor_id,
            'report_date' => $report_date,
            'date_of_entry' => date('Y-m-d H:i:s', strtotime($request['date'])),
            'is_printed' => $is_printed,
            'is_cancelled' => 0,
            'cancelled_remark' => '',
            'transaction_source' => $request['transaction_source'],
            'transaction_type' => $request['transaction_type'],
            'bank_name' => (!empty($request['bank_name'])) ? $request['bank_name'] : '',
            'bank_number' => (!empty($request['bank_number'])) ? $request['bank_number'] : '',
            'bank_date' => (!empty($request['bank_date'])) ? $request['bank_date'] : '',
            'bank_remark' => (!empty($request['bank_remark'])) ? $request['bank_remark'] : '',
            'remarks' => (!empty($request['remarks'])) ? $request['remarks'] : '',
            'client_type' => $request['customer_type'],
            ]);
        Session::put('serial_id', $request['serial_id']);



        # Update Serial
        $serial->serial_current = ($serial->serial_current == $serial->serial_end) ? 0 : $serial->serial_current + 1;
        $serial->save();

        if($request['prev_receipt_no'] != '' && $request['prev_date'] != '' &&  $request['prev_for_the_year'] != '' ){
            F56PreviousReceipt::updateOrCreate([
                                                'col_receipt_id' => $receipt->id,

                                        ],[
                                            'col_receipt_no' => $request['prev_receipt_no'],
                                            'col_receipt_date' => $request['prev_date'],
                                            'col_receipt_year' => $request['prev_for_the_year'],
                                        ]);
        }
        


        foreach ($request['account_id'] as $i => $ai) {
                        $rate_type = ($request['account_type'][$i] == 'title') ? 'col_acct_title_id' : 'col_acct_subtitle_id';
                        $rate = CollectionRate::where($rate_type, '=', $request['account_id'][$i])->first();

                        $share_provincial = $request['amount'][$i];
                        $share_municipal = 0;
                        $share_barangay = 0;
                        if (!empty($rate) && $rate->is_shared == 1) {
                            $share_provincial = bcdiv($request['amount'][$i] * ($rate->sharepct_provincial / 100), 1, 2);
                            $share_municipal = bcdiv($request['amount'][$i] * ($rate->sharepct_municipal / 100), 1, 2);
                            $share_barangay = bcdiv($request['amount'][$i] * ($rate->sharepct_barangay / 100), 1, 2);

                            $total_shared = $share_provincial + $share_municipal + $share_barangay;
                            if ($total_shared !== $request['amount'][$i]) {
                                if($total_shared > $request['amount'][$i]){
                                    $diffx = $total_shared - $request['amount'][$i];
                                    $share_barangay = $share_barangay - $diffx;
                                    // dd('test 1  = '.$diffx.'('.$total_shared.' = '.$request['amount'][$i].'--'.$share_provincial.'--'.$share_municipal.'--'.$share_barangay);
                                }elseif($total_shared < $request['amount'][$i]){
                                    $diffx =  $request['amount'][$i] - $total_shared;
                                    $share_provincial = $share_provincial + $diffx;
                                    // $total_shared2 = $share_provincial + $share_municipal + $share_barangay;
                                    // dd($total_shared2.'test 2  = '.$diffx.'('.$total_shared.' = '.$request['amount'][$i].'--'.$share_provincial.'--'.$share_municipal.'--'.$share_barangay);
                                }

                            }
                        }

                        $item_value = $request['amount'][$i];
                        if ($request['account_id'][$i] == 4 && $request['account_type'][$i] == 'title') {
                            $less_amt = 0;
                            // for receipt_item_detail
                            $colrate = CollectionRate::whereId($request['account_rate'][$i])->first();
                            if ($colrate == null) {
                                continue;
                            }
                            if ($request['account_rate'][$i] == 2) {
                                $less_amt = $request['sand_abc'] * $colrate->value;
                            } elseif ($request['account_rate'][$i] == 3) {
                                $less_amt = $request['sand_sandgravel'] * $colrate->value;
                            } elseif ($request['account_rate'][$i] == 4) {
                                $less_amt = $request['sand_boulders'] * $colrate->value;
                            } elseif ($request['account_rate'][$i] == 5) {
                                $less_amt = $request['sand_sandgravelprocessed'] * $colrate->value;
                            }
                            $item_value = $request['amount'][$i] - $less_amt;
                        }

                        $receipt_item = ReceiptItems::create([
                            'col_receipt_id' => $receipt->id,
                            'nature' => $request['nature'][$i],
                            'col_acct_title_id' => ($request['account_type'][$i] == 'title') ? $request['account_id'][$i] : 0,
                            'col_acct_subtitle_id' => ($request['account_type'][$i] == 'subtitle') ? $request['account_id'][$i] : 0,
                            'value' => $item_value,
                            'share_provincial' => $share_provincial,
                            'share_municipal' => $share_municipal,
                            'share_barangay' => $share_barangay,
                            ]);

                        // for receipt_item_detail
                        $colrate = CollectionRate::whereId($request['account_rate'][$i])->first();
                        if ($colrate == null) {
                            continue;
                        }
                        ReceiptItemDetail::create([
                            'col_receipt_item_id' => $receipt_item->id,
                            'col_collection_rate_id' => $request['account_rate'][$i],
                            'label' => $colrate->label,
                            'value' => $colrate->value,
                            'sched_is_perunit' => $colrate->sched_is_perunit,
                            'sched_unit' => $colrate->sched_unit,
                            ]);
        }

          $data = array();
            foreach($request['tdarpno'] as $i => $tan) {
                $detail = F56Detail::create([
                                            'col_receipt_id' => $receipt->id,
                                            'col_f56_type_id' => $request['f56_type'][$i],
                                            'owner_name' => $request['declared_owner'][$i],
                                            'tdrp_assedvalue' => $request['tdrp_assedvalue'][$i],
                                            'period_covered' => $request['period_covered'][$i],
                                            'basic_current' => $request['basic_current'][$i],
                                            'basic_discount' => $request['basic_discount'][$i],
                                            'basic_previous' => $request['basic_previous'][$i],
                                            'basic_penalty_current' => $request['basic_penalty_current'][$i],
                                            'basic_penalty_previous' => $request['basic_penalty_previous'][$i],
                                        ]);

                $row['col_f56_detail_id'] = $detail->id;
                $row['tdarpno'] = $tan;
                $row['municipality'] =  $request['municipality'];
                $row['barangay'] =  $request['tdrp_barangay'][$i];
                $row['f56_type'] = $request['f56_type'][$i];
                $row['previous_tax_type_id'] = $request['previous_tax_type']; // vague insertion
                array_push($data, $row);


            }
            F56TDARP::insert($data);

            Session::flash('info', ['Successfully created Form 56 transaction for serial: '.$receipt->serial_no]);
        return redirect()->route('form56.index');
    }

    public function update(Request $request)
    {




     $payor_id = 0;
     if (empty($request['customer_id'])) {
        $payor = Customer::where('name', '=', $request['customer'])->first();
        if (!empty($payor)) {
            if(!$payor->customer_type_id){
                $payor->update([
                    'customer_type_id' => $request['customer_type'],
                ]);
            }
            $payor_id = $payor->id;
        } else {
            $payor_id = Customer::create([
                'name' => $request['customer'],
                'customer_type_id' => $request['customer_type'],
                'address' => '',
                ]);
            $payor_id = $payor_id->id;
        }
    } else {
        $payor_id = $request['customer_id'];
    }

    $report_datex = new Carbon($request['date']);
    $dt_3pm = new Carbon($report_datex->format('Y-m-d'));
    if($report_datex->timestamp <= $dt_3pm->addHours(15)->timestamp   ){
        $report_date = $report_datex->format('Y-m-d');
    }else{
        $got_valid_date = false;
        $wh = WeekdayHoliday::where('date', $report_datex->format('Y-m-d'))->first();
        $rday = $report_datex->format('Y-m-d');
            while (!$got_valid_date) {
                $rday =  $report_datex->addDay();
                if ( $rday->format('D') == 'Sun' ||  $rday->format('D') == 'Sat' ) {
                    continue;
                }

                $wh = WeekdayHoliday::where('date', $rday->format('Y-m-d'))->first();
                if ($wh != null) {
                    continue;
                }
                $got_valid_date = true;
            }
            $report_date = $rday;
    }

        $dtx = new Carbon;
        # Success
        $receipt =  Receipt::find($request['receipt_id']);

        $receipt->col_municipality_id = $request['municipality'];
        $receipt->date_of_entry = date('Y-m-d H:i:s', strtotime($request['date']));
        $receipt->report_date = $report_date;
        $receipt->transaction_type =  $request['transaction_type'];
        $receipt->bank_name =  $request['bank_name'];
        $receipt->bank_number =  $request['bank_number'];
        $receipt->bank_date =  $request['bank_date'];
        $receipt->bank_remark =  $request['bank_remark'];


        $receipt->save();
        Session::put('serial_id', $request['serial_id']);
        if($request['prev_receipt_no'] != '' && $request['prev_date'] != '' && $request['prev_for_the_year'] != ''){
             F56PreviousReceipt::updateOrCreate([
                                                        'col_receipt_id' => $receipt->id,

                                                ],[
                                                    'col_receipt_no' => $request['prev_receipt_no'],
                                                    'col_receipt_date' => $request['prev_date'],
                                                    'col_receipt_year' => $request['prev_for_the_year'],
                                                ]);
        }
               

        foreach ($request['account_id'] as $i => $ai) {
                        $rate_type = ($request['account_type'][$i] == 'title') ? 'col_acct_title_id' : 'col_acct_subtitle_id';
                        $rate = CollectionRate::where($rate_type, '=', $request['account_id'][$i])->first();

                        $share_provincial = $request['amount'][$i];
                        $share_municipal = 0;
                        $share_barangay = 0;
                        if (!empty($rate) && $rate->is_shared == 1) {
                            $share_provincial = bcdiv($request['amount'][$i] * ($rate->sharepct_provincial / 100), 1, 2);
                            $share_municipal = bcdiv($request['amount'][$i] * ($rate->sharepct_municipal / 100), 1, 2);
                            $share_barangay = bcdiv($request['amount'][$i] * ($rate->sharepct_barangay / 100), 1, 2);

                            $total_shared = $share_provincial + $share_municipal + $share_barangay;
                            if ($total_shared !== $request['amount'][$i]) {
                                if($total_shared > $request['amount'][$i]){
                                    $diffx = $total_shared - $request['amount'][$i];
                                    $share_barangay = $share_barangay - $diffx;
                                    // dd('test 1  = '.$diffx.'('.$total_shared.' = '.$request['amount'][$i].'--'.$share_provincial.'--'.$share_municipal.'--'.$share_barangay);
                                }elseif($total_shared < $request['amount'][$i]){
                                    $diffx =  $request['amount'][$i] - $total_shared;
                                    $share_provincial = $share_provincial + $diffx;
                                    // $total_shared2 = $share_provincial + $share_municipal + $share_barangay;
                                    // dd($total_shared2.'test 2  = '.$diffx.'('.$total_shared.' = '.$request['amount'][$i].'--'.$share_provincial.'--'.$share_municipal.'--'.$share_barangay);
                                }

                            }
                        }

                        $item_value = $request['amount'][$i];
                        if ($request['account_id'][$i] == 4 && $request['account_type'][$i] == 'title') {
                            $less_amt = 0;
                            // for receipt_item_detail
                            $colrate = CollectionRate::whereId($request['account_rate'][$i])->first();
                            if ($colrate == null) {
                                continue;
                            }
                            if ($request['account_rate'][$i] == 2) {
                                $less_amt = $request['sand_abc'] * $colrate->value;
                            } elseif ($request['account_rate'][$i] == 3) {
                                $less_amt = $request['sand_sandgravel'] * $colrate->value;
                            } elseif ($request['account_rate'][$i] == 4) {
                                $less_amt = $request['sand_boulders'] * $colrate->value;
                            } elseif ($request['account_rate'][$i] == 5) {
                                $less_amt = $request['sand_sandgravelprocessed'] * $colrate->value;
                            }
                            $item_value = $request['amount'][$i] - $less_amt;
                        }
                        $receipt_item = ReceiptItems::where('col_receipt_id','=',$receipt->id)->first();
                        $receipt_item->nature = $request['nature'][$i];
                        $receipt_item->value = $item_value;
                        $receipt_item->share_provincial = $share_provincial;
                        $receipt_item->share_municipal = $share_municipal;
                        $receipt_item->share_barangay = $share_barangay;
                        $receipt_item->save();
                        // $receipt_item = ReceiptItems::create([
                        //     'col_receipt_id' => $receipt->id,
                        //     'nature' => $request['nature'][$i],
                        //     'col_acct_title_id' => ($request['account_type'][$i] == 'title') ? $request['account_id'][$i] : 0,
                        //     'col_acct_subtitle_id' => ($request['account_type'][$i] == 'subtitle') ? $request['account_id'][$i] : 0,
                        //     'value' => $item_value,
                        //     'share_provincial' => $share_provincial,
                        //     'share_municipal' => $share_municipal,
                        //     'share_barangay' => $share_barangay,
                        //     ]);

                        // for receipt_item_detail
                        $colrate = CollectionRate::whereId($request['account_rate'][$i])->first();
                        if ($colrate == null) {
                            continue;
                        }
                        $ReceiptItemDetail = ReceiptItemDetail::where('col_receipt_item_id','=',$receipt_item->id)->first();
                        $ReceiptItemDetail->label = $colrate->label;
                        $ReceiptItemDetail->value = $colrate->value;
                        $ReceiptItemDetail->save();
                        // ReceiptItemDetail::create([
                        //     'col_receipt_item_id' => $receipt_item->id,
                        //     'col_collection_rate_id' => $request['account_rate'][$i],
                        //     'label' => $colrate->label,
                        //     'value' => $colrate->value,
                        //     'sched_is_perunit' => $colrate->sched_is_perunit,
                        //     'sched_unit' => $colrate->sched_unit,
                        //     ]);
        }

          $data = array();
            foreach($request['tdarpno'] as $i => $tan) {
                if(isset($request['f56_detail_id'][$i])){
                     $detail = F56Detail::find($request['f56_detail_id'][$i]);
                     if(isset($request['f56_detail_deleted'][$i]) && $request['f56_detail_deleted'][$i] == 'true'){
                        $detail->delete();
                     }else{
                         $detail->col_f56_type_id = $request['f56_type'][$i];
                         $detail->owner_name = $request['declared_owner'][$i];
                         $detail->tdrp_assedvalue = $request['tdrp_assedvalue'][$i];
                         $detail->period_covered = $request['period_covered'][$i];
                         $detail->basic_current = $request['basic_current'][$i];
                         $detail->basic_discount = $request['basic_discount'][$i];
                         $detail->basic_previous = $request['basic_previous'][$i];
                         $detail->basic_penalty_current = $request['basic_penalty_current'][$i];
                         $detail->basic_penalty_previous = $request['basic_penalty_previous'][$i];
                         $detail->save();

                         $F56TDARP = F56TDARP::find($request['tdarpno_id'][$i]);
                         $F56TDARP->tdarpno = $tan;
                         $F56TDARP->municipality = $request['municipality'];
                         $F56TDARP->barangay =  $request['tdrp_barangay'][$i];
                         $F56TDARP->f56_type = $request['f56_type'][$i];
                         $F56TDARP->save();

                     }
                }else{

                            $detail = F56Detail::create([
                                            'col_receipt_id' => $receipt->id,
                                            'col_f56_type_id' => $request['f56_type'][$i],
                                            'owner_name' => $request['declared_owner'][$i],
                                            'tdrp_assedvalue' => $request['tdrp_assedvalue'][$i],
                                            'period_covered' => $request['period_covered'][$i],
                                            'basic_current' => $request['basic_current'][$i],
                                            'basic_discount' => $request['basic_discount'][$i],
                                            'basic_previous' => $request['basic_previous'][$i],
                                            'basic_penalty_current' => $request['basic_penalty_current'][$i],
                                            'basic_penalty_previous' => $request['basic_penalty_previous'][$i],
                                        ]);

                            $row['col_f56_detail_id'] = $detail->id;
                            $row['tdarpno'] = $tan;
                            $row['municipality'] =  $request['municipality'];
                            $row['barangay'] =  $request['tdrp_barangay'][$i];
                            $row['f56_type'] = $request['f56_type'][$i];
                            array_push($data, $row);
                }



            }
            F56TDARP::insert($data);

            Session::flash('info', ['Successfully created Form 56 transaction for serial: '.$receipt->serial_no]);
        return redirect()->route('form56.edit',$request['receipt_id']);
    }

    public function form56_benedict(){
            // $receipts = Receipt::where('af_type','=','2')->where('col_barangay_id','<>','0')->get();
            // foreach ($receipts as $receipt) {
            //     foreach ($receipt->F56Detailmny as $F56Detail) {
            //         foreach ( $F56Detail->TDARP as $TDARP) {
            //             $TDARP->municipality = $receipt->col_municipality_id;
            //             $TDARP->barangay = $receipt->col_barangay_id;
            //             $TDARP->save();
            //         }

            //     }
            // }

    }

    public function print_receipt($nsign,$id){
        $this->base['receipt'] = Receipt::find($id);
        $this->base['receipt']->is_printed = 1;
        $this->base['receipt']->save();


        $form56 = $this->set_table($this->base['receipt']);
        $this->base['form56'] = $form56;
        $this->base['sign'] = $nsign;
        $total_amnt = number_format($form56['total'], 2, '.', '');
        $total_amnt_e = explode('.',$total_amnt);
        $this->base['total_words'] = str_replace('only','', convert_number_to_words($total_amnt_e[0])).' and '.$total_amnt_e[1].'/100';
        $this->base['acctble_officer_name'] = ReportOfficers::whereId(10)->first();
        $this->base['acctble_officer_position'] = ReportOfficers::whereId(11)->first();
        $pdf = new PDF;
        $customPaper = array(0,0,456,960);
        $pdf = PDF::loadView('collection::form56/print_receipt',$this->base)
            ->setPaper($customPaper,'landscape');
        return @$pdf->stream();
    }

    private function set_table($receipt){
        // dd($receipt->F56Detailmny[0]->TDARPX->tdarpno);
        $table = '<table><tbody>';

        $tax_decs = $this->get_tax_decs($receipt);

        return $tax_decs;
    }

    private function get_tax_decs($receipt){
            $tr = [];
            $tax_decs = [];
            $yearly = [];
            $total = 0;
                foreach ($receipt->F56Detailmny as $key => $f56) {
                    if(!isset($tax_decs[$f56->owner_name])){

                    }
                    if(!isset($tax_decs[$f56->owner_name][$f56->TDARPX->tdarpno])){
                        $tax_decs[$f56->owner_name][$f56->TDARPX->tdarpno] = [
                                                                'owner' => $f56->owner_name,
                                                                'tax_dec' => $f56->TDARPX->tdarpno,
                                                                'tdrp_assedvalue' => $f56->tdrp_assedvalue,
                                                                'tax_due' => $f56->tdrp_assedvalue / 100,
                                                                'barangay_name' => $f56->TDARPX->barangay_name->name,
                                                                'tax_type' => $f56->F56Type->abbrev
                                                            ];
                    }

                    if(!isset($yearly[$f56->period_covered])){
                        $yearly[$f56->period_covered] = [
                                                                'sef' => $f56->basic_current + $f56->basic_previous,
                                                                'discount' => $f56->basic_discount,
                                                                'penalty' => $f56->basic_penalty_current + $f56->basic_penalty_previous,
                                                                'total' => $f56->basic_current - $f56->basic_discount + $f56->basic_previous + $f56->basic_penalty_current + $f56->basic_penalty_previous,
                                                            ];
                    }else{
                        $yearly[$f56->period_covered]['sef'] += $f56->basic_current + $f56->basic_previous;
                        $yearly[$f56->period_covered]['discount'] += $f56->basic_discount;
                        $yearly[$f56->period_covered]['penalty'] += $f56->basic_penalty_current + $f56->basic_penalty_previous;
                        $yearly[$f56->period_covered]['total'] += $f56->basic_current - $f56->basic_discount + $f56->basic_previous + $f56->basic_penalty_current + $f56->basic_penalty_previous;
                        
                    }
                    $total += $f56->basic_current - $f56->basic_discount + $f56->basic_previous + $f56->basic_penalty_current + $f56->basic_penalty_previous;
                }
                $tr['tax_decs'] = $tax_decs;
                $tr['yearly'] = $yearly;
                $tr['total'] = $total * 2;

                return $tr;
    }

  


}
