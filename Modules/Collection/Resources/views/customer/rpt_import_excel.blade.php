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

    #import-excel{
        float:right;
        cursor: pointer;
    }

    #imgViewer::-webkit-scrollbar {
    -webkit-appearance: none;
    height: 10px;
}
#imgViewer::-webkit-scrollbar-thumb {
    border-radius: 5px;
    background-color: rgba(0,0,0,.5);
    box-shadow: 0 0 1px rgba(255,255,255,.5);
}

</style>
@endsection

@section('content')

{{-- <form enctype="multipart/form-data" id="importExcel" method="post" action="{{ route('rpt.import_excel_report') }}" class="form-inline"> --}}
<h1 id="import-excel" data-toggle="modal" data-target="#modalHelp"><i class="fa fa-question-circle"></i></h1>
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
<form action="{{ route('rpt.save_excel_report') }}" id="excel-form" method="POST">
    {{ csrf_field() }}
    <input type="hidden" name="excel-data">
    <input type="hidden" name="excel_municipality">
    <button type="submit" class="btn btn-success" style="display:none; margin-top: 20px; position: absolute; right: 0;" id="save-import"><i class="fa fa-save"></i> Save Import</button>
</form>
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalHelp">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">A guide to importing municipal report excel file to the system</h4>
          </div>
        <div class="modal-body">
            <h3>Step 1: Checking and validating the excel file</h3>
            <h4>Please check the contents of the excel file if it meets the requirements before uploading it to the system. The requirements are as follows:</h4>

            <div class="panel">
                <div id="imgViewer" style="overflow-x: scroll;">
                    <img src="{{ asset('asset/images/import-guide/step-1.1.png') }}" alt="" class="guide-images">
                </div>
                <h5>Make sure that the table headers of the excel file you are uploading have the exact same header and exact letter column position as showed from the figure above. (Click the image to enlarge.)</h5>
            </div>

            <div class="panel">
                <img src="{{ asset('asset/images/import-guide/step-1.2.png') }}" alt=""><img src="{{ asset('asset/images/import-guide/step-1.8.png') }}" alt="">
                <h5>It is mandatory to put dates on every record for the system to recognize every data in the excel file. The format to be used to input dates in the excel file should be DD/MM/YYYY, not the word.</h5>
            </div>

            <div class="panel">
                <h4>Column skipping if under the same Official receipt number</h4>
                <img src="{{ asset('asset/images/import-guide/step-1.5.png') }}" alt="">
                <h5>The columns allowed to be skipped are the Name of the Taxpayor and the OR number. (Refer to the above figure)</h5>
            </div>

            <div class="panel">
                <h4>No using of abbreviations in barangay names</h4>
                <img src="{{ asset('asset/images/import-guide/step-1.6.png') }}" alt="">
                <h5>Type the whole word when adding barangay names to a record in the excel file</h5>
            </div>

            <h3>Step 2: Uploading  the excel file</h3>
            <div class="panel">
                <img src="{{ asset('asset/images/import-guide/choose.png') }}" style="width: 100%" alt="">
                <h4>Click the choose file button. A new window will appear. In this window, you will now locate and open the excel file you will upload.</h4>
            </div>
            <div class="panel">
                <img src="{{ asset('asset/images/import-guide/muni.png') }}" style="width: 100%" alt="">
                <h4>Once the file has been selected or if you can see the name of the file appeared in the choose file button, select what municipality the report belongs to.</h4>
            </div>
            <div class="panel">
                <img src="{{ asset('asset/images/import-guide/upload.png') }}" style="width: 100%" alt="">
                <h4>Once the proper municipality has been selected. Click the Excel File Button.</h4>
            </div>

            <div class="panel">
                <img src="{{ asset('asset/images/import-guide/success1.png') }}" style="width: 100%" alt="">
                <h4>If there were no errors, a table should appear below the form inputs containing the data from the excel file.</h4>
                <h4>Click Save Import if everything is correct.</h4>
            </div>
            
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success" data-dismiss="modal">Ok, I understand</button>
        </div>
         
      </div>
    </div>
  </div>

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
                    console.log(data.municipality)
                    $('#excel-form').find('input[name="excel-data"]').val(JSON.stringify(data.data));
                    $('#excel-form').find('input[name="excel_municipality"]').val(data.municipality);
                    $('#save-import').show();
                }else{
                    showMessage(data.message, 1);
                }
            }).fail(function(error){
                showMessage('Sorry, something went wrong. Please refresh the page and try again.', 1);
            }).always(function(){
                $importButton.find('.fa-spinner').css('display', 'none');
            });
        }else{
            showMessage('Sorry, you haven\'t chosen a file yet. Please choose a file first before pressing the button.', 1);
        }
    });

    // $('#excel-form').submit(function(){
    //     $.ajax({
    //         url: '{{ route("rpt.view_excel_report") }}',
    //         method: 'POST',
    //         data: $(this).serialize(),
    //         beforeSend: function(){

    //         }
    //     }).fail(function(){

    //     }).done(function(data){
            
    //     });
    // });


</script>
@endsection