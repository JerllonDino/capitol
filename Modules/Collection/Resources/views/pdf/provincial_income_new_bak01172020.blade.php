<!DOCTYPE html>
<html>
<head>
    <title>MONTHLY PROVINCIAL REPORT</title>
    {{ Html::style('/bootstrap-3.3.6/css/bootstrap.min.css') }}
    <style type="text/css">
        html {
            margin-bottom: 8px;
            margin-top: 8px;
            margin-left: 5px;
            margin-right: 10px;
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

                width: 550px;
                text-align: center;
                margin: 10px auto;
        }

           .image_logo{
                width: 100px;
            }


        td.total_group {
                 border-bottom: 3px double #1d60ef !important;
                border-top: 2px solid #1d60ef !important;
                font-size: 14px;
                font-weight: bold;
            }
        td.total_categ{
                border-bottom: 3px double #1d60ef !important;
                border-top: 2px solid #1d60ef !important;
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
            position: fixed;
            bottom:15px;
        }
        .pagenum:before {
            content: counter(page);
        }
        .table-condensed>tbody>tr>td, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>td, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>thead>tr>th {
            padding: 3px 2px;
            font-size:11px;
        }
        .table-condensed>tbody>tr>th{
            text-align: center;
        }


    </style>
</head>
<body>

<div class="header">
    <table class="center ">
        <tr>
            <td style="text-align:right; width:200px;">
                <img src="{{asset('asset/images/benguet_capitol.png')}}" class="image_logo" />
            </td>
            <td>
            Republic of the Philippines<br />
            <strong>PROVINCIAL GOVERNMENT OF BENGUET</strong><br />
            <small>La Trinidad, Benguet</small><br />
            <strong>OFFICE OF THE PROVINCIAL TREASURER</strong>
            </td>
        </tr>
    </table>
</div>
<div class="footer">
    Page <span class="pagenum"></span>
</div>

<?php
            $grand_total_be= 0;
            $grand_total_am= 0;
            $grand_total_atm= 0;
            $grand_total= 0;
            $cat_total_bdgt_e = [];
            $cat_total_actual_col_past = [];
            $cat_total_actual_col_month = [];
            $cols = 0;
            $start_month = 1;
            $end_month = $month - 1;

            $month_p = [];
            $d_month = 0;
            for($x=0; $x<$end_month; $x++){
                        $month_x =$month_x->addMonths($d_month);
                        $month_p[$x]['y-m-d'] = $month_x->format('Y-m-d');
                        $month_p[$x]['m'] = $month_x->format('m');
                        $d_month = 1;
            }

?>

 <div>
 <h4><strong>DATE :  {{$month_end->format('F')}} , {{ $year }}</strong></h4>
        <table border="1" class="table table-condensed page-break" id="accounts-mnthly">
        <thead>
            <tr class="page-break">
                <th  class="text-center">Account Title</th>
                <th  class="text-center" style="width:85px;" >Account Code</th>
                <th class="text-center" >Budget Estimate</th>
                @if( $month != 1)
                    <th class="text-center" style="width:110px;">Actual Collection
                        <br />
                        {{date('M', mktime(0, 0, 0,  $start_month, 10))}}-01-{{date('M', mktime(0, 0, 0,  $end_month, 10))}}, {{$year}}
                    </th>
                    <?php $cols = 1; ?>
                @endif
                <th class="text-center" style="width:110px;">Actual Collection
                    <br />
                       {{date('F', mktime(0, 0, 0,  $month, 10))}}, {{$year}}
                </th>
                <th class="text-center" >Total</th>
                <th class="text-center" >% of Collection</th>
            </tr>
        </thead>
        <tbody>


        @foreach ($categories as $category)

        @if($category->id != 5)


         <?php $category_total[$category->id] = 0;
                $other_genfund_be = 0;
                $other_genfund_pastm = 0;
                $other_genfund_crrentm = 0;
                $other_genfund_total = 0;
                $other_genfund_prcnt = 0;

          ?>
          @if($category->id != 3)
            <tr class="page-break">
                <td colspan="{{$cols+6}}" ><b>{{ $category->name }}</b></td>
            </tr>
            <?php
                        $catgrp_total_bdgt_e[$category->id] = [];
                        $catgrp_total_bdgt_e_total[$category->id] = [];
                        $catgrp_total_actual_col_month_past[$category->id] = [];
                        $catgrp_total_actual_col_month_past_total[$category->id]  = [];
                        $catgrp_total_actual_col_month[$category->id] = [];
                         $catgrp_total_actual_col_month_total[$category->id] = [];
                          $catgrp_total_actual_col_month_totalx[$category->id] = [];
            ?>
            @foreach ($category->group as $group)
                <?php
                        $catgrp_total_bdgt_e[$category->id][$group->id] = [];
                        $catgrp_total_actual_col_month_past[$category->id][$group->id] = [];
                        $catgrp_total_actual_col_month[$category->id][$group->id] = [];
                         $catgrp_total_actual_col_month_total[$category->id][$group->id] = [];
                         $group_total[$group->id] = 0;
                ?>
                @if($group->id != 13)
                <tr class="page-break" >
                    <td colspan="{{$cols+6}}" >
                      <div class="col-sm-12">{{ $group->name }}</div>
                      <br>
                    </td>
                </tr>
                @endif
                @foreach ($group->title as $title)

                         @if ($title->show_in_monthly == 1)
                                <?php
                                $catgrp_total_actual_col_month_past[$category->id][$group->id][$title->id] = 0;
                                 $catgrp_total_actual_col_month[$category->id][$group->id][$title->id] = 0;
                                 $catgrp_total_actual_col_month_total[$category->id][$group->id][$title->id] = 0;
                                 $catgrp_total_actual_col_month_prcnt_coll[$category->id][$group->id][$title->id] = 0;
                                 
                                 if(!$title->budget()->where('year','=',$year)->first()){
                                    // dd($title->budget()->where('year','=',$year)->first());
                                 }
                                $catgrp_total_bdgt_e[$category->id][$group->id][$title->id] = $title->budget()->where('year','=',$year)->first()->value;
                                $catgrp_total_actual_col_past[$category->id][$group->id][$title->id] = [];
                                            $cash_div_value = 0;
                                            $receipt_div_value = 0;
                                             $mnthly_pi_value = 0;

                                for($x=0; $x<$end_month; $x++){
                                    $mnthly_pi = $title->mnhtly_prov_income()->where('year','=',$year)->where('month','=',$month_p[$x]['m'])->first();
                                    if($mnthly_pi){
                                        $mnthly_pi_value += $mnthly_pi->value;
                                    }
                                }
                                    $this_month = 0;
                                    $this_monthx = 0;
                                      
                                         $mnthly_pix = $title->mnhtly_prov_income()->where('year','=',$year)->where('month','=',$month)->first();
                                         if($mnthly_pix){
                                                if($mnthly_pix->total_value != '0.00' || $mnthly_pix->total_value != '' ){
                                                    $this_month =  $mnthly_pix->total_value;
                                                }else{
                                                  $this_month =  $mnthly_pix->value;
                                                }

                                                $this_monthx = $mnthly_pix->value;

                                         }

                                  $catgrp_total_actual_col_month_past[$category->id][$group->id][$title->id] =  $cash_div_value + $mnthly_pi_value ;
                                  
                                  $catgrp_total_actual_col_month[$category->id][$group->id][$title->id] =  $this_monthx  ;
                                  $catgrp_total_actual_col_month_total[$category->id][$group->id][$title->id] = $cash_div_value + $mnthly_pi_value + $this_monthx;
                                  
                                  if($catgrp_total_bdgt_e[$category->id][$group->id][$title->id] > 0){
                                     $catgrp_total_actual_col_month_prcnt_coll[$category->id][$group->id][$title->id] = ( ($cash_div_value + $mnthly_pi_value +$this_month) / $catgrp_total_bdgt_e[$category->id][$group->id][$title->id] ) * 100;
                                  }
                                ?>
                         
                             <tr>
                                <td>
                                  {{ $title->name }}  
                                </td>

                                 <td >{{ $title->code }}</td>

                                <td class="text-right">{{ number_format($catgrp_total_bdgt_e[$category->id][$group->id][$title->id],2) }} 
                                </td>

                                @if( $month != 1)
                                    <td class="text-right">{{  number_format($catgrp_total_actual_col_month_past[$category->id][$group->id][$title->id],2) }}
                                    </td>
                                @endif

                                <!-- <td class="text-right">{{  number_format($catgrp_total_actual_col_month[$category->id][$group->id][$title->id],2) }}
                                </td> -->
                                @if($category->id == 1)
                                  @if($title->id == 2)
                                    <td class="text-right">{{  number_format($rpt_basic,2) }}</td>
                                    <?php
                                      $catgrp_total_actual_col_month[$category->id][$group->id]['rpt_basic'] = $rpt_basic;
                                    ?>
                                  @elseif($title->id == 54 || $title->id == 55)
                                    <td class="text-right">{{  number_format($rpt_basic_penalty,2) }}</td>
                                    <?php
                                      $catgrp_total_actual_col_month[$category->id][$group->id]['rpt_basic_penalty'] = $rpt_basic_penalty;
                                    ?>
                                  @else
                                    <td class="text-right">{{  number_format($catgrp_total_actual_col_month[$category->id][$group->id][$title->id],2) }}</td>
                                  @endif
                                @elseif($category->id == 4)
                                  @if($title->id == 49)
                                    <td class="text-right">{{  number_format($rpt_sef,2) }}</td>
                                    <?php
                                      $catgrp_total_actual_col_month[$category->id][$group->id]['rpt_sef'] = $rpt_sef;
                                    ?>
                                  @elseif($title->id == 54 || $title->id == 55)
                                    <td class="text-right">{{  number_format($rpt_sef_penalty,2) }}</td>
                                    <?php
                                      $catgrp_total_actual_col_month[$category->id][$group->id]['rpt_sef_penalty'] = $rpt_sef_penalty;
                                    ?>
                                  @else
                                    <td class="text-right">{{  number_format($catgrp_total_actual_col_month[$category->id][$group->id][$title->id],2) }}</td>
                                  @endif
                                @elseif($category->id == 2) 
                                  @if(isset($bts[$title->id]))
                                    <td class="text-right">{{  number_format($bts[$title->id],2) }}</td>
                                    <?php
                                      $catgrp_total_actual_col_month[$category->id][$group->id][$bts[$title->id]] = $bts[$title->id];
                                    ?>
                                  @else
                                    <td class="text-right">{{  number_format($catgrp_total_actual_col_month[$category->id][$group->id][$title->id],2) }}</td>
                                  @endif
                                @endif

                                <td class="text-right">{{  number_format($catgrp_total_actual_col_month_total[$category->id][$group->id][$title->id],2) }} </td>
                                

                                <td class="text-right" > {{ number_format($catgrp_total_actual_col_month_prcnt_coll[$category->id][$group->id][$title->id],2) }}% </td>
                            </tr>

                            

                          @endif

                         <?php
                                  $catgrp_total_bdgt_e_sub[$category->id][$group->id][$title->id] = [];
                                  $catgrp_total_actual_col_month_sub[$category->id][$group->id][$title->id] = [];
                                    $catgrp_total_actual_col_month_total_sub[$category->id][$group->id][$title->id] = [];
                                    $catgrp_total_actual_col_month_past_sub[$category->id][$group->id][$title->id]  = [];
                         ?>

                   @foreach ($title->subs as $subs)
                        @if ($subs->show_in_monthly == 1)
                          <?php
                                $catgrp_total_actual_col_month_past_sub[$category->id][$group->id][$title->id][$subs->id] = 0;

                                 $catgrp_total_actual_col_month_sub[$category->id][$group->id][$title->id][$subs->id] = 0;

                                 $catgrp_total_actual_col_month_total_sub[$category->id][$group->id][$title->id][$subs->id] = 0;

                                 $catgrp_total_actual_col_month_prcnt_coll_sub[$category->id][$group->id][$title->id][$subs->id] = 0;

                                $catgrp_total_bdgt_e_sub[$category->id][$group->id][$title->id][$subs->id] = $subs->budget()->where('year','=',$year)->first()->value;

                                $catgrp_total_actual_col_past_sub[$category->id][$group->id][$title->id][$subs->id] = [];

                                $subsvalue = 0;
                                $subsreceipt_div_value = 0;
                                $subscash_div_value = 0;
                                $this_submonth = 0;
                                $this_submonthx = 0;
                                $mnthly_pixx = $subs->mnhtly_prov_income()->where('year','=',$year)->where('month','=',$month)->first();
                                    if($mnthly_pixx){
                                         if($mnthly_pixx->total_value != '0.00' || $mnthly_pixx->total_value != '' ){
                                                    $this_submonth =  $mnthly_pixx->total_value;
                                                }else{
                                                  $this_submonth =  $mnthly_pixx->value;
                                                }

                                                $this_submonthx =  $mnthly_pixx->value;
                                    }
                                // }
                                $subsmnthly_pi_value = 0;
                                for($x=0; $x<$end_month; $x++){
                                    $subsmnthly_pi = $subs->mnhtly_prov_income()->where('year','=',$year)->where('month','=',$month_p[$x]['m'])->first();
                                    if($subsmnthly_pi){
                                        $subsmnthly_pi_value += $subsmnthly_pi->value;
                                    }
                                }
                                 $catgrp_total_actual_col_month_past_sub[$category->id][$group->id][$title->id][$subs->id] =  $subscash_div_value + $subsmnthly_pi_value ;
                                  $catgrp_total_actual_col_month_sub[$category->id][$group->id][$title->id][$subs->id] =  $this_submonthx ;
                                  $catgrp_total_actual_col_month_total_sub[$category->id][$group->id][$title->id][$subs->id] = $subscash_div_value + $subsmnthly_pi_value +$this_submonth;
                                  if($catgrp_total_bdgt_e_sub[$category->id][$group->id][$title->id][$subs->id] > 0){
                                     $catgrp_total_actual_col_month_prcnt_coll_sub[$category->id][$group->id][$title->id][$subs->id] = ( ($subscash_div_value + $subsmnthly_pi_value +$this_submonth) / $catgrp_total_bdgt_e_sub[$category->id][$group->id][$title->id][$subs->id] ) * 100;
                                  }
                                ?>
                        <tr class="page-break">
                            <td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   {{ $subs->name }}</td>
                           <td >{{ $subs->code }}</td>
                                <td class="text-right">{{ number_format($catgrp_total_bdgt_e_sub[$category->id][$group->id][$title->id][$subs->id] ,2) }}</td>
                                @if( $month != 1)
                                    <td class="text-right">{{  number_format($catgrp_total_actual_col_month_past_sub[$category->id][$group->id][$title->id][$subs->id] ,2) }}</td>
                                @endif

                                <td class="text-right">{{  number_format($catgrp_total_actual_col_month_sub[$category->id][$group->id][$title->id][$subs->id] ,2) }}</td>


                                <td class="text-right">{{  number_format($catgrp_total_actual_col_month_total_sub[$category->id][$group->id][$title->id][$subs->id] ,2) }}</td>
                                <td class="text-right" > {{ number_format($catgrp_total_actual_col_month_prcnt_coll_sub[$category->id][$group->id][$title->id][$subs->id] ,2) }}% </td>
                            </tr>
                        </tr>
                        @endif
                    @endforeach <!-- end of sub title -->
                    <?php
                    if ($title->show_in_monthly == 1){
                            $catgrp_total_bdgt_e[$category->id][$group->id][$title->id] +=  array_sum($catgrp_total_bdgt_e_sub[$category->id][$group->id][$title->id] ) ;
                            $catgrp_total_actual_col_month_past[$category->id][$group->id][$title->id] +=   array_sum($catgrp_total_actual_col_month_past_sub[$category->id][$group->id][$title->id] ) ;
                            $catgrp_total_actual_col_month[$category->id][$group->id][$title->id] +=   array_sum($catgrp_total_actual_col_month_sub[$category->id][$group->id][$title->id] ) ;
                            $catgrp_total_actual_col_month_total[$category->id][$group->id][$title->id] +=   array_sum($catgrp_total_actual_col_month_total_sub[$category->id][$group->id][$title->id] ) ;
                            $catgrp_total_bdgt_e_total[$category->id][$group->id] =   array_sum($catgrp_total_bdgt_e[$category->id][$group->id]);
                            $catgrp_total_actual_col_month_past_total[$category->id][$group->id] =   array_sum($catgrp_total_actual_col_month_past[$category->id][$group->id]);
                            $catgrp_total_actual_col_month_totalx[$category->id][$group->id] =   array_sum($catgrp_total_actual_col_month[$category->id][$group->id]);
                            $category_total[$category->id] += $catgrp_total_actual_col_month_total[$category->id][$group->id][$title->id];
                    }
                    ?>
                @endforeach <!-- end of title -->
                @if( $group->name == 'Tax Revenue' && $category->id == 1 )
                    <?php
                        $percentx = 0;
                        if(array_sum($catgrp_total_bdgt_e_total[$category->id]) > 0){
                            $percentx = ( array_sum( $catgrp_total_actual_col_month_total[$category->id][$group->id])  / array_sum($catgrp_total_bdgt_e[$category->id][$group->id]) ) * 100;
                        }
                     ?>
                   <tr class="page-break">
                        <td class="total_categ"><strong>{{ $group->name }} TOTAL</strong></td>
                        <td class="total_categ"></td>
                        <td  class="text-right total_categ" >{{  number_format(array_sum($catgrp_total_bdgt_e[$category->id][$group->id]),2) }} </td>
                             @if( $month != 1)
                                    <td  class="text-right total_categ" >{{  number_format(array_sum( $catgrp_total_actual_col_month_past[$category->id][$group->id]),2) }}  </td>
                           @endif
                        <td  class="text-right total_categ" >{{  number_format(array_sum( $catgrp_total_actual_col_month[$category->id][$group->id]),2) }}  </td>
                         <td  class="text-right total_categ" >{{  number_format(array_sum( $catgrp_total_actual_col_month_total[$category->id][$group->id]),2) }}  </td>
                          <td class="text-right total_categ">{{ number_format($percentx,2) }}%</td>
                    </tr>
            @elseif($group->name != 'Tax Revenue' && $category->id == 1)
                  <?php
                        $other_genfund_be += array_sum($catgrp_total_bdgt_e[$category->id][$group->id]);
                        $other_genfund_pastm += array_sum( $catgrp_total_actual_col_month_past[$category->id][$group->id]);
                        $other_genfund_crrentm += array_sum( $catgrp_total_actual_col_month[$category->id][$group->id]);
                        $other_genfund_total += array_sum( $catgrp_total_actual_col_month_total[$category->id][$group->id]);

                        if($other_genfund_total > 0){
                            $other_genfund_prcnt = ( $other_genfund_total  / $other_genfund_be ) * 100;
                        }
                     ?>
                     @if($group->id === 4)
                   <tr class="page-break">
                        <td class="total_categ"><strong>Sub  TOTALs</strong></td>
                        <td class="total_categ"></td>
                        <td  class="text-right total_categ" >{{  number_format($other_genfund_be,2) }} </td>
                             @if( $month != 1)
                                    <td  class="text-right total_categ" >{{  number_format($other_genfund_pastm,2) }}  </td>
                           @endif
                        <td  class="text-right total_categ" >{{  number_format($other_genfund_crrentm,2) }}  </td>
                         <td  class="text-right total_categ" >{{  number_format($other_genfund_total,2) }}  </td>
                          <td class="text-right total_categ">{{ number_format($other_genfund_prcnt,2) }}%</td>
                    </tr>
                    @endif
            @endif

            @endforeach <!-- end of groups -->

            <?php
                            if(in_array($category->id,[1,2,3])){
                                    $grand_total_be += array_sum($catgrp_total_bdgt_e_total[$category->id]);
                                    $grand_total_am += array_sum($catgrp_total_actual_col_month_past_total[$category->id]);
                                    $grand_total_atm += array_sum($catgrp_total_actual_col_month_totalx[$category->id]);
                                    $grand_total  += $category_total[$category->id];
                            }

             $percent = 0;
                if(array_sum($catgrp_total_bdgt_e_total[$category->id]) > 0){
                    $percent = ( $category_total[$category->id]  / array_sum($catgrp_total_bdgt_e_total[$category->id]) ) * 100;
                }
             ?>
                  <tr class="page-break">
                        <td class="total_categ"  colspan="2"><strong>{{ $category->name }} TOTAL</strong></td>
                         <td  class="text-right total_categ" >{{  number_format(array_sum($catgrp_total_bdgt_e_total[$category->id]),2)  }}</td>
                        @if( $month != 1)
                            <td  class="text-right total_categ" >{{  number_format(array_sum($catgrp_total_actual_col_month_past_total[$category->id]),2)  }}</td>
                        @endif
                        <td  class="text-right total_categ" >{{  number_format(array_sum($catgrp_total_actual_col_month_totalx[$category->id]),2)  }}</td>
                        <td class="text-right total_categ">{{ number_format($category_total[$category->id],2) }}</td>
                        <td  class="text-right total_categ">{{ number_format($percent,2) }}%</td>
            </tr>
            @endif
            @if($category->id == 3)
             <?php
                    $percentxx = ( $grand_total / $grand_total_be) * 100;
             ?>
                        <tr class="page-break">
                                <td class="total_categ" colspan="2"><strong>GRAND - GENERAL FUND </strong></td>
                                 <td  class="text-right total_categ" >{{  number_format($grand_total_be,2)  }}</td>
                                @if( $month != 1)
                                    <td  class="text-right total_categ" >{{  number_format($grand_total_am,2)  }}</td>
                                @endif
                                <td  class="text-right total_categ" >{{  number_format($grand_total_atm,2)  }}</td>
                                <td class="text-right total_categ">{{ number_format($grand_total ,2) }}</td>
                                <td  class="text-right total_categ">{{ number_format($percentxx,2) }}%</td>
                    </tr>
             @endif
            @endif
        @endforeach <!-- end of categories -->
        </tbody>
            </table>

            </div>
<footer>

</footer>
</body>

</html>


