@extends('nav')

@section('content')
<div class="row">
    {{ Form::open([ 'method' => 'PATCH', 'action' => ['\Modules\Collection\Http\Controllers\AccountTitleController@update', $base['accounttitle'] -> id] ]) }}
    <div class="form-group col-sm-6">
        <label for="Code">Code</label>
        <input type="text" class="form-control" value="{{$base['accounttitle']->code}}" name="code" required>
    </div>
    
    <div class="form-group col-sm-6">
        <label for="Name">Name</label>
        <input type="text" class="form-control" value="{{$base['accounttitle']->name}}" name="name" required>
    </div>
    <div class="form-group col-sm-6">
        <label for="Group">Group Name</label>
        <select class="form-control" id="group" name="group" required>
            @foreach($base['titlegroup'] as $group)
                @if ($base['accounttitle']->acct_group_id == $group->id)
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
            @if ($base['accounttitle']->show_in_monthly == 1)
                <option value="1" selected>Yes</option>
                <option value="0">No</option>
            @else
                <option value="1">Yes</option>
                <option value="0" selected>No</option>
            @endif
        </select>
    </div>
   <div id="budget" >
    @if ($base['accounttitle']->show_in_monthly == 1 && isset($base['accounttitle']->budget[0])  )
                <div class="form-group col-sm-6">
                               <label for="title_budget_estimate_value">Budget Estimate </label>
                                <input class="form-control" type="number" step="0.01" name="title_budget_estimate_value" value="{{ $base['accounttitle']->budget[0]->value }}" required /> 
                             </div>
                             <div class="form-group col-sm-6">
                                <label for="title_budget_estimate_year">Budget Estimate Year </label>
                                <input class="form-control" type="number"  name="title_budget_estimate_year" value="{{ $base['accounttitle']->budget[0]->year }}" required /> 
                             </div>
                </div>
    @endif
    </div>

    
    <div class="form-group col-sm-12">
        <input type="submit" class="btn btn-success" id="submit" value="Update">
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
                    var title_budget_value = '0';
                    var title_budget_year = '{{ date('Y') }}';
                 @if ($base['accounttitle']->show_in_monthly == 1 && isset($base['accounttitle']->budget[0]) )
                    title_budget_value = '{{ $base['accounttitle']->budget[0]->value}}';
                    title_budget_year = '{{ $base['accounttitle']->budget[0]->year}}';
                 @endif 
                    budget = '<div class="form-group col-sm-6">'+
                             '   <label for="title_budget_estimate_value">Budget Estimate </label>'+
                             '   <input class="form-control" type="number" step="0.01" name="title_budget_estimate_value" value="'+title_budget_value+'" required /> '+
                             '</div>'+
                             '<div class="form-group col-sm-6">'+
                             '   <label for="title_budget_estimate_year">Budget Estimate Year </label>'+
                             '   <input class="form-control" type="number"  name="title_budget_estimate_year"  value="'+title_budget_year+'" required /> '+
                             '</div>';
            }else{
                budget = '';
            }

            $('#budget').html(budget);

            


    });
</script>
@endsection