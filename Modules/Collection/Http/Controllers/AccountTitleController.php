<?php

namespace Modules\Collection\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbsController;

use Modules\Collection\Entities\AccountGroup;
use Modules\Collection\Entities\AccountTitle;
use Modules\Collection\Entities\BudgetEstimate;


class AccountTitleController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'Account Title Settings';
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('collection::accounttitle')->with('base', $this->base);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data = AccountGroup::orderBy('name')->get();
        $this->base['sub_header'] = 'Add';
        $this->base['titlegroup'] = AccountGroup::orderBy('name')->get();
        return view('collection::createtitle', compact('data'))->with('base', $this->base);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        if($request['monthly'] === '1'){
            $validator = Validator::make($request->all(), [
                            'code' => 'required|max:255',
                            'name' => 'required|max:255',
                            'group' => 'required',
                            'monthly' => 'required',
                            'title_budget_estimate_value' => 'required',
                            'title_budget_estimate_year' => 'required'
                        ]);
        }else{
            $validator = Validator::make($request->all(), [
                            'code' => 'required|max:255',
                            'name' => 'required|max:255',
                            'group' => 'required',
                            'monthly' => 'required',
                        ]);
        }
       
        
        if ($validator->fails()) {
            return redirect()->route('account_title.create')
                ->withErrors($validator)
                ->withInput();
        }
        
        $title_id = AccountTitle::create([
            'code'=>$request['code'], 
            'name'=>$request['name'], 
            'acct_group_id'=>$request['group'],
            'show_in_monthly'=>$request['monthly'],
        ]);

        if($request['monthly'] === '1'){
           BudgetEstimate::create([
                'year' => $request['title_budget_estimate_year'],
                'value' => $request['title_budget_estimate_value'],
                'col_acct_title_id' => $title_id->id,
                'col_acct_subtitle_id' => null,
                'col_acct_subtitleitems_id' => null,
            ]);
        }
        Session::flash('info', ['Account Title has been created with Budget estimate ('. $request['title_budget_estimate_year'] .' - '.$request['title_budget_estimate'] .').']);
        return redirect()->route('account_title.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $this->base['sub_header'] = 'View';
        $this->base['accounttitle'] = AccountTitle::where('id', $id)->first();
        $this->base['titlegroup'] = AccountGroup::orderBy('name')->get();
        return view('collection::titleedit')->with('base', $this->base);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $title = AccountTitle::whereId($id)->first();
        
        $validator = Validator::make($request->all(), [
            'code' => 'required|max:255',
            'name' => 'required|max:255',
            'group' => 'required',
            'monthly' => 'required',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('account_title.edit', ['id' => $id])
                ->withErrors($validator)
                ->withInput();
        }
        
        $title->code = $request['code'];
        $title->name = $request['name'];
        $title->acct_group_id = $request['group'];
        $title->show_in_monthly = $request['monthly'];
        $title->save();

        if($request['monthly'] === '1'){
            $title_budget = BudgetEstimate::where('year','=',$request['title_budget_estimate_year'])->where('col_acct_title_id','=',$title->id)->first();

            if($title_budget){
                $title_budget->value = $request['title_budget_estimate_value'];
                $title_budget->save();
            }else{
                 BudgetEstimate::create([
                'year' => $request['title_budget_estimate_year'],
                'value' => $request['title_budget_estimate_value'],
                'col_acct_title_id' => $title->id,
                'col_acct_subtitle_id' => null,
                'col_acct_subtitleitems_id' => null,
            ]);
            }
            
        }

        Session::flash('info', ['Account Title updated.']);
        return redirect()->route('account_title.index');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        $delete = AccountTitle::where('id', $id)->first();
        
        $check = $delete->receipt_items()->get();
        if($check->count()==0){
            $subs = $delete->subs()->get();
            foreach ($subs as $sub) {
                foreach ($sub->subtitleitems as $subtitleitem) {
                    $subtitleitem->delete();
                }
                $sub->delete();
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
            Session::flash('danger', ['Account Title has been deleted.']);
            return redirect()->route('account_title.index');
        }
        Session::flash('danger', [" Account Title has records and will not be deleted. "]);
            return redirect()->route('account_title.index');
    }

    public function show($id)
    {
        $this->base['sub_header'] = 'View';
        $this->base['accounttitle'] = AccountTitle::where('id', $id)->first();
        return view('collection::titleview')->with('base', $this->base);
    }
}
