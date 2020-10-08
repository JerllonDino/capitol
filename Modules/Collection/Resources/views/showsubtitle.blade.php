@extends('nav')

@section('content')
<div class="row">
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{$accountsub->name}}</dd>
            <dt>SubTitleOf</dt>
            <dd>{{$accountttl->name}}</dd>
        </dl>
    </div>
</div>
<div class="row">
    {{ Form::open([ 'method' => 'DELETE', 'id' => 'subform', 'route' => 
    ['account_subtitle.destroy', $accountsub->id] ]) }}
      <div class="form-group col-sm-12">
        @if ( Session::get('permission')['col_serial'] & $base['can_write'] )
        <a href="{{ route('account_subtitle.edit', $accountsub->id) }}" class="btn btn-info datatable-btn">
            Update
        </a>
        @endif
      </div>
    {{ Form::close() }}
</div>
@endsection

@section('js')
<script type="text/javascript">
  var confirm;
    $(document).ready(function(){

      confirm = $('#dialog').dialog({
        autoOpen:false,
        closeOnEscape: false,
        open: function(event, ui) {
            $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
        },
        show:{
          effect:'bounce',
          duration:1000
        },
        hide:{
          effect:'shake',
          duration:500
        },
        resizable:false,
        height:"auto",
        width:300,
        modal:true
      });
    });

    $('#close').click(function(){
      confirm.dialog('close');
    });
</script>
@endsection
