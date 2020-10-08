<!DOCTYPE html>
<html>
<head>
    <title>Collections And Deposits</title>
    <?php echo e(Html::style('/base/css/pdf.css')); ?>

    <style type="text/css">
        html {
            margin-bottom: 20px;
            margin-top: 20px;
            margin-left: 15px;
            margin-right: 15px;
        }
        body {
            font-family: 'Helvetica'
        }
        /* class works for table row */
        table tr.page-break{
          /*page-break-after:always*/
          page-break-inside: avoid;
        }


        /* class works for table */
        table.page-break{
          /*page-break-after:always*/
        }

        @media  print {
         /*.page-break  { display: block; page-break-before: always; }*/
        }
        @page  { 
            /*margin: 12px;*/
            margin-top: 72px;
            margin-bottom: 24px;
            margin-left: 24px;
            margin-right: 24px;
        }
        .center {
                width: 450px;
                text-align: center;
                margin: 12px auto;

        }

       .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
            border-top: 1px solid #868282;
            border-bottom: 1px solid #868282;
        }
       .table>thead:first-child>tr:first-child>th, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
            border: 1px solid #000;
        }
          .table-no-border>tbody>tr>td, .table-no-border>tbody>tr>th, .table-no-border>tfoot>tr>td, .table>tfoot>tr>th, .table-no-border>thead>tr>td, .table-no-border>thead>tr>th {
            border: none;
        }

        .table-borderedx{
                border: 1px solid #868282;
        }
         .table-border-right{
                border-right: 1px solid #868282;
         }

        .border-top{
                 border-top: 1px solid #000 !important;
        }

        .border-botts {
            border-bottom: 1px solid #000 !important;
        }

        .image_logo{
            width: 100px;
        }

        .val{
            font-weight: bold;
        }

        .height{
            height: 25px;
            vertical-align:middle;
        }


        .headerxxxx{
            /*width: 70%;*/
            margin: 0 auto;
        }

        h4{
            padding: 1px;
            margin: 1px;
        }


        div.container4 {
            align-items: center;
            /*justify-content: center;*/ 
            margin-bottom: 20px;
            margin-top: 20px;
        }

       #footer, .pagenum:before {
            content: counter(page);
              /*page-break-inside: auto;*/
          
        }

         #footer {
            bottom:15px;
            position: fixed;
            width: 100%;
            font-size: 12px;
            text-align: center;

            border-top:1px solid gray;
            /*page-break-inside: avoid;               background: #FFC0CB;*/
        }

        #collections {
            page-break-inside: auto;
        }

    </style>

            <?php if($total_columns >= 18): ?>
                <?php echo e(Html::style('/base/css/collections_deposti20.css')); ?>

            <?php elseif($total_columns >= 14): ?>
                <?php echo e(Html::style('/base/css/collections_deposti15.css')); ?>

            <?php elseif($total_columns >= 10): ?>
                <?php echo e(Html::style('/base/css/collections_deposti10.css')); ?>

            <?php elseif($total_columns >= 1): ?>
                <?php echo e(Html::style('/base/css/collections_deposti1.css')); ?>

            <?php endif; ?>
</head>

<body>
 
<div class=container4 >

      
 <!--<div id="footer">
         Page <span class="pagenum"></span> 
             <span style="float: right;">This is a computer-generated document</span> 
        </div> --> 
        
    <?php 
          $gtotal = 0;
    ?>

    <table class="center ">
    <tr>
        <td>
            <img src="<?php echo e(asset('asset/images/benguet_capitol.png')); ?>" class="image_logo" />
        </td>
        <td>
        REPORT OF COLLECTIONS AND DEPOSITS <br />
        <strong>PROVINCIAL GOVERNMENT OF BENGUET</strong><br />
        <strong>OFFICE OF THE PROVINCIAL TREASURER</strong>
        </td>
        </tr>
    </table>

    <table class="table table-condensed headerxxxx">
        <tr>
            <td>Fund: <b><?php echo e($fund->name); ?></b></td>
            <td>Date</td>
            <td class="underline"><?php echo e($report_date); ?></td>
        </tr>
        <tr>
            <td>Name of Accountable Officer: <b><?php echo e($acctble_officer_name->value); ?> - <?php echo e($acctble_officer_position->value); ?></b></td>
            <td class="val">Report No.</td>
            <td class="underline"><?php echo e($_GET['report_no']); ?></td>
        </tr>

    </table>

<h4>A. COLLECTIONS</h4>
<!-- <div class="table-responsive col-sm-6"> -->
    <table id="collections" class="table table-bordered table-condensed table-responsive page-break small-launay">
        <thead style="text-align: center;">
            <tr class="page-break">
                <th class="" rowspan="2">OR Nos.</th>
                <th class=" detail_payor" rowspan="2">Payor</th>
                <?php foreach($accounts as $i => $account): ?>
                    <?php
                        if($i == 'Benguet Technical School (BTS)')
                            continue;
                    ?>
                    <?php if($account['count'] > 0): ?>
                        <?php if($i == 'General Fund-Proper'): ?>
                            <?php
                                $add = count($accounts['General Fund-Proper']['titles']) + count($accounts['Benguet Technical School (BTS)']['titles']) + count($accounts['General Fund-Proper']['subtitles']) + count($accounts['Benguet Technical School (BTS)']['subtitles']);
                            ?>
                            <th class="" colspan="<?php echo e($add); ?>"><?php echo e($i); ?></th>
                        <?php else: ?>
                            <th class="" colspan="<?php echo e(count($account['titles']) + count($account['subtitles'])); ?>"><?php echo e($i); ?></th>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php if(count($shares) > 0): ?>
                <th class="" colspan="<?php echo e($share_columns); ?>">MUNICIPAL & BRGY SHARES</th>
                <?php endif; ?>

                <th class="" rowspan="2">TOTAL AMOUNT</th>
            </tr>
      
            <tr class="page-break">
                <?php $count_accts = 0; ?>
                <?php foreach($accounts as $i => $account): ?>
                    <?php if($account['count'] > 0): ?>
                        <?php $count_accts += $account['count']; ?>
                        <?php foreach($account['titles'] as $j => $title): ?>
                            <?php 
                                $acronym = $j ;
                            ?>
                            <?php if($count_accts > 15 && strlen($acronym) > 20): ?>
                                <?php if($i == 'Benguet Technical School (BTS)'): ?>
                                    <th style="font-size: 9px;"><?php echo e($acronym); ?> (BTS)</th>
                                <?php else: ?>
                                    <th style="font-size: 9px;"><?php echo e($acronym); ?></th>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php if($i == 'Benguet Technical School (BTS)'): ?>
                                    <th><?php echo e($acronym); ?> (BTS)</th>
                                <?php else: ?>
                                    <th><?php echo e($acronym); ?></th>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php foreach($account['subtitles'] as $j => $subtitle): ?>
                            <?php 
                                $acronym = $j ;
                            ?>
                            <?php if($count_accts > 15 && strlen($acronym) > 20): ?>
                                <?php if($i == 'Benguet Technical School (BTS)'): ?>
                                    <th style="font-size: 9px;"><?php echo e($acronym); ?> (BTS)</th>
                                <?php else: ?>
                                    <th style="font-size: 9px;"><?php echo e($acronym); ?></th>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php if($i == 'Benguet Technical School (BTS)'): ?>
                                    <th><?php echo e($acronym); ?> (BTS)</th>
                                <?php else: ?>
                                    <th><?php echo e($acronym); ?></th>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php foreach($shares as $i => $share): ?>
                    <th><?php echo e(isset($share['name']) ? $share['name'] : ''); ?></th>
    <!-- adjust share -->

                    <?php foreach($share['barangays'] as $j => $barangay): ?>
                        <th><?php echo e(isset($barangay['name']) ? $barangay['name'] : ''); ?></th>
                    <?php endforeach; ?>
    <!-- adjust share END -->
                <?php endforeach; ?>
            </tr>

            <tr class="border-botts page-break-before: always;">
                <th class="border-botts" colspan="<?php echo e($total_columns + 1); ?>"><?php echo e($date_range); ?></th>
            </tr>
        </thead>
            <!-- VALUES PER RECEIPT -->
        <tbody style="page-break-inside: avoid;">
                <?php $total_rc = []; $account_totals = []; ?>
                <?php foreach($receipts as $i => $receipt): ?>
                    <?php
                        if(!isset($total_rc[$receipt->serial_no])){
                             $total_rc[$receipt->serial_no] = 0;
                        }

                    ?>

                <tr class="page-break" style="min-height: 50px !important;">
                    <td style="vertical-align:middle" class="height val"><?php echo e($receipt->serial_no); ?></td>

                    <?php if(!isset($receipts_total[$receipt->serial_no]) || $receipt->is_cancelled == 1): ?>
                        <td style="vertical-align:middle; font-size: 12px;" class=" cancelled_remark" colspan="<?php echo e($total_columns); ?>">
                            Cancelled - <?php echo e($receipt->cancelled_remark); ?>

                        </td>
                    <?php else: ?>
                        <td style="vertical-align: middle; font-size: 12px;" class="detail_payor val">
                            <?php if($count_accts > 15): ?>
                                <?php
                                    $brk_word  = [];
                                    // if(strlen($receipt->customer->name) <= 40) {
                                        $brk_word = explode(" ", $receipt->customer->name);
                                    // }
                                ?>
                                    <?php foreach($brk_word as $word): ?>
                                        <?php echo e($word); ?> <br>
                                    <?php endforeach; ?>
                            <?php else: ?>
                                <?php echo e($receipt->customer->name); ?>

                            <?php endif; ?>
                        </td>

                        <?php foreach($accounts as $i => $account): ?>
                            <!-- per client type -->
                            <?php foreach($account['titles'] as $ji => $title): ?>
                                <?php
                                    if(!isset($account_totals[$ji])) {
                                        $account_totals[$ji] = 0;
                                    }
                                ?>
                                <td style="vertical-align: middle;" class=" val text-right">
                                    <?php if(isset($title[$receipt->serial_no])): ?>
                                        <?php if(strlen(number_format($title[$receipt->serial_no], 2)) >= 8): ?>
                                            <p style="font-size: 10px;"><?php echo e(number_format($title[$receipt->serial_no], 2)); ?></p>
                                        <?php else: ?>
                                            <p style="font-size: 12px;"><?php echo e(number_format($title[$receipt->serial_no], 2)); ?></p>
                                        <?php endif; ?>
                                        <?php 
                                            $total_rc[$receipt->serial_no] += $title[$receipt->serial_no];
                                            $account_totals[$ji] =+ $title[$receipt->serial_no];
                                        ?>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>

                            <?php foreach($account['subtitles'] as $j => $subtitle): ?>
                                <td style="vertical-align: middle;" class="val text-right">
                                    <?php if(isset($subtitle[$receipt->serial_no])): ?>
                                        <?php if(strlen(number_format($subtitle[$receipt->serial_no], 2)) >= 8): ?>
                                            <p style="font-size: 10px;"><?php echo e(number_format($subtitle[$receipt->serial_no], 2)); ?></p>
                                        <?php else: ?>
                                            <p style="font-size: 12px;"><?php echo e(number_format($subtitle[$receipt->serial_no], 2)); ?></p>
                                        <?php endif; ?>
                                        <?php  $total_rc[$receipt->serial_no] += $subtitle[$receipt->serial_no]; ?>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        <?php endforeach; ?>

                        <?php foreach($shares as $i => $share): ?>
                            <td style="vertical-align:middle;" class="val text-right">
                                <?php if(isset($share[$receipt->serial_no]) && $share[$receipt->serial_no] > 0): ?>
                                    <?php if(strlen(number_format($share[$receipt->serial_no], 2)) >= 8): ?>
                                        <p style="font-size: 10px;"><?php echo e(number_format($share[$receipt->serial_no], 2)); ?></p>
                                    <?php else: ?>
                                        <p style="font-size: 12px;"><?php echo e(number_format($share[$receipt->serial_no], 2)); ?></p>
                                    <?php endif; ?>
                                    <?php  $total_rc[$receipt->serial_no] += $share[$receipt->serial_no]; ?>
                                <?php endif; ?>
                            </td>

                            <?php foreach($share['barangays'] as $j => $barangay): ?>
                                <td style="vertical-align: middle;" class="val text-right">
                                    <?php if(isset($barangay[$receipt->serial_no]) && $barangay[$receipt->serial_no] > 0): ?>
                                        <?php if(strlen(number_format($barangay[$receipt->serial_no], 2)) >= 8): ?>
                                            <p style="font-size: 10px;"><?php echo e(number_format($barangay[$receipt->serial_no], 2)); ?></p>
                                        <?php else: ?>
                                            <p style="font-size: 12px;"><?php echo e(number_format($barangay[$receipt->serial_no], 2)); ?></p>
                                        <?php endif; ?>
                                        <?php  $total_rc[$receipt->serial_no] += $barangay[$receipt->serial_no]; ?>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        <?php endforeach; ?>

                        <td style="vertical-align: middle;" class="border-botts val text-right">
                            <?php 
                                $gtotal += $receipts_total[$receipt->serial_no];
                            ?>
                            <?php if(strlen(number_format($total_rc[$receipt->serial_no], 2)) >= 8): ?>
                                <p style="font-size: 10px;"><?php echo e(number_format($total_rc[$receipt->serial_no], 2)); ?></p>
                            <?php else: ?>
                                <p style="font-size: 12px;"><?php echo e(number_format($total_rc[$receipt->serial_no], 2)); ?></p>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
                <!-- TOTALS -->
                <tr class="page-break" style="min-height: 50px !important;">
                    <td class="val" colspan="2">GRAND TOTAL</td>
                    <?php foreach($accounts as $i => $account): ?>
                        <?php foreach($account['titles'] as $j => $title): ?>
                            <td class="val text-right" style="font-size: 10px;">
                                <?php if(isset($title['total'])): ?>
                                    <?php echo e(number_format($title['total'], 2)); ?>

                                <?php elseif(isset($account_totals[$j])): ?>
                                    <?php echo e(number_format($account_totals[$j], 2)); ?>

                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>

                        <?php foreach($account['subtitles'] as $j => $subtitle): ?>
                            <td class="val text-right" style="font-size: 10px;">
                                <?php if(isset($subtitle['total'])): ?>
                                    <?php echo e(number_format($subtitle['total'], 2)); ?>

                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    <?php endforeach; ?>

                    <?php foreach($shares as $i => $share): ?>
    <?php
        if(!isset($share['total_share']))
            dd($share);
    ?>
                        <td class="val text-right" style="font-size: 10px;">
                            <?php echo e(number_format(isset($share['total_share']) ? $share['total_share'] : 0, 2)); ?>

                        </td>
                        <?php foreach($share['barangays'] as $j => $barangay): ?>
                        <td class="val text-right" style="font-size: 10px;">
                            <?php echo e(number_format(isset($barangay['total_share']) ? $barangay['total_share'] : 0, 2)); ?>

                        </td>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                    <td class="val text-right"><?php echo e(number_format($gtotal, 2)); ?></td>
                </tr>
        </tbody>
    </table>
<!-- </div> -->
<table>
<tbody>
    <tr >
        <td  style="border-left: none; border-right: none; border-bottom: none; width: 550px;">
             <table class="table table-condensed small-launay table-no-border">
                    <tr >
                        <td><b>SUMMARY OF COLLECTION</b></td>
                        <td >
                            <?php $total = 0; ?>
                        </td>
                    </tr>
                    <?php foreach($accounts as $i => $account): ?>
                        <?php foreach($account['titles'] as $j => $title): ?>
                        <tr >
                            <td><?php echo e($j); ?></td>
                            <td class="val text-right">
                                <?php if(isset($title['total'])): ?>
                                    <?php
                                        $total += $title['total'];
                                    ?>
                                    <?php echo e(number_format($title['total'], 2)); ?>

                                <?php elseif(isset($account_totals[$j])): ?>
                                    <?php
                                        $total += $account_totals[$j];
                                    ?>
                                    <?php echo e(number_format($account_totals[$j], 2)); ?>

                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                        <?php foreach($account['subtitles'] as $j => $subtitle): ?>
                        <tr >
                            <td><?php echo e($j); ?></td>
                            <td class="val text-right">
                                <?php if(isset($subtitle['total'])): ?>
                                     <?php
                                        $total += $subtitle['total'];
                                    ?>
                                   
                                    <?php echo e(number_format($subtitle['total'], 2)); ?>

                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>

                    <?php if($bac_type_1 > 0 && $_GET['type'] == 1): ?>
                    <tr >
                        <td>BAC Goods & Services</td>
                        <td class="val">
                             <?php
                                   $total += $bac_type_1;
                                ?>
                            
                            <?php echo e(number_format($bac_type_1, 2)); ?>

                        </td>
                    </tr>
                    <?php endif; ?>

                    <?php if($bac_type_2 > 0 && $_GET['type'] == 1): ?>
                    <tr >
                        <td>BAC INFRA</td>
                        <td class="val text-right">
                            <span class="hidden">
                            <?php echo e($total += $bac_type_2); ?>

                            </span>
                            <?php echo e(number_format($bac_type_2, 2)); ?>

                        </td>
                    </tr>
                    <?php endif; ?>

                    <?php if($bac_type_3 > 0 && $_GET['type'] == 1): ?>
                    <tr >
                        <td>BAC Drugs & Meds</td>
                        <td class="val text-right">
                            <span class="hidden">
                            <?php echo e($total += $bac_type_3); ?>

                            </span>
                            <?php echo e(number_format($bac_type_3, 2)); ?>

                        </td>
                    </tr>
                    <?php endif; ?>

                    <tr class="set-border-tb">
                        <td ><b>TOTAL</b></td>
                        <td class="val text-right">
                            <b><?php echo e(number_format($total, 2)); ?></b>
                        </td>
                    </tr>

                </table>

        </td>

        <td  style="border-left: none; border-right: none; border-bottom: none;  width: 550px; vertical-align: top;">
        <table class="table table-no-border table-condensed small-launay">
        <tr>
        <td class="table-border-right">
      
        </td>
        <td style="padding: 0;"  >
            <?php if($_GET['type'] == 1): ?>
            <table class="table">
                <tr>
                    <td><b>Municipal/Barangay Share</b></td>
                    <td>
                         <?php
                            $total = 0;
                        
                        ?>
                    </td>
                </tr>
                <?php foreach($shares as $i => $share): ?>
                    <tr >
                        <td><b><?php echo e(isset($share['name']) ? $share['name'] : ''); ?></b></td>
                        <td class="val">
                             <?php
                                    if (isset($amusement_shares[$i])){
                                        if(isset($share['total_share']) && isset($amusement_shares[$i]['total_share']))
                                            $share_value = floatval($share['total_share']) - floatval($amusement_shares[$i]['total_share']);
                                        else if(isset($amusement_shares[$i]['total_share']))
                                            $share_value = floatval($amusement_shares[$i]['total_share']);
                                        else 
                                            $share_value = floatval($share['total_share']);
                                    } else{
                                        $share_value = isset($share['total_share']) ? $share['total_share'] : 0;
                                    }
                                $total += $share_value;
                              ?>
                            <?php echo e(number_format($share_value, 2)); ?>

                        </td>
                    </tr>
                    <?php foreach($share['barangays'] as $j => $barangay): ?>
                        <tr >
                            <td><div class="brgy"><?php echo e(isset($barangay['name']) ? $barangay['name'] : ''); ?></div></td>
                            <td class="val text-right">
                                <?php $total += isset($barangay['total_share']) ? $barangay['total_share'] : 0; ?>
                                <?php echo e(number_format(isset($barangay['total_share']) ? $barangay['total_share'] : 0, 2)); ?>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <tr class="set-border-tb">
                    <td ><b>TOTAL</b></td>
                    <td class="val text-right">
                        <b><?php echo e(number_format($total, 2)); ?></b>
                    </td>
                </tr>
            </table>
            <?php endif; ?>


            <?php if($_GET['type'] == 1): ?>
            <table class="table">
                <tr>
                    <td><b>Amusement Share</b></td>
                    <td>
                       <?php 
                            $total = 0;
                        ?>
                    </td>
                </tr>
                <?php foreach($amusement_shares as $i => $share): ?>
                    <tr>
                        <td><b><?php echo e(isset($share['name']) ? $share['name'] : ''); ?></b></td>
                        <td class="val text-right">
                            <?php
                                $total += $share['total_share'];
                            ?>
                            <?php echo e(number_format(isset($share['total_share']) ? $share['total_share'] : 0, 2)); ?>

                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr class="set-border-tb">
                    <td class=""><b>TOTAL</b></td>
                    <td class="val text-right">
                        <b><?php echo e(number_format($total, 2)); ?></b>
                    </td>
                </tr>
            </table>
            <?php endif; ?>
    </td>
    </tr>
    </table>

    </td>
</tr>
</tbody>
</table>

 <?php 
    $summary_total = 0;
    $total_with_ada = 0;
    $has_ada = 0;
    $ada = 0;
        foreach ($trantypes as $i => $type){
                    if ($i == 4){
                        if ($type['total'] > 0){
                          $ada = $type['total'];
                          $has_ada = 1;
                        }
                        $total_with_ada += $type['total'];
                    }else{
                        $total_with_ada += $type['total'];
                        $summary_total += $type['total'];
                    }
                        
                    
        }
        
?>

<h4>B. REMITTANCES/DEPOSITS</h4>
    <table class="table table-condensed small-launay">
        <tr>
            <th class="">ACCOUNTABLE OFFICER/BANK</th>
            <th class="">REFERENCE</th>
            <th class="">TOTAL</th>
        </tr>
        <tr >
            <td class="">
                <?php if($_GET['type'] == 5): ?>
                    <?php if(isset($trust_fund_officer_name->value)): ?>
                        <?php echo e($trust_fund_officer_name->value); ?>

                    <?php else: ?>
                        <?php echo e($trust_fund_officer_name); ?>

                    <?php endif; ?>
                <?php elseif($_GET['type'] == 2): ?>
                    <?php if(isset($bts_officer_name->value)): ?>
                        <?php echo e($bts_officer_name->value); ?>

                    <?php else: ?>
                        <?php echo e($bts_officer_name); ?>

                    <?php endif; ?>
                <?php elseif($_GET['type'] == 3): ?>
                    <?php if(isset($bese_report_officer->value)): ?>
                        <?php echo e($bese_report_officer->value); ?>

                    <?php else: ?>
                        <?php echo e($bese_report_officer); ?>

                    <?php endif; ?>
                <?php else: ?>
                    <?php if(isset($officer_name->value)): ?>
                        <?php echo e($officer_name->value); ?>

                    <?php else: ?>
                        <?php echo e($officer_name); ?>

                    <?php endif; ?>
                <?php endif; ?>
            </td>
            <td class=""><?php echo e($_GET['report_no']); ?></td>
            <td class=" val">PHP <?php echo e(number_format($total_with_ada, 2)); ?></td>
        </tr>
    </table>

    <!-- ACCOUNTABILITY FOR ACCOUNTABLE FORMS -->.
    <h4>C. ACCOUNTABILITY FOR ACCOUNTABLE FORMS</h4>
    <table class="table table-bordered table-condensed page-break small-launay">
    <thead>
        <tr class="page-break">
            <th class="" rowspan="3">Name of Forms & No.</th>
            <th class="" colspan="3">Beginning Balance</th>
            <th class="" colspan="3">Receipt</th>
            <th class="" colspan="3">Issued</th>
            <th class="" colspan="3">Ending Balance</th>
        </tr>
        <tr class="page-break">
            <th class="" rowspan="2">Qty.</th>
            <th class="" colspan="2">Inclusive Serial Nos.</th>
            <th class="" rowspan="2">Qty.</th>
            <th class="" colspan="2">Inclusive Serial Nos.</th>
            <th class="" rowspan="2">Qty.</th>
            <th class="" colspan="2">Inclusive Serial Nos.</th>
            <th class="" rowspan="2">Qty.</th>
            <th class="" colspan="2">Inclusive Serial Nos.</th>
        </tr>
        <tr class="page-break">
            <th class="">From</th>
            <th class="">To</th>
            <th class="">From</th>
            <th class="">To</th>
            <th class="">From</th>
            <th class="">To</th>
            <th class="">From</th>
            <th class="">To</th>
        </tr>
        <tr class="page-break">
            <th class="" colspan="13">
                Accountable Form 51
                <?php
                    $beg_total = 0;
                    $rec_total = 0;
                    $iss_total = 0;
                    $end_total = 0;
                ?>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($rcpt_acct as $rcpt): ?>
        <?php 
            $beg_total += $rcpt['beg_qty']?$rcpt['beg_qty']:0;
            $rec_total += $rcpt['rec_qty']?$rcpt['rec_qty']:0;
            $iss_total += $rcpt['iss_qty']?$rcpt['iss_qty']:0;
            $end_total += $rcpt['end_qty']?$rcpt['end_qty']:0;

        ?>
        <tr class="page-break">
            <td class="">
            </td>
            <td class=" val"><?php echo e($rcpt['beg_qty']); ?></td>
            <td class=" val"><?php echo e($rcpt['beg_from']); ?></td>
            <td class=" val"><?php echo e($rcpt['beg_to']); ?></td>
            <td class=" val"><?php echo e($rcpt['rec_qty']); ?></td>
            <td class=" val"><?php echo e($rcpt['rec_from']); ?></td>
            <td class=" val"><?php echo e($rcpt['rec_to']); ?></td>
            <td class=" val"><?php echo e($rcpt['iss_qty']); ?></td>
            <td class=" val"><?php echo e($rcpt['iss_from']); ?></td>
            <td class=" val"><?php echo e($rcpt['iss_to']); ?></td>
            <td class=" val"><?php echo e($rcpt['end_qty'] > 0 ? $rcpt['end_qty'] : "-"); ?></td>
            <td class=" val"><?php echo e($rcpt['end_qty'] > 0 ? $rcpt['end_from'] : "-"); ?></td>
            <td class=" val"><?php echo e($rcpt['end_qty'] > 0 ? $rcpt['end_to'] : "-"); ?></td>
        </tr>
        <?php endforeach; ?>
        <tr class="page-break">
            <td class=" val"></td>
            <td class=" val"><b><?php echo e($beg_total); ?></b></td>
            <td class=" val"></td>
            <td class=" val"></td>
            <td class=" val"><b><?php echo e($rec_total); ?></b></td>
            <td class=" val"></td>
            <td class=" val"></td>
            <td class=" val"><b><?php echo e($iss_total); ?></b></td>
            <td class=" val"></td>
            <td class=" val"></td>
            <td class=" val"><b><?php echo e($end_total); ?></b></td>
            <td class=" val"></td>
            <td class=" val"></td>
        </tr>
        </tbody>

    </table>


    <!-- SUMMARY OF COLLECTIONS AND REMITTANCES/DEPOSITS -->
    <div style="page-break-inside: avoid;">
        <table class="table  table-no-border  small-launay" style="page-break-inside: avoid;">
        <tr>
            <td colspan="2">  <h4>D. SUMMARY OF COLLECTIONS AND REMITTANCES/DEPOSITS</h4></td>
        </tr>
            <tr>
            <td class="table-border-right">
            <table class="table table-condensed">
                <tr>
                    <td class="">Beginning Balance <?php echo e($report_start); ?></td>
                    <td class=" val"></td>
                    <td class=" val text-right">0.00</td>
                </tr>
                <tr>
                    <td class="">Add: Collections <?php echo e($date_range); ?></td>
                    <td class=" val"></td>
                    <td></td>
                </tr>
                <?php $bank_depo = 0; $bank_depo_name = ''; ?>
                <?php foreach($trantypes as $i => $type): ?>
                    <?php
                        if($i == 5) { // get value for Bank Deposit/Transfer
                            $bank_depo = $type['total'];
                            $bank_depo_name = $type['name'];
                        }
                    ?>
                    <tr>
                        <td class=" tdindent"><?php echo e($type['name']); ?></td>
                        <td class=" val text-right">
                            <?php echo e(number_format($type['total'], 2)); ?>

                        </td>
                        <td class=" val text-right">
                            <?php if($i == 5): ?>
                                <?php echo e(number_format($summary_total, 2)); ?>

                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                    <tr>
                        <td class=""><b>Total Collections</b></td>
                        <td></td>
                        <td class=" val text-right"><b><?php echo e(number_format($total_with_ada, 2)); ?></b></td>
                    </tr>
                <?php if($has_ada): ?>
                    <tr>
                        <td class=""><b>Less ADA</b></td>
                        <td></td>
                        <td class=" val text-right"><b><?php echo e(number_format($ada, 2)); ?></b></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td class=""><b>Less: <?php echo e($bank_depo_name); ?></b></td>
                    <td></td>
                    <td class=" val text-right"><b><?php echo e(number_format($bank_depo, 2)); ?></b></td>
                </tr>
                <tr>
                    <td class=""><b>Remittance/Deposit to Cashier/Treasurer</b></td>
                    <td></td>
                    <td class=" val text-right"><b><?php echo e(number_format($summary_total - $bank_depo, 2)); ?></b></td>
                </tr>
                <tr>
                    <td class=""><b>Balance</b></td>
                    <td class=" val text-right"><b></b></td>
                    <td class=" val text-right"><b>0.00</b></td>
                </tr>
            </table>
            </td>

            <td >
               <?php
                    $bank_total = 0 ;
                ?>
                </span>
                 <table class="table table-condensed table-bordered table-responsive page-break">
                 <thead>
                    <tr>
                        <th class="">Drawee Bank</th>
                        <th class="">Check No.</th>
                        <th class="">Payee</th>
                        <th class="">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    

                    <?php 
                        $compiled_rec = array();
                    
                        foreach($bank as $b){
                            if(!isset($compiled_rec[$b['check_no']])){
                                $compiled_rec[$b['check_no']]['amt'] = 0;
                             }
                            $compiled_rec[$b['check_no']]['amt'] += $b['amt'];
                            $compiled_rec[$b['check_no']]['bank'] = $b['bank'];
                        }
                     ?>
                    
                    <?php foreach($compiled_rec as $key => $b): ?>
                        <tr>
                            <td class=""><?php echo e($b['bank']); ?></td>
                            <td class=""><?php echo e($key); ?></td>
                            <td class="">Provincial Government of Benguet</td>
                            <td class="val text-right">
                                <?php
                                    $bank_total += $b['amt'];
                                ?>
                                <?php echo e(number_format($b['amt'], 2)); ?>

                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td class="" colspan="3">Total</td>
                            <td class="val text-right"><?php echo e(number_Format($bank_total, 2)); ?></td>
                        </tr>
                </tbody>
            </table>
            </td>
        </tr>
        </table>
    </div>
    <br>

    <!-- CERTIFICATION/VERIFICATION AND ACKNOWLEDGEMENT -->
    <table  class="table table-borderedx  small-launay" style="padding-top: -20px;">
        <tr>
        <td class="table-border-right">
        <table class="table table-no-border">
            <tr>
                <th  class="border-botts" >CERTIFICATION</th>
            </tr>
            <tr>
                <td class="">
                    <table class="table table-no-border">
                        <tr>
                            <td colspan="3">
                            I hereby certify that the foregoing report of collections
                            and accountability for Accountable Forms is true and correct.
                            </td>
                        </tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr >
                            <th class="border-botts"><?php echo e($acctble_officer_name->value); ?></th>
                            <th></th>
                            <th class="border-botts"><?php echo e(date('F d, Y')); ?></th>
                        </tr>
                        <tr>
                            <th colspan="2"><?php echo e($acctble_officer_position->value); ?></th>
                            <th>Date</th>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        </td>
        <td>
        <table class="table table-no-border">
            <tr>
                <th  class="border-botts">VERIFICATION AND ACKNOWLEDGEMENT</th>
            </tr>
            <tr>
                <td class="">
                    <table class="table table-no-border">
                        <tr>
                            <td colspan="3">
                            I hereby certify that the foregoing report of collections
                            has been verified and acknowledge receipt of
                            <b><u><?php echo e($total_in_words); ?></u> (PHP <?php echo e(number_format($summary_total - $bank_depo, 2)); ?>)</b>.
                            </td>
                        </tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr >
                            <th class="border-botts">
                                <?php if($_GET['type'] == 5): ?>
                                    <?php if(isset($trust_fund_officer_name->value)): ?>
                                        <?php echo e($trust_fund_officer_name->value); ?>

                                    <?php else: ?> 
                                        <?php echo e($trust_fund_officer_name); ?>

                                    <?php endif; ?>
                                <?php elseif($_GET['type'] == 2): ?>
                                    <?php if(isset($bts_report_officer->value)): ?>
                                        <?php echo e($bts_report_officer->value); ?>

                                    <?php else: ?> 
                                        <?php echo e($bts_report_officer); ?>

                                    <?php endif; ?>
                                <?php elseif($_GET['type'] == 3): ?>
                                    <?php if(isset($bese_report_officer->value)): ?>
                                        <?php echo e($bese_report_officer->value); ?>

                                    <?php else: ?> 
                                        <?php echo e($bese_report_officer); ?>

                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php if(isset($officer_name->value)): ?>
                                        <?php echo e($officer_name->value); ?>

                                    <?php else: ?> 
                                        <?php echo e($officer_name); ?>

                                    <?php endif; ?>
                                <?php endif; ?>
                            </th>
                            <th></th>
                            <th class="border-botts"><?php echo e($report_date); ?></th>
                        </tr>
                        <tr>
                            <th>
                                <?php if($_GET['type'] == 2): ?> 
                                    <?php if(isset($bts_report_officer_position->value)): ?>
                                        <?php echo e($bts_report_officer_position->value); ?>

                                    <?php else: ?> 
                                        <?php echo e($bts_report_officer_position); ?>

                                    <?php endif; ?>
                                <?php elseif($_GET['type'] == 3): ?>
                                    <?php if(isset($bese_report_officer_position->value)): ?>
                                        <?php echo e($bese_report_officer_position->value); ?>

                                    <?php else: ?> 
                                        <?php echo e($bese_report_officer_position); ?>

                                    <?php endif; ?>
                                <?php elseif($_GET['type'] == 5): ?>
                                    <?php if(isset($trustfund_officer_position->value)): ?>
                                        <?php echo e($trustfund_officer_position->value); ?>

                                    <?php else: ?> 
                                        <?php echo e($trustfund_officer_positione); ?>

                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php if(isset($officer_position->value)): ?>
                                        <?php echo e($officer_position->value); ?>

                                    <?php else: ?> 
                                        <?php echo e($officer_position); ?>

                                    <?php endif; ?>
                                <?php endif; ?>
                            </th>
                            <th></th>
                            <th>Date</th>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        </td>
        </tr>
        </table>
    </div>
</div>
</body>
</html>