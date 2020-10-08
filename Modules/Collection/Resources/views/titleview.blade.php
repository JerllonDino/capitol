@extends('nav')

@section('content')
<div class="row">
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>Code</dt>
            <dd>{{ $base['accounttitle']['code'] }}</dd>
            <dt>Name</dt>
            <dd>{{ $base['accounttitle']['name'] }}</dd>
            <dt>Group</dt>
            <dd>{{ $base['accounttitle']['group']['name'] }}</dd>
        </dl>
    </div>
</div>
<div class="row">
    {{ Form::open([ 'method' => 'delete', 'id' => 'userform', 'action' => ['\Modules\Collection\Http\Controllers\AccountTitleController@destroy', $base['accounttitle']['id']] ]) }}
    <div class="form-group col-sm-12">
        @if ( Session::get('permission')['col_serial'] & $base['can_write'] )
        <a href="{{ route('account_title.edit', $base['accounttitle']['id']) }}" class="btn btn-info datatable-btn">
            Update
        </a>
        @endif
    </div>
    {{ Form::close() }}
</div>
@endsection
