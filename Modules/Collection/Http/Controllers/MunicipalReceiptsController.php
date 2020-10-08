<?php

namespace Modules\Collection\Http\Controllers;

use Illuminate\Http\{Request,Response};
use Illuminate\Support\Facades\{Session,Validator};

use App\Http\Controllers\{Controller,BreadcrumbsController};

use Carbon\Carbon,DB,Datatables,PDF;

use Modules\Collection\Entities\{MunicipalReceipt,MunicipalReceiptItems,F56Type,Form,TransactionType,Municipality,Barangay,Customer,CollectionRate,SandGravelTypes,RcptCertificateType,ReportOfficers,RcptCertificate,WithCert,OtherFeesCharges,ReportOfficerNew};
use App\Models\User;
use Modules\Collection\Entities\SandGravelTypes as sg_types;

class MunicipalReceiptsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    protected $receipt;

    public function __construct(Request $request, MunicipalReceipt $receipt)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'Municipal Receipts';
        $this->base['host'] = request()->getHttpHost();
        $this->receipt = $receipt;
    }

    private function ordinal($number) {
        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        if ((($number % 100) >= 11) && (($number%100) <= 13))
            return $number. 'th';
        else
            return $number. $ends[$number % 10];
    }

    public function index()
    {
        $this->base['f56_types'] = F56Type::get();
        $this->base['sub_header'] = '';
        $this->base['form'] = Form::all();
        $this->base['transaction_type'] = TransactionType::all();
        $this->base['municipalities'] = Municipality::all()->toarray();
        $this->base['brgys'] = Barangay::all()->toarray();
        $this->base['user'] = Session::get('user');
        $this->base['sandgravel_types'] = sg_types::all();

        return view('collection::mncpal.index')->with('base', $this->base);
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
    public function show()
    {
        return view('collection::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('collection::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }

    public function new_receipt(Request $req) {
        $messages = [
            'rcpt_no' => 'Receipt No. is required and must be numerical',
            'rcpt_date' => 'Receipt Date is required and must be in date format',
            'customer' => 'Payor/Customer is required',
            'mncpal_mnc' => 'Municipality is required',
            'transaction_type' => 'Transaction Type is required',
            'bank_name' => 'Bank Name is required',
            'bank_number' => 'Bank Number is required',
            'bank_date' => 'Bank Date is required and must be in date format'
        ];
        if($req->transaction_type > 1) {
            $rules = [
                'rcpt_no' => 'required|numeric',
                'rcpt_date' => 'required|date',
                'customer' => 'required',
                'mncpal_mnc' => 'required|numeric|min:0:not_in:0',
                'transaction_type' => 'required',
                'bank_name' => 'required',
                'bank_number' => 'required',
                'bank_date' => 'required|date'
            ];
        } else {
            $rules = [
                'rcpt_no' => 'required|numeric',
                'rcpt_date' => 'required|date',
                'customer' => 'required',
                'mncpal_mnc' => 'required|numeric|min:0:not_in:0',
                'transaction_type' => 'required'
            ];
        }

        $reqd = Validator::make($req->all(), $rules, $messages);
        if($reqd->fails()){
            return redirect(route('mncpal.index'))->withErrors($reqd)->withInput();
        }

        // check receipt and customer if existing
        $ifExistRcpt = MunicipalReceipt::where('rcpt_no', $req->rcpt_no)->first();
        if(count($ifExistRcpt) > 0) {
            Session::flash('error', ['Receipt existing in the system.']);
            return redirect()->route('mncpal.index');
        }
        $ifExistCust = Customer::find($req->customer_id);

        if(count($ifExistCust) == 0) {
            $new_customer = Customer::updateOrCreate(
                [
                    'name' => $req->customer
                ],
                [
                    'customer_type_id' => null,
                    'address' => ""
                ]
            );
            $mncpal_rcpt = MunicipalReceipt::updateOrCreate(
                [
                    'rcpt_no' => intval($req->rcpt_no)
                ],
                [
                    'rcpt_date' => Carbon::parse($req->rcpt_date)->format('Y-m-d'),
                    'date_of_entry' => Carbon::now()->format('Y-m-d'),
                    'col_customer_id' => $new_customer->id,
                    'col_municipality_id' => $req->mncpal_mnc,
                    'col_barangay_id' => $req->mncpal_brgy,
                    'transaction_type' => $req->transaction_type,
                    'drawee_bank' => $req->bank_name,
                    'bank_no' => $req->bank_number,
                    'bank_date' => Carbon::parse($req->bank_date)->format('Y-m-d'),
                    'remarks' => $req->mncpal_remarks,
                    'client_type' => $req->customer_type,
                    'dnlx_user_id' => $req->user_id,
                    'is_cancelled' => 0
                ]
            );

            for($i = 0; $i < count($req->account_id); $i++) {
                $colrate = CollectionRate::whereId($req['account_rate'][$i])->first();
                MunicipalReceiptItems::create([
                    'col_mncpal_receipt_id' => $mncpal_rcpt->id,
                    'col_acct_title_id' => ($req['account_type'][$i] == 'title') ? $req['account_id'][$i] : 0,
                    'col_acct_subtitle_id' => ($req['account_type'][$i] == 'subtitle') ? $req['account_id'][$i] : 0,
                    'col_collection_rate_id' => $req['account_rate'][$i],
                    'item_qty' => !is_null($colrate) ? $colrate->value : 0,
                    'sched_is_perunit' => !is_null($colrate) ? $colrate->sched_is_perunit : 0,
                    'sched_unit' => !is_null($colrate) ? $colrate->sched_unit : '',
                    'sched_label' => !is_null($colrate) ? $colrate->label : '',
                    'value' => $req['amount'][$i],
                    'nature' => $req['nature'][$i],
                ]);
            }

            Session::flash('info', ['Municipal receipt with OR number '.$req->rcpt_no.' successfully added.']);
            return redirect()->route('mncpal.index');
        }
        $mncpal_rcpt = MunicipalReceipt::updateOrCreate(
            [
                'rcpt_no' => $req->rcpt_no
            ],
            [
                'rcpt_date' => Carbon::parse($req->rcpt_date)->format('Y-m-d'),
                'date_of_entry' => Carbon::now()->format('Y-m-d'),
                'col_customer_id' => $req->customer_id,
                'col_municipality_id' => $req->mncpal_mnc,
                'col_barangay_id' => $req->mncpal_brgy,
                'transaction_type' => $req->transaction_type,
                'drawee_bank' => $req->bank_name,
                'bank_no' => $req->bank_number,
                'bank_date' => Carbon::parse($req->bank_date)->format('Y-m-d'),
                'remarks' => $req->mncpal_remarks,
                'client_type' => $req->customer_type,
                'dnlx_user_id' => $req->user_id,
                'is_cancelled' => 0
            ]
        );
        for($i = 0; $i < count($req->account_id); $i++) {
            $colrate = CollectionRate::whereId($req['account_rate'][$i])->first();
            MunicipalReceiptItems::create([
                'col_mncpal_receipt_id' => $mncpal_rcpt->id,
                'col_acct_title_id' => ($req['account_type'][$i] == 'title') ? $req['account_id'][$i] : 0,
                'col_acct_subtitle_id' => ($req['account_type'][$i] == 'subtitle') ? $req['account_id'][$i] : 0,
                'col_collection_rate_id' => $req['account_rate'][$i],
                'item_qty' => !is_null($colrate) ? $colrate->value : 0,
                'sched_is_perunit' => !is_null($colrate) ? $colrate->sched_is_perunit : 0,
                'sched_unit' => !is_null($colrate) ? $colrate->sched_unit : '',
                'sched_label' => !is_null($colrate) ? $colrate->label : '',
                'value' => $req['amount'][$i],
                'nature' => $req['nature'][$i],
            ]);
        }

        Session::flash('info', ['Municipal receipt with OR number '.$req->rcpt_no.' successfully added.']);
        return redirect()->route('mncpal.index');
    }

    public function mncpal_receipts_tbl(Request $req) {
        $data = MunicipalReceipt::select(DB::raw('col_mncpal_receipt.*, dnlx_user.realname, col_customer.name, col_municipality.name as mnc_name, col_barangay.name as brgy_name'))
            ->with('getCert')
            ->join('dnlx_user', 'col_mncpal_receipt.dnlx_user_id', '=', 'dnlx_user.id')
            ->join('col_customer', 'col_mncpal_receipt.col_customer_id', '=', 'col_customer.id')
            ->join('col_municipality', 'col_mncpal_receipt.col_municipality_id', '=', 'col_municipality.id')
            ->leftjoin('col_barangay', 'col_mncpal_receipt.col_barangay_id', '=', 'col_barangay.id')
            ->whereYear('rcpt_date', '=', $req->year)
            ->orderBy('rcpt_date', 'DESC')
            ->get();
        return DataTables::of($data)->make(true);
    }

    public function mncpal_rcpt_view($id) {
        $receipt = MunicipalReceipt::find($id);
        $this->base['receipt'] = $receipt;
        $this->base['rcpt_user'] = User::find($receipt->dnlx_user_id);
        $this->base['munic'] = Municipality::find($receipt->col_municipality_id);
        $this->base['brgy'] = Barangay::find($receipt->col_barangay_id);
        $this->base['sg_types'] = SandGravelTypes::find($receipt->client_type);
        $this->base['transac_type'] = TransactionType::find($receipt->transaction_type);
        $this->base['ctype'] = Municipality::find($receipt->col_barangay_id);
        $this->base['sub_header'] = 'View';
        $this->base['user'] = Session::get('user'); 
        return view('collection::mncpal.view')->with('base', $this->base);
    }

    public function mncpal_rcpt_edit($id) {
        $receipt = MunicipalReceipt::find($id);
        $this->base['receipt'] = $receipt;
        $this->base['rcpt_user'] = User::find($receipt->dnlx_user_id);
        $this->base['sg_types'] = SandGravelTypes::find($receipt->client_type);
        $this->base['transac_type'] = TransactionType::find($receipt->transaction_type);
        $this->base['ctype'] = Municipality::find($receipt->col_barangay_id);
        $this->base['sub_header'] = 'Edit';
        $this->base['user'] = Session::get('user'); 
        $this->base['f56_types'] = F56Type::get();
        $this->base['transaction_type'] = TransactionType::all();
        $this->base['municipalities'] = Municipality::all()->toarray();
        $this->base['brgys'] = Barangay::all()->toarray();
        $this->base['sandgravel_types'] = sg_types::all();
        return view('collection::mncpal.edit')->with('base', $this->base);
    }

    public function mncpal_receipt_update(Request $req) {
        $messages = [
            'rcpt_no' => 'Receipt No. is required and must be numerical',
            'rcpt_date' => 'Receipt Date is required and must be in date format',
            'customer' => 'Payor/Customer is required',
            'mncpal_mnc' => 'Municipality is required',
            'transaction_type' => 'Transaction Type is required',
            'bank_name' => 'Bank Name is required',
            'bank_number' => 'Bank Number is required',
            'bank_date' => 'Bank Date is required and must be in date format'
        ];
        if($req->transaction_type > 1) {
            $rules = [
                'rcpt_no' => 'required|numeric',
                'rcpt_date' => 'required|date',
                'customer' => 'required',
                'mncpal_mnc' => 'required|numeric|min:0:not_in:0',
                'transaction_type' => 'required',
                'bank_name' => 'required',
                'bank_number' => 'required',
                'bank_date' => 'required|date'
            ];
        } else {
            $rules = [
                'rcpt_no' => 'required|numeric',
                'rcpt_date' => 'required|date',
                'customer' => 'required',
                'mncpal_mnc' => 'required|numeric|min:0:not_in:0',
                'transaction_type' => 'required'
            ];
        }

        $reqd = Validator::make($req->all(), $rules, $messages);
        if($reqd->fails()){
            return redirect(route('mncpal.index'))->withErrors($reqd)->withInput();
        }
        $item_ids = [];
        // check customer if existing
        $ifExistCust = Customer::find($req->customer_id);
        if(count($ifExistCust) == 0) {
            $new_customer = Customer::updateOrCreate(
                [
                    'name' => $req->customer
                ],
                [
                    'customer_type_id' => null,
                    'address' => ""
                ]
            );
            $getRcpt = MunicipalReceipt::find($req['rcpt_id']);
            if(count($getRcpt) > 0) {
                $getRcpt->rcpt_no = intval($req->rcpt_no);
                $getRcpt->rcpt_date = Carbon::parse($req->rcpt_date)->format('Y-m-d');
                $getRcpt->col_customer_id = $new_customer->id;
                $getRcpt->col_municipality_id = $req->mncpal_mnc;
                $getRcpt->col_barangay_id = $req->mncpal_brgy;
                $getRcpt->transaction_type = $req->transaction_type;
                $getRcpt->drawee_bank = $req->bank_name;
                $getRcpt->bank_no = $req->bank_number;
                $getRcpt->bank_date = Carbon::parse($req->bank_date)->format('Y-m-d');
                $getRcpt->remarks = $req->mncpal_remarks;
                $getRcpt->client_type = $req->customer_type;
                $getRcpt->is_cancelled = 0; // base on minus markssss
                $getRcpt->save();
            } else {
                Session:flash('error', 'Receipt not found. Please refresh the page and try again.');
                return redirect()->back();
            }

            for($i = 0; $i < count($req->account_id); $i++) {
                $colrate = CollectionRate::whereId($req['account_rate'][$i])->first();
                if(isset($req['item_id'][$i])) {
                    $getItem = MunicipalReceiptItems::find($req['item_id'][$i]);
                    array_push($item_ids, $getItem->id);
                    if(count($getItem) > 0) {
                        $getItem->col_acct_title_id = ($req['account_type'][$i] == 'title') ? $req['account_id'][$i] : 0;
                        $getItem->col_acct_subtitle_id = ($req['account_type'][$i] == 'subtitle') ? $req['account_id'][$i] : 0;
                        $getItem->col_collection_rate_id = $req['account_rate'][$i];
                        $getItem->item_qty = !is_null($colrate) ? floatval($colrate->value) : 0;
                        $getItem->sched_is_perunit = !is_null($colrate) ? $colrate->sched_is_perunit : 0;
                        $getItem->sched_unit = !is_null($colrate) ? $colrate->sched_unit : '';
                        $getItem->sched_label = !is_null($colrate) ? $colrate->label : '';
                        $getItem->value = floatval($req['amount'][$i]);
                        $getItem->nature = $req['nature'][$i];
                        $getItem->save();
                    }
                } else {
                    $newItem = MunicipalReceiptItems::create(
                        [
                            'col_mncpal_receipt_id' => $req['rcpt_id'],
                            'col_acct_title_id' => ($req['account_type'][$i] == 'title') ? $req['account_id'][$i] : 0,
                            'col_acct_subtitle_id' => ($req['account_type'][$i] == 'subtitle') ? $req['account_id'][$i] : 0,
                            'col_collection_rate_id' => $req['account_rate'][$i],
                            'item_qty' => !is_null($colrate) ? $colrate->value : 0,
                            'sched_is_perunit' => !is_null($colrate) ? $colrate->sched_is_perunit : 0,
                            'sched_unit' => !is_null($colrate) ? $colrate->sched_unit : '',
                            'sched_label' => !is_null($colrate) ? $colrate->label : '',
                            'value' => $req['amount'][$i],
                            'nature' => $req['nature'][$i],
                        ]
                    );
                    array_push($item_ids, $newItem->id);
                }
            }

            Session::flash('info', ['Municipal receipt with OR number '.$req->$req->rcpt_no.' successfully updated.']);
            return redirect()->back();
        }
        $getRcpt = MunicipalReceipt::find($req['rcpt_id']);
        if(count($getRcpt) > 0) {
            $getRcpt->rcpt_no = intval($req->rcpt_no);
            $getRcpt->rcpt_date = Carbon::parse($req->rcpt_date)->format('Y-m-d');
            $getRcpt->col_customer_id = $req->customer_id;
            $getRcpt->col_municipality_id = $req->mncpal_mnc;
            $getRcpt->col_barangay_id = $req->mncpal_brgy;
            $getRcpt->transaction_type = $req->transaction_type;
            $getRcpt->drawee_bank = $req->bank_name;
            $getRcpt->bank_no = $req->bank_number;
            $getRcpt->bank_date = Carbon::parse($req->bank_date)->format('Y-m-d');
            $getRcpt->remarks = $req->mncpal_remarks;
            $getRcpt->client_type = $req->customer_type;
            $getRcpt->is_cancelled = 0; // base on minus markssss
            $getRcpt->save();
        } else {
            Session::flash('error', ['Receipt not found. Please refresh the page and try again.']);
            return redirect()->back();
        }

        for($i = 0; $i < count($req->account_id); $i++) {
            $colrate = CollectionRate::whereId($req['account_rate'][$i])->first();
            if(isset($req['item_id'][$i])) {
                $getItem = MunicipalReceiptItems::find($req['item_id'][$i]);
                array_push($item_ids, $getItem->id);
                if(count($getItem) > 0) {
                    $getItem->col_acct_title_id = ($req['account_type'][$i] == 'title') ? $req['account_id'][$i] : 0;
                    $getItem->col_acct_subtitle_id = ($req['account_type'][$i] == 'subtitle') ? $req['account_id'][$i] : 0;
                    $getItem->col_collection_rate_id = $req['account_rate'][$i];
                    $getItem->item_qty = !is_null($colrate) ? floatval($colrate->value) : 0;
                    $getItem->sched_is_perunit = !is_null($colrate) ? $colrate->sched_is_perunit : 0;
                    $getItem->sched_unit = !is_null($colrate) ? $colrate->sched_unit : '';
                    $getItem->sched_label = !is_null($colrate) ? $colrate->label : '';
                    $getItem->value = floatval($req['amount'][$i]);
                    $getItem->nature = $req['nature'][$i];
                    $getItem->save();
                }
            } else {
                $newItem = MunicipalReceiptItems::create(
                    [
                        'col_mncpal_receipt_id' => $req['rcpt_id'],
                        'col_acct_title_id' => ($req['account_type'][$i] == 'title') ? $req['account_id'][$i] : 0,
                        'col_acct_subtitle_id' => ($req['account_type'][$i] == 'subtitle') ? $req['account_id'][$i] : 0,
                        'col_collection_rate_id' => $req['account_rate'][$i],
                        'item_qty' => !is_null($colrate) ? floatval($colrate->value) : 0,
                        'sched_is_perunit' => !is_null($colrate) ? $colrate->sched_is_perunit : 0,
                        'sched_unit' => !is_null($colrate) ? $colrate->sched_unit : 0,
                        'sched_label' => !is_null($colrate) ? $colrate->label : 0,
                        'value' => floatval($req['amount'][$i]),
                        'nature' => $req['nature'][$i],
                    ]
                );
                array_push($item_ids, $newItem->id);
            }
        }

        // soft delete items not in item_ids array
        $itemsArr = $getRcpt->getItems;
        foreach ($itemsArr as $item) {
            if(in_array($item->id, $item_ids) == false) {
                $item->delete();
            }
        }

        Session::flash('info', ['Municipal receipt with OR number '.$req->rcpt_no.' successfully updated.']);
        return redirect()->back();
    }

    public function delete_rcpt($id) {
        $rcpt = MunicipalReceipt::find($id);
        if(count($rcpt) > 0) {
            $rcpt->is_cancelled = 1;
            $rcpt->save();
            $rcpt->delete();
            Session::flash('info', ['Municipal receipt with OR number '.$rcpt->rcpt_no.' successfully deleted.']);
            return redirect()->route('mncpal.index');
        } else {
            Session::flash('error', ['Municipal receipt not found. Please refresh the page and try again.']);
            return redirect()->route('mncpal.index');
        }
    }

    public function mncpal_cert($id) {
        $this->base['rcpt_certificatetype'] = RcptCertificateType::get();
        $this->base['rcpt_officers'] = ReportOfficers::whereIn('id',[2, 5])->get();
        $this->base['receipt'] = $id;
        $this->base['cert'] = RcptCertificate::where('col_mncpal_receipt_id', '=', $id)->first();
        $this->base['recipt_info'] = MunicipalReceipt::find($id);
        // $this->base['withcert'] = WithCert::where('mncpal_receipt_id', '=', $id)->first();
        $this->base['withcert'] = null;
        $this->base['OtherFeesCharges'] = OtherFeesCharges::where('mncpal_receipt_id', '=', $id)->get();
        $this->base['position'] = ReportOfficers::all();
        $this->base['officer'] = ReportOfficerNew::all();
        $this->base['prov_gov'] = ReportOfficerNew::withTrashed()->whereHas('position', function($q) {
            $q->where('position', 'like', 'provincial governor');
        })
        ->where('officer_name', 'not like', '%testing%')
        ->get();
        $this->base['is_mncpal_cert'] = true;
        $this->base['latest_prov_gov'] = ReportOfficerNew::whereHas('position', function($q) {
            $q->where('position', 'like', 'provincial governor');
        })->first();
        $this->base['user'] = User::find($this->base['recipt_info']->dnlx_user_id);
        $this->base['ctype'] = sg_types::find($this->base['recipt_info']->client_type);
        $this->base['rcpt_mnc'] = Municipality::find($this->base['recipt_info']->col_municipality_id);
        $this->base['rcpt_brgy'] = Barangay::find($this->base['recipt_info']->col_barangay_id);

        return view('collection::certificate.index')->with('base', $this->base);
    }

    public function mncpal_rcpt_cert($id, $gov, $ppr_size, $height=0, $width=0) {
        $receipt = MunicipalReceipt::whereId($id)->where('is_cancelled','=',0)->get();
        if($receipt[0]->is_many){
             $receipt = MunicipalReceipt::where('is_many','=',$receipt[0]->is_many)->where('is_cancelled','=',0)->orderBy('serial_no','asc')->get();
        }
        $this->base['receipts'] =  $receipt ;
        $this->base['current_receipt'] = MunicipalReceipt::find($id);

        $this->base['cert'] = RcptCertificate::where('col_mncpal_receipt_id', '=', $id)->first(); 
        $this->base['OtherFeesCharges'] = OtherFeesCharges::where('receipt_id', '=', $id)->get();
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
        $this->base['is_mncpal_cert'] = true;

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
                $latest_cert_fee = MunicipalReceipt::join('col_receipt_items', 'col_receipt.id', '=', 'col_receipt_items.col_mncpal_receipt_id')
                    ->where('col_customer_id','=',$receipt[0]->col_customer_id)
                    ->where('is_cancelled','=',0)
                    ->where('is_printed', '=', 1)
                    ->whereDate('report_date','<=', $receipt[0]->report_date)
                    ->where('nature', 'Certification Fee')
                    ->first();

                if(!is_null($latest_cert_fee)) {
                    if(!isset($this->base['cert']->include_from) && !isset($this->base['cert']->include_to)) {
                        $transactions = MunicipalReceipt::with('getItems')
                            ->where('col_customer_id','=',$receipt[0]->col_customer_id)
                            ->where('is_cancelled','=',0)
                            ->whereDate('report_date', '<=', $receipt[0]->report_date)
                            ->whereDate('report_date', '>=', $latest_cert_fee->report_date)
                            ->orderBy('report_date','asc')
                            ->get();
                    } else {
                        $transactions = MunicipalReceipt::with('getItems')
                            ->where('col_customer_id','=',$receipt[0]->col_customer_id)
                            ->where('is_cancelled','=',0)
                            ->whereDate('report_date', '>=', $this->base['cert']->include_from)
                            ->whereDate('report_date', '<=', $this->base['cert']->include_to)
                            ->orderBy('report_date','asc')
                            ->get();
                    }
                } else {
                    if(!isset($this->base['cert']->include_from) && !isset($this->base['cert']->include_to)) {
                        $transactions = MunicipalReceipt::with('getItems')
                            ->where('col_customer_id','=',$receipt[0]->col_customer_id)
                            ->where('is_cancelled','=',0)
                            ->whereDate('report_date', '<=', $receipt[0]->report_date)
                            ->orderBy('report_date','asc')
                            ->get();
                    } else {
                        $transactions = MunicipalReceipt::with('getItems')
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

                rsort($cert_receipt);

                $this->base['transactions'] =  $transactions;
                $this->base['cert_receipt'] = $cert_receipt;
                $this->base['not_crt'] = $not_crt;
                $this->base['sg_taxes'] = $sg_taxes;
                $this->base['cert_or'] = MunicipalReceipt::with('getCert')->find($id);
                $this->base['permit_pd'] = $permit_pd;
                $this->base['include_from'] = $this->base['cert']->include_from;
                $this->base['include_to'] = $this->base['cert']->include_to;

                $pdf = PDF::loadView('collection::pdf/cert_sand_gravel_tax', $this->base);
                break;
        }
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
}
