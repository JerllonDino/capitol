<!-- Error messages -->
@if (!$errors->isEmpty() || Session::has('error'))
<div class="row">
    <div class="col-lg-12">
        <div id="error-blk" class="alert alert-danger alert-dismissible">
            <button class="close" aria-hidden="true">x</button>
            @foreach ($errors->all() as $error)
            <span class="msg">{!! $error !!}</span>
            @endforeach
            
            @if (Session::has('error'))
                @foreach (Session::get('error') as $error)
                <span class="msg">{!! $error !!}</span>
                @endforeach
            @endif
        </div>
    </div>
</div>
@else
<div class="row row-hidden">
    <div class="col-lg-12">
        <div id="error-blk" class="alert alert-danger alert-dismissible">
            <button class="close" aria-hidden="true">x</button>
        </div>
    </div>
</div>
@endif

<!-- Info messages -->
@if (Session::has('info'))
<div class="row">
    <div class="col-lg-12">
        <div id="info-blk" class="alert alert-info alert-dismissible">
            <button class="close" aria-hidden="true">x</button>
            @foreach (Session::get('info') as $info)
            <span class="msg">{!! $info !!}</span>
            @endforeach
        </div>
    </div>
</div>
@else
<div class="row row-hidden">
    <div class="col-lg-12">
        <div id="info-blk" class="alert alert-info alert-dismissible">
            <button class="close" aria-hidden="true">x</button>
        </div>
    </div>
</div>
@endif

@if (Session::has('danger'))
<div class="row">
    <div class="col-lg-12">
        <div id="info-blk" class="alert alert-danger alert-dismissible">
            <button class="close" aria-hidden="true">x</button>
            @foreach (Session::get('danger') as $danger)
            <span class="msg">{!! $danger !!}</span>
            @endforeach
        </div>
    </div>
</div>
@endif