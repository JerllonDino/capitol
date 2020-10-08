<!DOCTYPE html>
<html>
<head>
    <title>Collections And Deposits</title>
    <?php echo e(Html::style('/bootstrap-3.3.6/css/bootstrap.min.css')); ?>

    <style type="text/css">
        html {
            margin-bottom: 8px;
            margin-top: 8px;
            margin-left: 15px;
            margin-right: 15px;
        }
        /* class works for table row */
        table tr.page-break{
          page-break-after:always
        }


        /* class works for table */
        table.page-break{
          page-break-after:always
        }

        @media  print {
         .page-break  { display: block; page-break-before: always; }
        }
        .center {
                width: 450px;
                text-align: center;
                margin: 10px auto;
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

        #collections{
            overflow-y: auto;
        }

        #collections > thead > tr > th, #collections > tbody > tr > td{
            padding: 1px;
        }


    </style>
       
            
</head>
<body>
    <span class="hidden">
         <?php echo e($gtotal = 0); ?>

    </span>
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

    <table class="table table-condensed ">
        <tr>
            <td>Fund: <b><?php echo e($base['fund']->name); ?></b></td>
            <td>Date</td>
            <td class="underline"><?php echo e($report_date); ?></td>
        </tr>
        <tr>
            <td>Name of Accountable Officer: <b><?php echo e($base['acctble_officer_name']->value); ?> - <?php echo e($base['acctble_officer_position']->value); ?></b></td>
            <td class="val">Report No.</td>
            <td class="underline"><?php echo e($_GET['report_no']); ?></td>
        </tr>

    </table>

<h4>A. COLLECTIONS</h4>
<div class="table-responsive">
    <table id="collections" class="table table-bordered table-condensed table-responsive page-break">
    <thead>
        <tr class="page-break">
            <th class="" rowspan="2">OR Nos.</th>
            <th class=" detail_payor" rowspan="2">Payor</th>
            <?php foreach($base['accounts'] as $i => $account): ?>
                <th class="" colspan="<?php echo e(count($account['titles']) + count($account['subtitles'])); ?>"><?php echo e($i); ?></th>
            <?php endforeach; ?>

            <?php if(count($base['shares']) > 0): ?>
            <th class="" colspan="<?php echo e($base['share_columns']); ?>">MUNICIPAL & BRGY SHARES</th>
            <?php endif; ?>

            <th class="" rowspan="2">TOTAL AMOUNT</th>
        </tr>
        <tr class="page-break">
            <?php foreach($base['accounts'] as $i => $account): ?>
                <?php foreach($account['titles'] as $j => $title): ?>
                <?php 
                $acronym = $j ;
                
                ?>
                    <th><?php echo e($acronym); ?></th>
                <?php endforeach; ?>
                <?php foreach($account['subtitles'] as $j => $subtitle): ?>
                    <?php 
                    $acronym = $j ;
                    ?>
                    <th><?php echo e($acronym); ?></th>
                <?php endforeach; ?>
            <?php endforeach; ?>

            <?php foreach($base['shares'] as $i => $share): ?>
                <th><?php echo e($share['name']); ?></th>
                <?php foreach($share['barangays'] as $j => $barangay): ?>
                <th><?php echo e($barangay['name']); ?></th>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tr>
        <tr class="border-botts page-break">
            <th class="border-botts" colspan="<?php echo e($base['total_columns'] + 1); ?>"><?php echo e($base['date_range']); ?></th>
        </tr>
</thead>
        <!-- VALUES PER RECEIPT -->
<tbody>
    <?php $total_rc = []; ?>
        <?php foreach($base['receipts'] as $i => $receipt): ?>
            <?php
                    if(!isset($total_rc[$receipt->serial_no])){
                         $total_rc[$receipt->serial_no] = 0;
                    }
            ?>

        <tr class="page-break">
            <td class=" val"><?php echo e($receipt->serial_no); ?></td>
            <?php if(!isset($base['receipts_total'][$receipt->serial_no])): ?>
                <td class=" cancelled_remark" colspan="<?php echo e($base['total_columns']); ?>">
                    Cancelled - <?php echo e($receipt->cancelled_remark); ?>

                </td>
            <?php else: ?>
                <td class=" detail_payor val"><?php echo e($receipt->customer->name); ?></td>
                <?php foreach($base['accounts'] as $i => $account): ?>
                    <?php foreach($account['titles'] as $ji => $title): ?>
                        <td class=" val text-right">
                            <?php if(isset($title[$receipt->serial_no])): ?>
                            <?php echo e(number_format($title[$receipt->serial_no], 2)); ?>

                            <?php  $total_rc[$receipt->serial_no] += $title[$receipt->serial_no]; ?>

                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>

                    <?php foreach($account['subtitles'] as $j => $subtitle): ?>
                        <td class=" val text-right">
                            <?php if(isset($subtitle[$receipt->serial_no])): ?>
                            <?php echo e(number_format($subtitle[$receipt->serial_no], 2)); ?>

                            <?php  $total_rc[$receipt->serial_no] += $subtitle[$receipt->serial_no]; ?>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                <?php endforeach; ?>

                <?php foreach($base['shares'] as $i => $share): ?>
                    <td class=" val text-right">
                        <?php if(isset($share[$receipt->serial_no]) && $share[$receipt->serial_no] > 0): ?>
                        <?php echo e(number_format($share[$receipt->serial_no], 2)); ?>

                        <?php  $total_rc[$receipt->serial_no] += $share[$receipt->serial_no]; ?>
                        <?php endif; ?>
                    </td>
                    <?php foreach($share['barangays'] as $j => $barangay): ?>
                    <td class=" val text-right">
                        <?php if(isset($barangay[$receipt->serial_no]) && $barangay[$receipt->serial_no] > 0): ?>
                        <?php echo e(number_format($barangay[$receipt->serial_no], 2)); ?>

                        <?php  $total_rc[$receipt->serial_no] += $barangay[$receipt->serial_no]; ?>
                        <?php endif; ?>
                    </td>
                    <?php endforeach; ?>
                <?php endforeach; ?>

                <td class=" border-botts val text-right">
                    <?php 
                        $gtotal += $base['receipts_total'][$receipt->serial_no];
                    ?>
                    <?php echo e(number_format($total_rc[$receipt->serial_no], 2)); ?>

                </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
        <!-- TOTALS -->
        <tr class="page-break">
            <td class="val" colspan="2">GRAND TOTAL</td>
            <?php foreach($base['accounts'] as $i => $account): ?>
                <?php foreach($account['titles'] as $j => $title): ?>
                    <td class="val text-right">
                        <?php echo e(number_format($title['total'], 2)); ?>

                    </td>
                <?php endforeach; ?>

                <?php foreach($account['subtitles'] as $j => $subtitle): ?>
                    <td class="val text-right">
                        <?php echo e(number_format($subtitle['total'], 2)); ?>

                    </td>
                <?php endforeach; ?>
            <?php endforeach; ?>

            <?php foreach($base['shares'] as $i => $share): ?>
                <td class=" val text-right">
                    <?php echo e(number_format($share['total_share'], 2)); ?>

                </td>
                <?php foreach($share['barangays'] as $j => $barangay): ?>
                <td class=" val text-right">
                    <?php echo e(number_format($barangay['total_share'], 2)); ?>

                </td>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <td class=" val text-right"><?php echo e(number_format($gtotal, 2)); ?></td>
        </tr>
</tbody>

<tfoot>

</tfoot>

</table>
   <div class="col-md-6">
             <table class="table table-condensed">
                    <tr >
                        <td><b>SUMMARY OF COLLECTION</b></td>
                        <td >
                            <span class="hidden">
                            <?php echo e($total = 0); ?>

                            </span>
                        </td>
                    </tr>
                    <?php foreach($base['accounts'] as $i => $account): ?>
                        <?php foreach($account['titles'] as $j => $title): ?>
                        <tr >
                            <td><?php echo e($j); ?></td>
                            <td class="val text-right">
                                <span class="hidden">
                                <?php echo e($total += $title['total']); ?>

                                </span>
                                <?php echo e(number_format($title['total'], 2)); ?>

                            </td>
                        </tr>
                        <?php endforeach; ?>

                        <?php foreach($account['subtitles'] as $j => $subtitle): ?>
                        <tr >
                            <td><?php echo e($j); ?></td>
                            <td class="val text-right">
                                <span class="hidden">
                                <?php echo e($total += $subtitle['total']); ?>

                                </span>
                                <?php echo e(number_format($subtitle['total'], 2)); ?>

                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>

                    <?php if($base['bac_type_1'] > 0 && $_GET['type'] == 1): ?>
                    <tr >
                        <td>BAC Goods & Services</td>
                        <td class="val">
                            <span class="hidden">
                            <?php echo e($total += $base['bac_type_1']); ?>

                            </span>
                            <?php echo e(number_format($base['bac_type_1'], 2)); ?>

                        </td>
                    </tr>
                    <?php endif; ?>

                    <?php if($base['bac_type_2'] > 0 && $_GET['type'] == 1): ?>
                    <tr >
                        <td>BAC INFRA</td>
                        <td class="val text-right">
                            <span class="hidden">
                            <?php echo e($total += $base['bac_type_2']); ?>

                            </span>
                            <?php echo e(number_format($base['bac_type_2'], 2)); ?>

                        </td>
                    </tr>
                    <?php endif; ?>

                    <?php if($base['bac_type_3'] > 0 && $_GET['type'] == 1): ?>
                    <tr >
                        <td>BAC Drugs & Meds</td>
                        <td class="val text-right">
                            <span class="hidden">
                            <?php echo e($total += $base['bac_type_3']); ?>

                            </span>
                            <?php echo e(number_format($base['bac_type_3'], 2)); ?>

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
</div>


<div class="col-md-6">
    <table class="table table-no-border table-condensed">
        <tr>
        <td class="table-border-right" >
      
        </td>
        <td  >
            <?php if($_GET['type'] == 1): ?>
            <table class="table">
                <tr>
                    <td><b>Municipal/Barangay Share</b></td>
                    <td>
                        <span class="hidden">
                        <?php echo e($total = 0); ?>

                        </span>
                    </td>
                </tr>
                <?php foreach($base['shares'] as $i => $share): ?>
                    <tr >
                        <td><b><?php echo e($share['name']); ?></b></td>
                        <td class="val">
                            <span class="hidden">
                            <?php if(isset($amusement_shares[$i])): ?>
                                <?php echo e($share_value = $share['total_share'] - $amusement_shares[$i]['total_share']); ?>

                            <?php else: ?>
                                <?php echo e($share_value = $share['total_share']); ?>

                            <?php endif; ?>
                            <?php echo e($total += $share_value); ?>

                            </span>
                            <?php echo e(number_format($share_value, 2)); ?>

                        </td>
                    </tr>
                    <?php foreach($share['barangays'] as $j => $barangay): ?>
                        <tr >
                            <td><div class="brgy"><?php echo e($barangay['name']); ?></div></td>
                            <td class="val text-right">
                                <span class="hidden">
                                <?php echo e($total += $barangay['total_share']); ?>

                                </span>
                                <?php echo e(number_format($barangay['total_share'], 2)); ?>

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
                        <span class="hidden">
                        <?php echo e($total = 0); ?>

                        </span>
                    </td>
                </tr>
                <?php foreach($base['amusement_shares'] as $i => $share): ?>
                    <tr>
                        <td><b><?php echo e($share['name']); ?></b></td>
                        <td class="val text-right">
                            <span class="hidden">
                            <?php echo e($total += $share['total_share']); ?>

                            </span>
                            <?php echo e(number_format($share['total_share'], 2)); ?>

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
</div>

         


</div>


 <?php 
	$summary_total = 0;
	$total_with_ada = 0;
	$has_ada = 0;
	$ada = 0;
		foreach ($base['trantypes'] as $i => $type){
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
    <table class="table table-condensed">
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
                <?php else: ?>
                    <?php if(isset($base['officer_name']->value)): ?>
                        <?php echo e($base['officer_name']->value); ?>

                    <?php else: ?> 
                        <?php echo e($base['officer_name']); ?>

                    <?php endif; ?>
                <?php endif; ?>
            </td>
            <td class=""><?php echo e($_GET['report_no']); ?></td>
            <td class=" val">PHP <?php echo e(number_format($total_with_ada, 2)); ?></td>
        </tr>
    </table>

    <!-- ACCOUNTABILITY FOR ACCOUNTABLE FORMS -->.
    <h4>C. ACCOUNTABILITY FOR ACCOUNTABLE FORMS</h4>
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
                <span class="hidden">
                <?php echo e($beg_total = 0); ?>

                <?php echo e($rec_total = 0); ?>

                <?php echo e($iss_total = 0); ?>

                <?php echo e($end_total = 0); ?>

                </span>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($base['rcpt_acct'] as $rcpt): ?>
        <tr class="page-break">
            <td class="">
                <span class="hidden">
                <?php echo e($beg_total += $rcpt['beg_qty']?$rcpt['beg_qty']:0); ?>

                <?php echo e($rec_total += $rcpt['rec_qty']?$rcpt['rec_qty']:0); ?>

                <?php echo e($iss_total += $rcpt['iss_qty']?$rcpt['iss_qty']:0); ?>

                <?php echo e($end_total += $rcpt['end_qty']?$rcpt['end_qty']:0); ?>

                </span>
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

    <table class="table  table-no-border">
    <tr>
        <td colspan="2">  <h4>D. SUMMARY OF COLLECTIONS AND REMITTANCES/DEPOSITS</h4></td>
    </tr>
        <tr>
        <td class="table-border-right">
            <table class="table table-condensed">
    			<tr>
    				<td class="">Beginning Balance <?php echo e($base['report_start']); ?></td>
    				<td class=" val"></td>
                    <td class=" val text-right">0.00</td>
    			</tr>
    			<tr>
    				<td class="">Add: Collections <?php echo e($base['date_range']); ?></td>
    				<td class=" val"></td>
                    <td></td>
    			</tr>
                <?php $bank_depo = 0; $bank_depo_name = ''; ?>
                <?php foreach($base['trantypes'] as $i => $type): ?>
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
            <span class="hidden">
            <?php echo e($bank_total = 0); ?>

            </span>
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
                             <?php foreach($base['bank'] as $b): ?>
                                <tr>
                                    <td class=""><?php echo e($b['bank']); ?></td>
                                    <td class=""><?php echo e($b['check_no']); ?></td>
                                    <td class="">Provincial Government of Benguet</td>
                                    <td class="val text-right">
                                        <span class="hidden">
                                        <?php echo e($bank_total += $b['amt']); ?>

                                        </span>
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
    <!-- <br> -->

    <!-- CERTIFICATION/VERIFICATION AND ACKNOWLEDGEMENT -->
    <table  class="table table-borderedx" style="padding-top: -20px;">
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
                            <th class="border-botts"><?php echo e($base['acctble_officer_name']->value); ?></th>
                            <th></th>
                            <th class="border-botts"><?php echo e(date('F d, Y')); ?></th>
                        </tr>
                        <tr>
                            <th colspan="2"><?php echo e($base['acctble_officer_position']->value); ?></th>
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
                            <b><u><?php echo e($base['total_in_words']); ?></u> (PHP <?php echo e(number_format($summary_total - $bank_depo, 2)); ?>)</b>.
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
                                <?php else: ?>
                                    <?php if(isset($base['officer_name']->value)): ?>
                                        <?php echo e($base['officer_name']->value); ?>

                                    <?php else: ?> 
                                        <?php echo e($base['officer_name']); ?>

                                    <?php endif; ?>
                                <?php endif; ?>
                            </th>
                            <th></th>
                            <th class="border-botts"><?php echo e(date('F d, Y')); ?></th>
                        </tr>
                        <tr>
                            <th>
                                <?php if($_GET['type'] == 2): ?> 
                                    <?php if(isset($bts_report_officer_position->value)): ?>
                                        <?php echo e($bts_report_officer_position->value); ?>

                                    <?php else: ?> 
                                        <?php echo e($bts_report_officer_position); ?>

                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php if(isset($base['officer_position']->value)): ?>
                                        <?php echo e($base['officer_position']->value); ?>

                                    <?php else: ?> 
                                        <?php echo e($base['officer_position']); ?>

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

</body>
</html>
