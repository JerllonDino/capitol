<center><strong>SAND AND GRAVEL TAX/ PENALTIES COLLECTED <br/><br> <u>FOR THE PERIOD {{ $datex->format('F') }} {{$year}}</u></strong></center><br>

<table>
    <thead>
        <tr>
            <th rowspan="2">DATE<br/>{{ $year}}</th>
            <th rowspan="2">OFFICIAL<br/>RECEIPT NO.</th>
            <th rowspan="2">MONITORING<br/>PENALTIES</th>
            <th rowspan="2">PROVINCIAL<br/>CONTRACTORS</th>
            <th rowspan="1" colspan="2">S & G PERMITTEES</th>
            <th rowspan="2">MUNICIPAL<br/>REMITTANCES</th>
            <th rowspan="2">TOTALS</th>
        </tr>

        <tr>
            <th>INDUSTRIAL</th>
            <th>COMMERCIAL</th>
        </tr>

        <tr>
            <th>{{ $datex->format('F') }}</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>0.00</th>
        </tr>
    </thead>
    <tbody>

        <?php
            $total = 0;
            $total1 = 0;
            $total2 = 0;
            $total5 = 0;
            $total6 = 0;
            $total16 = 0;
        ?>
        @foreach($dailygraveltypes as $key => $dly )
            @if(is_array($dly)) 
                @foreach($dly as $c_type => $rcpt)
                    @if(is_array($rcpt)) 
                        @foreach($rcpt as $key2 => $value)
                            <?php
                                $total += !is_null($value) ? $value : 0;
                                $total1 += $c_type == 1 ? $value : 0;
                                $total2 += $c_type == 2 ? $value : 0;
                                $total5 += $c_type == 5 ? $value : 0;
                                $total6 += $c_type == 6 ? $value : 0;
                                $total16 += $c_type == 16 ? $value : 0;
                            ?>
                            <tr>
                                <td>{{ $key }}</td> 
                                <td>{{ $key2 }}</td> 
                                <td>{{ $c_type == 1 ? number_format($value,2) : '' }}</td>
                                <td>{{ $c_type == 2 ? number_format($value,2) : '' }}</td>
                                <td>{{ $c_type == 5 ? number_format($value,2) : '' }}</td>
                                <td>{{ $c_type == 6 ? number_format($value,2) : '' }}</td>
                                <td>{{ $c_type == 16 ? number_format($value,2) : '' }}</td>
                                <td>{{ number_format($value,2) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <?php
                            $total += !is_null($rcpt) ? $rcpt : 0;
                            $total1 += $c_type == 1 ? $rcpt : 0;
                            $total2 += $c_type == 2 ? $rcpt : 0;
                            $total5 += $c_type == 5 ? $rcpt : 0;
                            $total6 += $c_type == 6 ? $rcpt : 0;
                            $total16 += $c_type == 16 ? $rcpt : 0;
                        ?>
                        <tr>
                            <td>{{ $key }}</td> 
                            <td></td> 
                            <td>{{ $c_type == 1 ? number_format($rcpt,2) : '' }}</td>
                            <td>{{ $c_type == 2 ? number_format($rcpt,2) : '' }}</td>
                            <td>{{ $c_type == 5 ? number_format($rcpt,2) : '' }}</td>
                            <td>{{ $c_type == 6 ? number_format($rcpt,2) : '' }}</td>
                            <td>{{ $c_type == 16 ? number_format($rcpt,2) : '' }}</td>
                            <td>{{ number_format($rcpt,2) }}</td>
                        </tr>
                    @endif
                @endforeach
            @endif
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">Total Collections for Sharing</th>
            <th>{{ number_format($total1,2) }}</th>
            <th>{{ number_format($total2,2) }}</th>
            <th>{{ number_format($total5,2) }}</th>
            <th>{{ number_format($total6,2) }}</th>
            <th>{{ number_format($total16,2) }}</th>
            <th>{{ number_format($total,2) }}</th>
        </tr> 

        <tr>
            <th colspan="7">Total Provincial Share {{ $datex->format('F') }} {{$year}} </th>
            <th>{{ number_format($provShare,2) }}</th>
        </tr>
    </tfoot>    
</table>

<table>
    <tr>
        <th colspan="3"><u>SUMMARY</u></th>
    </tr>
    <tr>
        <th colspan="3">&nbsp;</th>
    </tr>
    <tr>
        <td colspan="3"><u>Sand and Gravel Permittess:</u></td>
    </tr>

    @php($typestotal = 0)
    @if(isset($graveltypes['Commercial']))
        @php($typestotal += $graveltypes['Commercial']->value)
    <tr>
        <td></td>
        <td>Commercial</td>
        <td>{{ number_format($total6,2) }}</td>
    </tr>
    @endif
    @if(isset($graveltypes['Industrial ']))
        @php($typestotal += $graveltypes['Industrial ']->value)
    <tr>
        <td></td>
        <td>Industrial</td>
        <td>{{ number_format($total5,2) }}</td>
    </tr>

    @else
    <tr>
        <td></td>
        <td>Industrial</td>
        <td>{{ number_format(0,2) }}</td>
    </tr>
    @endif

    <tr>
        <td colspan="3"><u>Projects</u></td>
    </tr>

    @if(isset($graveltypes['Contractors (Prov.)']))
    @php($typestotal += $graveltypes['Contractors (Prov.)']->value)
    <tr>
        <td></td>
        <td>Provincial</td>
        <td>{{ number_format($total2,2) }}</td>
    </tr>
    @endif

    <tr>
        <td></td>
        <td colspan="2"></td>
    </tr>

    <tr>
        <td></td>
        <td colspan="2"></td>
    </tr>

    @if(isset($graveltypes['Monitoring']))
    @php($typestotal += $graveltypes['Monitoring']->value)
    <tr>
        <td><u>Sand and Gravel Penalties Through Monitoring</u></td>
        <td></td>
        <td>{{ number_format($total1,2) }}</td>
    </tr>
    @endif
    <tr>
        <td><u>Municipal Remittances</u></td>
        <td></td>
        <td>{{ number_format($total16,2) }}</td>
    </tr>

    <tr>
        <td>&nbsp;</td>
        <td>TOTAL</td>
        <td>{{ number_format($total,2) }}</td>
    </tr>

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
                $STR = strtolower($acctble_officer_name->value);
                $STR = strtoupper($STR);
             @endphp
            <td>{{ $STR }}</td>
        </tr>
        <tr>
             <td></td>
            <td>{{ $acctble_officer_position->value }}</td>
        </tr>


    </tbody>
</table>

