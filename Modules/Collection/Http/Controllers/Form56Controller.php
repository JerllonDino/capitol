<?php

namespace Modules\Collection\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbsController;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

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
use Modules\Collection\Entities\ReportOfficerNew; 
use Modules\Collection\Entities\PreviousTaxType;
use Carbon\Carbon,PDF,DB,Datatables;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use Smalot\PdfParser\Parser;

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

    public function importEx(Request $request)
    {
        $path = $request->file('imports')->getRealPath();
        
        $excel = Excel::load($path)->withHeadingRow();
        dd($excel);
    }


    public function index()
    {
        $this->base['f56_types'] = F56Type::get();
        $this->base['sub_header'] = 'FORM56 Land Tax';
        $this->base['form'] = Form::all();
        $this->base['transaction_type'] = TransactionType::all();
        $this->base['municipalities'] = Municipality::all()->toarray();
        $this->base['brgys'] = Barangay::all()->toarray();
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

        $f56_detail = F56Detail::where('col_receipt_id', $id)->first();
        $f56_tdarp = F56TDARP::where('col_f56_detail_id', $f56_detail->id)->first();
        $this->base['receipt_tdarp'] = $f56_tdarp;

        foreach( $this->base['receipt']->F56Detailmny as $key => $data){
            $period_covered = $data->period_covered;
            $recent_year = 0;
            if(strpos("-",$period_covered) == 0){
                    if($recent_year == 0){  
                        $latest_year = $period_covered;
                    }
                    if($period_covered > $latest_year){
                            $latest_year = $period_covered;
                    }
                }else{
                    $data = explode('-',$period_covered);

                    foreach($data as $d){
                        if($d > $latest_year){
                                $latest_year = $d;
                        }
                    }
                }
        }

        $this->base['cert_paid']  =  $latest_year;

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

        $f56_detail = F56Detail::where('col_receipt_id', $id)->first();
        // dd($f56_detail);
        $f56_tdarp = F56TDARP::where('col_f56_detail_id', $f56_detail->id)->first();
        $this->base['receipt_tdarp'] = $f56_tdarp;

         foreach( $this->base['receipt']->F56Detailmny as $key => $data){
            $period_covered = $data->period_covered;
            $recent_year = 0;
            if(strpos("-",$period_covered) == 0){
                    if($recent_year == 0){  
                        $latest_year = $period_covered;
                    }
                    if($period_covered > $latest_year){
                            $latest_year = $period_covered;
                    }
                }else{
                    $data = explode('-',$period_covered);

                    foreach($data as $d){
                        if($d > $latest_year){
                                $latest_year = $d;
                        }
                    }
                }
        }
        // dd($this->base);
        $this->base['cert_paid']  =  $latest_year;
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
                $payor_id = $payor->id;
                $payor->restore();
            } else {
                $payor_id = Customer::create([
                    'name' => $request['customer'],
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

        if($request['prev_receipt_no'] != ''){
            F56PreviousReceipt::updateOrCreate([
                'col_receipt_id' => $receipt->id,

            ],[
                'col_receipt_no' => $request['prev_receipt_no'],
                'col_receipt_date' => $request['prev_date'],
                'col_receipt_year' => $request['prev_for_the_year'],
                'col_prev_remarks' => $request['prev_remarks']
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
                    'full_partial' => $request['full_partial'][$i],
                    'basic_current' => $request['basic_current'][$i],
                    'basic_discount' => $request['basic_discount'][$i],
                    'basic_previous' => $request['basic_previous'][$i],
                    'basic_penalty_current' => $request['basic_penalty_current'][$i],
                    'basic_penalty_previous' => $request['basic_penalty_previous'][$i],
                    // 'manual_tax_due' => $request['tdrp_taxdue'][$i],
                    'manual_tax_due' => $request['tdrp_assedvalue'][$i]*.01,
                    'ref_num' => isset($request['ref_num'][$i]) ? $request['ref_num'][$i] : null,
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
                $payor_id = $payor->id;
            } else {
                $payor_id = Customer::create([
                    'name' => $request['customer'],
                    'address' => '',
                    ]);
                $payor_id = $payor_id->id;
            }
        } else {
            $payor_id = $request['customer_id'];
        }

        $report_datex = new Carbon($request['date']);
        $dt_3pm = new Carbon($report_datex->format('Y-m-d'));
        if($report_datex->timestamp <= $dt_3pm->addHours(15)->timestamp){
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

        // if(Carbon::parse($report_date)->format('Y-m-d') != Carbon::parse($request['report_date'])->format('Y-m-d')) {
        //     $receipt->report_date = Carbon::parse($request['report_date'])->format('Y-m-d');
        // } else {
        //     $receipt->report_date = $report_date;
        // }
        $receipt->report_date = $report_date;
        $receipt->col_municipality_id = $request['municipality'];
        $receipt->date_of_entry = date('Y-m-d H:i:s', strtotime($request['date']));
        $receipt->col_customer_id = $payor_id;
        $receipt->transaction_type =  $request['transaction_type'];
        $receipt->bank_name =  $request['bank_name'];
        $receipt->bank_number =  $request['bank_number'];
        $receipt->bank_date =  $request['bank_date'];
        $receipt->bank_remark =  $request['bank_remark'];

        $receipt->save();
        Session::put('serial_id', $request['serial_id']);
        if($request['prev_receipt_no'] != ''){
             F56PreviousReceipt::updateOrCreate([
                    'col_receipt_id' => $receipt->id,
                ],[
                    'col_receipt_no' => $request['prev_receipt_no'],
                    'col_receipt_date' => $request['prev_date'],
                    'col_receipt_year' => $request['prev_for_the_year'],
                    'col_prev_remarks' => $request['prev_remarks']
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
                         $detail->full_partial = $request['full_partial'][$i];
                         $detail->basic_current = $request['basic_current'][$i];
                         $detail->basic_discount = $request['basic_discount'][$i];
                         $detail->basic_previous = $request['basic_previous'][$i];
                         $detail->basic_penalty_current = $request['basic_penalty_current'][$i];
                         $detail->basic_penalty_previous = $request['basic_penalty_previous'][$i];
                         $detail->ref_num = isset($request['ref_num'][$i]) ? $request['ref_num'][$i] : null;
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
                        'full_partial' => $request['full_partial'][$i],
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
                    // $row['previous_tax_type_id'] = $request['previous_tax_type'];
                    array_push($data, $row);
                }
            }
            F56TDARP::insert($data);
            F56TDARP::where('col_f56_detail_id', $request['f56_detail_id'][0])->update(['previous_tax_type_id' => $request['previous_tax_type']]);

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

        public function print_receipt2($id){


        $this->base['receipt'] = Receipt::find($id);
        $this->base['receipt']->is_printed = 1;
        $this->base['receipt']->save();


        $form56 = $this->set_table($this->base['receipt']);
        $this->base['form56'] = $form56;
        $this->base['sign'] = Input::get('nsign');
        $this->base['wmunicipality'] = Input::get('wmunicipality');
        $total_amnt = number_format($form56['total'], 2, '.', '');
        $total_amnt_e = explode('.',$total_amnt);
        $this->base['total_words'] = str_replace('only','', convert_number_to_words($total_amnt_e[0])).' and '.$total_amnt_e[1].'/100';
        $this->base['acctble_officer_name'] = ReportOfficers::whereId(10)->first();
        $this->base['acctble_officer_position'] = ReportOfficers::whereId(11)->first();
        $pdf = new PDF;
        $customPaper = array(0,0,456,960);
        // $yearly = $this->base['form56'];
        // $this->base['totalSum'] = 0;
        // foreach ($yearly as $yearly) {
        //     $this->base['totalSum'] += $yearly[''];
        // }
        $pdf = PDF::loadView('collection::form56/print_receipt',$this->base)
            ->setPaper($customPaper,'landscape');
        return @$pdf->stream();
    }

    public function print_receipt3($id){
        $this->base['receipt'] = Receipt::find($id);
        $this->base['receipt']->is_printed = 1;
        $this->base['receipt']->save();

        $this->base['tax_type'] = $this->base['receipt']->F56Detail->TDARPX->previous_tax_type_id;

        $form56 = $this->set_table($this->base['receipt']);
        $this->base['form56'] = $form56;

        $annual_per_arp = $this->get_annual_per_arp($this->base['receipt']);
        $this->base['annual_per_arp'] = $this->get_annual_per_arp($this->base['receipt']);

        $arr = array();
        $group_arr = array();
        $limit = (count($form56['yearly']) > 4) ? 3 : count($form56['yearly']);
        $limit_counter = 0;
        $limit_end = 0;

        $years = array_keys($form56['yearly']);
        $arp_per_year = [];
        $unique_arp_per_year = [];
        $year_lumped = [];
        foreach($annual_per_arp['yearly'] as $arp => $data) {
            foreach($data as $year => $val) {
                $arp_per_year[$arp][$year]['assess_val'] = $val['assess_val'];
                // $arp_per_year[$arp][$year]['discount'] = $val['discount'];
                // $arp_per_year[$arp][$year]['penalty'] = $val['penalty'];
            }
        }
        foreach($arp_per_year as $arp => $data) {
            $arr = array_unique($data, SORT_REGULAR);
            $unique_arp_per_year[$arp] = $arr;
        }
        foreach($unique_arp_per_year as $arp => $data) {
            $keys = array_keys($data);
            $keys2 = array_keys($annual_per_arp['yearly'][$arp]);

            for($i = 0; $i < count($keys); $i++) {
                if(strlen($keys[$i]) == 4 && $keys[$i] < Carbon::now()->addYear()->format('Y')) {
                    $year_lumped[$arp][$keys[$i]]['assess_val'] = 0;
                    $year_lumped[$arp][$keys[$i]]['penalty'] = 0;
                    $year_lumped[$arp][$keys[$i]]['discount'] = 0;
                    $year_lumped[$arp][$keys[$i]]['sef'] = 0;

                    foreach($arp_per_year as $arp => $data2) {
                        foreach($data2 as $year => $val) {
                            // if(strlen($year) > 4) {
                                $penalty_percent = 0;
                                $discount = 0;
                                // $annual_per_arp['yearly'][array_search(current($arp_per_year), $arp_per_year)][$year]['prev_tax_dec_no'] == $arp
                                // $annual_per_arp['yearly'][array_search(next($arp_per_year), $arp_per_year)][$year]['prev_tax_dec_no'] == $arp
                                if(isset($keys[$i+1])) {
                                    if($year == $keys[$i]) {
                                        // if($year == Carbon::now()->format('Y')) {
                                        //     $entry_date = Carbon::parse($annual_per_arp[$arp]['entry_date']); 
                                        //     if($entry_date->format('m-d') >= Carbon::createFromDate(Carbon::now()->format('Y'), 3, 31)->format('m-d')) {
                                        //         // 1st quarter due date is Mar 31
                                        //         // $penalty_percent += 8; 
                                        //         $penalty_percent += $entry_date->format('m') * 2;
                                        //     } 
                                        //     if($entry_date->format('m-d') >= Carbon::createFromDate(Carbon::now()->format('Y'), 6, 30)->format('m-d')) {
                                        //         // 2nd quarter due date Jun 30
                                        //         // $penalty_percent += 14; 
                                        //         $penalty_percent += ($entry_date->format('m')-3) * 2;
                                        //     } 
                                        //     if($entry_date->format('m-d') >= Carbon::createFromDate(Carbon::now()->format('Y'), 9, 30)->format('m-d')) {
                                        //         // 3rd quarter due date Sep 30
                                        //         // $penalty_percent += 20; 
                                        //         $penalty_percent += ($entry_date->format('m')-6) * 2;
                                        //     } 
                                        //     if($entry_date->format('m-d') >= Carbon::createFromDate(Carbon::now()->format('Y'), 12, 30)->format('m-d') && $year == Carbon::now()->addYear()->format('Y')) {
                                        //         // $discount = $val['assess_val'] * .10;
                                        //     }
                                        // }
                                        if($year == Carbon::now()->format('Y')) {
                                            $year_lumped[$arp][$keys[$i]]['assess_val'] += $val['assess_val'];
                                            // $year_lumped[$arp][$keys[$i]]['penalty'] += (($val['assess_val']*.01)/4) * ($penalty_percent/100); // basic/sef
                                            $year_lumped[$arp][$keys[$i]]['penalty'] += isset($val['penalty']) ? $val['penalty'] : 0;
                                            $year_lumped[$arp][$keys[$i]]['discount'] += isset($val['discount']) ? $val['discount'] : 0;
                                            $year_lumped[$arp][$keys[$i]]['sef'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['sef'];
                                        } else {                             
                                            // $year_lumped[$arp][$keys[$i]]['assess_val'] += $data[$keys[$i]]['assess_val'];
                                            // $year_lumped[$arp][$keys[$i]]['penalty'] += $data[$keys[$i]]['penalty'];
                                            // $year_lumped[$arp][$keys[$i]]['discount'] += $data[$keys[$i]]['discount'];
                                            $year_lumped[$arp][$keys[$i]]['assess_val'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['assess_val'];
                                            $year_lumped[$arp][$keys[$i]]['penalty'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['penalty'];
                                            $year_lumped[$arp][$keys[$i]]['discount'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['discount'];
                                            $year_lumped[$arp][$keys[$i]]['sef'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['sef'];
                                        }
                                    // } else if($year < $keys[$i+1]) {
                                    // } else if($year > $keys[$i+1]) {
                                    } else {
                                        if(isset($year_lumped[$arp][$keys[$i]])) {
                                            // $year_lumped[$arp][$keys[$i]]['assess_val'] += $val['assess_val'];
                                            // $year_lumped[$arp][$keys[$i]]['penalty'] += $val['penalty'];
                                            // $year_lumped[$arp][$keys[$i]]['discount'] += $val['discount'];
                                            $year_lumped[$arp][$keys[$i]]['assess_val'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['assess_val'];
                                            $year_lumped[$arp][$keys[$i]]['penalty'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['penalty'];
                                            $year_lumped[$arp][$keys[$i]]['discount'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['discount'];
                                            $year_lumped[$arp][$keys[$i]]['sef'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['sef'];
                                            if($keys[$i] != $keys[$i+1]+1) {
                                                $year_lumped[$arp][$keys[$i]]['to'] = $keys[$i+1]-1;
                                            }
                                        }
                                    }
                                } else {
                                    if($year == $keys[$i]) {
                                        // if($year == Carbon::now()->format('Y')) {
                                        //     $entry_date = Carbon::parse($annual_per_arp[$arp]['entry_date']); 
                                        //     if($entry_date->format('m-d') >= Carbon::createFromDate(Carbon::now()->format('Y'), 3, 31)->format('m-d')) {
                                        //         // 1st quarter due date is Mar 31
                                        //         // $penalty_percent += 8; 
                                        //         $penalty_percent += $entry_date->format('m') * 2;
                                        //     } 
                                        //     if($entry_date->format('m-d') >= Carbon::createFromDate(Carbon::now()->format('Y'), 6, 30)->format('m-d')) {
                                        //         // 2nd quarter due date Jun 30
                                        //         // $penalty_percent += 14; 
                                        //         $penalty_percent += ($entry_date->format('m')-3) * 2;
                                        //     } 
                                        //     if($entry_date->format('m-d') >= Carbon::createFromDate(Carbon::now()->format('Y'), 9, 30)->format('m-d')) {
                                        //         // 3rd quarter due date Sep 30
                                        //         // $penalty_percent += 20; 
                                        //         $penalty_percent += ($entry_date->format('m')-6) * 2;
                                        //     } 
                                        //     if($entry_date->format('m-d') >= Carbon::createFromDate(Carbon::now()->format('Y'), 12, 30)->format('m-d') && $year == Carbon::now()->addYear()->format('Y')) {
                                        //         // $discount = $val['assess_val'] * .10;
                                        //     }
                                        // }
                                        if($year == Carbon::now()->format('Y')) {  
                                            if(isset($year_lumped[$arp])) {
                                                // $year_lumped[$arp][$keys[$i]]['assess_val'] += $val['assess_val'];
                                                // $year_lumped[$arp][$keys[$i]]['penalty'] += (($val['assess_val']*.01)/4) * ($penalty_percent/100); // basic/sef
                                                $year_lumped[$arp][$keys[$i]]['assess_val'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['assess_val'];
                                                $year_lumped[$arp][$keys[$i]]['penalty'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['penalty'];
                                                $year_lumped[$arp][$keys[$i]]['discount'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['discount'];
                                                $year_lumped[$arp][$keys[$i]]['sef'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['sef'];
                                            } else {
                                                $year_lumped[$arp][$keys[$i]]['assess_val'] = $annual_per_arp['yearly'][$arp][$keys[$i]]['assess_val'];
                                                $year_lumped[$arp][$keys[$i]]['penalty'] = $annual_per_arp['yearly'][$arp][$keys[$i]]['penalty'];
                                                $year_lumped[$arp][$keys[$i]]['discount'] = $annual_per_arp['yearly'][$arp][$keys[$i]]['discount'];
                                                $year_lumped[$arp][$keys[$i]]['sef'] = $annual_per_arp['yearly'][$arp][$keys[$i]]['sef'];
                                            }
                                        } else {
                                            if(isset($year_lumped[$arp])) {
                                                // $year_lumped[$arp][$keys[$i]]['assess_val'] += $data[$keys[$i]]['assess_val'];
                                                $year_lumped[$arp][$keys[$i]]['assess_val'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['assess_val'];
                                                $year_lumped[$arp][$keys[$i]]['penalty'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['penalty']; // basic/sef
                                                $year_lumped[$arp][$keys[$i]]['discount'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['discount'];
                                                $year_lumped[$arp][$keys[$i]]['sef'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['sef'];
                                            } else {
                                                $year_lumped[$arp][$keys[$i]]['assess_val'] = $annual_per_arp['yearly'][$arp][$keys[$i]]['assess_val'];
                                                $year_lumped[$arp][$keys[$i]]['penalty'] = $annual_per_arp['yearly'][$arp][$keys[$i]]['penalty']; // basic/sef
                                                $year_lumped[$arp][$keys[$i]]['discount'] = $annual_per_arp['yearly'][$arp][$keys[$i]]['discount'];
                                                $year_lumped[$arp][$keys[$i]]['sef'] = $annual_per_arp['yearly'][$arp][$keys[$i]]['sef'];
                                            }
                                        }
                                    } else {
                                        if(isset($annual_per_arp['yearly'][$arp][$keys[$i]])) {
                                            if(isset($year_lumped[$arp][$keys[$i]])) {
                                                // $year_lumped[$arp][$keys[$i]]['assess_val'] += $data[$keys[$i]]['assess_val'];
                                                    $year_lumped[$arp][$keys[$i]]['assess_val'] += $annual_per_arp['yearly'][$arp][$year]['assess_val'];
                                                // $year_lumped[$arp][$keys[$i]]['penalty'] = $data[$keys[$i]]['penalty'];
                                                $year_lumped[$arp][$keys[$i]]['penalty'] += $annual_per_arp['yearly'][$arp][$year]['penalty'];
                                                // $year_lumped[$arp][$keys[$i]]['discount'] = $data[$keys[$i]]['discount'];
                                                $year_lumped[$arp][$keys[$i]]['discount'] += $annual_per_arp['yearly'][$arp][$year]['discount'];
                                                $year_lumped[$arp][$keys[$i]]['sef'] += $annual_per_arp['yearly'][$arp][$year]['sef'];
                                                if($year > $keys[$i]) {
                                                    $year_lumped[$arp][$keys[$i]]['to'] = $year;
                                                }
                                            } else {
                                                // $year_lumped[$arp][$keys[$i]]['assess_val'] += $data[$keys[$i]]['assess_val'];
                                                $year_lumped[$arp][$keys[$i]]['assess_val'] = $annual_per_arp['yearly'][$arp][$year]['assess_val'];
                                                // $year_lumped[$arp][$keys[$i]]['penalty'] = $data[$keys[$i]]['penalty'];
                                                $year_lumped[$arp][$keys[$i]]['penalty'] = $annual_per_arp['yearly'][$arp][$year]['penalty']; // basic/sef
                                                // $year_lumped[$arp][$keys[$i]]['discount'] = $data[$keys[$i]]['discount'];
                                                $year_lumped[$arp][$keys[$i]]['discount'] = $annual_per_arp['yearly'][$arp][$year]['discount'];
                                                $year_lumped[$arp][$keys[$i]]['sef'] += $annual_per_arp['yearly'][$year]['sef'];
                                                if($year > $keys[$i]) {
                                                    $year_lumped[$arp][$keys[$i]]['to'] = $year;
                                                }
                                            }
                                        }
                                    }
                                }
                            // }
                        }
                    }
                } else if(strlen($keys[$i]) > 4) {
                    $split = explode('-', $keys[$i]);
                    if($split[1] < Carbon::now()->addYear()->format('Y')) {
                        foreach($keys2 as $yearr) {
                            // if($keys[$i] != $yearr) {
                                if($annual_per_arp['yearly'][$arp][$keys[$i]]['assess_val'] == $annual_per_arp['yearly'][$arp][$yearr]['assess_val']) {
                                    if(isset($year_lumped[$arp][$keys[$i]])) {
                                        $year_lumped[$arp][$keys[$i]]['assess_val'] += $annual_per_arp['yearly'][$arp][$yearr]['assess_val'];
                                        $year_lumped[$arp][$keys[$i]]['penalty'] += $annual_per_arp['yearly'][$arp][$yearr]['penalty'];
                                        $year_lumped[$arp][$keys[$i]]['discount'] += $annual_per_arp['yearly'][$arp][$yearr]['discount'];
                                        $year_lumped[$arp][$keys[$i]]['sef'] += $annual_per_arp['yearly'][$arp][$yearr]['sef'];
                                        if($yearr > $split[1]) {
                                            $year_lumped[$arp][$keys[$i]]['to'] = $yearr;
                                        }
                                    } else {
                                        $year_lumped[$arp][$keys[$i]]['assess_val'] = $annual_per_arp['yearly'][$arp][$keys[$i]]['assess_val'];
                                        $year_lumped[$arp][$keys[$i]]['penalty'] = $annual_per_arp['yearly'][$arp][$keys[$i]]['penalty'];
                                        $year_lumped[$arp][$keys[$i]]['discount'] = $annual_per_arp['yearly'][$arp][$keys[$i]]['discount'];
                                        $year_lumped[$arp][$keys[$i]]['sef'] = $annual_per_arp['yearly'][$arp][$keys[$i]]['sef'];
                                        if($yearr > $split[1]) {
                                            $year_lumped[$arp][$keys[$i]]['to'] = $yearr;
                                        }
                                    }
                                }
                            // }
                        }
                    }
                } else if($keys[$i] == Carbon::now()->addYear()->format('Y')) {
                    // FOR ADVANCE PAYMENTS
                    $year_lumped[$arp][$keys[$i]]['assess_val'] = $annual_per_arp[$arp]['assess_val'];
                    $year_lumped[$arp][$keys[$i]]['penalty'] = $annual_per_arp['yearly'][$arp][$keys[$i]]['penalty']; // basic/sef
                    $year_lumped[$arp][$keys[$i]]['discount'] = $annual_per_arp['yearly'][$arp][$keys[$i]]['discount'];
                    $year_lumped[$arp][$keys[$i]]['sef'] = $annual_per_arp['yearly'][$arp][$keys[$i]]['sef'];
                    foreach($arp_per_year as $arp => $data2) {
                        foreach($data2 as $year => $val) {
                            $penalty_percent = 0;
                            $discount = 0;

                            if($year < Carbon::now()->addYear()->format('Y')) {
                                if(isset($keys2[$i+1])) {
                                    if($year == $keys2[$i+1]) { // skip 1st ta advance payment diay
                                        // if($year == Carbon::now()->format('Y')) {
                                        //     $entry_date = Carbon::parse($annual_per_arp[$arp]['entry_date']); 
                                        //     if($entry_date->format('m-d') >= Carbon::createFromDate(Carbon::now()->format('Y'), 3, 31)->format('m-d')) {
                                        //         // 1st quarter due date is Mar 31
                                        //         // $penalty_percent += 8; 
                                        //         $penalty_percent += $entry_date->format('m') * 2;
                                        //     } 
                                        //     if($entry_date->format('m-d') >= Carbon::createFromDate(Carbon::now()->format('Y'), 6, 30)->format('m-d')) {
                                        //         // 2nd quarter due date Jun 30
                                        //         // $penalty_percent += 14; 
                                        //         $penalty_percent += ($entry_date->format('m') - 3) * 2;
                                        //     } 
                                        //     if($entry_date->format('m-d') >= Carbon::createFromDate(Carbon::now()->format('Y'), 9, 30)->format('m-d')) {
                                        //         // 3rd quarter due date Sep 30
                                        //         // $penalty_percent += 20; 
                                        //         $penalty_percent += ($entry_date->format('m') - 6) * 2;
                                        //     } 
                                        // }
                                        if($year == Carbon::now()->format('Y')) {
                                            if(isset($year_lumped[$arp][$keys2[$i+1]])) {
                                                $year_lumped[$arp][$keys2[$i+1]]['assess_val'] += $val['assess_val'];
                                                // $year_lumped[$arp][$keys2[$i+1]]['penalty'] = (($val['assess_val']*.01)/4) * ($penalty_percent/100); 
                                                $year_lumped[$arp][$keys2[$i+1]]['penalty'] = $annual_per_arp['yearly'][$arp][$year]['penalty']; 
                                                $year_lumped[$arp][$keys2[$i+1]]['discount'] += $annual_per_arp['yearly'][$arp][$year]['discount'];
                                                $year_lumped[$arp][$keys2[$i+1]]['sef'] += $annual_per_arp['yearly'][$arp][$year]['sef'];
                                            } else {
                                                $year_lumped[$arp][$keys2[$i+1]]['assess_val'] = $val['assess_val'];
                                                // $year_lumped[$arp][$keys2[$i+1]]['penalty'] = number_format((($val['assess_val']*.01)/4) * ($penalty_percent/100)); 
                                                $year_lumped[$arp][$keys2[$i+1]]['penalty'] = $annual_per_arp['yearly'][$arp][$year]['penalty'];
                                                $year_lumped[$arp][$keys2[$i+1]]['discount'] = $annual_per_arp['yearly'][$arp][$year]['discount'];
                                                $year_lumped[$arp][$keys2[$i+1]]['sef'] += $annual_per_arp['yearly'][$arp][$year]['sef'];
                                            }
                                        } else {
                                            $year_lumped[$arp][$keys2[$i+1]]['assess_val'] += $data[$keys[$i]]['assess_val'];
                                            $year_lumped[$arp][$keys2[$i+1]]['penalty'] += $data[$keys[$i]]['penalty'];
                                            $year_lumped[$arp][$keys2[$i+1]]['discount'] += $annual_per_arp['yearly'][$arp][$year]['discount'];
                                            $year_lumped[$arp][$keys2[$i+1]]['sef'] += $annual_per_arp['yearly'][$arp][$year]['sef'];
                                        }
                                    } else if($year < $keys2[$i+1]) {
                                    // } else if($year > $keys[$i+1]) {
                                        if(isset($year_lumped[$arp][$keys2[$i+1]])) {
                                            $year_lumped[$arp][$keys2[$i+1]]['assess_val'] += $val['assess_val'];
                                            $year_lumped[$arp][$keys2[$i+1]]['penalty'] += $annual_per_arp['yearly'][$arp][$year]['penalty'];
                                            $year_lumped[$arp][$keys2[$i+1]]['discount'] += $annual_per_arp['yearly'][$arp][$year]['discount'];
                                            $year_lumped[$arp][$keys2[$i+1]]['sef'] += $annual_per_arp['yearly'][$arp][$year]['sef'];
                                            if($year < $keys[$i] && $keys2[$i+1] < Carbon::now()->addYear()->format('Y')) {
                                                $year_lumped[$arp][$keys2[$i+1]]['to'] = $year;
                                            }
                                        }
                                    }
                                } else {
                                    if($year == $keys[$i]) {
                                        // if($year == Carbon::now()->format('Y')) {
                                        //     $entry_date = Carbon::parse($annual_per_arp[$arp]['entry_date']); 
                                        //     if($entry_date->format('m-d') >= Carbon::createFromDate(Carbon::now()->format('Y'), 3, 31)->format('m-d')) {
                                        //         // 1st quarter due date is Mar 31
                                        //         // $penalty_percent += 8; 
                                        //         $penalty_percent += $entry_date->format('m') * 2;
                                        //     } 
                                        //     if($entry_date->format('m-d') >= Carbon::createFromDate(Carbon::now()->format('Y'), 6, 30)->format('m-d')) {
                                        //         // 2nd quarter due date Jun 30
                                        //         // $penalty_percent += 14; 
                                        //         $penalty_percent += ($entry_date->format('m') - 3) * 2;
                                        //     } 
                                        //     if($entry_date->format('m-d') >= Carbon::createFromDate(Carbon::now()->format('Y'), 9, 30)->format('m-d')) {
                                        //         // 3rd quarter due date Sep 30
                                        //         // $penalty_percent += 20; 
                                        //         $penalty_percent += ($entry_date->format('m') - 6) * 2;
                                        //     } 
                                        //     if($entry_date->format('m-d') >= Carbon::createFromDate(Carbon::now()->format('Y'), 12, 30)->format('m-d') && $year == Carbon::now()->addYear()->format('Y')) {
                                        //         // $discount = $val['assess_val'] * .10;
                                        //     }
                                        // }

                                        if($year == Carbon::now()->format('Y')) {
                                            // $year_lumped[$arp][$keys[$i]]['assess_val'] += $val['assess_val'];
                                            // $year_lumped[$arp][$keys[$i]]['penalty'] += (($val['assess_val']*.01)/4) * ($penalty_percent/100); // basic/sef
                                            $year_lumped[$arp][$keys[$i]]['assess_val'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['assess_val'];
                                            $year_lumped[$arp][$keys[$i]]['penalty'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['penalty'];
                                            $year_lumped[$arp][$keys[$i]]['discount'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['discount'];
                                            $year_lumped[$arp][$keys[$i]]['sef'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['sef'];
                                        } else {
                                            // $year_lumped[$arp][$keys[$i]]['assess_val'] += $data[$keys[$i]]['assess_val'];
                                            // $year_lumped[$arp][$keys[$i]]['penalty'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['penalty']; // basic/sef
                                            $year_lumped[$arp][$keys[$i]]['assess_val'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['assess_val'];
                                            $year_lumped[$arp][$keys[$i]]['penalty'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['penalty'];
                                            $year_lumped[$arp][$keys[$i]]['discount'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['discount'];
                                            $year_lumped[$arp][$keys[$i]]['sef'] += $annual_per_arp['yearly'][$arp][$keys[$i]]['sef'];
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    if(strlen($keys[$i]) > 4) {
                        $split = explode('-', $keys[$i]);
                        $diff = ($split[1] - $split[0]) + 1;
                        $year_lumped[$arp][$keys[$i]]['assess_val'] = $arp_per_year[$arp][$keys[$i]]['assess_val'] * $diff;
                        $year_lumped[$arp][$keys[$i]]['penalty'] = $annual_per_arp['yearly'][$arp][$keys[$i]]['penalty'];
                        $year_lumped[$arp][$keys[$i]]['discount'] = $annual_per_arp['yearly'][$arp][$keys[$i]]['discount'];
                        $year_lumped[$arp][$keys[$i]]['sef'] = $annual_per_arp['yearly'][$arp][$keys[$i]]['sef'];
                    } else {
                        $year_lumped[$arp][$keys[$i]]['assess_val'] = $arp_per_year[$arp][$keys[$i]]['assess_val'];
                        $year_lumped[$arp][$keys[$i]]['penalty'] = $annual_per_arp['yearly'][$arp][$keys[$i]]['penalty'];
                        $year_lumped[$arp][$keys[$i]]['discount'] = $annual_per_arp['yearly'][$arp][$keys[$i]]['discount'];
                        $year_lumped[$arp][$keys[$i]]['sef'] = $annual_per_arp['yearly'][$arp][$keys[$i]]['sef'];
                    }
                }
            }
        }

// dd($annual_per_arp);
        $this->base['annual_arp'] = $year_lumped;
        $this->base['breakdown'] =  $this->tax_breakdown($this->base['receipt']->F56Detailmny,$this->base['receipt']->report_date);

        $this->base['sign'] = Input::get('nsign');
        $this->base['wmunicipality'] = Input::get('wmunicipality');
        $total_amnt = number_format($form56['total'], 2, '.', '');
        $total_amnt_e = explode('.',$total_amnt);
        $this->base['total_words'] = str_replace('only','', convert_number_to_words($total_amnt_e[0])).' and '.$total_amnt_e[1].'/100';
        $this->base['acctble_officer_name'] = ReportOfficers::whereId(10)->first();
        $this->base['acctble_officer_position'] = ReportOfficers::whereId(11)->first();
        $pdf = new PDF;
        $customPaper = array(0,0,456,960);
        // $yearly = $this->base['form56'];
        // $this->base['totalSum'] = 0;
        // foreach ($yearly as $yearly) {
        //     $this->base['totalSum'] += $yearly[''];
        // }

        // $pdf = PDF::loadView('collection::form56/print_receipt2',$this->base)
        //     ->setPaper($customPaper,'landscape');

        $pdf = PDF::loadView('collection::form56/print_receipt3',$this->base)
            ->setPaper($customPaper,'landscape');
        // $pdf = PDF::loadView('collection::form56/print_receipt3_03092020_bak',$this->base)
        //     ->setPaper($customPaper,'landscape');
        // dd($this->base);
        return @$pdf->stream();
        // return view('collection::form56/print_receipt3',$this->base);
    }

    public function tax_breakdown($f56, $date_processed){
        $breakdown_array = [];
        $date = Carbon::parse($date_processed);

        foreach($f56 as $key => $f){
            $i = 3;
            $qrtr = 1;
            while($i < $date->month){ // while(true)
                if($i < $date->month && $i + 3 > $date->month){
                    $qrtr = 1;
                    break;
                }
                $qrtr++;
                $i += 3;
            }
            $assessed_value = $f->tdrp_assedvalue;

            $arp = explode("-", $f['TDARPX']->tdarpno);
            if($date->format('Y') == $f->period_covered){ 
                // orig code
                // if($date->month > 3){
                //     $breakdown_array[$date->year][$key]['penalty'] = (($assessed_value * 0.01) / 4 ) * ($date->month* 0.02);
                //     $breakdown_array[$date->year][$key]['discount'] = 0 ;
                // }else{
                //     $breakdown_array[$date->year][$key]['penalty'] = 0 ;
                //     $breakdown_array[$date->year][$key]['discount'] = ($assessed_value * 0.01) * (0.06) ;
                // }

                // orig code
                // $breakdown_array[$date->year][$key]['partial_value'] = $qrtr / 4 * $assessed_value * 0.01;
                // $breakdown_array[$date->year][$key]['total_value'] = ($assessed_value * 0.01) - $breakdown_array[$date->year][$key]['partial_value'];
      
                //////////////////////// per quarter ////////////////////////////
                if($date->month > 3 && $date->month < 7){ // 2nd quarter
                    $breakdown_array[$date->year][$key]['penalty'] = $arp[0] >= 94 ? (($assessed_value * 0.01) / 4 ) * ($date->month* 0.02) : $f->basic_penalty_current;
                    $breakdown_array[$date->year][$key]['discount'] = $f->basic_discount ;
                    $breakdown_array[$date->year][$key]['partial_value'] = $arp[0] >= 94 ? ($assessed_value/4) * 0.01 : (isset($f->manual_tax_due) ? $f->manual_tax_due/4 : ($assessed_value/4) * 0.01);
                    $breakdown_array[$date->year][$key]['total_value'] = $arp[0] >= 94 ? ($assessed_value * 0.01) - $breakdown_array[$date->year][$key]['partial_value'] : (isset($f->manual_tax_due) ? $f->manual_tax_due - $breakdown_array[$date->year][$key]['partial_value'] : ($assessed_value * 0.01) - $breakdown_array[$date->year][$key]['partial_value']);
                    $breakdown_array[$date->year][$key]['quarter'] = "2";
                    $breakdown_array[$date->year][$key]['assess_val'] = $assessed_value;
                } else if($date->month > 6 && $date->month < 10) { // 3rd quarter 
                    $breakdown_array[$date->year][$key]['penalty'] = $arp[0] >= 94 ? ((($assessed_value * 0.01) / 4 ) * ($date->month* 0.02)) * 2 : $f->basic_penalty_current;
                    $breakdown_array[$date->year][$key]['discount'] = $f->basic_discount ;
                    $breakdown_array[$date->year][$key]['partial_value'] = $arp[0] >= 94 ? ((($assessed_value/4)) * 0.01) * 2 : (isset($f->manual_tax_due) ? ($f->manual_tax_due/4)*2 : ((($assessed_value/4)) * 0.01) * 2);
                    $breakdown_array[$date->year][$key]['total_value'] = $arp[0] >= 94 ? ($assessed_value * 0.01) - $breakdown_array[$date->year][$key]['partial_value'] : (isset($f->manual_tax_due) ? $f->manual_tax_due - $breakdown_array[$date->year][$key]['partial_value'] : ($assessed_value * 0.01) - $breakdown_array[$date->year][$key]['partial_value']);
                    $breakdown_array[$date->year][$key]['quarter'] = "3";
                    $breakdown_array[$date->year][$key]['assess_val'] = $assessed_value;
                } else if($date->month > 9 && $date->month <= 12) { // 4th quarter
                    $breakdown_array[$date->year][$key]['penalty'] = $arp[0] >= 94 ? ((($assessed_value * 0.01) / 4 ) * ($date->month* 0.02)) * 3 : $f->basic_penalty_current;
                    $breakdown_array[$date->year][$key]['discount'] = $f->basic_discount ;
                    $breakdown_array[$date->year][$key]['partial_value'] = $arp[0] >= 94 ? ($qrtr / 4 * $assessed_value * 0.01) * 3 : (isset($f->manual_tax_due) ? ($f->manual_tax_due/4)*3 : ($qrtr / 4 * $assessed_value * 0.01) * 3);
                    $breakdown_array[$date->year][$key]['total_value'] = $arp[0] >= 94 ? ($assessed_value * 0.01) - $breakdown_array[$date->year][$key]['partial_value'] : (isset($f->manual_tax_due) ? $f->manual_tax_due - $breakdown_array[$date->year][$key]['partial_value'] : ($assessed_value * 0.01) - $breakdown_array[$date->year][$key]['partial_value']);
                    $breakdown_array[$date->year][$key]['quarter'] = "4";
                    $breakdown_array[$date->year][$key]['assess_val'] = $assessed_value;
                } else { // 1st quarter
                    $breakdown_array[$date->year][$key]['penalty'] = 0;
                    $breakdown_array[$date->year][$key]['discount'] = ($assessed_value * 0.01) * (0.06);
                    $breakdown_array[$date->year][$key]['partial_value'] = $arp[0] >= 94 ? ($assessed_value/4) * 0.01 : (isset($f->manual_tax_due) ? $f->manual_tax_due/4 : ($assessed_value/4) * 0.01); 
                    $breakdown_array[$date->year][$key]['total_value'] = $arp[0] >= 94 ? ($assessed_value * 0.01) - $breakdown_array[$date->year][$key]['partial_value'] : (isset($f->manual_tax_due) ? $f->manual_tax_due - $breakdown_array[$date->year][$key]['partial_value'] : ($assessed_value * 0.01) - $breakdown_array[$date->year][$key]['partial_value']);
                    $breakdown_array[$date->year][$key]['quarter'] = "1";
                    $breakdown_array[$date->year][$key]['assess_val'] = $assessed_value;
                }        
            }
        }
        return $breakdown_array;
    }

    public function print_certificate($id){
        $receipt = Receipt::find($id);
        $this->base['receipt'] = $receipt;
        // $this->base['cert_fee'] = $receipt->items->first();
        $this->base['f51_OR'] = Receipt::join('col_receipt_items', 'col_receipt.id', '=', 'col_receipt_items.col_receipt_id')
            ->where('col_receipt.col_customer_id', $receipt['col_customer_id'])
            ->where('nature', 'Certification Fee')
            ->orderBy('date_of_entry', 'desc')
            ->first();
        $this->base['acctble_officer_name'] = ReportOfficers::whereId(2)->first();
        $this->base['acctble_officer_position'] = ReportOfficers::whereId(6)->first();

        $tdrp = array();
        $tdrp_arr = array();
        foreach ($receipt->F56Detailmny as $key => $data) {
            array_push($tdrp_arr, $data->TDARPX->tdarpno);
        }
        foreach ($tdrp_arr as $value) {
            array_push($tdrp, DB::connection('mysql2')->select('select cert_title from tax_dec_archive_info where tax_dec_no = "' . $value .'"'));
        }
        $this->base['tdrp_title'] = $tdrp;

        $pdf = new PDF;
        $pdf = PDF::loadView('collection::form56/print_cert', $this->base)->setPaper('A4', 'portrait');
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

            $full = 0;
            $partial = 0;
                foreach ($receipt->F56Detailmny as $key => $f56) {

                    if($f56->full_partial == NULL || $f56->full_partial == NULL){
                        $full = 1;
                    }

                    if($f56->full_partial >= 1 && $f56->full_partial <= 4){
                        $partial = 1;
                    }
                    if(!isset($tax_decs[$f56->owner_name])){

                    } 
                    if(!isset($tax_decs[$f56->owner_name][$f56->TDARPX->tdarpno])){
                        $query = DB::connection('mysql2')->select(DB::raw('select kind, actual_use from tax_dec_archive_info 
                            join tax_dec_archive_kind_class on tax_dec_archive_info.id = tax_dec_archive_kind_class.tax_dec_id 
                            where tax_dec_no = "'.$f56->TDARPX->tdarpno.'" order by tax_dec_archive_kind_class.id'));

                        $tax_decs[$f56->owner_name][$f56->TDARPX->tdarpno] = [
                            'owner' => $f56->owner_name,
                            'tax_dec' => $f56->TDARPX->tdarpno,
                            'tdrp_assedvalue' => $f56->tdrp_assedvalue,
                            'tax_due' => $f56->tdrp_assedvalue / 100,
                            'barangay_name' => isset($f56->TDARPX->barangay_name) ? $f56->TDARPX->barangay_name->name : '',
                            'tax_type' => $f56->F56Type->abbrev,
                            'arp' => $f56->TDARPX->tdarpno,
                            'penalty' => $f56->basic_penalty_current + $f56->basic_penalty_previous,
                            'discount' => $f56->basic_discount,
                            'kind' => !empty($query[0]) ? $query[0]->kind : null,
                            'actual_use' => !empty($query[0]) ? $query[0]->actual_use : null,
                        ];
                    }

                    if(!isset($yearly[$f56->period_covered])){
                        $yearly[$f56->period_covered] = [
                            'sef' => $f56->basic_current + $f56->basic_previous,
                            'discount' => $f56->basic_discount,
                            'penalty' => $f56->basic_penalty_current + $f56->basic_penalty_previous,
                            'total' => $f56->basic_current - $f56->basic_discount + $f56->basic_previous + $f56->basic_penalty_current + $f56->basic_penalty_previous,
                            'assess_val' => $f56->tdrp_assedvalue,
                            'period_covered' => $f56->period_covered,
                            'arp' => $f56->TDARPX->tdarpno,
                            'penalty' => $f56->basic_penalty_current + $f56->basic_penalty_previous,
                            'discount' => $f56->basic_discount,
                        ];
                    }else if(isset($tax_decs[$f56->owner_name][$f56->TDARPX->tdarpno])){ // for arp's 93 and below only
                        $arp = explode("-", $tax_decs[$f56->owner_name][$f56->TDARPX->tdarpno]['tax_due']);
                        if($arp[0] < 94) {
                            $yearly[$f56->period_covered] = [
                                'sef' => $f56->manual_tax_due,
                                'discount' => $f56->basic_discount,
                                'penalty' => $f56->basic_penalty_current + $f56->basic_penalty_previous,
                                'total' => $f56->basic_current - $f56->basic_discount + $f56->basic_previous + $f56->basic_penalty_current + $f56->basic_penalty_previous,
                                'assess_val' => $f56->tdrp_assedvalue,
                                'period_covered' => $f56->period_covered,
                                'arp' => $f56->TDARPX->tdarpno,
                                'penalty' => $f56->basic_penalty_current + $f56->basic_penalty_previous,
                                'discount' => $f56->basic_discount,
                            ];
                        }
                    }else{
                        $yearly[$f56->period_covered]['sef'] += $f56->basic_current + $f56->basic_previous;
                        $yearly[$f56->period_covered]['discount'] += $f56->basic_discount;
                        $yearly[$f56->period_covered]['penalty'] += $f56->basic_penalty_current + $f56->basic_penalty_previous;
                        $yearly[$f56->period_covered]['total'] += $f56->basic_current - $f56->basic_discount + $f56->basic_previous + $f56->basic_penalty_current + $f56->basic_penalty_previous;
                        $yearly[$f56->period_covered]['assess_val'] += $f56->tdrp_assedvalue;
                        $yearly[$f56->period_covered]['period_covered'] = $f56->period_covered; 
                        $yearly[$f56->period_covered]['discount'] += $f56->basic_discount;
                    }
                    $total += $f56->basic_current - $f56->basic_discount + $f56->basic_previous + $f56->basic_penalty_current + $f56->basic_penalty_previous;
                }
                $tr['tax_decs'] = $tax_decs;
                $tr['yearly'] = $yearly;
                $tr['full'] = $full;
                $tr['partial'] = $partial;
                $tr['total'] = $total * 2;

                return $tr;
    }

    private function get_annual_per_arp($receipt){
        $yearly = [];
        foreach ($receipt->F56Detailmny as $key => $f56) {
            $query = DB::connection('mysql2')->select(DB::raw('select kind, actual_use, prevs_tax_dec_no from tax_dec_archive_info 
                join tax_dec_archive_kind_class on tax_dec_archive_info.id = tax_dec_archive_kind_class.tax_dec_id 
                where tax_dec_no = "'.$f56->TDARPX->tdarpno.'" order by tax_dec_archive_kind_class.id'));

            if(!isset($yearly[$f56->period_covered])){
                if(!isset($tr[$f56->TDARPX->tdarpno][$f56->period_covered]) && !isset($yearly[$f56->TDARPX->tdarpno][$f56->period_covered])) {
                    $tr[$f56->TDARPX->tdarpno]['owner'] = $f56->owner_name;
                    $tr[$f56->TDARPX->tdarpno]['entry_date'] = $receipt->date_of_entry;
                    $tr[$f56->TDARPX->tdarpno]['prev_tax_dec_no'] = !empty($query[0]) ? $query[0]->prevs_tax_dec_no : null;
                    $tr[$f56->TDARPX->tdarpno]['assess_val'] = $f56->tdrp_assedvalue;
                    if(!isset($tr[$f56->TDARPX->tdarpno]['assess_val_class'])) {
                        $tr[$f56->TDARPX->tdarpno]['assess_val_class'] = [];
                    }
                    array_push($tr[$f56->TDARPX->tdarpno]['assess_val_class'], array(
                        'kind' => !empty($query[0]) ? $query[0]->kind : null,
                        'actual_use' => !empty($query[0]) ? $query[0]->actual_use : null,
                        'assess_val' => $f56->tdrp_assedvalue,
                    ));
                    $tr[$f56->TDARPX->tdarpno]['assess_val_class'] = array_unique($tr[$f56->TDARPX->tdarpno]['assess_val_class'], SORT_REGULAR);
                    // $tr[$f56->TDARPX->tdarpno]['kind'] = !empty($query[0]) ? $query[0]->kind : null;
                    // $tr[$f56->TDARPX->tdarpno]['actual_use'] = !empty($query[0]) ? $query[0]->actual_use : null;
                    $yearly[$f56->TDARPX->tdarpno][$f56->period_covered] = [
                        'sef' => $f56->basic_current + $f56->basic_previous,
                        'discount' => $f56->basic_discount,
                        'penalty' => $f56->basic_penalty_current + $f56->basic_penalty_previous,
                        'total' => $f56->basic_current - $f56->basic_discount + $f56->basic_previous + $f56->basic_penalty_current + $f56->basic_penalty_previous,
                        'assess_val' => $f56->tdrp_assedvalue,
                        'period_covered' => $f56->period_covered,
                        'tax_type' => $f56['F56Type']->abbrev,
                        'brgy' => Barangay::find($f56['TDARPX']->barangay),
                        'kind' => !empty($query[0]) ? $query[0]->kind : null,
                        'actual_use' => !empty($query[0]) ? $query[0]->actual_use : null,
                        'prev_tax_dec_no' => !empty($query[0]) ? $query[0]->prevs_tax_dec_no : null,
                        'full_partial' => $f56->full_partial,
                    ];
                } else {
                    $tr[$f56->TDARPX->tdarpno]['assess_val'] += $f56->tdrp_assedvalue;
                    if(!isset($tr[$f56->TDARPX->tdarpno]['assess_val_class'])) {
                        $tr[$f56->TDARPX->tdarpno]['assess_val_class'] = [];
                    }
                    array_push($tr[$f56->TDARPX->tdarpno]['assess_val_class'], array(
                        'kind' => !empty($query[0]) ? $query[0]->kind : null,
                        'actual_use' => !empty($query[0]) ? $query[0]->actual_use : null,
                        'assess_val' => $f56->tdrp_assedvalue,
                    ));
                    $tr[$f56->TDARPX->tdarpno]['assess_val_class'] = array_unique($tr[$f56->TDARPX->tdarpno]['assess_val_class'], SORT_REGULAR);

                    $yearly[$f56->TDARPX->tdarpno][$f56->period_covered]['sef'] += $f56->basic_current + $f56->basic_previous;
                    $yearly[$f56->TDARPX->tdarpno][$f56->period_covered]['discount'] += $f56->basic_discount;
                    $yearly[$f56->TDARPX->tdarpno][$f56->period_covered]['penalty'] += $f56->basic_penalty_current + $f56->basic_penalty_previous;
                    $yearly[$f56->TDARPX->tdarpno][$f56->period_covered]['total'] += $f56->basic_current - $f56->basic_discount + $f56->basic_previous + $f56->basic_penalty_current + $f56->basic_penalty_previous;
                    $yearly[$f56->TDARPX->tdarpno][$f56->period_covered]['assess_val'] += $f56->tdrp_assedvalue;
                    $yearly[$f56->TDARPX->tdarpno][$f56->period_covered]['period_covered'] = $f56->period_covered; 
                    $yearly[$f56->TDARPX->tdarpno][$f56->period_covered]['tax_type'] = $f56['F56Type']->abbrev;
                    $yearly[$f56->TDARPX->tdarpno][$f56->period_covered]['brgy'] = Barangay::find($f56['TDARPX']->barangay);
                    $yearly[$f56->TDARPX->tdarpno][$f56->period_covered]['kind'] = !empty($query[0]) ? $query[0]->kind : null;
                    $yearly[$f56->TDARPX->tdarpno][$f56->period_covered]['actual_use'] = !empty($query[0]) ? $query[0]->actual_use : null;
                    $yearly[$f56->TDARPX->tdarpno][$f56->period_covered]['prev_tax_dec_no'] = !empty($query[0]) ? $query[0]->prevs_tax_dec_no : null;
                    $yearly[$f56->TDARPX->tdarpno][$f56->period_covered]['full_partial'] = $f56->full_partial;
                }
            }else if(isset($tax_decs[$f56->owner_name][$f56->TDARPX->tdarpno])){ // for arp's 93 and below only
                $arp = explode("-", $tax_decs[$f56->owner_name][$f56->TDARPX->tdarpno]['tax_due']);
                if($arp[0] < 94) {
                    $tr[$f56->TDARPX->tdarpno]['owner'] = $f56->owner_name;
                    $tr[$f56->TDARPX->tdarpno]['entry_date'] = $receipt->date_of_entry;
                    $tr[$f56->TDARPX->tdarpno]['prev_tax_dec_no'] = !empty($query[0]) ? $query[0]->prevs_tax_dec_no : null;
                    $tr[$f56->TDARPX->tdarpno]['assess_val'] = $f56->tdrp_assedvalue;
                    // $tr[$f56->TDARPX->tdarpno]['kind'] = !empty($query[0]) ? $query[0]->kind : null;
                    // $tr[$f56->TDARPX->tdarpno]['actual_use'] = !empty($query[0]) ? $query[0]->actual_use : null;
                    $yearly[$f56->TDARPX->tdarpno][$f56->period_covered] = [
                        'sef' => $f56->manual_tax_due,
                        'discount' => $f56->basic_discount,
                        'penalty' => $f56->basic_penalty_current + $f56->basic_penalty_previous,
                        'total' => $f56->basic_current - $f56->basic_discount + $f56->basic_previous + $f56->basic_penalty_current + $f56->basic_penalty_previous,
                        'assess_val' => $f56->tdrp_assedvalue,
                        'period_covered' => $f56->period_covered,
                        'tax_type' => $f56['F56Type']->abbrev,
                        'brgy' => Barangay::find($f56['TDARPX']->barangay),
                        'kind' => !empty($query[0]) ? $query[0]->kind : null,
                        'actual_use' => !empty($query[0]) ? $query[0]->actual_use : null,
                        'prev_tax_dec_no' => !empty($query[0]) ? $query[0]->prevs_tax_dec_no : null,
                        'full_partial' => $f56->full_partial,
                    ];
                }
            }else{
                $yearly[$f56->TDARPX->tdarpno][$f56->period_covered]['sef'] += $f56->basic_current + $f56->basic_previous;
                $yearly[$f56->TDARPX->tdarpno][$f56->period_covered]['discount'] += $f56->basic_discount;
                $yearly[$f56->TDARPX->tdarpno][$f56->period_covered]['penalty'] += $f56->basic_penalty_current + $f56->basic_penalty_previous;
                $yearly[$f56->TDARPX->tdarpno][$f56->period_covered]['total'] += $f56->basic_current - $f56->basic_discount + $f56->basic_previous + $f56->basic_penalty_current + $f56->basic_penalty_previous;
                $yearly[$f56->TDARPX->tdarpno][$f56->period_covered]['assess_val'] += $f56->tdrp_assedvalue;
                $yearly[$f56->TDARPX->tdarpno][$f56->period_covered]['period_covered'] = $f56->period_covered; 
                $yearly[$f56->TDARPX->tdarpno][$f56->period_covered]['tax_type'] = $f56['F56Type']->abbrev;
                $yearly[$f56->TDARPX->tdarpno][$f56->period_covered]['brgy'] = Barangay::find($f56['TDARPX']->barangay);
                $yearly[$f56->TDARPX->tdarpno][$f56->period_covered]['kind'] = !empty($query[0]) ? $query[0]->kind : null;
                $yearly[$f56->TDARPX->tdarpno][$f56->period_covered]['actual_use'] = !empty($query[0]) ? $query[0]->actual_use : null;
                $yearly[$f56->TDARPX->tdarpno][$f56->period_covered]['prev_tax_dec_no'] = !empty($query[0]) ? $query[0]->prevs_tax_dec_no : null;
                $yearly[$f56->TDARPX->tdarpno][$f56->period_covered]['full_partial'] = $f56->full_partial;
            }
        }
        // reverse order (fr. current year)
        // $reverse = [];
        // $arps = array_keys($yearly);
        // $vals = array_values($yearly);
        // for($i = 0; $i < count($arps); $i++) {
        //     $pr_yr_val = array_values($vals[$i]);
        //     $pr_yr_key = array_keys($vals[$i]);
        //     $semi_arr = [];

        //     for($j = count($pr_yr_val)-1; $j >= 0; $j--) {
        //         $semi_arr[$pr_yr_key[$j]] = $pr_yr_val[$j];
        //     }
        //     $reverse[$arps[$i]] = $semi_arr;
        // }

        $tr['yearly'] = $yearly; // orig
        // $tr['yearly'] = $reverse;
        return $tr;
    }

    public function rpt_delinquent() {
        $this->base['yr'] = Carbon::now()->format('Y');
        $this->base['sub_header'] = 'Delinquent Payors';
        $this->base['user'] = Session::get('user');
        return view('collection::form56.rpt_delinquents_index')->with('base', $this->base);
    }

    public function delinquents_tbl(Request $req) {
        // $req_date = Carbon::createFromDate($req->year, 12, 31)->format('Y-m-d');
        $req_date = Carbon::now()->format('Y-m-d');
        if($req->month != 'all') {
            $delqnts = Receipt::with('items')->with('F56Detailmny')->distinct()
            ->select('cust_last_pd.col_customer_id', 'name', 'cust_last_pd.last_pd', DB::raw('datediff('.$req_date.', cust_last_pd.last_pd) as datedif'))
            ->join(DB::raw('(select col_customer_id, max(report_date) as last_pd from col_receipt group by col_customer_id) as cust_last_pd'), 'col_receipt.col_customer_id', '=', 'cust_last_pd.col_customer_id')
            ->join('col_customer', 'cust_last_pd.col_customer_id', '=', 'col_customer.id')
            ->where('is_cancelled', 0)
            ->where('is_printed', 1)
            ->whereYear('cust_last_pd.last_pd', '=', $req->year)
            ->whereMonth('cust_last_pd.last_pd', '=', $req->month)
            ->whereRaw('datediff("'.$req_date.'", cust_last_pd.last_pd) > 365')
            ->orderBy('cust_last_pd.last_pd', 'desc')
            ->get();
            return Datatables::of($delqnts)->make(true);
        } else {
            $delqnts = Receipt::with('items')->with('F56Detailmny')->distinct()
            ->select('cust_last_pd.col_customer_id', 'name', 'cust_last_pd.last_pd', DB::raw('datediff('.$req_date.', cust_last_pd.last_pd) as datedif'))
            ->join(DB::raw('(select col_customer_id, max(report_date) as last_pd from col_receipt group by col_customer_id) as cust_last_pd'), 'col_receipt.col_customer_id', '=', 'cust_last_pd.col_customer_id')
            ->join('col_customer', 'cust_last_pd.col_customer_id', '=', 'col_customer.id')
            ->where('is_cancelled', 0)
            ->where('is_printed', 1)
            ->whereYear('cust_last_pd.last_pd', '=', $req->year)
            ->whereRaw('datediff("'.$req_date.'", cust_last_pd.last_pd) > 365')
            ->orderBy('cust_last_pd.last_pd', 'desc')
            ->get();
            return Datatables::of($delqnts)->make(true);
        }    
    }

    public function generate_notice($mnth, $yr, $date) {
        $mnth_split = explode(' ', $mnth);
        $req_date = Carbon::now()->format('Y-m-d');
        $per_payor = [];
        if($mnth != 'all') {
            if(isset($date)) {
                $per_date = Carbon::createFromDate($yr, $mnth_split[0], $date)->format('Y-m-d');
                $delqnts = Receipt::distinct()
                    ->select('cust_last_pd.col_customer_id', 'name', 'cust_last_pd.last_pd', 'cust_last_pd.last_serial', DB::raw('datediff('.$req_date.', cust_last_pd.last_pd) as datedif'))
                    ->join(DB::raw('(select col_customer_id, max(report_date) as last_pd, max(serial_no) as last_serial from col_receipt where is_printed = 1 and is_cancelled = 0 group by col_customer_id) as cust_last_pd'), 'col_receipt.col_customer_id', '=', 'cust_last_pd.col_customer_id')
                    ->join('col_customer', 'cust_last_pd.col_customer_id', '=', 'col_customer.id')
                    ->where('is_cancelled', 0)
                    ->where('is_printed', 1)
                    ->whereDate('cust_last_pd.last_pd', '=', $per_date)
                    ->whereRaw('datediff("'.$req_date.'", cust_last_pd.last_pd) > 365')
                    ->orderBy('cust_last_pd.last_pd', 'desc')
                    ->get();
            } else {
                $delqnts = Receipt::distinct()
                    ->select('cust_last_pd.col_customer_id', 'name', 'cust_last_pd.last_pd', DB::raw('datediff('.$req_date.', cust_last_pd.last_pd) as datedif'))
                    ->join(DB::raw('(select col_customer_id, max(report_date) as last_pd from col_receipt group by col_customer_id) as cust_last_pd'), 'col_receipt.col_customer_id', '=', 'cust_last_pd.col_customer_id')
                    ->join('col_customer', 'cust_last_pd.col_customer_id', '=', 'col_customer.id')
                    ->where('is_cancelled', 0)
                    ->where('is_printed', 1)
                    ->whereYear('cust_last_pd.last_pd', '=', $yr)
                    ->whereMonth('cust_last_pd.last_pd', '=', $mnth_split[0])
                    ->whereRaw('datediff("'.$req_date.'", cust_last_pd.last_pd) > 365')
                    ->orderBy('cust_last_pd.last_pd', 'desc')
                    ->get();
            }
        } else {
            $delqnts = Receipt::distinct()
                ->select('cust_last_pd.col_customer_id', 'name', 'cust_last_pd.last_pd', DB::raw('datediff('.$req_date.', cust_last_pd.last_pd) as datedif'))
                ->join(DB::raw('(select col_customer_id, max(report_date) as last_pd from col_receipt group by col_customer_id) as cust_last_pd'), 'col_receipt.col_customer_id', '=', 'cust_last_pd.col_customer_id')
                ->join('col_customer', 'cust_last_pd.col_customer_id', '=', 'col_customer.id')
                ->where('is_cancelled', 0)
                ->where('is_printed', 1)
                ->whereYear('cust_last_pd.last_pd', '=', $yr)
                ->whereRaw('datediff("'.$req_date.'", cust_last_pd.last_pd) > 365')
                ->orderBy('cust_last_pd.last_pd', 'desc')
                ->get();
        }
        foreach($delqnts as $key => $record) {
            $per_payor[$record->col_customer_id] = DB::connection('mysql2')->select(DB::raw('select tax_dec_no, municipality, brgy, tdak.land_area, measurement, tdak.assessed_value, class from tax_dec_archive_info tda join tax_dec_loc_property tdl on tda.loc_property_id = tdl.id join tax_dec_archive_kind_class tdak on tdak.tax_dec_id = tda.id where owner_id = '.$record->col_customer_id));
        }
        $this->base['tdarp_details'] = $per_payor;
        $this->base['delinquents'] = $delqnts;
        $this->base['municipalities'] = Municipality::all();
        $this->base['prov_trea'] = ReportOfficerNew::where('position_name', 4)->first();
        $this->base['prov_asst_trea'] = ReportOfficerNew::where('position_name', 5)->first();
        $pdf = new PDF;
        $pdf = PDF::loadView('collection::pdf.rpt_delqnt_notice', $this->base)->setPaper(array(0,0,612,936), 'portrait');
        return $pdf->stream();
    }

    public function view_delinquent($id) {
        $delqnt = Customer::find($id);
        $tdarp = DB::connection('mysql2')->select(DB::raw('select tax_dec_no, municipality, brgy, tdak.land_area, measurement, tdak.assessed_value, class, tda.remarks from tax_dec_archive_info tda join tax_dec_loc_property tdl on tda.loc_property_id = tdl.id join tax_dec_archive_kind_class tdak on tdak.tax_dec_id = tda.id where owner_id = '.$id));
        $last_pd = DB::select(DB::raw('select col_customer_id, max(report_date) as last_pd from col_receipt where col_customer_id = '.$id.' group by col_customer_id'));
        $this->base['payor_last_pd'] = $last_pd[0]->last_pd;
        $this->base['yr'] = Carbon::now()->format('Y');
        $this->base['sub_header'] = 'Delinquent Payors';
        $this->base['user'] = Session::get('user');
        $this->base['delqnt'] = $delqnt;
        $this->base['tdarp'] = $tdarp;
        $munic_query = Municipality::all();
        $munic_array = [];
        foreach($munic_query as $munic) {
            $munic_array[$munic->id] = $munic->name;
        }
        $brgy_query = Barangay::all();
        $brgy_array = [];
        foreach($brgy_query as $brgy) {
            $brgy_array[$brgy->id] = $brgy->name;
        }
        $this->base['munic'] = $munic_array;
        $this->base['brgy'] = $brgy_array;
        return view('collection::form56.rpt_delinquents_view')->with('base', $this->base);
    }

    public function edit_delinquent($id) {
        $delqnt = Customer::find($id);
        $tdarp = DB::connection('mysql2')->select(DB::raw('select tda.id, tax_dec_no, municipality, brgy, tdak.land_area, measurement, tdak.assessed_value, class, tda.remarks from tax_dec_archive_info tda join tax_dec_loc_property tdl on tda.loc_property_id = tdl.id join tax_dec_archive_kind_class tdak on tdak.tax_dec_id = tda.id where owner_id = '.$id));
        $last_pd = DB::select(DB::raw('select col_customer_id, max(report_date) as last_pd from col_receipt where col_customer_id = '.$id.' group by col_customer_id'));
        $this->base['payor_last_pd'] = $last_pd[0]->last_pd;
        $this->base['delqnt'] = $delqnt;
        $this->base['tdarp'] = $tdarp;
        $this->base['munic'] = Municipality::where('code', '!=', "00")->get();
        $this->base['brgy'] = Barangay::all();
        $this->base['sub_header'] = 'Delinquent Payors';
        $this->base['user'] = Session::get('user');
        $this->base['delq_id'] = $id;
        return view('collection::form56.rpt_delinquents_edit')->with('base', $this->base);
    }

    public function edit_delinquent_autofill(Request $req) {
        $brgys = Barangay::where('municipality_id', $req->mun_id)->get()->toArray();
        return $brgys;
    }

    public function update_arp(Request $req) {
        // dd($req);
        for($i = 0; $i < count($req); $i++) {
            DB::connection('mysql2')->update(DB::raw('update tax_dec_archive_info tda join tax_dec_loc_property tdl on tda.loc_property_id = tdl.id join tax_dec_archive_kind_class tdak on tdak.tax_dec_id = tda.id set tax_dec_no = "'.$req->arp[$i].'", municipality = '.$req->arp_munic[$i].', brgy = '.$req->arp_brgy[$i].', class = "'.$req->arp_class[$i].'", tdak.land_area = '.$req->arp_area[$i].', tdak.assessed_value = "'.$req->arp_assess_val[$i].'", remarks = "'.$req->remarks[$i].'" where owner_id = '.$req->delq_id));
        }
        return redirect()->route('rpt.delqnt_edit', $req->delq_id);
    }

    public function download_delq_format() {
        Excel::create('DELINQUENTS MASTERLIST', function($excel) {
            $excel->sheet('Masterlist', function($sheet) {
                $sheet->mergeCells('A1:L1');
                $sheet->mergeCells('A2:L2');
                $sheet->mergeCells('A3:L3');
                $sheet->mergeCells('A4:L4');
                $sheet->mergeCells('A5:L5');
                $sheet->mergeCells('A1:L1');
                $sheet->mergeCells('A7:I7');
                $sheet->mergeCells('J7:K7');
                $sheet->mergeCells('A8:A9');
                $sheet->mergeCells('B8:B9');
                $sheet->mergeCells('C8:C9');
                $sheet->mergeCells('D8:D9');
                $sheet->mergeCells('E8:E9');
                $sheet->mergeCells('F8:F9');
                $sheet->mergeCells('G8:G9');
                $sheet->mergeCells('H8:H9');
                $sheet->mergeCells('I8:I9');
                $sheet->mergeCells('J8:J9');
                $sheet->mergeCells('K8:K9');
                $sheet->mergeCells('L8:L9');

                $sheet->cells('A8:L8', function($cell) {
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });
                $sheet->setWidth('A', 25);
                $sheet->setWidth('B', 25);
                $sheet->setWidth('C', 25);
                $sheet->setWidth('D', 25);
                $sheet->cells('A1:L1', function($cell) {
                    $cell->setAlignment('center');
                });
                $sheet->cells('A2:L2', function($cell) {
                    $cell->setAlignment('center');
                });
                $sheet->cells('A3:L3', function($cell) {
                    $cell->setAlignment('center');
                });
                $sheet->cells('A4:L4', function($cell) {
                    $cell->setAlignment('center');
                });
                $sheet->cells('A5:L5', function($cell) {
                    $cell->setAlignment('center');
                });
                $sheet->setAllBorders('thin');
                $sheet->setColumnFormat(array('G:L' => '0.00'));
                $sheet->loadView('collection::excel.rpt_delq_masterlist');
            });
        })->export('xlsx');
    }

    public function rpt_import_excel(Request $req) {
        $validator = Validator::make($req->all(), [
            'excel_delq.*' => 'required|file|mimes:xlsx,xls'
        ]);
        $data_array = [];
        if ($validator->fails()) {
            Session::flash('error', ['File type not allowed. Please upload xlsx or xls file types only']);
            return redirect()->route('rpt.delinquent');
        } else {
            try {
                $path = $req->file('excel_delq')->getRealPath(); 
                Excel::load($path, function($reader) {
                    $data = $reader->get()->toArray();
                })->get();
                return redirect()->route('rpt.delinquent');
            } catch (\Exception $e) {
                Session::flash('error', ["Unable to read file"]);
                return redirect()->route('rpt.delinquent');
            }
        }
    }

    public function get_previous_rcpt(Request $req) {
        $data = [];
        if($req->tax_dec != null && $req->tax_dec != "") {
            // if($req->client_id == null || $req->client_id == '' || $req->client_id == 0) {
                $receipt_latest = Receipt::leftjoin('col_f56_detail', 'col_f56_detail.col_receipt_id', '=', 'col_receipt.id')
                    ->leftjoin('col_f56_tdarp', 'col_f56_tdarp.col_f56_detail_id', '=', 'col_f56_detail.id')
                    ->select('col_receipt.id', 'serial_no', 'date_of_entry', 'period_covered')
                    ->where('af_type', 2)
                    ->where('tdarpno','LIKE','%'.$req->tax_dec.'%')
                    ->orderBy('period_covered', 'DESC')
                    ->orderBy('date_of_entry', 'DESC')
                    ->first();

                if(!empty($receipt_latest)) {
                   $receipt_latest_deets = F56Detail::where('col_receipt_id', $receipt_latest->id)
                       ->select('period_covered')
                       ->orderBy('period_covered', 'DESC')
                       ->get();
                }

                $data['period_covered'] = '';
                $data['prev_receipt_no'] = '';
                $data['prev_date'] = '';
                if(!empty($receipt_latest)) {
                    if(!empty($receipt_latest_deets)) {
                        $data['prev_receipt_no'] = $receipt_latest->serial_no;
                        $data['prev_date'] = Carbon::parse($receipt_latest->date_of_entry)->format('Y-m-d');
                    }
                    $prev_count = count($receipt_latest_deets) - 1;
                    if($receipt_latest_deets[$prev_count]->period_covered != $receipt_latest_deets[0]->period_covered) {
                        $data['period_covered'] = $receipt_latest_deets[$prev_count]->period_covered.'-'.$receipt_latest_deets[0]->period_covered;
                    } else {
                        $data['period_covered'] = $receipt_latest_deets[$prev_count]->period_covered;
                    }
                }
            // } else {
                // $receipt_latest = Receipt::leftjoin('col_f56_detail', 'col_f56_detail.col_receipt_id', '=', 'col_receipt.id')
                //     ->leftjoin('col_f56_tdarp', 'col_f56_tdarp.col_f56_detail_id', '=', 'col_f56_detail.id')
                //     ->select('col_receipt.id', 'serial_no', 'date_of_entry', 'period_covered')
                //     ->where('col_customer_id', $req->client_id)
                //     ->where('af_type', 2)
                //     ->where('tdarpno','LIKE','%'.$req->tax_dec.'%')
                //     ->orderBy('period_covered', 'DESC')
                //     ->orderBy('date_of_entry', 'DESC')
                //     ->first();

                // if(!empty($receipt_latest)) {
                //    $receipt_latest_deets = F56Detail::where('col_receipt_id', $receipt_latest->id)
                //        ->select('period_covered')
                //        ->orderBy('period_covered', 'DESC')
                //        ->get();
                // }

                // $data['period_covered'] = '';
                // $data['prev_receipt_no'] = '';
                // $data['prev_date'] = '';
                // if(!empty($receipt_latest)) {
                //     if(!empty($receipt_latest_deets)) {
                //         $data['prev_receipt_no'] = $receipt_latest->serial_no;
                //         $data['prev_date'] = Carbon::parse($receipt_latest->date_of_entry)->format('Y-m-d');
                //     }
                //     $prev_count = count($receipt_latest_deets) - 1;
                //     if($receipt_latest_deets[$prev_count]->period_covered != $receipt_latest_deets[0]->period_covered) {
                //         $data['period_covered'] = $receipt_latest_deets[$prev_count]->period_covered.'-'.$receipt_latest_deets[0]->period_covered;
                //     } else {
                //         $data['period_covered'] = $receipt_latest_deets[$prev_count]->period_covered;
                //     }
                // }
            // }
        } 
        // else {
        //     $receipt_latest = Receipt::leftjoin('col_f56_detail', 'col_f56_detail.col_receipt_id', '=', 'col_receipt.id')
        //         ->select('col_receipt.id', 'serial_no', 'date_of_entry', 'period_covered')
        //         ->where('col_customer_id', $req->client_id)
        //         ->where('af_type', 2)
        //         ->orderBy('period_covered', 'DESC')
        //         ->orderBy('date_of_entry', 'DESC')
        //         ->first();

        //     if(!empty($receipt_latest)) {
        //         $receipt_latest_deets = F56Detail::where('col_receipt_id', $receipt_latest->id)
        //             ->select('period_covered')
        //             ->orderBy('period_covered', 'DESC')
        //             ->get();
        //     }

        //     $data['period_covered'] = '';
        //     $data['prev_receipt_no'] = '';
        //     $data['prev_date'] = '';
        //     if(!empty($receipt_latest)) {
        //         if(!empty($receipt_latest_deets)) {
        //             $data['prev_receipt_no'] = $receipt_latest->serial_no;
        //             $data['prev_date'] = Carbon::parse($receipt_latest->date_of_entry)->format('Y-m-d');
        //         }
        //         $prev_count = count($receipt_latest_deets) - 1;
        //         if($receipt_latest_deets[$prev_count]->period_covered != $receipt_latest_deets[0]->period_covered) {
        //             $data['period_covered'] = $receipt_latest_deets[$prev_count]->period_covered.'-'.$receipt_latest_deets[0]->period_covered;
        //         } else {
        //             $data['period_covered'] = $receipt_latest_deets[$prev_count]->period_covered;
        //         }
        //     }
        // }

        // if($req->tax_dec != "" && $req->client_id != "") {
        //     $data['period_covered'] = '';
        //     $data['prev_receipt_no'] = '';
        //     $data['prev_date'] = '';
        // }
        return $data;
    }

    // public function taxdec_parser(Request $req) {
    //     $get_taxdec_id = DB::connection('mysql2')->select(DB::raw('select id from tax_dec_archive_info where tax_dec_no ='.$req->tax_dec));
    //     if(!is_null($get_taxdec_id)) {
    //         $get_taxdec_path = DB::connection('mysql2')->select(DB::raw('select path_img from tax_dec_archive_docs where tax_dec_id ='.$get_taxdec_id[0]->id));
    //     } else {
    //         return 'TAX DECLARATION NOT FOUND';
    //     }

    //     $parser = new Parser();
    //     $pdf = $parser->parseFile();
    // }
}
