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
		background: white;
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
    .popup {
        background-color: #38c172;
        color: #fff !important;
        position: fixed;
        bottom: 0;
        right: 0;
        padding: 0.75rem 1.25rem;
        border-radius: 0.25rem;
        margin: 0.75rem;
        z-index: 1030;
    }
    .btn{
        outline: none !important; 
        padding: 10px;
        border-radius: 20px;
    }
</style>
@endsection

@section('content')
<div class="popup" style="display:none;"></div>
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

<div id="excel-container" style="padding-top: 30px; width: 100%; height: 70%">


</div>

<button class="btn btn-success" style="display:none; float:right; margin-top: 20px" id="save-import">Save Import</button>
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
                    $('#excel-container').html(data.html);
                }else{
                    showMessage(data.message, 1);
                }
                $excelContainer.css('overflow-x', 'scroll');
                $('#save-import').show();
            }).fail(function(){
                showMessage('Sorry, something went wrong. Please refresh the page.', 1);
            }).always(function(){
                $importButton.find('.fa-spinner').css('display', 'none');
            });
        }else{
            showMessage('Sorry, you haven\'t chosen a file yet. Please choose a file first before pressing the button.', 1);
        }
    });
    
    function showMessage(message, type = 0) {
        $message = $('.popup');
        if (type == 0) {
            $message.css('background-color', '#38c172');
        }else{
            $message.css('background-color', '#C0392B');
        }
        // (type == 0 ?  : $message.css('background-color', '#C0392B'));
        $message.html(message).slideDown();
        setTimeout(function(){
            $message.slideUp();
        },7000);
    }

</script>
@endsection