<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\{Controller};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Collection\Entities\Barangay;
use Modules\Collection\Entities\CollectionRate;
use Modules\Collection\Entities\Customer;
use Modules\Collection\Entities\F56Detail;
use Modules\Collection\Entities\F56PreviousReceipt;
use Modules\Collection\Entities\F56TDARP;
use Modules\Collection\Entities\F56Type;
use Modules\Collection\Entities\Municipality;
use Modules\Collection\Entities\Receipt;
use Modules\Collection\Entities\ReceiptItemDetail;
use Modules\Collection\Entities\ReceiptItems;
use Modules\Collection\Entities\WeekdayHoliday;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class MunicipalExcelImportController extends Controller
{
    public function index()
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
                
                $html = $html . $this->excelSummary(array_filter($arrayData));
                $html = $html . '</table>' . PHP_EOL;
                return response()->json([
                    'html' => $html,
                    'data' => $this->resortExcelData(array_filter($arrayData)),
                    'municipality' => $request->excel_municipality,
                ]);
            }else{
                return response()->json([
                    'message' => 'Hmmm. Seems like no data was recognized. Please check if columns in "A" of the excel file has dates.',
                ]);
            }
        }else{
            return response()->json([
                'message' => 'Sorry. The file you uploaded is not an excel file. Please upload a valid excel file.'
            ]);
        }
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

    private function resortExcelData($datas)
    {
        $newSortedData = [];
        $prevOr = 0;
        foreach($datas as $i => $data)
        {
            $values = [
                "date" => $data[0],
                "name" => $data[1],
                "period_covered" => $data[2],
                "serial_number" => $data[3],
                "tdarp" => $data[4],
                "barangay" => $data[5],
                "classification" => $data[6],
                "basic_advance_gross" => $data[7],
                "basic_advance_discount" => $data[8],
                "basic_current_gross" => $data[9],
                "basic_current_discount" => $data[10],
                "basic_immediate" => $data[11],
                "basic_prior_1992" => $data[12],
                "basic_prior_1991" => $data[13],
                "basic_penalty_current" => $data[14],
                "basic_penalty_immediate" => $data[15],
                "basic_penalty_prior_1992" => $data[16],
                "basic_penalty_prior_1991" => $data[17],
                "basic_subtotal_gross" => $data[18],
                "basic_subtotal_net" => $data[19],
                "sef_advance_gross" => $data[20],
                "sef_advance_discount" => $data[21],
                "sef_current_gross" => $data[22],
                "sef_current_discount" => $data[23],
                "sef_immediate" => $data[24],
                "sef_prior_1992" => $data[25],
                "sef_prior_1991" => $data[26],
                "sef_penalty_current" => $data[27],
                "sef_penalty_immediate" => $data[28],
                "sef_penalty_prior_1992" => $data[29],
                "sef_penalty_prior_1991" => $data[30],
                "sef_subtotal_gross" => $data[31],
                "sef_subtotal_net" => $data[32],
                "grandtotal_gross" => $data[33],
                "grandtotal_net" => $data[34],
            ];
            if(empty($data[3])){
                $newSortedData[$prevOr][$data[4]] = $values;
            }else{
                $prevOr = $data[3];
                $newSortedData[$data[3]][$data[4]] = $values;
            }
        }
        return $newSortedData;
    }

    public function saveUploadedExcel(Request $request)
    {
        $data = json_decode($request['excel-data']);
        $tdarps = [];
        $municipality = $request['excel_municipality'];
        $counter = 0;
        foreach ($data as $or_number => $values) {
            foreach($values as $tdarp => $value) {
        
        # Add payor if not existing
        $payor_id = 0;
        

        $payor = Customer::withTrashed()->where('name', $value->name)->first();
        if (!empty($payor)) {
            $payor_id = $payor->id;
            $payor->restore();
        } else {
            $payor_id = Customer::create([
                'name' => $value->name,
                'address' => '',
                ]);
            $payor_id = $payor_id->id;
        }
        
        // $is_printed = 0;
        // $report_datex = new Carbon($request['date']);

        $f56_type = 0;
        switch ($value->classification) {
            case 'R':
                $f56_type = 1;
            break;
            
            case 'A':
                $f56_type = 2;
            break;

            case 'C':
                $f56_type = 3;
            break;

            case 'I':
                $f56_type = 4;
            break;

            case 'M':
                $f56_type = 5;
            break;

            case 'S':
                $f56_type = 6;
            break;
        }
        $barangay = Barangay::where([
            ['name', '=', 'Kayapa'],
            ['municipality_id', '=', $municipality]
            ])->first();
        if ($barangay == null) {
            return redirect()->route('rpt.import_excel_report');
        }
        

        # Success
        $receipt = Receipt::create([
            'serial_no' => $value->serial_number,
            'af_type' => 2,
            'col_municipality_id' => $municipality,
            'col_barangay' => $barangay,
            'col_customer_id' => $payor_id,
            'report_date' => date('Y-m-d H:i:s'),
            'date_of_entry' => date('Y-m-d H:i:s'),
            'is_printed' => 1,
            'is_cancelled' => 0,
            'cancelled_remark' => '',
            'transaction_source' => 'field_land_tax',
            'transaction_type' => 1,
            'client_type' => 0,
        ]);


        $share_provincial = $value->grandtotal_net;
        $share_municipal = 0;
        $share_barangay = 0;

        $receipt_item = ReceiptItems::create([
            'col_receipt_id' => $receipt->id,
            'nature' => "Real Property Tax-Basic (Net of Discount)",
            'col_acct_title_id' => 2,
            'col_acct_subtitle_id' => 0,
            'value' => $value->grandtotal_net,
            'share_provincial' => $share_provincial,
            'share_municipal' => $share_municipal,
            'share_barangay' => $share_barangay,
        ]);

        $prev_tax_dec = DB::connection('mysql2')->select(DB::raw('select tax_dec_owner_info.id as owner_id, tax_dec_archive_info.id as taxdec_id, tax_dec_no, address, type_o, municipality, brgy, other_details, cert_title, class, tax_dec_archive_kind_class.assessed_value, actual_use, tax_dec_archive_info.id as id
            from tax_dec_archive_info 
            join tax_dec_owner_info on tax_dec_archive_info.owner_id = tax_dec_owner_info.id 
            left join tax_dec_loc_property on tax_dec_loc_property.tax_dec_id = tax_dec_archive_info.id 
            join tax_dec_archive_kind_class on tax_dec_archive_info.id = tax_dec_archive_kind_class.tax_dec_id
            where tax_dec_no = "'.$value->tdarp.'"'));
            // dd($prev_tax_dec);
        $detail = [];
        $period_covered = $value->period_covered;
                        
        $detail = F56Detail::create([ 
            'col_receipt_id' => $receipt->id,
            'col_f56_type_id' => $f56_type,
            'owner_name' => $value->name,
            'tdrp_assedvalue' => $prev_tax_dec[0]->assessed_value,
            'period_covered' => $value->period_covered,
            'basic_current' => $value->basic_current_gross,
            'basic_discount' => $value->basic_current_discount,
            'basic_previous' => $value->basic_immediate + $value->basic_prior_1992 + $value->basic_prior_1991,
            'basic_penalty_current' => $value->basic_penalty_current,
            'basic_penalty_previous' => $value->basic_penalty_immediate + $value->basic_penalty_prior_1992 + $value->basic_penalty_prior_1991,
            'manual_tax_due' => $prev_tax_dec[0]->assessed_value * .01,
        ]);
        
        
        $row['col_f56_detail_id'] = $detail->id;
        $row['tdarpno'] = $prev_tax_dec[0]->assessed_value;
        $row['municipality'] =  $municipality;
        $row['barangay'] =  $barangay->id;
        $row['f56_type'] = $f56_type;
        $row['previous_tax_type_id'] = 5; // vague insertion
        array_push($tdarps, $row);
        F56TDARP::insert($tdarps);

        $counter++;
    }
}
dd($receipt);
            
            
        //     F56TDARP::insert($data);

        //     Session::flash('info', ['Successfully created Form 56 transaction for serial: '.$receipt->serial_no]);
        // return redirect()->route('form56.index');
    }
}
