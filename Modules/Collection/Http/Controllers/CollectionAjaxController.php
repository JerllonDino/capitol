<?php

namespace Modules\Collection\Http\Controllers;

use Modules\Collection\Entities\AccountCategory;
use Modules\Collection\Entities\AccountTitle;
use Modules\Collection\Entities\AccountSubtitle;
use Modules\Collection\Entities\Barangay;
use Modules\Collection\Entities\CollectionRate;
use Modules\Collection\Entities\Customer;
use Modules\Collection\Entities\Form;
use Modules\Collection\Entities\Municipality;
use Modules\Collection\Entities\Serial;
use Modules\Collection\Entities\AccessAccounts;

use Modules\Collection\Entities\CashDivision;
use Modules\Collection\Entities\CashDivisionItems;
use Modules\Collection\Entities\Receipt;
use Modules\Collection\Entities\ReceiptItems;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Http\Request;
use Modules\Collection\Entities\PCSettings;
use App\Http\Controllers\AjaxController;
use Modules\Collection\Entities\ReportOfficerNew;

class CollectionAjaxController extends AjaxController
{
    protected function get_customer($params) {
        // $customers = Customer::with('latest_receipt')->where('name', 'like', '%'.$params['input'].'%')
        //     ->orderBy('name', 'asc')
        //     ->limit(5)
        //     ->get(['id', 'name'])
        //     ->toArray();
        // return $customers;

        $customers = Customer::with('all_receipt')->with('all_receipt.items')->where('name', 'like', '%'.$params['input'].'%')
            ->orderBy('name', 'asc')
            ->limit(5)
            ->get(['id', 'name'])
            ->toArray();
        return $customers;
    }



 protected function get_barangays($params) {
        $barangays = Barangay::where('municipality_id', '=', $params['input'])
            ->orderBy('name', 'asc')
            ->get(['id', 'code', 'name'])
            ->toArray();
        return $barangays;
    }

    protected function get_accounts($params) {
        $AccessAccounts = AccessAccounts::getTableName();
        if($params['collection_type'] != 'show_in_cashdivision' ){
         
         if(isset($params['form_type'])){
            if($params['form_type'] == 1){
                    $set_form = $AccessAccounts.'.show_in_form51';
                }else{
                     $set_form = $AccessAccounts.'.show_in_form56';
                }
                        $acct_title = AccountTitle::join($AccessAccounts,$AccessAccounts.'.col_acct_title_id','=','col_acct_title.id')
                                     ->where('name', 'like', '%'.$params['input'].'%')
                                     ->where($params['collection_type'], '=', '1')
                                     ->where($set_form,'=','1')
                                    ->orderBy('name', 'asc')
                                    ->get();

                        $acct_subtitle = AccountSubtitle::join($AccessAccounts,$AccessAccounts.'.col_acct_subtitle_id','=','col_acct_subtitle.id')
                                    ->where('name', 'like', '%'.$params['input'].'%')
                                    ->where($params['collection_type'], '=', '1')
                                    ->where($set_form,'=','1')
                                    ->orderBy('name', 'asc')
                                    ->get();
            }else{

                        $acct_title = AccountTitle::join($AccessAccounts,$AccessAccounts.'.col_acct_title_id','=','col_acct_title.id')
                                     ->where('name', 'like', '%'.$params['input'].'%')
                                     ->where($params['collection_type'], '=', '1')
                                    ->orderBy('name', 'asc')
                                    ->get();

                        $acct_subtitle = AccountSubtitle::join($AccessAccounts,$AccessAccounts.'.col_acct_subtitle_id','=','col_acct_subtitle.id')
                                    ->where('name', 'like', '%'.$params['input'].'%')
                                    ->where($params['collection_type'], '=', '1')
                                    ->orderBy('name', 'asc')
                                    ->get();
            }
        }else{

                        $acct_title = AccountTitle::join($AccessAccounts,$AccessAccounts.'.col_acct_title_id','=','col_acct_title.id')
                                     ->where('name', 'like', '%'.$params['input'].'%')
                                     ->where($params['collection_type'], '=', '1')
                                    ->orderBy('name', 'asc')
                                    ->get();

                        $acct_subtitle = AccountSubtitle::join($AccessAccounts,$AccessAccounts.'.col_acct_subtitle_id','=','col_acct_subtitle.id')
                                    ->where('name', 'like', '%'.$params['input'].'%')
                                    ->where($params['collection_type'], '=', '1')
                                    ->orderBy('name', 'asc')
                                    ->get();
        }



        # Puts data into data array which will be returned
        $data = array();
        foreach($acct_title as $title) {
            $titlex = $title->name;
            if($title->col_acct_title_id == 3 ){
                $titlex = $title->name.' (Sale w/ SP of )';
            }
            array_push($data, [
                'id' => $title->col_acct_title_id,
                'name' => $title->name .' ('. $title->group->category->name .')',
                'type' => 'title',
                'title' => $titlex
            ]);
        }

                    foreach($acct_subtitle as $subtitle) {
                         $category_n =  '';
                        if(isset( $subtitle->title->group->category->id)){
                                $category_n =  ' ('. $subtitle->title->group->category->name .')';
                        }
                              array_push($data, [
                            'id' => $subtitle->col_acct_subtitle_id,
                            'name' => $subtitle->name .$category_n,
                            'type' => 'subtitle',
                            'title' => $subtitle->name
                            ]);
                        }

                    return $data;

    }

    protected function get_rate($params) {
        $column = ($params['account_type'] == 'title') ? 'col_acct_title_id' : 'col_acct_subtitle_id';
        $rate = CollectionRate::where($column, $params['account_id'])->get();
        return $rate;
    }

    protected function get_serial($params) {
        // dd($params);
        $data = [];
        $collection_type = $params['collection_type'];
        $collection_type =  str_replace("show_in_","",$collection_type);
        $collection_type =  strtoupper($collection_type);
        $ip =  \Request::ip();
        $serials = Serial::where('serial_current', '<>', 0)
            ->where('acctble_form_id', $params['form']);

        if($params['source'] != "0"){
            $serials = $serials->whereNotNull('accountable_officer');
        }else{
            $serials = $serials->whereNull('accountable_officer');
        }
        $serials = $serials->get();

        foreach ($serials as $serial) {
            $addtl_data = '';
            $officer = null;
            if($serial->accountable_officer){
                $officer = ReportOfficerNew::find($serial->accountable_officer);
                $officer = $officer->officer_name;
            }
            if($params['form'] == 1 ){
                if($params['collection_type'] === 'show_in_landtax'){
                    if ($serial->pc_receipts){
                        if($serial->pc_receipts->pc_ip == $ip && $serial->pc_receipts->process_type == $collection_type ){
                        // if($serial->pc_receipts->process_type == $collection_type ){
                                if ($serial->municipality_id !== null && $params['form'] == 2) {
                                         $addtl_data = ' ' . $serial->municipality->name;
                                } else {
                                    $addtl_data = ' ' . $serial->unit . ' (' . $serial->fund->name . ')';
                                }
                                $checkCurrent = Receipt::where('serial_no', $serial->serial_current)->first();
                                if($serial->serial_current == $serial->serial_end && count($checkCurrent) > 0) {
                                    continue;
                                }
                                array_push($data, [
                                    'id' => $serial->id,
                                    'current' => $serial->serial_current,
                                    'officer' => $serial->accountable_officer,
                                    'label' => $serial->serial_begin .'-'. $serial->serial_end . ($params['source'] != "0" ? "(" . $officer . ")" : $addtl_data),
                                    'municipality' => $serial->municipality_id,

                                ]);

                        }
                    }
                }else{
                     if(!isset($serial->pc_receipts->pc_ip)){
                        if ($serial->municipality_id !== null && $params['form'] == 2) {
                                         $addtl_data = ' ' . $serial->municipality->name;
                                } else {
                                    $addtl_data = ' ' . $serial->unit . ' (' . $serial->fund->name . ')';
                                }
                                $checkCurrent = Receipt::where('serial_no', $serial->serial_current)->first();
                                if($serial->serial_current == $serial->serial_end && count($checkCurrent) > 0) {
                                    continue;
                                }
                                

                                array_push($data, [
                                    'id' => $serial->id,
                                    'current' => $serial->serial_current,
                                    'officer' => $serial->accountable_officer,
                                    'label' => $serial->serial_begin .'-'. $serial->serial_end . ($params['source'] != "0" ? " (".$officer.")" : $addtl_data),
                                    'municipality' => $serial->municipality_id
                                ]);
                     }
                    
                }
               
            }else{
                if ($serial->municipality_id !== null && $params['form'] == 2) {
                                         $addtl_data = ' ' . $serial->municipality->name;
                                } else {
                                    $addtl_data = ' ' . $serial->unit . ' (' . $serial->fund->name . ')';
                                }
                                // array_push($data, [
                                //     'id' => $serial->id,
                                //     'current' => $serial->serial_current,
                                //     'label' => $serial->serial_begin .'-'. $serial->serial_end . $addtl_data,
                                // ]);
                                $checkCurrent = Receipt::where('serial_no', $serial->serial_current)->first();
                                if($serial->serial_current == $serial->serial_end && count($checkCurrent) > 0) {
                                    continue;
                                }
                                $data[$serial->municipality->id][] = [
                                    'id' => $serial->id,
                                    'current' => $serial->serial_current,
                                    'label' => $serial->serial_begin .'-'. $serial->serial_end . $addtl_data,
                                    'municipality' => $serial->municipality_id,
                                    'municipality_name' => $serial->municipality->name,
                                    'municipality_code' => $serial->municipality->code,
                                ];
            }
          
              
        }
        if($params['form'] == 2 ){
            ksort($data);
            $datax = [];
            foreach ($data as $key => $value) {
                array_push($datax, $value );
            }

            $data =   $datax;

        }

        return $data;
    }

    protected function get_sched_settings($params) {
        $data = array();
        $rate = CollectionRate::whereId($params['rate_id'])->get();
        return $rate;
    }

    protected function get_schedule($params) {
        $column = ($params['acct_type'] == 'title') ? 'col_acct_title_id' : 'col_acct_subtitle_id';
        $schedule = CollectionRate::where($column, $params['acct_id'])
            ->where('label', 'like', '%' . $params['input'] . '%')
            ->get();
        return $schedule;
    }

    protected function get_municipality($params) {
        $serial = Serial::whereId($params['input'])->first();
        $municipality = Municipality::whereId($serial->municipality_id)->first();
        return $municipality;
    }

    public function get_shared_bac_report($params){
        $municipalities = Municipality::all();
        $municipalityx=[];
            foreach ($municipalities as $municipality) {
                if($municipality->id != 14){
                    $params['municipality'] = $municipality->id;
                    $municipalityx[$municipality->name]= $this->get_shared_bac_reportx($params);
                }
            }

            return $municipalityx;
    }

    protected function get_shared_bac_reportx($params) {
        $insurance_premium = 42;
        $date_start = $params['year'].'-'.$params['month'].'-01';
        $date_end = date('Y-m-t', strtotime($date_start));

        $municipality = Municipality::whereId($params['municipality'])->first();
        $municipality = $municipality->name;
        $res = [];

        # ADD VALUES NOT IN MONTHLY REPORT
        $receipts = Receipt::where('report_date','>=' , $date_start)
                                    ->where('report_date','<=' , $date_end )
                                    ->where('is_cancelled','=','0')
                                    ->get();

        foreach ($receipts as $receipt) {

            foreach ($receipt->items as $item) {
                $ref = ($item->col_acct_title_id != 0) ? 'acct_title' : 'acct_subtitle';
                if ($item->{$ref}->show_in_monthly == 1) {
                    continue;
                }
                $value = ($item->$ref->id == $insurance_premium) ? $item->value - 15 : $item->value;
                if (!isset($res[$item->{$ref}->id])) {
                    $cat = '';
                    if ($item->col_acct_title_id != 0) {
                        $cat = $item->$ref->group->category->name;
                    } else {
                        $cat = $item->$ref->title->group->category->name;
                    }
                    $res[$item->$ref->id] = [
                        'name' => $item->$ref->name.' ('.$cat.')',
                        'value' => $value
                    ];
                } else {
                    $res[$item->$ref->id]['value'] += $value;
                }

            }
        }
        $res[$municipality] = [];

            $receipts = Receipt::where('report_date','>=' , $date_start)
                            ->where('report_date','<=' , $date_end )
                            ->where('col_municipality_id', '=', $params['municipality'])
                            ->where('is_cancelled','=','0')
                            ->get();





        foreach($receipts as $receipt) {
            if (!isset($res[$receipt->barangay->name])) {
                $res[$receipt->barangay->name] = array();
            }

            if ($receipt->af_type == 1) {
                foreach($receipt->items as $item) {
                    $type = ($item->col_acct_title_id != 0) ? 'title' : 'subtitle';
                    $ref = ($item->col_acct_title_id != 0) ? 'acct_title' : 'acct_subtitle';
                    $id = ($item->col_acct_title_id != 0) ? $item->col_acct_title_id : $item->col_acct_subtitle_id;

                    if (isset($item->$ref->rate)) {
                        if ($item->$ref->rate->is_shared != 1) {
                            # not shared
                            continue;
                        }
                    } else {
                        # no rate
                        continue;
                    }

                    if (!isset($res[$receipt->barangay->name][$type.$id])) {
                        $res[$receipt->barangay->name][$type.$id] = [
                            'name' => $item->$ref->name,
                            'value' => $item->share_barangay,
                        ];
                    } else {
                        $res[$receipt->barangay->name][$type.$id]['value'] += $item->share_barangay;
                    }

                    if (!isset($res[$municipality][$type.$id])) {
                        $res[$municipality][$type.$id] = [
                            'name' => $item->$ref->name,
                            'value' => $item->share_municipal,
                        ];
                    } else {
                        $res[$municipality][$type.$id]['value'] += $item->share_municipal;
                    }
                }
            } else {
                # compute amt for shared
                if($receipt->F56Detail){
                        $basic_municipal = (round($receipt->F56Detail->basic_current * .40, 2) + round($receipt->F56Detail->basic_previous * .40, 2)) - round($receipt->F56Detail->basic_discount * .40, 2);
                        $basicpenalty_municipal = round($receipt->F56Detail->basic_penalty_current * .40, 2) + round($receipt->F56Detail->basic_penalty_previous * .40, 2);
                        $basic_barangay = (round($receipt->F56Detail->basic_current * .25, 2) + round($receipt->F56Detail->basic_previous * .25, 2)) - round($receipt->F56Detail->basic_discount * .25, 2);
                        $basicpenalty_barangay = round($receipt->F56Detail->basic_penalty_current * .25, 2) + round($receipt->F56Detail->basic_penalty_previous * .25, 2);

                        $sef_municipal = (bcdiv($receipt->F56Detail->basic_current * .5, 1, 2) + bcdiv($receipt->F56Detail->basic_previous * .5, 1, 2)) - bcdiv($receipt->F56Detail->basic_discount * .5, 1, 2);
                        $sefpenalty_municipal = bcdiv($receipt->F56Detail->basic_penalty_current * .5, 1, 2) + bcdiv($receipt->F56Detail->basic_penalty_previous * .5, 1, 2);



                        if (!isset($res[$municipality]['title2'])) {
                            $res[$municipality]['title2'] = [ 'name' => 'Real Property Tax-Basic (Net of Discount)', 'value' => $basic_municipal ];
                            $res[$municipality]['subtitle1'] = [ 'name' => 'Tax Revenue - Fines & Penalties - Real Property Taxes', 'value' => $basicpenalty_municipal ];
                            $res[$municipality]['title47'] = [ 'name' => 'Special Education Tax', 'value' => $sef_municipal ];
                            $res[$municipality]['title48'] = [ 'name' => 'Tax Revenue - Fines & Penalties - Property Tax', 'value' => $sefpenalty_municipal ];
                        } else {
                            $res[$municipality]['title2']['value'] += $basic_municipal;
                            $res[$municipality]['subtitle1']['value'] += $basicpenalty_municipal;
                            $res[$municipality]['title47']['value'] += $sef_municipal;
                            $res[$municipality]['title48']['value'] += $sefpenalty_municipal;
                        }

                         if (!isset($res[$receipt->barangay->name]['title2'])) {
                            $res[$receipt->barangay->name]['title2'] = [ 'name' => 'Real Property Tax-Basic (Net of Discount)', 'value' => $basic_barangay ];
                            $res[$receipt->barangay->name]['subtitle1'] = [ 'name' => 'Tax Revenue - Fines & Penalties - Real Property Taxes', 'value' => $basicpenalty_barangay ];
                        } else {
                            $res[$receipt->barangay->name]['title2']['value'] += $basic_barangay;
                            $res[$receipt->barangay->name]['subtitle1']['value'] += $basicpenalty_barangay;
                        }
                }
            }
        }

         $cashdivs = CashDivision::where('date_of_entry','>=' , $date_start)
                            ->where('date_of_entry','<=' , $date_end )
                            ->where('col_municipality_id', '=', $params['municipality'])
                            ->get();

         foreach($cashdivs as $cashdiv) {


                    foreach($cashdiv->items as $item) {
                            $type = ($item->col_acct_title_id != 0) ? 'title' : 'subtitle';
                            $ref = ($item->col_acct_title_id != 0) ? 'acct_title' : 'acct_subtitle';
                            $id = ($item->col_acct_title_id != 0) ? $item->col_acct_title_id : $item->col_acct_subtitle_id;

                            if (isset($item->$ref->rate)) {
                                if ($item->$ref->rate->is_shared != 1) {
                                    # not shared
                                    continue;
                                }
                            } else {
                                # no rate
                                continue;
                            }

                            if (!isset($res[$cashdiv->barangay->name][$type.$id])) {
                                $res[$cashdiv->barangay->name][$type.$id] = [
                                    'name' => $item->$ref->name,
                                    'value' => $item->share_barangay,
                                ];
                            } else {
                                $res[$cashdiv->barangay->name][$type.$id]['value'] += $item->share_barangay;
                            }

                            if (!isset($res[$municipality][$type.$id])) {
                                $res[$municipality][$type.$id] = [
                                    'name' => $item->$ref->name,
                                    'value' => $item->share_municipal,
                                ];
                            } else {
                                $res[$municipality][$type.$id]['value'] += $item->share_municipal;
                            }
                        }
        }

        return $res;
    }

    protected function rpt_p2_report($params) {

        $pdf = new PDF;
        $pdf = PDF::loadView('collection::pdf/real_property_p2', $this->base);
        $pdf->setPaper('A4', 'portrait');
        return @$pdf->stream();
    }

    protected function get_f56_accts($params) {
        $res = array();
        $res['account'] = AccountTitle::whereId(2)->first();
        $res['type'] = 'title';
        $res['rate'] = $res['account']->rate;
        $res['shared'] = ($res['rate'] == null) ? 0 : $res['rate']->is_shared;
        return $res;
    }
}