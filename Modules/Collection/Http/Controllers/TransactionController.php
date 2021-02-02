<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\{Controller,BreadcrumbsController};

use Illuminate\Http\{Request,Response};
use Session,Validator,DB,Carbon\Carbon;
use Modules\Collection\Entities\{
                                    SandGravelTypes as sg_types,
                                    Customer,Form,Municipality,Barangay,Receipt,WithCert,ReceiptItems,Serial,TransactionType,CollectionRate,
                                    F56Type,F56Detail,F56TDARP,WeekdayHoliday,ReceiptItemDetail,AdaSettings,RcptCertificate,ReportOfficers,
                                    IsManySerials,RcptCertificateType,PCSettings,SGbooklet
                                };
class TransactionController extends Controller
{
    protected $receipt;

    public function __construct(Request $request, Receipt $receipt)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'Land Tax Collections';
        $this->receipt = $receipt;
        $this->base['ada_settings'] = AdaSettings::get();
        $this->base['request_ip'] = $request->ip();
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $dt = Carbon::now();



        $yr = $dt->format('Y');
        $mnth = $dt->format('m');
        $this->base['sub_header'] = 'New';
        $this->base['form'] = Form::all();
        $this->base['transaction_type'] = TransactionType::all();
        $this->base['municipalities'] = Municipality::all()->toarray();
        $this->base['user'] = Session::get('user');
        $this->base['sandgravel_types'] = sg_types::all();

        $months = array();
        for ($month = 1; $month <= 12; $month++) {
            $months[$month] = date('F', mktime(0,0,0,$month));
        }
        $this->base['yr'] = $yr;
        $this->base['mnth'] = $mnth;
        $this->base['months'] = $months;

        $check = new PCSettings;
        $check = $check->pc_receipts()
                                    ->where('col_pc_settings.pc_ip','=',$this->base['request_ip'])
                                    ->where('col_receipt.is_printed','=',0)
                                    ->get();
        $this->base['check_unprinted'] = count($check);
        if(count($check) > 0){
             Session::flash('danger', ['There are '.count($check).' UNPRINTED RECEIPT/s .']);
        }

        return view('collection::receipt.index', $this->base)->with('base', $this->base);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $cert = null;
        $serial = Serial::whereId($request['serial_id'])->first();
        if($request['form'] == 2){
            $filter = [
            'user_id' => 'required|numeric',
            'date' => 'required',
            'serial_id' => 'required|numeric',
            'customer' => 'required',
            'transaction_type' => 'required',
            'brgy' => 'required',
            'municipality' => 'required',
            'amount.*' => 'required|min:0',
            ];
        }else{
            $filter = [
            'user_id' => 'required|numeric',
            'date' => 'required',
            'serial_id' => 'required|numeric',
            'customer' => 'required',
            'transaction_type' => 'required',
            'amount.*' => 'required|min:0',
            ];
        }
        
        $receipt_checker = null;
        $current = null;
        if(!is_null($serial)) {
            $receipt_checker = Receipt::where('serial_no','=',$serial->serial_current)->first();
            $current = $serial->serial_current;
        } else {
            Session::flash('danger', ['Cannot find serial. Please refresh and try again.']);
            return back();
        }

        if ($receipt_checker != null) {
            Session::flash('danger', ['This SERIAL is already in use Please contact ADMINISTRATOR: '.$current]);
            return back();
        }

        if (in_array(1, $request['account_is_shared'])) {
            $filter['municipality'] = 'required';
            $filter['brgy'] = 'required';
        }
        $validator = Validator::make($request->all(), $filter);
        if ($validator->fails()) {
            return redirect()->route('receipt.index')
            ->withErrors($validator);
        } elseif (in_array('', $request['account_id'])) {
            $validator->getMessageBag()
            ->add('account', 'An account field is empty or not identified');
            return redirect()->route('receipt.index')
            ->withErrors($validator);
        } elseif ($serial->serial_current == 0) {
            $validator->getMessageBag()
            ->add('serial', 'Series `'.$serial->serial_begin.'-'.$serial->serial_end.'` is finished. Please use another serial.');
            return redirect()->route('receipt.index')
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

        $is_printed = 0;
        if ($request['form'] == 2 || $request['transaction_source'] == "field_land_tax") {
            $is_printed = 1;
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
        $receipt = Receipt::updateOrCreate(
            [
                'serial_no' => $serial->serial_current
            ],
            [
                'af_type' => $request['form'],
                'col_serial_id' => $request['serial_id'],
                'col_municipality_id' => (!empty($request['municipality'])) ? $request['municipality'] : '',
                'col_barangay_id' => (!empty($request['brgy'])) ? $request['brgy'] : '',
                'dnlx_user_id' => $request['user_id'],
                'ip_addr' => $request->ip(),
                'col_customer_id' => $payor_id,
                'sex' => $request['Sex'],
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


        if (isset($request['sand_transaction'])) {
            if ($request['sand_transaction'] == 1) {
                $sg_booklets = [];
                foreach ($request['booklet_end'] as $key => $value) {
                    if( $request['booklet_start'][$key] != '' && $request['booklet_end'][$key] != ''   ){
                        $sg_booklets[] =    [
                                                'col_receipt_id'    => $receipt->id,
                                                'booklet_start'     => $request['booklet_start'][$key],
                                                'booklet_end'       => $request['booklet_end'][$key],
                                            ];
                    }
                }
                SGbooklet::insert($sg_booklets);

                $user = Session::get('user');
                $governor = ReportOfficers::whereId(1)->first()->value;
                $treasurer = ReportOfficers::whereId(2)->first()->value;
                $assttreasurer = ReportOfficers::whereId(9)->first()->value;
                $transfer_name = ReportOfficers::whereId(10)->first()->value;
                $transfer_position = ReportOfficers::whereId(11)->first()->value;
                $vice_gov = ReportOfficers::whereId(12)->first()->value;
                $provincial_note = $provincial_clearance_number = $provincial_type = $provincial_gov = $provincial_bidding = null;
                $transfer_notary_public = $transfer_ptr_number = $transfer_doc_number = $transfer_page_number = $transfer_book_number = $transfer_series = $transfer_prepare_name = $transfer_prepare_position = null;
                $sand_requestor = $sand_requestor_addr = $sand_requestor_sex = $sand_type = $sand_sandgravelprocessed = $sand_abc = $sand_sandgravel = $sand_boulders = null;
                $cert = RcptCertificate::create([
                    'col_receipt_id' => $receipt->id,
                    'col_rcpt_certificate_type_id' => 3,
                    'recipient' => '',
                    'address' => '',
                    'detail' => '',
                    'date_of_entry' => date('Y-m-d'),
                    'provincial_governor' => $governor,
                    'actingprovincial_governor' => $provincial_gov,
                    'provincial_treasurer' => $treasurer,
                    'asstprovincial_treasurer' => ($request['signee'] == 'provtreasurer') ? null : $assttreasurer,
                    'user' => $user->realname,
                    'provincial_note' => $provincial_note,
                    'provincial_clearance_number' => $provincial_clearance_number,
                    'provincial_type' => $provincial_type,
                    'provincial_bidding' => $provincial_bidding,
                    'transfer_notary_public' => $transfer_notary_public,
                    'transfer_ptr_number' => $transfer_ptr_number,
                    'transfer_doc_number' => $transfer_doc_number,
                    'transfer_page_number' => $transfer_page_number,
                    'transfer_book_number' => $transfer_book_number,
                    'transfer_series' => $transfer_series,
                    'transfer_prepare_name' => $transfer_name,
                    'transfer_prepare_position' => $transfer_position,
                    'sand_requestor' => $sand_requestor,
                    'sand_requestor_addr' => $sand_requestor_addr,
                    'sand_requestor_sex' => $sand_requestor_sex,
                    'sand_type' => $sand_type,
                    'sand_sandgravelprocessed' => $request['sand_sandgravelprocessed'],
                    'sand_abc' => $request['sand_abc'],
                    'sand_sandgravel' => $request['sand_sandgravel'],
                    'sand_boulders' => $request['sand_boulders'],
                    ]);
            }
        }

        if($request['with_cert']!='null' && !$cert){
              $WithCert =  new WithCert;
              $WithCert->trans_id = $receipt->id;
              $WithCert->cert_type = $request['with_cert'];
              $WithCert->process_status = 0;
              $WithCert->save();
        }


        foreach ($request['account_id'] as $i => $ai) {
            $rate_type = ($request['account_type'][$i] == 'title') ? 'col_acct_title_id' : 'col_acct_subtitle_id';
            $rate = CollectionRate::where($rate_type, '=', $request['account_id'][$i])->first();

            $share_provincial = $request['amount'][$i];
            $share_municipal = 0;
            $share_barangay = 0;
            if (!empty($rate) && $rate->is_shared == 1) {
                // $share_provincial = bcdiv($request['amount'][$i] * ($rate->sharepct_provincial / 100), 1, 2);
                // $share_municipal = bcdiv($request['amount'][$i] * ($rate->sharepct_municipal / 100), 1, 2);
                // $share_barangay = bcdiv($request['amount'][$i] * ($rate->sharepct_barangay / 100), 1, 2);
                $share_provincial = round(floatval(floatval($request['amount'][$i]) * floatval($rate->sharepct_provincial / 100)), 2);
                $share_municipal = round(floatval(floatval($request['amount'][$i]) * floatval($rate->sharepct_municipal / 100)), 2);
                $share_barangay = round(floatval(floatval($request['amount'][$i]) * floatval($rate->sharepct_barangay / 100)), 2);
                $total_shared = $share_provincial + $share_municipal + $share_barangay;
                if ($total_shared != $request['amount'][$i]) {
                    if($total_shared > $request['amount'][$i]){
                        // $diffx = $total_shared - $request['amount'][$i];
                        // $share_barangay = $share_barangay - $diffx;

                        // $share_barangay = round(floatval($total_shared), 2) - round(floatval($share_provincial), 2) - round(floatval($share_municipal), 2);
                        $share_municipal = round(floatval($request['amount'][$i]), 2) - round(floatval($share_provincial), 2) - round(floatval($share_barangay), 2);
                    } elseif($total_shared < $request['amount'][$i]) {
                        $diffx =  $request['amount'][$i] - $total_shared;
                        $share_provincial = $share_provincial + $diffx;
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
                'share_provincial' => round(floatval($share_provincial), 2),
                'share_municipal' => round(floatval($share_municipal), 2),
                'share_barangay' => round(floatval($share_barangay), 2),
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

        Session::flash('info', ['Successfully created transaction for serial: '.$current]);

        if($request['transaction_source'] == 'opag'){
            return redirect()->route('opag.index');
        }

        if($request['transaction_source'] == 'pvet'){
            return redirect()->route('pvet.index');
        }

        if($request['transaction_source'] == 'hospital_remittance'){
            return redirect()->route('hospital_remittance.index');
        }

        if ($request['form'] == 2) {
            if ($request['transaction_source'] == "receipt") {
                return redirect()->route('receipt.f56_detail_form', ['id' => $receipt->id]);
            }
            return redirect()->route('field_land_tax.f56_detail_form', ['id' => $receipt->id]);
        }

        if ($request['transaction_source'] == "receipt") {
            return redirect()->route('receipt.show', ['id' => $receipt->id]);
        }

        
        return redirect()->route('field_land_tax.index');
    }

    public function restore(Request $request){
            $receipt = Receipt::whereId($request->receipt)->first();
            $receipt->is_cancelled = 0;
            $receipt->save();
             // Session::flash();
            return [ 'info', ['This receipt '.$receipt->serial_no.' has been retored.']];
    }

    public function show($id)
    {
      $this->base['sub_header'] = 'View';
      $this->base['receipt'] = Receipt::whereId($id)->first();

      $this->base['total'] = 0;
      $items = $this->base['receipt']->items;
      foreach ($items as $item) {
         $this->base['total'] += $item->value;
     }

     $this->base['previous_id'] = Receipt::where('id', '<', $this->base['receipt']->id)
     ->where('transaction_source', '=', 'receipt')
     ->where('af_type', '=', $this->base['receipt']->af_type)
     ->max('id');
     $this->base['previous'] = Receipt::whereId($this->base['previous_id'])->first();
        // if (!is_null($this->base['previous']) && $this->base['previous']->is_printed == 0) {
        //     Session::flash('info', ['You may only print this receipt after printing the previous one.']);
        // }

     return view('collection::receipt.view')->with('base', $this->base);
 }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
         $this->base['sandgravel_types'] = sg_types::all();
        $this->base['rcpt_certificatetype'] = RcptCertificateType::get();
         $this->base['cert'] = RcptCertificate::where('col_receipt_id', '=', $id)->first();
         $this->base['withcert'] =WithCert::where('trans_id', '=', $id)->first();
        $this->base['sub_header'] = 'Edit';
        $this->base['form'] = Form::all();
        $this->base['user'] = Session::get('user');
        $this->base['receipt'] = Receipt::whereId($id)->first();
        $this->base['transaction_type'] = TransactionType::all();
        $this->base['municipalities'] = Municipality::orderBy('name', 'asc')
        ->get()
        ->toarray();
        $this->base['barangays'] = Barangay::where('municipality_id', $this->base['receipt']->col_municipality_id)
        ->orderBy('name', 'asc')
        ->get()
        ->toarray();
        if ($this->base['receipt']->is_cancelled == 1) {
            Session::flash('error', ['This receipt has been cancelled.']);
        } elseif ($this->base['receipt']->is_printed == 1 && $this->base['receipt']->af_type == 1) {
         Session::flash('info', ['This receipt has been printed.']);
     }

     return view('collection::receipt.edit')->with('base', $this->base);
 }



    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->base['sub_header'] = 'Update';
        $receipt = Receipt::whereId($id)->first();
        $filter = [
        'user_id' => 'required|numeric',
        'date' => 'required|date',
        'customer' => 'required',
        'transaction_type' => 'required'
        ];

        if (!is_null($request['account_is_shared'])) {
            if (in_array(1, $request['account_is_shared'])) {
                $filter['municipality'] = 'required';
                $filter['brgy'] = 'required';
            }
        }
        
        $validator = Validator::make($request->all(), $filter);

        if ($validator->fails()) {
            return redirect()->route('receipt.edit', ['receipt' => $id])
            ->withErrors($validator);
        } elseif (!is_null($request['account_id'])) {
            if (in_array('', $request['account_id'])) {
                $validator->getMessageBag()
                    ->add('account', 'An account field is empty or not identified');
                    return redirect()->route('receipt.edit', ['receipt' => $id])
                    ->withErrors($validator);
            }
        } elseif ($receipt->is_cancelled) {
            $validator->getMessageBag()
                ->add('record', 'Cannot edit cancelled records.');
                return redirect()->route('receipt.edit', ['receipt' => $id])
                ->withErrors($validator);
        } elseif ($receipt->is_printed && $receipt->af_type == 1 && session::get('user')->position != 'Administrator' ) {
            $validator->getMessageBag()
                ->add('record', 'Cannot edit printed records.');
                return redirect()->route('receipt.edit', ['receipt' => $id])
                ->withErrors($validator);
        }

        # Add payor if not existing
        $payor_id = 0;
        if (empty($request['customer_id'])) {
            $payor = Customer::where('name', '=', $request['customer'])->first();

            if (!empty($payor)) {
                $payor_id = $payor->id;
            } elseif(!is_null($request['orig_customer_id'])) {
                // search for customer name duplicate if attempting to edit
                // $search = Customer::where('name', 'like', '%'.$request['customer'].'%')->get();
                $search = DB::select('select * from col_customer where name like "%'.$request['customer'].'%"');
                if(!empty($search))
                    return redirect()->route('receipt.edit', ['receipt' => $id])->withErrors('Payor already exists, please re-input the payor\'s name and then select the payor from the list of existing payors that will appear.');
            } else {
                $search = DB::select('select * from col_customer where name like "%'.$request['customer'].'%"');
                if(!empty($search)) {
                    return redirect()->route('receipt.edit', ['receipt' => $id])->withErrors('Payor already exists, please re-input the payor\'s name and then select the payor from the list of existing payors that will appear.');
                } else {
                    $payor_id = Customer::create([
                        'name' => $request['customer'],
                        'address' => '',
                        ]);
                    $payor_id = $payor_id->id;
                }
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

    # successful validation
    if(Carbon::parse($report_date)->format('Y-m-d') != Carbon::parse($request['receipt_date'])->format('Y-m-d')) {
        $receipt->report_date = Carbon::parse($request['receipt_date'])->format('Y-m-d');
    } else {
        $receipt->report_date = $report_date;
    }
    $receipt->col_municipality_id = (!empty($request['municipality'])) ? $request['municipality'] : '';
    $receipt->col_barangay_id = (!empty($request['brgy'])) ? $request['brgy'] : '';
    $receipt->dnlx_user_id = $request['user_id'];
    $receipt->col_customer_id = $payor_id;
    $receipt->sex = (!empty($request['Sex'])) ? $request['Sex'] : '';
    $receipt->report_date = $report_date;
    $receipt->date_of_entry = date('Y-m-d H:i:s', strtotime($request['date']));
    $receipt->transaction_type = $request['transaction_type'];
    $receipt->bank_name = (!empty($request['bank_name'])) ? $request['bank_name'] : '';
    $receipt->bank_number = (!empty($request['bank_number'])) ? $request['bank_number'] : '';
    $receipt->bank_date = (!empty($request['bank_date'])) ? $request['bank_date'] : '';
    $receipt->bank_remark = (!empty($request['bank_remark'])) ? $request['bank_remark'] : '';
    $receipt->remarks = (!empty($request['remarks'])) ? $request['remarks'] : '';
    $receipt->client_type = $request['customer_type'];
    $receipt->save();

    if (isset($request['bookletx'])) {
            $sg_booklets = [];
            foreach ($request['booklet_end'] as $key => $value) {
                if( $request['booklet_start'][$key] != '' && $request['booklet_end'][$key] != ''   ){
                    if( isset($request['booklet_id'][$key])){
                            $sg_booklet = SGbooklet::find($request['booklet_id'][$key]);
                            $sg_booklet->booklet_start = $request['booklet_start'][$key];
                            $sg_booklet->booklet_end = $request['booklet_end'][$key];
                            $sg_booklet->save();
                    }else{
                         $sg_booklets[] =    [
                                            'col_receipt_id'    => $receipt->id,
                                            'booklet_start'     => $request['booklet_start'][$key],
                                            'booklet_end'       => $request['booklet_end'][$key],
                                        ];
                    }

                }
            }
            SGbooklet::insert($sg_booklets);
        }

    if (isset($request['sand_transaction'])) {
            if ($request['sand_transaction'] == 1) {
                $user = Session::get('user');
                $governor = ReportOfficers::whereId(1)->first()->value;
                $treasurer = ReportOfficers::whereId(2)->first()->value;
                $assttreasurer = ReportOfficers::whereId(9)->first()->value;
                $transfer_name = ReportOfficers::whereId(10)->first()->value;
                $transfer_position = ReportOfficers::whereId(11)->first()->value;
                $vice_gov = ReportOfficers::whereId(12)->first()->value;
                $provincial_note = $provincial_clearance_number = $provincial_type = $provincial_gov = $provincial_bidding = null;
                $transfer_notary_public = $transfer_ptr_number = $transfer_doc_number = $transfer_page_number = $transfer_book_number = $transfer_series = $transfer_prepare_name = $transfer_prepare_position = null;
                $sand_requestor = $sand_requestor_addr = $sand_requestor_sex = $sand_type = $sand_sandgravelprocessed = $sand_abc = $sand_sandgravel = $sand_boulders = null;

                 $cert = RcptCertificate::where('col_receipt_id','=',$receipt->id)->first();

                 if($cert){
                    $cert->sand_sandgravelprocessed =  $request['sand_sandgravelprocessed'];
                    $cert->sand_abc =  $request['sand_abc'];
                    $cert->sand_sandgravel =  $request['sand_sandgravel'];
                    $cert->sand_boulders =  $request['sand_boulders'];
                    $cert->sand_type = $sand_type;
                    $cert->save();
                 }else{
                    $cert = RcptCertificate::create([
                    'col_receipt_id' => $receipt->id,
                    'col_rcpt_certificate_type_id' => 3,
                    'recipient' => '',
                    'address' => '',
                    'detail' => '',
                    'date_of_entry' => date('Y-m-d'),
                    'provincial_governor' => $governor,
                    'actingprovincial_governor' => $provincial_gov,
                    'provincial_treasurer' => $treasurer,
                    'asstprovincial_treasurer' => ($request['signee'] == 'provtreasurer') ? null : $assttreasurer,
                    'user' => $user->realname,
                    'provincial_note' => $provincial_note,
                    'provincial_clearance_number' => $provincial_clearance_number,
                    'provincial_type' => $provincial_type,
                    'provincial_bidding' => $provincial_bidding,
                    'transfer_notary_public' => $transfer_notary_public,
                    'transfer_ptr_number' => $transfer_ptr_number,
                    'transfer_doc_number' => $transfer_doc_number,
                    'transfer_page_number' => $transfer_page_number,
                    'transfer_book_number' => $transfer_book_number,
                    'transfer_series' => $transfer_series,
                    'transfer_prepare_name' => $transfer_name,
                    'transfer_prepare_position' => $transfer_position,
                    'sand_requestor' => $sand_requestor,
                    'sand_requestor_addr' => $sand_requestor_addr,
                    'sand_requestor_sex' => $sand_requestor_sex,
                    'sand_type' => $sand_type,
                    'sand_sandgravelprocessed' => $request['sand_sandgravelprocessed'],
                    'sand_abc' => $request['sand_abc'],
                    'sand_sandgravel' => $request['sand_sandgravel'],
                    'sand_boulders' => $request['sand_boulders'],
                    ]);
                 }

            }
        }

        if($request['with_cert']!='null'){
            $WithCertx = RcptCertificate::where('col_receipt_id','=',$receipt->id)->first();
             $WithCert =  WithCert::where('trans_id','=',$receipt->id)->first();
             if(!$WithCertx){
                if(!$WithCert && !$WithCertx){
                 $WithCert =  new WithCert;
                 $WithCert->trans_id = $receipt->id;
                  $WithCert->process_status = 0;
                }
                  $WithCert->cert_type = $request['with_cert'];
                  $WithCert->save();
             }
        }

        # Update receipt items
    $receipt_items = $receipt->items;
    foreach ($receipt_items as $item) {
        if (isset($item->detail)) {
            $item->detail->delete();
        }
        $item->delete();
    }

    foreach ($request['account_id'] as $i => $ai) {
        $rate_type = ($request['account_type'][$i] == 'title') ? 'col_acct_title_id' : 'col_acct_subtitle_id';
        $rate = CollectionRate::where($rate_type, '=', $request['account_id'][$i])->first();

        $share_provincial = $request['amount'][$i];
        $share_municipal = 0;
        $share_barangay = 0;
        if (!empty($rate) && $rate->is_shared == 1) {
                // $share_provincial = bcdiv($request['amount'][$i] * ($rate->sharepct_provincial / 100), 1, 2);
                // $share_municipal = bcdiv($request['amount'][$i] * ($rate->sharepct_municipal / 100), 1, 2);
                // $share_barangay = bcdiv($request['amount'][$i] * ($rate->sharepct_barangay / 100), 1, 2);
                $share_provincial = round(floatval(floatval($request['amount'][$i]) * floatval($rate->sharepct_provincial / 100)), 2);
                $share_municipal = round(floatval(floatval($request['amount'][$i]) * floatval($rate->sharepct_municipal / 100)), 2);
                $share_barangay = round(floatval(floatval($request['amount'][$i]) * floatval($rate->sharepct_barangay / 100)), 2);

                $total_shared = $share_provincial + $share_municipal + $share_barangay;
                if ($total_shared !== $request['amount'][$i]) {
                    if($total_shared > $request['amount'][$i]){
                        // $diffx = $total_shared - $request['amount'][$i];
                        // $share_barangay = $share_barangay - $diffx;
                        
                        // $share_barangay = round(floatval($total_shared), 2) - round(floatval($share_provincial), 2) - round(floatval($share_municipal), 2);
                        $share_municipal = round(floatval($request['amount'][$i]), 2) - round(floatval($share_provincial), 2) - round(floatval($share_barangay), 2);
                    } elseif($total_shared < $request['amount'][$i]) {
                        $diffx =  $request['amount'][$i] - $total_shared;
                        $share_provincial = $share_provincial + $diffx;
                    }
                }
        }
        $receipt_item = ReceiptItems::create([
            'col_receipt_id' => $receipt->id,
            'nature' => $request['nature'][$i],
            'col_acct_title_id' => ($request['account_type'][$i] == 'title') ? $request['account_id'][$i] : 0,
            'col_acct_subtitle_id' => ($request['account_type'][$i] == 'subtitle') ? $request['account_id'][$i] : 0,
            'value' => $request['amount'][$i],
            'share_provincial' => round(floatval($share_provincial), 2),
            'share_municipal' => round(floatval($share_municipal), 2),
            'share_barangay' => round(floatval($share_barangay), 2),
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

    Session::flash('info', ['Successfully updated transaction for serial: '.$receipt->serial_no]);
    return redirect()->route('receipt.index');
}

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }

    public function cancel(Request $request, $id)
    {
        $receipt = Receipt::whereId($id)->first();
        $receipt->is_cancelled = 1;
        $receipt->cancelled_remark = $request->cancel_remark;
        $receipt->save();
        return redirect()->route('receipt.show', ['id' => $id]);
    }

    public function f56_detail_form($id)
    {
        $this->base['detail'] = F56Detail::where('col_receipt_id', '=', $id)->first();
        $this->base['sub_header'] = 'Form 56 Detail';
        $this->base['id'] = $id;
        $this->base['f56_types'] = F56Type::get();
        $this->base['receipt'] = Receipt::whereId($id)->first();
        return view('collection::receipt.f56_detail')->with('base', $this->base);
    }

    public function f56_Detail_add(Request $request, $id)
    {
        $receipt = Receipt::select('serial_no')
        ->whereId($id)
        ->first();
        $detail = F56Detail::where('col_receipt_id', '=', $id)->first();

        $filter = [
        'period_covered' => 'required',
        'tdarpno*' => 'required',
        'f56_type' => 'required',
        'basic_current' => 'required|numeric',
        'basic_discount' => 'required|numeric',
        'basic_previous' => 'required|numeric',
        'basic_penalty_current' => 'required|numeric',
        'basic_penalty_previous' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $filter);

        if ($validator->fails()) {
            return redirect()->route('receipt.f56_detail_form', ['id' => $id])
            ->withErrors($validator);
        }

        # Insert
        if (count($detail) == 0) {
            $detail = F56Detail::create([
                'col_receipt_id' => $id,
                'col_f56_type_id' => $request['f56_type'],
                'period_covered' => $request['period_covered'],
                'basic_current' => $request['basic_current'],
                'basic_discount' => $request['basic_discount'],
                'basic_previous' => $request['basic_previous'],
                'basic_penalty_current' => $request['basic_penalty_current'],
                'basic_penalty_previous' => $request['basic_penalty_previous'],
                ]);

            $data = array();
            foreach($request['tdarpno'] as $i => $tan) {
                $row['col_f56_detail_id'] = $detail->id;
                $row['tdarpno'] = $tan;
                array_push($data, $row);
            }
            F56TDARP::insert($data);

            Session::flash('info', ['Successfully created Form 56 detail for serial: '.$receipt->serial_no]);
            return redirect()->route('receipt.index');
        }

        # Update
        $detail->col_f56_type_id = $request['f56_type'];
        $detail->period_covered = $request['period_covered'];
        $detail->basic_current = $request['basic_current'];
        $detail->basic_discount = $request['basic_discount'];
        $detail->basic_previous = $request['basic_previous'];
        $detail->basic_penalty_current = $request['basic_penalty_current'];
        $detail->basic_penalty_previous = $request['basic_penalty_previous'];
        $detail->save();

        # Update receipt items
        $tdarpno = $detail->TDARP;
        foreach ($tdarpno as $tan) {
         $tan->delete();
     }

     $data = array();
     foreach($request['tdarpno'] as $i => $tan) {
        $row['col_f56_detail_id'] = $detail->id;
        $row['tdarpno'] = $tan;
        array_push($data, $row);
    }
    F56TDARP::insert($data);

    Session::flash('info', ['Successfully updated Form 56 detail for serial: '.$receipt->serial_no]);
    return redirect()->route('receipt.index');
}

public function another($id){
    $this->base['receipt_id'] = $id;
   $this->base['sub_header'] = 'ISSUE ANOTHER RECEIPT';
   $this->base['form'] = Form::all();
   $this->base['user'] = Session::get('user');
   $this->base['receipt'] = Receipt::whereId($id)->first();
   $this->base['transaction_type'] = TransactionType::all();
   $this->base['municipalities'] = Municipality::orderBy('name', 'asc')
   ->get()
   ->toarray();
   $this->base['barangays'] = Barangay::where('municipality_id', $this->base['receipt']->col_municipality_id)
   ->orderBy('name', 'asc')
   ->get()
   ->toarray();
   if ($this->base['receipt']->is_cancelled == 1) {
        Session::flash('error', ['This receipt has been cancelled.']);
    }

return view('collection::receipt.another')->with('base', $this->base);
}


 public function another_save(Request $request){
            $receiptx= Receipt::whereId($request->input('receipt_id'))->first();


        $serial = Serial::whereId($request['serial_id'])->first();
        if($request['form'] == 2){
            $filter = [
            'user_id' => 'required|numeric',
            'date' => 'required|date',
            'serial_id' => 'required|numeric',
            'customer_id' => 'required',
            'transaction_type' => 'required',
            'brgy' => 'required',
            'municipality' => 'required',
            'amount.*' => 'required|not_in:0',
            ];
        }else{
            $filter = [
            'user_id' => 'required|numeric',
            'date' => 'required|date',
            'serial_id' => 'required|numeric',
            'customer_id' => 'required',
            'transaction_type' => 'required',
            'amount.*' => 'required|not_in:0',
            ];
        }


        if (in_array(1, $request['account_is_shared'])) {
            $filter['municipality'] = 'required';
            $filter['brgy'] = 'required';
        }
        $validator = Validator::make($request->all(), $filter);
        if ($validator->fails()) {
            return redirect()->route('receipt.another',['id' => $request->input('receipt_id')])
            ->withErrors($validator);
        } elseif (in_array('', $request['account_id'])) {
            $validator->getMessageBag()
            ->add('account', 'An account field is empty or not identified');
            return redirect()->route('receipt.another',['id' => $request->input('receipt_id')])
            ->withErrors($validator);
        } elseif ($serial->serial_current == 0) {
            $validator->getMessageBag()
            ->add('serial', 'Series `'.$serial->serial_begin.'-'.$serial->serial_end.'` is finished. Please use another serial.');
            return redirect()->route('receipt.another',['id' => $request->input('receipt_id')])
            ->withErrors($validator);
        }

        # Add payor if not existing
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

        $current = $serial->serial_current;

        $is_printed = 0;
        if ($request['form'] == 2 || $request['transaction_source'] == "field_land_tax") {
            $is_printed = 1;
        }

        $report_date = date('Y-m-d', strtotime($request['date']));
        if ($request['transaction_source'] == "receipt") {
            # 2pm cut off
            # this means the receipt report date will be
            # next valid weekday
              $report_datex = Carbon::now();
                if($report_datex->format('H')<= 15   ){
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
            'remarks' => (!empty($request['remarks'])) ? $request['remarks'] : '',
            ]);


        if($receiptx->is_many){
            $IsManySerials = IsManySerials::find($receiptx->is_many);
            $add_serial = json_decode( $IsManySerials->col_serials );
            $add_serial[count($add_serial)] = $serial->serial_current ;
            $IsManySerials->col_serials = json_encode($add_serial);
            $IsManySerials->save();
        }else{
            $IsManySerials = new IsManySerials;
            $IsManySerials->col_receipt_id = $receiptx->id;
            $IsManySerials->col_receipt_serial_parent =$receiptx->serial_no;
            $IsManySerials->col_serials = json_encode([$serial->serial_current]);
            $IsManySerials->save();
        }
            $receiptx->is_many = $IsManySerials->id;
            $receiptx->save();
            $receiptxx = Receipt::find($receipt->id);
            $receiptxx->is_many = $IsManySerials->id;
            $receiptxx->save();
        Session::put('serial_id', $request['serial_id']);

        # Update Serial
        $serial->serial_current = ($serial->serial_current == $serial->serial_end) ? 0 : $serial->serial_current + 1;
        $serial->save();


        if (isset($request['sand_transaction'])) {
            if ($request['sand_transaction'] == 1) {
                $user = Session::get('user');
                $governor = ReportOfficers::whereId(1)->first()->value;
                $treasurer = ReportOfficers::whereId(2)->first()->value;
                $assttreasurer = ReportOfficers::whereId(9)->first()->value;
                $transfer_name = ReportOfficers::whereId(10)->first()->value;
                $transfer_position = ReportOfficers::whereId(11)->first()->value;
                $vice_gov = ReportOfficers::whereId(12)->first()->value;
                $provincial_note = $provincial_clearance_number = $provincial_type = $provincial_gov = $provincial_bidding = null;
                $transfer_notary_public = $transfer_ptr_number = $transfer_doc_number = $transfer_page_number = $transfer_book_number = $transfer_series = $transfer_prepare_name = $transfer_prepare_position = null;
                $sand_requestor = $sand_requestor_addr = $sand_requestor_sex = $sand_type = $sand_sandgravelprocessed = $sand_abc = $sand_sandgravel = $sand_boulders = null;
                $cert = RcptCertificate::create([
                    'col_receipt_id' => $receipt->id,
                    'col_rcpt_certificate_type_id' => 3,
                    'recipient' => '',
                    'address' => '',
                    'detail' => '',
                    'date_of_entry' => date('Y-m-d'),
                    'provincial_governor' => $governor,
                    'actingprovincial_governor' => $provincial_gov,
                    'provincial_treasurer' => $treasurer,
                    'asstprovincial_treasurer' => ($request['signee'] == 'provtreasurer') ? null : $assttreasurer,
                    'user' => $user->realname,
                    'provincial_note' => $provincial_note,
                    'provincial_clearance_number' => $provincial_clearance_number,
                    'provincial_type' => $provincial_type,
                    'provincial_bidding' => $provincial_bidding,
                    'transfer_notary_public' => $transfer_notary_public,
                    'transfer_ptr_number' => $transfer_ptr_number,
                    'transfer_doc_number' => $transfer_doc_number,
                    'transfer_page_number' => $transfer_page_number,
                    'transfer_book_number' => $transfer_book_number,
                    'transfer_series' => $transfer_series,
                    'transfer_prepare_name' => $transfer_name,
                    'transfer_prepare_position' => $transfer_position,
                    'sand_requestor' => $sand_requestor,
                    'sand_requestor_addr' => $sand_requestor_addr,
                    'sand_requestor_sex' => $sand_requestor_sex,
                    'sand_type' => $sand_type,
                    'sand_sandgravelprocessed' => $request['sand_sandgravelprocessed'],
                    'sand_abc' => $request['sand_abc'],
                    'sand_sandgravel' => $request['sand_sandgravel'],
                    'sand_boulders' => $request['sand_boulders'],
                    ]);
            }
        }


        foreach ($request['account_id'] as $i => $ai) {
            $rate_type = ($request['account_type'][$i] == 'title') ? 'col_acct_title_id' : 'col_acct_subtitle_id';
            $rate = CollectionRate::where($rate_type, '=', $request['account_id'][$i])->first();

            $share_provincial = $request['amount'][$i];
            $share_municipal = 0;
            $share_barangay = 0;
            if (!empty($rate) && $rate->is_shared == 1) {
                // $share_provincial = bcdiv($request['amount'][$i] * ($rate->sharepct_provincial / 100), 1, 2);
                // $share_municipal = bcdiv($request['amount'][$i] * ($rate->sharepct_municipal / 100), 1, 2);
                // $share_barangay = bcdiv($request['amount'][$i] * ($rate->sharepct_barangay / 100), 1, 2);
                $share_provincial = round(floatval(floatval($request['amount'][$i]) * floatval($rate->sharepct_provincial / 100)), 2);
                $share_municipal = round(floatval(floatval($request['amount'][$i]) * floatval($rate->sharepct_municipal / 100)), 2);
                $share_barangay = round(floatval(floatval($request['amount'][$i]) * floatval($rate->sharepct_barangay / 100)), 2);

                $total_shared = $share_provincial + $share_municipal + $share_barangay;
                if ($total_shared !== $request['amount'][$i]) {
                    if($total_shared > $request['amount'][$i]){
                        // $diffx = $total_shared - $request['amount'][$i];
                        // $share_barangay = $share_barangay - $diffx;
                        
                        // $share_barangay = round(floatval($total_shared), 2) - round(floatval($share_provincial), 2) - round(floatval($share_municipal), 2);
                        $share_municipal = round(floatval($request['amount'][$i]), 2) - round(floatval($share_provincial), 2) - round(floatval($share_barangay), 2);
                    }elseif($total_shared < $request['amount'][$i]){
                        $diffx =  $request['amount'][$i] - $total_shared;
                        $share_provincial = $share_provincial + $diffx;
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
                'share_provincial' => round(floatval($share_provincial), 2),
                'share_municipal' => round(floatval($share_municipal), 2),
                'share_barangay' => round(floatval($share_barangay), 2),
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

        Session::flash('info', ['Successfully created transaction for serial: '.$current]);

        if ($request['form'] == 2) {
            if ($request['transaction_source'] == "receipt") {
                return redirect()->route('receipt.f56_detail_form', ['id' => $receipt->id]);
            }
            return redirect()->route('field_land_tax.f56_detail_form', ['id' => $receipt->id]);
        }

        if ($request['transaction_source'] == "receipt") {
            return redirect()->route('receipt.show', ['id' => $receipt->id]);
        }
        return redirect()->route('field_land_tax.index');
    }
}
