@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    {{ Form::open([ 'method' => 'POST', 'route' => 'account_category.store' ]) }}
    <div class="form-group col-sm-12">
      <label for="Name">Name</label>
      <input type="text" class="form-control" name="name" value="{{ Request::old('name') }}"" required>
    </div>
    <div class="form-group col-sm-12">
      <input type="submit" class="btn btn-primary" value="ADD">
    </div>
    {{ Form::close() }}
</div>
@endsection
