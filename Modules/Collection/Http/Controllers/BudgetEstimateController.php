<?php

namespace Modules\Collection\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\BreadcrumbsController;
use App\Http\Controllers\Controller;

use Modules\Collection\Entities\BudgetEstimate;
use Modules\Collection\Entities\AccountTitle;
use Modules\Collection\Entities\AccountCategory;
use Modules\Collection\Entities\AccountSubtitle;
use Modules\Collection\Entities\SubTitleItems;


class BudgetEstimateController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'Settings';
    }

    public function index()
    {
        return view('collection::budget.index')->with('base', $this->base);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data['category'] = AccountCategory::all();
        $data['title'] = AccountTitle::orderBy('name')->where('show_in_monthly', 1)->get();
        $data['subtitle'] = AccountSubtitle::orderBy('name')->where('show_in_monthly', 1)->get();
        $data['subtitle_items'] = SubTitleItems::orderBy('item_name')->where('show_in_monthly', 1)->get();
        
        $this->base['sub_header'] = 'Add';
        return view('collection::budget.create', compact('data'))->with('base', $this->base);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'year' => 'required|numeric|unique:'.BudgetEstimate::getTableName(),
            'title_value*' => 'numeric',
            'subtitle_value*' => 'numeric',
            'subtitleitems_value*' => 'numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->route('budget_estimate.create')
                ->withErrors($validator)
                ->withInput();
        }
        
        $input = $data = [];
        if(count($request['title_value']) > 0) {
            foreach ($request['title_value'] as $i => $title_value) {
                $input['year'] = $request['year'];
                $input['value'] = $title_value;
                $input['col_acct_title_id'] = $request['title_id'][$i];
                $input['col_acct_subtitle_id'] = null;
                $input['col_acct_subtitleitems_id'] = null;
                array_push($data, $input);
            }
            BudgetEstimate::insert($data);
        }
        
        $input = $data = [];
        if(count($request['subtitle_value']) > 0) {
            foreach ($request['subtitle_value'] as $i => $subtitle_value) {
                $input['year'] = $request['year'];
                $input['value'] = $subtitle_value;
                $input['col_acct_title_id'] = null;
                $input['col_acct_subtitle_id'] = $request['subtitle_id'][$i];
                $input['col_acct_subtitleitems_id'] = null;
                array_push($data, $input);
            }
            BudgetEstimate::insert($data);
        }

        $input = $data = [];
        if(count($request['subtitleitems_value']) > 0) {
            foreach ($request['subtitleitems_value'] as $i => $subtitleitem_value) {
                $input['year'] = $request['year'];
                $input['value'] = $subtitleitem_value;
                $input['col_acct_title_id'] = null;
                $input['col_acct_subtitle_id'] = null;
                $input['col_acct_subtitleitems_id'] = $request['subtitleitems_id'][$i];
                array_push($data, $input);
            }
            BudgetEstimate::insert($data);
        }
        
        Session::flash('info', ['Budget Estimate has been added.']);
        return redirect()->route('budget_estimate.index');
    }
    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($year)
    {
        $data['category'] = AccountCategory::all();
        $data['title'] = AccountTitle::orderBy('name')->get();
        $data['subtitle'] = AccountSubtitle::orderBy('name')->get();
        $data['subtitle_items'] = SubTitleItems::orderBy('item_name')->get();
        
        $this->base['budget'] = BudgetEstimate::where('year', $year)->get();
        $this->base['year'] = $year;
        return view('collection::budget.edit', compact('data'))->with('base', $this->base);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'year' => 'required|numeric',
            'title_value*' => 'numeric',
            'subtitle_value*' => 'numeric',
            'subtitleitems_value*' => 'numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->route('budget_estimate.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }
        
        $budget = BudgetEstimate::where('year', '=', $id)->get();
        $count_updated_title = 0;
        $count_updated_items = 0;
        $count_updated_subtitle = 0;
        foreach ($budget as $b) {
            foreach ($request['title_value'] as $i => $title_value) {
                if ($b->col_acct_title_id == $request['title_id'][$i]) {
                    $b->value = $title_value;
                    $b->save();
                    $count_updated_title++;
                }
            }
            if(isset($request['subtitle_value'])){
                foreach ($request['subtitle_value'] as $i => $subtitle_value) {
                    if ($b->col_acct_subtitle_id == $request['subtitle_id'][$i]) {
                        $b->value = $subtitle_value;
                        $b->save();
                        $count_updated_subtitle++;
                    }
                }
            }

            if(isset($request['subtitleitems_value'])){
                
                foreach ($request['subtitleitems_value'] as $i => $subtitleitem_value) {

                    if ($b->col_acct_subtitleitems_id == $request['subtitleitems_id'][$i]) {
                        $b->value = $subtitleitem_value;
                        $b->save();
                        $count_updated_items++;
                    }
                }
            }   
            
        }


        $title_ids['insert'] = [];
        $title_ids['search'] = [];
        if($count_updated_title != count($request['title_id'])){
                foreach ($request['title_value'] as $i => $title_value) {
                    $budget = BudgetEstimate::where('year', '=', $id)->where('col_acct_title_id', '=', $request['title_id'][$i])->first();
                    $title_ids['search'][] = $request['title_id'][$i];
                    if(!$budget){
                        $title_ids['insert'][] = $request['title_id'][$i];
                            $bn = new BudgetEstimate;
                            $bn->year = $id;
                            $bn->col_acct_title_id = $request['title_id'][$i];
                            $bn->col_acct_subtitle_id = null;
                            $bn->col_acct_subtitleitems_id = null;
                            $bn->value = $title_value;
                            $bn->save();  
                    }
                             
                }
        }



        if($count_updated_subtitle != count($request['subtitle_id'])){

                foreach ($request['subtitle_value'] as $i => $subtitle_value) {
                    $budget = BudgetEstimate::where('year', '=', $id)->where('col_acct_subtitle_id', '=', $request['subtitle_id'][$i])->first();
                    if(!$budget){
                            $bn = new BudgetEstimate;
                            $bn->year = $id;
                            $bn->col_acct_title_id = null;
                            $bn->col_acct_subtitle_id = $request['subtitle_id'][$i];
                            $bn->col_acct_subtitleitems_id = null;
                            $bn->value = $subtitle_value;
                            $bn->save();  
                    }
                             
                }
        }

        if($count_updated_items != count($request['subtitleitems_id'])){
                foreach ($request['subtitleitems_value'] as $i => $subtitleitem_value) {
                    $budget = BudgetEstimate::where('col_acct_subtitleitems_id', '=', $request['subtitleitems_id'][$i])->first();
                    if(!$budget){
                            $bn = new BudgetEstimate;
                            $bn->value = $subtitleitem_value;
                            $bn->year = $id;
                            $bn->col_acct_title_id = null;
                            $bn->col_acct_subtitle_id = null;
                            $bn->col_acct_subtitleitems_id = $request['subtitleitems_id'][$i];
                            $bn->save();  
                    }
                }
        }

                            
        
        Session::flash('info', ['Budget updated.']);
        return redirect()->route('budget_estimate.index');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        $delete = BudgetEstimate::where('id', $id)->delete();
        Session::flash('info', ['Budget has been deleted.']);
        return redirect()->route('budget_estimate.index');
    }

    public function show($year)
    {
        $data['category'] = AccountCategory::all();
        $data['title'] = AccountTitle::orderBy('name')->get();
        $data['subtitle'] = AccountSubtitle::orderBy('name')->get();
        
        $this->base['budget'] = BudgetEstimate::where('year', $year)->get();
        $this->base['year'] = $year;
        return view('collection::budget.view', compact('data'))->with('base', $this->base);
    }
}
