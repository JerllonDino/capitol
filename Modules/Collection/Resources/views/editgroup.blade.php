@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    {{ Form::open([ 'method' => 'PUT', 'route' => ['account_group.update', $acctgrp->id] ]) }}

    <div class="form-group col-sm-6">
        <label for="Name">Name</label>
        <input type="text" class="form-control" name="name" value="{{$acctgrp->name}}" required>
    </div>

    <div class="form-group col-sm-6">
        <label for="categ">Category</label>
        <select class="form-control" name="categ">
            <option value="{{$cat->id}}" hidden>{{$cat->name}}</option>
            @foreach($acctcateg as $categ)
            <option value="{{$categ->id}}">{{$categ->name}}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-12">
        <input type="submit" class="btn btn-primary" value="UPDATE">
    </div>
    {{ Form::close() }}
</div>
@endsection
