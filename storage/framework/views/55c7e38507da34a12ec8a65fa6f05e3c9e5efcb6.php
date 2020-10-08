<!DOCTYPE html>
<html>
<head>
    <title>ACCOUNTS REPORT</title>
    <?php echo e(Html::style('/bootstrap-3.3.6/css/bootstrap.min.css')); ?>

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

        @media  print {
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

            .table>thead:first-child>tr:first-child>th,.table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th{
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
        .gtotal{
            font-weight: bold;
            font-size: 15px;
            padding-right: 10px;
        }

        .header,
        .footer {
            width: 100%;
            text-align: center;

        }
        .header {
            top: 0px;
            /*min-height: 250px;*/
        }
        .footer {
            bottom:15px;
            /*position: fixed;*/
        }
        .pagenum:before {
            /*content: counter(page);*/
        }





    </style>
</head>
<body>

<div class="header">
        <table class="center ">
    <tr>
        <td>
            <img src="<?php echo e(asset('asset/images/benguet_capitol.png')); ?>" class="image_logo" />
        </td>
        <td>
        REPORT OF ACCOUNTS<br />
        <strong>PROVINCIAL GOVERNMENT OF BENGUET</strong><br />
        <strong>OFFICE OF THE PROVINCIAL TREASURER</strong>
        </td>
        </tr>
    </table>

</div>
<!-- <div class="footer">
    Page <span class="pagenum"></span>
</div> -->



 <div class="col-sm-12">
 <h4><strong>DATE :  <?php echo e($data['start_date']->format('D F d, Y')); ?> <?php echo e(($data['start_date']!=$data['end_date'] ) ? '- '. $data['end_date']->format('D F d, Y'):''); ?></strong></h4>
        <table class="table table-condensed table-bordered page-break">
        <thead>
            <tr class="page-break">
                <th>Account Name</th>
                    <?php $d = 0; $sdate =$data['start_date']; $asdate[] = []; ?>
                    <?php for( $x = 0 ; $x<=$data['diff']; $x++): ?>
                     <?php
                     $xasdate = $sdate->addDays($d);
                     $asdate[$x]['y-m-d'] =$xasdate->format('Y-m-d');
                     $asdate[$x]['j'] =$xasdate->format('m/d');
                     $asdate[$x]['D'] =$xasdate->format('D');
                     ?>
                        <?php if( $asdate[$x]['D'] != 'Sun'  && $asdate[$x]['D'] != 'Sat' ): ?>
                            <th class="text-center"><?php echo e($asdate[$x]['j']); ?></th>
                            <?php $d = 1; ?>
                        <?php endif; ?>
                    <?php endfor; ?>
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
foreach ($categories as $category){
    foreach ($category->group as $group){
        foreach ($group->title as $title){
                 $title_total_sum[$category->id][$group->id][$title->id] = 0;
                 $tr_counter++;
            for( $x = 0 ; $x<=$data['diff']; $x++){
                        if( $asdate[$x]['D'] != 'Sun'  && $asdate[$x]['D'] != 'Sat' ){
                            $misc_add[$category->id][$group->id][$title->id][$asdate[$x]['y-m-d']] = 0;
                            $total_title[$category->id][$group->id][$title->id][$asdate[$x]['y-m-d']] = [];
                            if( $title->id == '55'){
                                  $title_id = 2 ;
                                  $title_total = $title->receipt($title_id)->leftJoin('col_f56_detail','col_f56_detail.col_receipt_id','=','col_receipt.id')->where('col_receipt.report_date','LIKE',$asdate[$x]['y-m-d'].'%' )->select(["col_f56_detail.basic_current","col_f56_detail.basic_previous","col_f56_detail.basic_penalty_current","col_f56_detail.basic_penalty_previous","col_f56_detail.basic_discount"])->get();
                                  $title_total= count($title_total)>0 ? $title_total : [] ;
                                    $titlereceipt_div_value = 0;

                                    foreach($title_total as $receipt_value){
                                            $titlereceipt_div_value +=  $receipt_value->basic_current + $receipt_value->basic_previous +  $receipt_value->basic_penalty_current + $receipt_value->basic_penalty_previous  - $receipt_value->basic_discount ;
                                    }
                                     $titlereceipt_div_value = $titlereceipt_div_value *0.50;

                            }else{
                                  $title_id = $title->id;
                                  $title_total = $title->receipt($title_id)->where('col_receipt.report_date','LIKE',$asdate[$x]['y-m-d'].'%' )->where('col_receipt.is_cancelled','=','0')->get();
                                        $title_total= count($title_total)>0 ? $title_total : [] ;
                                        $titlereceipt_div_value = 0;

                                        foreach($title_total as $receipt_value){
                                            if($title->id == $insurance_premium){
                                                $titlereceipt_div_value += $receipt_value->value - 15;
                                                $misc_add[$category->id][$group->id][39][$asdate[$x]['y-m-d']] += 15; 

                                            }else{
                                                $titlereceipt_div_value +=  $receipt_value->value;
                                            }
                                        }
                            }
                          

                            
                          

                            $titlecash_divs = $title->cash_div()->where('col_cash_division.date_of_entry','=',$asdate[$x]['y-m-d'])->select(["col_cash_division_items.value"])->get();
                            $titlecash_divs= count($titlecash_divs)>0 ? $titlecash_divs: [] ;
                            $titlecash_div_value = 0;
                            foreach($titlecash_divs as $titlecash_div){
                                     $titlecash_div_value = $titlecash_div_value  + $titlecash_div->value;
                            }

                            $total_title[$category->id][$group->id][$title->id][$asdate[$x]['y-m-d']] =  $titlecash_div_value + $titlereceipt_div_value;

                            $title_total_sum[$category->id][$group->id][$title->id] =  $title_total_sum[$category->id][$group->id][$title->id] + ($total_title[$category->id][$group->id][$title->id][$asdate[$x]['y-m-d']]);

                            foreach ($title->subs as $subs){
                                $subtitle_total_sum[$group->id][$title->id][$subs->id] = 0;
                                $tr_counter++;
                                for( $y = 0 ; $y<=$data['diff']; $y++){
                                        if( $asdate[$y]['D'] != 'Sun'  && $asdate[$y]['D'] != 'Sat' ){
                                                $total_subtitle[$category->id][$group->id][$title->id][$subs->id][$asdate[$y]['y-m-d']] = [];
                                                $subtitle_total = $subs->receipt()->where('col_receipt.report_date','LIKE',$asdate[$y]['y-m-d'].'%' )->where('col_receipt.is_cancelled','=','0')->where('col_receipt.is_printed','=','1')->select(["col_receipt_items.value"])->get();
                                                $subtitle_total= count($subtitle_total)>0 ? $subtitle_total: [] ;
                                                $subsreceipt_div_value = 0;
                                                foreach($subtitle_total as $receipt_value){
                                                    $subsreceipt_div_value =$subsreceipt_div_value  + $receipt_value->value;
                                                }

                                                $subscash_div = $subs->cash_div()->where('col_cash_division.date_of_entry','=',$asdate[$y]['y-m-d'])->select(["col_cash_division_items.value"])->get();
                                                $subscash_div= count($subscash_div)>0 ? $subscash_div: [] ;
                                                  $subscash_div_value = 0;
                                                foreach($subscash_div as $cashdiv_value){
                                                         $subscash_div_value += $cashdiv_value->value;
                                                }

                                                   $total_subtitle[$category->id][$group->id][$title->id][$subs->id][$asdate[$y]['y-m-d']] =  $subsreceipt_div_value + $subscash_div_value ;

                                                $subtitle_total_sum[$group->id][$title->id][$subs->id] =  $subtitle_total_sum[$group->id][$title->id][$subs->id] + ($total_subtitle[$category->id][$group->id][$title->id][$subs->id][$asdate[$y]['y-m-d']]);
                                        }
                                }
                            }
                   }
            }
        }
    }

}
$colsx = 0;
for( $x = 0 ; $x<=$data['diff']; $x++){
    if( $asdate[$x]['D'] != 'Sun'  && $asdate[$x]['D'] != 'Sat' ){
        $date_total[$asdate[$x]['y-m-d']] = 0;
        $colsx++;
    }
}
?>
<?php foreach($categories as $category): ?>
    <tr class="page-break">
                <td colspan="<?php echo e($colsx +2); ?>" ><strong><?php echo e($category->name); ?></strong></td>
    </tr>
    <?php foreach($category->group as $group): ?>
        <tr class="page-break">
                    <td colspan="<?php echo e($colsx + 2); ?>" style="padding: 1px 1px 1px 30px;" ><strong><?php echo e($group->name); ?></strong></td>
        </tr>
        <?php $totalxxt = []; ?>
        <?php foreach($group->title as $title): ?>
        <?php $totalxxt[$category->id][$group->id][$title->id] = 0; $dt_t = 0; ?>
            <tr class="page-break">
                            <td style="padding: 1px 1px 1px 50px; "><?php echo e($title->name); ?></td>

                            <?php for( $x = 0 ; $x<=$data['diff']; $x++): ?>
                                <?php if( $asdate[$x]['D'] != 'Sun'  && $asdate[$x]['D'] != 'Sat' ): ?>
                                    <?php 
                                        if($title->id == $miscllanaus){
                                            $title_totlasss = $total_title[$category->id][$group->id][$title->id][$asdate[$x]['y-m-d']] + $misc_add[$category->id][$group->id][39][$asdate[$x]['y-m-d']];
                                        }else{
                                            $title_totlasss = $total_title[$category->id][$group->id][$title->id][$asdate[$x]['y-m-d']];
                                        }

                                       $date_total[$asdate[$x]['y-m-d']]  += $title_totlasss;

                                        $totalxxt[$category->id][$group->id][$title->id] += $title_totlasss;
                                    ?>                                       
                                     <td class="text-right"><?php echo e(number_format($title_totlasss,2)); ?></td>
                                <?php endif; ?>
                            <?php endfor; ?>
                            <td  class="text-right gtotal"> <?php echo e(number_format($totalxxt[$category->id][$group->id][$title->id] , 2)); ?></td>
            </tr>

            <?php foreach($title->subs as $subs): ?>
                <tr class="page-break">
                                <td style="padding: 1px 1px 1px 70px; " ><?php echo e($subs->name); ?></td>
                                <?php for( $y = 0 ; $y<=$data['diff']; $y++): ?>
                                    <?php if( $asdate[$y]['D'] != 'Sun'  && $asdate[$y]['D'] != 'Sat' ): ?>
                                        <?php 
                                            $date_total[$asdate[$y]['y-m-d']] += $total_subtitle[$category->id][$group->id][$title->id][$subs->id][$asdate[$y]['y-m-d']];
                                        ?> 

                                        <td class="text-right"><?php echo e(number_format($total_subtitle[$category->id][$group->id][$title->id][$subs->id][$asdate[$y]['y-m-d']],2)); ?></td>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                <td  class="text-right gtotal"> <?php echo e(number_format($subtitle_total_sum[$group->id][$title->id][$subs->id] , 2)); ?></td>
                </tr>

            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
<?php endforeach; ?>

<tr>
    <td class="gtotal">DAILY TOTAL</td>
    <?php foreach($date_total as $date_k => $dt_value): ?>
        <td class="text-right gtotal"><?php echo e(number_format($dt_value,2)); ?></td>
    <?php endforeach; ?>

    <td class="text-right gtotal"><?php echo number_format(array_sum($date_total),2); ?></td>
</tr>

        </tbody>
    </table>

<div class="alert alert-info">
    <strong>NOTE:</strong> For SEF "Tax Revenue-Fines & Penalties-Real Property Taxes" share provincial 50%.
</div>

</div>



</body>

</html>

<?php echo e(dd()); ?>

