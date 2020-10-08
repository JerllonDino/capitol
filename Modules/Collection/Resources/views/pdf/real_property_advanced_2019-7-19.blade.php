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
    <table class="hidden">
        <tr>
            <td></td>
            <td width="70%"></td>
            <td>Report No.</td>
            <td>{{ $_GET['report_no'] }}</td>
        </tr>
        <tr>
            <td>A. COLLECTIONS</td>
            <td></td>
            <td>Date</td>
            <td>{{ $report_date }}</td>
        </tr>
    </table>
    <table class="border_all_table hidden">
        <tr>
            <th class="border_all" rowspan="3">Date</th>
            <th class="border_all" rowspan="3">Tax Payor</th>
            <th class="border_all" rowspan="3">Period Covered</th>
            <th class="border_all" rowspan="3">OR No.</th>
            <th class="border_all" rowspan="3">TD/ARP</th>
            <th class="border_all" rowspan="3">Brgy.</th>
            <th class="border_all" rowspan="3">Class</th>
            <th class="border_all" colspan="7">Basic Tax</th>
            <th class="border_all" colspan="7">SEF</th>
            <th class="border_all" rowspan="3">Grand Total (gross)</th>
            <th class="border_all" rowspan="3">Grand Total (net)</th>
        </tr>
        <tr>
            <th class="border_all" rowspan="2">Current Year Gross Amt.</th>
            <th class="border_all" rowspan="2">Discount</th>
            <th class="border_all" rowspan="2">Previous Years</th>
            <th class="border_all" colspan="2">Penalties</th>
            <th class="border_all" rowspan="2">Sub Total Gross Collections</th>
            <th class="border_all" rowspan="2">Sub Total Net Collections</th>
            <th class="border_all" rowspan="2">Current Year Gross Amt.</th>
            <th class="border_all" rowspan="2">Discount</th>
            <th class="border_all" rowspan="2">Previous Years</th>
            <th class="border_all" colspan="2">Penalties</th>
            <th class="border_all" rowspan="2">Sub Total Gross Collections</th>
            <th class="border_all" rowspan="2">Sub Total Net Collections</th>
        </tr>
        <tr>
            <th class="border_all">Current Year</th>
            <th class="border_all">Previous Years</th>
            <th class="border_all">Current Year</th>
            <th class="border_all">Previous Years</th>
        </tr>

        <!-- ROWS HERE -->
        <?php
            $total_basic_current = 0;
            $total_basic_discount = 0;
            $total_basic_previous = 0;
            $total_basic_penalty_current = 0;
            $total_basic_penalty_previous = 0;
            $total_basic_gross = 0;
            $total_basic_net = 0;
            $gt_gross = 0;
            $gt_net = 0;
            $counter = 0;
            $total_adv = 0;
      ?>

        @foreach ($receipts as $receipt)
           @php
             $rcpt_done = 0;
            @endphp

                @if ($receipt->is_cancelled)
                    <tr>
                        <td class="border_all">{{ date('M d', strtotime($receipt->report_date)) }}</td>
                        <td class="border_all" colspan="2" style="color:red;">Cancelled</td>
                        <td class="border_all" colspan="1" style="color:red;">{{ $receipt->serial_no }}</td>
                        <td class="border_all" colspan="19" style="color:red;"></td>
                    </tr>
                @else

                @if ( $receipt->F56Detailmny()->count() > 0 )
                @foreach ($receipt->F56Detailmny as $f56_detail)
                <?php
                    $counter++; 
                    $basic_gross = $f56_detail->basic_current + $f56_detail->basic_previous + $f56_detail->basic_penalty_current + $f56_detail->basic_penalty_previous;
                    $basic_net = $basic_gross - $f56_detail->basic_discount;
                    $total_basic_current += $f56_detail->basic_current;
                    $total_basic_discount += $f56_detail->basic_discount;
                    $total_basic_previous += $f56_detail->basic_previous;
                    $total_basic_penalty_current += $f56_detail->basic_penalty_current;
                    $total_basic_penalty_previous += $f56_detail->basic_penalty_previous;
                    $total_basic_gross += $basic_gross;
                    $total_basic_net += $basic_net;
                    $gt_gross += ($basic_gross + $basic_gross);
                    $gt_net += ($basic_net + $basic_net);
                ?>
                    
                    <tr>
                        @if ($rcpt_done == 0)
                               <?php   $rcpt_done = 1; ?>
                            <td class="border_all" >
                                {{ date('M d', strtotime($receipt->report_date)) }}
                            </td>
                            <td class="border_all"  >{{ $receipt->customer->name }}</td>
                            <td class="border_all"  >{{ $f56_detail->period_covered }}</td>
                            <td class="border_all"  >{{ $receipt->serial_no }}</td>
                        @else
                            <td class="border_all"></td>
                            <td class="border_all"></td>
                            <td class="border_all"></td>
                            <td class="border_all"></td>
                        @endif
                        @if(!isset($f56_detail->TDARP[0]))
                            <td class="border_all"></td>
                            <td class="border_all">{{  isset($f56_detail->TDARPX->barangay_name->name) ? $f56_detail->TDARPX->barangay_name->name : '' }}</td>
                            <td class="border_all">{{ substr($f56_detail->F56Type->name, 0, 3) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_current, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_discount, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_previous, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_penalty_current, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_penalty_previous, 2) }}</td>
                            <td class="border_all val">{{ number_format($basic_gross, 2) }}</td>
                            <td class="border_all val">{{ number_format($basic_net, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_current, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_discount, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_previous, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_penalty_current, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_penalty_previous, 2) }}</td>
                            <td class="border_all val">{{ number_format($basic_gross, 2) }}</td>
                            <td class="border_all val">{{ number_format($basic_net, 2) }}</td>
                            <td class="border_all val">{{ number_format(($basic_gross + $basic_gross), 2) }}</td>
                            <td class="border_all val">{{ number_format(($basic_net + $basic_net), 2) }}</td>
                        @else
                            <td class="border_all">{{ $f56_detail->TDARP[0]->tdarpno }}</td>
                            <td class="border_all">{{  isset($f56_detail->TDARPX->barangay_name->name) ? $f56_detail->TDARPX->barangay_name->name : '' }}</td>
                            <td class="border_all">{{ substr($f56_detail->F56Type->name, 0, 3) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_current, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_discount, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_previous, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_penalty_current, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_penalty_previous, 2) }}</td>
                            <td class="border_all val">{{ number_format($basic_gross, 2) }}</td>
                            <td class="border_all val">{{ number_format($basic_net, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_current, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_discount, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_previous, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_penalty_current, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_penalty_previous, 2) }}</td>
                            <td class="border_all val">{{ number_format($basic_gross, 2) }}</td>
                            <td class="border_all val">{{ number_format($basic_net, 2) }}</td>
                            <td class="border_all val">{{ number_format(($basic_gross + $basic_gross), 2) }}</td>
                            <td class="border_all val">{{ number_format(($basic_net + $basic_net), 2) }}</td>
                        @endif
                       

                    </tr>
                   
                @endforeach
                @endif
                @endif

        @endforeach
        <tr>
            <th class="border_all" colspan="7">TOTAL COLLECTION</th>
            <th class="border_all val">{{ number_format($total_basic_current, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_discount, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_previous, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_penalty_current, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_penalty_previous, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_gross, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_net, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_current, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_discount, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_previous, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_penalty_current, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_penalty_previous, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_gross, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_net, 2) }}</th>
            <th class="border_all val">{{ number_format($gt_gross, 2) }}</th>
            <th class="border_all val">{{ number_format($gt_net, 2) }}</th>
        </tr>
        <tr>
            <td colspan="23" class="border_all"></td>
        </tr>

        <tr>
            <th colspan="23">&nbsp;</th>
        </tr>
        
    </table>

<table class="table">
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

        @foreach($f56_type as $type)
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
            <td class="border_all val">{{ number_format($class_amt[$type->id]['basic_current'], 2) }}</td>
            <td class="border_all val">{{ number_format($class_amt[$type->id]['basic_discount'], 2) }}</td>
            <td class="border_all val">{{ number_format($class_amt[$type->id]['basic_previous'], 2) }}</td>
            <td class="border_all val">{{ number_format($class_amt[$type->id]['basic_penalty_current'], 2) }}</td>
            <td class="border_all val">{{ number_format($class_amt[$type->id]['basic_penalty_previous'], 2) }}</td>
            <td class="border_all val">{{ number_format($class_basic_gross, 2) }}</td>
            <td class="border_all val">{{ number_format($class_basic_net, 2) }}</td>
            <td class="border_all val">{{ number_format($class_amt[$type->id]['basic_current'], 2) }}</td>
            <td class="border_all val">{{ number_format($class_amt[$type->id]['basic_discount'], 2) }}</td>
            <td class="border_all val">{{ number_format($class_amt[$type->id]['basic_previous'], 2) }}</td>
            <td class="border_all val">{{ number_format($class_amt[$type->id]['basic_penalty_current'], 2) }}</td>
            <td class="border_all val">{{ number_format($class_amt[$type->id]['basic_penalty_previous'], 2) }}</td>
            <td class="border_all val">{{ number_format($class_basic_gross, 2) }}</td>
            <td class="border_all val">{{ number_format($class_basic_net, 2) }}</td>
            <td class="border_all val">{{ number_format($class_total_gross, 2) }}</td>
            <td class="border_all val">{{ number_format($class_total_net, 2) }}</td>
        </tr>
        @endforeach

        <tr>
            <td colspan="5"></td>
            <th class="border_all" colspan="2">TOTAL</th>
            <th class="border_all val">{{ number_format($total_basic_current, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_discount, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_previous, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_penalty_current, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_penalty_previous, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_gross, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_net, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_current, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_discount, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_previous, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_penalty_current, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_penalty_previous, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_gross, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_net, 2) }}</th>
            <th class="border_all val">{{ number_format($gt_gross, 2) }}</th>
            <th class="border_all val">{{ number_format($gt_net, 2) }}</th>
        </tr>
        <tr>
            <td colspan="5"></td>
            <td colspan="18" class="border_all"></td>
        </tr>

        <tr>
            <th colspan="23">&nbsp;</th>
        </tr>

</table>

<table class="table">
    <!-- DISPOSITION SECTION -->
        <tr>
            <th colspan="4"></th>
            <td><b>Disposition </b></td>
            <th colspan="2"></th>
            <th colspan="4" class="border_all">ADVANCED</th>
            <th colspan="5" class="border_all">CURRENT</th>
            <th colspan="3" class="border_all">PREVIOUS</th>
            <th colspan="6" class="border_all">PENALTIES</th>
            <th colspan="2" rowspan="2" class="border_all">TOTAL</th>
        </tr>
        <tr>
            <th colspan="4"></th>
            <td colspan="3">BASIC TAX 1%</td>
            <th class="border_all">%</th>
            <th colspan="1">AMOUNT</th>
            <th colspan="2" class="border_all">DISCOUNT</th>
            <th class="border_all">%</th>
            <th colspan="2" class="border_all">AMOUNT</th>
            <th colspan="2" class="border_all">DISCOUNT</th>
            <th class="border_all">%</th>
            <th colspan="2" class="border_all">AMOUNT</th>
            <th class="border_all">%</th>
            <th colspan="3" class="border_all">CURRENT</th>
            <th colspan="2" class="border_all">PREVIOUS</th>
        </tr>
        <tr>
            <span class="hidden">

            <?php
                $munshare_basic_current             = round($total_basic_current * .4,2);
                $munshare_basic_discount            = round($total_basic_discount * .4,2);
                $munshare_basic_previous            = round($total_basic_previous * .4,2);
                $munshare_basic_penalty_current     = round($total_basic_penalty_current * .4,2);
                $munshare_basic_penalty_previous    = round($total_basic_penalty_previous * .4,2);
                $munshare_basic_net                 = round($total_basic_net * .4,2);

                $brgyshare_basic_current            = round($total_basic_current * .25,2);
                $brgyshare_basic_discount           = round($total_basic_discount * .25,2);
                $brgyshare_basic_previous           = round($total_basic_previous * .25,2);
                $brgyshare_basic_penalty_current    = round($total_basic_penalty_current * .25,2);
                $brgyshare_basic_penalty_previous   = round($total_basic_penalty_previous * .25,2);
                $brgyshare_basic_net                = round($total_basic_net * .25,2);

                /* BASIC DISPOSITION*/
                $prv_crnt_ammount = ($total_basic_current - ($munshare_basic_current + $brgyshare_basic_current));
                $prv_crnt_discount = ($total_basic_discount - ($munshare_basic_discount + $brgyshare_basic_discount));
                $prv_prvious_ammount = ($total_basic_previous - ($munshare_basic_previous + $brgyshare_basic_previous));
                $prv_pnalties_crnt = $total_basic_penalty_current - ($munshare_basic_penalty_current + $brgyshare_basic_penalty_current);
                $prv_pnalties_prvious = $total_basic_penalty_previous - ($munshare_basic_penalty_previous + $brgyshare_basic_penalty_previous);

                $pprv_crnt_ammount = round($prv_crnt_ammount,2,PHP_ROUND_HALF_UP);
                $bbrgyshare_basic_current = round($brgyshare_basic_current,2,PHP_ROUND_HALF_DOWN);

                $pprv_crnt_discount = round($prv_crnt_discount,2,PHP_ROUND_HALF_DOWN);
                $bbrgyshare_basic_discount = round($brgyshare_basic_discount,2,PHP_ROUND_HALF_UP);

                $pprv_prvious_ammount = round($prv_prvious_ammount,2,PHP_ROUND_HALF_UP);
                $bbrgyshare_basic_previous = round($brgyshare_basic_previous,2,PHP_ROUND_HALF_DOWN);

                $pprv_pnalties_crnt =  round($prv_pnalties_crnt,2,PHP_ROUND_HALF_UP);
                $bbrgyshare_basic_penalty_current = round($brgyshare_basic_penalty_current,2,PHP_ROUND_HALF_DOWN);

                $pprv_pnalties_prvious = round($prv_pnalties_prvious,2,PHP_ROUND_HALF_UP);

                $bbrgyshare_basic_penalty_previous = round($brgyshare_basic_penalty_previous,2,PHP_ROUND_HALF_DOWN);

                $pprv_total_basic = ($pprv_crnt_ammount - $pprv_crnt_discount  + $pprv_prvious_ammount + $pprv_pnalties_crnt + $pprv_pnalties_prvious);

                $mncpal_total_basic = ($munshare_basic_current - $munshare_basic_discount  + $munshare_basic_previous + $munshare_basic_penalty_current + $munshare_basic_penalty_previous);

                $brgy_total_basic = ($bbrgyshare_basic_current - $bbrgyshare_basic_discount  + $bbrgyshare_basic_previous + $bbrgyshare_basic_penalty_current + $bbrgyshare_basic_penalty_previous);

                $total_basic_net = $pprv_total_basic + $mncpal_total_basic + $brgy_total_basic;

                $total_basic_current = $pprv_crnt_ammount + $munshare_basic_current + $bbrgyshare_basic_current;

                // ADVANCED
                $prv_adv = $pprv_crnt_ammount - ($pprv_crnt_ammount * ($pprv_crnt_discount/100)) + ($pprv_crnt_ammount * ($pprv_pnalties_crnt/100));
                $mnc_adv = $munshare_basic_current - ($munshare_basic_current * ($munshare_basic_discount/100)) + ($munshare_basic_current * ($pprv_pnalties_crnt/100));
                $brgy_adv = $bbrgyshare_basic_current - ($bbrgyshare_basic_current * ($munshare_basic_discount/100)) + ($bbrgyshare_basic_current * ($bbrgyshare_basic_discount/100));
                $total_adv = $prv_adv + $mnc_adv + $brgy_adv;
            ?>
            </span>
            <th colspan="4"></th>
            <td colspan="3">Provincial Share</td>
            <td class="border_all ctr">35%</td>
            <td class="border_all">{{ number_format($prv_adv, 2) }}</td>
            <td colspan="2" class="border_all val">       {{ number_format($pprv_crnt_discount , 2) }}           </td>
            <td class="border_all ctr">35%</td>
            <td colspan="2" class="border_all val">       {{ number_format($pprv_crnt_ammount , 2) }}           </td>
            <td colspan="2" class="border_all val">       {{ number_format($pprv_crnt_discount , 2) }}           </td>
            <td class="border_all ctr">35%</td>
            <td colspan="2" class="border_all val">       {{ number_format($pprv_prvious_ammount, 2) }}      </td>
            <td class="border_all ctr">35%</td>
            <td colspan="3" class="border_all val">       {{ number_format($pprv_pnalties_crnt, 2) }}            </td>
            <td colspan="2" class="border_all val">       {{ number_format($pprv_pnalties_prvious, 2)}}       </td>
            <td colspan="2" class="border_all val">       {{ number_format($pprv_total_basic, 2) }}           </td>
        </tr>
        <tr>
            <th colspan="4"></th>
            <td colspan="3">Municipal Share</td>
            <td class="border_all ctr">40%</td>
            <td class="border_all">{{ number_format($mnc_adv, 2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format($munshare_basic_discount,2)  }}</td>
            <td class="border_all ctr">40%</td>
            <td colspan="2" class="border_all val">{{ number_format($munshare_basic_current,2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format($munshare_basic_discount,2)  }}</td>
            <td class="border_all ctr">40%</td>
            <td colspan="2" class="border_all val">{{ number_format($munshare_basic_previous,2)  }}</td>
            <td class="border_all ctr">40%</td>
            <td colspan="3" class="border_all val">{{ number_format($munshare_basic_penalty_current,2)  }}</td>
            <td colspan="2" class="border_all val">{{ number_format($munshare_basic_penalty_previous,2)  }}</td>
            <td colspan="2" class="border_all val">{{ number_format($mncpal_total_basic,2)  }}</td>
        </tr>
        <tr>
            <th colspan="4"></th>
            <td colspan="3">Barangay Share</td>
            <td class="border_all ctr">25%</td>
            <td class="border_all">{{ number_format($brgy_adv, 2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format($bbrgyshare_basic_discount,2) }}</td>
            <td class="border_all ctr">25%</td>
            <td colspan="2" class="border_all val">{{ number_format($bbrgyshare_basic_current,2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format($bbrgyshare_basic_discount,2) }}</td>
            <td class="border_all ctr">25%</td>
            <td colspan="2" class="border_all val">{{ number_format($bbrgyshare_basic_previous,2) }}</td>
            <td class="border_all ctr">25%</td>
            <td colspan="3" class="border_all val">{{ number_format($bbrgyshare_basic_penalty_current,2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format($bbrgyshare_basic_penalty_previous,2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format($brgy_total_basic,2) }}</td>
        </tr>
        <tr>
            <th colspan="4"></th>
            <th colspan="3">TOTAL(S)</th>
            <td class="border_all"></td>
            <td class="border_all">{{ number_format($total_adv) }}</td>
            <td colspan="2" class="border_all val">{{ number_format($total_basic_discount, 2) }}</td>
            <td class="border_all"></td>
            <td colspan="2" class="border_all val">{{ number_format($total_basic_current, 2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format($total_basic_discount, 2) }}</td>
            <td class="border_all"></td>
            <td colspan="2" class="border_all val">{{ number_format($total_basic_previous, 2) }}</td>
            <td class="border_all"></td>
            <td colspan="3" class="border_all val">{{ number_format($total_basic_penalty_current, 2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format($total_basic_penalty_previous, 2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format($total_basic_net, 2) }}</td>
        </tr>
        <tr>
            <td colspan="7"></td>
            <td colspan="16" class="border_all"></td>
        </tr>

        <?php
            $xtotal_basic_current = round($total_basic_current * .5,2,PHP_ROUND_HALF_UP);
            $xtotal_basic_discount = round($total_basic_discount*.5,2,PHP_ROUND_HALF_DOWN) ;
            $xtotal_basic_previous = $total_basic_previous * .5;
            $xtotal_basic_penalty_current = $total_basic_penalty_current * .5;
            $xtotal_basic_penalty_previous = $total_basic_penalty_previous * .5;
            $xtotal_basic_net = round($xtotal_basic_current,2) - round($xtotal_basic_discount,2) + round($xtotal_basic_previous,2)  + round($xtotal_basic_penalty_current,2) + round($xtotal_basic_penalty_previous,2);
            
            $sef_mncpl_crnt = round($total_basic_current*.5,3,PHP_ROUND_HALF_DOWN);

            $sef_mncpl_crnt_e = explode('.', $sef_mncpl_crnt);
            $sef_mncpl_crnt_ex = $sef_mncpl_crnt_e[1] ?? '0';
            if(substr($sef_mncpl_crnt_ex,2,3) >= 5){
                $sef_mncpl_crnt = $sef_mncpl_crnt_e[0].'.'.substr($sef_mncpl_crnt_ex,0,2);
            }

            $sef_mncpl_dscnt = round($total_basic_discount *.5,2,PHP_ROUND_HALF_UP);
            $sef_mncpl_prev = round($total_basic_previous *.5,2,PHP_ROUND_HALF_DOWN);
            $sef_mncpl_pen_crnt = round($total_basic_penalty_current *.5,2,PHP_ROUND_HALF_DOWN);
            $sef_mncpl_pen_crnt_prev = round($total_basic_penalty_previous *.5,2,PHP_ROUND_HALF_DOWN);

            $sef_total_basic_net = round($sef_mncpl_crnt,2) - round($sef_mncpl_dscnt,2) + round($sef_mncpl_prev,2) + round($sef_mncpl_pen_crnt,2) + round($sef_mncpl_pen_crnt_prev,2) ;

            // ADVANCED
            $prov_sef_adv  = $xtotal_basic_current - ($xtotal_basic_current * ($xtotal_basic_discount/100)) + ($xtotal_basic_current * ($xtotal_basic_penalty_current/100));
            $mnc_sef_adv  = $sef_mncpl_crnt - ($sef_mncpl_crnt * ($sef_mncpl_dscnt/100)) + ($sef_mncpl_crnt * ($sef_mncpl_pen_crnt/100));
            $total_sef_adv = $prov_sef_adv + $mnc_sef_adv;
        ?>
        <tr>
            <th colspan="4"></th>
            <td colspan="3"><b>SEF TAX 1%</b></td>
            <td class="border_all"></td>
            <td class="border_all"></td>
            <td colspan="2" class="border_all"></td>
            <td class="border_all"></td>
            <td colspan="2" class="border_all"></td>
            <td colspan="2" class="border_all"></td> <!---->
            <td class="border_all"></td>
            <td colspan="2" class="border_all"></td>
            <td class="border_all"></td>
            <td colspan="3" class="border_all"></td>
            <td colspan="2" class="border_all"></td>
            <td colspan="2" class="border_all"></td>
        </tr>
        <tr>
            <th colspan="4"></th>
            <td colspan="3">Provincial Share</td>
            <td class="border_all ctr">50%</td>
            <td class="border_all">{{ number_format($prov_sef_adv, 2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format( $xtotal_basic_discount, 2 ) }}</td>
            <td class="border_all ctr">50%</td>
            <td colspan="2" class="border_all val">{{ number_format( $xtotal_basic_current, 2 ) }}</td>
            <td colspan="2" class="border_all val">{{ number_format( $xtotal_basic_discount, 2 ) }}</td>
            <td class="border_all ctr">50%</td>
            <td colspan="2" class="border_all val">{{ number_format( $xtotal_basic_previous, 2 ) }}</td>
            <td class="border_all ctr">50%</td>
            <td colspan="3" class="border_all val">{{ number_format( $xtotal_basic_penalty_current, 2 ) }}</td>
            <td colspan="2" class="border_all val">{{ number_format( $xtotal_basic_penalty_previous, 2 ) }}</td>
            <td colspan="2" class="border_all val">{{ number_format( $xtotal_basic_net, 2 ) }}</td>
        </tr>
        <tr>
            <th colspan="4"></th>
            <td colspan="3">Municipal Share</td>
            <td class="border_all ctr">50%</td>
            <td class="border_all">{{ number_format($mnc_sef_adv, 2) }}</td>
            <td colspan="2" class="border_all val">
                {{ number_format( $sef_mncpl_dscnt, 2 ) }}
            </td>
            <td class="border_all ctr">50%</td>
            <td colspan="2" class="border_all val">
                {{ number_format( $sef_mncpl_crnt, 2 ) }}
            </td>
            <td colspan="2" class="border_all val">
                {{ number_format( $sef_mncpl_dscnt, 2 ) }}
            </td>
            <td class="border_all ctr">50%</td>
            <td colspan="2" class="border_all val">
                {{ number_format( $sef_mncpl_prev, 2 ) }}
            </td>
            <td class="border_all ctr">50%</td>
            <td colspan="3" class="border_all val">
                {{ number_format( $sef_mncpl_pen_crnt, 2 ) }}
            </td>
            <td colspan="2" class="border_all val">
            {{ number_format( $sef_mncpl_pen_crnt_prev, 2 )  }}
            </td>
            <td colspan="2" class="border_all val">
                {{ number_format( $sef_total_basic_net, 2 ) }}
            </td>
        </tr>
        <tr>
            <th colspan="4"></th>
            <th colspan="3">TOTAL(S)</th>
            <td class="border_all"></td>
            <td class="border_all">{{ number_format($total_sef_adv, 2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format($total_basic_discount, 2) }}</td>
            <td class="border_all"></td>
            <td colspan="2" class="border_all val">{{ number_format($total_basic_current, 2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format($total_basic_discount, 2) }}</td>
            <td class="border_all"></td>
            <td colspan="2" class="border_all val">{{ number_format($total_basic_previous, 2) }}</td>
            <td class="border_all"></td>
            <td colspan="3" class="border_all val">{{ number_format($total_basic_penalty_current, 2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format($total_basic_penalty_previous, 2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format($total_basic_net, 2) }}</td>
        </tr>
        <tr>
            <td colspan="7"></td>
            <td colspan="20" class="border_all"></td>
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

