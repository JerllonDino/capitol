@extends('nav')

@section('css')
<style>
	.table td{
		padding:2px !important;
		font-size: 10px;
		margin:0;
		width: 50px;
	}
	.table thead tr th{
		padding:2px !important;
		font-size: 10px;
		background: #d9fffe;
		position: sticky;
		top:50px;
	}

	.table thead{
		background-color: white;
	}
	
	#imports{
		padding:10px;
		border-radius: 20px;
		cursor: pointer;
		outline:none;
	}

	#importExcel {
		display: flex;
		flex-flow: row wrap;
		align-items: center;
	}
    
    .btn{
        outline: none !important; 
        padding: 10px;
        border-radius: 20px;
    }
</style>
@endsection

@section('content')

{{-- <form enctype="multipart/form-data" id="importExcel" method="post" action="{{ route('rpt.import_excel_report') }}" class="form-inline"> --}}
<form enctype="multipart/form-data" id="importExcel" method="post" action="" class="form-inline">
    {{ csrf_field() }}
    <input type="file" name="imports" id="imports" class="btn btn-warning">
    <select name="excel_municipality" id="excel-municipality" class="form-control" style="margin-left: 3%; border-radius: 20px; outline: none;">
        @foreach($base['municipality'] as $m)
            <option value="{{ $m->id }}">{{ $m->name }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn btn-primary" style="margin-left: 3%; " id="submitExcel"> <i class="fa fa-spinner fa-spin" style="display: none"></i> &nbsp;<i class="fa fa-upload"></i> Upload Excel file</button>
</form>
<button class="btn btn-secondary" style="border-radius: 50%; float: right"><i class="fa fa-question-circle"></i></button>
<div id="excel-container" style="padding-top: 30px; width: 100%; height: 70%">


</div>

<button class="btn btn-success" style="display:none; margin-top: 20px; position: absolute; right: 0;" id="save-import"><i class="fa fa-save"></i> Save Import</button>
@endsection

@section('js')
{{ Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js') }}
{{ Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js') }}

<script type="text/javascript">
    $excelImport = $('#importExcel');
	$excelContainer = $('#excel-container');
    $importButton = $('#submitExcel');
    
	$excelImport.submit(function(e){
        e.preventDefault();
        $excelContainer.html('');
        $('#save-import').hide();
		data = new FormData(this);
        if ($('#imports').val()) {
            $.ajax({
                url: '{{ route("rpt.view_excel_report") }}',
                method: 'POST',
                data: data,
                contentType: false,
                cache: false,
                processData:false,
                beforeSend: function(){
                    $importButton.find('.fa-spinner').removeAttr('style');
                },
            }).done(function(data){
                if (data.html) {
                    $excelContainer.html(data.html);
                    $('#save-import').show();
                }else{
                    showMessage(data.message, 1);
                }
                // $excelContainer.css('overflow-x', 'scroll');
                
            }).fail(function(){
                showMessage('Sorry, something went wrong. Please refresh the page and try again.', 1);
            }).always(function(){
                $importButton.find('.fa-spinner').css('display', 'none');
            });
        }else{
            showMessage('Sorry, you haven\'t chosen a file yet. Please choose a file first before pressing the button.', 1);
        }
    });
    


</script>
@endsection