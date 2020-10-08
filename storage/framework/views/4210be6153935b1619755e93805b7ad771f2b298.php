<!DOCTYPE html>
<html>
<head>
    <title>Collections And Deposits</title>
    <?php echo e(Html::style('/bootstrap-3.3.6/css/bootstrap.min.css')); ?>

    <style type="text/css">
        html {
            margin-bottom: 0.25in;
            margin-top: 0.5in;
            margin-left: 0.5in;
            margin-right: 0.5in;
        }

        /* class works for table row */
        table tr.page-break{
          /*page-break-after:always*/
        }


        /* class works for table */
        table.page-break{
          /*page-break-after:always*/
        }

        @media  print {
         /*.page-break  { display: block; page-break-before: always; }*/
        }
        .center {
                width: 450px;
                text-align: center;
                margin: 1px auto;
                margin-bottom: 2px;
        }

        .table{
            margin-bottom: 3px;
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

         /*.detail_payor{
            width: 190px;
         }*/

        .border-top{
                 border-top: 1px solid #000 !important;
        }

        .border-botts {
            border-bottom: 1px solid #000 !important;
        }

        .image_logo{
            width: 60px;
        }

        .val{
            font-weight: bold;
        }

        .pagenum:before {
            content: counter(page);
        }

        .footer {
            bottom:8px;
            position: fixed;
            width: 100%;
            color:#44474c;
            font-size: 9px;
            text-align: center;
        }

        .headerxxx > tbody > tr > td{
            padding: 1px;
            font-size: 11px;
        }

        h4{
            padding: 1px;
            margin: 0px;
            font-size: 13px;
        }

        #collections > thead > tr > th.accountsxx{
            text-align: center;
        }

         #collections > tbody > tr > td.accountsxx{
            text-align: right;
         }

        #collections > thead > tr > th.accountsxx , #collections > tbody > tr > td.accountsxx{
            width: 10px !important;
            max-width: 10px !important;
            word-break: break-all;
            word-wrap: break-word;

        }

        #collections > tbody > tr.extra_tr > td{
            line-height: 17.1429px;
            height: 13px;
            max-height: 13px;

        }

        .table-responsive{
            padding: 0;
            border: none;
        }

        .table-responsive>table{
            border: 1px solid #000;
            border-left: 2px solid #000;
        }

        .small-launay>thead>tr>th,.small-launay>tbody>tr>td{
            margin: 0;
            padding: 0px 1px 0px 1px;


        }

        .text-center{
            vertical-align: middle;
        }

        .v-align{
            vertical-align: middle !important;
        }

        #collectionsx {
            /*table-layout: fixed;
            word-wrap: normal;*/
            /*width: 100% !important;*/
        }

        #d1 > tbody > tr > td, 
        #d2 > tbody > tr > td, 
        #d2 > thead > tr > th,
        #b1 > tr > td,
        #b1 > tr > th {
            padding: 0;
            margin: 0;
        }

        <?php if($total_columns >= 18): ?>
            <?php echo e(Html::style('/base/css/collections_deposti20.css')); ?>

        <?php elseif($total_columns >= 14): ?>
            <?php echo e(Html::style('/base/css/collections_deposti15.css')); ?>

        <?php elseif($total_columns >= 10): ?>
            <?php echo e(Html::style('/base/css/collections_deposti10.css')); ?>

        <?php elseif($total_columns >= 1): ?>
            <?php echo e(Html::style('/base/css/collections_deposti1.css')); ?>

        <?php endif; ?>
    </style>

</head>
<body>

<div class="footer">
    Page <span class="pagenum"></span>
</div>

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

    <table class="table table-condensed headerxxx">
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

<?php 
if(isset($accounts['Benguet Technical School (BTS)'])) {
    $cols = count(($accounts['Benguet Technical School (BTS)']['titles'])) + count(($accounts['Benguet Technical School (BTS)']['subtitles']));
    $cols_w = 600 - ( $cols * 100);
    $max_str = 40;
    //if($cols > 4){
        //$max_str = 40 - ($cols*2 + 2);
    //}

    //$cols_w = 420;
    
    //if($cols <= 6){
        //$cols_w = 920 - ($cols * 120); // 890 // 110
    //}
    switch($cols) {
        case 1 : $cols_w =  700; break;
        case 2 : $cols_w =  635; break;
        case 3 : $cols_w =  535; break;
        case 4 : $cols_w =  435; break;
        case 5 : $cols_w =  335; break;
        case 6 : $cols_w =  265; break;
        case ($cols > 6) : $cols_w =  175; break;
        default : $cols_w =  175;
    }
} else if(isset($accounts['Trust Fund'])) {
    $cols = count(($accounts['Trust Fund']['titles'])) + count(($accounts['Trust Fund']['subtitles']));
    $cols_w = 600 - ( $cols * 100);
    $max_str = 40;
    if($cols > 4){
        $max_str = 40 - ($cols*2 + 2);
    }

    $cols_w = 420;
    
    if($cols < 6){
        $cols_w = 740 - ($cols * 100); 
    }
    if($cols < 4){
        if($papr_size != "") {
            $cols_w = 1100 - ($cols * 100);
        } else if(isset($papr_size_custom_h) && isset($papr_size_custom_w)) {
            $cols_w = 800 - ($cols * 100); 
        } else {
            $cols_w = 950 - ($cols * 100); 
        }
    }
}
 ?>
<h4>A. COLLECTIONS</h4>
<div class="bs-example col-md-12" data-example-id="bordered-table" style="padding-bottom: 5px; padding-left: 0; padding-right: 0; margin-left: 0; margin-right: 0;">
    <table id="collectionsx" class="table table-bordered table-condensed page-break small-launay" style="margin-left:auto; margin-right: auto;">
    <?php /*<?php if($cols < 6): ?>*/ ?>
        <!-- <table id="collectionsx" class="table table-bordered table-condensed page-break small-launay" style="margin-left:auto; margin-right: auto; width: 120vw !important;"> -->
    <?php /*<?php else: ?>*/ ?>
        <!-- <table id="collectionsx" class="table table-bordered table-condensed page-break small-launay" style="margin-left:auto; margin-right: auto;"> -->
    <?php /*<?php endif; ?>*/ ?>
    <thead>
        <tr class="page-break">
            <th class="text-center v-align" rowspan="1" style="width: 10%; max-width: 100px;">OR Nos.</th>
            <th class="detail_payor text-center" rowspan="1" style="width: <?php echo e($cols_w); ?>px;vertical-align: middle;">Payor</th>
            <?php foreach($accounts as $i => $account): ?>
                <?php if(count($account['titles']) > 0): ?>
                    <?php foreach($account['titles'] as $j => $title): ?>
                    <?php
                        if($title['abbrv'] == null)
                            $acronym = $j;
                        else
                            $acronym = $title['abbrv'];
                        
                        $fsize = '14px';
                        if($cols < 7){
                             if(strlen($acronym) > 20){
                                $fsize = '10px';
                            }
                        }else{
                            if(strlen($acronym) > 20){
                                $fsize = '10px';
                            }
                        }

                    ?>
                    <th class="accountsxx text-center v-align" style="width: <?php echo e(strlen($acronym)+65); ?>px; max-width: 100px; font-size: <?php echo e($fsize); ?>; padding: 0 0 0 0;" ><?php echo $acronym; ?></th>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php if(count($account['subtitles']) > 0): ?>
                    <?php foreach($account['subtitles'] as $k => $subtitle): ?>
                        <?php
                            if($subtitle['abbrv'] == null)
                                $acronym = $k;
                            else
                                $acronym = $subtitle['abbrv'];

                            $fsize = '14px';
                            if($cols < 7){
                                 if(strlen($acronym) > 20){
                                    $fsize = '10px';
                                }
                            }else{
                                if(strlen($acronym) > 20){
                                    $fsize = '10px';
                                }
                            }
                        ?>
                        <th class="accountsxx text-center v-align" style="width: <?php echo e(strlen($acronym)+65); ?>px; max-width: 100px; font-size: <?php echo e($fsize); ?>; padding: 0 0 0 0;" ><?php echo $acronym; ?></th>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php foreach($shares as $i => $share): ?>
                <th class="accountsxx text-center v-align"><?php echo e($share['name']); ?></th>
                <?php foreach($share['barangays'] as $j => $barangay): ?>
                <th class="accountsxx text-center v-align"><?php echo e($barangay['name']); ?></th>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <th class="accountsxx text-center v-align" rowspan="1" style="width: 100px;">TOTAL AMOUNT</th>
        </tr>
        <tr class="border-botts page-break">
            <th class="border-botts  v-align" colspan="<?php echo e($total_columns + 1); ?>"><?php echo e($date_range); ?></th>
        </tr>
</thead>
        <!-- VALUES PER RECEIPT -->
<tbody>
    <?php  $count_rw = 1; ?>
       <?php $total_rc = []; ?>
        <?php foreach($receipts as $i => $receipt): ?>
            <?php
                    if(!isset($total_rc[$receipt->serial_no])){
                         $total_rc[$receipt->serial_no] = 0;
                    }
            ?>

        <tr class="page-break mgpd">
            <td class="  text-center  v-align"><?php echo e($receipt->serial_no); ?> </td>
            <?php if(!isset($receipts_total[$receipt->serial_no])): ?>
                <td class=" cancelled_remark  v-align" colspan="<?php echo e($total_columns); ?>">
                    Cancelled - <?php echo e($receipt->cancelled_remark); ?>

                </td>
            <?php else: ?>

            <?php 
                $ffsize =  '12px';
                if(strlen($receipt->customer->name) > $max_str){
                    $ffsize =  '9px';
                    //$cols_w = 900 - ($cols * 100);
                    //$cols_w = 500 - ($cols * 100);
                }
             ?>
                <td class="detail_payor v-align" style="font-size: <?php echo e($ffsize); ?>;"><?php echo e($receipt->customer->name); ?></td>
                <?php foreach($accounts as $i => $account): ?>
                    <?php foreach($account['titles'] as $ji => $title): ?>
                        <td class=" text-right accountsxx  v-align">
                            <?php if(isset($title[$receipt->serial_no])): ?>
                            <?php echo e(number_format($title[$receipt->serial_no], 2)); ?>

                            <?php  $total_rc[$receipt->serial_no] += $title[$receipt->serial_no]; ?>

                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>

                    <?php foreach($account['subtitles'] as $j => $subtitle): ?>
                        <td class=" text-right  v-align">
                            <?php if(isset($subtitle[$receipt->serial_no])): ?>
                            <?php echo e(number_format($subtitle[$receipt->serial_no], 2)); ?>

                            <?php  $total_rc[$receipt->serial_no] += $subtitle[$receipt->serial_no]; ?>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                <?php endforeach; ?>

                <?php foreach($shares as $i => $share): ?>
                    <td class=" text-right  v-align">
                        <?php if(isset($share[$receipt->serial_no]) && $share[$receipt->serial_no] > 0): ?>
                        <?php echo e(number_format($share[$receipt->serial_no], 2)); ?>

                        <?php  $total_rc[$receipt->serial_no] += $share[$receipt->serial_no]; ?>
                        <?php endif; ?>
                    </td>
                    <?php foreach($share['barangays'] as $j => $barangay): ?>
                    <td class=" text-right  v-align">
                        <?php if(isset($barangay[$receipt->serial_no]) && $barangay[$receipt->serial_no] > 0): ?>
                        <?php echo e(number_format($barangay[$receipt->serial_no], 2)); ?>

                        <?php  $total_rc[$receipt->serial_no] += $barangay[$receipt->serial_no]; ?>
                        <?php endif; ?>
                    </td>
                    <?php endforeach; ?>
                <?php endforeach; ?>

                <td class=" border-botts text-right accountsxx  v-align">
                    <?php
                        $gtotal += $receipts_total[$receipt->serial_no];
                    ?>
                    <?php echo e(number_format($total_rc[$receipt->serial_no], 2)); ?>

                </td>
            <?php endif; ?>
        </tr>
         <?php $count_rw++; ?>
        <?php endforeach; ?>
        <?php
            // $rows = 27;
            $rows = 24;
            if($_GET['type'] == 5){
                $rows = 23;
            }
        ?>
        <?php if( $count_rw < $rows): ?>
        <?php
            $cols = '';

            for ($i=0; $i < ($total_columns  + 1); $i++) {
                $cols .= '<td class=" val  v-align">&nbsp;</td>';
            }
        ?>
                <?php for( $x = 1; $x<=($rows-$count_rw-5) ; $x++ ): ?>
                   <tr class="page-break extra_tr mgpd  v-align">

                       <?php echo $cols; ?>

                    </tr>
                <?php endfor; ?>

        <?php endif; ?>



        <!-- TOTALS -->
       <tr class="page-break">
            <td class="val " colspan="2">GRAND TOTAL</td>
            <?php foreach($accounts as $i => $account): ?>
                <?php foreach($account['titles'] as $j => $title): ?>
                    <td class="val text-right  v-align">
                        <?php echo e(number_format($title['total'], 2)); ?>

                    </td>
                <?php endforeach; ?>

                <?php foreach($account['subtitles'] as $j => $subtitle): ?>
                    <td class="val text-right  v-align">
                        <?php echo e(number_format($subtitle['total'], 2)); ?>

                    </td>
                <?php endforeach; ?>
            <?php endforeach; ?>

            <?php foreach($shares as $i => $share): ?>
                <td class=" val text-right  v-align">
                    <?php echo e(number_format($share['total_share'], 2)); ?>

                </td>
                <?php foreach($share['barangays'] as $j => $barangay): ?>
                <td class=" val text-right  v-align">
                    <?php echo e(number_format($barangay['total_share'], 2)); ?>

                </td>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <td class=" val text-right  v-align"><?php echo e(number_format($gtotal, 2)); ?></td>
        </tr>
</tbody>
</table>
</div>
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
    <table class="table table-condensed small-launay" id="b1" style="page-break-after: always;">
        <tr>
            <th class="" style="padding: 0;margin: 0;">ACCOUNTABLE OFFICER/BANK</th>
            <th class="" style="padding: 0;margin: 0;">REFERENCE</th>
            <th class="" style="padding: 0;margin: 0;">TOTAL</th>
        </tr>
        <tr>
            <td class="" style="padding: 0;margin: 0;">
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
            <td class="" style="padding: 0;margin: 0;"><?php echo e($_GET['report_no']); ?></td>
            <td class="" style="padding: 0;margin: 0;">PHP <?php echo e(number_format($total_with_ada, 2)); ?></td>
        </tr>
    </table>
<br>
    <!-- ACCOUNTABILITY FOR ACCOUNTABLE FORMS -->
    <h4>C. ACCOUNTABILITY FOR ACCOUNTABLE FORMS</h4>
    <table class="table table-bordered table-condensed page-break small-launay">
    <thead>
        <tr class="page-break">
            <th class="" rowspan="3" style="vertical-align: top;">Name of Forms & No.</th>
            <th class="" colspan="3">Beginning Balance</th>
            <th class="" colspan="3">Receipt</th>
            <th class="" colspan="3">Issued</th>
            <th class="" colspan="3">Ending Balance</th>
        </tr>
        <tr class="page-break">
            <th class="" rowspan="2" style="vertical-align: top;">Qty.</th>
            <th class="" colspan="2">Inclusive Serial Nos.</th>
            <th class="" rowspan="2" style="vertical-align: top;">Qty.</th>
            <th class="" colspan="2">Inclusive Serial Nos.</th>
            <th class="" rowspan="2" style="vertical-align: top;">Qty.</th>
            <th class="" colspan="2">Inclusive Serial Nos.</th>
            <th class="" rowspan="2" style="vertical-align: top;">Qty.</th>
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
        <tr class="page-break">
            <td class="">
                <?php
                  $beg_total += $rcpt['beg_qty']?$rcpt['beg_qty']:0;
                  $rec_total += $rcpt['rec_qty']?$rcpt['rec_qty']:0;
                  $iss_total += $rcpt['iss_qty']?$rcpt['iss_qty']:0;
                  $end_total += $rcpt['end_qty']?$rcpt['end_qty']:0;
                ?>
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
            <td class=" val"><?php echo e($rcpt['end_qty']); ?></td>
            <td class=" val"><?php echo e($rcpt['end_from']); ?></td>
            <td class=" val"><?php echo e($rcpt['end_to']); ?></td>
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
<!-- <br> -->
    <div style="page-break-inside: avoid;"> 
        <table class="table  table-no-border  small-launay" style="page-break-inside: avoid;">
            <tr>
                <td colspan="2">  <h4>D. SUMMARY OF COLLECTIONS AND REMITTANCES/DEPOSITS</h4></td>
            </tr>
            <tr>
            <td class="table-border-right">
            <table class="table table-condensed" id="d1">
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
                  $bank_total = 0;
                ?>
                 <table class="table table-condensed table-bordered" id="d2">
                 <thead>
                            <tr>
                                <th class="">Drawee Bank</th>
                                <th class="">Check No.</th>
                                <th class="">Payee</th>
                                <th class="">Amount</th>
                            </tr>
                </thead>
                        <tbody>
                                 <?php foreach($bank as $b): ?>
                                    <tr>
                                        <td class=""><?php echo e($b['bank']); ?></td>
                                        <td class=""><?php echo e($b['check_no']); ?></td>
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
    <table  class="table table-borderedx  small-launay">
        <tr>
        <td class="table-border-right" style="width: 45%;">
        <table class="table table-no-border" >
            <tr>
                <th  class="border-botts" >CERTIFICATION</th>
            </tr>
            <tr>
                <td class="">
                    <table class="table table-no-border  small-launay" style="margin-top:10px;">
                        <tr>
                            <td colspan="3">
                            I hereby certify that the foregoing report of collections
                            and accountability for Accountable Forms is true and correct.
                            </td>
                        </tr>
                        <tr><td colspan="3" height="30px">&nbsp;</td></tr>
                        <tr >
                            <th   class="border-botts text-center" style="vertical-align: bottom;"><?php echo e($acctble_officer_name->value); ?></th>
                            <th></th>
                            <th class="border-botts text-center"><?php echo e(date('M')); ?>. <?php echo e(date('d, Y')); ?></th>
                        </tr>
                        <tr>
                            <th  class="text-center" style="width:60%;"><?php echo e($short_name_lcro->value); ?></th>
                             <th style="width:2%"></th>
                            <th class="text-center">Date</th>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        </td>
        <td style="width: 55%;">
        <table class="table table-no-border">
            <tr>
                <th  class="border-botts">VERIFICATION AND ACKNOWLEDGEMENT</th>
            </tr>
            <tr>
                <td class="">
                    <table class="table table-no-border  small-launay">
                        <tr>
                            <td colspan="3">
                            I hereby certify that the foregoing report of collections
                            has been verified and acknowledge receipt of
                            <b><u><?php echo e($total_in_words); ?> <?php if(strlen($total_in_words) >= 42 && strlen($total_in_words) <= 45): ?> <br> <?php endif; ?></u> (PHP <?php echo e(number_format($summary_total  - $bank_depo, 2)); ?>)</b>.
                            </td>
                        </tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr >
                            <th class="border-botts text-center" style="vertical-align: bottom;">
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
                            <th class="border-botts text-center" style="width: 30%"><?php echo e(date('M')); ?>. <?php echo e(date('d, Y')); ?></th>
                        </tr>
                        <tr>
                            <th class="text-center" style="width:70%" >
                                <?php if($_GET['type'] == 2): ?>

                                <?php
                                    // $bts_report_officer_position = $bts_report_officer_position->value;
                                    if (isset($bts_report_officer_position->value)) {
                                        $bts_report_officer_position = $bts_report_officer_position->value;
                                    } else {
                                        $bts_report_officer_position = $bts_report_officer_position;
                                    }
                                    // $bts_report_officer_position = str_replace("(", "<br />(", $bts_report_officer_position);
                                 ?>
                                    <?php echo $bts_report_officer_position; ?>

                               
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
                                        <?php echo e($trustfund_officer_position); ?>

                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php if(isset($officer_position->value)): ?>
                                        <?php echo e($officer_position->value); ?>

                                    <?php else: ?> 
                                        <?php echo e($officer_position); ?>

                                    <?php endif; ?>
                                <?php endif; ?>
                            </th>
                            <th style="width:3%"></th>
                            <th class="text-center">Date</th>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        </td>
        </tr>
        </table>


</body>
</html>


