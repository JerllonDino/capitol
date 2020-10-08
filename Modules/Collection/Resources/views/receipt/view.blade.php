@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    @if ( Session::get('permission')['col_receipt'] & $base['can_write'] )
    <div class="form-group col-sm-12">
        @if ($base['receipt']->is_cancelled == 1)
            <button type="submit" class="btn btn-info" id="confirm" disabled>Print</button>
        @else
            <a href="{{ route('pdf.receipt', ['id' => $base['receipt']->id]) }}" class="btn btn-info">Print</a>
        @endif

		@if ($base['receipt']->is_cancelled == 0)
            <a href="{{ route('receipt.certificate.index', ['id' => $base['receipt']->id]) }}" class="btn btn-info">Certificate</a>
		@else
            <a href="#" class="btn btn-info" disabled>Certificate</a>
		@endif

        @if ($base['receipt']->serial->formtype->id == 2)
            @if ($base['receipt']->is_cancelled == 1)
            <a href="#" class="btn btn-info" disabled>Form 56 Detail</a>
			@else
            <a href="{{ route('receipt.f56_detail_form', ['id' =>$base['receipt']->id]) }}" class="btn btn-info">Form 56 Detail</a>
            @endif
        @endif

        @if ($base['receipt']->is_printed == 1)
        <button type="button" class="btn btn-warning pull-right" id="cancel_btn">Cancel Receipt</button>
        @else
        <button type="button" class="btn btn-warning pull-right" id="cancel_btn" disabled>Cancel Receipt</button>
        @endif

    </div>
    @endif
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>User</dt>
            <dd>{{ $base['receipt']->user->realname }}</dd>
            <dt>AF Type</dt>
            <dd>{{ $base['receipt']->serial->formtype->name }}</dd>
			<dt>Serial Number</dt>
            <dd>{{ $base['receipt']->serial_no }}</dd>
			<dt>Payor/Customer</dt>

            <dd>{{ $base['receipt']->customer->name }}</dd>
            <dt>Client Type</dt>
            <dd>{{ $base['receipt']->client_type_desc->description ?? '' }}</dd>

			<dt>Municipality</dt>
            <dd>
            @if (!empty($base['receipt']->municipality->name))
            {{ $base['receipt']->municipality->name }}
            @endif
            </dd>
			<dt>Barangay</dt>
            <dd>
            @if (!empty($base['receipt']->barangay->name))
            {{ $base['receipt']->barangay->name }}
            @endif
            </dd>
			<dt>Date</dt>
            <dd>{{ date('m/d/Y', strtotime($base['receipt']->date_of_entry)) }}</dd>
			<dt>Transaction Type</dt>
            <dd>{{ $base['receipt']->transactiontype->name }}</dd>
			<dt>Bank Name</dt>
            <dd>{{ $base['receipt']->bank_name }}</dd>
			<dt>Number</dt>
            <dd>{{ $base['receipt']->bank_number }}</dd>
			<dt>Date</dt>
            <dd>{{ $base['receipt']->bank_date }}</dd>
			<dt>Remark</dt>
            <dd>{{ $base['receipt']->bank_remark }}</dd>
            <dt>Status</dt>
            <dd>
            @if ($base['receipt']->is_cancelled == 1)
                Cancelled
                <p>{{ $base['receipt']->cancelled_remark }}</p>
            @elseif ($base['receipt']->is_printed == 1)
                Printed
            @else
                Pending (To be printed)
            @endif
            </dd>
        </dl>
    </div>
</div>

<div id="cancel_panel">
    {{ Form::open(['method' => 'POST', 'route' => ['receipt.cancel', $base['receipt']->id]]) }}
    <div class="form-group col-sm-12">
        <label for="bank_remark">Remark</label>
        <textarea class="form-control" name="cancel_remark" required></textarea>
    </div>
    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success" id="go">Go</button>
    </div>
    {{ Form::close() }}
</div>

<div class="row">
<div class="form-group col-sm-12">
<table class="table">
	<thead>
		<tr>
			<th>Nature</th>
			<th>Amount</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($base['receipt']->items as $item)
		<tr>
			<td>{{ $item->nature }}</td>
			<td align="right">{{ number_format($item->value, 2) }}</td>
		</tr>
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			<td><b>TOTAL VALUE</b></td>
			<td align="right"><b>{{ number_format($base['total'], 2) }}</b></td>
		</tr>
	</tfoot>
</table>
</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
$('#cancel_panel').dialog({
    autoOpen: false,
    draggable:false,
    modal: true,
    resizable: false,
    title: 'Cancel',
    width: 600,
});

$(document).on('click', '#cancel_btn', function() {
    $('#cancel_panel').dialog('open');
});
</script>
@endsection
