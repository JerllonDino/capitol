@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
<style>
    #year {
        background:white !important;
    }
    .ui-datepicker-calendar,.ui-datepicker-month {
        display: none;
    }​
</style>
@endsection

@section('content')
<div class="row">
    {{ Form::open([ 'method' => 'PATCH', 'route' => ['budget_estimate.update', $base['year']] ]) }}
    <div class="form-group col-sm-12">
        <label for="Code">Year</label>
        <input type="number" class="form-control" step="1" name="year" value="{{ $base['year'] }}" readonly autofocus>
    </div>

    <div class="col-sm-12">
        <table class="table">

        @foreach ($data['category'] as $category)
            <tr>
                <td><div class="col-sm-12"><b>{{ $category->name }}</b></div></td>
                <td><div class="col-sm-12"></div></td>
            </tr>

            @foreach ($category->group as $group)
                <tr>
                    <td><div class="col-sm-12">{{ $group->name }}</div></td>
                    <td><div class="col-sm-12"></div></td>
                </tr>

                @foreach ($group->title as $title)
                    <tr>
                        <td><div class="col-sm-11 col-sm-offset-1">{{ $title->name }}</div></td>
                        <td>
                            <div class="col-sm-12">
                            <?php
                                $title_budget = $title->budget()->where('year','=',$base['year'])->first();
                            ?>
                               
                                @if ( $title_budget )
                                    <input class="form-control" type="number" step="0.01" name="title_value[]" value="{{ $title_budget->value }}">
                                    <input class="form-control" type="hidden" name="title_id[]" value="{{ $title->id }}">
                                @else
                                    <input class="form-control" type="number" step="0.01" name="title_value[]" value="0">
                                    <input class="form-control" type="hidden" name="title_id[]" value="{{ $title->id }}">
                                @endif
                            </div>
                        </td>
                    </tr>

                    @foreach ($title->subs as $subs)
                        <tr>
                            <td><div class="col-sm-10 col-sm-offset-2">{{ $subs->name }}</div></td>
                            <td>
                                <div class="col-sm-12">
                                <?php
                                    $subs_budget = $subs->budget()->where('year','=',$base['year'])->first();
                                ?>
                               
                                @if ( $subs_budget )
                                    <input class="form-control" type="number" step="0.01" name="subtitle_value[]" value="{{ $subs_budget->value }}">
                                    <input class="form-control" type="hidden" name="subtitle_id[]" value="{{ $subs->id }}">
                                @else
                                    <input class="form-control" type="number" step="0.01" name="subtitle_value[]" value="0">
                                    <input class="form-control" type="hidden" name="subtitle_id[]" value="{{ $subs->id }}">
                                @endif
                                </div>
                            </td>
                        </tr>
                            @foreach ($subs->subtitleitems as $subtitleitem)
                                @if ($subtitleitem->show_in_monthly == 1)
                                <tr>
                                    <td><div class="col-sm-9 col-sm-offset-3">{{ $subtitleitem->item_name }}</div></td>
                                    <td>
                                        <div class="col-sm-12">
                                            <?php
                                                $subtitleitem_budget = $subtitleitem->budget()->where('year','=',$base['year'])->first();
                                            ?>
                                           
                                            @if ( $subs_budget )
                                                <input class="form-control" type="number" step="0.01" name="subtitleitems_value[]" value="{{ $subs_budget->value }}">
                                                <input class="form-control" type="hidden" name="subtitleitems_id[]" value="{{ $subtitleitem->id }}">
                                            @else
                                                <input class="form-control" type="number" step="0.01" name="subtitleitems_value[]" value="0">
                                                <input class="form-control" type="hidden" name="subtitleitems_id[]" value="{{ $subtitleitem->id }}">
                                            @endif
                                            
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        
                    @endforeach
                @endforeach
            @endforeach
        @endforeach

        </table>
    </div>

    <div class="form-group col-sm-12">
        <input type="submit" class="btn btn-primary" value="UPDATE">
    </div>
    {{ Form::close() }}
</div>
@endsection

@section('js')
<script>

    $("#amount").on("keyup", function() {
        var num = ($(this).val().replace(/[^0-9\.\,]/g,''));
        if(num.split('.').length > 2) {
            num = num.replace(/\.+$/,"");
            num = num.replace(/\,+$/,"");
        }
        $(this).val(num);
    })

    $("#amount").on("change", function() {
        var num2 = parseFloat($(this).val()).toLocaleString('en-US', { style: 'decimal'});
        $(this).val(num2);
    })

</script>
@endsection