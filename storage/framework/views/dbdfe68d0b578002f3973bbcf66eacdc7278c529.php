<!DOCTYPE html>
<html>
<head>
    <title>Certificate - Provincial Permit</title>
    <style>
        body {
            font-family: arial, "sans-serif";
            /*margin: 0px;*/
            margin-left: 45px;
            margin-right: 30px;
            text-align: justify;
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
            width: 87%;
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
        }

        .forinabsence{
            padding-left: 0 !important;
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
            /*top: 73%;*/
            bottom: 15%;
            margin-left: 45px;
            margin-right: 30px;
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
        .dets {
            text-align: center;
            /*font-size: 12px;*/
            line-height: 107%;
            font-family: Verdana, sans-serif;
            font-weight: bold;
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
        <span class="header">Telephone Number (074) 422-56-57</span>
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
    <div class="blk" style="text-align: justify-all;">
        <span class="indent">THIS IS TO CERTIFY that</span>
        <u><b><?php echo e($cert->recipient); ?></b></u>
        <span>has paid the Transfer Tax on the following property/properties, to wit:</span>
    </div>
    <div class="blk dets">
        <?php
            $details = preg_replace('/<[^>]*>*/', "", $cert->detail); 
        ?>
        <?php echo nl2br($details); ?>

    </div>
    <table id="items">
        <span class="hidden">
        <?php echo e($total = 0); ?>

        </span>
       <?php foreach($receipts as $receipt): ?>
            <tr>
                <td colspan="3"><b>Official Receipt No.</b> <u><?php echo e($receipt->serial_no); ?> (dated <?php echo e(\Carbon\Carbon::parse($receipt->date_of_entry)->format('M')); ?>. <?php echo e(\Carbon\Carbon::parse($receipt->date_of_entry)->format('d, Y')); ?>)</u></td>
                <!-- <td class="center underline" colspan="2"><?php /* $receipt->serial_no */ ?> (dated <?php /* \Carbon\Carbon::parse($receipt->date_of_entry)->format('M') */ ?>. <?php /* \Carbon\Carbon::parse($receipt->date_of_entry)->format('d, Y') */ ?>)</td> -->
                <!-- <td></td> -->
            </tr>
            <?php $count=0; ?>
        <?php foreach($receipt->items as $item): ?>
            <tr>
                <td>
                    <b><?php echo e($item->nature); ?></b>
                </td>
                <td class="center"><?php echo e(($count==0)? 'PHP' : ''); ?></td>
                <td class="val">
                <span class="hidden">
                <?php echo e($total += $item->value); ?>

                </span>
                <?php echo e(number_format($item->value, 2)); ?>

                </td>
            </tr>
        <?php $count++; ?>
        <?php endforeach; ?>
       <?php endforeach; ?>
        <tr>
            <td><b>Total</b></td>
            <td class="center">PHP</td>
            <td class="val double-border"><?php echo e(number_format($total, 2)); ?></td>
        </tr>
    </table>
    <!-- <div class="blk">
        <span class="indent">
        This certification is hereby issued for purposes of authentication and genuineness of the Official Receipt.
        </span>
    </div> -->
    <br>

    <?php
        $by = 'by';
        $class_x = '';
               if($cert->signee == 'forinabsence'){
                    $by = 'For and in the absence of the Provincial Treasurer ';
                    $class_x = 'forinabsence';
                } 
        ?>
    <table id="officers" class="<?=$class_x?>">
        <tr>
            <td class="title" style="text-align: right;">Prepared by:</td>
            <td class="center"></td>
        </tr>
        <tr>
            <td></td>
            <td class="center" ><b><?php echo e($cert->transfer_prepare_name); ?></b></td>
        </tr>
        <tr>
            <td></td>
            <td class="center" style="width: 170px;"><?php echo $cert->transfer_prepare_position; ?></td>
        </tr>
        <tr>
            <td class="title" style="width: 250px; text-align: right;">Authenticated by:</td>
            <td class="center"></td>
        </tr>
        <tr>
            <td></td>
            <td class="center"><b><?php echo e($cert->provincial_treasurer); ?></b></td>
        </tr>
        <tr>
            <td></td>
            <td class="center" >Provincial Treasurer</td>
        </tr>
        <?php if($cert->asstprovincial_treasurer !== null): ?>

        
        <tr>
            <td class="title" style="text-align: right;"><?php echo e($by); ?>:</td>
            <td class="center"></td>
        </tr>
        <tr>
            <td></td>
            <td class="center"><b><?php echo e($cert->asstprovincial_treasurer); ?></b></td>
        </tr>
            <?php if($cert->asstprovincial_treasurer_position == null): ?>
            <tr>
                <td></td>
                <td class="center">Assistant Provincial Treasurer</td>
            </tr>
            <?php else: ?>
                <tr>
                <td></td>
                    <td class="center"><?php echo $cert->asstprovincial_treasurer_position; ?></td>
            </tr>
            <?php endif; ?>
        <?php endif; ?>
    </table>


   <table class="bottom2">
        <tr>
            <td style="width:100px; ">Notary Public:</td>
            <td class="text-right " style="max-width: 500px; width: 500px;text-indent: 12px;"><?php echo $cert->transfer_notary_public; ?></td>
        </tr>
        <tr>
            <td>PTR Number:</td>
            <td class="text-right " style="text-indent: 12px; width: 500px;"><b><u><?php echo e($cert->transfer_ptr_number); ?></u></b></td>
        </tr>
        <?php if($cert->transfer_ref_num): ?>
        <tr>
            <td>Reference No:</td>
            <td class="text-right " style="text-indent: 12px; width: 500px;"><b><u><?php echo e($cert->transfer_ref_num); ?></u></b></td>
        </tr>
        <?php endif; ?>
        <tr>
            <td>Doc. No.</td>
            <td class="text-right " style="text-indent: 12px;"><b><u><?php echo e($cert->transfer_doc_number); ?>;</u></b></td>
        </tr>
        <tr>
            <td>Page No.</td>
            <td class="text-right " style="text-indent: 12px;"><b><u><?php echo e($cert->transfer_page_number); ?>;</u></b></td>
        </tr>
        <tr>
            <td>Book No.</td>
            <td class="text-right " style="text-indent: 12px;"><b><u><?php echo e($cert->transfer_book_number); ?>;</u></b></td>
        </tr>
        <tr>
            <td>Series of</td>
            <td class="text-right " style="text-indent: 12px;"><b><u><?php echo e($cert->transfer_series); ?>.</u></b></td>
        </tr>
    </table>
</body>
</html>