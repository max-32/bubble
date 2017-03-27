<?php $__env->startPush('css-head'); ?>
<style type="text/css">
  .widget {
    border: 1px solid #CFE8EF !important;
  }
</style>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('js-bottom'); ?>
<script type="text/javascript">
$(function()
{
  var page =
  {
    userAuth: ko.observable( userAuth ),
    userCurrent: ko.observable( userCurrent ),
  };

  ko.applyBindings( page, document.getElementById('body') );
});
</script>
<?php $__env->stopPush(); ?>


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



<div class="col-md-8 col-md-offset-2">
  <div class="row">
  <div class="col-md-12">
    <div class="cover profile">
      <div class="wrapper">
        <div class="image">
          <img src="img/cover/profile-cover.jpg" class="show-in-modal" alt="people" style="width:800px; height:300px; background-color:#eee;">
        </div>
        <ul class="friends">
          <li>
            <a href="#">
              <img src="img/friends/friend-dummy.png" alt="" class="img-responsive">
            </a>
          </li>
          <li>
            <a href="#">
              <img src="img/friends/friend-dummy.png" alt="" class="img-responsive">
            </a>
          </li>
          <li>
            <a href="#">
              <img src="img/friends/friend-dummy.png" alt="" class="img-responsive">
            </a>
          </li>
          <li>
            <a href="#">
              <img src="img/friends/friend-dummy.png" alt="" class="img-responsive">
            </a>
          </li>
          <li>
            <a href="#">
              <img src="img/friends/friend-dummy.png" alt="" class="img-responsive">
            </a>
          </li>
          <li>
            <a href="#">
              <img src="img/friends/friend-dummy.png" alt="" class="img-responsive">
            </a>
          </li>
          <li>
            <a href="#">
              <img src="img/friends/friend-dummy.png" alt="" class="img-responsive">
            </a>
          </li>
          <li>
            <a href="#">
              <img src="img/friends/friend-dummy.png" alt="" class="img-responsive">
            </a>
          </li>
          <li><a href="#" class="group"><i class="fa fa-group"></i></a></li>
        </ul>
      </div>
      <div class="cover-info">
        <div class="avatar">
          <img data-bind="attr: {src: userCurrent().photo()}" alt="people">
        </div>
        <div class="name">
          <a href="#" data-bind="text: userCurrent().fname() + ' ' + userCurrent().lname()"></a>
        </div>
        <ul class="cover-nav">
          <?php if($isOwner): ?>
            <li><a href="<?php echo e(route('settings')); ?>"><i class="fa fa-fw fa-gears"></i></a></li>
          <?php endif; ?>
          <li class="active">
            <a href="<?php echo e(route('search-create')); ?>"><i class="fa fa-fw fa-search"></i> Искать</a>
          </li>
          <li>
            <a href=""><i class="fa fa-fw fa-feed"></i> Лента</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  </div>

  <div class="row">
  <div class="col-md-5">
    <div class="widget">
      <div class="widget-header">
        <h3 class="widget-caption">О пользователе</h3>
      </div>
      <div class="widget-body bordered-top bordered-sky">
        <ul class="list-unstyled profile-about margin-none">
          <li class="padding-v-5">
            <div class="row">
              <div class="col-sm-4"><span class="text-muted">Пол:</span></div>
              <!-- ko if: userCurrent().sex() == 'М' -->
              <div class="col-sm-8">Мужской</div>
              <!-- /ko -->
              <!-- ko if: userCurrent().sex() == 'Ж' -->
              <div class="col-sm-8">Женский</div>
              <!-- /ko -->
              <!-- ko ifnot: userCurrent().sex() -->
              <div class="col-sm-8 text-muted">(не указан)</div>
              <!-- /ko -->
            </div>
          </li>
        </ul>
      </div>
    </div>

    <div class="widget widget-friends">

      <div class="widget-header">
        <h3 class="widget-caption">Друзья</h3>
      </div>
      <div class="widget-body bordered-top  bordered-sky">
        <div class="row">
          <div class="col-md-12">
            <ul class="img-grid" style="margin: 0 auto;">
              <li>
                <a href="#">
                  <img src="img/friends/friend-dummy.png" alt="image">
                </a>
              </li>
              <li>
                <a href="#">
                  <img src="img/friends/friend-dummy.png" alt="image">
                </a>
              </li>
              <li>
                <a href="#">
                  <img src="img/friends/friend-dummy.png" alt="image">
                </a>
              </li>
              <li>
                <a href="#">
                  <img src="img/friends/friend-dummy.png" alt="image">
                </a>
              </li>
              <li>
                <a href="#">
                  <img src="img/friends/friend-dummy.png" alt="image">
                </a>
              </li>
              <li class="clearfix">
                <a href="#">
                  <img src="img/friends/friend-dummy.png" alt="image">
                </a>
              </li>
              <li>
                <a href="#">
                  <img src="img/friends/friend-dummy.png" alt="image">
                </a>
              </li>
              <li>
                <a href="#">
                  <img src="img/friends/friend-dummy.png" alt="image">
                </a>
              </li>
              <li>
                <a href="#">
                  <img src="img/friends/friend-dummy.png" alt="image">
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout/main_bubble', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>