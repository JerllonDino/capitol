@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
<style>
    .td_amt {
        width: 150px;
    }
    .td_nature {
        width: 450px;
    }
    .ui-autocomplete {
        max-height: 200px;
        overflow-y: scroll;
        overflow-x: hidden;
    }
    .autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
.autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
.autocomplete-selected { background: #F0F0F0; }
.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
.autocomplete-group { padding: 2px 5px; }
.autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
.select2-container{ width:100% !important; }
</style>
@endsection

@section('content')
<div class="row">
    {{ Form::open(['method' => 'POST', 'route' => ['sandgravel.types_clientsx']]) }}
    <div class="form-group col-sm-6">
        <label for="refno">TYPE DESC</label>
        <input type="text" class="form-control" name="type_desc" id="type_desc" value="" required>
        <input type="hidden" name="sandgravel_type_id" id="sandgravel_type_id" value="" >
    </div>

    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success" name="button" id="confirm">Add</button>
    </div>
    {{ Form::close() }}
</div>
<hr>

<div id="account_panel">


<dt>

<?php $count = 1; ?>


<table class="table" id="tbltype">
  <thead class="thead-dark">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Type</th>
      <th scope="col"></th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
    @foreach($base['sg_types'] as $key => $value)
         <tr>
          <th scope="row">{{$count}}</th>
          <td>{{$value->description}}</td>
          <td><button class="btn btn-info btn-sm" onclick="$(this).showEdit('{{$value->id}}','{{$value->description}}');">edit</button></td>
          <td>
              @if(!$value->deleted_at)
            <button class="btn btn-danger btn-sm" onclick="$(this).deleteTypes('{{$value->id}}','{{$value->description}}');">delete</button>
            @else
            <button class="btn btn-warning btn-sm" onclick="$(this).restoreTypes('{{$value->id}}','{{$value->description}}');">restore</button>
            @endif
          </td>
        </tr>
        <?php $count++; ?>
        
    @endforeach
  </tbody>
</table>
</dt>


</div>



@endsection

@section('js')
{{ Html::script('/vendor/autocomplete/jquery.autocomplete.js') }}
<script type="text/javascript">
    var collection_type = 'show_in_cashdivision';

    $.fn.showEdit = function(type_id,type_text){
        $('#confirm').text('Edit');
        $('#sandgravel_type_id').val(type_id);
        $('#type_desc').val(type_text);
    };

    $.fn.deleteTypes = function(type_id,type_text){
         $.ajax({
        type: 'POST',
        url: '{{ route("client_type.remove") }}',
        data: {
            '_token': '{{ csrf_token() }}',
            'type_id': type_id,
        },
        success: function(response) {
           location.reload(); 
        },
        error: function(response) {

        },
    });
    };

        $.fn.restoreTypes = function(type_id,type_text){
         $.ajax({
        type: 'POST',
        url: '{{ route("client_type.restore") }}',
        data: {
            '_token': '{{ csrf_token() }}',
            'type_id': type_id,
        },
        success: function(response) {
           location.reload(); 
        },
        error: function(response) {

        },
    });
    };

        $('#tbltype').DataTable({});


</script>

@endsection
