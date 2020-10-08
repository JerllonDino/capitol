<!DOCTYPE html>
<html>
<head>
    <title>ACCOUNTS REPORT</title>
    <?php echo e(Html::style('/bootstrap-3.3.6/css/bootstrap.min.css')); ?>

    <style type="text/css">
        html {
            margin-bottom: 8px;
            margin-top: 0.5in;
            margin-left: 3px;
            margin-right: 3px;
        }
        /* class works for table row */
        table tr.page-break{
          page-break-after:always
        }


        /* class works for table */
        table.page-break{
          page-break-after:always
        }

         table tfoot tr.page-break-before{
                page-break-after: always;
         }

        @media  print {
         .page-break  { display: block; page-break-before: always; }
        }
         .center {
                width: 450px;
                text-align: center;
                margin: 10px auto;
                font-size: 11px;
        }

           .image_logo{
                width: 80px;
            }

            .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th{
            border : 1px solid #000;
            padding: 1px;
            vertical-align: middle;
        }


      td.total_group {
            border-top: 3px double #000 !important;
            border-bottom: 2px solid #000 !important;
            font-size: 14px;
            font-weight: bold;
        }
      td.total_categ{
            border-top: 3px double #1d60ef !important;
            border-bottom: 2px solid #1d60ef !important;
            background: #f5f5f5;
           font-size: 16px;
            font-weight: bold;

        }

        .header,
        .footer {
            width: 100%;
            text-align: center;
        }
        .header {
             position: fixed;
            top: 0px;
            min-height: 250px;
        }
        .footer {
            bottom:15px;
            position: fixed;
        }
        .pagenum:before {
            content: counter(page);
        }


        .theader>tbody>tr>td{
            border: none;
        }


    </style>
</head>
<body>

<div class="header">
    <table class="table theader" border="0" width="100%" cellpadding="0" style="border-collapse: collapse;">
    <tr>
        <td align="right" width="25%"><img src="<?php echo e(asset('asset/images/benguet_capitol.png')); ?>" style="height: 70px; width: 70px; "></td>
        <td align="left" width="45%">
             <table border="0" style="width: 100%; border-collapse: collapse;" align="center" cellpadding="0">
                <tbody>
                    <tr><td style="text-align: center; font-family: britannic"><strong>REPUBLIC OF THE PHILIPPINES</strong></td></tr>
                    <tr><td style="text-align: center;">PROVINCE OF BENGUET</td></tr>
                    <tr><td style="text-align: center; font-weight: bold;">OFFICE OF THE PROVINCIAL TREASURER</td></tr>
                </tbody>
            </table>
        </td>
        <td width="25%"></td>
    </tr>
</table>

</div>

<div class="footer">
    Page <span class="pagenum"></span>
</div>



 <div class="col-sm-12" style="top:70px;margin-top:10px;" >
<h4 class="text-center">MONTHLY COLLECTIONS OF AMUSEMENT TAX <br /><br>
For the Month of &nbsp;<?php echo e($datex->format('F Y')); ?></h4>

 <?php $totalx = []; ?>

<table class="table table-bordered" style="width: 100%; margin: 0 auto;" >
        <thead>
            <tr>
            <th rowspan="2" class="text-center">DATE</th>
            <?php foreach($mcpal as $key => $value): ?>
                <?php if($p_tax['gtotal_ptax'] > 0): ?>
                    <th colspan="2" class="text-center">
                        <?php foreach($value as $keyx => $valuex): ?>
                           <?php echo e($keyx); ?>

                        <?php endforeach; ?>
                        , <?php echo e($key); ?>

                    </th>
                <?php else: ?>
                    <th colspan="1" class="text-center">
                        <?php foreach($value as $keyx => $valuex): ?>
                           <?php echo e($keyx); ?>

                        <?php endforeach; ?>
                        , <?php echo e($key); ?>

                    </th> 
                <?php endif; ?>
            <?php endforeach; ?>
            <th rowspan="2" class="text-center">Total</th>
            </tr>

            <tr>
            <?php foreach($mcpal as $key => $value): ?>
              <?php foreach($value as $keyx => $valuex): ?>
                <?php
                    if(!isset($totalx[$keyx][$key])){
                            $totalx[$keyx][$key]['AmusementTax'] = 0;
                            $totalx[$keyx][$key]['ProvincialPermit'] = 0;
                    }
                ?>
                <?php if($p_tax['gtotal_ptax'] > 0): ?>
                    <th class="text-center">Permit Fees</th>
                <?php endif; ?>
                <th class="text-center"> Amusement Tax</th>
                <?php endforeach; ?>
            <?php endforeach; ?>

            </tr>

        </thead>
        <tbody>
         <?php foreach($receipts as $key => $daymnth): ?>
         <?php $total[$key] = 0; ?>
                <tr>
                    <td><?php echo e($key); ?></td>
                    <?php foreach($mcpal as $mkey => $mvalue): ?>
                        <?php foreach($mvalue as $mbkey => $mbvalue): ?>
                            <?php if(isset( $daymnth[$mkey][$mbvalue] ) ): ?>
                                <?php if($p_tax['gtotal_ptax'] > 0): ?>
                                    <td class="text-right"><?php echo e(number_format($p_tax[$key][$mkey][$mbvalue]['p_tax'],2)); ?></td>
                                <?php endif; ?>
                                <td class="text-right"><?php echo e(number_format($p_tax[$key][$mkey][$mbvalue]['a_tax'],2)); ?></td>
                                    <?php
                                        $totalx[$mbkey][$mkey]['AmusementTax'] += $p_tax[$key][$mkey][$mbvalue]['a_tax'];
                                        $totalx[$mbkey][$mkey]['ProvincialPermit'] += $p_tax[$key][$mkey][$mbvalue]['p_tax'];
                                        $total[$key] += $p_tax[$key][$mkey][$mbvalue]['a_tax'] + $p_tax[$key][$mkey][$mbvalue]['p_tax']  ;
                                    ?>
                             <?php else: ?>
                                <?php if($p_tax['gtotal_ptax'] > 0): ?>
                                    <td></td>
                                <?php endif; ?>
                                <td></td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                     <?php endforeach; ?>
                     <td  class="text-right"><?php echo e(number_format($total[$key],2)); ?></td>
                </tr>
          <?php endforeach; ?>


        </tbody>

        <tfoot>
            <tr>
                <th>TOTAL</th>
                  <?php foreach($mcpal as $key => $value): ?>
                    <?php foreach($value as $keyx => $valuex): ?>
                        <?php if($p_tax['gtotal_ptax'] > 0): ?>
                            <th class="text-right"> <?php echo e(number_format($totalx[$keyx][$key]['ProvincialPermit'],2)); ?></th>
                        <?php endif; ?>
                        <th class="text-right"> <?php echo e(number_format($totalx[$keyx][$key]['AmusementTax'],2)); ?></th>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <th  class="text-right"><?php echo e(number_format( array_sum($total) , 2 )); ?></th>
            </tr>

        </tfoot>



</table>

<br />
    <table style="margin-left: 0px; margin-top: 20px;">
        <tr>
            <td style=" width: 10%">Prepared by:</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2" style="text-align: center;"><strong><?php echo e((strtoupper($acctble_officer_name->value))); ?></strong></td>

        </tr>

        <tr>
            <td ></td>
            <td colspan="2" style="text-align: center; "><?php echo e($acctble_officer_position->value); ?></td>

        </tr>


    </table>

            </div>

</body>

</html>
