<!DOCTYPE html>
<html>
<head>

    <title>Receipt</title>
    <style>
        html{ margin: 0px; width: 12.50cm; height: 25.5cm;}
        @page { margin: 0px; 
            size: 25.5cm 12.50cm ;}
        body{
            margin: 40px 0 0 0 ;
            font-size: 0.8em;
            font-family: arial, "sans-serif" !important;
            /*background-image: url({{ URL::asset('form56.png') }});*/
            
        }
        .hidden {
            /*display: none;*/
        }

        .text-hidden{
            color: #FFFF !important;
            /*color: #000 !important;*/
        }

        .border-hidden{
            /*border:1px solid #000 !important;*/
            border: hidden !important;
            border-color: #FFF !important;
        }
        table{
            border-collapse: collapse;
        }
       
        .text-center{
            text-align: center;
        }

        .text-right{
            text-align: right !important;
        }
        .text-left{
            text-align: left;
        }

        .rotated_vertical {
            -webkit-transform:rotate(270deg);
            -moz-transform:rotate(270deg);
            -ms-transform:rotate(270deg);
            -o-transform:rotate(270deg);
            transform:rotate(270deg);
            transform-origin: 50%;
            width: 20px;
        }

        .vertical-top{
            vertical-align: top;
        }
    </style>
</head>
<body>
    <table width="100%" class="border-hidden" style="margin: 0 ; background: ##dbba7d; position: absolute; top: -15px;">
        <tr>
            <td colspan=2 rowspan=2 height='15%' style="padding: 0; margin: 0; background: ##a7e57b;">
                <table width="100%" class="border-hidden" style="padding: 0; margin: 0;">
                    <tr >
                        <td style="margin:0" width="15%" ></td>
                        <td style="text-align: center;" width="50%">
                            @if($wmunicipality)
                                <b>{{strtoupper($receipt->municipality->name)}}, BENGUET</b>
                            @endif
                        </td>
                        <td style="padding: 0; margin: 0;">

                        @php
                            $tax_type = '';
                        
                            if(isset($receipt->F56Detail->col_f56_previous_receipt)){
                               $tax_type = $receipt->F56Detail->TDARPX->previousTaxType->previous_tax_name ;    
                            }

                            $prev_date = '';
                            $prev_receipt = '';
                            $prev_year = '';
                            if(isset($receipt->F56Previuos)){
                                $prev_year = $receipt->F56Previuos->col_receipt_year != '0000' ? $receipt->F56Previuos->col_receipt_year : '';
                                $prev_receipt = $receipt->F56Previuos->col_receipt_no != '0' ? $receipt->F56Previuos->col_receipt_no : '';
                                $prev_date =  new Carbon\Carbon($receipt->F56Previuos->col_receipt_date) ;
                                $prev_date = $receipt->F56Previuos->col_receipt_date != '0000-00-00' ? $prev_date->toFormattedDateString() : '';    
                            }
                        @endphp
                            <div style="height:60px;margin-left: 140px; margin-top: -10px; background: ##b480fc;">
                                <table width="95%" style="margin-top:0px;" class="border-hidden">
                                    <tbody>
                                        <tr>
                                            <td colspan=2 height='25px' class="border-hidden text-center" style="font-size: 12px;" >
                                                <!-- PREVIOUS TAX RECEIPT NO. -->
                                                <small>{{ $tax_type }} </small>
                                                {{ ($prev_receipt)  }} 

                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="28px"  width="100" class="border-hidden text-right"  style="font-size: 12px; background: ##4287f5;  vertical-align: bottom;">
                                            {{  $prev_date }} 
                                        </td>
                                            <td class="border-hidden text-right" style="font-size: 12px; width:2.7cm; background: ##5af542; vertical-align: bottom;">
                                                <!-- FOR THE YEAR -->
                                             {{ $prev_year }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="18%" class="border-hidden" style="background: ##eda6eb;">


                <b class="text-hidden">No. BGT <b> 
                <span style="font-size: 16px;padding:0;margin:0"></span></td>
        </tr>
        <tr>
            @php
                        $date_entry = new Carbon\Carbon($receipt->date_of_entry);
                        $period_cv = $receipt->F56Detailmny;

                        $period_covered = [];
                         foreach ($period_cv as $key => $value){
                            $p = explode('-',$value->period_covered);
                            foreach($p as $key => $pvalue){
                                 if(!in_array($pvalue,$period_covered)){
                                    $period_covered[] = $pvalue;
                                }
                            }
                           
                        }
                        sort($period_covered);
                        $first = reset($period_covered);
                        $last = end($period_covered);

                        $p_calendar_year = $first.' -'.$last; 
                        if($first == $last){
                            $p_calendar_year = $first;
                        }

                       
                        


             @endphp
            <td class="border-hidden text-right" >
                <!-- DATE -->
            <div style="margin-bottom:13px">
                {{ $date_entry->format('F d, Y') }}
            </div>
            </td>
        </tr>
        <tr>
            <td class="border-hidden text-center" height="20" style="background: ##a7e57b; padding-left: -150px;">
                {{$receipt->customer->name}}
            </td>
            <td class="border-hidden" style="padding-left: -30px;">{{ $total_words }} only</td>
            <td class="border-hidden text-right" style="text-indent: 13px;"><br />{{ number_format($form56['total'], 2) }}</td>
        </tr>
        <tr>
            <td colspan=2 class="border-hidden" height="28">
                <table width="100%" class="">
                    <tr>
                        <td width="12%" class="text-hidden">Philippine currency, in</td>
                        <td width="7%" style="background-color: ##bcf758;"><!-- <span style="border:1px solid"></span> -->
                            <!-- <input type="checkbox" style="margin: 0; padding-left: 45px; font-size: 12px; " checked="checked"><br> -->
                            <!-- full<br>
                            installment -->
                        </td>
                        <td width="100%" style="padding-top: 10px; padding-left: 25px;"><span class="text-hidden">payment of REAL PROPERTY TAX upon property(ies) described below for the Calendar Year ></span>{{ $p_calendar_year }} </td>
                    </tr>
                </table>
            </td>
            <td class="border-hidden">
                <!-- <input type="checkbox" style="margin: 0; padding-left: 10px; padding-top: 11px; font-size: 12px;" checked="checked"><br>
                <input type="checkbox" style="margin: 0; padding-left: 10px; font-size: 12px;" checked="checked"> -->
                <!-- BASIC TAX<br>
                SPECIAL EDUCATION FUND -->
            </td>
        </tr>
    </table>
    <table width="100%" style="margin: 4px 55px 0 8px; border-color: #ffffff00; background-color: ##42cbf4; position: absolute; top: 130px;" >
        <tr style="text-align:center;">
            <td class="border-hidden" style="width: 3.3cm;"><span class="text-hidden">Name Of <br>DECLARED OWNER</span></td>
            <td class="border-hidden" style="width: 3.3cm;" ><span class="text-hidden">Location<br>No./Street/Barangay</span></td>
            <td class="border-hidden"><span class="text-hidden">LOT<br>BLOCK NO.</span></td>
            <td class="border-hidden"><span class="text-hidden">TAX<br>DEC. NO</span></td>
            <td class="border-hidden"><span class="text-hidden">Land</span></td>
            <td class="border-hidden"><span class="text-hidden">Improvement</span></td>
            <td class="border-hidden"><span class="text-hidden">Total</span></td>
            <td class="border-hidden"><span class="text-hidden">TAX DUE</span></td>
            <td class="border-hidden"><span class="text-hidden">NO.</span></td>
            <td class="border-hidden"><span class="text-hidden">Payment</span></td>
            <td class="border-hidden"><span class="text-hidden">Full Payment</span></td>
            <td class="border-hidden"><span class="text-hidden">Penalty</span></td>
            <td class="border-hidden"><span class="text-hidden">TOTAL</span></td>
        </tr>
        @php
            $count_tr = 0;
            $period_covered  = '';

        @endphp
        @php 
                $count_tdrp = (count($receipt->F56Detailmny));
                $owner = '';

        @endphp
        <tr style="background: ##ef7385;">
            <td class="border-hidden text-left vertical-top" style="height: 155px; padding-left: 20px;">
            <?php
                // check if owner names are the same..
                $arr = array();
                foreach ($form56['tax_decs'] as $key => $f56x) {
                    array_push($arr, $key);
                }
                if (count($arr) >= 2) {
                for ($i = 1; $i < count($arr); $i++) {
                    if (strcmp($arr[$i], $arr[$i-1]) == 0) {
                        // echo $arr[$i-1].'<br>';
                        $split = explode(" ", $arr[$i-1]);
                        foreach ($split as $s) {
                            if($s != " ") {
                                echo $s.'<br/>';
                            }
                        }
                    } else
                        //echo $arr[$i].'<br>';
                        $split = explode(" ", $arr[$i]);
                        foreach ($split as $s) {
                            if($s != " ") {
                                echo $s.'<br/>';
                            }
                        }
                    }
                } else {
                    //echo $arr[0];
                    $split = explode(" ", $arr[0]);
                    foreach ($split as $s) {
                        if($s != " ") {
                            echo $s.'<br/>';
                        }
                    }
                }
            ?>
            </td>
            <td class="border-hidden text-left vertical-top" style="background: ##ef7385;">
            @php
                // check if locations are the same..
                $arr = array();
                foreach ($form56['tax_decs'] as $key => $f56x) {
                    foreach ($f56x as $key => $f56) {
                        echo $f56['barangay_name']." ".$f56['tax_type']."<br>";
                    }
                }
            @endphp
            </td>

            <td class="border-hidden text-left vertical-top" style="width: 3.3cm; background: ##689cf2;" colspan="2" >
            @php
                // check if locations are the same..
                $arr = array();
                foreach ($form56['tax_decs'] as $key => $f56x) {
                    foreach ($f56x as $key => $f56) {
                        array_push($arr, $f56['tax_dec']);
                    }
                }
                $group_arr = array_unique($arr);
                foreach ($group_arr as $key => $arr) {
                    echo $arr."<br>";
                }
            @endphp
            </td>
            
            <!-- LAND -->
            <td class="border-hidden text-right vertical-top" style="width: 1.3cm; background: ##4cef9b;" >
            <?php
                $arr = array();
                foreach ($form56['tax_decs'] as $key => $f56x) {
                    foreach ($f56x as $key => $f56) {
                        if(!is_null($f56['kind'])) {
                            if(preg_match('/building/i', $f56['kind']) != 1) {
                                // array_push($arr, number_format($f56['tdrp_assedvalue'],2));
                                echo number_format($f56['tdrp_assedvalue'],2)."<br>";
                            } else {
                                echo "<br>";
                            }
                        } else if(!is_null($f56['actual_use'])) {
                            if(preg_match('/bldg/i', $f56['actual_use']) != 1) {
                                // array_push($arr, number_format($f56['tdrp_assedvalue'],2));
                                echo number_format($f56['tdrp_assedvalue'],2)."<br>";
                            } else {
                                echo "<br>";
                            }
                        }
                    }
                }
                // $group_arr = array_unique($arr);
                // foreach ($group_arr as $val) {
                //     echo $val."<br>";
                // }
            ?>
            </td>

            <!-- IMPROVEMENT (BUILDING/MACHINE) -->
            <td class="border-hidden text-right vertical-top" style="width: 1.3cm; background: ##4287f5; padding-right: 5px;" >
            <?php
                $arr = array();
                foreach ($form56['tax_decs'] as $key => $f56x) {
                    foreach ($f56x as $key => $f56) {
                        if(!is_null($f56['kind'])) {
                            if(preg_match('/building/i', $f56['kind']) == 1) {
                                // array_push($arr, number_format($f56['tdrp_assedvalue'],2));
                                echo number_format($f56['tdrp_assedvalue'],2)."<br>";
                            } else {
                                echo "<br>";
                            }
                        } else if(!is_null($f56['actual_use'])) {
                            if(preg_match('/bldg/i', $f56['actual_use']) == 1) {
                                // array_push($arr, number_format($f56['tdrp_assedvalue'],2));
                                echo number_format($f56['tdrp_assedvalue'],2)."<br>";
                            } else {
                                echo "<br>";
                            }
                        }
                    }
                }
            ?>
            </td>

            <td class="border-hidden text-left vertical-top text-right" style="width: 1.5cm; background: ##f276c4; padding-right: -25px;">
                <!-- assessed value TOTAL -->
                <?php
                    $arr = array();
                    $group_arr = array();
                    $limit = (count($form56['yearly']) > 4) ? 3 : count($form56['yearly']);
                    $limit_counter = 0;
                    $limit_end = 0;

                    foreach ($form56['tax_decs'] as $keyx => $f56x) {
                        foreach ($f56x as $key => $f56) {
                            foreach ($form56['yearly'] as $yr => $y) {
                                array_push($arr, number_format($y['assess_val'],2));
                            }
                        }
                    }
          
                    $years = array_keys($form56['yearly']);

                    foreach($years as $year) {
                        $group_arr[$year]['assess_val'] = 0;
                        $group_arr[$year]['penalty'] = 0;
                        $group_arr[$year]['discount'] = 0;
                        if(strlen($year) > 4) {
                            $group_arr[$year]['assess_val'] = $form56['yearly'][$year]['assess_val'];
                            $group_arr[$year]['penalty'] = $form56['yearly'][$year]['penalty'];
                            $group_arr[$year]['discount'] = $form56['yearly'][$year]['discount'];
                            continue;
                        }
                        foreach($annual_arp['yearly'] as $arp => $data) {
                            if(isset($data[$year])) {
                                $group_arr[$year]['assess_val'] += $data[$year]['assess_val'];
                                $group_arr[$year]['penalty'] += $data[$year]['penalty'];
                                $group_arr[$year]['discount'] += $data[$year]['discount'];
                            }
                        }
                    }

                    $group_arrr = (array) $group_arr;
                    $group_arrr_unique = array_unique($group_arrr, SORT_REGULAR);
                    $keys = array_keys($group_arrr_unique);

                    $arr2 = array_unique($arr);
                    $final_group_arr = [];
                    if(count($group_arr) > 0) {
//////////////////// NOTE : CHANGED ALL ARR_UNIQUE TO GROUP_ARR...... ////////////////////
//////////////////// NOTE : CHANGED ALL GROUP_ARR TO FINAL_GROUP_ARR...... ////////////////////
                        for($i = 0; $i < count($keys); $i++) {
                            if(strlen($keys[$i]) == 4) {
                                $final_group_arr[$keys[$i]]['assess_val'] = 0;
                                $final_group_arr[$keys[$i]]['penalty'] = 0;
                                $final_group_arr[$keys[$i]]['discount'] = 0;

                                foreach($group_arrr as $year => $val) {
                                    if(isset($keys[$i+1])) {
                                        if($year == $keys[$i]) {
                                            $final_group_arr[$keys[$i]]['assess_val'] = $val['assess_val'];
                                            $final_group_arr[$keys[$i]]['penalty'] = $val['penalty'];
                                            $final_group_arr[$keys[$i]]['discount'] = $val['discount'];
                                        } else if($year < $keys[$i+1]) {
                                            $final_group_arr[$keys[$i]]['assess_val'] += $val['assess_val'];
                                            $final_group_arr[$keys[$i]]['penalty'] += $val['penalty'];
                                            $final_group_arr[$keys[$i]]['discount'] += $val['discount'];
                                            if($keys[$i] != $keys[$i+1]-1) {
                                                $final_group_arr[$keys[$i]]['to'] = $keys[$i+1]-1;
                                            }
                                        }
                                    } else {
                                        if($year == $keys[$i]) {
                                            $final_group_arr[$keys[$i]]['assess_val'] = $val['assess_val'];
                                            $final_group_arr[$keys[$i]]['penalty'] = $val['penalty'];
                                            $final_group_arr[$keys[$i]]['discount'] = $val['discount'];
                                        }
                                    }
                                }
                            } else {
                                $split = explode('-', $keys[$i]);
                                $diff = ($split[1] - $split[0]) + 1;
                                $final_group_arr[$keys[$i]]['assess_val'] = $group_arr[$keys[$i]]['assess_val'] * $diff;
                                $final_group_arr[$keys[$i]]['penalty'] = $group_arr[$keys[$i]]['penalty'] * $diff;
                                $final_group_arr[$keys[$i]]['discount'] = $group_arr[$keys[$i]]['discount'] * $diff;
                            }
                        }

                        foreach ($form56['yearly'] as $yr => $y) {
                            $breakdown_total = array(
                                'penalty' => 0,
                                'discount' => 0,
                                'partial_value' => 0,
                                'total_value' => 0
                            );
                            if(isset($breakdown[$yr])) {
                                foreach ($breakdown[$yr] as $key => $value) {
                                    $breakdown_total['penalty'] += $value['penalty'];
                                    $breakdown_total['discount'] += $value['discount'];
                                    $breakdown_total['partial_value'] += $value['partial_value'];
                                    $breakdown_total['total_value'] += $value['total_value'];
                                    $breakdown_total['quarter'] = $value['quarter'];
                                    $breakdown_total['assess_val'] = $value['assess_val'];
                                }
                            }

                            $partial_qrtr = '';
                            $total_qrtr = '';
                            if(!empty($final_group_arr)) { 
                                if(isset($breakdown[$yr])) {
                                    if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y')) {
                                        // echo number_format(($breakdown_total['partial_value']*100),2)."<br>";
                                        // echo number_format(($breakdown_total['total_value']*100),2)."<br>";
                                        // echo number_format(($breakdown_total['partial_value']*100),2)."<br>";
                                        // echo number_format(($breakdown_total['total_value']*100),2)."<br>";

                                        echo number_format(($breakdown_total['assess_val']),2)."<br><br>";
                                        // echo number_format(($breakdown_total['assess_val']),2)."<br>";
                                        // echo number_format(($breakdown_total['assess_val']),2)."<br>";
                                        // echo number_format(($breakdown_total['assess_val']),2)."<br>";
                                        $limit_counter++; 
                                        $limit_end = $yr;
                                    } else {
                                        break;
                                    }
                                } else if(isset($final_group_arr[$yr])) { 
                                    if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y')) {
                                        echo number_format($final_group_arr[$yr]['assess_val'],2)."<br><br>";
                                        // echo number_format($final_group_arr[$yr]['assess_val'],2)."<br>";
                                        if(isset($final_group_arr[$yr]['to'])) {
                                            $limit_end = $final_group_arr[$yr]['to'];
                                        } else {
                                            $limit_end = $yr;
                                        }
                                        $limit_counter++;
                                    } elseif($limit == 1 && $limit_counter < $limit && $y['period_covered'] >= \Carbon\Carbon::now()->addYear()->format('Y')) {
                                        // set limit when limit is only 1 and is an advanced payment OR
                                        echo number_format($final_group_arr[$yr]['assess_val'],2)."<br><br>";
                                        // echo number_format($final_group_arr[$yr]['assess_val'],2)."<br>";
                                        $limit_counter++;
                                        $limit_end = $yr;
                                    }
                                } else {
                                    if(strlen($yr) > 4) {
                                        echo number_format($y['assess_val'],2)."<br><br>";
                                        // echo number_format($y['assess_val'],2)."<br>";
                                        if(isset($final_group_arr[$yr]['to'])) {
                                            $limit_end = $final_group_arr[$yr]['to'];
                                        } else {
                                            $limit_end = $yr;
                                        }
                                        $limit_counter++;
                                    }
                                }
                            } else {
                                if(!empty($breakdown[$yr])) {
                                    foreach ($breakdown[$yr] as $key => $value) {
                                        if(!empty($breakdown[$yr])) {
                                            foreach ($breakdown[$yr] as $key => $value) {
                                                foreach ($arr2 as $a) {
                                                    if($limit_counter <= $limit) {
                                                        echo $a."<br><br><br>";
                                                        // echo $a."<br><br>";
                                                        $limit_counter++;
                                                        $limit_end = $k;
                                                    } else {
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } else{
                                    foreach ($arr2 as $a) {
                                        if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y')) {
                                            echo $a."<br><br>";
                                            // echo $a."<br>";
                                            $limit_counter++;
                                            $limit_end = $k;
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        foreach ($form56['yearly'] as $yr => $y) {
                            foreach ($arr2 as $a) {
                                if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y')) {
                                    echo $a."<br><br>";
                                    // echo $a."<br>";
                                    $limit_counter++;
                                    $limit_end = $yr;
                                }
                            }
                        }
                    }
                    // dd($group_arr_total);
                ?>
            </td>

            <td class="border-hidden text-left vertical-top" style="width: 3cm; background: ##a276c4; position: relative; padding-left: 25px;">
                <div style="margin: 0; padding: 0; text-align: right;"> 
                <?php
                    $arr = array();
                    $limit = (count($form56['yearly']) > 3) ? 2 : count($form56['yearly']);
                    $limit_counter = 0;
                    $limit_end = 0;

                    foreach ($form56['tax_decs'] as $keyx => $f56x) {
                        foreach ($f56x as $key => $f56) {
                            foreach ($form56['yearly'] as $yr => $y) {
                                array_push($arr, number_format($y['assess_val'],2));
                            }
                        }
                    }                    

                    $arr2 = array_unique($arr);
                    if(count($final_group_arr) > 0) {
                        $ii = key($final_group_arr);
                        $end_year = array_keys($form56['yearly'])[count($form56['yearly'])-1];
                        foreach ($form56['yearly'] as $yr => $y) {
                            $breakdown_total = array(
                                'penalty' => 0,
                                'discount' => 0,
                                'partial_value' => 0,
                                'total_value' => 0,
                                'assess_val' => 0
                            );
                            if(isset($breakdown[$yr])) {
                                foreach ($breakdown[$yr] as $key => $value) {
                                    // dd($value);
                                    $breakdown_total['penalty'] += $value['penalty'];
                                    $breakdown_total['discount'] += $value['discount'];
                                    $breakdown_total['partial_value'] += $value['partial_value'];
                                    $breakdown_total['total_value'] += $value['total_value'];
                                    $breakdown_total['quarter'] = $value['quarter'];
                                    $breakdown_total['assess_val'] = $value['assess_val'];
                                }
                            }
                            
                            $partial_qrtr = '';
                            $total_qrtr = '';

                            if(!empty($final_group_arr)) {
                                if(isset($breakdown[$yr])) {
                                        switch ($breakdown_total['quarter']) {
                                            case "1": 
                                                $partial_qrtr = '1st';
                                                $total_qrtr = '2nd-4th';
                                                break;
                                            case "2":
                                                $partial_qrtr = '1st';
                                                $total_qrtr = '2nd-4th';
                                                break;
                                            case "3":
                                                $partial_qrtr = '1st-2nd';
                                                $total_qrtr = '3rd-4th';
                                                break;
                                            default:
                                                $partial_qrtr = 'full';
                                                $total_qrtr = 'full';
                                                break;
                                        } 
                                        if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y')) {
                                            // echo number_format(($breakdown_total['partial_value']),2)."<span>&nbsp;&nbsp;&nbsp;</span>".$partial_qrtr."<br>(".$yr.")<span style='width: 30px; height: 3px; display: inline-block;'></span>qtr<br>";
                                            // echo number_format(($breakdown_total['total_value']),2)."<span>&nbsp;&nbsp;&nbsp;</span>".$total_qrtr."<br>(".$yr.")<span style='width: 30px; height: 3px; display: inline-block;'></span>qtr<br>";
                                            
                                            echo number_format(($breakdown_total['assess_val']*.01),2)."<span>&nbsp;&nbsp;&nbsp;</span>".$partial_qrtr."<br>(".$yr.")<span style='width: 30px; height: 3px; display: inline-block;'></span>qtr<br>";
                                            // echo number_format(($breakdown_total['assess_val']*.01),2)."<span>&nbsp;&nbsp;&nbsp;</span>".$total_qrtr."<br>(".$yr.")<span style='width: 30px; height: 3px; display: inline-block;'></span>qtr<br>";
                                            $limit_counter++;
                                            $limit_end = $yr;
                                        } else {
                                            break;
                                        }
                                } else if(isset($final_group_arr[$yr])) {
                                    if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y')) {
                                        if(isset($final_group_arr[$yr]['to'])) {
                                            $arp = isset($final_group_arr[$yr]['arp']) ? explode("-", $final_group_arr[$yr]['arp']) : '';
                                            // $taxdue = $arp_total != '' ? ($arp[0] < 94 ? $group_arr_total[$yr]['sef'] : $group_arr_total[$yr]['assess_val']*.01) : $group_arr_total[$yr]['assess_val']*.01;
                                            $taxdue = $arp != '' ? ($arp[0] < 94 ? $final_group_arr[$yr]['assess_val'] : $final_group_arr[$yr]['assess_val']*.01) : $final_group_arr[$yr]['assess_val']*.01;
                                            echo number_format($taxdue, 2)."<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(".$yr."-".$final_group_arr[$yr]['to'].")<br>";
                                        } else {
                                            $arp = isset($final_group_arr[$yr]['arp']) ? explode("-", $final_group_arr[$yr]['arp']) : '';
                                            $taxdue = $arp != '' ? ($arp[0] < 94 ? $final_group_arr[$yr]['assess_val'] : ($final_group_arr[$yr]['assess_val']*.01)) : $final_group_arr[$yr]['assess_val']*.01;
                                            echo number_format($final_group_arr[$yr]['assess_val'], 2)."<span>&nbsp;&nbsp;</span>full<br>(".$yr.")<span style='width: 35px; height: 3px; display: inline-block;'></span><br>";
                                        }
                                        $limit_counter++;
                                        if(isset($final_group_arr[$yr]['to'])) {
                                            $limit_end = $final_group_arr[$yr]['to'];
                                        } else {
                                            $limit_end = $yr;
                                        }
                                    } elseif($limit == 1 && $limit_counter < $limit && $y['period_covered'] >= \Carbon\Carbon::now()->addYear()->format('Y')) {
                                        if(isset($final_group_arr[$yr]['to'])) {
                                            $arp = isset($final_group_arr[$yr]['arp']) ? explode("-", $final_group_arr[$yr]['arp']) : '';
                                            // $taxdue = $arp != '' ? ($arp[0] < 94 ? $group_arr_total[$yr]['sef'] : $group_arr_total[$yr]['assess_val']*.01) : $group_arr_total[$yr]['assess_val']*.01;
                                            $taxdue = $arp != '' ? ($arp[0] < 94 ? $final_group_arr[$yr]['assess_val'] : $final_group_arr[$yr]['assess_val']*.01) : $final_group_arr[$yr]['assess_val']*.01;
                                            echo number_format($taxdue, 2)."<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(".$yr."-".$final_group_arr[$yr]['to'].")<br>";
                                        } else {
                                            $arp = isset($final_group_arr[$yr]['arp']) ? explode("-", $final_group_arr[$yr]['arp']) : '';
                                            // $taxdue = $arp != '' ? ($arp[0] < 94 ? $group_arr_total[$yr]['sef'] : ($group_arr_total[$yr]['assess_val']*.01)) : $group_arr_total[$yr]['assess_val']*.01;
                                            $taxdue = $arp != '' ? ($arp[0] < 94 ? $final_group_arr[$yr]['assess_val'] : ($final_group_arr[$yr]['assess_val']*.01)) : $final_group_arr[$yr]['assess_val']*.01;
                                            // echo number_format($group_arr_total[$yr]['sef'], 2)."<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(".$yr.")<span style='width: 45px; height: 3px; display: inline-block;'></span><br>";
                                            echo number_format($final_group_arr[$yr]['assess_val'], 2)."<span>&nbsp;&nbsp;&nbsp;</span>full<br>(".$yr.")<span style='width: 35px; height: 3px; display: inline-block;'></span><br>";
                                        }
                                        $limit_counter++;
                                        if(isset($final_group_arr[$yr]['to'])) {
                                            $limit_end = $final_group_arr[$yr]['to'];
                                        } else {
                                            $limit_end = $yr;
                                        }
                                    }
                                } else {
                                    if(strlen($yr) > 4) {
                                        $arp = isset($final_group_arr[$yr]) ? explode("-", $final_group_arr[$yr]['arp']) : '';
                                        $taxdue = $arp != '' ? ($arp[0] >= 94 ? $y['assess_val']*.01 : $y['sef']) : $y['assess_val']*.01;
                                        echo number_format($taxdue, 2)."<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(".$yr.")<span style='width: 
                                        40px; height: 3px; display: inline-block;'></span><br>";
                                        $limit_counter++;
                                        $limit_end = $yr;
                                    }
                                }
                            } else {
                                if(!empty($breakdown[$yr])) {
                                    foreach ($breakdown[$yr] as $key => $value) {
                                        if(!empty($breakdown[$yr])) {
                                            foreach ($breakdown[$yr] as $key => $value) {
                                                foreach ($arr2 as $a) {
                                                    if($limit_counter <= $limit) {
                                                        echo $a."<br><br><br>";
                                                        $limit_counter++;
                                                        $limit_end = $k;
                                                    } else {
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } else{
                                    foreach ($arr2 as $a) {
                                        if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y')) {
                                            echo $a."<br>";
                                            $limit_counter++;
                                            $limit_end = $k;
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        foreach ($form56['yearly'] as $yr => $y) {
                            foreach ($arr2 as $a) {
                                if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y')) {
                                    echo $a."<br>";
                                    $limit_counter++;
                                    $limit_end = $yr;
                                }
                            }
                        }
                    }
                ?>
                </div>
            </td>

            <td class="border-hidden text-left vertical-top" style="width: 1.7cm; background: ##cde25f; text-align: center;">
            <?php $limit_counter = 0; ?>
            @foreach($form56['yearly'] as $k => $y)
                @if(!empty($final_group_arr))
                    @if(isset($breakdown[$k]))
                            @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                                @if($k < $limit_end)
                                    BASIC<br />
                                    SEF<br />
                                    BASIC<br />
                                    SEF<br />
                                @else
                                    BASIC<br />
                                    SEF<br />
                                    BASIC<br />
                                    SEF<br />
                                @endif
                                <?php 
                                    $limit_counter++; 
                                ?>
                            @else
                                <?php break; ?>
                            @endif
                    @elseif(isset($final_group_arr[$k]))
                            @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                                BASIC<br />
                                SEF<br />
                                <?php 
                                    $limit_counter++;                                     
                                ?>
                            @elseif($limit == 1 && $limit_counter < $limit && $y['period_covered'] >= \Carbon\Carbon::now()->addYear()->format('Y'))
                                BASIC<br />
                                SEF<br />
                                <?php 
                                    $limit_counter++;                                     
                                ?>
                            @else
                                <?php break; ?>
                            @endif
                    @elseif(!isset($breakdown[$k]) && !isset($final_group_arr[$k]))
                        @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                            BASIC<br />
                            SEF<br />
                            <?php 
                                $limit_counter++; 
                            ?>
                        @else
                            <?php break; ?>
                        @endif
                    @endif
                @else
                    @if(!empty($breakdown[$k]))
                        @foreach($breakdown[$k] as $key => $bkd)
                            @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                                BASIC<br />
                                SEF<br />
                                BASIC<br />
                                SEF<br />
                                <?php 
                                    $limit_counter++; 
                                ?>
                            @else
                                <?php break; ?>
                            @endif
                        @endforeach
                    @else
                        @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                            BASIC<br />
                            SEF<br />
                            <?php 
                                $limit_counter++; 
                            ?>
                        @else
                            <?php break; ?>
                        @endif
                    @endif
                @endif
            @endforeach
            </td>

            <td class="border-hidden text-right vertical-top" style="width: 1.1cm; background: ##e8aa4e; padding-right: 8px;" >
            @php
                $partial_total = 0;
                $total_total = 0;
                $partial_total_compute = 0;
            @endphp
            @foreach($form56['yearly'] as $k => $y)
                @if(!empty($breakdown[$k]))
                    @foreach($breakdown[$k] as $key => $bkd)
                        @php
                            $partial_total += $breakdown[$k][$key]['partial_value'];
                            $total_total += $breakdown[$k][$key]['total_value'];
                        @endphp
                    @endforeach
                @else
                    <?php
                        // if(strlen($k) == 4) {
                        //     $group_arr[$k] = [];
                        //     $group_arr[$k]['assess_val'] = $y['assess_val'];
                        //     $group_arr[$k]['penalty'] = $y['penalty'];
                        // }
                    ?>
                @endif
            @endforeach
            <?php
                // tax breakdown 
                // $arr_unique = array_unique($group_arr, SORT_REGULAR);
                $ii = key($group_arr);
                $end_year = array_keys($form56['yearly'])[count($form56['yearly'])-1];
                // foreach ($group_arr as $key => $value) {
                //     $group_arr[$key]['sef'] = $form56['yearly'][$key]['sef'];
                //     if(strlen($key) == 4 && strlen($ii) == 4) {
                //         $key_next = $key+1;
                //         while ($key_next < $end_year) { 
                //             if(in_array($key_next, array_keys($group_arr))) {
                //                 break;
                //             } else {
                //                 $key_next++;
                //             }
                //         }  
                //         while($ii < $key_next) {
                //             if($ii < $key_next && $ii > $key) {
                //                 if($ii == $key) {
                //                     $group_arr[$key]['assess_val'] += floatval($form56['yearly'][$ii]['assess_val']);
                //                     $group_arr[$key]['penalty'] += floatval($form56['yearly'][$ii]['penalty']);
                //                     $group_arr[$key]['to'] = $ii;
                //                 }
                //             } 
                //             $ii++;
                //         }
                //     }
                // }
                
            ?>
            <?php $limit_counter = 0; ?>
            @foreach($form56['yearly'] as $k => $y)
                    @if(!empty($group_arr))
                        @if(isset($breakdown[$k]))
                            @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                                {{ number_format($partial_total, 2) }}<br>
                                {{ number_format($partial_total, 2) }}<br>
                                {{ number_format($total_total, 2) }}<br>
                                {{ number_format($total_total, 2) }}<br>
                                <?php 
                                    $limit_counter++; 
                                ?>
                            @else
                                <?php break; ?>
                            @endif
                        @elseif(isset($final_group_arr[$k]))
                            @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                                @if(strlen($k) > 4)
                                    <?php
                                        $split_year = explode('-', $k);
                                        $range = intval(intval($split_year[1]) - intval($split_year[0])) + 1;
                                    ?>
                                    {{ number_format(isset($range) ? ((intval($y['assess_val'])/100)*intval($range)) : ($y['assess_val']/100), 2) }}
                                    <br>
                                    {{ number_format(isset($range) ? ((intval($y['assess_val'])/100)*intval($range)) : (intval($y['assess_val'])/100), 2) }}
                                    <br>
                                    <?php 
                                        $limit_counter++; 
                                    ?>
                                @else
                                    {{-- number_format(($group_arr_total[$k]['sef']),2) --}}
                                    {{ number_format(($final_group_arr[$k]['assess_val'] * .01),2) }}
                                    <br/>
                                    {{-- number_format(($group_arr_total[$k]['sef']),2) --}}
                                    {{ number_format(($final_group_arr[$k]['assess_val'] * .01),2) }}
                                    <br/>
                                    <?php 
                                        $limit_counter++; 
                                    ?>
                                @endif
                            @elseif($limit == 1 && $limit_counter < $limit && $y['period_covered'] >= \Carbon\Carbon::now()->addYear()->format('Y'))
                                @if(strlen($k) > 4)
                                    <?php
                                        $split_year = explode('-', $k);
                                        $range = ($split_year[1] - $split_year[0]) + 1;
                                    ?>
                                    {{ number_format(isset($range) ? (($y['assess_val']/100)*$range) : ($y['assess_val']/100), 2) }}
                                    <br>
                                    {{ number_format(isset($range) ? (($y['assess_val']/100)*$range) : ($y['assess_val']/100), 2) }}
                                    <br>
                                    <?php 
                                        $limit_counter++; 
                                    ?>
                                @else
                                    {{-- number_format(($group_arr_total[$k]['sef']),2) --}}
                                    {{ number_format(($final_group_arr[$k]['assess_val'] *.01),2) }}
                                    <br/>
                                    {{-- number_format(($group_arr_total[$k]['sef']),2) --}}
                                    {{ number_format(($final_group_arr[$k]['assess_val'] *.01),2) }}
                                    <br/>
                                    <?php 
                                        $limit_counter++; 
                                    ?>
                                @endif
                            @else
                                <?php break; ?>
                            @endif
                        @else
                            @if(strlen($k) > 4)
                                <?php
                                    $split_year = explode('-', $k);
                                    $range = ($split_year[1] - $split_year[0]) + 1;
                                ?>
                                {{ number_format(isset($range) ? (($y['assess_val']/100)*$range) : ($y['assess_val']/100), 2) }}<br>
                                {{ number_format(isset($range) ? (($y['assess_val']/100)*$range) : ($y['assess_val']/100), 2) }}<br>
                                <?php 
                                    $limit_counter++; 
                                ?>
                            @endif
                        @endif
                    @else
                        @if(!empty($breakdown[$k]))
                            @foreach($breakdown[$k] as $key => $bkd)
                                @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                                    {{ number_format($partial_total, 2) }}<br>
                                    {{ number_format($partial_total, 2) }}<br>
                                    {{ number_format($total_total, 2) }}<br>
                                    {{ number_format($total_total, 2) }}<br>
                                    <?php 
                                        $limit_counter++; 
                                    ?>
                                @else
                                    <?php break; ?>
                                @endif
                            @endforeach
                        @else
                            @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                                {{ number_format($y['sef'],2) }}<br />
                                {{ number_format($y['sef'],2) }}<br />
                                <!-- {{-- number_format(($y['assess_val']/100 + $y['penalty']),2) --}}<br />
                                {{-- number_format(($y['assess_val']/100 + $y['penalty']),2) --}}<br /> -->
                                <?php 
                                    $limit_counter++; 
                                ?>
                            @else
                                <?php break; ?>
                            @endif
                        @endif
                    @endif
            @endforeach
            </td>

            <td class="border-hidden text-right vertical-top" style="width: 1cm; background: ##e56b60; padding-right: -20px;">
            @php
                $yrTotalPenalty = 0;
                $yrTotalDiscount = 0;
                //$group_arr = [];
            @endphp
            @foreach($form56['yearly'] as $k => $y)         
                @if(!empty($breakdown[$k]))
                    @foreach($breakdown[$k] as $key => $bkd)
                        @if(!empty($bkd))
                            @php
                                $yrTotalPenalty += $bkd['penalty'];
                                $yrTotalDiscount += $bkd['discount'];
                                // $yrTotalPenalty += $y['penalty'];
                                // $yrTotalDiscount += $y['discount'];
                            @endphp
                        @else

                        @endif 
                    @endforeach
                @else
                    <?php
                        // $group_arr[$k] = [];
                        // $group_arr[$k]['assess_val'] = $y['assess_val'];
                        // $group_arr[$k]['penalty'] = $y['penalty'];
                        // $group_arr[$k]['discount'] = $y['discount'];
                    ?>
                @endif
            @endforeach
            <?php
                // $arr_unique = array_unique($group_arr, SORT_REGULAR);
                $ii = key($group_arr);
                $end_year = array_keys($form56['yearly'])[count($form56['yearly'])-1];
                // foreach ($group_arr as $key => $value) {
                //     $group_arr[$key]['sef'] = $form56['yearly'][$key]['sef'];
                //     if(strlen($key) == 4 && strlen($ii) == 4) {
                //         $key_next = $key+1;
                //         while ($key_next < $end_year) { 
                //             if(in_array($key_next, array_keys($group_arr))) {
                //                 break;
                //             } else {
                //                 $key_next++;
                //             }
                //         }  
                //         while($ii < $key_next) {
                //             if($ii < $key_next && $ii > $key) {
                //                 $group_arr[$key]['assess_val'] += floatval($form56['yearly'][$ii]['assess_val']);
                //                 $group_arr[$key]['penalty'] += floatval($form56['yearly'][$ii]['penalty']);
                //                 $group_arr[$key]['to'] = $ii;
                //             } 
                //             $ii++;
                //         }
                //     }
                // }
            ?>
            <?php $limit_counter = 0; ?>
            @foreach($form56['yearly'] as $k => $y)
                @if(!empty($final_group_arr))
                    @if(isset($breakdown[$k]))
                        @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                            {{ $yrTotalPenalty == 0 ? '' : number_format($yrTotalPenalty,2) }}
                            {{ $yrTotalDiscount == 0 ? '' : '('.number_format($yrTotalDiscount,2).')' }}
                            <br>
                            {{ $yrTotalPenalty == 0 ? '' : number_format($yrTotalPenalty,2) }}
                            {{ $yrTotalDiscount == 0 ? '' : '('.number_format($yrTotalDiscount,2).')' }}
                            <br>
                            <br>
                            <br>
                            <?php 
                                $limit_counter++; 
                            ?>
                        @else
                            <?php break; ?>
                        @endif
                    @elseif(isset($final_group_arr[$k]))
                        @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                            {{ $final_group_arr[$k]['penalty'] == 0 ? '' : number_format($final_group_arr[$k]['penalty'],2) }}  
                            {{ $final_group_arr[$k]['discount'] == 0 ? '' : '('.number_format($final_group_arr[$k]['discount'],2).')' }} 
                            <br />
                            {{ $final_group_arr[$k]['penalty'] == 0 ? '' : number_format($final_group_arr[$k]['penalty'],2) }}
                            {{ $final_group_arr[$k]['discount'] == 0 ? '' : '('.number_format($final_group_arr[$k]['discount'],2).')' }} 
                            <br />
                            <?php 
                                $limit_counter++; 
                            ?>
                        @elseif($limit == 1 && $limit_counter < $limit && $y['period_covered'] >= \Carbon\Carbon::now()->addYear()->format('Y'))
                            {{ $final_group_arr[$k]['penalty'] == 0 ? '' : number_format($final_group_arr[$k]['penalty'],2) }}  
                            {{ $final_group_arr[$k]['discount'] == 0 ? '' : '('.number_format($final_group_arr[$k]['discount'],2).')' }} 
                            <br />
                            {{ $final_group_arr[$k]['penalty'] == 0 ? '' : number_format($final_group_arr[$k]['penalty'],2) }}
                            {{ $final_group_arr[$k]['discount'] == 0 ? '' : '('.number_format($final_group_arr[$k]['discount'],2).')' }} 
                            <br />
                            <?php 
                                $limit_counter++; 
                            ?>
                        @else
                            <?php break; ?>
                        @endif
                    @endif
                @else
                    @if(!empty($breakdown[$k]))
                        @foreach($breakdown[$k] as $key => $bkd)
                            @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                                {{ $yrTotalPenalty == 0 || $yrTotalDiscount != 0 ? '' : number_format($yrTotalPenalty,2) }}
                                {{ $yrTotalDiscount == 0 ? '' : '('.number_format($yrTotalDiscount,2).')' }}
                                <br>
                                {{ $yrTotalPenalty == 0 || $yrTotalDiscount != 0 ? '' : number_format($yrTotalPenalty,2) }}
                                {{ $yrTotalDiscount == 0 ? '' : '('.number_format($yrTotalDiscount,2).')' }}
                                <br>
                                <br>
                                <br>
                                <?php 
                                    $limit_counter++; 
                                ?>
                            @else
                                <?php break; ?>
                            @endif
                        @endforeach
                    @else
                        @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                            {{ $y['penalty'] == 0 ? '' : number_format($y['penalty'],2) }}  
                            {{ $y['discount'] == 0 ? '' : '('.number_format($y['discount'],2).')' }} 
                            <br />
                            {{ $y['penalty'] == 0 ? '' : $y['penalty'] }}
                            {{ $y['discount'] == 0 ? '' : '('.number_format($y['discount'],2).')' }} 
                            <br />
                            <?php 
                                $limit_counter++; 
                            ?>
                        @else
                            <?php break; ?>
                        @endif
                    @endif
                @endif
            @endforeach
            </td>

            <td class="border-hidden text-right vertical-top" style="width: 2.1cm; background: ##7fe83e; padding-left: 10px;">
            @php
                $finalPartialTotal = 0;
                $finalTotalTotal = 0;
                $limit_total = 0;
            @endphp
            @foreach($form56['yearly'] as $k => $y)      
                @if(!empty($breakdown[$k]))
                    @foreach($breakdown[$k] as $key => $bkd)
                        <?php
                            $total_penalty = $breakdown[$k][$key]['partial_value'] + $breakdown[$k][$key]['penalty'];
                            $total_discount = $breakdown[$k][$key]['partial_value'] - $breakdown[$k][$key]['discount'];

                            $finalPartialTotal += $breakdown[$k][$key]['discount'] != 0 ? $total_discount : $total_penalty;
                            $finalTotalTotal += $breakdown[$k][$key]['total_value'];
                            
                            // working dati
                            /*foreach($form56['tax_decs'] as $tax) {
                                foreach($tax as $t) {
                                    $partial_total = $t['tax_due']/4;
                                    $total_total = $t['tax_due'] - number_format($partial_total, 2);

                                    $total_penalty = $partial_total + $breakdown[$k][$key]['penalty'];
                                    $total_discount = $partial_total - $breakdown[$k][$key]['discount'];

                                    $finalPartialTotal += $breakdown[$k][$key]['discount'] != 0 ? $total_discount : $total_penalty;
                                    $finalTotalTotal += $breakdown[$k][$key]['discount'] != 0 ? $total_total - $breakdown[$k][$key]['discount'] : ($breakdown[$k][$key]['penalty'] != 0 ? $total_total + $breakdown[$k][$key]['penalty'] : $total_total);
                                }
                            }*/
                        ?>
                    @endforeach
                @endif
            @endforeach
            <?php $limit_counter = 0; ?>
            @foreach($form56['yearly'] as $k => $y)
                <!--{{--@if(!empty($breakdown[$k])) 
                    {{ number_format($finalPartialTotal, 2) }}<br>
                    {{ number_format($finalPartialTotal, 2) }}<br>
                    {{ number_format($finalTotalTotal, 2) }}<br>
                    {{ number_format($finalTotalTotal, 2) }}<br>
                @else
                    {{ number_format($y['total'],2) }}<br />
                    {{ number_format($y['total'],2) }}<br />
                @endif--}}-->
            @if(!empty($final_group_arr))
                @if(isset($breakdown[$k])) 
                    @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                        {{ number_format($finalPartialTotal, 2) }}<br>
                        {{ number_format($finalPartialTotal, 2) }}<br>
                        {{ number_format($finalTotalTotal, 2) }}<br>
                        {{ number_format($finalTotalTotal, 2) }}<br> 
                        <?php 
                            $limit_counter++; 
                            $limit_total += ($finalPartialTotal)*2 + ($finalTotalTotal)*2; 
                        ?>
                    @else
                        <?php break; ?> 
                    @endif
                @elseif(isset($final_group_arr[$k]))
                    @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                        @if(strlen($k) > 4)
                            <?php
                                $split_string = explode('-', $k);
                                $range = $split_string[1] - $split_string[0] + 1;
                                $limit_counter++; 
                                $limit_total += (($y['assess_val']/100)*$range+$y['penalty'])*2; 
                            ?>
                            <!-- {{-- number_format(($y['assess_val']/100)*$range+$y['penalty'], 2) --}}<br>
                            {{-- number_format(($y['assess_val']/100)*$range+$y['penalty'], 2) --}}<br> -->
                            <!-- {{-- number_format(($y['assess_val']/100 + $y['penalty']/100), 2) --}}<br>
                            {{-- number_format(($y['assess_val']/100 + $y['penalty']/100), 2) --}}<br>  -->
                            {{ number_format($y['sef'] + $y['penalty'], 2) }}<br>
                            {{ number_format($y['sef'] + $y['penalty'], 2) }}<br>              
                        @else
                            {{-- number_format((($group_arr[$k]['sef']) + $group_arr[$k]['penalty'] - $group_arr[$k]['discount']),2) --}}
                            {{ number_format((($final_group_arr[$k]['assess_val'] * .01) + $final_group_arr[$k]['penalty'] - $final_group_arr[$k]['discount']),2) }}
                            <br />
                            {{-- number_format((($group_arr[$k]['sef']) + $group_arr[$k]['penalty'] - $group_arr[$k]['discount']),2) --}}
                            {{ number_format((($final_group_arr[$k]['assess_val'] * .01) + $final_group_arr[$k]['penalty'] - $final_group_arr[$k]['discount']),2) }}
                            <br />
                            <?php 
                                $limit_counter++;  
                                // $limit_total += (($group_arr[$k]['sef']) + $group_arr[$k]['penalty'] - $group_arr[$k]['discount'])*2;
                                $limit_total += (($final_group_arr[$k]['assess_val'] * .01) + $final_group_arr[$k]['penalty'] - $final_group_arr[$k]['discount'])*2; 
                            ?>
                        @endif
                    @elseif($limit == 1 && $limit_counter < $limit && $y['period_covered'] >= \Carbon\Carbon::now()->addYear()->format('Y'))
                        @if(strlen($k) > 4)
                            <?php
                                $split_string = explode('-', $k);
                                $range = $split_string[1] - $split_string[0] + 1;
                                $limit_counter++; 
                                $limit_total += (($y['assess_val']*.01)*$range+$y['penalty'])*2; 
                            ?>
                            {{ number_format($y['sef'] + $y['penalty'], 2) }}<br>
                            {{ number_format($y['sef'] + $y['penalty'], 2) }}<br>              
                        @else
                            {{-- number_format((($group_arr[$k]['sef']) + $group_arr[$k]['penalty'] - $group_arr[$k]['discount']),2) --}}
                            {{ number_format((($final_group_arr[$k]['assess_val'] * .01) + $final_group_arr[$k]['penalty'] - $final_group_arr[$k]['discount']),2) }}
                            <br />
                            {{-- number_format((($group_arr[$k]['sef']) + $group_arr[$k]['penalty'] - $group_arr[$k]['discount']),2) --}}
                            {{ number_format((($final_group_arr[$k]['assess_val'] * .01) + $final_group_arr[$k]['penalty'] - $final_group_arr[$k]['discount']),2) }}
                            <br />
                            <?php 
                                $limit_counter++;  
                                // $limit_total += (($group_arr[$k]['sef']) + $group_arr[$k]['penalty'] - $group_arr[$k]['discount'])*2; 
                                $limit_total += (($final_group_arr[$k]['assess_val'] * .01) + $final_group_arr[$k]['penalty'] - $final_group_arr[$k]['discount'])*2; 
                            ?>
                        @endif
                    @else
                        <?php break; ?>
                    @endif
                @endif
            @else
                @if(!empty($breakdown[$k])) 
                    @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                        {{ number_format($finalPartialTotal, 2) }}<br>
                        {{ number_format($finalPartialTotal, 2) }}<br>
                        {{ number_format($finalTotalTotal, 2) }}<br>
                        {{ number_format($finalTotalTotal, 2) }}<br>
                        <?php 
                            $limit_counter++; 
                            $limit_total += ($finalPartialTotal)*2 + ($finalTotalTotal)*2; 
                        ?>
                    @else
                        <?php break; ?>
                    @endif
                @else
                    @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                        {{ number_format($y['total'],2) }}<br />
                        {{ number_format($y['total'],2) }}<br />
                        <?php 
                            $limit_counter++; 
                            $limit_total += ($y['total'])*2;  
                        ?>
                    @else
                        <?php break; ?>
                    @endif
                @endif
            @endif
            @endforeach
        </td>
    </tr>   
        <tr class="">
            <td colspan=5 rowspan="2"  style="border:0px #ffffff00" >
                <table width="100%">
                    <tr>
                        <td class="text-hidden">
                            <div style="width:80%">
                                <!-- Payment without pernalty may be made within the periods stated below is by installment -->
                            </div>
                            <table width="90%" style="margin-top: 5px">
                                <tr>
                                    <td width="30%">1st Inst.</td>
                                    <td width="20%">_</td>
                                    <td width="50%"><!-- Jan 1. to Mar. 31 --></td>
                                </tr>
                                <tr>
                                    <td>2nd Inst.</td>
                                    <td>_</td>
                                    <td><!-- Apr. 1 to Jun. 30 --></td>
                                </tr>
                            </table>
                        </td>
                        <td width="60%" class="">
                            <table width="100%" >
                                <tr>
                                    <td colspan="2" class="text-hidden" ><!-- MODE OF PAYMENT --></td>
                                </tr>
                                <tr>
                                    <td width="70%" height="15px" class="text-hidden">CASH</td>
                                    <td style="padding-top: -10px;">{{ number_format($form56['total'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td height="15px" class="text-hidden">CHECK</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td height="15px" class="text-hidden">TW/PMO</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td height="15px" class="text-hidden" >TOTAL</td>
                                    <td style="padding-top: -15px;"> {{ number_format($form56['total'], 2) }}</td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            @if($limit_counter > $limit && $limit_end <= \Carbon\Carbon::now()->format('Y'))
                <td colspan="7" class="border-hidden text-right" style="background-color: ##7fe83e; padding-top: -38px;"><span class="text-hidden">TOTAL ></span> {{ number_format($limit_total, 2) }}</td> 
            @else
                <td colspan="7" class="border-hidden text-right" style="background-color: ##7fe83e; padding-top: -38px;"><span class="text-hidden">TOTAL ></span> {{ number_format($form56['total'], 2) }}</td><!-- padding top -50 ok -->
            @endif
        </tr>
        <tr>
            <td colspan="3" class="border-hidden" style="padding-top: -15px;">
                <div style="text-align: center; ">
                    {{$sign ? $acctble_officer_name->value : ''}}
                    <BR>
                    {{$sign ? $acctble_officer_position->value : ''}}
                </div>
            </td>
            <td colspan="3" class="border-hidden" style="padding-top: -15px;">
                <div style="text-align: center; ">
                    <!-- IMELDA I. MACANES -->
                    {{$sign ? 'IMELDA I. MACANES ' : ''}}
                    <BR>
                    <!-- PROVINCIAL TREASURER -->
                    {{$sign ? 'PROVINCIAL TREASURER ' : ''}}
                </div>
            </td>
        </tr>
    </table>
<!-- page break, new OR -->
    {{-- @if(((count($form56['yearly']) > 3 && isset($limit_end)) || $y['period_covered'] > \Carbon\Carbon::now()->format('Y')) && $limit > 1) --}}
    @if(((count($final_group_arr) > 3 && isset($limit_end)) || $y['period_covered'] > \Carbon\Carbon::now()->format('Y')) && $limit > 1)
    <div style="page-break-after: always;"></div>
    <table width="100%" class="border-hidden" style="margin: 0 ; background: ##dbba7d; position: absolute; top: -15px;">
        <tr>
            <td colspan=2 rowspan=2 height='15%' style="padding: 0; margin: 0; background: ##a7e57b;">
                <table width="100%" class="border-hidden" style="padding: 0; margin: 0;">
                    <tr >
                        <td style="margin:0" width="15%" ></td>
                        <td style="text-align: center;" width="50%">
                            @if($wmunicipality)
                                <b>{{strtoupper($receipt->municipality->name)}}, BENGUET</b>
                            @endif
                        </td>
                        <td style="padding: 0; margin: 0;">

                        @php
                            $tax_type = '';
                        
                            if(isset($receipt->F56Detail->col_f56_previous_receipt)){
                               $tax_type = $receipt->F56Detail->TDARPX->previousTaxType->previous_tax_name ;    
                            }

                            $prev_date = '';
                            $prev_receipt = '';
                            $prev_year = '';
                            if(isset($receipt->F56Previuos)){
                                $prev_year = $receipt->F56Previuos->col_receipt_year != '0000' ? $receipt->F56Previuos->col_receipt_year : '';
                                $prev_receipt = $receipt->F56Previuos->col_receipt_no != '0' ? $receipt->F56Previuos->col_receipt_no : '';
                                $prev_date =  new Carbon\Carbon($receipt->F56Previuos->col_receipt_date) ;
                                $prev_date = $receipt->F56Previuos->col_receipt_date != '0000-00-00' ? $prev_date->toFormattedDateString() : '';    
                            }
                        @endphp
                            <div style="height:60px;margin-left: 140px; margin-top: -10px; background: ##b480fc;">
                                <table width="95%" style="margin-top:0px;" class="border-hidden">
                                    <tbody>
                                        <tr>
                                            <td colspan=2 height='25px' class="border-hidden text-center" style="font-size: 12px;" >
                                                <!-- PREVIOUS TAX RECEIPT NO. -->
                                                <small>{{ $tax_type }} </small>
                                                {{ ($prev_receipt)  }} 

                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="28px"  width="100" class="border-hidden text-left"  style="font-size: 12px;" >
                                            {{  $prev_date }} 
                                        </td>
                                            <td class="border-hidden text-left" style="font-size: 12px; width:2.7cm;">
                                                <!-- FOR THE YEAR -->
                                             {{ $prev_year }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="18%" class="border-hidden" style="background: ##eda6eb;">
                <b class="text-hidden">No. BGT <b> 
                <span style="font-size: 16px;padding:0;margin:0"></span></td>
        </tr>
        <tr>
            @php
                $date_entry = new Carbon\Carbon($receipt->date_of_entry);
                $period_cv = $receipt->F56Detailmny;

                $period_covered = [];
                 foreach ($period_cv as $key => $value){
                    $p = explode('-',$value->period_covered);
                    foreach($p as $key => $pvalue){
                         if(!in_array($pvalue,$period_covered)){
                            $period_covered[] = $pvalue;
                        }
                    }
                   
                }
                sort($period_covered);
                $first = reset($period_covered);
                $last = end($period_covered);

                $p_calendar_year = $first.' -'.$last; 
                if($first == $last){
                    $p_calendar_year = $first;
                }
             @endphp
            <td class="border-hidden text-right" >
                <!-- DATE -->
            <div style="margin-bottom:13px">
                {{ $date_entry->format('F d, Y') }}
            </div>
            </td>
        </tr>
        <tr>
            <td class="border-hidden text-center" height="20" style="background: ##a7e57b; padding-left: -150px;">
                {{$receipt->customer->name}}
            </td>
            <td class="border-hidden" style="padding-left: -30px;">{{ $total_words }} only</td>
            <td class="border-hidden text-right" style="text-indent: 13px;"><br />{{ number_format($form56['total'], 2) }}</td>
        </tr>
        <tr>
            <td colspan=2 class="border-hidden" height="28">
                <table width="100%" class="">
                    <tr>
                        <td width="12%" class="text-hidden" >Philippine currency, in</td>
                        <td width="7%"><!-- <span style="border:1px solid"></span> -->
                            <input type="checkbox" style="margin: 0; padding-left: 40px; font-size: 12px;" checked="checked"><br>
                            <!-- full<br>
                            installment -->
                        </td>
                        <td width="100%" style="padding-top: 10px; padding-left: 45px;"><span class="text-hidden">payment of REAL PROPERTY TAX upon property(ies) described below for the Calendar Year ></span>{{ $p_calendar_year }} </td>
                    </tr>
                </table>
            </td>
            <td class="border-hidden">
                <input type="checkbox" style="margin: 0; padding-left: 10px; padding-top: 11px; font-size: 12px;" checked="checked"><br>
                <input type="checkbox" style="margin: 0; padding-left: 10px; font-size: 12px;" checked="checked">
                <!-- BASIC TAX<br>
                SPECIAL EDUCATION FUND -->
            </td>
        </tr>
    </table>
    <table width="100%" style="margin: 4px 55px 0 8px; border-color: #ffffff00; background-color: ##42cbf4; position: absolute; top: 130px;" >
        <tr style="text-align:center;">
            <td class="border-hidden" style="width: 3.3cm;"><span class="text-hidden">Name Of <br>DECLARED OWNER</span></td>
            <td class="border-hidden" style="width: 3.3cm;" ><span class="text-hidden">Location<br>No./Street/Barangay</span></td>
            <td class="border-hidden"><span class="text-hidden">LOT<br>BLOCK NO.</span></td>
            <td class="border-hidden"><span class="text-hidden">TAX<br>DEC. NO</span></td>
            <td class="border-hidden"><span class="text-hidden">Land</span></td>
            <td class="border-hidden"><span class="text-hidden">Improvement</span></td>
            <td class="border-hidden"><span class="text-hidden">Total</span></td>
            <td class="border-hidden"><span class="text-hidden">TAX DUE</span></td>
            <td class="border-hidden"><span class="text-hidden">NO.</span></td>
            <td class="border-hidden"><span class="text-hidden">Payment</span></td>
            <td class="border-hidden"><span class="text-hidden">Full Payment</span></td>
            <td class="border-hidden"><span class="text-hidden">Penalty</span></td>
            <td class="border-hidden"><span class="text-hidden">TOTAL</span></td>
        </tr>
        @php
            $count_tr = 0;
            $period_covered  = '';

        @endphp
        @php 
                $count_tdrp = (count($receipt->F56Detailmny));
                $owner = '';

        @endphp
        <tr style="background: ##ef7385;">
            <td class="border-hidden text-left vertical-top" style="height: 155px; padding-left: 20px;">
            <?php
                // check if owner names are the same..
                $arr = array();
                foreach ($form56['tax_decs'] as $key => $f56x) {
                    array_push($arr, $key);
                }
                if (count($arr) >= 2) {
                for ($i = 1; $i < count($arr); $i++) {
                    if (strcmp($arr[$i], $arr[$i-1]) == 0) {
                        $split = explode(" ", $arr[$i-1]);
                        foreach ($split as $s) {
                            if($s != " ") {
                                echo $s.'<br/>';
                            }
                        }
                    } else 
                        $split = explode(" ", $arr[$i]);
                        foreach ($split as $s) {
                            if($s != " ") {
                                echo $s.'<br/>';
                            }
                        }
                    }
                } else {
                    $split = explode(" ", $arr[0]);
                    foreach ($split as $s) {
                        if($s != " ") {
                            echo $s.'<br/>';
                        }
                    }
                }
            ?>
            </td>
            <td class="border-hidden text-left vertical-top" style="background: ##ef7385">
            @php
                // check if locations are the same..
                // $arr = array();
                foreach ($form56['tax_decs'] as $key => $f56x) {
                    foreach ($f56x as $key => $f56) {
                        // array_push($arr, $f56['barangay_name']." ".$f56['tax_type']);
                        echo $f56['barangay_name'].' '.$f56['tax_type'].'<br>';
                    }
                }
                // $group_arr = array_unique($arr);

                /*foreach($group_arr as $arr) {
                    echo $arr.'<br>';
                }*/
            @endphp
            </td>

            <td class="border-hidden text-left vertical-top" style="width: 3.3cm; background: ##689cf2;" colspan="2" >
            @php
                // check if locations are the same..
                $arr = array();
                foreach ($form56['tax_decs'] as $key => $f56x) {
                    foreach ($f56x as $key => $f56) {
                        array_push($arr, $f56['tax_dec']);
                    }
                }
                $group_arr = array_unique($arr);
                foreach ($group_arr as $key => $arr) {
                    echo $arr."<br>";
                }
            @endphp
            </td>

            <!-- LAND -->
            <td class="border-hidden text-right vertical-top" style="width: 1.3cm; background: ##4cef9b;" >
            <?php
                $arr = array();
                foreach ($form56['tax_decs'] as $key => $f56x) {
                    foreach ($f56x as $key => $f56) {
                        if(!is_null($f56['kind'])) {
                            if(preg_match('/building/i', $f56['kind']) != 1) {
                                // array_push($arr, number_format($f56['tdrp_assedvalue'],2));
                                echo number_format($f56['tdrp_assedvalue'],2)."<br>";
                            }
                        } else if(!is_null($f56['actual_use'])) {
                            if(preg_match('/bldg/i', $f56['actual_use']) != 1) {
                                // array_push($arr, number_format($f56['tdrp_assedvalue'],2));
                                echo number_format($f56['tdrp_assedvalue'],2)."<br>";
                            }
                        }
                    }
                }
                // $group_arr = array_unique($arr);
                // foreach ($group_arr as $val) {
                //     echo $val."<br>";
                // }
            ?>
            </td>

            <!-- IMPROVEMENT (BUILDING/MACHINE) -->
            <td class="border-hidden text-right vertical-top" style="width: 1.3cm; background: ##4287f5; padding-right: 5px;">
            <?php
                $arr = array();
                foreach ($form56['tax_decs'] as $key => $f56x) {
                    foreach ($f56x as $key => $f56) {
                        if(!is_null($f56['kind'])) {
                            if(preg_match('/building/i', $f56['kind']) == 1) {
                                // array_push($arr, number_format($f56['tdrp_assedvalue'],2));
                                echo number_format($f56['tdrp_assedvalue'],2)."<br>";
                            }
                        } else if(!is_null($f56['actual_use'])) {
                            if(preg_match('/bldg/i', $f56['actual_use']) == 1) {
                                // array_push($arr, number_format($f56['tdrp_assedvalue'],2));
                                echo number_format($f56['tdrp_assedvalue'],2)."<br>";
                            }
                        }
                    }
                }
            ?>
            </td>

            <td class="border-hidden text-left vertical-top text-right" style="width: 1.5cm; background: ##f276c4; padding-right: -25px;">
                <!-- assessed value TOTAL -->
                <?php
                    $arr = array();
                    // $group_arr = array();
                    // $limit = (count($form56['yearly']) > 4) ? 3 : count($form56['yearly']);
                    $limit = (count($final_group_arr) > 4) ? 3 : count($final_group_arr);
                    $limit_counter = 0;
                    // $limit_end = 0;

                    foreach ($form56['tax_decs'] as $keyx => $f56x) {
                        foreach ($f56x as $key => $f56) {
                            foreach ($form56['yearly'] as $yr => $y) {
                                if($yr > $limit_end) {
                                    array_push($arr, number_format($y['assess_val'],2));
                                }
                            }
                        }
                    }

                    $arr2 = array_unique($arr);

                    if(count($group_arr) > 0) {
//////////////////// NOTE : CHANGED ALL ARR_UNIQUE TO GROUP_ARR...... ////////////////////
                        foreach ($form56['yearly'] as $yr => $y) {
                            if($yr > $limit_end) {
                                $breakdown_total = array(
                                    'penalty' => 0,
                                    'discount' => 0,
                                    'partial_value' => 0,
                                    'total_value' => 0
                                );
                                if(isset($breakdown[$yr])) {
                                    foreach ($breakdown[$yr] as $key => $value) {
                                        $breakdown_total['penalty'] += $value['penalty'];
                                        $breakdown_total['discount'] += $value['discount'];
                                        $breakdown_total['partial_value'] += $value['partial_value'];
                                        $breakdown_total['total_value'] += $value['total_value'];
                                        $breakdown_total['quarter'] = $value['quarter'];
                                    }
                                }
                                $partial_qrtr = '';
                                $total_qrtr = ''; 
                                if(!empty($final_group_arr)) { 
                                    if(isset($breakdown[$yr])) {
                                        if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y') && $y['period_covered'] > $limit_end) {
                                            echo number_format(($breakdown_total['partial_value']*100),2)."<br>";
                                            echo number_format(($breakdown_total['total_value']*100),2)."<br>";
                                            echo number_format(($breakdown_total['partial_value']*100),2)."<br>";
                                            echo number_format(($breakdown_total['total_value']*100),2)."<br>";
                                            $limit_counter++; 
                                            // $limit_end = $yr;
                                        } else {
                                            break;
                                        }
                                    } else if(isset($final_group_arr[$yr])) { 
                                        // dd($limit);
                                        if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y') && $yr > $limit_end) {
                                            if(strlen($year) > 4) {
                                                $split = explode('-', $year);
                                                if($year[1] > $limit_end) {
                                                    echo number_format($final_group_arr[$yr]['assess_val'],2)."<br>";
                                                    echo number_format($final_group_arr[$yr]['assess_val'],2)."<br>";
                                                }
                                            } else {
                                                echo number_format($final_group_arr[$yr]['assess_val'],2)."<br>";
                                                echo number_format($final_group_arr[$yr]['assess_val'],2)."<br>";
                                            }

                                            // if(isset($final_group_arr[$yr]['to'])) {
                                            //     $limit_end = $final_group_arr[$yr]['to'];
                                            // } else {
                                            //     $limit_end = $yr;
                                            // }
                                            $limit_counter++;
                                        } else if($limit == 1 && $limit_counter < $limit && $y['period_covered'] >= \Carbon\Carbon::now()->addYear()->format('Y') && $yr > $limit_end) {
                                            // set limit when limit is only 1 and is an advanced payment OR
                                            echo number_format($final_group_arr[$yr]['assess_val'],2)."<br>";
                                            echo number_format($final_group_arr[$yr]['assess_val'],2)."<br>";
                                            $limit_counter++;
                                            // if(isset($final_group_arr[$yr]['to'])) {
                                            //     $limit_end = $final_group_arr[$yr]['to'];
                                            // } else {
                                            //     $limit_end = $yr;
                                            // }
                                        }
                                    } else {
                                        if(strlen($yr) > 4) {
                                            echo number_format($y['assess_val'],2)."<br>";
                                            echo number_format($y['assess_val'],2)."<br>";
                                            $limit_counter++;
                                            // $limit_end = $yr;
                                        }
                                    }
                                } else {
                                    if(!empty($breakdown[$yr])) {
                                        foreach ($breakdown[$yr] as $key => $value) {
                                            if(!empty($breakdown[$yr])) {
                                                foreach ($breakdown[$yr] as $key => $value) {
                                                    foreach ($arr2 as $a) {
                                                        if($limit_counter <= $limit) {
                                                            echo $a."<br><br>";
                                                            echo $a."<br><br>";
                                                            $limit_counter++;
                                                            // $limit_end = $k;
                                                        } else {
                                                            break;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    } else{
                                        foreach ($arr2 as $a) {
                                            if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y')) {
                                                echo $a."<br>";
                                                echo $a."<br>";
                                                $limit_counter++;
                                                // $limit_end = $k;
                                            }
                                        }
                                    }
                                }
                            }
                            ///////
                        }
                    } else {
                        foreach ($form56['yearly'] as $yr => $y) {
                            if($yr > $limit_end) {
                                foreach ($arr2 as $a) {
                                    if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y')) {
                                        echo $a."<br>";
                                        echo $a."<br>";
                                        $limit_counter++;
                                        // $limit_end = $yr;
                                    }
                                }
                            }
                        }
                    }
                ?>
            </td>

            <td class="border-hidden text-left vertical-top" style="width: 3cm; background: ##a276c4; position: relative; padding-left: 25px;">
                <div style="margin: 0; padding: 0; text-align: right;"> 
                <?php
                    $arr = array();
                    $limit = (count($form56['yearly']) > 3) ? 2 : count($form56['yearly']);
                    $limit_counter = 0;
                    // $limit_end = 0;

                    foreach ($form56['tax_decs'] as $keyx => $f56x) {
                        foreach ($f56x as $key => $f56) {
                            foreach ($form56['yearly'] as $yr => $y) {
                                if($yr > $limit_end) {
                                    array_push($arr, number_format($y['assess_val'],2));
                                }
                            }
                        }
                    }                    

                    $arr2 = array_unique($arr);
                    if(count($final_group_arr) > 0) {
                        $ii = key($final_group_arr);
                        $end_year = array_keys($form56['yearly'])[count($form56['yearly'])-1];
                        foreach ($form56['yearly'] as $yr => $y) {
                                if(strlen($yr) > 4) {
                                    $split = explode('-', $yr);
                                    if($split[0] > $limit_end){
                                        continue;
                                    }
                                }
                                if($yr > $limit_end) {
                                    $breakdown_total = array(
                                        'penalty' => 0,
                                        'discount' => 0,
                                        'partial_value' => 0,
                                        'total_value' => 0
                                    );
                                    if(isset($breakdown[$yr])) {
                                        foreach ($breakdown[$yr] as $key => $value) {
                                            $breakdown_total['penalty'] += $value['penalty'];
                                            $breakdown_total['discount'] += $value['discount'];
                                            $breakdown_total['partial_value'] += $value['partial_value'];
                                            $breakdown_total['total_value'] += $value['total_value'];
                                            $breakdown_total['quarter'] = $value['quarter'];
                                        }
                                    }
                                    
                                    $partial_qrtr = '';
                                    $total_qrtr = '';

                                    if(!empty($final_group_arr)) {
                                        if(isset($breakdown[$yr]) && $yr > $limit_end) {
                                                switch ($breakdown_total['quarter']) {
                                                    case "1": 
                                                        $partial_qrtr = '1st';
                                                        $total_qrtr = '2nd-4th';
                                                        break;
                                                    case "2":
                                                        $partial_qrtr = '1st';
                                                        $total_qrtr = '2nd-4th';
                                                        break;
                                                    case "3":
                                                        $partial_qrtr = '1st-2nd';
                                                        $total_qrtr = '3rd-4th';
                                                        break;
                                                    default:
                                                        $partial_qrtr = 'full';
                                                        $total_qrtr = 'full';
                                                        break;
                                                } 
                                                if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y')) {
                                                    echo number_format(($breakdown_total['partial_value']),2)."<span>&nbsp;&nbsp;&nbsp;</span>".$partial_qrtr."<br>(".$yr.")<span style='width: 30px; height: 3px; display: inline-block;'></span>qtr<br>";
                                                    echo number_format(($breakdown_total['total_value']),2)."<span>&nbsp;&nbsp;&nbsp;</span>".$total_qrtr."<br>(".$yr.")<span style='width: 30px; height: 3px; display: inline-block;'></span>qtr<br>";
                                                    $limit_counter++;
                                                    // $limit_end = $yr;
                                                } else {
                                                    break;
                                                }
                                        } else if(isset($final_group_arr[$yr])) {
                                            if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y')) {
                                                if(isset($final_group_arr[$yr]['to'])) {
                                                    $arp = isset($final_group_arr[$yr]['arp']) ? explode("-", $final_group_arr[$yr]['arp']) : '';
                                                    // $taxdue = $arp_total != '' ? ($arp[0] < 94 ? $group_arr_total[$yr]['sef'] : $group_arr_total[$yr]['assess_val']*.01) : $group_arr_total[$yr]['assess_val']*.01;
                                                    $taxdue = $arp != '' ? ($arp[0] < 94 ? $final_group_arr[$yr]['assess_val'] : $final_group_arr[$yr]['assess_val']*.01) : $final_group_arr[$yr]['assess_val']*.01;
                                                    echo number_format($taxdue, 2)."<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(".$yr."-".$final_group_arr[$yr]['to'].")<br>";
                                                } else {
                                                    $arp = isset($final_group_arr[$yr]['arp']) ? explode("-", $final_group_arr[$yr]['arp']) : '';
                                                    $taxdue = $arp != '' ? ($arp[0] < 94 ? $final_group_arr[$yr]['assess_val'] : ($final_group_arr[$yr]['assess_val']*.01)) : $final_group_arr[$yr]['assess_val']*.01;
                                                    echo number_format($final_group_arr[$yr]['assess_val'], 2)."<span>&nbsp;&nbsp;&nbsp;</span>full<br>(".$yr.")<span style='width: 35px; height: 3px; display: inline-block;'></span><br>";
                                                }
                                                $limit_counter++;
                                                // $limit_end = $yr;
                                            } elseif($limit == 1 && $limit_counter < $limit && $y['period_covered'] >= \Carbon\Carbon::now()->addYear()->format('Y')) {
                                                if(isset($final_group_arr[$yr]['to'])) {
                                                    $arp = isset($final_group_arr[$yr]['arp']) ? explode("-", $final_group_arr[$yr]['arp']) : '';
                                                    // $taxdue = $arp != '' ? ($arp[0] < 94 ? $group_arr_total[$yr]['sef'] : $group_arr_total[$yr]['assess_val']*.01) : $group_arr_total[$yr]['assess_val']*.01;
                                                    $taxdue = $arp != '' ? ($arp[0] < 94 ? $final_group_arr[$yr]['assess_val'] : $final_group_arr[$yr]['assess_val']*.01) : $final_group_arr[$yr]['assess_val']*.01;
                                                    echo number_format($taxdue, 2)."<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(".$yr."-".$final_group_arr[$yr]['to'].")<br>";
                                                } else {
                                                    $arp = isset($final_group_arr[$yr]['arp']) ? explode("-", $final_group_arr[$yr]['arp']) : '';
                                                    // $taxdue = $arp != '' ? ($arp[0] < 94 ? $group_arr_total[$yr]['sef'] : ($group_arr_total[$yr]['assess_val']*.01)) : $group_arr_total[$yr]['assess_val']*.01;
                                                    $taxdue = $arp != '' ? ($arp[0] < 94 ? $final_group_arr[$yr]['assess_val'] : ($final_group_arr[$yr]['assess_val']*.01)) : $final_group_arr[$yr]['assess_val']*.01;
                                                    // echo number_format($group_arr_total[$yr]['sef'], 2)."<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(".$yr.")<span style='width: 45px; height: 3px; display: inline-block;'></span><br>";
                                                    echo number_format($final_group_arr[$yr]['assess_val'], 2)."<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(".$yr.")<span style='width: 45px; height: 3px; display: inline-block;'></span><br>";
                                                }
                                                $limit_counter++;
                                                // $limit_end = $yr;
                                            }
                                        } else {
                                            if(strlen($yr) > 4) {
                                                $arp = isset($final_group_arr[$yr]) ? explode("-", $final_group_arr[$yr]['arp']) : '';
                                                $taxdue = $arp != '' ? ($arp[0] >= 94 ? $y['assess_val']*.01 : $y['sef']) : $y['assess_val']*.01;
                                                echo number_format($taxdue, 2)."<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(".$yr.")<span style='width: 
                                                40px; height: 3px; display: inline-block;'></span><br>";
                                                $limit_counter++;
                                                // $limit_end = $yr;
                                            }
                                        }
                                    } else {
                                        if(!empty($breakdown[$yr])) {
                                            foreach ($breakdown[$yr] as $key => $value) {
                                                if(!empty($breakdown[$yr])) {
                                                    foreach ($breakdown[$yr] as $key => $value) {
                                                        foreach ($arr2 as $a) {
                                                            if($limit_counter <= $limit) {
                                                                echo $a."<br><br><br>";
                                                                $limit_counter++;
                                                                // $limit_end = $k;
                                                            } else {
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        } else{
                                            foreach ($arr2 as $a) {
                                                if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y')) {
                                                    echo $a."<br>";
                                                    $limit_counter++;
                                                    // $limit_end = $k;
                                                }
                                            }
                                        }
                                    }
                                }
                            
                        }
                    } else {
                        foreach ($form56['yearly'] as $yr => $y) {
                            if($yr > $limit_end) {
                                foreach ($arr2 as $a) {
                                    if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y')) {
                                        echo $a."<br>";
                                        $limit_counter++;
                                        // $limit_end = $yr;
                                    }
                                }
                            }
                        }
                    }
                ?>
                </div>
            </td>

            <td class="border-hidden text-left vertical-top" style="width: 1.7cm; background: ##cde25f; text-align: center;">
            <?php $limit_counter = 0; ?>
            @foreach($form56['yearly'] as $k => $y)
                <?php
                    if(strlen($k) > 4) {
                        $split = explode('-', $k);
                        if($split[0] > $limit_end){
                            continue;
                        }
                    }
                ?>
                @if(!empty($final_group_arr))
                    @if($k > $limit_end)
                        @if(isset($breakdown[$k]))
                                @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                                    @if($k < $limit_end)
                                        BASIC<br />
                                        SEF<br />
                                        BASIC<br />
                                        SEF<br />
                                    @else
                                        BASIC<br />
                                        SEF<br />
                                        BASIC<br />
                                        SEF<br />
                                    @endif
                                    <?php 
                                        $limit_counter++; 
                                    ?>
                                @else
                                    <?php break; ?>
                                @endif
                        @elseif(isset($final_group_arr[$k]))
                                @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                                    BASIC<br />
                                    SEF<br />
                                    <?php 
                                        $limit_counter++;                                     
                                    ?>
                                @elseif($limit == 1 && $limit_counter < $limit && $y['period_covered'] >= \Carbon\Carbon::now()->addYear()->format('Y'))
                                    BASIC<br />
                                    SEF<br />
                                    <?php 
                                        $limit_counter++;                                     
                                    ?>
                                @else
                                    <?php break; ?>
                                @endif
                        @elseif(!isset($breakdown[$k]) && !isset($group_arr[$k]))
                            @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                                BASIC<br />
                                SEF<br />
                                <?php 
                                    $limit_counter++; 
                                ?>
                            @else
                                <?php break; ?>
                            @endif
                        @endif
                    @else
                        @if(!empty($breakdown[$k]))
                            @foreach($breakdown[$k] as $key => $bkd)
                                @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                                    BASIC<br />
                                    SEF<br />
                                    BASIC<br />
                                    SEF<br />
                                    <?php 
                                        $limit_counter++; 
                                    ?>
                                @else
                                    <?php break; ?>
                                @endif
                            @endforeach
                        @else
                            @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                                BASIC<br />
                                SEF<br />
                                <?php 
                                    $limit_counter++; 
                                ?>
                            @else
                                <?php break; ?>
                            @endif
                        @endif
                    @endif
                @endif
            @endforeach
            </td>

            <td class="border-hidden text-right vertical-top" style="width: 1.1cm; background: ##e8aa4e; padding-right: 8px;" >
            @php
                $partial_total = 0;
                $total_total = 0;
                $partial_total_compute = 0;
            @endphp
            @foreach($form56['yearly'] as $k => $y)
                <?php
                    if(strlen($k) > 4) {                        
                        $split = explode('-', $k);
                        if($split[0] > $limit_end){
                            continue;
                        }
                    }
                ?>
                @if($k > $limit_end)
                    @if(!empty($breakdown[$k]))
                        @foreach($breakdown[$k] as $key => $bkd)
                            @php
                                $partial_total += $breakdown[$k][$key]['partial_value'];
                                $total_total += $breakdown[$k][$key]['total_value'];
                            @endphp
                        @endforeach
                    @else
                        <?php
                            // if(strlen($k) == 4) {
                            //     $group_arr[$k] = [];
                            //     $group_arr[$k]['assess_val'] = $y['assess_val'];
                            //     $group_arr[$k]['penalty'] = $y['penalty'];
                            // }
                        ?>
                    @endif
                @endif
            @endforeach
            <?php
                // tax breakdown 
                $ii = key($group_arr);
                $end_year = array_keys($form56['yearly'])[count($form56['yearly'])-1];
            ?>
            <?php $limit_counter = 0; ?>
            @foreach($form56['yearly'] as $k => $y)
                <?php
                    if(strlen($k) > 4) {
                        $split = explode('-', $k);
                        if($split[0] > $limit_end){
                            continue;
                        }
                    }
                ?>
                @if($k > $limit_end)
                    @if(!empty($group_arr))
                        @if(isset($breakdown[$k]))
                            @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                                {{ number_format($partial_total, 2) }}<br>
                                {{ number_format($partial_total, 2) }}<br>
                                {{ number_format($total_total, 2) }}<br>
                                {{ number_format($total_total, 2) }}<br>
                                <?php 
                                    $limit_counter++; 
                                ?>
                            @else
                                <?php break; ?>
                            @endif
                        @elseif(isset($final_group_arr[$k]))
                            @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                                @if(strlen($k) > 4)
                                    <?php
                                        $split_year = explode('-', $k);
                                        $range = ($split_year[1] - $split_year[0]) + 1;
                                    ?>
                                    {{ number_format(isset($range) ? (($y['assess_val']/100)*$range) : ($y['assess_val']/100), 2) }}
                                    <br>
                                    {{ number_format(isset($range) ? (($y['assess_val']/100)*$range) : ($y['assess_val']/100), 2) }}
                                    <br>
                                    <?php 
                                        $limit_counter++; 
                                    ?>
                                @else
                                    {{-- number_format(($group_arr_total[$k]['sef']),2) --}}
                                    {{ number_format(($final_group_arr[$k]['assess_val'] * .01),2) }}
                                    <br/>
                                    {{-- number_format(($group_arr_total[$k]['sef']),2) --}}
                                    {{ number_format(($final_group_arr[$k]['assess_val'] * .01),2) }}
                                    <br/>
                                    <?php 
                                        $limit_counter++; 
                                    ?>
                                @endif
                            @elseif($limit == 1 && $limit_counter < $limit && $y['period_covered'] >= \Carbon\Carbon::now()->addYear()->format('Y'))
                                @if(strlen($k) > 4)
                                    <?php
                                        $split_year = explode('-', $k);
                                        $range = ($split_year[1] - $split_year[0]) + 1;
                                    ?>
                                    {{ number_format(isset($range) ? (($y['assess_val']/100)*$range) : ($y['assess_val']/100), 2) }}
                                    <br>
                                    {{ number_format(isset($range) ? (($y['assess_val']/100)*$range) : ($y['assess_val']/100), 2) }}
                                    <br>
                                    <?php 
                                        $limit_counter++; 
                                    ?>
                                @else
                                    {{-- number_format(($group_arr_total[$k]['sef']),2) --}}
                                    {{ number_format(($final_group_arr[$k]['assess_val'] *.01),2) }}
                                    <br/>
                                    {{-- number_format(($group_arr_total[$k]['sef']),2) --}}
                                    {{ number_format(($final_group_arr[$k]['assess_val'] *.01),2) }}
                                    <br/>
                                    <?php 
                                        $limit_counter++; 
                                    ?>
                                @endif
                            @else
                                <?php break; ?>
                            @endif
                        @else
                            @if(strlen($k) > 4)
                                <?php
                                    $split_year = explode('-', $k);
                                    $range = ($split_year[1] - $split_year[0]) + 1;
                                ?>
                                {{ number_format(isset($range) ? (($y['assess_val']/100)*$range) : ($y['assess_val']/100), 2) }}<br>
                                {{ number_format(isset($range) ? (($y['assess_val']/100)*$range) : ($y['assess_val']/100), 2) }}<br>
                                <?php 
                                    $limit_counter++; 
                                ?>
                            @endif
                        @endif
                    @else
                        @if(!empty($breakdown[$k]))
                            @foreach($breakdown[$k] as $key => $bkd)
                                @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                                    {{ number_format($partial_total, 2) }}<br>
                                    {{ number_format($partial_total, 2) }}<br>
                                    {{ number_format($total_total, 2) }}<br>
                                    {{ number_format($total_total, 2) }}<br>
                                    <?php 
                                        $limit_counter++; 
                                    ?>
                                @else
                                    <?php break; ?>
                                @endif
                            @endforeach
                        @else
                            @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                                {{ number_format($y['sef'],2) }}<br />
                                {{ number_format($y['sef'],2) }}<br />
                                <!-- {{-- number_format(($y['assess_val']/100 + $y['penalty']),2) --}}<br />
                                {{-- number_format(($y['assess_val']/100 + $y['penalty']),2) --}}<br /> -->
                                <?php 
                                    $limit_counter++; 
                                ?>
                            @else
                                <?php break; ?>
                            @endif
                        @endif
                    @endif
                @endif
            @endforeach
            </td>

            <td class="border-hidden text-right vertical-top" style="width: 1cm; background: ##e56b60; padding-right: -20px;">
            @php
                $yrTotalPenalty = 0;
                $yrTotalDiscount = 0;
                //$group_arr = [];
            @endphp
            @foreach($form56['yearly'] as $k => $y)       
                <?php
                    if(strlen($k) > 4) {
                        $split = explode('-', $k);
                        if($split[0] > $limit_end){
                            continue;
                        }
                    }
                ?>
                @if($k > $limit_end)
                    @if(!empty($breakdown[$k]))
                        @foreach($breakdown[$k] as $key => $bkd)
                            @if(!empty($bkd))
                                @php
                                    $yrTotalPenalty += $bkd['penalty'];
                                    $yrTotalDiscount += $bkd['discount'];
                                    // $yrTotalPenalty += $y['penalty'];
                                    // $yrTotalDiscount += $y['discount'];
                                @endphp
                            @else

                            @endif 
                        @endforeach
                    @else
                        <?php
                            // $group_arr[$k] = [];
                            // $group_arr[$k]['assess_val'] = $y['assess_val'];
                            // $group_arr[$k]['penalty'] = $y['penalty'];
                            // $group_arr[$k]['discount'] = $y['discount'];
                        ?>
                    @endif
                @endif
            @endforeach
            <?php
                $ii = key($group_arr);
                $end_year = array_keys($form56['yearly'])[count($form56['yearly'])-1];
            ?>
            <?php $limit_counter = 0; ?>
            @foreach($form56['yearly'] as $k => $y)
                @if($k > $limit_end)
                    @if(!empty($final_group_arr))
                        @if(isset($breakdown[$k]))
                            @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                                {{ $yrTotalPenalty == 0 ? '' : number_format($yrTotalPenalty,2) }}
                                {{ $yrTotalDiscount == 0 ? '' : '('.number_format($yrTotalDiscount,2).')' }}
                                <br>
                                {{ $yrTotalPenalty == 0 ? '' : number_format($yrTotalPenalty,2) }}
                                {{ $yrTotalDiscount == 0 ? '' : '('.number_format($yrTotalDiscount,2).')' }}
                                <br>
                                <br>
                                <br>
                                <?php 
                                    $limit_counter++; 
                                ?>
                            @else
                                <?php break; ?>
                            @endif
                        @elseif(isset($final_group_arr[$k]))
                            @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                                {{ $final_group_arr[$k]['penalty'] == 0 ? '' : number_format($final_group_arr[$k]['penalty'],2) }}  
                                {{ $final_group_arr[$k]['discount'] == 0 ? '' : '('.number_format($final_group_arr[$k]['discount'],2).')' }} 
                                <br />
                                {{ $final_group_arr[$k]['penalty'] == 0 ? '' : number_format($final_group_arr[$k]['penalty'],2) }}
                                {{ $final_group_arr[$k]['discount'] == 0 ? '' : '('.number_format($final_group_arr[$k]['discount'],2).')' }} 
                                <br />
                                <?php 
                                    $limit_counter++; 
                                ?>
                            @elseif($limit == 1 && $limit_counter < $limit && $y['period_covered'] >= \Carbon\Carbon::now()->addYear()->format('Y'))
                                {{ $final_group_arr[$k]['penalty'] == 0 ? '' : number_format($final_group_arr[$k]['penalty'],2) }}  
                                {{ $final_group_arr[$k]['discount'] == 0 ? '' : '('.number_format($final_group_arr[$k]['discount'],2).')' }} 
                                <br />
                                {{ $final_group_arr[$k]['penalty'] == 0 ? '' : number_format($final_group_arr[$k]['penalty'],2) }}
                                {{ $final_group_arr[$k]['discount'] == 0 ? '' : '('.number_format($final_group_arr[$k]['discount'],2).')' }} 
                                <br />
                                <?php 
                                    $limit_counter++; 
                                ?>
                            @else
                                <?php break; ?>
                            @endif
                        @endif
                    @else
                        @if(!empty($breakdown[$k]))
                            @foreach($breakdown[$k] as $key => $bkd)
                                @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                                    {{ $yrTotalPenalty == 0 || $yrTotalDiscount != 0 ? '' : number_format($yrTotalPenalty,2) }}
                                    {{ $yrTotalDiscount == 0 ? '' : '('.number_format($yrTotalDiscount,2).')' }}
                                    <br>
                                    {{ $yrTotalPenalty == 0 || $yrTotalDiscount != 0 ? '' : number_format($yrTotalPenalty,2) }}
                                    {{ $yrTotalDiscount == 0 ? '' : '('.number_format($yrTotalDiscount,2).')' }}
                                    <br>
                                    <br>
                                    <br>
                                    <?php 
                                        $limit_counter++; 
                                    ?>
                                @else
                                    <?php break; ?>
                                @endif
                            @endforeach
                        @else
                            @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                                {{ $y['penalty'] == 0 ? '' : number_format($y['penalty'],2) }}  
                                {{ $y['discount'] == 0 ? '' : '('.number_format($y['discount'],2).')' }} 
                                <br />
                                {{ $y['penalty'] == 0 ? '' : $y['penalty'] }}
                                {{ $y['discount'] == 0 ? '' : '('.number_format($y['discount'],2).')' }} 
                                <br />
                                <?php 
                                    $limit_counter++; 
                                ?>
                            @else
                                <?php break; ?>
                            @endif
                        @endif
                    @endif
                @endif
            @endforeach
            </td>

            <td class="border-hidden text-right vertical-top" style="width: 2.1cm; background: ##7fe83e; padding-left: 10px;">
            @php
                $finalPartialTotal = 0;
                $finalTotalTotal = 0;
                $limit_total = 0;
            @endphp
            @foreach($form56['yearly'] as $k => $y)      
                <?php
                    if(strlen($k) > 4) {
                        $split = explode('-', $k);
                        if($split[0] > $limit_end){
                            continue;
                        }
                    }
                ?>
                @if($y > $limit_end)
                    @if(!empty($breakdown[$k]))
                        @foreach($breakdown[$k] as $key => $bkd)
                            <?php
                                $total_penalty = $breakdown[$k][$key]['partial_value'] + $breakdown[$k][$key]['penalty'];
                                $total_discount = $breakdown[$k][$key]['partial_value'] - $breakdown[$k][$key]['discount'];

                                $finalPartialTotal += $breakdown[$k][$key]['discount'] != 0 ? $total_discount : $total_penalty;
                                $finalTotalTotal += $breakdown[$k][$key]['total_value'];
                                
                                // working dati
                                /*foreach($form56['tax_decs'] as $tax) {
                                    foreach($tax as $t) {
                                        $partial_total = $t['tax_due']/4;
                                        $total_total = $t['tax_due'] - number_format($partial_total, 2);

                                        $total_penalty = $partial_total + $breakdown[$k][$key]['penalty'];
                                        $total_discount = $partial_total - $breakdown[$k][$key]['discount'];

                                        $finalPartialTotal += $breakdown[$k][$key]['discount'] != 0 ? $total_discount : $total_penalty;
                                        $finalTotalTotal += $breakdown[$k][$key]['discount'] != 0 ? $total_total - $breakdown[$k][$key]['discount'] : ($breakdown[$k][$key]['penalty'] != 0 ? $total_total + $breakdown[$k][$key]['penalty'] : $total_total);
                                    }
                                }*/
                            ?>
                        @endforeach
                    @endif
                @endif
            @endforeach
            <?php $limit_counter = 0; ?>
            @foreach($form56['yearly'] as $k => $y)
                @if($k > $limit_end)
                    @if(!empty($final_group_arr))
                        @if(isset($breakdown[$k])) 
                            @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                                {{ number_format($finalPartialTotal, 2) }}<br>
                                {{ number_format($finalPartialTotal, 2) }}<br>
                                {{ number_format($finalTotalTotal, 2) }}<br>
                                {{ number_format($finalTotalTotal, 2) }}<br> 
                                <?php 
                                    $limit_counter++; 
                                    $limit_total += ($finalPartialTotal)*2 + ($finalTotalTotal)*2; 
                                ?>
                            @else
                                <?php break; ?> 
                            @endif
                        @elseif(isset($final_group_arr[$k]))
                            @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                                @if(strlen($k) > 4)
                                    <?php
                                        $split_string = explode('-', $k);
                                        $range = $split_string[1] - $split_string[0] + 1;
                                        $limit_counter++; 
                                        $limit_total += (($y['assess_val']/100)*$range+$y['penalty'])*2; 
                                    ?>
                                    <!-- {{-- number_format(($y['assess_val']/100)*$range+$y['penalty'], 2) --}}<br>
                                    {{-- number_format(($y['assess_val']/100)*$range+$y['penalty'], 2) --}}<br> -->
                                    <!-- {{-- number_format(($y['assess_val']/100 + $y['penalty']/100), 2) --}}<br>
                                    {{-- number_format(($y['assess_val']/100 + $y['penalty']/100), 2) --}}<br>  -->
                                    {{ number_format($y['sef'] + $y['penalty'], 2) }}<br>
                                    {{ number_format($y['sef'] + $y['penalty'], 2) }}<br>              
                                @else
                                    {{-- number_format((($group_arr[$k]['sef']) + $group_arr[$k]['penalty'] - $group_arr[$k]['discount']),2) --}}
                                    {{ number_format((($final_group_arr[$k]['assess_val'] * .01) + $final_group_arr[$k]['penalty'] - $final_group_arr[$k]['discount']),2) }}
                                    <br />
                                    {{-- number_format((($group_arr[$k]['sef']) + $group_arr[$k]['penalty'] - $group_arr[$k]['discount']),2) --}}
                                    {{ number_format((($final_group_arr[$k]['assess_val'] * .01) + $final_group_arr[$k]['penalty'] - $final_group_arr[$k]['discount']),2) }}
                                    <br />
                                    <?php 
                                        $limit_counter++;  
                                        // $limit_total += (($group_arr[$k]['sef']) + $group_arr[$k]['penalty'] - $group_arr[$k]['discount'])*2;
                                        $limit_total += (($final_group_arr[$k]['assess_val'] * .01) + $final_group_arr[$k]['penalty'] - $final_group_arr[$k]['discount'])*2; 
                                    ?>
                                @endif
                            @elseif($limit == 1 && $limit_counter < $limit && $y['period_covered'] >= \Carbon\Carbon::now()->addYear()->format('Y'))
                                @if(strlen($k) > 4)
                                    <?php
                                        $split_string = explode('-', $k);
                                        $range = $split_string[1] - $split_string[0] + 1;
                                        $limit_counter++; 
                                        $limit_total += (($y['assess_val']*.01)*$range+$y['penalty'])*2; 
                                    ?>
                                    {{ number_format($y['sef'] + $y['penalty'], 2) }}<br>
                                    {{ number_format($y['sef'] + $y['penalty'], 2) }}<br>              
                                @else
                                    {{-- number_format((($group_arr[$k]['sef']) + $group_arr[$k]['penalty'] - $group_arr[$k]['discount']),2) --}}
                                    {{ number_format((($final_group_arr[$k]['assess_val'] * .01) + $final_group_arr[$k]['penalty'] - $final_group_arr[$k]['discount']),2) }}
                                    <br />
                                    {{-- number_format((($group_arr[$k]['sef']) + $group_arr[$k]['penalty'] - $group_arr[$k]['discount']),2) --}}
                                    {{ number_format((($final_group_arr[$k]['assess_val'] * .01) + $final_group_arr[$k]['penalty'] - $final_group_arr[$k]['discount']),2) }}
                                    <br />
                                    <?php 
                                        $limit_counter++;  
                                        // $limit_total += (($group_arr[$k]['sef']) + $group_arr[$k]['penalty'] - $group_arr[$k]['discount'])*2; 
                                        $limit_total += (($final_group_arr[$k]['assess_val'] * .01) + $final_group_arr[$k]['penalty'] - $final_group_arr[$k]['discount'])*2; 
                                    ?>
                                @endif
                            @else
                                <?php break; ?>
                            @endif
                        @endif
                    @else
                        @if(!empty($breakdown[$k])) 
                            @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                                {{ number_format($finalPartialTotal, 2) }}<br>
                                {{ number_format($finalPartialTotal, 2) }}<br>
                                {{ number_format($finalTotalTotal, 2) }}<br>
                                {{ number_format($finalTotalTotal, 2) }}<br>
                                <?php 
                                    $limit_counter++; 
                                    $limit_total += ($finalPartialTotal)*2 + ($finalTotalTotal)*2; 
                                ?>
                            @else
                                <?php break; ?>
                            @endif
                        @else
                            @if($limit_counter <= $limit && $y['period_covered'] <= \Carbon\Carbon::now()->format('Y'))
                                {{ number_format($y['total'],2) }}<br />
                                {{ number_format($y['total'],2) }}<br />
                                <?php 
                                    $limit_counter++; 
                                    $limit_total += ($y['total'])*2;  
                                ?>
                            @else
                                <?php break; ?>
                            @endif
                        @endif
                    @endif
                @endif
            @endforeach
        </td>
    </tr>   
              
        <tr class="">
            <td colspan=5 rowspan="2"  style="border:0px #ffffff00" >
                <table width="100%">
                    <tr>
                        <td class="text-hidden">
                            <div style="width:80%">
                                <!-- Payment without pernalty may be made within the periods stated below is by installment -->
                            </div>
                            <table width="90%" style="margin-top: 5px">
                                <tr>
                                    <td width="30%">1st Inst.</td>
                                    <td width="20%">_</td>
                                    <td width="50%"><!-- Jan 1. to Mar. 31 --></td>
                                </tr>
                                <tr>
                                    <td>2nd Inst.</td>
                                    <td>_</td>
                                    <td><!-- Apr. 1 to Jun. 30 --></td>
                                </tr>
                            </table>
                        </td>
                        <td width="60%" class="">
                            <table width="100%" >
                                <tr>
                                    <td colspan="2" class="text-hidden" ><!-- MODE OF PAYMENT --></td>
                                </tr>
                                <tr>
                                    <td width="70%" height="15px" class="text-hidden">CASH</td>
                                    <td style="padding-top: -10px;">{{ number_format($form56['total'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td height="15px" class="text-hidden">CHECK</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td height="15px" class="text-hidden">TW/PMO</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td height="15px" class="text-hidden" >TOTAL</td>
                                    <td style="padding-top: -15px;"> {{ number_format($form56['total'], 2) }}</td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            @if($limit_counter > $limit || $limit_end <= \Carbon\Carbon::now()->format('Y'))
                <td colspan="7" class="border-hidden text-right" style="background-color: ##7fe83e; padding-top: -38px;"><span class="text-hidden">TOTAL ></span> {{ number_format($limit_total, 2) }}</td> <!-- padding top -50 ok -->
            @else
                <td colspan="7" class="border-hidden text-right" style="background-color: ##7fe83e; padding-top: -38px;"><span class="text-hidden">TOTAL ></span> {{ number_format($form56['total'], 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="3" class="border-hidden" style="padding-top: -15px;">
                <div style="text-align: center; ">
                    {{$sign ? $acctble_officer_name->value : ''}}
                    <BR>
                    {{$sign ? $acctble_officer_position->value : ''}}
                </div>
            </td>
            <td colspan="3" class="border-hidden" style="padding-top: -15px;">
                <div style="text-align: center; ">
                    <!-- IMELDA I. MACANES -->
                    {{$sign ? 'IMELDA I. MACANES ' : ''}}
                    <BR>
                    <!-- PROVINCIAL TREASURER -->
                    {{$sign ? 'PROVINCIAL TREASURER ' : ''}}
                </div>
            </td>
        </tr>
    </table>
    @endif
<div class="bg">
</div>

</body>
</html>