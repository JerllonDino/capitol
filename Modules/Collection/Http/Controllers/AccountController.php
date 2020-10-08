<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Http\Controllers\BreadcrumbsController;

use Illuminate\Http\Request;

use Illuminate\Http\Response;

use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Validator;

use Modules\Collection\Entities\AccountCategory;

use Modules\Collection\Entities\Receipt;

use Modules\Collection\Entities\IsManySerials;

class AccountController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'Account Category Settings';
    }
    
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('collection::category')->with('base', $this->base);
    }

    public function show($id)
    {
        $this->base['sub_header'] = 'View';
        $account = AccountCategory::findOrFail($id);
        return view('collection::showcategory', compact('account'))->with('base', $this->base);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $this->base['sub_header'] = 'Add';
        return view('collection::createcategory')->with('base', $this->base);
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
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('account_category.create')
                ->withErrors($validator)
                ->withInput();
        }
        
        AccountCategory::create(['name' => $request['name']]);
        Session::flash('info', ['Account Category has been created.']);
        return redirect()->route('account_category.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $this->base['sub_header'] = 'Update';
        $res = AccountCategory::whereId($id)->first();
        return view('collection::editcategory', compact('res'))->with('base', $this->base);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $category = AccountCategory::whereId($id)->first();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('account_category.edit', ['id' => $id])
                ->withErrors($validator)
                ->withInput();
        }
        
        $category->name = $request['name'];
        $category->save();
        Session::flash('info', ['Account Category has been updated.']);
        return redirect()->route('account_category.index');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        $accountcat = AccountCategory::findOrFail($id);
        $accountcat->deleteChild();
        Session::flash('info', ['Account Category has been deleted.']);
        return redirect()->route('account_category.index');
    }


    public function check_serial_combine(Request $request){
            // $receipt[0] = Receipt::where('serial_no','=',$request->input('receipt'))->where('transaction_source','=',$request->input('collection_type'))->first();
            $receipt[0] = Receipt::where('serial_no','=',$request->input('receipt'))->first();
            $receipt[1] = Receipt::find($request->input('receipt_id'));

            $data = [];
            if($receipt[0]){
                    $data['status'] = 1;
                    $data['reciept'] = $receipt[0];
                    if($receipt[0]->is_many &&  $receipt[1]->is_many){
                         $data['status'] = 0;
                         $data['message'] = 'can\'t combine a serial with existing parent!!!.';
                        
                    }
                    elseif($receipt[0]->is_many || $receipt[1]->is_many){

                        $IsManySerials = IsManySerials::find($receipt[0]->is_many);
                        if(!$IsManySerials){
                            $IsManySerials = IsManySerials::find($receipt[1]->is_many);
                            $add_serial = json_decode( $IsManySerials->col_serials );
                            $add_serial[count($add_serial)] = $receipt[1]->serial_no ;
                            $IsManySerials->col_serials = json_encode($add_serial);
                            $IsManySerials->save();
                             $data['check'] = 1;
                        }else{
                            $add_serial = json_decode( $IsManySerials->col_serials );
                            $add_serial[count($add_serial)] = $receipt[0]->serial_no ;
                            $IsManySerials->col_serials = json_encode($add_serial);
                            $IsManySerials->save();
                             $data['check'] = 1;
                        }
                        $receipt[0]->is_many = $IsManySerials->id;
                        $receipt[0]->save();
                    
                        $receipt[1]->is_many = $IsManySerials->id;
                        $receipt[1]->save();
                        
                    }else{
                        $IsManySerials = new IsManySerials;
                        $IsManySerials->col_receipt_id = $receipt[0]->id;
                        $IsManySerials->col_receipt_serial_parent =$receipt[0]->serial_no;
                        $IsManySerials->col_serials = json_encode([$receipt[1]->serial_no]);
                        $IsManySerials->save();

                        $receipt[0]->is_many = $IsManySerials->id;
                        $receipt[0]->save();
                    
                        $receipt[1]->is_many = $IsManySerials->id;
                        $receipt[1]->save();
                        $data['check'] = 2;

                    }
                    
            }else{
                 $data['status'] = 0;
                 $data['message'] = 'No serial Found!!!.';
            }
        return $data;
    }

    public function field_land_tax_combine(Request $request){
            // $receipt[0] = Receipt::where('serial_no','=',$request->input('receipt'))->where('transaction_source','=',$request->input('collection_type'))->first();
            $receipt[0] = Receipt::where('serial_no','=',$request->input('receipt'))->first();
            $receipt[1] = Receipt::find($request->input('receipt_id'));

            $data = [];
            if($receipt[0]){
                    $data['status'] = 1;
                    $data['reciept'] = $receipt[0];
                    if($receipt[0]->is_many &&  $receipt[1]->is_many){
                         $data['status'] = 0;
                         $data['message'] = 'can\'t combine a serial with existing parent!!!.';
                        
                    }
                    elseif($receipt[0]->is_many || $receipt[1]->is_many){

                        $IsManySerials = IsManySerials::find($receipt[0]->is_many);
                        if(!$IsManySerials){
                            $IsManySerials = IsManySerials::find($receipt[1]->is_many);
                            $add_serial = json_decode( $IsManySerials->col_serials );
                            $add_serial[count($add_serial)] = $receipt[1]->serial_no ;
                            $IsManySerials->col_serials = json_encode($add_serial);
                            $IsManySerials->save();
                             $data['check'] = 1;
                        }else{
                            $add_serial = json_decode( $IsManySerials->col_serials );
                            $add_serial[count($add_serial)] = $receipt[0]->serial_no ;
                            $IsManySerials->col_serials = json_encode($add_serial);
                            $IsManySerials->save();
                             $data['check'] = 1;
                        }
                        $receipt[0]->is_many = $IsManySerials->id;
                        $receipt[0]->save();
                    
                        $receipt[1]->is_many = $IsManySerials->id;
                        $receipt[1]->save();
                        
                    }else{
                        $IsManySerials = new IsManySerials;
                        $IsManySerials->col_receipt_id = $receipt[0]->id;
                        $IsManySerials->col_receipt_serial_parent =$receipt[0]->serial_no;
                        $IsManySerials->col_serials = json_encode([$receipt[1]->serial_no]);
                        $IsManySerials->save();

                        $receipt[0]->is_many = $IsManySerials->id;
                        $receipt[0]->save();
                    
                        $receipt[1]->is_many = $IsManySerials->id;
                        $receipt[1]->save();
                        $data['check'] = 2;

                    }
                    
            }else{
                 $data['status'] = 0;
                 $data['message'] = 'No serial Found!!!.';
            }
        return $data;
    }

    public function field_land_tax_uncombine(Request $req) {
        $search = IsManySerials::where('col_receipt_serial_parent', '=', $req->parent)->first();
        $success = 'false';
        if(count($search) > 0) {
            $clean = trim($search->col_serials, "[");
            $clean2 = trim($clean, "]");
            $children = explode(",", $clean2);
            if(in_array($req->child, $children)) {
                if(count($children) > 1) {
                    // remove particular child OR
                    $new_children = '';
                    for ($i = 0; $i < count($children); $i++) {
                        if($req->child != $children[$i]) {
                            if(count($children) == 2) {
                                $new_children = '['.$children[$i].']';
                            } else {
                                $new_children .= $i == 0 ? ('['.$children[$i].',') : ($i == count($children)-1 ? $children[$i].']' : $children[$i].",");
                            }
                        }
                    }
                    IsManySerials::where('col_receipt_serial_parent', '=', $req->parent)->update(['col_serials' => $new_children]);
                    $success = 'true';
                } else {
                    // delete instead, remove col_many_receipt id from col_receipt
                    Receipt::where('is_many', '=', $search->id)->update(['is_many' => null]);
                    IsManySerials::where('col_receipt_serial_parent', '=', $req->parent)->delete();
                    $success = 'true';
                }
            }
        }
        return $success;
    }
}
