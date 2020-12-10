<?php

namespace Modules\Collection\Http\Controllers;

use App\Http\Controllers\{Controller};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Collection\Entities\CollectionRate;
use Modules\Collection\Entities\Customer;
use Modules\Collection\Entities\F56Detail;
use Modules\Collection\Entities\F56PreviousReceipt;
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
                "sef_penalty_current" => $data[14],
                "sef_penalty_immediate" => $data[15],
                "sef_penalty_prior_1992" => $data[16],
                "sef_penalty_prior_1991" => $data[17],
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


        foreach ($data as $or_number => $values) {
            # code...
        }
        # Add payor if not existing
        $payor_id = 0;
        
        $payor = Customer::withTrashed()->where('name',$values['name'])->first();
        if (!empty($payor)) {
            $payor_id = $payor->id;
            $payor->restore();
        } else {
            $payor_id = Customer::create([
                'name' => $request['customer'],
                'address' => '',
                ]);
            $payor_id = $payor_id->id;
        }
        

        $is_printed = 0;
        $report_datex = new Carbon($request['date']);

        $dt_3pm = new Carbon($report_datex->format('Y-m-d'));

        if($report_datex->timestamp <= $dt_3pm->addHours(15)->timestamp){
            $report_date = $report_datex->format('Y-m-d');
        }else{
            $got_valid_date = false;
            $wh = WeekdayHoliday::where('date', $report_datex->format('Y-m-d'))->first();
            $rday = $report_datex->format('Y-m-d');
                while (!$got_valid_date) {
                    $rday =  $report_datex->addDay();
                    if ( $rday->format('D') == 'Sun' ||  $rday->format('D') == 'Sat' ) {
                        continue;
                    }

                    $wh = WeekdayHoliday::where('date', $rday->format('Y-m-d'))->first();
                    if ($wh != null) {
                        continue;
                    }
                    $got_valid_date = true;
                }
                $report_date = $rday;
        }
        $aftype = 0;
        switch ($values['classification']) {
            case 'R':
                $aftype = F56Type::where('id', 1)->first();
            break;
            
            case 'A':
                $aftype = F56Type::where('id', 2)->first();
            break;

            case 'C':
                $aftype = F56Type::where('id', 3)->first();
            break;

            case 'I':
                $aftype = F56Type::where('id', 4)->first();
            break;

            case 'M':
                $aftype = F56Type::where('id', 5)->first();
            break;

            case 'S':
                $aftype = F56Type::where('id', 6)->first();
            break;

        }

        $municipality = Municipality::where('name', $request['municipality'])->first();
        $dtx = new Carbon;
        # Success
        $receipt = Receipt::create([
            'serial_no' => $values['serial_number'],
            'af_type' => $aftype->id,
            'col_municipality_id' => $municipality->id,
            'col_customer_id' => $payor_id,
            'report_date' => date('Y-m-d H:i:s'),
            'date_of_entry' => date('Y-m-d H:i:s'),
            'is_printed' => $is_printed,
            'is_cancelled' => 0,
            'cancelled_remark' => '',
            'transaction_source' => $values['field_land_tax'],
            'transaction_type' => 1,

            'client_type' => 0,
            ]);


            $share_provincial = 0;
            $share_municipal = 0;
            $share_barangay = 0;

            $receipt_item = ReceiptItems::create([
                'col_receipt_id' => $receipt->id,
                'nature' => "Real Property Tax-Basic (Net of Discount)",
                'col_acct_title_id' => 2,
                'col_acct_subtitle_id' => 0,
                'value' => $values["grandtotal_net"],
                'share_provincial' => $values["grandtotal_net"],
                'share_municipal' => $share_municipal,
                'share_barangay' => $share_barangay,
                ]);
        
            $detail = F56Detail::create([
                'col_receipt_id' => $receipt->id,
                'col_f56_type_id' => $aftype,
                'owner_name' => $values['name'],
                'tdrp_assedvalue' => $request['tdrp_assedvalue'],
                'period_covered' => $request['period_covered'],
                'full_partial' => $request['full_partial'],
                'basic_current' => $request['basic_current'],
                'basic_discount' => $request['basic_discount'],
                'basic_previous' => $request['basic_previous'],
                'basic_penalty_current' => $request['basic_penalty_current'],
                'basic_penalty_previous' => $request['basic_penalty_previous'],
                // 'manual_tax_due' => $request['tdrp_taxdue'],
                'manual_tax_due' => $request['tdrp_assedvalue']*.01,
                'ref_num' => isset($request['ref_num']) ? $request['ref_num'] : null,
            ]);

            $row['col_f56_detail_id'] = $detail->id;
            $row['tdarpno'] = $tan;
            $row['municipality'] =  $request['municipality'];
            $row['barangay'] =  $request['tdrp_barangay'][$i];
            $row['f56_type'] = $request['f56_type'][$i];
            $row['previous_tax_type_id'] = $request['previous_tax_type']; // vague insertion
            array_push($data, $row);
            
            F56TDARP::insert($data);

            Session::flash('info', ['Successfully created Form 56 transaction for serial: '.$receipt->serial_no]);
        return redirect()->route('form56.index');
    }
}
