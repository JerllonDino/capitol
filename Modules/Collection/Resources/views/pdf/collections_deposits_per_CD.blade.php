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

            @if ($total_columns >= 18)
                {{ Html::style('/base/css/collections_deposti20.css') }}
            @elseif($total_columns >= 15)
                {{ Html::style('/base/css/collections_deposti15.css') }}
            @elseif($total_columns >= 10)
                {{ Html::style('/base/css/collections_deposti10.css') }}
            @elseif($total_columns >= 1)
                {{ Html::style('/base/css/collections_deposti1.css') }}
            @endif
</head>
<body>

<?php  $gtotal = 0; 
    $summary_total = 0;
    $total_with_ada = 0;
    $has_ada = 0;
    $ada = 0;
        foreach ($trantypes as $i => $type){
                    if ($i == 4){
                        if ($type['total'] > 0){
                          $ada = $type['total'];
                          $has_ada = 1;
                        }
                        $total_with_ada += $type['total'];
                    }else{
                        $total_with_ada += $type['total'];
                        $summary_total += $type['total'];
                    }
                        
                    
        }
        
?>

    <!-- ACCOUNTABILITY FOR ACCOUNTABLE FORMS -->.
    <h4 class="mgpd0">C. ACCOUNTABILITY FOR ACCOUNTABLE FORMS</h4>
    <table class="table table-bordered table-condensed">
    <thead>
        <tr class="page-break">
            <th class="" rowspan="3">Name of Forms & No.</th>
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
                Accountable Form 51
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

    <!-- SUMMARY OF COLLECTIONS AND REMITTANCES/DEPOSITS -->
    <div style="page-break-inside: avoid;">
        <table class="table  table-no-border table-condensed" style="page-break-inside: avoid;">
        <tr>
            <td colspan="2">  <h4>D. SUMMARY OF COLLECTIONS AND REMITTANCES/DEPOSITS</h4></td>
        </tr>
            <tr>
                <td class="table-border-right">
                    <table class="table table-condensed">
                        <tr>
                            <td class="">Beginning Balance {{ $report_start }}</td>
                            <td class=" val"></td>
                            <td class=" val text-right">0.00</td>
                        </tr>
                        <tr>
                            <td class="">Add: Collections {{ $date_range }}</td>
                            <td class=" val"></td>
                            <td></td>
                        </tr>
                        <?php $bank_depo = 0; $bank_depo_name = ''; ?>
                        @foreach ($trantypes as $i => $type)
                            <?php
                                if($i == 5) { // get value for Bank Deposit/Transfer
                                    $bank_depo = $type['total'];
                                    $bank_depo_name = $type['name'];
                                }
                            ?>
                            <tr>
                                <td class=" tdindent">{{ $type['name'] }}</td>
                                <td class=" val text-right">
                                    {{ number_format($type['total'], 2) }}
                                </td>
                                <td class=" val text-right">
                                    @if($i == 5)
                                        {{ number_format($summary_total, 2) }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                            <tr>
                                <td class=""><b>Total Collections</b></td>
                                <td></td>
                                <td class=" val text-right"><b>{{ number_format($total_with_ada, 2) }}</b></td>
                            </tr>
                        @if ($has_ada)
                            <tr>
                                <td class=""><b>Less ADA</b></td>
                                <td></td>
                                <td class=" val text-right"><b>{{ number_format($ada, 2) }}</b></td>
                            </tr>
                        @endif
                        <tr>
                            <td class=""><b>Less: {{ $bank_depo_name }}</b></td>
                            <td></td>
                            <td class=" val text-right"><b>{{ number_format($bank_depo, 2) }}</b></td>
                        </tr>
                        <tr>
                            <td class=""><b>Remittance/Deposit to Cashier/Treasurer</b></td>
                            <td></td>
                            <td class=" val text-right"><b>{{ number_format($summary_total - $bank_depo, 2) }}</b></td>
                        </tr>
                        <tr>
                            <td class=""><b>Balance</b></td>
                            <td class=" val text-right"><b></b></td>
                            <td class=" val text-right"><b>0.00</b></td>
                        </tr>
                    </table>
                </td>

                <td >
                    <span class="hidden">
                    {{ $bank_total = 0 }}
                    </span>
                    <table class="table table-condensed table-bordered">
                     <thead>
                                <tr>
                                    <th class="">Drawee Bank</th>
                                    <th class="">Check No.</th>
                                    <th class="">Payee</th>
                                    <th class="">Amount</th>
                                </tr>
                    </thead>
                        <tbody>
                                @php
                                    $compiled_rec = array();
                                
                                foreach($bank as $b){
                                    if(!isset($compiled_rec[$b['check_no']])){
                                        $compiled_rec[$b['check_no']]['amt'] = 0;
                                     }
                                    $compiled_rec[$b['check_no']]['amt'] += $b['amt'];
                                    $compiled_rec[$b['check_no']]['bank'] = $b['bank'];
                                }
                                @endphp
                                
                                 @foreach($compiled_rec as $key => $b)
                                    <tr>
                                        <td class="">{{ $b['bank'] }}</td>
                                        <td class="">{{ $key }}</td>
                                        <td class="">Provincial Government of Benguet</td>
                                        <td class="val text-right">
                                            <span class="hidden">
                                            {{ $bank_total += $b['amt'] }}
                                            </span>
                                            {{ number_format($b['amt'], 2) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td class="" colspan="3">Total</td>
                                        <td class="val text-right">{{ number_Format($bank_total, 2) }}</td>
                                    </tr>
                        </tbody>
                    </table>
                </td>
        </tr>
        </table>
    </div>

    <!-- CERTIFICATION/VERIFICATION AND ACKNOWLEDGEMENT -->
    <table class="table table-borderedx" style="padding-top: -20px;">
        <tr>
        <td class="table-border-right">
        <table class="table table-no-border">
            <tr>
                <th  class="border-botts">CERTIFICATION</th>
            </tr>
            <tr>
                <td class="">
                    <table class="table table-no-border">
                        <tr>
                            <td colspan="3">
                            I hereby certify that the foregoing report of collections
                            and accountability for Accountable Forms is true and correct.
                            </td>
                        </tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr >
                            <th class="border-botts">{{ $acctble_officer_name->value }}</th>
                            <th></th>
                            <th class="border-botts">{{ \Carbon\Carbon::parse($report_date)->format('M') }}. {{ \Carbon\Carbon::parse($report_date)->format('d, Y') }}</th>
                        </tr>
                        <tr>
                            <th colspan="2">{{ $acctble_officer_position->value }}</th>
                            <th>Date</th>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        </td>
        <td>
        <table class="table table-no-border">
            <tr>
                <th  class="border-botts">VERIFICATION AND ACKNOWLEDGEMENT</th>
            </tr>
            <tr>
                <td class="">
                    <table class="table table-no-border">
                        <tr>
                            <td colspan="3">
                            I hereby certify that the foregoing report of collections
                            has been verified and acknowledge receipt of

                            <b><u>{{ $total_in_words }} @if(strlen($total_in_words) > 96)  <br> @endif </u> (PHP {{ number_format($summary_total - $bank_depo, 2) }})</b>.
                            </td>
                        </tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr >
                            <th class="border-botts" style="width:50%">
                                @if ($_GET['type'] == 5)
                                    @if(isset($trust_fund_officer_name->value))
                                        {{ $trust_fund_officer_name->value }}
                                    @else 
                                        {{ $trust_fund_officer_name }}
                                    @endif
                                @elseif($_GET['type'] == 2)
                                    @if(isset($bts_report_officer->value))
                                        {{ $bts_report_officer->value }}
                                    @else 
                                        {{ $bts_report_officer }}
                                    @endif
                                @elseif($_GET['type'] == 3)
                                    @if(isset($bese_report_officer->value))
                                        {{ $bese_report_officer->value }}
                                    @else 
                                        {{ $bese_report_officer }}
                                    @endif
                                @else
                                    @if(isset($officer_name->value))
                                        {{ $officer_name->value }}
                                    @else 
                                        {{ $officer_name }}
                                    @endif
                                @endif
                            </th>
                            <th></th>
                            <!-- <th class="border-botts"> date('F d, Y') </th> -->
                            <th class="border-botts">{{ \Carbon\Carbon::parse($report_date)->format('M') }}. {{ \Carbon\Carbon::parse($report_date)->format('d, Y') }}</th>
                        </tr>
                        <tr>
                            <th>
                                @if ($_GET['type'] == 2) 
                                    @if(isset($bts_report_officer_position->value))
                                        {{ $bts_report_officer_position->value }}
                                    @else 
                                        {{ $bts_report_officer_position }}
                                    @endif
                                @elseif($_GET['type'] == 3)
                                    @if(isset($bese_report_officer_position->value))
                                        {{ $bese_report_officer_position->value }}
                                    @else 
                                        {{ $bese_report_officer_position }}
                                    @endif
                                @elseif($_GET['type'] == 5)
                                    @if(isset($trustfund_officer_position->value))
                                        {{ $trustfund_officer_position->value }}
                                    @else 
                                        {{ $trustfund_officer_position }}
                                    @endif
                                @else
                                    @if(isset($officer_position->value))
                                        {{ $officer_position->value }}
                                    @else 
                                        {{ $officer_position }}
                                    @endif
                                @endif
                            </th>
                            <th></th>
                            <th>Date</th>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        </td>
        </tr>
        </table>

</body>
</html>
