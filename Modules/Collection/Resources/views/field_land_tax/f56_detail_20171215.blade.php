@extends('nav')

@section('content')

{{ Form::open(['method' => 'POST', 'route' => ['field_land_tax.f56_detail_submit', 'id' => $base['id']]]) }}
<div class="row">
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>Date</dt>
            <dd>{{ date('F d, Y', strtotime($base['receipt']->date_of_entry)) }}</dd>
            <dt>Payor</dt>
            <dd>{{ $base['receipt']->customer->name }}</dd>
            <dt>OR No.</dt>
            <dd>{{ $base['receipt']->serial_no }}</dd>
            <dt>Brgy.</dt>
            <dd>
            @if (!empty($base['receipt']->barangay))
                {{ $base['receipt']->barangay->name}}
                <input type="hidden" name="barangay_code" id="barangay_code" value="{{$base['receipt']->barangay->code}}">
                <input type="hidden" name="municipal_code" id="municipal_code" value="{{$base['receipt']->municipality->code}}">
            @endif

            </dd>
        </dl>
    </div>
</div>

@if (!isset($base['detail']))
<div class="row">
    <div class="form-group col-sm-12">
            <div id="mncpal_brgy_code_error"  ></div>
        <table class="table" id="table">
            <thead>
                <tr>
                    <th>TD/ARP No. </th>
                    <th>Classification</th>
                    <th>Period Covered</th>
                    <th>Current Year Gross Amt.</th>
                    <th>Discount</th>
                    <th>Previous Year/s</th>
                    <th>Penalty for Current Year</th>
                    <th>Penalty for Previous Year/s</th>
                    <th><button id="add_row" class="btn btn-sm btn-success" type="button"><i class="fa fa-plus"></i></button></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input type="text" class="form-control tdarpno" name="tdarpno[]" required>
                    </td>
                    <td>
                        <select class="form-control" id="f56_type" name="f56_type[]" required>
                            <option selected disabled></option>
                            @foreach ($base['f56_types'] as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="text" class="form-control" name="period_covered[]" value="" required></td>
                    <td><input type="number" class="form-control" name="basic_current[]" value="0" min="0" step="0.01" required></td>
                    <td><input type="number" class="form-control" name="basic_discount[]" value="0" min="0" step="0.01" required></td>
                    <td><input type="number" class="form-control" name="basic_previous[]" value="0" min="0" step="0.01" required></td>
                    <td><input type="number" class="form-control" name="basic_penalty_current[]" value="0" min="0" step="0.01" required></td>
                    <td><input type="number" class="form-control" name="basic_penalty_previous[]" value="0" min="0" step="0.01" required></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

   <!--  <div class="form-group col-sm-6">
        <label for="period_covered">Period Covered</label>
        <input type="text" class="form-control" name="period_covered" value="" required>
    </div> -->

  
</div>
<br>
<!-- <div class="row">
    <div class="form-group col-sm-4">
        <label for="basic_current">Current Year Gross Amt.</label>
        <input type="number" class="form-control" name="basic_current" value="0" min="0" step="0.01" required>
    </div>

    <div class="form-group col-sm-4">
        <label for="basic_discount">Discount</label>
        <input type="number" class="form-control" name="basic_discount" value="0" min="0" step="0.01" required>
    </div>

    <div class="form-group col-sm-4">
        <label for="basic_previous">Previous Year/s</label>
        <input type="number" class="form-control" name="basic_previous" value="0" min="0" step="0.01" required>
    </div>

    <div class="form-group col-sm-6">
        <label for="basic_penalty_current">Penalty for Current Year</label>
        <input type="number" class="form-control" name="basic_penalty_current" value="0" min="0" step="0.01" required>
    </div>

    <div class="form-group col-sm-6">
        <label for="basic_penalty_previous">Penalty for Previous Year/s</label>
        <input type="number" class="form-control" name="basic_penalty_previous" value="0" min="0" step="0.01" required>
    </div>
</div> -->
<br>
<div class="row">
    <div class="form-group col-sm-12">
        <input type="submit" class="btn btn-success" value="Submit">
    </div>
</div>
@else
<div class="row">
    <div class="form-group col-sm-12">
    <div id="mncpal_brgy_code_error"  ></div>
        <table class="table" id="table">
            <thead>
                <tr>
                    <th>TD/ARP No. </th>
                    <th>Classification </th>
                    <th><button id="add_row" class="btn btn-sm btn-success" type="button"><i class="fa fa-plus"></i></button></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($base['detail']->TDARP as $tan)
                <tr>
                    <td>
                        <input type="text" class="form-control tdarpno" name="tdarpno[]" value="{{ $tan->tdarpno }}" required>
                    </td>
                    <td>
                       <select class="form-control" id="f56_type" name="f56_type[]" required>
                            @foreach ($base['f56_types'] as $type)
                                @if ($tan->f56_type == $type->id)
                                <option value="{{ $type->id }}" selected>{{ $type->name }}</option>
                                @else
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm rem_row" type="button"><i class="fa fa-minus"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="form-group col-sm-6">
        <label for="period_covered">Period Covered</label>
        <input type="text" class="form-control" name="period_covered" value="{{ $base['detail']->period_covered }}" required>
    </div>

 

   
</div>
<br>
<div class="row">
    <div class="form-group col-sm-4">
        <label for="basic_current">Current Year Gross Amt.</label>
        <input type="number" class="form-control" name="basic_current" value="{{ $base['detail']->basic_current }}" min="0" step="0.01" required>
    </div>

    <div class="form-group col-sm-4">
        <label for="basic_discount">Discount</label>
        <input type="number" class="form-control" name="basic_discount" value="{{ $base['detail']->basic_discount }}" min="0" step="0.01" required>
    </div>

    <div class="form-group col-sm-4">
        <label for="basic_previous">Previous Year/s</label>
        <input type="number" class="form-control" name="basic_previous" value="{{ $base['detail']->basic_previous }}" min="0" step="0.01" required>
    </div>

    <div class="form-group col-sm-6">
        <label for="basic_penalty_current">Penalty for Current Year</label>
        <input type="number" class="form-control" name="basic_penalty_current" value="{{ $base['detail']->basic_penalty_current }}" min="0" step="0.01" required>
    </div>

    <div class="form-group col-sm-6">
        <label for="basic_penalty_previous">Penalty for Previous Year/s</label>
        <input type="number" class="form-control" name="basic_penalty_previous" value="{{ $base['detail']->basic_penalty_previous }}" min="0" step="0.01" required>
    </div>
</div>
<br>
<div class="row">
    <div class="form-group col-sm-12">
        <input type="submit" class="btn btn-success" value="Submit">
    </div>
</div>
@endif
{{ Form::close() }}

@endsection

@section('js')
<script>
$('#add_row').click( function() {
    var add_tdarp = '<tr>'+
                        '<td><input type="text" class="form-control tdarpno" name="tdarpno[]" required></td>'+
                        '<td><select class="form-control" id="f56_type" name="f56_type[]" required>'+
                            '<option selected disabled></option>'+
                            @foreach ($base['f56_types'] as $type)
                                '<option value="{{ $type->id }}">{{ $type->name }}</option>'+
                            @endforeach
                        '</select></td>'+
                        '<td><input type="text" class="form-control" name="period_covered[]" value="" required></td>'+
                        '<td><input type="number" class="form-control" name="basic_current[]" value="0" min="0" step="0.01" required></td>'+
                        '<td><input type="number" class="form-control" name="basic_discount[]" value="0" min="0" step="0.01" required></td>'+
                        '<td><input type="number" class="form-control" name="basic_previous[]" value="0" min="0" step="0.01" required></td>'+
                        '<td><input type="number" class="form-control" name="basic_penalty_current[]" value="0" min="0" step="0.01" required></td>'+
                        '<td><input type="number" class="form-control" name="basic_penalty_previous[]" value="0" min="0" step="0.01" required></td>'+
                        '<td><button type="button" class="btn btn-warning btn-sm rem_row" ><i class="fa fa-minus"></i></button></td>'+
                    '</tr>';
    $('#table').find('tbody').append(add_tdarp);
    // $('#table').find('tbody')
    //     .append($('<tr>')
    //         .append($('<td>')
    //             .append($('<input>')
    //                 .attr('type', 'text')
    //                 .attr('class', 'form-control tdarpno')
    //                 .attr('required', 'true')
    //                 .attr('name', 'tdarpno[]')
    //             )
    //         )
    //         append($('<tr>')
    //         .append($('<td>')
    //             .append($('<input>')
    //                 .attr('type', 'text')
    //                 .attr('class', 'form-control tdarpno')
    //                 .attr('required', 'true')
    //                 .attr('name', 'tdarpno[]')
    //             )
    //         )
    //         .append($('<td>')
    //             .append($('<button>')
    //                 .attr('type', 'button')
    //                 .attr('class', 'btn btn-warning btn-sm rem_row')
    //                 .append($('<i>')
    //                     .attr('class', 'fa fa-minus')
    //                 )
    //             )
    //         )
    //     );
        $.fn.tdarpno();
});

$(document).on('click', '.rem_row', function() {
    $(this).parent().parent().remove();
    compute_total();
    check_shared();
});

function zeroPad(num, places) {
    if(!isNaN(num)){
         var zero = places - num.toString().length + 1;
         return Array(+(zero > 0 && zero)).join("0") + num;
    }
}




$.fn.tdarpno = function(){
    var mncpal_brgy_code_error = $('#mncpal_brgy_code_error');

    $('.tdarpno').on('keyup',function(){
         mncpal_brgy_code_error.html('');
        mncpal_brgy_code_error.removeClass('alert alert-danger alert-dismissible');
        var el = $(this);
        var el_val = el.val().split('-');
        var mncplty = el_val[1];
        var brgy = el_val[2];
            brgy = zeroPad(brgy,3);
        var barangay_code = $('#barangay_code').val();
        var municipal_code = $('#municipal_code').val();
        var error = '';
        if(el_val[0] === '2010'){
             if( el_val.length >1 && mncplty != ''&& mncplty !== municipal_code){
             error =' <strong> Error ! Wrong Municipality Code</strong>';
            }
              if(el_val.length >2 && brgy != '' && brgy !== barangay_code){
                error =  error +  ' <strong> Error ! Wrong Barangay Code</strong>';
            }
            if(error!=''){
                mncpal_brgy_code_error.html(error);
                mncpal_brgy_code_error.addClass('alert alert-danger alert-dismissible');
            }
        }
       


    });
};
$.fn.tdarpno();
</script>
@endsection