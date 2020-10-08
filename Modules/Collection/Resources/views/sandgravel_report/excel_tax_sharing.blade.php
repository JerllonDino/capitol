<center><strong>SAND AND GRAVEL TAXES/PENALTIES SHARING OF COLLECTIONS <br /><br /> <u> {{ strtoupper($datex->format('F')) }} 01-{{ strtoupper($datex->endOfMonth()->format('d')) }},  {{$year}}</u></strong></center><br />

<table>

    <thead>
        <tr>
            <th>MUNICIPALITY</th>
            <th>Brgy</th>
            <th>PROVINCIAL SHARE</th>
            <th>MUNICIPAL SHARE</th>
            <th>BRGY SHARE</th>
            <th>TOTALS</th>
        </tr>
    </thead>

    <tbody>
        <?php
            $totals_mpb = [];
            $totals_prv = $totals_mun  = $totals_bgy =   $totals = 0;
        ?>
        @foreach($municipality as $mun)
            <?php
                $totals_mpb[$mun['name']] = ['prv'=>0,'mun'=>0,'brgy'=>0, 'ttal'=>0 ];

            ?>
                @if( $mun['id'] != 14)
                    <tr>
                        <td>{{strtoupper($mun['name'])}}</td>
                        <td></td>
                        <td>@if($landtaxsharing[$mun['name']]['provincial_value']) {{number_format($landtaxsharing[$mun['name']]['provincial_value'],2)}} <?php $totals_mpb[$mun['name']]['prv'] = $landtaxsharing[$mun['name']]['provincial_value']; $totals_prv += $landtaxsharing[$mun['name']]['provincial_value'];   ?> @else - @endif</td>
                        <td>@if($landtaxsharing[$mun['name']]['value']) {{number_format($landtaxsharing[$mun['name']]['value'],2)}}  <?php $totals_mpb[$mun['name']]['mun'] = $landtaxsharing[$mun['name']]['value']; $totals_mun += $landtaxsharing[$mun['name']]['value']; ?> @else - @endif</td>
                        <td>  @if(isset($landtaxsharing[$mun['name']]['brgy'])) @else - @endif </td>
                        <td>@if($landtaxsharing[$mun['name']]['provincial_value'])  {{ number_format($totals_mpb[$mun['name']]['mun'] + $totals_mpb[$mun['name']]['prv'] , 2 ) }} @else - @endif</td>
                    </tr>
                    @php($totals += $landtaxsharing[$mun['name']]['value'])
                        @if(isset($landtaxsharing[$mun['name']]['brgy']))
                                @foreach($landtaxsharing[$mun['name']]['brgy'] as $key => $brgy)
                                    <tr>
                                        <td></td>
                                        <td>{{strtoupper($key)}}</td>
                                        <td></td>
                                        <td></td>
                                        <td>{{number_format($brgy,2)}}</td>
                                        <td>{{number_format($brgy,2)}}</td>
                                    </tr>
                                     <?php $totals_mpb[$mun['name']]['brgy'] += $brgy; ?>
                                    @php($totals += $brgy)
                                @endforeach
                        @endif

                    @if($landtaxsharing[$mun['name']]['provincial_value'])
                    <?php  $totals_mpb[$mun['name']]['ttal'] = $totals_mpb[$mun['name']]['mun'] + $totals_mpb[$mun['name']]['prv'] + $totals_mpb[$mun['name']]['brgy']; $totals_bgy += $totals_mpb[$mun['name']]['brgy'];;  ?>
                        <tr>
                            <td colspan="5"><strong> Sub - Total {{ $mun['name'] }} </strong> </td>
                            <td><strong>  {{ number_format( $totals_mpb[$mun['name']]['ttal'], 2 ) }} </strong>  </td>
                        </tr>
                    @endif
                @endif
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <th colspan="2">TOTAL</th>
            <th>{{number_format($totals_prv,2)}}</th>
            <th>{{number_format($totals_mun,2)}}</th>
            <th>{{number_format($totals_bgy,2)}}</th>
            <th>{{number_format($totals_prv + $totals_mun + $totals_bgy,2)}}</th>
        </tr>
        <tr>
            <th colspan="6"></th>
        </tr>

    </tfoot>
</table>

<br />

<table>
    <tbody>
        <tr>
            <td>Prepared by:<br><br></td>
            <td>&nbsp;</td>
        </tr>
         <tr>
             <td></td>

             @php
                $STR = strtolower($officer->value);
                $STR = strtoupper($STR);
             @endphp
            <td>{{ $STR }}</td>
        </tr>
        <tr>
             <td></td>
            <td>{{ $position->value }}</td>
        </tr>


    </tbody>
</table>

</body>
</html>


