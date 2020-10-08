<!DOCTYPE html>
<html>
<head>
    <title>SAND and GRAVEL</title>
    <style>
        @page  { margin: 0.5in 10px; }
        body {
            margin: 0px 10px; 
            font-family: arial, "sans-serif";
            font-size: 8.5;
        }

        
        .center {
                width: 325px;
                text-align: center;
                margin: 10px auto;
        }

           .image_logo{
                width: 80px;
                height:80px;
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
            border:1px solid #ccc;
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
            padding: 1px;
        }

        .bold-text{
            font-weight: bold;
        }

        .table, .table>thead>tr>th, .table>tfoot>tr>th, .table>tbody>tr>td{
            border: 2px solid #000;
            /*border: 2px solid #000 !important;*/
        }

        /*table>thead>tr>th, .table>tfoot>tr>th, .table>tbody>tr>td{
            border: 2px solid #000 !important;
        }*/

        .table {
            border-top: none !important;
            border-right: none !important;
            border-left: none !important;
            border-collapse: collapse !important;
        }        

        .table-no-bordered, .table-no-bordered>thead>tr>th, .table-no-bordered>tbody>tr>td{
                border:none !important;
        }
        /*.table-bordered {
            border: 2px solid #000 !important;
        }*/

        /* page header */
        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 160px;

            text-align: center;
            margin-top: 160px;
            margin-right: 10px;
            margin-left: 10px;
            /*padding-bottom: 150px !important;*/
            /*margin-bottom: 150px !important;*/
        }

        main {
            /*border-left: 2px solid #000000;
            border-right: 2px solid #000000;*/
            /*border-bottom: 2px solid #000000;*/
            padding-left: 0;
            padding-right: 0;
            margin-left: 0;
            margin-right: 0;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 160px;

            text-align: center;
            margin-bottom: 160px;
            margin-right: 10px;
            margin-left: 10px;
            font-size: 11px;
        }

        .pg_header {
            /*border-top: 2px solid #000;*/
            text-align: center;
            /*margin-top: 150px;*/
        }

        .table-borderedx{
            padding: 0px;
            margin: 0px;
            border: 2px solid #000000 !important;
        }

        .pagenum:before {
            position: fixed;
            bottom: 0cm;
            line-height: .7cm;
            content: counter(page);
        }

        #collections {
            page-break-inside: auto;
        }
    </style>
</head>
<body>
<header>
    <div class="pg-header">
        <table class="center">
            <tr>
            <td style="width:10px; padding:0px;">
                <img src="<?php echo e(asset('asset/images/benguet-logo.png')); ?>" class="image_logo" alt/>
            </td>
            <td style="width: 230px">
                REPUBLIC OF THE PHILIPPINES<br />
                BENGUET PROVINCE<br />
                La Trinidad<br/>
                <strong>OFFICE OF THE PROVINCIAL TREASURER</strong>
            </td>
            </tr>
        </table>
        <?php if($datex->format('m') == 1): ?>
            <center><strong>SUMMARY OF SAND AND GRAVEL TAX COLLECTIONS <br/><br> <u>FOR THE PERIOD JANUARY <?php echo e($year); ?></u></strong></center><br>
        <?php else: ?>
            <center><strong>SUMMARY OF SAND AND GRAVEL TAX COLLECTIONS <br/><br> <u>FOR THE PERIOD JANUARY-<?php echo e(strtoupper($datex->format('F'))); ?> <?php echo e($year); ?></u></strong></center><br>
        <?php endif; ?>
    </div>
</header>

<footer>
    <div class="pagenum"></div>
</footer>

<!-- main content -->
<main>
    <!-- <div> -->
        <table class="table table-condensed table-bordered table-borderedx" id="collections">
            <thead>
                <tr>
                    <th rowspan="2">DATE<br/><?php echo e($year); ?></th>
                    <th rowspan="2">OFFICIAL<br/>RECEIPT NO.</th>
                    <!-- <th rowspan="2">PROVINCIAL<br/>SHARE</th> -->
                    <th rowspan="2">MONITORING<br/>PENALTIES</th>
                    <th rowspan="2">PROVINCIAL<br/>CONTRACTORS</th>
                    <?php if($dpwh_total != 0 && isset($prev_month_totals[3])): ?>
                        <?php if(array_sum($prev_month_totals[3]) > 0): ?>
                        <th rowspan="2">DPWH-CAR<br/>REMITTANCE</th>
                        <?php endif; ?>
                    <?php endif; ?>
                    <!-- <th rowspan="2">MUN/BRGY<br/>REMITTANCE</th> -->
                    <th rowspan="1" colspan="2">S & G PERMITTEES</th>
                    <th rowspan="2">MUNICIPAL/BRGY<br/>REMITTANCES</th>
                    <th rowspan="2">TOTALS</th>
                </tr>

                <tr>
                    <th>INDUSTRIAL</th>
                    <th>COMMERCIAL</th>
                </tr> 
            </thead>
            <tbody style="page-break-inside: avoid;">
                <!-- previous collections and month header START -->
                <tr>
                    <?php
                        $report_month = $datex;
                        $prev_month = \Carbon\Carbon::createFromDate($datex->format('Y'), $datex->format('m')-1);
                        $prev_total = 0;
                        for ($i=0; $i <= 16; $i++) { 
                            if(isset($prev_month_totals[$i])) {
                                if($i == 3 && $prev_month_totals[$i] != 0) {
                                    $prev_total += round(floatval($prev_month_totals[$i]['val']), 2);
                                } else {
                                    $prev_total += round(floatval($prev_month_totals[$i]['val']), 2);
                                }
                            }
                        }
                    ?>
                    <?php if($report_month->format('m') != 1): ?>
                        <?php if(!empty($prev_month_totals)): ?>
                            <td colspan="2"><b>Collections As of <?php echo e($prev_month->format('F')); ?> <?php echo e($year); ?></b></td>
                            <td class="text-right"><?php echo e(number_format($prev_month_totals[1]['val'], 2)); ?></td>
                            <td class="text-right"><?php echo e(number_format($prev_month_totals[2]['val'], 2)); ?></td>
                            <?php if($dpwh_total != 0 && isset($prev_month_totals[3])): ?>
                                <?php if(array_sum($prev_month_totals[3]) > 0): ?>
                                <td class="text-right"><?php echo e(number_format($prev_month_totals[3]['val'], 2)); ?></td>
                                <?php endif; ?>
                            <?php endif; ?>
                            <td class="text-right"><?php echo e(number_format($prev_month_totals[5]['val'], 2)); ?></td>
                            <!-- <th></th> -->
                            <td class="text-right"><?php echo e(number_format($prev_month_totals[6]['val'], 2)); ?></td>
                            <td class="text-right"><?php echo e(number_format(round(floatval($prev_month_totals[16]['val']), 2) + round(floatval($prev_month_totals[4]['val']), 2), 2)); ?></td>
                            <td class="text-right"><?php echo e(number_format($prev_total, 2)); ?></td>
                        <?php else: ?>
                            <td><b><?php echo e($report_month->format('F')); ?></b></td>
                            <td></td>
                            <td></td>
                            <?php if($dpwh_total != 0 && isset($prev_month_totals[3])): ?>
                                <?php if(array_sum($prev_month_totals[3]) > 0): ?>
                                <td></td>
                                <?php endif; ?>
                            <?php endif; ?>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right">0.00</td>
                        <?php endif; ?>
                    <?php else: ?>
                        <td><b><?php echo e($report_month->format('F')); ?></b></td>
                        <td></td>
                        <td></td>
                        <?php if($dpwh_total != 0 && isset($prev_month_totals[3])): ?>
                            <?php if(array_sum($prev_month_totals[3]) > 0): ?>
                            <td></td>
                            <?php endif; ?>
                        <?php endif; ?>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right">0.00</td>
                    <?php endif; ?>
                </tr>

                <?php if($report_month->format('m') != 1): ?>
                <?php
                    $provshare_prev_total = round(floatval($prev_month_totals[1]['prov_share']), 2)
                        + round(floatval($prev_month_totals[2]['prov_share']), 2)
                        + round(floatval($prev_month_totals[3]['prov_share']), 2)
                        + round(floatval($prev_month_totals[4]['prov_share']), 2)
                        + round(floatval($prev_month_totals[5]['prov_share']), 2)
                        + round(floatval($prev_month_totals[6]['prov_share']), 2)
                        + round(floatval($prev_month_totals[16]['prov_share']), 2);
                ?>
                <tr>
                    <td colspan="2"><b>Prov'l Share As of <?php echo e($prev_month->format('F')); ?></b></td>
                    <td class="text-right"><?php echo e(number_format($prev_month_totals[1]['prov_share'], 2)); ?></td>
                    <td class="text-right"><?php echo e(number_format($prev_month_totals[2]['prov_share'], 2)); ?></td>
                    <?php if($dpwh_total != 0 && isset($prev_month_totals[3])): ?>
                        <?php if(array_sum($prev_month_totals[3]) > 0): ?>
                        <td class="text-right"><?php echo e(number_format($prev_month_totals[3]['prov_share'], 2)); ?></td>
                        <?php endif; ?>
                    <?php endif; ?>
                    <td class="text-right"><?php echo e(number_format($prev_month_totals[5]['prov_share'], 2)); ?></td>
                    <td class="text-right"><?php echo e(number_format($prev_month_totals[6]['prov_share'], 2)); ?></td>
                    <td class="text-right"><?php echo e(number_format((round(floatval($prev_month_totals[4]['prov_share']), 2) + round(floatval($prev_month_totals[16]['prov_share']), 2)), 2)); ?></td>
                    <td class="text-right"><?php echo e(number_format($provshare_prev_total, 2)); ?>

                    </td>
                </tr>
                <?php endif; ?>

                <?php if($datex->format('m') != 1): ?>
                <tr>
                    <td><b><?php echo e($datex->format('F')); ?></b></td>
                    <td></td>
                    <td></td>
                    <?php if($dpwh_total != 0 && isset($prev_month_totals[3])): ?>
                        <?php if(array_sum($prev_month_totals[3]) > 0): ?>
                        <td></td>
                        <?php endif; ?>
                    <?php endif; ?>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right">0.00</td>
                </tr>
                <?php endif; ?>
                <!-- previous collections and month header END -->
                <?php
                    // $total = [];
                    // $t1 = $t2 = $t3 = $t416 = $t5 = $t6 = 0;

                    $total = 0;
                    $total1 = 0;
                    $total2 = 0;
                    $total3 = 0;
                    $total4 = 0;
                    $total5 = 0;
                    $total6 = 0;
                    $total16 = 0;
                    $provShare1 = 0;
                    $provShare2 = 0;
                    $provShare3 = 0;
                    $provShare4 = 0;
                    $provShare5 = 0;
                    $provShare6 = 0;
                    $provShare16 = 0;
                    $total_provshare = 0;
                ?>
                <?php foreach( $dailygraveltypes as $key => $dly ): ?>
                    <?php
                        // dd($dailygraveltypes);
                        // orig
                        // $mun_bgy = $dly[4] + $dly[16];
                        // $total[$key] = $dly[1] + $dly[2] + $dly[3] + $mun_bgy + $dly[5] + $dly[6];

                        // $t1 += $dly[1];
                        // $t2 += $dly[2];
                        // $t3 += $dly[3];
                        // $t416 += $mun_bgy;
                        // $t5 += $dly[5];
                        // $t6 += $dly[6];
                    ?>
                    <?php if(is_array($dly)): ?> 
                        <?php foreach($dly as $c_type => $rcpt): ?>
                            <?php
                                // $total += 0;
                                // $total1 += isset($dly[1]) ? (is_array($dly[1]) ? array_sum($dly[1]) : $dly[1]) : 0;
                                // $total2 += isset($dly[2]) ? (is_array($dly[2]) ? array_sum($dly[2]) : $dly[2]) : 0;
                                // $total4 += isset($dly[4]) ? (is_array($dly[4]) ? array_sum($dly[4]) : $dly[4]) : 0;
                                // $total5 += isset($dly[5]) ? (is_array($dly[5]) ? array_sum($dly[5]) : $dly[5]) : 0;
                                // $total6 += isset($dly[6]) ? (is_array($dly[6]) ? array_sum($dly[6]) : $dly[6]) : 0;
                                // $total16 += isset($dly[16]) ? (is_array($dly[16]) ? array_sum($dly[16]) : $dly[16]) : 0;
                            ?>
                            <?php if(is_array($rcpt)): ?> 
                                <?php foreach($rcpt as $key2 => $value): ?>
                                    <?php
                                        $total += !is_null(round(floatval($value['val']), 2)) ? $value['val'] : 0;
                                        $total1 += $c_type == 1 ? round(floatval($value['val']), 2) : 0;
                                        $total2 += $c_type == 2 ? round(floatval($value['val']), 2) : 0;
                                        $total3 += $c_type == 3 ? round(floatval($value['val']), 2) : 0;
                                        $total4 += $c_type == 4 ? round(floatval($value['val']), 2) : 0;
                                        $total5 += $c_type == 5 ? round(floatval($value['val']), 2) : 0;
                                        $total6 += $c_type == 6 ? round(floatval($value['val']), 2) : 0;
                                        $total16 += $c_type == 16 ? round(floatval($value['val']), 2) : 0;

                                        $provShare1 += $c_type == 1 ? round(floatval($value['prov_share']), 2) : 0;
                                        $provShare2 += $c_type == 2 ? round(floatval($value['prov_share']), 2) : 0;
                                        $provShare3 += $c_type == 3 ? round(floatval($value['prov_share']), 2) : 0;
                                        $provShare4 += $c_type == 4 ? round(floatval($value['prov_share']), 2) : 0;
                                        $provShare5 += $c_type == 5 ? round(floatval($value['prov_share']), 2) : 0;
                                        $provShare6 += $c_type == 6 ? round(floatval($value['prov_share']), 2) : 0;
                                        $provShare16 += $c_type == 16 ? round(floatval($value['prov_share']), 2) : 0;

                                        $total_provshare +=  round(floatval($value['prov_share']), 2);
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo e($key); ?></td> <!-- date -->
                                        <td style="border-bottom: 2px solid #000 !important;"><?php echo e($key2); ?></td> 
                                        <!-- <td class="text-right"><?php /* number_format($value['prov_share'], 2) */ ?></td> -->
                                        <td class="text-right"><?php echo e($c_type == 1 ? number_format($value['val'],2) : ''); ?></td>
                                        <td class="text-right"><?php echo e($c_type == 2 ? number_format($value['val'],2) : ''); ?></td>
                                        <?php if($dpwh_total != 0 && isset($prev_month_totals[3])): ?>
                                            <?php if(array_sum($prev_month_totals[3]) > 0): ?>
                                            <td class="text-right"><?php echo e($c_type == 3 ? number_format($value['val'],2) : ''); ?></td>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <td class="text-right"><?php echo e($c_type == 5 ? number_format($value['val'],2) : ''); ?></td>
                                        <td class="text-right"><?php echo e($c_type == 6 ? number_format($value['val'],2) : ''); ?></td>
                                        <td class="text-right"><?php echo e($c_type == 16 || $c_type == 4 ? number_format($value['val'],2) : ''); ?></td>
                                        <!-- <td class="text-right"><?php /* $c_type == 16 ? number_format($value,2) : '' */ ?></td> -->
                                        <td class="text-right"><?php echo e(number_format($value['val'],2)); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <?php
                                    // $total += !is_null($rcpt) ? $rcpt : 0;
                                    // $total1 += $c_type == 1 ? $rcpt : 0;
                                    // $total2 += $c_type == 2 ? $rcpt : 0;
                                    // $total3 += $c_type == 3 ? $rcpt : 0;
                                    // $total4 += $c_type == 4 ? $rcpt : 0;
                                    // $total5 += $c_type == 5 ? $rcpt : 0;
                                    // $total6 += $c_type == 6 ? $rcpt : 0;
                                    // $total16 += $c_type == 16 ? $rcpt : 0;
                                    // $total_provshare += $dly['prov_share'];
                                ?>
                                <!-- <?php /* <tr>
                                    <td class="text-center"><?php echo e($key); ?></td>
                                    <td></td> 
                                    <td><?php echo e(number_format($dly['prov_share'], 2)); ?></td> 
                                    <td class="text-right"><?php echo e($c_type == 1 ? number_format($rcpt,2) : ''); ?></td>
                                    <td class="text-right"><?php echo e($c_type == 2 ? number_format($rcpt,2) : ''); ?></td>
                                    <?php if($dpwh_total != 0): ?>
                                    <td class="text-right"><?php echo e($c_type == 3 ? number_format($rcpt,2) : ''); ?></td>
                                    <?php endif; ?>
                                    <td class="text-right"><?php echo e($c_type == 5 ? number_format($rcpt,2) : ''); ?></td>
                                    <td class="text-right"><?php echo e($c_type == 6 ? number_format($rcpt,2) : ''); ?></td>
                                    <td class="text-right"><?php echo e($c_type == 16 || $c_type == 4 ? number_format($rcpt,2) : ''); ?></td>
                                    <td class="text-right"><?php echo e(number_format($rcpt,2)); ?></td>
                                </tr> */ ?> -->
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">SHARING FOR <?php echo e($report_month->format('F')); ?> <?php echo e($year); ?></th>
                    <!-- <th class="text-right"></th> -->
                    <!-- <th class="text-right"><?php /* number_format($total_provshare, 2) */ ?></th> -->
                    <th class="text-right"><?php echo e(number_format($total1,2)); ?></th>
                    <th class="text-right"><?php echo e(number_format($total2,2)); ?></th>
                    <?php if($dpwh_total != 0 && isset($prev_month_totals[3])): ?>
                        <?php if(array_sum($prev_month_totals[3]) > 0): ?>
                        <th class="text-right"><?php echo e(number_format($total3,2)); ?></th>
                        <?php endif; ?>
                    <?php endif; ?>
                    <th class="text-right"><?php echo e(number_format($total5,2)); ?></th>
                    <th class="text-right"><?php echo e(number_format($total6,2)); ?></th>
                    <th class="text-right"><?php echo e(!is_null($total4) ? (!is_null($total16) ? number_format((round(floatval($total4), 2) + round(floatval($total16), 2)) ,2) : number_format($total4,2)) : (!is_null($total16) ? number_format($total16,2) : 0.00)); ?></th>        
                    <!-- <th class="text-right"><?php echo e(number_format($total16,2)); ?></th> -->
                    <th class="text-right"><?php echo e(number_format($total,2)); ?></th>
                </tr> 

                <!-- <tr>
                    <?php if($dpwh_total != 0): ?>
                    <th colspan="8">Total Provincial Share <?php echo e($datex->format('F')); ?> <?php echo e($year); ?> </th>
                    <?php else: ?>
                    <th colspan="7">Total Provincial Share <?php echo e($datex->format('F')); ?> <?php echo e($year); ?> </th>
                    <?php endif; ?>
                    <td></td>
                    <th class="text-right"><?php echo e(number_format($provShare,2)); ?></th>
                </tr> -->
               
                <!-- <tr>
                    <th colspan="7">Total Collections</th>
                    <th class="text-right"><?php /* number_format(array_sum($total),2) */ ?></th>
                </tr> -->
                <?php if($report_month->format('m') != 1): ?>
                <tr>
                    <?php if($datex->format('m') != 1): ?>
                        <?php if(!empty($prev_month_totals)): ?>
                            <!-- <th colspan="2"><?php /* "Jan-" . $report_month->format('M') */ ?> Collections</th> -->
                            <th colspan="2">COLLECTIONS TO DATE</th>
                            <!-- <th class="text-right"><?php /* number_format(($total_provshare + $prev_month_totals['prov_share']), 2) */ ?></th> -->
                            <th class="text-right"><?php echo e(number_format(round(floatval($total1), 2) + round(floatval($prev_month_totals[1]['val']), 2), 2)); ?></th>
                            <th class="text-right"><?php echo e(number_format(round(floatval($total2), 2) + round(floatval($prev_month_totals[2]['val']), 2) , 2)); ?></th>
                            <?php if($dpwh_total != 0 && isset($prev_month_totals[3])): ?>
                                <?php if(array_sum($prev_month_totals[3]) > 0): ?>
                                <th class="text-right"><?php echo e(number_format(round(floatval($total3), 2) + round(floatval($prev_month_totals[3]['val']), 2) , 2)); ?></th>
                                <?php endif; ?>
                            <?php endif; ?>
                            <th class="text-right"><?php echo e(number_format(round(floatval($total5), 2) + round(floatval($prev_month_totals[5]['val']), 2) , 2)); ?></th>
                            <th class="text-right"><?php echo e(number_format(round(floatval($total6), 2) + round(floatval($prev_month_totals[6]['val']), 2), 2)); ?></th>
                            <th class="text-right"><?php echo e(number_format(round(floatval($total16), 2) + round(floatval($prev_month_totals[16]['val']), 2) , 2)); ?></th>
                            <th class="text-right"><?php echo e(number_format(round(floatval($total), 2) + round(floatval($prev_total), 2), 2)); ?></th>
                        <?php else: ?>
                            <?php if($report_month->format('m') == 2): ?>
                                <!-- <th colspan="2"><?php /* $prev_month->format('F') */ ?> Collections</th> -->
                                <th colspan="2">COLLECTIONS TO DATE</th>
                            <?php else: ?>
                                <!-- <th colspan="2"><?php /* "January-" . $report_month->format('F') */ ?> Collections</th> -->
                                <th colspan="2">COLLECTIONS TO DATE</th>
                            <?php endif; ?>
                            <!-- <th class="text-right"><?php /* number_format(($total_provshare + $prev_month_totals['prov_share']), 2) */ ?></th>  -->
                            <th class="text-right">0.00</th>
                            <th class="text-right">0.00</th>
                            <?php if($dpwh_total != 0 && isset($prev_month_totals[3])): ?>
                                <?php if(array_sum($prev_month_totals[3]) > 0): ?>
                                <th class="text-right">0.00</th>
                                <?php endif; ?>
                            <?php endif; ?>
                            <th class="text-right">0.00</th>
                            <th class="text-right">0.00</th>
                            <th class="text-right">0.00</th>
                            <th class="text-right">0.00</th>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if($report_month->format('m') == 2): ?>
                            <!-- <th colspan="2"><?php /* $prev_month->format('F') */ ?> Collections</th> -->
                            <th colspan="2">COLLECTIONS TO DATE</th>
                        <?php else: ?>
                            <!-- <th colspan="2"><?php /* "January-" . $report_month->format('F') */ ?> Collections</th> -->
                            <th colspan="2">COLLECTIONS TO DATE</th>
                        <?php endif; ?>
                        <!-- <th class="text-right"><?php /* number_format(($total_provshare + $prev_month_totals['prov_share']), 2) */ ?></th>  -->
                        <th class="text-right"><?php echo e(number_format($total1,2)); ?></th>
                        <th class="text-right"><?php echo e(number_format($total2,2)); ?></th>
                        <?php if($dpwh_total != 0 && isset($prev_month_totals[3])): ?>
                            <?php if(array_sum($prev_month_totals[3]) > 0): ?>
                            <th class="text-right"><?php echo e(number_format($total3,2)); ?></th>
                            <?php endif; ?>
                        <?php endif; ?>
                        <th class="text-right"><?php echo e(number_format($total5,2)); ?></th>
                        <th class="text-right"><?php echo e(number_format($total6,2)); ?></th>
                        <th class="text-right"><?php echo e(number_format($total16,2)); ?></th>
                        <th class="text-right"><?php echo e(number_format(round(floatval($total), 2) + round(floatval($prev_total), 2) ,2)); ?></th>
                    <?php endif; ?>
                </tr>
                <?php endif; ?>

                <tr>
                    <th colspan="2">PROV'L SHARE <?php echo e($report_month->format('F')); ?> <?php echo e($year); ?></th>
                    <!-- <th class="text-right"><?php /* number_format(($total_provshare + $prev_month_totals['prov_share']), 2) */ ?></th>  -->
                    <th class="text-right"><?php echo e(number_format($provShare1, 2)); ?></th>
                    <th class="text-right"><?php echo e(number_format($provShare2, 2)); ?></th>
                    <?php if($dpwh_total != 0 && isset($prev_month_totals[3])): ?>
                        <?php if(array_sum($prev_month_totals[3]) > 0): ?>
                        <th class="text-right"><?php echo e(number_format($provShare3, 2)); ?></th>
                        <?php endif; ?>
                    <?php endif; ?>
                    <th class="text-right"><?php echo e(number_format($provShare5, 2)); ?></th>
                    <th class="text-right"><?php echo e(number_format($provShare6, 2)); ?></th>
                    <th class="text-right"><?php echo e(number_format((round(floatval($provShare4), 2) + round(floatval($provShare16), 2)), 2)); ?></th>
                    <th class="text-right"><?php echo e(number_format($total_provshare, 2)); ?></th>
                </tr>
                <?php if($report_month->format('m') != 1): ?>
                <tr>
                    <!-- <th colspan="2">Prov'l Share January - <?php /* $report_month->format('F') */ ?></th> -->
                    <th colspan="2">PROV'L SHARE TO DATE</th>
                    <!-- <th class="text-right"><?php /* number_format(($total_provshare + $prev_month_totals['prov_share']), 2) */ ?></th>  -->
                    <th class="text-right"><?php echo e(number_format((round(floatval($provShare1), 2) + round(floatval($prev_month_totals[1]['prov_share']), 2)), 2)); ?></th>
                    <th class="text-right"><?php echo e(number_format((round(floatval($provShare2), 2) + round(floatval($prev_month_totals[2]['prov_share']), 2)), 2)); ?></th>
                    <?php if($dpwh_total != 0 && isset($prev_month_totals[3])): ?>
                        <?php if(array_sum($prev_month_totals[3]) > 0): ?>
                        <th class="text-right"><?php echo e(number_format((round(floatval($provShare3), 2) + round(floatval($prev_month_totals[3]['prov_share']), 2)), 2)); ?></th>
                        <?php endif; ?>
                    <?php endif; ?>
                    <th class="text-right"><?php echo e(number_format((round(floatval($provShare5), 2) + round(floatval($prev_month_totals[5]['prov_share']), 2)), 2)); ?></th>
                    <th class="text-right"><?php echo e(number_format((round(floatval($provShare6), 2) + round(floatval($prev_month_totals[6]['prov_share']), 2)), 2)); ?></th>
                    <th class="text-right"><?php echo e(number_format((round(floatval($provShare4), 2) + round(floatval($provShare16), 2) + round(floatval($prev_month_totals[4]['prov_share']), 2) + round(floatval($prev_month_totals[16]['prov_share']), 2)), 2)); ?></th>
                    <th class="text-right"><?php echo e(number_format((round(floatval($total_provshare), 2) + round(floatval($provshare_prev_total), 2)), 2)); ?></th>
                </tr>
                <?php endif; ?>
            </tfoot>    
        </table>
    <!-- </div> -->
</main>

<div style="page-break-inside: avoid;">
    <table style="width: 50%; margin: 0 auto; padding-top: 30px;">
        <tr>
            <th colspan="3"><u>SUMMARY</u></th>
        </tr>
        <tr>
            <th colspan="3">&nbsp;</th>
        </tr>
        <!-- <tr>
            <th colspan="3"><u>SOURCES OF SAND AND GRAVEL TAX COLLECTIONS</u></th>
        </tr> -->
        <tr>
            <td colspan="3"><u>Sand and Gravel Permittees:</u></td>
        </tr>

        <?php ($typestotal = 0); ?>
        <?php if(isset($graveltypes['Commercial'])): ?>
            <?php ($typestotal += round(floatval($graveltypes['Commercial']->value))); ?>
            <tr>
                <td width=".75cm"></td>
                <td class="text-left"> - Commercial</td>
                <td class="text-right bold-text"><?php echo e(number_format($total6,2)); ?></td>
            </tr>
        <?php endif; ?> 

        <?php if(isset($graveltypes['Industrial '])): ?>
            <?php ($typestotal += round(floatval($graveltypes['Industrial ']->value), 2)); ?>
            <tr>
                <td></td>
                <td class="text-left"> - Industrial</td>
                <td class="text-right bold-text"><?php echo e(number_format($total5,2)); ?></td> 
            </tr>
        <?php else: ?>
            <tr>
                <td></td>
                <td class="text-left"> - Industrial</td>
                <td class="text-right bold-text"><?php echo e(number_format(0,2)); ?></td>
            </tr>
        <?php endif; ?>

        <tr>
            <td colspan="2"><u>Projects</u></td>
        </tr>

        <?php if(isset($graveltypes['Contractors (Prov.)'])): ?>
            <?php ($typestotal += round(floatval($graveltypes['Contractors (Prov.)']->value), 2)); ?> 
            <tr>
                <td></td>
                <td class="text-left"> - Provincial</td>
                <td class="text-right bold-text"><?php echo e(number_format($total2,2)); ?></td>
            </tr>
        <?php endif; ?>

        <?php if(isset($graveltypes['DPWH - CAR (ADA)'])): ?>
            <?php ($typestotal += round(floatval($graveltypes['DPWH - CAR (ADA)']->value), 2)); ?>
            <tr>
                <td></td>
                <td class="text-left"> - DPWH Remittances</td>
                <td class="" ass="text-right bold-text"><?php echo e(number_format($graveltypes['DPWH - CAR (ADA)']->value,2)); ?></td>
            </tr>
        <?php endif; ?>

        <tr>
            <td></td>
            <td colspan="2"></td>
        </tr>

        <tr>
            <td></td>
            <td colspan="2"></td>
        </tr>

        <?php if(isset($graveltypes['Monitoring'])): ?>
            <?php ($typestotal += $graveltypes['Monitoring']->value); ?>
            <tr>
                <td class="text-left" colspan="2"><u>Sand and Gravel Penalties Through Monitoring</u></td>
                <!-- <td></td> -->
                <td class="text-right bold-text"><?php echo e(number_format($total1,2)); ?></td>
            </tr>
        <?php endif; ?> 
        <tr>
            <td class="text-left" colspan="2"><u>Municipal/Brgy Remittances</u></td>
            <!-- <td></td> -->
            <td class="text-right bold-text"><?php echo e(number_format((round(floatval($total16), 2) + round(floatval($total4), 2)),2)); ?></td>
        </tr>

        <tr>
            <td>&nbsp;</td>
            <td  class="text-left bold-text">TOTAL</td>
            <td  class="text-right bold-text" style="border-bottom: 3px double #000; border-top: 1px solid #000;"><?php echo e(number_format($total,2)); ?></td>
        </tr>

    </table>

    <!-- <br /> -->
    <table class="table table-no-bordered" style="width: 300px; padding-top: 40px;">
            <tr>
                <td style="width: 50px;">Prepared by: <br><br></td>
                <td style="width: 150px;" >&nbsp;</td>
            </tr>
             <tr>
                 <td></td>
                 <?php 
                    $STR = strtolower($acctble_officer_name->value);
                    $STR = strtoupper($STR);
                  ?>
                <td style="font-weight: bold; text-align: center; "><?php echo e($STR); ?></td>
            </tr>
            <tr>
                 <td></td>
                <td style="font-weight: bold;  text-align: center;"><?php echo e($acctble_officer_position->value); ?></td>
            </tr>
    </table>
</div>
</body>
</html>


