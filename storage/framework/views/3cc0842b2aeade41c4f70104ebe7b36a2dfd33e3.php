<!-- Error messages -->
<?php if(!$errors->isEmpty() || Session::has('error')): ?>
<div class="row">
    <div class="col-lg-12">
        <div id="error-blk" class="alert alert-danger alert-dismissible">
            <button class="close" aria-hidden="true">x</button>
            <?php foreach($errors->all() as $error): ?>
            <span class="msg"><?php echo $error; ?></span>
            <?php endforeach; ?>
            
            <?php if(Session::has('error')): ?>
                <?php foreach(Session::get('error') as $error): ?>
                <span class="msg"><?php echo $error; ?></span>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php else: ?>
<div class="row row-hidden">
    <div class="col-lg-12">
        <div id="error-blk" class="alert alert-danger alert-dismissible">
            <button class="close" aria-hidden="true">x</button>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Info messages -->
<?php if(Session::has('info')): ?>
<div class="row">
    <div class="col-lg-12">
        <div id="info-blk" class="alert alert-info alert-dismissible">
            <button class="close" aria-hidden="true">x</button>
            <?php foreach(Session::get('info') as $info): ?>
            <span class="msg"><?php echo $info; ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php else: ?>
<div class="row row-hidden">
    <div class="col-lg-12">
        <div id="info-blk" class="alert alert-info alert-dismissible">
            <button class="close" aria-hidden="true">x</button>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if(Session::has('danger')): ?>
<div class="row">
    <div class="col-lg-12">
        <div id="info-blk" class="alert alert-danger alert-dismissible">
            <button class="close" aria-hidden="true">x</button>
            <?php foreach(Session::get('danger') as $danger): ?>
            <span class="msg"><?php echo $danger; ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>