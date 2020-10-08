@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>Customer Name</dt>
            <dd>{{ $base['customer']->name }}</dd>
            <dt>Customer Address</dt>
            <dd>{{ $base['customer']->address }}</dd>
        </dl>
    </div>
</div>
<div class="row">
    {{ Form::open([ 'route' => ['customer.destroy', $base['customer']['id']], 'method' => 'delete', 'id' => 'form' ]) }}
        <div class="form-group col-sm-12">

            @if ( Session::get('permission')['col_customer'] & $base['can_write'] )
            <a href="{{ route('customer.edit', $base['customer']['id']) }}" class="btn btn-info datatable-btn">
                Update
            </a>
            @endif

            @if ( Session::get('permission')['col_customer'] & $base['can_delete'] )
            <button type="button" id="delete" class="btn btn-danger datatable-btn pull-right">
                Delete
            </button>
            @endif

        </div>
    {{ Form::close() }}
</div>

@if ( Session::get('permission')['col_receipt'] & $base['can_read'] )
<!-- <table id="seriallist" class="table table-striped table-hover" cellspacing=0 width="100%">
    <thead>
        <tr>
            <th>Serial Number</th>
            <th>Date of Entry</th>
            <th>Actions</th>
        </tr>
    </thead>
</table> -->
@endif

@if ( Session::get('permission')['col_receipt'] & $base['can_read'] )
<hr />
<form class="form-inline">
  <div class="form-group">
    <label for="show_year">YEAR</label>
    <input type="number" min="2017" max="{{ $yr }}" class="form-control" name="show_year" id="show_year" placeholder="{{ $yr }}" value="{{ $yr }}">
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
<button type="submit" class="btn btn-default" onclick="$(this).loadTable();" >SHOW</button>


</form>
<hr />
<h3> RECEIPT INFO</h3>
<table id="seriallist_nw" class="table table-striped table-hover table-boredered" cellspacing=0 width="100%">
    <thead>
        <tr>
            <th>Date of Entry</th>
            <th>Serial Number</th>
            <th>Remarks</th>
            <th>Account</th>
            <th>Nature</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $r_total = 0;
        ?>
        @foreach($receipts as $k => $receipt)
        <?php
            $row_span = count($receipt->items);
            $rr = 0;
        ?>
            <tr>
                <td rowspan="{{ $row_span }}">{{\Carbon\Carbon::parse($receipt->date_of_entry)->toFormattedDateString()}}</td>
                <td rowspan="{{ $row_span }}">{{$receipt->serial_no}}</td>
                <td rowspan="{{ $row_span }}">
                    <?php
                        $receipt_rem = preg_replace('/[^A-Za-z0-9&\s]+/', "", trim(strip_tags($receipt->remarks)));
                        $receipt_rem = preg_replace('/&nbsp/', "", $receipt_rem);
                        $receipt_rem = preg_replace('~\R~', "", $receipt_rem);

                        $bank_rem = preg_replace('/[^A-Za-z0-9&\s]+/', "", trim(strip_tags($receipt->bank_remark)));
                        $bank_rem = preg_replace('/&nbsp/', "", $bank_rem);
                        $bank_rem = preg_replace('~\R~', "", $bank_rem);
                        if($receipt->certificate != null){
                            $cert_rem = preg_replace('/[^A-Za-z0-9&\s]+/', "", trim(strip_tags($receipt->certificate->detail)));
                            $cert_rem = preg_replace('/&nbsp/', '', $cert_rem);
                            $cert_rem = preg_replace('~\R~', '', $cert_rem);
                        }
                    ?>
                    @if(strcasecmp(strtoupper($receipt_rem), strtoupper($bank_rem)) != 0)
                        {!! $receipt->remarks !!}<br />
                        {!! $receipt->bank_remark !!}
                        
                        @if(!empty($receipt->certificate))
                            @if(strcasecmp(strtoupper($receipt_rem), strtoupper($cert_rem)) != 0 && strcasecmp(strtoupper($bank_rem), strtoupper($cert_rem)) != 0)
                                {!! $cert_rem !!} 
                            @endif
                        @endif
                    @else
                        {!! $receipt->remarks !!}<br />
                        @if(!empty($receipt->certificate))
                            @if(strcasecmp($receipt_rem, $cert_rem) != 0)
                                {!! $cert_rem !!} 
                            @endif
                        @endif
                    @endif
                </td>
                    <?php
                        $tr = '';
                        foreach($receipt->items as $i => $item):
                            $acct = '';
                            $nature = '';
                            if($item->col_acct_title_id != 0){
                                 $acct = $item->acct_title->name;
                            }else{
                                $acct = $item->acct_subtitle->name;
                            }

                            if($rr > 0):
                                $tr .= '<tr>';
                            endif;
                            $r_total += $item->value;
                            $tr .= '<td>'.$acct.'</td>
                                    <td>'.$item->nature.'</td>
                                    
                                    <td class="text-right">'.number_format($item->value,2).'</td>
                                </tr>';
                                $rr++;
                        endforeach;
                    ?>

                 {!! $tr !!}

        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <th colspan="5">Total</th>
            <th class="text-right">{{ number_format($r_total,2) }}</th>
        </tr>
    </tfoot>
</table>
<hr />

@if(!($cashdivs->isEmpty()))

<h3> CASH DIVISION INFO</h3>
<table id="seriallist_nw" class="table table-striped table-hover table-boredered" cellspacing=0 width="100%">
    <thead>
        <tr>
            <th>Date of Entry</th>
            <th>Account</th>
            <th>Nature</th>
            <th>Amount</th>

        </tr>
    </thead>
    <tbody>
        <?php
            $c_total = 0;
        ?>
        @foreach($cashdivs as $k => $cashdiv)
        <?php
            $crow_span = count($cashdiv->items);
            $cc = 0;
        ?>
            <tr>
                <td rowspan="{{ $crow_span }}">{{\Carbon\Carbon::parse($cashdiv->date_of_entry)->toFormattedDateString()}}</td>
                    <?php
                        $ctr = '';
                        foreach($cashdiv->items as $i => $item):
                            $acct = '';
                            $nature = '';
                            if($item->col_acct_title_id != 0){
                                 $acct = $item->acct_title->name;
                            }else{
                                $acct = $item->acct_subtitle->name;
                            }

                            if($cc > 0):
                                $ctr .= '<tr>';
                            endif;
                            $c_total += $item->value;
                            $ctr .= '<td>'.$acct.'</td>
                                    <td>'.$item->nature.'</td>
                                    <td class="text-right">'.number_format($item->value,2).'</td>
                                </tr>';
                                $cc++;
                        endforeach;
                    ?>
                 {!! $ctr !!}

        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <th colspan="3">Total</th>
            <th class="text-right">{{ number_format($c_total,2) }}</th>
        </tr>
    </tfoot>
</table>

<hr />
@endif

<h3>MUNICIPAL RECEIPTS</h3>
<table id="seriallist_nw" class="table table-striped table-hover table-boredered" cellspacing=0 width="100%">
    <thead>
        <tr>
            <th>Receipt Date</th>
            <th>Serial Number</th>
            <th>Remarks</th>
            <th>Account</th>
            <th>Nature</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($mncpal_receipts as $rcpt)
            <?php
                $items_count = count($rcpt->getItems);
            ?>
            <tr>
                <td rowspan="{{ $items_count }}">{{ \Carbon\Carbon::parse($rcpt->rcpt_date)->toFormattedDateString() }}</td>
                <td rowspan="{{ $items_count }}">{{ $rcpt->rcpt_no }}</td>
                <td rowspan="{{ $items_count }}">{!! $rcpt->remarks !!}</td>
                @foreach($rcpt->getItems as $item)
                        <td>
                            @if($item->col_acct_title_id > 0)
                                {{ $item->getAccount->name }}
                            @else
                                {{ $item->getSubAccount->name }}
                            @endif
                        </td>
                        <td>{{ $item->nature }}</td>
                        <td>{{ $item->value }}</td>
                    </tr>
                @endforeach
        @endforeach
    </tbody>
</table>

<?php
    $xcount = 0;
?>
@foreach($receipts as $k => $receipt)
    <?php
        $xcount += count($receipt->sgbooklet);
    ?>
@endforeach
@if($xcount > 0)

<h3> SAND and GRAVEL BOOKLET</h3>
<table id="seriallist_nw" class="table table-striped table-hover table-boredered" cellspacing=0 width="100%">
    <thead>
        <tr>
            <th>Date of Entry</th>
            <th>Serial Number</th>
            <th>SG BOOKLET START</th>
            <th>SG BOOKLET END</th>
        </tr>
    </thead>
    <tbody>
        @foreach($receipts as $k => $receipt)
            <?php
                $row_spansg = count($receipt->sgbooklet);
                $rr = 0;
            ?>
             <tr>
                @if($row_spansg > 0)
                    <td rowspan="{{ $row_spansg }}">{{ \Carbon\Carbon::parse($receipt->date_of_entry)->toFormattedDateString() }}</td>
                    <td rowspan="{{ $row_spansg }}">{{ $receipt->serial_no }}</td>
                @else
                    <td>{{ \Carbon\Carbon::parse($receipt->date_of_entry)->toFormattedDateString() }}</td>
                    <td>{{ $receipt->serial_no }}</td>
                @endif
                <?php
                    $tr = '';
                    foreach($receipt->sgbooklet as $i => $sgbooklet):
                        if($rr > 0):
                            $tr .= '<tr>';
                        endif;
                        if($row_spansg > 0):
                            $tr .= '
                                <td>'.$sgbooklet->booklet_start.'</td>
                                <td>'.$sgbooklet->booklet_end.'</td>
                            </tr>';
                        else:
                            $tr .= '
                                <td></td>
                                <td></td>
                            </tr>';
                        endif;

                        $rr++;
                    endforeach;
                ?>
                {!! $tr !!}
        @endforeach
    </tbody>
</table>
@endif
@endif

<div id="delete_confirm">
    Are you sure you want to delete customer/payor: '{{ $base['customer']->name }}'?
</div>
@endsection

@section('js')
{{ Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js') }}
{{ Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js') }}
<script type="text/javascript">
    $('#delete').click( function() {
        $('#delete_confirm').dialog('open');
    });

    $('#delete_confirm').dialog({
        autoOpen: false,
        draggable:false,
        modal: true,
        resizable: false,
        title: 'Delete Customer/Payor?',
        width: 'auto',
        buttons: {
            'Delete': function() {
                $('#form').submit();
            },

            'Cancel': function() {
                $(this).dialog('close');
            },
        },
    });

    $(document).on('keyup', '#show_year', function() {
        var route = $('#rpt_rec').attr('href');
        var route2 = route.replace('year', $('#show_year').val());
        $('#rpt_rec').attr('href', route2);
    });

    $(document).on('change', '#show_mnth', function() {
        var route = $('#rpt_rec').attr('href');
        var route2 = route.replace('month', $('#show_mnth').val());
        $('#rpt_rec').attr('href', route2);
    });

    $(document).on('click', '#rpt_rec', function(e) {
        var route = $('#rpt_rec').attr('href');
        var route2 = route.replace('month', $('#show_mnth').val());
        var route3 = route2.replace('year', $('#show_year').val());
        $('#rpt_rec').attr('href', route3);
    }); 

    $('#seriallist').DataTable({
        dom: '<"dt-custom">frtip',
        processing: true,
        serverSide: true,
        ajax: '{{ route("collection.datatables", ["customer_rcpt", "customer" => $base["customer"]->id]) }}',

        columns: [
            { data: 'serial_no', name: 'serial_no' },
            { data: 'date_of_entry', name: 'date_of_entry' },
            { data:
                function(data) {
                    var view = '';
                    @if ( Session::get('permission')['col_receipt'] & $base['can_read'] )
                    view = '<a href="{{ route('receipt.index') }}/'+data.id+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                    @endif
                    return view;
                },
                bSortable: false,
                searchable: false,
            }
        ]
    });


     // $('#seriallist_nw').DataTable();
</script>
@endsection
