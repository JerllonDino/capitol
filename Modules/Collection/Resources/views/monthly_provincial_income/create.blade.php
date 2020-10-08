@extends('nav')

@section('content')
<div class="row">
    {{ Form::open([ 'method' => 'POST', 'route' => 'monthly_provincial_income.store' ]) }}
    
    <div class="form-group col-sm-6">
        <label for="month">Month</label>
        <select name="month" class="form-control" required autofocus>
            @foreach ($base['months'] as $i => $month)
                @if ($i + 1 == date('m'))
                <option value="{{ $i + 1 }}" selected>{{ $month }}</option>
                @else
                <option value="{{ $i + 1 }}">{{ $month }}</option>
                @endif
            @endforeach
        </select>
    </div>
    
    <div class="form-group col-sm-6">
        <label for="year">Year</label>
        <input type="number" class="form-control" step="1" name="year" value="{{ date('Y') }}" required>
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
								@if ($title->show_in_monthly == 1)
                                <input class="form-control" type="number" step="0.01" name="title_value[]">
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
									@if ($subs->show_in_monthly == 1)
                                    <input class="form-control" type="number" step="0.01" name="subtitle_value[]">
                                    <input class="form-control" type="hidden" name="subtitle_id[]" value="{{ $subs->id }}">
									@endif
                                </div>
                            </td>
                        </tr>
						
                        
                    @endforeach
                @endforeach
            @endforeach
        @endforeach
        </table>
    </div>

    <div class="form-group col-sm-12">
        <input type="submit" class="btn btn-primary" value="ADD">
    </div>
    {{ Form::close() }}
</div>
@endsection