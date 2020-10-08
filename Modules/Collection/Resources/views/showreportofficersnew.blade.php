@extends('nav')

@section('content')
<div class="row">
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{$officer->officer_name}}</dd>
            <dt>Position</dt>
            <dd>{{$position->position}}</dd>
        </dl>
    </div>
</div>
@endsection
