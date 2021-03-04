<!DOCTYPE html>
<html>
<head>
    <title>Collections And Deposits A</title>
    {{ Html::style('/base/css/pdf.css') }}
    {{-- {{ Html::style('/bootstrap-3.3.6/css/bootstrap.min.css') }} --}}
    <style type="text/css">
        html {
            margin-bottom: 8px;
            margin-top: 1.5cm;
            margin-left: 15px;
            margin-right: 15px;
        }
        body {
            font-family: 'Helvetica'
        }
        /* class works for table row */
        table tr.page-break{
          /*page-break-after:always*/
        }

        .table-break{
            page-break-before: always;
            /* display: none; */
        }

        /* class works for table */
        table.page-break{
          /*page-break-after:always*/
        }

        #collections>thead>tr>th,#collections>tbody>tr>td, #collections>tbody>tr>th{
            font-size: 12px;
            text-align: center;
            padding: 1px;
        }

        .small-launay>thead>tr>th,.small-launay>tbody>tr>th,.small-launay>tbody>tr>td{
            font-size: 9px;
            padding: 1px;
        }

        .center {
                width: 450px;
                text-align: center;
                margin: 10px auto;
        }

       .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
            /* border-top: 1px solid #868282;
            border-bottom: 1px solid #868282; */
            border:none;
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

        .pagenum:before {
            content: counter(page);
        }

         .footer {
            bottom:15px;
            position: fixed;
            width: 100%;
            color:#44474c;
            font-size: 9px;
            text-align: center;
        }
         .firstpage { 
            position: absolute;
          top: -1.6cm; 
          width: 100%;
          height: 100px;
          margin: 0;
        }


        .headerxxxx{
            /*width: 70%;*/
            margin: 0 auto;
            margin-top: 2.2cm;
        }

        h4{
            padding: 1px;
            margin: 1px;
        }

        .detail_payor {
            min-width: 50px !important;
            max-width: 150px !important;
            word-wrap: break-word;
        }
    </style>



</head>
<body>

<div class="footer">
    Page <span class="pagenum"></span>
</div>
    
        <?php 
          $gtotal = 0;
        ?>
<div class="firstpage" style="margin-top: 50px">
    <table class="center">
    <tr>
        <td style="font-size: 12px;">
        REPORT OF COLLECTIONS AND DEPOSITS <br />
        <strong>Office of the Provincial Agriculture</strong><br />
        Period of Collection of {{ $date_range }}
        </td>
        </tr>
    </table>
</div>

<div class="otherpage" style="margin-top: 50px">
    <table class="table table-condensed" >
        <tr>
            <td><b>{{ $account }}</b></td>
            <td></td>
            <td class="underline"></td>
        </tr>
        <tr>
            <td>Accountable Officer: <b>{{ $acctble_officer_name->officer_name }}</b></td>
            <td class="val">Report No.</td>
            <td class="underline">{{ $_GET['report_no'] }}</td>
        </tr>
    </table>

<div class="table-responsive col-sm-6">
    <table id="collections" class="table table-bordered table-condensed table-responsive page-break">
    <thead style="text-align: center;">
        <tr class="page-break">
            <th rowspan="3">Date</th>
            <th rowspan="3">OR Nos.</th>
            <th rowspan="3">Payor</th>
            <th rowspan="3">Particulars</th>
            <th colspan="2">Amount</th>
        </tr>
        <tr>
            <th>Sale</th>
            <th>Lodging</th>
        </tr>
        <tr>
            <th></th>
            <th>Rental</th>
        </tr>
</thead>
        <!-- VALUES PER RECEIPT -->
<tbody>
    @php
        $sum = 0;
    @endphp
@foreach ($receipts as $i => $receipt)
    @foreach ($receipt->items as $item)

        <tr>
            <td>{{ date("j-M", strtotime($receipt->report_date)) }}</td>
            <td>{{ $receipt->serial_no }}</td>
            <td>{{ $receipt->customer->name }}</td>
            <td>{{ $item->nature }}</td>
            @if ($item->col_acct_subtitle_id == 3)
                <td>{{ $item->value }}</td>
                <td></td>
            @else
                <td></td>
                <td>{{ $item->value }}</td>
            @endif
        </tr>
        @php
            $sum += $item->value;
        @endphp
    @endforeach
    @if ($i == count($receipts)-1)
        <tr>
            <td></td>
            <td></td>
            <td><b>Total</b></td>
            <td></td>
            <td></td>
            <td>{{ number_format((float)$sum, 2, '.', '')}}</td>
        </tr>
    @endif

@endforeach
</tbody>
</table>
</div>

</body>
</html>
