<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbsController;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Modules\Collection\Entities\AccountTitle;
use Modules\Collection\Entities\AccountSubtitle;
use Modules\Collection\Entities\BudgetEstimate;

class AccntSubController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'Account Subtitle Settings';
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('collection::accountsubtitle')->with('base', $this->base);
    }

    public function show($id)
    {
        $accountsub = AccountSubtitle::find($id);
        $accountttl = AccountSubtitle::find($id)->title;
        $this->base['sub_header'] = 'View';
        return view('collection::showsubtitle', compact('accountsub', 'accountttl'))->with('base', $this->base);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $res = AccountTitle::orderBy('name', 'asc')->get();
        $this->base['sub_header'] = 'Add';
        return view('collection::createsubtitle', compact('res'))->with('base', $this->base);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'sub' => 'required',
            'monthly' => 'required',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('account_subtitle.create')
                ->withErrors($validator)
                ->withInput();
        }
        
        $subtitle_id = AccountSubtitle::create([
            'name'=>$request['name'],
            'col_acct_title_id'=>$request['sub'],
            'show_in_monthly' => $request['monthly'],
        ]);

        if($request['monthly'] === '1'){
           BudgetEstimate::create([
                'year' => $request['subtitle_budget_estimate_year'],
                'value' => $request['subtitle_budget_estimate_value'],
                'col_acct_title_id' => null,
                'col_acct_subtitle_id' => $subtitle_id->id,
                'col_acct_subtitleitems_id' => null,
            ]);
        }
        Session::flash('info', ['Account SubTitle has been Created.']);
        return redirect()->route('account_subtitle.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $sub = AccountSubtitle::findOrFail($id)->title;
        $accountsub = AccountSubtitle::findOrFail($id);
        $accounttitle = AccountTitle::orderBy('name', 'asc')->get();
        $this->base['sub_header'] = 'Update';
        return view('collection::editsubtitle', compact('sub', 'accountsub', 'accounttitle'))->with('base', $this->base);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $subtitle = AccountSubtitle::whereId($id)->first();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'sub' => 'required',
            'monthly' => 'required',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('account_subtitle.edit', ['id' => $id])
                ->withErrors($validator)
                ->withInput();
        }
        
        $subtitle->name = $request['name'];
        $subtitle->col_acct_title_id = $request['sub'];
        $subtitle->show_in_monthly = $request['monthly'];
        $subtitle->save();
        if($request['monthly'] === '1'){
            $subtitle_budget = BudgetEstimate::where('year','=',$request['subtitle_budget_estimate_year'])->where('col_acct_subtitle_id','=',$subtitle->id)->first();

            if($subtitle_budget){
                $subtitle_budget->value = $request['subtitle_budget_estimate_value'];
                $subtitle_budget->save();
            }else{
                 BudgetEstimate::create([
                'year' => $request['subtitle_budget_estimate_year'],
                'value' => $request['subtitle_budget_estimate_value'],
                'col_acct_title_id' => null,
                'col_acct_subtitle_id' => $subtitle->id,
                'col_acct_subtitleitems_id' => null,
            ]);
            }
            
        }

        Session::flash('info', ['Account SubTitle has been updated.']);
        return redirect()->route('account_subtitle.index');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        $delete = AccountSubtitle::find($id);
        $check = $delete->receipt_items()->get();
        if($check->count()==0){
            $subtitleitems =  $delete->subtitleitems()->get();
            foreach ($subtitleitems as $subtitleitem) {
                    $subtitleitem->delete();
            }
            $budgets = $delete->budget()->get();
            foreach ($budgets as $budget) {
                $budget->delete();
            }
            $rates = $delete->rate()->get();
            foreach ($rates as $rate) {
                $rate->delete();
            }
            $delete->delete();
            Session::flash('info', ['Account SubTitle has been deleted.']);
            return redirect()->route('account_subtitle.index');
        }
        Session::flash('danger', [" Account SubTitle has records and will not be deleted. "]);
            return redirect()->route('account_subtitle.index');

        
    }
}
