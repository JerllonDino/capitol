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
            <td>A. COLLECTIONS</td>
            <td></td>
            <td>Date</td>
            <td>{{ $report_date }}</td>
        </tr>
    </table>
    <table class="border_all_table" style="background-color: ##5af542;">
        <thead>
            <tr>
                <th class="border_all" rowspan="3">Date</th>
                <th class="border_all" rowspan="3">Tax Payor</th>
                <th class="border_all" rowspan="3">Period Covered</th>
                <th class="border_all" rowspan="3">OR No.</th>
                <th class="border_all" rowspan="3">TD/ARP</th>
                <th class="border_all" rowspan="3">Brgy.</th>
                <th class="border_all" rowspan="3">Class</th>
                <th class="border_all" colspan="4">Basic Tax</th>
                <th class="border_all" colspan="4">SEF</th>
                <th class="border_all" rowspan="3">Grand Total (gross)</th>
                <th class="border_all" rowspan="3">Grand Total (net)</th>
            </tr>
            <tr>
                <th class="border_all" rowspan="2">Advance Year Gross Amt.</th>
                <th class="border_all" rowspan="2">Discount</th>
                <th class="border_all" rowspan="2">Sub Total Gross Collections</th>
                <th class="border_all" rowspan="2">Sub Total Net Collections</th>
                <th class="border_all" rowspan="2">Advance Year Gross Amt.</th>
                <th class="border_all" rowspan="2">Discount</th>
                <th class="border_all" rowspan="2">Sub Total Gross Collections</th>
                <th class="border_all" rowspan="2">Sub Total Net Collections</th>
            </tr>
            <tr></tr>
        </thead>
        <tbody>
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

            $adv_val_total = 0;
            $adv_discount_total = 0;
            $adv_gross_total = 0;
            $adv_net_total = 0;
            $gtotal_gross = 0;
            $gtotal_net = 0;
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
                    @if($receipt->F56Detailmny()->count() > 0)
                        @foreach ($receipt->F56Detailmny as $f56_detail)
                            @if($f56_detail->period_covered == \Carbon\Carbon::now()->addYear()->format('Y') || preg_match('/advance/i', $f56_detail->period_covered) == 1)
                                <?php
                                    $counter++; 
                                    // advance values
                                    $adv_val = $f56_detail->tdrp_assedvalue/100;
                                    $adv_discount = ($f56_detail->tdrp_assedvalue/100)*.10; // fixed, 10% discount for the ff. year
                                    $adv_gross = $adv_val;
                                    $adv_net = $adv_val - $adv_discount;
                                    $adv_val_total += $adv_val;
                                    $adv_discount_total += $adv_discount;
                                    $adv_gross_total += $adv_gross;
                                    $adv_net_total += $adv_net;
                                    $gtotal_gross += $adv_gross*2;
                                    $gtotal_net += $adv_net*2;
                                ?>
                                <tr style="background-color: ##5af542;">
                                    @if ($rcpt_done == 0)
                                           <?php $rcpt_done = 1; ?>
                                        <td class="border_all" >
                                            @if(\Carbon\Carbon::parse($receipt->date_of_entry)->format('M') == 'Sep')
                                            Sept {{ date('d', strtotime($receipt->date_of_entry)) }}
                                            @else
                                            {{ date('M d', strtotime($receipt->date_of_entry)) }}
                                            @endif
                                        </td>
                                        <td class="border_all">{{ $f56_detail->owner_name }}</td>
                                        <td class="border_all">{{ $f56_detail->period_covered }}</td>
                                        <td class="border_all">{{ $receipt->serial_no }}</td>
                                    @else
                                        <td class="border_all"></td>
                                        <td class="border_all"></td>
                                        <td class="border_all"></td>
                                        <td class="border_all"></td>
                                    @endif
                                    @if(!isset($f56_detail->TDARP[0]))
                                        <td class="border_all"></td>
                                        <td class="border_all">{{ isset($f56_detail->TDARPX->barangay_name->name) ? $f56_detail->TDARPX->barangay_name->name : '' }}</td>
                                        <td class="border_all">{{ substr($f56_detail->F56Type->name, 0, 3) }}</td>
                                        <td class="border_all val">{{ number_format($adv_val, 2) }}</td>
                                        <td class="border_all val">{{ number_format($adv_discount, 2) }}</td>
                                        <td class="border_all val">{{ number_format($adv_gross, 2) }}</td>
                                        <td class="border_all val">{{ number_format($adv_net, 2) }}</td>
                                        <td class="border_all val">{{ number_format($adv_val, 2) }}</td>
                                        <td class="border_all val">{{ number_format($adv_discount, 2) }}</td>
                                        <td class="border_all val">{{ number_format($adv_gross, 2) }}</td>
                                        <td class="border_all val">{{ number_format($adv_net, 2) }}</td>
                                        <td class="border_all val">{{ number_format(($adv_gross + $adv_gross), 2) }}</td>
                                        <td class="border_all val">{{ number_format(($adv_net*2), 2) }}</td>
                                    @else
                                        <td class="border_all">{{ $f56_detail->TDARP[0]->tdarpno }}</td>
                                        <td class="border_all">{{ isset($f56_detail->TDARPX->barangay_name->name) ? $f56_detail->TDARPX->barangay_name->name : '' }}</td>
                                        <td class="border_all">{{ substr($f56_detail->F56Type->name, 0, 3) }}</td>
                                        <td class="border_all val">{{ number_format($adv_val, 2) }}</td>
                                        <td class="border_all val">{{ number_format($adv_discount, 2) }}</td>
                                        <td class="border_all val">{{ number_format($adv_gross, 2) }}</td>
                                        <td class="border_all val">{{ number_format($adv_net, 2) }}</td>
                                        <td class="border_all val">{{ number_format($adv_val, 2) }}</td>
                                        <td class="border_all val">{{ number_format($adv_discount, 2) }}</td>
                                        <td class="border_all val">{{ number_format($adv_gross, 2) }}</td>
                                        <td class="border_all val">{{ number_format($adv_net, 2) }}</td>
                                        <td class="border_all val">{{ number_format(($adv_gross + $adv_gross), 2) }}</td>
                                        <td class="border_all val">{{ number_format(($adv_net*2), 2) }}</td>
                                    @endif
                                </tr>
                            @endif
                        @endforeach
                    @else
                        <?php
                            $counter++; 
                            // advance values
                            $adv_val = $receipt->tdrp_assedvalue/100;
                            $adv_discount = ($receipt->tdrp_assedvalue/100)*.10; // fixed, 10% discount for the ff. year
                            $adv_gross = $adv_val;
                            $adv_net = $adv_val - $adv_discount;
                            $adv_val_total += $adv_val;
                            $adv_discount_total += $adv_discount;
                            $adv_gross_total += $adv_gross;
                            $adv_net_total += $adv_net;
                            $gtotal_gross += $adv_gross*2;
                            $gtotal_net += $adv_net*2;
                        ?>
                        <tr style="background-color: ##5af542;">
                            @if ($rcpt_done == 0)
                                   <?php $rcpt_done = 1; ?>
                                <td class="border_all" >
                                    {{ date('M d', strtotime($receipt->report_date)) }}
                                </td>
                                <td class="border_all">{{ $receipt->owner_name }}</td>
                                <td class="border_all">{{ $receipt->period_covered }}</td>
                                <td class="border_all">{{ $receipt->serial_no }}</td>
                            @else
                                <td class="border_all"></td>
                                <td class="border_all"></td>
                                <td class="border_all"></td>
                                <td class="border_all"></td>
                            @endif
                                <td class="border_all"></td>
                                <td class="border_all">{{ isset($receipt->brgy_name) ? $freceipt->brgy_name : '' }}</td>
                                <td class="border_all">{{ substr($reeipt->class, 0, 3) }}</td>
                                <td class="border_all val">{{ number_format($adv_val, 2) }}</td>
                                <td class="border_all val">{{ number_format($adv_discount, 2) }}</td>
                                <td class="border_all val">{{ number_format($adv_gross, 2) }}</td>
                                <td class="border_all val">{{ number_format($adv_net, 2) }}</td>
                                <td class="border_all val">{{ number_format($adv_val, 2) }}</td>
                                <td class="border_all val">{{ number_format($adv_discount, 2) }}</td>
                                <td class="border_all val">{{ number_format($adv_gross, 2) }}</td>
                                <td class="border_all val">{{ number_format($adv_net, 2) }}</td>
                                <td class="border_all val">{{ number_format(($adv_gross + $adv_gross), 2) }}</td>
                                <td class="border_all val">{{ number_format(($adv_net*2), 2) }}</td>
                        </tr>
                    @endif
                @endif

        @endforeach
        <tr>
            <th class="border_all" colspan="7">TOTAL COLLECTION</th>
            <th class="border_all val">{{ number_format($adv_val_total, 2) }}</th>
            <th class="border_all val">{{ number_format($adv_discount_total, 2) }}</th>
            <!--{{-- <th class="border_all val">{{ number_format($total_basic_previous, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_penalty_current, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_penalty_previous, 2) }}</th> --}}-->
            <th class="border_all val">{{ number_format($adv_gross_total, 2) }}</th>
            <th class="border_all val">{{ number_format($adv_net_total, 2) }}</th>
            <th class="border_all val">{{ number_format($adv_val_total, 2) }}</th>
            <th class="border_all val">{{ number_format($adv_discount_total, 2) }}</th>
            <!--{{-- <th class="border_all val">{{ number_format($total_basic_previous, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_penalty_current, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_penalty_previous, 2) }}</th> --}}-->
            <th class="border_all val">{{ number_format($adv_gross_total, 2) }}</th>
            <th class="border_all val">{{ number_format($adv_net_total, 2) }}</th>
            <th class="border_all val">{{ number_format($gtotal_gross, 2) }}</th>
            <th class="border_all val">{{ number_format($gtotal_net, 2) }}</th>
        </tr>
        <tr>
            <td colspan="17" class="border_all"></td>
        </tr>

        <tr>
            <th colspan="17">&nbsp;</th>
        </tr>
        </tbody>
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