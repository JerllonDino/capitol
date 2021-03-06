<!DOCTYPE html>
<html>
<head>
    <title>ACCOUNTS REPORT Barangay Income</title>
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
        REPORT OF ACCOUNTS (BARANGAY SHARE)<br />
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
 <h4><strong>DATE :  {{$data['start_date']->format('D F d, Y')}} {{ ($data['start_date']!=$data['end_date'] ) ? '- '. $data['end_date']->format('D F d, Y'):'' }} Barangay Income</strong></h4>
        <table class="table table-condensed table-bordered page-break">
        <thead>
            <tr class="page-break">
                <th>Account Name</th>
                    <?php $d = 0; $sdate =$data['start_date']; $asdate[] = []; ?>
                    @for( $x = 0 ; $x<=$data['diff']; $x++)
                     <?php
                     $xasdate = $sdate->addDays($d);
                     $asdate[$x]['y-m-d'] =$xasdate->format('Y-m-d');
                     $asdate[$x]['j'] =$xasdate->format('m/d');
                     $asdate[$x]['D'] =$xasdate->format('D');
                     ?>
                        @if( $asdate[$x]['D'] != 'Sun'  && $asdate[$x]['D'] != 'Sat' )
                            <th class="text-center">{{ $asdate[$x]['j'] }}</th>
                            <?php $d = 1; ?>
                        @endif
                    @endfor
                    <th>Total</th>
            </tr>
        </thead>
        <tbody>

        <?php
            $total_title = [];
            $tr_counter = 0;
            $pm_categ = [1=>[1=>[2,4,54]]];

            $data['diffx'] = $data['diff'] == 0 ? 3 :  $data['diff']-1;
        ?>
        @foreach ($categories as $category)
        @if(isset($pm_categ[$category->id]))
         <?php $category_total[$category->id] = 0; ?>
            <tr class="page-break">
                <td colspan="{{  $data['diffx'] }}" ><div class="col-sm-12"><b>{{ $category->name }}</b></div></td>
            </tr>
            @foreach ($category->group as $group)
            @if(isset($pm_categ[$category->id][$group->id]))
             <?php $group_total[$group->id] = 0; ?>
                <tr class="page-break">
                    <td colspan="{{ $data['diffx']  }}" ><div class="col-sm-12">{{ $group->name }}</div></td>
                </tr>

                @foreach ($group->title as $title)
                <?php $title_total_sum[$group->id][$title->id] = 0; ?>
                    @if(in_array($title->id,$pm_categ[$category->id][$group->id]))
                    <?php $tr_counter++; ?>
                        <tr class="page-break">
                            <td><div class="col-sm-11 col-sm-offset-1">{{ $title->name }}</div></td>
                                @for( $x = 0 ; $x<=$data['diff']; $x++)
                                    @if( $asdate[$x]['D'] != 'Sun'  && $asdate[$x]['D'] != 'Sat' )
                                    <?php
                                        $total_title[$category->id][$group->id][$title->id] = [];
                                        $title_total = $title->receipt()->where('col_receipt.report_date','LIKE',$asdate[$x]['y-m-d'].'%' )->where('col_receipt.is_cancelled','=','0')->where('col_receipt.is_printed','=','1')->select(["col_receipt_items.share_barangay","col_receipt_items.value"])->get();
                                        $title_total= count($title_total)>0 ? $title_total: [] ;
                                        $titlereceipt_div_value = 0;
                                        foreach($title_total as $receipt_value){
                                            if($receipt_value->share_barangay >0){
                                                $titlereceipt_div_value = $titlereceipt_div_value + $receipt_value->share_barangay;
                                            }else{
                                                $titlereceipt_div_value = $titlereceipt_div_value + $receipt_value->value;
                                            }
                                        }

                                        $titlecash_divs = $title->cash_div()->where('col_cash_division.date_of_entry','=',$asdate[$x]['y-m-d'])->select(["col_cash_division_items.value"])->get();
                                        $titlecash_divs= count($titlecash_divs)>0 ? $titlecash_divs: [] ;
                                          $titlecash_div_value = 0;
                                        foreach($titlecash_divs as $titlecash_div){

                                                 $titlecash_div_value = $titlecash_div_value  + $titlecash_div->value;
                                        }

                                        $total_title[$category->id][$group->id][$title->id] =  $titlecash_div_value + $titlereceipt_div_value;

                                        $title_total_sum[$group->id][$title->id] =  $title_total_sum[$group->id][$title->id] + ($total_title[$category->id][$group->id][$title->id]);
                                      ?>
                                        <td class="text-right">{{  number_format(($total_title[$category->id][$group->id][$title->id]),2) }} </td>
                                    @endif
                                @endfor
                                <?php $group_total[$group->id] = $group_total[$group->id] + ($title_total_sum[$group->id][$title->id] ); ?>
                                <td  class="text-right"> {{  number_format($title_total_sum[$group->id][$title->id] ,2) }}</td>
                        </tr>

                    @foreach ($title->subs as $subs)
                     <?php $subtitle_total_sum[$group->id][$title->id][$subs->id] = 0; ?>

                         <?php $tr_counter++; ?>
                            <tr class="page-break">
                                <td><div class="col-sm-10 col-sm-offset-2">{{ $subs->name }} {{ $subs->id }}</div></td>
                                @for( $x = 0 ; $x<=$data['diff']; $x++)
                                    @if( $asdate[$x]['D'] != 'Sun'  && $asdate[$x]['D'] != 'Sat' )
                                    <?php
                                        $total_subtitle[$category->id][$group->id][$title->id][$subs->id] = [];
                                        $subtitle_total = $subs->receipt()->where('col_receipt.report_date','LIKE',$asdate[$x]['y-m-d'].'%' )->where('col_receipt.is_cancelled','=','0')->where('col_receipt.is_printed','=','1')->select(["col_receipt_items.share_barangay","col_receipt_items.value"])->get();
                                        $subtitle_total= count($subtitle_total)>0 ? $subtitle_total: [] ;
                                        $subsreceipt_div_value = 0;
                                        foreach($subtitle_total as $receipt_value){
                                            
                                            if($receipt_value->share_barangay >0){
                                                $subsreceipt_div_value =$subsreceipt_div_value  + $receipt_value->share_barangay;
                                            }else{
                                                $subsreceipt_div_value =$subsreceipt_div_value  + $receipt_value->value;
                                            }
                                        }

                                        $subscash_div = $subs->cash_div()->where('col_cash_division.date_of_entry','=',$asdate[$x]['y-m-d'])->select(["col_cash_division_items.value"])->get();
                                        $subscash_div= count($subscash_div)>0 ? $subscash_div: [] ;
                                          $subscash_div_value = 0;
                                        foreach($subscash_div as $cashdiv_value){
                                                 $subscash_div_value += $cashdiv_value->value;
                                        }

                                           $total_subtitle[$category->id][$group->id][$title->id][$subs->id] =  $subsreceipt_div_value + $subscash_div_value ;

                                        $subtitle_total_sum[$group->id][$title->id][$subs->id] =  $subtitle_total_sum[$group->id][$title->id][$subs->id] + ($total_subtitle[$category->id][$group->id][$title->id][$subs->id]);
                                      ?>
                                        <td  class="text-right">{{  number_format(($total_subtitle[$category->id][$group->id][$title->id][$subs->id]),2) }} </td>
                                    @endif
                                @endfor
                                <?php $group_total[$group->id] = $group_total[$group->id] + ($subtitle_total_sum[$group->id][$title->id][$subs->id] ); ?>
                                <td  class="text-right"> {{  number_format($subtitle_total_sum[$group->id][$title->id][$subs->id] ,2) }}</td>
                            </tr>

                    @endforeach <!-- end of sub title -->
                    @endif
                @endforeach <!-- end of title -->
              <?php   $category_total[$category->id] = $category_total[$category->id] + $group_total[$group->id] ; ?>
                <tr class="page-break">
                <td class="total_group"><div class="col-sm-12"><strong>{{ $group->name }} TOTAL</strong></div></td>
                 <td  colspan="{{$data['diffx']-1}}" class="text-right total_group" >{{ number_format($group_total[$group->id],2) }}</td>
            </tr>
            @endif
            @endforeach <!-- end of groups -->
                  <tr class="page-break">
                <td class="total_categ"><strong>{{ $category->name }} TOTAL</strong></td>
                 <td colspan="{{$data['diffx']-1}}" class="text-right total_categ" >{{ number_format($category_total[$category->id],2) }}</td>
            </tr>

           
        @endif
        @endforeach <!-- end of categories -->
        </tbody>




            </table>

            </div>

</body>

</html>
