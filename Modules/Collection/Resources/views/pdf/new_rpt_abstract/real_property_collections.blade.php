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
            font-size: 8;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
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
        .border_all_table tr th, .border_all_table tr td  {
            border: 1px solid #000000;
            /*font-size: 8px;*/ /* 10px */
            text-align: center;
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
        .table-center {
            text-align: center;
            padding: 0;
            margin: 0;
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
        @if(isset($mun->name))
        <tr>
            <td>MUNICIPALITY OF {{ strtoupper($mun->name) }}</td>
        </tr>
        @endif
        <tr>
            <td>{{ $date_range }}</td>
        </tr>
    </table>
    <table>
        <tr>
            <td width="50%">Name of Accountable Officer: ISABEL D. KIW-AN - Local Recenue Collection Officer IV</td>
            <td width="35%"></td>
            @if(isset($report_no))
            <td>Report No.</td>
            <td>{{ $report_no }}</td>
            @endif
        </tr>
        <tr>
            <td>A. COLLECTIONS</td>
            <td></td>
            <td>Date</td>
            <td>{{ $report_date }}</td>
        </tr>
    </table>
    <?php
        // $prior_start = \Carbon\Carbon::now()->subYears(2)->format('Y');
        // $preceeding = \Carbon\Carbon::now()->subYear()->format('Y');
        // $advance_yr = \Carbon\Carbon::now()->addYear()->format('Y');
        // $current = \Carbon\Carbon::now()->format('Y');
    ?>
    <table class="border_all_table">
        <thead>
            <tr>
                <th rowspan="4">Date</th>
                <th rowspan="4">Name of Tax Payor</th>
                <th rowspan="4">Period Covered</th>
                <th rowspan="4">Official Receipt Number</th>
                <th rowspan="4">TD/ARP No.</th>
                <th rowspan="4">Name of Brgy.</th>
                <th rowspan="4">Classifi <br> cation</th>
                <th colspan="11">BASIC TAX</th>
                <th rowspan="4">Sub-total Gross Collection</th>
                <th rowspan="4">Sub-total Net Collection</th>
                <th colspan="11">SPECIAL EDUCATION FUND</th>
                <th rowspan="4">Sub-total Gross Collection</th>
                <th rowspan="4">Sub-total Net Collection</th>
                <th rowspan="4">Grand Total Gross Collection</th>
                <th rowspan="4">Grand Total Net Collection</th>
            </tr>
            <tr>
                <!-- basic --> 
                <th colspan="2" rowspan="2">Advance</th>
                <th colspan="2" rowspan="2">Current Year</th>
                <th rowspan="3">{{ $preceeding }}</th>
                <th colspan="2" rowspan="2">PRIOR YEARS</th>
                <th colspan="4">PENALTIES</th>
                <!-- sef --> 
                <th colspan="2" rowspan="2">Advance</th>
                <th colspan="2" rowspan="2">Current Year</th>
                <th rowspan="3">{{ $preceeding }}</th>
                <th colspan="2" rowspan="2">PRIOR YEARS</th>
                <th colspan="4">PENALTIES</th>
            </tr>
            <tr>
                <!-- basic -->
                <th rowspan="2">Current Year</th>
                <th rowspan="2">{{ $preceeding }}</th>
                <th colspan="2">PRIOR YEARS</th>
                <!-- sef -->
                <th rowspan="2">Current Year</th>
                <th rowspan="2">{{ $preceeding }}</th>
                <th colspan="2">PRIOR YEARS</th>
            </tr>
            <tr>
                <!-- basic -->
                <th>Gross Amount</th>
                <th>
                    D<br>
                    I<br>
                    S<br>
                    C<br>
                    O<br>
                    U<br>
                    N<br>
                    T<br>
                </th>
                <th>Gross Amount</th>
                <th>
                    D<br>
                    I<br>
                    S<br>
                    C<br>
                    O<br>
                    U<br>
                    N<br>
                    T<br>
                </th>
                <th>{{ $prior_start }}-1992</th>
                <th>1991 & Below</th>
                <th>{{ $prior_start }}-1992</th>
                <th>1991 & Below</th>

                <!-- sef -->
                <th>Gross Amount</th>
                <th>
                    D<br>
                    I<br>
                    S<br>
                    C<br>
                    O<br>
                    U<br>
                    N<br>
                    T<br>
                </th>
                <th>Gross Amount</th>
                <th>
                    D<br>
                    I<br>
                    S<br>
                    C<br>
                    O<br>
                    U<br>
                    N<br>
                    T<br>
                </th>
                <th>{{ $prior_start }}-1992</th>
                <th>1991 & Below</th>
                <th>{{ $prior_start }}-1992</th>
                <th>1991 & Below</th>
            </tr>
        </thead>
        <tbody>
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

                // immediate preceeding year
                $total_preceed = 0;

                // prior years
                $total_prior_1992 = 0; // for 1992 and above
                $total_prior_1991 = 0; // for 1991 and below
                $total_penalty_prior_1992 = 0;
                $total_penalty_prior_1991 = 0;

                // advance
                $total_adv = 0;
                $total_adv_discount = 0;
            ?>
            @foreach($receipts as $receipt)
                @php
                    $rcpt_done = 0;
                    $entry_date = \Carbon\Carbon::parse($receipt->date_of_entry);
                @endphp
                @if ($receipt->is_cancelled)
                    <tr>
                        <td>{{ date('M d', strtotime($receipt->date_of_entry)) }}</td>
                        <td colspan="2" style="color:red;">Cancelled</td>
                        <td colspan="1" style="color:red;">{{ $receipt->serial_no }}</td>
                        <td colspan="31" style="color:red;"></td>
                    </tr>
                @elseif($receipt->F56Detailmny()->count() > 0)
                    @foreach ($receipt->F56Detailmny as $f56_detail)
                    <?php
                        $counter++; 
                        // current
                        if($f56_detail->period_covered == $current) {
                            $total_basic_current += $f56_detail->basic_current;
                            $total_basic_discount += $f56_detail->basic_discount;
                            $total_basic_penalty_current += $f56_detail->basic_penalty_current;
                        }

                        // immediate preceeding year
                        if($f56_detail->period_covered == $preceeding) {
                            $total_basic_previous += $f56_detail->basic_previous;
                            $total_basic_penalty_previous += $f56_detail->basic_penalty_previous;
                            $total_preceed += $f56_detail->basic_previous + $f56_detail->basic_penalty_previous;
                        }

                        if($f56_detail->period_covered >= $advance_yr) {
                            // $basic_gross = $f56_detail->basic_penalty_previous + $f56_detail->basic_previous + ($f56_detail->tdrp_assedvalue * .01);
                            // $total_adv += $f56_detail->tdrp_assedvalue * .01;
                            // $total_adv_discount += ($f56_detail->tdrp_assedvalue * .01) * .10;

                            $basic_gross = $f56_detail->basic_penalty_previous + $f56_detail->basic_previous + $f56_detail->basic_current;
                            $total_adv += $f56_detail->basic_current;
                            $total_adv_discount += $f56_detail->basic_discount;
                        } else if($f56_detail->period_covered == $current) {
                            $basic_gross = $f56_detail->basic_current + $f56_detail->basic_penalty_current + $f56_detail->basic_penalty_previous + $f56_detail->basic_previous;
                        } else {
                            $basic_gross = $f56_detail->basic_penalty_previous + $f56_detail->basic_previous;
                        }
                        if($f56_detail->period_covered >= $advance_yr) {
                            // $basic_net = $basic_gross - ($f56_detail->tdrp_assedvalue * .01) * .10;
                            $basic_net = $basic_gross - $f56_detail->basic_discount;
                        } else if($f56_detail->period_covered == $current) {
                            $basic_net = $basic_gross - $f56_detail->basic_discount;
                        } else {
                            $basic_net = $basic_gross;
                        }
                        $total_basic_gross += $basic_gross;
                        $total_basic_net += $basic_net;
                        $gt_gross += ($basic_gross*2);
                        $gt_net += ($basic_net*2);

                        // prior years
                        if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered >= 1992) {
                            $total_prior_1992 += $f56_detail->basic_previous;
                            $total_penalty_prior_1992 += $f56_detail->basic_penalty_previous;
                        }
                        if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered <= 1991) {
                            $total_prior_1991 += $f56_detail->basic_previous;
                            $total_penalty_prior_1991 += $f56_detail->basic_penalty_previous;
                        }
                    ?>
                    <tr>
                        @if($rcpt_done == 0)
                            <?php $rcpt_done = 1; ?>
                            <td>
                                @if($entry_date->format('M') == 'Sep')
                                    Sept {{ date('d', strtotime($receipt->date_of_entry)) }}
                                @else
                                    {{ date('M d', strtotime($receipt->date_of_entry)) }}
                                @endif
                            </td>
                            <td>{{ $f56_detail->owner_name }}</td>
                            <td>{{ $f56_detail->period_covered }}</td>
                            <td>{{ $receipt->serial_no }}</td>
                        @else
                            <td></td>
                            <td></td>
                            <td>{{ $f56_detail->period_covered }}</td>
                            <td></td>
                        @endif

                        @if(!isset($f56_detail->TDARP[0]))
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        @else
                            <td>{{ $f56_detail->TDARP[0]->tdarpno }}</td>
                            <td>{{ isset($f56_detail->TDARPX->barangay_name->name) ? $f56_detail->TDARPX->barangay_name->name : '' }}</td>
                            <td>{{ $f56_detail->F56Type->abbrev }}</td>
                            <td>
                                @if($f56_detail->period_covered >= $advance_yr)
                                    {{-- number_format(($f56_detail->tdrp_assedvalue*.01), 2) --}}
                                    {{ number_format($f56_detail->basic_current, 2) }}
                                @else
                                    0.00
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered >= $advance_yr)
                                    {{-- number_format((($f56_detail->tdrp_assedvalue*.01)*.10), 2) --}}
                                    {{ number_format($f56_detail->basic_discount, 2) }}
                                @else
                                    0.00
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered == $current)
                                    {{ number_format($f56_detail->basic_current, 2) }}
                                @else
                                    0.00
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered == $current)
                                    {{ number_format($f56_detail->basic_discount, 2) }}
                                @else
                                    0.00
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered == $preceeding)
                                    {{ number_format($f56_detail->basic_previous, 2) }}
                                @else
                                    0.00
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered >= 1992)
                                    {{ number_format($f56_detail->basic_previous, 2) }}
                                @else
                                    0.00
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered <= 1991)
                                    {{ number_format($f56_detail->basic_previous, 2) }}
                                @else
                                    0.00
                                @endif
                            </td>
                            <td>{{ number_format($f56_detail->basic_penalty_current, 2) }}</td>
                            <td>
                                @if($f56_detail->period_covered == $preceeding)
                                    {{ number_format($f56_detail->basic_penalty_previous, 2) }}
                                @else
                                    0.00
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered >= 1992)
                                    {{ number_format($f56_detail->basic_penalty_previous, 2) }}
                                @else
                                    0.00
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered <= 1991)
                                    {{ number_format($f56_detail->basic_penalty_previous, 2) }}
                                @else
                                    0.00
                                @endif
                            </td>
                            <td>{{ number_format($basic_gross, 2) }}</td>
                            <td>{{ number_format($basic_net, 2) }}</td>
                            <td>
                                @if($f56_detail->period_covered >= $advance_yr)
                                    {{-- number_format(($f56_detail->tdrp_assedvalue*.01), 2) --}}
                                    {{ number_format($f56_detail->basic_current, 2) }}
                                @else
                                    0.00
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered >= $advance_yr)
                                    {{-- number_format((($f56_detail->tdrp_assedvalue*.01)*.10), 2) --}}
                                    {{ number_format($f56_detail->basic_discount, 2) }}
                                @else
                                    0.00
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered == $current)
                                    {{ number_format($f56_detail->basic_current, 2) }}
                                @else
                                    0.00
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered == $current)
                                    {{ number_format($f56_detail->basic_discount, 2) }}
                                @else
                                    0.00
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered == $preceeding)
                                    {{ number_format($f56_detail->basic_previous, 2) }}
                                @else
                                    0.00
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered >= 1992)
                                    {{ number_format($f56_detail->basic_previous, 2) }}
                                @else
                                    0.00
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered <= 1991)
                                    {{ number_format($f56_detail->basic_previous, 2) }}
                                @else
                                    0.00
                                @endif
                            </td>
                            <td>{{ number_format($f56_detail->basic_penalty_current, 2) }}</td>
                            <td>
                                @if($f56_detail->period_covered == $preceeding)
                                    {{ number_format($f56_detail->basic_penalty_previous, 2) }}
                                @else
                                    0.00
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered >= 1992)
                                    {{ number_format($f56_detail->basic_penalty_previous, 2) }}
                                @else
                                    0.00
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered <= 1991)
                                    {{ number_format($f56_detail->basic_penalty_previous, 2) }}
                                @else
                                    0.00
                                @endif
                            </td>
                            <td>{{ number_format($basic_gross, 2) }}</td>
                            <td>{{ number_format($basic_net, 2) }}</td>
                            <td>{{ number_format($basic_gross*2, 2) }}</td>
                            <td>{{ number_format($basic_net*2, 2) }}</td>
                        @endif
                    </tr>
                    @endforeach
                @endif
            @endforeach
            <tr>
                <th colspan="7">TOTAL COLLECTION</th>
                <th>{{ number_format($total_adv, 2) }}</th>
                <th>{{ number_format($total_adv_discount, 2) }}</th>
                <th>{{ number_format($total_basic_current, 2) }}</th>
                <th>{{ number_format($total_basic_discount, 2) }}</th>
                <th>{{ number_format($total_basic_previous, 2) }}</th>
                <th>{{ number_format($total_prior_1992, 2) }}</th>
                <th>{{ number_format($total_prior_1991, 2) }}</th>
                <th>{{ number_format($total_basic_penalty_current, 2) }}</th>
                <th>{{ number_format($total_basic_penalty_previous, 2) }}</th>
                <th>{{ number_format($total_penalty_prior_1992, 2) }}</th>
                <th>{{ number_format($total_penalty_prior_1991, 2) }}</th>
                <th>{{ number_format($total_basic_gross, 2) }}</th>
                <th>{{ number_format($total_basic_net, 2) }}</th>
                <th>{{ number_format($total_adv, 2) }}</th>
                <th>{{ number_format($total_adv_discount, 2) }}</th>
                <th>{{ number_format($total_basic_current, 2) }}</th>
                <th>{{ number_format($total_basic_discount, 2) }}</th>
                <th>{{ number_format($total_basic_previous, 2) }}</th>
                <th>{{ number_format($total_prior_1992, 2) }}</th>
                <th>{{ number_format($total_prior_1991, 2) }}</th>
                <th>{{ number_format($total_basic_penalty_current, 2) }}</th>
                <th>{{ number_format($total_basic_penalty_previous, 2) }}</th>
                <th>{{ number_format($total_penalty_prior_1992, 2) }}</th>
                <th>{{ number_format($total_penalty_prior_1991, 2) }}</th>
                <th>{{ number_format($total_basic_gross, 2) }}</th>
                <th>{{ number_format($total_basic_net, 2) }}</th>
                <th>{{ number_format($gt_gross, 2) }}</th>
                <th>{{ number_format($gt_net, 2) }}</th>
            </tr>
            <tr>
                <td colspan="35"></td>
            </tr>
        </tbody>
    </table>
</body>
</html>