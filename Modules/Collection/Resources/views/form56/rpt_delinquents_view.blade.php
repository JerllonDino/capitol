@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
<style type="text/css">
    #delqnt_payments > thead > tr > th {
        text-align: center;
    }
</style>
@endsection

@section('content')
    <table>
        <tr>
            <td style="padding-right: 20px;"><b>Payor Name</b></td>
            <td>{{ $base['delqnt']->name }}</td>
        </tr>
        <tr>
            <td><b>Address</b></td>
            <td>{{ $base['delqnt']->address }}</td>
        </tr>
    </table>
    
    <br>
    <table id="delqnt_payments" class="table table-responsive">
        <thead>
            <tr>
                <th rowspan="2">ARP No.</th>
                <th rowspan="2">Address</th>
                <th rowspan="2">Classification</th>
                <th rowspan="2">Area(sq.m.)</th>
                <th rowspan="2">Assessed Value</th>
                <th rowspan="2">Annual Tax</th>
                <th rowspan="2">Year</th>
                <th rowspan="1" colspan="2">Basic/SEF</th>
                <th rowspan="2">Grand Total</th>
                <th rowspan="2">Remarks</th>
            </tr>
            <tr>
                <th>Tax</th>
                <th>Penalty</th>
            </tr>
        </thead>
        <tbody>
            @if(count($base['tdarp']) > 0)
                @foreach($base['tdarp'] as $key => $arp)
                    <?php
                        $measure = preg_replace('/[^A-Za-z0-9]/', '', $arp->measurement);
                        $land_area = strcasecmp($measure, 'sqm') == 0 || strcasecmp($measure, 'sq') == 0 ? $arp->land_area : $arp->land_area*10000 ;
                        $annual_tax = 0;
                        $year = \Carbon\Carbon::parse($base['payor_last_pd'])->format('Y') . "-" . \Carbon\Carbon::now()->format('Y');
                        $tax = 0;
                        $penalty = \Carbon\Carbon::now()->diffInMonths(\Carbon\Carbon::parse($base['payor_last_pd'])) * .02 * $arp->assessed_value;
                        $gtotal = $penalty*2;
                    ?>
                    <tr>
                        <td>{{ $arp->tax_dec_no }}</td>
                        <td>{{ $base['brgy'][$arp->brgy].', '.$base['munic'][$arp->municipality] }}</td>
                        <td>{{ $arp->class }}</td>
                        <td>{{ $arp->land_area }}</td>
                        <td>{{ number_format($arp->assessed_value, 2) }}</td>
                        <td>{{ number_format($annual_tax, 2) }}</td>
                        <td>{{ $year }}</td>
                        <td>{{ number_format($tax, 2) }}</td>
                        <td>{{ number_format($penalty, 2) }}</td>
                        <td>{{ number_format($gtotal, 2) }}</td>
                        <td>{{ $arp->remarks }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10">No Data</td>
                </tr>
            @endif
        </tbody>
    </table>
@endsection

@section('js')
    <script type="text/javascript">
        $('#delqnt_payments').DataTable();
    </script>
@endsection
