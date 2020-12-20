<!DOCTYPE html>
<html>
<head>
    <title>Collections And Deposits</title>
    <style>
        @page { margin: 0px; }
        body {
            margin-bottom: 8px;
            margin-top: 150px;
            margin-left: 18px;
            margin-right: 18px;
            font-family: arial, "sans-serif";
            /* font-size: 8px; */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            page-break-inside: auto;
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
            font-size: 8px;
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

        header{
            position: fixed;
            top: 50px;
            left: 20px;
            height: 50px;
        }

        .summary th{
            border:none !important;
        }
        .summary-table tr th, .summary-table tr td  {
            text-align: center;
            font-size: 8px;
        }

        .disposition-table tr th, .disposition-table tr td  {
            text-align: center;
            
        }

        header table tr td{
            font-size: 10px;
        }
    </style>
</head>
<body>
    <header>
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

        <table style="width: 100%; margin-right: 20px;">
            <tr>
                <td width="48%"><b>Name of Accountable Officer:</b> <u>ISABEL D. KIW-AN - Local Recenue Collection Officer IV</u></td>
                <td width="33%"></td>
                @if(isset($report_no))
                <td style="font-weight: bold;" width="5%">Report No.</td>
                <td style="border-bottom: 1px solid black !important; text-align:center">{{ $report_no }}</td>
                @endif
            </tr>
            <tr>
                <td style="font-weight: bold">A. COLLECTIONS</td>
                <td></td>
                <td style="font-weight: bold">Date</td>
                <td style="border-bottom: 1px solid black !important; text-align:center">{{ $report_date }}</td>
            </tr>
        </table>
    </header>
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
                $rowCounter = 0;

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
                        if ($rowCounter == 19 || $rowCounter == 38) {
                            echo('<tr><td><div class="page-break"></div></td></tr>');
                            $rowCounter = 0;
                        }
                    
                        $rowCounter++; 
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
                            <?php 
                                $rcpt_done = 1; 
                                
                            ?>
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
                                    {{ zeroToDash($f56_detail->basic_current, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered >= $advance_yr)
                                    {{ zeroToDash($f56_detail->basic_discount, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered == $current)
                                    {{ zeroToDash($f56_detail->basic_current, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered == $current)
                                    {{ zeroToDash($f56_detail->basic_discount, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered == $preceeding)
                                    {{ zeroToDash($f56_detail->basic_previous, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered >= 1992)
                                    {{ zeroToDash($f56_detail->basic_previous, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered <= 1991)
                                    {{ zeroToDash($f56_detail->basic_previous, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ zeroToDash($f56_detail->basic_penalty_current, 2) }}</td>
                            <td>
                                @if($f56_detail->period_covered == $preceeding)
                                    {{ zeroToDash($f56_detail->basic_penalty_previous, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered >= 1992)
                                    {{ zeroToDash($f56_detail->basic_penalty_previous, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered <= 1991)
                                    {{ zeroToDash($f56_detail->basic_penalty_previous, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ zeroToDash($basic_gross, 2) }}</td>
                            <td>{{ zeroToDash($basic_net, 2) }}</td>
                            <td>
                                @if($f56_detail->period_covered >= $advance_yr)
                                    {{-- zeroToDash(($f56_detail->tdrp_assedvalue*.01), 2) --}}
                                    {{ zeroToDash($f56_detail->basic_current, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered >= $advance_yr)
                                    {{-- zeroToDash((($f56_detail->tdrp_assedvalue*.01)*.10), 2) --}}
                                    {{ zeroToDash($f56_detail->basic_discount, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered == $current)
                                    {{ zeroToDash($f56_detail->basic_current, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered == $current)
                                    {{ zeroToDash($f56_detail->basic_discount, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered == $preceeding)
                                    {{ zeroToDash($f56_detail->basic_previous, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered >= 1992)
                                    {{ zeroToDash($f56_detail->basic_previous, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered <= 1991)
                                    {{ zeroToDash($f56_detail->basic_previous, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ zeroToDash($f56_detail->basic_penalty_current, 2) }}</td>
                            <td>
                                @if($f56_detail->period_covered == $preceeding)
                                    {{ zeroToDash($f56_detail->basic_penalty_previous, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered >= 1992)
                                    {{ zeroToDash($f56_detail->basic_penalty_previous, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($f56_detail->period_covered <= $prior_start && $f56_detail->period_covered <= 1991)
                                    {{ zeroToDash($f56_detail->basic_penalty_previous, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ zeroToDash($basic_gross, 2) }}</td>
                            <td>{{ zeroToDash($basic_net, 2) }}</td>
                            <td>{{ zeroToDash($basic_gross*2, 2) }}</td>
                            <td>{{ zeroToDash($basic_net*2, 2) }}</td>
                        @endif
                    </tr>
                    @endforeach
                @endif
            @endforeach

            <tr>
                <th colspan="7">TOTAL COLLECTION</th>
                <th>{{ zeroToDash($total_adv, 2) }}</th>
                <th>{{ zeroToDash($total_adv_discount, 2) }}</th>
                <th>{{ zeroToDash($total_basic_current, 2) }}</th>
                <th>{{ zeroToDash($total_basic_discount, 2) }}</th>
                <th>{{ zeroToDash($total_basic_previous, 2) }}</th>
                <th>{{ zeroToDash($total_prior_1992, 2) }}</th>
                <th>{{ zeroToDash($total_prior_1991, 2) }}</th>
                <th>{{ zeroToDash($total_basic_penalty_current, 2) }}</th>
                <th>{{ zeroToDash($total_basic_penalty_previous, 2) }}</th>
                <th>{{ zeroToDash($total_penalty_prior_1992, 2) }}</th>
                <th>{{ zeroToDash($total_penalty_prior_1991, 2) }}</th>
                <th>{{ zeroToDash($total_basic_gross, 2) }}</th>
                <th>{{ zeroToDash($total_basic_net, 2) }}</th>
                <th>{{ zeroToDash($total_adv, 2) }}</th>
                <th>{{ zeroToDash($total_adv_discount, 2) }}</th>
                <th>{{ zeroToDash($total_basic_current, 2) }}</th>
                <th>{{ zeroToDash($total_basic_discount, 2) }}</th>
                <th>{{ zeroToDash($total_basic_previous, 2) }}</th>
                <th>{{ zeroToDash($total_prior_1992, 2) }}</th>
                <th>{{ zeroToDash($total_prior_1991, 2) }}</th>
                <th>{{ zeroToDash($total_basic_penalty_current, 2) }}</th>
                <th>{{ zeroToDash($total_basic_penalty_previous, 2) }}</th>
                <th>{{ zeroToDash($total_penalty_prior_1992, 2) }}</th>
                <th>{{ zeroToDash($total_penalty_prior_1991, 2) }}</th>
                <th>{{ zeroToDash($total_basic_gross, 2) }}</th>
                <th>{{ zeroToDash($total_basic_net, 2) }}</th>
                <th>{{ zeroToDash($gt_gross, 2) }}</th>
                <th>{{ zeroToDash($gt_net, 2) }}</th>
            </tr>
            <tr>
                <td colspan="35"></td>
            </tr>
        </tbody>
    
    @if ($rowCounter >= 3)
        </table>
        <div class="newpage"></div>
        <table class="table summary-table">
            <thead class="border_all_table">
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

        @endif
        <tr class="summary">
            <th colspan="3"></th>
            <th colspan="4"><b>Summary</b></th>
            <!-- advance -->
            <th></th>
            <th></th>
            <!-- current --> 
            <th></th>
            <th></th>
            <!-- immediate preceeding year --> 
            <th></th>
            <!-- prior years -->
            <th></th>
            <th></th>
            <!-- penalties -->
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <!-- subtotal gross -->
            <th></th>
            <!-- subtotal net -->
            <th></th>
            <!-- SEF --> 
            <!-- advance -->
            <th></th>
            <th></th>
            <!-- current --> 
            <th></th>
            <th></th>
            <!-- immediate preceeding year --> 
            <th></th>
            <!-- prior years -->
            <th></th>
            <th></th>
            <!-- penalties -->
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <!-- subtotal gross -->
            <th></th>
            <!-- subtotal net -->
            <th></th>
            <!-- grandtotal gross -->
            <th></th>
            <!-- grandtotal net -->
            <th></th>
        </tr>

        @foreach($f56_type as $type)
        <tr>
            <td colspan="5" style="border:none">
                <!-- <span class="hidden"> -->
                    <?php
                        $class_basic_gross = ($class_amt[$type->id]['basic_current'] 
                            + $class_amt[$type->id]['basic_previous'] 
                            + $class_amt[$type->id]['basic_adv'] 
                            + $class_amt[$type->id]['basic_prior_1992'] 
                            + $class_amt[$type->id]['basic_prior_1991'] 
                            + $class_amt[$type->id]['basic_penalty_current'] 
                            + $class_amt[$type->id]['basic_penalty_previous']
                            + $class_amt[$type->id]['basic_prior_penalty_1992']
                            + $class_amt[$type->id]['basic_prior_penalty_1991']
                        );
                        $class_basic_net = $class_basic_gross - ($class_amt[$type->id]['basic_discount'] + $class_amt[$type->id]['basic_adv_discount']);
                        $class_total_gross = $class_basic_gross + $class_basic_gross;
                        $class_total_net = $class_basic_net + $class_basic_net;
                    ?>
                <!-- </span> -->
            </td>
            <td colspan="2" style="border:none; text-align: left">{{ $type->name }}</td>
            <!-- BASIC -->
            <!-- advance -->
            <td class="border_all val">{{ zeroToDash($class_amt[$type->id]['basic_adv'], 2) }}</td>
            <td class="border_all val">{{ zeroToDash($class_amt[$type->id]['basic_adv_discount'], 2) }}</td>
            <!-- current --> 
            <td class="border_all val">{{ zeroToDash($class_amt[$type->id]['basic_current'], 2) }}</td>
            <td class="border_all val">{{ zeroToDash($class_amt[$type->id]['basic_discount'], 2) }}</td>
            <!-- immediate preceeding year --> 
            <td class="border_all val">{{ zeroToDash($class_amt[$type->id]['basic_previous'], 2) }}</td>
            <!-- prior years -->
            <td class="border_all val">{{ zeroToDash($class_amt[$type->id]['basic_prior_1992'], 2) }}</td>
            <td class="border_all val">{{ zeroToDash($class_amt[$type->id]['basic_prior_1991'], 2) }}</td>
            <!-- penalties -->
            <td class="border_all val">{{ zeroToDash($class_amt[$type->id]['basic_penalty_current'], 2) }}</td>
            <td class="border_all val">{{ zeroToDash($class_amt[$type->id]['basic_penalty_previous'], 2) }}</td>
            <td class="border_all val">{{ zeroToDash($class_amt[$type->id]['basic_prior_penalty_1992'], 2) }}</td>
            <td class="border_all val">{{ zeroToDash($class_amt[$type->id]['basic_prior_penalty_1991'], 2) }}</td>
            <!-- subtotal gross -->
            <td class="border_all val">{{ zeroToDash($class_basic_gross, 2) }}</td>
            <!-- subtotal net -->
            <td class="border_all val">{{ zeroToDash($class_basic_net, 2) }}</td>
            <!-- SEF --> 
            <!-- advance -->
            <td class="border_all val">{{ zeroToDash($class_amt[$type->id]['basic_adv'], 2) }}</td>
            <td class="border_all val">{{ zeroToDash($class_amt[$type->id]['basic_adv_discount'], 2) }}</td>
            <!-- current --> 
            <td class="border_all val">{{ zeroToDash($class_amt[$type->id]['basic_current'], 2) }}</td>
            <td class="border_all val">{{ zeroToDash($class_amt[$type->id]['basic_discount'], 2) }}</td>
            <!-- immediate preceeding year --> 
            <td class="border_all val">{{ zeroToDash($class_amt[$type->id]['basic_previous'], 2) }}</td>
            <!-- prior years -->
            <td class="border_all val">{{ zeroToDash($class_amt[$type->id]['basic_prior_1992'], 2) }}</td>
            <td class="border_all val">{{ zeroToDash($class_amt[$type->id]['basic_prior_1991'], 2) }}</td>
            <!-- penalties -->
            <td class="border_all val">{{ zeroToDash($class_amt[$type->id]['basic_penalty_current'], 2) }}</td>
            <td class="border_all val">{{ zeroToDash($class_amt[$type->id]['basic_penalty_previous'], 2) }}</td>
            <td class="border_all val">{{ zeroToDash($class_amt[$type->id]['basic_prior_penalty_1992'], 2) }}</td>
            <td class="border_all val">{{ zeroToDash($class_amt[$type->id]['basic_prior_penalty_1991'], 2) }}</td>
            <!-- subtotal gross -->
            <td class="border_all val">{{ zeroToDash($class_basic_gross, 2) }}</td>
            <!-- subtotal net -->
            <td class="border_all val">{{ zeroToDash($class_basic_net, 2) }}</td>
            <!-- grandtotal gross -->
            <td class="border_all val">{{ zeroToDash($class_total_gross, 2) }}</td>
            <!-- grandtotal net -->
            <td class="border_all val">{{ zeroToDash($class_total_net, 2) }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="5" style="border:none"></td>
            <th class="border_all" colspan="2">TOTAL</th>
            <th class="border_all val">{{ zeroToDash($total_adv, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($total_adv_discount, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($total_basic_current, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($total_basic_discount, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($total_basic_previous, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($total_prior_1992, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($total_prior_1991, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($total_basic_penalty_current, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($total_basic_penalty_previous, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($total_penalty_prior_1992, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($total_penalty_prior_1991, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($total_basic_gross, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($total_basic_net, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($total_adv, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($total_adv_discount, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($total_basic_current, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($total_basic_discount, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($total_basic_previous, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($total_prior_1992, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($total_prior_1991, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($total_basic_penalty_current, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($total_basic_penalty_previous, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($total_penalty_prior_1992, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($total_penalty_prior_1991, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($total_basic_gross, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($total_basic_net, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($gt_gross, 2) }}</th>
            <th class="border_all val">{{ zeroToDash($gt_net, 2) }}</th>
        </tr>
        <tr>
            <td colspan="5" style="border:none"></td>
            <td colspan="30" class="border_all"></td>
        </tr>

        <tr>
            <th colspan="35" style="border:none">&nbsp;</th>
        </tr>

    </table>

    <div>
        <table class="table dis-pgbreak disposition-table">
            <!-- DISPOSITION SECTION -->
            <tr>
                <th colspan="4"></th>
                <td style="text-align:left"><b>Disposition </b></td>
                <th colspan="2"></th>
                <th colspan="5" class="border_all ctr">ADVANCE</th>
                <th colspan="5" class="border_all ctr">CURRENT</th>
                <th colspan="3" class="border_all ctr">{{ $preceeding }}</th>
                <th colspan="3" class="border_all ctr">{{ $prior_start }}-1992</th>
                <th colspan="3" class="border_all ctr">1991 & below</th>
                <th colspan="10" class="border_all ctr">PENALTIES</th>
                <th colspan="2" rowspan="2" class="border_all ctr">TOTAL</th>
            </tr>           
            <tr>
                <th colspan="4"></th>
                <td colspan="3" style="text-align:left">BASIC TAX 1%</td>
                <!-- ADVANCE -->
                <th class="border_all">%</th>
                <th colspan="2" class="border_all ctr">AMOUNT</th>
                <th colspan="2" class="border_all ctr">DISCOUNT</th>
                <!-- CURRENT -->
                <th class="border_all">%</th>
                <th colspan="2" class="border_all ctr">AMOUNT</th>
                <th colspan="2" class="border_all ctr">DISCOUNT</th>
                <!-- PREVIOUS -->
                <th class="border_all">%</th>
                <th colspan="2" class="border_all ctr">AMOUNT</th>
                <!-- PRIOR YEARS, 1992-above -->
                <th class="border_all">%</th>
                <th colspan="2" class="border_all ctr">AMOUNT</th>
                <!-- PRIOR YEARS, 1991-below -->
                <th class="border_all">%</th>
                <th colspan="2" class="border_all ctr">AMOUNT</th>
                <!-- PENALTIES -->
                <th class="border_all">%</th>
                <th colspan="3" class="border_all ctr">CURRENT</th>
                <th colspan="2" class="border_all ctr">{{ $preceeding }}</th>
                <th colspan="2" class="border_all ctr">{{ $prior_start }}-1992</th>
                <th colspan="2" class="border_all ctr">1991 & below</th>
            </tr>

            <tr>
                <?php
                    // $munshare_basic_current             = $total_basic_current * .4;
                    // $munshare_basic_discount            = $total_basic_discount * .4;
                    // $munshare_basic_previous            = $total_basic_previous * .4;
                    // $munshare_basic_penalty_current     = $total_basic_penalty_current * .4;
                    // $munshare_basic_penalty_previous    = $total_basic_penalty_previous * .4;
                    // $munshare_basic_net                 = $total_basic_net * .4;

                    // $prv_crnt_ammount = $total_basic_current * .35;
                    // $prv_crnt_discount = $total_basic_discount * .35;
                    // $prv_prvious_ammount = $total_basic_previous * .35;
                    // $prv_pnalties_crnt = $total_basic_penalty_current * .35;
                    // $prv_pnalties_prvious = $total_basic_penalty_previous * .35;

                    // // advance
                    // $prv_adv_ammount = $total_adv * .35;
                    // $prv_adv_discount = $total_adv_discount * .35;
                    // $mnc_adv_ammount = $total_adv * .40;
                    // $mnc_adv_discount = $total_adv_discount * .40;
                    // $brgy_adv_ammount = round(floatval($total_adv), 2) - round(floatval($prv_adv_ammount), 2) - round(floatval($mnc_adv_ammount), 2);
                    // $brgy_adv_discount = round(floatval($total_adv_discount), 2) - round(floatval($prv_adv_discount), 2) - round(floatval($mnc_adv_discount), 2);
                    $total_adv_amt = round(floatval($merged['prv_adv_ammount']), 2) + round(floatval($merged['mnc_adv_ammount']), 2) + round(floatval($merged['brgy_adv_ammount']), 2);
                    $total_adv_discount = round(floatval($merged['prv_adv_discount']), 2) + round(floatval($merged['mnc_adv_discount']), 2) + round(floatval($merged['brgy_adv_discount']), 2);
                    // // 1992-above
                    // $prv_prior_1992_amt = $total_prior_1992 * .35;
                    // $prv_prior_1992_penalties = $total_penalty_prior_1992 * .35;
                    // $mnc_prior_1992_amt = $total_prior_1992 * .40;
                    // $mnc_prior_1992_penalties = $total_penalty_prior_1992 * .40;
                    // $brgy_prior_1992_amt = round(floatval($total_prior_1992), 2) - round(floatval($prv_prior_1992_amt), 2) - round(floatval($mnc_prior_1992_amt), 2);
                    // $brgy_prior_1992_penalties = round(floatval($total_penalty_prior_1992), 2) - round(floatval($prv_prior_1992_penalties), 2) - round(floatval($mnc_prior_1992_penalties), 2);
                    $total_prior_1992_amt = round(floatval($merged['prv_prior_1992_amt']), 2) + round(floatval($merged['mnc_prior_1992_amt']), 2) + round(floatval($merged['brgy_prior_1992_amt']), 2);
                    $total_prior_1992_penalties = round(floatval($merged['prv_prior_1992_penalties']), 2) + round(floatval($merged['mnc_prior_1992_penalties']), 2) + round(floatval($merged['brgy_prior_1992_penalties']), 2);

                    // // 1991-below
                    // $prv_prior_1991_amt = $total_prior_1991 * .35;
                    // $prv_prior_1991_penalties = $total_penalty_prior_1991 * .35;
                    // $mnc_prior_1991_amt = $total_prior_1991 * .40;
                    // $mnc_prior_1991_penalties = $total_penalty_prior_1991 * .40;
                    // $brgy_prior_1991_amt = round(floatval($total_prior_1991), 2) - round(floatval($prv_prior_1991_amt), 2) - round(floatval($mnc_prior_1991_amt), 2);
                    // $brgy_prior_1991_penalties = round(floatval($total_penalty_prior_1991), 2) - round(floatval($prv_prior_1991_penalties), 2) - round(floatval($mnc_prior_1991_penalties), 2);
                    $total_prior_1991_amt = round(floatval($merged['prv_prior_1991_amt']), 2) + round(floatval($merged['mnc_prior_1991_amt']), 2) + round(floatval($merged['brgy_prior_1991_amt']), 2);
                    $total_prior_1991_penalties = round(floatval($merged['prv_prior_1991_penalties']), 2) + round(floatval($merged['mnc_prior_1991_penalties']), 2) + round(floatval($merged['brgy_prior_1991_penalties']), 2);         

                    // $brgyshare_basic_current            = round(floatval($total_basic_current), 2) - round(floatval($prv_crnt_ammount), 2) - round(floatval($munshare_basic_current), 2);
                    // $brgyshare_basic_discount           = round(floatval($total_basic_discount), 2) - round(floatval($prv_crnt_discount), 2) - round(floatval($munshare_basic_discount), 2);
                    // $brgyshare_basic_previous           = round(floatval($total_basic_previous), 2) - round(floatval($prv_prvious_ammount), 2) - round(floatval($munshare_basic_previous), 2);
                    // $brgyshare_basic_penalty_current    = round(floatval($total_basic_penalty_current), 2) - round(floatval($prv_pnalties_crnt), 2) - round(floatval($munshare_basic_penalty_current), 2);
                    // $brgyshare_basic_penalty_previous   = round(floatval($total_basic_penalty_previous), 2) - round(floatval($prv_pnalties_prvious), 2) - round(floatval($munshare_basic_penalty_previous), 2);

                    $prv_total_basic = round(floatval($merged['prv_crnt_ammount']), 2) - round(floatval($merged['prv_crnt_discount']), 2)  + round(floatval($merged['prv_prvious_ammount']), 2) + round(floatval($merged['prv_pnalties_crnt']), 2) + round(floatval($merged['prv_pnalties_prvious']), 2) + round(floatval($merged['prv_adv_ammount']), 2) + round(floatval($merged['prv_prior_1992_amt']), 2) + round(floatval($merged['prv_prior_1991_amt']), 2) - round(floatval($merged['prv_adv_discount']), 2) + round(floatval($merged['prv_prior_1992_penalties']), 2) + round(floatval($merged['prv_prior_1991_penalties']), 2);
                    $mncpal_total_basic = (round(floatval($merged['munshare_basic_current']), 2) - round(floatval($merged['munshare_basic_discount']), 2)  + round(floatval($merged['munshare_basic_previous']), 2) + round(floatval($merged['munshare_basic_penalty_current']), 2) + round(floatval($merged['munshare_basic_penalty_previous']), 2)) + round(floatval($merged['mnc_adv_ammount']) ,2) - round(floatval($merged['mnc_adv_discount']), 2) + round(floatval($merged['mnc_prior_1992_amt']), 2) + round(floatval($merged['mnc_prior_1992_penalties']), 2) + round(floatval($merged['mnc_prior_1991_amt']), 2) + round(floatval($merged['mnc_prior_1991_penalties'])); 
                    $brgy_total_basic = round(floatval($merged['brgyshare_basic_current']), 2) - round(floatval($merged['brgyshare_basic_discount']), 2)  + round(floatval($merged['brgyshare_basic_previous']), 2) + round(floatval($merged['brgyshare_basic_penalty_current']), 2) + round(floatval($merged['brgyshare_basic_penalty_previous']), 2) + round(floatval($merged['brgy_adv_ammount']), 2) - round(floatval($merged['brgy_adv_discount']), 2) + round(floatval($merged['brgy_prior_1992_amt']), 2) + round(floatval($merged['brgy_prior_1992_penalties']), 2) + round(floatval($merged['brgy_prior_1991_amt']), 2) + round(floatval($merged['brgy_prior_1991_penalties']), 2);

                    $total_basic_net = round(floatval($merged['prv_total_basic']), 2) + round(floatval($merged['mncpal_total_basic']), 2) + round(floatval($merged['brgy_total_basic']), 2);
                    $total_basic_current = round(floatval($merged['prv_crnt_ammount']), 2) + round(floatval($merged['munshare_basic_current']), 2) + round(floatval($merged['brgyshare_basic_current']), 2);
                ?>

                <th colspan="4"></th>
                <td colspan="3" style="text-align:left">Provincial Share</td>
                <!-- advance -->
                <td class="border_all ctr">35%</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['prv_adv_ammount'], 2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['prv_adv_discount'], 2) }}</td>
                <!-- current --> 
                <td class="border_all ctr">35%</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['prv_crnt_ammount'], 2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['prv_crnt_discount'], 2) }}</td>
                <!-- previous -->
                <td class="border_all ctr">35%</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['prv_prvious_ammount'], 2) }}</td>
                <!-- 1992-above -->
                <td class="border_all ctr">35%</td>
                <td class="border_all val" colspan="2">{{ zeroToDash($merged['prv_prior_1992_amt'], 2) }}</td>
                <!-- 1991-below -->
                <td class="border_all ctr">35%</td>
                <td class="border_all val" colspan="2">{{ zeroToDash($merged['prv_prior_1991_amt'], 2) }}</td>
                <!-- penalties -->
                <td class="border_all ctr">35%</td>
                <td colspan="3" class="border_all val">{{ zeroToDash($merged['prv_pnalties_crnt'], 2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['prv_pnalties_prvious'], 2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['prv_prior_1992_penalties'], 2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['prv_prior_1991_penalties'], 2) }}</td>
                <!-- total -->
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['prv_total_basic'], 2) }}</td>
            </tr>
            <tr>
                <th colspan="4"></th>
                <td colspan="3" style="text-align:left">Municipal Share</td>
                <!-- advance -->
                <td class="border_all ctr">40%</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['mnc_adv_ammount'], 2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['mnc_adv_discount'], 2) }}</td>
                <!-- current -->
                <td class="border_all ctr">40%</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['munshare_basic_current'], 2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['munshare_basic_discount'], 2)  }}</td>
                <!-- previous -->
                <td class="border_all ctr">40%</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['munshare_basic_previous'], 2)  }}</td>
                <!-- 1992-above -->
                <td class="border_all ctr">40%</td>
                <td class="border_all val" colspan="2">{{ zeroToDash($merged['mnc_prior_1992_amt'], 2) }}</td>
                <!-- 1991-below -->
                <td class="border_all ctr">40%</td>
                <td class="border_all val" colspan="2">{{ zeroToDash($merged['mnc_prior_1991_amt'], 2) }}</td>
                <!-- penalties -->
                <td class="border_all ctr">40%</td>
                <td colspan="3" class="border_all val">{{ zeroToDash($merged['munshare_basic_penalty_current'], 2)  }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['munshare_basic_penalty_previous'], 2)  }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['mnc_prior_1992_penalties'], 2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['mnc_prior_1991_penalties'], 2) }}</td>
                <!-- total -->
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['mncpal_total_basic'], 2)  }}</td>
            </tr>      
            <tr>
                <th colspan="4"></th>
                <td colspan="3" style="text-align:left">Barangay Share</td>
                <!-- advance -->
                <td class="border_all ctr">25%</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['brgy_adv_ammount'], 2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['brgy_adv_discount'], 2) }}</td>
                <!-- current --> 
                <td class="border_all ctr">25%</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['brgyshare_basic_current'], 2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['brgyshare_basic_discount'], 2) }}</td>
                <!-- previous -->
                <td class="border_all ctr">25%</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['brgyshare_basic_previous'], 2) }}</td>
                <!-- 1992-above -->
                <td class="border_all ctr">25%</td>
                <td class="border_all val" colspan="2">{{ zeroToDash($merged['brgy_prior_1992_amt'], 2) }}</td>
                <!-- 1991-below -->
                <td class="border_all ctr">25%</td>
                <td class="border_all val" colspan="2">{{ zeroToDash($merged['brgy_prior_1991_amt'], 2) }}</td>
                <!-- penalties -->
                <td class="border_all ctr">25%</td>
                <td colspan="3" class="border_all val">{{ zeroToDash($merged['brgyshare_basic_penalty_current'], 2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['brgyshare_basic_penalty_previous'], 2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['brgy_prior_1992_penalties'], 2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['brgy_prior_1991_penalties'], 2) }}</td>
                <!-- total -->
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['brgy_total_basic'], 2) }}</td>
            </tr>     
            <tr>
                <th colspan="4"></th>
                <th colspan="3" style="text-align:left">TOTAL(S)</th>
                <!-- advance -->
                <td class="border_all"></td>
                <td class="border_all val" colspan="2">{{ zeroToDash($merged['total_adv_amt'], 2) }}</td>
                <td class="border_all val" colspan="2">{{ zeroToDash($merged['total_adv_discount'], 2) }}</td>
                <!-- current -->
                <td class="border_all"></td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['total_basic_current'], 2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['total_basic_discount'], 2) }}</td>
                <!-- previous -->
                <td class="border_all"></td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['total_basic_previous'], 2) }}</td>
                <!-- 1992-above -->
                <td class="border_all"></td>
                <td class="border_all val" colspan="2">{{ zeroToDash($merged['total_prior_1992_amt'], 2) }}</td>
                <!-- 1991-below -->
                <td class="border_all"></td>
                <td class="border_all val" colspan="2">{{ zeroToDash($merged['total_prior_1991_amt'], 2) }}</td>
                <!-- penalties -->
                <td class="border_all"></td>
                <td colspan="3" class="border_all val">{{ zeroToDash($merged['total_basic_penalty_current'], 2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['total_basic_penalty_previous'], 2) }}</td>
                <td class="border_all val" colspan="2">{{ zeroToDash($merged['total_prior_1992_penalties'], 2) }}</td>
                <td class="border_all val" colspan="2">{{ zeroToDash($merged['total_prior_1991_penalties'], 2) }}</td>
                <!-- total -->
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['total_basic_net'], 2) }}</td>
            </tr>            
            <tr>
                <td colspan="7"></td>
                <td colspan="31" class="border_all"></td>
            </tr>

            <?php
                $xtotal_basic_current = $total_basic_current * .5;
                $xtotal_basic_discount = $total_basic_discount*.5;
                $xtotal_basic_previous = $total_basic_previous * .5;
                $xtotal_basic_penalty_current = $total_basic_penalty_current * .5;
                $xtotal_basic_penalty_previous = $total_basic_penalty_previous * .5;

                $xtotal_basic_net = ($xtotal_basic_current - $xtotal_basic_discount) + $xtotal_basic_previous  + $xtotal_basic_penalty_current + $xtotal_basic_penalty_previous;
            ?>
            <tr>
                <th colspan="4"></th>
                <td colspan="3" style="text-align:left"><b>SEF TAX 1%</b></td>
                <!-- advance -->
                <td class="border_all"></td>
                <td colspan="2" class="border_all"></td>
                <td colspan="2" class="border_all"></td>
                <!-- current -->
                <td class="border_all"></td>
                <td colspan="2" class="border_all"></td>
                <td colspan="2" class="border_all"></td>
                <!-- previous --> 
                <td class="border_all"></td>
                <td colspan="2" class="border_all"></td>
                <!-- 1992-above --> 
                <td class="border_all"></td>
                <td colspan="2" class="border_all"></td>
                <!-- 1991-below --> 
                <td class="border_all"></td>
                <td colspan="2" class="border_all"></td>
                <!-- penalties -->
                <td class="border_all"></td>
                <td colspan="3" class="border_all"></td>
                <td colspan="2" class="border_all"></td>
                <td colspan="2" class="border_all"></td>
                <td colspan="2" class="border_all"></td>
                <!-- total -->
                <td colspan="2" class="border_all"></td>
            </tr>
            <?php
                // // PROVINCIAL SHARE LARGER SHARE (* 50%)
                // // discount lower for prov'l share
                // $sef_prv_amt = $total_basic_current * .5;
                // $sef_prev_prv_amt = $total_basic_previous * .5;
                // // $sef_prv_penalty = $total_basic_penalty_current * .5;
                // // $sef_prev_prv_penalty = $total_basic_penalty_previous * .5;          

                // if($total_basic_discount > 0 && $total_adv_discount > 0) {
                //     $sef_prv_discount = $total_basic_discount * .5; 
                //     $sef_mncpl_dscnt = round(floatval($total_basic_discount), 2) - round(floatval($sef_prv_discount), 2);
                // } else {
                //     $sef_mncpl_dscnt = $total_basic_discount * .5; 
                //     $sef_prv_discount = round(floatval($total_basic_discount), 2) - round(floatval($sef_mncpl_dscnt), 2);
                // }

                // $sef_mncpl_crnt = round(floatval($total_basic_current), 2) - round(floatval($sef_prv_amt), 2); 
                // $sef_mncpl_prev = round(floatval($total_basic_previous), 2) - round(floatval($sef_prev_prv_amt), 2); 
                // // $sef_mncpl_pen_crnt = round(floatval($total_basic_penalty_current), 2) - round(floatval($sef_prv_penalty), 2); // less
                // // $sef_mncpl_pen_crnt_prev = round(floatval($total_basic_penalty_previous), 2) - round(floatval($sef_prev_prv_penalty), 2); // less  

                // if($total_basic_penalty_current > 0 && $total_basic_penalty_previous <= 0 && $total_penalty_prior_1992 <= 0 && $total_penalty_prior_1991 <= 0 && ($total_basic_discount > 0 || $total_adv_discount > 0)) {
                //     $sef_mncpl_pen_crnt = $total_basic_penalty_current * .5;
                //     $sef_prv_penalty = round(floatval($total_basic_penalty_current), 2) - round(floatval($sef_mncpl_pen_crnt), 2); // less
                // } else {
                //     $sef_prv_penalty = $total_basic_penalty_current * .5;
                //     $sef_mncpl_pen_crnt = round(floatval($total_basic_penalty_current), 2) - round(floatval($sef_prv_penalty), 2); // less
                // }

                // if($total_basic_penalty_current > 0 && $total_basic_penalty_previous <= 0 && $total_penalty_prior_1992 <= 0 && $total_penalty_prior_1991 <= 0 && ($total_basic_discount > 0 || $total_adv_discount > 0)) {
                //     $sef_mncpl_pen_crnt_prev = $total_basic_penalty_previous * .5; 
                //     $sef_prev_prv_penalty = round(floatval($total_basic_penalty_previous), 2) - round(floatval($sef_mncpl_pen_crnt_prev), 2); // less 
                // } else {
                //     $sef_prev_prv_penalty = $total_basic_penalty_previous * .5; 
                //     $sef_mncpl_pen_crnt_prev = round(floatval($total_basic_penalty_previous), 2) - round(floatval($sef_prev_prv_penalty), 2); // less 
                // }

                // $sef_curr_total = round(floatval($sef_prv_amt), 2) + round(floatval($sef_mncpl_crnt), 2); 
                // $sef_curr_discount_total = round(floatval($sef_prv_discount), 2) + round(floatval($sef_mncpl_dscnt), 2);
                // $sef_curr_pen_total = round(floatval($sef_prv_penalty), 2) + round(floatval($sef_mncpl_pen_crnt), 2);


                // $sef_prev_total = round(floatval($sef_prev_prv_amt), 2) + round(floatval($sef_mncpl_prev), 2);
                // $sef_prev_penalty_total = round(floatval($sef_prev_prv_penalty), 2) + round(floatval($sef_mncpl_pen_crnt_prev), 2);

                // // advance
                // $sef_prv_adv_amt = $total_adv * .50;
                // $sef_mnc_adv_amt = round(floatval($total_adv), 2) - round(floatval($sef_prv_adv_amt), 2);
                // $sef_total_adv_amt = round(floatval($sef_prv_adv_amt), 2) + round(floatval($sef_mnc_adv_amt), 2);
                // if($total_basic_discount > 0 && $total_adv_discount > 0) {
                //     $sef_prv_adv_discount = $total_adv_discount * .50;
                //     $sef_mnc_adv_discount = round(floatval($total_adv_discount), 2) - round(floatval($sef_prv_adv_discount), 2); // lesser
                // } else {
                //     $sef_mnc_adv_discount = $total_adv_discount * .50;
                //     $sef_prv_adv_discount = round(floatval($total_adv_discount), 2) - round(floatval($sef_mnc_adv_discount), 2); // lesser
                // }
                
                // $sef_total_adv_discount = round(floatval($sef_prv_adv_discount), 2) + round(floatval($sef_mnc_adv_discount), 2);

                // // 1992-above
                // $sef_prv_prior_1992_amt = $total_prior_1992 * .50;
                // $sef_mnc_prior_1992_amt = round(floatval($total_prior_1992), 2) - round(floatval($sef_prv_prior_1992_amt), 2);
                // // $sef_prv_prior_1992_penalties = $total_penalty_prior_1992 * .50;
                // // $sef_mnc_prior_1992_penalties = round(floatval($total_penalty_prior_1992), 2) - round(floatval($sef_prv_prior_1992_penalties), 2);
                // if($total_basic_penalty_current <= 0 && $total_basic_penalty_previous <= 0 && $total_penalty_prior_1992 > 0 && $total_penalty_prior_1991 <= 0 && ($total_basic_discount > 0 || $total_adv_discount > 0)) {
                //     $sef_mnc_prior_1992_penalties = $total_penalty_prior_1992 * .50;
                //     $sef_prv_prior_1992_penalties = round(floatval($total_penalty_prior_1992), 2) - round(floatval($sef_mnc_prior_1992_penalties), 2);
                // } else {
                //     $sef_prv_prior_1992_penalties = $total_penalty_prior_1992 * .50;
                //     $sef_mnc_prior_1992_penalties = round(floatval($total_penalty_prior_1992), 2) - round(floatval($sef_prv_prior_1992_penalties), 2);
                // }
                // $sef_total_prior_1992_amt = round(floatval($sef_prv_prior_1992_amt), 2) + round(floatval($sef_mnc_prior_1992_amt), 2);
                // $sef_total_prior_1992_penalties = round(floatval($sef_prv_prior_1992_penalties), 2) + round(floatval($sef_mnc_prior_1992_penalties), 2);

                // // 1991-below
                // $sef_prv_prior_1991_amt = $total_prior_1991 * .50;
                // $sef_mnc_prior_1991_amt = round(floatval($total_prior_1991), 2) - round(floatval($sef_prv_prior_1991_amt), 2);
                // // $sef_prv_prior_1991_penalties = $total_penalty_prior_1991 * .50;
                // // $sef_mnc_prior_1991_penalties = round(floatval($total_penalty_prior_1991), 2) - round(floatval($sef_prv_prior_1991_penalties), 2);
                // if($total_basic_penalty_current <= 0 && $total_basic_penalty_previous <= 0 && $total_penalty_prior_1992 <= 0 && $total_penalty_prior_1991 > 0 && ($total_basic_discount > 0 || $total_adv_discount > 0)) {
                //     $sef_mnc_prior_1991_penalties = $total_penalty_prior_1991 * .50;
                //     $sef_prv_prior_1991_penalties = round(floatval($total_penalty_prior_1991), 2) - round(floatval($sef_mnc_prior_1991_penalties), 2);
                // } else {
                //     $sef_prv_prior_1991_penalties = $total_penalty_prior_1991 * .50;
                //     $sef_mnc_prior_1991_penalties = round(floatval($total_penalty_prior_1991), 2) - round(floatval($sef_prv_prior_1991_penalties), 2);
                // }
                // $sef_total_prior_1991_amt = round(floatval($sef_prv_prior_1991_amt), 2) + round(floatval($sef_mnc_prior_1991_amt), 2);
                // $sef_total_prior_1991_penalties = round(floatval($sef_prv_prior_1991_penalties), 2) + round(floatval($sef_mnc_prior_1991_penalties), 2);

                // $sef_prv_net = round(floatval($sef_prv_amt), 2) - round(floatval($sef_prv_discount), 2) + round(floatval($sef_prv_penalty), 2) + round(floatval($sef_prev_prv_amt), 2) + round(floatval($sef_prev_prv_penalty), 2) + round(floatval($sef_prv_adv_amt), 2) - round(floatval($sef_prv_adv_discount), 2) + round(floatval($sef_prv_prior_1992_amt), 2) + round(floatval($sef_prv_prior_1992_penalties), 2) + round(floatval($sef_prv_prior_1991_amt), 2) + round(floatval($sef_prv_prior_1991_penalties), 2);
                // $sef_total_basic_net = round(floatval($sef_mncpl_crnt), 2) - round(floatval($sef_mncpl_dscnt), 2) + round(floatval($sef_mncpl_prev), 2) + round(floatval($sef_mncpl_pen_crnt), 2) + round(floatval($sef_mncpl_pen_crnt_prev), 2) + round(floatval($sef_mnc_adv_amt), 2) - round(floatval($sef_mnc_adv_discount), 2) + round(floatval($sef_mnc_prior_1992_amt), 2) + round(floatval($sef_mnc_prior_1992_penalties), 2) + round(floatval($sef_mnc_prior_1991_amt), 2) + round(floatval($sef_mnc_prior_1991_penalties), 2);
                // $gtotal_sef = round(floatval($sef_prv_net), 2) + round(floatval($sef_total_basic_net), 2);
            ?>
            <tr>
                <th colspan="4"></th>
                <td colspan="3" style="text-align:left">Provincial Share</td>
                <!-- advance -->
                <td class="border_all ctr">50%</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_prv_adv_amt'], 2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_prv_adv_discount'], 2) }}</td>
                <!-- current -->
                <td class="border_all ctr">50%</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_prv_amt'], 2 )}}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_prv_discount'], 2) }}</td>
                <!-- previous -->
                <td class="border_all ctr">50%</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_prev_prv_amt'], 2) }}</td>
                <!-- 1992-above -->
                <td class="border_all ctr">50%</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_prv_prior_1992_amt'], 2) }}</td>
                <!-- 1991-below -->
                <td class="border_all ctr">50%</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_prv_prior_1991_amt'], 2) }}</td>
                <!-- penalties -->
                <td class="border_all ctr">50%</td>
                <td colspan="3" class="border_all val">{{ zeroToDash($merged['sef_prv_penalty'], 2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_prev_prv_penalty'], 2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_prv_prior_1992_penalties'], 2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_prv_prior_1991_penalties'], 2) }}</td>
                <!-- total -->
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_prv_net'], 2) }}</td>
            </tr>
            <tr>
                <th colspan="4"></th>
                <td colspan="3" style="text-align:left">Municipal Share</td>
                <!-- advance -->
                <td class="border_all ctr">50%</td>
                <td class="border_all val" colspan="2">{{ zeroToDash($merged['sef_mnc_adv_amt'], 2) }}</td>
                <td class="border_all val" colspan="2">{{ zeroToDash($merged['sef_mnc_adv_discount'], 2) }}</td>
                <!-- current -->
                <td class="border_all ctr">50%</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_mncpl_crnt'],2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_mncpl_dscnt'],2) }}</td>
                <!-- previous -->
                <td class="border_all ctr">50%</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_mncpl_prev'],2) }}</td>
                <!-- 1992-above -->
                <td class="border_all ctr">50%</td>
                <td class="border_all val" colspan="2">{{ zeroToDash($merged['sef_mnc_prior_1992_amt'], 2) }}</td>
                <!-- 1991-below -->
                <td class="border_all ctr">50%</td>
                <td class="border_all val" colspan="2">{{ zeroToDash($merged['sef_mnc_prior_1991_amt'], 2) }}</td>
                <!-- penalties -->
                <td class="border_all ctr">50%</td>
                <td colspan="3" class="border_all val">{{ zeroToDash($merged['sef_mncpl_pen_crnt'],2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_mncpl_pen_crnt_prev'],2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_mnc_prior_1992_penalties'], 2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_mnc_prior_1991_penalties'], 2) }}</td>
                <!-- total -->
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_total_basic_net'],2) }}</td>
            </tr>
            <tr>
                <th colspan="4"></th>
                <th colspan="3" style="text-align:left">TOTAL(S)</th>
                <!-- advance --> 
                <td class="border_all"></td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_total_adv_amt'], 2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_total_adv_discount'], 2) }}</td>
                <!-- current -->
                <td class="border_all"></td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_curr_total'], 2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_curr_discount_total'], 2) }}</td>
                <!-- previous -->
                <td class="border_all"></td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_prev_total'], 2) }}</td>
                <!-- 1992-above -->
                <td class="border_all"></td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_total_prior_1992_amt'], 2) }}</td>
                <!-- 1991-below -->
                <td class="border_all"></td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_total_prior_1991_amt'], 2) }}</td>
                <!-- penalties -->
                <td class="border_all"></td>
                <td colspan="3" class="border_all val">{{ zeroToDash($merged['sef_curr_pen_total'], 2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_prev_penalty_total'], 2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_total_prior_1992_penalties'], 2) }}</td>
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['sef_total_prior_1991_penalties'], 2) }}</td>
                <!-- total -->
                <td colspan="2" class="border_all val">{{ zeroToDash($merged['gtotal_sef'], 2) }}</td>
            </tr>
            <tr>
                <td colspan="7"></td>
                <td colspan="31" class="border_all"></td>
            </tr>
        </table>
    </div>
    <div style="width: 200px;font-weight:bold;float:right;margin-top:20px; font-size: 10px;">
          Certified Correct By:  <br><br>
          <center>
          ISABEL KIW-AN<br>
          LCRO-IV
          </center> 
    </div>
</body>
</html>