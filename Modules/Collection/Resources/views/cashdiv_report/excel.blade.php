<table>
    <thead>
        <tr>
            <th rowspan="2" colspan="1">DATE</th>
            <th colspan="2" rowspan="1">OPAG</th>
            <th rowspan="2"  colspan="1">Totals</th>
        </tr>
        <tr>
        <th></th>
            <th >SALES</th>
            <th >LODGING</th>
        </tr>
    </thead>

    <tbody>
       @foreach($base['opag'] as $key => $value)
       <?php $opag_total[$key] = 0; ?>
                   <tr>
                       <td>{{  $key }}</td>
                       @if(isset($value['sales']))
                                        <td>{{ ($value['sales'][0]->value)}}</td>
                                         <?php $opag_total[$key] += $value['sales'][0]->value; ?>
                       @else
                                        <td></td>
                       @endif

                       @if(isset($value['lodging']))
                                        <td>{{ ($value['lodging'][0]->value)}}</td>
                                        <?php $opag_total[$key] += $value['lodging'][0]->value; ?>
                       @else
                                        <td></td>
                       @endif

                        <td>{{ $opag_total[$key]  }}</td>

                    </tr>
            @endforeach
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th rowspan="2" colspan="1">DATE</th>
            <th colspan="2" rowspan="1">PVET</th>
            <th rowspan="2"  colspan="1">Totals</th>
        </tr>
        <tr>
        <th></th>
            <th >SALES</th>
            <th ></th>
        </tr>
    </thead>

    <tbody>
       @foreach($base['pvet'] as $key => $value)
       <?php $pvet_total[$key] = 0; ?>
                   <tr>
                       <td>{{  $key }}</td>
                                        <td>{{ ($value[0]->value)}}</td>
                                         <?php $pvet_total[$key] += $value[0]->value; ?>
                            <td></td>

                        <td>{{ $pvet_total[$key]  }}</td>

                    </tr>
            @endforeach
    </tbody>
</table>


<table>
    <thead>
        <tr>
            <th rowspan="2" colspan="1">DATE</th>
            <th colspan="2" rowspan="1">COLD CHAIN</th>
            <th rowspan="2"  colspan="1">Totals</th>
        </tr>
        <tr>
        <th></th>
            <th >SALES</th>
            <th ></th>
        </tr>
    </thead>

    <tbody>
       @foreach($base['coldchain'] as $key => $value)
       <?php $coldchain_total[$key] = 0; ?>
                   <tr>
                       <td>{{  $key }}</td>
                                        <td>{{ ($value[0]->value)}}</td>
                                         <?php $coldchain_total[$key] += $value[0]->value; ?>
                            <td></td>

                        <td>{{ $coldchain_total[$key]  }}</td>

                    </tr>
            @endforeach
    </tbody>
</table>



<table>
    <thead>
        <tr>
            <th rowspan="2" colspan="1">DATE</th>
            <th colspan="2" rowspan="1">OPP</th>
            <th rowspan="2"  colspan="1">Totals</th>
        </tr>
        <tr>
        <th></th>
            <th >SALES</th>
            <th ></th>
        </tr>
    </thead>

    <tbody>
       @foreach($base['opp'] as $key => $value)
       <?php $opp_total[$key] = 0; ?>
                   <tr>
                       <td>{{  $key }}</td>
                                        <td>{{ ($value[0]->value)}}</td>
                                         <?php $opp_total[$key] += $value[0]->value; ?>
                            <td></td>

                        <td>{{ $opp_total[$key]  }}</td>

                    </tr>
            @endforeach
    </tbody>
</table>