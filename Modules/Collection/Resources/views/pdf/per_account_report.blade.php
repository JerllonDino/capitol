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
          page-break-after:always
        }


        /* class works for table */
        table.page-break{
          page-break-after:always
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
            border : 1px solid #000;
            padding: 1px;
        }


      td.total_group {
            border-top: 3px double #000 !important;
            border-bottom: 2px solid #000 !important;
            font-size: 14px;
            font-weight: bold;
        }
      td.total_categ{
            border-top: 3px double #1d60ef !important;
            border-bottom: 2px solid #1d60ef !important;
            background: #f5f5f5;
           font-size: 16px;
            font-weight: bold;

        }

        .header,
        .footer {
            width: 100%;
            text-align: center;
            position: fixed;

        }
        .header {
             position: fixed;
            top: 0px;
            min-height: 250px;
        }
        .footer {
            bottom:15px;
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



 <div class="col-sm-12" style="top: 135px;">
 <h4><strong>DATE :  {{$data['start_date']->format('D F d, Y')}} {{ ($data['start_date']!=$data['end_date'] ) ? '- '. $data['end_date']->format('D F d, Y'):'' }}</strong></h4>
        <table class="table table-condensed table-bordered page-break">
        <thead>
                <tr>
                    <th class="text-center">NO</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">OR</th>
                    <th class="text-center">PAYOR</th>
                    <th class="text-center">REMARKS</th>
                    <th class="text-center">OTHER REMARKS</th>
                    <th class="text-center">NATURE</th>
                    <th class="text-center">AMOUNT</th>
                </tr>
        </thead>
        <tbody>
            <?php $totalx = 0; $count = 1; ?>
            @if( count( $title->subs) == 0 )
                <tr ><td colspan="8" class="title"  >{{ $title->name }}</td></tr>
                    @foreach($receipts as $key => $receipt)
                    <?php  $datex =  Carbon\Carbon::parse($receipt->date_of_entry); ?>
                            <tr>
                                <td class="text-center" >{{ $count }}</td>
                                <td class="text-center" >{{ $datex->format('Y-m-d') }}</td>
                                <td class="text-center" >{{ $receipt->serial_no }}</td>
                                <td>{{ $receipt->customer->name }}</td>
                                <td>{!! $receipt->remarks !!}</td>
                                <td>{!! $receipt->bank_remark !!}</td>
                                <td>{{ $receipt->nature }}</td>
                                <td class="text-right" >{{ number_format( $receipt->value ,2) }}</td>
                                
                                
                            </tr>
                             <?php $totalx += $receipt->value; $count++; ?>
                    @endforeach
            @else
            <tr ><td colspan="8" class="title"  >{{ $title->name }}</td></tr>
                @foreach($receipts as $key => $receipt)
                    <?php  $datex =  Carbon\Carbon::parse($receipt->date_of_entry); ?>
                            <tr>
                                <td class="text-center" >{{ $count }}</td>
                                <td class="text-center" >{{ $datex->format('Y-m-d') }}</td>
                                <td class="text-center" >{{ $receipt->serial_no }}</td>
                                <td>{{ $receipt->customer->name }}</td>
                                <td>{!! $receipt->remarks !!}</td>
                                <td>{!! $receipt->bank_remark !!}</td>
                                <td>{{ $receipt->nature }}</td>
                                <td class="text-right" >{{ number_format( $receipt->value ,2) }}</td>
                                
                                
                            </tr>
                             <?php $totalx += $receipt->value; $count++; ?>
                    @endforeach

                    @foreach($title->subs as $keys => $sub)
                        <tr ><td colspan="8"  class="subs" >{{ $sub->name }}</td></tr>

                        <?php
                            $subreceipts = $sub->receipt()
                            ->where('col_receipt.report_date','>=' , $data['start_date']->format('Y-m-d') )
                            ->where('col_receipt.report_date','<=' , $data['end_date']->format('Y-m-d') )
                            ->where('col_receipt.is_printed', '=', '1')
                            ->where('col_receipt_items.col_acct_subtitle_id', '=', $sub->id)
                            ->get();
                        ?>

                    @foreach($subreceipts as $skey => $sreceipt)
                    <?php  $datex =  Carbon\Carbon::parse($sreceipt->date_of_entry); 
                           $customer = DB::table('col_customer')->where('id',$sreceipt->col_customer_id)->first();
                    ?>
                            <tr>
                                <td class="text-center" >{{ $count }}</td>
                                <td class="text-center" >{{ $datex->format('Y-m-d') }}</td>
                                <td class="text-center" >{{ $sreceipt->serial_no }}</td>
                                <td>{{$customer->name}}</td>
                                <td>{!! $sreceipt->remarks !!}</td>
                                <td>{!! $sreceipt->bank_remark !!}</td>
                                <td>{{ $sreceipt->nature }}</td>
                                <td class="text-right" >{{ number_format( $sreceipt->value ,2) }}</td>
                                
                                
                            </tr>
                             <?php $totalx += $sreceipt->value; $count++; ?>
                    @endforeach

                    @endforeach

            @endif
        
        </tbody>

        <tfoot>
            <tr>
                <td class="text-center total" colspan="3" >TOTAL</td>
                <td colspan="4"></td>
                <td class="text-right total" >{{ number_format( $totalx,2 ) }}</td>
            </tr>
        </tfoot>


            </table>

            </div>

</body>

</html>
