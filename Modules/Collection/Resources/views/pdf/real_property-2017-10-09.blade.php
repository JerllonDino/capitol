<!DOCTYPE html>
<html>
<head>
    <title>Collections And Deposits</title>
    <style>
        @page { margin: 0px; }
        body {
            margin: 10px;
            font-family: arial, "sans-serif";
            font-size: 8;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, td {
            padding: 2px;
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
			<td></td>
			<td width="70%"></td>
            <td>Report No.</td>
            <td>{{ $_GET['report_no'] }}</td>
        </tr>
        <tr>
			<td>A. COLLECTIONS</td>
			<td></td>
            <td>Date</td>
            <td>{{ date('F d, Y') }}</td>
        </tr>
    </table>
    <table class="border_all_table">
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
      ?>

        @foreach ($receipts as $receipt)
        
            
            
            <span class="hidden">
            {{ $rcpt_done = 0 }}
            </span>

                @if ($receipt->is_cancelled)
                    <tr>
                        <td class="border_all">{{ date('M d', strtotime($receipt->report_date)) }}</td>
                        <td class="border_all" colspan="2" style="color:red;">Cancelled</td>
                        <td class="border_all" colspan="1" style="color:red;">{{ $receipt->serial_no }}</td>
                        <td class="border_all" colspan="19" style="color:red;"></td>
                    </tr>
                @else

                @if (isset($receipt->f56detail))

                @foreach ($receipt->f56detail->TDARP as $tan)
                    @if ($rcpt_done == 0)
                    <tr>
                        <td class="border_all">
                            <span class="hidden">
                            {{ $rcpt_done = 1 }}
                            {{ $basic_gross = $receipt->f56detail->basic_current + $receipt->f56detail->basic_previous + $receipt->f56detail->basic_penalty_current + $receipt->f56detail->basic_penalty_previous }}
                            {{ $basic_net = $basic_gross - $receipt->f56detail->basic_discount }}
                            {{ $total_basic_current += $receipt->f56detail->basic_current }}
                            {{ $total_basic_discount += $receipt->f56detail->basic_discount }}
                            {{ $total_basic_previous += $receipt->f56detail->basic_previous }}
                            {{ $total_basic_penalty_current += $receipt->f56detail->basic_penalty_current }}
                            {{ $total_basic_penalty_previous += $receipt->f56detail->basic_penalty_previous }}
                            {{ $total_basic_gross += $basic_gross }}
                            {{ $total_basic_net += $basic_net }}
                            {{ $gt_gross += ($basic_gross + $basic_gross) }}
                            {{ $gt_net += ($basic_net + $basic_net) }}
                            </span>
                            {{ date('M d', strtotime($receipt->report_date)) }}
                        </td>
                        <td class="border_all">{{ $receipt->customer->name }}</td>
                        <td class="border_all">{{ $receipt->f56detail->period_covered }}</td>
                        <td class="border_all">{{ $receipt->serial_no }}</td>
                        <td class="border_all">{{ $tan->tdarpno }}</td>
                        <td class="border_all">{{  isset($receipt->barangay->name) ? $receipt->barangay->name : '' }}</td>
                        <td class="border_all">{{ substr($receipt->f56detail->F56Type->name, 0, 3) }}</td>
                        <td class="border_all val">{{ number_format($receipt->f56detail->basic_current, 2) }}</td>
                        <td class="border_all val">{{ number_format($receipt->f56detail->basic_discount, 2) }}</td>
                        <td class="border_all val">{{ number_format($receipt->f56detail->basic_previous, 2) }}</td>
                        <td class="border_all val">{{ number_format($receipt->f56detail->basic_penalty_current, 2) }}</td>
                        <td class="border_all val">{{ number_format($receipt->f56detail->basic_penalty_previous, 2) }}</td>
                        <td class="border_all val">{{ number_format($basic_gross, 2) }}</td>
                        <td class="border_all val">{{ number_format($basic_net, 2) }}</td>
                        <td class="border_all val">{{ number_format($receipt->f56detail->basic_current, 2) }}</td>
                        <td class="border_all val">{{ number_format($receipt->f56detail->basic_discount, 2) }}</td>
                        <td class="border_all val">{{ number_format($receipt->f56detail->basic_previous, 2) }}</td>
                        <td class="border_all val">{{ number_format($receipt->f56detail->basic_penalty_current, 2) }}</td>
                        <td class="border_all val">{{ number_format($receipt->f56detail->basic_penalty_previous, 2) }}</td>
                        <td class="border_all val">{{ number_format($basic_gross, 2) }}</td>
                        <td class="border_all val">{{ number_format($basic_net, 2) }}</td>
                        <td class="border_all val">{{ number_format(($basic_gross + $basic_gross), 2) }}</td>
                        <td class="border_all val">{{ number_format(($basic_net + $basic_net), 2) }}</td>
                    </tr>
                    @else
                    <tr>
                        <td class="border_all"></td>
                        <td class="border_all"></td>
                        <td class="border_all"></td>
                        <td class="border_all"></td>
                        <td class="border_all">{{ $tan->tdarpno }}</td>
                        <td class="border_all"></td>
                        <td class="border_all"></td>
                        <td class="border_all"></td>
                        <td class="border_all"></td>
                        <td class="border_all"></td>
                        <td class="border_all"></td>
                        <td class="border_all"></td>
                        <td class="border_all"></td>
                        <td class="border_all"></td>
                        <td class="border_all"></td>
                        <td class="border_all"></td>
                        <td class="border_all"></td>
                        <td class="border_all"></td>
                        <td class="border_all"></td>
                        <td class="border_all"></td>
                        <td class="border_all"></td>
                        <td class="border_all"></td>
                        <td class="border_all"></td>
                    </tr>
                    @endif
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

        <!-- DISPOSITION SECTION -->
        <tr>
            <th colspan="4"></th>
            <td><b>Disposition</b></td>
            <th colspan="2"></th>
            <th colspan="5" class="border_all">CURRENT</th>
            <th colspan="3" class="border_all">PREVIOUS</th>
            <th colspan="6" class="border_all">PENALTIES</th>
            <th colspan="2" rowspan="2" class="border_all">TOTAL</th>
        </tr>
        <tr>
            <th colspan="4"></th>
            <td colspan="3">BASIC TAX 1%</td>
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
            $munshare_basic_current             = ($total_basic_current * .4);
            $munshare_basic_discount            = ($total_basic_discount * .4);
            $munshare_basic_previous            = ($total_basic_previous * .4);
            $munshare_basic_penalty_current     = ($total_basic_penalty_current * .4);
            $munshare_basic_penalty_previous    = ($total_basic_penalty_previous * .4);
            $munshare_basic_net                 = ($total_basic_net * .4);

            $brgyshare_basic_current            = ($total_basic_current * .25);
            $brgyshare_basic_discount           = ($total_basic_discount * .25);
            $brgyshare_basic_previous           = ($total_basic_previous * .25);
            $brgyshare_basic_penalty_current    = ($total_basic_penalty_current * .25);
            $brgyshare_basic_penalty_previous   = ($total_basic_penalty_previous * .25);
            $brgyshare_basic_net                = ($total_basic_net * .25);




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
            ?>

            </span>
            <th colspan="4"></th>
            <td colspan="3">Provincial Share</td>
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
            $xtotal_basic_current = number_format($total_basic_current * .5, 2);
            $xtotal_basic_discount = number_format($total_basic_discount * .5, 2);
            $xtotal_basic_previous = number_format($total_basic_previous * .5, 2);
            $xtotal_basic_penalty_current = number_format($total_basic_penalty_current * .5, 2);
            $xtotal_basic_penalty_previous = number_format($total_basic_penalty_previous * .5, 2);
            $xtotal_basic_net = number_format($total_basic_net * .5, 2);

        ?>
        <tr>
            <th colspan="4"></th>
            <td colspan="3"><b>SEF TAX 1%</b></td>
            <td class="border_all"></td>
            <td colspan="2" class="border_all"></td>
            <td colspan="2" class="border_all"></td>
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
            <td colspan="2" class="border_all val">{{ $xtotal_basic_current }}</td>
            <td colspan="2" class="border_all val">{{ $xtotal_basic_discount }}</td>
            <td class="border_all ctr">50%</td>
            <td colspan="2" class="border_all val">{{ $xtotal_basic_previous }}</td>
            <td class="border_all ctr">50%</td>
            <td colspan="3" class="border_all val">{{ $xtotal_basic_penalty_current }}</td>
            <td colspan="2" class="border_all val">{{ $xtotal_basic_penalty_previous }}</td>
            <td colspan="2" class="border_all val">{{ $xtotal_basic_net }}</td>
        </tr>
        <tr>
            <th colspan="4"></th>
            <td colspan="3">Municipal Share</td>
            <td class="border_all ctr">50%</td>
            <td colspan="2" class="border_all val">
            @if (strlen(substr(strrchr(($total_basic_current * .5), "."), 1)) > 2)
                {{ number_format(($total_basic_current * .5) - 0.01, 2) }}
            @else
                {{ number_format($total_basic_current * .5, 2) }}
            @endif
            </td>
            <td colspan="2" class="border_all val">
            @if (strlen(substr(strrchr(($total_basic_discount * .5), "."), 1)) > 2)
                {{ number_format(($total_basic_discount * .5) - 0.01, 2) }}
            @else
                {{ number_format($total_basic_discount * .5, 2) }}
            @endif
            </td>
            <td class="border_all ctr">50%</td>
            <td colspan="2" class="border_all val">
            @if (strlen(substr(strrchr(($total_basic_previous * .5), "."), 1)) > 2)
                {{ number_format(($total_basic_previous * .5) - 0.01, 2) }}
            @else
                {{ number_format($total_basic_previous * .5, 2) }}
            @endif
            </td>
            <td class="border_all ctr">50%</td>
            <td colspan="3" class="border_all val">
            @if (strlen(substr(strrchr(($total_basic_penalty_current * .5), "."), 1)) > 2)
                {{ number_format(($total_basic_penalty_current * .5) - 0.01, 2) }}
            @else
                {{ number_format($total_basic_penalty_current * .5, 2) }}
            @endif
            </td>
            <td colspan="2" class="border_all val">
            @if (strlen(substr(strrchr(($total_basic_penalty_previous * .5), "."), 1)) > 2)
                {{ number_format(($total_basic_penalty_previous * .5) - 0.01, 2) }}
            @else
                {{ number_format($total_basic_penalty_previous * .5, 2) }}
            @endif
            </td>
            <td colspan="2" class="border_all val">
            @if (strlen(substr(strrchr(($total_basic_net * .5), "."), 1)) > 2)
                {{ number_format(($total_basic_net * .5) - 0.01, 2) }}
            @else
                {{ number_format($total_basic_net * .5, 2) }}
            @endif
            </td>
        </tr>
        <tr>
            <th colspan="4"></th>
            <th colspan="3">TOTAL(S)</th>
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
    </table>
</body>
</html>