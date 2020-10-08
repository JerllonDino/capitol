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
    <form action="{{ route('rpt.update') }}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="delq_id" value="{{ $base['delq_id'] }}">
        <table id="delqnt_payments" class="table table-responsive">
            <thead>
                <tr>
                    <th rowspan="2" style="display: none;">Tax Dec ID</th>
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
                            $penalty = 0*2;
                            $gtotal = 0;
                        ?>
                        <tr>
                            <td style="display: none;"><input type="text" name="arp_id[]" value="{{ $arp->id }}"></td>
                            <td><input class="form-control" type="text" name="arp[]" id="arp" value="{{ $arp->tax_dec_no }}" placeholder="{{ $arp->tax_dec_no }}"></td>
                            <td>
                                <input type="hidden" id="arp_brgy_set" value="{{ $arp->brgy }}">
                                <select class="form-control" type="text" name="arp_brgy[]" id="arp_brgy">
                                </select>
                                <select class="form-control" type="text" name="arp_munic[]" id="arp_munic">
                                    @foreach($base['munic'] as $key => $munic)
                                        @if($arp->municipality == $munic->id)
                                            <option value="{{ $munic->id }}" selected>{{ $munic->name }}</option>
                                        @else
                                            <option value="{{ $munic->id }}">{{ $munic->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" name="arp_class[]" id="arp_class" value="{{ $arp->class }}" placeholder="{{ $arp->class }}" class="form-control"></td>
                            <td><input type="number" name="arp_area[]" id="arp_area" value="{{ $arp->land_area }}" placeholder="{{ $arp->land_area }}" step="0.0001" class="form-control"></td>
                            <td><input type="text" name="arp_assess_val[]" id="arp_assess_val" value="{{ number_format($arp->assessed_value, 2) }}" placeholder="{{ number_format($arp->assessed_value, 2) }}" step="0.01" class="form-control"></td>
                            <td><input type="number" value="{{ number_format($annual_tax, 2) }}" placeholder="{{ number_format($annual_tax, 2) }}" step="0.01" class="form-control" readonly></td>
                            <td><input type="text" value="{{ $year }}" class="form-control"></td>
                            <td><input type="number" value="{{ number_format($tax, 2) }}" placeholder="{{ number_format($tax, 2) }}" step="0.01" class="form-control" readonly></td>
                            <td><input type="number" value="{{ number_format($penalty, 2) }}" placeholder="{{ number_format($penalty, 2) }}" step="0.01" class="form-control" readonly=""></td>
                            <td><input type="number" value="{{ number_format($gtotal, 2) }}" step="0.01" class="form-control" readonly></td>
                            <td><textarea name="remarks[]" id="remarks" placeholder="{{ $arp->remarks }}" class="form-control">{{ $arp->remarks }}</textarea></td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="10">No Data</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <br>
        <button type="submit" class="btn btn-warning pull-right">Submit Changes</button>
    </form>
@endsection

@section('js')
    <script type="text/javascript">
        var munics = [];
        var munics_id = [];
        $(document).on('change', '#arp_munic', function() {
            $.ajax({
                url : '{{ route("rpt.delqnt_edit_autofill") }}',
                type: 'post',
                data : {
                    mun_id : $('#arp_munic').val(),
                    _token : '{{ csrf_token() }}',
                },
                success : function(data) {
                    $('#arp_brgy').empty();
                    $.each(data, function(key, brgy) {
                        $('#arp_brgy').append('<option value="'+brgy.id+'">'+brgy.name+'</option>');
                    });
                }, 
            });
        });
        $(document).ready(function() {
            $.ajax({
                url : '{{ route("rpt.delqnt_edit_autofill") }}',
                type: 'post',
                data : {
                    mun_id : $('#arp_munic').val(),
                    _token : '{{ csrf_token() }}',
                },
                success : function(data) {
                    $('#arp_brgy').empty();
                    if($('#arp_brgy_set').val() != '') {
                        $.each(data, function(key, brgy) {
                            if($('#arp_brgy_set').val() == brgy.id) {
                                $('#arp_brgy').append('<option value="'+brgy.id+'" selected>'+brgy.name+'</option>');
                            } else {
                                $('#arp_brgy').append('<option value="'+brgy.id+'">'+brgy.name+'</option>');
                            }
                        });
                    }
                }, 
            });
        });
        $('input, select, textarea').focus(function() {
            $(this).animate({"margin-right": "110px"}, 700);
        });
        $('input, select, textarea').focusout(function() {
            $(this).animate({"margin-right": "0px"}, 700);
        });
    </script>
@endsection
