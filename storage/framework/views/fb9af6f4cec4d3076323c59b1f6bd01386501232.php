<!DOCTYPE html>
<html>
<head>
    <title>Certificate - Provincial Permit</title>
    <style>
        html,body{
            margin-bottom: 0px;
        }
         body {
            font-family: arial, "sans-serif";
            /*margin: 0px;*/
            margin-left: 38px;
            margin-right: 38px;
            font-size: 16px;
            text-align: justify;
        }
        #items {
            width: 80%;
            margin-right: auto;
            margin-left: auto;
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
            font-weight: bold;
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
        /*#cert {
            margin-top: 60px;
            margin-bottom: 30px;
            font-size: 18px;
            text-align: center;
            width: 100%;
        }*/
        .signatories{
            text-align: center;
            margin-top: 0cm;
        }
        #cert{
            margin-top: 90px;
            font-size: 18px;
            text-align: center;
            width: 100%;
        }
        .underline {
            border-bottom: 1px solid #000000;
        }
        .blk {
            margin-top: 7px;
        }
        .entity {
            text-decoration: underline;
            font-weight: bold;
        }
        #detail {
            width: 100%;
        }
        .ctr {
            text-align: center;
        }
        #items {
            width: 100%;
        }
        #items>thead>tr>th {
            font-size: 12px;
            text-align :center ;
        }
         #items>tbody>tr>td {
            font-size: 10px;
        }
        .val {
            text-align: right;
        }
        hr {
            margin-top:5px;
        }
        #ft {
            margin-top: 100px;

        }
        #conditions {
            /*top:23.8cm;*/
            /*margin-bottom: 0px;*/
            width: 100%;
            font-size: 9px;
            bottom: 4.3cm;
            position: fixed;
            /*padding-top: 15px;*/
            page-break-inside: avoid;
        }
        #conditions span {
            font-size: 11px;
        }
        .indent {
            padding-left: 30px;
        }
        #lines_business {
            width: 1000px;
            text-align: center;
        }
        #center_lines_business {
            font-size: 18px;
            text-align: center;
            width: 100%;
            margin-bottom: 0.25cm;
            margin-top: 0.20cm;
        }
        .add-padding>td{
            padding-top: 5px;
        }

        .black{
            background: #000;
            width: 20px;
            height: 20px;
            float:left;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <?php echo e(Html::image('/asset/images/benguet_capitol.png', "Logo", array('id' => 'logo'))); ?>

        <span class="header">Republic of the Philippines</span>
        <span class="header">PROVINCE OF BENGUET</span>
        <span class="header">La Trinidad</span>
        <span class="header"><strong>OFFICE OF THE PROVINCIAL GOVERNOR</strong></span>
    </div>
    <table id="header-dt">
        <tr>
            <td >Clearance Number:</td>
            <td class="underline" width="125"><strong><?php echo e($cert->provincial_clearance_number); ?></strong></td>
        </tr>
        <tr>
            <td></td>

            <td><div class="<?php echo ($cert->provincial_type == 'new' ?'black' : ''); ?>"></div>NEW</td>
        </tr>
        <tr>
            <td></td>
            <td><div class="<?php echo ($cert->provincial_type == 'renewal' ?'black' : ''); ?>"></div>RENEWAL</td>
        </tr>
    </table>
    <div id="cert">
        <b>PROVINCIAL PERMIT/CLEARANCE TO ENGAGE IN BUSINESS</b>
    </div>
    <div class="blk">
        <span class="indent">
            Pursuant to the provision of the Provincial Ordinance No. 15-176 series of 2015 of the
            Province of Benguet,
        </span>
        <span>
            clearance is hereby issued to <span class="entity"><?php echo e($cert->recipient); ?></span>
            with business address at <span class="entity"><?php echo e($cert->address); ?></span>
            to engage in the following line/s of business/occupation within the Province of Benguet.
        </span>
    </div>
    <div id="center_lines_business">
        <!-- <br /> -->
        <span id="lines_business"><b><u>LINE(S) OF BUSINESS</u></b></span>
    </div>
    </br>
    <div id="detail">
        <?php echo trim($cert->detail); ?>

    </div>
    <div class="blk">
        <span class="indent">
            This clearance shall take effect upon approval and will terminate on
            <?php echo e(date("F d, Y", strtotime("Last day of December", strtotime($cert->date_of_entry)))); ?>

            unless
        </span>
        <span>sooner revoked for cause or in the interest of the public.</span>
        <p class="indent">
        Issued this <span class="entity"><?php echo e($ordinal_date); ?></span>
        day of <span class="entity"><?php echo e(date('F', strtotime($cert->date_of_entry))); ?></span>,
        <span class="entity"><?php echo e(date('Y', strtotime($cert->date_of_entry))); ?></span>
        at La Trinidad, Benguet.
        </p>
    </div>
<br />
    <table class="signatories">
        <tr>
            <td style="width: 430px;">&nbsp;</td>
            <td class="underline" width="125" style="padding-bottom: 3px;">
            <?php if($cert->actingprovincial_governor != null): ?>
                <strong><?php echo e($cert->actingprovincial_governor); ?></strong>
            <?php else: ?>
                <?php /* <strong><?php echo e($cert->provincial_governor); ?></strong> */ ?>
                <strong><?php echo e(!is_null($prov_gov) ? $prov_gov->officer_name : $cert->provincial_governor); ?></strong>
            <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
            <?php if($cert->actingprovincial_governor != null): ?>
                Acting
            <?php endif; ?>
            Provincial Governor
            </td>
        </tr>
    </table>
    <?php if($cert->provincial_bidding == 1): ?>
        <span class="indent">*** For bidding purposes</span>
    <?php endif; ?>
    <hr>

    <table id="items">
    <thead>
        <tr>
            <th style="text-align: left;">TAXES/FEES/CHARGES</th>
            <th style="text-align: right;">AMOUNT</th>
            <th>OR NUMBER</th>
            <th>DATE</th>
            <th><!-- initials of user who input record --></th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: left;"><?php echo e($cert->provincial_note); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if(count($OtherFeesCharges) > 0): ?>
            <?php for($x = 0 ; $x < count($OtherFeesCharges) ; $x++): ?>
                <tr class="add-padding">
                    <td>
                    <?php   
                        $nnnx = str_replace("#br", "<br />&nbsp;&nbsp;", ucwords($OtherFeesCharges[$x]->fees_charges));
                        $nnnx = str_replace("__", "&nbsp;&nbsp;",  $nnnx );
                    ?>
                    &nbsp;&nbsp; <?php echo $nnnx; ?>

                    </td>
                    <td class="val"><?php echo e(number_format($OtherFeesCharges[$x]->ammount, 2)); ?></td>
                    <td class="ctr"><?php echo e($OtherFeesCharges[$x]->or_number); ?></td>
                    <td class="ctr" style="width: 120px; max-width: 120px;"><?php echo e(date('F d, Y', strtotime($OtherFeesCharges[$x]->fees_date))); ?></td>
                    <td>
                        <?php echo e($OtherFeesCharges[$x]->initials); ?>

                    </td>
                </tr>
            <?php endfor; ?>
        <?php endif; ?>

        <?php
            $Surcharge_Interest = 0;
            $Surcharge_Interest_1 = "";
            $Surcharge_Interest_2 = "";
            $Surcharge_Interest_3 = "";

            $values = [];
            foreach($receipts as $receipt) {
                if(isset($is_mncpal_cert)) {
                    foreach($receipt->getItems as $item) {
                        if( ucwords($item->nature) == 'Fixed Tax On Delivery (1 Unit)' ) {
                            
                        } else {
                            $nnn = str_replace("#br", "<br />&nbsp;&nbsp;", ucwords($item->nature));
                            $nnn = str_replace("__", "&nbsp;&nbsp;",  $nnn );
                            if(!isset($values[$receipt->serial_no][$nnn][date('F d, Y', strtotime($receipt->date_of_entry))])) {
                                $values[$receipt->serial_no][$nnn][date('F d, Y', strtotime($receipt->date_of_entry))] = 0;
                            }
                            
                            $values[$receipt->serial_no][$nnn][date('F d, Y', strtotime($receipt->date_of_entry))] += $item->value;
                        }
                    }
                } else {
                    foreach($receipt->items as $item) {
                        if( ucwords($item->nature) == 'Fixed Tax On Delivery (1 Unit)' ) {
                            
                        } else {
                            $nnn = str_replace("#br", "<br />&nbsp;&nbsp;", ucwords($item->nature));
                            $nnn = str_replace("__", "&nbsp;&nbsp;",  $nnn );
                            if(!isset($values[$receipt->serial_no][$nnn][date('F d, Y', strtotime($receipt->date_of_entry))])) {
                                $values[$receipt->serial_no][$nnn][date('F d, Y', strtotime($receipt->date_of_entry))] = 0;
                            }
                            
                            $values[$receipt->serial_no][$nnn][date('F d, Y', strtotime($receipt->date_of_entry))] += $item->value;
                        }
                    }
                }
            }
        ?>

        <?php foreach($values as $or_num => $data): ?>
            <?php foreach($data as $nature => $data2): ?>
            <?php foreach($data2 as $date => $amt): ?>
            <?php
                $check_si = strcmp('Surcharge & Interest',ucwords($item->nature));
            ?>
                <?php if(  $check_si <= 0 && $check_si >= -2  ): ?>
                    <?php 
                        $Surcharge_Interest += $item->value;
                        $Surcharge_Interest_1 = $receipt->serial_no;
                        $Surcharge_Interest_2 = date('F d, Y', strtotime($receipt->date_of_entry));
                        $Surcharge_Interest_3 = $initials;
                     ?>
                <?php else: ?>
                <?php endif; ?> 
                <?php if(preg_match('/Annual Fee/i', $nature) == 1 || preg_match('/Fixed Tax/i', $nature) == 1 || preg_match('/Permit Fee/i', $nature) == 1): ?>
                    <tr>
                        <td>
                            &nbsp;&nbsp; <?php echo $nature; ?> 
                        </td>
                        <!-- <td class="val"><?php /* number_format($item->value, 2) */ ?></td>
                        <td class="ctr"><?php /* $receipt->serial_no */ ?></td>
                        <td class="ctr"><?php /* date('F d, Y', strtotime($receipt->date_of_entry)) */ ?></td>
                        <td><?php /* $initials */ ?></td> -->
                        <td class="val"><?php echo e(number_format($amt,2)); ?></td>
                        <td class="ctr"><?php echo e(isset($is_mncpal_cert) ? $receipt->rcpt_no : $or_num); ?></td>
                        <td class="ctr"><?php echo e(isset($is_mncpal_cert) ? \Carbon\Carbon::parse($receipt->rcpt_date)->format('F d, Y') : $date); ?></td>
                        <td>     
                            <?php if(strcasecmp($initials, 'A') == 0 && $item->col_acct_title_id == 18 && isset($is_mncpal_cert)): ?>
                                MTO/A
                            <?php else: ?>                    
                                <?php echo e($initials); ?>

                            <?php endif; ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php continue; ?>
                <?php endif; ?>
            <?php endforeach; ?>       
        <?php endforeach; ?>
      <?php endforeach; ?>

      <?php foreach($values as $or_num => $data): ?>
            <?php foreach($data as $nature => $data2): ?>
            <?php foreach($data2 as $date => $amt): ?>
            <?php
                $check_si = strcmp('Surcharge & Interest',ucwords($item->nature));
            ?>
           <?php if(  $check_si <= 0 && $check_si >= -2  ): ?>
                <?php 
                    $Surcharge_Interest += $item->value;
                    $Surcharge_Interest_1 = $receipt->serial_no;
                    $Surcharge_Interest_2 = date('F d, Y', strtotime($receipt->date_of_entry));
                    $Surcharge_Interest_3 = $initials;
                 ?>
            <?php else: ?>
            <?php endif; ?> 
                <?php if(preg_match('/Annual Fee/i', $nature) != 1 && preg_match('/Fixed Tax/i', $nature) != 1 && preg_match('/Permit Fee/i', $nature) != 1): ?>
                    <tr>
                        <td>
                            &nbsp;&nbsp; <?php echo '- '.$nature; ?> 
                        </td>
                        <!-- <td class="val"><?php /* number_format($item->value, 2) */ ?></td>
                        <td class="ctr"><?php /* $receipt->serial_no */ ?></td>
                        <td class="ctr"><?php /* date('F d, Y', strtotime($receipt->date_of_entry)) */ ?></td>
                        <td><?php /* $initials */ ?></td> -->
                        <td class="val"><?php echo e(number_format($amt,2)); ?></td>
                        <td class="ctr"><?php echo e(isset($is_mncpal_cert) ? $receipt->rcpt_no : $or_num); ?></td>
                        <td class="ctr"><?php echo e(isset($is_mncpal_cert) ? \Carbon\Carbon::parse($receipt->rcpt_date)->format('F d, Y') : $date); ?></td>
                        <td>
                            <?php if(strcasecmp($initials, 'A') == 0 && $item->col_acct_title_id == 18 && isset($is_mncpal_cert)): ?> 
                                MTO/A
                            <?php else: ?>
                                <?php echo e($initials); ?>

                            <?php endif; ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php continue; ?>
                <?php endif; ?>
            <?php endforeach; ?>       
        <?php endforeach; ?>
        <?php endforeach; ?>

      <!-- <?php if($Surcharge_Interest>0): ?>
          <tr>

                            <td>
                                   &nbsp;&nbsp;&nbsp;&nbsp; - Surcharge & Interest
                            </td>
                            <td class="val"><?php echo e(number_format($Surcharge_Interest, 2)); ?></td>
                            <td class="ctr"><?php echo e($Surcharge_Interest_1); ?></td>
                            <td class="ctr"><?php echo e($Surcharge_Interest_2); ?></td>
                            <td><?php echo e($Surcharge_Interest_3); ?></td>
                        </tr>
      <?php endif; ?> -->

        </tbody>
    </table>

    <!-- <div> -->
        <table class="signatories">
            <tr>
                <td style="width: 460px;">&nbsp;</td>
                <td class="underline" width="125" style="padding-bottom: 3px;">
                    <br />
                    <br />
                    <strong><?php echo e($cert->provincial_treasurer); ?></strong>
                </td>
            </tr>
            <tr>
                <td style="width: 460px;">&nbsp;</td>
                <td colspan="2">Provincial Treasurer</td>
            </tr>
            <?php if($cert->asstprovincial_treasurer !== null): ?>
            <tr>
                <td><br /></td>
                <td><br></td>
            </tr>
            <tr>
                <td></td>
                <td class="underline" width="125" style="padding-bottom: 3px;">
                    <?php echo e($cert->asstprovincial_treasurer); ?>

                </td>
            </tr>
            <tr>
                <td></td>
                <td>Assistant Provincial Treasurer</td>
            </tr>
            <?php endif; ?>
            <!-- <br/> <br /> -->
        </table>
    <!-- </div> -->

    <div>
        <table id="conditions">
            <tr>
                <td></td>
                <td>
                <span class="entity">
                CONDITIONS FOR THE VALIDITY OF THIS PERMIT
                </span>
                </td>
            </tr>
            <tr>
                <td>1.</td>
                <td>
                This permit/clearance is not valid if not signed by the Provincial Governor
                and the Provincial Treasurer.
                </td>
            </tr>
            <tr>
                <td>2.</td>
                <td>
                This permit/clearance must be displayed in a conspicuous place within
                the business establishment.
                </td>
            </tr>
            <tr>
                <td>3.</td>
                <td>
                This permit/clearance is not valid if there is any unsigned, alteration, addition or
                erasures in it or taxes, fees charges are not paid for, as required under existing
                ordinance.
                </td>
            </tr>
            <tr>
                <td>4.</td>
                <td>
                This permit/clearance is subject to the compliance by permittee to all existing laws,
                ordinances, rules & regulations on the business, trade or profession.
                </td>
            </tr>
            <tr>
                <td>5.</td>
                <td>
                Permittee shall notify the Office of the Provincial Treasurer not later than the date of
                retirement and surrender this permit including all previous permits issued to him/her
                upon discontinuance or retirement from business, trade, or profession herein permitted.
                </td>
            </tr>
            <tr>
                <td>6.</td>
                <td>
                This permit/clearance shall be renewed within the first twenty (20) days of January following
                its expiration provided above.
                </td>
            </tr>
            <tr>
                <td>7.</td>
                <td>
                THIS PERMIT IS NON-TRANSFERABLE AND NON-ASSIGNABLE.
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
