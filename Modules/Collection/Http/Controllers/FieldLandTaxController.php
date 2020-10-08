<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\{Controller,BreadcrumbsController};
use Illuminate\Http\{ Request,Response};
use Illuminate\Support\Facades\{ Session,Validator};

use Modules\Collection\Entities\{ Customer,Form,Municipality,Barangay,Receipt,ReceiptItems,
        Serial, WeekdayHoliday, TransactionType, CollectionRate,
        F56Type, F56Detail, F56TDARP, ReceiptItemDetail, AdaSettings,
        SandGravelTypes as sg_types,SGbooklet, RcptCertificate, RcptCertificateType, ReportOfficers, WithCert, ReportOfficerNew, OtherFeesCharges
        };

use Carbon\Carbon;

class FieldLandTaxController extends Controller
{
    protected $receipt;

    public function __construct(Request $request, Receipt $receipt)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'Field Land Tax Collections';
        $this->receipt = $receipt;
        $this->base['ada_settings'] = AdaSettings::get();
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
        $this->base['sub_header'] = 'Field Land Tax';
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
        return view('collection::field_land_tax.index', $this->base)->with('base', $this->base);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('collection::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {

    }

    public function show($id)
    {
        $this->base['sub_header'] = 'View';
        $this->base['receipt'] = Receipt::with('customer')->whereId($id)->first();

        $this->base['total'] = 0;
        $items = $this->base['receipt']->items;
        foreach ($items as $item) {
            $this->base['total'] += $item->value;
        }

        $this->base['previous_id'] = Receipt::where('id', '<', $this->base['receipt']->id)
            ->where('transaction_source', '=', 'field_land_tax')
            ->max('id');
        $this->base['previous'] = Receipt::whereId($this->base['previous_id'])->first();
        if (!is_null($this->base['previous']) && $this->base['previous']->is_printed == 0) {
            Session::flash('info', ['You may only print this receipt after printing the previous one.']);
        }

        // for certification and permits
        $this->base['withcert'] = WithCert::where('trans_id', '=', $id)->first();
        $this->base['rcpt_certificatetype'] = RcptCertificateType::get();
        $this->base['rcpt_officers'] = ReportOfficers::whereIn('id',[2, 5])->get();
        $this->base['cert'] = RcptCertificate::where('col_receipt_id', '=', $id)->first();
        // $this->base['prov_gov'] = ReportOfficerNew::all();
        $this->base['prov_gov'] = ReportOfficerNew::withTrashed()->whereHas('position', function($q) {
            $q->where('position', 'like', 'provincial governor');
        })
        ->where('officer_name', 'not like', '%testing%')
        ->get();
        $this->base['latest_prov_gov'] = ReportOfficerNew::whereHas('position', function($q) {
            $q->where('position', 'like', 'provincial governor');
        })->first();
        $this->base['OtherFeesCharges'] = OtherFeesCharges::where('receipt_id', '=', $id)->get();

        return view('collection::field_land_tax.view')->with('base', $this->base);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $this->base['sandgravel_types'] = sg_types::all();
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
        }
        $this->base['cert'] = RcptCertificate::where('col_receipt_id', '=', $id)->first();

        // for certification and permits
        $this->base['withcert'] = WithCert::where('trans_id', '=', $id)->first();
        $this->base['rcpt_certificatetype'] = RcptCertificateType::get();
        $this->base['rcpt_officers'] = ReportOfficers::whereIn('id',[2, 5])->get();
        // $this->base['prov_gov'] = ReportOfficerNew::all();
        $this->base['prov_gov'] = ReportOfficerNew::withTrashed()->whereHas('position', function($q) {
            $q->where('position', 'like', 'provincial governor');
        })
        ->where('officer_name', 'not like', '%testing%')
        ->get();
        $this->base['latest_prov_gov'] = ReportOfficerNew::whereHas('position', function($q) {
            $q->where('position', 'like', 'provincial governor');
        })->first();
        $this->base['OtherFeesCharges'] = OtherFeesCharges::where('receipt_id', '=', $id)->get();

        return view('collection::field_land_tax.edit')->with('base', $this->base);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $receipt = Receipt::whereId($id)->first();

        $filter = [
            'user_id' => 'required|numeric',
            'date' => 'required|date',
            'customer' => 'required',
            'transaction_type' => 'required'
        ];

        if (in_array(1, $request['account_is_shared'])) {
            $filter['municipality'] = 'required';
            $filter['brgy'] = 'required';
        }


        $validator = Validator::make($request->all(), $filter);

        if ($validator->fails()) {
            return redirect()->route('field_land_tax.edit', ['receipt' => $id])
                ->withErrors($validator);
        } elseif (in_array('', $request['account_id'])) {
            $validator->getMessageBag()
                ->add('account', 'An account field is empty or not identified');
            return redirect()->route('field_land_tax.edit', ['receipt' => $id])
                ->withErrors($validator);
        } elseif ($receipt->is_cancelled == 1) {
            $validator->getMessageBag()
                ->add('record', 'Cannot edit cancelled records.');
            return redirect()->route('field_land_tax.edit', ['receipt' => $id])
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
            $payor = Customer::find($payor_id);
            if (!empty($payor)) {
                $payor->name = $request['customer'];
                $payor->save();
            }
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
        if(Carbon::parse($report_date)->format('Y-m-d') != Carbon::parse($request['report_date'])->format('Y-m-d')) {
            $receipt->report_date = Carbon::parse($request['report_date'])->format('Y-m-d');
        } else {
            $receipt->report_date = $report_date;
        }
        $receipt->col_municipality_id = (!empty($request['municipality'])) ? $request['municipality'] : '';
        $receipt->col_barangay_id = (!empty($request['brgy'])) ? $request['brgy'] : '';
        $receipt->dnlx_user_id = $request['user_id'];
        $receipt->col_customer_id = $payor_id;
        $receipt->sex = (!empty($request['Sex'])) ? $request['Sex'] : '';
        $receipt->date_of_entry = date('Y-m-d H:i:s', strtotime($request['date']));
        $receipt->transaction_type = $request['transaction_type'];
        $receipt->bank_name = (!empty($request['bank_name'])) ? $request['bank_name'] : '';
        $receipt->bank_number = (!empty($request['bank_number'])) ? $request['bank_number'] : '';
        $receipt->bank_date = (!empty($request['bank_date'])) ? $request['bank_date'] : '';
        $receipt->bank_remark = (!empty($request['bank_remark'])) ? $request['bank_remark'] : '';
        $receipt->remarks = (!empty($request['remarks'])) ? $request['remarks'] : '';
        $receipt->client_type = $request['customer_type'];
        $receipt->save();

        # Update receipt items
        $receipt_items = $receipt->items;
        foreach ($receipt_items as $item) {
            $item->delete();
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
            $receipt_item = ReceiptItems::create([
                'col_receipt_id' => $receipt->id,
                'nature' => $request['nature'][$i],
                'col_acct_title_id' => ($request['account_type'][$i] == 'title') ? $request['account_id'][$i] : 0,
                'col_acct_subtitle_id' => ($request['account_type'][$i] == 'subtitle') ? $request['account_id'][$i] : 0,
                'value' => $request['amount'][$i],
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

        Session::flash('info', ['Successfully updated transaction for serial: '.$receipt->serial_no]);
        return redirect()->route('field_land_tax.index');
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
        return redirect()->route('field_land_tax.show', ['id' => $id]);
    }

    public function f56_detail_form($id)
    {
        $this->base['detail'] = F56Detail::where('col_receipt_id', '=', $id)->get();
        $this->base['sub_header'] = 'Form 56 Detail';
        $this->base['id'] = $id;
        $this->base['f56_types'] = F56Type::get();
        $this->base['receipt'] = Receipt::whereId($id)->first();
        return view('collection::field_land_tax.f56_detail')->with('base', $this->base);
    }

    public function f56_Detail_add(Request $request, $id)
    {
        $receipt = Receipt::select('serial_no')
            ->whereId($id)
            ->first();


        $filter = [
            'period_covered*' => 'required',
            'tdarpno*' => 'required',
            'f56_type*' => 'required',
            'basic_current*' => 'required|numeric',
            'basic_discount*' => 'required|numeric',
            'basic_previous*' => 'required|numeric',
            'basic_penalty_current*' => 'required|numeric',
            'basic_penalty_previous*' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $filter);

        if ($validator->fails()) {
            return redirect()->route('field_land_tax.f56_detail_form', ['id' => $id])
                ->withErrors($validator);
        }

        # Insert

        if (count($request['detail_id']) == 0) {
            $f56_type_general = $request['f56_type'][0];
            $data = array();
            foreach($request['tdarpno'] as $i => $tan) {
                $detail = F56Detail::create([
                'col_receipt_id' => $id,
                'col_f56_type_id' => $request['f56_type'][$i],
                'period_covered' => $request['period_covered'][$i],
                'basic_current' => $request['basic_current'][$i],
                'basic_discount' => $request['basic_discount'][$i],
                'basic_previous' => $request['basic_previous'][$i],
                'basic_penalty_current' => $request['basic_penalty_current'][$i],
                'basic_penalty_previous' => $request['basic_penalty_previous'][$i],
            ]);

                $row['col_f56_detail_id'] = $detail->id;
                $row['tdarpno'] = $tan;
                $row['f56_type'] = $request['f56_type'][$i];
                array_push($data, $row);


            }
            F56TDARP::insert($data);

            Session::flash('info', ['Successfully created Form 56 detail for serial: '.$receipt->serial_no]);
            return redirect()->route('field_land_tax.index');
        }

       foreach ($request['period_covered'] as $dey => $dval) {
        if(isset( $request['basic_penalty_delete'][$dey] )){
            if($request['basic_penalty_delete'][$dey] == 'TRUE'){
                $detail = F56Detail::find($request['detail_id'][$dey]);
                $detail->delete();
                continue;
            }
        }
          if(isset( $request['detail_id'][$dey] )){
            $detail = F56Detail::find($request['detail_id'][$dey]);
            $tdarpno = F56TDARP::where('col_f56_detail_id','=',$request['detail_id'][$dey])->first();
            if(!$tdarpno){
                $tdarpno = new F56TDARP;
            }
          }else{
            $detail = new F56Detail;
            $tdarpno = new F56TDARP;
            $detail->col_receipt_id = $id;
          }

            $detail->col_f56_type_id = $request['f56_type'][$dey];
            $detail->period_covered = $request['period_covered'][$dey];
            $detail->basic_current = $request['basic_current'][$dey];
            $detail->basic_discount = $request['basic_discount'][$dey];
            $detail->basic_previous = $request['basic_previous'][$dey];
            $detail->basic_penalty_current = $request['basic_penalty_current'][$dey];
            $detail->basic_penalty_previous = $request['basic_penalty_previous'][$dey];
            $detail->save();

            $tdarpno->col_f56_detail_id = $detail->id;
            $tdarpno->tdarpno = $request['tdarpno'][$dey];
            $tdarpno->save();

       }
        Session::flash('info', ['Successfully updated Form 56 detail for serial: '.$receipt->serial_no]);
        return redirect()->route('field_land_tax.index');
    }

    public function rererere(){
         $detail = F56Detail::all();

         foreach ($detail as $key => $value) {

           $tdarpno =  F56TDARP::where('col_f56_detail_id','=',$value->id)->get();
           foreach ($tdarpno as $keyx => $valuex) {
               $tdarpnox =  F56TDARP::find($valuex->id);
               $tdarpnox->f56_type = $value->col_f56_type_id;
               $tdarpnox->save();
           }

         }
    }

    public function flt_update_detail(Request $req) {
        $user = Session::get('user');
        $cert = RcptCertificate::where('col_receipt_id', '=', $req['receipt_id'])->first();

        $filter = [
            'type' => 'required',
            'date' => 'date|required',
            'recipient' => 'required',
        ];

        $validator = Validator::make($req->all(), $filter);

        if ($validator->fails()) {
            // return redirect()->route('field_land_tax.index', ['receipt' => $req['receipt_id']])
            //     ->withErrors($validator);
            return redirect()->back()->withErrors($validator);
        }
        // officers

        if($req['signee'] == 'asstprovtreasurer1' ){
            // if($req->prov_gov > 0) {
            //     $governor = ReportOfficerNew::whereId($req->prov_gov)->first()->officer_name;
            // } else {
            //     $governor = ReportOfficers::whereId(1)->first()->value;
            // }

            if(!is_null($req->prov_gov) && $req->prov_gov > 0) {
                $find_gov = ReportOfficerNew::withTrashed()->whereId($req->prov_gov)->first();
                if(!is_null($find_gov)) {
                    $governor = $find_gov->officer_name;
                } else {
                    $governor = ReportOfficerNew::whereHas('position', function($q) {
                        $q->where('position', 'like', 'provincial governor');
                    })->first()->officer_name;
                }
            } else {
                $governor = ReportOfficerNew::whereHas('position', function($q) {
                    $q->where('position', 'like', 'provincial governor');
                })->first()->officer_name;
            }

            $treasurer = ReportOfficers::whereId(2)->first()->value;
            $assttreasurer = ReportOfficers::whereId(10)->first()->value;
            $assttreasurer_position = ReportOfficers::whereId(11)->first()->value;
            $vice_gov = ReportOfficers::whereId(12)->first()->value; 
        }elseif ($req['signee'] == 'forinabsence' ) {
            // if($req->prov_gov > 0) {
            //     $governor = ReportOfficerNew::whereId($req->prov_gov)->first()->officer_name;
            // } else {
            //     $governor = ReportOfficers::whereId(1)->first()->value;
            // }

            if(!is_null($req->prov_gov) && $req->prov_gov > 0) {
                $find_gov = ReportOfficerNew::withTrashed()->whereId($req->prov_gov)->first();
                if(!is_null($find_gov)) {
                    $governor = $find_gov->officer_name;
                } else {
                    $governor = ReportOfficerNew::whereHas('position', function($q) {
                        $q->where('position', 'like', 'provincial governor');
                    })->first()->officer_name;
                }
            } else {
                $governor = ReportOfficerNew::whereHas('position', function($q) {
                    $q->where('position', 'like', 'provincial governor');
                })->first()->officer_name;
            }

            $treasurer = ReportOfficers::whereId(2)->first()->value;
            $assttreasurer = ReportOfficers::whereId(30)->first()->value;
            $assttreasurer_position = ReportOfficers::whereId(31)->first()->value;
            $vice_gov = ReportOfficers::whereId(12)->first()->value; 
        }else{
            // if($req->prov_gov > 0) {
            //     $governor = ReportOfficerNew::whereId($req->prov_gov)->first()->officer_name;
            // } else {
            //     $governor = ReportOfficers::whereId(1)->first()->value;
            // }
            if(!is_null($req->prov_gov) && $req->prov_gov > 0) {
                $find_gov = ReportOfficerNew::withTrashed()->whereId($req->prov_gov)->first();
                if(!is_null($find_gov)) {
                    $governor = $find_gov->officer_name;
                } else {
                    $governor = ReportOfficerNew::whereHas('position', function($q) {
                        $q->where('position', 'like', 'provincial governor');
                    })->first()->officer_name;
                }
            } else {
                $governor = ReportOfficerNew::whereHas('position', function($q) {
                    $q->where('position', 'like', 'provincial governor');
                })->first()->officer_name;
            }
            $treasurer = ReportOfficers::whereId(2)->first()->value;
            $assttreasurer = ReportOfficers::whereId(9)->first()->value;
            $assttreasurer_position = 'Assistant Provincial Treasurer';
            $vice_gov = ReportOfficers::whereId(12)->first()->value; 
        }

        if($req['prepared_by'] == 'provtreasurer' ){
            $transfer_name = ReportOfficers::whereId(10)->first()->value;
            $transfer_position = ReportOfficers::whereId(11)->first()->value;
        } elseif ($req['prepared_by'] == 'asstprovtreasurer' ) {
            $transfer_name = ReportOfficers::whereId(28)->first()->value;
            $transfer_position = ReportOfficers::whereId(29)->first()->value;
        } else {
            $transfer_name = ReportOfficers::whereId(32)->first()->value;
            $transfer_position = ReportOfficers::whereId(33)->first()->value;
        }
        // provincial permit
        $provincial_note = $provincial_clearance_number = $provincial_type = $provincial_gov = $provincial_bidding = null;
        $transfer_notary_public = $transfer_ptr_number = $transfer_doc_number = $transfer_page_number = $transfer_book_number = $transfer_series = $transfer_prepare_name = $transfer_prepare_position = null;
        $sand_requestor = $sand_requestor_addr = $sand_requestor_sex = $sand_type = $sand_sandgravelprocessed = $sand_abc = $sand_sandgravel = $sand_boulders = null;

        $transfer_ref_num = $req['transfer_ref_num'];
        if ($req['type'] == 1) {
            $provincial_note = $req['provincial_note'];
            $provincial_clearance_number = $req['provincial_clearance_number'];
            $provincial_type = $req['provincial_type'];
            $provincial_gov = ($req['provincial_gov'] == 0) ? $vice_gov : null;
            $provincial_bidding = $req['provincial_bidding'];
            $sand_requestor = $req['prv_requestor'];
        } elseif ($req['type'] == 2) {
            $filter = [
                'transfer_doc_number' => 'required',
                'transfer_page_number' => 'required',
                'transfer_book_number' => 'required',
                'transfer_series' => 'required',
            ];

            $validator = Validator::make($req->all(), $filter);

            if ($validator->fails()) {
                return redirect()->back()->withErrors('Fields for Doc. Number, Page Number, Book Number, and Series are required.');
            }

            $transfer_notary_public = $req['transfer_notary_public'];
            $transfer_ptr_number = $req['transfer_ptr_number'];
            $transfer_doc_number = $req['transfer_doc_number'];
            $transfer_page_number = $req['transfer_page_number'];
            $transfer_book_number = $req['transfer_book_number'];
            $transfer_series = $req['transfer_series'];
        } elseif ($req['type'] == 3) {
            $sand_requestor = $req['sand_requestor'];
            $sand_requestor_addr = $req['sand_requestor_addr'];
            $sand_requestor_sex = $req['sand_requestor_sex'];
            $sand_type = $req['sand_type'];
            $sand_sandgravelprocessed = $req['sand_sandgravelprocessed'];
            $sand_abc = $req['sand_abc'];
            $sand_sandgravel = $req['sand_sandgravel'];
            $sand_boulders = $req['sand_boulders'];
        } elseif($req['type'] == 4) {
            $sand_requestor = $req['prv_requestor'];
        }

        if (count($cert) == 0) {
            $cert = RcptCertificate::create([
                'col_receipt_id' => $req['receipt_id'],
                'col_rcpt_certificate_type_id' => $req['type'],
                'recipient' => $req['recipient'],
                'address' => $req['address'],
                'detail' => $req['detail'],
                'date_of_entry' => date('Y-m-d'),
                'provincial_governor' => $governor,
                'actingprovincial_governor' => $provincial_gov,
                'provincial_treasurer' => $treasurer,
                'asstprovincial_treasurer' => ($req['signee'] == 'provtreasurer') ? null : $assttreasurer,
                'asstprovincial_treasurer_position' => $assttreasurer_position,
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
                'signee' => $req['signee'],
                'prepared_by' => $req['prepared_by'],
                'sand_requestor' => $sand_requestor,
                'sand_requestor_addr' => $sand_requestor_addr,
                'sand_requestor_sex' => $sand_requestor_sex,
                'sand_type' => $sand_type,
                'sand_sandgravelprocessed' => $sand_sandgravelprocessed,
                'sand_abc' => $sand_abc,
                'sand_sandgravel' => $sand_sandgravel,
                'sand_boulders' => $sand_boulders,
                'include_from' => $req['incl_date_frm'] != "" ? Carbon::parse($req['incl_date_frm'])->format('Y-m-d') : null,
                'include_to' => $req['incl_date_to'] != "" ? Carbon::parse($req['incl_date_to'])->format('Y-m-d') : null,
                'transfer_ref_num' => $transfer_ref_num
            ]);

            Session::flash('info', ['Successfully created certificate']);
        } else {
            $cert->col_rcpt_certificate_type_id = $req['type'];
            $cert->recipient = $req['recipient'];
            $cert->address = $req['address'];
            $cert->detail = $req['detail'];
            // $cert->date_of_entry = date('Y-m-d');
            $cert->date_of_entry = Carbon::parse($req['date'])->format('Y-m-d');
            $cert->provincial_governor = $governor;
            $cert->actingprovincial_governor = $provincial_gov;
            $cert->provincial_treasurer = $treasurer;
            $cert->asstprovincial_treasurer = ($req['signee'] == 'provtreasurer') ? null : $assttreasurer;
            $cert->asstprovincial_treasurer_position = ($req['signee'] == 'provtreasurer') ? null : $assttreasurer_position;
            $cert->user = $user->realname;
            $cert->provincial_note = $provincial_note;
            $cert->provincial_clearance_number = $provincial_clearance_number;
            $cert->provincial_type = $provincial_type;
            $cert->provincial_bidding = $provincial_bidding;
            $cert->transfer_notary_public = $transfer_notary_public;
            $cert->transfer_ptr_number = $transfer_ptr_number;
            $cert->transfer_doc_number = $transfer_doc_number;
            $cert->transfer_page_number = $transfer_page_number;
            $cert->transfer_book_number = $transfer_book_number;
            $cert->transfer_series = $transfer_series;
            $cert->transfer_prepare_name = $transfer_name;
            $cert->transfer_prepare_position = $transfer_position;
            $cert->signee = $req['signee'];
            $cert->prepared_by = $req['prepared_by'];
            $cert->sand_requestor = $sand_requestor;
            $cert->sand_requestor_addr = $sand_requestor_addr;
            $cert->sand_requestor_sex = $sand_requestor_sex;
            $cert->sand_type = $sand_type;
            $cert->sand_sandgravelprocessed = $sand_sandgravelprocessed;
            $cert->sand_abc = $sand_abc;
            $cert->sand_sandgravel = $sand_sandgravel;
            $cert->sand_boulders = $sand_boulders;
            $cert->include_from = $req['incl_date_frm'] != "" ? Carbon::parse($req['incl_date_frm'])->format('Y-m-d') : null;
            $cert->include_to = $req['incl_date_to'] != "" ? Carbon::parse($req['incl_date_to'])->format('Y-m-d') : null;
            $cert->transfer_ref_num = $transfer_ref_num;
            $cert->save();

            Session::flash('info', ['Successfully updated certificate']);
        }

        // receipt remarks
        Receipt::updateOrCreate(
            [ 
                'id' => $req['receipt_id'] 
            ],
            [
                'remarks' => $req['remarks'],
                'bank_remark' => $req['bank_remark']
            ]
        );

        if(count($req['fees_charges']) > 0 && $req['fees_charges'][0] != '' ){
            for($x = 0 ; $x<count($req['fees_charges']); $x++){
                if( $req['fees_charges'][$x] != ''  && $req['fees_ammount'][$x] != '' && $req['fees_or_number'][$x] != '' && $req['other_date'][$x] != '' ) {

                    $other_date[$x] = new Carbon($req['other_date'][$x]);
                    if ( !isset($req['other_fees_id'][$x]) ) {
                         $OtherFeesCharges  = new  OtherFeesCharges ;
                    } else {
                        $OtherFeesCharges = OtherFeesCharges::where('id','=',$req['other_fees_id'][$x])->first();
                    }

                    $OtherFeesCharges->receipt_id = $req['receipt_id'];
                    $OtherFeesCharges->fees_charges = $req['fees_charges'][$x];
                    $OtherFeesCharges->ammount = $req['fees_ammount'][$x];
                    $OtherFeesCharges->or_number = $req['fees_or_number'][$x];
                    $OtherFeesCharges->fees_date = $other_date[$x]->format('Y-m-d');
                    $OtherFeesCharges->initials = $req['fees_initials'][$x];
                    $OtherFeesCharges->save();
                }
            }
        }

        $cert_type = RcptCertificateType::find($req['type']);
        
        $WithCert =  WithCert::where('trans_id', '=', $req['receipt_id'])->first();
        if($WithCert){
            $WithCert->process_status = 1;
            $WithCert->cert_type = $cert_type->name;
            $WithCert->save();
        } else {
            $insert = WithCert::insert(
                [ 
                    'trans_id' => $req['receipt_id'],
                    'cert_type' => $cert_type->name,
                    'process_status' => 1, 
                ]
            );
        }
        Session::flash('info', ['Successfully updated details for serial: '.$req->receipt_serial]);
        return redirect()->route('field_land_tax.index');
    }

    public function delete_booklet($id) {
        // soft delete
        $booklet = SGbooklet::find($id);
        $or_num = Receipt::find($booklet->col_receipt_id);
        SGbooklet::find($id)->delete();
        Session::flash('info', ['Deleted booklet for receipt '.$or_num->serial_no]);
        return redirect()->route('field_land_tax.index');
    }
}
