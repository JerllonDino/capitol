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
								@if ( Session::get('permission')['col_settings'] & $base['can_write'] )
								<a href="{{ route('rates.edit', ['type' => 'title', 'id' => $title->id]) }}" class="btn btn-info">Set Rate</a>
								@endif
                            </div>
                        </td>
                    </tr>
                    
                    @foreach ($title->subs as $subs)
                        <tr>
                            <td><div class="col-sm-10 col-sm-offset-2">{{ $subs->name }}</div></td>
                            <td>
                                <div class="col-sm-12">
								@if ( Session::get('permission')['col_settings'] & $base['can_write'] )
								<a href="{{ route('rates.edit', ['type' => 'subtitle', 'id' => $subs->id]) }}" class="btn btn-info">Set Rate</a>
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
</div>
@endsection

@section('js')
<script>
    
</script>
@endsection