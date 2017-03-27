<?php $__env->startPush('css-head'); ?>
<link href="assets/css/login_register.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Gloria+Hallelujah" rel="stylesheet">
<style type="text/css">
  .quote-font {
    font-family: 'Gloria Hallelujah', cursive;
  }
  body {
    background: inherit;
  }
  .navbar {
    margin-bottom: 0px !important;
  }
  .page-content {
    margin-top: 70px !important;
  }
  .card {
    padding: 20px;
    margin-top: 8px;
    border: 1px solid #aaa;
  }
  .img-pulp-fiction {
    margin-top: -54px;
  }
  .navbar-fixed-top {
    display: none;
  }
  .page-content {
    margin-top: 0px !important;
  }
  .card {
    margin-right: 4px !important;
    margin-left: 4px !important;
  }
  .card:hover {
    background: #FAF7FD;
    cursor: pointer;
  }
</style>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('js-bottom'); ?>
<script type="text/javascript">
  $(function() {

    // go back
    $('.card').click(function() {
      window.history.go(-1);
    });

    // show "go back" arrow
    $('.card').hover(function() {
      $('#back-arrow').show();
    },function() {
      $('#back-arrow').fadeOut(740);
    });

  });
</script>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('navbar-item-guest'); ?>
<li>
  <a href="<?php echo e(URL::previous()); ?>">Назад</a>
</li>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-sm-10 col-sm-push-1 col-md-6 col-md-push-3 col-lg-6 col-lg-push-3">
    <div class="card">

      <div class="center">
        <h4 class"m-b-0">
         <p id="back-arrow" style="position:absolute; display:none;"><<<</p> Немного о "Bubble"
        </h4>
      
        <p class="quote-font">
          &laquo; Путь праведника труден, ибо препятствуют ему себялюбивые и тирания злых людей. Блажен тот пастырь, кто во имя милосердия и доброты ведет слабых за собой сквозь долину тьмы, ибо именно он и есть тот самый, кто воистину печется о ближних своих. И совершу над ними великое мщение наказаниями яростными над теми, кто замыслит отравить и повредить братьям моим, и узнаешь ты, что имя моё - Господь, когда мщение моё падёт на тебя &#187;
        </p>
        <img src="img/pulp-finction.png" class="img-responsive img-pulp-fiction">
      </div>

  </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout/main_bubble', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>