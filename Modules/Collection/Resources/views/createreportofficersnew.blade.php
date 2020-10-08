@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    {{ Form::open([ 'method' => 'POST', 'route' => 'settings_report_officers_new.store', 'autocomplete' => 'off' ]) }}
    <div class="form-group col-sm-4">
        <label for="Name">Name</label>
        <input type="text" class="form-control" name="officer_name" required>
    </div>

    <div class="form-group col-sm-4">
        <label for="Sub">Position</label>
        <input type="text" class="form-control" name="position_name" required>
        <!-- <select class="form-control form-control-lg" name="position_name">
            <option value="" disabled="">-- Select Position --</option>
            @foreach ($position as $p)
                <option value="{{$p->id}}">{{$p->position}}</option>
            @endforeach
        </select>  -->
    </div>

    <div class="form-group col-sm-12">
        <input type="submit" class="btn btn-primary" value="ADD">
    </div>
    {{ Form::close() }}
</div>
@endsection
