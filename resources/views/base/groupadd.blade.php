@extends('nav')

@section('content')
<div class="row">
    {{ Form::open([ 'route' => ['group.store'], 'method' => 'post' ]) }}
        <div class="form-group col-sm-12">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ Request::old('name') }}" autofocus required>
        </div>
        <div class="form-group col-sm-12">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description">{{ Request::old('description') }}</textarea>
        </div>
        <div class="form-group col-sm-12">
            <input type="submit" class="btn btn-success" id="submit" value="Submit">
        </div>
    {{ Form::close() }}
</div>
@endsection