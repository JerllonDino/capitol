<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\{Controller,BreadcrumbsController};
use Illuminate\Http\{ Request,Response};
use Illuminate\Support\Facades\{ Session,Validator};
use DB;

use Modules\Collection\Entities\{AccountCategory, Customer,Form,Municipality,Barangay,Receipt,ReceiptItems,
        Serial, WeekdayHoliday, TransactionType, CollectionRate,
        F56Type, F56Detail, F56TDARP, ReceiptItemDetail, AdaSettings,
        SandGravelTypes as sg_types,SGbooklet, RcptCertificate, RcptCertificateType, ReportOfficers, WithCert, ReportOfficerNew, OtherFeesCharges
        };

use Carbon\Carbon;

class OpagController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'Opag Collection';
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
        $this->base['sub_header'] = 'Opag Collection';
        $this->base['form'] = Form::all();
        $this->base['transaction_type'] = TransactionType::all();
        $this->base['municipalities'] = Municipality::all()->toarray();
        $this->base['user'] = Session::get('user');
        $this->base['sandgravel_types'] = sg_types::all();
        $this->base['categories'] = AccountCategory::get();
        $this->base['report_officers'] = ReportOfficerNew::join('col_report_officer_position', 'col_report_officer_position.id', '=', 'col_new_report_officers.position_name')->select(DB::raw('col_new_report_officers.id as officer_id, col_new_report_officers.position_name as position_id'), 'position', 'officer_name')->where('col_new_report_officers.deleted_at', null)
        ->where('col_report_officer_position.deleted_at', null)
        ->get();

        $months = array();
        for ($month = 1; $month <= 12; $month++) {
            $months[$month] = date('F', mktime(0,0,0,$month));
        }

        $this->base['yr'] = $yr;
        $this->base['mnth'] = $mnth;
        $this->base['months'] = $months;
        return view('collection::opag.index', $this->base)->with('base', $this->base);
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

    /**
     * Show the specified resource.
     * @return Response
     */
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
            ->where('transaction_source', '=', 'opag')
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

        return view('collection::opag.view')->with('base', $this->base);
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

        return view('collection::opag.edit')->with('base', $this->base);
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
            return redirect()->route('opag.edit', ['receipt' => $id])
                ->withErrors($validator);
        } elseif (in_array('', $request['account_id'])) {
            $validator->getMessageBag()
                ->add('account', 'An account field is empty or not identified');
            return redirect()->route('opag.edit', ['receipt' => $id])
                ->withErrors($validator);
        } elseif ($receipt->is_cancelled == 1) {
            $validator->getMessageBag()
                ->add('record', 'Cannot edit cancelled records.');
            return redirect()->route('opag.edit', ['receipt' => $id])
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
        return redirect()->route('opag.index');
    }

    // public function f56_detail_form($id)
    // {
    //     $this->base['detail'] = F56Detail::where('col_receipt_id', '=', $id)->get();
    //     $this->base['sub_header'] = 'Form 56 Detail';
    //     $this->base['id'] = $id;
    //     $this->base['f56_types'] = F56Type::get();
    //     $this->base['receipt'] = Receipt::whereId($id)->first();
    //     return view('collection::opag.f56_detail')->with('base', $this->base);
    // }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }

    public function report()
    {
        $this->base['categories'] = AccountCategory::get();
        $this->base['page_title'] = 'OPAG Report';
        $this->base['months'] = array();
        $this->base['report_officers'] = ReportOfficerNew::join('col_report_officer_position', 'col_report_officer_position.id', '=', 'col_new_report_officers.position_name')->select(DB::raw('col_new_report_officers.id as officer_id, col_new_report_officers.position_name as position_id'), 'position', 'officer_name')->where('col_new_report_officers.deleted_at', null)
        ->where('col_report_officer_position.deleted_at', null)
        ->get();
        for ($month = 1; $month <= 12; $month++) {
            $this->base['months'][$month] = date('F', mktime(0,0,0,$month));
        }
        return view('collection::opag.report')->with('base', $this->base);
    }
}
