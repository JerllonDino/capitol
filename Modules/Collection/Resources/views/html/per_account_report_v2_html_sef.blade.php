<!DOCTYPE html>
<html>
<head>
     <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>PER ACCOUNT REPORT  SEF</title>
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
                        <th class="border_all" rowspan="3">Date</th>
                        <th class="border_all" rowspan="3">Tax Payor</th>
                        <th class="border_all" rowspan="3">Period Covered</th>
                        <th class="border_all" rowspan="3">OR No.</th>
                        <th class="border_all" rowspan="3">TD/ARP</th>
                        <th class="border_all" rowspan="3">Brgy.</th>
                        <th class="border_all" rowspan="3">Class</th>
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
                    </tr>
                  
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
      ?>

        @foreach ($receiptss as $receipt)
        
            
            
            <?php 
             $rcpt_done = 0;
           ?>

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
                          <!--   <td class="border_all val">{{ number_format($f56_detail->basic_current, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_discount, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_previous, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_penalty_current, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_penalty_previous, 2) }}</td>
                            <td class="border_all val">{{ number_format($basic_gross, 2) }}</td>
                            <td class="border_all val">{{ number_format($basic_net, 2) }}</td> -->
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
       <!--                      <td class="border_all val">{{ number_format($f56_detail->basic_current, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_discount, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_previous, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_penalty_current, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_penalty_previous, 2) }}</td>
                            <td class="border_all val">{{ number_format($basic_gross, 2) }}</td>
                            <td class="border_all val">{{ number_format($basic_net, 2) }}</td> -->
                            <td class="border_all val">{{ number_format($f56_detail->basic_current, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_discount, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_previous, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_penalty_current, 2) }}</td>
                            <td class="border_all val">{{ number_format($f56_detail->basic_penalty_previous, 2) }}</td>
                            <td class="border_all val">{{ number_format($basic_gross, 2) }}</td>
                            <td class="border_all val">{{ number_format($basic_net, 2) }}</td>
                            <td class="border_all val">{{ number_format(($basic_gross + $basic_gross), 2) }}</td>
                            <td class="border_all val">{{ number_format(($basic_net + $basic_net), 2) }}</td>
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
           <!--  <th class="border_all val">{{ number_format($total_basic_current, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_discount, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_previous, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_penalty_current, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_penalty_previous, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_gross, 2) }}</th>
            <th class="border_all val">{{ number_format($total_basic_net, 2) }}</th> -->
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
                    <th class="text-center">AMOUNT</th>

                </tr>
        </thead>
        <tbody>
        <tr class="page-break" ><td colspan="6" class="title"  >{{ $title->name }}</td></tr>
        
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
                                <td class="text-right" >{{ number_format( $valuei->value ,2) }}</td>
                             </tr> 
                        @endforeach 
                             <?php $countcd++; ?>

          @endforeach

        </tbody>
        <tfoot>

            <tr class="page-break" >
                <td class="text-center total" colspan="6" >TOTAL</td>
            </tr>
            @foreach($acct_totalcd as $key => $value)
             <tr class="page-break" >
                <td class="text-left total" colspan="3" > {{ $key }} </td>
                <td colspan="3" class="text-right total">{{ number_format( $value  ,2) }}</td>
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

      