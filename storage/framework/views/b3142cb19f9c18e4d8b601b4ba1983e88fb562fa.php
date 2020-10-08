<!DOCTYPE html>
<html>
<head>
    <title>SAND and GRAVEL </title>
    <style>
        @page  { 
            /*margin: 0.25in 15px; */
            margin-top: .75in;
            margin-bottom: 0.25in;
            margin-left: 15px;
            margin-right: 15px;
        }
        body {
            margin: 0px 0px;
            font-family: arial, "sans-serif";
            font-size: 8.5;
        }


         .center {
                width: 325px;
                text-align: center;
                margin: 10px auto;
        }

        .image_logo{
                width: 60px;
            }
        .right {
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, td {
            padding: 2px;
        }
        thead{
            font-weight: bold;
            text-align: center;
        }

        .table,.table>thead>tr>th,.table>tbody>tr>td{
            border:1px solid #000;
        }
        .text-center{
            text-align: center;
        }

        .text-right{
            text-align: right;
        }


        #sand_gravel_share{
            border: 2px solid #000;
        }

        #sand_gravel_share > thead > tr > th,#sand_gravel_share > tbody > tr > td,#sand_gravel_share > tfoot > tr > th{
            border-right: 2px solid #000;
            border-left: 2px solid #000;
        }

        #sand_gravel_share > thead > tr > th{
            border: 2px solid #000;
        }

        #sand_gravel_share > tfoot > tr > th{
            border: 2px solid #000;
            border-bottom: 3px solid #000;
        }

        #sand_gravel_share>thead>tr>th,#sand_gravel_share>tbody>tr>td{
            font-size: 12px;
            padding: 2px;
        }

        .table-no-bordered, .table-no-bordered>thead>tr>th, .table-no-bordered>tbody>tr>td{
                border:none !important;
        }

    </style>
</head>
<body>
    <?php
        $report_month = $datex;
        $prev_month_date = \Carbon\Carbon::createFromDate($datex->format('Y'), $datex->format('m')-1);
    ?>
    <table class="center">
        <tr>
        <td style="width:10px; padding:0px;">
            <img src="<?php echo e(asset('asset/images/benguet-logo.png')); ?>" class="image_logo" alt/>
        </td>
        <td style=" width: 230px">
        REPUBLIC OF THE PHILIPPINES<br />
        BENGUET PROVINCE<br />
        La Trinidad<br/>
        <strong>OFFICE OF THE PROVINCIAL TREASURER</strong>
        </td>
        </tr>
    </table>

    <center><strong>SAND AND GRAVEL TAXES/PENALTIES SHARING<br /><br /> <u> <?php echo e(strtoupper($datex->format('F'))); ?> 01-<?php echo e(strtoupper($datex->endOfMonth()->format('d'))); ?>,  <?php echo e($year); ?></u></strong></center><br />

<table class="table table-condensed table-bordered">
    <thead>
        <tr>
            <th>MUNICIPALITY</th>
            <th>Brgy</th>
            <th>PROVINCIAL SHARE</th>
            <th>MUNICIPAL SHARE</th>
            <th>BRGY SHARE</th>
            <th>TOTALS</th>
        </tr>
    </thead>

    <tbody>
        <?php
            $totals_mpb = [];
            $totals_prv = $totals_mun  = $totals_bgy =   $totals = 0;
            $per_client_summary = [];
        ?>
        <!-- previous months total's -->
        <?php if(!empty($prev_month) && $datex->format('m') != 1): ?>
            <tr>
                <td style="border-top: 2px solid #000 !important; border-bottom: 2px solid #000 !important;"><strong>As of <?php echo e($prev_month_date->format('F')); ?> <?php echo e($year); ?></strong></td>
                <td style="border-top: 2px solid #000 !important; border-bottom: 2px solid #000 !important;"></td>
                <td style="text-align: right; border-top: 2px solid #000 !important; border-bottom: 2px solid #000 !important;"><?php echo e(isset($prev_month['provincial_value']) ? number_format($prev_month['provincial_value'],2) : 0.00); ?></td>
                <td style="text-align: right; border-top: 2px solid #000 !important; border-bottom: 2px solid #000 !important;"><?php echo e(isset($prev_month['municipal_value']) ?  number_format($prev_month['municipal_value'], 2) : 0.00); ?></td>
                <td style="text-align: right; border-top: 2px solid #000 !important; border-bottom: 2px solid #000 !important;"><?php echo e(isset($prev_month['brgy_value']) ? number_format($prev_month['brgy_value'], 2) : 0.00); ?></td>
                <td style="text-align: right; border-top: 2px solid #000 !important; border-bottom: 2px solid #000 !important;"><?php echo e(number_format(array_sum($prev_month), 2)); ?></td>
            </tr>
        <?php endif; ?>

        <?php foreach($municipality as $mun): ?>
            <?php
                $totals_mpb[$mun['name']] = ['prv'=>0,'mun'=>0,'brgy'=>0, 'ttal'=>0 ];
            ?>
                <?php if( $mun['id'] != 14): ?>
                    <tr>
                        <td><?php echo e(strtoupper($mun['name'])); ?></td>
                        <td></td>
                        <td style="text-align: right;"><?php if($landtaxsharing[$mun['name']]['provincial_value']): ?> <?php echo e(number_format($landtaxsharing[$mun['name']]['provincial_value'],2)); ?> <?php $totals_mpb[$mun['name']]['prv'] = $landtaxsharing[$mun['name']]['provincial_value']; $totals_prv += $landtaxsharing[$mun['name']]['provincial_value'];   ?> <?php else: ?> - <?php endif; ?></td>
                        <td style="text-align: right;"><?php if($landtaxsharing[$mun['name']]['value']): ?> <?php echo e(number_format($landtaxsharing[$mun['name']]['value'],2)); ?>  <?php $totals_mpb[$mun['name']]['mun'] = $landtaxsharing[$mun['name']]['value']; $totals_mun += $landtaxsharing[$mun['name']]['value']; ?> <?php else: ?> - <?php endif; ?></td>
                        <td style="text-align: right;" >  <?php if(isset($landtaxsharing[$mun['name']]['brgy'])): ?> <?php else: ?> - <?php endif; ?> </td>
                        <td style="text-align: right;  padding-right: 5px; " ><?php if($landtaxsharing[$mun['name']]['provincial_value']): ?>  <?php echo e(number_format($totals_mpb[$mun['name']]['mun'] + $totals_mpb[$mun['name']]['prv'] , 2 )); ?> <?php else: ?> - <?php endif; ?></td>
                    </tr>
                    <?php ($totals += $landtaxsharing[$mun['name']]['value']); ?>
                            <?php if(isset($landtaxsharing[$mun['name']]['brgy'])): ?>
                                    <?php foreach($landtaxsharing[$mun['name']]['brgy'] as $key => $brgy): ?>
                                        <tr>
                                            <td></td>
                                            <td><?php echo e(strtoupper($key)); ?></td>
                                            <td></td>
                                            <td></td>
                                            <td style="text-align: right;"><?php echo e(number_format($brgy,2)); ?></td>
                                            <td style="text-align: right; padding-right: 5px;"><?php echo e(number_format($brgy,2)); ?></td>
                                        </tr>
                                         <?php $totals_mpb[$mun['name']]['brgy'] += $brgy; ?>
                                        <?php ($totals += $brgy); ?>
                                    <?php endforeach; ?>
                            <?php endif; ?>

                    <?php if($landtaxsharing[$mun['name']]['provincial_value']): ?>
                    <?php  $totals_mpb[$mun['name']]['ttal'] = $totals_mpb[$mun['name']]['mun'] + $totals_mpb[$mun['name']]['prv'] + $totals_mpb[$mun['name']]['brgy']; $totals_bgy += $totals_mpb[$mun['name']]['brgy'];;  ?>
                        <tr>
                            <td colspan="5" style=" padding-left: 102px; border-top: 2px solid #000 !important; border-bottom: 2px solid #000 !important;"><strong> Sub - Total <?php echo e($mun['name']); ?> </strong> </td>
                            <td style="text-align: right; border-top: 2px solid #000 !important; border-bottom: 2px solid #000 !important; " ><strong>  <?php echo e(number_format( $totals_mpb[$mun['name']]['ttal'], 2 )); ?> </strong>  </td>
                        </tr>
                    <?php endif; ?>
                <?php endif; ?>
        <?php endforeach; ?>
    </tbody>

    <tfoot>
        <tr>
            <th colspan="2" class="text-center" style=" border-top: 2px solid #000 !important; border-bottom: 3px solid #000 !important;">SHARING FOR <?php echo e(strtoupper($datex->format('F'))); ?>  <?php echo e($year); ?></th>
            <th class="text-right" style=" padding-right: 5px; border-top: 2px solid #000 !important; border-bottom: 3px solid #000 !important;"><?php echo e(number_format($totals_prv,2)); ?></th>
            <th class="text-right" style=" padding-right: 5px; border-top: 2px solid #000 !important; border-bottom: 3px solid #000 !important;"><?php echo e(number_format($totals_mun,2)); ?></th>
            <th class="text-right" style=" padding-right: 5px; border-top: 2px solid #000 !important; border-bottom: 3px solid #000 !important;"><?php echo e(number_format($totals_bgy,2)); ?></th>
            <th class="text-right" style=" padding-right: 5px; border-top: 2px solid #000 !important; border-bottom: 3px solid #000 !important;"><?php echo e(number_format($totals_prv + $totals_mun + $totals_bgy,2)); ?></th>
        </tr>
        <tr>
            <th colspan="2" class="text-center" style=" border-top: 2px solid #000 !important; border-bottom: 3px solid #000 !important;">TO DATE</th>
            <th class="text-right" style=" padding-right: 5px; border-top: 2px solid #000 !important; border-bottom: 3px solid #000 !important;"><?php echo e(isset($prev_month['provincial_value']) ? number_format(($totals_prv + $prev_month['provincial_value']),2) : number_format($totals_prv, 2)); ?></th>
            <th class="text-right" style=" padding-right: 5px; border-top: 2px solid #000 !important; border-bottom: 3px solid #000 !important;"><?php echo e(isset($prev_month['municipal_value']) ? number_format($totals_mun + $prev_month['municipal_value'],2) : number_format($totals_mun, 2)); ?></th>
            <th class="text-right" style=" padding-right: 5px; border-top: 2px solid #000 !important; border-bottom: 3px solid #000 !important;"><?php echo e(isset($prev_month['brgy_value']) ? number_format(($totals_bgy + $prev_month['brgy_value']),2) : number_format($totals_bgy, 2)); ?></th>
            <th class="text-right" style=" padding-right: 5px; border-top: 2px solid #000 !important; border-bottom: 3px solid #000 !important;"><?php echo e(number_format($totals_prv + $totals_mun + $totals_bgy + array_sum($prev_month),2)); ?></th>
        </tr>
        <tr>
            <th colspan="6"></th>
        </tr>

    </tfoot>
</table>

<br />

<!-- <?php /* <div style="page-break-inside: avoid !important;">
    <table style="width: 70%; margin: 0 auto;">
        <tr>
            <th colspan="3"><u>SUMMARY</u></th>
        </tr>
        <tr>
            <th colspan="3">&nbsp;</th>
        </tr>
        <tr>
            <td colspan="3"><u>Sand and Gravel Permittees:</u></td>
        </tr>

        <?php foreach($summary_per_client as $key => $val): ?>
            <?php if(in_array($key, [5,6])): ?> 
                <tr>
                    <td></td>
                    <td class="text-left"><?php echo e($client_types[$key]); ?></td>
                    <td class="text-right bold-text"><?php echo e(number_format($val, 2)); ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>

        <tr>
            <td colspan="3"><u>Projects</u></td>
        </tr>

        <?php foreach($summary_per_client as $key => $val): ?>
            <?php if(in_array($key, [2,3])): ?> 
                <tr>
                    <td></td>
                    <td class="text-left"><?php echo e($client_types[$key]); ?></td>
                    <td class="text-right bold-text"><?php echo e(number_format($val, 2)); ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>

        <tr>
            <td></td>
            <td colspan="2"></td>
        </tr>

        <tr>
            <td></td>
            <td colspan="2"></td>
        </tr>

        <tr>
            <td class="text-left"><u>Sand and Gravel Penalties Through Monitoring</u></td>
            <td></td>
            <td class="text-right bold-text"><?php echo e(number_format((isset($summary_per_client[1]) ? $summary_per_client[1] : 0), 2)); ?></td>
        </tr>

        <tr>
            <td class="text-left"><u>Municipal/Brgy Remittances</u></td>
            <td></td>
            <td class="text-right bold-text"><?php echo e(number_format((isset($summary_per_client[16]) ? (isset($summary_per_client[4]) ? $summary_per_client[4] + $summary_per_client[16] : $summary_per_client[16] ) : 0),2)); ?></td>
        </tr>

        <tr>
            <td>&nbsp;</td>
            <td  class="text-left bold-text">TOTAL</td>
            <td  class="text-right bold-text" style="border-bottom: 3px double #000; border-top: 1px solid #000;"><?php echo e(number_format(array_sum($summary_per_client), 2)); ?></td>
        </tr>

    </table>
</div> */ ?> -->

<table class="table table-no-bordered" style="width: 300px;">
    <tbody>
        <tr>
            <td style="width: 50px; ">Prepared by:<br><br></td>
            <td style="width: 150px;" >&nbsp;</td>
        </tr>
         <tr>
             <td></td>

             <?php 
                $STR = strtolower($officer->value);
                $STR = strtoupper($STR);
              ?>
            <td style="font-weight: bold; text-align: center; "><?php echo e($STR); ?></td>
        </tr>
        <tr>
             <td></td>
            <td style="font-weight: bold;  text-align: center;"><?php echo e($position->value); ?></td>
        </tr>


    </tbody>
</table>

</body>
</html>


