@extends('nav')

@section('css')
<style>
.btn-file {
    position: relative;
    overflow: hidden;
}

.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
}
</style>
@endsection

@section('content')
<div class="row">
    {{ Form::open([ 'route' => ['settings.update'], 'method' => 'post', 'files' => true, 'class' => 'form-horizontal' ]) }}
        <div class="col-sm-12">
            
            <div class="form-group">
                <label class="control-label col-sm-4" for="logo">Logo</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <span class="input-group-btn">
                            <span class="btn btn-default btn-file">
                                Browse... <input type="file" name="logo" id="logo">
                            </span>
                        </span>
                        <input type="text" value="{{ $base['settings'][0]['value'] }}" class="form-control" readonly>
                        <span class="input-group-btn">
                            @if (empty($base['settings'][0]['value']))
                            <a href="#" class="btn btn-default" title="View" id="img-aload">
                                <i class="fa fa-search"></i>
                            </a>
                            @else
                            <a href="{{ URL::to('/') }}/base/img/{{ $base['settings'][0]['value'] }}" class="btn btn-default" data-featherlight="image" title="View" id="img-aload">
                                <i class="fa fa-search"></i>
                            </a>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- <div class="form-group">
                <label class="control-label col-sm-4" for="auditlogdays">Delete Audit Logs older than N days</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" name="auditlogdays" min="-1" step="1" value="{{ $base['settings'][1]['value'] }}" placeholder="Input 0 to never keep logs or -1 to never delete logs">
                </div>
            </div> -->
            
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

@section('js')
<script>
$(document).ready( function() {
    $(document).on('change', '.btn-file :file', function() {
        var input = $(this),
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [label]);
    });
    
    $('.btn-file :file').on('fileselect', function(event, label) {
        var input = $(this).parents('.input-group').find(':text');
        var log = label;
        
        if( input.length ) {
            input.val(log);
        }
    });
    
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#img-aload').attr('href', e.target.result);
                $('#img-aload').attr('data-featherlight', 'image');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#logo").change(function(){
        readURL(this);
    });
});
</script>
@endsection