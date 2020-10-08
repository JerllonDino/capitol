@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    {{ Form::open([ 'method' => 'POST', 'route' => 'account_subtitle_items.store' ]) }}
    <div class="form-group col-sm-4">
        <label for="Name">Name</label>
        <input type="text" class="form-control" name="name" required>
    </div>

    <div class="form-group col-sm-4">
        <label for="Sub">Subtitle Of</label>
        <select class="form-control" name="sub" required>
            <option value="" disabled selected></option>
            @foreach($res as $result)
            <option value="{{$result->id}}">{{$result->name}}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="monthly">Show in Monthly Report</label>
        <select class="form-control" name="monthly" required>
            <option value="" disabled selected></option>
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>
    </div>

    <div class="form-group col-sm-12">
        <input type="submit" class="btn btn-primary" value="ADD">
    </div>
    {{ Form::close() }}
</div>
@endsection
