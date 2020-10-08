<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbsController;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Modules\Collection\Entities\WithCert;
use Modules\Collection\Entities\OtherFeesCharges;
use Modules\Collection\Entities\Municipality;
use Modules\Collection\Entities\Barangay;
use Modules\Collection\Entities\Receipt;
use Modules\Collection\Entities\ReceiptItems;
use Modules\Collection\Entities\TransactionType;
use Modules\Collection\Entities\RcptCertificateType;
use Modules\Collection\Entities\RcptCertificate;
use Modules\Collection\Entities\ReportOfficers; //position
use Modules\Collection\Entities\ReportOfficerNew; //name of officers
use Carbon\Carbon;
class CertificateController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'Certificate';
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($id)
    {

        $this->base['rcpt_certificatetype'] = RcptCertificateType::get();
        $this->base['rcpt_officers'] = ReportOfficers::whereIn('id',[2, 5])->get();
        $this->base['receipt'] = $id;
        $this->base['cert'] = RcptCertificate::where('col_receipt_id', '=', $id)->first();
        $this->base['recipt_info'] = Receipt::find($id);
        $this->base['withcert'] =WithCert::where('trans_id', '=', $id)->first();
        $this->base['OtherFeesCharges'] = OtherFeesCharges::where('receipt_id', '=', $id)->get();
        $this->base['position'] = ReportOfficers::all();
        $this->base['officer'] = ReportOfficerNew::all();
        return view('collection::certificate.index')->with('base', $this->base);
    }


    public function clear_other_municpal_fees(Request $request){

       $other_fees =  OtherFeesCharges::where('id', '=', $request->receipt)->first();
       $other_fees->delete();
        return json_encode('other fees has been deleted');
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
    public function store(Request $request, $id)
    {
        $user = Session::get('user');
        $cert = RcptCertificate::where('col_receipt_id', '=', $id)->first();

        $filter = [
            'type' => 'required',
            'date' => 'date|required',
            'recipient' => 'required',
            // 'address' => 'required',
            'detail' => 'required',
        ];

        $validator = Validator::make($request->all(), $filter);

        if ($validator->fails()) {
            return redirect()->route('receipt.certificate.index', ['receipt' => $id])
                ->withErrors($validator);
        }
        /*if($request['signee'] == 'asstprovtreasurer1' ){
            $governor = ReportOfficers::whereId(1)->first()->value;
            $treasurer = ReportOfficers::whereId(2)->first()->value;
            $assttreasurer = ReportOfficers::whereId(10)->first()->value;
            $assttreasurer_position = ReportOfficers::whereId(11)->first()->value;
            $vice_gov = ReportOfficers::whereId(12)->first()->value;
        }elseif ($request['signee'] == 'forinabsence' ) {
            $governor = ReportOfficers::whereId(1)->first()->value;
            $treasurer = ReportOfficers::whereId(2)->first()->value;
            $assttreasurer = ReportOfficers::whereId(30)->first()->value;
            $assttreasurer_position = ReportOfficers::whereId(31)->first()->value;
            $vice_gov = ReportOfficers::whereId(12)->first()->value;
        }else{
            $governor = ReportOfficers::whereId(1)->first()->value;
            $treasurer = ReportOfficers::whereId(2)->first()->value;
            $assttreasurer = ReportOfficers::whereId(9)->first()->value;
            $assttreasurer_position = 'Assistant Provincial Treasurer';
            $vice_gov = ReportOfficers::whereId(12)->first()->value;
        }

        if($request['prepared_by'] == 'provtreasurer' ){
            $transfer_name = ReportOfficers::whereId(10)->first()->value;
            $transfer_position = ReportOfficers::whereId(11)->first()->value;
        }elseif ($request['prepared_by'] == 'asstprovtreasurer' ) {
            $transfer_name = ReportOfficers::whereId(28)->first()->value;
            $transfer_position = ReportOfficers::whereId(29)->first()->value;
        }else{
           $transfer_name = ReportOfficers::whereId(32)->first()->value;
            $transfer_position = ReportOfficers::whereId(33)->first()->value;
        }*/

        $governor = "CRESCENCIO C. PACALSO";
        $treasurer = "IMELDA I. MACANES";
        $assttreasurer = "ISABEL D. KIW-AN";
        $assttreasurer_position = "Local Revenue Collection Officer IV";
        $transfer_name = "ISABEL D. KIW-AN";
        $transfer_position = "Local Revenue Collection Officer IV";

        // $governor = ReportOfficerNew::whereId(1)->value('officer_name');
        // $treasurer = ReportOfficerNew::whereId(3)->value('officer_name');
        // $assttreasurer = ReportOfficerNew::whereId(8)->value('officer_name');
        // $assttreasurer_position = ReportOfficerNew::whereId(9)->value('position');
        // $transfer_name = ReportOfficerNew::whereId(8)->value('officer_name');
        // $transfer_position = ReportOfficerNew::whereId(9)->value('position');
        


        $provincial_note = $provincial_clearance_number = $provincial_type = $provincial_gov = $provincial_bidding = null;
        $transfer_notary_public = $transfer_ptr_number = $transfer_doc_number = $transfer_page_number = $transfer_book_number = $transfer_series = $transfer_prepare_name = $transfer_prepare_position = null;
        $sand_requestor = $sand_requestor_addr = $sand_requestor_sex = $sand_type = $sand_sandgravelprocessed = $sand_abc = $sand_sandgravel = $sand_boulders = null;
        if ($request['type'] == 1) {
            $provincial_note = $request['provincial_note'];
            $provincial_clearance_number = $request['provincial_clearance_number'];
            $provincial_type = $request['provincial_type'];
            $provincial_gov = ($request['provincial_gov'] == 0) ? $vice_gov : null;
            $provincial_bidding = $request['provincial_bidding'];
        } elseif ($request['type'] == 2) {
            $transfer_notary_public = $request['transfer_notary_public'];
            $transfer_ptr_number = $request['transfer_ptr_number'];
            $transfer_doc_number = $request['transfer_doc_number'];
            $transfer_page_number = $request['transfer_page_number'];
            $transfer_book_number = $request['transfer_book_number'];
            $transfer_series = $request['transfer_series'];
        } elseif ($request['type'] == 3) {
            $sand_requestor = $request['sand_requestor'];
            $sand_requestor_addr = $request['sand_requestor_addr'];
            $sand_requestor_sex = $request['sand_requestor_sex'];
            $sand_type = $request['sand_type'];
            $sand_sandgravelprocessed = $request['sand_sandgravelprocessed'];
            $sand_abc = $request['sand_abc'];
            $sand_sandgravel = $request['sand_sandgravel'];
            $sand_boulders = $request['sand_boulders'];
        }

        # Insert
        if (count($cert) == 0) {
            $cert = RcptCertificate::create([
                'col_receipt_id' => $id,
                'col_rcpt_certificate_type_id' => $request['type'],
                'recipient' => $request['recipient'],
                'address' => $request['address'],
                'detail' => $request['detail'],
                'date_of_entry' => date('Y-m-d'),
                'provincial_governor' => $governor,
                'actingprovincial_governor' => $provincial_gov,
                'provincial_treasurer' => $treasurer,
                'asstprovincial_treasurer' => /*($request['signee'] == 'provtreasurer') ? null :*/ $assttreasurer,
                'asstprovincial_treasurer_position' => $assttreasurer_position,
                'user' => $user->realname,
                'provincial_note' => $provincial_note,
                'provincial_clearance_number' => $provincial_clearance_number,
                'provincial_type' => $provincial_type,
                'provincial_bidding' => $provincial_bidding,
                'transfer_notary_public' => $transfer_notary_public,
                'transfer_ptr_number' => $transfer_ptr_number,
                'transfer_doc_number' => $transfer_doc_number,
                'transfer_page_number' => $transfer_page_number,
                'transfer_book_number' => $transfer_book_number,
                'transfer_series' => $transfer_series,
                'transfer_prepare_name' => $transfer_name,
                'transfer_prepare_position' => $transfer_position,
                'signee' => $request['signee'],
                'prepared_by' => $request['prepared_by'],
                'sand_requestor' => $sand_requestor,
                'sand_requestor_addr' => $sand_requestor_addr,
                'sand_requestor_sex' => $sand_requestor_sex,
                'sand_type' => $sand_type,
                'sand_sandgravelprocessed' => $sand_sandgravelprocessed,
                'sand_abc' => $sand_abc,
                'sand_sandgravel' => $sand_sandgravel,
                'sand_boulders' => $sand_boulders,
            ]);

            Session::flash('info', ['Successfully created certificate']);
        } else {
            $cert->col_rcpt_certificate_type_id = $request['type'];
            $cert->recipient = $request['recipient'];
            $cert->address = $request['address'];
            $cert->detail = $request['detail'];
            $cert->date_of_entry = date('Y-m-d');
            $cert->provincial_governor = $governor;
            $cert->actingprovincial_governor = $provincial_gov;
            $cert->provincial_treasurer = $treasurer;
            $cert->asstprovincial_treasurer = /*($request['signee'] == 'provtreasurer') ? null :*/ $assttreasurer;
            $cert->asstprovincial_treasurer_position = /*($request['signee'] == 'provtreasurer') ? null :*/ $assttreasurer_position;
            $cert->user = $user->realname;
            $cert->provincial_note = $provincial_note;
            $cert->provincial_clearance_number = $provincial_clearance_number;
            $cert->provincial_type = $provincial_type;
            $cert->provincial_bidding = $provincial_bidding;
            $cert->transfer_notary_public = $transfer_notary_public;
            $cert->transfer_ptr_number = $transfer_ptr_number;
            $cert->transfer_doc_number = $transfer_doc_number;
            $cert->transfer_page_number = $transfer_page_number;
            $cert->transfer_book_number = $transfer_book_number;
            $cert->transfer_series = $transfer_series;
            $cert->transfer_prepare_name = $transfer_name;
            $cert->transfer_prepare_position = $transfer_position;
            $cert->signee = $request['signee'];
            $cert->prepared_by = $request['prepared_by'];
            $cert->sand_requestor = $sand_requestor;
            $cert->sand_requestor_addr = $sand_requestor_addr;
            $cert->sand_requestor_sex = $sand_requestor_sex;
            $cert->sand_type = $sand_type;
            $cert->sand_sandgravelprocessed = $sand_sandgravelprocessed;
            $cert->sand_abc = $sand_abc;
            $cert->sand_sandgravel = $sand_sandgravel;
            $cert->sand_boulders = $sand_boulders;
            $cert->save();

            Session::flash('info', ['Successfully updated certificate']);
        }

        if(count($request['fees_charges']) > 0 && $request['fees_charges'][0] != '' ){

            for($x = 0 ; $x<count($request['fees_charges']); $x++){
                    if( $request['fees_charges'][$x] != ''  && $request['fees_ammount'][$x] != '' && $request['fees_or_number'][$x] != '' && $request['other_date'][$x] != '' ){


                        $other_date[$x] = new Carbon($request['other_date'][$x]);
                        if( !isset($request['other_fees_id'][$x]) ){
                             $OtherFeesCharges  = new  OtherFeesCharges ;
                        }else{
                            $OtherFeesCharges = OtherFeesCharges::where('id','=',$request['other_fees_id'][$x])->first();
                        }

                            $OtherFeesCharges->receipt_id = $id;
                            $OtherFeesCharges->fees_charges = $request['fees_charges'][$x];
                            $OtherFeesCharges->ammount = $request['fees_ammount'][$x];
                            $OtherFeesCharges->or_number = $request['fees_or_number'][$x];
                            $OtherFeesCharges->fees_date = $other_date[$x]->format('Y-m-d');
                            $OtherFeesCharges->initials = $request['fees_initials'][$x];
                            $OtherFeesCharges->save();
                    }
            }
        }

                $WithCert =   WithCert::where('trans_id', '=', $id)->first();
                if($WithCert){
                       $WithCert->process_status = 1;
                        $WithCert->save();
                }

        return redirect()->route('receipt.certificate.index', ['receipt' => $id]);
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
