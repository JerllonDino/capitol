@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    {{ Form::open([ 'method' => 'POST', 'route' => 'account_title.store' ]) }}
    <div class="form-group col-sm-6">
        <label for="Code">Code</label>
        <input type="text" class="form-control" name="code" value="{{ Request::old('code') }}" required>
    </div>

    <div class="form-group col-sm-6">
        <label for="Name">Name</label>
        <input type="text" class="form-control" name="name" value="{{ Request::old('name') }}" required>
    </div>

    <div class="form-group col-sm-6">
        <label for="Group">Group Name</label>
        <select class="form-control" name="group" required>
            <option value=""></option>
            @foreach($data as $group)
                @if (Request::old('group') == $group->id)
                <option title="{{ $group->description }}" value="{{ $group->id }}" selected>{{ $group->name .' ('.$group->category->name.')'}}</option>
                @else
                <option title="{{ $group->description }}" value="{{ $group->id }}">{{ $group->name .' ('.$group->category->name.')' }}</option>
                @endif
            @endforeach
        </select>
    </div>
    
    <div class="form-group col-sm-6">
        <label for="monthly">Show in Monthly Report</label>
        <select class="form-control" name="monthly" id="monthly" required>
            <option value="" selected disabled></option>
            <option value="1">Yes</option>
            <option value="0">No</option> 
        </select>
    </div>

    <div id="budget"></div>

    <div class="form-group col-sm-12">
        <input type="submit" class="btn btn-primary" value="ADD">
    </div>
    {{ Form::close() }}
</div>
@endsection


@section('js')
<script type="text/javascript">
    $('#monthly').on('change',function(){
            var el = $(this);
            var budget = '';
            if(el.val() === '1'){
                    budget = '<div class="form-group col-sm-6">'+
                             '   <label for="title_budget_estimate">Budget Estimate </label>'+
                             '   <input class="form-control" type="number" step="0.01" name="title_budget_estimate_value" required /> '+
                             '</div>'+
                             '<div class="form-group col-sm-6">'+
                             '   <label for="title_budget_estimate_year">Budget Estimate Year </label>'+
                             '   <input class="form-control" type="number"  name="title_budget_estimate_year" value="{{ date('Y') }}" required /> '+
                             '</div>';
            }else{
                budget = '';
            }

            $('#budget').html(budget);


    });
</script>
@endsection