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
        Provincial Health Office<br />
        Period of Collection of {{ $date_range }}
        </td>
        </tr>
    </table>
</div>

<div class="otherpage" style="margin-top: 50px">
    <table class="table table-condensed" >
        <tr>
            <td><b>{{ $account->name }}</b></td>
            <td></td>
            <td class="underline"></td>
        </tr>
        <tr>
            <td>Accountable Collector: <b>{{ $acctble_officer_name->officer_name }}</b></td>
            <td class="val">Report No.</td>
            <td class="underline">PVET-{{ $_GET['report_no'] }}</td>
        </tr>
    </table>

<h4>A. COLLECTIONS</h4>
<div class="table-responsive col-sm-6">
    <table id="collections" class="table table-bordered table-condensed table-responsive page-break">
    <thead style="text-align: center;">
        <tr class="page-break">
            <th>Date</th>
            <th>OR Nos.</th>
            <th>Payor</th>
            <th>TOTAL AMOUNT</th>
        </tr>
  
</thead>
        <!-- VALUES PER RECEIPT -->
<tbody>
    @php
        $subTotal = 0;
        $total = 0;
    @endphp
@foreach ($receipts as $i => $receipt)
<tr>
    <td>{{ date("j-M", strtotime($receipt->report_date)) }}</td>
    <td>{{ $receipt->serial_no }}</td>
    <td>{{ $receipt->customer->name }}</td>
    <td>
        @php
            $sum = 0;   
        @endphp
        @foreach ($receipt->items as $item)
            @php
                $sum += $item->value;
            @endphp
        @endforeach
        {{ $sum }}
        @php
            $subTotal =+ $sum;
        @endphp
    </td>
</tr>

@if ($i == 45 || $i == count($receipts)-1)
    @php
        $total += $subTotal;
    @endphp
    <tr>
        <td></td>
        <td><b>Sub Total</b></td>
        <td></td>
        <td>{{ number_format((float)$subTotal, 2, '.', '')}}</td>
    </tr>
    @if ($i == count($receipts)-1)
        <tr>
            <td></td>
            <td><b>Total</b></td>
            <td></td>
            <td>{{ number_format((float)$total, 2, '.', '')}}</td>
        </tr>
    @endif
    @if ($i == 45)
        <tr style="border:none"><td colspan=4 style="border:none"><div class="table-break"></div></td></tr>
        @php
            $subTotal = 0;
        @endphp
    @endif
        
    @endif
@endforeach
</tbody>
</table>
</div>
<h4>B. REMITTANCES/DEPOSITS</h4>
<div class="table-responsive col-sm-6">
    <table id="collections" class="table table-bordered table-condensed table-responsive page-break">
    <thead style="text-align: center;">
        <tr class="page-break">
            <th>Accountable Officer/Bank</th>
            <th>Reference No.</th>
            <th>Amount</th>
        </tr>
</thead>
<tbody>
    <tr>
        <td>IRENE C. BAGKING</td>
        <td>{{ $_GET['report_no'] }}</td>
        <td>{{ number_format((float)$total, 2, '.', '')}}</td>
    </tr>
</tbody>
</table>
</div>
</body>
</html>
