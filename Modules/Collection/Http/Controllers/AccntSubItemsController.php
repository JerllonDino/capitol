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
use Modules\Collection\Entities\SubTitleItems;

class AccntSubItemsController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'Account Subtitle - ITEMS Settings';
    }
    public function index()
    {
        return view('collection::accountsubtitleitems')->with('base', $this->base);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $res = AccountSubtitle::orderBy('name', 'asc')->get();
        $this->base['sub_header'] = 'Add';
        return view('collection::createsubtitleitems', compact('res'))->with('base', $this->base);
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

        SubTitleItems::create([
            'item_name'=>$request['name'],
            'col_acct_subtitle_id'=>$request['sub'],
            'show_in_monthly' => $request['monthly'],
        ]);
        Session::flash('info', ['Account SubTitle Item has been Created.']);
        return redirect()->route('account_subtitle_items.index');
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
    public function edit($id)
    {

        $accountsubitems = SubTitleItems::findOrFail($id);
        $sub = AccountSubtitle::findOrFail($accountsubitems->col_acct_subtitle_id);
        $accounsubttitle = AccountSubtitle::orderBy('name', 'asc')->get();
        $this->base['sub_header'] = 'Update';
        return view('collection::editsubtitleitems', compact('sub', 'accountsubitems', 'accounsubttitle'))->with('base', $this->base);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
          $subtitle = SubTitleItems::whereId($id)->first();

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'sub' => 'required',
            'monthly' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('account_subtitle_items.edit', ['id' => $id])
                ->withErrors($validator)
                ->withInput();
        }

        $subtitle->item_name = $request['name'];
        $subtitle->col_acct_subtitle_id = $request['sub'];
        $subtitle->show_in_monthly = $request['monthly'];
        $subtitle->save();
        Session::flash('info', ['Account SubTitle Item has been updated.']);
        return redirect()->route('account_subtitle_items.index');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        SubTitleItems::find($id)->delete();
        Session::flash('info', ['Account SubTitle Item has been deleted.']);
        return redirect()->route('account_subtitle_items.index');
    }
}
