<?php
        $bac_infra_ids = [];
        $bac_infra__names = [];
         $valuex = [];
            foreach ($base['shares']['bac_infra'] as $key => $bac_infra) {
               
                foreach ($bac_infra as $keyx => $details) {
                   

                    if(!in_array( $keyx , $bac_infra_ids)){
                        $bac_infra_ids[$keyx] = $keyx;
                        $bac_infra__names[$keyx] =  $details['name'];
                        $valuex[$key][$keyx]= $details['value'];
                    }else{
                          if(!isset($valuex[$key][$keyx]))  $valuex[$key][$keyx] = $details['value'];
                          else $valuex[$key][$keyx] += $details['value'];
                    }
                }
               
            }
            ksort($bac_infra_ids);
            ksort($bac_infra__names);
         $bacc_infra = [];
            foreach ($valuex as $key => $vvalue) {
                    foreach ($bac_infra_ids as $zkeyd => $zvalue) {
                        if(!isset($vvalue[$zvalue])){
                            $bacc_infra[$key][$zvalue] = 0;
                        }else{
                             $bacc_infra[$key][$zvalue] = $vvalue[$zvalue];
                        }
                    }  
            }

            $Municipal_brgy = [];
            foreach ($base['shares']['municipalities'] as $mncplk => $mncpl) {
                if($mncpl->id != 14){
                    $Municipal_brgy[$mncpl->name] = [];
                    foreach ($base['shares']['dates'] as $key => $date) {
                        if( $date['D'] != 'Sun'  && $date['D'] != 'Sat' ){
                            foreach ($base['shares']['shares_mncpal'][$mncpl->name][$date['j']] as $key => $value) {
                                if(!array_key_exists($key, $Municipal_brgy[$mncpl->name])){
                                    $Municipal_brgy[$mncpl->name][$key] = [];
                                    if(count($value) > 0){
                                        foreach ($value as $keyt => $valuet) {
                                            if(!in_array($valuet['name'], $Municipal_brgy[$mncpl->name][$key])){
                                                $Municipal_brgy[$mncpl->name][$key][$keyt] = $valuet['name'];
                                            } 
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
// dd( $Municipal_brgy );
// dd( $base['shares']['shares_mncpal']);

$Municipal_share_brgy = [];
foreach ($base['shares']['municipalities'] as $mncplk => $mncpl) {
    if($mncpl->id != 14){
        $Municipal_share_brgy[$mncpl->name] = [];
        foreach ($base['shares']['dates'] as $keys => $date) {
            if( $date['D'] != 'Sun'  && $date['D'] != 'Sat' ){
                $Municipal_share_brgy[$mncpl->name][$date['j']] = [];
                foreach ($Municipal_brgy[$mncpl->name] as $brgy => $bvalue) {
                    if(!array_key_exists($brgy,$Municipal_share_brgy[$mncpl->name][$date['j']])){
                        $Municipal_share_brgy[$mncpl->name][$date['j']][$brgy] = [];

                        foreach ($Municipal_brgy[$mncpl->name][$brgy] as $ccc => $cvalue) {
                            if(isset( $base['shares']['shares_mncpal'][$mncpl->name][$date['j']][$brgy][$ccc] ))
                                $Municipal_share_brgy[$mncpl->name][$date['j']][$brgy][$ccc] = $base['shares']['shares_mncpal'][$mncpl->name][$date['j']][$brgy][$ccc];
                            else
                                $Municipal_share_brgy[$mncpl->name][$date['j']][$brgy][$ccc] = [];
                        }
                    }
                }
            }
        }
    }
}

// dd( $base['shares']['shares_mncpal']);

            
?>

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

        .totals{
            border:3px solid #000 !important;
            font-weight: bold;
            font-size: 15px;
        }

        .text-right{
            text-align: right;
            padding-right: 8px !important;
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

        tr.center>th{
        	text-align: center;
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
<div class="footer">
    Page <span class="pagenum"></span>
</div>


 <div class="col-sm-12" style="top: 135px;">
 <h4>BAC INFRA</h4>
 <table class="table table-condensed table-bordered">
    <thead>
            <tr class="center">
                <th>TYPE</th>
                <?php
                        foreach ($base['shares']['dates'] as $key => $date) {
                            if( $date['D'] != 'Sun'  && $date['D'] != 'Sat' ){
                                echo '<th>'.$date['j'].'</th>';
                            }
                        }
                ?>
                <th>TOTAL</th>
            </tr>
    </thead>

    <tbody>
    <?php


            $totalxx = [];
            foreach ($bac_infra__names as $key => $value) {
                $totalxx[$key] = 0;
                echo '<tr>';
                    echo '<td><strong>'.$value.'</strong></td>';
                    foreach ($base['shares']['dates'] as $dkey => $date) {
                        if( $date['D'] != 'Sun'  && $date['D'] != 'Sat' ){
                             $totalxx[$key] += $bacc_infra[$date['j']][$key];
                            echo '<td class="text-right">'.($bacc_infra[$date['j']][$key]).'</td>';
                        }
                    }
                    echo '<td>'.$totalxx[$key].'</td>';
                echo '</tr>';
            }
    ?>

        
    </tbody>
 </table>
<br /><br /><br />

<h4>Municipal Shares</h4>
<table class="table table-condensed table-bordered">
    <thead>
            <tr class="center">
                <th>Municipality</th>
                <?php
                        foreach ($base['shares']['dates'] as $key => $date) {
                            if( $date['D'] != 'Sun'  && $date['D'] != 'Sat' ){
                                echo '<th>'.$date['j'].'</th>';
                            }
                        }
                ?>
                <th>TOTAL</th>
            </tr>
    </thead>

    <tbody>
        <?php
        $total_r = [];
        $total = 0;
        foreach ($base['shares']['municipalities'] as $mncplk => $mncpl) {
            if($mncpl->id != 14){
                echo '<tr>';
                    echo '<td colspan="'.(count($base['shares']['dates'])+1).'" style="font-size:19px;" >'.$mncpl->name.'</td>';
                echo '<tr>';
                
                foreach ($Municipal_brgy[$mncpl->name] as $mkey => $mvalue) {
                        echo '<tr>';
                        if($mkey == $mncpl->name){
                            echo '<td colspan="'.(count($base['shares']['dates'])+1).'" ><strong>&nbsp;&nbsp;'.$mkey.'</strong></td>';
                        }else{
                            echo '<td colspan="'.(count($base['shares']['dates'])+1).'" ><strong>&nbsp;&nbsp;&nbsp;&nbsp;'.$mkey.'</strong></td>';
                        }
                        echo '</tr>';
                            foreach ($mvalue as $key => $value) {
                                $total_c[$mncpl->name][$mkey][$value] = 0;
                                    echo '<tr>';
                                        echo '<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$value.'</td>';
                                        foreach ($base['shares']['dates'] as $dkey => $date) {
                                            if( $date['D'] != 'Sun'  && $date['D'] != 'Sat' ){

                                                   if(count($Municipal_share_brgy[$mncpl->name][$date['j']][$mkey][$key]) > 0){
                                                        echo '<td class="text-right">'.($Municipal_share_brgy[$mncpl->name][$date['j']][$mkey][$key]['value']).'</td>';
                                                         $total_c[$mncpl->name][$mkey][$value] += $Municipal_share_brgy[$mncpl->name][$date['j']][$mkey][$key]['value'];
                                                         $total_r[$date['j']][] = $Municipal_share_brgy[$mncpl->name][$date['j']][$mkey][$key]['value'];
                                                   }else{
                                                        echo '<td></td>';
                                                   }
                                            }
                                        }
                                        echo '<td class="text-right totals">'.($total_c[$mncpl->name][$mkey][$value])."</td>";
                                         $total += $total_c[$mncpl->name][$mkey][$value];
                                    echo '</tr>';
                            }

                }
            }
        }
        ksort($total_r);
        echo "<tr>";
                 echo '<td class="totals">Total</td>';
                foreach ($base['shares']['dates'] as $dkey => $date) {
                    if( $date['D'] != 'Sun'  && $date['D'] != 'Sat' ){
                        if(isset($total_r[$date['j']]))
                            echo '<td class="text-right totals" >'.array_sum($total_r[$date['j']]).'</td>';
                        else
                            echo '<td class="totals"></td>';
                    }
                }

                echo '<td class="text-right totals">'.($total)."</td>";

        echo "</tr>";
        ?>
        
    </tbody>
 </table>

</div>
</body>

</html>