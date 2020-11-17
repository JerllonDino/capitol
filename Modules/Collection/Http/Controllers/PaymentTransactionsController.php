<?php

namespace Modules\Collection\Http\Controllers;

use Illuminate\Http\{Request,Response};
use Illuminate\Support\Facades\{Session,Validator};
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Modules\Collection\Entities\Barangay;
use Modules\Collection\Entities\F56Type;
use Modules\Collection\Entities\Form;
use Modules\Collection\Entities\Municipality;
use Modules\Collection\Entities\PCSettings;
use Modules\Collection\Entities\TransactionType;
use Modules\Collection\Entities\SandGravelTypes as sg_types;

class PaymentTransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'Payment Transactions';
        $this->base['host'] = request()->getHttpHost();
        $this->base['request_ip'] = $request->ip();
        // dd($this->base);
    }

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

        $check = new PCSettings();
        $check = $check->pc_receipts()
                                    ->where('col_pc_settings.pc_ip','=',$this->base['request_ip'])
                                    ->where('col_receipt.is_printed','=',0)
                                    ->get();
        $this->base['check_unprinted'] = count($check);
        if(count($check) > 0){
             Session::flash('danger', ['There are '.count($check).' UNPRINTED RECEIPT/s .']);
        }

        
        return view('collection::payment_transactions.index')->with('base', $this->base);
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
        return view('collection::payment_transactions.index')->with('base', $this->base);
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
}
