<!DOCTYPE html>
<html>
<head>
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
          page-break-after:always;
        }


        /* class works for table */
        table.page-break{
          page-break-after:always;
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
        }

           .image_logo{
                width: 100px;
            }

            .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th{
            border : 1px solid #000 !important;
            padding: 1px;
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
            bottom:15px;
            position: fixed;
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

<table class="table table-condensed table-bordered page-break">
        <thead>
                <tr>
                    <th class="text-center">NO</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">OR</th>
                    <th class="text-center">PAYOR</th>
                    <th class="text-center">RECEIPT REMARKS</th>
                    <th class="text-center">OTHER REMARKS</th>
                    <th class="text-center">NATURE</th>
                    <th class="text-center">AMOUNT</th>

                </tr>
        </thead>
        <tbody>
            
        
        </tbody>

        <?php $totalx = 0; $count = 1; $title_total = []; ?>
                <tr class="page-break" ><td colspan="8" class="title"  >{{ $title->name }}</td></tr>
                    @foreach($receiptss as $key => $receipt)
                    <?php  $datex =  Carbon\Carbon::parse($receipt->date_of_entry);
                            $rcpt_done = 0;
                     ?>
                    
                        @foreach($receipt->items as $keyi => $valuei )
                            <?php 
                                if(!isset($title_total[$valuei->acct_title->name])){
                                        $title_total[$valuei->acct_title->name] = 0;
                                }
                                $title_total[$valuei->acct_title->name] += $valuei->value;
                            ?>
                             <tr class="page-break" >
                                @if ($rcpt_done == 0)
                                    <?php   $rcpt_done = 1; ?>
                                        <td class="text-center" rowspan="{{ count($receipt->items) }}" >{{ $count }}</td>
                                        <td class="text-center" rowspan="{{ count($receipt->items) }}" >{{ $datex->format('Y-m-d') }}</td>
                                        <td class="text-center" rowspan="{{ count($receipt->items) }}" >{{ $receipt->serial_no }}</td>
                                        <td rowspan="{{ count($receipt->items) }}"  >{{ $receipt->customer->name }}</td>
                                        <td rowspan="{{ count($receipt->items) }}"  >{!! $receipt->remarks !!}</td>
                                        <td rowspan="{{ count($receipt->items) }}" >{!! $receipt->bank_remark !!}</td>
                                @endif
                                <td>{{ $valuei->nature }}</td>
                                <td class="text-right" >{{ number_format( $valuei->value ,2) }}</td>
                             </tr> 
                        @endforeach 
                          

                                
                            
                             <?php $totalx += $receipt->value; $count++; ?>
                    @endforeach
        <tfoot>
            <tr class="page-break" >
                <td class="text-center total" colspan="8" >TOTAL</td>
            </tr>
            @foreach($title_total as $key => $value)
             <tr class="page-break" >
                <td class="text-center total" colspan="3" > {{ $key }} TOTAL</td>
                <td colspan="4"></td>
                <td class="text-right total" >{{ number_format( $value  ,2) }}</td>
            </tr>
            @endforeach
        </tfoot>


            </table>

</div>

</body>

</html>


      