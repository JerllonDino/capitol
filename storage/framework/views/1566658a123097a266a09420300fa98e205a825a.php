

<?php $__env->startSection('content'); ?>
<div class="row">
    <?php echo e(Form::open(['method' => 'PUT', 'route' => ['customer.update', $base['customer']->id]])); ?>

    <div class="form-group col-sm-8">
        <label for="name">Name</label>
        <input type="text" class="form-control" name="name" value="<?php echo e($base['customer']->name); ?>" required autofocus>
    </div>
    <div class="form-group col-sm-4">
        <label for="customer_type">Client Type</label>
             <select class="form-control" name="customer_type" id="customer_type">
            <option ></option>
            <?php foreach($base['sandgravel_types'] as $sandgravel_types): ?>
                <option value="<?php echo e($sandgravel_types['id']); ?>" <?php if($sandgravel_types['id'] == $base['customer']->customer_type_id): ?> selected <?php endif; ?> ><?php echo e($sandgravel_types['description']); ?></option>
            <?php endforeach; ?>
            </select>
    </div>
    
    <div class="form-group col-sm-12">
        <label for="address">Address</label>
        <textarea class="form-control" name="address"><?php echo e($base['customer']->address); ?></textarea>
    </div>

    <div class="form-group col-sm-12">
      <button type="submit" class="btn btn-success" name="button" id="confirm">Update</button>
    </div>
    <?php echo e(Form::close()); ?>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>