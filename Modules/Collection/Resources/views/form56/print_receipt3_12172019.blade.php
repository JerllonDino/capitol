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

            if(isset($next_pg)) {
                if($next_pg == false) {
                    continue;
                }
            }
        ?>
        @foreach($dataa as $year => $val)
            <?php
                $kk = array_keys($dataa);
                $search_exist = array_search(\Carbon\Carbon::now()->addYear()->format('Y'), $kk);
                if ($search_exist >= 0 && $search_exist !== false && count($kk) > 1) {
                    $advance = true;
                    $next_pg = true;
                }
            ?>
            <table width="100%" class="border-hidden" style="margin: 0 ; background: ##dbba7d; position: absolute; top: -15px;">
                <tr>
                    <td colspan=2 rowspan=2 height='15%' style="padding: 0; margin: 0; background: ##a7e57b;">
                        <table width="100%" class="border-hidden" style="padding: 0; margin: 0;">
                            <tr>
                                <td style="margin:0" width="15%" ></td>
                                <td style="text-align: right; background-color: ##f7e9d7;" width="50%">
                                    @if($wmunicipality)
                                        <b>{{strtoupper($receipt->municipality->name)}}, BENGUET</b>
                                    @endif
                                </td>
                                <td style="padding: 0; margin: 0; padding-left: -150px; background-color: ##fcba03;">

                                @php
                                    $tax_type = '';
                                
                                    if(isset($receipt->F56Detail->col_f56_previous_receipt)){
                                       $tax_type = $receipt->F56Detail->TDARPX->previousTaxType->previous_tax_name ;    
                                    }

                                    $prev_date = '';
                                    $prev_receipt = '';
                                    $prev_year = '';
                                    $prev_remarks = '';
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
                                                        <small>{{ $tax_type }} </small>
                                                        {{ ($prev_receipt)  }} 
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="28px"  width="100" class="border-hidden text-right"  style="font-size: 12px; background: ##4287f5;  vertical-align: bottom;">
                                                        {{  $prev_remarks }} 
                                                    </td>
                                                    <td height="28px"  width="100" class="border-hidden text-right"  style="font-size: 12px; background: ##4287f5;  vertical-align: bottom;">
                                                        {{  $prev_date }} 
                                                    </td>
                                                    <td class="border-hidden text-right" style="font-size: 12px; width:2.7cm; background: ##5af542; vertical-align: bottom;">
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
                    $count_tdrp = (count($receipt->F56Detailmny));
                    $owner = '';
                @endphp
                <tr style="background: ##ef7385;">
                    <td class="border-hidden text-left vertical-top" style="height: 155px; padding-left: 20px;">
                        {{ $annual_per_arp[$arp]['owner'] }}
                    </td>

                    <td class="border-hidden text-left vertical-top" style="background: ##ef7385;">
                        @if(!is_null($val['brgy']))
                            {{ $val['brgy']->name }} &nbsp; {{$val['tax_type']}}
                        @else
                            {{$val['tax_type']}}
                        @endif
                    </td>

                    <td class="border-hidden text-left vertical-top" style="width: 3.3cm; background: ##689cf2;" colspan="2" >
                        @if($limit_counter <= $limit) 
                            @foreach($annual_arp as $this_arp => $data)
                                <?php 
                                    /*$this_arp_next = array_search(next($annual_arp), $annual_arp);
                                    prev($annual_arp);
                                    $this_arp_prev = array_search(prev($annual_arp), $annual_arp);
                                    next($annual_arp);*/

                                    next($annual_arp);
                                    $this_arp_next = key($annual_arp);
                                    prev($annual_arp);
                                    prev($annual_arp);
                                    $this_arp_prev = key($annual_arp);
                                    next($annual_arp);
                                ?>
                                @if(isset($annual_per_arp['yearly'][$this_arp][$year]))
                                    @if($this_arp_next != false)
                                        @if(isset($annual_per_arp[$this_arp_next]))
                                            @foreach($annual_arp as $this_arp => $data)
                                                @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp)
                                                    {{ $this_arp }}<br>
                                                    {{ $this_arp_next }}<br>
                                                @endif
                                            @endforeach
                                        @else
                                            {{ $arp }}<br>
                                        @endif
                                    @elseif($this_arp_prev != false)
                                        @if(isset($annual_per_arp[$this_arp_prev]))
                                            @foreach($annual_arp as $this_arp => $data)
                                                @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_prev || $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] == $this_arp)
                                                    {{ $this_arp_prev }}<br>
                                                    {{ $this_arp }}<br>
                                                @endif
                                            @endforeach
                                        @else
                                            {{ $arp }}<br>
                                        @endif
                                    @elseif($this_arp == $arp)
                                        @if($this_arp_next == null && $this_arp_prev == null && count(array_keys($annual_arp)) == 1) 
                                            {{ $arp }}<br>
                                        @elseif($this_arp_next == null && $this_arp_prev == null)
                                            {{ $arp }}<br>
                                        @endif
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </td>
   
                    <!-- LAND -->
                    <td class="border-hidden text-right vertical-top" style="width: 1.3cm; background: ##4cef9b;" >
                        @if(isset($val['kind']))
                            @if(!is_null($val['kind']))
                                @if(preg_match('/building/i', $val['kind']) != 1)
                                    {{ number_format($val['assess_val'],2) }}<br>
                                @else
                                    <br>
                                @endif
                            @elseif(isset($val['actual_use']))
                                @if(!is_null($val['actual_use']))
                                    @if(preg_match('/bldg/i', $val['actual_use']) != 1)
                                        {{ number_format($val['assess_val'],2) }}<br>
                                    @else
                                        <br>
                                    @endif
                                @endif
                            @endif
                        @elseif(isset($val['actual_use']))
                            @if(!is_null($val['actual_use']))
                                @if(preg_match('/bldg/i', $val['actual_use']) != 1)
                                    {{ number_format($val['assess_val'],2) }}<br>
                                @else
                                    <br>
                                @endif
                            @endif
                        @endif
                    </td>

                    <!-- IMPROVEMENT (BUILDING/MACHINE) -->
                    <td class="border-hidden text-right vertical-top" style="width: 1.3cm; background: ##4287f5; padding-right: 5px;" >
                        @if(isset($val['kind']))
                            @if(!is_null($val['kind']))
                                @if(preg_match('/building/i', $val['kind']) == 1)
                                    {{ number_format($val['assess_val'],2) }}<br>
                                @else
                                    <br>
                                @endif
                            @elseif(isset($val['actual_use']))
                                @if(!is_null($val['actual_use']))
                                    @if(preg_match('/bldg/i', $val['actual_use']) == 1)
                                        {{ number_format($val['assess_val'],2) }}<br>
                                    @else
                                        <br>
                                    @endif
                                @endif
                            @endif
                        @elseif(isset($val['actual_use']))
                            @if(!is_null($val['actual_use']))
                                @if(preg_match('/bldg/i', $val['actual_use']) == 1)
                                    {{ number_format($val['assess_val'],2) }}<br>
                                @else
                                    <br>
                                @endif
                            @endif
                        @endif
                    </td>

                    <td class="border-hidden text-left vertical-top text-right" style="width: 1.5cm; background: ##f276c4; padding-right: -25px;">
                        <!-- assessed value TOTAL -->
                        <?php 
                            $total_assess_val = 0; 
                            $limit_counter = 0; 
                        ?>
                        {{-- @if($limit_counter <= $limit)
                            @foreach($annual_arp as $this_arp => $data) --}}
                                <?php 
                                    // next($annual_arp);
                                    // $this_arp_next = key($annual_arp);
                                    // prev($annual_arp);
                                    // prev($annual_arp);
                                    // $this_arp_prev = key($annual_arp);
                                    // next($annual_arp);
                                ?>
                                {{-- @if(isset($annual_per_arp['yearly'][$this_arp][$year]))
                                    @if($this_arp_next != false)
                                        @if(isset($annual_per_arp[$this_arp_next]))
                                            @foreach($annual_arp as $this_arp => $data)
                                                @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp) --}}
                                                    {{-- @foreach($data as $year => $val) --}}
                                                        <?php 
                                                            //$total_assess_val += $annual_per_arp[$this_arp]['assess_val'] + $annual_per_arp[$this_arp_next]['assess_val'];
                                                        ?>
                                                    {{-- @endforeach --}}
                                                {{-- @endif
                                            @endforeach
                                        @endif
                                    @elseif($this_arp_prev != false)
                                        @if(isset($annual_per_arp[$this_arp_prev]))
                                            @foreach($annual_arp as $this_arp => $data)
                                                @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_prev || $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] == $this_arp) --}}
                                                    <?php 
                                                        // $total_assess_val += $val['assess_val'];
                                                        //$total_assess_val += $annual_per_arp[$this_arp]['assess_val'] + $annual_per_arp[$this_arp_next]['assess_val'];
                                                    ?>
                                                {{-- @endif
                                            @endforeach
                                        @endif
                                    @endif
                                @endif
                            @endforeach
                        @endif --}}

                        @if($limit_counter <= $limit)
                            @foreach($annual_arp as $this_arp => $data)
                                <?php 
                                    /*$this_arp_next = array_search(next($annual_arp), $annual_arp);
                                    prev($annual_arp);
                                    $this_arp_prev = array_search(prev($annual_arp), $annual_arp);
                                    next($annual_arp);*/

                                    next($annual_arp);
                                    $this_arp_next = key($annual_arp);
                                    prev($annual_arp);
                                    prev($annual_arp);
                                    $this_arp_prev = key($annual_arp);
                                    next($annual_arp);
                                ?>
                                @if(isset($annual_per_arp['yearly'][$this_arp][$year]))
                                    @if($this_arp_next != false)
                                        @if(isset($annual_per_arp[$this_arp_next]))
                                            @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp)
                                                {{-- number_format($total_assess_val, 2) --}}<!-- <br> -->
                                                {{ number_format($annual_per_arp[$this_arp]['assess_val']) }}<br>
                                                {{ number_format($annual_per_arp[$this_arp_next]['assess_val']) }}<br>
                                            @endif
                                        @else
                                            {{ number_format($val['assess_val'], 2) }}
                                        @endif
                                    @elseif($this_arp_prev != false)
                                        @if(isset($annual_per_arp[$this_arp_prev]))
                                            @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_prev || $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] == $this_arp)
                                                {{-- number_format($total_assess_val, 2) --}}<!-- <br> -->
                                                {{ number_format($annual_per_arp[$this_arp_prev]['assess_val']) }}<br>
                                                {{ number_format($annual_per_arp[$this_arp]['assess_val']) }}<br>
                                            @endif
                                        @else
                                            {{ number_format($val['assess_val'], 2) }}
                                        @endif
                                    @elseif($this_arp == $arp)
                                        @if($this_arp_next == null && $this_arp_prev == null && count(array_keys($annual_arp)) == 1) 
                                            {{ number_format($val['assess_val'], 2) }}
                                        @elseif($this_arp_next == null && $this_arp_prev == null)
                                            {{ number_format($val['assess_val'], 2) }}
                                        @endif
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </td>

                    <td class="border-hidden text-left vertical-top" style="width: 3cm; background: ##a276c4; position: relative; padding-left: 25px;">
                        <div style="margin: 0; padding: 0; text-align: right;"> 
                            <?php
                                $limit_counter = 0;
                                $total_tax_due = 0;
                                $year_to = 0;
                            ?>
                            @if($limit_counter <= $limit)
                                @foreach($annual_arp as $this_arp => $data)
                                    <?php 
                                        next($annual_arp);
                                        $this_arp_next = key($annual_arp);
                                        prev($annual_arp);
                                        prev($annual_arp);
                                        $this_arp_prev = key($annual_arp);
                                        next($annual_arp);
                                    ?>
                                    @if(isset($annual_per_arp['yearly'][$this_arp][$year]))
                                        @if($this_arp_next != false)
                                            @if(isset($annual_per_arp[$this_arp_next]))
                                                @foreach($annual_arp as $this_arp => $data)
                                                    @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp)
                                                        @foreach($annual_per_arp['yearly'] as $this_arp2 => $data2)
                                                            <?php 
                                                                if($this_arp2 == $this_arp || $this_arp2 == $this_arp_next) {
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
                            @endif

                            @if($limit_counter <= $limit)
                                @foreach($annual_arp as $this_arp => $data)
                                    <?php 
                                        next($annual_arp);
                                        $this_arp_next = key($annual_arp);
                                        prev($annual_arp);
                                        prev($annual_arp);
                                        $this_arp_prev = key($annual_arp);
                                        next($annual_arp);
                                    ?>
                                    @if(isset($annual_per_arp['yearly'][$this_arp][$year]))
                                        @if($this_arp_next != false)
                                            @if(isset($annual_per_arp[$this_arp_next]))
                                                @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp)
                                                    @if($year_to > 0)       
                                                        @if(strlen($year) == 4)
                                                            <!--{{-- number_format($total_tax_due, 2) --}}  <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br> ({{-- $year --}}-{{-- $year_to --}}) <br> -->
                                                            <?php
                                                                $yrs = array_keys($annual_per_arp['yearly'][$this_arp_next]);
                                                                $least_yr = count(array_keys($annual_per_arp['yearly'][$this_arp_next]));
                                                            ?>
                                                            @if(isset($annual_per_arp['yearly'][$this_arp][$year_to]))
                                                                {{ number_format(($annual_per_arp[$this_arp]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $yrs[$least_yr-1] }}-{{ $year_to }})<br>
                                                            @else
                                                                {{ number_format(($annual_per_arp[$this_arp]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }})<br>
                                                            @endif

                                                            @if(isset($annual_per_arp['yearly'][$this_arp_next][$year_to]))
                                                                {{ number_format(($annual_per_arp[$this_arp_next]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $yrs[$least_yr-1]  }}-{{ $year_to }})<br>
                                                            @else
                                                                {{ number_format(($annual_per_arp[$this_arp_next]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }})<br>
                                                            @endif
                                                            
                                                            <?php
                                                                $limit_counter++;
                                                            ?>
                                                        @else
                                                            <?php
                                                                $year_ex = explode('-', $year);
                                                                $yrs = array_keys($annual_per_arp['yearly'][$this_arp_next]);
                                                                $least_yr = count(array_keys($annual_per_arp['yearly'][$this_arp_next]));
                                                            ?>
                                                            <!-- {{-- number_format($total_tax_due, 2) --}} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{-- $year_ex[0] --}}-{{ $year_to --}})<br> -->
        
                                                            @if(isset($annual_per_arp['yearly'][$this_arp][$year_to]))
                                                                {{ number_format(($annual_per_arp[$this_arp]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $yrs[$least_yr-1] }}-{{ $year_to }})<br>
                                                            @else
                                                                {{ number_format(($annual_per_arp[$this_arp]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year_ex[0] }}-{{ $year_ex[1] }})<br>
                                                            @endif

                                                            @if(isset($annual_per_arp['yearly'][$this_arp_next][$year_to]))
                                                                @if(strlen($yrs[$least_yr-1]) > 4)
                                                                    <?php
                                                                        $br = explode('-', $yrs[$least_yr-1]);
                                                                    ?>
                                                                    {{ number_format(($annual_per_arp[$this_arp_next]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $br[0] }}-{{ $year_to }})<br>
                                                                @else
                                                                    {{ number_format(($annual_per_arp[$this_arp_next]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $yrs[$least_yr-1] }}-{{ $year_to }})<br>
                                                                @endif
                                                                
                                                            @else
                                                                {{ number_format(($annual_per_arp[$this_arp_next]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year_ex[0] }}-{{ $year_ex[1] }})<br>
                                                            @endif
                                                            <?php
                                                                $limit_counter++;
                                                            ?>
                                                        @endif
                                                    @elseif(isset($val['to']))                                                    
                                                        @if(strlen($year) == 4)
                                                            <!-- {{-- number_format($total_tax_due, 2) --}} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{-- $year --}}-{{-- $val['to'] --}})<br> -->

                                                            {{ number_format(($annual_per_arp[$this_arp]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }}-{{ $val['to'] }})<br>
                                                            {{ number_format(($annual_per_arp[$this_arp_next]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }}-{{ $val['to'] }})<br>
                                                            <?php
                                                                $limit_counter++;
                                                            ?>
                                                        @else
                                                            <?php
                                                                $year_ex = explode('-', $year);
                                                            ?>
                                                            <!-- {{-- number_format($total_tax_due, 2) --}} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{-- $year_ex[0] --}}-{{-- $val['to'] --}})<br> -->

                                                            {{ number_format(($annual_per_arp[$this_arp]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year_ex[0] }}-{{ $val['to'] }})<br>
                                                            {{ number_format(($annual_per_arp[$this_arp_next]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year_ex[0] }}-{{ $val['to'] }})<br>
                                                            <?php
                                                                $limit_counter++;
                                                            ?>
                                                        @endif
                                                    @else
                                                        <!-- {{-- number_format($total_tax_due, 2) --}} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{-- $year --}})<br>  -->

                                                        {{ number_format(($annual_per_arp[$this_arp]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }})<br> 
                                                        {{ number_format(($annual_per_arp[$this_arp_next]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }})<br> 
                                                        <?php
                                                            $limit_counter++;
                                                        ?>
                                                    @endif
                                                @endif
                                            @endif
                                        @elseif($this_arp_prev != false)
                                            @if(isset($annual_per_arp[$this_arp_prev]))
                                                @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_prev || $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] == $this_arp || $this_arp == $arp)
                                                    @if($year_to > 0)
                                                        <!-- {{-- number_format($total_tax_due, 2) --}} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{-- $year --}}-{{-- $year_to --}})<br> -->

                                                        {{ number_format(($annual_per_arp[$this_arp]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }}-{{ $year_to }})<br>
                                                        {{ number_format(($annual_per_arp[$this_arp_prev]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }}-{{ $year_to }})<br>
                                                        <?php
                                                            $limit_counter++;
                                                        ?>
                                                    @elseif(isset($val['to']))
                                                        <!-- {{-- number_format($total_tax_due, 2) --}} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{-- $year --}}-{{-- $val['to'] --}})<br> -->

                                                        {{ number_format(($annual_per_arp[$this_arp]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }}-{{ $val['to'] }})<br>
                                                        {{ number_format(($annual_per_arp[$this_arp_prev]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }}-{{ $val['to'] }})<br>
                                                        <?php
                                                            $limit_counter++;
                                                        ?>
                                                    @else
                                                        <!-- {{-- number_format($total_tax_due, 2) --}} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{-- $year --}})<br>  -->

                                                        {{ number_format(($annual_per_arp[$this_arp]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }})<br> 
                                                        {{ number_format(($annual_per_arp[$this_arp_prev]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }})<br> 
                                                        <?php
                                                            $limit_counter++;
                                                        ?>
                                                    @endif
                                                @endif
                                            @else
                                                @foreach($annual_arp as $this_arp => $data)
                                                    @if($this_arp == $arp)
                                                        @foreach($data as $year => $val)
                                                            @if(isset($val['to']))                                
                                                                {{ number_format($val['assess_val']*.01, 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }}-{{ $val['to'] }})<br>
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            @else
                                                                {{ number_format($val['assess_val']*.01, 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }})<br> 
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @endif
                                        @elseif($this_arp == $arp)
                                            @if($this_arp_next == null && $this_arp_prev == null && count(array_keys($annual_arp)) == 1) 
                                                @foreach($data as $year => $val)
                                                    <?php
                                                        if($year < \Carbon\Carbon::now()->addYear()->format('Y') && $advance == true) {
                                                            $next_pg = true;
                                                            continue;
                                                        }
                                                    ?>
                                                    @if(isset($val['to']))                     
                                                        <!-- {{-- number_format($val['assess_val']*.01, 2) --}} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{-- $year --}}-{{-- $val['to'] --}})<br> --> 

                                                        @if($year < $val['to'])
                                                            {{ number_format($annual_per_arp[$this_arp]['assess_val']*.01, 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }}-{{ $val['to'] }})<br>
                                                        @else
                                                            {{ number_format($annual_per_arp[$this_arp]['assess_val']*.01, 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $val['to'] }}-{{ $year }})<br>
                                                        @endif
                                                        <?php
                                                            $limit_counter++;
                                                        ?>
                                                    @else
                                                        <?php 
                                                            $year_to = $year > $year_to ? $year : $year_to; 
                                                        ?>
                                                        @if($year_to > $year)
                                                            <!-- {{-- number_format($val['assess_val']*.01, 2) --}} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{-- $year --}})<br> -->

                                                            {{ number_format($annual_per_arp[$this_arp]['assess_val']*.01, 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }})<br>
                                                        @elseif($year_to < $year)
                                                            <!-- {{-- number_format($val['assess_val']*.01, 2) --}} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{-- $year_to --}}-{{-- $year --}})<br> -->

                                                            {{ number_format($annual_per_arp[$this_arp]['assess_val']*.01, 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year_to }}-{{ $year }})<br>
                                                        @else
                                                            <!-- {{-- number_format($val['assess_val']*.01, 2) --}} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{-- $year --}})<br> -->

                                                            {{ number_format($annual_per_arp[$this_arp]['assess_val']*.01, 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }})<br>
                                                        @endif 
                                                        <?php
                                                            $limit_counter++;
                                                        ?>
                                                    @endif
                                                @endforeach
                                            @elseif($this_arp_next == null && $this_arp_prev == null)
                                                @foreach($data as $year => $val)
                                                    <?php
                                                        if($year < \Carbon\Carbon::now()->addYear()->format('Y') && $advance == true) {
                                                            $next_pg = true;
                                                            continue;
                                                        }
                                                    ?>
                                                    @if(isset($val['to']))                     
                                                        <!-- {{-- number_format($val['assess_val']*.01, 2) --}} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{-- $year --}}-{{-- $val['to'] --}})<br> --> 

                                                        @if($year < $val['to'])
                                                            {{ number_format($annual_per_arp[$this_arp]['assess_val']*.01, 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }}-{{ $val['to'] }})<br>
                                                        @else
                                                            {{ number_format($annual_per_arp[$this_arp]['assess_val']*.01, 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $val['to'] }}-{{ $year }})<br>
                                                        @endif
                                                        <?php
                                                            $limit_counter++;
                                                        ?>
                                                    @else
                                                        <?php 
                                                            $year_to = $year > $year_to ? $year : $year_to; 
                                                        ?>
                                                        @if($year_to > $year)
                                                            <!-- {{-- number_format($val['assess_val']*.01, 2) --}} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{-- $year --}})<br> -->

                                                            {{ number_format($annual_per_arp[$this_arp]['assess_val']*.01, 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }})<br>
                                                        @elseif($year_to < $year)
                                                            <!-- {{-- number_format($val['assess_val']*.01, 2) --}} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{-- $year_to --}}-{{-- $year --}})<br> -->

                                                            {{ number_format($annual_per_arp[$this_arp]['assess_val']*.01, 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year_to }}-{{ $year }})<br>
                                                        @else
                                                            <!-- {{-- number_format($val['assess_val']*.01, 2) --}} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{-- $year --}})<br> -->

                                                            {{ number_format($annual_per_arp[$this_arp]['assess_val']*.01, 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }})<br>
                                                        @endif 
                                                        <?php
                                                            $limit_counter++;
                                                        ?>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </td>

                    <td class="border-hidden text-left vertical-top" style="width: 1.7cm; background: ##cde25f; text-align: center;">
                        <?php $limit_counter = 0; ?>
                        @if($limit_counter <= $limit)
                            @foreach($annual_arp as $this_arp => $data)
                                <?php 
                                    next($annual_arp);
                                    $this_arp_next = key($annual_arp);
                                    prev($annual_arp);
                                    prev($annual_arp);
                                    $this_arp_prev = key($annual_arp);
                                    next($annual_arp);
                                ?>
                                @if(isset($annual_per_arp['yearly'][$this_arp][$year]))
                                    @if($this_arp_next != false)
                                        @if(isset($annual_per_arp[$this_arp_next]))
                                            @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp)
                                                @if(isset($val['to']))                                
                                                    BASIC<br> 
                                                    SEF<br> 
                                                    BASIC<br> 
                                                    SEF<br>  
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                @else
                                                    BASIC<br> 
                                                    SEF<br> 
                                                    BASIC<br> 
                                                    SEF<br> 
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                @endif
                                            @endif
                                        @else
                                            @foreach($data as $year => $val)
                                                @if(isset($val['to']))                                
                                                    BASIC<br> 
                                                    SEF<br> 
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                @else
                                                    BASIC<br> 
                                                    SEF<br> 
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif($this_arp_prev != false)
                                        @if(isset($annual_per_arp[$this_arp_prev]))
                                            @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_prev || $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] == $this_arp)
                                                @if(isset($val['to']))                                
                                                    BASIC<br> 
                                                    SEF<br> 
                                                    BASIC<br> 
                                                    SEF<br>   
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                @else
                                                    BASIC<br> 
                                                    SEF<br> 
                                                    BASIC<br> 
                                                    SEF<br>  
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                @endif
                                            @else
                                                @if(isset($val['to']))                                
                                                    BASIC<br> 
                                                    SEF<br>  
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                @else
                                                    BASIC<br> 
                                                    SEF<br>  
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                @endif
                                            @endif
                                        @else
                                            @foreach($data as $year => $val)
                                                @if(isset($val['to']))                                
                                                    BASIC<br> 
                                                    SEF<br>  
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                @else
                                                    BASIC<br> 
                                                    SEF<br> 
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif($this_arp == $arp)
                                        @foreach($data as $year => $val)
                                            <?php
                                                if($advance == true && $year < \Carbon\Carbon::now()->addYear()->format('Y')) {
                                                    $next_pg = true;
                                                    continue;
                                                }
                                            ?>
                                            @if(isset($val['to']))                                
                                                BASIC<br> 
                                                SEF<br> 
                                                <?php
                                                    $limit_counter++;
                                                ?>
                                            @else
                                                BASIC<br> 
                                                SEF<br> 
                                                <?php
                                                    $limit_counter++;
                                                ?>
                                            @endif
                                        @endforeach
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </td>

                    <td class="border-hidden text-right vertical-top" style="width: 1.1cm; background: ##e8aa4e; padding-right: 8px;">
                        <?php
                            $limit_counter = 0;
                            // $total_tax_due = 0;
                            $total_tax_due = [];
                        ?>
                        @if($limit_counter <= $limit)
                            @foreach($annual_arp as $this_arp => $data)
                                <?php 
                                    next($annual_arp);
                                    $this_arp_next = key($annual_arp);
                                    prev($annual_arp);
                                    prev($annual_arp);
                                    $this_arp_prev = key($annual_arp);
                                    next($annual_arp);

                                    $keys = array_keys($data);

                                ?>
                                @if(isset($annual_per_arp[$this_arp]))
                                    @if($this_arp_next != false)
                                        @if(isset($annual_per_arp[$this_arp_next]))
                                            @foreach($annual_arp as $this_arp => $data)
                                                @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp)
                                                    @foreach($annual_per_arp['yearly'] as $this_arp2 => $data2)
                                                        <?php 
                                                            if($this_arp2 == $this_arp || $this_arp2 == $this_arp_next) {
                                                                foreach($data2 as $year2 => $val2) {
                                                                    if(!isset($total_tax_due[$this_arp2])) {
                                                                        $total_tax_due[$this_arp2] = 0;
                                                                    }
                                                                    for($i = 0; $i < count($keys); $i++) {
                                                                        if($year2 < \Carbon\Carbon::now()->addYear()->format('Y') && $advance == true) {
                                                                            if(strlen($year2) > 4) {
                                                                                $total_tax_due[$this_arp2] += $val2['sef'];
                                                                            } else {
                                                                                $total_tax_due[$this_arp2] += $val2['assess_val']*.01;
                                                                            }
                                                                        } else if($year2 < \Carbon\Carbon::now()->addYear()->format('Y') && $year2 >= $keys[$i]) {
                                                                            if(strlen($year2) > 4) {
                                                                                $total_tax_due[$this_arp2] += $val2['sef'];
                                                                            } else {
                                                                                $total_tax_due[$this_arp2] += $val2['assess_val']*.01;
                                                                            }
                                                                        }
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
                                                                    if(!isset($total_tax_due[$this_arp2])) {
                                                                        $total_tax_due[$this_arp2] = 0;
                                                                    }
                                                                    if($year2 < \Carbon\Carbon::now()->addYear()->format('Y') && $advance == true) {
                                                                        if(strlen($year2) > 4) {
                                                                            $total_tax_due[$this_arp2] += $val2['sef'];
                                                                        } else {
                                                                            $total_tax_due[$this_arp2] += $val2['assess_val']*.01;
                                                                        }
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
                        @endif

                        @if($limit_counter <= $limit)
                            @foreach($annual_arp as $this_arp => $data)
                                <?php 
                                    next($annual_arp);
                                    $this_arp_next = key($annual_arp);
                                    prev($annual_arp);
                                    prev($annual_arp);
                                    $this_arp_prev = key($annual_arp);
                                    next($annual_arp);
                                ?>
                                @if(isset($annual_per_arp['yearly'][$this_arp][$year]))
                                    @if($this_arp_next != false)
                                        @if(isset($annual_per_arp[$this_arp_next]))
                                            @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp)
                                                @if(isset($val['to']))                                
                                                    {{ number_format($total_tax_due[$this_arp], 2) }}<br>
                                                    {{ number_format($total_tax_due[$this_arp], 2) }}<br> 
                                                    {{ number_format($total_tax_due[$this_arp_next], 2) }}<br>
                                                    {{ number_format($total_tax_due[$this_arp_next], 2) }}<br> 
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                @else
                                                    {{ number_format($total_tax_due[$this_arp], 2) }}<br>
                                                    {{ number_format($total_tax_due[$this_arp], 2) }}<br> 
                                                    {{ number_format($total_tax_due[$this_arp_next], 2) }}<br>
                                                    {{ number_format($total_tax_due[$this_arp_next], 2) }}<br> 
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                @endif
                                            @endif
                                        @else
                                            @foreach($data as $year => $val)
                                                @if(isset($val['to']))                                
                                                    {{ number_format($val['assess_val']*.01, 2) }}<br>
                                                    {{ number_format($val['assess_val']*.01, 2) }}<br> 
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                @else
                                                    {{ number_format($val['assess_val']*.01, 2) }}<br> 
                                                    {{ number_format($val['assess_val']*.01, 2) }}<br> 
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif($this_arp_prev != false)
                                        @if(isset($annual_per_arp[$this_arp_prev]))
                                            @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_prev || $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] == $this_arp)
                                                @if(isset($val['to']))                                
                                                    {{ number_format($total_tax_due[$this_arp], 2) }}<br>
                                                    {{ number_format($total_tax_due[$this_arp], 2) }}<br> 
                                                    {{ number_format($total_tax_due[$this_arp_prev], 2) }}<br>
                                                    {{ number_format($total_tax_due[$this_arp_prev], 2) }}<br>  
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                @else
                                                    {{ number_format($total_tax_due[$this_arp], 2) }}<br>
                                                    {{ number_format($total_tax_due[$this_arp], 2) }}<br> 
                                                    {{ number_format($total_tax_due[$this_arp_prev], 2) }}<br>
                                                    {{ number_format($total_tax_due[$this_arp_prev], 2) }}<br>  
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                @endif
                                            @else
                                                @if(isset($val['to']))                                
                                                    {{ number_format($total_tax_due[$this_arp], 2) }}<br>
                                                    {{ number_format($total_tax_due[$this_arp], 2) }}<br> 
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                @else
                                                    {{ number_format($total_tax_due[$this_arp], 2) }}<br> 
                                                    {{ number_format($total_tax_due[$this_arp], 2) }}<br> 
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                @endif
                                            @endif
                                        @else
                                            @foreach($data as $year => $val)
                                                @if(isset($val['to']))                                
                                                    {{ number_format($val['assess_val']*.01, 2) }}<br>
                                                    {{ number_format($val['assess_val']*.01, 2) }}<br> 
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                @else
                                                    {{ number_format($val['assess_val']*.01, 2) }}<br> 
                                                    {{ number_format($val['assess_val']*.01, 2) }}<br> 
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif($this_arp == $arp)
                                        @foreach($data as $year => $val)
                                            <?php
                                                if($advance == true && $year < \Carbon\Carbon::now()->addYear()->format('Y')) {
                                                    $next_pg = true;
                                                    continue;
                                                }
                                            ?>
                                            @if(isset($val['to']))                                
                                                {{ number_format($val['assess_val']*.01, 2) }}<br>
                                                {{ number_format($val['assess_val']*.01, 2) }}<br> 
                                                <?php
                                                    $limit_counter++;
                                                ?>
                                            @else
                                                {{ number_format($val['assess_val']*.01, 2) }}<br> 
                                                {{ number_format($val['assess_val']*.01, 2) }}<br> 
                                                <?php
                                                    $limit_counter++;
                                                ?>
                                            @endif
                                        @endforeach
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </td>

                    <td class="border-hidden text-right vertical-top" style="width: 1cm; background: ##e56b60; padding-right: -20px;">
                        <?php
                            $limit_counter = 0;
                            // $total_penalty = 0;
                            // $total_discount = 0;
                            $total_penalty = [];
                            $total_discount = [];
                        ?>
                        @if($limit_counter <= $limit)
                            @foreach($annual_arp as $this_arp => $data)
                                <?php 
                                    /*$this_arp_next = array_search(next($annual_arp), $annual_arp);
                                    prev($annual_arp);
                                    $this_arp_prev = array_search(prev($annual_arp), $annual_arp);
                                    next($annual_arp);*/

                                    next($annual_arp);
                                    $this_arp_next = key($annual_arp);
                                    prev($annual_arp);
                                    prev($annual_arp);
                                    $this_arp_prev = key($annual_arp);
                                    next($annual_arp);
                                ?>
                                @if(isset($annual_per_arp['yearly'][$this_arp][$year]))
                                    @if($this_arp_next != false)
                                        @if(isset($annual_per_arp[$this_arp_next]))
                                            @foreach($annual_arp as $this_arp => $data)
                                                @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp)
                                                    @foreach($annual_per_arp['yearly'] as $this_arp2 => $data2)
                                                        <?php 
                                                            // if($val['penalty'] > 0) {
                                                            //     $total_penalty += $val['penalty'];
                                                            // } elseif($val['discount'] > 0) {
                                                            //     $total_discount += $val['discount'];
                                                            // }

                                                            if(!isset($total_penalty[$this_arp2])) {
                                                                $total_penalty[$this_arp2] = 0;
                                                            }
                                                            if(!isset($total_discount[$this_arp2])) {
                                                                $total_discount[$this_arp2] = 0;
                                                            }

                                                            if ($this_arp2 == $this_arp || $this_arp2 == $this_arp_next) {
                                                                foreach($data2 as $year2 => $val2) {
                                                                    $total_penalty[$this_arp2] += $val2['penalty'];
                                                                    $total_discount[$this_arp2] += $val2['discount'];
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
                                                            if(!isset($total_penalty[$this_arp2])) {
                                                                $total_penalty[$this_arp2] = 0;
                                                            }
                                                            if(!isset($total_discount[$this_arp2])) {
                                                                $total_discount[$this_arp2] = 0;
                                                            }

                                                            if ($this_arp2 == $this_arp || $this_arp2 == $this_arp_next) {
                                                                foreach($data2 as $year2 => $val2) {
                                                                    $total_penalty[$this_arp2] += $val2['penalty'];
                                                                    $total_discount[$this_arp2] += $val2['discount'];
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
                        @endif

                        @if($limit_counter <= $limit)
                            @foreach($annual_arp as $this_arp => $data)
                                <?php 
                                    next($annual_arp);
                                    $this_arp_next = key($annual_arp);
                                    prev($annual_arp);
                                    prev($annual_arp);
                                    $this_arp_prev = key($annual_arp);
                                    next($annual_arp);
                                ?>
                                @if(isset($annual_per_arp['yearly'][$this_arp][$year]))
                                    @if($this_arp_next != false)
                                        @if(isset($annual_per_arp[$this_arp_next]))
                                            @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp)
                                                @if($total_penalty > 0)                                
                                                    {{ number_format($total_penalty[$this_arp], 2) }}<br>
                                                    {{ number_format($total_penalty[$this_arp], 2) }}<br> 
                                                    {{ number_format($total_penalty[$this_arp_next], 2) }}<br>
                                                    {{ number_format($total_penalty[$this_arp_next], 2) }}<br> 
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                @elseif($total_discount > 0)
                                                    ({{ number_format($total_discount[$this_arp], 2) }})<br> 
                                                    ({{ number_format($total_discount[$this_arp], 2) }})<br> 
                                                    ({{ number_format($total_discount[$this_arp_next], 2) }})<br> 
                                                    ({{ number_format($total_discount[$this_arp_next], 2) }})<br> 
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                @endif
                                            @endif
                                        @else
                                            @foreach($data as $year => $val)
                                                @if($val['penalty'] > 0)
                                                    {{ number_format($val['penalty'], 2) }}<br>
                                                    {{ number_format($val['penalty'], 2) }}<br>
                                                @elseif($val['discount'] > 0)
                                                    ({{ number_format($val['discount'], 2) }})<br>
                                                    ({{ number_format($val['discount'], 2) }})<br>
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif($this_arp_prev != false)
                                        @if(isset($annual_per_arp[$this_arp_prev]))
                                            @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_prev || $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] == $this_arp)
                                                @if($total_penalty > 0)                                
                                                    {{ number_format($total_penalty[$this_arp], 2) }}<br>
                                                    {{ number_format($total_penalty[$this_arp], 2) }}<br> 
                                                    {{ number_format($total_penalty[$this_arp_prev], 2) }}<br>
                                                    {{ number_format($total_penalty[$this_arp_prev], 2) }}<br> 
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                @elseif($total_discount > 0)
                                                    ({{ number_format($total_discount[$this_arp], 2) }})<br> 
                                                    ({{ number_format($total_discount[$this_arp], 2) }})<br> 
                                                    ({{ number_format($total_discount[$this_arp_prev], 2) }})<br> 
                                                    ({{ number_format($total_discount[$this_arp_prev], 2) }})<br> 
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                @endif
                                            @endif
                                        @else
                                            @foreach($data as $year => $val)
                                                @if($val['penalty'] > 0)
                                                    {{ number_format($val['penalty'], 2) }}<br>
                                                    {{ number_format($val['penalty'], 2) }}<br>
                                                @elseif($val['discount'] > 0)
                                                    ({{ number_format($val['discount'], 2) }})<br>
                                                    ({{ number_format($val['discount'], 2) }})<br>
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif($this_arp == $arp)
                                        @foreach($data as $year => $val)
                                            <?php
                                                if($advance == true && $year < \Carbon\Carbon::now()->addYear()->format('Y')) {
                                                    $next_pg = true;
                                                    continue;
                                                }
                                            ?>
                                            @if($val['penalty'] > 0)
                                                {{ number_format($val['penalty'], 2) }}<br>
                                                {{ number_format($val['penalty'], 2) }}<br>
                                            @elseif($val['discount'] > 0)
                                                ({{ number_format($val['discount'], 2) }})<br>
                                                ({{ number_format($val['discount'], 2) }})<br>
                                            @else
                                                0.00<br>
                                                0.00<br>
                                            @endif
                                        @endforeach
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </td>

                    <td class="border-hidden text-right vertical-top" style="width: 2.1cm; background: ##7fe83e; padding-left: 10px;">
                        <?php 
                            $limit_total = 0; 
                            $limit_counter = 0; 
                            // $row_total = 0;
                            $row_total = [];
                            $next_pg = true;
                        ?>
                        @if($limit_counter <= $limit)
                            @foreach($annual_arp as $this_arp => $data)
                                <?php 
                                    /*$this_arp_next = array_search(next($annual_arp), $annual_arp);
                                    prev($annual_arp);
                                    $this_arp_prev = array_search(prev($annual_arp), $annual_arp);
                                    next($annual_arp);*/

                                    next($annual_arp);
                                    $this_arp_next = key($annual_arp);
                                    prev($annual_arp);
                                    prev($annual_arp);
                                    $this_arp_prev = key($annual_arp);
                                    next($annual_arp);
                                ?>
                                @if(isset($annual_per_arp['yearly'][$this_arp][$year]))
                                    @if($this_arp_next != false)
                                        @if(isset($annual_per_arp[$this_arp_next]))
                                            @foreach($annual_arp as $this_arp => $data)
                                                @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp)
                                                    @foreach($annual_per_arp['yearly'] as $this_arp2 => $data2)
                                                        <?php 
                                                            // if($val['penalty'] > 0) {
                                                            //     $row_total += ($val['assess_val']*.01) + $val['penalty'];
                                                            //     $limit_total += ((number_format($val['assess_val']*.01, 2)) + number_format($val['penalty'], 2))*2;
                                                            // } elseif($val['discount'] > 0) {
                                                            //     $row_total += ($val['assess_val']*.01) - $val['discount'];
                                                            //     $limit_total += (number_format($val['assess_val']*.01, 2) - number_format($val['discount'], 2))*2;
                                                            // }
                                                            
                                                            if(!isset($row_total[$this_arp2])) {
                                                                $row_total[$this_arp2] = 0;
                                                            }

                                                            if($this_arp2 == $this_arp || $this_arp2 == $this_arp_next) {
                                                                foreach($data2 as $year2 => $val2) {
                                                                    // $row_total += number_format($val2['assess_val']*.01, 2) + number_format($val2['penalty'], 2) - number_format($val2['discount'], 2);
                                                                    // $limit_total += ((number_format($val2['assess_val']*.01, 2)) + number_format($val2['penalty'], 2) - number_format($val2['discount'], 2))*2;

                                                                    if(strlen($year2) > 4) {
                                                                        $row_total[$this_arp2] += $val2['sef'] + number_format($val2['penalty'], 2) - number_format($val2['discount'], 2);
                                                                        $limit_total += ($val2['sef'] + number_format($val2['penalty'], 2) - number_format($val2['discount'], 2))*2;
                                                                    } else {
                                                                        $row_total[$this_arp2] += ($val2['assess_val']*.01) + number_format($val2['penalty'], 2) - number_format($val2['discount'], 2);
                                                                        $limit_total += (($val2['assess_val']*.01) + number_format($val2['penalty'], 2) - number_format($val2['discount'], 2))*2;
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
                                                            // if($val['penalty'] > 0) {
                                                            //     $row_total += (number_format($val['assess_val']*.01, 2)) + number_format($val['penalty'], 2);
                                                            //     $limit_total += ((number_format($val['assess_val']*.01, 2)) + number_format($val['penalty'], 2))*2;
                                                            // } elseif($val['discount'] > 0) {
                                                            //     $row_total += number_format($val['assess_val']*.01, 2) - number_format($val['discount'], 2);
                                                            //     $limit_total += (number_format($val['assess_val']*.01, 2) - number_format($val['discount'], 2))*2;
                                                            // }

                                                            if(!isset($row_total[$this_arp2])) {
                                                                $row_total[$this_arp2] = 0;
                                                            }

                                                            if($this_arp2 == $this_arp || $this_arp2 == $this_arp_prev) {
                                                                foreach($data2 as $year2 => $val2) {
                                                                    // $row_total += number_format($val2['assess_val']*.01, 2) + number_format($val2['penalty'], 2) - number_format($val2['discount'], 2);
                                                                    // $limit_total += ((number_format($val2['assess_val']*.01, 2)) + number_format($val2['penalty'], 2) - number_format($val2['discount'], 2))*2;

                                                                    if(strlen($year2) > 4) {
                                                                        $row_total[$this_arp2] += $val2['sef'] + number_format($val2['penalty'], 2) - number_format($val2['discount'], 2);
                                                                        $limit_total += ($val2['sef'] + number_format($val2['penalty'], 2) - number_format($val2['discount'], 2))*2;
                                                                    } else {
                                                                        $row_total[$this_arp2] += ($val2['assess_val']*.01) + number_format($val2['penalty'], 2) - number_format($val2['discount'], 2);
                                                                        $limit_total += (($val2['assess_val']*.01) + number_format($val2['penalty'], 2) - number_format($val2['discount'], 2))*2;
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
                        @endif

                        @if($limit_counter <= $limit)
                            @foreach($annual_arp as $this_arp => $data)
                                <?php 
                                    // $this_arp_next = array_search(next($annual_arp), $annual_arp);
                                    // prev($annual_arp);
                                    // $this_arp_prev = array_search(prev($annual_arp), $annual_arp);
                                    // next($annual_arp);

                                    next($annual_arp);
                                    $this_arp_next = key($annual_arp);
                                    prev($annual_arp);
                                    prev($annual_arp);
                                    $this_arp_prev = key($annual_arp);
                                    next($annual_arp);
                                ?>
                                @if(isset($annual_per_arp['yearly'][$this_arp][$year]))
                                    @if($this_arp_next != false)
                                        @if(isset($annual_per_arp[$this_arp_next]))
                                            @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp)                              
                                                {{ number_format($row_total[$this_arp], 2) }}<br>
                                                {{ number_format($row_total[$this_arp], 2) }}<br> 
                                                {{ number_format($row_total[$this_arp_next], 2) }}<br>
                                                {{ number_format($row_total[$this_arp_next], 2) }}<br> 
                                                <?php
                                                    $limit_counter++;
                                                    $next_pg = false;
                                                ?>
                                            @endif
                                        @else
                                            @foreach($data as $year => $val)
                                                @if($val['penalty'] > 0)
                                                    {{ number_format(($val['assess_val']*.01 + $val['penalty']), 2) }}<br>
                                                    {{ number_format(($val['assess_val']*.01 + $val['penalty']), 2) }}<br>
                                                @elseif($val['discount'] > 0)
                                                    {{ number_format(($val['assess_val']*.01 - $val['discount']), 2) }}<br>
                                                    {{ number_format(($val['assess_val']*.01 - $val['discount']), 2) }}<br>
                                                @endif
                                                <?php
                                                    $limit_counter++;
                                                    if($val['penalty'] > 0) {
                                                        $limit_total += ((number_format($val['assess_val']*.01, 2)) + number_format($val['penalty'], 2))*2;
                                                    } elseif($val['discount'] > 0) {
                                                        $limit_total += (number_format($val['assess_val']*.01, 2) - number_format($val['discount'], 2))*2;
                                                    }
                                                ?>
                                            @endforeach
                                        @endif
                                    @elseif($this_arp_prev != false)
                                        @if(isset($annual_per_arp[$this_arp_prev]))
                                            @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_prev || $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] == $this_arp)                     
                                                {{ number_format($row_total[$this_arp], 2) }}<br>
                                                {{ number_format($row_total[$this_arp], 2) }}<br> 
                                                {{ number_format($row_total[$this_arp_prev], 2) }}<br>
                                                {{ number_format($row_total[$this_arp_prev], 2) }}<br> 
                                                <?php
                                                    $limit_counter++;
                                                    $next_pg = false;
                                                ?>
                                            @endif
                                        @else
                                            @foreach($data as $year => $val)
                                                @if($val['penalty'] > 0)
                                                    {{ number_format(($val['assess_val']*.01 + $val['penalty']), 2) }}<br>
                                                    {{ number_format(($val['assess_val']*.01 + $val['penalty']), 2) }}<br>
                                                @elseif($val['discount'] > 0)
                                                    {{ number_format(($val['assess_val']*.01 - $val['discount']), 2) }}<br>
                                                    {{ number_format(($val['assess_val']*.01 - $val['discount']), 2) }}<br>
                                                @endif
                                                <?php
                                                    $limit_counter++;
                                                    if($val['penalty'] > 0) {
                                                        $limit_total += ((number_format($val['assess_val']*.01, 2)) + number_format($val['penalty'], 2))*2;
                                                    } elseif($val['discount'] > 0) {
                                                        $limit_total += (number_format($val['assess_val']*.01, 2) - number_format($val['discount'], 2))*2;
                                                    }
                                                ?>
                                            @endforeach
                                        @endif
                                    @elseif($this_arp == $arp)
                                        @foreach($data as $year => $val)
                                            <?php
                                                if($advance == true && $year != \Carbon\Carbon::now()->addYear()->format('Y')) {
                                                    $next_pg = true;
                                                    continue;
                                                }

                                                $assess_val = (float) $val['assess_val'];
                                                $penalty = (float) $val['penalty'];
                                                $discount = (float) $val['discount'];
                                            ?>
                                            @if($val['penalty'] > 0)
                                                {{ number_format(($assess_val*.01 + $penalty), 2) }}<br>
                                                {{ number_format(($assess_val*.01 + $penalty), 2) }}<br>
                                            @elseif($val['discount'] > 0)
                                                {{ number_format(($assess_val*.01 - $discount), 2) }}<br>
                                                {{ number_format(($assess_val*.01 - $discount), 2) }}<br>
                                            @else
                                                {{ number_format(($assess_val*.01), 2) }}<br>
                                                {{ number_format(($assess_val*.01), 2) }}<br>
                                            @endif
                                            <?php
                                                $limit_counter++;
                                                if($penalty > 0) {
                                                    // $limit_total += (number_format($assess_val*.01, 2) + number_format($penalty, 2))*2;
                                                    $limit_total += ($assess_val*.01 + round(floatval($penalty), 2))*2;
                                                } elseif($discount > 0) {
                                                    $limit_total += ($assess_val*.01 - round(floatval($discount), 2))*2;
                                                } else {
                                                    $limit_total += ($assess_val*.01)*2;
                                                }
                                            ?>
                                        @endforeach
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </td>
            </tr>   
                <tr class="">
                    <td colspan=5 rowspan="2"  style="border:0px #ffffff00" >
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
                    <td colspan="7" class="border-hidden text-right" style="background-color: ##7fe83e; padding-top: -38px;"><span class="text-hidden">TOTAL ></span> {{ number_format($limit_total, 2) }}</td> 
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
        @endforeach
        @if($advance == true && count($kk) > 1)
            <?php
                unset($next_pg);

                next($annual_arp);
                $this_arp_next = key($annual_arp);
                prev($annual_arp);
            ?>
            @foreach($dataa as $year => $val)
                <?php
                    if(isset($next_pg)) {
                        if($next_pg == false && $this_arp_next == null) {
                            continue;
                        }
                    }
                ?>
                <!-- PAGE BREAK -->
                @if($year < \Carbon\Carbon::now()->addYear()->format('Y'))
                    <div style="page-break-after: always;"></div>
                    <table width="100%" class="border-hidden" style="margin: 0 ; background: ##dbba7d; position: absolute; top: -15px;">
                        <tr>
                            <td colspan=2 rowspan=2 height='15%' style="padding: 0; margin: 0; background: ##a7e57b;">
                                <table width="100%" class="border-hidden" style="padding: 0; margin: 0;">
                                    <tr>
                                        <td style="margin:0" width="15%" ></td>
                                        <td style="text-align: right; background-color: ##f7e9d7;" width="50%">
                                            @if($wmunicipality)
                                                <b>{{strtoupper($receipt->municipality->name)}}, BENGUET</b>
                                            @endif
                                        </td>
                                        <td style="padding: 0; margin: 0; padding-left: -150px; background-color: ##fcba03;">

                                        @php
                                            $tax_type = '';
                                        
                                            if(isset($receipt->F56Detail->col_f56_previous_receipt)){
                                               $tax_type = $receipt->F56Detail->TDARPX->previousTaxType->previous_tax_name ;    
                                            }

                                            $prev_date = '';
                                            $prev_receipt = '';
                                            $prev_year = '';
                                            $prev_remarks = '';
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
                                                                <small>{{ $tax_type }} </small>
                                                                {{ ($prev_receipt)  }} 
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td height="28px"  width="100" class="border-hidden text-right"  style="font-size: 12px; background: ##4287f5;  vertical-align: bottom;">
                                                                {{  $prev_remarks }} 
                                                            </td>
                                                            <td height="28px"  width="100" class="border-hidden text-right"  style="font-size: 12px; background: ##4287f5;  vertical-align: bottom;">
                                                                {{  $prev_date }} 
                                                            </td>
                                                            <td class="border-hidden text-right" style="font-size: 12px; width:2.7cm; background: ##5af542; vertical-align: bottom;">
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
                            $count_tdrp = (count($receipt->F56Detailmny));
                            $owner = '';
                        @endphp
                        <tr style="background: ##ef7385;">
                            <td class="border-hidden text-left vertical-top" style="height: 155px; padding-left: 20px;">
                                {{ $annual_per_arp[$arp]['owner'] }}
                            </td>

                            <td class="border-hidden text-left vertical-top" style="background: ##ef7385;">
                                @if(!is_null($val['brgy']))
                                    {{ $val['brgy']->name }} &nbsp; {{$val['tax_type']}}
                                @else
                                    {{$val['tax_type']}}
                                @endif
                            </td>

                            <td class="border-hidden text-left vertical-top" style="width: 3.3cm; background: ##689cf2;" colspan="2" >
                                @if($limit_counter <= $limit) 
                                    @foreach($annual_arp as $this_arp => $data)
                                        <?php 
                                            /*$this_arp_next = array_search(next($annual_arp), $annual_arp);
                                            prev($annual_arp);
                                            $this_arp_prev = array_search(prev($annual_arp), $annual_arp);
                                            next($annual_arp);*/

                                            next($annual_arp);
                                            $this_arp_next = key($annual_arp);
                                            prev($annual_arp);
                                            prev($annual_arp);
                                            $this_arp_prev = key($annual_arp);
                                            next($annual_arp);
                                        ?>
                                        @if(isset($annual_per_arp['yearly'][$this_arp][$year]))
                                            @if($this_arp_next != false)
                                                @if(isset($annual_per_arp[$this_arp_next]))
                                                    @foreach($annual_arp as $this_arp => $data)
                                                        @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp)
                                                            {{ $this_arp }}<br>
                                                            {{ $this_arp_next }}<br>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    {{ $arp }}<br>
                                                @endif
                                            @elseif($this_arp_prev != false)
                                                @if(isset($annual_per_arp[$this_arp_prev]))
                                                    @foreach($annual_arp as $this_arp => $data)
                                                        @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_prev || $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] == $this_arp)
                                                            {{ $this_arp }}<br>
                                                            {{ $this_arp_prev }}<br>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    {{ $arp }}<br>
                                                @endif
                                            @elseif($this_arp == $arp)
                                                {{ $arp }}<br>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            </td>
           
                            <!-- LAND -->
                            <td class="border-hidden text-right vertical-top" style="width: 1.3cm; background: ##4cef9b;" >
                                @if(isset($val['kind']))
                                    @if(!is_null($val['kind']))
                                        @if(preg_match('/building/i', $val['kind']) != 1)
                                            {{ number_format($val['assess_val'],2) }}<br>
                                        @else
                                            <br>
                                        @endif
                                    @elseif(isset($val['actual_use']))
                                        @if(!is_null($val['actual_use']))
                                            @if(preg_match('/bldg/i', $val['actual_use']) != 1)
                                                {{ number_format($val['assess_val'],2) }}<br>
                                            @else
                                                <br>
                                            @endif
                                        @endif
                                    @endif
                                @elseif(isset($val['actual_use']))
                                    @if(!is_null($val['actual_use']))
                                        @if(preg_match('/bldg/i', $val['actual_use']) != 1)
                                            {{ number_format($val['assess_val'],2) }}<br>
                                        @else
                                            <br>
                                        @endif
                                    @endif
                                @endif
                            </td>

                            <!-- IMPROVEMENT (BUILDING/MACHINE) -->
                            <td class="border-hidden text-right vertical-top" style="width: 1.3cm; background: ##4287f5; padding-right: 5px;" >
                                @if(isset($val['kind']))
                                    @if(!is_null($val['kind']))
                                        @if(preg_match('/building/i', $val['kind']) == 1)
                                            {{ number_format($val['assess_val'],2) }}<br>
                                        @else
                                            <br>
                                        @endif
                                    @elseif(isset($val['actual_use']))
                                        @if(!is_null($val['actual_use']))
                                            @if(preg_match('/bldg/i', $val['actual_use']) == 1)
                                                {{ number_format($val['assess_val'],2) }}<br>
                                            @else
                                                <br>
                                            @endif
                                        @endif
                                    @endif
                                @elseif(isset($val['actual_use']))
                                    @if(!is_null($val['actual_use']))
                                        @if(preg_match('/bldg/i', $val['actual_use']) == 1)
                                            {{ number_format($val['assess_val'],2) }}<br>
                                        @else
                                            <br>
                                        @endif
                                    @endif
                                @endif
                            </td>

                            <td class="border-hidden text-left vertical-top text-right" style="width: 1.5cm; background: ##f276c4; padding-right: -25px;">
                                <!-- assessed value TOTAL -->
                                <?php 
                                    $total_assess_val = 0; 
                                    $limit_counter = 0; 
                                ?>

                                @if($limit_counter <= $limit)
                                    @foreach($annual_arp as $this_arp => $data)
                                        <?php 
                                            /*$this_arp_next = array_search(next($annual_arp), $annual_arp);
                                            prev($annual_arp);
                                            $this_arp_prev = array_search(prev($annual_arp), $annual_arp);
                                            next($annual_arp);*/

                                            next($annual_arp);
                                            $this_arp_next = key($annual_arp);
                                            prev($annual_arp);
                                            prev($annual_arp);
                                            $this_arp_prev = key($annual_arp);
                                            next($annual_arp);
                                        ?>
                                        @if(isset($annual_per_arp['yearly'][$this_arp][$year]))
                                            @if($this_arp_next != false)
                                                @if(isset($annual_per_arp[$this_arp_next]))
                                                    @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp)
                                                        {{-- number_format($total_assess_val, 2) --}}<!-- <br> -->
                                                        {{ number_format($annual_per_arp[$this_arp]['assess_val']) }}<br>
                                                        {{ number_format($annual_per_arp[$this_arp_next]['assess_val']) }}<br>
                                                    @endif
                                                @else
                                                    {{ number_format($val['assess_val'], 2) }}
                                                @endif
                                            @elseif($this_arp_prev != false)
                                                @if(isset($annual_per_arp[$this_arp_prev]))
                                                    @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_prev || $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] == $this_arp)
                                                        {{-- number_format($total_assess_val, 2) --}}<!-- <br> -->
                                                        {{ number_format($annual_per_arp[$this_arp_prev]['assess_val']) }}<br>
                                                        {{ number_format($annual_per_arp[$this_arp]['assess_val']) }}<br>
                                                    @endif
                                                @else
                                                    {{ number_format($val['assess_val'], 2) }}
                                                @endif
                                            @elseif($this_arp == $arp)
                                                {{ number_format($val['assess_val'], 2) }}
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            </td>

                            <td class="border-hidden text-left vertical-top" style="width: 3cm; background: ##a276c4; position: relative; padding-left: 25px;">
                                <div style="margin: 0; padding: 0; text-align: right;"> 
                                    <?php
                                        $limit_counter = 0;
                                        $total_tax_due = 0;
                                        $year_to = 0;
                                    ?>
                                    @if($limit_counter <= $limit)
                                        @foreach($annual_arp as $this_arp => $data)
                                            <?php 
                                                next($annual_arp);
                                                $this_arp_next = key($annual_arp);
                                                prev($annual_arp);
                                                prev($annual_arp);
                                                $this_arp_prev = key($annual_arp);
                                                next($annual_arp);
                                            ?>
                                            @if(isset($annual_per_arp['yearly'][$this_arp][$year]))
                                                @if($year < \Carbon\Carbon::now()->addYear()->format('Y'))
                                                    <?php $next_pg = false; ?>
                                                    @if($this_arp_next != false)
                                                        @if(isset($annual_per_arp[$this_arp_next]))
                                                            @foreach($annual_arp as $this_arp => $data)
                                                                @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp)
                                                                    @foreach($annual_per_arp['yearly'] as $this_arp2 => $data2)
                                                                        <?php 
                                                                            if($this_arp2 == $this_arp || $this_arp2 == $this_arp_next) {
                                                                                foreach($data2 as $year2 => $val2) {
                                                                                    if(strlen($year2) > 4) {
                                                                                        // $total_tax_due += $val2['sef'];
                                                                                        if($year2 < \Carbon\Carbon::now()->addYear()->format('Y')) {
                                                                                            $year_to = $year2 > $year_to ? $year2 : $year_to;
                                                                                        }
                                                                                    } else {
                                                                                        // $total_tax_due += $val2['assess_val']*.01;
                                                                                        $y = explode('-', $year2);
                                                                                        if ($y[0] < \Carbon\Carbon::now()->addYear()->format('Y')) {
                                                                                            $year_to = $year2 > $year_to ? $year2 : $year_to;
                                                                                        }
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
                                                                                        if($year2 < \Carbon\Carbon::now()->addYear()->format('Y')) {
                                                                                            $year_to = $year2 > $year_to ? $year2 : $year_to;
                                                                                        }
                                                                                    } else {
                                                                                        // $total_tax_due += $val2['assess_val']*.01;
                                                                                        $y = explode('-', $year2);
                                                                                        if ($y[0] < \Carbon\Carbon::now()->addYear()->format('Y')) {
                                                                                            $year_to = $year2 > $year_to ? $year2 : $year_to;
                                                                                        }
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
                                            @endif
                                        @endforeach
                                    @endif

                                    @if($limit_counter <= $limit)
                                        @foreach($annual_arp as $this_arp => $data)
                                            <?php 
                                                next($annual_arp);
                                                $this_arp_next = key($annual_arp);
                                                prev($annual_arp);
                                                prev($annual_arp);
                                                $this_arp_prev = key($annual_arp);
                                                next($annual_arp);
                                            ?>
                                            @if(isset($annual_per_arp['yearly'][$this_arp][$year]))
                                                @if($year < \Carbon\Carbon::now()->addYear()->format('Y'))
                                                    <?php $next_pg = false; ?>
                                                    @if($this_arp_next != false)
                                                        @if(isset($annual_per_arp[$this_arp_next]))
                                                            @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp)
                                                                @if($year_to > 0)       
                                                                    @if(strlen($year) == 4)
                                                                        <!--{{-- number_format($total_tax_due, 2) --}}  <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br> ({{-- $year --}}-{{-- $year_to --}}) <br> -->

                                                                        <?php
                                                                            $yrs = array_keys($annual_per_arp['yearly'][$this_arp_next]);
                                                                            $least_yr = count(array_keys($annual_per_arp['yearly'][$this_arp_next]));
                                                                        ?>

                                                                        @if(isset($annual_per_arp['yearly'][$this_arp][$year_to]))
                                                                            {{ number_format(($annual_per_arp[$this_arp]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $yrs[$least_yr-1] }}-{{ $year_to }})<br>
                                                                        @else
                                                                            {{ number_format(($annual_per_arp[$this_arp]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }})<br>
                                                                        @endif

                                                                        @if(isset($annual_per_arp['yearly'][$this_arp_next][$year_to]))
                                                                            {{ number_format(($annual_per_arp[$this_arp_next]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $yrs[$least_yr-1]  }}-{{ $year_to }})<br>
                                                                        @else
                                                                            {{ number_format(($annual_per_arp[$this_arp_next]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }})<br>
                                                                        @endif

                                                                        <?php
                                                                            $limit_counter++;
                                                                        ?>
                                                                    @else
                                                                        <?php
                                                                            $year_ex = explode('-', $year);
                                                                            $yrs = array_keys($annual_per_arp['yearly'][$this_arp_next]);
                                                                            $least_yr = count(array_keys($annual_per_arp['yearly'][$this_arp_next]));
                                                                        ?>
                                                                        <!-- {{-- number_format($total_tax_due, 2) --}} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{-- $year_ex[0] }}-{{ $year_to --}})<br> -->

                                                                        @if(isset($annual_per_arp['yearly'][$this_arp][$year_to]))
                                                                            {{ number_format(($annual_per_arp[$this_arp]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $yrs[$least_yr-1] }}-{{ $year_to }})<br>
                                                                        @else
                                                                            {{ number_format(($annual_per_arp[$this_arp]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year_ex[0] }}-{{ $year_ex[1] }})<br>
                                                                        @endif

                                                                        @if(isset($annual_per_arp['yearly'][$this_arp_next][$year_to]))
                                                                            @if(strlen($yrs[$least_yr-1]) > 4)
                                                                                <?php
                                                                                    $br = explode('-', $yrs[$least_yr-1]);
                                                                                ?>
                                                                                {{ number_format(($annual_per_arp[$this_arp_next]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $br[0] }}-{{ $year_to }})<br>
                                                                            @else
                                                                                {{ number_format(($annual_per_arp[$this_arp_next]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $yrs[$least_yr-1] }}-{{ $year_to }})<br>
                                                                            @endif
                                                                            
                                                                        @else
                                                                            {{ number_format(($annual_per_arp[$this_arp_next]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year_ex[0] }}-{{ $year_ex[1] }})<br>
                                                                        @endif
                                                                        <?php
                                                                            $limit_counter++;
                                                                        ?>
                                                                    @endif
                                                                @elseif(isset($val['to']))
                                                                    @if(strlen($year) == 4)
                                                                        <!-- {{-- number_format($total_tax_due, 2) --}} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{-- $year --}}-{{-- $val['to'] --}})<br> -->

                                                                        {{ number_format(($annual_per_arp[$this_arp]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }}-{{ $val['to'] }})<br>
                                                                        {{ number_format(($annual_per_arp[$this_arp_next]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }}-{{ $val['to'] }})<br>
                                                                        <?php
                                                                            $limit_counter++;
                                                                        ?>
                                                                    @else
                                                                        <?php
                                                                            $year_ex = explode('-', $year);
                                                                        ?>
                                                                        <!-- {{-- number_format($total_tax_due, 2) --}} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{-- $year_ex[0] --}}-{{-- $val['to'] --}})<br> -->

                                                                        {{ number_format(($annual_per_arp[$this_arp]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year_ex[0] }}-{{ $val['to'] }})<br>
                                                                        {{ number_format(($annual_per_arp[$this_arp_next]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year_ex[0] }}-{{ $val['to'] }})<br>
                                                                        <?php
                                                                            $limit_counter++;
                                                                        ?>
                                                                    @endif
                                                                @else
                                                                    <!-- {{-- number_format($total_tax_due, 2) --}} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{-- $year --}})<br>  -->

                                                                    {{ number_format(($annual_per_arp[$this_arp]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }})<br> 
                                                                    {{ number_format(($annual_per_arp[$this_arp_next]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }})<br> 
                                                                    <?php
                                                                        $limit_counter++;
                                                                    ?>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @elseif($this_arp_prev != false)
                                                        @if(isset($annual_per_arp[$this_arp_prev]))
                                                            @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_prev || $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] == $this_arp || $this_arp == $arp)
                                                                @if($year_to > 0)
                                                                    <!-- {{-- number_format($total_tax_due, 2) --}} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{-- $year --}}-{{-- $year_to --}})<br> -->

                                                                    {{ number_format(($annual_per_arp[$this_arp]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }}-{{ $year_to }})<br>
                                                                    {{ number_format(($annual_per_arp[$this_arp_prev]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }}-{{ $year_to }})<br>
                                                                    <?php
                                                                        $limit_counter++;
                                                                    ?>
                                                                @elseif(isset($val['to']))
                                                                    <!-- {{-- number_format($total_tax_due, 2) --}} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{-- $year --}}-{{-- $val['to'] --}})<br> -->

                                                                    {{ number_format(($annual_per_arp[$this_arp]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }}-{{ $val['to'] }})<br>
                                                                    {{ number_format(($annual_per_arp[$this_arp_prev]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }}-{{ $val['to'] }})<br>
                                                                    <?php
                                                                        $limit_counter++;
                                                                    ?>
                                                                @else
                                                                    <!-- {{-- number_format($total_tax_due, 2) --}} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{-- $year --}})<br>  -->

                                                                    {{ number_format(($annual_per_arp[$this_arp]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }})<br> 
                                                                    {{ number_format(($annual_per_arp[$this_arp_prev]['assess_val']*.01), 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }})<br> 
                                                                    <?php
                                                                        $limit_counter++;
                                                                    ?>
                                                                @endif
                                                            @endif
                                                        @else
                                                            @foreach($annual_arp as $this_arp => $data)
                                                                @if($this_arp == $arp)
                                                                    @foreach($data as $year => $val)
                                                                        @if($year < \Carbon\Carbon::now()->addYear()->format('Y'))
                                                                            @if(isset($val['to']))                                
                                                                                {{ number_format($annual_per_arp[$this_arp]['assess_val']*.01, 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }}-{{ $val['to'] }})<br>
                                                                                <?php
                                                                                    $limit_counter++;
                                                                                ?>
                                                                                @else
                                                                                {{ number_format($annual_per_arp[$this_arp]['assess_val']*.01, 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }})<br> 
                                                                                <?php
                                                                                    $limit_counter++;
                                                                                ?>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @elseif($this_arp == $arp)
                                                        @foreach($data as $year => $val)
                                                            @if($year < \Carbon\Carbon::now()->addYear()->format('Y'))
                                                                @if(isset($val['to']))                     
                                                                    {{ number_format($annual_per_arp[$this_arp]['assess_val']*.01, 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }}-{{ $val['to'] }})<br>
                                                                    <?php
                                                                        $limit_counter++;
                                                                    ?>
                                                                @else
                                                                    {{ number_format($annual_per_arp[$this_arp]['assess_val']*.01, 2) }} <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>({{ $year }})<br> 
                                                                    <?php
                                                                        $limit_counter++;
                                                                    ?>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </td>

                            <td class="border-hidden text-left vertical-top" style="width: 1.7cm; background: ##cde25f; text-align: center;">
                                <?php $limit_counter = 0; ?>
                                @if($limit_counter <= $limit)
                                    @foreach($annual_arp as $this_arp => $data)
                                        <?php 
                                            next($annual_arp);
                                            $this_arp_next = key($annual_arp);
                                            prev($annual_arp);
                                            prev($annual_arp);
                                            $this_arp_prev = key($annual_arp);
                                            next($annual_arp);
                                        ?>
                                        @if(isset($annual_per_arp['yearly'][$this_arp][$year]))
                                            @if($this_arp_next != false)
                                                @if(isset($annual_per_arp[$this_arp_next]))
                                                    @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp)
                                                        @if(isset($val['to']))                                
                                                            BASIC<br> 
                                                            SEF<br> 
                                                            BASIC<br> 
                                                            SEF<br>  
                                                            <?php
                                                                $limit_counter++;
                                                            ?>
                                                        @else
                                                            BASIC<br> 
                                                            SEF<br> 
                                                            BASIC<br> 
                                                            SEF<br> 
                                                            <?php
                                                                $limit_counter++;
                                                            ?>
                                                        @endif
                                                    @endif
                                                @else
                                                    @foreach($data as $year => $val)
                                                        @if(isset($val['to']))                                
                                                            BASIC<br> 
                                                            SEF<br> 
                                                            <?php
                                                                $limit_counter++;
                                                            ?>
                                                        @else
                                                            BASIC<br> 
                                                            SEF<br> 
                                                            <?php
                                                                $limit_counter++;
                                                            ?>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @elseif($this_arp_prev != false)
                                                @if(isset($annual_per_arp[$this_arp_prev]))
                                                    @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_prev || $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] == $this_arp)
                                                        @if(isset($val['to']))                                
                                                            BASIC<br> 
                                                            SEF<br> 
                                                            BASIC<br> 
                                                            SEF<br>   
                                                            <?php
                                                                $limit_counter++;
                                                            ?>
                                                        @else
                                                            BASIC<br> 
                                                            SEF<br> 
                                                            BASIC<br> 
                                                            SEF<br>  
                                                            <?php
                                                                $limit_counter++;
                                                            ?>
                                                        @endif
                                                    @else
                                                        @if(isset($val['to']))                                
                                                            BASIC<br> 
                                                            SEF<br>  
                                                            <?php
                                                                $limit_counter++;
                                                            ?>
                                                        @else
                                                            BASIC<br> 
                                                            SEF<br>  
                                                            <?php
                                                                $limit_counter++;
                                                            ?>
                                                        @endif
                                                    @endif
                                                @else
                                                    @foreach($data as $year => $val)
                                                        @if(isset($val['to']))                                
                                                            BASIC<br> 
                                                            SEF<br>  
                                                            <?php
                                                                $limit_counter++;
                                                            ?>
                                                        @else
                                                            BASIC<br> 
                                                            SEF<br> 
                                                            <?php
                                                                $limit_counter++;
                                                            ?>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @elseif($this_arp == $arp)
                                                @foreach($data as $year => $val)
                                                    <?php
                                                        if($advance == true && $year < \Carbon\Carbon::now()->addYear()->format('Y')) {
                                                            // $next_pg = true;
                                                            continue;
                                                        }
                                                    ?>
                                                    @if(isset($val['to']))                                
                                                        BASIC<br> 
                                                        SEF<br> 
                                                        <?php
                                                            $limit_counter++;
                                                        ?>
                                                    @else
                                                        BASIC<br> 
                                                        SEF<br> 
                                                        <?php
                                                            $limit_counter++;
                                                        ?>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            </td>

                            <td class="border-hidden text-right vertical-top" style="width: 1.1cm; background: ##e8aa4e; padding-right: 8px;">
                                <?php
                                    $limit_counter = 0;
                                    // $total_tax_due = 0;
                                    $total_tax_due = [];
                                ?>
                                @if($limit_counter <= $limit)
                                    @foreach($annual_arp as $this_arp => $data)
                                        <?php 
                                            next($annual_arp);
                                            $this_arp_next = key($annual_arp);
                                            prev($annual_arp);
                                            prev($annual_arp);
                                            $this_arp_prev = key($annual_arp);
                                            next($annual_arp);
                                            $next_pg = false;
                                        ?>
                                        @if(isset($annual_per_arp[$this_arp]))
                                            @if($this_arp_next != false)
                                                @if(isset($annual_per_arp[$this_arp_next]))
                                                    @foreach($annual_arp as $this_arp => $data)
                                                        @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp)
                                                            @foreach($annual_per_arp['yearly'] as $this_arp2 => $data2)
                                                                <?php 
                                                                    if($this_arp2 == $this_arp || $this_arp2 == $this_arp_next) {
                                                                        foreach($data2 as $year2 => $val2) {
                                                                            if(!isset($total_tax_due[$this_arp2])) {
                                                                                $total_tax_due[$this_arp2] = 0;
                                                                            }
                                                                            for($i = 0; $i < count($keys); $i++) {
                                                                                if($year2 < \Carbon\Carbon::now()->addYear()->format('Y')) {
                                                                                    if(strlen($year2) > 4) {
                                                                                        $total_tax_due[$this_arp2] += $val2['sef'];
                                                                                    } else {
                                                                                        $total_tax_due[$this_arp2] += $val2['assess_val']*.01;
                                                                                    }
                                                                                } else if($year2 < \Carbon\Carbon::now()->addYear()->format('Y') && $year2 >= $keys[$i]) {
                                                                                    if(strlen($year2) > 4) {
                                                                                        $total_tax_due[$this_arp2] += $val2['sef'];
                                                                                    } else {
                                                                                        $total_tax_due[$this_arp2] += $val2['assess_val']*.01;
                                                                                    }
                                                                                }
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
                                                                            if(!isset($total_tax_due[$this_arp2])) {
                                                                                $total_tax_due[$this_arp2] = 0;
                                                                            }
                                                                            if($year2 < \Carbon\Carbon::now()->addYear()->format('Y') && $advance == true) {
                                                                                if(strlen($year2) > 4) {
                                                                                    $total_tax_due[$this_arp2] += $val2['sef'];
                                                                                } else {
                                                                                    $total_tax_due[$this_arp2] += $val2['assess_val']*.01;
                                                                                }
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
                                @endif

                                @if($limit_counter <= $limit)
                                    @foreach($annual_arp as $this_arp => $data)
                                        @if($year < \Carbon\Carbon::now()->addYear()->format('Y'))
                                            <?php 
                                                next($annual_arp);
                                                $this_arp_next = key($annual_arp);
                                                prev($annual_arp);
                                                prev($annual_arp);
                                                $this_arp_prev = key($annual_arp);
                                                next($annual_arp);
                                                $next_pg = false;
                                            ?>
                                            @if(isset($annual_per_arp['yearly'][$this_arp][$year]))
                                                @if($this_arp_next != false)
                                                    @if(isset($annual_per_arp[$this_arp_next]))
                                                        @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp)
                                                            @if(isset($val['to']))                                
                                                                {{ number_format($total_tax_due[$this_arp], 2) }}<br>
                                                                {{ number_format($total_tax_due[$this_arp], 2) }}<br> 
                                                                {{ number_format($total_tax_due[$this_arp_next], 2) }}<br>
                                                                {{ number_format($total_tax_due[$this_arp_next], 2) }}<br> 
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            @else
                                                                {{ number_format($total_tax_due[$this_arp], 2) }}<br>
                                                                {{ number_format($total_tax_due[$this_arp], 2) }}<br> 
                                                                {{ number_format($total_tax_due[$this_arp_next], 2) }}<br>
                                                                {{ number_format($total_tax_due[$this_arp_next], 2) }}<br> 
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            @endif
                                                        @endif
                                                    @else
                                                        @foreach($data as $year => $val)
                                                            @if(isset($val['to']))                                
                                                                {{ number_format($val['assess_val']*.01, 2) }}<br>
                                                                {{ number_format($val['assess_val']*.01, 2) }}<br> 
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            @else
                                                                {{ number_format($val['assess_val']*.01, 2) }}<br> 
                                                                {{ number_format($val['assess_val']*.01, 2) }}<br> 
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @elseif($this_arp_prev != false)
                                                    @if(isset($annual_per_arp[$this_arp_prev]))
                                                        @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_prev || $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] == $this_arp)
                                                            @if(isset($val['to']))                                
                                                                {{ number_format($total_tax_due[$this_arp], 2) }}<br>
                                                                {{ number_format($total_tax_due[$this_arp], 2) }}<br> 
                                                                {{ number_format($total_tax_due[$this_arp_prev], 2) }}<br>
                                                                {{ number_format($total_tax_due[$this_arp_prev], 2) }}<br>  
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            @else
                                                                {{ number_format($total_tax_due[$this_arp], 2) }}<br>
                                                                {{ number_format($total_tax_due[$this_arp], 2) }}<br> 
                                                                {{ number_format($total_tax_due[$this_arp_prev], 2) }}<br>
                                                                {{ number_format($total_tax_due[$this_arp_prev], 2) }}<br> 
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            @endif
                                                        @else
                                                            @if(isset($val['to']))                                
                                                                {{ number_format($total_tax_due[$this_arp], 2) }}<br>
                                                                {{ number_format($total_tax_due[$this_arp], 2) }}<br> 
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            @else
                                                                {{ number_format($total_tax_due[$this_arp], 2) }}<br> 
                                                                {{ number_format($total_tax_due[$this_arp], 2) }}<br> 
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            @endif
                                                        @endif
                                                    @else
                                                        @foreach($data as $year => $val)
                                                            @if(isset($val['to']))                                
                                                                {{ number_format($val['assess_val']*.01, 2) }}<br>
                                                                {{ number_format($val['assess_val']*.01, 2) }}<br> 
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            @else
                                                                {{ number_format($val['assess_val']*.01, 2) }}<br> 
                                                                {{ number_format($val['assess_val']*.01, 2) }}<br> 
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @elseif($this_arp == $arp)
                                                    @foreach($data as $year => $val)
                                                        @if($year < \Carbon\Carbon::now()->addYear()->format('Y'))
                                                            <?php
                                                                // if($advance == true && $year != \Carbon\Carbon::now()->addYear()->format('Y')) {
                                                                //     $next_pg = true;
                                                                //     continue;
                                                                // }
                                                                // dd($data);
                                                            ?>
                                                            @if(isset($val['to']))                                
                                                                {{ number_format($val['assess_val']*.01, 2) }}<br>
                                                                {{ number_format($val['assess_val']*.01, 2) }}<br> 
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            @else
                                                                {{ number_format($val['assess_val']*.01, 2) }}<br> 
                                                                {{ number_format($val['assess_val']*.01, 2) }}<br> 
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            </td>

                            <td class="border-hidden text-right vertical-top" style="width: 1cm; background: ##e56b60; padding-right: -20px;">
                                <?php
                                    $limit_counter = 0;
                                    // $total_penalty = 0;
                                    // $total_discount = 0;
                                    $total_penalty = [];
                                    $total_discount = [];
                                ?>
                                @if($limit_counter <= $limit)
                                    @foreach($annual_arp as $this_arp => $data)
                                        <?php 
                                            /*$this_arp_next = array_search(next($annual_arp), $annual_arp);
                                            prev($annual_arp);
                                            $this_arp_prev = array_search(prev($annual_arp), $annual_arp);
                                            next($annual_arp);*/

                                            next($annual_arp);
                                            $this_arp_next = key($annual_arp);
                                            prev($annual_arp);
                                            prev($annual_arp);
                                            $this_arp_prev = key($annual_arp);
                                            next($annual_arp);
                                        ?>
                                        @if($year < \Carbon\Carbon::now()->addYear()->format('Y'))
                                            <?php $next_pg = false; ?>
                                            @if(isset($annual_per_arp['yearly'][$this_arp][$year]))
                                                @if($this_arp_next != false)
                                                    @if(isset($annual_per_arp[$this_arp_next]))
                                                        @foreach($annual_arp as $this_arp => $data)
                                                            @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp)
                                                                @foreach($annual_per_arp['yearly'] as $this_arp2 => $data2)
                                                                    <?php 
                                                                        // if($val['penalty'] > 0) {
                                                                        //     $total_penalty += $val['penalty'];
                                                                        // } elseif($val['discount'] > 0) {
                                                                        //     $total_discount += $val['discount'];
                                                                        // }

                                                                        if(!isset($total_penalty[$this_arp2])) {
                                                                            $total_penalty[$this_arp2] = 0;
                                                                        }
                                                                        if(!isset($total_discount[$this_arp2])) {
                                                                            $total_discount[$this_arp2] = 0;
                                                                        }

                                                                        if ($this_arp2 == $this_arp || $this_arp2 == $this_arp_next) {
                                                                            foreach($data2 as $year2 => $val2) {
                                                                                $total_penalty[$this_arp2] += $val2['penalty'];
                                                                                $total_discount[$this_arp2] += $val2['discount'];
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
                                                                        // if($val['penalty'] > 0) {
                                                                        //     $total_penalty += $val['penalty'];
                                                                        // } elseif($val['discount'] > 0) {
                                                                        //     $total_discount += $val['discount'];
                                                                        // }

                                                                        if(!isset($total_penalty[$this_arp2])) {
                                                                            $total_penalty[$this_arp2] = 0;
                                                                        }
                                                                        if(!isset($total_discount[$this_arp2])) {
                                                                            $total_discount[$this_arp2] = 0;
                                                                        }

                                                                        if ($this_arp2 == $this_arp || $this_arp2 == $this_arp_next) {
                                                                            foreach($data2 as $year2 => $val2) {
                                                                                $total_penalty[$this_arp2] += $val2['penalty'];
                                                                                $total_discount[$this_arp2] += $val2['discount'];
                                                                            }
                                                                        }
                                                                    ?>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                @endif

                                @if($limit_counter <= $limit)
                                    @foreach($annual_arp as $this_arp => $data)
                                        <?php 
                                            next($annual_arp);
                                            $this_arp_next = key($annual_arp);
                                            prev($annual_arp);
                                            prev($annual_arp);
                                            $this_arp_prev = key($annual_arp);
                                            next($annual_arp);
                                        ?>
                                        @if($year < \Carbon\Carbon::now()->addYear()->format('Y'))
                                            <?php $next_pg = false; ?>
                                            @if(isset($annual_per_arp['yearly'][$this_arp][$year]))
                                                @if($this_arp_next != false)
                                                    @if(isset($annual_per_arp[$this_arp_next]))
                                                        @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp)
                                                            @if($total_penalty > 0)                                
                                                                {{ number_format($total_penalty[$this_arp], 2) }}<br>
                                                                {{ number_format($total_penalty[$this_arp], 2) }}<br> 
                                                                {{ number_format($total_penalty[$this_arp_next], 2) }}<br>
                                                                {{ number_format($total_penalty[$this_arp_next], 2) }}<br> 
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            @elseif($total_discount > 0)
                                                                ({{ number_format($total_discount[$this_arp], 2) }})<br> 
                                                                ({{ number_format($total_discount[$this_arp], 2) }})<br> 
                                                                ({{ number_format($total_discount[$this_arp_next], 2) }})<br> 
                                                                ({{ number_format($total_discount[$this_arp_next], 2) }})<br> 
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            @endif
                                                        @endif
                                                    @else
                                                        @foreach($data as $year => $val)
                                                            @if($val['penalty'] > 0)
                                                                {{ number_format($val['penalty'], 2) }}<br>
                                                                {{ number_format($val['penalty'], 2) }}<br>
                                                            @elseif($val['discount'] > 0)
                                                                ({{ number_format($val['discount'], 2) }})<br>
                                                                ({{ number_format($val['discount'], 2) }})<br>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @elseif($this_arp_prev != false)
                                                    @if(isset($annual_per_arp[$this_arp_prev]))
                                                        @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_prev || $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] == $this_arp)
                                                            @if($total_penalty > 0)                                
                                                                {{ number_format($total_penalty[$this_arp], 2) }}<br>
                                                                {{ number_format($total_penalty[$this_arp], 2) }}<br> 
                                                                {{ number_format($total_penalty[$this_arp_prev], 2) }}<br>
                                                                {{ number_format($total_penalty[$this_arp_prev], 2) }}<br> 
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            @elseif($total_discount > 0)
                                                                ({{ number_format($total_discount[$this_arp], 2) }})<br> 
                                                                ({{ number_format($total_discount[$this_arp], 2) }})<br> 
                                                                ({{ number_format($total_discount[$this_arp_prev], 2) }})<br> 
                                                                ({{ number_format($total_discount[$this_arp_prev], 2) }})<br> 
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            @endif
                                                        @endif
                                                    @else
                                                        @foreach($data as $year => $val)
                                                            @if($val['penalty'] > 0)
                                                                {{ number_format($val['penalty'], 2) }}<br>
                                                                {{ number_format($val['penalty'], 2) }}<br>
                                                            @elseif($val['discount'] > 0)
                                                                ({{ number_format($val['discount'], 2) }})<br>
                                                                ({{ number_format($val['discount'], 2) }})<br>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @elseif($this_arp == $arp)
                                                    @foreach($data as $year => $val)
                                                        @if($year < \Carbon\Carbon::now()->addYear()->format('Y'))
                                                            @if($val['penalty'] > 0)
                                                                {{ number_format($val['penalty'], 2) }}<br>
                                                                {{ number_format($val['penalty'], 2) }}<br>
                                                            @elseif($val['discount'] > 0)
                                                                ({{ number_format($val['discount'], 2) }})<br>
                                                                ({{ number_format($val['discount'], 2) }})<br>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            </td>

                            <td class="border-hidden text-right vertical-top" style="width: 2.1cm; background: ##7fe83e; padding-left: 10px;">
                                <?php 
                                    $limit_total = 0; 
                                    $limit_counter = 0; 
                                    $row_total = 0;
                                    // $next_pg = true;
                                ?>
                                @if($limit_counter <= $limit)
                                    @foreach($annual_arp as $this_arp => $data)
                                        <?php 
                                            next($annual_arp);
                                            $this_arp_next = key($annual_arp);
                                            prev($annual_arp);
                                            prev($annual_arp);
                                            $this_arp_prev = key($annual_arp);
                                            next($annual_arp);
                                        ?>
                                        @if($year < \Carbon\Carbon::now()->addYear()->format('Y'))
                                            <?php $next_pg = false; ?>
                                            @if(isset($annual_per_arp['yearly'][$this_arp][$year]))
                                                @if($this_arp_next != false)
                                                    @if(isset($annual_per_arp[$this_arp_next]))
                                                        @foreach($annual_arp as $this_arp => $data)
                                                            @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp)
                                                                @foreach($annual_per_arp['yearly'] as $this_arp2 => $data2)
                                                                    <?php 
                                                                        if(!isset($row_total[$this_arp2])) {
                                                                            $row_total[$this_arp2] = 0;
                                                                        }

                                                                        if($this_arp2 == $this_arp || $this_arp2 == $this_arp_next) {
                                                                            foreach($data2 as $year2 => $val2) {
                                                                                if(strlen($year2) > 4) {
                                                                                    if($year2 < \Carbon\Carbon::now()->addYear()->format('Y')) {
                                                                                        $row_total[$this_arp2] += $val2['sef'] + number_format($val2['penalty'], 2) - number_format($val2['discount'], 2);
                                                                                        $limit_total += ($val2['sef'] + number_format($val2['penalty'], 2) - number_format($val2['discount'], 2))*2;
                                                                                    }
                                                                                } else {
                                                                                    $y = explode('-', $year2);
                                                                                    if($y[0] < \Carbon\Carbon::now()->addYear()->format('Y')) {
                                                                                        $row_total[$this_arp2] += ($val2['assess_val']*.01) + number_format($val2['penalty'], 2) - number_format($val2['discount'], 2);
                                                                                        $limit_total += (($val2['assess_val']*.01) + number_format($val2['penalty'], 2) - number_format($val2['discount'], 2))*2;
                                                                                    }
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
                                                                        if(!isset($row_total[$this_arp2])) {
                                                                            $row_total[$this_arp2] = 0;
                                                                        }

                                                                        if($this_arp2 == $this_arp || $this_arp2 == $this_arp_prev) {
                                                                            foreach($data2 as $year2 => $val2) {
                                                                                if(strlen($year2) > 4) {
                                                                                    if($year2 < \Carbon\Carbon::now()->addYear()->format('Y')) {
                                                                                        $row_total[$this_arp2] += $val2['sef'] + number_format($val2['penalty'], 2) - number_format($val2['discount'], 2);
                                                                                        $limit_total += ($val2['sef'] + number_format($val2['penalty'], 2) - number_format($val2['discount'], 2))*2;
                                                                                    }
                                                                                } else {
                                                                                    $y = explode('-', $year2);
                                                                                    if($y[0] < \Carbon\Carbon::now()->addYear()->format('Y')) {
                                                                                        $row_total[$this_arp2] += ($val2['assess_val']*.01) + number_format($val2['penalty'], 2) - number_format($val2['discount'], 2);
                                                                                        $limit_total += (($val2['assess_val']*.01) + number_format($val2['penalty'], 2) - number_format($val2['discount'], 2))*2;
                                                                                    }
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
                                        @endif
                                    @endforeach
                                @endif

                                @if($limit_counter <= $limit)
                                    @foreach($annual_arp as $this_arp => $data)
                                        <?php 
                                            next($annual_arp);
                                            $this_arp_next = key($annual_arp);
                                            prev($annual_arp);
                                            prev($annual_arp);
                                            $this_arp_prev = key($annual_arp);
                                            next($annual_arp);
                                        ?>
                                        @if($year < \Carbon\Carbon::now()->addYear()->format('Y'))
                                            <?php $next_pg = false; ?>
                                            @if(isset($annual_per_arp['yearly'][$this_arp][$year]))
                                                @if($this_arp_next != false)
                                                    @if(isset($annual_per_arp[$this_arp_next]))
                                                        @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp)                              
                                                            {{ number_format($row_total[$this_arp], 2) }}<br>
                                                            {{ number_format($row_total[$this_arp], 2) }}<br> 
                                                            {{ number_format($row_total[$this_arp_next], 2) }}<br>
                                                            {{ number_format($row_total[$this_arp_next], 2) }}<br> 
                                                            <?php
                                                                $limit_counter++;
                                                                $next_pg = false;
                                                            ?>
                                                        @endif
                                                    @else
                                                        @foreach($data as $year => $val)
                                                            @if($year < \Carbon\Carbon::now()->addYear()->format('Y'))
                                                                @if($val['penalty'] > 0)
                                                                    {{ number_format(($val['assess_val']*.01 + $val['penalty']), 2) }}<br>
                                                                    {{ number_format(($val['assess_val']*.01 + $val['penalty']), 2) }}<br>
                                                                @elseif($val['discount'] > 0)
                                                                    {{ number_format(($val['assess_val']*.01 - $val['discount']), 2) }}<br>
                                                                    {{ number_format(($val['assess_val']*.01 - $val['discount']), 2) }}<br>
                                                                @endif
                                                                <?php
                                                                    $limit_counter++;
                                                                    if($val['penalty'] > 0) {
                                                                        $limit_total += ((number_format($val['assess_val']*.01, 2)) + number_format($val['penalty'], 2))*2;
                                                                    } elseif($val['discount'] > 0) {
                                                                        $limit_total += (number_format($val['assess_val']*.01, 2) - number_format($val['discount'], 2))*2;
                                                                    }
                                                                ?>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @elseif($this_arp_prev != false)
                                                    @if(isset($annual_per_arp[$this_arp_prev]))
                                                        @if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_prev || $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] == $this_arp)                     
                                                            {{ number_format($row_total[$this_arp], 2) }}<br>
                                                            {{ number_format($row_total[$this_arp], 2) }}<br> 
                                                            {{ number_format($row_total[$this_arp_prev], 2) }}<br>
                                                            {{ number_format($row_total[$this_arp_prev], 2) }}<br> 
                                                            <?php
                                                                $limit_counter++;
                                                                $next_pg = false;
                                                            ?>
                                                        @endif
                                                    @else
                                                        @foreach($data as $year => $val)
                                                            @if($year < \Carbon\Carbon::now()->addYear()->format('Y'))
                                                                @if($val['penalty'] > 0)
                                                                    {{ number_format(($val['assess_val']*.01 + $val['penalty']), 2) }}<br>
                                                                    {{ number_format(($val['assess_val']*.01 + $val['penalty']), 2) }}<br>
                                                                @elseif($val['discount'] > 0)
                                                                    {{ number_format(($val['assess_val']*.01 - $val['discount']), 2) }}<br>
                                                                    {{ number_format(($val['assess_val']*.01 - $val['discount']), 2) }}<br>
                                                                @endif
                                                                <?php
                                                                    $limit_counter++;
                                                                    if($val['penalty'] > 0) {
                                                                        $limit_total += ((number_format($val['assess_val']*.01, 2)) + number_format($val['penalty'], 2))*2;
                                                                    } elseif($val['discount'] > 0) {
                                                                        $limit_total += (number_format($val['assess_val']*.01, 2) - number_format($val['discount'], 2))*2;
                                                                    }
                                                                ?>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @elseif($this_arp == $arp)
                                                    @foreach($data as $year => $val)
                                                        @if($year < \Carbon\Carbon::now()->addYear()->format('Y'))
                                                            @if($val['penalty'] > 0)
                                                                {{ number_format(($val['assess_val']*.01 + $val['penalty']), 2) }}<br>
                                                                {{ number_format(($val['assess_val']*.01 + $val['penalty']), 2) }}<br>
                                                            @elseif($val['discount'] > 0)
                                                                {{ number_format(($val['assess_val']*.01 - $val['discount']), 2) }}<br>
                                                                {{ number_format(($val['assess_val']*.01 - $val['discount']), 2) }}<br>
                                                            @endif
                                                            <?php
                                                                $limit_counter++;
                                                                if($val['penalty'] > 0) {
                                                                    $limit_total += ((number_format($val['assess_val']*.01, 2)) + number_format($val['penalty'], 2))*2;
                                                                } elseif($val['discount'] > 0) {
                                                                    $limit_total += (number_format($val['assess_val']*.01, 2) - number_format($val['discount'], 2))*2;
                                                                }
                                                            ?>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                    </tr>   
                        <tr class="">
                            <td colspan=5 rowspan="2"  style="border:0px #ffffff00" >
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
                            <td colspan="7" class="border-hidden text-right" style="background-color: ##7fe83e; padding-top: -38px;"><span class="text-hidden">TOTAL ></span> {{ number_format($limit_total, 2) }}</td> 
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
            @endforeach
        @endif
        @if(isset($next_pg))
            @if($next_pg == true)
                <?php
                    next($annual_arp);
                    $this_arp_next = key($annual_arp);
                    prev($annual_arp);
                ?>
                @if($this_arp_next != null)
                    <div style="page-break-after: always;"></div>
                @endif
            @endif
        @endif
    @endforeach
    <div class="bg">
    </div>
    </body>
</html>