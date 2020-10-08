  @extends('collection::pdf/per_account_report_template')

@section('per_accounts')
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
@endsection
           