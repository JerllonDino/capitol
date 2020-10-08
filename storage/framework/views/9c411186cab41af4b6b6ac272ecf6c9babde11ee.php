

<?php $__env->startSection('page'); ?>
<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo e(route("profile.dashboard")); ?>"><?php echo e($base['site_title']); ?></a>
        </div>

        <!-- Top Menu Items -->
        <ul class="nav navbar-right top-nav">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo e(Session::get('user')->realname); ?> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="<?php echo e(route("profile.edit")); ?>"><i class="fa fa-fw fa-user"></i> Profile</a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="<?php echo e(route("session.logout")); ?>"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li class="top-menu">
                    <a href="javascript:;" data-toggle="collapse" data-target="#profile"><i class="fa fa-user"></i> <?php echo e(Session::get('user')->realname); ?> <i class="fa fa-fw fa-caret-down pull-right"></i></a>
                    <ul id="profile" class="collapse">
                        <li>
                            <a href="<?php echo e(route("profile.edit")); ?>"><i class="fa fa-fw fa-user"></i> Profile</a>
                        </li>
                        <li>
                            <a href="<?php echo e(route("session.logout")); ?>"><i class="fa fa-angle-double-right"></i> Log Out</a>
                        </li>
                    </ul>
                </li>

                <?php foreach($base['navigation'] as $i => $nav): ?>
                <?php if(empty($nav['children'])): ?>
                <li>
                    <a href="<?php echo e(route($nav['route'])); ?>"><i class="fa fa-fw <?php echo e($nav['icon']); ?>"></i> <?php echo e($nav['title']); ?> </a>
                </li>
                <?php else: ?>
                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#link-<?php echo e($i); ?>"><i class="fa fa-fw <?php echo e($nav['icon']); ?>"></i> <?php echo e($nav['title']); ?> <i class="fa fa-fw fa-caret-down pull-right"></i></a>
                    <ul id="link-<?php echo e($i); ?>" class="collapse">
                        <?php foreach($nav['children'] as $n): ?>
                        <li>
                            <a href="<?php echo e(route($n['route'])); ?>"><i class="fa fa-angle-double-right"></i> <?php echo e($n['title']); ?></a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <?php endif; ?>
                <?php endforeach; ?>

            </ul>
        </div>
    </nav>

    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        <?php echo e($base['page_title']); ?>

                        <?php if(!empty($base['sub_header'])): ?>
                        <small><?php echo e($base['sub_header']); ?></small>
                        <?php endif; ?>
                    </h1>
                    <?php if(isset($_GET['types'])): ?>
                     <ol class="breadcrumb">
                        <li class="active">
                            <a href="<?php echo e(route('field_land_tax.index')); ?>">Field Land Tax</a>
                        </li>
                       <li class="active">
                                Field Land Tax View
                            </li>
                    </ol>
                    <?php else: ?>
                        <ol class="breadcrumb">
                        <?php foreach($base['breadcrumbs'] as $breadcrumb_ctr => $breadcrumb): ?>
                            <?php if($breadcrumb_ctr === (count($base['breadcrumbs']) - 1)): ?>
                            <li class="active">
                                <?php echo e($breadcrumb['title']); ?>

                            </li>
                            <?php else: ?>
                            <li>
                                <a href="<?php echo e($breadcrumb['url']); ?>"><?php echo e($breadcrumb['title']); ?></a>
                            </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </ol>
                </div>
            </div>

            <?php echo $__env->make('message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

            <?php echo $__env->yieldContent('content'); ?>

        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>