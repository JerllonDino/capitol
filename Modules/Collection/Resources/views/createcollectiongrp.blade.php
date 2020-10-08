@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    {{ Form::open([ 'method' => 'POST', 'route' => 'account_group.store' ]) }}
    <div class="form-group col-sm-6">
        <label for="Name">Name</label>
        <input type="text" class="form-control" name="name" value="{{ Request::old('name') }}"" required>
    </div>

    <div class="form-group col-sm-6">
        <label for="Name">Category Name</label>
        <select class="form-control" name="category" required>
            <option value=""></option>
            @foreach($res as $result)
                @if ($result->id == Request::old('category'))
                    <option value="{{$result->id}}" selected>{{$result->name}}</option>
                @else
                    <option value="{{$result->id}}">{{$result->name}}</option>
                @endif
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-12">
        <input type="submit" class="btn btn-primary" value="ADD">
    </div>
    {{ Form::close() }}
</div>
@endsection
