@extends('nonav')

@section('content')
<div class="row">
    <div class="col-sm-4 col-sm-offset-4" id="login-form">
        <div class="form-group">
            <center>
                @if(!empty($logo))
                {{ Html::image($logo, "Logo", array('width' => 250, 'height' => 250)) }}
                @endif
            </center>
        </div>
        
        @include('message')
        
        {{ Form::open([ 'route' => ['session.login'], 'method' => 'post' ]) }}
            <div class="form-group">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" autofocus required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="submit" class="col-sm-12 btn btn-success" id="submit" value="Log in">
            </div>
        {{ Form::close() }}
    </div>
</div>
@endsection
