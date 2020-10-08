<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbsController;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use Modules\Collection\Entities\AccountCategory;
use Modules\Collection\Entities\AccountGroup;

class AccountGroupController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'Account Group Settings';
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('collection::collectiongrp')->with('base', $this->base);
    }

    public function show($id)
    {
        $this->base['sub_header'] = 'View';
        $accountgrp = AccountGroup::findOrFail($id);
        $accountcat = AccountGroup::findOrFail($id)->category;
        return view('collection::showgroup', compact('accountgrp', 'accountcat'))->with('base', $this->base);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $res = AccountCategory::orderBy('name', 'asc')->get();
        $this->base['sub_header'] = 'Add';
        return view('collection::createcollectiongrp', compact('res'))->with('base', $this->base);
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
            'category' => 'required',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('account_group.create')
                ->withErrors($validator)
                ->withInput();
        }
        
        AccountGroup::create([
            'name' => $request['name'],
            'acct_category_id' => $request['category']
        ]);
        Session::flash('info', ['Account Group has been created.']);
        return redirect()->route('account_group.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $this->base['sub_header'] = 'Update';
        $cat = AccountGroup::findOrFail($id)->category;
        $acctgrp = AccountGroup::findOrFail($id);
        $acctcateg = AccountCategory::orderBy('name', 'asc')->get();
        return view('collection::editgroup', compact('cat', 'acctgrp', 'acctcateg'))->with('base', $this->base);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $group = AccountGroup::whereId($id)->first();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'category' => 'required',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('account_group.edit', ['id' => $id])
                ->withErrors($validator)
                ->withInput();
        }
        
        $group->name = $request['name'];
        $group->acct_category_id = $request['categ'];
        $group->save();
        Session::flash('info', ['Account Group has been updated.']);
        return redirect()->route('account_group.index');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        $accountgrp = AccountGroup::findOrFail($id);
        $accountgrp->delete();
        $accountgrp->title()->delete();
        Session::flash('info', ['Account Group has been deleted.']);
        return redirect()->route('account_group.index');
    }
}
