@extends('nav')

@section('content')
<div class="row">
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>Fullname</dt>
            <dd>{{ $base['user']['realname'] }}</dd>
            <dt>Username</dt>
            <dd>{{ $base['user']['username'] }}</dd>
            <dt>Position</dt>
            <dd>{{ $base['user']['position'] }}</dd>
            <dt>Email</dt>
            <dd>{{ $base['user']['email'] }}</dd>
            <dt>Group</dt>
            <dd>
                <a href="{{ route('group.show', $base['user']->group->id) }}">
                    {{ $base['user']->group->name }}
                </a>
            </dd>
        </dl>
    </div>
</div>
<div class="row">
    {{ Form::open([ 'method' => 'delete', 'id' => 'userform', 'action' => ['UserController@destroy', $base['user']['id']] ]) }}
        <div class="form-group col-sm-12">
            
            @if ( Session::get('permission')['user'] & $base['can_write'] )
            <a href="{{ route('user.edit', $base['user']['id']) }}" class="btn btn-info datatable-btn">
                Update
            </a>
            @endif
            
            @if ( Session::get('permission')['user'] & $base['can_delete'] )
            <button type="button" id="delete" class="btn btn-danger datatable-btn pull-right">
                Delete
            </button>
            @endif
            
        </div>
    {{ Form::close() }}
</div>

<div id="delete_confirm">
    Are you sure you want to delete '{{ $base['user']['username'] }}'?
</div>
@endsection

@section('js')
<script type="text/javascript">
    $('#delete').click( function() {
        $('#delete_confirm').dialog('open');
    });

    $('#delete_confirm').dialog({
        autoOpen: false,
        draggable:false,
        modal: true,
        resizable: false,
        title: 'Delete User?',
        width: 'auto',
        buttons: {
            'Delete': function() {
                $('#userform').submit();
            },
            
            'Cancel': function() {
                $(this).dialog('close');
            },
        },
    });
</script>
@endsection
