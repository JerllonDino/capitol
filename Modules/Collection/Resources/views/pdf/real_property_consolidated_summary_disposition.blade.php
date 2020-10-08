<!DOCTYPE html>
<html>
<head>
    <title>Collections And Deposits (Summary and Disposition)</title>
    {{ Html::style('/bootstrap-3.3.6/css/bootstrap.min.css') }}
    <style type="text/css">
        html {
            margin-bottom: 8px;
            margin-top: 8px;
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

        @media print {
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

        .val{
            text-align: right;
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
            <td>{{ $date_range }}</td>
        </tr>
    </table>
    <table>
        <tr>
			<td></td>
			<td width="70%"></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
			<td>A. COLLECTIONS</td>
			<td></td>
            <td>Date</td>
            <td>{{ $report_date }}</td>
        </tr>
    </table>
    <table class="table-bordered hidden">
        <thead>
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
        </thead>
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
        <tbody>
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
                            <td class="border_all" rowspan=" {{ $receipt->F56Detailmny()->count() }}">
                                {{ date('M d', strtotime($receipt->report_date)) }}
                            </td>
                            <td class="border_all"  rowspan=" {{ $receipt->F56Detailmny()->count() }}" >{{ $receipt->customer->name }}</td>
                            <td class="border_all"  rowspan=" {{ $receipt->F56Detailmny()->count() }}" >{{ $f56_detail->period_covered }}</td>
                            <td class="border_all"  rowspan=" {{ $receipt->F56Detailmny()->count() }}" >{{ $receipt->serial_no }}</td>
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
                            <td class="border_all val">{{ number_format(($basic_net + $basic_net), 2) }} </td>
                        @endif
                       

                    </tr>
                   
                @endforeach
                @endif
                @endif

        @endforeach
        </tbody>
        <tfoot>
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

         </tfoot>
    </table>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th colspan="4"></th>
            <td class="fs"><b>Summary</b></td>
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
            <td colspan="2" class="fs">{{ $type->name }}</td>
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
    </tbody>
    <tfoot>
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
        </tfoot>
    </table>

    <table class="table table-bordered">
        <tbody>
        <!-- DISPOSITION SECTION -->
        <tr>
            <th colspan="4"></th>
            <td style="font-size: 14px;"><b>Disposition</b></td>
            <th colspan="2"></th>
            <th colspan="5" class="border_all">CURRENT</th>
            <th colspan="3" class="border_all">PREVIOUS</th>
            <th colspan="6" class="border_all">PENALTIES</th>
            <th colspan="2" rowspan="2" class="border_all">TOTAL</th>
        </tr>
        <tr>
            <th colspan="4"></th>
            <td colspan="3" class="fs">BASIC TAX 1%</td>
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
            {{ $munshare_basic_current = ($total_basic_current * .4) }}
            {{ $munshare_basic_discount = ($total_basic_discount * .4) }}
            {{ $munshare_basic_previous = ($total_basic_previous * .4) }}
            {{ $munshare_basic_penalty_current = ($total_basic_penalty_current * .4) }}
            {{ $munshare_basic_penalty_previous = ($total_basic_penalty_previous * .4) }}
            {{ $munshare_basic_net = ($total_basic_net * .4) }}

            {{ $brgyshare_basic_current = ($total_basic_current * .25) }}
            {{ $brgyshare_basic_discount = ($total_basic_discount * .25) }}
            {{ $brgyshare_basic_previous = ($total_basic_previous * .25) }}
            {{ $brgyshare_basic_penalty_current = ($total_basic_penalty_current * .25) }}
            {{ $brgyshare_basic_penalty_previous = ($total_basic_penalty_previous * .25) }}
            {{ $brgyshare_basic_net = ($total_basic_net * .25) }}
            </span>
            <th colspan="4"></th>
            <td colspan="3" class="fs">Provincial Share</td>
            <td class="border_all ctr">35%</td>
            <td colspan="2" class="border_all val">
                {{ number_format($total_basic_current - ($munshare_basic_current + $brgyshare_basic_current), 2) }}
            </td>
            <td colspan="2" class="border_all val">
                {{ number_format($total_basic_discount - ($munshare_basic_discount + $brgyshare_basic_discount), 2) }}
            </td>
            <td class="border_all ctr">35%</td>
            <td colspan="2" class="border_all val">
                {{ number_format($total_basic_previous - ($munshare_basic_previous + $brgyshare_basic_previous), 2) }}
            </td>
            <td class="border_all ctr">35%</td>
            <td colspan="3" class="border_all val">
                {{ number_format($total_basic_penalty_current - ($munshare_basic_penalty_current + $brgyshare_basic_penalty_current), 2) }}
            </td>
            <td colspan="2" class="border_all val">
                {{ number_format($total_basic_penalty_previous - ($munshare_basic_penalty_previous + $brgyshare_basic_penalty_previous), 2) }}
            </td>
            <td colspan="2" class="border_all val">
                {{ number_format($total_basic_net - ($munshare_basic_net + $brgyshare_basic_net), 2) }}
            </td>
        </tr>
        <tr>
            <th colspan="4"></th>
            <td colspan="3" class="fs">Municipal Share</td>
            <td class="border_all ctr">40%</td>
            <td colspan="2" class="border_all val">{{ number_format($munshare_basic_current,2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format($munshare_basic_discount,2) }}</td>
            <td class="border_all ctr">40%</td>
            <td colspan="2" class="border_all val">{{ number_format($munshare_basic_previous,2) }}</td>
            <td class="border_all ctr">40%</td>
            <td colspan="3" class="border_all val">{{ number_format($munshare_basic_penalty_current,2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format($munshare_basic_penalty_previous,2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format($munshare_basic_net,2) }}</td>
        </tr>
        <tr>
            <th colspan="4"></th>
            <td colspan="3" class="fs">Barangay Share</td>
            <td class="border_all ctr">25%</td>
            <td colspan="2" class="border_all val">{{ number_format($brgyshare_basic_current,2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format($brgyshare_basic_discount,2) }}</td>
            <td class="border_all ctr">25%</td>
            <td colspan="2" class="border_all val">{{ number_format($brgyshare_basic_previous,2) }}</td>
            <td class="border_all ctr">25%</td>
            <td colspan="3" class="border_all val">{{ number_format($brgyshare_basic_penalty_current,2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format($brgyshare_basic_penalty_previous,2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format($brgyshare_basic_net,2) }}</td>
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
        <tr>
            <th colspan="4"></th>
            <td colspan="3" class="fs"><b>SEF TAX 1%</b></td>
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
            <td colspan="3" class="fs">Provincial Share</td>
            <td class="border_all ctr">50%</td>
            <td colspan="2" class="border_all val">{{ number_format($total_basic_current * .5, 2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format($total_basic_discount * .5, 2) }}</td>
            <td class="border_all ctr">50%</td>
            <td colspan="2" class="border_all val">{{ number_format($total_basic_previous * .5, 2) }}</td>
            <td class="border_all ctr">50%</td>
            <td colspan="3" class="border_all val">{{ number_format($total_basic_penalty_current * .5, 2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format($total_basic_penalty_previous * .5, 2) }}</td>
            <td colspan="2" class="border_all val">{{ number_format($total_basic_net * .5, 2) }}</td>
        </tr>
        <tr>
            <th colspan="4"></th>
            <td colspan="3" class="fs">Municipal Share</td>
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
        </tbody>
    </table>
</body>
</html>

