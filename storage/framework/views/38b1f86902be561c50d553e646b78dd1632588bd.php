<!DOCTYPE html>
<html>
<head>
    <title>Receipt</title>
    <style>
        html{ margin: 0px; width: 12.50cm; height: 25.5cm;}
        @page  { margin: 0px; 
            size: 25.5cm 12.50cm ;}
        body{
            margin: 40px 0 0 0 ;
            font-size: 0.8em;
            font-family: arial, "sans-serif" !important;
            /*background-image: url(<?php echo e(URL::asset('form56.png')); ?>);*/
            
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
    <?php foreach($annual_per_arp['yearly'] as $arp => $dataa): ?>
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

        <?php foreach($dataa as $year => $val): ?>
            <?php
                $kk = array_keys($dataa);
                $search_exist = array_search($entry_year_adv, $kk);
                // if ($search_exist >= 0 && $search_exist !== false && count($kk) > 1) {
                //     $advance = true;
                //     $next_pg = true;
                // }
            ?>

            <?php /* <?php if($year <= $entry_year || (count($kk) == 1 && $year > $entry_year)): ?> */ ?>
                <table width="100%" class="border-hidden" style="margin: 0 ; background: ##dbba7d; position: absolute; top: -15px;">
                    <tr>
                        <td colspan=2 rowspan=2 height='15%' style="padding: 0; margin: 0; background: ##a7e57b;">
                            <table width="100%" class="border-hidden" style="padding: 0; margin: 0;">
                                <tr>
                                    <td style="margin:0" width="15%"></td>
                                    <td style="text-align: right; background-color: ##f7e9d7;" width="50%">
                                        <?php if($wmunicipality): ?>
                                            <b><?php echo e(strtoupper($receipt->municipality->name)); ?>, BENGUET</b>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding: 0; margin: 0; padding-left: -150px; background-color: ##fcba03;">
                                        <?php 
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
                                         ?>
                                        <div style="height:60px;margin-left: 140px; margin-top: -10px; background: ##b480fc;">
                                            <table width="95%" style="margin-top:0px;" class="border-hidden">
                                                <tbody>
                                                    <tr>
                                                        <td colspan=2 height='25px' class="border-hidden text-right" style="font-size: 12px;" >
                                                            <!-- PREVIOUS TAX RECEIPT NO. -->
                                                            <?php echo e(($prev_receipt)); ?>

                                                            <small><?php echo e($tax_type_name); ?></small>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td height="28px"  width="100" class="border-hidden text-right"  style="font-size: 12px; background: ##4287f5;  vertical-align: bottom;">
                                                            <?php echo e($prev_remarks); ?> 
                                                        </td>
                                                        <td height="28px"  width="100" class="border-hidden text-center"  style="font-size: 12px; background: ##4287f5;  vertical-align: bottom;">
                                                            <?php echo e($prev_date); ?> 
                                                        </td>
                                                        <td class="border-hidden text-center" style="font-size: 12px; width:2.7cm; background: ##5af542; vertical-align: bottom;">
                                                            <!-- FOR THE YEAR -->
                                                            <?php echo e($prev_year); ?>

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
                        <?php 
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
                          ?>
                        <td class="border-hidden text-right" style="background: ##eda6eb;">
                            <!-- DATE -->
                            <div style="margin-bottom:13px; padding-right: 25px;">
                                <?php echo e($date_entry->format('F d, Y')); ?>

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-hidden" height="20" style="background: ##a7e57b; padding-left: -20px;">
                            <!-- class="center" style="padding-left: -150px;" --> 
                            <div style="padding-left: 50px; padding-right: 45px; word-wrap: break-word;">
                                <?php echo e($receipt->customer->name); ?>

                            </div>
                        </td>
                        <td class="border-hidden" style="padding-left: -30px;"><?php echo e($total_words); ?> only</td>
                        <td class="border-hidden text-right" style="text-indent: 13px; padding-right: 25px; background: ##a7e57b;"><br /><?php echo e(number_format($form56['total'], 2)); ?></td>
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

                                        <?php foreach($full_part_unq as $fp => $dates): ?>
                                            <?php if(isset($full_partial_type[$fp])): ?>
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
                                                <?php if($prep_yr_frm == $prep_yr_to): ?>
                                                    <div style="word-wrap: break-word; background: ##e63e3e; padding-left: 75%;">
                                                        <?php echo e($prep_yr_frm); ?> <?php echo e($full_partial_type[$fp]); ?>

                                                    </div>
                                                <?php else: ?>
                                                    <div style="word-wrap: break-word; background: ##e63e3e; padding-left: 75%;">
                                                        <?php echo e($prep_yr_frm); ?>-<?php echo e($prep_yr_to); ?> <?php echo e($full_partial_type[$fp]); ?>

                                                    </div>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span style="word-wrap: break-word; width: 100px; background: ##70fc41; padding-left: 75%;">
                                                    <?php echo e($p_calendar_year); ?>

                                                </span>
                                            <?php endif; ?> 
                                            <br>
                                        <?php endforeach; ?>
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

                    <?php 
                        $count_tr = 0;
                        $period_covered  = '';
                        $count_tdrp = (count($receipt->F56Detailmny));
                        $owner = '';
                     ?>

                    <tr style="background: ##ef7385;">
                        <td class="border-hidden text-left vertical-top" style="height: 155px; padding-left: 20px; padding-right: 10px; background: ##ef7865;">
                            <?php if(isset($annual_per_arp[$arp]['owner'])): ?>
                            <?php echo e($annual_per_arp[$arp]['owner']); ?>

                            <?php endif; ?>
                        </td>

                        <!-- width: 1.2cm; -->
                        <td class="border-hidden text-left vertical-top" style="background: ##ef6585; padding-left: -15px;">
                            <?php if(!is_null($val['brgy'])): ?>
                                <?php echo e($val['brgy']->name); ?> <br> <?php echo e($val['tax_type']); ?>

                            <?php else: ?>
                                <?php echo e($val['tax_type']); ?>

                            <?php endif; ?>
                        </td>

                        <!-- width: 1cm; -->
                        <!-- padding-left: -70px; -->
                        <td class="border-hidden text-left vertical-top" style="background: ##689cf2; padding-left: -55px;" colspan="2">
                            <?php if($limit_counter <= $limit): ?> 
                                <?php foreach($annual_arp as $this_arp => $data): ?>
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
                                    <?php if(isset($annual_per_arp['yearly'][$this_arp][$year])): ?>
                                        <?php if($this_arp_next !== false && $this_arp_next !== null && $arp != $this_arp_next): ?>
                                            <?php if(isset($annual_per_arp[$this_arp_next])): ?>
                                                <?php foreach($annual_arp as $this_arp => $data): ?>
                                                    <?php if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp): ?>
                                                        <?php echo e($this_arp); ?><br>
                                                        <?php echo e($this_arp_next); ?><br>
                                                        <?php break; ?>
                                                    <?php elseif($annual_per_arp[$this_arp]['prev_tax_dec_no'] != $this_arp_next && $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] != $this_arp): ?>
                                                        <?php if(isset($count[$arp])): ?>
                                                            <?php if($count[$arp] <= 0): ?> 
                                                                <?php echo e($arp); ?><br>
                                                                <?php
                                                                    $count[$arp]++;
                                                                ?>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                        <?php 
                                                            break; 
                                                        ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <?php if(isset($count[$arp])): ?>
                                                    <?php if($count[$arp] <= 0): ?> 
                                                        <?php echo e($arp); ?><br>
                                                        <?php $count[$arp]++; ?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php break; ?>
                                            <?php endif; ?>
                                        <?php elseif($this_arp_prev !== false && $this_arp_prev !== null): ?>
                                            <?php if(isset($annual_per_arp[$this_arp_prev])): ?>
                                                <?php foreach($annual_arp as $this_arp => $data): ?>
                                                    <?php if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_prev || $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] == $this_arp): ?>
                                                        <?php echo e($this_arp_prev); ?><br>
                                                        <?php echo e($this_arp); ?><br>
                                                        <?php break; ?>
                                                    <?php elseif($annual_per_arp[$this_arp]['prev_tax_dec_no'] != $this_arp_prev && $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] != $this_arp): ?>
                                                        <?php if(isset($count[$arp])): ?>
                                                            <?php if($count[$arp] <= 0): ?>
                                                                <?php echo e($arp); ?><br>
                                                                <?php $count[$arp]++; ?>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                        <?php break; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <?php if(isset($count[$arp])): ?>
                                                    <?php if($count[$arp] <= 0): ?>
                                                        <?php echo e($arp); ?><br>
                                                        <?php $count[$arp]++; ?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php break; ?>
                                            <?php endif; ?>
                                        <?php elseif($this_arp == $arp): ?>
                                            <?php if($this_arp_next == null && $this_arp_prev == null && count(array_keys($annual_arp)) == 1): ?> 
                                                <?php if(isset($count[$arp])): ?>
                                                    <?php if($count[$arp] <= 0): ?>
                                                        <?php echo e($arp); ?><br>
                                                        <?php $count[$arp]++; ?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php break; ?>
                                            <?php elseif($this_arp_next == null && $this_arp_prev == null): ?>
                                                <?php if(isset($count[$arp])): ?>
                                                    <?php if($count[$arp] <= 0): ?>
                                                        <?php echo e($arp); ?><br>
                                                        <?php $count[$arp]++; ?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php break; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </td>

                        <!-- LAND -->
                        <!-- padding-left: -78px; -->
                        <td class="border-hidden text-left vertical-top" style="width: 1.3cm; background: ##4cef9b; padding-left: -65px; padding-right: 10px;">
                            <?php if(isset($annual_per_arp[$arp]['assess_val_class'])): ?>
                                <?php foreach($annual_per_arp[$arp]['assess_val_class'] as $index => $val): ?>
                                    <?php if(!is_null($val['kind'])): ?>
                                        <?php if(preg_match('/building/i', $val['kind']) != 1): ?>
                                            <?php echo e(number_format($val['assess_val'],2)); ?><br>
                                        <?php else: ?>
                                            <br>
                                        <?php endif; ?>
                                    <?php elseif(!is_null($val['actual_use'])): ?>
                                        <?php if(preg_match('/bldg/i', $val['actual_use']) != 1): ?>
                                            <?php echo e(number_format($val['assess_val'],2)); ?><br>
                                        <?php else: ?>
                                            <br>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </td>

                        <!--  padding-right: 5px; -->
                        <!-- IMPROVEMENT (BUILDING/MACHINE) -->
                        <td class="border-hidden text-right vertical-top" style="width: 1.3cm; background: ##4287f5; padding-right: 15px; padding-left: -250px;">
                            <?php if(isset($annual_per_arp[$arp]['assess_val_class'])): ?>
                                <?php foreach($annual_per_arp[$arp]['assess_val_class'] as $index => $val): ?>
                                    <?php if(!is_null($val['kind'])): ?>
                                        <?php if(preg_match('/building/i', $val['kind']) == 1): ?>
                                            <?php echo e(number_format($val['assess_val'],2)); ?><br>
                                        <?php else: ?>
                                            <br>
                                        <?php endif; ?>
                                    <?php elseif(!is_null($val['actual_use'])): ?>
                                        <?php if(preg_match('/bldg/i', $val['actual_use']) == 1): ?>
                                            <?php echo e(number_format($val['assess_val'],2)); ?><br>
                                        <?php else: ?>
                                            <br>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </td>

                        <!-- padding-right: -25px; width: 1.5cm;  -->
                        <td class="border-hidden text-left vertical-top text-right" style=" width: 2cm; background: ##f276c4; padding-right: -40px;">
                            <!-- assessed value TOTAL -->
                            <?php 
                                // $total_assess_val = 0; 
                                $limit_counter = 0; 
                            ?>
                            <?php if($limit_counter <= $limit): ?>
                                <?php foreach($annual_arp as $this_arp => $data): ?>
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
                                    <?php if(isset($annual_per_arp['yearly'][$this_arp][$year])): ?>
                                        <?php if($this_arp_next != false): ?>
                                            <?php if(isset($annual_per_arp[$this_arp_next])): ?>
                                                <?php if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp): ?>
                                                    <?php echo e(number_format($annual_per_arp[$this_arp]['assess_val'], 2)); ?><br>
                                                    <?php echo e(number_format($annual_per_arp[$this_arp_next]['assess_val'], 2)); ?><br>
                                                <?php elseif($annual_per_arp[$this_arp]['prev_tax_dec_no'] != $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] != $this_arp): ?>
                                                    <?php echo e(number_format($annual_per_arp[$arp]['assess_val'], 2)); ?><br>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php echo e(number_format($val['assess_val'], 2)); ?>

                                            <?php endif; ?>
                                        <?php elseif($this_arp_prev != false): ?>
                                            <?php if(isset($annual_per_arp[$this_arp_prev])): ?>
                                                <?php if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_prev || $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] == $this_arp): ?>
                                                    <?php echo e(number_format($annual_per_arp[$this_arp_prev]['assess_val'], 2)); ?><br>
                                                    <?php echo e(number_format($annual_per_arp[$this_arp]['assess_val'], 2)); ?><br>
                                                <?php elseif($annual_per_arp[$this_arp]['prev_tax_dec_no'] != $this_arp_prev || $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] != $this_arp): ?>
                                                    <?php echo e(number_format($annual_per_arp[$arp]['assess_val'], 2)); ?><br>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php echo e(number_format($val['assess_val'], 2)); ?>

                                            <?php endif; ?>
                                        <?php elseif($this_arp == $arp): ?>
                                            <?php /* <?php if($this_arp_next == null && $this_arp_prev == null && count(array_keys($annual_arp[$arp])) == 1): ?>  */ ?>
                                                <?php /* number_format($val['assess_val'], 2) */ ?>
                                            <?php /* <?php else: ?> */ ?>
                                                <?php if(isset($annual_per_arp[$this_arp]['assess_val_class'])): ?>
                                                    <?php foreach($annual_per_arp[$this_arp]['assess_val_class'] as $i => $val): ?>
                                                        <?php echo e(number_format($val['assess_val'], 2)); ?>

                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            <?php /* <?php endif; ?> */ ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
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

                        <?php if($limit_counter <= $limit): ?>
                            <?php foreach($annual_arp as $this_arp => $data): ?>
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
                                <?php if(isset($annual_per_arp['yearly'][$this_arp][$year])): ?>
                                    <?php if($this_arp_next != false): ?>
                                        <?php if(isset($annual_per_arp[$this_arp_next])): ?>
                                            <?php foreach($annual_arp as $this_arp => $data): ?>
                                                <?php if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp): ?>
                                                    <?php foreach($annual_per_arp['yearly'] as $this_arp2 => $data2): ?>
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
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php elseif($this_arp_prev != false): ?>
                                        <?php if(isset($annual_per_arp[$this_arp_prev])): ?> 
                                            <?php foreach($annual_arp as $this_arp => $data): ?>
                                                <?php if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_prev || $annual_per_arp[$this_arp_prev]['prev_tax_dec_no'] == $this_arp): ?>
                                                    <?php foreach($annual_per_arp['yearly'] as $this_arp2 => $data2): ?>
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
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <td class="border-hidden text-left vertical-top" style="width: 3cm; background: ##a276c4; position: relative; padding-left: 25px;">
                                <div style="margin: 0; padding: 0; text-align: right;"> 
                                    <?php foreach($annual_arp as $this_arp => $data): ?>
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
                                        ?>
                                        <?php if(isset($annual_per_arp['yearly'][$this_arp][$year])): ?>
                                            <?php if($this_arp_next != false): ?>
                                                <?php if(isset($annual_per_arp[$this_arp_next])): ?>
                                                <?php
                                                    $yrs = array_keys($annual_per_arp['yearly'][$this_arp_next]);
                                                ?>
                                                    <?php if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp): ?>
                                                        <?php if($year_to > 0): ?> 
                                                            <?php if(strlen($year) == 4): ?>
                                                                <?php if(isset($annual_per_arp['yearly'][$this_arp][$year_to])): ?>
                                                                    <?php if($year_to == $entry_year): ?> 
                                                                        <?php
                                                                            $diff = ($year_to) - $yrs[$least_yr];
                                                                        ?>
                                                                        <?php echo e(number_format(($annual_per_arp[$this_arp]['assess_val']*.01), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($yrs[$least_yr]); ?>-<?php echo e($year_to-1); ?>)<br>
                                                                        <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp][[$year]]['sef']), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year_to); ?>)<br>
                                                                    <?php else: ?>
                                                                        <?php
                                                                            $diff = ($year_to) - $yrs[$least_yr];
                                                                        ?>
                                                                        <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp][$year]['sef']), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($yrs[$least_yr]); ?>-<?php echo e($year_to); ?>)<br>
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year); ?>)<br>
                                                                <?php endif; ?>

                                                                <?php if(isset($annual_per_arp['yearly'][$this_arp_next][$year_to])): ?>
                                                                    <?php if($year_to == $entry_year): ?>
                                                                        <?php
                                                                            $diff = intval($year_to) - intval($yrs[0]);
                                                                        ?>
                                                                        <?php if(isset($annual_per_arp['yearly'][$this_arp_next][$year_to-1])): ?>
                                                                            <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp_next][$year_to-1]['sef']), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($yrs[0]); ?>-<?php echo e($year_to-1); ?>)<br>
                                                                        <?php else: ?>
                                                                            <?php
                                                                                $yearss = array_keys($annual_per_arp['yearly'][$this_arp_next]);
                                                                                $yrto_key = array_search($year_to, $yearss);
                                                                                $get_b4_yrto_year = $year_to-1;
                                                                                if($yrto_key != false) {
                                                                                    // get previous year before year_to
                                                                                    $b4_yrto = $yearss[$yrto_key-1];
                                                                                    if(strpos($b4_yrto, strval($year_to-1)) != false) {
                                                                                        $get_b4_yrto_year = $b4_yrto;
                                                                                    }
                                                                                }
                                                                            ?>
                                                                            <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp_next][$get_b4_yrto_year]['sef']), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($get_b4_yrto_year); ?>)<br>
                                                                        <?php endif; ?>
                                                                        <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp_next][$year_to]['sef']), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year_to); ?>)<br>
                                                                    <?php else: ?>
                                                                        <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp_next]['assess_val']*.01), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($yrs[0]); ?>-<?php echo e($year_to); ?>)<br>
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year); ?>)<br>
                                                                <?php endif; ?>
                                                                
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            <?php else: ?>
                                                                <?php
                                                                    $year_ex = explode('-', $year);
                                                                    $diff = intval($year_to) - intval($yrs[$least_yr]);
                                                                ?>
                                                                <?php if(isset($annual_per_arp['yearly'][$this_arp][$year_to])): ?>
                                                                    <?php if($year_to == $entry_year && isset($annual_per_arp['yearly'][$this_arp][$year])): ?>
                                                                        <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp][$year]['sef']), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($yrs[$least_yr]); ?>-<?php echo e($year_to-1); ?>)<br>
                                                                        <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp][$year]['sef']), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year_to); ?>)<br>
                                                                    <?php else: ?>
                                                                        <?php echo e(number_format(($annual_per_arp[$this_arp]['assess_val']*.01), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($yrs[$least_yr]); ?>-<?php echo e($year_to); ?>)<br>
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <?php if($year_ex[1] == $entry_year && isset($annual_per_arp['yearly'][$this_arp][$year])): ?>
                                                                        <?php
                                                                            $diff = ($year_ex[1]) - $year_ex[0];
                                                                        ?>
                                                                        <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp][$year]['sef']), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year_ex[0]); ?>-<?php echo e($year_ex[1]-1); ?>)<br>
                                                                        <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp][$year]['sef']), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year_ex[1]); ?>)<br>
                                                                    <?php else: ?>
                                                                        <?php echo e(number_format(($annual_per_arp[$this_arp]['assess_val']*.01), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year_ex[0]); ?>-<?php echo e($year_ex[1]); ?>)<br>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>

                                                                <?php if(isset($annual_per_arp['yearly'][$this_arp_next][$year_to])): ?>
                                                                    <?php if(strlen($yrs[$least_yr]) > 4 && isset($annual_per_arp['yearly'][$this_arp_next][$year])): ?>
                                                                        <?php
                                                                            $br = explode('-', $yrs[$least_yr]);
                                                                            $diff = ($year_to) - $br[0];
                                                                        ?>
                                                                        <?php if($year_to == $entry_year): ?>)
                                                                            <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp_next][$year]['sef']), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($br[0]); ?>-<?php echo e($year_to-1); ?>)<br>
                                                                            <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp_next][$year]['sef']), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year_to); ?>)<br>
                                                                        <?php else: ?>
                                                                            <?php echo e(number_format(($annual_per_arp[$this_arp_next]['assess_val']*.01), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($br[0]); ?>-<?php echo e($year_to); ?>)<br>
                                                                        <?php endif; ?>
                                                                    <?php elseif(strlen($yrs[$least_yr]) > 4): ?>
                                                                        <?php
                                                                            $br = explode('-', $yrs[$least_yr]);
                                                                        ?>
                                                                        <?php echo e(number_format(($annual_per_arp[$this_arp_next]['assess_val']*.01), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($br[0]); ?>-<?php echo e($year_to); ?>)<br>
                                                                    <?php else: ?>
                                                                        <?php echo e(number_format(($annual_per_arp[$this_arp_next]['assess_val']*.01), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($yrs[$least_yr]); ?>-<?php echo e($year_to); ?>)<br>
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <?php if($year_to == $entry_year && isset($annual_per_arp['yearly'][$this_arp_next][$year])): ?>
                                                                        <?php $diff = ($year_ex[1]) - $year_ex[0]; ?>
                                                                        <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp_next][$year]['sef']), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year_ex[0]); ?>-<?php echo e($year_ex[1]-1); ?>)<br>
                                                                        <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp_next][$year]['sef']), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year_ex[1]); ?>)<br>
                                                                    <?php else: ?>
                                                                        <?php echo e(number_format(($annual_per_arp[$this_arp_next]['assess_val']*.01), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year_ex[0]); ?>-<?php echo e($year_ex[1]); ?>)<br>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            <?php endif; ?>
                                                        <?php elseif(isset($val['to'])): ?>
                                                            <?php if(strlen($year) == 4): ?>
                                                                <?php $diff = ($val['to']) - $year; ?>
                                                                <?php if($val['to'] == $entry_year): ?>
                                                                    <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp][$year]['sef']), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($val['to']); ?>)<br>
                                                                    <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp][$year]['sef']), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year); ?>-<?php echo e($val['to']-1); ?>)<br>
                                                                    <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp_next][$year]['sef']), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($val['to']); ?>)<br>
                                                                    <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp_next][$year]['sef']), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year); ?>-<?php echo e($val['to']-1); ?>)<br>
                                                                <?php else: ?>
                                                                    <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp_next][$year]['sef']), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year); ?>-<?php echo e($val['to']); ?>)<br>
                                                                    <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp][$year]['sef']), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year); ?>-<?php echo e($val['to']); ?>)<br>
                                                                <?php endif; ?>
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            <?php else: ?>
                                                                <?php
                                                                    $year_ex = explode('-', $year);
                                                                    $diff = ($val['to']) - $year_ex[0];
                                                                ?>
                                                                <?php if($val['to'] == $entry_year): ?>
                                                                    <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp][$year]['sef']), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($val['to']); ?>)<br>
                                                                    <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp][$year]['sef']), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year_ex[0]); ?>-<?php echo e($val['to']-1); ?>)<br>
                                                                    <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp_next][$year]['sef']), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($val['to']); ?>)<br>
                                                                    <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp_next][$year]['sef']), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year_ex[0]); ?>-<?php echo e($val['to']-1); ?>)<br>
                                                                <?php else: ?>
                                                                    <?php echo e(number_format(($annual_per_arp[$this_arp_next]['assess_val']*.01), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year_ex[0]); ?>-<?php echo e($val['to']); ?>)<br>
                                                                    <?php echo e(number_format(($annual_per_arp[$this_arp]['assess_val']*.01), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year_ex[0]); ?>-<?php echo e($val['to']); ?>)<br>
                                                                <?php endif; ?>
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp_next][$year]['sef']), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year); ?>)<br> 
                                                            <?php echo e(number_format(($annual_per_arp['yearly'][$this_arp][$year]['sef']), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year); ?>)<br> 
                                                            <?php
                                                                $limit_counter++;
                                                            ?>
                                                        <?php endif; ?>
                                                    <?php elseif($annual_per_arp[$this_arp]['prev_tax_dec_no'] != $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] != $this_arp): ?>
                                                        <?php echo e(number_format(($annual_per_arp['yearly'][$arp][$year]['assess_val']*.01), 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year); ?>)<br> 
                                                        <?php
                                                            $limit_counter++;
                                                            break;
                                                        ?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php elseif($this_arp == $arp): ?>
                                                <?php if($this_arp_next == null && $this_arp_prev == null && count(array_keys($annual_arp)) == 1): ?> 
                                                    <?php foreach($data as $year => $val): ?>
                                                        <?php
                                                            // if($year == $entry_year_adv && $advance == true) {
                                                            //     $next_pg = true;
                                                            //     continue;
                                                            // }
                                                        ?>
                                                        <?php if(isset($val['to'])): ?>
                                                            <?php if($year < $val['to']): ?>
                                                                <?php if($val['to'] == $entry_year): ?>
                                                                    <?php if($year <= ($val['to']-1)): ?>
                                                                        <!-- <?php /* <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($val['to']-1); ?>)<br> */ ?> -->
                                                                        <?php
                                                                            $yr_from1 = 0;
                                                                            $yr_to1 = 0;
                                                                            if(strlen($year) > 4) {
                                                                                $split = explode('-', $year);
                                                                                if($split[0] < $yr_from1 || $yr_from1 == 0)
                                                                                    $yr_from1 = $split[0];
                                                                                if($split[1] > $yr_to1 || $yr_to1 == 0)
                                                                                    $yr_to1 = $split[1];
                                                                            } else {
                                                                                if($year < $yr_from1 || $yr_from1 == 0)
                                                                                    $yr_from1 = $year;
                                                                                if($year > $yr_to1 || $yr_to1 == 0)
                                                                                    $yr_to1 = $year;
                                                                            }

                                                                            if(strlen($val['to']-1) > 4) {
                                                                                $split2 = explode('-', $val['to']-1);
                                                                                if($split2[0] < $yr_from1 || $yr_from1 == 0)
                                                                                    $yr_from1 = $split2[0];
                                                                                if($split2[1] > $yr_to1 || $yr_to1 == 0)
                                                                                    $yr_to1 = $split2[1];
                                                                            } else {
                                                                                if($val['to']-1 < $yr_from1 || $yr_from1 == 0)
                                                                                    $yr_from1 = $val['to']-1;
                                                                                if($val['to']-1 > $yr_to1 || $yr_to1 == 0)
                                                                                    $yr_to1 = $val['to']-1;
                                                                            }
                                                                        ?>
                                                                        <!-- <?php /* <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year); ?>-<?php echo e($val['to']-1); ?>)<br> */ ?> -->
                                                                        <!-- <?php /* <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($val['to']); ?>)<br> */ ?> -->
                                                                        <?php if($yr_from1 < $yr_to1): ?>
                                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($yr_from1); ?>-<?php echo e($yr_to1); ?>)<br>
                                                                        <?php else: ?>
                                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($yr_to1); ?>)<br>
                                                                        <?php endif; ?>
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($val['to']); ?>)<br>
                                                                    <?php else: ?>
                                                                        <?php 
                                                                            $diff = intval($val['to']) - intval($year); 
                                                                            if(strlen($year) > 4)
                                                                                $split = explode('-', $year);
                                                                        ?>
                                                                        <?php if(strlen($year) == 4): ?>
                                                                            <?php if($year < $val['to']-1): ?>
                                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year); ?>-<?php echo e($val['to']-1); ?>)<br>
                                                                            <?php else: ?>
                                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($val['to']-1); ?>)<br>
                                                                            <?php endif; ?>
                                                                        <?php else: ?>
                                                                            <?php if($split[0] < $val['to']-1): ?>
                                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($split[0]); ?>-<?php echo e($val['to']-1); ?>)<br>
                                                                            <?php else: ?>
                                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($val['to']-1); ?>)<br>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($val['to']); ?>)<br>
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <?php if($val['to'] == $entry_year_adv): ?>
                                                                        <!-- previous -->
                                                                        <?php if($year < $entry_year-1): ?>
                                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year); ?>-<?php echo e($val['to']-2); ?>)<br>
                                                                        <?php endif; ?>
                                                                        <!-- for current year -->
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']-1]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($val['to']-1); ?>)<br>
                                                                        <!-- advance year -->
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($val['to']); ?>)<br>
                                                                    <?php else: ?>
                                                                        <?php if(strlen($year) == 4): ?>
                                                                            <?php /* <?php if($year < $val['to']): ?>
                                                                                <?php echo e(number_format($val['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year); ?>-<?php echo e($val['to']); ?>)<br>
                                                                            <?php else: ?>
                                                                                <?php echo e(number_format($val['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($val['to']); ?>)<br>
                                                                            <?php endif; ?> */ ?>

                                                                            <?php if($year < $val['to']): ?>
                                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year); ?>-<?php echo e($val['to']); ?>)<br>
                                                                            <?php else: ?>
                                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($val['to']); ?>)<br>
                                                                            <?php endif; ?>
                                                                        <?php else: ?>
                                                                            <?php
                                                                                $split = explode('-', $year);
                                                                            ?>
                                                                            <?php if($split[0] < $val['to']): ?>
                                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($split[0]); ?>-<?php echo e($val['to']); ?>)<br>
                                                                            <?php else: ?>
                                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($val['to']); ?>)<br>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <?php $diff = ($year) - $val['to']; ?>
                                                                <?php if($year == $entry_year): ?>
                                                                    <?php if($val['to'] < $year-1): ?>
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($val['to']); ?>-<?php echo e($year-1); ?>)<br>
                                                                    <?php else: ?>
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year-1); ?>)<br>
                                                                    <?php endif; ?>
                                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year); ?>)<br>
                                                                <?php else: ?>
                                                                    <?php if($year == $entry_year_adv): ?>
                                                                        <?php if($val['to'] < $year-1): ?>
                                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($val['to']); ?>-<?php echo e($year-1); ?>)<br>
                                                                        <?php else: ?>
                                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year-1); ?>)<br>
                                                                        <?php endif; ?>
                                                                    <?php else: ?>
                                                                        <?php if($val['to'] < $year): ?>
                                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($val['to']); ?>-<?php echo e($year); ?>)<br>
                                                                        <?php else: ?>
                                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year); ?>)<br>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                            <?php
                                                                $limit_counter++;
                                                            ?>
                                                        <?php else: ?>
                                                            <!-- old code here 1 --> 
                                                            <?php if($year_to > $year && $year_to > 0): ?>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year); ?>)<br>
                                                            <?php elseif($year_to < $year && $year_to > 0): ?>
                                                                <?php $diff = ($year) - $year_to; ?>
                                                                <?php if($year_to < $year-1): ?>
                                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year_to); ?>-<?php echo e($year-1); ?>)<br>
                                                                <?php else: ?>
                                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year-1); ?>)<br>
                                                                <?php endif; ?>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year); ?>)<br>
                                                            <?php else: ?>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year); ?>)<br>
                                                            <?php endif; ?>
                                                            <?php
                                                                $limit_counter++;
                                                            ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php elseif($this_arp_next == null && $this_arp_prev == null): ?>
                                                    <?php foreach($data as $year => $val): ?>
                                                        <?php
                                                            // if($year < $entry_year_adv && $advance == true) {
                                                            //     $next_pg = true;
                                                            //     continue;
                                                            // }
                                                        ?>
                                                        <?php if(isset($val['to'])): ?>       
                                                            <?php if($year < $val['to']): ?>
                                                                <?php if($val['to'] == $entry_year): ?>
                                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year); ?>-<?php echo e($val['to']-1); ?>)<br>
                                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($val['to']); ?>)<br>
                                                                <?php else: ?>
                                                                    <?php if($val['to'] == $entry_year_adv): ?>
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year); ?>-<?php echo e($val['to']-1); ?>)<br>
                                                                    <?php else: ?>
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year); ?>-<?php echo e($val['to']); ?>)<br>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <?php if($year == $entry_year): ?>
                                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($val['to']); ?>-<?php echo e($year); ?>)<br>
                                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year); ?>)<br>
                                                                <?php else: ?>
                                                                    <?php if($year == $entry_year_adv): ?>
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($val['to']); ?>-<?php echo e($year-1); ?>)<br>
                                                                    <?php else: ?>
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($val['to']); ?>-<?php echo e($year); ?>)<br>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                            <?php
                                                                $limit_counter++;
                                                            ?>
                                                        <?php else: ?>
                                                            <?php 
                                                                $year_to = $year > $year_to ? $year : $year_to; 
                                                            ?>
                                                            <?php if($year_to > $year): ?>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year); ?>)<br>
                                                            <?php elseif($year_to < $year): ?>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year_to); ?>-<?php echo e($year); ?>)<br>
                                                            <?php else: ?>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>full<br>(<?php echo e($year); ?>)<br>
                                                            <?php endif; ?> 
                                                            <?php
                                                                $limit_counter++;
                                                            ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </td>

                            <td class="border-hidden text-left vertical-top" style="width: 1.5cm; background: ##cde25f; text-align: center;">
                                <?php foreach($annual_arp as $this_arp => $data): ?>
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
                                    ?>

                                    <?php if(isset($annual_per_arp['yearly'][$this_arp][$year])): ?>
                                        <?php if($this_arp_next != false): ?>
                                            <?php if(isset($annual_per_arp[$this_arp_next])): ?>
                                                <?php if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp): ?>
                                                    <?php if($year_to > 0): ?>   
                                                        <?php if($year_to == $entry_year): ?>   
                                                            <?php if(isset($annual_per_arp['yearly'][$this_arp][$year_to])): ?>
                                                                BASIC <br>
                                                                SEF <br>
                                                                BASIC <br>
                                                                SEF <br>
                                                            <?php else: ?>
                                                                BASIC <br>
                                                                SEF <br>
                                                            <?php endif; ?>

                                                            <?php if(isset($annual_per_arp['yearly'][$this_arp_next][$year_to])): ?>
                                                                BASIC <br>
                                                                SEF <br>
                                                                BASIC <br>
                                                                SEF <br>
                                                            <?php else: ?>
                                                                BASIC <br>
                                                                SEF <br>
                                                            <?php endif; ?>
                                                        <?php else: ?> 
                                                            <?php if(strlen($year) == 4): ?>
                                                                BASIC <br>
                                                                SEF <br>
                                                            <?php else: ?>
                                                                <?php
                                                                    $year_ex = explode('-', $year);
                                                                ?>
                                                                <?php if($year_ex[1] == $entry_year): ?>
                                                                    BASIC <br>
                                                                    SEF <br>
                                                                    BASIC <br>
                                                                    SEF <br>
                                                                <?php else: ?>
                                                                    BASIC <br>
                                                                    SEF <br>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                        <?php
                                                            $limit_counter++;
                                                        ?>
                                                    <?php elseif(isset($val['to'])): ?>
                                                        <?php if($val['to'] == $entry_year): ?>                          
                                                            BASIC<br> 
                                                            SEF<br> 
                                                            BASIC<br> 
                                                            SEF<br>  
                                                            BASIC<br> 
                                                            SEF<br> 
                                                        <?php else: ?>
                                                            BASIC<br> 
                                                            SEF<br> 
                                                            BASIC<br> 
                                                            SEF<br>
                                                        <?php endif; ?>
                                                        <?php
                                                            $limit_counter++;
                                                        ?>
                                                    <?php else: ?>
                                                        <?php if($year == $entry_year): ?>   
                                                            BASIC<br> 
                                                            SEF<br> 
                                                            BASIC<br> 
                                                            SEF<br> 
                                                            BASIC<br> 
                                                            SEF<br>
                                                        <?php else: ?>
                                                            BASIC<br> 
                                                            SEF<br> 
                                                            BASIC<br> 
                                                            SEF<br> 
                                                        <?php endif; ?>
                                                        <?php
                                                            $limit_counter++;
                                                        ?>
                                                    <?php endif; ?>
                                                <?php elseif($annual_per_arp[$this_arp]['prev_tax_dec_no'] != $this_arp_next && $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] != $this_arp): ?>
                                                    <?php if($year == $entry_year && count(array_keys($annual_per_arp['yearly'][$this_arp])) > 1): ?>
                                                        BASIC<br> 
                                                        SEF<br> 
                                                        BASIC<br> 
                                                        SEF<br> 
                                                    <?php else: ?>
                                                        BASIC<br> 
                                                        SEF<br> 
                                                    <?php endif; ?>
                                                    <?php
                                                        $limit_counter++;
                                                        break;
                                                    ?>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php foreach($data as $year => $val): ?>
                                                    <?php if(isset($val['to'])): ?>
                                                        <?php if($val['to'] == $entry_year): ?>      
                                                            BASIC<br> 
                                                            SEF<br> 
                                                            BASIC<br> 
                                                            SEF<br>                           
                                                        <?php else: ?>
                                                            BASIC<br> 
                                                            SEF<br> 
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        BASIC<br> 
                                                        SEF<br> 
                                                    <?php endif; ?>
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        <?php elseif($this_arp == $arp): ?>
                                            <?php foreach($data as $year => $val): ?>
                                                <?php
                                                    // if($advance == true && $year == $entry_year_adv) {
                                                    //     $next_pg = true;
                                                    //     continue;
                                                    // }
                                                ?>
                                                <?php if(isset($val['to'])): ?>  
                                                    <?php if($val['to'] == $entry_year): ?>    
                                                        BASIC<br> 
                                                        SEF<br>
                                                        BASIC<br> 
                                                        SEF<br>                      
                                                    <?php else: ?>
                                                        <?php if($val['to'] == $entry_year_adv): ?>
                                                            <!-- previous -->
                                                            <?php if($year < $entry_year-1): ?>
                                                                BASIC<br> 
                                                                SEF<br> 
                                                            <?php endif; ?>
                                                            <!-- current -->
                                                            BASIC<br> 
                                                            SEF<br> 
                                                            <!-- advance -->
                                                            BASIC<br> 
                                                            SEF<br> 
                                                        <?php else: ?>
                                                            BASIC<br> 
                                                            SEF<br> 
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                <?php else: ?>
                                                    BASIC<br> 
                                                    SEF<br> 
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </td>

                            <td class="border-hidden text-right vertical-top" style="width: 1.1cm; background: ##e8aa4e; padding-right: 6px;">
                                <?php foreach($annual_arp as $this_arp => $data): ?>
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
                                    ?>

                                    <?php if(isset($annual_per_arp['yearly'][$this_arp][$year])): ?>
                                        <?php if($this_arp_next != false): ?>
                                            <?php if(isset($annual_per_arp[$this_arp_next])): ?>
                                                <?php if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp): ?>
                                                    <?php if($year_to > 0): ?>                                              
                                                        <?php if($year_to == $entry_year): ?>
                                                            <?php if(isset($annual_per_arp['yearly'][$this_arp][$year_to])): ?>
                                                                <?php echo e(number_format($total_tax_due[$this_arp] - $annual_per_arp['yearly'][$this_arp][$year_to]['sef'], 2)); ?>

                                                                <?php echo e(number_format($total_tax_due[$this_arp] - $annual_per_arp['yearly'][$this_arp][$year_to]['sef'], 2)); ?>

                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year_to]['sef'], 2)); ?>

                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year_to]['sef'], 2)); ?>

                                                            <?php else: ?>
                                                                <?php if(isset($annual_arp[$this_arp][$year])): ?>
                                                                    <?php echo e(number_format($annual_arp[$this_arp][$year]['sef'], 2)); ?>

                                                                    <?php echo e(number_format($annual_arp[$this_arp][$year]['sef'], 2)); ?>

                                                                <?php else: ?>
                                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?>

                                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?>

                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                            <?php if(isset($annual_per_arp['yearly'][$this_arp_next][$year_to])): ?>
                                                                <?php echo e(number_format($total_tax_due[$this_arp_next] - $annual_per_arp['yearly'][$this_arp_next][$year_to]['sef'], 2)); ?>

                                                                <?php echo e(number_format($total_tax_due[$this_arp_next] - $annual_per_arp['yearly'][$this_arp_next][$year_to]['sef'], 2)); ?>

                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year_to]['sef'], 2)); ?>

                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year_to]['sef'], 2)); ?>

                                                            <?php else: ?>
                                                                <?php if(isset($annual_arp[$this_arp_next][$year])): ?>
                                                                    <?php echo e(number_format($annual_arp[$this_arp_next][$year]['sef'], 2)); ?>

                                                                    <?php echo e(number_format($annual_arp[$this_arp_next][$year]['sef'], 2)); ?>

                                                                <?php else: ?>
                                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year]['sef'], 2)); ?>

                                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year]['sef'], 2)); ?>

                                                                <?php endif; ?>
                                                            <?php endif; ?>                                                        
                                                        <?php else: ?>
                                                            <?php if(strlen($year) == 4): ?>
                                                                <?php if(isset($annual_per_arp['yearly'][$this_arp][$year_to])): ?>
                                                                    <?php echo e(number_format($total_tax_due[$this_arp], 2)); ?>

                                                                    <?php echo e(number_format($total_tax_due[$this_arp], 2)); ?>

                                                                <?php elseif(isset($annual_per_arp['yearly'][$this_arp_next][$year_to])): ?>
                                                                    <?php echo e(number_format($total_tax_due[$this_arp_next], 2)); ?>

                                                                    <?php echo e(number_format($total_tax_due[$this_arp_next], 2)); ?>

                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <?php
                                                                    $year_ex = explode('-', $year);
                                                                ?>
                                                                <?php if($year_ex[1] == $entry_year): ?>
                                                                    <?php if(isset($annual_per_arp['yearly'][$this_arp][$year_ex[1]])): ?>
                                                                        <?php echo e(number_format($total_tax_due[$this_arp] - $annual_per_arp['yearly'][$this_arp][$year_ex[1]]['sef'], 2)); ?>

                                                                        <?php echo e(number_format($total_tax_due[$this_arp] - $annual_per_arp['yearly'][$this_arp][$year_ex[1]]['sef'], 2)); ?>

                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year_ex[1]]['sef'], 2)); ?>

                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year_ex[1]]['sef'], 2)); ?>

                                                                    <?php elseif(isset($annual_per_arp['yearly'][$this_arp_next][$year_ex[1]])): ?>
                                                                        <?php echo e(number_format($total_tax_due[$this_arp_next] - $annual_per_arp['yearly'][$this_arp_next][$year_ex[1]]['sef'], 2)); ?>

                                                                        <?php echo e(number_format($total_tax_due[$this_arp_next] - $annual_per_arp['yearly'][$this_arp_next][$year_ex[1]]['sef'], 2)); ?>

                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year_ex[1]]['sef'], 2)); ?>

                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year_ex[1]]['sef'], 2)); ?>

                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <?php if(isset($annual_per_arp['yearly'][$this_arp][$year_ex[1]])): ?>
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year_ex[1]]['sef'], 2)); ?>

                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year_ex[1]]['sef'], 2)); ?>

                                                                    <?php elseif(isset($annual_per_arp['yearly'][$this_arp_next][$year_ex[1]])): ?>
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year_ex[1]]['sef'], 2)); ?>

                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year_ex[1]]['sef'], 2)); ?>

                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        <?php endif; ?>   
                                                        <?php
                                                            $limit_counter++;
                                                        ?>                                                         
                                                    <?php elseif(isset($val['to'])): ?>
                                                        <?php if($val['to'] == $entry_year): ?>
                                                            <?php echo e(number_format($total_tax_due[$this_arp], 2)); ?><br>
                                                            <?php echo e(number_format($total_tax_due[$this_arp], 2)); ?><br> 
                                                            <?php echo e(number_format($total_tax_due[$this_arp_next] - $annual_per_arp['yearly'][$this_arp_next][$val['to']['sef']], 2)); ?><br>
                                                            <?php echo e(number_format($total_tax_due[$this_arp_next] - $annual_per_arp['yearly'][$this_arp_next][$val['to']['sef']], 2)); ?><br> 
                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$val['to']['sef']], 2)); ?><br>
                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$val['to']['sef']], 2)); ?><br> 
                                                        <?php else: ?>     
                                                            <?php echo e(number_format($total_tax_due[$this_arp], 2)); ?><br>
                                                            <?php echo e(number_format($total_tax_due[$this_arp], 2)); ?><br> 
                                                            <?php echo e(number_format($total_tax_due[$this_arp_next], 2)); ?><br>
                                                            <?php echo e(number_format($total_tax_due[$this_arp_next], 2)); ?><br> 
                                                        <?php endif; ?>
                                                        <?php
                                                            $limit_counter++;
                                                        ?>
                                                    <?php else: ?>
                                                        <?php if($year == $entry_year): ?>
                                                            <?php echo e(number_format($total_tax_due[$this_arp], 2)); ?><br>
                                                            <?php echo e(number_format($total_tax_due[$this_arp], 2)); ?><br> 
                                                            <?php echo e(number_format($total_tax_due[$this_arp_next] - $annual_per_arp['yearly'][$this_arp_next][$year]['sef'], 2)); ?><br>
                                                            <?php echo e(number_format($total_tax_due[$this_arp_next] - $annual_per_arp['yearly'][$this_arp_next][$year]['sef'], 2)); ?><br> 
                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year]['sef'], 2)); ?><br>
                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year]['sef'], 2)); ?><br>
                                                        <?php else: ?>
                                                            <?php echo e(number_format($total_tax_due[$this_arp], 2)); ?><br>
                                                            <?php echo e(number_format($total_tax_due[$this_arp], 2)); ?><br> 
                                                            <?php echo e(number_format($total_tax_due[$this_arp_next], 2)); ?><br>
                                                            <?php echo e(number_format($total_tax_due[$this_arp_next], 2)); ?><br> 
                                                        <?php endif; ?>
                                                        <?php
                                                            $limit_counter++;
                                                        ?>
                                                    <?php endif; ?>
                                                <?php elseif($annual_per_arp[$this_arp]['prev_tax_dec_no'] != $this_arp_next && $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] != $this_arp): ?>
                                                    <?php echo e(number_format($annual_per_arp[$arp]['assess_val']*.01, 2)); ?><br>
                                                    <?php echo e(number_format($annual_per_arp[$arp]['assess_val']*.01, 2)); ?><br>
                                                    <?php break; ?>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php foreach($data as $year => $val): ?>
                                                    <?php if(isset($val['to'])): ?>   
                                                        <?php if($val['to'] == $entry_year): ?> 
                                                            <?php echo e(number_format(($val['assess_val']*01) - $annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?><br>
                                                            <?php echo e(number_format(($val['assess_val']*01) - $annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?><br>
                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?><br>
                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?><br>
                                                        <?php else: ?>                          
                                                            <?php echo e(number_format($val['assess_val']*.01, 2)); ?><br>
                                                            <?php echo e(number_format($val['assess_val']*.01, 2)); ?><br>
                                                        <?php endif; ?>
                                                        <?php
                                                            $limit_counter++;
                                                        ?>
                                                    <?php else: ?>
                                                        <?php echo e(number_format($val['assess_val']*.01, 2)); ?><br>
                                                        <?php echo e(number_format($val['assess_val']*.01, 2)); ?><br>
                                                        <?php
                                                            $limit_counter++;
                                                        ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        <?php elseif($this_arp == $arp): ?>
                                            <?php foreach($data as $year => $val): ?>
                                                <?php
                                                    // if($advance == true && $year == $entry_year_adv) {
                                                    //     $next_pg = true;
                                                    //     continue;
                                                    // }
                                                ?>
                                                <?php if(isset($val['to'])): ?>
                                                    <?php if($val['to'] == $entry_year): ?>
                                                        <?php
                                                            // if($val['to'] == $entry_year_adv)
                                                            //     $diff = intval($val['to']-1) - intval($year);
                                                            // else
                                                            //     $diff = intval($val['to']) - intval($year);
                                                            // $compute = $annual_per_arp['yearly'][$this_arp][$year]['sef']*$diff;

                                                            $total_sef = 0;
                                                            foreach($annual_per_arp['yearly'][$this_arp] as $yearr => $data) {
                                                                if($yearr < $val['to'] && $yearr >= $year) {
                                                                    $total_sef += $data['sef'];
                                                                }
                                                            }
                                                        ?>   
                                                        <?php /* number_format($compute, 2) */ ?><!-- <br> -->
                                                        <?php /* number_format($compute, 2) */ ?><!-- <br> -->
                                                        <?php /* number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2) */ ?><!-- <br> -->
                                                        <?php /* number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2) */ ?><!-- <br> -->
                                                        <?php echo e(number_format($total_sef, 2)); ?><br>
                                                        <?php echo e(number_format($total_sef, 2)); ?><br>
                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']]['sef'], 2)); ?><br>
                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']]['sef'], 2)); ?><br>
                                                    <?php elseif($val['to'] >= $entry_year_adv && $year >= $entry_year_adv): ?>
                                                        <?php
                                                            if(strlen($year) == 4) {
                                                                $compute = 0;
                                                                foreach($annual_per_arp['yearly'] as $arpp => $data) {
                                                                    foreach ($data as $yearrr => $value) {
                                                                        if($yearrr >= $year && $yearrr <= $val['to']) {
                                                                            $compute += $value['sef'];
                                                                        }
                                                                    }
                                                                }
                                                                $compute2 = $annual_per_arp['yearly'][$this_arp][$year]['sef'];
                                                            } else {
                                                                // $compute = $annual_per_arp['yearly'][$this_arp][$year]['sef'];
                                                                $compute = 0;
                                                                foreach($annual_per_arp['yearly'] as $arpp => $data) {
                                                                    foreach ($data as $yearrr => $value) {
                                                                    $split = explode("-", $yearrr);
                                                                    if($split[0] >= $year && $split[1] <= $val['to'])
                                                                        $compute += $value['sef'];
                                                                    }
                                                                }
                                                            }
                                                        ?>   

                                                        <?php echo e(number_format($compute, 2)); ?> <br>
                                                        <?php echo e(number_format($compute, 2)); ?> <br>
                                                    <?php else: ?>
                                                        <?php
                                                            // if($val['to'] == $entry_year_adv)
                                                            //     $diff = (intval($val['to']-2) - intval($year))+1;
                                                            // else
                                                            //     $diff = (intval($val['to']) - intval($year))+1;
                                                            if(strlen($year) == 4) {
                                                                // $compute = $annual_per_arp['yearly'][$this_arp][$year]['sef']*$diff;
                                                                $compute = 0;
                                                                foreach($annual_per_arp['yearly'] as $arpp => $data) {
                                                                    foreach ($data as $yearrr => $value) {
                                                                        if($yearrr >= $year && $yearrr < $entry_year && $yearrr < $entry_year_adv && $yearrr <= $val['to']) {
                                                                            $compute += $value['sef'];
                                                                        }
                                                                    }
                                                                }
                                                                $compute2 = $annual_per_arp['yearly'][$this_arp][$year]['sef'];
                                                            } else {
                                                                // $compute = $annual_per_arp['yearly'][$this_arp][$year]['sef'];
                                                                $compute = 0;
                                                                foreach($annual_per_arp['yearly'] as $arpp => $data) {
                                                                    foreach ($data as $yearrr => $value) {
                                                                    $split = explode("-", $yearrr);
                                                                    if($split[0] >= $year && $split[0] < $entry_year && $split[1] < $entry_year_adv && $split[1] <= $val['to'])
                                                                        $compute += $value['sef'];
                                                                    }
                                                                }
                                                            }
                                                        ?>     
                                                        <?php if($val['to'] == $entry_year_adv): ?>   
                                                            <!-- previous -->
                                                            <?php if($year < $entry_year-1): ?>
                                                                <?php echo e(number_format($compute, 2)); ?><br>
                                                                <?php echo e(number_format($compute, 2)); ?><br> 
                                                            <?php endif; ?>
                                                            <!-- current -->
                                                            <?php echo e(number_format($compute2, 2)); ?><br>
                                                            <?php echo e(number_format($compute2, 2)); ?><br> 
                                                            <!-- advance -->
                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$entry_year_adv]['sef'], 2)); ?><br>
                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$entry_year_adv]['sef'], 2)); ?><br>
                                                        <?php else: ?>       
                                                            <?php echo e(number_format($compute, 2)); ?><br>
                                                            <?php echo e(number_format($compute, 2)); ?><br>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                <?php else: ?>
                                                    <?php /* number_format($val['assess_val']*.01, 2) */ ?><!-- <br>  -->
                                                    <?php /* number_format($val['assess_val']*.01, 2) */ ?><!-- <br> -->
                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?><br> 
                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?><br> 
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </td>

                            <td class="border-hidden text-right vertical-top" style="width: 1cm; background: ##e56b60; padding-right: -15px;">
                                <?php foreach($annual_arp as $this_arp => $data): ?>
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
                                    ?>

                                    <?php if(isset($annual_per_arp['yearly'][$this_arp][$year])): ?>
                                        <?php if($this_arp_next != false): ?>
                                            <?php if(isset($annual_per_arp[$this_arp_next])): ?>
                                                <?php if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp): ?>
                                                    <?php if($year_to > 0): ?>  
                                                        <?php if($year_to == $entry_year): ?>
                                                            <?php if(isset($annual_per_arp['yearly'][$this_arp][$year_to])): ?>
                                                                <?php if($total_penalty[$this_arp] > 0): ?>
                                                                    <?php echo e(number_format($total_penalty[$this_arp] - $annual_per_arp['yearly'][$this_arp][$year_to]['penalty'], 2)); ?><br>
                                                                    <?php echo e(number_format($total_penalty[$this_arp] - $annual_per_arp['yearly'][$this_arp][$year_to]['penalty'], 2)); ?><br>
                                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year_to]['penalty'], 2)); ?><br>
                                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year_to]['penalty'], 2)); ?><br>
                                                                <?php endif; ?>
                                                                <?php if($total_discount[$this_arp] > 0): ?>
                                                                    (<?php echo e(number_format($total_discount[$this_arp] - $annual_per_arp['yearly'][$this_arp][$year_to]['discount'], 2)); ?>)<br>
                                                                    (<?php echo e(number_format($total_discount[$this_arp] - $annual_per_arp['yearly'][$this_arp][$year_to]['discount'], 2)); ?>)<br>
                                                                    (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year_to]['discount'], 2)); ?>)<br>
                                                                    (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year_to]['discount'], 2)); ?>)<br>
                                                                <?php endif; ?>
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            <?php else: ?>
                                                                <?php if(isset($annual_arp[$this_arp][$year])): ?>
                                                                    <?php if($annual_arp[$this_arp][$year]['penalty'] > 0): ?>
                                                                        <?php echo e(number_format($annual_arp[$this_arp][$year]['penalty'], 2)); ?>

                                                                        <?php echo e(number_format($annual_arp[$this_arp][$year]['penalty'], 2)); ?>

                                                                    <?php endif; ?>
                                                                    <?php if($annual_arp[$this_arp][$year]['discount'] > 0): ?>
                                                                        <?php echo e(number_format($annual_arp[$this_arp][$year]['discount'], 2)); ?>

                                                                        <?php echo e(number_format($annual_arp[$this_arp][$year]['discount'], 2)); ?>

                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <?php if($total_penalty[$this_arp] > 0): ?>
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['penalty'], 2)); ?><br>
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['penalty'], 2)); ?><br>
                                                                    <?php endif; ?>
                                                                    <?php if($total_discount[$this_arp] > 0): ?>
                                                                        (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['discount'], 2)); ?>)<br>
                                                                        (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['discount'], 2)); ?>)<br>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            <?php endif; ?>

                                                            <?php if(isset($annual_per_arp['yearly'][$this_arp_next][$year_to])): ?>
                                                                <?php if($total_penalty[$this_arp_next] > 0): ?>
                                                                    <?php echo e(number_format($total_penalty[$this_arp_next] - $annual_per_arp['yearly'][$this_arp_next][$year_to]['penalty'], 2)); ?><br>
                                                                    <?php echo e(number_format($total_penalty[$this_arp_next] - $annual_per_arp['yearly'][$this_arp_next][$year_to]['penalty'], 2)); ?><br>
                                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year_to]['penalty'], 2)); ?><br>
                                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year_to]['penalty'], 2)); ?><br>
                                                                <?php endif; ?>
                                                                <?php if($total_discount[$this_arp_next] > 0): ?>
                                                                    (<?php echo e(number_format($total_discount[$this_arp_next] - $annual_per_arp['yearly'][$this_arp_next][$year_to]['discount'], 2)); ?>)<br>
                                                                    (<?php echo e(number_format($total_discount[$this_arp_next] - $annual_per_arp['yearly'][$this_arp_next][$year_to]['discount'], 2)); ?>)<br>
                                                                    (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year_to]['discount'], 2)); ?>)<br>
                                                                    (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year_to]['discount'], 2)); ?>)<br>
                                                                    <?php
                                                                        $limit_counter++;
                                                                    ?>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <?php if(isset($annual_arp[$this_arp_next][$year])): ?>
                                                                    <?php if($annual_arp[$this_arp_next][$year]['penalty'] > 0): ?>
                                                                        <?php echo e(number_format($annual_arp[$this_arp_next][$year]['penalty'], 2)); ?>

                                                                        <?php echo e(number_format($annual_arp[$this_arp_next][$year]['penalty'], 2)); ?>

                                                                    <?php endif; ?>
                                                                    <?php if($annual_arp[$this_arp_next][$year]['discount'] > 0): ?>
                                                                        (<?php echo e(number_format($annual_arp[$this_arp_next][$year]['discount'], 2)); ?>)
                                                                        (<?php echo e(number_format($annual_arp[$this_arp_next][$year]['discount'], 2)); ?>)
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <?php if($total_penalty[$this_arp_next] > 0): ?>
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year]['penalty'], 2)); ?><br>
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year]['penalty'], 2)); ?><br>
                                                                    <?php endif; ?>
                                                                    <?php if($total_discount[$this_arp_next] > 0): ?>
                                                                        (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year]['discount'], 2)); ?>)<br>
                                                                        (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year]['discount'], 2)); ?>)<br>
                                                                        <?php
                                                                            $limit_counter++;
                                                                        ?>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <?php if(strlen($year) == 4): ?>
                                                                <?php if(isset($annual_per_arp['yearly'][$this_arp][$year_to])): ?>
                                                                    <?php if($total_penalty[$this_arp] > 0): ?>
                                                                        <?php echo e(number_format($total_penalty[$this_arp], 2)); ?><br>
                                                                        <?php echo e(number_format($total_penalty[$this_arp], 2)); ?><br>
                                                                    <?php endif; ?>
                                                                    <?php if($total_discount[$this_arp] > 0): ?>
                                                                        (<?php echo e(number_format($total_discount[$this_arp], 2)); ?>)<br>
                                                                        (<?php echo e(number_format($total_discount[$this_arp], 2)); ?>)<br>
                                                                    <?php endif; ?>
                                                                    <?php
                                                                        $limit_counter++;
                                                                    ?>
                                                                <?php elseif(isset($annual_per_arp['yearly'][$this_arp_next][$year_to])): ?>
                                                                    <?php if($total_penalty[$this_arp_next] > 0): ?>
                                                                        <?php echo e(number_format($total_penalty[$this_arp_next], 2)); ?><br>
                                                                        <?php echo e(number_format($total_penalty[$this_arp_next], 2)); ?><br>
                                                                    <?php endif; ?>
                                                                    <?php if($total_discount[$this_arp_next] > 0): ?>
                                                                        (<?php echo e(number_format($total_discount[$this_arp_next], 2)); ?>)<br>
                                                                        (<?php echo e(number_format($total_discount[$this_arp_next], 2)); ?>)<br>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <?php                                                                
                                                                    $year_ex = explode('-', $year);
                                                                ?>
                                                                <?php if($year_ex[1] == $entry_year): ?>
                                                                    <?php if(isset($annual_per_arp['yearly'][$this_arp][$year_ex[1]])): ?>
                                                                        
                                                                    <?php elseif(isset($annual_per_arp['yearly'][$this_arp_next][$year_ex[1]])): ?>
                                                                        
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <?php if(isset($annual_per_arp['yearly'][$this_arp][$year_ex[1]])): ?>
                                                                        
                                                                    <?php elseif(isset($annual_per_arp['yearly'][$this_arp_next][$year_ex[1]])): ?>
                                                                        
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        <?php endif; ?>   
                                                        <?php
                                                            $limit_counter++;
                                                        ?>                                                         
                                                    <?php elseif(isset($val['to'])): ?>
                                                        <?php if($val['to'] == $entry_year): ?>
                                                            <?php if($total_penalty[$this_arp] > 0): ?>
                                                                <?php echo e(number_format($total_penalty[$this_arp], 2)); ?><br>
                                                                <?php echo e(number_format($total_penalty[$this_arp], 2)); ?><br> 
                                                                <?php echo e(number_format($total_penalty[$this_arp_next] - $annual_per_arp['yearly'][$this_arp_next][$val['to']]['penalty'], 2)); ?><br>
                                                                <?php echo e(number_format($total_penalty[$this_arp_next] - $annual_per_arp['yearly'][$this_arp_next][$val['to']]['penalty'], 2)); ?><br>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$val['to']]['penalty'], 2)); ?><br>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$val['to']]['penalty'], 2)); ?><br> 
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            <?php endif; ?>
                                                            <?php if($total_discount[$this_arp] > 0): ?>
                                                                (<?php echo e(number_format($total_discount[$this_arp], 2)); ?>)<br>
                                                                (<?php echo e(number_format($total_discount[$this_arp], 2)); ?>)<br> 
                                                                (<?php echo e(number_format($total_discount[$this_arp_next] - $annual_per_arp['yearly'][$this_arp_next][$val['to']]['discount'], 2)); ?>)<br>
                                                                (<?php echo e(number_format($total_discount[$this_arp_next] - $annual_per_arp['yearly'][$this_arp_next][$val['to']]['discount'], 2)); ?>)<br>
                                                                (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$val['to']]['discount'], 2)); ?>)<br>
                                                                (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$val['to']]['discount'], 2)); ?>)<br> 
                                                                <?php
                                                                    $limit_counter++;
                                                                ?>
                                                            <?php endif; ?> 
                                                        <?php else: ?>     
                                                            <?php if($total_penalty[$this_arp] > 0): ?>
                                                                <?php echo e(number_format($total_penalty[$this_arp], 2)); ?><br>
                                                                <?php echo e(number_format($total_penalty[$this_arp], 2)); ?><br> 
                                                                <?php echo e(number_format($total_penalty[$this_arp_next], 2)); ?><br>
                                                                <?php echo e(number_format($total_penalty[$this_arp_next], 2)); ?><br> 
                                                            <?php endif; ?>
                                                            <?php if($total_discount[$this_arp] > 0): ?>
                                                                (<?php echo e(number_format($total_discount[$this_arp], 2)); ?>)<br>
                                                                (<?php echo e(number_format($total_discount[$this_arp], 2)); ?>)<br> 
                                                                (<?php echo e(number_format($total_discount[$this_arp_next], 2)); ?>)<br>
                                                                (<?php echo e(number_format($total_discount[$this_arp_next], 2)); ?>)<br>
                                                            <?php endif; ?>
                                                            <?php
                                                                $limit_counter++;
                                                            ?>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <?php if($year == $entry_year): ?>
                                                            <?php if($total_penalty[$this_arp] > 0): ?>
                                                                <?php echo e(number_format($total_penalty[$this_arp], 2)); ?><br>
                                                                <?php echo e(number_format($total_penalty[$this_arp], 2)); ?><br> 
                                                                <?php echo e(number_format($total_penalty[$this_arp_next] - $annual_per_arp['yearly'][$this_arp_next][$year]['penalty'], 2)); ?><br>
                                                                <?php echo e(number_format($total_penalty[$this_arp_next] - $annual_per_arp['yearly'][$this_arp_next][$year]['penalty'], 2)); ?><br>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year]['penalty'], 2)); ?><br>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year]['penalty'], 2)); ?><br>
                                                            <?php endif; ?>
                                                            <?php if($total_discount[$this_arp] > 0): ?>
                                                                (<?php echo e(number_format($total_discount[$this_arp], 2)); ?>)<br>
                                                                (<?php echo e(number_format($total_discount[$this_arp], 2)); ?>)<br> 
                                                                (<?php echo e(number_format($total_discount[$this_arp_next] - $annual_per_arp['yearly'][$this_arp_next][$year]['discount'], 2)); ?>)<br>
                                                                (<?php echo e(number_format($total_discount[$this_arp_next] - $annual_per_arp['yearly'][$this_arp_next][$year]['discount'], 2)); ?>)<br> 
                                                                (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year]['discount'], 2)); ?>)<br>
                                                                (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year]['discount'], 2)); ?>)<br> 
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <?php if($total_penalty[$this_arp] > 0): ?>
                                                                <?php echo e(number_format($total_penalty[$this_arp], 2)); ?><br>
                                                                <?php echo e(number_format($total_penalty[$this_arp], 2)); ?><br> 
                                                                <?php echo e(number_format($total_penalty[$this_arp_next], 2)); ?><br>
                                                                <?php echo e(number_format($total_penalty[$this_arp_next], 2)); ?><br> 
                                                            <?php endif; ?>
                                                            <?php if($total_discount[$this_arp] > 0): ?>
                                                                (<?php echo e(number_format($total_discount[$this_arp], 2)); ?>)<br>
                                                                (<?php echo e(number_format($total_discount[$this_arp], 2)); ?>)<br> 
                                                                (<?php echo e(number_format($total_discount[$this_arp_next], 2)); ?>)<br>
                                                                (<?php echo e(number_format($total_discount[$this_arp_next], 2)); ?>)<br> 
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                        <?php
                                                            $limit_counter++;
                                                        ?>
                                                    <?php endif; ?>
                                                <?php elseif($annual_per_arp[$this_arp]['prev_tax_dec_no'] != $this_arp_next && $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] != $this_arp): ?>
                                                    <?php if($annual_per_arp['yearly'][$arp][$year]['penalty'] > 0): ?>
                                                        <?php echo e(number_format($annual_per_arp['yearly'][$arp][$year]['penalty'], 2)); ?><br>
                                                        <?php echo e(number_format($annual_per_arp['yearly'][$arp][$year]['penalty'], 2)); ?><br>
                                                    <?php endif; ?>
                                                    <?php if($annual_per_arp['yearly'][$arp][$year]['discount'] > 0): ?>
                                                        (<?php echo e(number_format($annual_per_arp['yearly'][$arp][$year]['discount'], 2)); ?>)<br>
                                                        (<?php echo e(number_format($annual_per_arp['yearly'][$arp][$year]['discount'], 2)); ?>)<br>
                                                    <?php endif; ?>
                                                    <?php break; $limit_counter++; ?>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php foreach($data as $year => $val): ?>
                                                    <?php if(isset($val['to'])): ?>   
                                                        <?php if($val['to'] == $entry_year): ?> 
                                                            <?php if($annual_per_arp['yearly'][$this_arp][$val['to']]['penalty'] > 0): ?>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']]['penalty'], 2)); ?><br>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']]['penalty'], 2)); ?><br>
                                                                <?php echo e(number_format(($annual_arp[$this_arp][$year]['penalty']) - $annual_per_arp['yearly'][$this_arp][$year]['penalty'], 2)); ?><br>
                                                                <?php echo e(number_format(($annual_arp[$this_arp][$year]['penalty']) - $annual_per_arp['yearly'][$this_arp][$year]['penalty'], 2)); ?><br>
                                                            <?php endif; ?>
                                                            <?php if($annual_per_arp['yearly'][$this_arp][$val['to']]['discount'] > 0): ?>
                                                                (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']]['discount'], 2)); ?>)<br>
                                                                (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']]['discount'], 2)); ?>)<br>
                                                                (<?php echo e(number_format(($annual_arp[$this_arp][$year]['discount']) - $annual_per_arp['yearly'][$this_arp][$year]['discount'], 2)); ?>)<br>
                                                                (<?php echo e(number_format(($annual_arp[$this_arp][$year]['discount']) - $annual_per_arp['yearly'][$this_arp][$year]['discount'], 2)); ?>)<br>
                                                            <?php endif; ?>
                                                        <?php else: ?>                          
                                                            <?php if($annual_per_arp['yearly'][$this_arp][$year]['penalty'] > 0): ?>                    
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['penalty'], 2)); ?><br>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['penalty'], 2)); ?><br>
                                                            <?php endif; ?>
                                                            <?php if($annual_per_arp['yearly'][$this_arp][$year]['discount'] > 0): ?>
                                                                (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['discount'], 2)); ?>)<br>
                                                                (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['discount'], 2)); ?>)<br>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                        <?php
                                                            $limit_counter++;
                                                        ?>
                                                    <?php else: ?>
                                                        <?php if($annual_per_arp['yearly'][$this_arp][$year]['penalty'] > 0): ?>
                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['penalty'], 2)); ?><br>
                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['penalty'], 2)); ?><br>
                                                        <?php endif; ?>
                                                        <?php if($annual_per_arp['yearly'][$this_arp][$year]['discount'] > 0): ?>
                                                            (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['discount'], 2)); ?>)<br>
                                                            (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['discount'], 2)); ?>)<br>
                                                        <?php endif; ?>
                                                        <?php
                                                            $limit_counter++;
                                                        ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        <?php elseif($this_arp == $arp): ?>
                                            <?php foreach($data as $year => $val): ?>
                                                <?php
                                                    // if($advance == true && $year == $entry_year_adv) {
                                                    //     $next_pg = true;
                                                    //     continue;
                                                    // }
                                                ?>
                                                <?php if(isset($val['to'])): ?> 
                                                    <?php if($val['to'] == $entry_year): ?>
                                                        <!-- previous -->
                                                        <?php if($year <= $entry_year-1): ?>
                                                            <?php if($annual_arp[$this_arp][$year]['penalty'] > 0): ?>
                                                                <?php echo e(number_format($annual_arp[$this_arp][$year]['penalty'] - $annual_per_arp['yearly'][$this_arp][$val['to']]['penalty'], 2)); ?><br>
                                                                <?php echo e(number_format($annual_arp[$this_arp][$year]['penalty'] - $annual_per_arp['yearly'][$this_arp][$val['to']]['penalty'], 2)); ?><br>
                                                            <?php elseif($annual_arp[$this_arp][$year]['discount'] > 0): ?>
                                                                (<?php echo e(number_format($annual_arp[$this_arp][$year]['discount'] - $annual_per_arp['yearly'][$this_arp][$val['to']]['discount'], 2)); ?>)<br>
                                                                (<?php echo e(number_format($annual_arp[$this_arp][$year]['discount'] - $annual_per_arp['yearly'][$this_arp][$val['to']]['discount'], 2)); ?>)<br>
                                                            <?php else: ?>
                                                                0.00<br>
                                                                0.00<br>
                                                            <?php endif; ?>
                                                        <?php endif; ?>

                                                        <!-- current -->
                                                        <?php if($annual_per_arp['yearly'][$this_arp][$val['to']]['penalty'] > 0): ?>
                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']]['penalty'], 2)); ?><br>
                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']]['penalty'], 2)); ?><br>
                                                        <?php elseif($annual_per_arp['yearly'][$this_arp][$val['to']]['discount'] > 0): ?>
                                                            (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']]['discount'], 2)); ?>)<br>
                                                            (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']]['discount'], 2)); ?>)<br>
                                                        <?php else: ?>
                                                            0.00<br>
                                                            0.00<br>
                                                        <?php endif; ?>

                                                        <?php
                                                            $limit_counter++;
                                                        ?>
                                                    <?php else: ?>       
                                                        <?php if($val['to'] == $entry_year_adv): ?>                          
                                                            <?php if($annual_arp[$this_arp][$year]['penalty'] > 0 || $annual_per_arp['yearly'][$this_arp][$val['to']-1]['penalty'] > 0): ?>
                                                                <?php
                                                                    $compute1 = round(floatval($val['penalty']), 2) - round(floatval($annual_per_arp['yearly'][$this_arp][$val['to']-1]['penalty']), 2);
                                                                ?>
                                                                <-- previous -->
                                                                <?php if($year < $entry_year-1): ?>
                                                                <?php echo e(number_format($compute1, 2)); ?><br>
                                                                <?php echo e(number_format($compute1, 2)); ?><br>
                                                                <?php endif; ?>

                                                                <!-- current -->
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']-1]['penalty'], 2)); ?><br>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']-1]['penalty'], 2)); ?><br>
                                                            <?php elseif($annual_arp[$this_arp][$year]['discount'] > 0 || $annual_per_arp['yearly'][$this_arp][$val['to']-1]['discount'] > 0): ?>
                                                                <?php
                                                                    $compute1 = round(floatval($val['discount']), 2) - round(floatval($annual_per_arp['yearly'][$this_arp][$val['to']-1]['discount']), 2);
                                                                ?>
                                                                <!-- previous -->
                                                                <?php if($year < $entry_year-1): ?>
                                                                (<?php echo e(number_format($compute1, 2)); ?>)<br>
                                                                (<?php echo e(number_format($compute1, 2)); ?>)<br>
                                                                <?php endif; ?>

                                                                <!-- current -->
                                                                (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']-1]['discount'], 2)); ?>)<br>
                                                                (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']-1]['discount'], 2)); ?>)<br>
                                                            <?php else: ?>
                                                                <!-- previous -->
                                                                <?php if($year < $entry_year-1): ?>
                                                                0.00 asd<br>
                                                                0.00<br>
                                                                <?php endif; ?>

                                                                <!-- current -->
                                                                0.00 sss<br>
                                                                0.00<br>
                                                            <?php endif; ?>

                                                            <!-- advance -->
                                                            <?php if($annual_per_arp['yearly'][$this_arp][$val['to']]['penalty'] > 0): ?>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']]['penalty'], 2)); ?><br>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']]['penalty'], 2)); ?><br>
                                                            <?php elseif($annual_per_arp['yearly'][$this_arp][$val['to']]['discount'] > 0): ?>
                                                                (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']]['discount'], 2)); ?>)<br>
                                                                (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']]['discount'], 2)); ?>)<br>
                                                            <?php else: ?>
                                                                0.00<br>
                                                                0.00<br>
                                                            <?php endif; ?>
                                                        <?php else: ?>           
                                                            <?php if($annual_per_arp['yearly'][$this_arp][$year]['penalty'] > 0): ?>           
                                                                <?php echo e(number_format($annual_arp[$this_arp][$year]['penalty'], 2)); ?><br>
                                                                <?php echo e(number_format($annual_arp[$this_arp][$year]['penalty'], 2)); ?><br>
                                                            <?php elseif($annual_per_arp['yearly'][$this_arp][$year]['discount'] > 0): ?>
                                                                (<?php echo e(number_format($annual_arp[$this_arp][$year]['discount'], 2)); ?>)<br>
                                                                (<?php echo e(number_format($annual_arp[$this_arp][$year]['discount'], 2)); ?>)<br>
                                                            <?php else: ?>
                                                                0.00<br>
                                                                0.00<br>
                                                            <?php endif; ?>
                                                        <?php endif; ?>                                                   
                                                    <?php endif; ?>
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                <?php else: ?>
                                                    <?php if($annual_per_arp['yearly'][$this_arp][$year]['penalty'] > 0): ?>
                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['penalty'], 2)); ?><br>
                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['penalty'], 2)); ?><br>
                                                    <?php elseif($annual_per_arp['yearly'][$this_arp][$year]['discount'] > 0): ?>
                                                        (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['discount'], 2)); ?>)<br>
                                                        (<?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['discount'], 2)); ?>)<br>
                                                    <?php else: ?>
                                                        0.00<br>
                                                        0.00<br>
                                                    <?php endif; ?>
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </td>

                            <td class="border-hidden text-right vertical-top" style="width: 2.1cm; background: ##7fe83e; padding-left: 10px;">
                                <?php foreach($annual_arp as $this_arp => $data): ?>
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
                                    ?>

                                    <?php if(isset($annual_per_arp['yearly'][$this_arp][$year])): ?>
                                        <?php if($this_arp_next != false): ?>
                                            <?php if(isset($annual_per_arp[$this_arp_next])): ?>
                                                <?php if($annual_per_arp[$this_arp]['prev_tax_dec_no'] == $this_arp_next || $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] == $this_arp): ?>
                                                    <?php if($year_to > 0): ?>  
                                                        <?php
                                                            $yrs = array_keys($annual_per_arp['yearly'][$this_arp_next]);
                                                            $diff = intval($year_to) - intval($yrs[0]);
                                                        ?>
                                                        <?php if($year_to == $entry_year): ?>
                                                            <?php if(isset($annual_per_arp['yearly'][$this_arp][$year_to])): ?>
                                                                <?php if($total_penalty[$this_arp] > 0): ?>
                                                                    <?php
                                                                        $compute1 = round(floatval($annual_arp[$this_arp][$year]), 2) + (round(floatval($total_penalty[$this_arp]), 2) - round(floatval($annual_per_arp['yearly'][$this_arp][$year_to]['penalty']), 2));
                                                                        $compute2 = round(floatval($annual_per_arp['yearly'][$this_arp][$year_to]['sef']), 2) + round(floatval($annual_per_arp['yearly'][$this_arp][$year_to]['penalty']), 2);
                                                                        $limit_total += ($compute1*2) + ($compute2*2);
                                                                    ?>
                                                                    <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php echo e(number_format($compute2, 2)); ?><br>
                                                                    <?php echo e(number_format($compute2, 2)); ?><br>
                                                                <?php elseif($total_discount[$this_arp] > 0): ?>
                                                                    <?php
                                                                        $compute1 = round(floatval($annual_arp[$this_arp][$year]['assess_val']), 2) - (round(floatval($total_discount[$this_arp]), 2) - round(floatval($annual_per_arp['yearly'][$this_arp][$year_to]['discount']), 2));
                                                                        $compute2 = round(floatval($annual_per_arp['yearly'][$this_arp][$year_to]['sef']), 2) - round(floatval($annual_per_arp['yearly'][$this_arp][$year_to]['discount']), 2);
                                                                        $limit_total += ($compute1*2) + ($compute2*2);
                                                                    ?>
                                                                    <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php echo e(number_format($compute2, 2)); ?><br>
                                                                    <?php echo e(number_format($compute2, 2)); ?><br>
                                                                <?php else: ?>
                                                                    <?php
                                                                        $limit_total += (($annual_per_arp['yearly'][$this_arp][$year_to]['sef']*$diff)*2) + ($annual_per_arp['yearly'][$this_arp][$year_to]['sef']*2);
                                                                    ?>
                                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year_to]['sef']*$diff, 2)); ?><br>
                                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year_to]['sef']*$diff, 2)); ?><br>
                                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year_to]['sef'], 2)); ?><br>
                                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year_to]['sef'], 2)); ?><br>
                                                                <?php endif; ?>
                                                                <?php
                                                                    $limit_counter++;
                                                                    $next_pg = false;
                                                                ?>
                                                            <?php else: ?>
                                                                <?php if(isset($annual_arp[$this_arp][$year])): ?>       
                                                                    <?php if($annual_arp[$this_arp][$year]['penalty']): ?>
                                                                        <?php
                                                                            $compute = round(floatval($annual_arp[$this_arp][$year]['sef']), 2) + round(floatval($annual_arp[$this_arp][$year]['penalty']), 2);
                                                                            $limit_total += $compute*2;
                                                                        ?>
                                                                        <?php echo e(number_format($compute, 2)); ?><br>
                                                                        <?php echo e(number_format($compute, 2)); ?><br>
                                                                    <?php elseif($annual_arp[$this_arp][$year]['discount']): ?>
                                                                        <?php
                                                                            $compute = round(floatval($annual_arp[$this_arp][$year]['sef']), 2) - round(floatval($annual_arp[$this_arp][$year]['discount']), 2);
                                                                            $limit_total += $compute*2;
                                                                        ?>
                                                                        <?php echo e(number_format($compute, 2)); ?><br>
                                                                        <?php echo e(number_format($compute, 2)); ?><br>
                                                                    <?php else: ?>
                                                                        <?php
                                                                            $limit_total += ($annual_arp[$this_arp][$year]['sef'])*2;
                                                                        ?>
                                                                        <?php echo e(number_format($annual_arp[$this_arp][$year]['sef'], 2)); ?><br>
                                                                        <?php echo e(number_format($annual_arp[$this_arp][$year]['sef'], 2)); ?><br>
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <?php if($annual_per_arp['yearly'][$this_arp][$year]['penalty'] > 0): ?>
                                                                        <?php
                                                                            $compute1 = round(floatval($annual_per_arp['yearly'][$this_arp][$year]['sef']), 2) + round(floatval($annual_per_arp['yearly'][$this_arp][$year]['penalty']), 2);
                                                                            $limit_total += ($compute1*2);
                                                                        ?>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php elseif($annual_per_arp['yearly'][$this_arp][$year]['discount'] > 0): ?>
                                                                        <?php
                                                                            $compute1 = round(floatval($annual_per_arp['yearly'][$this_arp][$year]['sef']), 2) - round(floatval($annual_per_arp['yearly'][$this_arp][$year]['discount']), 2);
                                                                            $limit_total += ($compute1*2);
                                                                        ?>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                        <?php echo e(number_format($compute2, 2)); ?><br>
                                                                        <?php echo e(number_format($compute2, 2)); ?><br>
                                                                    <?php else: ?>
                                                                        <?php
                                                                            $limit_total += ($annual_per_arp['yearly'][$this_arp][$year]['sef']*2);
                                                                        ?>
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?><br>
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?><br>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                                <?php
                                                                    $limit_counter++;
                                                                    $next_pg = false;
                                                                ?>
                                                            <?php endif; ?>

                                                            <?php if(isset($annual_per_arp['yearly'][$this_arp_next][$year_to])): ?>     
                                                                <?php if($total_penalty[$this_arp_next] > 0): ?>
                                                                    <?php
                                                                        $compute1 = round(floatval($annual_per_arp['yearly'][$this_arp_next][$year_to]['sef'])*$diff, 2) + (round(floatval($total_penalty[$this_arp_next]), 2) - round(floatval($annual_per_arp['yearly'][$this_arp_next][$year_to]['penalty']), 2));
                                                                        $compute2 = round(floatval($annual_per_arp['yearly'][$this_arp_next][$year_to]['sef']), 2) + round(floatval($annual_per_arp['yearly'][$this_arp_next][$year_to]['penalty']), 2);
                                                                        $limit_total += ($compute1*2) + ($compute2*2);
                                                                    ?>
                                                                    <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php echo e(number_format($compute2, 2)); ?><br>
                                                                    <?php echo e(number_format($compute2, 2)); ?><br>
                                                                <?php elseif($total_discount[$this_arp_next] > 0): ?>
                                                                    <?php
                                                                        $compute1 = round(floatval($annual_per_arp['yearly'][$this_arp_next][$year_to]['sef']*$diff), 2) + (round(floatval($total_discount[$this_arp_next]), 2) - round(floatval($annual_per_arp['yearly'][$this_arp_next][$year_to]['discount']), 2));
                                                                        $compute2 = round(floatval($annual_per_arp['yearly'][$this_arp_next][$year_to]['sef']), 2) + round(floatval($annual_per_arp['yearly'][$this_arp_next][$year_to]['discount']), 2);
                                                                        $limit_total += ($compute1*2) + ($compute2*2);
                                                                    ?>
                                                                    <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php echo e(number_format($compute2, 2)); ?><br>
                                                                    <?php echo e(number_format($compute2, 2)); ?><br>
                                                                <?php else: ?>
                                                                    <?php
                                                                        $limit_total += (($annual_per_arp['yearly'][$this_arp_next][$year_to]['sef']*$diff)*2) + (($annual_per_arp['yearly'][$this_arp_next][$year_to]['sef'])*2);
                                                                    ?>
                                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year_to]['sef']*$diff, 2)); ?><br>
                                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year_to]['sef']*$diff, 2)); ?><br>
                                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year_to]['sef'], 2)); ?><br>
                                                                    <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year_to]['sef'], 2)); ?><br>
                                                                <?php endif; ?>  
                                                                <?php
                                                                    $limit_counter++;
                                                                    $next_pg = false;
                                                                ?>
                                                            <?php else: ?>
                                                                <?php if(isset($annual_arp[$this_arp_next][$year])): ?>
                                                                    <?php if($annual_arp[$this_arp_next][$year]['penalty']): ?>
                                                                        <?php
                                                                            $compute = round(floatval($annual_arp[$this_arp_next][$year]['assess_val']*.01), 2) + round(floatval($annual_arp[$this_arp_next][$year]['penalty']), 2);
                                                                            $limit_total += $compute*2;
                                                                        ?>
                                                                        <?php echo e(number_format($compute, 2)); ?><br>
                                                                        <?php echo e(number_format($compute, 2)); ?><br>
                                                                    <?php elseif($annual_arp[$this_arp_next][$year]['discount']): ?>
                                                                        <?php
                                                                            $compute = round(floatval($annual_arp[$this_arp_next][$year]['assess_val']*.01), 2) - round(floatval($annual_arp[$this_arp_next][$year]['discount']), 2);
                                                                            $limit_total += $compute*2;
                                                                        ?>
                                                                        <?php echo e(number_format($compute, 2)); ?><br>
                                                                        <?php echo e(number_format($compute, 2)); ?><br>
                                                                    <?php else: ?>
                                                                        <?php
                                                                            $limit_total += ($annual_arp[$this_arp_next][$year]['assess_val']*.01)*2;
                                                                        ?>
                                                                        <?php echo e(number_format($annual_arp[$this_arp_next][$year]['assess_val']*.01, 2)); ?><br>
                                                                        <?php echo e(number_format($annual_arp[$this_arp_next][$year]['assess_val']*.01, 2)); ?><br>
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <?php if($annual_per_arp['yearly'][$this_arp_next][$year]['penalty'] > 0): ?>
                                                                        <?php
                                                                            $compute1 = round(floatval($annual_per_arp['yearly'][$this_arp_next][$year]['sef']), 2) + round(floatval($annual_per_arp['yearly'][$this_arp_next][$year]['penalty']), 2);
                                                                            $limit_total += ($compute1*2);
                                                                        ?>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php elseif($annual_per_arp['yearly'][$this_arp_next][$year]['discount'] > 0): ?>
                                                                        <?php
                                                                            $compute1 = round(floatval($annual_per_arp['yearly'][$this_arp_next][$year]['sef']), 2) + round(floatval($annual_per_arp['yearly'][$this_arp_next][$year]['discount']), 2);
                                                                            $limit_total += ($compute1*2);
                                                                        ?>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php else: ?>
                                                                        <?php
                                                                            $limit_total += ($annual_per_arp['yearly'][$this_arp_next][$year]['sef'])*2;
                                                                        ?>
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year]['sef'], 2)); ?><br>
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year]['sef'], 2)); ?><br>
                                                                    <?php endif; ?>  
                                                                <?php endif; ?>    
                                                                <?php
                                                                    $limit_counter++;
                                                                    $next_pg = false;
                                                                ?>                                                    
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <?php if(strlen($year) == 4): ?>
                                                                <?php if(isset($annual_per_arp['yearly'][$this_arp][$year_to])): ?>
                                                                    <?php if($total_penalty[$this_arp] > 0): ?>
                                                                        <?php
                                                                            $compute1 = round(floatval($annual_per_arp['yearly'][$this_arp][$year]['sef']*$diff), 2) + round(floatval($total_penalty[$this_arp]), 2);
                                                                            $limit_total += $compute1*2;
                                                                        ?>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php elseif($total_discount[$this_arp] > 0): ?>
                                                                        <?php
                                                                            $compute1 = round(floatval($annual_per_arp['yearly'][$this_arp][$year]['sef']*$diff), 2) - round(floatval($total_discount[$this_arp]), 2);
                                                                            $limit_total += $compute1*2;
                                                                        ?>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php else: ?>
                                                                        <?php
                                                                            $limit_total += ($annual_per_arp['yearly'][$this_arp][$year]['sef']*$diff)*2;
                                                                        ?>
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef']*$diff, 2)); ?><br>
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef']*$diff, 2)); ?><br>
                                                                    <?php endif; ?>
                                                                <?php elseif(isset($annual_per_arp['yearly'][$this_arp_next][$year_to])): ?>
                                                                    <?php if($total_penalty[$this_arp_next] > 0): ?>
                                                                        <?php
                                                                            $diff = intval($year_to) - intval($yrs[$least_yr]);
                                                                            $compute1 = round($annual_per_arp['yearly'][$this_arp_next][$year_to]['sef']*$diff, 2) + round($total_penalty[$this_arp_next], 2);
                                                                            $limit_total += $compute1*2;
                                                                        ?>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php elseif($total_discount[$this_arp_next] > 0): ?>
                                                                        <?php
                                                                            $diff = intval($year_to) - intval($yrs[$least_yr]);
                                                                            $compute1 = round($annual_per_arp['yearly'][$this_arp_next][$year_to]['sef']*$diff, 2) - round($total_discount[$this_arp_next], 2);
                                                                            $limit_total += $compute1*2;
                                                                        ?>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php else: ?>
                                                                        <?php
                                                                            $limit_total += ($annual_per_arp['yearly'][$this_arp_next][$year_to]['sef']*$diff)*2;
                                                                        ?>
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year_to]['sef']*$diff, 2)); ?><br>
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year_to]['sef']*$diff, 2)); ?><br>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                                <?php
                                                                    $limit_counter++;
                                                                    $next_pg = false;
                                                                ?>
                                                            <?php else: ?>
                                                                <?php
                                                                    $year_ex = explode('-', $year);
                                                                ?>
                                                                <?php if($year_ex[1] == $entry_year): ?>
                                                                    <?php if(isset($annual_per_arp['yearly'][$this_arp][$year_ex[1]])): ?>
                                                                        <?php if($total_penalty[$this_arp] > 0): ?>
                                                                            <?php
                                                                                $diff = intval($year_ex[1]) - intval($year_ex[0]);
                                                                                $compute1 = round($annual_per_arp['yearly'][$this_arp][$year_ex[1]]['sef']*$diff, 2) + (round(floatval($total_penalty[$this_arp]), 2) - round(floatval($annual_per_arp['yearly'][$this_arp][$year_ex[1]]['penalty']), 2));
                                                                                $compute2 = round($annual_per_arp['yearly'][$this_arp][$year_ex[1]]['sef']*$diff, 2) + round(floatval($annual_per_arp['yearly'][$this_arp][$year_ex[1]]['penalty']), 2);
                                                                                $limit_total += ($compute1)*2 + ($compute2)*2;
                                                                            ?>
                                                                            <?php echo e(number_format($compute1, 2)); ?><br>
                                                                            <?php echo e(number_format($compute1, 2)); ?><br>
                                                                            <?php echo e(number_format($compute2, 2)); ?><br>
                                                                            <?php echo e(number_format($compute2, 2)); ?><br>
                                                                        <?php elseif($total_discount[$this_arp] > 0): ?>
                                                                            <?php
                                                                                $diff = intval($year_ex[1]) - intval($year_ex[0]);
                                                                                $compute1 = round($annual_per_arp['yearly'][$this_arp][$year_ex[1]]['sef']*$diff, 2) - (round(floatval($total_discount[$this_arp]), 2) - round(floatval($annual_per_arp['yearly'][$this_arp][$year_ex[1]]['discount']), 2));
                                                                                $compute2 = round($annual_per_arp['yearly'][$this_arp][$year_ex[1]]['sef']*$diff, 2) - round(floatval($annual_per_arp['yearly'][$this_arp][$year_ex[1]]['discount']), 2);
                                                                                $limit_total += ($compute1)*2 + ($compute2)*2;
                                                                            ?>
                                                                            <?php echo e(number_format($compute1, 2)); ?><br>
                                                                            <?php echo e(number_format($compute1, 2)); ?><br>
                                                                            <?php echo e(number_format($compute2, 2)); ?><br>
                                                                            <?php echo e(number_format($compute2, 2)); ?><br>
                                                                        <?php else: ?>
                                                                            <?php
                                                                                $limit_total += ($annual_per_arp['yearly'][$this_arp][$year_ex[1]]['sef']*$diff)*2;
                                                                            ?>
                                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year_ex[1]]['sef']*$diff, 2)); ?><br>
                                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year_ex[1]]['sef']*$diff, 2)); ?><br>
                                                                        <?php endif; ?>
                                                                    <?php elseif(isset($annual_per_arp['yearly'][$this_arp_next][$year_ex[1]])): ?>
                                                                        <?php if($total_penalty[$this_arp] > 0): ?>
                                                                            <?php
                                                                                $diff = intval($year_ex[1]) - intval($year_ex[0]);
                                                                                $compute1 = round($annual_per_arp['yearly'][$this_arp_next][$year_ex[1]]['sef']*$diff, 2) + (round(floatval($total_penalty[$this_arp_next]), 2) - round(floatval($annual_per_arp['yearly'][$this_arp_next][$year_ex[1]]['penalty']), 2));
                                                                                $compute2 = round($annual_per_arp['yearly'][$this_arp_next][$year_ex[1]]['sef']*$diff, 2) + round(floatval($annual_per_arp['yearly'][$this_arp_next][$year_ex[1]]['penalty']), 2);
                                                                                $limit_total += ($compute1)*2 + ($compute2)*2;
                                                                            ?>
                                                                            <?php echo e(number_format($compute1, 2)); ?><br>
                                                                            <?php echo e(number_format($compute1, 2)); ?><br>
                                                                            <?php echo e(number_format($compute2, 2)); ?><br>
                                                                            <?php echo e(number_format($compute2, 2)); ?><br>
                                                                        <?php elseif($total_discount[$this_arp_next] > 0): ?>
                                                                            <?php
                                                                                $diff = intval($year_ex[1]) - intval($year_ex[0]);
                                                                                $compute1 = round($annual_per_arp['yearly'][$this_arp_next][$year_ex[1]]['sef']*$diff, 2) - (round(floatval($total_discount[$this_arp_next]), 2) - round(floatval($annual_per_arp['yearly'][$this_arp_next][$year_ex[1]]['discount']), 2));
                                                                                $compute2 = round($annual_per_arp['yearly'][$this_arp_next][$year_ex[1]]['sef']*$diff, 2) - round(floatval($annual_per_arp['yearly'][$this_arp_next][$year_ex[1]]['discount']), 2);
                                                                                $limit_total += ($compute1*2) + ($compute2*2);
                                                                            ?>
                                                                            <?php echo e(number_format($compute1, 2)); ?><br>
                                                                            <?php echo e(number_format($compute1, 2)); ?><br>
                                                                            <?php echo e(number_format($compute2, 2)); ?><br>
                                                                            <?php echo e(number_format($compute2, 2)); ?><br>
                                                                        <?php else: ?>
                                                                            <?php
                                                                                $limit_total += ($annual_per_arp['yearly'][$this_arp_next][$year_ex[1]]['sef']*$diff)*2;
                                                                            ?>
                                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year_ex[1]]['sef']*$diff, 2)); ?><br>
                                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year_ex[1]]['sef']*$diff, 2)); ?><br>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>
                                                                    <?php
                                                                        $limit_counter++;
                                                                        $next_pg = false;
                                                                    ?>
                                                                <?php else: ?>
                                                                    <?php if(isset($annual_per_arp['yearly'][$this_arp][$year_ex[1]])): ?>
                                                                        <?php if($total_penalty[$this_arp] > 0): ?>
                                                                            <?php
                                                                                $compute1 = round($annual_per_arp['yearly'][$this_arp][$year_to]['sef']*$diff, 2) + (round(floatval($total_penalty[$this_arp]), 2) - round(floatval($annual_per_arp['yearly'][$this_arp][$year_to]['penalty']), 2));
                                                                                $limit_total += $compute1*2;
                                                                            ?>
                                                                            <?php echo e(number_format($compute1, 2)); ?><br>
                                                                            <?php echo e(number_format($compute1, 2)); ?><br>
                                                                        <?php elseif($total_discount[$this_arp] > 0): ?>
                                                                            <?php
                                                                                $compute1 = round($annual_per_arp['yearly'][$this_arp][$year_to]['sef']*$diff, 2) - (round(floatval($total_discount[$this_arp]), 2) - round(floatval($annual_per_arp['yearly'][$this_arp][$year_to]['discount']), 2));
                                                                                $limit_total += $compute1*2;
                                                                            ?>
                                                                            <?php echo e(number_format($compute1, 2)); ?><br>
                                                                            <?php echo e(number_format($compute1, 2)); ?><br>
                                                                        <?php else: ?>
                                                                        <?php
                                                                            $limit_total += ($annual_per_arp['yearly'][$this_arp][$year_to]['sef']*$diff)*2;
                                                                        ?>
                                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year_to]['sef']*$diff, 2)); ?><br>
                                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year_to]['sef']*$diff, 2)); ?><br>
                                                                        <?php endif; ?>
                                                                    <?php elseif(isset($annual_per_arp['yearly'][$this_arp_next][$year_ex[1]])): ?>
                                                                        <?php if($total_penalty[$this_arp_next] > 0): ?>
                                                                            <?php
                                                                                $compute1 = round($annual_per_arp['yearly'][$this_arp_next][$year_to]['sef']*$diff, 2) + (round(floatval($total_penalty[$this_arp_next]), 2) - round(floatval($annual_per_arp['yearly'][$this_arp_next][$year_to]['penalty']), 2));
                                                                                $limit_total += $compute1*2;
                                                                            ?>
                                                                            <?php echo e(number_format($compute1, 2)); ?><br>
                                                                            <?php echo e(number_format($compute1, 2)); ?><br>
                                                                        <?php elseif($total_discount[$this_arp_next] > 0): ?>
                                                                            <?php
                                                                                $compute1 = round($annual_per_arp['yearly'][$this_arp_next][$year_to]['sef']*$diff, 2) - (round(floatval($total_discount[$this_arp_next]), 2) - round(floatval($annual_per_arp['yearly'][$this_arp_next][$year_to]['discount']), 2));
                                                                                $limit_total += $compute1*2;
                                                                            ?>
                                                                            <?php echo e(number_format($compute1, 2)); ?><br>
                                                                            <?php echo e(number_format($compute1, 2)); ?><br>
                                                                        <?php else: ?>
                                                                        <?php
                                                                            $limit_total += ($annual_per_arp['yearly'][$this_arp_next][$year_to]['sef']*$diff)*2;
                                                                        ?>
                                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year_to]['sef']*$diff, 2)); ?><br>
                                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year_to]['sef']*$diff, 2)); ?><br>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>
                                                                    <?php
                                                                        $limit_counter++;
                                                                        $next_pg = false;
                                                                    ?>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        <?php endif; ?>   
                                                        <?php
                                                            // $limit_counter++;
                                                            // $next_pg = false;
                                                        ?>                                                         
                                                    <?php elseif(isset($val['to'])): ?>
                                                        <?php
                                                            $diff = intval($val['to']) - intval($year);
                                                        ?> 
                                                        <?php if($val['to'] == $entry_year): ?>
                                                            <?php if($total_penalty[$this_arp] > 0): ?>
                                                                <?php
                                                                    $compute1 = ($annual_per_arp['yearly'][$this_arp][$val['to']]['sef']*$diff) + floatval($total_penalty[$this_arp]);
                                                                    $compute2 = ($annual_per_arp['yearly'][$this_arp_next][$val['to']]['sef']*$diff) + (floatval($total_penalty[$this_arp_next]) - floatval($annual_per_arp['yearly'][$this_arp_next][$val['to']]['penalty']));
                                                                    $compute3 = floatval($annual_per_arp['yearly'][$this_arp_next][$val['to']]['sef']) + floatval($annual_per_arp['yearly'][$this_arp_next][$val['to']]['penalty']); 
                                                                    $limit_total += ($compute1 + $compute2 + $compute3)*2;
                                                                ?>
                                                                <?php echo e(number_format($compute1, 2)); ?><br>
                                                                <?php echo e(number_format($compute1, 2)); ?><br> 
                                                                <?php echo e(number_format($compute2, 2)); ?><br>
                                                                <?php echo e(number_format($compute2, 2)); ?><br>
                                                                <?php echo e(number_format($compute3, 2)); ?><br>
                                                                <?php echo e(number_format($compute3, 2)); ?><br> 
                                                            <?php elseif($total_discount[$this_arp] > 0): ?>
                                                                <?php
                                                                    $compute1 = ($annual_per_arp['yearly'][$this_arp][$val['to']]['sef']*$diff) - floatval($total_discount[$this_arp]);
                                                                    $compute2 = ($annual_per_arp['yearly'][$this_arp_next][$val['to']]['sef']*$diff) - (floatval($total_discount[$this_arp_next]) - floatval($annual_per_arp['yearly'][$this_arp_next][$val['to']]['discount']));
                                                                    $compute3 = floatval($annual_per_arp['yearly'][$this_arp_next][$val['to']]['sef']) - floatval($annual_per_arp['yearly'][$this_arp_next][$val['to']]['discount']); 
                                                                    $limit_total += ($compute1 + $compute2 + $compute3)*2;
                                                                ?>
                                                                <?php echo e(number_format($compute1, 2)); ?><br>
                                                                <?php echo e(number_format($compute1, 2)); ?><br> 
                                                                <?php echo e(number_format($compute2, 2)); ?><br>
                                                                <?php echo e(number_format($compute2, 2)); ?><br>
                                                                <?php echo e(number_format($compute3, 2)); ?><br>
                                                                <?php echo e(number_format($compute3, 2)); ?><br> 
                                                            <?php else: ?>
                                                            <?php
                                                                $limit_total += ($annual_per_arp['yearly'][$this_arp][$val['to']]['sef']*$diff)*2 + ($annual_per_arp['yearly'][$this_arp_next][$val['to']]['sef']*$diff)*2 + ($annual_per_arp['yearly'][$this_arp_next][$val['to']]['sef'])*2;
                                                            ?>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']]['sef']*$diff, 2)); ?><br>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']]['sef']*$diff, 2)); ?><br>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$val['to']]['sef']*$diff, 2)); ?><br>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$val['to']]['sef']*$diff, 2)); ?><br>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$val['to']]['sef'], 2)); ?><br>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$val['to']]['sef'], 2)); ?><br>
                                                            <?php endif; ?> 
                                                        <?php else: ?>     
                                                            <?php if($total_penalty[$this_arp] > 0): ?>
                                                                <?php
                                                                    $compute1 = ($annual_per_arp['yearly'][$this_arp][$year]['sef']*$diff) + floatval($total_penalty[$this_arp]);
                                                                    $compute2 = ($annual_per_arp['yearly'][$this_arp_next][$year]['sef']*$diff) + floatval($total_penalty[$this_arp_next]);
                                                                    $limit_total += ($compute1 + $compute2)*2;
                                                                ?>
                                                                <?php echo e(number_format($compute1, 2)); ?><br>
                                                                <?php echo e(number_format($compute1, 2)); ?><br> 
                                                                <?php echo e(number_format($compute2, 2)); ?><br>
                                                                <?php echo e(number_format($compute2, 2)); ?><br> 
                                                            <?php elseif($total_discount[$this_arp] > 0): ?>
                                                                <?php
                                                                    $compute1 = ($annual_per_arp['yearly'][$this_arp][$year]['sef']*$diff) - floatval($total_discount[$this_arp]);
                                                                    $compute2 = ($annual_per_arp['yearly'][$this_arp_next][$year]['sef']*$diff) - floatval($total_discount[$this_arp_next]);
                                                                ?>
                                                                <?php echo e(number_format($compute1, 2)); ?><br>
                                                                <?php echo e(number_format($compute1, 2)); ?><br> 
                                                                <?php echo e(number_format($compute2, 2)); ?><br>
                                                                <?php echo e(number_format($compute2, 2)); ?><br>
                                                            <?php else: ?>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef']*$diff, 2)); ?><br>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef']*$diff, 2)); ?><br>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year]['sef']*$diff, 2)); ?><br>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year]['sef']*$diff, 2)); ?><br>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                        <?php
                                                            $limit_counter++;
                                                            $next_pg = false;
                                                        ?>
                                                    <?php else: ?>
                                                        <?php if($year == $entry_year): ?>
                                                            <?php
                                                                $yrs = array_keys($annual_per_arp['yearly'][$this_arp_next]);
                                                                $diff = intval($year) - intval($yrs[0]);
                                                            ?>
                                                            <?php if($total_penalty[$this_arp] > 0): ?>
                                                                <?php                                                    
                                                                    $compute1 = ($annual_arp[$this_arp][$year]['assess_val']*.01) + floatval($total_penalty[$this_arp]);
                                                                    $compute2 = ($annual_per_arp['yearly'][$this_arp_next][$year]['sef']*$diff) + (floatval($total_penalty[$this_arp_next]) - floatval($annual_per_arp['yearly'][$this_arp_next][$year]['penalty']));
                                                                    $compute3 = ($annual_per_arp['yearly'][$this_arp_next][$year]['sef']) + floatval($annual_per_arp['yearly'][$this_arp_next][$year]['penalty']);
                                                                ?>
                                                                <?php echo e(number_format($compute1, 2)); ?><br>
                                                                <?php echo e(number_format($compute1, 2)); ?><br> 
                                                                <?php echo e(number_format($compute2, 2)); ?><br>
                                                                <?php echo e(number_format($compute2, 2)); ?><br>
                                                                <?php echo e(number_format($compute3, 2)); ?><br>
                                                                <?php echo e(number_format($compute3, 2)); ?><br> 
                                                            <?php elseif($total_discount[$this_arp] > 0): ?>
                                                                <?php
                                                                    $compute1 = ($annual_arp[$this_arp][$year]['assess_val']*.01) - floatval($total_discount[$this_arp]);
                                                                    $compute2 = ($annual_per_arp['yearly'][$this_arp_next][$year]['sef']*$diff) - (floatval($total_discount[$this_arp_next]) - floatval($annual_per_arp['yearly'][$this_arp_next][$year]['discount']));
                                                                    $compute3 = ($annual_per_arp['yearly'][$this_arp_next][$year]['sef']) - floatval($annual_per_arp['yearly'][$this_arp_next][$year]['discount']);
                                                                ?>
                                                                <?php echo e(number_format($compute1, 2)); ?><br>
                                                                <?php echo e(number_format($compute1, 2)); ?><br> 
                                                                <?php echo e(number_format($compute2, 2)); ?><br>
                                                                <?php echo e(number_format($compute2, 2)); ?><br>
                                                                <?php echo e(number_format($compute3, 2)); ?><br>
                                                                <?php echo e(number_format($compute3, 2)); ?><br> 
                                                            <?php else: ?>
                                                                <?php echo e(number_format($annual_arp[$this_arp][$year]['assess_val']*.01, 2)); ?><br>
                                                                <?php echo e(number_format($annual_arp[$this_arp][$year]['assess_val']*.01, 2)); ?><br>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year]['sef']*$diff, 2)); ?><br>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year]['sef']*$diff, 2)); ?><br>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year]['sef'], 2)); ?><br>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year]['sef'], 2)); ?><br>
                                                            <?php endif; ?>
                                                            <?php
                                                                $limit_counter++;
                                                                $next_pg = false;
                                                            ?>
                                                        <?php else: ?>
                                                            <?php if($total_penalty[$this_arp] > 0): ?>
                                                                <?php
                                                                    $yrs = array_keys($annual_per_arp['yearly'][$this_arp_next]);
                                                                    $diff = intval($year) - intval($yrs[0]);
                                                                    $compute1 = ($annual_per_arp['yearly'][$this_arp][$year]['sef']*$diff) + floatval($total_penalty[$this_arp]);
                                                                    $compute2 = ($annual_per_arp['yearly'][$this_arp_next][$year]['sef']*$diff) + floatval($total_penalty[$this_arp_next]);
                                                                ?>
                                                                <?php echo e(number_format($compute1, 2)); ?><br>
                                                                <?php echo e(number_format($compute1, 2)); ?><br> 
                                                                <?php echo e(number_format($compute2, 2)); ?><br>
                                                                <?php echo e(number_format($compute2, 2)); ?><br> 
                                                            <?php elseif($total_discount[$this_arp] > 0): ?>
                                                                <?php
                                                                    $yrs = array_keys($annual_per_arp['yearly'][$this_arp_next]);
                                                                    $diff = intval($year) - intval($yrs[0]);
                                                                    $compute1 = ($annual_per_arp['yearly'][$this_arp][$year]['sef']*$diff) - floatval($total_discount[$this_arp]);
                                                                    $compute2 = ($annual_per_arp['yearly'][$this_arp_next][$year]['sef']*$diff) - floatval($total_discount[$this_arp_next]);
                                                                ?>
                                                                <?php echo e(number_format($compute1, 2)); ?><br>
                                                                <?php echo e(number_format($compute1, 2)); ?><br> 
                                                                <?php echo e(number_format($compute2, 2)); ?><br>
                                                                <?php echo e(number_format($compute2, 2)); ?><br> 
                                                            <?php else: ?>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef']*$diff, 2)); ?><br>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef']*$diff, 2)); ?><br>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year]['sef']*$diff, 2)); ?><br>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp_next][$year]['sef']*$diff, 2)); ?><br>
                                                            <?php endif; ?>
                                                            <?php
                                                                $limit_counter++;
                                                                $next_pg = false;
                                                            ?>
                                                        <?php endif; ?>
                                                        <?php
                                                            // $limit_counter++;
                                                        ?>
                                                    <?php endif; ?>
                                                <?php elseif($annual_per_arp[$this_arp]['prev_tax_dec_no'] != $this_arp_next && $annual_per_arp[$this_arp_next]['prev_tax_dec_no'] != $this_arp && $arp == $this_arp): ?>
                                                    <?php if($annual_per_arp['yearly'][$arp][$year]['penalty'] > 0): ?>
                                                        <?php
                                                            $compute = floatval($annual_per_arp['yearly'][$arp][$year]['sef']) + floatval($annual_per_arp['yearly'][$arp][$year]['penalty']);
                                                            $limit_total += (round(floatval($annual_per_arp['yearly'][$arp][$year]['sef']), 2) + round(floatval($annual_per_arp['yearly'][$arp][$year]['penalty']), 2))*2;
                                                        ?>
                                                        <?php echo e(number_format($compute, 2)); ?><br>
                                                        <?php echo e(number_format($compute, 2)); ?><br>
                                                    <?php elseif($annual_per_arp['yearly'][$arp][$year]['discount'] > 0): ?>
                                                        <?php
                                                            $compute = floatval($annual_per_arp['yearly'][$arp][$year]['sef']) - floatval($annual_per_arp['yearly'][$arp][$year]['discount']);
                                                            $limit_total += (round(floatval($annual_per_arp['yearly'][$arp][$year]['sef']), 2) - round(floatval($annual_per_arp['yearly'][$arp][$year]['discount']), 2))*2;
                                                        ?>
                                                        <?php echo e(number_format($compute, 2)); ?><br>
                                                        <?php echo e(number_format($compute, 2)); ?><br>
                                                    <?php else: ?>
                                                        <?php echo e(number_format($annual_per_arp['yearly'][$arp][$year]['sef'], 2)); ?><br>
                                                        <?php echo e(number_format($annual_per_arp['yearly'][$arp][$year]['sef'], 2)); ?><br>
                                                    <?php endif; ?>
                                                    <?php 
                                                        if($this_arp_next == null)
                                                            break; 
                                                        $limit_counter++; 
                                                    ?>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php foreach($data as $year => $val): ?>
                                                    <?php if(isset($val['to'])): ?>   
                                                        <?php if($val['to'] == $entry_year): ?> 
                                                            <?php if($annual_per_arp['yearly'][$this_arp][$val['to']]['penalty'] > 0): ?>
                                                                <?php
                                                                    $compute1 = floatval($annual_per_arp['yearly'][$this_arp][$val['to']]['sef']) + floatval($annual_per_arp['yearly'][$this_arp][$val['to']]['penalty']);
                                                                    $compute2 = floatval($annual_arp[$this_arp][$year]['sef']) + floatval($annual_per_arp['yearly'][$this_arp][$year]['penalty']);
                                                                    $limit_total += (round(floatval($val['assess_val']*.01), 2) + round(floatval($val['penalty']), 2))*2;
                                                                ?>
                                                                <?php echo e(number_format($compute1, 2)); ?><br>
                                                                <?php echo e(number_format($compute1, 2)); ?><br>
                                                                <?php echo e(number_format($compute2, 2)); ?><br>
                                                                <?php echo e(number_format($compute2, 2)); ?><br>
                                                            <?php elseif($annual_per_arp['yearly'][$this_arp][$val['to']]['discount'] > 0): ?>
                                                                <?php
                                                                    $compute1 = floatval($annual_per_arp['yearly'][$this_arp][$val['to']]['sef']) - floatval($annual_per_arp['yearly'][$this_arp][$val['to']]['discount']);
                                                                    $compute2 = floatval($annual_arp[$this_arp][$year]['sef']) - floatval($annual_per_arp['yearly'][$this_arp][$year]['discount']);
                                                                ?>
                                                                <?php echo e(number_format($compute1, 2)); ?><br>
                                                                <?php echo e(number_format($compute1, 2)); ?><br>
                                                                <?php echo e(number_format($compute2, 2)); ?><br>
                                                                <?php echo e(number_format($compute2, 2)); ?><br>
                                                            <?php else: ?>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']]['sef'], 2)); ?><br>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']]['sef'], 2)); ?><br>
                                                                <?php echo e(number_format($annual_arp[$this_arp][$year]['sef'], 2)); ?><br>
                                                                <?php echo e(number_format($annual_arp[$this_arp][$year]['sef'], 2)); ?><br>
                                                            <?php endif; ?>
                                                        <?php else: ?>                       
                                                            <?php if($annual_per_arp['yearly'][$this_arp][$val['to']]['penalty'] > 0): ?>
                                                                <?php
                                                                    $compute2 = floatval($annual_arp[$this_arp][$year]['sef']) + floatval($annual_per_arp['yearly'][$this_arp][$year]['penalty']);
                                                                    $limit_total += (round(floatval($val['assess_val']*.01), 2) + round(floatval($val['penalty']), 2))*2;
                                                                ?>
                                                                <?php echo e(number_format($compute2, 2)); ?><br>
                                                                <?php echo e(number_format($compute2, 2)); ?><br>
                                                            <?php elseif($annual_per_arp['yearly'][$this_arp][$val['to']]['discount'] > 0): ?>
                                                                <?php
                                                                    $compute2 = floatval($annual_arp[$this_arp][$year]['sef']) - floatval($annual_per_arp['yearly'][$this_arp][$year]['discount']);
                                                                ?>
                                                                <?php echo e(number_format($compute2, 2)); ?><br>
                                                                <?php echo e(number_format($compute2, 2)); ?><br>
                                                            <?php else: ?>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']]['sef'], 2)); ?><br>
                                                                <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$val['to']]['sef'], 2)); ?><br>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                        <?php
                                                            $limit_counter++;
                                                        ?>
                                                    <?php else: ?>
                                                        <?php if($annual_per_arp['yearly'][$this_arp][$year]['penalty'] > 0): ?>
                                                            <?php
                                                                $compute = floatval($annual_per_arp['yearly'][$this_arp][$year]['sef']) + floatval($annual_per_arp['yearly'][$this_arp][$year]['penalty']);
                                                            ?>
                                                            <?php echo e(number_format($compute, 2)); ?><br>
                                                            <?php echo e(number_format($compute, 2)); ?><br>
                                                        <?php elseif($annual_per_arp['yearly'][$this_arp][$year]['discount'] > 0): ?>
                                                            <?php
                                                                $compute = floatval($annual_per_arp['yearly'][$this_arp][$year]['sef']) - floatval($annual_per_arp['yearly'][$this_arp][$year]['discount']);
                                                            ?>
                                                            <?php echo e(number_format($compute, 2)); ?><br>
                                                            <?php echo e(number_format($compute, 2)); ?><br>
                                                        <?php else: ?>
                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?><br>
                                                            <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?><br>
                                                        <?php endif; ?>
                                                        <?php
                                                            $limit_counter++;
                                                        ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        <?php elseif($this_arp == $arp): ?>
                                            <?php foreach($data as $year => $val): ?>
                                                <?php
                                                    // if($advance == true && $year == $entry_year_adv) {
                                                    //     $next_pg = true;
                                                    //     continue;
                                                    // }
                                                ?>
                                                <?php if(isset($val['to'])): ?>
                                                    <?php if($val['to'] == $entry_year): ?>                                                    
                                                        <?php
                                                            $diff = intval($val['to']) - intval($year);
                                                        ?>   
                                                        <!-- previous -->
                                                        <?php if($year <= $entry_year-1): ?>                                                      
                                                            <?php
                                                                $total_sef = 0;
                                                                $total_penalty = 0;
                                                                $total_discount = 0;
                                                                foreach ($annual_per_arp['yearly'][$this_arp] as $yearr => $data) {
                                                                    if($yearr < $val['to'] && $yearr >= $year) {
                                                                        $total_sef += $data['sef'];
                                                                        $total_penalty += $data['penalty'];
                                                                        $total_discount += $data['discount'];
                                                                    }
                                                                }
                                                            ?>
                                                            <?php if($annual_arp[$this_arp][$year]['penalty'] > 0): ?>
                                                                <?php
                                                                    // $compute1 = (floatval($val['assess_val']*.01) - floatval($annual_per_arp['yearly'][$this_arp][$val['to']]['sef'])) + (floatval($val['penalty']) - floatval($annual_per_arp['yearly'][$this_arp][$val['to']]['penalty']));

                                                                    // $compute1 = (floatval($val['sef']) - floatval($annual_per_arp['yearly'][$this_arp][$val['to']]['sef'])) + (floatval($val['penalty']) - floatval($annual_per_arp['yearly'][$this_arp][$val['to']]['penalty']));

                                                                    // if($val['to'] == $entry_year_adv)
                                                                    //     $diff = intval($val['to']-1) - intval($year);
                                                                    // else
                                                                    //     $diff = intval($val['to']) - intval($year);
                                                                    // $compute1 = ($annual_per_arp['yearly'][$this_arp][$year]['sef'] * $diff) + (floatval($val['penalty']) - floatval($annual_per_arp['yearly'][$this_arp][$val['to']]['penalty']));

                                                                    $compute1 = floatval($total_sef) + floatval($total_penalty);
                                                                    $limit_total += ($compute1*2);
                                                                ?>
                                                                <?php echo e(number_format($compute1, 2)); ?><br>
                                                                <?php echo e(number_format($compute1, 2)); ?><br>
                                                            <?php elseif($annual_arp[$this_arp][$year]['discount'] > 0): ?>
                                                                <?php
                                                                    if($val['to'] == $entry_year_adv)
                                                                        $diff = intval($val['to']-1) - intval($year);
                                                                    else
                                                                        $diff = intval($val['to']) - intval($year);
                                                                    // $compute2 = ($annual_per_arp['yearly'][$this_arp][$year]['sef']*$diff) - (floatval($annual_arp[$this_arp][$year]['discount']) - floatval($annual_per_arp['yearly'][$this_arp][$year]['discount']));
                                                                    // $compute1 = ($annual_per_arp['yearly'][$this_arp][$year]['sef'] * $diff) + (floatval($val['discount']) - floatval($annual_per_arp['yearly'][$this_arp][$val['to']]['discount']));

                                                                    $compute1 = floatval($total_sef) - floatval($total_discount);
                                                                    $limit_total += ($compute1*2);
                                                                ?>
                                                                <?php echo e(number_format($compute2, 2)); ?><br>
                                                                <?php echo e(number_format($compute2, 2)); ?><br>
                                                            <?php else: ?>
                                                                <?php
                                                                    // $compute2 = $annual_per_arp['yearly'][$this_arp][$year]['sef']*$diff;
                                                                    $compute2 = $total_sef;
                                                                    $limit_total += ($compute2*2);
                                                                ?>
                                                                <?php echo e(number_format($compute2, 2)); ?><br>
                                                                <?php echo e(number_format($compute2, 2)); ?><br>
                                                            <?php endif; ?>
                                                        <?php endif; ?>

                                                        <!-- current -->
                                                        <?php if($annual_per_arp['yearly'][$this_arp][$val['to']]['penalty'] > 0): ?>
                                                            <?php
                                                                $compute2 = floatval($annual_per_arp['yearly'][$this_arp][$val['to']]['sef']) + floatval($annual_per_arp['yearly'][$this_arp][$val['to']]['penalty']);
                                                                $limit_total += $compute2*2;
                                                            ?>
                                                            <?php echo e(number_format($compute2, 2)); ?><br>
                                                            <?php echo e(number_format($compute2, 2)); ?><br>
                                                        <?php elseif($annual_arp[$this_arp][$year]['discount'] > 0): ?>
                                                            <?php
                                                                $compute1 = floatval($annual_per_arp['yearly'][$this_arp][$val['to']]['sef']) - floatval($annual_per_arp['yearly'][$this_arp][$val['to']]['discount']);
                                                                $limit_total += ($compute1*2);
                                                            ?>
                                                            <?php echo e(number_format($compute1, 2)); ?><br>
                                                            <?php echo e(number_format($compute1, 2)); ?><br>
                                                        <?php else: ?>
                                                            <?php
                                                                $compute1 = $annual_per_arp['yearly'][$this_arp][$val['to']]['sef'];
                                                                $limit_total += ($compute1*2);
                                                            ?>
                                                            <?php echo e(number_format($compute1, 2)); ?><br>
                                                            <?php echo e(number_format($compute1, 2)); ?><br>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <?php
                                                            if($val['to'] == $entry_year_adv)
                                                                $diff = (intval($val['to']-2) - intval($year))+1;
                                                            else
                                                                $diff = (intval($val['to']) - intval($year))+1;

                                                            $total_sef = 0;
                                                            $total_discount = 0;
                                                            $total_penalty = 0;
                                                            $total_adv_sef = 0;
                                                            $total_adv_discount = 0;
                                                            $total_adv_penalty = 0;
                                                            foreach($annual_per_arp['yearly'] as $arpp => $data) {
                                                                foreach($data as $yearrr => $value) {
                                                                    if($yearrr >= $year && $yearrr < $entry_year && $yearrr < $entry_year_adv && $yearrr <= $val['to']) {
                                                                        $total_sef += $value['sef'];
                                                                        $total_discount += $value['discount'];
                                                                        $total_penalty += $value['penalty'];
                                                                    }

                                                                    if($yearrr >= $year && $yearrr >= $entry_year_adv && $yearrr <= $val['to']) {
                                                                        $total_adv_sef += $value['sef'];
                                                                        $total_adv_discount += $value['discount'];
                                                                        $total_adv_penalty += $value['penalty'];
                                                                    }
                                                                }
                                                            }
                                                        ?>    
                                                       
                                                        <?php if($val['to'] > $entry_year): ?> 
                                                            <?php if(isset($annual_per_arp['yearly'][$this_arp][$entry_year])): ?>
                                                                <?php if($annual_per_arp['yearly'][$this_arp][$year]['penalty'] > 0): ?>
                                                                    <?php
                                                                        $curr_yr = $entry_year;
                                                                        // $compute1 = round(floatval($annual_per_arp['yearly'][$this_arp][$year]['sef'])*$diff, 2) + round(floatval($prev_penalty), 2); 
                                                                        $compute1 = round(floatval($total_sef), 2) + round(floatval($total_penalty), 2); 
                                                                        $compute2 = round(floatval($annual_per_arp['yearly'][$this_arp][$curr_yr]['sef']), 2) + round(floatval($annual_per_arp['yearly'][$this_arp][$curr_yr]['penalty']), 2);

                                                                        $limit_total += ($compute1*2) + ($compute2*2);
                                                                    ?>
                                                                    <!-- previous -->
                                                                    <?php /* <?php if($val['to'] <= $entry_year-1): ?> */ ?>
                                                                    <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php /* <?php endif; ?> */ ?>

                                                                    <!-- current -->
                                                                    <?php echo e(number_format($compute2, 2)); ?><br>
                                                                    <?php echo e(number_format($compute2, 2)); ?><br>
                                                                <?php elseif($annual_per_arp['yearly'][$this_arp][$year]['discount'] > 0): ?>
                                                                    <?php
                                                                        $curr_yr = $entry_year;
                                                                        // $compute1 = round(floatval($annual_per_arp['yearly'][$this_arp][$year]['sef'])*$diff, 2) - round(floatval($annual_arp[$this_arp][$year]['discount']), 2); 

                                                                        // $compute1 = (round(floatval($total_sef), 2) - round(floatval($annual_per_arp['yearly'][$this_arp][$curr_yr]['sef']))) - (round(floatval($total_discount), 2) + round(floatval($annual_per_arp['yearly'][$this_arp][$curr_yr]['discount']))); 

                                                                        $compute1 = round(floatval($total_sef), 2) - round(floatval($total_discount), 2); 
                                                                        $compute2 = round(floatval($annual_per_arp['yearly'][$this_arp][$curr_yr]['sef']), 2) - round(floatval($annual_per_arp['yearly'][$this_arp][$curr_yr]['discount']), 2);
                                                                        if($val['to'] <= ($entry_year-1))
                                                                            $limit_total += ($compute1*2) + ($compute2*2);
                                                                        else
                                                                            $limit_total += ($compute2*2);
                                                                    ?>
                                                                    <!-- previous -->
                                                                    <?php if($val['to'] <= $entry_year-1): ?>
                                                                    <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php endif; ?>

                                                                    <!-- current -->
                                                                    <?php echo e(number_format($compute2, 2)); ?><br>
                                                                    <?php echo e(number_format($compute2, 2)); ?><br>
                                                                <?php else: ?>
                                                                    <?php
                                                                        $curr_yr = $entry_year;
                                                                        // $compute1 = round(floatval($annual_per_arp['yearly'][$this_arp][$year]['sef'])*$diff, 2); 
                                                                        $compute2 = round(floatval($annual_per_arp['yearly'][$this_arp][$curr_yr]['sef']), 2);
                                                                        $compute1 = round(floatval($total_sef), 2) - $compute2; 

                                                                        if($val['to'] <= $entry_year-1)
                                                                            $limit_total += ($compute1*2) + ($compute2*2);
                                                                        else
                                                                            $limit_total += ($compute2*2);
                                                                    ?>
                                                                    <!-- previous -->
                                                                    <?php if($val['to'] <= $entry_year-1): ?>
                                                                    <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php endif; ?>

                                                                    <!-- current -->
                                                                    <?php echo e(number_format($compute2, 2)); ?><br>
                                                                    <?php echo e(number_format($compute2, 2)); ?><br>
                                                                <?php endif; ?>
                                                            <?php endif; ?>

                                                            <!-- advance -->
                                                            <?php if($annual_per_arp['yearly'][$this_arp][$val['to']]['penalty'] > 0): ?>
                                                                <?php
                                                                    // $compute3 = round(floatval($annual_per_arp['yearly'][$this_arp][$val['to']]['sef']), 2) + round(floatval($annual_per_arp['yearly'][$this_arp][$val['to']]['penalty']), 2);
                                                                    $compute3 = round(floatval($total_adv_sef), 2) + round(floatval($total_adv_penalty), 2);
                                                                    $limit_total += number_format($compute3, 2)*2;
                                                                ?>
                                                                <?php echo e(number_format($compute3, 2)); ?><br>
                                                                <?php echo e(number_format($compute3, 2)); ?><br>
                                                            <?php elseif($annual_per_arp['yearly'][$this_arp][$val['to']]['discount'] > 0): ?>
                                                                <?php
                                                                    // $compute3 = round(floatval($annual_per_arp['yearly'][$this_arp][$val['to']]['sef']), 2) - round(floatval($annual_per_arp['yearly'][$this_arp][$val['to']]['discount']), 2);
                                                                    $compute3 = round(floatval($total_adv_sef), 2) - round(floatval($total_adv_discount), 2);
                                                                    $limit_total += number_format($compute3, 2)*2;
                                                                ?>
                                                                <?php echo e(number_format($compute3, 2)); ?><br>
                                                                <?php echo e(number_format($compute3, 2)); ?><br>
                                                            <?php else: ?>
                                                                <?php
                                                                    // $compute3 = round(floatval($annual_per_arp['yearly'][$this_arp][$val['to']]['sef']), 2);
                                                                    $compute3 = round(floatval($total_adv_sef), 2);
                                                                    $limit_total += number_format($compute3, 2)*2;
                                                                ?>
                                                                <?php echo e(number_format($compute3, 2)); ?><br>
                                                                <?php echo e(number_format($compute3, 2)); ?><br>
                                                            <?php endif; ?>
                                                        <?php else: ?>                                                          
                                                            <?php if(strlen($year) > 4): ?>
                                                                <?php if(isset($annual_arp[$this_arp][$year])): ?>
                                                                    <?php if($annual_arp[$this_arp][$year]['penalty'] > 0): ?>
                                                                        <?php
                                                                            $compute1 = round(floatval($annual_arp[$this_arp][$year]['sef']), 2) + round(floatval($annual_arp[$this_arp][$year]['penalty']), 2);
                                                                            $limit_total += $compute1*2;
                                                                        ?>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php elseif($annual_arp[$this_arp][$year]['discount'] > 0): ?>
                                                                        <?php
                                                                            $compute1 = round(floatval($annual_arp[$this_arp][$year]['sef']), 2) + round(floatval($annual_arp[$this_arp][$year]['discount']), 2); 
                                                                            $limit_total += $compute1*2;
                                                                        ?>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php else: ?>
                                                                        <?php
                                                                            // $limit_total += ($annual_arp[$this_arp][$year]['assess_val']*.01)*2;
                                                                            
                                                                            $limit_total += ($annual_arp[$this_arp][$year]['sef'])*2;
                                                                        ?>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <?php if($annual_arp[$this_arp][$year]['penalty'] > 0): ?>
                                                                        <?php
                                                                            $compute1 = round(floatval($annual_per_arp['yearly'][$this_arp][$year]['sef']), 2) + round(floatval($annual_arp[$this_arp][$year]['penalty']), 2); 
                                                                            $limit_total += $compute1*2;
                                                                        ?>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php elseif($annual_arp[$this_arp][$year]['discount'] > 0): ?>
                                                                        <?php
                                                                            $compute1 = round(floatval($annual_per_arp['yearly'][$this_arp][$year]['sef']), 2) + round(floatval($annual_arp[$this_arp][$year]['discount']), 2); 
                                                                            $limit_total += $compute1*2;
                                                                        ?>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                        <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php else: ?>
                                                                        <?php
                                                                            // $limit_total += ($annual_arp[$this_arp][$year]['assess_val']*.01)*2;
                                                                            $limit_total += ($annual_per_arp['yearly'][$this_arp][$year]['sef'])*2;
                                                                        ?>
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?><br>s
                                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?><br>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <?php if($annual_arp[$this_arp][$year]['penalty'] > 0): ?>
                                                                    <?php
                                                                        // $compute1 = round(floatval($annual_per_arp['yearly'][$this_arp][$year]['sef'])*$diff, 2) + round(floatval($annual_arp[$this_arp][$year]['penalty']), 2);
                                                                        $compute1 = round(floatval($total_sef), 2) + round(floatval($total_penalty), 2); 
                                                                        $limit_total += $compute1*2;
                                                                    ?>
                                                                    <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php echo e(number_format($compute1, 2)); ?><br>
                                                                <?php elseif($annual_arp[$this_arp][$year]['discount'] > 0): ?>
                                                                    <?php
                                                                        // $compute1 = round(floatval($annual_arp[$this_arp][$year]['assess_val'])*.01, 2) + round(floatval($annual_arp[$this_arp][$year]['discount']), 2);

                                                                        // $compute1 = round(floatval($annual_per_arp['yearly'][$this_arp][$year]['sef'])*$diff, 2) + round(floatval($annual_arp[$this_arp][$year]['discount']), 2);
                                                                        $compute1 = round(floatval($total_sef), 2) - round(floatval($total_discount), 2);
                                                                        $limit_total += $compute1*2;
                                                                    ?>
                                                                    <?php echo e(number_format($compute1, 2)); ?><br>
                                                                    <?php echo e(number_format($compute1, 2)); ?><br>
                                                                <?php else: ?>
                                                                    <?php
                                                                        // $limit_total += ($annual_arp[$this_arp][$year]['assess_val']*.01)*2;

                                                                        // $limit_total += ($annual_per_arp['yearly'][$this_arp][$year]['sef']*$diff)*2;
                                                                        $limit_total += ($total_sef)*2;
                                                                    ?>
                                                                    <?php /* number_format($annual_per_arp['yearly'][$this_arp][$year]['sef']*$diff, 2) */ ?><!-- <br> -->
                                                                    <?php /* number_format($annual_per_arp['yearly'][$this_arp][$year]['sef']*$diff, 2) */ ?><!-- <br> -->

                                                                    <?php echo e(number_format($total_sef, 2)); ?><br>
                                                                    <?php echo e(number_format($total_sef, 2)); ?><br>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                <?php else: ?>
                                                    <?php if($annual_per_arp['yearly'][$this_arp][$year]['penalty'] > 0): ?>
                                                        <?php
                                                            $compute = floatval($annual_per_arp['yearly'][$this_arp][$year]['sef']) + floatval($annual_per_arp['yearly'][$this_arp][$year]['penalty']);
                                                            $limit_total += $compute*2;
                                                        ?>
                                                        <?php echo e(number_format($compute, 2)); ?><br>
                                                        <?php echo e(number_format($compute, 2)); ?><br>
                                                    <?php elseif($annual_per_arp['yearly'][$this_arp][$year]['discount'] > 0): ?>
                                                        <?php
                                                            $compute = floatval($annual_per_arp['yearly'][$this_arp][$year]['sef']) - floatval($annual_per_arp['yearly'][$this_arp][$year]['discount']);
                                                            $limit_total += $compute*2;
                                                        ?>
                                                        <?php echo e(number_format($compute, 2)); ?><br>
                                                        <?php echo e(number_format($compute, 2)); ?><br>
                                                    <?php else: ?>
                                                        <?php
                                                            $limit_total += ($annual_per_arp['yearly'][$this_arp][$year]['sef'])*2;
                                                        ?>
                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?><br>
                                                        <?php echo e(number_format($annual_per_arp['yearly'][$this_arp][$year]['sef'], 2)); ?><br>
                                                    <?php endif; ?>
                                                    <?php
                                                        $limit_counter++;
                                                    ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </td>
                        <?php endif; ?>
                    </tr>

                    <tr style="background: ##d68db8;">
                        <td style="width: 2.5cm;">
                            <?php if($receipt->bank_number != null || $receipt->bank_number != ''): ?>
                            <?php echo e($receipt->bank_number); ?>

                            <?php endif; ?>
                        </td>
                        <td style="width: 2.5cm;">
                            <?php if($receipt->bank_name != null || $receipt->bank_name != ''): ?>
                            <?php echo e($receipt->bank_name); ?>

                            <?php endif; ?>
                        </td>
                        <td style="width: 2.5cm;">
                            <?php if($receipt->bank_date != null || $receipt->bank_date != ''): ?>
                            <?php echo e(\Carbon\Carbon::parse($receipt->bank_date)->format('M d,Y')); ?>

                            <?php endif; ?>
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
                                                <td style="padding-top: -10px; text-align: right; background: ##52aac7;"><?php echo e(number_format($form56['total'], 2)); ?></td>
                                            </tr>
                                            <tr>
                                                <td height="15px" class="text-hidden">CHECK</td>
                                                <td style="padding-top: -10px; padding-left: -50px; text-align: right;">
                                                    <?php if($receipt->bank_number != null || $receipt->bank_number != ''): ?>
                                                        <?php echo e($receipt->bank_number); ?><br>
                                                    <?php endif; ?>
                                                    <?php if($receipt->bank_name != null || $receipt->bank_name != ''): ?>
                                                        <?php echo e($receipt->bank_name); ?><br>
                                                    <?php endif; ?>
                                                    <?php if($receipt->bank_date != null || $receipt->bank_date != ''): ?>
                                                        <?php echo e(\Carbon\Carbon::parse($receipt->bank_date)->format('M d,Y')); ?><br>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td height="15px" class="text-hidden">TW/PMO</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td height="15px" class="text-hidden" >TOTAL</td>
                                                <td style="padding-top: -25px; text-align: right; background-color: ##7fe83e;"> <?php echo e(number_format($form56['total'], 2)); ?></td>
                                                <!-- padding-top: -15px; -->
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <!-- padding-top: -38px; -->
                        <td colspan="7" class="border-hidden text-right" style="background-color: ##7fe83e; padding-top: -55px;"><span class="text-hidden">TOTAL ></span> <?php echo e(number_format($limit_total, 2)); ?></td> 
                    </tr>
                    <tr>
                        <td colspan="3" class="border-hidden" style="padding-top: -15px; background-color: ##7fe83e;">
                            <div style="text-align: center; ">
                                <?php echo e($sign ? $acctble_officer_name->value : ''); ?>

                                <BR>
                                <?php echo e($sign ? $acctble_officer_position->value : ''); ?>

                            </div>
                        </td>
                        <td colspan="3" class="border-hidden" style="padding-top: -15px; background-color: ##7fe83e;">
                            <div style="text-align: center; ">
                                <!-- IMELDA I. MACANES -->
                                <?php echo e($sign ? 'IMELDA I. MACANES ' : ''); ?>

                                <BR>
                                <!-- PROVINCIAL TREASURER -->
                                <?php echo e($sign ? ucwords(strtolower('PROVINCIAL TREASURER ')) : ''); ?>

                            </div>
                        </td>
                    </tr>
                </table>
        <?php /* <?php endif; ?> */ ?>
        <?php endforeach; ?>

        <!-- PAGE BREAK -->

        <?php if(isset($next_pg)): ?>
            <?php if($next_pg == true): ?>
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
                <?php if($this_arp_next != null && $is_arp <= 0 && $this_arp_next != $arp): ?>
                    <div style="page-break-after: always;"></div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
        
    <?php endforeach; ?>
</body>
</html>