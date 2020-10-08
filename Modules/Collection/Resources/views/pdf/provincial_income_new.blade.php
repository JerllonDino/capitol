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
  $grand_total_be = 0;
  $grand_total_am = 0;
  $grand_total_atm = 0;
  $grand_total = 0;
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
            @if($month > 2)
              {{ date('M', mktime(0, 0, 0,  $start_month, 10)) }}-01-{{ date('M', mktime(0, 0, 0,  $end_month, 10)) }}, {{ $year }}
            @else
              {{ date('F', mktime(0, 0, 0,  $start_month, 10)) }}, {{ $year }}
            @endif
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
      @foreach($per_account as $category => $data1)
        @if($category != 2) 
          <tr>
            <td colspan="{{$cols+6}}"><b>{{ $categories_wo_bts[$category] }}</b></td>
          </tr>
        @endif
        @if($category != 5)
          @if($category != 3)
            @foreach($data1 as $group => $data2)
              <tr>
                <td colspan="{{$cols+6}}"><div class="col-sm-12">{{ $data2['group_name'] }}</div> <br></td>
              </tr>
              @foreach($data2['group'] as $title => $data3)
                <tr>
                  @if(isset($data3['is_bts']))
                    <td>{{ $data3['account_title'] }} (BTS)</td>
                  @else
                    <td>{{ $data3['account_title'] }}</td>
                  @endif
                  <td>{{ $data3['account_code'] }}</td>
                  <td class="text-right">{{ isset($data3['budget_estimate']) && !is_null($data3['budget_estimate']) ? number_format($data3['budget_estimate'], 2) : number_format(0, 2) }}</td>
                  @if($month != 1)
                    <td class="text-right">{{ isset($data3['past_month']) && !is_null($data3['past_month']) ? number_format($data3['past_month'], 2) : number_format(0, 2) }}</td>
                  @endif
                  <td class="text-right">{{ isset($data3['actual_coll']) && !is_null($data3['actual_coll']) ? number_format($data3['actual_coll'], 2) : number_format(0, 2) }}</td>
                  <td class="text-right">{{ isset($data3['total']) && !is_null($data3['total']) ? number_format($data3['total'], 2) : number_format(0, 2) }}</td>
                  <td class="text-right">{{ isset($data3['percent_coll']) && !is_null($data3['percent_coll']) ? number_format($data3['percent_coll'], 2) : number_format(0, 2) }}%</td>
                </tr>
                @if(isset($data3['subs']))
                  @foreach($data3['subs'] as $sub => $data4)
                    <tr>
                      <td>{{ $data4['sub_title'] }}</td>
                      <td></td>
                      <td class="text-right">{{ isset($data4['budget_estimate']) && !is_null($data4['budget_estimate']) ? number_format($data4['budget_estimate'], 2) : number_format(0, 2) }}</td>
                      @if($month != 1)
                        <td class="text-right">{{ isset($data4['past_month']) && !is_null($data4['past_month']) ? number_format($data4['past_month'], 2) : number_format(0, 2) }}</td>
                      @endif
                      <td class="text-right">{{ isset($data4['actual_collection']) && !is_null($data4['actual_collection']) ? number_format($data4['actual_collection'], 2) : number_format(0, 2) }}</td>
                      <td class="text-right">{{ isset($data4['total']) && !is_null($data4['total']) ? number_format($data4['total'], 2) : number_format(0, 2) }}</td>
                      <td class="text-right">{{ isset($data4['percent_coll']) && !is_null($data4['percent_coll']) ? number_format($data4['percent_coll'], 2) : number_format(0, 2) }}%</td>
                    </tr>
                  @endforeach
                @endif
              @endforeach

              @if( $data2['group_name'] == 'Tax Revenue' && $category == 1 )
                <tr class="page-break">
                  <td class="total_categ"><strong>{{ $data2['group_name'] }}</strong></td>
                  <td class="total_categ"></td>
                  <td class="total_categ text-right">{{ isset($total_per_category[$category]['tax_revenue']['budget_estimate']) && !is_null($total_per_category[$category]['tax_revenue']['budget_estimate']) ? number_format($total_per_category[$category]['tax_revenue']['budget_estimate'], 2) : number_format(0, 2) }}</td>
                  @if($month != 1)
                    <td class="total_categ text-right">{{ isset($total_per_category[$category]['tax_revenue']['past_month']) && !is_null($total_per_category[$category]['tax_revenue']['past_month']) ?  number_format($total_per_category[$category]['tax_revenue']['past_month'], 2) : number_format(0, 2) }}</td>
                  @endif
                  <td class="total_categ text-right">{{ isset($total_per_category[$category]['tax_revenue']['actual_coll']) && !is_null($total_per_category[$category]['tax_revenue']['actual_coll']) ? number_format($total_per_category[$category]['tax_revenue']['actual_coll'], 2) : number_format(0, 2) }}</td>
                  <td class="total_categ text-right">{{ isset($total_per_category[$category]['tax_revenue']['total']) && !is_null($total_per_category[$category]['tax_revenue']['total']) ? number_format($total_per_category[$category]['tax_revenue']['total'], 2) : number_format(0, 2) }}</td>
                  <td class="total_categ text-right">
                    <?php
                      $percent_coll = 0;
                      if($total_per_category[$category]['tax_revenue']['budget_estimate'] > 0)
                        $percent_coll = ($total_per_category[$category]['tax_revenue']['total'] / $total_per_category[$category]['tax_revenue']['budget_estimate'])*100;
                    ?>
                    {{ number_format($percent_coll, 2) }}%
                  </td>
                </tr>
              @endif
            @endforeach
            @if($category == 1 || $category == 2)
              @if($category == 2)
                <?php
                  $categ_gen_bts_total = [];
                  $categ_gen_bts_total['budget_estimate'] = $total_per_category[1]['budget_estimate'] + $total_per_category[2]['budget_estimate'];
                  $categ_gen_bts_total['actual_coll'] = $total_per_category[1]['actual_coll'] + $total_per_category[2]['actual_coll'];
                  $categ_gen_bts_total['total'] = $total_per_category[1]['total'] + $total_per_category[2]['total'];
                  // $categ_gen_bts_total['percent_coll'] = $total_per_category[1]['percent_coll'] + $total_per_category[2]['percent_coll'];
                  $categ_gen_bts_total['percent_coll'] = $total_per_category[1]['budget_estimate'] + $total_per_category[2]['budget_estimate'] > 0 ? (($total_per_category[1]['total'] + $total_per_category[2]['total']) / ($total_per_category[1]['budget_estimate'] + $total_per_category[2]['budget_estimate']))*100 : 0;
                  $categ_gen_bts_total['past_month'] = $total_per_category[1]['past_month'] + $total_per_category[2]['past_month'];

                  // for gen. fund sub total excluding tax revenue
                  $no_tax_revenue_total = [];
                  $no_tax_revenue_total['budget_estimate'] = $total_per_category[1]['budget_estimate'] - $total_per_category[1]['tax_revenue']['budget_estimate'];
                  // $no_tax_revenue_total['actual_coll'] = $total_per_category[1]['actual_coll'] - $total_per_category[1]['tax_revenue']['actual_coll'];
                  $no_tax_revenue_total['actual_coll'] = $categ_gen_bts_total['actual_coll'] - $total_per_category[1]['tax_revenue']['actual_coll'];
                  $no_tax_revenue_total['total'] = $total_per_category[1]['total'] - $total_per_category[1]['tax_revenue']['total'] + ($total_per_category[2]['total']);
                  // $no_tax_revenue_total['percent_coll'] = $total_per_category[1]['percent_coll'] - $total_per_category[1]['tax_revenue']['percent_coll'];
                  $no_tax_revenue_total['percent_coll'] = ($total_per_category[1]['budget_estimate'] - $total_per_category[1]['tax_revenue']['budget_estimate']) > 0 ? (($total_per_category[1]['total'] - $total_per_category[1]['tax_revenue']['total']) / ($total_per_category[1]['budget_estimate'] - $total_per_category[1]['tax_revenue']['budget_estimate']))*100 : 0;
                  // $no_tax_revenue_total['past_month'] = abs($total_per_category[1]['percent_coll'] - $total_per_category[1]['tax_revenue']['past_month']);
                  $no_tax_revenue_total['past_month'] = $categ_gen_bts_total['past_month'] - $total_per_category[1]['tax_revenue']['past_month'];
                ?> 
                <!-- SUB-TOTAL FOR NON-TAX REVENUE, GEN. FUND --> 
                <tr class="page-break">
                  <td class="total_categ"><strong>Sub TOTALS</strong></td>
                  <td class="total_categ"></td>
                  <td class="total_categ text-right">{{ number_format($no_tax_revenue_total['budget_estimate'], 2) }}</td>
                  @if($month != 1)
                    <td class="total_categ text-right">{{ number_format($no_tax_revenue_total['past_month'], 2) }}</td>
                  @endif
                  <td class="total_categ text-right">{{ number_format($no_tax_revenue_total['actual_coll'], 2) }}</td>
                  <td class="total_categ text-right">{{ number_format($no_tax_revenue_total['total'], 2) }}</td>
                  <td class="total_categ text-right">{{ number_format($no_tax_revenue_total['percent_coll'], 2) }}%</td>
                </tr>
                <tr class="page-break">
                  <td class="total_categ" colspan="2"><strong>{{ $categories_wo_bts[1] }} TOTAL</strong></td>
                  <td class="total_categ text-right">{{ number_format($categ_gen_bts_total['budget_estimate'], 2) }}</td>
                  @if($month != 1)
                    <td class="total_categ text-right">{{ number_format($categ_gen_bts_total['past_month'], 2) }}</td>
                  @endif
                  <td class="total_categ text-right">{{ number_format($categ_gen_bts_total['actual_coll'], 2) }}</td>
                  <td class="total_categ text-right">{{ number_format($categ_gen_bts_total['total'], 2) }}</td>
                  <td class="total_categ text-right">{{ number_format($categ_gen_bts_total['percent_coll'], 2) }}%</td>
                </tr>
              @endif
            @else
              <tr class="page-break">
                <td class="total_categ" colspan="2"><strong>{{ $categories_wo_bts[$category] }} TOTAL</strong></td>
                <td class="total_categ text-right">{{ number_format($total_per_category[$category]['budget_estimate'], 2) }}</td>
                @if($month != 1)
                  <td class="total_categ text-right">{{ number_format($total_per_category[$category]['past_month'], 2) }}</td>
                @endif
                <td class="total_categ text-right">{{ number_format($total_per_category[$category]['actual_coll'], 2) }}</td>
                <td class="total_categ text-right">{{ number_format($total_per_category[$category]['total'], 2) }}</td>
                <td class="total_categ text-right">
                  <?php
                    $percent_coll = 0;
                    if($total_per_category[$category]['budget_estimate'] > 0)
                      $percent_coll = ($total_per_category[$category]['total'] / $total_per_category[$category]['budget_estimate'])*100;
                  ?>
                  {{ number_format($percent_coll, 2) }}%
                </td>
              </tr>
            @endif
          @endif
          @if($category == 3)
            <tr class="page-break">
              <td class="total_categ" colspan="2"><strong>GRAND - GENERAL FUND </strong></td>
              <td class="total_categ text-right">{{ number_format($total_per_category[$category]['budget_estimate'], 2) }}</td>
              @if($month != 1)
                <td class="total_categ text-right">{{ number_format($total_per_category[$category]['past_month'], 2) }}</td>
              @endif
              <td class="total_categ text-right">{{ number_format($total_per_category[$category]['actual_coll'], 2) }}</td>
              <td class="total_categ text-right">{{ number_format($total_per_category[$category]['total'], 2) }}</td>
              <td class="total_categ text-right">
                <?php
                  $percent_coll = 0;
                  if($total_per_category[$category]['budget_estimate'] > 0)
                    $percent_coll = ($total_per_category[$category]['total'] / $total_per_category[$category]['budget_estimate'])*100;
                ?>
                {{ number_format($percent_coll, 2) }}%
              </td>
            </tr>
          @endif
        @endif
      @endforeach
    </tbody>
  </table>

</div>
<footer></footer>
</body>

</html>


