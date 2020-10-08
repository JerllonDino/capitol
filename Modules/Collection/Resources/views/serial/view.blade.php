@extends('nav')

@section('content')
<div class="row">
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>Accountable Form</dt>
            <dd>{{ $base['serial']->formtype->name }}</dd>
            <dt>Serial Begin</dt>
            <dd>{{ $base['serial']->serial_begin }}</dd>
            <dt>Serial End</dt>
            <dd>{{ $base['serial']->serial_end }}</dd>
            <dt>Serial In Use</dt>
            <dd>{{ $base['serial']->serial_current }}</dd>
            <dt>Date Added</dt>
            <dd>{{ date('F d, Y', strtotime($base['serial']->date_added)) }}</dd>
            @if ($base['serial']->unit !== null)
                <dt>Unit</dt>
                <dd>{{ $base['serial']->unit }}</dd>
            @endif
            @if ($base['serial']->acct_cat_id !== null)
                <dt>Fund</dt>
                <dd>{{ $base['serial']->fund->name }}</dd>
            @endif
            @if ($base['serial']->municipality !== null)
                <dt>Municipality</dt>
                <dd>{{ $base['serial']->municipality->name }}</dd>
            @endif
        </dl>
    </div>
</div>
<div class="row">
    {{ Form::open([ 'route' => ['serial.destroy', $base['serial']['id']], 'method' => 'delete', 'id' => 'serialform' ]) }}
        @if ( $base['serial']->serial_begin == $base['serial']->serial_current )
        <div class="form-group col-sm-12">
            
            @if ( Session::get('permission')['col_serial'] & $base['can_write'] )
            <a href="{{ route('serial.edit', $base['serial']['id']) }}" class="btn btn-info datatable-btn">
                Update
            </a>
            @endif
            
            @if ( Session::get('permission')['col_serial'] & $base['can_delete'] )
            <button type="button" id="delete" class="btn btn-danger datatable-btn pull-right">
                Delete
            </button>
            @endif
            
        </div>
        @endif
    {{ Form::close() }}
</div>

<div id="delete_confirm">
    Are you sure you want to delete this serial?
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
                $('#serialform').submit();
            },
            
            'Cancel': function() {
                $(this).dialog('close');
            },
        },
    });
</script>
@endsection
