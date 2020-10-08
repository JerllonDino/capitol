@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    {{ Form::open([ 'method' => 'PUT', 'route' => ['account_subtitle.update', $accountsub->id] ]) }}

    <div class="form-group col-sm-4">
        <label for="Name">Name</label>
        <input type="text" class="form-control" name="name" value="{{$accountsub->name}}" required>
    </div>

    <div class="form-group col-sm-4">
        <label for="Name">SubTitleOf</label>
        <select class="form-control" name="sub" required>
            <option value="{{$sub->id}}" hidden>{{$sub->name . '(' . $sub->group->name . ' - ' . $sub->group->category->name .')'}}</option>
            @foreach($accounttitle as $titles)
            <option value="{{$titles->id}}">{{$titles->name . '(' . $titles->group->name . ' - ' . $titles->group->category->name .')'}}</option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group col-sm-4">
        <label for="monthly">Show in Monthly Report</label>
        <select class="form-control" name="monthly" id="monthly" required>
            @if ($accountsub->show_in_monthly == 1)
                <option value="1" selected>Yes</option>
                <option value="0">No</option>
            @else
                <option value="1">Yes</option>
                <option value="0" selected>No</option>
            @endif
        </select>
    </div>

    <div id="budget" >
    @if ($accountsub->show_in_monthly == 1 && isset($accountsub->budget[0]) )
        <div class="form-group col-sm-6">
                       <label for="subtitle_budget_estimate_value">Budget Estimate </label>
                        <input class="form-control" type="number" step="0.01" name="subtitle_budget_estimate_value" value="{{ $accountsub->budget[0]->value}}" required /> 
                     </div>
                     <div class="form-group col-sm-6">
                        <label for="subtitle_budget_estimate_year">Budget Estimate Year </label>
                        <input class="form-control" type="number"  name="subtitle_budget_estimate_year" value="{{ $accountsub->budget[0]->year ?? date('Y') }}" required /> 
                     </div>
        </div>
    @endif
    </div>

    <div class="form-group col-sm-12">
        <input type="submit" class="btn btn-primary" value="UPDATE">
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
                    var sub_budget_value = '0';
                    var sub_budget_year = '{{ date('Y') }}';
                 @if ($accountsub->show_in_monthly == 1)
                    sub_budget_value = '{{ $accountsub->budget[0]->value}}';
                    sub_budget_year = '{{ $accountsub->budget[0]->year}}';
                 @endif 
                    budget = '<div class="form-group col-sm-6">'+
                             '   <label for="subtitle_budget_estimate_value">Budget Estimate </label>'+
                             '   <input class="form-control" type="number" step="0.01" name="subtitle_budget_estimate_value" value="'+sub_budget_value+'" required /> '+
                             '</div>'+
                             '<div class="form-group col-sm-6">'+
                             '   <label for="subtitle_budget_estimate_year">Budget Estimate Year </label>'+
                             '   <input class="form-control" type="number"  name="subtitle_budget_estimate_year"  value="'+sub_budget_year+'" required /> '+
                             '</div>';
            }else{
                budget = '';
            }

            $('#budget').html(budget);

            


    });
</script>
@endsection
