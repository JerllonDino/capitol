<!DOCTYPE html>
<html>
<head>
    <title>MONTHLY PROVINCIAL REPORT</title>
    {{ Html::style('/bootstrap-3.3.6/css/bootstrap.min.css') }}
    <style type="text/css">
        html {
            margin-bottom: 8px;
            margin-top: 20px;
            margin-left: 20px;
            margin-right: 20px;
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
        .table-condensed>tbody>tr>th, .table-condensed>thead>tr>th{
            text-align: center;
        }
        .table {
          border-color: #000 !important;
        }
        /*.table>tbody>tr>td:nth-child(1n+2) {
          text-align: right;
        }*/
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
  
?>

<div> <!-- content div -->
  <?php
    $mnths_arr = [ 1 => "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
  ?>
  <h4 style="text-align: center;"><strong>SUMMARY OF RPT PROVINCIAL SHARE FOR THE MONTH OF {{ strtoupper($mnths_arr[$month]) }}</strong></h4>

  <div>
    <h5><strong>LANDTAX COLLECTION for BASIC</strong></h5>
    <table class="table table-bordered table-condensed" border="1">
      <thead>
        <tr>
          <th rowspan="2">MUNICIPALITY</th>
          <th colspan="2">ADVANCE</th>
          <th colspan="2">CURRENT</th>
          <th>{{ $preceeding }}</th>
          <th>{{ $prior_start }}-1992</th>
          <th>1991 & below</th>
          <th colspan="4">PENALTIES</th>
          <th rowspan="2">TOTAL</th>
        </tr>
        <tr>
          <th>Amount</th>
          <th>Discount</th>
          <th>Amount</th>
          <th>Discount</th>
          <th>Amount</th>
          <th>Amount</th>
          <th>Amount</th>
          <th>Current</th>
          <th>{{ $preceeding }}</th>
          <th>{{ $prior_start }}-1992</th>
          <th>1991 & below</th>
        </tr>
      </thead>
      <tbody>     
        @foreach($munics as $mun)
          <?php 
            if($mun->id == 14)
              continue; 
          ?>
          <tr>
            <td>{{ $mun->name }}</td>
            <td style="text-align: right;">{{ number_format($rpt_basic_adv[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_basic_adv_discount[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_basic[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_basic_discount[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_basic_prev[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_basic_1992[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_basic_1991[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_basic_penalty[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_basic_prev_penalty[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_basic_1992_penalty[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_basic_1991_penalty[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_basic_total[$mun->id], 2) }}</td>
          </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td>SUB TOTAL BASIC</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_basic_adv), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_basic_adv_discount), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_basic), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_basic_discount), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_basic_prev), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_basic_1992), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_basic_1991), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_basic_penalty), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_basic_prev_penalty), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_basic_1992_penalty), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_basic_1991_penalty), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_basic_total), 2) }}</td>
        </tr>
      </tfoot>
    </table>
  </div>
  <div style="page-break-after: always;"></div>
  <div>
    <h5><strong>CASH COLLECTION for BASIC</strong></h5>
    <table class="table table-bordered table-condensed" border="1">
      <thead>
        <tr>
          <th rowspan="2">MUNICIPALITY</th>
          <th colspan="2">ADVANCE</th>
          <th colspan="2">CURRENT</th>
          <th>{{ $preceeding }}</th>
          <th>{{ $prior_start }}-1992</th>
          <th>1991 & below</th>
          <th colspan="4">PENALTIES</th>
          <th rowspan="2">TOTAL</th>
        </tr>
        <tr>
          <th>Amount</th>
          <th>Discount</th>
          <th>Amount</th>
          <th>Discount</th>
          <th>Amount</th>
          <th>Amount</th>
          <th>Amount</th>
          <th>Current</th>
          <th>{{ $preceeding }}</th>
          <th>{{ $prior_start }}-1992</th>
          <th>1991 & below</th>
        </tr>
      </thead>
      <tbody>
        @foreach($munics as $mun)
          <?php 
            if($mun->id == 14)
              continue; 
          ?>
          <tr>
            <td>{{ $mun->name }}</td>
            <td style="text-align: right;">{{ number_format($rpt_basic_adv_cd[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_basic_adv_discount_cd[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_basic_cd[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_basic_discount_cd[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_basic_prev_cd[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_basic_1992_cd[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_basic_1991_cd[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_basic_penalty_cd[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_basic_prev_penalty_cd[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_basic_1992_penalty_cd[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_basic_1991_penalty_cd[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_basic_total_cd[$mun->id], 2) }}</td>
          </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td>TOTAL BASIC(LANDTAX AND CASH COLLECTION)</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_basic_adv) + array_sum($rpt_basic_adv_cd), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_basic_adv_discount) + array_sum($rpt_basic_adv_discount_cd), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_basic) + array_sum($rpt_basic_cd), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_basic_discount) + array_sum($rpt_basic_discount_cd), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_basic_prev) + array_sum($rpt_basic_prev_cd), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_basic_1992) + array_sum($rpt_basic_1992_cd), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_basic_1991) + array_sum($rpt_basic_1991_cd), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_basic_penalty) + array_sum($rpt_basic_penalty_cd), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_basic_prev_penalty) + array_sum($rpt_basic_prev_penalty_cd), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_basic_1992_penalty) + array_sum($rpt_basic_1992_penalty_cd), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_basic_1991_penalty) + array_sum($rpt_basic_1991_penalty_cd), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_basic_total) + array_sum($rpt_basic_total_cd), 2) }}</td>
        </tr>
      </tfoot>
    </table>
  </div>
  <div style="page-break-after: always;"></div>
  <div>
    <h5><strong>LANDTAX COLLECTION for SEF</strong></h5>
    <table class="table table-bordered table-condensed" border="1">
      <thead>
        <tr>
          <th rowspan="2">MUNICIPALITY</th>
          <th colspan="2">ADVANCE</th>
          <th colspan="2">CURRENT</th>
          <th>{{ $preceeding }}</th>
          <th>{{ $prior_start }}-1992</th>
          <th>1991 & below</th>
          <th colspan="4">PENALTIES</th>
          <th rowspan="2">TOTAL</th>
        </tr>
        <tr>
          <th>Amount</th>
          <th>Discount</th>
          <th>Amount</th>
          <th>Discount</th>
          <th>Amount</th>
          <th>Amount</th>
          <th>Amount</th>
          <th>Current</th>
          <th>{{ $preceeding }}</th>
          <th>{{ $prior_start }}-1992</th>
          <th>1991 & below</th>
        </tr>
      </thead>
      <tbody>
        @foreach($munics as $mun)
          <?php 
            if($mun->id == 14)
              continue; 
          ?>
          <tr>
            <td>{{ $mun->name }}</td>
            <td style="text-align: right;">{{ number_format($rpt_sef_adv[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_sef_adv_discount[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_sef[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_sef_discount[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_sef_prev[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_sef_1992[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_sef_1991[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_sef_penalty[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_sef_prev_penalty[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_sef_1992_penalty[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_sef_1991_penalty[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_sef_total[$mun->id], 2) }}</td>
          </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td>SUB TOTAL SEF</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_sef_adv), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_sef_adv_discount), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_sef), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_sef_discount), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_sef_prev), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_sef_1992), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_sef_1991), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_sef_penalty), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_sef_prev_penalty), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_sef_1992_penalty), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_sef_1991_penalty), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_sef_total), 2) }}</td>
        </tr>
      </tfoot>
    </table>
  </div>
  <div style="page-break-after: always;"></div>
  <div>
    <h5><strong>CASH COLLECTION for SEF</strong></h5>
    <table class="table table-bordered table-condensed" border="1">
      <thead>
        <tr>
          <th rowspan="2">MUNICIPALITY</th>
          <th colspan="2">ADVANCE</th>
          <th colspan="2">CURRENT</th>
          <th>{{ $preceeding }}</th>
          <th>{{ $prior_start }}-1992</th>
          <th>1991 & below</th>
          <th colspan="4">PENALTIES</th>
          <th rowspan="2">TOTAL</th>
        </tr>
        <tr>
          <th>Amount</th>
          <th>Discount</th>
          <th>Amount</th>
          <th>Discount</th>
          <th>Amount</th>
          <th>Amount</th>
          <th>Amount</th>
          <th>Current</th>
          <th>{{ $preceeding }}</th>
          <th>{{ $prior_start }}-1992</th>
          <th>1991 & below</th>
        </tr>
      </thead>
      <tbody>
        @foreach($munics as $mun)
          <?php 
            if($mun->id == 14)
              continue; 
          ?>
          <tr>
            <td>{{ $mun->name }}</td>
            <td style="text-align: right;">{{ number_format($rpt_sef_adv_cd[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_sef_adv_discount_cd[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_sef_cd[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_sef_discount_cd[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_sef_prev_cd[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_sef_1992_cd[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_sef_1991_cd[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_sef_penalty_cd[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_sef_prev_penalty_cd[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_sef_1992_penalty_cd[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_sef_1991_penalty_cd[$mun->id], 2) }}</td>
            <td style="text-align: right;">{{ number_format($rpt_sef_total_cd[$mun->id], 2) }}</td>
          </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td>TOTAL SEF (LANDTAX AND CASH COLLECTION)</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_sef_adv) + array_sum($rpt_sef_adv_cd), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_sef_adv_discount) + array_sum($rpt_sef_adv_discount_cd), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_sef) + array_sum($rpt_sef_cd), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_sef_discount) + array_sum($rpt_sef_discount_cd), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_sef_prev) + array_sum($rpt_sef_prev_cd), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_sef_1992) + array_sum($rpt_sef_1992_cd), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_sef_1991) + array_sum($rpt_sef_1991_cd), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_sef_penalty) + array_sum($rpt_sef_penalty_cd), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_sef_prev_penalty) + array_sum($rpt_sef_prev_penalty_cd), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_sef_1992_penalty) + array_sum($rpt_sef_1992_penalty_cd), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_sef_1991_penalty) + array_sum($rpt_sef_1991_penalty_cd), 2) }}</td>
          <td style="text-align: right;">{{ number_format(array_sum($rpt_sef_total) + array_sum($rpt_sef_total_cd), 2) }}</td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
<footer></footer>
</body>

</html>


