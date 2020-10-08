@extends('nav')

@section('content')
<div class="row">
    {{ Form::open([ 'route' => ['settings_ada.update'], 'method' => 'post', 'class' => 'form-horizontal' ]) }}
        <div class="col-sm-12">
        
            <div class="form-group">
                <label class="control-label col-sm-4" for="bank_name">Bank Name</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="bank_name" value="{{ $base['ada_settings'][0]->value }}" required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="control-label col-sm-4" for="bank_number">Bank Number</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="bank_number" value="{{ $base['ada_settings'][1]->value }}" required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="control-label col-sm-4"></label>
                <div class="col-sm-8">
                    <input type="submit" class="btn btn-success" id="submit" value="Submit">
                </div>
            </div>
            
        </div>
    {{ Form::close() }}
</div>
@endsection