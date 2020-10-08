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
 <h4><strong>DATE :  <?php echo e($month_end->format('F')); ?> , <?php echo e($year); ?></strong></h4>
        <table>
          <thead>
              <tr>
                  <th>Account Title</th>
                  <th>Account Code</th>
                  <th>Budget Estimate</th>
                  <?php if( $month != 1): ?>
                      <th>Actual Collection
                          <br />
                          <?php echo e(date('M', mktime(0, 0, 0,  $start_month, 10))); ?>-01-<?php echo e(date('M', mktime(0, 0, 0,  $end_month, 10))); ?>, <?php echo e($year); ?>

                      </th>
                      <?php $cols = 1; ?>
                  <?php endif; ?>
                  <th>Actual Collection
                      <br />
                         <?php echo e(date('F', mktime(0, 0, 0,  $month, 10))); ?>, <?php echo e($year); ?>

                  </th>
                  <th>Total</th>
                  <th>% of Collection</th>
              </tr>
          </thead>
          <tbody>
          <?php foreach($categories as $category): ?>
          <?php if($category->id != 5): ?>
           <?php $category_total[$category->id] = 0;
                  $other_genfund_be = 0;
                  $other_genfund_pastm = 0;
                  $other_genfund_crrentm = 0;
                  $other_genfund_total = 0;
                  $other_genfund_prcnt = 0;

            ?>
            <?php if($category->id != 3): ?>
              <tr>
                  <td colspan="<?php echo e($cols+6); ?>" ><b><?php echo e($category->name); ?></b></td>
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
              <?php foreach($category->group as $group): ?>
                  <?php
                          $catgrp_total_bdgt_e[$category->id][$group->id] = [];
                          $catgrp_total_actual_col_month_past[$category->id][$group->id] = [];
                          $catgrp_total_actual_col_month[$category->id][$group->id] = [];
                           $catgrp_total_actual_col_month_total[$category->id][$group->id] = [];
                           $group_total[$group->id] = 0;
                  ?>
                  <?php if($group->id != 13): ?>
                  <tr>
                      <td colspan="<?php echo e($cols+6); ?>" >
                        <div><?php echo e($group->name); ?></div>
                        <br>
                      </td>
                  </tr>
                  <?php endif; ?>
                  <?php foreach($group->title as $title): ?>

                           <?php if($title->show_in_monthly == 1): ?>
                                  <?php
                                  $catgrp_total_actual_col_month_past[$category->id][$group->id][$title->id] = 0;
                                   $catgrp_total_actual_col_month[$category->id][$group->id][$title->id] = 0;
                                   $catgrp_total_actual_col_month_total[$category->id][$group->id][$title->id] = 0;
                                   $catgrp_total_actual_col_month_prcnt_coll[$category->id][$group->id][$title->id] = 0;
                                   
                                   if(!$title->budget()->where('year','=',$year)->first()){
                                      
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
                                    <?php echo e($title->name); ?>  
                                  </td>

                                   <td><?php echo e($title->code); ?></td>

                                  <td><?php echo e(number_format($catgrp_total_bdgt_e[$category->id][$group->id][$title->id],2)); ?> 
                                  </td>

                                  <?php if( $month != 1): ?>
                                      <td><?php echo e(number_format($catgrp_total_actual_col_month_past[$category->id][$group->id][$title->id],2)); ?>

                                      </td>
                                  <?php endif; ?>

                                  <?php if($category->id == 1): ?>
                                    <?php if($title->id == 2): ?>
                                      <td><?php echo e(number_format($rpt_basic,2)); ?></td>
                                      <?php
                                        $catgrp_total_actual_col_month[$category->id][$group->id]['rpt_basic'] = $rpt_basic;
                                      ?>
                                    <?php elseif($title->id == 54 || $title->id == 55): ?>
                                      <td><?php echo e(number_format($rpt_basic_penalty,2)); ?></td>
                                      <?php
                                        $catgrp_total_actual_col_month[$category->id][$group->id]['rpt_basic_penalty'] = $rpt_basic_penalty;
                                      ?>
                                    <?php else: ?>
                                      <td><?php echo e(number_format($catgrp_total_actual_col_month[$category->id][$group->id][$title->id],2)); ?></td>
                                    <?php endif; ?>
                                  <?php elseif($category->id == 4): ?>
                                    <?php if($title->id == 49): ?>
                                      <td><?php echo e(number_format($rpt_sef,2)); ?></td>
                                      <?php
                                        $catgrp_total_actual_col_month[$category->id][$group->id]['rpt_sef'] = $rpt_sef;
                                      ?>
                                    <?php elseif($title->id == 54 || $title->id == 55): ?>
                                      <td><?php echo e(number_format($rpt_sef_penalty,2)); ?></td>
                                      <?php
                                        $catgrp_total_actual_col_month[$category->id][$group->id]['rpt_sef_penalty'] = $rpt_sef_penalty;
                                      ?>
                                    <?php else: ?>
                                      <td><?php echo e(number_format($catgrp_total_actual_col_month[$category->id][$group->id][$title->id],2)); ?></td>
                                    <?php endif; ?>
                                  <?php elseif($category->id == 2): ?> 
                                    <?php if(isset($bts[$title->id])): ?>
                                      <td><?php echo e(number_format($bts[$title->id],2)); ?></td>
                                      <?php
                                        $catgrp_total_actual_col_month[$category->id][$group->id][$bts[$title->id]] = $bts[$title->id];
                                      ?>
                                    <?php else: ?>
                                      <td><?php echo e(number_format($catgrp_total_actual_col_month[$category->id][$group->id][$title->id],2)); ?></td>
                                    <?php endif; ?>
                                  <?php endif; ?>

                                  <td><?php echo e(number_format($catgrp_total_actual_col_month_total[$category->id][$group->id][$title->id],2)); ?> </td>
                                  

                                  <td> <?php echo e(number_format($catgrp_total_actual_col_month_prcnt_coll[$category->id][$group->id][$title->id],2)); ?>% </td>
                              </tr>

                              

                            <?php endif; ?>

                           <?php
                                    $catgrp_total_bdgt_e_sub[$category->id][$group->id][$title->id] = [];
                                    $catgrp_total_actual_col_month_sub[$category->id][$group->id][$title->id] = [];
                                      $catgrp_total_actual_col_month_total_sub[$category->id][$group->id][$title->id] = [];
                                      $catgrp_total_actual_col_month_past_sub[$category->id][$group->id][$title->id]  = [];
                           ?>

                     <?php foreach($title->subs as $subs): ?>
                          <?php if($subs->show_in_monthly == 1): ?>
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
                          <tr>
                              <td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo e($subs->name); ?></td>
                             <td ><?php echo e($subs->code); ?></td>
                                  <td><?php echo e(number_format($catgrp_total_bdgt_e_sub[$category->id][$group->id][$title->id][$subs->id] ,2)); ?></td>
                                  <?php if( $month != 1): ?>
                                      <td><?php echo e(number_format($catgrp_total_actual_col_month_past_sub[$category->id][$group->id][$title->id][$subs->id] ,2)); ?></td>
                                  <?php endif; ?>

                                  <td><?php echo e(number_format($catgrp_total_actual_col_month_sub[$category->id][$group->id][$title->id][$subs->id] ,2)); ?></td>

                                  <td><?php echo e(number_format($catgrp_total_actual_col_month_total_sub[$category->id][$group->id][$title->id][$subs->id] ,2)); ?></td>
                                  <td> <?php echo e(number_format($catgrp_total_actual_col_month_prcnt_coll_sub[$category->id][$group->id][$title->id][$subs->id] ,2)); ?>% </td>
                              </tr>
                          </tr>
                          <?php endif; ?>
                      <?php endforeach; ?> <!-- end of sub title -->
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
                  <?php endforeach; ?> 
                  <?php if( $group->name == 'Tax Revenue' && $category->id == 1 ): ?>
                      <?php
                          $percentx = 0;
                          if(array_sum($catgrp_total_bdgt_e_total[$category->id]) > 0){
                              $percentx = ( array_sum( $catgrp_total_actual_col_month_total[$category->id][$group->id])  / array_sum($catgrp_total_bdgt_e[$category->id][$group->id]) ) * 100;
                          }
                       ?>
                      <tr>
                        <td><strong><?php echo e($group->name); ?> TOTAL</strong></td>
                        <td></td>
                        <td><?php echo e(number_format(array_sum($catgrp_total_bdgt_e[$category->id][$group->id]),2)); ?> </td>
                             <?php if( $month != 1): ?>
                                    <td><?php echo e(number_format(array_sum( $catgrp_total_actual_col_month_past[$category->id][$group->id]),2)); ?>  </td>
                           <?php endif; ?>
                        <td><?php echo e(number_format(array_sum( $catgrp_total_actual_col_month[$category->id][$group->id]),2)); ?>  </td>
                        <td><?php echo e(number_format(array_sum( $catgrp_total_actual_col_month_total[$category->id][$group->id]),2)); ?>  </td>
                        <td><?php echo e(number_format($percentx,2)); ?>%</td>
                      </tr>
              <?php elseif($group->name != 'Tax Revenue' && $category->id == 1): ?>
                    <?php
                          $other_genfund_be += array_sum($catgrp_total_bdgt_e[$category->id][$group->id]);
                          $other_genfund_pastm += array_sum( $catgrp_total_actual_col_month_past[$category->id][$group->id]);
                          $other_genfund_crrentm += array_sum( $catgrp_total_actual_col_month[$category->id][$group->id]);
                          $other_genfund_total += array_sum( $catgrp_total_actual_col_month_total[$category->id][$group->id]);

                          if($other_genfund_total > 0){
                              $other_genfund_prcnt = ( $other_genfund_total  / $other_genfund_be ) * 100;
                          }
                       ?>
                       <?php if($group->id === 4): ?>
                      <tr>
                        <td><strong>Sub  TOTALs</strong></td>
                        <td></td>
                        <td><?php echo e(number_format($other_genfund_be,2)); ?> </td>
                          <?php if( $month != 1): ?>
                            <td ><?php echo e(number_format($other_genfund_pastm,2)); ?>  </td>
                          <?php endif; ?>
                        <td><?php echo e(number_format($other_genfund_crrentm,2)); ?>  </td>
                        <td><?php echo e(number_format($other_genfund_total,2)); ?>  </td>
                        <td><?php echo e(number_format($other_genfund_prcnt,2)); ?>%</td>
                      </tr>
                      <?php endif; ?>
              <?php endif; ?>

              <?php endforeach; ?> 

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
              <tr>
                <td colspan="2"><strong><?php echo e($category->name); ?> TOTAL</strong></td>
                <td><?php echo e(number_format(array_sum($catgrp_total_bdgt_e_total[$category->id]),2)); ?></td>
                <?php if( $month != 1): ?>
                  <td><?php echo e(number_format(array_sum($catgrp_total_actual_col_month_past_total[$category->id]),2)); ?></td>
                <?php endif; ?>
                <td><?php echo e(number_format(array_sum($catgrp_total_actual_col_month_totalx[$category->id]),2)); ?></td>
                <td><?php echo e(number_format($category_total[$category->id],2)); ?></td>
                <td><?php echo e(number_format($percent,2)); ?>%</td>
              </tr>
              <?php endif; ?>
              <?php if($category->id == 3): ?>
               <?php
                      $percentxx = ( $grand_total / $grand_total_be) * 100;
               ?>
                  <tr>
                    <td><strong>GRAND - GENERAL FUND </strong></td>
                    <td><?php echo e(number_format($grand_total_be,2)); ?></td>
                    <?php if( $month != 1): ?>
                      <td><?php echo e(number_format($grand_total_am,2)); ?></td>
                    <?php endif; ?>
                    <td><?php echo e(number_format($grand_total_atm,2)); ?></td>
                    <td><?php echo e(number_format($grand_total ,2)); ?></td>
                    <td><?php echo e(number_format($percentxx,2)); ?>%</td>
                  </tr>
               <?php endif; ?>
              <?php endif; ?>
          <?php endforeach; ?>
        </tbody>
      </table>

    </div>
<footer>

</footer>
</body>

</html>


