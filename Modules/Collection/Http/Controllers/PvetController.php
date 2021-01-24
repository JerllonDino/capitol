<?php

namespace Modules\Collection\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session,Validator,DB,PDF,Excel,Carbon\Carbon;
use App\Http\Controllers\{Controller};
use Modules\Collection\Entities\{CollectionRate,Municipality,Customer,SandGravelTypes as sg_types, PvetCollection, PvetItems, Barangay};

class PvetController extends Controller
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
        $this->base['page_title'] = 'PVET Collections';
        $this->base['sandgravel_types'] = sg_types::all();
        $this->base['sub_header'] = 'New';
        $this->base['municipalities'] = Municipality::all()->toarray();
        $this->base['user'] = Session::get('user');
        return view('collection::pvet.index')->with('base', $this->base);
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
        $filter = [
            'user_id' => 'required|numeric',
            'date' => 'required|date',
            'refno' => 'required|max:300',
        ];

        if (in_array(1, $request['account_is_shared'])) {
            $filter['municipality'] = 'required';
            $filter['brgy'] = 'required';
        }

        $validator = Validator::make($request->all(), $filter);
        if ($validator->fails()) {
            return redirect()->route('pvet.index')
                ->withErrors($validator);
        } elseif (in_array('', $request['account_id'])) {
            $validator->getMessageBag()
                ->add('account', 'An account field is empty or not identified');
            return redirect()->route('pvet.index')
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
        $addtl = PvetCollection::create([
            'col_customer_id' => $payor_id,
            'sex' => (!empty($request['Sex'])) ? $request['Sex'] : '',
            'col_municipality_id' => (!empty($request['municipality'])) ? $request['municipality'] : '',
            'col_barangay_id' => (!empty($request['brgy'])) ? $request['brgy'] : '',
            'dnlx_user_id' => $request['user_id'],
            'date_of_entry' => date('Y-m-d', strtotime($request['date'])),
            'refno' => $request['refno'],
            'client_type' => $request['customer_type'],
        ]);

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
            $row['col_pvet_id'] = $addtl->id;
            $row['col_acct_title_id'] = ($request['account_type'][$i] == 'title') ? $request['account_id'][$i] : 0;
            $row['col_acct_subtitle_id'] = ($request['account_type'][$i] == 'subtitle') ? $request['account_id'][$i] : 0;
            $row['value'] = $request['amount'][$i];
            $row['share_provincial'] = $share_provincial;
            $row['share_municipal'] = $share_municipal;
            $row['share_barangay'] = $share_barangay;
            $row['nature'] = $request['nature'][$i];

            array_push($data, $row);
        }
        PvetItems::insert($data);

        Session::flash('info', ['Successfully added!']);
        return redirect()->route('pvet.index');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $this->base['sub_header'] = 'View';
        $this->base['addtl'] = PvetCollection::whereId($id)->first();

        return view('collection::pvet.view')->with('base', $this->base);
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
        $this->base['addtl'] = PvetCollection::whereId($id)->first();

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
        $addtl = PvetCollection::whereId($id)->first();

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
            return redirect()->route('pvet.edit', ['id' => $id])
                ->withErrors($validator);
        } elseif (in_array('', $request['account_id'])) {
            $validator->getMessageBag()
                ->add('account', 'An account field is empty or not identified');
            return redirect()->route('pvet.edit', ['id' => $id])
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
            $row['col_pvet_id'] = $addtl->id;
            $row['col_acct_title_id'] = ($request['account_type'][$i] == 'title') ? $request['account_id'][$i] : 0;
            $row['col_acct_subtitle_id'] = ($request['account_type'][$i] == 'subtitle') ? $request['account_id'][$i] : 0;
            $row['value'] = $request['amount'][$i];
            $row['share_provincial'] = $share_provincial;
            $row['share_municipal'] = $share_municipal;
            $row['share_barangay'] = $share_barangay;
            $row['nature'] = $request['nature'][$i];

            array_push($data, $row);
        }
        PvetItems::insert($data);

        Session::flash('info', ['Successfully updated record']);
        return redirect()->route('pvet.index');
    }

    public function pvet_delete(Request $request){
        $pvet = PvetCollection::find($request->input('pvet'));
        $pvet->delete();
        return response()->json('test');
    }

    public function pvet_restore(Request $request){
        $pvet = PvetCollection::withTrashed()->find($request->input('pvet'));
        $pvet->restore();
        return response()->json('test');
    }
    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
