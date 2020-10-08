<!DOCTYPE html>
<html>
<head>
    <title>Collections And Deposits</title>
    <style>
        @page { margin: 0px; }
        body {
            margin-bottom: 8px;
            margin-top: 2cm;
            margin-left: 18px;
            margin-right: 18px;

            font-family: arial, "sans-serif";
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, td {
            padding: 1px;
        }
        .center {
            width: 100%;
            text-align: center;
        }
        .border_all_table {
            margin: 1px;
            padding-top: 5px;
        }
        .border_all {
            border: 1px solid #000000;
            font-size: 10px;
        }
        .val {
            text-align: right;
        }
        .hidden {
            display: none;
        }
        .min_width {
            width: 1px;
        }
        .underline {
            border-bottom: 1px solid #000000;
        }
        .ctr {
            text-align: center;
        }
        .remdep {
            width: 50%;
            margin-left: auto;
            margin-right: auto;
        }
        .newpage {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <table class="center">
        <tr>
            <td>RECORD OF REAL PROPERTY TAX COLLECTIONS</td>
        </tr>
        <tr>
            <td>BASIC & SEF</td>
        </tr>
        <tr>
            <td>MUNICIPALITY OF {{ strtoupper($municipality->name) }}</td>
        </tr>
        <tr>
            <td>{{ $date_range }}</td>
        </tr>
    </table>
    <table>
        <tr>
            <td width="50%">Name of Accountable Officer: ISABEL D. KIW-AN - Local Recenue Collection Officer IV</td>
            <td width="35%"></td>
            <td>Report No.</td>
            <td>{{ $_GET['report_no'] }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>Date</td>
            <td>{{ $report_date }}</td>
        </tr>
    </table>

<table class="table">
        <thead>
            <tr>
                <th colspan="4"></th>
                <td><b>Summary</b></td>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php
            $adv_val_total = 0;
            $adv_discount_total = 0;
            $gtotal_gross = 0;
            $gtotal_net = 0;
            $gtotal_gross_total = 0;
            $gtotal_net_total = 0;

            // disposition
            $prv_adv_amount = 0;
            $mnc_adv_amount = 0;
            $brgy_adv_amount = 0;
        ?>
        @foreach($f56_type as $type)
            <?php
                $adv_val = 0;
                $adv_discount = 0;
                $adv_gross = 0;
                $adv_net = 0;
                $adv_gross_total = 0;
                $adv_net_total = 0;
            ?>
            @foreach($receipts as $receipt)
                @foreach($receipt['items'] as $key => $items)
                    <?php
                        $prv_adv_amount += $items->share_provincial;
                        $mnc_adv_amount += $items->share_municipal;
                        $brgy_adv_amount += $items->share_barangay;
                    ?>
                @endforeach
                @foreach($receipt->F56Detailmny as $f56_detail)
                    <?php 
                        $adv_val += $f56_detail['F56Type']->id == $type->id ? $f56_detail->tdrp_assedvalue*.01 : 0;
                        $adv_discount += $f56_detail['F56Type']->id == $type->id ? ($f56_detail->tdrp_assedvalue*.01)*.10 : 0; // fixed, 10% discount for the ff. year
                        $adv_gross += $f56_detail['F56Type']->id == $type->id ? $f56_detail->tdrp_assedvalue*.01 : 0;
                        $adv_net += $f56_detail['F56Type']->id == $type->id ? $f56_detail->tdrp_assedvalue*.01 - (($f56_detail->tdrp_assedvalue*.01)*.10) : 0;

                        $gtotal_gross_total += $f56_detail['F56Type']->id == $type->id ? $f56_detail->tdrp_assedvalue*.01 : 0;
                        $gtotal_net_total += $f56_detail['F56Type']->id == $type->id ? $f56_detail->tdrp_assedvalue*.01 - (($f56_detail->tdrp_assedvalue*.01)*.10) : 0;
                    ?>
                @endforeach
            @endforeach
            <?php
                $adv_gross_total += $adv_gross + $adv_gross;
                $adv_net_total += $adv_net + $adv_net;
                $adv_val_total += $adv_val;
                $adv_discount_total += $adv_discount;
                $gtotal_gross += $adv_gross_total;
                $gtotal_net += $adv_net_total;
            ?>
            <tr>
                <td colspan="5">
                    <span class="hidden">
                    {{ $class_basic_gross = ($class_amt[$type->id]['basic_current'] + $class_amt[$type->id]['basic_previous'] + $class_amt[$type->id]['basic_penalty_current'] + $class_amt[$type->id]['basic_penalty_previous']) }}
                    {{ $class_basic_net = $class_basic_gross - $class_amt[$type->id]['basic_discount'] }}
                    {{ $class_total_gross = $class_basic_gross + $class_basic_gross }}
                    {{ $class_total_net = $class_basic_net + $class_basic_net }}
                    </span>
                </td>
                <td colspan="2">{{ $type->name }}</td>
                <td class="border_all val">{{ number_format($adv_val, 2) }}</td>
                <td class="border_all val">{{ number_format($adv_discount, 2) }}</td>
                <td class="border_all val">{{ number_format($adv_gross, 2) }}</td>
                <td class="border_all val">{{ number_format($adv_net, 2) }}</td>
                <td class="border_all val">{{ number_format($adv_gross_total, 2) }}</td>
                <td class="border_all val">{{ number_format($adv_net_total, 2) }}</td>
                <td></td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: ##429ef5;">
                <td colspan="5"></td>
                <th class="border_all" colspan="2">TOTAL</th>
                <th class="border_all val">{{ number_format($adv_val_total, 2) }}</th>
                <th class="border_all val">{{ number_format($adv_discount_total, 2) }}</th>
                <th class="border_all val">{{ number_format($gtotal_gross_total, 2) }}</th>
                <th class="border_all val">{{ number_format($gtotal_net_total, 2) }}</th>
                <th class="border_all val">{{ number_format($gtotal_gross, 2) }}</th>
                <th class="border_all val">{{ number_format($gtotal_net, 2) }}</th>
                <th></th>
            </tr>
            <tr>
                <td colspan="5"></td>
                <td colspan="8" class="border_all"></td>
                <td></td>
            </tr>

            <tr>
                <th colspan="23">&nbsp;</th>
            </tr>
        </tfoot>
</table>
<table class="table">
    <!-- DISPOSITION SECTION -->
        <tr>
            <th colspan="4"></th>
            <td><b>Disposition </b></td>
            <th colspan="2"></th>
            <th colspan="4" class="border_all">ADVANCED</th>
            <th colspan="2" rowspan="2" class="border_all" style="background-color: ##ebeb34;">TOTAL</th>
        </tr>
        <tr>
            <th colspan="4"></th>
            <td colspan="3">BASIC TAX 1%</td>
            <th class="border_all">%</th>
            <th colspan="1" class="border_all">AMOUNT</th>
            <th colspan="2" class="border_all">DISCOUNT</th>
        </tr>
        <?php
            // ADVANCED
            // basic
            $prv_adv_basic = $adv_val_total * .35; // provincial
            $mnc_adv_basic = $adv_val_total * .4; // municipal
            $brgy_adv_basic = round($adv_val_total, 2) - round($prv_adv_basic, 2) - round($mnc_adv_basic, 2); // barangay

            $pprv_adv_discount = $prv_adv_basic * .10;
            $mmnc_adv_discount = $mnc_adv_basic * .10;
            $bbrgy_adv_discount = round($adv_discount_total, 2) - round($pprv_adv_discount, 2) - round($mmnc_adv_discount, 2);
            $adv_discount_all = round($pprv_adv_discount, 2) + round($mmnc_adv_discount, 2) + round($bbrgy_adv_discount, 2);

            // for SEF..
            $prv_adv_sef = $adv_val_total * .5;
            $mnc_adv_sef = round($adv_val_total, 2) - round($prv_adv_sef, 2);
            // $pprv_adv_sef_discount = $prv_adv_sef * .10;
            
            // $mmnc_adv_discount_basic = round($adv_discount_total, 2) - round($pprv_adv_sef_discount, 2);
            $mmnc_adv_discount_basic = $mnc_adv_sef * .10;

            $pprv_adv_sef_discount = round($adv_discount_total, 2) - round($mmnc_adv_discount_basic, 2);
            $mmnc_adv_sef_discount = round($adv_discount_all, 2) - round($pprv_adv_sef_discount, 2);
            $all_adv_sef_discount = round($pprv_adv_sef_discount, 2) + round($mmnc_adv_sef_discount, 2);
            // total column..
            $pprv_adv_sef = round($prv_adv_sef, 2) - round($pprv_adv_sef_discount, 2);
            $mmnc_adv_sef = round($mnc_adv_sef, 2) - round($mmnc_adv_sef_discount, 2);
            // sef total row
            $total_sef = $prv_adv_sef + $mnc_adv_sef;
            $gtotal_sef = $pprv_adv_sef + $mmnc_adv_sef;

            $split_adv_sef = explode(".", number_format($total_sef, 2));
            $split_adv_discount = explode(".", number_format($all_adv_sef_discount, 2));
            $split_adv_total_sef = explode(".", number_format($gtotal_sef, 2));

            // $final_prv_adv_sef = isset($split_adv_sef[1]) ? (fmod($split_adv_sef[1], 2) > 0 ? $prv_adv_sef + 0.01 : $prv_adv_sef) : $prv_adv_sef;
            $final_prv_adv_discount = isset($split_adv_discount[1]) ? (fmod($split_adv_discount[1], 2) > 0 ? $pprv_adv_sef_discount + 0.01 : $pprv_adv_sef_discount) : $pprv_adv_sef_discount;
            // SEF mnc discount..
            

            // for BASIC..
            // brgy share
            // $bbrgy_adv_discount_basic = ($final_prv_adv_discount - $mmnc_adv_sef_discount) > 0 ? ($bbrgy_adv_discount) + 0.01 : ($bbrgy_adv_discount); 
    
            $pprv_adv_basic = round($prv_adv_basic, 2) - round($pprv_adv_discount, 2);
            $mmnc_adv_basic = round($mnc_adv_basic, 2) - round($mmnc_adv_discount, 2);
            $bbrgy_adv_basic = round($brgy_adv_basic, 2) - round($bbrgy_adv_discount, 2);
            $gtotal_adv = $pprv_adv_basic + round($mmnc_adv_basic, 2) + round($bbrgy_adv_basic, 2);
            $total_adv = round($prv_adv_basic, 2) + round($mnc_adv_basic, 2) + round($brgy_adv_basic, 2);

            // $final_pprv_adv_sef = isset($split_adv_total_sef[1]) ? (fmod($split_adv_total_sef[1], 2) > 0 ? $pprv_adv_sef + 0.01 : $pprv_adv_sef) : $pprv_adv_sef;
        ?>
        <tr>
            <th colspan="4"></th>
            <td colspan="3">Provincial Share</td>
            <td class="border_all ctr">35%</td>
            <td class="border_all val">{{ number_format(round($prv_adv_basic, 2), 2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format(round($pprv_adv_discount, 2), 2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format(round($pprv_adv_basic, 2), 2) }}</td>
        </tr>
        <tr>
            <th colspan="4"></th>
            <td colspan="3">Municipal Share</td>
            <td class="border_all ctr">40%</td>
            <td class="border_all val">{{ number_format(round($mnc_adv_basic, 2), 2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format(round($mmnc_adv_discount, 2), 2)  }}</td>
            <td colspan="2" class="border_all val">{{ number_format(round($mmnc_adv_basic, 2), 2)  }}</td>
        </tr>
        <tr>
            <th colspan="4"></th>
            <td colspan="3">Barangay Share</td>
            <td class="border_all ctr">25%</td>
            <td class="border_all val">{{ number_format(round($brgy_adv_basic, 2), 2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format(round($bbrgy_adv_discount, 2), 2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format(round($bbrgy_adv_basic, 2), 2) }}</td>
        </tr>
        <tr>
            <th colspan="4"></th>
            <th colspan="3">TOTAL(S)</th>
            <td class="border_all"></td>
            <td class="border_all val">{{ number_format(round($total_adv, 2), 2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format(round($adv_discount_all, 2), 2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format(round($gtotal_adv, 2), 2) }}</td>
        </tr>
        <tr>
            <td colspan="7"></td>
            <td colspan="6" class="border_all"></td>
        </tr>

        <tr>
            <th colspan="4"></th>
            <td colspan="3"><b>SEF TAX 1%</b></td>
            <td class="border_all"></td>
            <td class="border_all"></td>
            <td colspan="2" class="border_all"></td>
            <td colspan="2" class="border_all"></td>
        </tr>
        <tr>
            <th colspan="4"></th>
            <td colspan="3">Provincial Share</td>
            <td class="border_all ctr">50%</td>
            <!-- <td class="border_all">{{-- number_format(round($final_prv_adv_sef, 2), 2) --}}</td> -->
            <td class="border_all val">{{ number_format(round($prv_adv_sef, 2), 2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format(round($pprv_adv_sef_discount, 2), 2 ) }}</td> 
            <!-- <td colspan="2" class="border_all val">{{-- number_format(round($final_pprv_adv_sef, 2), 2 ) --}}</td> -->
            <td colspan="2" class="border_all val">{{ number_format(round($pprv_adv_sef, 2), 2 ) }}</td>
        </tr>
        <tr>
            <th colspan="4"></th>
            <td colspan="3">Municipal Share</td>
            <td class="border_all ctr">50%</td>
            <td class="border_all val">{{ number_format(round($mnc_adv_sef, 2), 2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format(round($mmnc_adv_discount_basic, 2), 2 ) }}</td>
            <td colspan="2" class="border_all val">{{ number_format(round($mmnc_adv_sef, 2), 2 ) }}</td>
        </tr>
        <tr>
            <th colspan="4"></th>
            <th colspan="3">TOTAL(S)</th>
            <td class="border_all"></td>
            <td class="border_all val">{{ number_format(round($total_sef, 2), 2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format(round($all_adv_sef_discount, 2), 2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format(round($gtotal_sef, 2), 2) }}</td>
        </tr>
        <tr>
            <td colspan="7"></td>
            <td colspan="6" class="border_all"></td>
        </tr>
</table>

<div style="margin: 40px 290px 0 0 ;font-size:14px;float:right;">
    Certified Correct by:<br><br>
    
    <div style="padding:0 50px 0 50px;">
        <br>
        <br>
    <b>ISABEL D. KIW-AN</b>
    <br>
    <b style="margin-left: 30px">LRCO IV</b>
    </div>
</div>
</body>
</html>

