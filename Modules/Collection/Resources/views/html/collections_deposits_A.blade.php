<!DOCTYPE html>
<html>
<head>
    <title>Collections And Deposits</title>
    {{ Html::style('/bootstrap-3.3.6/css/bootstrap.min.css') }}
    <style type="text/css">
        html {
            margin-bottom: 8px;
            margin-top: 8px;
            margin-left: 15px;
            margin-right: 15px;
        }
        /* class works for table row */
        table tr.page-break{
          page-break-after:always
        }


        /* class works for table */
        table.page-break{
          page-break-after:always
        }

        @media print {
         .page-break  { display: block; page-break-before: always; }
        }
        .center {
                width: 450px;
                text-align: center;
                margin: 10px auto;
        }

       .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
            border-top: 1px solid #868282;
            border-bottom: 1px solid #868282;
        }
       .table>thead:first-child>tr:first-child>th, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
            border: 1px solid #000;
        }
          .table-no-border>tbody>tr>td, .table-no-border>tbody>tr>th, .table-no-border>tfoot>tr>td, .table>tfoot>tr>th, .table-no-border>thead>tr>td, .table-no-border>thead>tr>th {
            border: none;
        }

        .table-borderedx{
                border: 1px solid #868282;
        }
         .table-border-right{
                border-right: 1px solid #868282;
         }

        .border-top{
                 border-top: 1px solid #000 !important;
        }

        .border-botts {
            border-bottom: 1px solid #000 !important;
        }

        .image_logo{
            width: 100px;
        }

        .val{
            font-weight: bold;
        }

        #collections{
            overflow-y: auto;
        }

        #collections > thead > tr > th, #collections > tbody > tr > td{
            padding: 1px;
        }


    </style>
       
            
</head>
<body>
    <span class="hidden">
         {{ $gtotal = 0 }}
    </span>
    <table class="center ">
    <tr>
        <td>
            <img src="{{asset('asset/images/benguet_capitol.png')}}" class="image_logo" />
        </td>
        <td>
        REPORT OF COLLECTIONS AND DEPOSITS <br />
        <strong>PROVINCIAL GOVERNMENT OF BENGUET</strong><br />
        <strong>OFFICE OF THE PROVINCIAL TREASURER</strong>
        </td>
        </tr>
    </table>

    <table class="table table-condensed ">
        <tr>
            <td>Fund: <b>{{ $base['fund']->name }}</b></td>
            <td>Date</td>
            <td class="underline">{{ $report_date }}</td>
        </tr>
        <tr>
            <td>Name of Accountable Officer: <b>{{ $base['acctble_officer_name']->value }} - {{ $base['acctble_officer_position']->value }}</b></td>
            <td class="val">Report No.</td>
            <td class="underline">{{ $_GET['report_no'] }}</td>
        </tr>

    </table>

<h4>A. COLLECTIONS</h4>
<div class="table-responsive">
    <table id="collections" class="table table-bordered table-condensed table-responsive page-break">
    <thead>
        <tr class="page-break">
            <th class="" rowspan="2">OR Nos.</th>
            <th class=" detail_payor" rowspan="2">Payor</th>
            @foreach($base['accounts'] as $i => $account)
                <th class="" colspan="{{ count($account['titles']) + count($account['subtitles']) }}">{{ $i }}</th>
            @endforeach

            @if (count($base['shares']) > 0)
            <th class="" colspan="{{ $base['share_columns'] }}">MUNICIPAL & BRGY SHARES</th>
            @endif

            <th class="" rowspan="2">TOTAL AMOUNT</th>
        </tr>
        <tr class="page-break">
            @foreach($base['accounts'] as $i => $account)
                @foreach($account['titles'] as $j => $title)
                <?php 
                $acronym = $j ;
                
                ?>
                    <th>{{ $acronym }}</th>
                @endforeach
                @foreach($account['subtitles'] as $j => $subtitle)
                    <?php 
                    $acronym = $j ;
                    ?>
                    <th>{{ $acronym }}</th>
                @endforeach
            @endforeach
            @foreach($base['shares'] as $i => $share)
                <th>{{ $share['name'] }}</th>
                @foreach($share['barangays'] as $j => $barangay)
                <th>{{ $barangay['name'] }}</th>
                @endforeach
            @endforeach
        </tr>
        <tr class="border-botts page-break">
            <th class="border-botts" colspan="{{ $base['total_columns'] + 1 }}">{{ $base['date_range'] }}</th>
        </tr>
</thead>
        <!-- VALUES PER RECEIPT -->
<tbody>
    <?php $total_rc = []; ?>
        @foreach ($base['receipts'] as $i => $receipt)
            <?php
                    if(!isset($total_rc[$receipt->serial_no])){
                         $total_rc[$receipt->serial_no] = 0;
                    }
            ?>

        <tr class="page-break">
            <td class=" val">{{ $receipt->serial_no }}</td>
            @if (!isset($base['receipts_total'][$receipt->serial_no]))
                <td class=" cancelled_remark" colspan="{{ $base['total_columns'] }}">
                    Cancelled - {{ $receipt->cancelled_remark }}
                </td>
            @else
                <td class=" detail_payor val">{{ $receipt->customer->name }}</td>
                @foreach($base['accounts'] as $i => $account)
                    @foreach($account['titles'] as $ji => $title)
                        <td class=" val text-right">
                            @if (isset($title[$receipt->serial_no]))
                            {{ number_format($title[$receipt->serial_no], 2) }}
                            <?php  $total_rc[$receipt->serial_no] += $title[$receipt->serial_no]; ?>

                            @endif
                        </td>
                    @endforeach

                    @foreach($account['subtitles'] as $j => $subtitle)
                        <td class=" val text-right">
                            @if (isset($subtitle[$receipt->serial_no]))
                            {{ number_format($subtitle[$receipt->serial_no], 2) }}
                            <?php  $total_rc[$receipt->serial_no] += $subtitle[$receipt->serial_no]; ?>
                            @endif
                        </td>
                    @endforeach
                @endforeach

                @foreach($base['shares'] as $i => $share)
                    <td class=" val text-right">
                        @if (isset($share[$receipt->serial_no]) && $share[$receipt->serial_no] > 0)
                        {{ number_format($share[$receipt->serial_no], 2) }}
                        <?php  $total_rc[$receipt->serial_no] += $share[$receipt->serial_no]; ?>
                        @endif
                    </td>
                    @foreach($share['barangays'] as $j => $barangay)
                    <td class=" val text-right">
                        @if (isset($barangay[$receipt->serial_no]) && $barangay[$receipt->serial_no] > 0)
                        {{ number_format($barangay[$receipt->serial_no], 2) }}
                        <?php  $total_rc[$receipt->serial_no] += $barangay[$receipt->serial_no]; ?>
                        @endif
                    </td>
                    @endforeach
                @endforeach

                <td class=" border-botts val text-right">
                    <?php 
                        $gtotal += $base['receipts_total'][$receipt->serial_no];
                    ?>
                    {{ number_format($total_rc[$receipt->serial_no], 2) }}
                </td>
            @endif
        </tr>
        @endforeach
        <!-- TOTALS -->
        <tr class="page-break">
            <td class="val" colspan="2">GRAND TOTAL</td>
            @foreach($base['accounts'] as $i => $account)
                @foreach($account['titles'] as $j => $title)
                    <td class="val text-right">
                        {{ number_format($title['total'], 2) }}
                    </td>
                @endforeach

                @foreach($account['subtitles'] as $j => $subtitle)
                    <td class="val text-right">
                        {{ number_format($subtitle['total'], 2) }}
                    </td>
                @endforeach
            @endforeach

            @foreach($base['shares'] as $i => $share)
                <td class=" val text-right">
                    {{ number_format($share['total_share'], 2) }}
                </td>
                @foreach($share['barangays'] as $j => $barangay)
                <td class=" val text-right">
                    {{ number_format($barangay['total_share'], 2) }}
                </td>
                @endforeach
            @endforeach
            <td class=" val text-right">{{ number_format($gtotal, 2) }}</td>
        </tr>
</tbody>

<tfoot>

</tfoot>

</table>
   

</body>
</html>
