<!DOCTYPE html>
<html>
<head>
     <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>PER ACCOUNT REPORT  </title>
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
            page-break-inside:avoid;
          page-break-after:always;
        }


        /* class works for table */
        table.page-break{
          page-break-after:always;
        }

         table tfoot tr.page-break-before{
            page-break-inside:avoid;
                page-break-after: always;
         }

        @media print {
         .page-break  { display: block;  page-break-before: always; }
        }

         .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th{
            border : 1px solid #000 !important;
            padding: 1px;
        }
         .center {
                width: 450px;
                text-align: center;
                margin: 10px auto;
        }

           .image_logo{
                width: 100px;
            }






        .header,
        .footer {
            width: 100%;
            text-align: center;

        }
        .header {
            top: 0px;
            min-height: 250px;
        }
        .footer {
            bottom: 15px;
            position: fixed;
            color:  #898786 ;
            font-weight: bold;
        }
        .pagenum:before {
            content: counter(page);
        }

        .title{
            text-indent: 5px;
            font-weight: bold;
        }

        .subs{
            text-indent: 40px;
            font-weight: bold;
        }

        .total{
            font-weight: bold;
        }






    </style>
</head>
<body>

    @php
      $stitle = '';
        if($sharing == 'value'){

        }elseif($sharing == 'share_barangay'){
             $stitle = 'Barangay Share';
        }elseif($sharing == 'share_municipal'){
             $stitle = 'Municipal Share';
        }elseif($sharing == 'share_provincial'){
             $stitle = 'Provincial Share';
        }
    @endphp

<div class="header">
        <table class="center ">
    <tr>
        <td>
            <img src="{{asset('asset/images/benguet_capitol.png')}}" class="image_logo" />
        </td>
        <td>
        REPORT OF PER ACCOUNT<br />
        <strong>PROVINCIAL GOVERNMENT OF BENGUET</strong><br />
        <strong>OFFICE OF THE PROVINCIAL TREASURER</strong>
        </td>
        </tr>
    </table>

</div>
<div class="footer">
    Page <span class="pagenum"></span>
</div>



 <div class="col-sm-12" style="top: 15px; bottom: 15px;">
 <h4><strong>DATE :  {{$data['start_date']->format('D F d, Y')}} {{ ($data['start_date']!=$data['end_date'] ) ? '- '. $data['end_date']->format('D F d, Y'):'' }}</strong></h4>

       <!-- per accounts -->

<table class="table table-condensed table-hover table-bordered page-break">
        <thead>
                <tr>
                    <!-- <th class="text-center">NO</th> -->
                    <th class="text-center">OR</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">PAYOR</th>
                    <th class="text-center">RECEIPT REMARKS</th>
                    <th class="text-center">OTHER REMARKS</th>
                    <th class="text-center">NATURE</th>
                    <th class="text-center">AMOUNT</th>

                </tr>
        </thead>
        <tbody>
        <tr class="page-break" ><td colspan="7" class="title"  >{{ $title->name }} {{$stitle}}</td></tr>
        <?php $count = 1; $acct_total = [];   ?>

                    @foreach($receiptss as $key => $receipt)
                    <?php  $datex =  Carbon\Carbon::parse($receipt->date_of_entry);
                            $rcpt_done = 0;

                     ?>

                        @foreach($receipt->items as $keyi => $valuei )

                            <?php

                                if( isset($valuei->acct_title) ){
                                    $acct = $valuei->acct_title->name;
                                }else{
                                    $acct = $valuei->acct_subtitle->name;
                                }
                                if(!isset($acct_total[$acct])){
                                        $acct_total[$acct] = 0;
                                }
                                $acct_total[$acct] += $valuei->{$sharing};
                            ?>
                             <tr class="page-break" >
                                    @if($rcpt_done == 0)
                                        <!-- <td class="text-center"  >{{ $count }}</td> -->

                                    @else
                                        <!-- <td colspan="1"></td> -->
                                    @endif
                                    <td class="text-center"  >{{ $receipt->serial_no }}</td>
                                    <td class="text-center"  >{{ $datex->toFormattedDateString() }}</td>
                                        <td   >{{ $receipt->customer->name }}</td>
                                    @if($rcpt_done == 0)
                                    <?php  $rcpt_done = 1; ?>
                                        <td   >{!! $receipt->remarks !!}</td>
                                        <td   >{!! $receipt->bank_remark !!}</td>
                                    @else
                                        <td colspan="1"></td>
                                        <td colspan="1"></td>
                                    @endif

                                <td class="text-center" >{!! $valuei->nature !!}</td>
                                <td class="text-right" >{{ number_format( $valuei->{$sharing} ,2) }}</td>
                             </tr>
                        @endforeach
                             <?php $count++; ?>
                    @endforeach
        </tbody>
        <tfoot>
            <tr class="page-break" >
                <td class=" total" colspan="7" >Total Transactions: {{$count-1}}</td>
            </tr>
            @foreach($acct_total as $key => $value)
             <tr class="page-break" >
                <td class="text-center total" colspan="3" > {{ $key }} TOTAL</td>
                <td colspan="3"></td>
                <td class="text-right total" >{{ number_format( $value  ,2) }}</td>
            </tr>
            @endforeach
        </tfoot>


            </table>

@if(count($cash_divs) > 0 )

<hr />

<h3>CASH DIVISION</h3>

<table class="table table-condensed table-hover table-bordered page-break">
        <thead>
                <tr>
                    <th class="text-center">NO</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">PAYOR</th>
                    <th class="text-center">REF. NO</th>
                    <th class="text-center">ACCT</th>
                    <th class="text-center">NATURE</th>
                    <th class="text-center">AMOUNT</th>
                </tr>
        </thead>
        <tbody>
        <tr class="page-break" ><td colspan="7" class="title"  >{{ $title->name }}</td></tr>

          <?php $countcd = 1; $acct_totalcd = []; ?>
          @foreach($cash_divs as $ckey => $cashdiv)

            <?php  $datex =  Carbon\Carbon::parse($cashdiv->date_of_entry);
                            $rcpt_donecd = 0;
                     ?>

                        @foreach($cashdiv->items as $keyi => $valuei )
                            <?php

                                if( isset($valuei->acct_title) ){
                                    $acctcd = $valuei->acct_title->name;
                                }else{
                                    $acctcd = $valuei->acct_subtitle->name;
                                }
                                if(!isset($acct_totalcd[$acctcd])){
                                        $acct_totalcd[$acctcd] = 0;
                                }
                                $acct_totalcd[$acctcd] += $valuei->value;
                            ?>
                             <tr class="page-break" >
                                    @if($rcpt_donecd == 0)
                                        <td class="text-center"  >{{ $countcd }}</td>

                                    @else
                                        <td colspan="1"></td>
                                    @endif
                                    <td class="text-center"  >{{ $datex->format('Y-m-d') }}</td>
                                        <td   >{{ $cashdiv->customer->name }}</td>
                                    @if($rcpt_donecd == 0)
                                    <?php  $rcpt_donecd = 1; ?>
                                        <td   >{!! $cashdiv->refno !!}</td>
                                    @else
                                        <td colspan="1"></td>
                                    @endif

                                <td class="text-left" >{!! $acctcd !!}</td>
                                <td class="text-left">{{ $valuei->nature }}</td>
                                <td class="text-right" >{{ number_format( $valuei->value ,2) }}</td>
                             </tr>
                        @endforeach
                             <?php $countcd++; ?>

          @endforeach

        </tbody>
        <tfoot>

            <tr class="page-break" >
                <td class="text-center total" colspan="7" >TOTAL</td>
            </tr>
            @foreach($acct_totalcd as $key => $value)
             <tr class="page-break" >
                <td class="text-left total" colspan="3" > {{ $key }} </td>
                <td colspan="4" class="text-right total">{{ number_format( $value  ,2) }}</td>
            </tr>
            @endforeach

        </tfoot>


            </table>

@endif

</div>

 {{ Html::script('/jquery-2.2.4/jquery-2.2.4.min.js') }}

    <!-- Bootstrap JS -->
    {{ Html::script('/bootstrap-3.3.6/js/bootstrap.min.js') }}

    <!-- jQuery UI -->
    {{ Html::script('/jquery-ui-1.12.1/jquery-ui.min.js') }}

</body>

</html>

