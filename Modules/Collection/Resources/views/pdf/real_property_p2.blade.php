<!DOCTYPE html>
<html>
<head>
    <title>Collections And Deposits </title>
    {{ Html::style('/bootstrap-3.3.6/css/bootstrap.min.css') }}
    <style>
        
        html {
            margin-bottom: 8px;
            margin-top: 60px;
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

        .font-small{
            font-size: 12px;
        }
        .head-width{
            width: 125px;
            font-weight: bold;
        }
        .text-hidden{
            color: #FFFF !important;
        }
    </style>
</head>
<body>
    <!-- REMITTANCES/DEPOSITS -->
    <table style="float:right">
        <tr>
            <td class="head-width" style="text-align: right">Report No.:</td>
            <td class="head-width" style="text-align: center">{{$report_no}}</td>
        </tr>
        <tr>
            <td class="head-width" style="text-align: right">Date:</td>
            <td class="head-width" style="text-align: center">{{$report_date}}</td>
        </tr>
    </table><br>
    B. REMITTANCES/DEPOSITS<br><br><br>
        
    <table class="table page-break table-condensed table-bordered">
        <span class="hidden">
        {{ $total = 0 }}
        </span>
        <tr>
            <th >Accountable Officer/Bank</th>
            <th >Reference</th>
            <th >Amount</th>
        </tr>

        @foreach ($remdep as $rd)
            <tr>
                <td style="font-size:12px">{{ $officer_name->value }} - {{ $officer_position->value }}</td>
                <td >{{ $rd['name'] }}</td>
                <td class="text-right">
               <?php
                     $total += $rd['value'];
                ?>
                {{ number_format($rd['value'], 2) }}
                </td>
            </tr>
        @endforeach
        <tr>
            <th >GRAND TOTAL</th>
            <th ></th>
            <th class="text-right" >{{ number_format($total, 2) }}</th>
        </tr>
        <tr>
            <td colspan="3" ></td>
        </tr>
    </table>

    <!-- ACCOUNTABILITY FOR ACCOUNTABLE FORMS -->
    <table class="table page-break table-condensed table-bordered">
    <thead>
        <tr>
            <th class="" rowspan="1"  colspan="13">C. ACCOUNTABILITY FOR ACCOUNTABLE FORMS</th>
        </tr>
        <tr>
            <th  rowspan="3">Name of Forms & No.</th>
            <th  colspan="3">Beginning Balance</th>
            <th  colspan="3">Receipt</th>
            <th  colspan="3">Issued</th>
            <th  colspan="3">Ending Balance</th>
        </tr>
        <tr>
            <th  rowspan="2">Qty.</th>
            <th  rowspan="1" colspan="2" class="font-small" >Inclusive Serial Nos.</th>
            <th  rowspan="2">Qty.</th>
            <th  rowspan="1" colspan="2" class="font-small" >Inclusive Serial Nos.</th>
            <th  rowspan="2">Qty.</th>
            <th  rowspan="1" colspan="2" class="font-small" >Inclusive Serial Nos.</th>
            <th  rowspan="2">Qty.</th>
            <th  rowspan="1" colspan="2" class="font-small" >Inclusive Serial Nos.</th>
        </tr>
        <tr>
            <th rowspan="1" >From</th>
            <th rowspan="1" >To</th>
            <th rowspan="1" >From</th>
            <th rowspan="1" >To</th>
            <th rowspan="1" >From</th>
            <th rowspan="1" >To</th>
            <th rowspan="1" >From</th>
            <th rowspan="1" >To</th>
        </tr>

        <tr>
            <th  colspan="13">
                Accountable Form 56
                <span class="hidden">
                {{ $beg_total = 0 }}
                {{ $rec_total = 0 }}
                {{ $iss_total = 0 }}
                {{ $end_total = 0 }}
                </span>
            </th>
        </tr>
        </thead>
        @foreach ($rcpt_acct as $rcpt)
            @if ($rcpt['src'] == "Other Cities/Prov." || $rcpt['src'] == 'La Trinidad')
                @if (date('m/d/Y', strtotime('12/1/2020')) <= date('m/d/Y', strtotime($report_date)));
                    @php
                        continue;
                    @endphp
                @endif
                
            @endif
        <tr>
            <td >
                <span class="hidden">
                {{ $beg_total += $rcpt['beg_qty']?$rcpt['beg_qty']:0 }}
                {{ $rec_total += $rcpt['rec_qty']?$rcpt['rec_qty']:0 }}
                {{ $iss_total += $rcpt['iss_qty']?$rcpt['iss_qty']:0 }}
                {{ $end_total += $rcpt['end_qty']?$rcpt['end_qty']:0 }}
                </span>
                {{ $rcpt['src'] }}
            </td>
            <td >{{ $rcpt['beg_qty'] }}</td>
            <td >{{ $rcpt['beg_from'] }}</td>
            <td >{{ $rcpt['beg_to'] }}</td>
            <td >{{ $rcpt['rec_qty'] }}</td>
            <td >{{ $rcpt['rec_from'] }}</td>
            <td >{{ $rcpt['rec_to'] }}</td>
            <td >{{ $rcpt['iss_qty'] }}</td>
            <td >{{ $rcpt['iss_from'] }}</td>
            <td >{{ $rcpt['iss_to'] }}</td>
            @if($rcpt['end_qty'] > 0)
            <td >{{ $rcpt['end_qty'] }}</td>
            <td >{{ $rcpt['end_from'] }}</td>
            <td >{{ $rcpt['end_to'] }}</td>
            @else
            <td style="text-align: center;">-</td>
            <td style="text-align: center;">-</td>
            <td style="text-align: center;">-</td>
            @endif
        </tr>
        @endforeach
        <tr>
            <td ></td>
            <td ><b>{{ $beg_total }}</b></td>
            <td ></td>
            <td ></td>
            <td ><b>{{ $rec_total }}</b></td>
            <td ></td>
            <td ></td>
            <td ><b>{{ $iss_total }}</b></td>
            <td ></td>
            <td ></td>
            <td ><b>{{ $end_total }}</b></td>
            <td ></td>
            <td ></td>
        </tr>
    </table>

    <!-- SUMMARY OF COLLECTIONS AND REMITTANCES/DEPOSITS -->
   <table class="table table-no-border">
   <tr>
    <td>
        <span class="hidden">
        {{ $summary_total = 0 }}
        {{ $total_with_ada = 0 }}
        {{ $has_ada = 0 }}
        {{ $ada = 0 }}
        </span>
        <table class="table table-condensed table-bordered">
            <tr>
                <th  colspan="2">SUMMARY OF COLLECTIONS AND REMITTANCES/DEPOSITS</th>
            </tr>
            <tr>
                <td >Beginning Balance {{ $report_start }}</td>
                <td >

                </td>
            </tr>
            <tr>
                <td >Add: Collections {{ $date_range }}</td>
                <td >

                </td>
            </tr>
            <?php $count_rows = 0; ?>
            @foreach ($trantypes as $i => $type)
                <tr>
                    <td class="border_all tdindent">{{ $type['name'] }}</td>
                    <td class="text-right" >
                      <?php 
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
                      ?>
                        {{ number_format($type['total'], 2) }}
                    </td>
                </tr>
                <?php $count_rows++; ?>
            @endforeach
                <tr>
                    <td ><b>Total</b></td>
                    <td  class="text-right" ><b>{{ number_format($total_with_ada, 2) }}</b></td>
                </tr>
                <?php $count_rows++; ?>
            @if ($has_ada)
                <tr>
                    <th >Less ADA</th>
                    <th  class="text-right">{{ number_format($ada, 2) }}</th>
                </tr>
                <?php $count_rows++; ?>
            @endif
            <tr>
                <td ><b>Remittance/Deposit to Cashier/Treasurer</b></td>
                <td class="text-right" ><b>{{ number_format($summary_total, 2) }}</b></td>
            </tr>
            <tr>
                <td ><b>Balance</b></td>
                <td ><b></b></td>
            </tr>
            <?php $count_rows += 2; ?>
        </table>
   </td>
   <td>
        <span class="hidden">
            {{ $bank_total = 0 }}
        </span>
        <table class="table table-condensed table-bordered" >
            <tr>
                <th >Drawee Bank</th>
                <th >Check No.</th>
                <th >Payee</th>
                <th >Amount</th>
            </tr>
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
            <?php $count_dis_rows = 0; ?>
            @foreach($compiled_rec as $key => $b)
            <tr>
                <td >{{ $b['bank'] }}</td>
                <td >{{ $key }}</td>
                <td >Provincial Government of Benguet</td>
                <td class="text-right">
                   <?php $bank_total += $b['amt']; ?>
                    {{ number_format($b['amt'] , 2) }}
                </td>
            </tr>
            <?php $count_dis_rows++; ?>
            @endforeach
            @if($count_dis_rows < $count_rows) 
                <?php $diff = $count_rows - $count_dis_rows; ?>
                    @for($i=0; $i <= $diff; $i++)
                        <tr>
                            <td class="text-hidden">s</td>
                            <td class="text-hidden">s</td>
                            <td class="text-hidden">s</td>
                            <td class="text-hidden">s</td>
                        </tr>
                    @endfor
            @endif
            <tr>
                <th colspan="3">Total</th>
                <th class="text-right">{{ number_Format($bank_total, 2) }}</th>
            </tr>
        </table>
    </td>
    </tr>
    </table>

    <!-- CERTIFICATION/VERIFICATION AND ACKNOWLEDGEMENT -->

    <table  class="table table-borderedx">
        <tr>
        <td class="table-border-right">
        <table class="table table-no-border">
            <tr>
                <th  class="border-botts" >CERTIFICATION</th>
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
                            <th class="border-botts">{{ $report_date }}</th>
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

                            <b><u>{{ $total_in_words }} @if(strlen($total_in_words) > 96)  <br> @endif </u> (PHP {{ number_format($summary_total, 2) }})</b>.
                            </td>
                        </tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr >
                            <th class="border-botts" style="width:50%">{{ $officer_name->value }}</th>
                            <th></th>
                            <!-- <th class="border-botts"> date('F d, Y') </th> -->
                            <th class="border-botts"> {{ $report_date }} </th>
                        </tr>
                        <tr>
                            <th>{{ $officer_position->value }}</th>
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
