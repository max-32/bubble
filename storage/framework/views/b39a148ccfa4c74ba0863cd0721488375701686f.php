<?php $__env->startPush('css-head'); ?>
<link href="assets/css/login_register.css" rel="stylesheet">
<style type="text/css">
  body {
    background: inherit;
  }
  .navbar {
    margin-bottom: 0px !important;
  }
  .page-content {
    margin-top: 70px !important;
  }
</style>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('js-bottom'); ?>
<script type="text/javascript">
  $(function() {
    // Animate login box when document is ready
    $('.card-group.animated').fadeIn(700);
  });
</script>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>

<div class="parallax filter-black">
<div class="parallax-image"></div>
<div class="small-info">

<div class="col-sm-10 col-sm-push-1 col-md-6 col-md-push-3 col-lg-6 col-lg-push-3">
  <div class="card-group animated" style="display:none;">
    <div class="card">
      <div class="card-block">
      <div class="center">
        <h4 class="m-b-0">
          <span class="icon-text">
            <a href="https://ru.wikipedia.org/wiki/OAuth" target="_blank" title="Как это работает?">OAuth 2.0</a>
            <span style="color:#666;">Авторизация</span>
          </span>
        </h4>
        <p class="text-muted">Войдите, используя одну из соц. сетей:</p>
      </div>

      <!-- Login errors box -->
      <div class="login-errors-box">
        
        <?php if($errors->any()): ?>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                <p style="color:#e46f61;"><b><?php echo e($error); ?></b></p>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
        <?php endif; ?>

        
        <?php if(Session::has('error')): ?>
           <p style="color:#e46f61;"><b><?php echo e(Session::get('error')); ?></b></p>
        <?php endif; ?>

        
        <?php if(Session::has('message')): ?>
           <p style="color:#a0d468;"><b><?php echo e(Session::get('message')); ?></b></p>
        <?php endif; ?>
      </div>
      <!-- End login errors box -->

      <!-- Cocial buttons box -->
      <div class="cocial-buttons-box">
        <a href="<?php echo e($vk_auth_link); ?>" class="btn btn-block btn-social btn-vk vk-btn social-buttons">
            <span class="fa fa-vk"></span>Вконтакте
        </a>
        <a href="<?php echo e($facebook_auth_link); ?>" class="btn btn-block btn-social btn-facebook facebook-btn social-buttons">
            <span class="fa fa-facebook"></span>Facebook
        </a>
        <a href="<?php echo e($google_auth_link); ?>" class="btn btn-block btn-social btn-google google-btn social-buttons">
            <span  class="fa fa-google"></span>Google
        </a>
        <a href="<?php echo e($instagram_auth_link); ?>" class="btn btn-block btn-social btn-instagram instagram-btn social-buttons">
            <span class="fa fa-instagram"></span>Instagram
        </a>
      </div>
      <!-- End social buttons box -->

      </div>
    </div>
  </div>
</div>

</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout/main_bubble', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>