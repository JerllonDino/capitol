@extends('nav')

@section('css')
<style>
	.modal-content{
		padding: 1%;
		overflow: scroll;
	}

	.modal .table td{
		padding:2px;
		font-size: 10px;
		margin:0;
	}
	@media (min-width: 1200px) {
	.modal-xlg {
		width: 100%; 
	}
	}
</style>
@endsection

@section('content')
<h4>Import Municipality RPT Report</h4>

<form enctype="multipart/form-data" id="importExcel" method="post" action="">
	{{ csrf_field() }}
	<input type="hidden" name="base64">
    <input type="file" name="imports" id="imports" class="btn btn-success">
    <input type="submit" value="Import Excel" class="btn btn-primary" id="submitExcel">
</form>
	<div class="form-inline">
		<label>MUNICIPALITY </label>
		<select name="mun" id="mun" class="form-control">
			@foreach($base['municipality'] as $m)
				<option value="{{ $m->id }}">{{ $m->name }}</option>
			@endforeach
		</select>
		<button class="btn btn-default" id="show" onclick="$.fn.getRecords()">SHOW</button>
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
	<div class="modal fade bd-example-modal-lg excelModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-xlg">
		  <div class="modal-content">
			...
		  </div>
		</div>
	  </div>
@endsection

@section('js')
{{ Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js') }}
{{ Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js') }}

<script type="text/javascript">
	$.fn.getRecords = function() {
		if($.fn.DataTable.isDataTable('#records')) {
			$('#records').DataTable().destroy();
		}
		$('#records').DataTable({
			processing: true, 
			serverSide: false,
			ajax: {
				url: "{{ route('rpt.records_dt') }}",
				data: {
					'mun' : $('#mun').val(),
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
			]
		});
	}
	$.fn.getRecords();
	$excelImport = $('#importExcel');
	$excelImport.submit(function(e){
        e.preventDefault();
		data = new FormData(this);

        $.ajax({
            url: '{{ route("rpt.import_excel_report") }}',
			method: 'POST',
            data: data,
			contentType: false,
			cache: false,
			processData:false,
            beforeSend: function(){
                
            },
        }).done(function(data){
			// console.log(data.html);
			if (data.html) {
				$('.excelModal').find('.modal-content').html(data.html);
			}else{
				$('.excelModal').find('.modal-content').html(data.message);
			}
            
			$('.excelModal').modal('show');
        }).fail(function(){

        });
	});
	
	$excelImport.on('change', '[name="imports"]', function () {
		var file = $(this);
		let reader = new FileReader();
		let fileName = file[0].files[0].name;
		
		reader.addEventListener("load", function (e) {
			base64 = e.currentTarget.result;
			$("[name='base64']").data('base64', base64);
			
		});
		reader.readAsDataURL(file[0].files[0]);
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