@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{ $base['group']['name'] }}</dd>
            <dt>Description</dt>
            <dd>{{ $base['group']['description'] }}</dd>
        </dl>
    </div>
</div>
<div class="row">
    {{ Form::open([ 'route' => ['group.destroy', $base['group']['id']], 'method' => 'delete', 'id'=>'form' ]) }}
    <div class="form-group col-sm-12">
        @if ( Session::get('permission')['group'] & $base['can_write'] )
        <a href="{{ route('group.edit', $base['group']['id']) }}" class="btn btn-info datatable-btn">
            Update
        </a>
        @endif
        
        @if ( Session::get('permission')['group'] & $base['can_read'] )
        <a href="{{ route('group.permission.index', $base['group']['id']) }}" class="btn btn-info datatable-btn">
            Permissions
        </a>
        @endif
        
        @if ( Session::get('permission')['group'] & $base['can_delete'] )
        <button type="submit" class="btn btn-danger datatable-btn pull-right deletebtn">
            Delete
        </button>
		<!-- <input type="button" id="grp_rm_btn" class="btn btn-danger datatable-btn pull-right" value="Delete"> -->
        @endif
    </div>
</div>

@if ( Session::get('permission')['user'] & $base['can_read'] )
<div class="row">
    <div class="col-lg-12">
        <table id="grouplist" class="dtable table table-striped table-hover">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Realname</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endif

@endsection

@section('js')
{{ Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js') }}
{{ Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js') }}
<script>
$('#grouplist').DataTable({
    dom: '<"dt-custom">frtip',
    processing: true,
    serverSide: true,
    ajax: '{{ route("datatables", ["group_members", "id" => $base["group"]["id"]]) }}',
    columns: [
        { data: 'username', name: 'username' },
        { data: 'realname', name: 'realname' },
        { data:
            function(data) {
                var view = '';
                var write = '';
                @if ( Session::get('permission')['user'] & $base['can_read'] )
                var view = '<a href="{{ route('user.index') }}/'+data.id+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                @endif
                @if ( Session::get('permission')['user'] & $base['can_write'] )
                var write = '<a href="{{ route('user.index') }}/'+data.id+'/edit" class="btn btn-sm btn-info datatable-btn" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                @endif
                return view + write;
            },
            bSortable: false,
            searchable: false,
        }
    ]
});

$("div.dt-custom").html('<h1>Members</h1>');

$('#grp_rm_dlg').dialog({
	autoOpen: false,
	draggable: false,
	modal: true,
	resizable: false,
	title: 'Select action for group members',
	width: 'auto',
});

$('#grp_rm_btn').click( function() {
	$.ajax({
		type: 'POST',
		dataType: 'JSON',
		url: '{{ url("ajax") }}',
		data: {
			'_token': '{{ csrf_token() }}',
			'action': 'get_groupmembers',
			'groupid': {{ $base['group']['id'] }},
		},
		
		success: function(response) {
			// Show dialog if group has members and delete if empty
			if (response.members.length > 0) {
				$('#grp_rm_dlg').dialog('open');
			} else {
				var path = '{{ route("group.destroy", [$base["group"]["id"]]) }}';
				var parameters = {
					'_method': 'DELETE',
					'_token': '{{ csrf_token() }}',
				};
				post(path, parameters);
			}
		},
		
		error: function(response) {
			
		},
	});
});

function post(path, parameters) {
	var form = $('<form></form>');
	
	form.attr('method', 'POST');
	form.attr('action', path);
	
	$.each(parameters, function(key, value) {
		var field = $('<input>');
		
		field.attr('type', 'hidden');
		field.attr('name', key);
		field.attr('value', value);
		
		form.append(field);
	});
	
	$(document.body).append(form);
	form.submit();
}
</script>
@endsection