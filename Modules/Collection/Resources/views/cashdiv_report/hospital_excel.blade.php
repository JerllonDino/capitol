<table>
    <thead>
        <tr>
                <th rowspan="2">DATE</th>
                <th rowspan="2">HOSPITALS</th>
                <th rowspan="2">PERIOD COVERED</th>
                <th rowspan="1">DRUGS and MEDICINES</th>
                <th rowspan="1">MED/LAB/DEN</th>
                <th rowspan="2">HOSPITAL FEES</th>
                <th rowspan="2">OTHER SERVICES </th>
                <th rowspan="2">AFFILIATION </th>
                <th rowspan="2">TOTALS </th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th>COST</th>
            <th>Xray/Suplies</th>
        </tr>
    </thead>


    <tbody>
    @foreach($base['hospitals'] as $key => $details)

    <tr>
            <td>{{$key}}</td>
                <?php  $c = 0; ?>
                @foreach($details as $keyz => $detail)
                        @if($c > 0)
                            <tr>
                                <td></td>
                        @endif
                         <?php $totals[$keyz] = 0; ?>
                            <td>{{$keyz}}</td>
                             <td></td>

                             @if( isset($detail['drugsmeds']) )
                                <td>{{ $detail['drugsmeds'][0]->value }}</td>
                                <?php $totals[$keyz] += $detail['drugsmeds'][0]->value; ?>
                            @else
                                <td></td>
                            @endif

                            @if( isset($detail['medlabsden']) )
                                <td>{{ $detail['medlabsden'][0]->value }}</td>
                                <?php $totals[$keyz] += $detail['medlabsden'][0]->value; ?>
                            @else
                                <td></td>
                            @endif

                            @if( isset($detail['hospitals']) )
                                <td>{{ $detail['hospitals'][0]->value }}</td>
                                <?php $totals[$keyz] += $detail['hospitals'][0]->value; ?>
                            @else
                                <td></td>
                            @endif

                            @if( isset($detail['hothersrvcs']) )
                                <td>{{ $detail['hothersrvcs'][0]->value }}</td>
                                <?php $totals[$keyz] += $detail['hothersrvcs'][0]->value; ?>
                            @else
                                <td></td>
                            @endif
                                <td></td>
                            <td>{{  $totals[$keyz] }}</td>

                    <?php  $c++; ?>
                @endforeach
    </tr>
    @endforeach
    </tbody>
</table>