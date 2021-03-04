<!DOCTYPE html>
<html>
<head>
    <title>Collections And Deposits</title>
    {{ Html::style('/bootstrap-3.3.6/css/bootstrap.min.css') }}
    <style type="text/css">
        html {
            margin-bottom: 8px;
            margin-top: 2cm;
            margin-left: 60px;
            margin-right: 60px;
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
            padding: 1px 2px 0px 2px ;
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
            font-size:12px;
            height:10;
        }

         .footer {
            bottom:15px;
            position: fixed;
             width: 100%;
             color:#717477;
             font-size: 9px;
               text-align: center;
        }

         .pagenum:before {
            /*content: counter(page);*/
        }

        .mgpd0{
            margin: 0px;
            padding: 0px;
        }



    </style>

</head>
<body>

    <!-- ACCOUNTABILITY FOR ACCOUNTABLE FORMS -->.
    <h4 class="mgpd0">C. ACCOUNTABILITY FOR ACCOUNTABLE FORMS</h4>
    <table class="table table-bordered table-condensed">
    <thead>
        <tr class="page-break">
            <th class="" rowspan="3">Name of Forms</th>
            <th class="" colspan="3">Beginning Balance</th>
            <th class="" colspan="3">Receipt</th>
            <th class="" colspan="3">Issued</th>
            <th class="" colspan="3">Ending Balance</th>
        </tr>
        <tr class="page-break">
            <th class="text-center" rowspan="2">Qty.</th>
            <th class="" colspan="2">Inclusive Serial Nos.</th>
            <th class="text-center" rowspan="2">Qty.</th>
            <th class="" colspan="2">Inclusive Serial Nos.</th>
            <th class="text-center" rowspan="2">Qty.</th>
            <th class="" colspan="2">Inclusive Serial Nos.</th>
            <th class="text-center" rowspan="2">Qty.</th>
            <th class="" colspan="2">Inclusive Serial Nos.</th>
        </tr>
        <tr class="page-break">
            <th class="text-center">From</th>
            <th class="text-center">To</th>
            <th class="text-center">From</th>
            <th class="text-center">To</th>
            <th class="text-center">From</th>
            <th class="text-center">To</th>
            <th class="text-center">From</th>
            <th class="text-center">To</th>
        </tr>
        <tr class="page-break">
            <th class="" colspan="13">
                Form 51
                <span class="hidden">
                {{ $beg_total = 0 }}
                {{ $rec_total = 0 }}
                {{ $iss_total = 0 }}
                {{ $end_total = 0 }}
                </span>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rcpt_acct as $rcpt)
        <?php
            // if($rcpt['end_qty'] <= 0)
            //     continue;
            $beg_total += $rcpt['beg_qty']?$rcpt['beg_qty']:0;
            $rec_total += $rcpt['rec_qty']?$rcpt['rec_qty']:0;
            $iss_total += $rcpt['iss_qty']?$rcpt['iss_qty']:0;
            $end_total += $rcpt['end_qty']?$rcpt['end_qty']:0;
        ?>
        <tr class="page-break">
            <td class="">
            </td>
            <td class="text-center val">{{ ($rcpt['beg_qty']) }}</td>
            <td class="text-center val">{{ $rcpt['beg_from'] }}</td>
            <td class="text-center val">{{ $rcpt['beg_to'] }}</td>
            <td class="text-center val">{{ ($rcpt['rec_qty']) }}</td>
            <td class="text-center val">{{ $rcpt['rec_from'] }}</td>
            <td class="text-center val">{{ $rcpt['rec_to'] }}</td>
            <td class="text-center val">{{ ($rcpt['iss_qty']) }}</td>
            <td class="text-center val">{{ $rcpt['iss_from'] }}</td>
            <td class="text-center val">{{ $rcpt['iss_to'] }}</td>
            <td class="text-center val">{{ $rcpt['end_qty'] > 0 ? $rcpt['end_qty'] : "-" }}</td>
            <td class="text-center val">{{ $rcpt['end_qty'] > 0 ? $rcpt['end_from'] : "-" }}</td>
            <td class="text-center val">{{ $rcpt['end_qty'] > 0 ? $rcpt['end_to'] : "-" }}</td>
        </tr>
        @endforeach
        <tr class="page-break">
            <td class="text-center val"></td>
            <td class="text-center val"><b>{{ number_format($beg_total,0) }}</b></td>
            <td class="text-center val"></td>
            <td class="text-center val"></td>
            <td class="text-center val"><b>{{ number_format($rec_total,0) }}</b></td>
            <td class="text-center val"></td>
            <td class="text-center val"></td>
            <td class="text-center val"><b>{{ number_format($iss_total,0) }}</b></td>
            <td class="text-center val"></td>
            <td class="text-center val"></td>
            <td class="text-center val"><b>{{ number_format($end_total,0) }}</b></td>
            <td class="text-center val"></td>
            <td class="text-center val"></td>
        </tr>
        </tbody>
    </table>

    <div style="page-break-after: always;"></div>

</body>
</html>
