

<?php $__env->startSection('css'); ?>
<?php echo e(Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css')); ?>

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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <?php echo e(Form::open(['method' => 'POST', 'route' => ['sandgravel.types_clientsx']])); ?>

    <div class="form-group col-sm-6">
        <label for="refno">TYPE DESC</label>
        <input type="text" class="form-control" name="type_desc" id="type_desc" value="" required>
        <input type="hidden" name="sandgravel_type_id" id="sandgravel_type_id" value="" >
    </div>

    <div class="form-group col-sm-12">
        <button type="submit" class="btn btn-success" name="button" id="confirm">Add</button>
    </div>
    <?php echo e(Form::close()); ?>

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
    <?php foreach($base['sg_types'] as $key => $value): ?>
         <tr>
          <th scope="row"><?php echo e($count); ?></th>
          <td><?php echo e($value->description); ?></td>
          <td><button class="btn btn-info btn-sm" onclick="$(this).showEdit('<?php echo e($value->id); ?>','<?php echo e($value->description); ?>');">edit</button></td>
          <td>
              <?php if(!$value->deleted_at): ?>
            <button class="btn btn-danger btn-sm" onclick="$(this).deleteTypes('<?php echo e($value->id); ?>','<?php echo e($value->description); ?>');">delete</button>
            <?php else: ?>
            <button class="btn btn-warning btn-sm" onclick="$(this).restoreTypes('<?php echo e($value->id); ?>','<?php echo e($value->description); ?>');">restore</button>
            <?php endif; ?>
          </td>
        </tr>
        <?php $count++; ?>
        
    <?php endforeach; ?>
  </tbody>
</table>
</dt>


</div>



<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<?php echo e(Html::script('/vendor/autocomplete/jquery.autocomplete.js')); ?>

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
        url: '<?php echo e(route("client_type.remove")); ?>',
        data: {
            '_token': '<?php echo e(csrf_token()); ?>',
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
        url: '<?php echo e(route("client_type.restore")); ?>',
        data: {
            '_token': '<?php echo e(csrf_token()); ?>',
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>