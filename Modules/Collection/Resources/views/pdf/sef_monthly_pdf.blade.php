  <?php
  $total_basic_current = 0 ;
         $total_basic_discount = 0 ;
         $total_basic_previous = 0 ;
         $total_basic_penalty_current = 0 ;
         $total_basic_penalty_previous = 0 ;
         $total_basic_gross = 0 ;
         $total_basic_net = 0 ;
         $gt_gross = 0 ;
         $gt_net = 0;



?>
<!DOCTYPE html>
<html>
<head>
    <title>Collections And Deposits</title>
    {{ Html::style('/bootstrap-3.3.6/css/bootstrap.min.css') }}
    <style type="text/css">
        html {
            margin-bottom: 8px;
            margin-top: 8px;
            margin-left: 15px;
            margin-right: 15px;
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
                width: 450px;
                text-align: center;
                margin: 10px auto;
        }

       .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
            border-top: 1px solid #868282;
            border-bottom: 1px solid #868282;
        }
       .table>thead:first-child>tr:first-child>th, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
            border: 1px solid #000;
        }
          .table-no-border>tbody>tr>td, .table-no-border>tbody>tr>th, .table-no-border>tfoot>tr>td, .table>tfoot>tr>th, .table-no-border>thead>tr>td, .table-no-border>thead>tr>th {
            border: none;
        }

        .table-borderedx{
                border: 1px solid #868282;
        }
         .table-border-right{
                border-right: 1px solid #868282;
         }

        .border-top{
                 border-top: 1px solid #000 !important;
        }

        .border-botts {
            border-bottom: 1px solid #000 !important;
        }

        .image_logo{
            width: 100px;
        }

        .val,.th_a{
            font-weight: normal;
            text-align: right;
            font-size: 11px !important;
        }

        .total{

        }   

        .table>td{
            font-weight: normal;
            font-size: 11px !important;
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
        REPORT OF ACCOUNTS<br />
        <strong>PROVINCIAL GOVERNMENT OF BENGUET</strong><br />
        <strong>OFFICE OF THE PROVINCIAL TREASURER</strong>
        </td>
        </tr>
    </table>

</div>


    

    SEF FROM : <strong> {{ $month->format('F , Y') }} </strong>
<table class="table table-bordered">
	<thead>
		<tr>
			<th rowspan="2" class="th_a" >Munipality</th>
			<th colspan="2" class="th_a" >CURRENT</th>
			<th colspan="1" class="th_a" >PREVIOUS</th>
            <th colspan="1" rowspan="2" class="th_a" >TOTAL CURRENT & PREVIOUS </th>
			<th colspan="2" class="th_a" >PENALTIES</th>
			<th colspan="1" rowspan="2" class="th_a" >TOTAL</th>
		</tr>
		<tr>
			<th class="th_a" >AMOUNT 50%</th>
            <th class="th_a" >DISCOUNT</th>
            <th class="th_a" >AMOUNT 50%</th>
            <th class="th_a" >CURRENT 50%</th>
            <th class="th_a" >PREVIOUS</th>

		</tr>

		<tr>
			<th colspan="8">SEF TAX 1%</th>
		</tr>

	</thead>


	<tbody>
        <?php 
                $provincial_share = [];
                $provincial_share_dscnt = [];
                $provincial_share_prev = [];
                $provincial_share_pen_crnt = [];
                $provincial_share_pen_crnt_prev = [];
                $provincial_share_total = [];

                $mcpal_share = [];
                $mcpal_share_dscnt = [];
                $mcpal_share_prev = [];
                $mcpal_share_pen_crnt = [];
                $mcpal_share_pen_crnt_prev = [];
                $mcpal_share_total = [];
                $count = 0;
        ?>
		@foreach($municipalities as $key => $value)
           @if( $value->id != 14 ) 
            <?php
                $provincial_share[$count] = $sef[$value->name][$value->id]['class_amtx']['basic_current']*.5;
                $provincial_share_dscnt[$count] = round($sef[$value->name][$value->id]['class_amtx']['basic_discount']*.5,2,PHP_ROUND_HALF_DOWN) ;
                $provincial_share_prev[$count] = round($sef[$value->name][$value->id]['class_amtx']['basic_previous']*.5,2) ;
                $provincial_share_pen_crnt[$count] = round($sef[$value->name][$value->id]['class_amtx']['basic_penalty_current']*.5,2) ;
                $provincial_share_pen_crnt_prev[$count] = round($sef[$value->name][$value->id]['class_amtx']['basic_penalty_previous']*.5,2) ;
                $provincial_share_total[$count] = $provincial_share[$count] - $provincial_share_dscnt[$count] + $provincial_share_prev[$count] + $provincial_share_pen_crnt[$count] + $provincial_share_pen_crnt_prev[$count];



                $sef_mncpl_crnt = round($sef[$value->name][$value->id]['class_amtx']['basic_current']*.5,3,PHP_ROUND_HALF_DOWN);
                $sef_mncpl_crnt_e = explode('.', $sef_mncpl_crnt);
                if(isset($sef_mncpl_crnt_e[1])){
                  $sef_mncpl_crnt_ex = $sef_mncpl_crnt_e[1];
                  if(substr($sef_mncpl_crnt_ex,2,3) >= 5){
                      $sef_mncpl_crnt = $sef_mncpl_crnt_e[0].'.'.substr($sef_mncpl_crnt_ex,0,2);
                  }
                }
                $mcpal_share[$count] = $sef_mncpl_crnt;

                $mcpal_share_dscnt[$count] = round($sef[$value->name][$value->id]['class_amtx']['basic_discount'] *.5,2,PHP_ROUND_HALF_UP);
                $mcpal_share_prev[$count] = round($sef[$value->name][$value->id]['class_amtx']['basic_previous'] *.5,2,PHP_ROUND_HALF_DOWN);

                $mcpal_share_pen_crnt[$count] = round($sef[$value->name][$value->id]['class_amtx']['basic_penalty_current'] *.5,2,PHP_ROUND_HALF_DOWN);
                $mcpal_share_pen_crnt_prev[$count] = round($sef[$value->name][$value->id]['class_amtx']['basic_penalty_previous'] *.5,2,PHP_ROUND_HALF_DOWN);
                $mcpal_share_total[$count] = $mcpal_share[$count] - $mcpal_share_dscnt[$count] + $mcpal_share_prev[$count] + $mcpal_share_pen_crnt[$count] + $mcpal_share_pen_crnt_prev[$count];
            ?>

			<tr >
					<td colspan="1"  class="val" >{{$value->name}}</td>
                    <td colspan="1"  class="val" >{{ number_format($mcpal_share[$count],2)}}</td>
                    <td colspan="1"  class="val" >{{ number_format($mcpal_share_dscnt[$count],2)}}</td>
                    <td colspan="1"  class="val" >{{ number_format($mcpal_share_prev[$count],2)}}</td>
                    <td colspan="1"  class="val" >{{ number_format($mcpal_share[$count]- $mcpal_share_dscnt[$count]+ $mcpal_share_prev[$count],2)}}</td>

                    <td colspan="1"  class="val" >{{ number_format($mcpal_share_pen_crnt[$count],2)}}</td>
                    <td colspan="1"  class="val" >{{ number_format($mcpal_share_pen_crnt_prev[$count],2)}}</td>
                    <td colspan="1"  class="val" >{{ number_format($mcpal_share_total[$count],2)}}</td>
                    
			</tr>
            <?php $count++; ?>
            @endif
		@endforeach
        <tr class="total" >
                    <td colspan="1" class="val" >TOTAL</td>
                    <td colspan="1" class="val" >{{ number_format(array_sum($mcpal_share),2)}}</td>
                    <td colspan="1" class="val" >{{ number_format(array_sum($mcpal_share_dscnt),2)}}</td>
                    <td colspan="1" class="val" >{{ number_format(array_sum($mcpal_share_prev),2)}}</td>
                    <td colspan="1" class="val" >{{ number_format(array_sum($mcpal_share) - array_sum($mcpal_share_dscnt) + array_sum($mcpal_share_prev),2)}}</td>

                    <td colspan="1" class="val" >{{ number_format(array_sum($mcpal_share_pen_crnt),2)}}</td>
                    <td colspan="1" class="val" >{{ number_format(array_sum($mcpal_share_pen_crnt_prev),2)}}</td>
                    <td colspan="1" class="val" >{{ number_format(array_sum($mcpal_share_total),2)}}</td>
                    
            </tr>
	</tbody>
</table>

<table class="table table-bordered">
    <thead>
        <tr>
            <th rowspan="2" class="th_a" >Provincial</th>
            <th colspan="2" class="th_a" >CURRENT</th>
            <th colspan="1" class="th_a" >PREVIOUS</th>
            <th colspan="1" rowspan="2" class="th_a" >TOTAL CURRENT & PREVIOUS </th>
            <th colspan="2" class="th_a" >PENALTIES</th>
            <th colspan="1" rowspan="2" class="th_a" >TOTAL</th>
        </tr>
        <tr>
          <th class="th_a" >AMOUNT 50%</th>
            <th class="th_a" >DISCOUNT</th>
            <th class="th_a" >AMOUNT 50%</th>
            <th class="th_a" >CURRENT 50%</th>
            <th class="th_a" >PREVIOUS</th>

        </tr>

        <tr>
            <th colspan="8">SEF TAX 1%</th>
        </tr>

    </thead>
    <tbody>
        <tr >
                    <td colspan="1"  class="val" >Provicial Share</td>
                    <td colspan="1"  class="val">{{ number_format(array_sum($provincial_share),2)}}</td>
                    <td colspan="1"  class="val" >{{ number_format(array_sum($provincial_share_dscnt),2)}}</td>
                    <td colspan="1"  class="val" >{{ number_format(array_sum($provincial_share_prev),2)}}</td>
                    <td colspan="1"  class="val" >{{ number_format(array_sum($provincial_share) - array_sum($provincial_share_dscnt) + array_sum($provincial_share_prev),2)}}</td>


                    <td colspan="1"  class="val" >{{ number_format(array_sum($provincial_share_pen_crnt),2)}}</td>
                    <td colspan="1"  class="val" >{{ number_format(array_sum($provincial_share_pen_crnt_prev),2)}}</td>
                    <td colspan="1"  class="val" >{{ number_format(array_sum($provincial_share_total),2)}}</td>

                    
            </tr>

    </tbody>
</table>


</body>
</html>
