<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\{Controller,BreadcrumbsController};

use Illuminate\Http\{Request,Response};
use Illuminate\Support\Facades\{Session,Validator,URL,Crypt};

use Modules\Collection\Entities\{Customer,Receipt,ReceiptItems,CashDivision,CashDivisionItems,Municipality,Barangay,PreviousTaxType,MunicipalReceipt,F56TDARP, RptSefAdjustments};
use Modules\Collection\Entities\SandGravelTypes as sg_types;
use Carbon\Carbon,PDF,DB,Datatables;
use Smalot\PdfParser\Parser;
use mikehaertl\pdftk\Pdf as Pdftk;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class CustomerController extends Controller
{
    protected $customer;

    public function __construct(Request $request, Customer $customer)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'Customer/Payor';
        $this->customer = $customer;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('collection::customer.index')->with('base', $this->base);
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:' . Customer::getTableName(),
            'address' => 'max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->route('customer.index')
                ->withErrors($validator);
        }

        # Success
        Customer::create([
            'name' => $request['name'],
            'address' => $request['address'],
        ]);
        return redirect()->route('customer.index');
    }

    public function importIndex()
    {
        $this->base['municipality'] = Municipality::all();
        $this->base['page_title'] = 'Import Monthly Municipal RPT Report';
        return view('collection::customer.rpt_import_excel')->with('base', $this->base);
    }

    public function viewExcel(Request $request)
    {
        
        $tableHeaders = '<thead>
        <tr>
            <th rowspan="4">Date</th>
            <th rowspan="4">Name of Tax Payor</th>
            <th rowspan="4">Period Covered</th>
            <th rowspan="4">Official Receipt Number</th>
            <th rowspan="4">TD/ARP No.</th>
            <th rowspan="4">Name of Brgy.</th>
            <th rowspan="4">Classifi <br> cation</th>
            <th colspan="11">BASIC TAX</th>
            <th rowspan="4">Sub-total Gross Collection</th>
            <th rowspan="4">Sub-total Net Collection</th>
            <th colspan="11">SPECIAL EDUCATION FUND</th>
            <th rowspan="4">Sub-total Gross Collection</th>
            <th rowspan="4">Sub-total Net Collection</th>
            <th rowspan="4">Grand Total Gross Collection</th>
            <th rowspan="4">Grand Total Net Collection</th>
        </tr>
        <tr>
            <!-- basic --> 
            <th style="top: 70px" colspan="2" rowspan="2">Advance</th>
            <th style="top: 70px" colspan="2" rowspan="2">Current Year</th>
            <th style="top: 70px" rowspan="3">'.(date("Y")-1).'</th>
            <th style="top: 70px" colspan="2" rowspan="2">PRIOR YEARS</th>
            <th style="top: 70px" colspan="4">PENALTIES</th>
            <!-- sef --> 
            <th style="top: 70px" colspan="2" rowspan="2">Advance</th>
            <th style="top: 70px" colspan="2" rowspan="2">Current Year</th>
            <th style="top: 70px" rowspan="3">'.(date("Y")-1).'</th>
            <th style="top: 70px" colspan="2" rowspan="2">PRIOR YEARS</th>
            <th style="top: 70px" colspan="4">PENALTIES</th>
        </tr> 
        <tr>
            <!-- basic -->
            <th style="top: 90px" rowspan="2">'.date("Y").'</th>
            <th style="top: 90px" rowspan="2">'.(date("Y")-1).'</th>
            <th style="top: 90px" colspan="2">PRIOR YEARS</th>
            <!-- sef -->
            <th style="top: 90px" rowspan="2">'.date("Y").'</th>
            <th style="top: 90px" rowspan="2">'.(date("Y")-1).'</th>
            <th style="top: 90px" colspan="2">PRIOR YEARS</th>
        </tr>
        <tr>
            <!-- basic -->
            <th style="top: 110px">Gross Amount</th>
            <th style="top: 110px">
               Disc
            </th>
            <th style="top: 110px">Gross Amount</th>
            <th style="top: 110px">
               Disc
            </th>
            <th style="top: 110px">'.(date("Y")-2).'-1992</th>
            <th style="top: 110px">1991 & Below</th>
            <th style="top: 110px">'.(date("Y")-2).'-1992</th>
            <th style="top: 110px">1991 & Below</th>

            <!-- sef -->
            <th style="top: 110px">Gross Amount</th>
            <th style="top: 110px">
                Disc
            </th>
            <th style="top: 110px">Gross Amount</th>
            <th style="top: 110px">
               Disc
            </th>
            <th style="top: 110px">'.(date("Y")-2).'-1992</th>
            <th style="top: 110px">1991 & Below</th>
            <th style="top: 110px">'.(date("Y")-2).'-1992</th>
            <th style="top: 110px">1991 & Below</th>
        </tr>
    </thead>';
        $file = $request->file('imports');
        $non_numeric_cells = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
        if ($file->extension() == 'xlsx' || $file->extension() == 'xls' || $file->extension() == 'csv' and $file->isValid()) {
            
            $path = $file->getRealPath();
            $reader = IOFactory::createReader('Xls');
            $reader->setReadDataOnly(TRUE);
            $excel = $reader->load($path);
            $sheets = $excel->getSheetNames();
            $worksheet = $excel->getActiveSheet();
            $html = '<table class="table table-bordered table-hover">' . PHP_EOL;
            $html = $html . $tableHeaders;
            $arrayData = [];
            foreach ($worksheet->getRowIterator() as $i => $row) {
                $html = $html . '<tr>' . PHP_EOL;
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE);
                $columns = '';
                $emptyCounter = 0;
                $subArrayData = [];
                    foreach ($cellIterator as $it => $cell) {
                        if($it == 'AJ'){
                            break;
                        }
                        if($it == 'A'){
                            if(!is_int($cell->getValue())){
                                $columns = "";
                                break;
                            }
                        }
                        if(empty($cell->getValue())){
                            $emptyCounter = $emptyCounter + 1;
                        }
                        if($emptyCounter > 27){
                            $columns = "";
                            continue;
                        }
                        
                        $tdData = $it == 'A' ? date('Y-m-d', Date::excelToTimestamp($cell->getValue())) : ($cell->getOldCalculatedValue() ? $cell->getOldCalculatedValue() : $cell->getFormattedValue());
                        $tdValues = (array_search($it, $non_numeric_cells) === false ? number_format( floatval($tdData), 2) : $tdData);
                        
                        array_push($subArrayData, (array_search($it, $non_numeric_cells) === false ? floatval($tdData) : $tdData) );
                        $columns = $columns . '<td>' . ( $tdValues === "0.00" ? '' : $tdValues )  . '</td>' . PHP_EOL;
                        // dd($tdValues);
                    }
                     
                array_push($arrayData, $subArrayData);
                $html = $html . $columns;
                $html = $html . '</tr>' . PHP_EOL;
            }

            if(count(array_filter($arrayData)) > 0){
                // $this->saveImportedExcel(array_filter($arrayData));
                $html = $html . $this->excelSummary(array_filter($arrayData));
                $html = $html . '</table>' . PHP_EOL;
                return response()->json([
                    'html' => $html
                ]);
            }else{
                return response()->json([
                    'message' => 'Hmmm. Seems like no data was recognized. Please check if columns in "A" of the excel file has dates.'
                ]);
            }
        }else{
            return response()->json([
                'message' => 'Sorry. The file you uploaded is not an excel file. Please upload a valid excel file.'
            ]);
        }
    }

    private function saveImportedExcel($datas)
    {
        $newSortedData = [];
        $prevOr = 0;
        foreach($datas as $i => $data)
        {
            if(empty($data[3])){
                $newSortedData[$prevOr][$data[4]] = $data;
            }else{
                $prevOr = $data[3];
                $newSortedData[$data[3]][$data[4]] = $data; 
            }
        }
        dd($newSortedData);
    }

    private function excelSummary($datas)
    {
        $computedValues = [];
        foreach($datas as $i => $data){
            switch($data[6]){
                case 'R':
                    $computedValues = $this->computeDisposition('residential', $data, $computedValues);
                break;
                
                case 'A':
                    $computedValues = $this->computeDisposition('agricultural', $data, $computedValues);
                break;

                case 'C':
                    $computedValues = $this->computeDisposition('commercial', $data, $computedValues);
                break;

                case 'I':
                    $computedValues = $this->computeDisposition('industrial', $data, $computedValues);
                break;

                case 'M':
                    $computedValues = $this->computeDisposition('mineral', $data, $computedValues);
                break;

                case 'S':
                    $computedValues = $this->computeDisposition('special', $data, $computedValues);
                break;

                default:

                break;
            }
        }
        $html = '';
        $sum = [];
        foreach($computedValues as $type => $data){
            $sum = isset($sum) ? array_map(function () {
                return array_sum(func_get_args());
            }, $sum, $data) : $sum = $data;
            $html = $html . '<tr>'  . PHP_EOL;
            $html = $html . '
                <td colspan=7 style="text-align: right">'.ucwords($type).':</td>' . PHP_EOL;
                foreach($data as $value){
                    $html = $html . '<td>'. (number_format(floatval($value), 2) === '0.00' ? '' : number_format(floatval($value), 2)) .'</td>' . PHP_EOL;
                }
            $html = $html . '</tr>' . PHP_EOL;
        }
        $html = $html . '<tr>
            <td colspan=7 style="text-align: right"><b>Total:</b></td>
        ' . PHP_EOL;
        $provincialHtml = '';
        $excemp = [11,12,24,25,26];
        $provincialTotal = 0;
        $provincial = 0;
        foreach($sum as  $iterator => $value){
            $html = $html . '<td><b>'. (number_format(floatval($value), 2) === '0.00' ? '' : number_format(floatval($value), 2)) . '</b></td>' . PHP_EOL;
            $provincial = (array_search($iterator, $excemp) !== false || $iterator == '27' ? 0 : (floatval($value) * ($iterator > 10 ? .5 : .35 )));
            $provincialTotal = ($iterator == "1" || $iterator == "3" || $iterator == "14" || $iterator == "16" ? $provincialTotal - $provincial : $provincialTotal + $provincial);
            $provincialHtml .= '<td>' . (number_format(floatval($value), 2) === '0.00' || array_search($iterator, $excemp) !== false ? '' : ($iterator == '27' ? number_format(floatval($provincialTotal), 2) : number_format($provincial, 2))) . '</td>' . PHP_EOL;
        }
        $html = $html . '</tr>' . PHP_EOL;
        $html = $html . '<tr>' . PHP_EOL . '<td colspan=7 style="text-align: right">Provincial Share</td>' . $provincialHtml . PHP_EOL . '</tr>'. PHP_EOL;
        
        return $html;
    }

    private function computeDisposition($type, $data, $computedValues)
    {
        isset($computedValues[$type]['basic_advance_gross']) ? $computedValues[$type]['basic_advance_gross'] += floatval($data[7]) : $computedValues[$type]['basic_advance_gross'] = floatval($data[7]);
        isset($computedValues[$type]['basic_advance_discount']) ? $computedValues[$type]['basic_advance_discount'] += floatval($data[8]) : $computedValues[$type]['basic_advance_discount'] = floatval($data[8]);
        isset($computedValues[$type]['basic_current_gross']) ? $computedValues[$type]['basic_current_gross'] += floatval($data[9]) : $computedValues[$type]['basic_current_gross'] = floatval($data[9]);
        isset($computedValues[$type]['basic_current_discount']) ? $computedValues[$type]['basic_current_discount'] += floatval($data[10]) : $computedValues[$type]['basic_current_discount'] = floatval($data[10]);
        isset($computedValues[$type]['basic_immediate']) ? $computedValues[$type]['basic_immediate'] += floatval($data[11]) : $computedValues[$type]['basic_immediate'] = floatval($data[11]);
        isset($computedValues[$type]['basic_prior_1992']) ? $computedValues[$type]['basic_prior_1992'] += floatval($data[12]) : $computedValues[$type]['basic_prior_1992'] = floatval($data[12]);
        isset($computedValues[$type]['basic_prior_1991']) ? $computedValues[$type]['basic_prior_1991'] += floatval($data[13]) : $computedValues[$type]['basic_prior_1991'] = floatval($data[13]);
        isset($computedValues[$type]['basic_penalty_current']) ? $computedValues[$type]['basic_penalty_current'] += floatval($data[14]) : $computedValues[$type]['basic_penalty_current'] = floatval($data[14]);
        isset($computedValues[$type]['basic_penalty_immediate']) ? $computedValues[$type]['basic_penalty_immediate'] += floatval($data[15]) : $computedValues[$type]['basic_penalty_immediate'] = floatval($data[15]);
        isset($computedValues[$type]['basic_penalty_prior_1992']) ? $computedValues[$type]['basic_penalty_prior_1992'] += floatval($data[16]) : $computedValues[$type]['basic_penalty_prior_1992'] = floatval($data[16]);
        isset($computedValues[$type]['basic_penalty_prior_1991']) ? $computedValues[$type]['basic_penalty_prior_1991'] += floatval($data[17]) : $computedValues[$type]['basic_penalty_prior_1991'] = floatval($data[17]);
        isset($computedValues[$type]['basic_subtotal_gross']) ? $computedValues[$type]['basic_subtotal_gross'] += floatval($data[18]) : $computedValues[$type]['basic_subtotal_gross'] = floatval($data[18]);
        isset($computedValues[$type]['basic_subtotal_net']) ? $computedValues[$type]['basic_subtotal_net'] += floatval($data[19]) : $computedValues[$type]['basic_subtotal_net'] = floatval($data[19]);
        isset($computedValues[$type]['sef_advance_gross']) ? $computedValues[$type]['sef_advance_gross'] += floatval($data[20]) : $computedValues[$type]['sef_advance_gross'] = floatval($data[20]);
        isset($computedValues[$type]['sef_advance_discount']) ? $computedValues[$type]['sef_advance_discount'] += floatval($data[21]) : $computedValues[$type]['sef_advance_discount'] = floatval($data[21]);
        isset($computedValues[$type]['sef_current_gross']) ? $computedValues[$type]['sef_current_gross'] += floatval($data[22]) : $computedValues[$type]['sef_current_gross'] = floatval($data[22]);
        isset($computedValues[$type]['sef_current_discount']) ? $computedValues[$type]['sef_current_discount'] += floatval($data[23]) : $computedValues[$type]['sef_current_discount'] = floatval($data[23]);
        isset($computedValues[$type]['sef_immediate']) ? $computedValues[$type]['sef_immediate'] += floatval($data[24]) : $computedValues[$type]['sef_immediate'] = floatval($data[24]);
        isset($computedValues[$type]['sef_prior_1992']) ? $computedValues[$type]['sef_prior_1992'] += floatval($data[25]) : $computedValues[$type]['sef_prior_1992'] = floatval($data[25]);
        isset($computedValues[$type]['sef_prior_1991']) ? $computedValues[$type]['sef_prior_1991'] += floatval($data[26]) : $computedValues[$type]['sef_prior_1991'] = floatval($data[26]);
        isset($computedValues[$type]['sef_penalty_current']) ? $computedValues[$type]['sef_penalty_current'] += floatval($data[27]) : $computedValues[$type]['sef_penalty_current'] = floatval($data[27]);
        isset($computedValues[$type]['sef_penalty_immediate']) ? $computedValues[$type]['sef_penalty_immediate'] += floatval($data[28]) : $computedValues[$type]['sef_penalty_immediate'] = floatval($data[28]);
        isset($computedValues[$type]['sef_penalty_prior_1992']) ? $computedValues[$type]['sef_penalty_prior_1992'] += floatval($data[29]) : $computedValues[$type]['sef_penalty_prior_1992'] = floatval($data[29]);
        isset($computedValues[$type]['sef_penalty_prior_1991']) ? $computedValues[$type]['sef_penalty_prior_1991'] += floatval($data[30]) : $computedValues[$type]['sef_penalty_prior_1991'] = floatval($data[30]);
        isset($computedValues[$type]['sef_subtotal_gross']) ? $computedValues[$type]['sef_subtotal_gross'] += floatval($data[31]) : $computedValues[$type]['sef_subtotal_gross'] = floatval($data[31]);
        isset($computedValues[$type]['sef_subtotal_net']) ? $computedValues[$type]['sef_subtotal_net'] += floatval($data[32]) : $computedValues[$type]['sef_subtotal_net'] = floatval($data[32]);
        isset($computedValues[$type]['grandtotal_gross']) ? $computedValues[$type]['grandtotal_gross'] += floatval($data[33]) : $computedValues[$type]['grandtotal_gross'] = floatval($data[33]);
        isset($computedValues[$type]['grandtotal_net']) ? $computedValues[$type]['grandtotal_net'] += floatval($data[34]) : $computedValues[$type]['grandtotal_net'] = floatval($data[34]);
        return $computedValues;
    }

    public function show($id,Request $request)
    {
        $dt = Carbon::now();
        $yr = $dt->format('Y');
        $mnth = $dt->format('m');
        $client_type = 0;
            if(($request->input('show_year'))){
                $yr = $request->input('show_year');
            }

            if(($request->input('show_mnth'))){
                $mnth = $request->input('show_mnth');
            }

        $months = array();
        for ($month = 1; $month <= 12; $month++) {
            $months[$month] = date('F', mktime(0,0,0,$month));
        }
        $this->base['yr'] = $yr;
        $this->base['mnth'] = $mnth;
        $this->base['months'] = $months;
        $this->base['client_type'] = $client_type;

        $this->base['sub_header'] = 'View';
        $this->base['customer'] = Customer::whereId($id)->first();
        if($mnth != 'ALL'):
            $this->base['receipts'] = Receipt::where([['col_customer_id',$id]])->whereYear('report_date','=',$yr )->where('is_cancelled','!=','1')->whereMonth('report_date','=',$mnth )->get();
            $this->base['cashdivs'] = CashDivision::where([['col_customer_id',$id]])->whereYear('date_of_entry','=',$yr )->whereMonth('date_of_entry','=',$mnth )->get();
            $this->base['mncpal_receipts'] = MunicipalReceipt::where('col_customer_id', $id)->whereYear('rcpt_date', '=', $yr)->whereMonth('rcpt_date', '=', $mnth)->where('is_cancelled', 0)->get();
        else:
            $this->base['receipts'] = Receipt::where([['col_customer_id',$id]])->whereYear('report_date','=',$yr )->where('is_cancelled','!=','1')->get();
            $this->base['cashdivs'] = CashDivision::where([['col_customer_id',$id]])->whereYear('date_of_entry','=',$yr )->get();
            $this->base['mncpal_receipts'] = MunicipalReceipt::where('col_customer_id', $id)->where('is_cancelled', 0)->get();
        endif;

        $this->base['sandgravel_types'] = sg_types::all();

        return view('collection::customer/view',$this->base)->with('base', $this->base);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $this->base['customer'] = Customer::findOrFail($id);
        $this->base['sandgravel_types'] = sg_types::all();
        $this->base['sub_header'] = 'Edit';
        return view('collection::customer.edit')->with('base', $this->base);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::whereId($id)->first();

        $editable['address'] = 'max:500';
        if ($request['name'] != $customer->name) {
            $editable['name'] = 'required|unique:' . Customer::getTableName();
        }
        // check for duplicate here..
        // excluding deleted 
        // $search = DB::select('select * from col_customer where name like "%'.$request['name'].'%" and deleted_at is null');

        $validator = Validator::make($request->all(), $editable);
        if ($validator->fails()) {
            return redirect()->route('customer.edit', $id)
                ->withErrors($validator);
        }
        // if(empty($search)) {
        //     Customer::updateOrCreate([ 'id' => $id ], [ 'name' => $request['name'] ]);
        // } else {
        //     dd($search);
        //     return redirect()->route('customer.edit', $id)
        //         ->withErrors('The name has already been taken.');
        // }

        # Update
        $customer->name = $request['name'];
        $customer->address = $request['address'];
        $customer->save();

        Session::flash('info', ['Customer/Payor information has been updated.']);
        return redirect()->route('customer.index');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        $Receipt = Receipt::where('col_customer_id',$id)->get();
        if($Receipt->isEmpty()){
            $customer = $this->customer->whereId($id)->first();
            $customer->delete();
            Session::flash('info', ['Customer/Payor has been deleted.']);
        }else{
            Session::flash('danger', ['Customer/Payor has a record Please update the record before deletion.']);
        }
        
        return redirect()->route('customer.index');
    }

    public function rpt_record_index() {
        $this->base['page_title'] = 'Payment Records';
        $this->base['municipality'] = Municipality::all();
        return view('collection::customer.rpt_record_index')->with('base', $this->base);    
    }

    public function rpt_record_dt(Request $req) {
        $tax_dec_info = array();
        // $tax_decs = DB::connection('mysql2')->select(DB::raw('select tax_dec_owner_info.id as owner_id, tax_dec_archive_info.id as taxdec_id, tax_dec_no, address, type_o, municipality, brgy, other_details from tax_dec_archive_info join tax_dec_owner_info on tax_dec_archive_info.owner_id = tax_dec_owner_info.id left join tax_dec_loc_property on tax_dec_loc_property.tax_dec_id = tax_dec_archive_info.id where municipality = '.$req->mun.' and tax_dec_no != "" and tax_dec_no is not null and tax_dec_owner_info.deleted_at is null and tax_dec_archive_info.deleted_at is null and tax_dec_loc_property.deleted_at is null
        //     and (canceled_by_tdrp is null or canceled_by_tdrp = "")
        //     group by tax_dec_no')); 
        $tax_decs = DB::connection('mysql2')->select(DB::raw('select tax_dec_owner_info.id as owner_id, tax_dec_archive_info.id as taxdec_id, tax_dec_no, address, type_o, municipality, brgy, other_details, canceled_by_tdrp from tax_dec_archive_info join tax_dec_owner_info on tax_dec_archive_info.owner_id = tax_dec_owner_info.id left join tax_dec_loc_property on tax_dec_loc_property.tax_dec_id = tax_dec_archive_info.id where municipality = '.$req->mun.' and tax_dec_no != "" and tax_dec_no is not null and tax_dec_owner_info.deleted_at is null and tax_dec_archive_info.deleted_at is null and tax_dec_loc_property.deleted_at is null group by tax_dec_no'));
        $tax_decs = collect($tax_decs);
        foreach($tax_decs as $det) {
            $owner_name = '';
            $loc_mnc = Municipality::find($det->municipality);
            $loc_brgy = Barangay::find($det->brgy);

            if($det->type_o == 'Spouse') {
                $get_owner_info = DB::connection('mysql2')->select(DB::raw('select spouse, married_to from tax_dec_archive_owner_spouse where owner_id = '.$det->owner_id));
                $get_owner_info = collect($get_owner_info);
                foreach($get_owner_info as $info) {
                    $owner_name = nl2br($info->spouse . ' & ' . $info->married_to . '
                        ');
                }
            } elseif($det->type_o == 'Person') {
                $get_owner_info = DB::connection('mysql2')->select(DB::raw('select fname, lname, mname, ename from tax_dec_archive_owner_person where owner_id = '.$det->owner_id));
                $get_owner_info = collect($get_owner_info);
                foreach($get_owner_info as $info) {
                    $owner_name = nl2br($info->fname . ' ' . $info->mname . ' ' . $info->lname . ' ' . $info->ename .'
                        ');
                }
            } elseif($det->type_o == 'Company') {
                $get_owner_info = DB::connection('mysql2')->select(DB::raw('select company_name from tax_dec_archive_owner_company where owner_id = '.$det->owner_id));
                $get_owner_info = collect($get_owner_info);
                foreach($get_owner_info as $info) {
                    $owner_name = nl2br($info->company_name . '
                        ');
                }
            } elseif($det->type_o == 'MarriedTo') {
                $get_owner_info = DB::connection('mysql2')->select(DB::raw('select owner_name, married_to from tax_dec_archive_owner_marriedto where owner_id = '.$det->owner_id));
                $get_owner_info = collect($get_owner_info);
                foreach($get_owner_info as $info) {
                    $owner_name = nl2br($info->owner_name . ' married to ' . $info->married_to .'
                        ');
                }
            } elseif($det->type_o == 'Special') {
                $get_owner_info = DB::connection('mysql2')->select(DB::raw('select special_name from tax_dec_archive_owner_special where owner_id = '.$det->owner_id));
                $get_owner_info = collect($get_owner_info);
                foreach($get_owner_info as $info) {
                    $owner_name = nl2br($info->special_name.'
                        ');
                }
            }

            $receipts = F56TDARP::where('tdarpno', $det->tax_dec_no)->first();            
            if(($det->canceled_by_tdrp != "" && !is_null($det->canceled_by_tdrp)) && (empty($receipts) || count($receipts) == 0)) {
                continue; // for cancelled tax decs w/ NO payments recorded
            }

            $enc_id = Crypt::encrypt($det->owner_id);
            $enc_td = Crypt::encrypt($det->tax_dec_no);
            array_push($tax_dec_info, [
                'arp_no' => $det->tax_dec_no,
                'owner_id' => $det->owner_id,
                'view_link' => route('rpt_record_get', [$enc_id, $enc_td]),
                'owner_name' => $owner_name,
                'owner_address' => $det->address,
                'property_mnc' => $loc_mnc->name,
                'property_brgy' => $loc_brgy->name,
                'property_details' => $det->other_details,
            ]);
        }

        $old_tax_decs = DB::connection('mysql2')->select(DB::raw('select owner_name, id, municipality, brgy, tax_dec_no, prevs_tax_dec_no, class, assessed_value
            from tax_dec_old_archive_info
            where municipality = "'.$req->mun.'"
            and (canceled_by_tdrp is null
            or canceled_by_tdrp = "")
            and deleted_at is null'));
        // $old_tax_decs = DB::connection('mysql2')->select(DB::raw('select owner_name, id, municipality, brgy, tax_dec_no, prevs_tax_dec_no, class, assessed_value, canceled_by_tdrp
        //     from tax_dec_old_archive_info
        //     where municipality = "'.$req->mun.'"
        //     and deleted_at is null'));

        foreach($old_tax_decs as $old_td) {
            $loc_mnc = Municipality::find($old_td->municipality);
            $loc_brgy = Barangay::find($old_td->brgy);

            // $receipts = F56TDARP::where('tdarpno', $old_td->tax_dec_no)->first();            
            // if(($det->canceled_by_tdrp != "" || !is_null($det->canceled_by_tdrp)) && (empty($receipts) || count($receipts) == 0)) {
            //     continue; // for cancelled tax decs w/ NO payments recorded
            // }

            array_push($tax_dec_info, [
                'arp_no' => $old_td->tax_dec_no,
                'owner_id' => $old_td->id,
                'view_link' => route('rpt_record_get', ['old', $old_td->tax_dec_no]),
                'owner_name' => $old_td->owner_name,
                'owner_address' => '',
                'property_mnc' => $loc_mnc->name,
                'property_brgy' => $loc_brgy->name,
                'property_details' => '',
            ]);
        }

        $tax_dec_info = collect($tax_dec_info);
        return Datatables::of($tax_dec_info)->make(true);
    }

    public function rpt_record_get($id = null, $td, $isPdf = null) {
        $tax_decs = F56TDARP::with('F56Detail')
                                ->get()
                                ->toArray();
        dd($tax_decs[1000]['f56_detail']);
        $id = Crypt::decrypt($id);
        $td = Crypt::decrypt($td);
        $receipts = Receipt::with('F56Detailmny.TDARPX')
            ->with('F56Detailmny.F56Type')
            ->whereHas('F56Detailmny.TDARPX', function($q) use($td) {
                $q->where('tdarpno', '=', $td);
            })
            ->where('is_cancelled', 0)
            ->where('is_printed', 1)
            ->whereYear('report_date', '>=', 2019) // [temporary] filter 2019 to present records
            ->where('af_type', 2)
            ->orderBy('date_of_entry', 'DESC')
            ->get();
        
        $property_record = [];
        $payment_record = [];
        $owner = [];
        $receipt_det = 0;
        $owner_new_arr = [];
        $effectivity_new = [];
        $cancel_by = [];

        // PROPERTY
        $tax_decs = DB::connection('mysql2')->select(DB::raw('select tax_dec_owner_info.id as owner_id, tax_dec_archive_info.id as taxdec_id, tax_dec_no, address, type_o, municipality, brgy, other_details, cert_title, class, actual_use, tax_dec_archive_kind_class.assessed_value, effectivity_assesment_yr, canceled_by_tdrp  
            from tax_dec_archive_info 
            join tax_dec_owner_info on tax_dec_archive_info.owner_id = tax_dec_owner_info.id 
            left join tax_dec_loc_property on tax_dec_loc_property.tax_dec_id = tax_dec_archive_info.id 
            join tax_dec_archive_kind_class on tax_dec_archive_info.id = tax_dec_archive_kind_class.tax_dec_id
            where tax_dec_no = "'.$td.'"
            and tax_dec_owner_info.deleted_at is null 
            and tax_dec_archive_info.deleted_at is null 
            and tax_dec_loc_property.deleted_at is null
            order by effectivity_assesment_yr ASC')); 
        if(count($tax_decs) > 0) {
            $tax_decs = collect($tax_decs);
            $owners = [];
            foreach($tax_decs as $det) {
               
                $owner_name = '';
                $loc_mnc = Municipality::find($det->municipality);
                $loc_brgy = Barangay::find($det->brgy);
                if (!in_array($det->owner_id, $owners)) {
                    
                    if($det->type_o == 'Spouse') {
                        $get_owner_info = DB::connection('mysql2')->select(DB::raw('select spouse, married_to from tax_dec_archive_owner_spouse where owner_id = '.$det->owner_id));
                        $get_owner_info = collect($get_owner_info);
                        foreach($get_owner_info as $info) {
                            $owner_name .= nl2br($info->spouse . ' & ' . $info->married_to . '
                                ');
                        }
                    } elseif($det->type_o == 'Person') {
                        $get_owner_info = DB::connection('mysql2')->select(DB::raw('select fname, lname, mname, ename from tax_dec_archive_owner_person where owner_id = '.$det->owner_id));
                        $get_owner_info = collect($get_owner_info);
                        foreach($get_owner_info as $info) {
                            $owner_name .= nl2br($info->fname . ' ' . $info->mname . ' ' . $info->lname . ' ' . $info->ename .'
                                ');
                        }
                    } elseif($det->type_o == 'Company') {
                        $get_owner_info = DB::connection('mysql2')->select(DB::raw('select company_name from tax_dec_archive_owner_company where owner_id = '.$det->owner_id));
                        $get_owner_info = collect($get_owner_info);
                        foreach($get_owner_info as $info) {
                            $owner_name .= nl2br($info->company_name . '
                                ');
                        }
                    } elseif($det->type_o == 'MarriedTo') {
                        $get_owner_info = DB::connection('mysql2')->select(DB::raw('select owner_name, married_to from tax_dec_archive_owner_marriedto where owner_id = '.$det->owner_id));
                        $get_owner_info = collect($get_owner_info);
                        foreach($get_owner_info as $info) {
                            $owner_name .= nl2br($info->owner_name . ' married to ' . $info->married_to .'
                                ');
                        }
                    } elseif($det->type_o == 'Special') {
                        $get_owner_info = DB::connection('mysql2')->select(DB::raw('select special_name from tax_dec_archive_owner_special where owner_id = '.$det->owner_id));
                        $get_owner_info = collect($get_owner_info);
                        foreach($get_owner_info as $info) {
                            $owner_name .= nl2br($info->special_name.'
                                ');
                        }
                    }
                    
                }
                
                if($det->canceled_by_tdrp != null && $det->canceled_by_tdrp != "") {
                    $replace = preg_replace('/[^0-9-\s]+/', '', $det->canceled_by_tdrp);
                    $split_td = explode(" ", $replace);
                } else {
                    $split_td = [];
                }
                
                if(count($split_td) > 0) {
                    foreach($split_td as $td_no) {
                        $owner_new = '';
                        $getNewOwner = DB::connection('mysql2')->select(DB::raw('select owner_id, type_o, effectivity_assesment_yr from tax_dec_archive_info join tax_dec_owner_info on tax_dec_archive_info.owner_id = tax_dec_owner_info.id where tax_dec_no = "'.$td_no.'"'));

                        if(count($getNewOwner) > 0) {
                            if($getNewOwner[0]->type_o == 'Spouse') {
                                $get_owner_info_new = DB::connection('mysql2')->select(DB::raw('select spouse, married_to from tax_dec_archive_owner_spouse where owner_id = '.$getNewOwner[0]->owner_id));
                                $get_owner_info_new = collect($get_owner_info_new);
                                foreach($get_owner_info_new as $info) {
                                    $owner_new .= nl2br($info->spouse . ' & ' . $info->married_to . '
                                        ');
                                }
                            } elseif($getNewOwner[0]->type_o == 'Person') {
                                $get_owner_info_new = DB::connection('mysql2')->select(DB::raw('select fname, lname, mname, ename from tax_dec_archive_owner_person where owner_id = '.$getNewOwner[0]->owner_id));
                                $get_owner_info_new = collect($get_owner_info_new);
                                foreach($get_owner_info_new as $info) {
                                    $owner_new .= nl2br($info->fname . ' ' . $info->mname . ' ' . $info->lname . ' ' . $info->ename .'
                                        ');
                                }
                            } elseif($getNewOwner[0]->type_o == 'Company') {
                                $get_owner_info_new = DB::connection('mysql2')->select(DB::raw('select company_name from tax_dec_archive_owner_company where owner_id = '.$getNewOwner[0]->owner_id));
                                $get_owner_info_new = collect($get_owner_info_new);
                                foreach($get_owner_info_new as $info) {
                                    $owner_new .= nl2br($info->company_name . '
                                        ');
                                }
                            } elseif($getNewOwner[0]->type_o == 'MarriedTo') {
                                $get_owner_info_new = DB::connection('mysql2')->select(DB::raw('select owner_name, married_to from tax_dec_archive_owner_marriedto where owner_id = '.$getNewOwner[0]->owner_id));
                                $get_owner_info_new = collect($get_owner_info_new);
                                foreach($get_owner_info_new as $info) {
                                    $owner_new .= nl2br($info->owner_name . ' married to ' . $info->married_to .'
                                        ');
                                }
                            } elseif($getNewOwner[0]->type_o == 'Special') {
                                $get_owner_info_new = DB::connection('mysql2')->select(DB::raw('select special_name from tax_dec_archive_owner_special where owner_id = '.$getNewOwner[0]->owner_id));
                                $get_owner_info_new = collect($get_owner_info_new);
                                foreach($get_owner_info_new as $info) {
                                    $owner_new .= nl2br($info->special_name.'
                                        ');
                                }
                            }
                            array_push($effectivity_new, $getNewOwner[0]->effectivity_assesment_yr);
                            array_push($owner_new_arr, $owner_new);
                        } else {
                            $getNewOwner = DB::connection('mysql2')->select(DB::raw('select owner_name from tax_dec_old_archive_info where tax_dec_no = "'.$td_no.'"'));
                            if(count($getNewOwner) > 0) {
                                $owner_new = $getNewOwner[0]->owner_name;
                                array_push($owner_new_arr, $owner_new);
                                array_push($effectivity_new, '');
                            }
                        }

                        $get_cancelBy = DB::connection('mysql2')->select(DB::raw('select id from tax_dec_archive_info where tax_dec_no = "'.$td_no.'"'));
                        if(count($get_cancelBy) == 0) {
                            $get_cancelBy = DB::connection('mysql2')->select(DB::raw('select id from tax_dec_old_archive_info where tax_dec_no = "'.$td_no.'"'));
                        }

                        array_push($cancel_by, (!is_null($get_cancelBy) && count($get_cancelBy > 0) && !empty($get_cancelBy) ? $get_cancelBy[0]->id : 'id'));
                    }
                }
                array_push($property_record, [
                    'arp_no' => $det->tax_dec_no,
                    'owner_id' => $det->owner_id,
                    'owner_name' => $owner_name,
                    'owner_address' => $det->address,
                    'title' => $det->cert_title,
                    'location' => $det->other_details != "" && $det->other_details != null ? $det->other_details . ", " . $loc_brgy->name . ", " . $loc_mnc->name : $loc_brgy->name . ", " . $loc_mnc->name,
                    'class' => $det->class,
                    'actual_use' => $det->actual_use,
                    'assessed_val' => $det->assessed_value,
                    'tax_due' => $det->assessed_value/100, 
                    'effect_assess_yr' => $det->effectivity_assesment_yr,
                    'cancelled_by' => $split_td,
                    'owner_new' => $owner_new_arr,
                    'effectivity_new' => $effectivity_new,
                    'id' => $cancel_by,
                ]);
            }
        } else {
            $old_tax_decs = DB::connection('mysql2')->select(DB::raw('select owner_name, id, municipality, brgy, tax_dec_no, prevs_tax_dec_no, class, assessed_value, canceled_by_tdrp
                from tax_dec_old_archive_info
                where tax_dec_no = "'.$td.'"
                and deleted_at is null'));
            $old_tax_decs = collect($old_tax_decs);
            foreach($old_tax_decs as $det) {
                $loc_mnc = Municipality::find($det->municipality);
                $loc_brgy = Barangay::find($det->brgy);
                if($det->canceled_by_tdrp != null && $det->canceled_by_tdrp != "") {
                    $replace = preg_replace('/[^0-9-\s]+/', '', $det->canceled_by_tdrp);
                    $split_td = explode(" ", $replace);
                } else {
                    $split_td = [];
                }

                if(count($split_td) > 0) {
                    foreach($split_td as $td_no) {
                        $getNewOwner = DB::connection('mysql2')->select(DB::raw('select owner_id, type_o, effectivity_assesment_yr from tax_dec_archive_info join tax_dec_owner_info on tax_dec_archive_info.owner_id = tax_dec_owner_info.id where tax_dec_no = "'.$td_no.'"'));            
                        $owner_new = '';
                        $replace = preg_replace('/[^0-9-\s]+/', '', $td_no);
                        $split_td = explode(" ", $replace);

                        // if($det->canceled_by_tdrp != null && $det->canceled_by_tdrp != "") {
                            if(count($getNewOwner) > 0) {
                                if($getNewOwner[0]->type_o == 'Spouse') {
                                    $get_owner_info_new = DB::connection('mysql2')->select(DB::raw('select spouse, married_to from tax_dec_archive_owner_spouse where owner_id = '.$getNewOwner[0]->owner_id));
                                    $get_owner_info_new = collect($get_owner_info_new);
                                    foreach($get_owner_info_new as $info) {
                                        $owner_new .= nl2br($info->spouse . ' & ' . $info->married_to . '
                                            ');
                                    }
                                } elseif($getNewOwner[0]->type_o == 'Person') {
                                    $get_owner_info_new = DB::connection('mysql2')->select(DB::raw('select fname, lname, mname, ename from tax_dec_archive_owner_person where owner_id = '.$getNewOwner[0]->owner_id));
                                    $get_owner_info_new = collect($get_owner_info_new);
                                    foreach($get_owner_info_new as $info) {
                                        $owner_new .= nl2br($info->fname . ' ' . $info->mname . ' ' . $info->lname . ' ' . $info->ename .'
                                            ');
                                    }
                                } elseif($getNewOwner[0]->type_o == 'Company') {
                                    $get_owner_info_new = DB::connection('mysql2')->select(DB::raw('select company_name from tax_dec_archive_owner_company where owner_id = '.$getNewOwner[0]->owner_id));
                                    $get_owner_info_new = collect($get_owner_info_new);
                                    foreach($get_owner_info_new as $info) {
                                        $owner_new .= nl2br($info->company_name . '
                                            ');
                                    }
                                } elseif($getNewOwner[0]->type_o == 'MarriedTo') {
                                    $get_owner_info_new = DB::connection('mysql2')->select(DB::raw('select owner_name, married_to from tax_dec_archive_owner_marriedto where owner_id = '.$getNewOwner[0]->owner_id));
                                    $get_owner_info_new = collect($get_owner_info_new);
                                    foreach($get_owner_info_new as $info) {
                                        $owner_new .= nl2br($info->owner_name . ' married to ' . $info->married_to .'
                                            ');
                                    }
                                } elseif($getNewOwner[0]->type_o == 'Special') {
                                    $get_owner_info_new = DB::connection('mysql2')->select(DB::raw('select special_name from tax_dec_archive_owner_special where owner_id = '.$getNewOwner[0]->owner_id));
                                    $get_owner_info_new = collect($get_owner_info_new);
                                    foreach($get_owner_info_new as $info) {
                                        $owner_new .= nl2br($info->special_name.'
                                            ');
                                    }
                                }

                                array_push($owner_new_arr, $owner_new);
                                array_push($effectivity_new, $getNewOwner[0]->effectivity_assesment_yr);
                            } else {
                                // foreach($split_td as $td_no) {
                                    $getNewOwner = DB::connection('mysql2')->select(DB::raw('select owner_name from tax_dec_old_archive_info where tax_dec_no = '.$td_no));
                                    if(count($getNewOwner) > 0) {
                                        $owner_new = $getNewOwner[0]->owner_name;
                                        array_push($owner_new_arr, $owner_new);
                                        array_push($effectivity_new, '');
                                    }
                                // }
                            }
                        // }

                        $get_cancelBy = DB::connection('mysql2')->select(DB::raw('select id from tax_dec_archive_info where tax_dec_no = "'.$td_no.'"'));
                        if(count($get_cancelBy) == 0) {
                            $get_cancelBy = DB::connection('mysql2')->select(DB::raw('select id from tax_dec_old_archive_info where tax_dec_no = '.$td_no.'"'));
                        }
                        array_push($cancel_by, (!is_null($get_cancelBy) && count($get_cancelBy > 0) ? $get_cancelBy[0]->id : 'id'));

                        array_push($property_record, [
                            'arp_no' => $det->tax_dec_no,
                            'owner_id' => '',
                            'owner_name' => $det->owner_name,
                            'owner_address' => '',
                            'title' => '',
                            'location' => $loc_brgy->name . ", " . $loc_mnc->name,
                            'class' => $det->class,
                            'actual_use' => '',
                            'assessed_val' => $det->assessed_value,
                            'tax_due' => $det->assessed_value/100, 
                            'effect_assess_yr' => '',
                            'is_old_archived' => true,
                            'cancelled_by' => $split_td,
                            'owner_new' => $owner_new_arr,
                            'effectivity_new' => $effectivity_new,
                            'id' => $cancel_by
                        ]);
                    }
                }
            }
        }

        foreach ($receipts as $key => $receipt) {
            $date_process = Carbon::parse($receipt->date_of_entry)->format('m');
            foreach($receipt->F56Detailmny as $key => $detail){
                if(!isset($payment_record[$receipt['col_customer_id']][$detail['TDARPX']['tdarpno']][$receipt['serial_no']])) {
                    $payment_record[$receipt['col_customer_id']][$detail['TDARPX']['tdarpno']][$receipt['serial_no']]['date'] = $payment_record[$receipt['col_customer_id']][$detail['TDARPX']['tdarpno']][$receipt['serial_no']]['period_covered'] = $payment_record[$receipt['col_customer_id']][$detail['TDARPX']['tdarpno']][$receipt['serial_no']]['tax'] = $payment_record[$receipt['col_customer_id']][$detail['TDARPX']['tdarpno']][$receipt['serial_no']]['penalty'] = $payment_record[$receipt['col_customer_id']][$detail['TDARPX']['tdarpno']][$receipt['serial_no']]['discount'] = $payment_record[$receipt['col_customer_id']][$detail['TDARPX']['tdarpno']][$receipt['serial_no']]['tax_type'] = array();
                }

                // PAYMENT
                array_push($payment_record[$receipt['col_customer_id']][$detail['TDARPX']['tdarpno']][$receipt['serial_no']]['date'], $receipt['date_of_entry']); 
                array_push($payment_record[$receipt['col_customer_id']][$detail['TDARPX']['tdarpno']][$receipt['serial_no']]['period_covered'], $detail['period_covered']);

                if($detail->basic_previous > 0) {
                    array_push($payment_record[$receipt['col_customer_id']][$detail['TDARPX']['tdarpno']][$receipt['serial_no']]['tax'], $detail->basic_previous);
                } elseif($detail->basic_current > 0) {
                    array_push($payment_record[$receipt['col_customer_id']][$detail['TDARPX']['tdarpno']][$receipt['serial_no']]['tax'], $detail->basic_current);
                } else {
                    array_push($payment_record[$receipt['col_customer_id']][$detail['TDARPX']['tdarpno']][$receipt['serial_no']]['tax'], 0);
                }
                array_push($payment_record[$receipt['col_customer_id']][$detail['TDARPX']['tdarpno']][$receipt['serial_no']]['penalty'], $detail['basic_penalty_current'] + $detail['basic_penalty_previous']);
                array_push($payment_record[$receipt['col_customer_id']][$detail['TDARPX']['tdarpno']][$receipt['serial_no']]['discount'], $detail['basic_discount']);
                array_push($payment_record[$receipt['col_customer_id']][$detail['TDARPX']['tdarpno']][$receipt['serial_no']]['tax_type'], $receipt->F56Detail->TDARPX['previous_tax_type_id']);

                $receipt_det++;
            }
        }  

        // history record of property
        // previous tdarpno/s
        $prev_tax_dec_record = [];
        $prev_tax_dec_paymnt = [];
        $get_tax_dec = DB::connection('mysql2')->select(DB::raw('select prevs_tax_dec_no from tax_dec_archive_info where tax_dec_no = "'.$td.'"')); 
        if(count($get_tax_dec) > 0) {
            // some tax decs have multiple previous tax decs, encoded using special characters and "AND"
            $clean = preg_replace("/[^0-9-\s]+/", " ", $get_tax_dec[0]->prevs_tax_dec_no);
            $split = explode(" ", $clean);
            for($i = 0; $i < count($split); $i++) {
                if($split[$i] != "") {
                    $owner_name = '';
                    $prev_tax_dec = DB::connection('mysql2')->select(DB::raw('select tax_dec_owner_info.id as owner_id, tax_dec_archive_info.id as taxdec_id, tax_dec_no, address, type_o, municipality, brgy, other_details, cert_title, class, tax_dec_archive_kind_class.assessed_value, actual_use, tax_dec_archive_info.id as id
                        from tax_dec_archive_info 
                        join tax_dec_owner_info on tax_dec_archive_info.owner_id = tax_dec_owner_info.id 
                        left join tax_dec_loc_property on tax_dec_loc_property.tax_dec_id = tax_dec_archive_info.id 
                        join tax_dec_archive_kind_class on tax_dec_archive_info.id = tax_dec_archive_kind_class.tax_dec_id
                        where tax_dec_no = "'.$split[$i].'"'));

                    foreach ($prev_tax_dec as $det) {
                        // property
                            $owner_name = '';
                            if($det->type_o == 'Spouse') {
                                $get_owner_info = DB::connection('mysql2')->select(DB::raw('select spouse, married_to from tax_dec_archive_owner_spouse where owner_id = '.$det->owner_id));
                                $get_owner_info = collect($get_owner_info);
                                foreach($get_owner_info as $info) {
                                    $owner_name .= nl2br($info->spouse . ' & ' . $info->married_to . '
                                        ');
                                }
                            } elseif($det->type_o == 'Person') {
                                $get_owner_info = DB::connection('mysql2')->select(DB::raw('select fname, lname, mname, ename from tax_dec_archive_owner_person where owner_id = '.$det->owner_id));
                                $get_owner_info = collect($get_owner_info);
                                foreach($get_owner_info as $info) {
                                    $owner_name .= nl2br($info->fname . ' ' . $info->mname . ' ' . $info->lname . ' ' . $info->ename .'
                                        ');
                                }
                            } elseif($det->type_o == 'Company') {
                                $get_owner_info = DB::connection('mysql2')->select(DB::raw('select company_name from tax_dec_archive_owner_company where owner_id = '.$det->owner_id));
                                $get_owner_info = collect($get_owner_info);
                                foreach($get_owner_info as $info) {
                                    $owner_name .= nl2br($info->company_name . '
                                        ');
                                }
                            } elseif($det->type_o == 'MarriedTo') {
                                $get_owner_info = DB::connection('mysql2')->select(DB::raw('select owner_name, married_to from tax_dec_archive_owner_marriedto where owner_id = '.$det->owner_id));
                                $get_owner_info = collect($get_owner_info);
                                foreach($get_owner_info as $info) {
                                    $owner_name .= nl2br($info->owner_name . ' married to ' . $info->married_to .'
                                        ');
                                }
                            } elseif($det->type_o == 'Special') {
                                $get_owner_info = DB::connection('mysql2')->select(DB::raw('select special_name from tax_dec_archive_owner_special where owner_id = '.$det->owner_id));
                                $get_owner_info = collect($get_owner_info);
                                foreach($get_owner_info as $info) {
                                    $owner_name .= nl2br($info->special_name.'
                                        ');
                                }
                            }
                            $mnc = Municipality::find($det->municipality);
                            $brgy = Barangay::find($det->brgy);


                            array_push($prev_tax_dec_record, [
                                'owner_name' => $owner_name,
                                'owner_address' => $det->address,
                                'arp_no' => $det->tax_dec_no,
                                'title' => $det->cert_title,
                                'location' => $brgy->name . ", " .  $mnc->name,
                                'classification' => $det->class,
                                'actual_use' => $det->actual_use,
                                'assess_val' => $det->assessed_value,
                                'tax_due' => $det->assessed_value/100,
                                'id' => $det->id
                            ]);

                        // payments
                            $receipts = Receipt::with('F56Detailmny.TDARPX')
                                ->with('F56Detailmny.F56Type')
                                ->whereHas('F56Detailmny.TDARPX', function($q) use($split, $i) {
                                    $q->where('tdarpno', '=', $split[$i]);
                                })
                                ->where('is_cancelled', 0)
                                ->where('is_printed', 1)
                                ->whereYear('report_date', '>=', 2019) // temporary filter for 2019 and later records
                                ->where('af_type', 2)
                                ->orderBy('date_of_entry', 'DESC')
                                ->get();

                        if(count($receipts) > 0) {
                            foreach ($receipts as $prev_pay) {
                                $prev_tax_dec_paymnt[$split[$i]][$prev_pay->serial_no]['date'] = $prev_pay->date_of_entry;
                                if(count($prev_pay['F56Detailmny']) > 0) {
                                    foreach($prev_pay['F56Detailmny'] as $vals) {
                                        $prev_tax_dec_paymnt[$split[$i]][$prev_pay->serial_no]['period_covered'] = $vals->period_covered;
                                        if($vals->basic_previous > 0) {
                                            $prev_tax_dec_paymnt[$split[$i]][$prev_pay->serial_no]['tax'] = $vals->basic_previous;
                                        } elseif($vals->basic_current > 0) {
                                            $prev_tax_dec_paymnt[$split[$i]][$prev_pay->serial_no]['tax'] = $vals->basic_current;
                                        } else {
                                            $prev_tax_dec_paymnt[$split[$i]][$prev_pay->serial_no]['tax'] = 0;
                                        }
                                        $prev_tax_dec_paymnt[$split[$i]][$prev_pay->serial_no]['penalty'] = $vals['basic_penalty_current'] + $vals['basic_penalty_previous'];
                                        $prev_tax_dec_paymnt[$split[$i]][$prev_pay->serial_no]['discount'] = $vals['basic_discount'];
                                    }
                                }
                                $prev_tax_dec_paymnt[$split[$i]][$prev_pay->serial_no]['tax_type'] = $prev_pay->F56Detail->TDARPX['previous_tax_type_id'];
                            }
                        }
                    }
                }
            }
        } else {
            // search from old archive
            $get_tax_dec = DB::connection('mysql2')->select(DB::raw('select prevs_tax_dec_no from tax_dec_old_archive_info where tax_dec_no = "'.$td.'"')); 
            if(!empty($get_tax_dec) && !is_nul($get_tax_dec)) {
                $clean = preg_replace("/[^0-9-\s]+/", " ", $get_tax_dec[0]->prevs_tax_dec_no);
                $split = explode(" ", $clean);
                for($i = 0; $i < count($split); $i++) {
                    if($split[$i] != "") {
                        $owner_name = '';
                        $prev_tax_dec = DB::connection('mysql2')->select(DB::raw('select *
                            from tax_dec_old_archive_info 
                            where tax_dec_no = "'.$split[$i].'"'));
                        
                        foreach ($prev_tax_dec as $det) {
                            // property
                            $mnc = Municipality::find($det->municipality);
                            $brgy = Barangay::find($det->brgy);
                            array_push($prev_tax_dec_record, [
                                'owner_name' => $det->owner_name,
                                'owner_address' => '',
                                'arp_no' => $det->tax_dec_no,
                                'title' => '',
                                'location' => $brgy->name . ", " .  $mnc->name,
                                'classification' => $det->class,
                                'actual_use' => '',
                                'assess_val' => $det->assessed_value,
                                'tax_due' => $det->assessed_value/100,
                                'id' => $det->id
                            ]);

                            // payments
                                $receipts = Receipt::with('F56Detailmny.TDARPX')
                                    ->with('F56Detailmny.F56Type')
                                    ->whereHas('F56Detailmny.TDARPX', function($q) use($split, $i) {
                                        $q->where('tdarpno', '=', $split[$i]);
                                    })
                                    ->where('is_cancelled', 0)
                                    ->where('is_printed', 1)
                                    ->whereYear('report_date', '>=', 2019) // temporary filter for 2019 and later records
                                    ->where('af_type', 2)
                                    ->orderBy('date_of_entry', 'DESC')
                                    ->get();
                            if(count($receipts) > 0) {
                                foreach ($receipts as $prev_pay) {    
                                    $prev_tax_dec_paymnt[$split[$i]][$prev_pay->serial_no]['date'] = $prev_pay->date_of_entry;
                                    if($prev_pay['F56Detailmny']) {
                                        foreach($prev_pay['F56Detailmny'] as $prev_vals) {
                                            $prev_tax_dec_paymnt[$split[$i]][$prev_pay->serial_no]['period_covered'] = $prev_vals->period_covered;
                                            if($prev_vals->basic_previous > 0) {
                                                $prev_tax_dec_paymnt[$fcancsplit[$i]][$prev_pay->serial_no]['tax'] = $prev_vals->basic_previous;
                                            } elseif($prev_vals->basic_current > 0) {
                                                $prev_tax_dec_paymnt[$split[$i]][$prev_pay->serial_no]['tax'] = $prev_vals->basic_current;
                                            } else {
                                                $prev_tax_dec_paymnt[$split[$i]][$prev_pay->serial_no]['tax'] = 0;
                                            }
                                            $prev_tax_dec_paymnt[$split[$i]][$prev_pay->serial_no]['penalty'] = $prev_vals['basic_penalty_current'] + $prev_vals['basic_penalty_previous'];
                                            $prev_tax_dec_paymnt[$split[$i]][$prev_pay->serial_no]['discount'] = $prev_vals['basic_discount'];
                                        }
                                    }
                                    $prev_tax_dec_paymnt[$split[$i]][$prev_pay->serial_no]['tax_type'] = $prev_pay->F56Detail->TDARPX['previous_tax_type_id'];
                                }
                            } else {

                            }
                        }
                    }
                }
            }
            
        }

        if(!($isPdf)) {
            if(count($payment_record) == 0 && count($prev_tax_dec_paymnt) == 0) {
                Session::flash('error', ['No payment records found for ARP No. '.$td]);
                return redirect()->route('rpt.records_index');
            }
        } else {
            $this->base['msg_no_record'] = "NO RECORDS FOUND FOR TAX DECLARATION ".$td;
        }
       
        $this->base['property_rec'] = $property_record;
        $this->base['payment_rec'] = $payment_record;
        $this->base['prev_tax_dec_rec'] = $prev_tax_dec_record;
        $this->base['prev_tax_dec_pay'] = $prev_tax_dec_paymnt;
        $this->base['owner'] = $owner;
        $pdf = new PDF;
        $pdf = PDF::loadView('collection::customer.rpt_record', $this->base);
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream();
    }

}
