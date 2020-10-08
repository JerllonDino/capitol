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
            margin-top: 60px;
            margin-bottom: 30px;
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
        .blank-line {
            width: 200px;
            border-bottom: 1px solid #000000;
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
    <table id="header-dt">
        <tr>
            <td></td>
            <td class="underline" width="125"><?php echo e(date('F d, Y', strtotime($cert->date_of_entry))); ?></td>
        </tr>
        <tr>
            <td></td>
            <td>Date</td>
        </tr>
    </table>
    <div id="cert">
        <b>C E R T I F I C A T I O N</b>
    </div>
    <div class="blk">
        <span class="indent">THIS IS TO CERTIFY that</span>
        <?php if(isset($cert->recipient) &&  $cert->recipient != ''): ?>
            <u><b><?php echo e($cert->recipient); ?></b></u>
        <?php else: ?>
            <u><b><?php echo e($receipts[0]->customer->name); ?></b></u>
        <?php endif; ?>
        
        <span>contractor of</span>
        <?php if($cert->address != ''): ?>
            <u><b><?php echo e($cert->address); ?></b></u>
        <?php elseif(isset($cert->sand_requestor_addr) &&  $cert->sand_requestor_addr != ''): ?>
            <u><b><?php echo e($cert->sand_requestor_addr); ?></b></u>
        <?php else: ?>
            <div class="blank-line" style="display: inline-block;"></div>
        <?php endif; ?>
        <span>has paid the following obligation on the following contracts:</span>
    </div>
    <div class="blk">
        <?php
            $trim = trim(str_replace('&nbsp;', "", $cert->detail));
        ?>
        <?php if($cert->detail != ""): ?>
            <span><u><?php echo $trim; ?></u></span>
        <?php endif; ?>
    </div>
    <?php
        $cert_sandgravelprocessed = 0;
        $cert_abc = 0;
        $cert_sandgravel = 0;
        $cert_boulders = 0;
    ?>

    <table id="items" style="padding-top: 30px;">
        <?php
            $total = 0;
        ?>
        <?php foreach($receipts as $receipt): ?>
        <tr>
            <td><b>Under Official Receipt No.</b></td>
            <td class="center underline" colspan="2"><?php echo e($receipt->serial_no); ?></td>
            <?php /* <td></td> */ ?>
            <?php /* <td colspan="3"><b style="display: inline;">Under Official Receipt No.</b> <p class="center underline" style="display: inline;" width="100"><?php echo e($receipt->serial_no); ?></p></td> */ ?>
        </tr>
        <tr>
            <td><b>Dated</b></td>
            <td class="center underline" colspan="2"><?php echo e(date('F d, Y', strtotime($receipt->date_of_entry))); ?></td>
            <?php /* <td></td> */ ?>
            <?php /* <td colspan="3"><b style="display: inline;">Dated</b> <p class="center underline" style="display: inline;" width="100"><?php echo e(date('F d, Y', strtotime($receipt->date_of_entry))); ?></p></td> */ ?>
        </tr>
        <tr>
            <td><b>Sand and gravel Tax:</b></td>
            <td></td>
            <td></td>
        </tr>
<?php /* dd($receipt->items) */ ?>
        <?php
                $cert_sandgravelprocessedx = 0;
                $cert_sandgravelprocessedxx = 0;
                $cert_abcx = 0;
                $cert_abcxx = 0;
                $cert_sandgravelx = 0;
                $cert_sandgravelxx = 0;
                $cert_bouldersx = 0;
                $cert_bouldersxx = 0;
        ?>
        <?php foreach($receipt->items as $item): ?>
            <?php if($item->id == 123141): ?> 
                <?php /* dd($item->detail) */ ?>
            <?php endif; ?>
            <?php if(isset($item->detail) ): ?>
                <?php
                    $sg_array = array('158','159','160','161');
                        $show_sg = true;
                        // if ($item->detail->col_collection_rate_id ==  158 || preg_match('/ABC/', $item->nature) || preg_match('/SBBC/', $item->nature) || preg_match('/ABC/', $item->detail->label) || preg_match('/SBBC/', $item->detail->label)){
                        if (preg_match('/ABC/', $item->detail->label) || preg_match('/SBBC/', $item->detail->label)){
                            $cert_abc += $item->value/$item->detail->value; 
                            $show_sg = false;
                        }
                        // elseif ($item->detail->col_collection_rate_id == 159 || preg_match('/sand and gravel/i', $item->nature) || preg_match('/sand/iA', $item->nature) || preg_match('/gravel/iA', $item->nature) || preg_match('/river/i', $item->nature) || 
                        //     preg_match('/sand and gravel/i', $item->detail->label) || preg_match('/sand/iA', $item->detail->label) || preg_match('/gravel/iA', $item->detail->label) || preg_match('/river/i', $item->detail->label)){
                        elseif (preg_match('/sand and gravel/i', $item->detail->label) || preg_match('/sand/iA', $item->detail->label) || preg_match('/gravel/iA', $item->detail->label) || preg_match('/river/i', $item->detail->label)){
                            $cert_sandgravel += $item->value/$item->detail->value; 
                            $show_sg = false;
                        }
                        elseif ($item->detail->col_collection_rate_id ==  160 || preg_match_all('/boulders/i', $item->nature) ||
                            preg_match_all('/boulders/i', $item->detail->label)){
                            $cert_boulders += $item->value/$item->detail->value; 
                            $show_sg = false;
                        } 
                        // elseif ($item->detail->col_collection_rate_id ==  161 || preg_match('/crushed/i', $item->nature) || preg_match('/processed/i', $item->nature) || preg_match('/washed/i', $item->nature)){
                        elseif ($item->detail->col_collection_rate_id ==  161 || preg_match('/\b gravel and sand/i', $item->nature) || preg_match('/\b gravel/i', $item->nature) || preg_match('/\b sand/i', $item->nature)) {
                              $cert_sandgravelprocessed += $item->value/$item->detail->value; 
                              $show_sg = false;
                        }
                          
                        $cert_sandgravelprocessedx = $cert_sandgravelprocessed + $cert->sand_sandgravelprocessed;
                        $cert_sandgravelprocessedxx = $cert_sandgravelprocessedx - $cert->sand_sandgravelprocessed;
       
                        $cert_abcx = $cert_abc + $cert->sand_abc;
                        $cert_abcxx = $cert_abcx - $cert->sand_abc;
                  
                        $cert_sandgravelx = $cert_sandgravel + $cert->sand_sandgravel;
                        $cert_sandgravelxx = $cert_sandgravelx - $cert->sand_sandgravel;

                        $cert_sandgravelx = $cert_sandgravel + $cert->sand_sandgravel;
                        $cert_sandgravelxx = $cert_sandgravelx  - $cert->sand_sandgravel;

                        $cert_bouldersx = $cert_boulders  + $cert->sand_boulders;
                        $cert_bouldersxx = $cert_bouldersx - $cert->sand_boulders;
                        
                       
                        $total += $item->value ;
                        
                ?>
              
                <tr>
                    <td class="">
                        <b><?php echo e($item->nature); ?></b>
                    </td>
                    <td class="center underline " style="text-align: right;">
                        
                    </td>
                    <td class="val underline">
                      <?php echo e(number_format($item->value, 2)); ?>

                    </td>
                </tr>
            <?php else: ?>
                <tr>
                    <td class="">
                        <b><?php echo e($item->nature); ?></b>
                    </td>
                    <td class="center underline"  style="text-align: right;">PHP</td>
                    <td class="val underline">
                    <span class="hidden">
                    <?php echo e($total += $item->value); ?>

                    </span>
                    <?php echo e(number_format($item->value, 2)); ?>

                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php endforeach; ?>
        <tr>
            <td><b>Total Amount Paid</b></td>
            <td  style="text-align: right;">PHP</td>
            <td class="val " style="border-bottom: 3px double #000;"><?php echo e(number_format($total, 2)); ?></td>
        </tr>
    </table>
    <br><br><br>
    <table id="officers">
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
    <table id="detail" style="margin: 80px 0 0;">
        <tr id="detail-head">
            <th width="40%"></th>
            <th>Sand and Gravel Processed <br /><span class="small-font">(in CU.M)</span></th>
            <th>Aggregate Base Course/SBBC <br /><span class="small-font">(in CU.M)</span></th>
            <th>River Gravel/Sand <br /><span class="small-font">(in CU.M)</span></th>
            <th>Boulders <br /><span class="small-font">(in CU.M)</span></th>
        </tr>
        <tr>
            <td>Total Volume as per Approved Agency Estimate</td>
            <td class="underline val">
            <?php echo e(number_format( $cert_sandgravelprocessedx ,2)); ?>

            </td>
            <td class="underline val">
            <?php echo e(number_format( $cert_abcx,2)); ?>

            </td>
            <td class="underline val">
            <?php
                $x_cert_sandgravelx = number_format( $cert_sandgravelx ,3);
                $x_cert_sandgravelx = explode('.', $x_cert_sandgravelx );
            ?>
            <?php echo e($x_cert_sandgravelx[0].'.'.substr($x_cert_sandgravelx[1],0,2)); ?>

            </td>
            <td class="underline val">
            
            <?php echo e(number_format( $cert_bouldersx ,2)); ?>

            </td>
        </tr>
        <tr>
            <td>Less: Supported by Official Receipt</td>
            <td class="underline val"><?php echo e(number_format($cert->sand_sandgravelprocessed, 2)); ?></td>
            <td class="underline val"><?php echo e(number_format($cert->sand_abc, 2)); ?></td>
            <td class="underline val">
            <?php
                $y_cert_sandgravelx = number_format( $cert->sand_sandgravel ,3);
                $y_cert_sandgravelx = explode('.', $y_cert_sandgravelx );
            ?>
            <?php echo e($y_cert_sandgravelx[0].'.'.substr($y_cert_sandgravelx[1],0,2)); ?>


            </td>
            <td class="underline val"><?php echo e(number_format($cert->sand_boulders, 2)); ?></td>
        </tr>
        <tr>
            <td>Balance</td>
            <!-- 161 --><td class="underline val"><?php echo e(number_format($cert_sandgravelprocessedxx, 2)); ?> </td>
            <!-- 158 --><td class="underline val"><?php echo e(number_format($cert_abcxx, 2)); ?> </td>
            <!-- 159 --><td class="underline val"> 

            <?php
                    $z_cert_sandgravelx = number_format( $cert_sandgravelxx ,3);
                    $z_cert_sandgravelx = explode('.', $z_cert_sandgravelx );
            ?>
            <?php echo e($z_cert_sandgravelx[0].'.'.substr($z_cert_sandgravelx[1],0,2)); ?>

                </td>

            <!-- 160 --><td class="underline val"><?php echo e(number_format($cert_bouldersxx, 2)); ?> </td>

        </tr>
    </table>
</body>
</html>

