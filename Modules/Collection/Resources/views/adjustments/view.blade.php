@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>User</dt>
            <dd>{{ $base['addtl']->user->realname }}</dd>
			<dt>Municipality</dt>
            <dd>
            @if (!empty($base['addtl']->municipality->name))
            {{ $base['addtl']->municipality->name }}
            @endif
            </dd>
			<dt>Barangay</dt>
            <dd>
            @if (!empty($base['addtl']->barangay->name))
            {{ $base['addtl']->barangay->name }}
            @endif
            </dd>
			<dt>Date</dt>
            <dd>{{ date('m/d/Y', strtotime($base['addtl']->date_of_entry)) }}</dd>
            <dt>Reference No.</dt>
            <dd>{{ $base['addtl']->refno }}</dd>
        </dl>
    </div>
</div>

<div class="row">
<div class="form-group col-sm-12">
<table class="table">
	<thead>
		<tr>
			<th>Account</th>
			<th>Amount</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($base['addtl']->items as $item)
		<tr>
			<td>
            @if (isset($item->acct_title))
            {{ $item->acct_title->name }}
            @else
            {{ $item->acct_subtitle->name }}
            @endif
            </td>
			<td align="right">{{ number_format($item->value, 2) }}</td>
		</tr>
		@endforeach
	</tbody>
</table>
</div>
</div>
@endsection