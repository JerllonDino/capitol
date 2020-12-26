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
            color: rgba(rgba(255, 255, 255, 0), green, blue, alpha);
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

        table.fixed {
            width: 100%; 
            table-layout:fixed;
        }
        table.fixed td { 
            overflow: visible;
        }

        table.fixed th { 
            border-collapse: collapse;
            
        }
  
        
        table.main-values {
            /* margin: 0px 55px 0 8px; */
            /* border-color: #ffffff00; */
            /* background-color: ##42cbf4;  */
            position: absolute;
            top: 113px;
            left: 30px;
        }

        table.signatories{
            table-layout:fixed;
            width: 70%;
            position: absolute;
            top: 66.5%;
            left: 26.4%;
        }

        table.signatories td{
            overflow: visible;
        }
    </style>
</head>
<body>
    @foreach($annual_per_arp['yearly'] as $arp => $dataa)
        <?php
            $next_arp = array_search(next($annual_per_arp['yearly']), $annual_per_arp['yearly']);
            prev($annual_per_arp['yearly']);
            $prev_arp = array_search(prev($annual_per_arp['yearly']), $annual_per_arp['yearly']);
            next($annual_per_arp['yearly']);
            $limit = (count($form56['yearly']) > 4) ? 3 : count($form56['yearly']);
            $limit_counter = 0;
            $limit_end = 0;
            $advance = false;
            $entry_year = \Carbon\Carbon::parse($receipt->date_of_entry)->format('Y');
            $entry_year_adv = \Carbon\Carbon::parse($receipt->date_of_entry)->addYear()->format('Y');
            $least_yr = 0;

            if(isset($next_pg)) {
                if($next_pg == false) {
                    continue;
                }
            }
        ?>

        @foreach($dataa as $year => $val)
            <?php
                $kk = array_keys($dataa);
                $search_exist = array_search($entry_year_adv, $kk);
                // if ($search_exist >= 0 && $search_exist !== false && count($kk) > 1) {
                //     $advance = true;
                //     $next_pg = true;
                // }
            ?>

            {{-- @if($year <= $entry_year || (count($kk) == 1 && $year > $entry_year)) --}}
                <table width="100%" class="border-hidden" style="margin: 0 ; background: ##dbba7d; position: absolute; top: -5px;">
                    <tr>
                        <td colspan=2 rowspan=2 height='15%' style="padding: 0; margin: 0; background: ##a7e57b;">
                            <table width="100%" class="border-hidden" style="padding: 0; margin: 0;">
                                <tr>
                                    <td style="margin:0" width="15%"></td>
                                    <td style="text-align: right; background-color: ##f7e9d7;" width="50%">
                                        @if($wmunicipality)
                                            <b>{{strtoupper($receipt->municipality->name)}}, BENGUET</b>
                                        @endif
                                    </td>
                                    <td style="padding: 0; margin: 0; padding-left: -150px; background-color: ##fcba03;">
                                        @php
                                            $prev_date = '';
                                            $prev_receipt = '';
                                            $prev_year = '';
                                            $prev_remarks = '';
                                            $tax_type_name = '';
                                            if(isset($tax_type)) {
                                                if($tax_type == 5)
                                                    $tax_type_name = 'MTO';
                                                elseif($tax_type == 6)
                                                    $tax_type_name = 'PTO';
                                            }
                                            if(isset($receipt->F56Previuos)){
                                                $prev_year = $receipt->F56Previuos->col_receipt_year != '0000' ? $receipt->F56Previuos->col_receipt_year : '';
                                                $prev_receipt = $receipt->F56Previuos->col_receipt_no != '0' ? $receipt->F56Previuos->col_receipt_no : '';
                                                $prev_date =  new Carbon\Carbon($receipt->F56Previuos->col_receipt_date) ;
                                                $prev_date = $receipt->F56Previuos->col_receipt_date != '0000-00-00' ? $prev_date->toFormattedDateString() : '';  
                                                $prev_remarks = $receipt->F56Previuos->col_prev_remarks;
                                            }
                                        @endphp
                                        <div style="height:60px;margin-left: 140px; margin-top: -10px; background: ##b480fc;">
                                            <table width="95%" style="margin-top:0px;transform:translate(0, -3px)" class="border-hidden">
                                                <tbody>
                                                    <tr>
                                                        <td colspan=2 height='25px' class="border-hidden text-right" style="font-size: 12px;" >
                                                            <!-- PREVIOUS TAX RECEIPT NO. -->
                                                            {{ ($prev_receipt)  }}
                                                            <small>{{ $tax_type_name }}</small>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td height="28px"  width="100" class="border-hidden text-right"  style="font-size: 12px; background: ##4287f5;  vertical-align: bottom;">
                                                            {{  $prev_remarks }} 
                                                        </td>
                                                        <td height="28px"  width="100" class="border-hidden text-center"  style="font-size: 12px; background: ##4287f5;  vertical-align: bottom;">
                                                            {{  $prev_date }} 
                                                        </td>
                                                        <td class="border-hidden text-center" style="font-size: 12px; width:2.7cm; vertical-align: bottom; transform: translate(4px, 0)">
                                                            <!-- FOR THE YEAR -->
                                                            {{ $prev_year }}
                                                        </td>
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
                            <span style="font-size: 16px;padding:0;margin:0"></span>
                        </td>
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

                            $p_calendar_year = $first.'-'.$last;
                            if($first == $last){
                                $p_calendar_year = $first;
                            }
                         @endphp
                        <td class="border-hidden text-right" style="background: ##eda6eb;">
                            <!-- DATE -->
                            <div style="margin-bottom:13px; padding-right: 35px;">
                                {{ $date_entry->format('F d, Y') }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-hidden" height="20" style="background: ##a7e57b; padding-left: -20px;">
                            <!-- class="center" style="padding-left: -150px;" --> 
                            <div style="padding-left: 50px; padding-right: 45px; word-wrap: break-word;">
                                {!! dynamicFontSize($receipt->customer->name, 1) !!}
                            </div>
                        </td>
                        <td class="border-hidden" style="padding-left: -30px;">{{ $total_words }} only</td>
                        <td class="border-hidden text-right" style="text-indent: 13px; padding-right: 35px; background: ##a7e57b;transform:translate(0, -7px)"><br />{{ number_format($form56['total'], 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan=2 class="border-hidden" height="28">
                            <?php
                                $dets = $receipt['F56Detailmny'];
                                $full_part_unq = [];
                                foreach($dets as $d) {
                                    if(array_key_exists($d->full_partial, $full_part_unq) == false) {
                                        if(!isset($full_part_unq[$d->full_partial]['yr_from']))
                                            $full_part_unq[$d->full_partial]['yr_from'] = $d->period_covered;
                                        if(!isset($full_part_unq[$d->full_partial]['yr_to']))
                                            $full_part_unq[$d->full_partial]['yr_to'] = $d->period_covered;
                                        if(strlen($d->period_covered) > 4) {
                                            $spliit = explode('-', $d->period_covered);
                                            if($spliit[1] > $full_part_unq[$d->full_partial]['yr_to'])
                                                $full_part_unq[$d->full_partial]['yr_to'] = $spliit[1];

                                            if($spliit[0] < $full_part_unq[$d->full_partial]['yr_from'])
                                                $full_part_unq[$d->full_partial]['yr_from'] = $spliit[0];
                                        } else {
                                            if($d->period_covered > $full_part_unq[$d->full_partial]['yr_to'])
                                                $full_part_unq[$d->full_partial]['yr_to'] = $d->period_covered;

                                            if($d->period_covered < $full_part_unq[$d->full_partial]['yr_from'])
                                                $full_part_unq[$d->full_partial]['yr_from'] = $d->period_covered;
                                        }
                                    } else {
                                        if(strlen($d->period_covered) > 4) {
                                            $spliit = explode('-', $d->period_covered);
                                            if($spliit[1] > $full_part_unq[$d->full_partial]['yr_to'])
                                                $full_part_unq[$d->full_partial]['yr_to'] = $spliit[1];

                                            if($spliit[0] < $full_part_unq[$d->full_partial]['yr_from'])
                                                $full_part_unq[$d->full_partial]['yr_from'] = $spliit[0];
                                        } else {
                                            if($d->period_covered > $full_part_unq[$d->full_partial]['yr_to'])
                                                $full_part_unq[$d->full_partial]['yr_to'] = $d->period_covered;

                                            if($d->period_covered < $full_part_unq[$d->full_partial]['yr_from'])
                                                $full_part_unq[$d->full_partial]['yr_from'] = $d->period_covered;
                                        }
                                    }
                                }
                                // $full_partial_type = ['Full', '1st Qtr', '2nd Qtr', '3rd Qtr', '4th Qtr', 'Partial Advance', 'Balance Settlement', 'Backtax', 'Add\'l Payment'];
                                $full_partial_type = ['FP', '1st Qtr', '2nd Qtr', '3rd Qtr', '4th Qtr', 'PA', 'BS', 'BT', 'AP'];
                            ?>
                            <table width="100%" class="">
                                <tr>
                                    <td width="12%" class="text-hidden">Philippine currency, in</td>
                                    <td width="7%" style="background-color: ##bcf758;"><!-- <span style="border:1px solid"></span> -->
                                        <!-- <input type="checkbox" style="margin: 0; padding-left: 45px; font-size: 12px; " checked="checked"><br> -->
                                        <!-- full<br>
                                        installment -->
                                    </td>
                                    <td width="100%" style="padding-left: 25px; background: ##80fc03; text-align: center; float: right;font-size: 13px">
                                        <!-- <span class="text-hidden">payment of REAL PROPERTY TAX upon property(ies) described below for the Calendar Year ></span> -->

                                        @foreach($full_part_unq as $fp => $dates)
                                        
                                            @if(isset($full_partial_type[$fp]))
                                                <?php
                                                    $prep_yr_frm = '';
                                                    $prep_yr_to = '';
                                                    if(strlen($dates['yr_from']) > 4) {
                                                        $split_date = explode('-', $dates['yr_from']);
                                                        $prep_yr_frm = $split_date[0];
                                                    } else {
                                                        $prep_yr_frm = $dates['yr_from'];
                                                    }

                                                    if(strlen($dates['yr_to']) > 4) {
                                                        $split_date = explode('-', $dates['yr_to']);
                                                        $prep_yr_to = $split_date[1];
                                                    } else {
                                                        $prep_yr_to = $dates['yr_to'];
                                                    }
                                                ?>
                                            <!-- float: right; text-align: center;" -->
                                                {{-- @if($prep_yr_frm == $prep_yr_to)
                                                    <div style="word-wrap: break-word; background: ##e63e3e; padding-left: 75%;">
                                                        {{ $prep_yr_frm }}
                                                    </div>
                                                @else
                                                    <div style="word-wrap: break-word; background: ##e63e3e; padding-left: 75%;">
                                                        {{ $prep_yr_frm }}-{{ $prep_yr_to }}
                                                    </div>
                                                @endif --}}
                                            @else
                                                {{-- <span style="word-wrap: break-word; width: 100px; background: ##70fc41; padding-left: 75%;">
                                                        {{ $p_calendar_year }}
                                                </span> --}}
                                            @endif 
                                            
                                        @endforeach
                                        <div style="word-wrap: break-word; background: ##e63e3e; padding-left: 77%;">
                                            {{ $p_calendar_year }}
                                        </div>
                                    </td>
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

                <table class="fixed main-values">
                    <thead>
                        <tr style="text-align:center;">
                            <th class="border-hidden" style="width: 12.7%;"><span class="text-hidden">Name Of <br>DECLARED OWNER</span></th>
                            <th class="border-hidden" style="width: 13%" ><span class="text-hidden">Location<br>No./Street/Barangay</span></th>
                            <th class="border-hidden" style="width: 6.7%; text-align: left;"><span class="text-hidden">LOT<br>BLOCK NO.</span></th>
                            <th class="border-hidden" style="width: 6.7%; text-align: left;"><span class="text-hidden">TAX<br>DEC. NO</span></th>
                            <th class="border-hidden" style="width: 6%;"><span class="text-hidden">Land</span></th>
                            <th class="border-hidden" style="width: 6%"><span class="text-hidden">Improvement</span></th>
                            <th class="border-hidden" style="width: 6.4%"><span class="text-hidden">Total</span></th>
                            <th class="border-hidden" style="width: 9.5%"><span class="text-hidden">TAX DUE</span></th>
                            <th class="border-hidden" style="width: 6%"><span class="text-hidden">NO.</span></th>
                            <th class="border-hidden" style="width: 6.2%"><span class="text-hidden">Payment</span></th>
                            <th class="border-hidden" style="width: 5.2%"><span class="text-hidden">Full Payment</span></th>
                            <th class="border-hidden" style="width: 9%"><span class="text-hidden">Penalty</span></th>
                            <th class="border-hidden" style="width: 6.7%"><span class="text-hidden">TOTAL</span></th>
                        </tr>
                    </thead>
                    @php
                        $count_tr = 0;
                        $period_covered  = '';
                        $count_tdrp = (count($receipt->F56Detailmny));
                        $owner = '';
                    @endphp

                    <tr style="background: ##ef7385;">
                        <td class="border-hidden text-left vertical-top">
                            @if(isset($annual_per_arp[$arp]['owner']))
                            {!! dynamicFontSize($annual_per_arp[$arp]['owner'], 1) !!}
                            @endif
                        </td>

                        <!-- width: 1.2cm; -->
                        <td class="border-hidden text-left vertical-top">
                            @if(!is_null($val['brgy']))
                                {{ $val['brgy']->name }}
                            @endif
                            @php
                                $data_array = [];   
                            @endphp
                            @foreach ($dataa as $data)
                                @if (!in_array($data['tax_type'], $data_array))
                                    <br>
                                    {{ $data_array[] = $data['tax_type'] }}
                                @endif
                            @endforeach
                        </td>

                        <!-- width: 1cm; -->
                        <!-- padding-left: -70px; -->
                        <td class="border-hidden text-left vertical-top" colspan="2">
                            
                            @if($limit_counter <= $limit) 
                                @foreach($annual_arp as $this_arp => $data)
                                    <?php 
                                        // next($annual_arp);
                                        // $this_arp_next = key($annual_arp);
                                        // prev($annual_arp);
                                        // prev($annual_arp);
                                        // $this_arp_prev = key($annual_arp);
                                        // next($annual_arp);

                                        $array_keys = array_keys($annual_arp);
                                        $this_arp_next = '';
                                        $this_arp_prev = null;
                                        foreach($array_keys as $i => $key) {
                                            if($this_arp == $key) {
                                                $this_arp_next = isset($array_keys[$i+1]) ? $array_keys[$i+1] : null;
                                                // $this_arp_prev = isset($array_keys[$i-1]) ? $array_keys[$i-1] : null;
                                            }
                                        }

                                        // quickfix haha
                                        if(!isset($count)) {
                                            $count = [];
                                        }
                                        if(!isset($count[$arp])) {
                                            $count[$arp] = 0;
                                        }
                                    ?>
                                    @if(isset($annual_per_arp['yearly'][$this_arp][$year]))
                                        @if($this_arp_next !== false && $this_arp_next !== null && $arp != $this_arp_next)
                                            @if(isset($annual_per_arp[$this_arp_next]))
                                                @foreach($annual_arp as $this_arp => $data)
                                                    @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp)
                                                        {{ $this_arp }}<br>
                                                        {{ $this_arp_next }}<br>
                                                        <?php break; ?>
                                                    @elseif($annual_per_arp[$this_arp]['prev_tax_dec_no'] != $this_arp_next && $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] != $this_arp)
                                                        @if(isset($count[$arp]))
                                                            @if($count[$arp] <= 0) 
                                                                {{ $arp }}<br>
                                                                <?php
                                                                    $count[$arp]++;
                                                                ?>
                                                            @endif
                                                        @endif
                                                        <?php 
                                                            break; 
                                                        ?>
                                                    @endif
                                                @endforeach
                                            @else
                                                @if(isset($count[$arp]))
                                                    @if($count[$arp] <= 0) 
                                                        {{ $arp }}<br>
                                                        <?php $count[$arp]++; ?>
                                                    @endif
                                                @endif
                                                <?php break; ?>
                                            @endif
                                        @elseif($this_arp_prev !== false && $this_arp_prev !== null)
                                            @if(isset($annual_per_arp[$this_arp_prev]))
                                                @foreach($annual_arp as $this_arp => $data)
                                                    @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_prev || $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] == $this_arp)
                                                        {{ $this_arp_prev }}<br>
                                                        {{ $this_arp }}<br>
                                                        <?php break; ?>
                                                    @elseif($annual_per_arp[$this_arp]['prev_tax_dec_no'] != $this_arp_prev && $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] != $this_arp)
                                                        @if(isset($count[$arp]))
                                                            @if($count[$arp] <= 0)
                                                                {{ $arp }}<br>
                                                                <?php $count[$arp]++; ?>
                                                            @endif
                                                        @endif
                                                        <?php break; ?>
                                                    @endif
                                                @endforeach
                                            @else
                                                @if(isset($count[$arp]))
                                                    @if($count[$arp] <= 0)
                                                        {{ $arp }}<br>
                                                        <?php $count[$arp]++; ?>
                                                    @endif
                                                @endif
                                                <?php break; ?>
                                            @endif
                                        @elseif($this_arp == $arp)
                                            @if($this_arp_next == null && $this_arp_prev == null && count(array_keys($annual_arp)) == 1) 
                                                @if(isset($count[$arp]))
                                                    @if($count[$arp] <= 0)
                                                        {{ $arp }}<br>
                                                        <?php $count[$arp]++; ?>
                                                    @endif
                                                @endif
                                                <?php break; ?>
                                            @elseif($this_arp_next == null && $this_arp_prev == null)
                                                @if(isset($count[$arp]))
                                                    @if($count[$arp] <= 0)
                                                        {{ $arp }}<br>
                                                        <?php $count[$arp]++; ?>
                                                    @endif
                                                @endif
                                                <?php break; ?>
                                            @endif
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                        </td>

                        <!-- LAND -->
                        <!-- padding-left: -78px; -->
                        <td class="border-hidden text-left vertical-top">
                            @if(isset($annual_per_arp[$arp]['assess_val_class']))
                                @foreach($annual_per_arp[$arp]['assess_val_class'] as $index => $val)
                                    @if(!is_null($val['kind']))
                                        @if(preg_match('/building/i', $val['kind']) != 1)
                                            {!! dynamicFontSize(number_format($val['assess_val'],2)) !!}<br><br>
                                        @else
                                            <br>
                                        @endif
                                    @elseif(!is_null($val['actual_use']))
                                        @if(preg_match('/bldg/i', $val['actual_use']) != 1)
                                            {!! dynamicFontSize(number_format($val['assess_val'],2)) !!}<br><br>
                                        @else
                                            <br>
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                        </td>

                        <!--  padding-right: 5px; -->
                        <!-- IMPROVEMENT (BUILDING/MACHINE) -->
                        <td class="border-hidden text-right vertical-top">
                            @if(isset($annual_per_arp[$arp]['assess_val_class']))
                                @foreach($annual_per_arp[$arp]['assess_val_class'] as $index => $val)
                                    @if(!is_null($val['kind']))
                                        @if(preg_match('/building/i', $val['kind']) == 1)
                                            {!! dynamicFontSize(number_format($val['assess_val'],2)) !!}<br><br>
                                        @else
                                            <br>
                                        @endif
                                    @elseif(!is_null($val['actual_use']))
                                        @if(preg_match('/bldg/i', $val['actual_use']) == 1)
                                            {!! dynamicFontSize(number_format($val['assess_val'],2)) !!}<br><br>
                                        @else
                                            <br>
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                        </td>

                        <!-- padding-right: -25px; width: 1.5cm;  -->
                        <td class="border-hidden text-left vertical-top text-right">
                            
                            <!-- assessed value TOTAL -->
                            <?php 
                                // $total_assess_val = 0; 
                                $limit_counter = 0; 
                                
                            ?>
                            @if($limit_counter <= $limit)
                                @foreach($annual_arp as $this_arp => $data)
                                    <?php 
                                        // next($annual_arp);
                                        // $this_arp_next = key($annual_arp);
                                        // prev($annual_arp);
                                        // prev($annual_arp);
                                        // $this_arp_prev = key($annual_arp);
                                        // next($annual_arp);

                                        $array_keys = array_keys($annual_arp);
                                        $this_arp_next = '';
                                        $this_arp_prev = null;
                                        foreach($array_keys as $i => $key) {
                                            if($this_arp == $key) {
                                                $this_arp_next = isset($array_keys[$i+1]) ? $array_keys[$i+1] : null;
                                                // $this_arp_prev = isset($array_keys[$i-1]) ? $array_keys[$i-1] : null;
                                            }
                                        }
                                        
                                    ?>
                                    @if(isset($annual_per_arp['yearly'][$this_arp][$year]))
                                        @if($this_arp_next != false)
                                            @if(isset($annual_per_arp[$this_arp_next]))
                                                @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp)

                                                    {!! dynamicFontSize(number_format($annual_per_arp[$this_arp]['assess_val'], 2)) !!}<br><br>
                                                    {!! dynamicFontSize(number_format($annual_per_arp[$this_arp_next]['assess_val'], 2)) !!}<br><br>
                                                @elseif($annual_per_arp[$this_arp]['prev_tax_dec_no'] != $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] != $this_arp)
                                                    {!! dynamicFontSize(number_format($annual_per_arp[$arp]['assess_val'], 2)) !!}<br><br>
                                                @endif
                                            @else
                                                {!! dynamicFontSize(number_format($val['assess_val'], 2)) !!}
                                            @endif
                                        @elseif($this_arp_prev != false)
                                            @if(isset($annual_per_arp[$this_arp_prev]))
                                                @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_prev || $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] == $this_arp)
                                                    {!! dynamicFontSize(number_format($annual_per_arp[$this_arp_prev]['assess_val'], 2)) !!}<br><br>
                                                    {!! dynamicFontSize(number_format($annual_per_arp[$this_arp]['assess_val'], 2)) !!}<br><br>
                                                @elseif($annual_per_arp[$this_arp]['prev_tax_dec_no'] != $this_arp_prev || $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] != $this_arp)
                                                    {!! dynamicFontSize(number_format($annual_per_arp[$arp]['assess_val'], 2)) !!}<br><br>
                                                @endif
                                            @else
                                                {!! dynamicFontSize(number_format($val['assess_val'], 2)) !!}
                                            @endif
                                        @elseif($this_arp == $arp)
                                            {{-- @if($this_arp_next == null && $this_arp_prev == null && count(array_keys($annual_arp[$arp])) == 1)  --}}
                                                {{-- number_format($val['assess_val'], 2) --}}
                                            {{-- @else --}}
                                                @if(isset($annual_per_arp[$this_arp]['assess_val_class']))
                                                    @foreach($annual_per_arp[$this_arp]['assess_val_class'] as $i => $val)
                                                        {!! dynamicFontSize(number_format($val['assess_val'], 2)) !!}<br><br>
                                                    @endforeach
                                                @endif
                                            {{-- @endif --}}
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                        </td>

                        <!-- INSTALLMENT -->
                        <?php
                            $limit_counter = 0;
                            $year_to = 0;
                            $total_tax_due = [];
                            $total_penalty = [];
                            $total_discount = [];
                            $least_year = 0;
                            $limit_total = 0;
                            $next_pg = true;
                        ?>


                            @foreach($annual_arp as $this_arp => $data)
                                <?php 
                                    $array_keys = array_keys($annual_arp);
                                    $this_arp_next = '';
                                    $this_arp_prev = null;
                                    foreach($array_keys as $i => $key) {
                                        if($this_arp == $key) {
                                            $this_arp_next = isset($array_keys[$i+1]) ? $array_keys[$i+1] : null;
                                            // $this_arp_prev = isset($array_keys[$i-1]) ? $array_keys[$i-1] : null;
                                        }
                                    }
                                    $keys = array_keys($data);
                                ?>
                                @if(isset($annual_per_arp['yearly'][$this_arp][$year]))
                                    @if($this_arp_next != false)
                                        @if(isset($annual_per_arp[$this_arp_next]))
                                            @foreach($annual_arp as $this_arp => $data)
                                                @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp)
                                                    @foreach($annual_per_arp['yearly'] as $this_arp2 => $data2)
                                                        <?php 
                                                            if(!isset($total_penalty[$this_arp2])) {
                                                                $total_penalty[$this_arp2] = 0;
                                                            }
                                                            if(!isset($total_discount[$this_arp2])) {
                                                                $total_discount[$this_arp2] = 0;
                                                            }
                                                            if($this_arp2 == $this_arp || $this_arp2 == $this_arp_next) {                  
                                                                foreach($data2 as $year2 => $val2) {
                                                                    $total_penalty[$this_arp2] += $val2['penalty'];
                                                                    $total_discount[$this_arp2] += $val2['discount'];

                                                                    if(strlen($year2) > 4) {
                                                                        $year_to = $year2 > $year_to ? $year2 : $year_to;
                                                                    } else {
                                                                        $year_to = $year2 > $year_to ? $year2 : $year_to;
                                                                    }

                                                                    if(!isset($total_tax_due[$this_arp2])) {
                                                                        $total_tax_due[$this_arp2] = 0;
                                                                    }
                                                                    for($i = 0; $i < count($keys); $i++) {
                                                                        // if($year2 < $entry_year_adv && $advance == true) {
                                                                        //     if(strlen($year2) > 4) {
                                                                        //         $total_tax_due[$this_arp2] += $val2['sef'];
                                                                        //     } else {
                                                                        //         $total_tax_due[$this_arp2] += $val2['assess_val']*.01;
                                                                        //     }
                                                                        // } else if($year2 < $entry_year_adv && $year2 >= $keys[$i]) {
                                                                            if(strlen($year2) > 4) {
                                                                                $total_tax_due[$this_arp2] += $val2['sef'];
                                                                                $year_to = $year2 > $year_to ? $year2 : $year_to;
                                                                            } else {
                                                                                $total_tax_due[$this_arp2] += $val2['assess_val']*.01;
                                                                            }
                                                                        // }
                                                                    }
                                                                }
                                                            }
                                                        ?>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif($this_arp_prev != false)
                                        @if(isset($annual_per_arp[$this_arp_prev])) 
                                            @foreach($annual_arp as $this_arp => $data)
                                                @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_prev || $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] == $this_arp)
                                                    @foreach($annual_per_arp['yearly'] as $this_arp2 => $data2)
                                                        <?php 
                                                            if($this_arp2 == $this_arp || $this_arp2 == $this_arp_prev) {
                                                                foreach($data2 as $year2 => $val2) {
                                                                    if(strlen($year2) > 4) {
                                                                        // $total_tax_due += $val2['sef'];
                                                                        $year_to = $year2 > $year_to ? $year2 : $year_to;
                                                                    } else {
                                                                        // $total_tax_due += $val2['assess_val']*.01;
                                                                        $year_to = $year2 > $year_to ? $year2 : $year_to;
                                                                    }
                                                                }
                                                            }
                                                        ?>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                    @endif
                                @endif
                            @endforeach

                            {{-- LUMP DIFFERENT PAYMENT TYPES --}}
                            @php
                                $display = [];   
                            @endphp
                            @foreach ($annual_per_arp['yearly'][$this_arp] as $detail)
                                    @php
                                        $display[$detail['full_partial']][] = $detail; 
                                    @endphp
                            @endforeach
                            {{-- TAX DUE AND TYPE --}}
                            <td class="border-hidden vertical-top">
                                <div style="margin: 0; padding: 0; text-align: center;"> 
                                    

                                    @foreach ($display as $payment_type => $lumped)
                                        @php
                                            $printedSef='';
                                            $dates = [];
                                            
                                            // if ($payment_type == 7) {

                                            //     echo(number_format($lumped[0]['sef'], 2)."<br>"."(".$lumped[0]['period_covered'] . (count($lumped) > 1 ? "-".$lumped[count($lumped)-1]['period_covered'] : "").")<br>");
                                            //     continue;
                                            // }
                                        @endphp
                                        @foreach ($lumped as $key => $data)
                                            @php
                                                if(count($lumped) == 1){
                                                    echo(number_format($data['sef'], 2)."<br>(". $data['period_covered'] .")<br>");
                                                }else{
                                                    if(isset($lumped[$key-1])){
                                                        if ($data['discount'] == 0 and $data['penalty'] != 0 and $key < count($lumped)-1) {
                                                            if ($data['penalty'] == $lumped[$key-1]['penalty'] and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                                array_push($dates, $data['period_covered']);
                                                            }else{
                                                                $dates = [];
                                                                array_push($dates, $data['period_covered']);
                                                            }
                                                        }
                                                        if ($data['penalty'] == 0 and $data['discount'] != 0 and $key < count($lumped)-1) {
                                                            
                                                            if ($data['discount'] == $lumped[$key-1]['discount']) {
                                                                array_push($dates, $data['period_covered'] and $data['assess_val'] == $lumped[$key-1]['assess_val']);
                                                            }else{
                                                                $dates = [];
                                                                array_push($dates, $data['period_covered']);
                                                            }
                                                        }
                                                        if ($data['penalty'] == 0 and $data['discount'] == 0 and $key < count($lumped)-1) {
                                                            if ($lumped[$key-1]['penalty'] == 0 and $lumped[$key-1]['discount'] == 0 and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                                array_push($dates, $data['period_covered']);
                                                            }else{
                                                                $dates = [];
                                                                array_push($dates, $data['period_covered']);
                                                            }
                                                            
                                                        }
                                                    }else{
                                                        if ($data['discount'] == 0 and $data['penalty'] != 0) {
                                                            array_push($dates, $data['period_covered']);
                                                        }
                                                        if ($data['penalty'] == 0 and $data['discount'] != 0) {
                                                            
                                                            array_push($dates, $data['period_covered']);
                                                        }
                                                        if ($data['penalty'] == 0 and $data['discount'] == 0) {
                                                            array_push($dates, $data['period_covered']);
                                                        }
                                                    }
                                                    if (isset($lumped[$key+1])) {
                                                        if ($data['discount'] == 0 and $data['penalty'] != 0) {
                                                            
                                                            if ($data['penalty'] == $lumped[$key+1]['penalty'] and $data['assess_val'] == $lumped[$key+1]['assess_val']) {
                                                                continue;
                                                            }else{
                                                                echo(( $printedSef == $data['sef'] ? '' : number_format($data['sef'], 2))."<br>"."(".$dates[0] . (count($dates) > 1 ? "-".$dates[count($dates)-1] : "").")<br>");
                                                                $printedSef = $data['sef'];
                                                            }
                                                        }if ($data['penalty'] == 0 and $data['discount'] != 0) {
                                                            if ($data['discount'] == $lumped[$key+1]['discount'] and $data['assess_val'] == $lumped[$key+1]['assess_val']) {
                                                                continue;
                                                            }else{
                                                                echo(( $printedSef == $data['sef'] ? '' : number_format($data['sef'], 2))."<br>"."(".$dates[0] . (count($dates) > 1 ? "-".$dates[count($dates)-1] : "").")<br>");
                                                                $printedSef = $data['sef'];
                                                            }
                                                        }
                                                        if ($data['penalty'] == 0 and $data['discount'] == 0) {
                                                            if ($lumped[$key+1]['penalty'] == 0 and $lumped[$key+1]['discount'] == 0 and $data['assess_val'] == $lumped[$key+1]['assess_val']) {
                                                                continue;
                                                            }else{
                                                                echo(( $printedSef == $data['sef'] ? '' : number_format($data['sef'], 2))."<br>"."(".$dates[0] . (count($dates) > 1 ? "-".$dates[count($dates)-1] : "").")<br>");
                                                                $printedSef = $data['sef'];
                                                            }
                                                        }
                                                        
                                                    }else{
                                                        if ($data['discount'] == 0 and $data['penalty'] != 0) {
                                                            if ($data['penalty'] == $lumped[$key-1]['penalty'] and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                                array_push($dates, $data['period_covered']);
                                                                echo(( $printedSef == $data['sef'] ? '' : number_format($data['sef'], 2))."<br>"."(".$dates[0] . (count($dates) > 1 ? "-".$dates[count($dates)-1] : "").")<br>");
                                                            }else{
                                                                $dates = [];
                                                                array_push($dates, $data['period_covered']);
                                                                echo(( $printedSef == $data['sef'] ? '' : number_format($data['sef'], 2))."<br>"."(".$dates[0] . (count($dates) > 1 ? "-".$dates[count($dates)-1] : "").")<br>");
                                                            }
                                                            
                                                            
                                                        }
                                                        if ($data['penalty'] == 0 and $data['discount'] != 0) {
                                                            if ($data['discount'] == $lumped[$key-1]['discount'] and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                                array_push($dates, $data['period_covered']);
                                                                echo(( $printedSef == $data['sef'] ? '' : number_format($data['sef'], 2))."<br>"."(".$dates[0] . (count($dates) > 1 ? "-".$dates[count($dates)-1] : "").")<br>");
                                                            }else{
                                                                $dates = [];
                                                                array_push($dates, $data['period_covered']);
                                                                echo(( $printedSef == $data['sef'] ? '' : number_format($data['sef'], 2))."<br>"."(".$dates[0] . (count($dates) > 1 ? "-".$dates[count($dates)-1] : "").")<br>");
                                                            }
                                                        }
                                                        if ($data['penalty'] == 0 and $data['discount'] == 0) {
                                                            if ($lumped[$key-1]['penalty'] == 0 and $lumped[$key-1]['discount'] == 0 and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                                array_push($dates, $data['period_covered']);
                                                                echo(( $printedSef == $data['sef'] ? '' : number_format($data['sef'], 2))."<br>"."(".$dates[0] . (count($dates) > 1 ? "-".$dates[count($dates)-1] : "").")<br>");
                                                            }else{
                                                                $dates = [];
                                                                array_push($dates, $data['period_covered']);
                                                                echo(( $printedSef == $data['sef'] ? '' : number_format($data['sef'], 2))."<br>"."(".$dates[0] . (count($dates) > 1 ? "-".$dates[count($dates)-1] : "").")<br>");
                                                            }
                                                        }
                                                    }
                                                }
                                            @endphp
                                        @endforeach
                                    @endforeach
                                                                            
                                </div>
                            </td>

                            <td class="border-hidden text-left vertical-top" style="text-align: center">
                                @foreach ($display as $payment_type => $lumped)
                                    @php
                                        $computedValue = 0;
                                        // if ($payment_type == 7) {
                                        //     echo("BASIC<br>");
                                        //     echo("SEF<br>");
                                        //     continue;
                                        // }
                                    @endphp
                                    @foreach ($lumped as $key => $data)
                                        @php
                                            if(count($lumped) == 1){
                                                echo("BASIC<br>");
                                                echo("SEF<br>");
                                            }else{
                                                if(isset($lumped[$key-1])){
                                                    if ($data['discount'] == 0 and $data['penalty'] != 0 and $key < count($lumped)-1) {
                                                        if ($data['penalty'] == $lumped[$key-1]['penalty'] and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                            $computedValue = $data['sef'];
                                                        }else{
                                                            $computedValue = $data['sef'];
                                                        }
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] != 0 and $key < count($lumped)-1) {
                                                        if ($data['discount'] == $lumped[$key-1]['discount'] and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                            $computedValue = $data['sef'];
                                                        }else{
                                                            $computedValue = $data['sef'];
                                                        }
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] == 0 and $key < count($lumped)-1) {
                                                        if ($lumped[$key-1]['penalty'] == 0 and $lumped[$key-1]['discount'] == 0 and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                            $computedValue = $data['sef'];
                                                        }else{
                                                            $computedValue = $data['sef'];
                                                        }
                                                        
                                                    }
                                                }else{
                                                    if ($data['discount'] == 0 and $data['penalty'] != 0) {
                                                        $computedValue = $data['sef'];
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] != 0) {
                                                        $computedValue = $data['sef'];
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] == 0) {
                                                        $computedValue = $data['sef'];
                                                    }
                                                }
                                                if (isset($lumped[$key+1])) {
                                                    if ($data['discount'] == 0 and $data['penalty'] != 0) {
                                                        if ($data['penalty'] == $lumped[$key+1]['penalty'] and $data['assess_val'] == $lumped[$key+1]['assess_val']) {
                                                            continue;
                                                        }else{
                                                            
                                                            echo("BASIC<br>");
                                                            echo("SEF<br>");
                                                            
                                                        }
                                                    }if ($data['penalty'] == 0 and $data['discount'] != 0) {
                                                        if ($data['discount'] == $lumped[$key+1]['discount'] and $data['assess_val'] == $lumped[$key+1]['assess_val']) {
                                                            continue;
                                                        }else{
                                                            echo("BASIC<br>");
                                                            echo("SEF<br>");
                                                            
                                                        }
                                                    }if ($data['penalty'] == 0 and $data['discount'] == 0) {
                                                        if ($lumped[$key+1]['penalty'] == 0 and $lumped[$key+1]['discount'] == 0 and $data['assess_val'] == $lumped[$key+1]['assess_val']) {
                                                            continue;
                                                        }else{
                                                            echo("BASIC<br>");
                                                            echo("SEF<br>");
                                                        }
                                                    }
                                                    
                                                }else{
                                                    if ($data['discount'] == 0 and $data['penalty'] != 0) {
                                                        if ($data['penalty'] == $lumped[$key-1]['penalty'] and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                            $computedValue = $data['sef'];
                                                            echo("BASIC<br>");
                                                            echo("SEF<br>");
                                                        }else{
                                                            $computedValue = $data['sef'];
                                                            echo("BASIC<br>");
                                                            echo("SEF<br>");
                                                        }
                                                        
                                                        
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] != 0) {
                                                        if ($data['discount'] == $lumped[$key-1]['discount'] and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                            $computedValue = $data['sef'];
                                                            echo("BASIC<br>");
                                                            echo("SEF<br>");
                                                        }else{
                                                            $computedValue = $data['sef'];
                                                            echo("BASIC<br>");
                                                            echo("SEF<br>");
                                                        }
                                                        
                                                        
                                                    }if ($data['penalty'] == 0 and $data['discount'] == 0) {
                                                        if ($lumped[$key-1]['penalty'] == 0 and $lumped[$key-1]['discount'] == 0 and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                            $computedValue = $data['sef'];
                                                            echo("BASIC<br>");
                                                            echo("SEF<br>");
                                                        }else{
                                                            $computedValue = $data['sef'];
                                                            echo("BASIC<br>");
                                                            echo("SEF<br>");
                                                        }
                                                    }
                                                }
                                            }
                                        @endphp
                                    @endforeach
                                @endforeach
                            </td>
                            {{-- FULL PAYMENT --}}
                            <td class="border-hidden text-right vertical-top" style="text-align: center">
                                @foreach ($display as $payment_type => $lumped)
                                    @php
                                        $computedValue = 0;
                                        // if ($payment_type == 7) {
                                        //     $sum = 0;
                                        //     foreach ($lumped as $key => $value) {
                                        //         $sum += $value['sef'];
                                        //     }
                                        //     echo(number_format($sum,2)."<br>");
                                        //     echo(number_format($sum,2)."<br>");
                                        //     continue;
                                        // }
                                        // dd($display);
                                    @endphp

                                    @foreach ($lumped as $key => $data)
                                        @php
                                            if(count($lumped) == 1){
                                                echo(dynamicFontSize(number_format($data['sef'],2))."<br>");
                                                echo(dynamicFontSize(number_format($data['sef'],2))."<br>");
                                            }else{
                                                if(isset($lumped[$key-1])){
                                                    if ($data['discount'] == 0 and $data['penalty'] != 0 and $key < count($lumped)-1) {
                                                        if ($data['penalty'] == $lumped[$key-1]['penalty'] and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                            $computedValue += $data['sef'];
                                                        }else{
                                                            $computedValue = $data['sef'];
                                                        }
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] != 0 and $key < count($lumped)-1) {
                                                        if ($data['discount'] == $lumped[$key-1]['discount'] and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                            $computedValue += $data['sef'];
                                                        }else{
                                                            $computedValue = $data['sef'];
                                                        }
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] == 0 and $key < count($lumped)-1) {
                                                        if ($lumped[$key-1]['penalty'] == 0 and $lumped[$key-1]['discount'] == 0 and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                            $computedValue += $data['sef'];
                                                            
                                                        }else{
                                                            $computedValue = $data['sef'];
                                                        }
                                                        
                                                    }
                                                }else{
                                                    if ($data['discount'] == 0 and $data['penalty'] != 0) {
                                                        $computedValue = $data['sef'];
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] != 0) {
                                                        $computedValue = $data['sef'];
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] == 0) {
                                                        $computedValue = $data['sef'];
                                                    }
                                                }
                                                if (isset($lumped[$key+1])) {
                                                    if ($data['discount'] == 0 and $data['penalty'] != 0) {
                                                        if ($data['penalty'] == $lumped[$key+1]['penalty'] and $data['assess_val'] == $lumped[$key+1]['assess_val']) {
                                                            continue;
                                                        }else{
                                                            
                                                            echo(dynamicFontSize(number_format($computedValue, 2))."<br>");
                                                            echo(dynamicFontSize(number_format($computedValue, 2))."<br>");
                                                            
                                                        }
                                                    }if ($data['penalty'] == 0 and $data['discount'] != 0) {
                                                        if ($data['discount'] == $lumped[$key+1]['discount'] and $data['assess_val'] == $lumped[$key+1]['assess_val']) {
                                                            continue;
                                                        }else{
                                                            echo(dynamicFontSize(number_format($computedValue, 2))."<br>");
                                                            echo(dynamicFontSize(number_format($computedValue, 2))."<br>");
                                                            
                                                        }
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] == 0) {
                                                        if ($lumped[$key+1]['penalty'] == 0 and $lumped[$key+1]['discount'] == 0 and $data['assess_val'] == $lumped[$key+1]['assess_val']) {
                                                            continue;
                                                        }else{
                                                            echo(dynamicFontSize(number_format($computedValue, 2))."<br>");
                                                            echo(dynamicFontSize(number_format($computedValue, 2))."<br>");
                                                        }
                                                    }
                                                    
                                                }else{
                                                    
                                                    if ($data['discount'] == 0 and $data['penalty'] != 0) {
                                                        if ($data['penalty'] == $lumped[$key-1]['penalty'] and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                            $computedValue += $data['sef'];
                                                            echo(dynamicFontSize(number_format($computedValue, 2))."<br>");
                                                            echo(dynamicFontSize(number_format($computedValue, 2))."<br>");
                                                        }else{
                                                            $computedValue = $data['sef'];
                                                            echo(dynamicFontSize(number_format($computedValue, 2))."<br>");
                                                            echo(dynamicFontSize(number_format($computedValue, 2))."<br>");
                                                        }
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] != 0) {
                                                        if ($data['discount'] == $lumped[$key-1]['discount'] and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                            $computedValue += $data['sef'];
                                                            echo(dynamicFontSize(number_format($computedValue, 2))."<br>");
                                                            echo(dynamicFontSize(number_format($computedValue, 2))."<br>");
                                                        }else{
                                                            $computedValue = $data['sef'];
                                                            echo(dynamicFontSize(number_format($computedValue, 2))."<br>");
                                                            echo(dynamicFontSize(number_format($computedValue, 2))."<br>");
                                                        }
                                                        
                                                        
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] == 0) {
                                                        
                                                        if ($lumped[$key-1]['penalty'] == 0 and $lumped[$key-1]['discount'] == 0 and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                            $computedValue += $data['sef'];
                                                            echo(dynamicFontSize(number_format($computedValue, 2))."<br>");
                                                            echo(dynamicFontSize(number_format($computedValue, 2))."<br>");
                                                        }else{
                                                            $computedValue = $data['sef'];
                                                            echo(dynamicFontSize(number_format($computedValue, 2))."<br>");
                                                            echo(dynamicFontSize(number_format($computedValue, 2))."<br>");
                                                        }
                                                        
                                                    }
                                                    
                                                }
                                            }
                                        @endphp
                                    @endforeach
                                @endforeach
                                                            
                            </td>
                            {{-- PENALTY OR DISCOUNT --}}
                            <td class="border-hidden text-right vertical-top" style="text-align: center">
                                @foreach ($display as $payment_type => $lumped)
                                    @php
                                        $computedValue = 0;
                                        // if ($payment_type == 7) {
                                        //     echo(number_format($lumped[0]['penalty'],2)."<br>");
                                        //     echo(number_format($lumped[0]['penalty'],2)."<br>");
                                        //     continue;
                                        // }
                                    @endphp
                                    @foreach ($lumped as $key => $data)
                                        @php
                                            if(count($lumped) == 1){
                                                if ($data['penalty'] != 0 and $data['discount'] == 0) {
                                                    echo(number_format($data['penalty'],2)."<br>");
                                                    echo(number_format($data['penalty'],2)."<br>");
                                                }
                                                if ($data['discount'] != 0 and $data['penalty'] == 0) {
                                                    echo("(".number_format($data['discount'],2).")<br>");
                                                    echo("(".number_format($data['discount'],2).")<br>");
                                                }

                                                if($data['discount'] == 0 and $data['penalty'] == 0){
                                                    echo("0.00<br>");
                                                    echo("0.00<br>");
                                                }
                                                
                                            }else{
                                                if(isset($lumped[$key-1])){
                                                    if ($data['discount'] == 0 and $data['penalty'] != 0 and $key < count($lumped)-1) {
                                                        
                                                        if ($data['penalty'] == $lumped[$key-1]['penalty'] and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                            
                                                            $computedValue += $data['penalty'];
                                                        }else{
                                                            $computedValue = $data['penalty'];
                                                        }
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] != 0 and $key < count($lumped)-1) {
                                                        if ($data['discount'] == $lumped[$key-1]['discount'] and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                            $computedValue += $data['discount'];
                                                        }else{
                                                            $computedValue = $data['discount'];
                                                        }
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] == 0 and $key < count($lumped)-1) {
                                                        if ($lumped[$key-1]['penalty'] == 0 and $lumped[$key-1]['discount'] == 0 and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                            $computedValue = 0;
                                                        }else{
                                                            $computedValue = 0;
                                                        }
                                                    }
                                                }else{
                                                    if ($data['discount'] == 0 and $data['penalty'] != 0) {
                                                        $computedValue = $data['penalty'];
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] != 0) {
                                                        $computedValue = $data['discount'];
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] == 0) {
                                                        $computedValue = 0;
                                                    }
                                                }
                                                if (isset($lumped[$key+1])) {
                                                    if ($data['discount'] == 0 and $data['penalty'] != 0) {
                                                        if ($data['penalty'] == $lumped[$key+1]['penalty'] and $data['assess_val'] == $lumped[$key+1]['assess_val']) {
                                                            continue;
                                                        }else{
                                                            
                                                            echo(number_format($computedValue, 2)."<br>");
                                                            echo(number_format($computedValue, 2)."<br>");
                                                            
                                                        }
                                                    }if ($data['penalty'] == 0 and $data['discount'] != 0) {
                                                        if ($data['discount'] == $lumped[$key+1]['discount'] and $data['assess_val'] == $lumped[$key+1]['assess_val']) {
                                                            continue;
                                                        }else{
                                                            echo("(".number_format($computedValue, 2).")<br>");
                                                            echo("(".number_format($computedValue, 2).")<br>");
                                                        }
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] == 0) {
                                                        if ($lumped[$key+1]['penalty'] == 0 and $lumped[$key+1]['discount'] == 0 and $data['assess_val'] == $lumped[$key+1]['assess_val']) {
                                                            continue;
                                                        }else{
                                                            echo("0.00<br>");
                                                            echo("0.00<br>");
                                                        }
                                                    }
                                                    
                                                }else{
                                                    if ($data['discount'] == 0 and $data['penalty'] != 0) {
                                                        if ($data['penalty'] == $lumped[$key-1]['penalty'] and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                            $computedValue += $data['penalty'];
                                                            echo(number_format($computedValue, 2)."<br>");
                                                            echo(number_format($computedValue, 2)."<br>");
                                                        }else{
                                                            $computedValue = $data['penalty'];
                                                            echo(number_format($computedValue, 2)."<br>");
                                                            echo(number_format($computedValue, 2)."<br>");
                                                        }
                                                        
                                                        
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] != 0) {
                                                        if ($data['discount'] == $lumped[$key-1]['discount'] and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                            $computedValue += $data['discount'];
                                                            echo("(".number_format($computedValue, 2).")<br>");
                                                            echo("(".number_format($computedValue, 2).")<br>");
                                                        }else{
                                                            $computedValue = $data['discount'];
                                                            echo("(".number_format($computedValue, 2).")<br>");
                                                            echo("(".number_format($computedValue, 2).")<br>");
                                                        }
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] == 0) {
                                                        echo("0.00<br>");
                                                        echo("0.00<br>");
                                                    }
                                                    
                                                }
                                            }
                                        @endphp
                                    @endforeach
                                @endforeach
                                                            
                            </td>
                            {{-- TOTAL --}}
                            <td class="border-hidden text-right vertical-top" style="text-align: center">
                                @foreach ($display as $payment_type => $lumped)
                                    @php
                                        $computedValue = 0;
                                        // if ($payment_type == 7) {
                                        //     $sum = 0;
                                        //     foreach ($lumped as $key => $value) {
                                        //         $sum += $value['total'];
                                        //     }
                                        //     echo(number_format($sum,2)."<br>");
                                        //     echo(number_format($sum,2)."<br>");
                                        //     continue;
                                        // }
                                    @endphp
                                    @foreach ($lumped as $key => $data)
                                        @php
                                            if(count($lumped) == 1){
                                                echo(number_format($data['total'],2)."<br>");
                                                echo(number_format($data['total'],2)."<br>");
                                            }else{
                                                if(isset($lumped[$key-1])){
                                                    if ($data['discount'] == 0 and $data['penalty'] != 0 and $key < count($lumped)-1) {
                                                        
                                                        if ($data['penalty'] == $lumped[$key-1]['penalty'] and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                            $computedValue += $data['total'];
                                                        }else{
                                                            $computedValue = $data['total'];
                                                        }
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] != 0 and $key < count($lumped)-1) {
                                                        if ($data['discount'] == $lumped[$key-1]['discount'] and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                            $computedValue += $data['total'];
                                                        }else{
                                                            $computedValue = $data['total'];
                                                        }
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] == 0 and $key < count($lumped)-1) {
                                                        if ($lumped[$key-1]['penalty'] == 0 and $lumped[$key-1]['discount'] == 0 and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                            $computedValue += $data['total'];
                                                        }else{
                                                            $computedValue = $data['total'];
                                                        }
                                                        
                                                    }
                                                }else{
                                                    if ($data['discount'] == 0 and $data['penalty'] != 0) {
                                                        $computedValue = $data['total'];
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] != 0) {
                                                        $computedValue = $data['total'];
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] == 0) {
                                                        $computedValue = $data['total'];
                                                    }
                                                }
                                                if (isset($lumped[$key+1])) {
                                                    if ($data['discount'] == 0 and $data['penalty'] != 0) {
                                                        if ($data['penalty'] == $lumped[$key+1]['penalty'] and $data['assess_val'] == $lumped[$key+1]['assess_val']) {
                                                            continue;
                                                        }else{
                                                            echo(number_format($computedValue, 2)."<br>");
                                                            echo(number_format($computedValue, 2)."<br>");
                                                        
                                                        }
                                                    }if ($data['penalty'] == 0 and $data['discount'] != 0) {
                                                        if ($data['discount'] == $lumped[$key+1]['discount'] and $data['assess_val'] == $lumped[$key+1]['assess_val']) {
                                                            continue;
                                                        }else{
                                                            echo(number_format($computedValue, 2)."<br>");
                                                            echo(number_format($computedValue, 2)."<br>");
                                                            
                                                        }
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] == 0) {
                                                        if ($lumped[$key+1]['penalty'] == 0 and $lumped[$key+1]['discount'] == 0 and $data['assess_val'] == $lumped[$key+1]['assess_val']) {
                                                            continue;
                                                        }else{
                                                            echo(number_format($computedValue, 2)."<br>");
                                                            echo(number_format($computedValue, 2)."<br>");
                                                        }
                                                    }
                                                    
                                                }else{
                                                    if ($data['discount'] == 0 and $data['penalty'] != 0) {
                                                        if ($data['penalty'] == $lumped[$key-1]['penalty'] and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                            $computedValue += $data['total'];
                                                            echo(number_format($computedValue, 2)."<br>");
                                                            echo(number_format($computedValue, 2)."<br>");
                                                        }else{
                                                            $computedValue = $data['total'];
                                                            echo(number_format($computedValue, 2)."<br>");
                                                            echo(number_format($computedValue, 2)."<br>");
                                                        }
                                                        
                                                        
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] != 0) {
                                                        if ($data['discount'] == $lumped[$key-1]['discount'] and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                            $computedValue += $data['total'];
                                                            echo(number_format($computedValue, 2)."<br>");
                                                            echo(number_format($computedValue, 2)."<br>");
                                                        }else{
                                                            $computedValue = $data['total'];
                                                            echo(number_format($computedValue, 2)."<br>");
                                                            echo(number_format($computedValue, 2)."<br>");
                                                        }
                                                        
                                                        
                                                    }
                                                    if ($data['penalty'] == 0 and $data['discount'] == 0) {
                                                        if ($lumped[$key-1]['penalty'] == 0 and $lumped[$key-1]['discount'] == 0 and $data['assess_val'] == $lumped[$key-1]['assess_val']) {
                                                            $computedValue += $data['total'];
                                                            echo(number_format($computedValue, 2)."<br>");
                                                            echo(number_format($computedValue, 2)."<br>");
                                                        }else{
                                                            $computedValue = $data['total'];
                                                            echo(number_format($computedValue, 2)."<br>");
                                                            echo(number_format($computedValue, 2)."<br>");
                                                        }
                                                        
                                                    }
                                                    
                                                }
                                            }
                                        @endphp
                                    @endforeach
                                @endforeach

                            </td>
                        
                    </tr>
                </table>
                <table class="signatories">

                    <tr style="background: ##d68db8; transform: translate(0px, 5px)">
                        <td style="width: 2.5cm;">
                            {{-- @if($receipt->bank_number != null || $receipt->bank_number != '')
                            {{ $receipt->bank_number }}
                            @endif --}}
                        </td>
                        <td style="width: 2.5cm;">
                            {{-- @if($receipt->bank_name != null || $receipt->bank_name != '')
                            {{ $receipt->bank_name }}
                            @endif --}}
                        </td>
                        <td style="width: 2.5cm;">
                            {{-- @if($receipt->bank_date != null || $receipt->bank_date != '')
                            {{ \Carbon\Carbon::parse($receipt->bank_date)->format('M d,Y') }}
                            @endif --}}
                        </td>
                    </tr> 

                    <tr>
                        <td colspan=5 rowspan="2"  style="border:0px ##ffffff00" >
                            <table width="100%" style="position: absolute; top: 45px; left: -30px">
                                <tr>
                                    <td class="text-hidden">
                                        <div style="width:80%">
                                            <!-- Payment without penalty may be made within the periods stated below is by installment -->
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
                                    <td width="100%" class="" style="background: ##52aac7; padding-top: -15px;">
                                        <table width="100%" id="payment_dets" style="table-layout: fixed;">
                                            <tr>
                                                <td colspan="2" class="text-hidden" ><!-- MODE OF PAYMENT --></td>
                                            </tr>
                                            <tr>
                                                <td style="width:80%" class="text-hidden">CASH</td>
                                                <td style="width:20%"><span style="position:absolute; top: -5px">{{  $receipt->bank_number != null || $receipt->bank_name != null || $receipt->bank_date != null ? " " : number_format($form56['total'], 2) }}</span></td>
                                            </tr>
                                            <tr>
                                                <td style="word-break: break-all;vertical-align: text-top;">
                                                    {{-- <span style="vertical-align: text-top;word-break: break-all; font-size: 7px; color:rgba(0, 0, 0, 1)"></span> --}}
                                                        <span style="word-break: break-all; font-size: 8px;position: absolute; top: -5px">
                                                        @if($receipt->bank_number != null || $receipt->bank_number != '')
                                                            {{ $receipt->bank_number }}
                                                        @endif
                                                        @if($receipt->bank_name != null || $receipt->bank_name != '')
                                                            {{ $receipt->bank_name }}<br>
                                                        @endif
                                                        @if($receipt->bank_date != null || $receipt->bank_date != '')
                                                            {{ \Carbon\Carbon::parse($receipt->bank_date)->format('M d,Y') }}
                                                        @endif
                                                        
                                                    </span>
                                                </td>
                                                <td>
                                                    <span style="position:absolute; top: -5px">{{  $receipt->bank_number != null || $receipt->bank_name != null || $receipt->bank_date != null ? number_format($form56['total'], 2) : "" }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-hidden">TW/PMO</td>
                                                <td><span style="position:absolute; top: 10px"></span></td>
                                            </tr>
                                            <tr style="transform:translate(0px, 5px)">
                                                <td height="15px" class="text-hidden" >TOTAL</td>
                                                <td> {{ number_format($form56['total'], 2) }}</td>
                                                <!-- padding-top: -15px; -->
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <!-- padding-top: -38px; -->
                        <td colspan="7" class="border-hidden text-right" style="transform:translate(0px, 13px)"><span class="text-hidden">TOTAL ></span> {{ number_format($form56['total'], 2) }}</td> 
                    </tr>
                    <tr style="transform:translate(31px, 32px)">
                        <td colspan="3" class="border-hidden">
                            <div style="text-align: center;">
                                {{$sign ? $acctble_officer_name->value : ''}}
                                <BR>
                            <span style="white-space:nowrap">
                                {{$sign ? $acctble_officer_position->value : ''}}
                            </span>
                            </div>
                        </td>
                        <td colspan="4" class="border-hidden" style="transform:translate(25px, 0px)">
                            <div style="text-align: center;">
                                <!-- IMELDA I. MACANES -->
                                {{$sign ? 'IMELDA I. MACANES ' : ''}}
                                <BR>
                                <!-- PROVINCIAL TREASURER -->
                                {{$sign ? ucwords(strtolower('PROVINCIAL TREASURER ')) : ''}}
                            </div>
                        </td>
                    </tr>
                </table>
        {{-- @endif --}}
        @break
        @endforeach

        <!-- PAGE BREAK -->

        @if(isset($next_pg))
            @if($next_pg == true)
                <?php
                    // next($annual_per_arp);
                    // $this_arp_next = key($annual_per_arp);
                    // prev($annual_per_arp);

                    $array_keys = array_keys($annual_per_arp);
                    foreach($array_keys as $i => $key) {
                        if($key == $arp)
                            $this_arp_next = $array_keys[$i+1];
                    }

                    $is_arp = preg_match('/[A-Za-z]/', $this_arp_next);
                ?>
                @if($this_arp_next != null && $is_arp <= 0 && $this_arp_next != $arp)
                    <div style="page-break-after: always;"></div>
                @endif
            @endif
        @endif
        
    @endforeach

</body>
</html>