<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\{Controller,BreadcrumbsController};

use Illuminate\Http\{Request,Response};
use Session,Validator,DB,PDF,Excel,Carbon\Carbon;
use Modules\Collection\Entities\{ReportOfficers,Form,CashDivision,CashDivisionItems,Barangay,CollectionRate,Municipality,Customer,SandGravelTypes as sg_types,CashDivAdjustment, Receipt, RptMunicipalExcel, Serial};
use Yajra\Datatables\Datatables;

class CashDivisionController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'Cash Division';
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $this->base['sandgravel_types'] = sg_types::all();
        $this->base['form'] = Form::all();
        $this->base['sub_header'] = 'New';
        $this->base['municipalities'] = Municipality::all()->toarray();
        $this->base['user'] = Session::get('user');

        return view('collection::cash_division.index')->with('base', $this->base);
    }

    public function cashdiv_delete(Request $request){
        $cash_div = CashDivision::find($request->input('cash_div'));
        $cash_div->delete();
        return response()->json('test');
    }

    public function cashdiv_restore(Request $request){
        $cash_div = CashDivision::withTrashed()->find($request->input('cash_div'));
        $cash_div->restore();
        return response()->json('test');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function cashdiv_daily()
    {
         $this->base['page_title'] = 'Cash Division Daily Report';
         $this->base['months'] = array();
        for ($month = 1; $month <= 12; $month++) {
            $this->base['months'][$month] = date('F', mktime(0,0,0,$month));
        }

        return view('collection::cashdiv_report.index')->with('base', $this->base);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $filter = [
            'user_id' => 'required|numeric',
            'date' => 'required|date',
            'serial_id' => 'required|numeric',
            // 'refno' => 'required|max:300',
        ];
        $serial = Serial::whereId($request['serial_id'])->first();
        
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

        if (in_array(1, $request['account_is_shared'])) {
            $filter['municipality'] = 'required';
            $filter['brgy'] = 'required';
        }

        $validator = Validator::make($request->all(), $filter);
        if ($validator->fails()) {
            return redirect()->route('cash_division.index')
                ->withErrors($validator);
        } elseif (in_array('', $request['account_id'])) {
            $validator->getMessageBag()
                ->add('account', 'An account field is empty or not indentified');
            return redirect()->route('cash_division.index')
                ->withErrors($validator);
        } elseif ($serial->serial_current == 0) {
            $validator->getMessageBag()
            ->add('serial', 'Series `'.$serial->serial_begin.'-'.$serial->serial_end.'` is finished. Please use another serial.');
            return redirect()->route('receipt.index')
            ->withErrors($validator);
        }

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
        
        # Success
        $addtl = CashDivision::updateOrCreate(
            [
                'serial_no' => $serial->serial_current
            ],
            [
            'col_serial_id' => $request['serial_id'],
            'col_customer_id' => $payor_id,
            'sex' => (!empty($request['Sex'])) ? $request['Sex'] : '',
            'col_municipality_id' => (!empty($request['municipality'])) ? $request['municipality'] : '',
            'col_barangay_id' => (!empty($request['brgy'])) ? $request['brgy'] : '',
            'dnlx_user_id' => $request['user_id'],
            'date_of_entry' => date('Y-m-d', strtotime($request['date'])),
            // 'refno' => $request['refno'],
            'client_type' => $request['customer_type'],
        ]);
        Session::put('serial_id', $request['serial_id']);

         # Update Serial
         $serial->serial_current = ($serial->serial_current == $serial->serial_end) ? 0 : $serial->serial_current + 1;
         $serial->save();

        $data = array();
        foreach ($request['account_id'] as $i => $ai) {
            $rate_type = ($request['account_type'][$i] == 'title') ? 'col_acct_title_id' : 'col_acct_subtitle_id';
            $rate = CollectionRate::where($rate_type, '=', $request['account_id'][$i])->first();

            $share_provincial = $request['amount'][$i];
            $share_municipal = 0;
            $share_barangay = 0;
            if (!empty($rate) && $rate->is_shared == 1) {
                $share_provincial = $request['amount'][$i] * ($rate->sharepct_provincial / 100);
                $share_municipal = $request['amount'][$i] * ($rate->sharepct_municipal / 100);
                $share_barangay = $request['amount'][$i] * ($rate->sharepct_barangay / 100);
            }
            $row['col_cash_division_id'] = $addtl->id;
            $row['col_acct_title_id'] = ($request['account_type'][$i] == 'title') ? $request['account_id'][$i] : 0;
            $row['col_acct_subtitle_id'] = ($request['account_type'][$i] == 'subtitle') ? $request['account_id'][$i] : 0;
            $row['value'] = $request['amount'][$i];
            $row['share_provincial'] = $share_provincial;
            $row['share_municipal'] = $share_municipal;
            $row['share_barangay'] = $share_barangay;
            $row['nature'] = $request['nature'][$i];

            array_push($data, $row);
        }
        CashDivisionItems::insert($data);

        if(isset($request['rpt_value'])){
            $rpt = explode('-', $request['rpt_value']);
            if($rpt[0] == 'basic'){
                RptMunicipalExcel::where('id', $rpt[1])->update(['is_printed_basic' => 1]);
            }else{
                RptMunicipalExcel::where('id', $rpt[1])->update(['is_printed_sef' => 1]);
            }
        }

        Session::flash('info', ['Successfully added Field Income record']);
        return redirect()->route('cash_division.index');
    }

    public function show($id)
    {
        $this->base['sub_header'] = 'View';
        $this->base['addtl'] = CashDivision::whereId($id)->first();

        return view('collection::cash_division.view')->with('base', $this->base);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $this->base['sandgravel_types'] = sg_types::all();
        $this->base['sub_header'] = 'Edit';
        $this->base['user'] = Session::get('user');
        $this->base['addtl'] = CashDivision::whereId($id)->first();

        $this->base['municipalities'] = Municipality::orderBy('name', 'asc')
            ->get()
            ->toarray();
        $this->base['barangays'] = Barangay::where('municipality_id', $this->base['addtl']->col_municipality_id)
            ->orderBy('name', 'asc')
            ->get()
            ->toarray();

        return view('collection::cash_division.edit')->with('base', $this->base);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $addtl = CashDivision::whereId($id)->first();

        $filter = [
            'user_id' => 'required|numeric',
            'date' => 'required|date',
            'refno' => 'required|max:300',
        ];

        if (in_array(1, $request['account_is_shared'])) {
            $filter['municipality'] = 'required';
            // $filter['brgy'] = 'required';
        }



        $validator = Validator::make($request->all(), $filter);
        if ($validator->fails()) {
            return redirect()->route('cash_division.edit', ['id' => $id])
                ->withErrors($validator);
        } elseif (in_array('', $request['account_id'])) {
            $validator->getMessageBag()
                ->add('account', 'An account field is empty or not identified');
            return redirect()->route('cash_division.edit', ['id' => $id])
                ->withErrors($validator);
        }

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



        # Successful validation
        $addtl->col_municipality_id = (!empty($request['municipality'])) ? $request['municipality'] : '';
        $addtl->col_customer_id = $payor_id;
        $addtl->sex = (!empty($request['Sex'])) ? $request['Sex'] : '';
        $addtl->col_barangay_id = (!empty($request['brgy'])) ? $request['brgy'] : '';
        $addtl->dnlx_user_id = $request['user_id'];
        $addtl->date_of_entry = date('Y-m-d', strtotime($request['date']));
        $addtl->refno = $request['refno'];
        $addtl->client_type = $request['customer_type'];
        $addtl->save();

        # Update items
        $items = $addtl->items;
        foreach ($items as $item) {
            $item->delete();
        }

        $data = array();
        foreach ($request['account_id'] as $i => $ai) {
            $rate_type = ($request['account_type'][$i] == 'title') ? 'col_acct_title_id' : 'col_acct_subtitle_id';
            $rate = CollectionRate::where($rate_type, '=', $request['account_id'][$i])->first();

            $share_provincial = $request['amount'][$i];
            $share_municipal = 0;
            $share_barangay = 0;
            if (!empty($rate) && $rate->is_shared == 1) {
                $share_provincial = $request['amount'][$i] * ($rate->sharepct_provincial / 100);
                $share_municipal = $request['amount'][$i] * ($rate->sharepct_municipal / 100);
                $share_barangay = $request['amount'][$i] * ($rate->sharepct_barangay / 100);
            }
            $row['col_cash_division_id'] = $addtl->id;
            $row['col_acct_title_id'] = ($request['account_type'][$i] == 'title') ? $request['account_id'][$i] : 0;
            $row['col_acct_subtitle_id'] = ($request['account_type'][$i] == 'subtitle') ? $request['account_id'][$i] : 0;
            $row['value'] = $request['amount'][$i];
            $row['share_provincial'] = $share_provincial;
            $row['share_municipal'] = $share_municipal;
            $row['share_barangay'] = $share_barangay;
            $row['nature'] = $request['nature'][$i];

            array_push($data, $row);
        }
        CashDivisionItems::insert($data);

        Session::flash('info', ['Successfully updated record']);
        return redirect()->route('cash_division.index');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }

    public function adjustment_add(Request $req) {
        // dd($req->adj_mnth);
        CashDivAdjustment::updateOrCreate(
            [
                'month' => $req->adj_mnth,
                'year' => $req->adj_yr,
                'type' => $req->adj_type,
                'amount' => $req->adj_amt
            ]
        );
        return redirect()->route('cash_division.index');
    }

    public function cashdiv_report_others(Request $request){
        $this->base['cash_div_type'] = $request->cash_div_type;
        $date = Carbon::createFromDate($request->year, $request->month, 1, 'Asia/Manila');
        $this->base['datex'] = $date;

        $days = Carbon::createFromDate($request->year, $request->month, 1, 'Asia/Manila');
        $end_mnth = $date->endOfMonth()->format('d') ;
        $start_mnth = $date->startOfMonth()->format('d') ;
        $municpality = Municipality::all();
           $d = 0;
          $month_p = [];
          $opag = [];
          $pvet = [];
          $coldchain = [];
          $opp = [];

          /* HOSPITALS */
          $drugsmeds = [];
          $medlabsden = [];
          $hospitals = [];
          $hothersrvcs = [];
          $h_dates = [];
          $h_datesx = [];

        // RPT 
        $mun_rpt_advance = [];

        for($x=1; $x<=$end_mnth ; $x++){
              $month_x =$days->addDays($d);
              $month_p[$x]['y-m-d'] = $month_x->format('Y-m-d');
              $month_px[$x]['y-m-d'] = $month_x->format('F d');
              $month_pxx[$x]['y-m-d'] = $month_x->format('j');
              $d = 1;
              /**
               * OPAG
               */
                    if( $this->base['cash_div_type'] == 'OPAg' ){
                        $opagx_sales = DB::table('col_cash_division')
                            ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                            ->select(db::raw('col_cash_division.id,col_cash_division.refno ,col_cash_division.date_of_entry , SUM(col_cash_division_items.value) as value'))
                            ->where('col_cash_division.date_of_entry','=',$month_p[$x]['y-m-d'])
                            ->where('col_cash_division_items.col_acct_subtitle_id','=','3')
                            ->get();

                            if($opagx_sales[0]->value){
                                    $opag[$month_pxx[$x]['y-m-d']]['sales'] = $opagx_sales;
                            }

                        $opagx_lodging = DB::table('col_cash_division')
                            ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                            ->select(db::raw('col_cash_division.id,col_cash_division.refno ,col_cash_division.date_of_entry , SUM(col_cash_division_items.value) as value'))
                            ->where('col_cash_division.date_of_entry','=',$month_p[$x]['y-m-d'])
                            ->where('col_cash_division_items.col_acct_subtitle_id','=','10')
                            ->get();

                        if($opagx_lodging[0]->value){
                                $opag[$month_pxx[$x]['y-m-d']]['lodging'] = $opagx_lodging;
                        }
                    }elseif( $this->base['cash_div_type'] == 'PVET' ){
                        $pvety = DB::table('col_cash_division')
                            ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                            ->select(db::raw('col_cash_division.id,col_cash_division.refno ,col_cash_division.date_of_entry , SUM(col_cash_division_items.value) as value'))
                            ->where('col_cash_division.date_of_entry','=',$month_p[$x]['y-m-d'])
                            // ->where('col_cash_division_items.col_acct_title_id','=','61')
                            ->where(function($q) {
                                $q->where('col_cash_division_items.col_acct_title_id','=','61')
                                    ->orWhere('client_type', '=', '54');
                            })
                            ->groupby('col_cash_division.refno')
                            ->get();

                        $pto = DB::table('col_receipt')
                            ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                            ->select(db::raw('col_receipt.id, col_receipt.date_of_entry , SUM(col_receipt_items.value) as value'))
                            ->where('col_receipt.report_date','=',$month_p[$x]['y-m-d'])
                            // ->where('col_receipt_items.col_acct_title_id','=','61')
                            ->where(function($q) {
                                $q->where('col_receipt_items.col_acct_title_id','=','61')
                                    ->orWhere('client_type', '=', '54');
                            })
                            ->where('col_receipt.is_cancelled', '=', '0')
                            ->first();


                        // if($pvety){
                        if($pvety || !is_null($pto->value)){
                            $pvet[$month_pxx[$x]['y-m-d']]['pto'] = $pto;
                        }

                        if(isset($pvety[0]->id)){
                            foreach ($pvety as $key => $pvetyx) {
                                    $pvetx = DB::table('col_cash_division')
                                        ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                                        ->where('col_cash_division.date_of_entry','=',$month_p[$x]['y-m-d'])
                                        ->where('col_cash_division_items.col_acct_subtitle_id','=','5')
                                        ->where('col_cash_division.refno','=',$pvetyx->refno)
                                        ->first();

                                    $pvetz = DB::table('col_cash_division')
                                        ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                                        ->select(db::raw('col_cash_division.id,col_cash_division.refno ,col_cash_division.date_of_entry , SUM(col_cash_division_items.value) as value'))
                                        ->where('col_cash_division.date_of_entry','=',$month_p[$x]['y-m-d'])
                                        ->where('col_cash_division_items.col_acct_title_id','=','19')
                                        ->where('col_cash_division.refno','=',$pvetyx->refno)
                                        ->first();

                                    $pvetyy = DB::table('col_cash_division')
                                        ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                                        ->select(db::raw('col_cash_division.id,col_cash_division.refno ,col_cash_division.date_of_entry , SUM(col_cash_division_items.value) as value'))
                                        ->where('col_cash_division.date_of_entry','=',$month_p[$x]['y-m-d'])
                                        ->where('col_cash_division_items.col_acct_title_id','=','61')
                                        ->where('col_cash_division.refno','=',$pvetyx->refno)
                                        ->first();

                                    // dd($pvetyy);
                                    if(isset($pvetx)){
                                            $pvet[$month_pxx[$x]['y-m-d']]['sales'][$pvetyx->refno] = $pvetx;
                                    }
                                    if(($pvetyy)){
                                        $pvet[$month_pxx[$x]['y-m-d']]['61'][$pvetyx->refno] = $pvetyy;
                                    }

                                    if(isset($pvetz)){
                                            $pvet[$month_pxx[$x]['y-m-d']]['19'][$pvetyx->refno] = $pvetz;
                                    }
                                    
                            }

                        }


// here


                    }elseif( $this->base['cash_div_type'] == 'COLD CHAIN' ){
                        $coldchainx = DB::table('col_cash_division')
                                                ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                                                ->select(db::raw('col_cash_division.id,col_cash_division.refno ,col_cash_division.date_of_entry , SUM(col_cash_division_items.value) as value'))
                                                ->where('col_cash_division.date_of_entry','=',$month_p[$x]['y-m-d'])
                                                ->where('col_cash_division_items.col_acct_subtitle_id','=','9')
                                                ->get();

                        if($coldchainx[0]->value){
                                $coldchain[$month_pxx[$x]['y-m-d']] = $coldchainx;
                        }
                    }elseif( $this->base['cash_div_type'] == 'CERTIFICATIONS OPP - DOJ' ){
                        $oppx = DB::table('col_cash_division')
                                                ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                                                ->select(db::raw('col_cash_division.id,col_cash_division.refno ,col_cash_division.date_of_entry , SUM(col_cash_division_items.value) as value'))
                                                ->where('col_cash_division.date_of_entry','=',$month_p[$x]['y-m-d'])
                                                ->where('col_cash_division_items.col_acct_title_id','=','19')
                                                ->where('col_cash_division.refno','NOT LIKE','%GF%')
                                                ->get();

                        if($oppx[0]->value){
                                $opp[$month_pxx[$x]['y-m-d']] = $oppx;
                        }
                   }


        }
        $rpt_dates = $mun_rpt = '';
        if( $this->base['cash_div_type'] == 'PROVINCIAL HEALTH OFFICE' ){
          $hospitals_clients = DB::table('col_cash_division')
                                                ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                                                ->leftjoin('col_customer','col_customer.id','=','col_cash_division.col_customer_id')
                                                ->select(db::raw('col_cash_division.refno ,col_customer.name,col_cash_division.col_customer_id'))
                                                ->where('col_cash_division.date_of_entry','>=',$date->startOfMonth()->format('Y-m-d'))
                                                ->where('col_cash_division.date_of_entry','<=',$date->endOfMonth()->format('Y-m-d'))
                                                ->orwhere('col_cash_division_items.col_acct_title_id','=','17')
                                                ->orwhere('col_cash_division_items.col_acct_subtitle_id','=','12')
                                                ->orwhere('col_cash_division_items.col_acct_title_id','=','26')
                                                ->orwhere('col_cash_division_items.col_acct_title_id','=','22')
                                                ->groupby('col_cash_division.col_customer_id')
                                                ->get();
          for($x=1; $x<=$end_mnth ; $x++){
            foreach ($hospitals_clients as $key => $value) {
                 $drugsmedsx = DB::table('col_cash_division')
                                                ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                                                 ->leftjoin('col_customer','col_customer.id','=','col_cash_division.col_customer_id')
                                                ->select(db::raw('col_cash_division.refno ,col_cash_division.date_of_entry , SUM(col_cash_division_items.value) as value, col_customer.name,col_cash_division.id'))
                                                ->where('col_cash_division.date_of_entry','=',$month_p[$x]['y-m-d'])
                                                ->where('col_cash_division_items.col_acct_subtitle_id','=','7')
                                                 ->where('col_cash_division.col_customer_id','=',$value->col_customer_id)
                                                ->groupby('col_cash_division.col_customer_id')
                                                ->get();

                        if($drugsmedsx){
                                $h_datesx[$month_pxx[$x]['y-m-d']]  = $month_p[$x]['y-m-d'];
                                $hospitals[$month_pxx[$x]['y-m-d']][$value->name]['drugsmeds'] = $drugsmedsx;
                        }

             $medlabsdenx = DB::table('col_cash_division')
                                                ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                                                 ->leftjoin('col_customer','col_customer.id','=','col_cash_division.col_customer_id')
                                                ->select(db::raw('col_cash_division.refno ,col_cash_division.date_of_entry ,SUM(col_cash_division_items.value) as value, col_customer.name,col_cash_division.id '))
                                                ->where('col_cash_division.date_of_entry','=',$month_p[$x]['y-m-d'])
                                                ->where('col_cash_division_items.col_acct_subtitle_id','=','12')
                                                ->where('col_cash_division.col_customer_id','=',$value->col_customer_id)
                                                ->groupby('col_cash_division.col_customer_id')
                                                ->get();

                        if($medlabsdenx){
                                $h_datesx[$month_pxx[$x]['y-m-d']]  = $month_p[$x]['y-m-d'];
                                $hospitals[$month_pxx[$x]['y-m-d']][$value->name]['medlabsden'] = $medlabsdenx;
                        }

            $hospitalsx = DB::table('col_cash_division')
                                                ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                                                ->leftjoin('col_customer','col_customer.id','=','col_cash_division.col_customer_id')
                                                ->select(db::raw('col_cash_division.refno ,col_cash_division.date_of_entry ,SUM(col_cash_division_items.value) as value, col_customer.name,col_cash_division.id '))
                                                ->where('col_cash_division.date_of_entry','=',$month_p[$x]['y-m-d'])
                                                ->where('col_cash_division_items.col_acct_title_id','=','26')
                                                ->where('col_cash_division.col_customer_id','=',$value->col_customer_id)
                                                ->groupby('col_cash_division.col_customer_id')
                                                ->get();

                        if($hospitalsx){
                                $h_datesx[$month_pxx[$x]['y-m-d']]  = $month_p[$x]['y-m-d'];
                                 $hospitals[$month_pxx[$x]['y-m-d']][$value->name]['hospitals'] = $hospitalsx;
                        }

            $hothersrvcsx = DB::table('col_cash_division')
                                                ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                                                 ->leftjoin('col_customer','col_customer.id','=','col_cash_division.col_customer_id')
                                                ->select(db::raw('col_cash_division.refno ,col_cash_division.date_of_entry ,SUM(col_cash_division_items.value) as value, col_customer.name,col_cash_division.id'))
                                                ->where('col_cash_division.date_of_entry','=',$month_p[$x]['y-m-d'])
                                                ->where('col_cash_division_items.col_acct_title_id','=','22')
                                                ->where('col_cash_division.col_customer_id','=',$value->col_customer_id)
                                                ->groupby('col_cash_division.col_customer_id')
                                                ->get();

                        if($hothersrvcsx){
                                $h_datesx[$month_pxx[$x]['y-m-d']]  = $month_p[$x]['y-m-d'];
                                $hospitals[$month_pxx[$x]['y-m-d']][$value->name]['hothersrvcs'] = $hothersrvcsx;
                        }
            }

                        $h_dates[$month_px[$x]['y-m-d']]  = [$month_px[$x]['y-m-d'] , $month_p[$x]['y-m-d']];
          }
          }elseif( $this->base['cash_div_type'] == 'RPT' ){

          /*RPT*/
          $this->base['municipalities'] = Municipality::all()->toarray();
          $mun_rpt = [];
          $rpt_dates = [];
            for($x=1; $x<=$end_mnth ; $x++){
                foreach ($this->base['municipalities'] as $key => $value) {
                    // $rpt_basic = DB::table('col_cash_division')
                    //     ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                    //      ->leftjoin('col_customer','col_customer.id','=','col_cash_division.col_customer_id')
                    //     ->select(db::raw('col_cash_division.date_of_entry ,SUM(col_cash_division_items.value) as value, col_customer.name,col_cash_division.id'))
                    //     ->where('col_cash_division.date_of_entry','=',$month_p[$x]['y-m-d'])
                    //     ->where('col_cash_division_items.col_acct_title_id','=','2')
                    //     ->where('col_cash_division.col_municipality_id','=',$value['id'])
                    //     ->groupby('col_cash_division.col_municipality_id')
                    //     ->get();

                    // if($rpt_basic){
                    //         $rpt_dates[$month_pxx[$x]['y-m-d']]  = $month_p[$x]['y-m-d'];
                    //         $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']]['rpt_basic'] = $rpt_basic;
                    // }

                    // $rpt_basic_penalty = DB::table('col_cash_division')
                    //     ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                    //      ->leftjoin('col_customer','col_customer.id','=','col_cash_division.col_customer_id')
                    //     ->select(db::raw('col_cash_division.date_of_entry ,SUM(col_cash_division_items.value) as value, col_customer.name,col_cash_division.id'))
                    //     ->where('col_cash_division.date_of_entry','=',$month_p[$x]['y-m-d'])
                    //     ->where('col_cash_division_items.col_acct_title_id','=','54')
                    //     ->where('col_cash_division.col_municipality_id','=',$value['id'])
                    //     ->groupby('col_cash_division.col_municipality_id')
                    //     ->get();

                    // if($rpt_basic_penalty){
                    //         $rpt_dates[$month_pxx[$x]['y-m-d']]  = $month_p[$x]['y-m-d'];
                    //         $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']]['rpt_basic_penalty'] = $rpt_basic_penalty;
                    // }

                    // $special_educfund = DB::table('col_cash_division')
                    //     ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                    //      ->leftjoin('col_customer','col_customer.id','=','col_cash_division.col_customer_id')
                    //     ->select(db::raw('col_cash_division.date_of_entry ,SUM(col_cash_division_items.value) as value, col_customer.name,col_cash_division.id'))
                    //     ->where('col_cash_division.date_of_entry','=',$month_p[$x]['y-m-d'])
                    //     ->where('col_cash_division_items.col_acct_title_id','=','49')
                    //     ->where('col_cash_division.col_municipality_id','=',$value['id'])
                    //     ->groupby('col_cash_division.col_municipality_id')
                    //     ->get();

                    // if($special_educfund){
                    //         $rpt_dates[$month_pxx[$x]['y-m-d']]  = $month_p[$x]['y-m-d'];
                    //         $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']]['special_educfund'] = $special_educfund;
                    // }

                    // $sef_penalty = DB::table('col_cash_division')
                    //     ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                    //      ->leftjoin('col_customer','col_customer.id','=','col_cash_division.col_customer_id')
                    //     ->select(db::raw('col_cash_division.date_of_entry ,SUM(col_cash_division_items.value) as value, col_customer.name,col_cash_division.id'))
                    //     ->where('col_cash_division.date_of_entry','=',$month_p[$x]['y-m-d'])
                    //     ->where('col_cash_division_items.col_acct_title_id','=','55')
                    //     ->where('col_cash_division.col_municipality_id','=',$value['id'])
                    //     ->groupby('col_cash_division.col_municipality_id')
                    //     ->get();

                    // if($sef_penalty){
                    //         $rpt_dates[$month_pxx[$x]['y-m-d']]  = $month_p[$x]['y-m-d'];
                    //         $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']]['sef_penalty'] = $sef_penalty;
                    // }

                    $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']] = array(
                        'rpt_basic' => 0,
                        'rpt_basic_penalty' => 0,
                        'special_educfund' => 0,
                        'sef_penalty' => 0,
                        'discount_basic' => 0,
                        'discount_sef' => 0,
                        'advance' => 0
                    );
                    $rpt = Receipt::with('items')
                        ->where('report_date','=', $month_p[$x]['y-m-d'])
                        ->where('is_printed', '=', 1)
                        ->where('af_type', '=', 2)
                        ->where('col_municipality_id', '=', $value['id'])
                        ->where('remarks', 'not like', '%paid under protest%')
                        ->where('remarks', 'not like', '%held in trust%')
                        ->where('bank_remark', 'not like', '%paid under protest%')
                        ->where('bank_remark', 'not like', '%held in trust%')
                        ->whereHas('F56Detailmny', function($query) {
                            $query->where('period_covered', 'not like', Carbon::now()->addYear()->format('Y'))
                                ->where('period_covered', 'not like', '%advance%');
                        })
                        ->orderBy('serial_no', 'ASC')
                        ->get();
                    
                    $rpt_basic = [];
                    $rpt_basic_penalty = [];
                    $special_educfund = [];
                    $sef_penalty = [];
                    $discount = [];
                    if (count($rpt) > 0) {
                        foreach($rpt as $key => $r) {
                            // if($count == 0) {
                                $rpt_basic[$r->serial_no] = 0;
                                $rpt_basic_penalty[$r->serial_no] = 0;
                                $special_educfund[$r->serial_no] = 0;
                                $sef_penalty[$r->serial_no] = 0;
                                $discount[$r->serial_no] = 0;
                            // }
                            
                            if ($r->is_cancelled) {
                                continue;
                            }
                            if ($r->F56Detailmny()->count() > 0) {
                                foreach($r->F56Detailmny as $det) {
                                    // percentages and values based from RPT municipal report - provincial share
                                    $rpt_basic[$r->serial_no] += $det->basic_current + $det->basic_previous;
                                    $rpt_basic_penalty[$r->serial_no] += $det->basic_penalty_current + $det->basic_penalty_previous;
                                    $special_educfund[$r->serial_no] += $det->basic_current + $det->basic_previous;
                                    $sef_penalty[$r->serial_no] += $det->basic_penalty_current + $det->basic_penalty_previous;
                                    $discount[$r->serial_no] += $det->basic_discount;
                                    // $count++;

                                    //orig, medj working
                                    // $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']]['rpt_basic'] = ($det->basic_current + $det->basic_previous)*.35;
                                    // $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']]['rpt_basic_penalty'] = ($det->basic_penalty_current + $det->basic_penalty_previous)*.35;
                                    // $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']]['special_educfund'] = ($det->basic_current + $det->basic_previous)*.5;
                                    // $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']]['sef_penalty'] = ($det->basic_penalty_current + $det->basic_penalty_previous)*.5;
                                    // $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']]['discount_basic'] = $det->basic_discount*.35;
                                    // $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']]['discount_sef'] = $det->basic_discount*.5;
                                }
                                // $count = 0;
                            }
                        }
                        // dd($rpt_basic);
                        $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']]['rpt_basic'] = array_sum($rpt_basic)*.35;
                        $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']]['rpt_basic_penalty'] = array_sum($rpt_basic_penalty)*.35;
                        $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']]['special_educfund'] = array_sum($special_educfund)*.5;
                        $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']]['sef_penalty'] = array_sum($sef_penalty)*.5;
                        $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']]['discount_basic'] = array_sum($discount)*.35;
                        $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']]['discount_sef'] = array_sum($discount)*.5;
                    }   
                    
                    // advance payments
                    // $mun_rpt_advance[$month_pxx[$x]['y-m-d']][$value['name']] = 0;
                    // $rpt_advance = Receipt::join('col_f56_detail', 'col_f56_detail.col_receipt_id', '=', 'col_receipt.id')
                    //     ->where(function($query) {
                    //         $query->where('period_covered','>=', Carbon::now()->addYear()->format('Y'))
                    //             ->orWhere('period_covered', 'like', '%advance%');
                    //     })
                    //     ->where('report_date','=', $month_p[$x]['y-m-d'])
                    //     ->where('is_printed', '=', 1)
                    //     ->where('af_type', '=', 2)
                    //     ->where('col_municipality_id', '=', $value['id'])
                    //     ->orderBy('serial_no', 'ASC')
                    //     ->get();

                    $rpt_advance = Receipt::with('F56Detailmny')
                        ->where('report_date','=', $month_p[$x]['y-m-d'])
                        ->where('is_printed', '=', 1)
                        ->where('af_type', '=', 2)
                        ->where('col_municipality_id', '=', $value['id'])
                        ->whereHas('F56Detailmny', function($query) {
                            $query->where(function($q) {
                                $q->where('period_covered', '=', Carbon::now()->addYear()->format('Y'))
                                    ->orWhere('period_covered', 'like', '%advance%');
                            });
                        })
                        ->orderBy('serial_no', 'ASC')
                        ->get();
                    
                    if(count($rpt_advance) > 0) {
                        // dd($rpt_advance);
                        foreach ($rpt_advance as $key => $rpt) {
                            if ($rpt->is_cancelled) {
                                continue;
                            }
                            foreach($rpt['F56Detailmny'] as $detail) {
                                if ($detail->period_covered >= Carbon::now()->addYear()->format('Y') || preg_match('/advance/i', $rpt->period_covered) == 1) {
                                    $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']]['advance'] += ($detail->tdrp_assedvalue*.01)*.35;
                                    // dd($mun_rpt);
                                }
                            }
                        }
                    }
                /**************************************************************************************************/
                    $prof_tax = DB::table('col_cash_division')
                        ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                        ->leftjoin('col_customer','col_customer.id','=','col_cash_division.col_customer_id')
                        ->select(db::raw('col_cash_division.date_of_entry ,SUM(col_cash_division_items.value) as value, col_customer.name,col_cash_division.id'))
                        ->where('col_cash_division.date_of_entry','=',$month_p[$x]['y-m-d'])
                        ->where('col_cash_division_items.col_acct_title_id','=','1')
                        ->where('col_cash_division.col_municipality_id','=',$value['id'])
                        ->groupby('col_cash_division.col_municipality_id')
                        ->get();

                    // $prof_tax = DB::table('col_receipt')
                    //     ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt_items.id')
                    //     ->leftjoin('col_customer','col_customer.id','=','col_receipt.col_customer_id')
                    //     ->select(db::raw('col_receipt.date_of_entry ,SUM(col_receipt_items.value) as value, col_customer.name,col_receipt.id'))
                    //     ->where('col_receipt.report_date','=',$month_p[$x]['y-m-d'])
                    //     ->where('col_receipt_items.col_acct_title_id','=','1')
                    //     ->where('col_receipt.col_municipality_id','=',$value['id'])
                    //     ->groupby('col_receipt.col_municipality_id')
                    //     ->get();

                    if($prof_tax){
                            $rpt_dates[$month_pxx[$x]['y-m-d']]  = $month_p[$x]['y-m-d'];
                            $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']]['prof_tax'] = $prof_tax;
                    }

                    $prof_tax_fines = DB::table('col_cash_division')
                        ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                         ->leftjoin('col_customer','col_customer.id','=','col_cash_division.col_customer_id')
                        ->select(db::raw('col_cash_division.date_of_entry ,SUM(col_cash_division_items.value) as value, col_customer.name,col_cash_division.id'))
                        ->where('col_cash_division.date_of_entry','=',$month_p[$x]['y-m-d'])
                        ->where('col_cash_division_items.col_acct_title_id','=','10')
                        ->where('col_cash_division.col_municipality_id','=',$value['id'])
                        ->groupby('col_cash_division.col_municipality_id')
                        ->get();

                    // $prof_tax_fines = DB::table('col_receipt')
                    //     ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                    //     ->leftjoin('col_customer','col_customer.id','=','col_receipt.col_customer_id')
                    //     ->select(db::raw('col_receipt.date_of_entry ,SUM(col_receipt_items.value) as value, col_customer.name,col_receipt.id'))
                    //     ->where('col_receipt.report_date','=',$month_p[$x]['y-m-d'])
                    //     ->where('col_receipt_items.col_acct_title_id','=','10')
                    //     ->where('col_receipt.col_municipality_id','=',$value['id'])
                    //     ->groupby('col_receipt.col_municipality_id')
                    //     ->get();

                    if($prof_tax_fines){
                            $rpt_dates[$month_pxx[$x]['y-m-d']]  = $month_p[$x]['y-m-d'];
                            $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']]['prof_tax_fines'] = $prof_tax_fines;
                    }

                    $permit_fees = DB::table('col_cash_division')
                        ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                         ->leftjoin('col_customer','col_customer.id','=','col_cash_division.col_customer_id')
                        ->select(db::raw('col_cash_division.date_of_entry ,SUM(col_cash_division_items.value) as value, col_customer.name,col_cash_division.id'))
                        ->where('col_cash_division.date_of_entry','=',$month_p[$x]['y-m-d'])
                        ->where('col_cash_division_items.col_acct_title_id','=','18')
                        ->where('col_cash_division.col_municipality_id','=',$value['id'])
                        ->groupby('col_cash_division.col_municipality_id')
                        ->get();

                    // $permit_fees = DB::table('col_receipt')
                    //     ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                    //     ->leftjoin('col_customer','col_customer.id','=','col_receipt.col_customer_id')
                    //     ->select(db::raw('col_receipt.date_of_entry ,SUM(col_receipt_items.value) as value, col_customer.name,col_receipt.id'))
                    //     ->where('col_receipt.report_date','=',$month_p[$x]['y-m-d'])
                    //     ->where('col_receipt_items.col_acct_title_id','=','18')
                    //     ->where('col_receipt.col_municipality_id','=',$value['id'])
                    //     ->groupby('col_receipt.col_municipality_id')
                    //     ->get();

                    if($permit_fees){
                            $rpt_dates[$month_pxx[$x]['y-m-d']]  = $month_p[$x]['y-m-d'];
                            $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']]['permit_fees'] = $permit_fees;
                    }

                    $permit_fees_fines = DB::table('col_cash_division')
                        ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                         ->leftjoin('col_customer','col_customer.id','=','col_cash_division.col_customer_id')
                        ->select(db::raw('col_cash_division.date_of_entry ,SUM(col_cash_division_items.value) as value, col_customer.name,col_cash_division.id'))
                        ->where('col_cash_division.date_of_entry','=',$month_p[$x]['y-m-d'])
                        ->where('col_cash_division_items.col_acct_title_id','=','21')
                        ->where('col_cash_division.col_municipality_id','=',$value['id'])
                        ->groupby('col_cash_division.col_municipality_id')
                        ->get();

                    // $permit_fees_fines = DB::table('col_receipt')
                    //     ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                    //     ->leftjoin('col_customer','col_customer.id','=','col_receipt.col_customer_id')
                    //     ->select(db::raw('col_receipt.date_of_entry ,SUM(col_receipt_items.value) as value, col_customer.name,col_receipt.id'))
                    //     ->where('col_receipt.report_date','=',$month_p[$x]['y-m-d'])
                    //     ->where('col_receipt_items.col_acct_title_id','=','21')
                    //     ->where('col_receipt.col_municipality_id','=',$value['id'])
                    //     ->groupby('col_receipt.col_municipality_id')
                    //     ->get();

                    if($permit_fees_fines){
                            $rpt_dates[$month_pxx[$x]['y-m-d']]  = $month_p[$x]['y-m-d'];
                            $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']]['permit_fees_fines'] = $permit_fees_fines;
                    }

                    $permit_fees_fines = DB::table('col_cash_division')
                        ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                        ->leftjoin('col_customer','col_customer.id','=','col_cash_division.col_customer_id')
                        ->select(db::raw('col_cash_division.date_of_entry ,SUM(col_cash_division_items.value) as value, col_customer.name,col_cash_division.id'))
                        ->where('col_cash_division.date_of_entry','=',$month_p[$x]['y-m-d'])
                        ->where('col_cash_division_items.col_acct_title_id','=','21')
                        ->where('col_cash_division.col_municipality_id','=',$value['id'])
                        ->groupby('col_cash_division.col_municipality_id')
                        ->get();

                    // $permit_fees_fines = DB::table('col_receipt')
                    //     ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                    //     ->leftjoin('col_customer','col_customer.id','=','col_receipt.col_customer_id')
                    //     ->select(db::raw('col_receipt.date_of_entry ,SUM(col_receipt_items.value) as value, col_customer.name,col_receipt.id'))
                    //     ->where('col_receipt.report_date','=',$month_p[$x]['y-m-d'])
                    //     ->where('col_receipt_items.col_acct_title_id','=','21')
                    //     ->where('col_receipt.col_municipality_id','=',$value['id'])
                    //     ->groupby('col_receipt.col_municipality_id')
                    //     ->get();

                    if($permit_fees_fines){
                            $rpt_dates[$month_pxx[$x]['y-m-d']]  = $month_p[$x]['y-m-d'];
                            $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']]['permit_fees_fines'] = $permit_fees_fines;
                    }

                    $tax_sand_gravel = DB::table('col_cash_division')
                        ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                        ->leftjoin('col_customer','col_customer.id','=','col_cash_division.col_customer_id')
                        ->select(db::raw('col_cash_division.date_of_entry ,SUM(col_cash_division_items.value) as value, col_customer.name,col_cash_division.id'))
                        ->where('col_cash_division.date_of_entry','=',$month_p[$x]['y-m-d'])
                        ->where('col_cash_division_items.col_acct_title_id','=','4')
                        ->where('col_cash_division.col_municipality_id','=',$value['id'])
                        ->groupby('col_cash_division.col_municipality_id')
                        ->get();

                    // $tax_sand_gravel = DB::table('col_receipt')
                    //     ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                    //     ->leftjoin('col_customer','col_customer.id','=','col_receipt.col_customer_id')
                    //     ->select(db::raw('col_receipt.date_of_entry ,SUM(col_receipt_items.value) as value, col_customer.name,col_receipt.id'))
                    //     ->where('col_receipt.report_date','=',$month_p[$x]['y-m-d'])
                    //     ->where('col_receipt_items.col_acct_title_id','=','4')
                    //     ->where('col_receipt.col_municipality_id','=',$value['id'])
                    //     ->groupby('col_receipt.col_municipality_id')
                    //     ->get();

                    if($tax_sand_gravel){
                            $rpt_dates[$month_pxx[$x]['y-m-d']]  = $month_p[$x]['y-m-d'];
                            $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']]['tax_sand_gravel'] = $tax_sand_gravel;
                    }

                    $mining_tax = DB::table('col_cash_division')
                        ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                        ->leftjoin('col_customer','col_customer.id','=','col_cash_division.col_customer_id')
                        ->select(db::raw('col_cash_division.date_of_entry ,SUM(col_cash_division_items.value) as value, col_customer.name,col_cash_division.id'))
                        ->where('col_cash_division.date_of_entry','=',$month_p[$x]['y-m-d'])
                        ->where('col_cash_division_items.col_acct_title_id','=','9')
                        ->where('col_cash_division.col_municipality_id','=',$value['id'])
                        ->groupby('col_cash_division.col_municipality_id')
                        ->get();

                    // $mining_tax = DB::table('col_receipt')
                    //     ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                    //     ->leftjoin('col_customer','col_customer.id','=','col_receipt.col_customer_id')
                    //     ->select(db::raw('col_receipt.date_of_entry ,SUM(col_receipt_items.value) as value, col_customer.name,col_receipt.id'))
                    //     ->where('col_receipt.report_date','=',$month_p[$x]['y-m-d'])
                    //     ->where('col_receipt_items.col_acct_title_id','=','9')
                    //     ->where('col_receipt.col_municipality_id','=',$value['id'])
                    //     ->groupby('col_receipt.col_municipality_id')
                    //     ->get();

                    if($mining_tax){
                            $rpt_dates[$month_pxx[$x]['y-m-d']]  = $month_p[$x]['y-m-d'];
                            $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']]['mining_tax'] = $mining_tax;
                    }

                    $acct_forms = DB::table('col_cash_division')
                        ->join('col_cash_division_items','col_cash_division_items.col_cash_division_id','=','col_cash_division.id')
                        ->leftjoin('col_customer','col_customer.id','=','col_cash_division.col_customer_id')
                        ->select(db::raw('col_cash_division.date_of_entry ,SUM(col_cash_division_items.value) as value, col_customer.name,col_cash_division.id'))
                        ->where('col_cash_division.date_of_entry','=',$month_p[$x]['y-m-d'])
                        ->where('col_cash_division_items.col_acct_subtitle_id','=','6')
                        ->where('col_cash_division.col_municipality_id','=',$value['id'])
                        ->groupby('col_cash_division.col_municipality_id')
                        ->get();

                    // $acct_forms = DB::table('col_receipt')
                    //     ->join('col_receipt_items','col_receipt_items.col_receipt_id','=','col_receipt.id')
                    //     ->leftjoin('col_customer','col_customer.id','=','col_receipt.col_customer_id')
                    //     ->select(db::raw('col_receipt.date_of_entry ,SUM(col_receipt_items.value) as value, col_customer.name,col_receipt.id'))
                    //     ->where('col_receipt.report_date','=',$month_p[$x]['y-m-d'])
                    //     ->where('col_receipt_items.col_acct_subtitle_id','=','6')
                    //     ->where('col_receipt.col_municipality_id','=',$value['id'])
                    //     ->groupby('col_receipt.col_municipality_id')
                    //     ->get();

                    if($acct_forms){
                        $rpt_dates[$month_pxx[$x]['y-m-d']]  = $month_p[$x]['y-m-d'];
                        $mun_rpt[$month_pxx[$x]['y-m-d']][$value['name']]['acct_forms'] = $acct_forms;
                    }
                }
            }  
        }

        $this->base['rpt_advance'] = $mun_rpt_advance;

        $this->base['acctble_officer_name'] = ReportOfficers::whereId(10)->first();
        $this->base['acctble_officer_position'] = ReportOfficers::whereId(11)->first();

        $date = Carbon::createFromDate($request['year'],$request['month'],'01')->format('F Y');
        if($this->base['cash_div_type'] == 'OPAg') {
            if(count($opag) == 0 || is_null($opag)) {
                Session::flash('error', ['No transactions for '.$this->base['cash_div_type'].' on '.$date]);
                return redirect()->route('report.cashdiv_daily_report');
            }
        } elseif($this->base['cash_div_type'] == 'PVET') {
    // dd($pvet);
            if(count($pvet) == 0 || is_null($pvet)) {
                Session::flash('error', ['No transactions for '.$this->base['cash_div_type'].' on '.$date]);
                return redirect()->route('report.cashdiv_daily_report');
            }
        } elseif($this->base['cash_div_type'] == 'COLD CHAIN') {
            if(count($coldchain) == 0 || is_null($coldchain)) {
                Session::flash('error', ['No transactions for '.$this->base['cash_div_type'].' on '.$date]);
                return redirect()->route('report.cashdiv_daily_report');
            }
        } elseif($this->base['cash_div_type'] == 'CERTIFICATIONS OPP - DOJ') {
            if(count($opp) == 0 || is_null($opp)) {
                Session::flash('error', ['No transactions for '.$this->base['cash_div_type'].' on '.$date]);
                return redirect()->route('report.cashdiv_daily_report');
            }
        } elseif($this->base['cash_div_type'] == 'PROVINCIAL HEALTH OFFICE') {
            if(count($hospitals) == 0 || is_null($hospitals)) {
                Session::flash('error', ['No transactions for '.$this->base['cash_div_type'].' on '.$date]);
                return redirect()->route('report.cashdiv_daily_report');
            }
        } elseif($this->base['cash_div_type'] == 'RPT') {
            if(count($mun_rpt) == 0 || is_null($mun_rpt)) {
                Session::flash('error', ['No transactions for '.$this->base['cash_div_type'].' on '.$date]);
                return redirect()->route('report.cashdiv_daily_report');
            }
        }
          $this->base['opag'] = $opag;
          $this->base['pvet'] = $pvet;
          $this->base['coldchain'] = $coldchain;
          $this->base['opp'] = $opp;

          /*HOSPITALS*/
          $this->base['h_dates'] = $h_dates;
          $this->base['h_datesx'] = $h_datesx;
          $this->base['hospitals'] = $hospitals;

          /*RPT*/

          $this->base['rpt_dates'] = $rpt_dates;
          $this->base['mun_rpt'] = $mun_rpt;
// dd($mun_rpt);
          $this->base['adjustments'] = CashDivAdjustment::select(DB::raw('amount as sum'))->where('month', '=', $request->month)->where('year', '=', $request->year)->where('type', $request->cash_div_type)->orderBy('id', 'desc')->first();

          if(isset($request['button_excel'])){
            Excel::create('CASH DIVISION REPORTS', function($excel) use($opag) {
                        $excel->sheet('CASHDIV OTHER', function($sheet) use($opag) {
                            $sheet->loadView('collection::cashdiv_report.excel') ->with('base', $this->base);
                        });

                        $excel->sheet('CASHDIV HOSPITAL', function($sheet) use($opag) {
                            $sheet->loadView('collection::cashdiv_report.hospital_excel') ->with('base', $this->base);
                        });

                        $excel->sheet('CASHDIV RPT', function($sheet) use($opag) {
                            $sheet->loadView('collection::cashdiv_report.rpt_excel') ->with('base', $this->base);
                        });

                    })->export('xls');
        }else{
            $pdf = new PDF;
            $this->base['img_size'] = '35%';
            $this->base['title_size'] = '40%';
            $this->base['fsize'] = '13px';
            if(in_array($this->base['cash_div_type'] ,['RPT','PROVINCIAL HEALTH OFFICE']) ){
                $this->base['img_size'] = '25%';
                $this->base['title_size'] = '15%';
                $this->base['fsize'] = '12px';
                $pdf = PDF::loadView('collection::cashdiv_report/pdf', $this->base)
                                    ->setPaper('legal', 'landscape');
            }else{
                $pdf = PDF::loadView('collection::cashdiv_report/pdf', $this->base)
                                    ->setPaper('legal', 'portrait');
            }


            return @$pdf->stream();


        }
    }

    /* CASHDIV ADJUSTMENT */
    public function adjustment_view() {
        $this->base['sub_header'] = 'View Adjustments';
        $this->base['user'] = Session::get('user');
        return view('collection::cash_division.cashdiv_adjustments')->with('base', $this->base);
    }

    public function adjustment_view_dt(Request $req) {
        $records = CashDivAdjustment::where('year', $req->year)->get();
        return Datatables::of($records)->make(true);
    }

    public function adjustment_update(Request $req) {
        CashDivAdjustment::updateOrCreate(
            [ 
                'id' => $req->id 
            ],
            [ 
                'type' => $req->adj_type,
                'amount' => $req->adj_amt
            ]
        );
        return redirect()->route('cashdiv.adjustment_view');
    }

    public function delete_adjustment(Request $req) {
        CashDivAdjustment::where('id', $req->id)->delete();
        return redirect()->route('cashdiv.adjustment_view');
    }
    /* CASHDIV ADJUSTMENT END*/
}
