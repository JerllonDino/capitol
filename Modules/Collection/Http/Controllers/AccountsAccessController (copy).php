<?php

namespace Modules\Collection\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Modules\Collection\Entities\AccountCategory;
use Modules\Collection\Entities\AccessAccounts;

class AccountsAccessController extends Controller
{
     public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'ACCOUNTS';
        $this->base['request_ip'] = $request->ip();
    }

    public function index()
    {
        $this->base['category'] = AccountCategory::all();

        return view('collection::accounts.index')->with('base', $this->base);
    }

    public function set_account(Request $request){
        if($request->input('type')=== 'title'){
            $AccessAccounts = AccessAccounts::where('col_acct_title_id','=', $request->input('title_id'))->first();
        }else{
            $AccessAccounts = AccessAccounts::where('col_acct_subtitle_id','=',$request->input('subtitle_id'))->first();
        }
             if(!$AccessAccounts){
                $AccessAccounts =new AccessAccounts;
             }
             $AccessAccounts->col_acct_title_id = $request->input('title_id');
             $AccessAccounts->col_acct_subtitle_id = $request->input('subtitle_id');
             $AccessAccounts->show_in_landtax = $request->input('landtax');
             $AccessAccounts->show_in_fieldlandtax = $request->input('fieldlandtax');
             $AccessAccounts->show_in_cashdivision = $request->input('cashdivision');
             $AccessAccounts->show_in_form51 = $request->input('form51');
             $AccessAccounts->show_in_form56 = $request->input('form56');
             $AccessAccounts->save();
             return json_encode('success');
    }


}
