<!DOCTYPE html>
<html>
<head>

</head>
<body>

<div class="header">
        <table class="center ">
                    <tr>
                    <th colspan="6">
                        <img src="{{public_path().'/asset/images/benguet_capitol.png' }}" class="image_logo" width="100" />
                    </th>
                    </tr>
                    <tr>
                            <th>
                                Republic of the Philippines
                            </th>
                            </tr>
                              <tr>
                            <th>
                               PROVINCE OF BENGUET
                            </th>
                            </tr>
                              <tr>
                             <th>
                               La Trinidad
                            </th>
                            </tr>
                              <tr>
                             <th>
                               OFFICE OF THE PROVINCIAL TREASURER
                            </th>
                        </tr>
    </table>

<table>
    <thead>
        <tr>
            <th colspan="6" >AMusement TAX COLLECTION</th>
        </tr>
         <tr>
            <th colspan="6" >{{$base['datex']->format('F Y')}}</th>
        </tr>
    </thead>
</table>

</div>

<table>
        <thead>
            <tr>
            <th></th>
            @foreach($base['mcpal'] as $key => $value)
                <th colspan="{{count($value)*2}}">{{  $key }}</th>
            @endforeach
            <th></th>
            </tr>
            <tr>
            <th>BRGY</th>
            @foreach($base['mcpal'] as $key => $value)
                @foreach($value as $keyx => $valuex)
                <th colspan="2">{{$keyx}}</th>
                @endforeach
            @endforeach
            <th></th>
            </tr>

            <tr>
            <th>DATE</th>
            @foreach($base['mcpal'] as $key => $value)
              @foreach($value as $keyx => $valuex)
                <th>A. Tax</th>
                 <th>P. Permit</th>
                @endforeach
            @endforeach
            <th>Total</th>
            </tr>
             <tr>
                    <th>{{ $base['datex']->format('F')}}</th>
            </tr>


        </thead>

        <tbody>
                 @foreach($base['receipts'] as $key => $daymnth)
                 <?php $total[$key] = 0; ?>
                        <tr>
                         <td>{{ $key }}</td>
                            @foreach($base['mcpal'] as $mkey => $mvalue)

                                        @foreach($mvalue as $mbkey => $mbvalue)
                                                @if(isset( $daymnth[$mkey][$mbvalue] ) )
                                                            <td>{{ ($daymnth[$mkey][$mbvalue][0]->value) }}</td>
                                                             <?php $total[$key] += $daymnth[$mkey][$mbvalue][0]->value ; ?>
                                                            <?php ?>
                                                            @if(isset($base['p_tax'][$key][$mkey][$mbvalue]))
                                                                <td>{{ $base['p_tax'][$key][$mkey][$mbvalue] }}</td>
                                                                <?php $total[$key] += $base['p_tax'][$key][$mkey][$mbvalue]  ; ?>
                                                            @else
                                                                <td>0</td>
                                                            @endif
                                                 @else
                                                        <td></td>
                                                         <td></td>
                                                @endif
                                        @endforeach
                             @endforeach
                             <td>{{ $total[$key] }}</td>
                        </tr>
                  @endforeach


        </tbody>



</table>




</body>

</html>
