@extends('nav')

@section('css')
    .hidden {
        display: none;
    }
@endsection

@section('content')
<div class="row">
    
    {{ Form::open([ 'method' => 'POST', 'route' => 'rates.update' ]) }}
    <div class="col-sm-12">
        @if ($data['title'] != null)
            <h3>{{$data['title']->name}}</h3>
            <input type="hidden" name="id" value="{{ $data['title']->id }}">
            <input type="hidden" name="type" value="title">
        @elseif ($data['subtitle'] != null)
            <h3>{{$data['subtitle']->name}}</h3>
            <input type="hidden" name="id" value="{{ $data['subtitle']->id }}">
            <input type="hidden" name="type" value="subtitle">
        @endif
        
        <div class="form-group col-sm-12">
            <label class="control-label col-sm-4" for="is_shared">Shared with Municipality and/or Barangay</label>
            <div class="col-sm-8">
                <select class="form-control" id="is_shared" name="is_shared" required autofocus>
                    
                    @if (isset($data['collectionrate'][0]))
                        @if ($data['collectionrate'][0]->is_shared == 1)
                            <option value="1" selected>Yes</option>
                            <option value="0">No</option>
                        @else
                            <option value="1">Yes</option>
                            <option value="0" selected>No</option>
                        @endif
                    @else
                        <option value="" disabled selected></option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    @endif
                    
                </select>
            </div>
        </div>
        
        <div class="form-group col-sm-12 share_blks">
            <label class="control-label col-sm-4" for="sharepct_provincial">Provincial Share (%)</label>
            <div class="col-sm-8">
                @if (isset($data['collectionrate'][0]))
                <input value="{{ $data['collectionrate'][0]->sharepct_provincial }}" type="number" min="0" max="100" step="0.01" name="sharepct_provincial" id="sharepct_provincial" class="form-control shares">
                @else
                <input type="number" min="0" max="100" step="0.01" name="sharepct_provincial" id="sharepct_provincial" class="form-control">
                @endif
            </div>
        </div>
        <div class="form-group col-sm-12 share_blks">
            <label class="control-label col-sm-4" for="sharepct_municipal">Municipal Share (%)</label>
            <div class="col-sm-8">
                @if (isset($data['collectionrate'][0]))
                <input value="{{ $data['collectionrate'][0]->sharepct_municipal }}" type="number" min="0" max="100" step="0.01" name="sharepct_municipal" id="sharepct_municipal" class="form-control shares">
                @else
                <input type="number" min="0" max="100" step="0.01" name="sharepct_municipal" id="sharepct_municipal" class="form-control">
                @endif
            </div>
        </div>
        <div class="form-group col-sm-12 share_blks">
            <label class="control-label col-sm-4" for="sharepct_barangay">Barangay Share (%)</label>
            <div class="col-sm-8">
                @if (isset($data['collectionrate'][0]))
                <input value="{{ $data['collectionrate'][0]->sharepct_barangay }}" type="number" min="0" max="100" step="0.01" name="sharepct_barangay" id="sharepct_barangay" class="form-control shares">
                @else
                <input type="number" min="0" max="100" step="0.01" name="sharepct_barangay" id="sharepct_barangay" class="form-control">
                @endif
            </div>
        </div>
        
        <div class="form-group col-sm-12">
            <label class="control-label col-sm-4" for="rate_type">Rate Type</label>
            <div class="col-sm-8">
                <select class="form-control" id="rate_type" name="rate_type" required>
                    
                    @if (isset($data['collectionrate'][0]))
                        @if ($data['collectionrate'][0]->type == "fixed")
                            <option value="fixed" selected>Fixed</option>
                        @else
                            <option value="fixed">Fixed</option>
                        @endif
                        
                        @if ($data['collectionrate'][0]->type == "manual")
                            <option value="manual" selected>Manual</option>
                        @else
                            <option value="manual">Manual</option>
                        @endif
                        
                        @if ($data['collectionrate'][0]->type == "percent")
                            <option value="percent" selected>Percent</option>
                        @else
                            <option value="percent">Percent</option>
                        @endif
                        
                        @if ($data['collectionrate'][0]->type == "schedule")
                            <option value="schedule" selected>Schedule</option>
                        @else
                            <option value="schedule">Schedule</option>
                        @endif
                    @else
                        <option value="" disabled selected></option>
                        <option value="fixed">Fixed</option>
                        <option value="manual">Manual</option>
                        <option value="percent">Percent</option>
                        <option value="schedule">Schedule</option>
                    @endif
                    
                </select>
            </div>
        </div>
        
        <div class="form-group" id="content" style="padding-top:50px;"></div>
    </div>

    <div class="form-group col-sm-12">
        <input type="submit" class="btn btn-primary" value="Save Rate">
    </div>
	{{ Form::close() }}
	
</div>
@endsection

@section('js')
<script>
    rate_change($('#rate_type').val());
    @if (!isset($data['collectionrate'][0]))
        $('.share_blks').addClass('hidden');
    @endif
    
    // hide rate after deadline
    if ($('#deadline').val() == 0) {
        $('.percent_after_deadline').addClass('hidden');
        $('#rate_per_month').attr('required', false);
    } else {
        $('#rate_per_month').attr('required', true);
    }
    
    // hide share partitions if not shared
    if ($('#is_shared').val() == 0) {
        $('.share_blks').addClass('hidden');
    }
    
    $(document).on('change', '#deadline', function() {
        if ($(this).val() == 1) {
            $('.percent_after_deadline').removeClass('hidden');
            $('#rate_per_month').attr('disabled', false).attr('required', true);
            return;
        }
        $('.percent_after_deadline').addClass('hidden');
        $('#rate_per_month').val('').attr('disabled', true).attr('required', false);;
    });
    
    $('#is_shared').change( function() {
        $('.shares').val(0);
        if ($(this).val() == 1) {
            $('.share_blks').removeClass('hidden');
            return;
        }
        $('.share_blks').addClass('hidden');
        $('.shares').val('');
    });
    
    $('#rate_type').change( function() {
        rate_change($(this).val());
    });
    
    function rate_change(rate) {
        if (rate == 'fixed') {
			$('#content').html(
				'<div class="form-group col-sm-12">' +
					'<label class="control-label col-sm-4" for="fixed_val">Fixed Value</label>' +
					'<div class="col-sm-8">' +
                        @if (isset($data['collectionrate'][0]))
                        '<input class="form-control" type="number" name="fixed_val" min="0"  step="0.01" value="{{ $data['collectionrate'][0]->value }}" required>' +
                        @else
                        '<input class="form-control" type="number" name="fixed_val" min="0"  step="0.01" required>' +
                        @endif
					'</div>' +
				'</div>'
			);
        } else if (rate == 'manual') {
            $('#content').html('<div class="form-group col-sm-12"></div>');
		} else if (rate == 'percent') {
			$('#content').html(
				'<div class="form-group col-sm-12">' +
					'<label class="control-label col-sm-4" for="percent_val">Percent Value</label>' +
					'<div class="col-sm-8">' +
                        @if (isset($data['collectionrate'][0]))
						'<input class="form-control" type="number" name="percent_val" min="0"  step="0.01" value="{{ $data['collectionrate'][0]->value }}" required>' +
                        @else
                        '<input class="form-control" type="number" name="percent_val" min="0"  step="0.01" required>' +
                        @endif
					'</div>' +
				'</div>' +
                '<div class="form-group col-sm-12">' +
                    '<label class="control-label col-sm-4" for="percent_of">Percent of</label>' +
                    '<div class="col-sm-8">' +
                        '<select class="form-control" name="percent_of" id="percent_of" required>' +
                        @if (isset($data['collectionrate'][0]))
                            @if ($data['collectionrate'][0]->pct_is_sum_given == 1)
                            '<option value="1" selected>Given Value</option>' +
                            '<option value="0">Total</option>' +
                            @else
                            '<option value="1">Given Value</option>' +
                            '<option value="0" selected>Total</option>' +
                            @endif
                        @else
                            '<option selected disabled></option>' +
                            '<option value="1">Given Value</option>' +
                            '<option value="0">Total</option>' +
                        @endif
                        '</select>' +
                    '</div>' +
				'</div>' +
                '<div class="form-group col-sm-12">' +
					'<label class="control-label col-sm-4" for="deadline">Deadline</label>' +
					'<div class="col-sm-8">' +
						'<select class="form-control" name="deadline" id="deadline" required>' +
                        @if (isset($data['collectionrate'][0]))
                            @if ($data['collectionrate'][0]->pct_deadline == 1)
                            '<option value="1" selected>Yes</option>' +
                            '<option value="0">No</option>' +
                            @else
                            '<option value="1">Yes</option>' +
                            '<option value="0" selected>No</option>' +
                            @endif
                        @else
                            '<option selected disabled></option>' +
                            '<option value="1">Yes</option>' +
                            '<option value="0">No</option>' +
                        @endif
                        '</select>' +
					'</div>' +
				'</div>' +
                @if ((isset($data['collectionrate'][0])) && ($data['collectionrate'][0]->pct_deadline == 1))
                '<div class="form-group col-sm-12 percent_after_deadline">' +
                @else
                '<div class="form-group col-sm-12 percent_after_deadline hidden">' +
                @endif
                    '<label class="control-label col-sm-4" for="rate_per_month">Rate after Deadline (per month)</label>' +
                    '<div class="col-sm-8">' +
                        @if ((isset($data['collectionrate'][0])) && ($data['collectionrate'][0]->pct_deadline == 1))
                        '<input class="form-control" type="number" name="rate_per_month" id="rate_per_month" min="0"  step="0.01" value="{{ $data['collectionrate'][0]->pct_rate_per_month }}" required>' +
                        @else
                        '<input class="form-control" type="number" name="rate_per_month" id="rate_per_month" min="0"  step="0.01">' +
                        @endif
                    '</div>' +
                '</div>'+
                // date of deadline
                @if ((isset($data['collectionrate'][0])) && ($data['collectionrate'][0]->pct_deadline == 1))
                '<div class="form-group col-sm-12 percent_after_deadline">' +
                @else
                '<div class="form-group col-sm-12 percent_after_deadline hidden">' +
                @endif
                    '<label class="control-label col-sm-4" for="rate_per_month">Deadline Date</label>' +
                    '<div class="col-sm-8">' +
                        @if ((isset($data['collectionrate'][0])) && ($data['collectionrate'][0]->pct_deadline == 1))
                        '<input class="form-control datepicker" type="text" name="date_deadline" id="date_deadline" value="{{ $data['collectionrate'][0]->pct_deadline_date  }}" >' +
                        @else
                        '<input class="form-control datepicker" type="text" name="date_deadline" id="date_deadline" >' +
                        @endif
                    '</div>' +
                '</div>'
			);
		} else if (rate == 'schedule') {
			$('#content').html(
				'<div class="form-group col-sm-12">' +
					'<label class="control-label col-sm-12" for="schedule_val">Schedule</label>' +
					'<table class="table" id="sched_table">' +
						'<tr>' +
							'<th>Label</th>' +
							'<th width="15%">Value</th>' +
                            '<th width="10%">Per Unit?</th>' +
                            '<th width="20%">Unit</th>' +
							'<th><button type="button" class="btn btn-sm btn-success" id="sched_add"><i class="fa fa-plus"></i></button></th>' +
						'</tr>' +
                        @if (isset($data['collectionrate'][0]))
                        @foreach($data['collectionrate'] as $cr)
						'<tr>' +
                            @if (isset($data['collectionrate'][0]))
                                '<td><input type="text" class="form-control" name="sched_label[]" value="{{ $cr->label }}" required></td>' +
                                '<td><input type="number" min="0" step="0.01" class="form-control" name="sched_val[]" value="{{ $cr->value }}" required></td>' +
                                '<td>' +
                                    '<select class="form-control sched_is_perunit" name="sched_is_perunit[]" required>' +
                                        @if ($cr->sched_is_perunit)
                                        '<option value="0">No</option>' +
                                        '<option value="1" selected>Yes</option>' +
                                        @else
                                        '<option value="0" selected>No</option>' +
                                        '<option value="1">Yes</option>' +
                                        @endif
                                    '</select>' +
                                '</td>' +
                                @if ($cr->sched_is_perunit)
                                '<td><input type="text" class="form-control" name="sched_unit[]" value="{{ $cr->sched_unit }}" required></td>' +
                                @else
                                '<td><input type="text" class="form-control" name="sched_unit[]" readonly required></td>' +
                                @endif
                            @else
                                '<td><input type="text" class="form-control" name="sched_label[]" required></td>' +
                                '<td><input type="number" min="0" step="0.01" class="form-control" name="sched_val[]" required></td>' +
                                '<td>' +
                                    '<select class="form-control sched_is_perunit" name="sched_is_perunit[]" required>' +
                                        '<option value="0">No</option>' +
                                        '<option value="1">Yes</option>' +
                                    '</select>' +
                                '</td>' +
                                '<td><input type="text" class="form-control" name="sched_unit[]" readonly required></td>' +
                            @endif
							'<td><button type="button" class="btn btn-sm btn-warning" id="sched_rem"><i class="fa fa-minus"></i></button></td>' +
						'</tr>' +
                        @endforeach
                        @else
                        '<tr>' +
							'<td>' +
                            '<input type="text" class="form-control" name="sched_label[]" required>' +
                            '</td>' +
                            '<td><input type="number" min="0" step="0.01" class="form-control" name="sched_val[]" required></td>' +
                            '<td>' +
                                '<select class="form-control sched_is_perunit" name="sched_is_perunit[]" required>' +
                                    '<option value="0">No</option>' +
                                    '<option value="1">Yes</option>' +
                                '</select>' +
                            '</td>' +
                            '<td><input type="text" class="form-control" name="sched_unit[]" readonly required></td>' +
							'<td></td>' +
						'</tr>' +
                        @endif
					'</table>' +
				'</div>'
			);
		}
    }
	
	$(document).on('click', '#sched_add', function() {
		$('#sched_table tr:last').after(
			'<tr>' +
				'<td><input type="text" class="form-control" name="sched_label[]" required></td>' +
				'<td><input type="number" min="0" step="0.01" class="form-control" name="sched_val[]" required></td>' +
                '<td>' +
                    '<select class="form-control sched_is_perunit" name="sched_is_perunit[]" required>' +
                        '<option value="0">No</option>' +
                        '<option value="1">Yes</option>' +
                    '</select>' +
                '</td>' +
                '<td><input type="text" class="form-control" name="sched_unit[]" readonly required></td>' +
				'<td><button type="button" class="btn btn-sm btn-warning" id="sched_rem"><i class="fa fa-minus"></i></button></td>' +
			'</tr>'
		);
	});
	
	$(document).on('click', '#sched_rem', function() {
		$(this).parent('td').parent('tr').remove();
	});
    
    $(document).on('focus', '.datepicker', function() {
        $(this).datepicker({
            changeMonth: true,
            changeYear: true
        })
    });
    
    $(document).on('change', '.sched_is_perunit', function() {
        var unit = $(this).parent().next().children('input');
        if ($(this).val() == 1) {
            unit.attr('readonly', false);
            return;
        }
        unit.attr('readonly', true).val('');
    });

    $.fn.datepickerx = function(){
        $('.datepicker').datepicker({
            changeMonth:true,
            changeYear:true,
            showAnim:'slide'
            
        });
    };
$.fn.datepickerx();
</script>
@endsection