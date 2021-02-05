@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
{{ Html::style('/base/sweetalert/sweetalert2.min.css') }}
<style>
    .td_amt {
        width: 150px;
    }
    .td_nature {
        width: 450px;
    }
    .ui-autocomplete {
        max-height: 200px;
        overflow-y: scroll;
        overflow-x: hidden;
    }
    .autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
    .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
    .autocomplete-selected { background: #F0F0F0; }
    .autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
    .autocomplete-group { padding: 2px 5px; }
    .autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
    .select2-container{ width:100% !important; }

    .hide{
        display:none;
    }
</style>
@endsection

@section('content')
@if ( Session::get('permission')['col_cash_division'] & $base['can_write'] )

<button class="btn btn-primary toggle-col" style="margin-bottom: 20px" data-toggle="collapse" data-target="#rpt-dt" disabled>Municipal RPT Records</button>

<div class="table collapse" id="rpt-dt" style="overflow-y: scroll;">
    <table class="table table-hovered" id="imported-excel" style="margin-top: 20px; width: 100%;">
        <thead>
            <th>Year</th>
            <th>Month</th>
            <th>Municipality</th>
            <th>Date Imported</th>
            <th>Action</th>
        </thead>
    </table>
</div>

<div class="row">
    {{ Form::open(['method' => 'POST', 'route' => ['cash_division.store']]) }}
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>User</dt>
            <dd>{{ $base['user']->realname }}</dd>
        </dl>
        <input type="hidden" class="form-control" name="user_id" id="user_id" value="{{ $base['user']->id }}">
        <input type="hidden" class="form-control" name="transaction" id="transaction" value="cash_division">
    </div>



    <div class="form-group col-sm-6">
        <label for="date">Date</label>
        <input type="text" class="form-control datepicker" name="date" value="{{ date('m/d/Y') }}" required autofocus>
    </div>

    <select style="display:none" class="form-control" id="form" name="form" readonly>
        <option selected disabled></option>
        @foreach ($base['form'] as $form)
            @if( $form->id == '1')
                <option value="{{ $form->id }}" selected>{{ $form->name }}</option>
            @endif
        @endforeach
    </select>

    

    <div class="form-group col-sm-6">
        <label for="serial_id">Series</label>
        <select class="form-control" name="serial_id" id="serial_id" disabled required>
        </select>
    </div>

    {{-- <div class="form-group col-sm-6">
        <label for="refno">Reference No.</label>
        <!--<input type="text" class="form-control" name="refno" value="" required>-->
         <textarea class="form-control" name="refno" value="" rows="2" required></textarea> 
    </div> --}}

    <div class="form-group col-sm-6">
        <label for="municipality">Municipality</label>
        <select class="form-control" name="municipality" id="municipality">
            <option selected></option>
            @foreach($base['municipalities'] as $municipality)
                <option value="{{ $municipality['id'] }}">{{ $municipality['name'] }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-6">
        <label for="barangay">Barangay</label>
        <select class="form-control" name="brgy" id="brgy" disabled>
        </select>
    </div>

     <div class="form-group col-sm-6">
        <label for="customer">Payor/Customer</label>
        <input type="text" class="form-control" name="customer" id="customer" >
        <input type="hidden" class="form-control" name="customer_id" id="customer_id">
    </div>

    <div class="form-group col-sm-4">
        <label for="customer_type">Client Type</label>
             <select class="form-control" name="customer_type" id="customer_type">
            <option ></option>
            @foreach($base['sandgravel_types'] as $sandgravel_types)
                <option value="{{ $sandgravel_types['id'] }}">{{ $sandgravel_types['description'] }}</option>
            @endforeach
            </select>
    </div>

    <div class="form-group col-sm-2">
        <label for="municipality">Sex</label>
        <select class="form-control" name="Sex" id="Sex" >
            <option selected></option>
            <option value="female">Female</option>
            <option value="male">Male</option>

        </select>
    </div>

    <div class="form-group col-sm-12">
        <table class="table" id="table">
            <thead>
                <tr>
                    <th colspan="2">Account</th>
                    <th class="td_nature">Nature</th>
                    <th>Amount</th>
                    <th><button id="add_row" class="btn btn-sm btn-success" data-transactionType="1" type="button"><i class="fa fa-plus"></i></button></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td><input type="text" id="total" class="form-control" readonly></td>
                    <td></td>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td>
                        <input type="text" class="form-control account" required>
                        <input type="hidden" class="form-control" name="account_id[]">
                        <input type="hidden" class="form-control" name="account_type[]">
                        <input type="hidden" class="form-control account_is_shared" value="0" name="account_is_shared[]">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info account_addtl" disabled>Select</button>
                        <input type="hidden" class="form-control">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="nature[]" maxlength="300" required>
                    </td>
                    <td class="td_amt">
                        <input type="number" class="form-control amounts" name="amount[]" min="0" step="0.01" required>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success" name="button" id="confirm">Add</button>
    </div>
    {{ Form::close() }}
</div>
<hr>

<div id="account_panel">
</div>

<div class="panel panel-default">
    <div class="panel-heading"><b>Adjustment</b></div>
    <div class="panel-body">
        <form action="{{ route('cashdiv.adjustment_add') }}" method="post" autocomplete="off">
            {{ csrf_field() }}
            <div class="form-group col-md-3">
                <label>Year</label>
                <select class="form-control" name="adj_yr" required> 
                    <option></option>
                    <?php
                        $year = \Carbon\Carbon::now()->format('Y');
                        for (; $year > 2015; $year--) { 
                            echo "<option value='".$year."'>".$year."</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>Month</label>
                <select class="form-control" name="adj_mnth" required>
                    <option></option>
                    <?php 
                        $month = ['1'=>'January', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                        foreach ($month as $key => $val) { 
                            echo "<option value='".$key."'>".$val."</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>Type</label>
                <select class="form-control" name="adj_type" required>
                    <option></option>
                    <option value="OPAg">OPAg</option>
                    <option value="PVET">PVET</option>
                    <option value="COLD CHAIN">COLD CHAIN</option>
                    <option value="CERTIFICATIONS OPP - DOJ">CERTIFICATIONS OPP - DOJ</option>
                    <option value="PROVINCIAL HEALTH OFFICE">PROVINCIAL HEALTH OFFICE</option>
                    <option value="RPT">RPT</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label>Adjustment Amount</label>
                <input type="number" step="0.01" name="adj_amt" class="form-control" required>
            </div>
            <br>
            <button class="btn btn-success" type="submit">Add</button>
            <a href="{{ route('cashdiv.adjustment_view') }}" class="btn btn-info">View Adjustments</a>
        </form>
    </div>
</div>
@endif
@if ( Session::get('permission')['col_cash_division'] & $base['can_read'] )
<table id="seriallist" class="table table-striped table-hover" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>User</th>
            <th>Date</th>
            <th>REFNO</th>
            <th>CUSTOMER</th>
            <th>MUNICIPALITY</th>
            <th>Actions</th>
        </tr>
    </thead>
</table>
@endif
@endsection

@section('js')
{{ Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js') }}
{{ Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js') }}
{{ Html::script('/base/sweetalert/sweetalert2.min.js') }}
<script type="text/javascript">
var rowCounter = 1;
$('#add_row').click(function(){
    rowCounter = rowCounter + 1;
    console.log(rowCounter);
});
$(document).on('click', '.rem_row', function(){
    rowCounter = rowCounter-1;
    console.log(rowCounter);
})
importedExcelDatatable()
 $.fn.loadTable = function(){
  if ( $.fn.DataTable.isDataTable('#seriallist') ) {
  $('#seriallist').DataTable().destroy();
}
    $('#seriallist').dataTable({
        dom: '<"dt-custom">frtip',
        processing: true,
        serverSide: true,
        ajax: '{{ route("collection.datatables", "cash_division") }}',
        columns: [
            { data: 'realname', name: 'realname' },
            { data:
                function(data) {
                    var date = new Date(data.date_of_entry);
                    var month = date.toLocaleString('en-us', {month: 'long'});
                    return month +' '+ date.getDate() +', '+ date.getFullYear();
                }
            },
            { data: 'refno', name: 'refno' },
            { data: 'customer_name', name: 'customer_name' },
            { data: 'name', name: 'name' },
            { data:
                function(data) {
                    var view = '';
                    var write = '';
                    var deletez = '';
                    @if ( Session::get('permission')['col_cash_division'] & $base['can_read'] )
                    view = '<a href="{{ route('cash_division.index') }}/'+data.id+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                    @endif
                    @if ( Session::get('permission')['col_cash_division'] & $base['can_write'] )
                    write = '<a href="{{ route('cash_division.index') }}/'+data.id+'/edit" class="btn btn-sm btn-info datatable-btn" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                    @endif
                    @if ( Session::get('permission')['col_cash_division'] & $base['can_write'] )
                    if(data.deleted_at == null){
                        deletez = view + write+'<button onclick="$(this).deolete(\''+data.id+'\');"  class="btn btn-sm btn-danger datatable-btn" title="Edit"><i class="fa fa-trash"></i></button>';
                    }else{
                        deletez = '<button onclick="$(this).restore(\''+data.id+'\');"  class="btn btn-sm btn-warning datatable-btn" title="Edit"><i class="fa fa-undo"></i></button>';
                    }

                    @endif
                    return deletez;
                },
                bSortable: false,
                searchable: false,
            },
        ]
    });
}
    function getMonthName(monthNumber) {
        var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        return months[monthNumber - 1];
    }
    
    function importedExcelDatatable()
    {
        if($.fn.DataTable.isDataTable('#imported-excel')) {
            $('#imported-excel').DataTable().destroy();
        }
        $('#imported-excel').DataTable({
            processing: true, 
            serverSide: false,
            deferRender: true,
            order: [[ 0, 'desc' ]],
            ajax: {
                url: "{{ route('collection.datatables', 'cash_municipal_rpt_excel') }}",
            },
            columns: [
                { data: 'report_year', name: 'report_year' },
                { data: null, render: function(data){
                    return getMonthName(data.report_month);
                } },
                { data: 'municipality_name', name: 'municipality_name' },
                { data: 'created_at', name: 'created_at' },
                { data: null, render: function(data) {
                    var basicNet = 0;
                    var basicPenalty = 0;
                    var sefNet = 0;
                    var sefPenalty = 0;
                    data.excel_items.forEach(element => {
                        basicNet = basicNet + 
                            parseFloat(element.basic_advance_gross) +
                            parseFloat(element.basic_current_gross) +
                            parseFloat(element.basic_immediate) +
                            parseFloat(element.basic_prior_1991) +
                            parseFloat(element.basic_prior_1992) +
                             - parseFloat(element.basic_advance_discount) + parseFloat(element.basic_current_discount);
                        basicPenalty = basicPenalty + (
                            parseFloat(element.basic_penalty_1991) +
                            parseFloat(element.basic_penalty_1992) +
                            parseFloat(element.basic_penalty_current) +
                            parseFloat(element.basic_penalty_immediate)
                        );
                        sefNet = sefNet + 
                            parseFloat(element.sef_advance_gross) +
                            parseFloat(element.sef_current_gross) +
                            parseFloat(element.sef_immediate) +
                            parseFloat(element.sef_prior_1991) +
                            parseFloat(element.sef_prior_1992) +
                             - parseFloat(element.sef_advance_discount) + parseFloat(element.sef_current_discount);
                        sefPenalty = sefPenalty + (
                            parseFloat(element.sef_penalty_1991) +
                            parseFloat(element.sef_penalty_1992) +
                            parseFloat(element.sef_penalty_current) +
                            parseFloat(element.sef_penalty_immediate)
                        );
                    });
                    console.log(parseFloat(basicNet).toFixed(2) + ', ' + parseFloat(basicPenalty).toFixed(2) + ', ' + parseFloat(sefNet).toFixed(2) + ', ' + parseFloat(sefPenalty).toFixed(2));

                    var values = {
                        created_at: data.created_at,
                        id: data.id,
                        is_printed: data.is_printed,
                        municipal: data.municipal,
                        municipality_name: data.municipality_name,
                        report_month: data.report_month,
                        report_year: data.report_year,
                        updated_at: data.updated_at
                    }
                    
                    return `
                    <button class="btn btn-info add-report basic `+ (data.is_printed_basic == 1 ? "hide" : "" ) +`" data-netvalue="`+basicNet+`" data-penaltyvalue="`+basicPenalty+`" data-values='`+JSON.stringify(values)+`'><i class="fa fa-spinner fa-spin" style="display:none"></i> <i class="fa fa-plus"></i> Basic</button>
                    <button class="btn btn-info add-report sef `+ (data.is_printed_sef == 1 ? "hide" : "" ) +`" data-netvalue="`+sefNet+`" data-penaltyvalue="`+sefPenalty+`" data-values='`+JSON.stringify(values)+`'><i class="fa fa-spinner fa-spin" style="display:none"></i> <i class="fa fa-plus"></i> SEF</button>
                    `;
                } }
            ],
        });
    }

    $('#imported-excel').on('click', '.add-report', function(){
        $('#rpt-dt').collapse('hide');
        var values = $(this).data('values');
        $('.datepicker').val(values.updated_at);
        $('textarea[name="refno"]').val(values.municipality_name + "-" + values.report_month + "-" + values.report_year);
        $('#municipality').val(values.municipal);
        $('#customer').val(values.municipality_name);
        $('#customer_type').val(16);
        var rpt_net_data = {
            account_id : 2,
            account_type : 'title',
            account_title : "Real Property Tax-Basic (Net of Discount)",
            rpt_value : $(this).data('netvalue').toFixed(2),
            rpt_type : '',
            rpt_id : values.id
        };
        var rpt_penalty_data = {
            account_id : 0,
            account_type : 'title',
            account_title : "",
            rpt_value : $(this).data('penaltyvalue').toFixed(2),
            rpt_type : '',
            rpt_id : values.id
        };
        var html = '';

        if ($(this).hasClass('basic')) {
            rpt_penalty_data.account_id = 54;
            rpt_penalty_data.rpt_type = 'basic';
            rpt_net_data.rpt_type = 'basic';
            rpt_penalty_data.account_title = "Tax Revenue-Fines & Penalties-Real Property Taxes (General Fund-Proper)";
        }else{
            rpt_penalty_data.rpt_type = 'sef';
            rpt_net_data.rpt_type = 'sef';
            rpt_penalty_data.account_id = 55;
            rpt_penalty_data.account_title = "Tax Revenue-Fines & Penalties-Real Property Taxes  (Special Education Fund (SEF))";
        }
        console.log(rpt_net_data);
        if(rowCounter == 1){
            $element = $('#table').find('tbody').find('tr').find('td').find('.account');
            $element.val(rpt_net_data.account_title);
            $element.attr('disabled', 'disabled');
            $element.next('input').val(rpt_net_data.account_id);
            $element.next('input').next('input').val(rpt_net_data.account_type);
            $element.parent().next('td').next('td').find('input').val(rpt_net_data.account_title);
            $element.parent().next('td').next('td').find('input').attr('readonly', 'readonly');
            $element.parent().next('td').next('td').next('td').find('input').val(rpt_net_data.rpt_value);
            $('#table').find('tbody').find('input[name="account_id"]').val(rpt_net_data.account_id);
            $('#table').find('tbody').find('input[name="account_type"]').val(rpt_net_data.account_type);
            $element.parent().next('td').next('td').next('td').next('td').append('<input type="hidden" name="rpt_value" value="'+rpt_net_data.rpt_type + '-' + rpt_net_data.rpt_id +'"/>');
            $element.parent().next('td').next('td').next('td').next('td').append('<button type="button" class="btn btn-primary btn-sm" id="clear_row"><i class="fa fa-minus"></i></button>');
            $('#table').find('tbody').append(rptHtml(rpt_penalty_data));
            rowCounter = rowCounter + 2;
        }else{
            html = rptHtml(rpt_net_data) + rptHtml(rpt_penalty_data);
            $('#table').find('tbody').append(html);
            rowCounter = rowCounter + 2;
        }
        $.fn.natureAutoComplete();
        compute_total();
    });

    function rptHtml(rpt_data){
        return `
                <tr>
                    <td>
                        <input type="text" class="form-control account" value="`+rpt_data.account_title+`"" required>
                        <input type="hidden" class="form-control" name="account_id[]" value="`+rpt_data.account_id+`">
                        <input type="hidden" class="form-control" name="account_type[]" value="`+rpt_data.account_type+`">
                        <input type="hidden" class="form-control account_is_shared" value="0" name="account_is_shared[]">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info account_addtl" disabled>Select</button>
                        <input type="hidden" class="form-control">
                        <input type="hidden" class="form-control account_rate" name="account_rate[]" value="0">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="nature[]" maxlength="300" value="`+rpt_data.account_title+`" required>
                    </td>
                    <td class="td_amt">
                        <input type="number" class="form-control amounts" name="amount[]" value="`+rpt_data.rpt_value+`" step="0.01" required>
                    </td>
                    <td>
                        <input type="hidden" name="rpt_value" value="`+rpt_data.rpt_type + `-` + rpt_data.rpt_id +`"/>
                        <button type="button" class="btn btn-warning btn-sm rem_row"><i class="fa fa-minus"></i></button>
                    </td>
                </tr>
            `
    }
    
    $(document).on('click', '#clear_row', function(){
        $element = $('#table').find('tbody').find('tr td:nth-child(1)');
        $element.find('.account').val('');
        $element.find('.account').removeAttr('disabled');
        $element.find('.account').next('input').val('');
        $element.find('.account').next('input').next('input').val('');
        $element.next('td').next('td').find('input').val('');
        $element.next('td').next('td').find('input').removeAttr('readonly');
        $element.next('td').next('td').next('td').find('input').val('');
        $element.next('td').next('td').next('td').next('td').find('input').remove();
        $element.next('td').next('td').next('td').next('td').find('button').remove();
        rowCounter = rowCounter - 1;
        compute_total();
    });

    $.fn.deolete = function(deleteid){
        swal({
              title: 'Are you sure?',
              text: "",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#a22314',
              cancelButtonColor: '#c9bebe',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.value) {
                    $.ajax({
                        url: '{{ route("cash_div.delete") }}',
                        type: 'POST',
                        data:{
                          cash_div: deleteid,
                          _token: '{{ csrf_token() }}'
                        },
                        dataType: 'JSON',
                        success: (data) => {
                        }
                    });
                swal({
                      title: 'Deleted!',
                      text: 'Cash Div Data deleted',
                      timer: 1000,
                      onOpen: () => {
                        swal.showLoading()
                      }
                    }).then((result) => {
                      if (result.dismiss === 'timer') {
                        $.fn.loadTable();
                      }
                    })


              }
            });

    };
    $.fn.restore = function(deleteid){
        swal({
              title: 'Are you sure?',
              text: "",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#a22314',
              cancelButtonColor: '#c9bebe',
              confirmButtonText: 'Yes, restore it!'
            }).then((result) => {
              if (result.value) {
                    $.ajax({
                        url: '{{ route("cash_div.restore") }}',
                        type: 'POST',
                        data:{
                          cash_div: deleteid,
                          _token: '{{ csrf_token() }}'
                        },
                        dataType: 'JSON',
                        success: (data) => {
                        }
                    });
                swal({
                      title: 'Restored!',
                      text: 'Cash Div Data restored',
                      timer: 1000,
                      onOpen: () => {
                        swal.showLoading()
                      }
                    }).then((result) => {
                      if (result.dismiss === 'timer') {
                        $.fn.loadTable();
                      }
                    })


              }
            });

    };
     $.fn.loadTable();
     $('#rpt-dt').on('shown.bs.collapse', function () {
         console.log('test');
        $(this).css('height', '200px');
    });
</script>
{{ Html::script('/vendor/autocomplete/jquery.autocomplete.js') }}
<script type="text/javascript">
    var collection_type = 'show_in_cashdivision';
</script>
@include('collection::shared.transactions_js')
@endsection
