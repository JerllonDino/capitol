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
                <table width="100%" class="border-hidden" style="margin: 0 ; background: ##dbba7d; position: absolute; top: -15px;">
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
                                            <table width="95%" style="margin-top:0px;" class="border-hidden">
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
                                                        <td class="border-hidden text-center" style="font-size: 12px; width:2.7cm; background: ##5af542; vertical-align: bottom;">
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

                            $p_calendar_year = $first.' -'.$last;
                            if($first == $last){
                                $p_calendar_year = $first;
                            }
                         @endphp
                        <td class="border-hidden text-right" style="background: ##eda6eb;">
                            <!-- DATE -->
                            <div style="margin-bottom:13px; padding-right: 25px;">
                                {{ $date_entry->format('F d, Y') }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-hidden" height="20" style="background: ##a7e57b; padding-left: -20px;">
                            <!-- class="center" style="padding-left: -150px;" --> 
                            <div style="padding-left: 50px; padding-right: 45px; word-wrap: break-word;">
                                {{ $receipt->customer->name }}
                            </div>
                        </td>
                        <td class="border-hidden" style="padding-left: -30px;">{{ $total_words }} only</td>
                        <td class="border-hidden text-right" style="text-indent: 13px; padding-right: 25px; background: ##a7e57b;"><br />{{ number_format($form56['total'], 2) }}</td>
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
                                    <td width="100%" style="padding-top: 10px; padding-left: 25px; background: ##80fc03; text-align: center; float: right;">
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
                                                @if($prep_yr_frm == $prep_yr_to)
                                                    <div style="word-wrap: break-word; background: ##e63e3e; padding-left: 75%;">
                                                        {{ $prep_yr_frm }} {{ $full_partial_type[$fp] }}
                                                    </div>
                                                @else
                                                    <div style="word-wrap: break-word; background: ##e63e3e; padding-left: 75%;">
                                                        {{ $prep_yr_frm }}-{{ $prep_yr_to }} {{ $full_partial_type[$fp] }}
                                                    </div>
                                                @endif
                                            @else
                                                <span style="word-wrap: break-word; width: 100px; background: ##70fc41; padding-left: 75%;">
                                                    {{ $p_calendar_year }}
                                                </span>
                                            @endif 
                                            <br>
                                        @endforeach
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
                        $count_tdrp = (count($receipt->F56Detailmny));
                        $owner = '';
                    @endphp

                    <tr style="background: ##ef7385;">
                        <td class="border-hidden text-left vertical-top" style="height: 155px; padding-left: 20px; padding-right: 10px; background: ##ef7865;">
                            @if(isset($annual_per_arp[$arp]['owner']))
                            {{ $annual_per_arp[$arp]['owner'] }}
                            @endif
                        </td>

                        <!-- width: 1.2cm; -->
                        <td class="border-hidden text-left vertical-top" style="background: ##ef6585; padding-left: -15px;">
                            @if(!is_null($val['brgy']))
                                {{ $val['brgy']->name }}
                            @endif
                            <br><br>
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
                        <td class="border-hidden text-left vertical-top" style="background: ##689cf2; padding-left: -55px;" colspan="2">
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
                        <td class="border-hidden text-left vertical-top" style="width: 1.3cm; background: ##4cef9b; padding-left: -65px; padding-right: 10px;">
                            @if(isset($annual_per_arp[$arp]['assess_val_class']))
                                @foreach($annual_per_arp[$arp]['assess_val_class'] as $index => $val)
                                    @if(!is_null($val['kind']))
                                        @if(preg_match('/building/i', $val['kind']) != 1)
                                            {{ number_format($val['assess_val'],2) }}<br>
                                        @else
                                            <br>
                                        @endif
                                    @elseif(!is_null($val['actual_use']))
                                        @if(preg_match('/bldg/i', $val['actual_use']) != 1)
                                            {{ number_format($val['assess_val'],2) }}<br>
                                        @else
                                            <br>
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                        </td>

                        <!--  padding-right: 5px; -->
                        <!-- IMPROVEMENT (BUILDING/MACHINE) -->
                        <td class="border-hidden text-right vertical-top" style="width: 1.3cm; background: ##4287f5; padding-right: 15px; padding-left: -250px;">
                            @if(isset($annual_per_arp[$arp]['assess_val_class']))
                                @foreach($annual_per_arp[$arp]['assess_val_class'] as $index => $val)
                                    @if(!is_null($val['kind']))
                                        @if(preg_match('/building/i', $val['kind']) == 1)
                                            {{ number_format($val['assess_val'],2) }}<br>
                                        @else
                                            <br>
                                        @endif
                                    @elseif(!is_null($val['actual_use']))
                                        @if(preg_match('/bldg/i', $val['actual_use']) == 1)
                                            {{ number_format($val['assess_val'],2) }}<br>
                                        @else
                                            <br>
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                        </td>

                        <!-- padding-right: -25px; width: 1.5cm;  -->
                        <td class="border-hidden text-left vertical-top text-right" style=" width: 2cm; background: ##f276c4; padding-right: -40px;">
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
                                                    {{ number_format($annual_per_arp[$this_arp]['assess_val'], 2) }}<br>
                                                    {{ number_format($annual_per_arp[$this_arp_next]['assess_val'], 2) }}<br>
                                                @elseif($annual_per_arp[$this_arp]['prev_tax_dec_no'] != $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] != $this_arp)
                                                    {{ number_format($annual_per_arp[$arp]['assess_val'], 2) }}<br>
                                                @endif
                                            @else
                                                {{ number_format($val['assess_val'], 2) }}
                                            @endif
                                        @elseif($this_arp_prev != false)
                                            @if(isset($annual_per_arp[$this_arp_prev]))
                                                @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_prev || $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] == $this_arp)
                                                    {{ number_format($annual_per_arp[$this_arp_prev]['assess_val'], 2) }}<br>
                                                    {{ number_format($annual_per_arp[$this_arp]['assess_val'], 2) }}<br>
                                                @elseif($annual_per_arp[$this_arp]['prev_tax_dec_no'] != $this_arp_prev || $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] != $this_arp)
                                                    {{ number_format($annual_per_arp[$arp]['assess_val'], 2) }}<br>
                                                @endif
                                            @else
                                                {{ number_format($val['assess_val'], 2) }}
                                            @endif
                                        @elseif($this_arp == $arp)
                                            {{-- @if($this_arp_next == null && $this_arp_prev == null && count(array_keys($annual_arp[$arp])) == 1)  --}}
                                                {{-- number_format($val['assess_val'], 2) --}}
                                            {{-- @else --}}
                                                @if(isset($annual_per_arp[$this_arp]['assess_val_class']))
                                                    @foreach($annual_per_arp[$this_arp]['assess_val_class'] as $i => $val)
                                                        {{ number_format($val['assess_val'], 2) }}
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

                            {{-- TAX DUE AND TYPE --}}
                            <td class="border-hidden text-left vertical-top" style="width: 3cm; background: ##a276c4; position: relative; padding-left: 25px;">
                                <div style="margin: 0; padding: 0; text-align: right;"> 
                                                                            @php
                                                                                $display = [];   
                                                                            @endphp
                                                                            @foreach ($annual_per_arp['yearly'][$this_arp] as $detail)
                                                                                    @php
                                                                                        $display[$detail['full_partial']][] = $detail; 
                                                                                    @endphp
                                                                            @endforeach
                                                                            
                                                                                
                                                                            @foreach ($display as $lumped)
                                                                                @php
                                                                                    $previousPenalty = 0;
                                                                                    $previousDiscount = 0;
                                                                                    $computedValue=0;
                                                                                    $dates = [];
                                                                                    $sameCounter = 0;
                                                                                @endphp
                                                                                @foreach ($lumped as $key => $data)
                                                                                    @php
                                                                                        if(count($lumped) == 1){
                                                                                            echo(number_format($data['sef'], 2)."<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(". $data['period_covered'] .")<br>");
                                                                                        }else{
                                                                                         if($data['discount'] == 0 and $data['penalty'] == 0){
                                                                                            if ($previousPenalty == $data['penalty'] and $sameCounter <= 3) {
                                                                                                    array_push($dates, $data['period_covered']);
                                                                                                    $previousPenalty = $data['penalty'];
                                                                                                    $sameCounter++;
                                                                                                }else{
                                                                                                    if ($computedValue) {
                                                                                                        echo(number_format($data['sef'], 2)."<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>"."(".$dates[0] . ($dates[count($dates)-1] != $dates[0] ? "-".$dates[count($dates)-1] : "").")<br>");
                                                                                                        $sameCounter = 0;
                                                                                                    }
                                                                                                    array_push($dates, $data['period_covered']);
                                                                                                    $computedValue = $data['sef'];
                                                                                                    $previousPenalty = $data['penalty'];
                                                                                                    
                                                                                                }
                                                                                                if(count($lumped)-1 == $key){
                                                                                                    echo(number_format($data['sef'], 2)."<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(".$dates[count($dates)-1].")<br>");
                                                                                                }
                                                                                        }elseif($data['discount'] == 0){
                                                                                                if ($previousPenalty == $data['penalty'] and $sameCounter <= 3) {
                                                                                                    array_push($dates, $data['period_covered']);
                                                                                                    $previousPenalty = $data['penalty'];
                                                                                                    $sameCounter++;
                                                                                                }else{
                                                                                                    if ($computedValue) {
                                                                                                        echo(number_format($data['sef'], 2)."<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>"."(".$dates[0] . ($dates[count($dates)-1] != $dates[0] ? "-".$dates[count($dates)-1] : "").")<br>");
                                                                                                        $sameCounter = 0;
                                                                                                    }
                                                                                                    array_push($dates, $data['period_covered']);
                                                                                                    $computedValue = $data['sef'];
                                                                                                    $previousPenalty = $data['penalty'];
                                                                                                    
                                                                                                }
                                                                                                if(count($lumped)-1 == $key){
                                                                                                    echo(number_format($data['sef'], 2)."<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(".$dates[count($dates)-1].")<br>");
                                                                                                }
                                                                                                
                                                                                            }elseif($data['penalty'] == 0){
                                                                                                if ($previousDiscount == $data['discount'] and $sameCounter <= 3) {
                                                                                                    $computedValue += $data['sef'];
                                                                                                    $previousDiscount = $data['discount'];
                                                                                                    $sameCounter++;
                                                                                                }else{
                                                                                                    if ($computedValue) {
                                                                                                        echo(number_format($data['sef'], 2)."<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>"."(".$dates[0] . ($dates[count($dates)-1] != $dates[0] ? "-".$dates[count($dates)-1] : "").")<br>");
                                                                                                        
                                                                                                        $sameCounter = 0;
                                                                                                    }
                                                                                                    array_push($dates, $data['period_covered']);
                                                                                                    $computedValue = $data['sef'];
                                                                                                    $previousPenalty = $data['penalty'];
                                                                                                    
                                                                                                }
                                                                                                if(count($lumped)-1 == $key){
                                                                                                    echo(number_format($data['sef'], 2)."<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(".$dates[count($dates)-1].")<br>");
                                                                                                }
                                                                                            }
                                                                                            
                                                                                        }
                                                                                    @endphp
                                                                                @endforeach
                                                                            @endforeach
                                                                            
                                </div>
                            </td>

                            <td class="border-hidden text-left vertical-top" style="width: 1.5cm; background: ##cde25f; text-align: center;">
                                                        @php
                                                        $display = [];   
                                                    @endphp
                                                    @foreach ($annual_per_arp['yearly'][$this_arp] as $detail)
                                                            @php
                                                                $display[$detail['full_partial']][] = $detail;
                                                            @endphp
                                                    @endforeach
                                                    
                                                            
                                                    @foreach ($display as $lumped)
                                                        @php
                                                            $previousPenalty = 0;
                                                            $previousDiscount = 0;
                                                            $computedValue = 0;
                                                            $sameCounter = 0;
                                                        @endphp
                                                        @foreach ($lumped as $key => $data)
                                                            @php
                                                                if(count($lumped) == 1){
                                                                    echo("BASIC<br>");
                                                                    echo("SEF<br>");
                                                                }else{
                                                                    
                                                                    if($data['discount'] == 0){
                                                                        if ($previousPenalty == $data['penalty'] and $sameCounter <= 3) {
                                                                            $computedValue += $data['sef'];
                                                                            $previousPenalty = $data['penalty'];
                                                                            $sameCounter++;
                                                                        }else{
                                                                            if ($computedValue) {
                                                                                echo("BASIC<br>");
                                                                                echo("SEF<br>");
                                                                                $sameCounter = 0;
                                                                            }
                                                                            $computedValue = $data['sef'];
                                                                            $previousPenalty = $data['penalty'];
                                                                            
                                                                        }
                                                                        if(count($lumped)-1 == $key){
                                                                            echo("BASIC<br>");
                                                                            echo("SEF<br>");
                                                                        }
                                                                        
                                                                    }elseif($data['penalty'] == 0){
                                                                        if ($previousDiscount == $data['discount'] and $sameCounter <= 3) {
                                                                            $computedValue += $data['sef'];
                                                                            $previousDiscount = $data['discount'];
                                                                            $sameCounter++;
                                                                        }else{
                                                                            if ($computedValue) {
                                                                                echo("BASIC<br>");
                                                                                echo("SEF<br>");
                                                                                $sameCounter = 0;
                                                                            }
                                                                            $computedValue = $data['sef'];
                                                                            $previousDiscount = $data['penalty'];
                                                                            
                                                                        }
                                                                        if(count($lumped)-1 == $key){
                                                                            
                                                                                echo("BASIC<br>");
                                                                                echo("SEF<br>");
                                                                            
                                                                        }
                                                                    }
                                                                    
                                                                }
                                                            @endphp
                                                        @endforeach
                                                    @endforeach
                            </td>
                            {{-- FULL PAYMENT --}}
                            <td class="border-hidden text-right vertical-top" style="width: 1.1cm; background: ##e8aa4e; padding-right: 6px;">

                                                            @php
                                                                $display = [];   
                                                            @endphp
                                                            @foreach ($annual_per_arp['yearly'][$this_arp] as $detail)
                                                                    @php
                                                                        $display[$detail['full_partial']][] = $detail;
                                                                    @endphp
                                                            @endforeach
                                                            
                                                                
                                                            @foreach ($display as $lumped)
                                                                @php
                                                                    $previousPenalty = 0;
                                                                    $previousDiscount = 0;
                                                                    $computedValue = 0;
                                                                    $sameCounter = 0;
                                                                    $isPrevPenalty = 0;
                                                                @endphp
                                                                @foreach ($lumped as $key => $data)
                                                                    @php
                                                                        if(count($lumped) == 1){
                                                                            echo(number_format($data['sef'],2)."<br>");
                                                                            echo(number_format($data['sef'],2)."<br>");
                                                                        }else{
                                                                            
                                                                            if($data['discount'] == 0){
                                                                                if ($previousPenalty == $data['penalty'] and $sameCounter <= 3) {
                                                                                    $computedValue += $data['sef'];
                                                                                    $previousPenalty = $data['penalty'];
                                                                                    $sameCounter++;
                                                                                }else{
                                                                                    
                                                                                    if (!$isPrevPenalty) {
                                                                                        if ($computedValue) {
                                                                                            echo(number_format($computedValue, 2)."<br>");
                                                                                            echo(number_format($computedValue, 2)."<br>");
                                                                                            $sameCounter = 0;
                                                                                        }
                                                                                    }else{
                                                                                        if ($computedValue) {
                                                                                            echo(number_format($computedValue, 2)."<br>");
                                                                                            echo(number_format($computedValue, 2)."<br>");
                                                                                            $sameCounter = 0;
                                                                                        }
                                                                                    }
                                                                                    $computedValue = $data['sef'];
                                                                                    $previousPenalty = $data['penalty'];
                                                                                    $isPrevPenalty = 1;
                                                                                    
                                                                                }
                                                                                if(count($lumped)-1 == $key){
                                                                                    echo(number_format($computedValue, 2)."<br>");
                                                                                    echo(number_format($computedValue, 2)."<br>");
                                                                                }
                                                                                
                                                                            }elseif($data['penalty'] == 0){
                                                                                if ($previousDiscount == $data['discount'] and $sameCounter <= 3) {
                                                                                    $computedValue += $data['sef'];
                                                                                    $previousDiscount = $data['discount'];
                                                                                    $sameCounter++;
                                                                                }else{
                                                                                    if ($isPrevPenalty) {
                                                                                        if ($computedValue) {
                                                                                            echo(number_format($computedValue, 2)."<br>");
                                                                                            echo(number_format($computedValue, 2)."<br>");
                                                                                            $sameCounter = 0;
                                                                                        }
                                                                                    }else{
                                                                                        if ($computedValue) {
                                                                                            echo(number_format($computedValue, 2)."<br>");
                                                                                            echo(number_format($computedValue, 2)."<br>");
                                                                                            $sameCounter = 0;
                                                                                        }
                                                                                    }
                                                                                    $computedValue = $data['sef'];
                                                                                    $previousDiscount = $data['penalty'];
                                                                                    $isPrevPenalty = 0;
                                                                                    
                                                                                }
                                                                                if(count($lumped)-1 == $key){
                                                                                    
                                                                                        echo(number_format($computedValue, 2)."<br>");
                                                                                        echo(number_format($computedValue, 2)."<br>");
                                                                                    
                                                                                }
                                                                            }
                                                                            
                                                                        }
                                                                    @endphp
                                                                @endforeach
                                                            @endforeach
                                                            
                            </td>
                            {{-- PENALTY OR DISCOUNT --}}
                            <td class="border-hidden text-right vertical-top" style="width: 1cm; background: ##e56b60; padding-right: -15px;">
                                
                                                            @php
                                                                $display = [];   
                                                            @endphp
                                                            @foreach ($annual_per_arp['yearly'][$this_arp] as $detail)
                                                                    @php
                                                                        $display[$detail['full_partial']][] = $detail;
                                                                    @endphp
                                                            @endforeach
                                                            @php
                                                                dd($display)
                                                            @endphp
                                                             
                                                            @foreach ($display as $lumped)
                                                                @php
                                                                    $previousPenalty = 0;
                                                                    $previousDiscount = 0;
                                                                    $computedValue = 0;
                                                                    $sameCounter = 0;
                                                                    $isPrevPenalty = 0;
                                                                    $isPrevDisc = 0;
                                                                @endphp
                                                                @foreach ($lumped as $key => $data)
                                                                    @php
                                                                        if(count($lumped) == 1){
                                                                            echo(number_format($data['penalty'],2)."<br>");
                                                                            echo(number_format($data['penalty'],2)."<br>");
                                                                        }else{
                                                                            
                                                                            if($data['discount'] == 0){
                                                                                if ($isPrevDisc) {
                                                                                    if ($computedValue) {
                                                                                    echo("(".number_format($computedValue, 2).")<br>");
                                                                                    echo("(".number_format($computedValue, 2).")<br>");
                                                                                    $sameCounter = 0;
                                                                                }
                                                                                    }
                                                                                
                                                                                if ($previousPenalty == $data['penalty'] and $sameCounter <= 3) {
                                                                                    $computedValue += $data['penalty'];
                                                                                    $previousPenalty = $data['penalty'];
                                                                                    $sameCounter++;
                                                                                }else{

                                                                                        if ($computedValue) {
                                                                                            echo(number_format($computedValue, 2)."<br>");
                                                                                            echo(number_format($computedValue, 2)."<br>");
                                                                                            $sameCounter = 0;
                                                                                        }
                                                                                    
                                                                                    $computedValue = $data['penalty'];
                                                                                    $previousPenalty = $data['penalty'];
                                                                                    
                                                                                    
                                                                                }
                                                                                if(count($lumped)-1 == $key){
                                                                                    echo(number_format($computedValue, 2)."<br>");
                                                                                    echo(number_format($computedValue, 2)."<br>");
                                                                                }
                                                                                $isPrevPenalty = 1;
                                                                                $isPrevDisc = 0;
                                                                                
                                                                            }elseif($data['penalty'] == 0){
                                                                                if ($isPrevPenalty) {
                                                                                        if ($computedValue) {
                                                                                            echo(number_format($computedValue, 2)."<br>");
                                                                                            echo(number_format($computedValue, 2)."<br>");
                                                                                            $sameCounter = 0;
                                                                                        }
                                                                                    }
                                                                                if ($previousDiscount == $data['discount'] and $sameCounter <= 3) {
                                                                                    $computedValue += $data['discount'];
                                                                                    $previousDiscount = $data['discount'];
                                                                                    $sameCounter++;
                                                                                }else{
                                                                                    
                                                                                    
                                                                                        if($computedValue){
                                                                                            echo("(".number_format($computedValue, 2).")<br>");
                                                                                            echo("(".number_format($computedValue, 2).")<br>");
                                                                                            $sameCounter = 0;
                                                                                        }
                                                                                    
                                                                                    $computedValue = $data['discount'];
                                                                                    $previousDiscount = $data['discount'];
                                                                                    
                                                                                    
                                                                                }
                                                                                if(count($lumped)-1 == $key){
                                                                                    
                                                                                        echo("(".number_format($computedValue, 2).")<br>");
                                                                                        echo("(".number_format($computedValue, 2).")<br>");
                                                                                    
                                                                                }
                                                                                $isPrevDisc = 1;
                                                                                $isPrevPenalty = 0;
                                                                            }elseif ($data['full_partial'] == 7) {
                                                                                # code...
                                                                            }
                                                                            
                                                                        }
                                                                    @endphp
                                                                @endforeach
                                                            @endforeach
                                                            
                            </td>
                            {{-- TOTAL --}}
                            <td class="border-hidden text-right vertical-top" style="width: 2.1cm; background: ##7fe83e; padding-left: 10px;">
                               
                                                                    @php
                                                                        $display = [];   
                                                                    @endphp
                                                                    @foreach ($annual_per_arp['yearly'][$this_arp] as $detail)
                                                                            @php
                                                                                $display[$detail['full_partial']][] = $detail;
                                                                            @endphp
                                                                    @endforeach
                                                                   
                                                                     
                                                                    @foreach ($display as $lumped)
                                                                        @php
                                                                            $previousPenalty = 0;
                                                                            $previousDiscount = 0;
                                                                            $computedValue = 0;
                                                                            $sameCounter = 0;
                                                                        @endphp
                                                                        @foreach ($lumped as $key => $data)
                                                                            @php
                                                                                if(count($lumped) == 1){
                                                                                    echo(number_format($data['total'],2)."<br>");
                                                                                    echo(number_format($data['total'],2)."<br>");
                                                                                }else{
                                                                                    
                                                                                    if($data['discount'] == 0){
                                                                                        if ($previousPenalty == $data['penalty'] and $sameCounter <= 3) {
                                                                                            $computedValue += $data['total'];
                                                                                            $previousPenalty = $data['penalty'];
                                                                                            $sameCounter++;
                                                                                        }else{
                                                                                            if ($computedValue) {
                                                                                                echo(number_format($computedValue, 2)."<br>");
                                                                                                echo(number_format($computedValue, 2)."<br>");
                                                                                                $sameCounter = 0;
                                                                                            }
                                                                                            $computedValue = $data['total'];
                                                                                            $previousPenalty = $data['penalty'];
                                                                                            
                                                                                        }
                                                                                        if(count($lumped)-1 == $key){
                                                                                            echo(number_format($computedValue, 2)."<br>");
                                                                                            echo(number_format($computedValue, 2)."<br>");
                                                                                        }
                                                                                        
                                                                                    }elseif($data['penalty'] == 0){
                                                                                        if ($previousDiscount == $data['discount'] and $sameCounter <= 3) {
                                                                                            $computedValue += $data['total'];
                                                                                            $previousDiscount = $data['discount'];
                                                                                            $sameCounter++;
                                                                                        }else{
                                                                                            if ($computedValue) {
                                                                                                echo(number_format($computedValue, 2)."<br>");
                                                                                                echo(number_format($computedValue, 2)."<br>");
                                                                                                $sameCounter = 0;
                                                                                            }
                                                                                            $computedValue = $data['total'];
                                                                                            $previousDiscount = $data['discount'];
                                                                                            
                                                                                        }
                                                                                        if(count($lumped)-1 == $key){
                                                                                            if($previousDiscount == $data['discount']){
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

                    <tr style="background: ##d68db8;">
                        <td style="width: 2.5cm;">
                            @if($receipt->bank_number != null || $receipt->bank_number != '')
                            {{ $receipt->bank_number }}
                            @endif
                        </td>
                        <td style="width: 2.5cm;">
                            @if($receipt->bank_name != null || $receipt->bank_name != '')
                            {{ $receipt->bank_name }}
                            @endif
                        </td>
                        <td style="width: 2.5cm;">
                            @if($receipt->bank_date != null || $receipt->bank_date != '')
                            {{ \Carbon\Carbon::parse($receipt->bank_date)->format('M d,Y') }}
                            @endif
                        </td>
                    </tr> 

                    <tr class="" style="background: ##c542f5;">
                        <td colspan=5 rowspan="2"  style="border:0px ##ffffff00" >
                            <table width="100%">
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
                                    <td width="70%" class="" style="background: ##52aac7; padding-top: -24px;">
                                        <table width="100%" id="payment_dets">
                                            <tr>
                                                <td colspan="2" class="text-hidden" ><!-- MODE OF PAYMENT --></td>
                                            </tr>
                                            <tr>
                                                <td width="70%" height="15px" class="text-hidden">CASH</td>
                                                <td style="padding-top: -10px; text-align: right; background: ##52aac7;">{{ number_format($form56['total'], 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td height="15px" class="text-hidden">CHECK</td>
                                                <td style="padding-top: -10px; padding-left: -50px; text-align: right;">
                                                    @if($receipt->bank_number != null || $receipt->bank_number != '')
                                                        {{ $receipt->bank_number }}<br>
                                                    @endif
                                                    @if($receipt->bank_name != null || $receipt->bank_name != '')
                                                        {{ $receipt->bank_name }}<br>
                                                    @endif
                                                    @if($receipt->bank_date != null || $receipt->bank_date != '')
                                                        {{ \Carbon\Carbon::parse($receipt->bank_date)->format('M d,Y') }}<br>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td height="15px" class="text-hidden">TW/PMO</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td height="15px" class="text-hidden" >TOTAL</td>
                                                <td style="padding-top: -25px; text-align: right; background-color: ##7fe83e;"> {{ number_format($form56['total'], 2) }}</td>
                                                <!-- padding-top: -15px; -->
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <!-- padding-top: -38px; -->
                        <td colspan="7" class="border-hidden text-right" style="background-color: ##7fe83e; padding-top: -55px;"><span class="text-hidden">TOTAL ></span> {{ number_format($form56['total'], 2) }}</td> 
                    </tr>
                    <tr>
                        <td colspan="3" class="border-hidden" style="padding-top: -15px; background-color: ##7fe83e;">
                            <div style="text-align: center; ">
                                {{$sign ? $acctble_officer_name->value : ''}}
                                <BR>
                                {{$sign ? $acctble_officer_position->value : ''}}
                            </div>
                        </td>
                        <td colspan="3" class="border-hidden" style="padding-top: -15px; background-color: ##7fe83e;">
                            <div style="text-align: center; ">
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