<?php $__env->startSection('content'); ?>


<?php if($errors->any()): ?>
<p class='text-center alert alert-warning'>
    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
        <span><?php echo e($error); ?></span>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
 </p>
<?php endif; ?>


<p class='text-center'>
	<?php if(Session::has('message')): ?>
	   <span class="alert alert-info"><?php echo e(Session::get('message')); ?></span>
	<?php endif; ?>
</p>


<p class='text-center'>Для продолжения Вам необходимо войти:</p>

<div class='text-center'>
	<div style="max-width:400px; margin:0 auto;">
		<a href="<?php echo e($vk_auth_link); ?>" class="btn btn-block btn-social btn-vk">
	    	<span style="position:relative; margin:0 12px 0 -14px; padding-right:12px;" class="fa fa-vk"></span>через Vk
	  	</a>
		<a href="<?php echo e($facebook_auth_link); ?>" class="btn btn-block btn-social btn-facebook">
	    	<span style="position:relative; margin:0 12px 0 -14px; padding-right:12px;" class="fa fa-facebook"></span>через Facebook
	  	</a>
		<a href="<?php echo e($google_auth_link); ?>" class="btn btn-block btn-social btn-google">
	    	<span style="position:relative; margin:0 12px 0 -14px; padding-right:12px;" class="fa fa-google"></span>через Google
	  	</a>
		<a href="<?php echo e($instagram_auth_link); ?>" class="btn btn-block btn-social btn-instagram">
	    	<span style="position:relative; margin:0 12px 0 -14px; padding-right:12px;" class="fa fa-instagram"></span>через Instagram
	  	</a>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout/main_bubble', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>