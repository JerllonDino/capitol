<!DOCTYPE html>
<html>
<head>
    <title>Certificate - Real Property Tax</title>
    <style>
        html,body{
            /*margin-right:1.5in;
            margin-left:1.5in;*/
            margin-right:.5in;
            margin-left:1in;
            margin-top: 1in;
        }
         body {
            font-family: arial, "sans-serif";
            margin: 0px;
            font-size: 14px;
        }
        #items {
            width: 80%;
            margin-right: auto;
            margin-left: auto;
        }
        .underline {
            border-bottom: 1px solid #000000;
        }
        .header-container {
            width: 80%;
            text-align: center;
        }
        .header {
            width: 95%;
            display: block;
            font-weight: bold;
        }
        #logo {
            height: 80px;
            float: left;
            margin-left: 100px;
        }
        #header-dt {
            float: right;
            text-align: center;
            margin-right:20px;
        }
        #cert {
            margin-top: 20px;
            margin-bottom: 30px;
            font-size: 18px;
            text-align: center;
            width: 100%;
        }
        .signatories{
            text-align: center;
            margin-top: 0cm;
            margin-bottom: 50px;
        }
        #cert{
            margin-top: 50px;
            font-size: 18px;
            text-align: center;
            width: 100%;
        }
        .underline {
            border-bottom: 1px solid #000000;
        }
        .blk {
            margin-top: 15px;
            margin-right: 20px;
            text-align: justify;
        }
        .entity {
            text-decoration: underline;
            font-weight: bold;
        }
        #detail {
            width: 100%;
        }
        .ctr {
            text-align: center;
        }
        #items {
            width: 100%;
        }
          #items>thead>tr>th {
            font-size: 13px;
            text-align :center ;
        }
         #items>tbody>tr>td {
            font-size: 13px;
        }
        .val {
            text-align: right;
        }
        hr {
            margin-top:5px;
        }
        #ft {
            margin-top: 100px;

        }
        #conditions {
            top:23.8cm;
            margin-bottom: 0px;
            width: 100%;
            font-size: 10px;
            position: fixed;
        }
        #conditions span {
            font-size: 12px;
        }
        .indent {
            padding-left: 30px;
        }
        #lines_business {
            width: 1000px;
            text-align: center;
        }
        #center_lines_business {
            font-size: 18px;
            text-align: center;
            width: 100%;
            margin-bottom: 0.25cm;
        }
        .add-padding>td{
            padding-top: 5px;
        }

        .black{
            background: #000;
            width: 20px;
            height: 20px;
            float:left;
        }
        .image_logo{
            width: 80px;
        }
        .center {
            /*width: 450px;*/
            width: 550px;
            text-align: center;
                
        }
        .other_dtls{
            width:130px;
            border-bottom:1px solid black;
            text-align:center;
            font-size:13px;
        }
        .underline_dtl{
            display:inline-block;
            border-bottom:1px solid black;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body >
    <table class="center"  >
    <tr>
        <td width="10" >
            <img src="{{asset('asset/images/benguet_capitol.png')}}" class="image_logo" />
        </td>
        <td style="font-size: 14px;" width="150">
        Republic of the Philippines<br />
        PROVINCE OF BENGUET<br />
        La Trinidad<br />
        <strong>OFFICE OF THE PROVINCIAL TREASURER</strong>
        </td>
        </tr>
    </table>

    <div id="cert">
        <b class="indent">CERTIFICATION</b>
    </div>
    <B>TO WHOM THIS MAY CONCERN:</B><BR>
    <!-- OLD TO NEW -->
    <?php
        
        $recent_year = 0;
        $latest_year = 0;

        foreach($receipt->F56Detailmny as $key => $data){
            $period_covered = $data->period_covered;        
            if(strpos("-",$period_covered) == 0){
                    if($recent_year == 0){  
                        $recent_year = $period_covered;
                        $latest_year = $period_covered;
                    }
                    if($period_covered < $recent_year){
                            $recent_year = $period_covered;
                    }
                    if($period_covered > $latest_year){
                            $latest_year = $period_covered;
                    }
                }else{
                    $data = explode('-',$period_covered);

                    foreach($data as $d){
                        if($d < $recent_year){
                                $recent_year = $d;
                        }
                        if($d > $latest_year){
                                $latest_year = $d;
                        }
                    }
                }
        }
        $period_covered =  ($recent_year == $latest_year) ? $latest_year.' only ' : (($recent_year > 0) ? $recent_year.'-'.$latest_year : $latest_year);

    ?>
    <!-- OLD TO NEW -->
    <div class="blk">
        <span class="indent">
            THIS IS TO CERTIFY that &nbsp; <u><b>{{$receipt->customer->name}}</b></u>&nbsp; of &nbsp;
            @if(!is_null($receipt->customer->address) && $receipt->customer->address != "")
                <b><u>{{ $receipt->customer->address }}</u></b>
            @else
                <span class="underline_dtl" style="width:150px;font-size: 14px"></span>
            @endif

            has paid the real property tax/es for 
            the year/s  <u><b>{{ $period_covered }}</b></u> on the property/ies under Title No. 

            @if(!is_null($tdrp_title) && $tdrp_title != "")
                <?php $title_arr = []; ?>
                @foreach($tdrp_title as $val)
                    @foreach($val as $title)
                        @if(!is_null($title->cert_title) && $title->cert_title != "") 
                            <!-- <b><u>{{-- $title->cert_title --}},</u></b> -->
                            <?php
                                array_push($title_arr, $title->cert_title);
                            ?>
                        @else 
                            <?php break; ?>
                        @endif 
                    @endforeach
                @endforeach

                @if(count($title_arr) > 0)
                    <?php $title_arr = array_unique($title_arr); ?>
                    @foreach($title_arr as $title)
                        <b><u>{{ $title }},</u></b>
                    @endforeach
                @endif
            @else
                <span class="underline_dtl" style="width:150px;font-size: 14px"></span>,
            @endif
            Tax Declaration No.
            <?php $arp_arr = []; ?>
            @foreach($receipt->F56Detailmny as $key => $data)
                    <?php
                        $tdarp = explode('-',$data->TDARPX->tdarpno);
                        array_push($arp_arr, $data->TDARPX->tdarpno);
                    ?>
                    <!-- <div style="display: inline;"> -->
                        <!-- <u><b>{{-- $data->TDARPX->tdarpno --}}&nbsp;</b></u> -->
                    <!-- </div> -->

                    <?php
                        $barangay = $data->TDARPX->barangay_name->name;
                        $municipality = $receipt->municipality->name;
                        $owner = $receipt->F56Detail->owner_name;
                    ?>
            @endforeach

            @if(count($arp_arr) > 0)
                <?php $arp_arr = array_unique($arp_arr); ?>
                @foreach($arp_arr as $arp)
                    <u><b>{{ $arp }}&nbsp;</b></u>
                @endforeach
            @endif

            under the name of &nbsp;<u><b>{{$owner}}</b></u>
             located/situated at
             &nbsp;<u><b>{{$barangay.', '.$municipality}}, </b></u>&nbsp;
             Benguet,  

            as per OR No. &nbsp;<u><b>{{$receipt->serial_no}}</b></u> &nbsp;dated
           &nbsp; <u><b>{{\Carbon\Carbon::parse($receipt->date_of_entry)->format('F j, Y')}} </b></u> issued by this Office.
        </span><br><br>

        <span class="indent">
            This certification is issued upon the request of &nbsp;<u><b>{{$receipt->customer->name}}</b></u> &nbsp;for legal purposes.<br><br>
        </span>

        <span style="margin-left:80px">         
            @if(is_null($f51_OR))
                Issued this <u>__________________</u> day of <u>__________________</u> at La Trinidad, Benguet.
            @else
                Issued this <u>{{\Carbon\Carbon::parse($f51_OR->date_of_entry)->format('jS')}} </u> day of <u>{{\Carbon\Carbon::parse($f51_OR->date_of_entry)->format('F Y')}} </u> at La Trinidad, Benguet.
            @endif
        </span>

    </div>

<br />

    <table class="signatories">
        <tr>
            <!-- width: 300px; -->
            <td style="width: 400px;">&nbsp;</td>
            <td width="125">
            <br />
              <br />
             <strong>{{$acctble_officer_name->value}}</strong>
            </td>
        </tr>
        <tr>
            <td style="width: 500px;"></td>
            <td colspan="2">{{$acctble_officer_position->value}}</td>
        </tr>
        <br /> <br />
    </table>

    <table>
        <tr>
            <td style="font-size: 13px">Cert. Fee:</td>
            <td class="other_dtls">&nbsp; {{ $f51_OR ? $f51_OR['value']:'' }}</td>
        </tr>
        <tr>
            <td style="font-size: 13px">O.R. No.:</td>
            <td class="other_dtls">&nbsp; {{ $f51_OR ? $f51_OR['serial_no']:'' }}</td><!-- base from form 51 -->
        </tr>
        <tr>
            <td style="font-size: 13px">Dated:</td>
            <td class="other_dtls">{{\Carbon\Carbon::parse($receipt->date_of_entry)->format('F j, Y')}}</td>
        </tr>
    </table>
   
</body>
</html>
