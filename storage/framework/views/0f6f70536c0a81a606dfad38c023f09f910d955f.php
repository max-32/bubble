<?php $__env->startSection('content'); ?>
<p>Here is my content.</p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout/main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>