        <table class="table table-condensed table-bordered page-break">
        <thead>
            <tr class="page-break">
                <th>Account Name</th>
                    <?php $d = 0; $sdate =$base['data']['start_date']; $asdate[] = []; ?>
                    @for( $x = 0 ; $x<=$base['data']['diff']; $x++)
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
        ?>
        @foreach ($base['categories'] as $category)
         <?php $category_total[$category->id] = 0; ?>
            <tr class="page-break">
                <td colspan="{{$base['data']['diff']+3}}" ><div class="col-sm-12"><b>{{ $category->name }}</b></div></td>
            </tr>
            @foreach ($category->group as $group)
             <?php $group_total[$group->id] = 0; ?>
                <tr class="page-break">
                    <td colspan="{{$base['data']['diff']+3}}" ><div class="col-sm-12">{{ $group->name }}</div></td>
                </tr>

                @foreach ($group->title as $title)
                <?php $title_total_sum[$group->id][$title->id] = 0; ?>

                    <?php $tr_counter++; ?>
                        <tr class="page-break">
                            <td><div class="col-sm-11 col-sm-offset-1">{{ $title->name }}</div></td>
                                @for( $x = 0 ; $x<=$base['data']['diff']; $x++)
                                    @if( $asdate[$x]['D'] != 'Sun'  && $asdate[$x]['D'] != 'Sat' )
                                    <?php
                                        $total_title[$category->id][$group->id][$title->id] = [];
                                        $title_total = $title->receipt()->where('col_receipt.date_of_entry','LIKE',$asdate[$x]['y-m-d'].'%' )->select(["col_receipt_items.value"])->get();
                                        $title_total= count($title_total)>0 ? $title_total: [] ;
                                        $titlereceipt_div_value = 0;
                                        foreach($title_total as $receipt_value){
                                                $titlereceipt_div_value = $titlereceipt_div_value + $receipt_value->value;
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
                                @for( $x = 0 ; $x<=$base['data']['diff']; $x++)
                                    @if( $asdate[$x]['D'] != 'Sun'  && $asdate[$x]['D'] != 'Sat' )
                                    <?php
                                        $total_subtitle[$category->id][$group->id][$title->id][$subs->id] = [];
                                        $subtitle_total = $subs->receipt()->where('col_receipt.date_of_entry','LIKE',$asdate[$x]['y-m-d'].'%' )->select(["col_receipt_items.value"])->get();
                                        $subtitle_total= count($subtitle_total)>0 ? $subtitle_total: [] ;
                                        $subsreceipt_div_value = 0;
                                        foreach($subtitle_total as $receipt_value){
                                            $subsreceipt_div_value =$subsreceipt_div_value  + $receipt_value->value;
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

                    @endforeach
                @endforeach 
              <?php   $category_total[$category->id] = $category_total[$category->id] + $group_total[$group->id] ; ?>
                <tr class="page-break">
                <td class="total_group"><div class="col-sm-12"><strong>{{ $group->name }} TOTAL</strong></div></td>
                 <td  colspan="{{$base['data']['diff']+2}}" class="text-right total_group" >{{ number_format($group_total[$group->id],2) }}</td>
            </tr>
            @endforeach 
                  <tr class="page-break">
                <td class="total_categ"><strong>{{ $category->name }} TOTAL</strong></td>
                 <td colspan="{{$base['data']['diff']+2}}" class="text-right total_categ" >{{ number_format($category_total[$category->id],2) }}</td>
            </tr>

            @if($tr_counter / 20 === 1)
                 <tr class="page-break-before">
                    <td colspan="{{$base['data']['diff']+2}}" >TEST</td>
                </tr>
            @endif
        @endforeach 
        </tbody>
</table>
