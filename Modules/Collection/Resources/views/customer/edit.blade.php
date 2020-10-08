@extends('nav')

@section('content')
<div class="row">
    {{ Form::open(['method' => 'PUT', 'route' => ['customer.update', $base['customer']->id]]) }}
    <div class="form-group col-sm-8">
        <label for="name">Name</label>
        <input type="text" class="form-control" name="name" value="{{ $base['customer']->name }}" required autofocus>
    </div>
    <div class="form-group col-sm-4">
        <label for="customer_type">Client Type</label>
             <select class="form-control" name="customer_type" id="customer_type">
            <option ></option>
            @foreach($base['sandgravel_types'] as $sandgravel_types)
                <option value="{{ $sandgravel_types['id'] }}" @if($sandgravel_types['id'] == $base['customer']->customer_type_id) selected @endif >{{ $sandgravel_types['description'] }}</option>
            @endforeach
            </select>
    </div>
    
    <div class="form-group col-sm-12">
        <label for="address">Address</label>
        <textarea class="form-control" name="address">{{ $base['customer']->address }}</textarea>
    </div>

    <div class="form-group col-sm-12">
      <button type="submit" class="btn btn-success" name="button" id="confirm">Update</button>
    </div>
    {{ Form::close() }}
</div>
@endsection