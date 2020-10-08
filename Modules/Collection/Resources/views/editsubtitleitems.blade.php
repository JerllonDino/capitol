@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    {{ Form::open([ 'method' => 'PUT', 'route' => ['account_subtitle_items.update', $accountsubitems->id] ]) }}

    <div class="form-group col-sm-4">
        <label for="Name">Name</label>
        <input type="text" class="form-control" name="name" value="{{$accountsubitems->item_name}}" required>
    </div>

    <div class="form-group col-sm-4">
        <label for="Name">SubTitleOf</label>
        <select class="form-control" name="sub" required>
            <option value="{{$sub->id}}" hidden>{{$sub->name}}</option>
            @foreach($accounsubttitle as $titles)
            <option value="{{$titles->id}}">{{$titles->name}}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="monthly">Show in Monthly Report</label>
        <select class="form-control" name="monthly" required>
            @if ($accountsubitems->show_in_monthly == 1)
                <option value="1" selected>Yes</option>
                <option value="0">No</option>
            @else
                <option value="1">Yes</option>
                <option value="0" selected>No</option>
            @endif
        </select>
    </div>

    <div class="form-group col-sm-12">
        <input type="submit" class="btn btn-primary" value="UPDATE">
    </div>
    {{ Form::close() }}
</div>
@endsection
