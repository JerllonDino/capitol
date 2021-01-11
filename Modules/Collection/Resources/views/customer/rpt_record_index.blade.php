@extends('nav')

@section('content')
	<div class="form-inline">
		<label>MUNICIPALITY </label>
		<select name="mun" id="mun" class="form-control">
			@foreach($base['municipality'] as $m)
				<option value="{{ $m->id }}">{{ $m->name }}</option>
			@endforeach
		</select>
		<label>BARANGAY </label>
		<select name="brgy" id="brgy" class="form-control">
			@foreach($base['barangays'] as $b)
				<option value="{{ $b->id }}">{{ $b->name }}</option>
			@endforeach
		</select>
		<button class="btn btn-default" id="show" onclick="$.fn.getRecords(1)">Show Paid</button>
		<button class="btn btn-default" id="show" onclick="$.fn.getRecords(0)">Show Delinquent</button>
	</div>
	<br>
	<table id="records" class="table table-responsive table-striped table-hover">
		<thead>
			<tr>
				<th>ARP No.</th>
				<th>Owner Name</th>
				<th>Owner Address</th>
				<th>Municipality</th>
				<th>Barangay</th>
				<th>Other Details</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>

	<!-- <div class="modal" id="mnth_year">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close pull-right" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body"> -->
					<form action="" method="post" id="select_form">
						{{ csrf_field() }}
						<input type="hidden" name="owner_name" id="owner_name">
						<input type="hidden" name="arp_no" id="arp_no">
						<!--<div class="form-inline">
							<div class="form-group">
								<label>Month</label>
								<select class="form-control" id="mnth"> -->
									<?php
										// $mnths = ['ALL', 'January', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
									?>
									{{-- @foreach($mnths as $i => $m) --}}
										<!-- <option value="{{-- $i --}}">{{-- $m --}}</option> -->
									{{-- @endforeach --}}
								<!-- </select>
							</div>
							
							<div class="form-group pull-right">
								<label>Year</label>
								<input type="number" id="year" class="form-control">
							</div>
						</div>
						<br>
						<div class="modal-footer">
							<button type="button" class="btn btn-success pull-right" id="submit_btn">Submit</button>
						</div> -->
					</form>
				<!-- </div>
			</div>
		</div>
	</div> -->
@endsection

@section('js')
{{ Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js') }}
{{ Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js') }}

<script type="text/javascript">
var isTableProcessed = 0;
var processingOnce = 0;
	$.fn.getRecords = function(isPaid) {
		if($.fn.DataTable.isDataTable('#records')) {
			$('#records').DataTable().destroy();
		}
		$('#records').on( 'processing.dt', function ( e, settings, processing ) {
			if (processing) {
				isTableProcessed = 0;
				processingOnce++;
				console.log('processing');
				seconds = 0;
				if (processingOnce < 2) {
					console.log(processingOnce);
				var waitTimer = setInterval(function(){
						seconds++;
						if (isTableProcessed == 1) {
							clearInterval(waitTimer);
						}
						if (seconds == 15) {
								showMessage('Hello! This could really take some time. I hope you are doing good today!');
						}
						if (seconds >= 30) {
							if ( seconds % 15 == 0 ) {
								showMantra(Math.floor((Math.random() * 10) + 1));
							}
						}
					},1000);
					
				}
				$('.dataTables_filter').find('input[type=search]').attr('disabled', 'disabled');
			}else{
				console.log('processed');
				seconds = 0;
				isTableProcessed = 1;
				processingOnce = 0;
				$('.dataTables_filter').find('input[type=search]').removeAttr('disabled');
			}
			// $('#processingIndicator').css( 'display', processing ? 'block' : 'none' );
		} ).DataTable({
			processing: true, 
			serverSide: false,
			deferRender: true,
			ajax: {
				url: "{{ route('rpt.records_dt', ['isPaid' => 'isPaid']) }}".replace('isPaid', isPaid),
				data: {
					'mun' : $('#mun').val(),
					'brgy' : $('#brgy').val()
				}
			},
			columns: [
				{ data: 'arp_no', name: 'arp_no' },
				{ data: 'owner_name', name: 'owner_name' },
				{ data: 'owner_address', name: 'owner_address' },
				{ data: 'property_mnc', name: 'property_mnc' },
				{ data: 'property_brgy', name: 'property_brgy' },
				{ data: 'property_details', name: 'property_details' },
				{ data: null, render: function(data) {
					// return '<button type="button" class="btn btn-info" id="rpt_rec" value="'+data.view_link+'"><i class="fa fa-eye"></i> View</button>\
					// 	<input type="hidden" value="'+data.owner_name+'" id="data_ownr_name">\
					// 	<input type="hidden" value="'+data.arp_no+'" id="data_arp_no">';
					return '<a class="btn btn-info" id="rpt_rec" href="'+data.view_link+'"><i class="fa fa-eye"></i> View</button>';
				} }
			],
		});
	}
	// $.fn.getRecords();
	showMessage('Please select the municipality you want to filter and press SHOW to display the table.');
	$(document).on('click', '.dataTables_filter', function(){
		if ($(this).find('input[type=search]').prop('disabled')) {
			showMessage('Please wait while we get the informations you need. Thank you.', 1);
		}
	});
	var idleTimer = setInterval(function(){
		if (! $.fn.DataTable.isDataTable( '#records' )) {
			showMessage('Hello! Seems like you haven\'t selected a filter yet. Please select a municipality and click SHOW to get started.');
		}
	},30*1000);

function showMantra(number){
	switch (number) {
		case 1:
			showMessage('"Be a magnet for joy, love and abundance."')
			break;

		case 2:
			showMessage('"Let faith lead the way."');
			break;
	
		case 3:
			showMessage('How\'s you\'re day going?');
		break;

		case 4:
			showMessage('That haircut looks good on you!');
		break;

		case 5:
			showMessage('"Patience is bitter, but its fruit is sweet. - Aristotle"');
		break;

		case 6:
			showMessage('"A man who masters patience masters everything else."');
		break;

		case 7:
			showMessage('"Our patience will achieve more than our force." - Edmund Burke');
		break;

		case 8:
			showMessage('"Patience is when you\'re supposed to get mad, but you choose to understand" - Anonymous');
		break;

		case 9:
			showMessage('"Patience attracts happiness; it brings near that which is far" - Swahili Proverb');
		break;

		case 10:
			showMessage('"With love and patience, nothing is impossible." - Daisaku Ikeda');
		break;

		default:
			break;
	}
}

$('#mun').change(function(){
	$.ajax({
		type: 'post',
		url: '{{ route("collection.ajax") }}',
		data: {
			'_token': '{{ csrf_token() }}',
			'action': 'get_barangays',
			'input': $('#mun').val(),
		},
	}).done(function(response){
		$('#brgy').html('');
		$.each( response, function(key, brgy) {
			if(brgy.id == brgy) {
				$('#brgy').append($('<option>', {
					'data-code':brgy.code,
					value: brgy.id,
					text: brgy.name,
					selected: true
				}));
			} else {
				$('#brgy').append($('<option>', {
					'data-code':brgy.code,
					value: brgy.id,
					text: brgy.name
				}));
			}
		});
	})
});


	// $(document).on('click', '#rpt_rec', function() {
	// 	// $('#mnth_year').modal('toggle');
	// 	var link = $(this).val();
	// 	$('#select_form').attr('action', link);

	// 	var this_ownr_name = $(this).siblings().eq(0).val();
	// 	var this_arp_no = $(this).siblings().eq(1).val();
	// 	$('#owner_name').val(this_ownr_name);
	// 	$('#arp_no').val(this_arp_no);

	// 	var link = $('#select_form').attr('action');
	// 	$('#select_form').attr('action', link);
	// 	$('#select_form').submit();
	// });

	// $(document).on('click', '#submit_btn', function() {
		// var link = $('#select_form').attr('action');
		// var mnth = $('#mnth').val();
		// var year = $('#year').val();
		// var link2 = link.replace('month', mnth);
		// var link3 = link2.replace('year', year);

	// 	$('#select_form').attr('action', link);
	// 	$('#select_form').submit();
	// });
</script>
@endsection