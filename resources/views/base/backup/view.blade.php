@extends('nav')

@section('css')
<style>
    .hidden {
        display: none;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>Creation Date</dt>
            <dd>{{ $base['backup']->date_of_entry }}</dd>
            <dt>Remark</dt>
            <dd>{{ $base['backup']->remark }}</dd>
        </dl>
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-12">
        <button type="button" class="restore btn btn-sm btn-info datatable-btn" title="Restore">Restore Database</button>
        <button type="button" class="delete btn btn-sm btn-danger datatable-btn pull-right" title="Delete">Delete Backup</button>
    </div>
</div>                                                                                                        
<div class="row hidden">
    {{ Form::open([ 'method' => 'delete', 'id' => 'delete_form', 'action' => ['BackupController@destroy', $base['backup']['id']] ]) }}
        <input type="hidden" name="delete_id" value="{{ $base['backup']->id }}">
    {{ Form::close() }}
</div>
<div class="row hidden">
    {{ Form::open([ 'method' => 'post', 'id' => 'restore_form', 'action' => ['BackupController@restore', $base['backup']['id']] ]) }}
        <input type="hidden" name="restore_id" value="{{ $base['backup']->id }}">
    {{ Form::close() }}
</div>

<div id="restore_confirm" class="hidden">
    Are you sure you want to restore with this backup?
</div>

<div id="delete_confirm" class="hidden">
    Are you sure you want to delete this backup?
</div>
@endsection

@section('js')
<script type="text/javascript">
$(document).on('click', '.delete', function() {
    var id = $(this).next('input').val();
    $('#delete_confirm').removeClass('hidden');
    $('#delete_confirm').data('id', id).dialog('open');
});

$(document).on('click', '.restore', function() {
    var id = $(this).next().next('input').val();
    $('#restore_confirm').removeClass('hidden');
    $('#restore_confirm').data('id', id).dialog('open');
});

$('#delete_confirm').dialog({
    autoOpen: false,
    draggable:false,
    modal: true,
    resizable: false,
    title: 'Delete Backup?',
    width: 'auto',
    buttons: {
        'Delete': function() {
            $('#delete_form').submit();
        },
        
        'Cancel': function() {
            $(this).dialog('close');
        },
    },
});

$('#restore_confirm').dialog({
    autoOpen: false,
    draggable:false,
    modal: true,
    resizable: false,
    title: 'Restore Backup?',
    width: 'auto',
    buttons: {
        'Restore': function() {
            $('#restore_form').submit();
        },
        
        'Cancel': function() {
            $(this).dialog('close');
        },
    },
});
</script>
@endsection
