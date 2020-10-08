@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    {{ Form::open([ 'method' => 'POST', 'route' => ['settings_report_officers_new.update'], 'autocomplete' => 'off', 'id' => 'update_form' ]) }}
        <div class="form-group col-sm-12">
            {{@csrf_field()}}
            {{--method_field('put')--}}
            <label for="Name">Name</label>
            <input type="hidden" value="{{$id}}" name="officer_id">
            <input type="text" class="form-control" name="officer_name" value="{{$officer->officer_name}}" required>
        </div>
        <div class="form-group col-sm-12">
            <label for="Position">Position</label>
            <input type="text" class="form-control" name="position_name" value="{{$officer->position}}" required>
            {{-- <select class="form-control form-control-lg" name="position_name">
                @foreach ($position as $p)
                @if($officer->position_name == $p->id)
                    <option value="{{$officer->position_name}}" selected>{{$p->position}}</option>
                @else
                    <option value="{{$p->id}}">{{$p->position}}</option>
                @endif
                @endforeach
            </select> --}}
        </div>
        <div class="form-group col-sm-12">
            <input type="submit" class="btn btn-primary" id="updtBtn" value="UPDATE">
        </div>
    {{ Form::close() }}
</div>
@endsection
