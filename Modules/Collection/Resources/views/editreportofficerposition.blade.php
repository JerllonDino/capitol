@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    {{ Form::open([ 'method' => 'PUT', 'route' => ['settings_report_officers.update', $position->id] ]) }}
        <div class="form-group col-sm-12">
            <label for="Position">Position</label>
            <input type="text" class="form-control" name="position" value="{{$position->position}}" required>
        </div>
        <div class="form-group col-sm-12">
            <input type="submit" class="btn btn-primary" value="UPDATE">
        </div>
    {{ Form::close() }}
</div>
@endsection
