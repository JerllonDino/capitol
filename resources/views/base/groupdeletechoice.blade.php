@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div title="Delete Group">
            <p>Choose one of the following:</p>
            {{ Form::open([ 'route' => ['group.delete', $base['group']['id']], 'method' => 'post' ]) }}
            <div class="form-group col-sm-12">
                <div class="col-md-6 radio">
                   <label><input type="radio" name="deletetype" required value="1"/>Delete users and group</label>
                </div>
            </div>
            <div class="form-group col-sm-12">
                <div class="col-md-6 radio">
                   <label><input type="radio" name="deletetype" required value="2"/>Delete current group, create new group and move members</label>
                </div>
            </div>
            <div class="form-group col-md-6 hidden groupdetails">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" />
            </div>
            <div class="form-group col-md-6 hidden groupdetails">
                <label for="name">Description</label>
                <input type="text" name="description" id="description" class="form-control" />
            </div>
            <div class="form-group col-sm-12">
                <div class="col-md-6 radio">
                    <label><input type="radio" name="deletetype" required value="3"/>Delete group and move members to an existing group</label>
                </div>
            </div>
            <div class="col-md-6">
                <select class="form-control hidden" id="group" name="group" required>
                    <option value="">Group Name</option>
                    @foreach($base['group_details'] as $group)
                        <option title="{{ $group['description'] }}" value="{{ $group['id'] }}" >{{ $group['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-warning datatable-btn pull-right">Delete</button>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection

@section('js')
{{ Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js') }}
{{ Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js') }}
<script>

@if ( Session::get('permission')['group'] & $base['can_write'] )
$("div.dt-custom").html(
    '<a href="{{ route("group.create") }}" class="btn btn-med btn-success">Add</a>'
);
$(".radio").on("click", function(event){

    var checked = $("input[type='radio']:checked").val();
    if( checked==3 ) {
        $("#group").removeClass('hidden');
        $("#group").prop("required", true);
        $(".groupdetails").addClass('hidden');
        $("#name").prop('required', false);
        $("#description").prop('required', false);
    } else if( checked==2 ) {
        $(".groupdetails").removeClass('hidden');
        $("#name").prop('required', true);
        $("#description").prop('required', true);
        $("#group").addClass('hidden');
        $("#group").prop("required", false);
    } else {
        $(".groupdetails").addClass('hidden');
        $("#group").addClass('hidden');
        $("#group").prop("required", false);
        $("#name").prop('required', false);
        $("#description").prop('required', false);
    }
})

@endif
</script>
@endsection