<!DOCTYPE html>
<html>
<head>
    <title>ACCOUNTS REPORT</title>
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
        REPORT OF ACCOUNTS (PROVINCIAL SHARE)<br />
        <strong>PROVINCIAL GOVERNMENT OF BENGUET</strong><br />
        <strong>OFFICE OF THE PROVINCIAL TREASURER</strong>
        </td>
        </tr>
    </table>

</div>
<div class="footer">
    Page <span class="pagenum"></span>
</div>



 <div class="col-sm-12">
 <h4><strong>DATE :  {{$data['start_date']->format('D F d, Y')}} {{ ($data['start_date']!=$data['end_date'] ) ? '- '. $data['end_date']->format('D F d, Y'):'' }} Municipal Income</strong></h4>
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
                    <th class="text-center" >Total</th>
            </tr>
        </thead>
        <tbody>

<?php
$total_title = [];
$title_total_sum = [];
$tr_counter = 0;
$insurance_premium = 42;
$miscllanaus = 39;
$misc_add = [];
$pm_categ = [1=>[1=>[2,4,6,54]],4=>[10=>[49,55]]];
foreach ($categories as $category){
    if(isset($pm_categ[$category->id])){
        $total_title[$category->id] = [];
    foreach ($category->group as $group){
        if(isset($pm_categ[$category->id][$group->id])){
            $total_title[$category->id][$group->id] = [];
        foreach ($group->title as $title){
            if(in_array($title->id,$pm_categ[$category->id][$group->id])){
                    $title_total_sum[$category->id][$group->id][$title->id] = 0;

                           
                            $title_total = $title->receipt()->where('col_receipt.report_date','>=',$data['start_datex']->format('Y-m-d') )->where('col_receipt.report_date','<=',$data['end_datex']->format('Y-m-d') )->where('col_receipt.is_cancelled','=','0')->where('col_receipt.is_printed','=','1')->select(["col_receipt_items.share_municipal","col_receipt_items.value"])->get();
                            $titlereceipt_div_value = 0;
                            foreach($title_total as $receipt_value){
                                if($receipt_value->share_municipal > 0){
                                    $titlereceipt_div_value = $titlereceipt_div_value + $receipt_value->share_municipal;
                                }else{
                                        $titlereceipt_div_value +=  $receipt_value->share_municipal;
                                }
                                
                            }
                            $title_total_sum[$category->id][$group->id][$title->id] += $titlereceipt_div_value;

                            $titlecash_divs = $title->cash_div()->where('col_cash_division.date_of_entry','>=',$data['start_datex']->format('Y-m-d') )->where('col_cash_division.date_of_entry','<=',$data['end_datex']->format('Y-m-d') )->select(["col_cash_division_items.value"])->get();
                            $titlecash_divs= count($titlecash_divs)>0 ? $titlecash_divs: [] ;
                            $titlecash_div_value = 0;
                            foreach($titlecash_divs as $titlecash_div){
                                     $titlecash_div_value = $titlecash_div_value  + $titlecash_div->value;
                            }

                            

                            foreach ($title->subs as $subs){
                                $subtitle_total_sum[$group->id][$title->id][$subs->id] = 0;
                                $tr_counter++;
                                
                            }
                    }//end if in_array title
                }// end title
            } //end if isset group
        }// end group
    } //end if isset category
} // end category
$data['diffx'] = $data['diff'] == 0 ? 3 :  $data['diff']-1;

?>


        </tbody>
    </table>
</div>

</body>
</html>
{{dd()}}