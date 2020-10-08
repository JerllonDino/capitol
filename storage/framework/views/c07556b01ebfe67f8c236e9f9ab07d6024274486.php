<!DOCTYPE html>
<html>
<head>
    <title>Certificate - Provincial Permit</title>
    <style>
        body {
            font-family: arial, "sans-serif";
            margin: 0px;
            font-size: 16px;
        }
        #items {
            width: 100%;
            border: 1px solid black;
            border-collapse: collapse;
            margin-right: auto;
            margin-left: auto;
        }
        .other_item{
            border: 1px solid black;
        }
        }
        .underline {
            border-bottom: 1px solid #000000;
        }
        .header-container {
            width: 80%;
            text-align: center;
        }
        .header {
            width: 95%;
            display: block;
            font-weight: strong;
        }
        #logo {
            height: 80px;
            float: left;
            margin-left: 100px;
        }
        #header-dt {
            float: right;
            text-align: center;
            margin-top: 30px;
        }
        #cert {
            /*margin-top: 60px;*/
            margin-top: 20px;
            /*margin-bottom: 30px;*/
            margin-bottom: 10px;
            font-size: 18px;
            text-align: center;
            width: 100%;
        }
        #officers {
            width: 100%;
            padding-left: 35%;
        }
        .underline {
            border-bottom: 1px solid #000000;
        }
        .blk {
            padding-top: 10px;
        }
        .center {
            text-align: center;
        }
        .val {
            text-align: right;
        }
        .hidden {
            display: none;
        }
        .title {
            padding-top: 20px;
        }
        .bottom2 {
            position: fixed;
            top: 73%;
        }
        .bottom {
            position: fixed;
            top: 77%;
        }
        .indent {
            padding-left: 30px;
        }
        .double-border{
            border-top: 1px solid #000000;
            border-bottom: 3px double #000000;
        }
        #detail-head th {
            font-weight: none;
            font-size: 13px;
        }

        .small-font{
            font-size: 9px;
            text-transform: lowercase;
        }

    </style>
</head>
<body>
    <div class="header-container">
        <?php echo e(Html::image('/asset/images/benguet_capitol.png', "Logo", array('id' => 'logo'))); ?>

        <span class="header">Republic of the Philippines</span>
        <span class="header">PROVINCE OF BENGUET</span>
        <span class="header">La Trinidad</span>
        <span class="header">OFFICE OF THE PROVINCIAL TREASURER</span>
    </div>
    <!-- <table id="header-dt">
        <tr>
            <td></td>
            <td class="underline" width="125"><?php /* date('F d, Y', strtotime($cert->date_of_entry)) */ ?></td>
        </tr>
        <tr>
            <td></td>
            <td>Date</td>
        </tr>
    </table> -->
    <div id="cert">
        <b>C E R T I F I C A T I O N</b>
    </div>
    <div class="blk">
        <span class="indent">THIS IS TO CERTIFY that as per records of this Office, </span>
        <u><b><?php echo e($cert->recipient); ?></b></u>
        <span>has paid the Provincial Permit Fee, Sand and Gravel taxes and other fees under the following receipts, to wit:</span>
    </div><br>
    <!-- <div class="blk">
        <u><?php echo $cert->detail; ?></u>
    </div> -->
    <span class="hidden">
    <?php echo e($cert_sandgravelprocessed = 0); ?>

    <?php echo e($cert_abc = 0); ?>

    <?php echo e($cert_sandgravel = 0); ?>

    <?php echo e($cert_boulders = 0); ?>

    <?php $cert_fees = array(); ?>
    </span>
    <table id="items">
        <span class="hidden">
        <?php echo e($total = 0); ?>

        </span>
        <thead>
            <tr>
                <td class="other_item center"><b>Date of Payment</b></td>
                <td class="other_item center"><b>O.R. Number</b></td>
                <td class="other_item center"><b>Particulars</b></td>
                <td class="other_item center"><b>Amount Paid</b></td>
            </tr>
        </thead>
        <tbody>
        <?php $j = 0; ?> 
         <?php foreach($transactions as $transaction): ?>
            <?php if(!is_null($OtherFeesCharges)): ?>
                <?php if(isset($OtherFeesCharges[$j])): ?>
                    <?php if($OtherFeesCharges[$j]->fees_date <= $transaction->report_date && $OtherFeesCharges[$j]->fees_date <= $transactions[0]->report_date): ?>
                        <tr>
                            <td class="other_item"><?php echo e(\Carbon\Carbon::parse($OtherFeesCharges[$j]->fees_date)->toFormattedDateString()); ?></td>
                            <td class="other_item center"><?php echo e($OtherFeesCharges[$j]->or_number); ?></td>
                            <td class="other_item"><?php echo e($OtherFeesCharges[$j]->fees_charges); ?></td>
                            <td class="other_item" style="text-align: right"><?php echo e(number_format($OtherFeesCharges[$j]->ammount, 2)); ?></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php endif; ?>
                    <?php $j++; ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php foreach($transaction->items as $key => $t): ?>
                <!-- <tr> -->
                    <?php $i = 0; ?>
                    <?php if($key == 0): ?>   
                        <?php if(!in_array($t->col_receipt_id, $not_crt)): ?>
                            <?php if(strcasecmp($t->nature, 'Certified Photocopy') != 0): ?> 
                                <?php if(strcasecmp($t->nature, " Sand and Gravel Tax") == 0 && $t->col_acct_title_id == 4): ?>               
                                    <?php if($transaction->serial_no == $sg_taxes[0]): ?>
                                        <tr>
                                            <td class="other_item"><?php echo e(\Carbon\Carbon::parse($transaction->date_of_entry)->toFormattedDateString()); ?></td>
                                            <td class="other_item center"><?php echo e($transaction->serial_no); ?></td>
                                            <td class="other_item"><?php echo e($t->nature); ?></td>
                                            <td class="other_item" style="text-align: right"><?php echo e(number_format($t->value,2)); ?></td>
                                            <?php $total += $t->value; ?>
                                        </tr>
                                    <?php else: ?>
                                        <?php $ff_year = \Carbon\Carbon::parse($transaction->date_of_entry)->addYear(); ?>
                                        <!--
                                            receipts issued on the same day w/ certificate receipt exempted....
                                        -->
                                        <?php if(strcasecmp(\Carbon\Carbon::now()->format('Y'), $ff_year->format('Y')) || (\Carbon\Carbon::parse($transaction->date_of_entry)->format('Y-m-d') == \Carbon\Carbon::parse($cert_or->date_of_entry)->format('Y-m-d')) || (isset($include_from) && isset($include_to))): ?>
                                            <?php if(!isset($include_from) && !isset($include_to)): ?>
                                                <?php if(\Carbon\Carbon::parse($transaction->date_of_entry)->format('Y-m-d') >= \Carbon\Carbon::parse($include_from)->format('Y-m-d') && \Carbon\Carbon::parse($transaction->date_of_entry)->format('Y-m-d') <= \Carbon\Carbon::parse($include_to)->format('Y-m-d')): ?>
                                                    <tr>
                                                        <td class="other_item"><?php echo e(\Carbon\Carbon::parse($transaction->date_of_entry)->toFormattedDateString()); ?></td>
                                                        <td class="other_item center"><?php echo e($transaction->serial_no); ?></td>
                                                        <td class="other_item"><?php echo e($t->nature); ?></td>
                                                        <td class="other_item" style="text-align: right"><?php echo e(number_format($t->value,2)); ?></td>
                                                        <?php $total += $t->value; ?>
                                                    </tr>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td class="other_item"><?php echo e(\Carbon\Carbon::parse($transaction->date_of_entry)->toFormattedDateString()); ?></td>
                                                    <td class="other_item center"><?php echo e($transaction->serial_no); ?></td>
                                                    <td class="other_item"><?php echo e($t->nature); ?></td>
                                                    <td class="other_item" style="text-align: right"><?php echo e(number_format($t->value,2)); ?></td>
                                                    <?php $total += $t->value; ?>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <tr>
                                        <td class="other_item"><?php echo e(\Carbon\Carbon::parse($transaction->date_of_entry)->toFormattedDateString()); ?></td>
                                        <td class="other_item center"><?php echo e($transaction->serial_no); ?></td>
                                        <td class="other_item"><?php echo e($t->nature); ?></td>
                                        <td class="other_item" style="text-align: right"><?php echo e(number_format($t->value,2)); ?></td>
                                        <?php $total += $t->value; ?>
                                    </tr>
                                <?php endif; ?>
                                <!-- <tr>
                                    <td class="other_item"><?php /*\Carbon\Carbon::parse($transaction->date_of_entry)->toFormattedDateString()*/ ?></td>
                                    <td class="other_item center"><?php /*$transaction->serial_no*/ ?></td>
                                    <td class="other_item"><?php /*$t->nature*/ ?></td>
                                    <td class="other_item" style="text-align: right"><?php /*number_format($t->value,2)*/ ?></td>
                                </tr> -->
                                <?php //$total += $t->value; ?>
                            <?php endif; ?>
                        <?php elseif(count($cert_receipt) > 0): ?>
                            <?php if($cert_receipt[$i]->report_date <= $transaction->report_date && $cert_receipt[$i]->report_date >= $transactions[0]->report_date): ?>
                                <?php if(strcasecmp($cert_receipt[$i]['items'][0]->nature, 'Certified Photocopy') != 0): ?>
                                    <tr>
                                        <td class="other_item"><?php echo e(\Carbon\Carbon::parse($cert_receipt[$i]->date_of_entry)->toFormattedDateString()); ?></td>
                                        <td class="other_item center"><?php echo e($cert_receipt[$i]->serial_no); ?></td>
                                        <td class="other_item"><?php echo e($cert_receipt[$i]['items'][0]->nature); ?></td>
                                        <td class="other_item" style="text-align: right"><?php echo e(number_format($cert_receipt[$i]['items'][0]->value,2)); ?></td>
                                        <?php 
                                            $total += $t->value; 
                                            $i++;
                                        ?>
                                    </tr>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <tr>
                            <td class="other_item"></td>
                            <td class="other_item"></td>
                            <td class="other_item"><?php echo e($t->nature); ?></td>
                            <td class="other_item" style="text-align: right"><?php echo e(number_format($t->value,2)); ?></td>
                            <?php $total += $t->value; ?>
                        </tr>
                    <?php endif; ?>
                <!-- </tr> -->
            
            <?php endforeach; ?>
         <?php endforeach; ?>

        <?php if(!is_null($OtherFeesCharges)): ?>
            <?php if(isset($OtherFeesCharges[$j])): ?>
                <?php if($OtherFeesCharges[$j]->fees_date >= $transaction->report_date && $OtherFeesCharges[$j]->fees_date >= $transactions[0]->report_date): ?>
                    <tr>
                        <td class="other_item"><?php echo e(\Carbon\Carbon::parse($OtherFeesCharges[$j]->fees_date)->toFormattedDateString()); ?></td>
                        <td class="other_item center"><?php echo e($OtherFeesCharges[$j]->or_number); ?></td>
                        <td class="other_item"><?php echo e($OtherFeesCharges[$j]->fees_charges); ?></td>
                        <td class="other_item" style="text-align: right"><?php echo e(number_format($OtherFeesCharges[$j]->ammount, 2)); ?></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php endif; ?>
                <?php $j++; ?>
            <?php endif; ?>
        <?php endif; ?>

         <tr>
             <td class="other_item"></td>
             <td class="other_item"></td>
             <td class="other_item"><b>Total Amount Paid</b><span style="float:right; font-weight: bold">Php</span></td>
             <td class="other_item" style="text-align: right"><b><?php echo e(number_format($total,2)); ?></b></td>
         </tr>
        </tbody>
    </table>

    <div class="blk">
        <span class="indent">This certification is issued upon the request of</span>
        <?php if(!is_null($cert->sand_requestor)): ?>
            <u><b><?php echo e($cert->sand_requestor); ?></b></u>
        <?php else: ?>
            <u><b><?php echo e($cert->recipient); ?></b></u>
        <?php endif; ?>
        <span>to support         
        <!-- his/her  -->
        <?php if($cert->sand_requestor_sex == 2 && $cert->sand_requestor_sex != ""): ?>
            <!-- his/her/<u>their</u> -->
            their
        <?php elseif($cert->sand_requestor_sex == 1 && $cert->sand_requestor_sex != ""): ?>
            <!-- <u>his</u>/her/their -->
            his
        <?php elseif($cert->sand_requestor_sex == 0 && $cert->sand_requestor_sex != ""): ?>
            <!-- his/<u>her</u>/their -->
            her
        <?php else: ?>
            his/her/their
        <?php endif; ?>
        
        application for renewal of sand and gravel extraction permit at <?php echo e($cert->address ? $cert->address : '_____'); ?>.</span>
    </div>
    <?php 
        $date = \Carbon\Carbon::parse($cert->date_of_entry);
        $date_now = \Carbon\Carbon::now();
        $cert_receipt_date = \Carbon\Carbon::parse($cert_or->date_of_entry);
     ?>
    <div class="blk">
        <span class="indent"> Issued this <?php echo e($date->format('jS')); ?> day of <?php echo e($date->format('F, Y')); ?> at La Trinidad, Benguet. </span>
    </div>

    <!-- <br><br><br> -->
    <table id="officers" style="padding-top: 33px;">
        <tr>
            <td></td>
            <td class="center"><b><?php echo e($cert->provincial_treasurer); ?></b></td>
        </tr>
        <tr>
            <td></td>
            <td class="center">Provincial Treasurer</td>
        </tr>
    </table>
    <br>
    <!-- <table id="detail" style="margin: 20px 0 0; font-size: 13px;"> -->
    <div>
        <table id="detail" style="font-size: 13px;" style="margin-top: -20px; page-break-inside: avoid;">
            <tr>
                <td>Certification Fee:</td>
                <td>50.00</td>
            </tr>
            <tr>
                <td>O.R. No.:</td>
                <td><?php echo e($receipts[0]->serial_no); ?></td>
            </tr>
            <tr>
                <td>Dated:</td>
                <td><?php echo e($cert_receipt_date->format('F j, Y')); ?></td>
            </tr>
        </table>
    </div>
</body>
</html>

