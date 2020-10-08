<!DOCTYPE html>
<html>
<head>
    <title></title>

    <style type="text/css">
     
    </style>
</head>
<body>


<table class="center ">
        <tr>
            <td>REPORT OF COLLECTIONS AND DEPOSITS</td>
        </tr>
        <tr>
            <td>PROVINCIAL GOVERNMENT OF BENGUET</td>
        </tr>
        <tr>
            <td>OFFICE OF THE PROVINCIAL TREASURER</td>
        </tr>
    </table>

    <table class="table table-condensed ">
        <tr>
            <td>Fund: <b><?php echo e($fund->name); ?></b></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Date</td>
            <td class="underline"><?php echo e(date('F d, Y')); ?></td>
        </tr>
        <tr>
            <td>Name of Accountable Officer: <b><?php echo e($acctble_officer_name->value); ?> - <?php echo e($acctble_officer_position->value); ?></b></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="val">Report No.</td>
            <td class="underline"><?php echo e($_GET['report_no']); ?></td>
        </tr>

    </table>

<h4>A. COLLECTIONS</h4>

    <table id="collections" class="table table-bordered table-condensed table-responsive page-break">
    <thead>
        <tr class="page-break">
            <th class="ors" rowspan="3" colspan="1">OR Nos.</th>
            <th class=" detail_payor" rowspan="3" colspan="1" >Payor</th>
            <?php foreach($accounts as $i => $account): ?>
                <th rowspan="1" colspan="<?php echo e(count($account['titles']) + count($account['subtitles'])); ?>"><?php echo e($i); ?> </th>
            <?php endforeach; ?>

            <?php if(count($shares) > 0): ?>
            <th rowspan="1" colspan="<?php echo e($share_columns); ?>">MUNICIPAL & BRGY SHARES</th>
            <?php endif; ?>

            <th class="" rowspan="3" colspan="1">TOTAL AMOUNT</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <?php foreach($accounts as $i => $account): ?>

                <?php foreach($account['titles'] as $j => $title): ?>
                    <th  rowspan="2"><?php echo e($j); ?> </th>
                <?php endforeach; ?>
                <?php foreach($account['subtitles'] as $j => $subtitle): ?>
                    <th rowspan="2" ><?php echo e($j); ?> </th>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <?php foreach($shares as $i => $share): ?>
                <th rowspan="2" ><?php echo e($share['name']); ?></th>
                <?php foreach($share['barangays'] as $j => $barangay): ?>
                <th rowspan="2" ><?php echo e($barangay['name']); ?></th>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tr>
</thead>
        
<tbody>
<?php $gtotal = 0; ?>
        <?php foreach($receipts as $i => $receipt): ?>
        <tr class="page-break">
            <td class=" val " ><?php echo e($receipt->serial_no); ?></td>
            <?php if(!isset($receipts_total[$receipt->serial_no])): ?>
                <td class=" cancelled_remark" colspan="<?php echo e($total_columns); ?>">
                    Cancelled - <?php echo e($receipt->cancelled_remark); ?>

                </td>
            <?php else: ?>
                <td class=" detail_payor val"><?php echo e($receipt->customer->name); ?></td>
                <?php foreach($accounts as $i => $account): ?>
                    <?php foreach($account['titles'] as $ji => $title): ?>
                        <td class=" val text-right">
                            <?php if(isset($title[$receipt->serial_no])): ?>
                            <?php echo e(round($title[$receipt->serial_no], 2)); ?>

                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>

                    <?php foreach($account['subtitles'] as $j => $subtitle): ?>
                        <td class=" val text-right">
                            <?php if(isset($subtitle[$receipt->serial_no])): ?>
                            <?php echo e(round($subtitle[$receipt->serial_no], 2)); ?>

                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                <?php endforeach; ?>

                <?php foreach($shares as $i => $share): ?>
                    <td class=" val text-right">
                        <?php if(isset($share[$receipt->serial_no]) && $share[$receipt->serial_no] > 0): ?>
                        <?php echo e(round($share[$receipt->serial_no], 2)); ?>

                        <?php endif; ?>
                    </td>
                    <?php foreach($share['barangays'] as $j => $barangay): ?>
                    <td class=" val text-right">
                        <?php if(isset($barangay[$receipt->serial_no]) && $barangay[$receipt->serial_no] > 0): ?>
                        <?php echo e(round($barangay[$receipt->serial_no], 2)); ?>

                        <?php endif; ?>
                    </td>
                    <?php endforeach; ?>
                <?php endforeach; ?>

                <td class=" border-botts val text-right">
                   <?php 
                         $gtotal += $receipts_total[$receipt->serial_no];
                    ?>
                    <?php echo e(round($receipts_total[$receipt->serial_no], 2)); ?>

                </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
        <tr class="page-break">
            <td class="val" colspan="2">GRAND TOTAL</td>
            <?php foreach($accounts as $i => $account): ?>
                <?php foreach($account['titles'] as $j => $title): ?>
                    <td class="val text-right">
                        <?php echo e(round($title['total'], 2)); ?>

                    </td>
                <?php endforeach; ?>

                <?php foreach($account['subtitles'] as $j => $subtitle): ?>
                    <td class="val text-right">
                        <?php echo e(round($subtitle['total'], 2)); ?>

                    </td>
                <?php endforeach; ?>
            <?php endforeach; ?>

            <?php foreach($shares as $i => $share): ?>
                <td class=" val text-right">
                    <?php echo e(round($share['total_share'], 2)); ?>

                </td>
                <?php foreach($share['barangays'] as $j => $barangay): ?>
                <td class=" val text-right">
                    <?php echo e(round($barangay['total_share'], 2)); ?>

                </td>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <td class=" val text-right"><?php echo e(round($gtotal, 2)); ?></td>
        </tr>
    </tbody>
</table>


<table>
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
                                <?php $total += $title['total']; ?> 
                                <?php echo e(round($title['total'], 2)); ?>

                            </td>
                        </tr>
                        <?php endforeach; ?>

                        <?php foreach($account['subtitles'] as $j => $subtitle): ?>
                        <tr >
                            <td><?php echo e($j); ?></td>
                            <td class="val text-right">
                               <?php $total += $subtitle['total']; ?> 
                                <?php echo e(round($subtitle['total'], 2)); ?>

                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>

                    <?php if($bac_type_1 > 0 && $_GET['type'] == 1): ?>
                    <tr >
                        <td>BAC Goods & Services</td>
                        <td class="val">
                            <?php $total += $bac_type_1; ?>
                            <?php echo e(round($bac_type_1, 2)); ?>

                        </td>
                    </tr>
                    <?php endif; ?>

                    <?php if($bac_type_2 > 0 && $_GET['type'] == 1): ?>
                    <tr >
                        <td>BAC INFRA</td>
                        <td class="val text-right">
                            <?php $total += $bac_type_2; ?>
                            <?php echo e(round($bac_type_2, 2)); ?>

                        </td>
                    </tr>
                    <?php endif; ?>

                    <?php if($bac_type_3 > 0 && $_GET['type'] == 1): ?>
                    <tr >
                        <td>BAC Drugs & Meds</td>
                        <td class="val text-right">
                            <?php $total += $bac_type_3; ?>

                            <?php echo e(round($bac_type_3, 2)); ?>

                        </td>
                    </tr>
                    <?php endif; ?>

                    <tr class="set-border-tb">
                        <td ><b>TOTAL</b></td>
                        <td class="val text-right">
                            <b><?php echo e(round($total, 2)); ?></b>
                        </td>
                    </tr>

                </table>
    <?php if($_GET['type'] == 1): ?>
            <table class="table">
                <tr>
                    <td><b>Municipal/Barangay Share</b></td>
                    <td>
                       <?php $total = 0; ?>
                    </td>
                </tr>
                <?php foreach($shares as $i => $share): ?>
                    <tr >
                        <td><b><?php echo e($share['name']); ?></b></td>
                        <td class="val">
                            <?php if(isset($amusement_shares[$i])): ?>
                                <?php  $share_value = $share['total_share'] - $amusement_shares[$i]['total_share']; ?>
                            <?php else: ?>
                                <?php $share_value = $share['total_share']; ?>
                            <?php endif; ?>
                            <?php $total += $share_value; ?>

                            <?php echo e(round($share_value, 2)); ?>

                        </td>
                    </tr>
                    <?php foreach($share['barangays'] as $j => $barangay): ?>
                        <tr >
                            <td><div class="brgy"><?php echo e($barangay['name']); ?></div></td>
                            <td class="val text-right">
                               
                                <?php $total += $barangay['total_share']; ?>
                                
                                <?php echo e(round($barangay['total_share'], 2)); ?>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <tr class="set-border-tb">
                    <td ><b>TOTAL</b></td>
                    <td class="val text-right">
                        <b><?php echo e(round($total, 2)); ?></b>
                    </td>
                </tr>
            </table>

            <table class="table">
                <tr>
                    <td><b>Amusement Share</b></td>
                    <td>
                        <?php $total = 0; ?>
                    </td>
                </tr>
                <?php foreach($amusement_shares as $i => $share): ?>
                    <tr>
                        <td><b><?php echo e($share['name']); ?></b></td>
                        <td class="val text-right">
                          <?php $total += $share['total_share']; ?>
                            <?php echo e(round($share['total_share'], 2)); ?>

                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr class="set-border-tb">
                    <td class=""><b>TOTAL</b></td>
                    <td class="val text-right">
                        <b><?php echo e(round($total, 2)); ?></b>
                    </td>
                </tr>
            </table>
<?php endif; ?>


<h4>REMITTANCES/DEPOSITS</h4>
       <?php $bank_total = 0; ?>
<table class="table table-condensed table-bordered">
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
                                    <td class=" val">
                                        <?php $bank_total += $b['amt']; ?>
                                        <?php echo e(round($b['amt'], 2)); ?>

                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td class="" colspan="3">Total</td>
                                    <td class="  val"><?php echo e(round($bank_total, 2)); ?></td>
                                </tr>
                    </tbody>
</table>



 <h4>ACCOUNTABILITY FOR ACCOUNTABLE FORMS</h4>
<table class="table table-bordered table-condensed page-break">
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
                 $beg_total = 0 ;
                 $rec_total = 0 ;
                 $iss_total = 0 ;
                 $end_total = 0 ;
                ?>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($rcpt_acct as $rcpt): ?>
        <tr class="page-break">
            <td class="">
                <?php 
                 $beg_total += $rcpt['beg_qty']?$rcpt['beg_qty']:0 ;
                 $rec_total += $rcpt['rec_qty']?$rcpt['rec_qty']:0 ;
                 $iss_total += $rcpt['iss_qty']?$rcpt['iss_qty']:0 ;
                 $end_total += $rcpt['end_qty']?$rcpt['end_qty']:0 ;
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

            <?php  
                $summary_total = 0;
                $total_with_ada = 0;
                $has_ada = 0;
                $ada = 0;
        ?>

        <?php foreach($trantypes as $i => $type): ?>
                    <?php if($i == 4): ?>
                        <?php if($type['total'] > 0): ?>
                        <?php   $ada = $type['total'];
                                $has_ada = 1;
                        ?>
                        <?php endif; ?>
                        <?php  $total_with_ada += $type['total']; ?>
                    <?php else: ?>
                        <?php $total_with_ada += $type['total'];
                              $summary_total += $type['total']; 
                        ?>
                    <?php endif; ?>
        <?php endforeach; ?>
        <table class="table table-condensed">
            <tr>
                <td class="">Beginning Balance <?php echo e($report_start); ?></td>
                <td class=" val">

                </td>
            </tr>
            <tr>
                <td class="">Add: Collections <?php echo e($date_range); ?></td>
                <td class=" val">

                </td>
            </tr>
            <?php foreach($trantypes as $i => $type): ?>
                <tr>
                    <td class=" tdindent"><?php echo e($type['name']); ?></td>
                    <td class=" val text-right">
                        <?php echo e(round($type['total'], 2)); ?>

                    </td>
                </tr>
            <?php endforeach; ?>
                <tr>
                    <td class=""><b>Total</b></td>
                    <td class=" val text-right"><b><?php echo e(round($total_with_ada, 2)); ?></b></td>
                </tr>
            <?php if($has_ada): ?>
                <tr>
                    <td class=""><b>Less ADA</b></td>
                    <td class=" val text-right"><b><?php echo e(round($ada, 2)); ?></b></td>
                </tr>
            <?php endif; ?>
            <tr>
                <td class=""><b>Remittance/Deposit to Cashier/Treasurer</b></td>
                <td class=" val text-right"><b><?php echo e(round($summary_total, 2)); ?></b></td>
            </tr>
            <tr>
                <td class=""><b>Balance</b></td>
                <td class=" val text-right"><b></b></td>
            </tr>
        </table>


</body>
</html>
