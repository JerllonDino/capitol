@extends('nav')

@section('content')
<div class="row">
    {{ Form::open([ 'route' => ['group.update', $base['group']->id], 'method' => 'put' ]) }}
        <div class="form-group col-sm-12">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $base['group']->name }}" autofocus required>
        </div>
        <div class="form-group col-sm-12">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description">{{ $base['group']->description }}</textarea>
        </div>
        <div class="form-group col-sm-12">
            <input type="submit" class="btn btn-success" id="submit" value="Update">
        </div>
    {{ Form::close() }}
</div>
@endsection