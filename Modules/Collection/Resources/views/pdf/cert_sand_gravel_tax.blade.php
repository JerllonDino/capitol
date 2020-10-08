<!DOCTYPE html>
<html>
<head>
    <title>Certificate - Provincial Permit</title>
    <style>
        body {
            font-family: arial, "sans-serif";
            margin: 0px;
            font-size: 16px;
        }
        #items {
            width: 100%;
            border: 1px solid black;
            border-collapse: collapse;
            margin-right: auto;
            margin-left: auto;
        }
        .other_item{
            border: 1px solid black;
        }
        }
        .underline {
            border-bottom: 1px solid #000000;
        }
        .header-container {
            width: 80%;
            text-align: center;
        }
        .header {
            width: 95%;
            display: block;
            font-weight: strong;
        }
        #logo {
            height: 80px;
            float: left;
            margin-left: 100px;
        }
        #header-dt {
            float: right;
            text-align: center;
            margin-top: 30px;
        }
        #cert {
            /*margin-top: 60px;*/
            margin-top: 20px;
            /*margin-bottom: 30px;*/
            margin-bottom: 10px;
            font-size: 18px;
            text-align: center;
            width: 100%;
        }
        #officers {
            width: 100%;
            padding-left: 35%;
        }
        .underline {
            border-bottom: 1px solid #000000;
        }
        .blk {
            padding-top: 10px;
        }
        .center {
            text-align: center;
        }
        .val {
            text-align: right;
        }
        .hidden {
            display: none;
        }
        .title {
            padding-top: 20px;
        }
        .bottom2 {
            position: fixed;
            top: 73%;
        }
        .bottom {
            position: fixed;
            top: 77%;
        }
        .indent {
            padding-left: 30px;
        }
        .double-border{
            border-top: 1px solid #000000;
            border-bottom: 3px double #000000;
        }
        #detail-head th {
            font-weight: none;
            font-size: 13px;
        }

        .small-font{
            font-size: 9px;
            text-transform: lowercase;
        }

    </style>
</head>
<body>
    <div class="header-container">
        {{ Html::image('/asset/images/benguet_capitol.png', "Logo", array('id' => 'logo')) }}
        <span class="header">Republic of the Philippines</span>
        <span class="header">PROVINCE OF BENGUET</span>
        <span class="header">La Trinidad</span>
        <span class="header">OFFICE OF THE PROVINCIAL TREASURER</span>
    </div>
    <!-- <table id="header-dt">
        <tr>
            <td></td>
            <td class="underline" width="125">{{-- date('F d, Y', strtotime($cert->date_of_entry)) --}}</td>
        </tr>
        <tr>
            <td></td>
            <td>Date</td>
        </tr>
    </table> -->
    <div id="cert">
        <b>C E R T I F I C A T I O N</b>
    </div>
    <div class="blk">
        <span class="indent">THIS IS TO CERTIFY that as per records of this Office, </span>
        <u><b>{{ $cert->recipient }}</b></u>
        <span>has paid the Provincial Permit Fee, Sand and Gravel taxes and other fees under the following receipts, to wit:</span>
    </div><br>
    <!-- <div class="blk">
        <u>{!! $cert->detail !!}</u>
    </div> -->
    <span class="hidden">
    {{ $cert_sandgravelprocessed = 0 }}
    {{ $cert_abc = 0 }}
    {{ $cert_sandgravel = 0 }}
    {{ $cert_boulders = 0 }}
    <?php $cert_fees = array(); ?>
    </span>
    <table id="items">
        <span class="hidden">
        {{ $total = 0 }}
        </span>
        <thead>
            <tr>
                <td class="other_item center"><b>Date of Payment</b></td>
                <td class="other_item center"><b>O.R. Number</b></td>
                <td class="other_item center"><b>Particulars</b></td>
                <td class="other_item center"><b>Amount Paid</b></td>
            </tr>
        </thead>
        <tbody>
        <?php $j = 0; ?> 
         @foreach($transactions as $transaction)
            @if(!is_null($OtherFeesCharges))
                @if(isset($OtherFeesCharges[$j]))
                    @if($OtherFeesCharges[$j]->fees_date <= $transaction->report_date && $OtherFeesCharges[$j]->fees_date <= $transactions[0]->report_date)
                        <tr>
                            <td class="other_item">{{ \Carbon\Carbon::parse($OtherFeesCharges[$j]->fees_date)->toFormattedDateString() }}</td>
                            <td class="other_item center">{{ $OtherFeesCharges[$j]->or_number }}</td>
                            <td class="other_item">{{ $OtherFeesCharges[$j]->fees_charges }}</td>
                            <td class="other_item" style="text-align: right">{{ number_format($OtherFeesCharges[$j]->ammount, 2) }}</td>
                        </tr>
                    @else
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endif
                    <?php $j++; ?>
                @endif
            @endif
            @foreach($transaction->items as $key => $t)
                <!-- <tr> -->
                    <?php $i = 0; ?>
                    @if($key == 0)   
                        @if(!in_array($t->col_receipt_id, $not_crt))
                            @if(strcasecmp($t->nature, 'Certified Photocopy') != 0) 
                                @if(strcasecmp($t->nature, " Sand and Gravel Tax") == 0 && $t->col_acct_title_id == 4)               
                                    @if($transaction->serial_no == $sg_taxes[0])
                                        <tr>
                                            <td class="other_item">{{\Carbon\Carbon::parse($transaction->date_of_entry)->toFormattedDateString()}}</td>
                                            <td class="other_item center">{{$transaction->serial_no}}</td>
                                            <td class="other_item">{{$t->nature}}</td>
                                            <td class="other_item" style="text-align: right">{{number_format($t->value,2)}}</td>
                                            <?php $total += $t->value; ?>
                                        </tr>
                                    @else
                                        <?php $ff_year = \Carbon\Carbon::parse($transaction->date_of_entry)->addYear(); ?>
                                        <!--
                                            receipts issued on the same day w/ certificate receipt exempted....
                                        -->
                                        @if(strcasecmp(\Carbon\Carbon::now()->format('Y'), $ff_year->format('Y')) || (\Carbon\Carbon::parse($transaction->date_of_entry)->format('Y-m-d') == \Carbon\Carbon::parse($cert_or->date_of_entry)->format('Y-m-d')) || (isset($include_from) && isset($include_to)))
                                            @if(!isset($include_from) && !isset($include_to))
                                                @if(\Carbon\Carbon::parse($transaction->date_of_entry)->format('Y-m-d') >= \Carbon\Carbon::parse($include_from)->format('Y-m-d') && \Carbon\Carbon::parse($transaction->date_of_entry)->format('Y-m-d') <= \Carbon\Carbon::parse($include_to)->format('Y-m-d'))
                                                    <tr>
                                                        <td class="other_item">{{\Carbon\Carbon::parse($transaction->date_of_entry)->toFormattedDateString()}}</td>
                                                        <td class="other_item center">{{$transaction->serial_no}}</td>
                                                        <td class="other_item">{{$t->nature}}</td>
                                                        <td class="other_item" style="text-align: right">{{number_format($t->value,2)}}</td>
                                                        <?php $total += $t->value; ?>
                                                    </tr>
                                                @endif
                                            @else
                                                <tr>
                                                    <td class="other_item">{{\Carbon\Carbon::parse($transaction->date_of_entry)->toFormattedDateString()}}</td>
                                                    <td class="other_item center">{{$transaction->serial_no}}</td>
                                                    <td class="other_item">{{$t->nature}}</td>
                                                    <td class="other_item" style="text-align: right">{{number_format($t->value,2)}}</td>
                                                    <?php $total += $t->value; ?>
                                                </tr>
                                            @endif
                                        @endif
                                    @endif
                                @else
                                    <tr>
                                        <td class="other_item">{{\Carbon\Carbon::parse($transaction->date_of_entry)->toFormattedDateString()}}</td>
                                        <td class="other_item center">{{$transaction->serial_no}}</td>
                                        <td class="other_item">{{$t->nature}}</td>
                                        <td class="other_item" style="text-align: right">{{number_format($t->value,2)}}</td>
                                        <?php $total += $t->value; ?>
                                    </tr>
                                @endif
                                <!-- <tr>
                                    <td class="other_item">{{--\Carbon\Carbon::parse($transaction->date_of_entry)->toFormattedDateString()--}}</td>
                                    <td class="other_item center">{{--$transaction->serial_no--}}</td>
                                    <td class="other_item">{{--$t->nature--}}</td>
                                    <td class="other_item" style="text-align: right">{{--number_format($t->value,2)--}}</td>
                                </tr> -->
                                <?php //$total += $t->value; ?>
                            @endif
                        @elseif(count($cert_receipt) > 0)
                            @if($cert_receipt[$i]->report_date <= $transaction->report_date && $cert_receipt[$i]->report_date >= $transactions[0]->report_date)
                                @if(strcasecmp($cert_receipt[$i]['items'][0]->nature, 'Certified Photocopy') != 0)
                                    <tr>
                                        <td class="other_item">{{\Carbon\Carbon::parse($cert_receipt[$i]->date_of_entry)->toFormattedDateString()}}</td>
                                        <td class="other_item center">{{$cert_receipt[$i]->serial_no}}</td>
                                        <td class="other_item">{{$cert_receipt[$i]['items'][0]->nature}}</td>
                                        <td class="other_item" style="text-align: right">{{number_format($cert_receipt[$i]['items'][0]->value,2)}}</td>
                                        <?php 
                                            $total += $t->value; 
                                            $i++;
                                        ?>
                                    </tr>
                                @endif
                            @endif
                        @endif
                    @else
                        <tr>
                            <td class="other_item"></td>
                            <td class="other_item"></td>
                            <td class="other_item">{{$t->nature}}</td>
                            <td class="other_item" style="text-align: right">{{number_format($t->value,2)}}</td>
                            <?php $total += $t->value; ?>
                        </tr>
                    @endif
                <!-- </tr> -->
            
            @endforeach
         @endforeach

        @if(!is_null($OtherFeesCharges))
            @if(isset($OtherFeesCharges[$j]))
                @if($OtherFeesCharges[$j]->fees_date >= $transaction->report_date && $OtherFeesCharges[$j]->fees_date >= $transactions[0]->report_date)
                    <tr>
                        <td class="other_item">{{ \Carbon\Carbon::parse($OtherFeesCharges[$j]->fees_date)->toFormattedDateString() }}</td>
                        <td class="other_item center">{{ $OtherFeesCharges[$j]->or_number }}</td>
                        <td class="other_item">{{ $OtherFeesCharges[$j]->fees_charges }}</td>
                        <td class="other_item" style="text-align: right">{{ number_format($OtherFeesCharges[$j]->ammount, 2) }}</td>
                    </tr>
                @else
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endif
                <?php $j++; ?>
            @endif
        @endif

         <tr>
             <td class="other_item"></td>
             <td class="other_item"></td>
             <td class="other_item"><b>Total Amount Paid</b><span style="float:right; font-weight: bold">Php</span></td>
             <td class="other_item" style="text-align: right"><b>{{number_format($total,2)}}</b></td>
         </tr>
        </tbody>
    </table>

    <div class="blk">
        <span class="indent">This certification is issued upon the request of</span>
        @if(!is_null($cert->sand_requestor))
            <u><b>{{ $cert->sand_requestor }}</b></u>
        @else
            <u><b>{{ $cert->recipient }}</b></u>
        @endif
        <span>to support         
        <!-- his/her  -->
        @if($cert->sand_requestor_sex == 2 && $cert->sand_requestor_sex != "")
            <!-- his/her/<u>their</u> -->
            their
        @elseif($cert->sand_requestor_sex == 1 && $cert->sand_requestor_sex != "")
            <!-- <u>his</u>/her/their -->
            his
        @elseif($cert->sand_requestor_sex == 0 && $cert->sand_requestor_sex != "")
            <!-- his/<u>her</u>/their -->
            her
        @else
            his/her/their
        @endif
        
        application for renewal of sand and gravel extraction permit at {{$cert->address ? $cert->address : '_____'}}.</span>
    </div>
    @php
        $date = \Carbon\Carbon::parse($cert->date_of_entry);
        $date_now = \Carbon\Carbon::now();
        $cert_receipt_date = \Carbon\Carbon::parse($cert_or->date_of_entry);
    @endphp
    <div class="blk">
        <span class="indent"> Issued this {{ $date->format('jS') }} day of {{ $date->format('F, Y') }} at La Trinidad, Benguet. </span>
    </div>

    <!-- <br><br><br> -->
    <table id="officers" style="padding-top: 33px;">
        <tr>
            <td></td>
            <td class="center"><b>{{ $cert->provincial_treasurer }}</b></td>
        </tr>
        <tr>
            <td></td>
            <td class="center">Provincial Treasurer</td>
        </tr>
    </table>
    <br>
    <!-- <table id="detail" style="margin: 20px 0 0; font-size: 13px;"> -->
    <div>
        <table id="detail" style="font-size: 13px;" style="margin-top: -20px; page-break-inside: avoid;">
            <tr>
                <td>Certification Fee:</td>
                <td>50.00</td>
            </tr>
            <tr>
                <td>O.R. No.:</td>
                <td>{{$receipts[0]->serial_no}}</td>
            </tr>
            <tr>
                <td>Dated:</td>
                <td>{{$cert_receipt_date->format('F j, Y')}}</td>
            </tr>
        </table>
    </div>
</body>
</html>

