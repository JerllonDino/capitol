@extends('nav')

@section('css')
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

     #sg_booklets{
        background: burlywood;
    }

    .btn-green{
        color : #fff;
        background-image: linear-gradient(to bottom,#73e641 0,#4a9c18 100%);
    }

    .btn-gray{
        color : #fff;
        background-image: linear-gradient(to bottom,#959294 0,#625e61 100%);
    }


    .btn-pink{
        background-image: linear-gradient(to bottom,#f66adc 0,#b11faa 100%);
    }

    .btn-gray{
        color : #fff;
        background-image: linear-gradient(to bottom,#959294 0,#625e61 100%);
    }

    .btn-green{
        color : #fff;
        background-image: linear-gradient(to bottom,#73e641 0,#4a9c18 100%);
    }

    .btn-red{
        color : #fff;
        background-image: linear-gradient(to bottom,#ff0009 0,#9e1523 100%);
    }

    .btn-another{
        color:#fff;
        background-image: linear-gradient(to bottom,#229568 0,#0b470e 100%);
    }

    .btn-another-none{
        color:#fff;
        background-image: linear-gradient(to bottom,#5a755d 0,#435744 100%);
    }

    #sg_booklets{
        background: burlywood;
    }

    .bg-cert-ttc{
        background-image: linear-gradient(to bottom,#0b147d 0,#0c2768 100%);
    }

    .bg-cert-sg{
        background-image:linear-gradient(to bottom,#ae3bcb 0,#cd3bd1 100%);
    }

    .bg-cert-pp{
        background-image: linear-gradient(to bottom,#0a933c 0,#0f661d 100%);
    }
    .bg-cert-pp > a{
        color: #000;
    }


.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
.autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
.autocomplete-selected { background: #F0F0F0; }
.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
.autocomplete-group { padding: 2px 5px; }
.autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
.select2-container{ width:100% !important; }

</style>
@endsection

@section('content')
{{-- @if ( Session::get('permission')['col_field_land_tax'] & $base['can_write'] ) --}}
<div class="row">
    {{ Form::open(['method' => 'POST', 'route' => ['receipt.store'], 'id'=>'store_form']) }}
    <input type="hidden" name="with_cert" value="null" />
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>User</dt>
            <dd>{{ $base['user']->realname }}</dd>
        </dl>
        <input type="hidden" class="form-control" name="user_id" id="user_id" value="{{ $base['user']->id }}">
        <input type="hidden" class="form-control" name="transaction_source" id="transaction_source" value="pvet">
    </div>
    <div class="row">
        <div class="form-group col-sm-4">
            <div class="col-md-4">
            <label for="date">AUTO TIMER</label>
        </div>
            <div class="col-md-4">
                <input type="checkbox" class="form-control " value="true" checked="" name="auto_timer" id="auto_timer" />
            </div>
        </div>
    </div>

    <div class="form-group col-sm-2">
        <label for="date">Date</label>
        <input type="text" class="form-control datepicker" name="date" id="date_timex"  required autofocus>
    </div>

    <div class="form-group col-sm-2 d-none">
        <label for="user">AF Type</label>
        <select class="form-control" id="form" name="form" readonly>
            <option selected disabled></option>
            @foreach ($base['form'] as $form)
                @if( $form->id == '1')
                    <option value="{{ $form->id }}" selected>{{ $form->name }}</option>
                @endif
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="serial_id">Series</label>
        <select class="form-control" name="serial_id" id="serial_id" disabled required>
        </select>
    </div>

    <div class="form-group col-sm-3">
        <label for="municipality">Municipality</label>
        <select class="form-control" name="municipality" id="municipality" >
            <option selected></option>
            @foreach($base['municipalities'] as $municipality)
                <option value="{{ $municipality['id'] }}">{{ $municipality['name'] }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-3">
        <label for="barangay">Barangay</label>
        <select class="form-control" name="brgy" id="brgy" disabled >
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="customer">Payor/Customer</label>
        <input type="text" class="form-control" name="customer" id="customer" required>
        <input type="hidden" class="form-control" name="customer_id" id="customer_id">
    </div>

    <div class="form-group col-sm-4">
        <label for="customer_type">Client Type </label>
        <small title="Auto-fill for clients having transaction/s with 'Permit Fees' or 'Professional Tax' accounts or client type 'Professional Tax' only. 
The default client type and remarks set by the auto-fill function are based on the client's most recent transaction with the aforementioned account/client types."><i class="fa fa-info-circle"></i> NOTE</small> <br>
        <small id="client_type_msg" style="color: red;"></small>
        <!-- <select class="form-control" name="customer_type" id="new_customer_type"> -->
        <select class="form-control" name="customer_type" id="customer_type">
            <option></option>
            @foreach($base['sandgravel_types'] as $sandgravel_types)
                <option value="{{ $sandgravel_types['id'] }}">{{ $sandgravel_types['description'] }}</option>
            @endforeach
        </select>
    </div>

     <div class="form-group col-sm-4">
        <label for="municipality">Sex</label>
        <select class="form-control" name="Sex" id="Sex" required="">
            <option selected></option>
            <option value="female">Female</option>
            <option value="male">Male</option>

        </select>
    </div>



    <div class="form-group col-sm-4">
        <label for="user">Transaction Type</label>
        <select class="form-control" id="transaction_type" name="transaction_type" required>
            <!-- <option selected disabled></option> -->
            @foreach ($base['transaction_type'] as $transaction_type)
                <option value="{{ $transaction_type->id }}">{{ $transaction_type->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="bank_name">Bank Name</label>
        <input type="text" class="form-control bank_input" name="bank_name" id="bank_name" value="" disabled>
    </div>

    <div class="form-group col-sm-4">
        <label for="bank_number">Number</label>
        <input type="text" class="form-control bank_input" name="bank_number" id="bank_number" value="" disabled>
    </div>

    <div class="form-group col-sm-4">
        <label for="bank_date">Date</label>
        <input type="text" class="form-control bank_input datepicker" id="bank_date" name="bank_date" value="" disabled>
    </div>

    <div class="form-group col-sm-8">
        <label for="bank_remark">Remark</label>
        <small title="Auto-fill for clients having transaction/s with 'Permit Fees' or 'Professional Tax' accounts or client type 'Professional Tax' only. 
The default client type and remarks set by the auto-fill function are based on the client's most recent transaction with the aforementioned account/client types."><i class="fa fa-info-circle"></i> NOTE</small> <br>
        <small id="info_bank_rem" style="color: red;"></small>
        <textarea class="form-control bank_input" name="bank_remark" id="bank_remark" value=""></textarea>
        <!-- <input type="text" class="form-control bank_input" name="bank_remark" id="bank_remark" value="" maxlength="500"> -->
    </div>

    <div class="form-group col-sm-12">
        <table class="table" id="table">
            <thead>
                <tr>
                    <th colspan="2">Account</th>
                    <th class="td_nature">Nature</th>
                    <th>Amount</th>
                    <th><button id="add_row" class="btn btn-sm btn-success" type="button" data-transactionType="3"><i class="fa fa-plus"></i></button></th>
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
                        <select name="account_list" id="account_list" class="form-control" required>
                            <option></option>
                            <option value="5">Sales on Veterinary Products</option>
                            <option data-title="title" value="61">Supervision and Regulation, Enforcement Fees (Quarantine Fees)</option>
                        </select>
                        <input type="hidden" class="form-control" name="account_id[]">
                        <input type="hidden" class="form-control" name="account_type[]">
                        <input type="hidden" class="form-control account_is_shared" value="0" name="account_is_shared[]">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info account_addtl" disabled>Select</button>
                        <input type="hidden" class="form-control">
                        <input type="hidden" class="form-control account_rate" name="account_rate[]" value="0">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="nature[]" maxlength="300" required>
                    </td>
                    <td class="td_amt">
                        <input type="number" class="form-control amounts" name="amount[]"  step="0.01" required>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="col-md-12 hidden" id="sg_booklets">
            <table class="table table-bordered center" id="booklets_sg">
                    <thead>
                        <tr>
                            <th class="text-center">BOOKLET START</th>
                            <th class="text-center">BOOKLET END</th>
                            <th class="text-center"><button id="add_booklet_row" class="btn btn-sm btn-info"><i class="fa fa-plus"></i></button></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="number" class="form-control booklet_start" name="booklet_start[]"></td>
                            <td><input type="number" class="form-control booklet_end" name="booklet_end[]"></td>
                            <td></td>
                        </tr>
                    </tbody>
            </table>
    </div>

    <br/>

    <div class="form-group col-sm-12">
        @if (isset($base['serial']->serial_begin))
        <button type="submit" class="btn btn-success btnf51" name="button" id="confirm">Add</button>
        @else
        <button type="submit" class="btn btn-success btnf51" name="button" id="confirm" disabled>Add</button>
        @endif
    </div>
    {{ Form::close() }}
</div>
<hr>

<div id="account_panel">
</div>

{{-- @endif --}}
{{-- @if ( Session::get('permission')['col_field_land_tax'] & $base['can_read'] ) --}}
<form class="form-inline">
  <div class="form-group">
    <label for="show_year">YEAR</label>
    <input type="number" min="2017" max="{{ $yr }}" class="form-control" id="show_year" placeholder="{{ date('Y') }}" value="{{ date('Y') }}">
  </div>
  <div class="form-group">
    <label for="show_mnth">Month</label>
    <select class="form-control" name="show_mnth" id="show_mnth">
        <option value="ALL">ALL</option>
        @foreach ( $base['months'] as $mkey => $month)
            @if($mnth == $mkey)
                <option value="{{ $mkey }}" selected>{{ $month }} </option>
            @else
                <option value="{{ $mkey }}">{{ $month }}</option>
            @endif
        @endforeach
    </select>
  </div>
  <div class="form-group">
      <label>Day</label>
      <select class="form-control" name="show_day" id="show_day">
      </select>
  </div>
<button type="button" class="btn btn-default" onclick="$(this).loadTable();" >SHOW</button>
</form>

<table id="seriallist" class="table table-striped table-hover" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>User</th>
            <th>Serial</th>
            <th>Date</th>
            <th>Customer/Payor</th>
            <th>Transaction Type</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
</table>
{{-- @endif --}}
@endsection

@section('js')

<script type="text/javascript">
$.fn.showDays = function() {
    if($('#show_mnth').val() != 'ALL') {
        $('#show_day').empty();
        var date_string = parseInt($('#show_year').val()) + "-" + parseInt($('#show_mnth').val()) + "-" + "1";
        var moment_date = moment(date_string).format("YYYY-MM-DD");
        var end_date = moment(moment_date).endOf('month').format('DD');

        // get current day 
        var current_day = moment(new Date()).format("DD");
        $('#show_day').append('<option value="ALL">ALL</option>');
        for(var i = 1; i <= end_date; i++) {
            if(i == current_day) 
                $('#show_day').append('<option value="'+i+'" selected>'+i+'</option>');
            else
                $('#show_day').append('<option value="'+i+'">'+i+'</option>');
        }
    }
}
$.fn.showDays();
$('#show_mnth').change(function() {
    $.fn.showDays();
});
$('#show_year').change(function() {
    $.fn.showDays();
});

$.fn.loadTable = function(){
  if ( $.fn.DataTable.isDataTable('#seriallist') ) {
     $('#seriallist').DataTable().destroy();
    }
    $('#seriallist').dataTable({
        dom: '<"dt-custom">frtip',
        processing: true,
        serverSide: true,
        ajax: { 'url' : '{{ route("collection.datatables", "pvet") }}',
                'data' : {'show_year' : $('#show_year').val(),
                        'show_mnth' : $('#show_mnth').val(),
                        'show_day' : $('#show_day').val(),
                 }
            },
        columns: [
            { data: 'realname', name: 'realname' },
            { data: 'serial_no', name: 'serial_no' },
           { data: 'date_of_entry',name : 'date_of_entry',
                  render: function(data) {
                      var d = moment(data);
                    return d.format('MMMM DD, YYYY HH:mm:ss');
                },
                bSortable: true,
                searchable : true,
            },
            { data: 'name', name: 'name' },
            { data: 'transaction_type', name: 'transaction_type' },
            { data:
                function(data) {
                    var status = '';
                    if (data.is_cancelled == 1) {
                        status = 'Cancelled';
                    } else if (data.is_printed == 1) {
                        status = 'Issued';
                    }
                    return status;
                }
            },
            { data:
                function(data) {
                    var view = '';
                    var write = '';
                    var cert = '';
                    var another = '';
                    var restore = '';
                    var cert_color = '';
                    var combine = '';

                    view = '<a href="{{ route('pvet.index') }}/'+data.id+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';


                    write = (data.is_cancelled == 0) ? '<a href="{{ route('pvet.index') }}/'+data.id+'/edit" class="btn btn-sm btn-info datatable-btn" title="Edit"><i class="fa fa-pencil-square-o"></i></a>' : '';


                    // if(data.process_status == '0'){

                    // }else if(data.process_status == '1'){
                    //     cert = (data.is_cancelled == 0) ? '<a href="{{-- route('receipt.index') --}}/'+data.id+'/certificate?types=field" class="btn btn-sm btn-green datatable-btn" title="'+data.cert_type+' Certificate"><i class="fa fa-certificate"></i></a>' : '';
                    // }else if(data.col_rcpt_certificate_type_id != null){
                    //     cert = (data.is_cancelled == 0) ? '<a href="{{-- route('receipt.index') --}}/'+data.id+'/certificate?types=field" class="btn btn-sm btn-green datatable-btn" title="'+data.cert_typex+' Certificate"><i class="fa fa-certificate"></i></a>' : '';
                    // }else{
                    //     cert = (data.is_cancelled == 0) ? '<a href="{{-- route('receipt.index') --}}/'+data.id+'/certificate?types=field" class="btn btn-sm btn-gray datatable-btn" title="Certificate"><i class="fa fa-certificate"></i></a>' : '';
                    // }

 
                       if(data.is_printed == 1){
                            if(data.col_receipt_serial_parent == null)
                                another = (data.is_cancelled == 0) ? '<a href="{{ route('pvet.index') }}/'+data.id+'/another?types=field" class="btn btn-sm btn-another-none datatable-btn" title="ANOTHER RECEIPT"><i class="fa fa-plus"></i></a>' : '';
                            else
                                if(data.col_receipt_serial_parent == data.serial_no ){
                                another = (data.is_cancelled == 0) ? '<a href="{{ route('pvet.index') }}/'+data.id+'/another?types=field" class="btn btn-sm btn-another datatable-btn" title="PARENT : '+data.col_receipt_serial_parent+'"><i class="fa fa-plus"></i></a>' : '';
                                }else{
                                    another = '<button class="btn btn-sm btn-another datatable-btn" title="PARENT : '+data.col_receipt_serial_parent+'">  '+data.col_receipt_serial_parent+'</button>';
                                }
                       }


                    
                        //  if (data.is_cancelled == 1) {
                        //     restore = '<a href="{{ route('receipt.index') }}/'+data.id+'/restore" class="btn btn-sm btn-warning datatable-btn" title="Restore : '+data.serial_no+'"><i class="fa fa-undo"></i></a>';
                        // }
                         if (data.is_cancelled == 1) {
                            restore = '<button class="btn btn-sm btn-warning datatable-btn" title="Restore : '+data.serial_no+'" onclick="$(this).restore(\''+data.id+'\');" ><i class="fa fa-undo"></i></a>';
                        }
                    


                       if(data.is_printed == 1){
                            if(data.col_receipt_serial_parent == null){
                                combine = '<button class="btn btn-sm btn-warning datatable-btn" title="Combine" onclick="$(this).combine(\''+data.id+'\');" ><img src="{{ asset('asset/images/arrows_converge_2-512.png')}}" class="img-responsive combinex" /></a>';
                                another = (data.is_cancelled == 0) ? '<a href="{{ route('pvet.index') }}/'+data.id+'/another" class="btn btn-sm btn-another-none datatable-btn" title="ANOTHER RECEIPT"><i class="fa fa-plus"></i></a>' : '';
                            }else{
                                if(data.col_receipt_serial_parent == data.serial_no ){
                                    var children = "CHILDREN : " +  data.col_serials;
                                    //combine = '<button class="btn btn-sm btn-warning datatable-btn" title="Combine" onclick="$(this).combine(\''+data.id+'\');" ><img src="{{-- asset('asset/images/arrows_converge_2-512.png') --}}" class="img-responsive combinex" /></a>';
                                    combine = '<button class="btn btn-sm btn-warning datatable-btn" title="'+children+'" onclick="$(this).combine(\''+data.id+'\');" ><img src="{{ asset('asset/images/arrows_converge_2-512.png') }}" class="img-responsive combinex" /></a>';
                                    another = (data.is_cancelled == 0) ? '<a href="{{ route('pvet.index') }}/'+data.id+'/another" class="btn btn-sm btn-another datatable-btn" title="PARENT : '+data.col_receipt_serial_parent+'"><i class="fa fa-plus"></i></a>' : '';
                                }else{
                                    another = '<button class="btn btn-sm btn-another datatable-btn" title="PARENT : '+data.col_receipt_serial_parent+'" onclick="$.fn.uncombine('+data.col_receipt_serial_parent+', '+data.serial_no+')">  '+data.col_receipt_serial_parent+'</button>';
                                    // 1st part parent OR, 2nd part serial no.(child OR)
                                }
                            }

                       }


                    return view + write + another+restore+combine;

                },
                bSortable: false,
                searchable: false,
            },
        ],
        order : [[ 2, "desc" ]]
    });
 };
$.fn.loadTable();
</script>
<script type="text/javascript">
    var collection_type = 'show_in_fieldlandtax';
    var source = "pvet";
     $.fn.restore = function(id){

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
                        url: '{{ route("receipt.restore") }}',
                        type: 'POST',
                        data:{
                          receipt: id,
                          _token: '{{ csrf_token() }}'
                        },
                        dataType: 'JSON',
                        success: (data) => {
                        }
                    });
                swal({
                      title: 'Restored!',
                      text: 'RECEIPT RESTORED',
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

    $.fn.combine = function(id){
        swal({
            title: 'Enter Receipt No.',
            input: 'text',
            showCancelButton: true,
            confirmButtonText: 'Submit',
            showLoaderOnConfirm: true,
            allowOutsideClick: false,
            preConfirm: (receiptxx) => {
                return new Promise((resolve) => {
                  setTimeout(() => {
                    $.ajax({
                            url: '{{ route("combine.field_land_tax_combine") }}',
                            type: 'POST',
                            data:{
                                collection_type : 'receipt',
                                receipt_id: id,
                                receipt: receiptxx,
                                _token: '{{ csrf_token() }}'
                            },
                            dataType: 'JSON',
                            success: (data) => {
                                // console.log(data);
                                if(data.status == '0'){
                                   swal.showValidationError(data.message);
                                }else{
                                       console.log(data);
                                }
                            }
                        });
                    resolve()
                  }, 2000)
                })
              },
            }).then((result) => {
              if (result.value) {
                swal({
                  type: 'info',
                  title: 'Receipt Combine Done!!!',
                })
                $.fn.loadTable();
              }
            });
    };

    $.fn.uncombine = function(parent, child) {
        swal({
          title: 'Uncombine OR '+child+' from OR '+parent+'?',
          showCancelButton: true,
          confirmButtonText: 'Submit',
          showLoaderOnConfirm: true,
          allowOutsideClick: false,
          preConfirm: (receiptxx) => {
            return new Promise((resolve) => {
              setTimeout(() => {
                $.ajax({
                    url: '{{ route("combine.field_land_tax_uncombine") }}',
                    type: 'POST',
                    data:
                        {
                            collection_type : 'receipt',
                            parent: parent,
                            child: child,
                            _token: '{{ csrf_token() }}'
                        },
                  dataType: 'JSON',
                  success: (data) => {
                        // console.log(data);
                        if(data.status == '0'){
                            swal.showValidationError(data.message);
                        }else{
                            // console.log(data);
                        }
                    }
                });
                resolve()
            }, 2000)
          })
        },
    }).then((result) => {
       if (result.value) {
            swal({
                type: 'info',
                title: 'Receipt Uncombined',
            })
            $.fn.loadTable();
        }
    });
    }


</script>
@include('collection::shared.transactions_js')
@endsection
