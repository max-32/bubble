<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Bubble">
    <meta name="keywords" content="Bubble">
    <meta name="author" content="Max Novikov, Bryansk, Zhukovka">
    <link rel="icon" href="/img/favicon-bubble.png">
    
    <title><?php echo $__env->yieldContent('title', 'Bubble'); ?></title>

    <style type="text/css">
      /* cyrillic */
      @font-face {
        font-family: 'Philosopher';
        font-style: normal;
        font-weight: 400;
        src: local('Philosopher Regular'), local('Philosopher-Regular'), url(/fonts/Philosopher-Regular.ttf) format('woff2');
        unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
      }

      body {
        font-family: 'Philosopher', sans-serif !important;
        font-size: 1.55em !important;
      }

      h1,h2,h3 {
        font-family: 'Philosopher', sans-serif !important;
        font-size: 1.em !important;
      }

      .widget-caption {
        font-family: 'Philosopher', sans-serif !important;
        font-size: 1em !important;
      }
    </style>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap-fix.css" rel="stylesheet">
    <link href="/bootstrap.3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="/font-awesome.4.6.1/css/font-awesome.min.css" rel="stylesheet">
    <link href="/assets/css/animate.min.css" rel="stylesheet">
    <link href="/assets/css/timeline.css" rel="stylesheet">
    <link href="/assets/css/cover.css" rel="stylesheet">
    <link href="/assets/css/forms.css" rel="stylesheet">
    <link href="/assets/css/buttons.css" rel="stylesheet">
    <?php echo $__env->yieldPushContent('css-head'); ?>
    
    <script src="/assets/js/jquery.1.11.1.min.js"></script>
    
    <script src="/bootstrap.3.3.6/js/bootstrap.min.js"></script>
    
    <script src="/js/functions.js"></script>
    <script src="/assets/js/custom.js"></script>
    <script src="/js/knockout.js"></script>
    <script src="/js/knockout.mapping.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php echo $__env->yieldPushContent('js-head'); ?>
  </head>

<body id="body" class="animated fadeIn">

  <!-- Begin Navigation -->
  <nav class="navbar navbar-white navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        
        <a class="navbar-brand" href="/">
          <img src="/img/Bubble_Logo_new_blue.png" class="img-responsive logo_bubble">
        </a>
      </div>

      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
          <?php if(Auth::check()): ?>
          <li>
            <a href="<?php echo e(route('signout')); ?>">Выход</a>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
              Странички <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li><a href="profile2.html">Profile 2</a></li>
              <li><a href="profile3.html">Profile 3</a></li>
              <li><a href="profile4.html">Profile 4</a></li>
              <li><a href="sidebar_profile.html">Sidebar profile</a></li>
              <li><a href="user_detail.html">User detail</a></li>
              <li><a href="edit_profile.html">Edit profile</a></li>
              <li><a href="about.html">About</a></li>
              <li><a href="friends.html">Friends</a></li>
              <li><a href="friends2.html">Friends 2</a></li>
              <li><a href="profile_wall.html">Profile wall</a></li>
              <li><a href="photos1.html">Photos 1</a></li>
              <li><a href="photos2.html">Photos 2</a></li>
              <li><a href="view_photo.html">View photo</a></li>
              <li><a href="messages1.html">Messages 1</a></li>
              <li><a href="messages2.html">Messages 2</a></li>
              <li><a href="group.html">Group</a></li>
              <li><a href="list_users.html">List users</a></li>
              <li><a href="file_manager.html">File manager</a></li>
              <li><a href="people_directory.html">People directory</a></li>
              <li><a href="list_posts.html">List posts</a></li>
              <li><a href="grid_posts.html">Grid posts</a></li>
              <li><a href="forms.html">Forms</a></li>
              <li><a href="buttons.html">Buttons</a></li>
              <li><a href="error404.html">Error 404</a></li>
              <li><a href="error500.html">Error 500</a></li>
              <li><a href="recover_password.html">Recover password</a></li>
              <li><a href="registration_mail.html">Registration mail</a></li>
            </ul>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle nav-controller" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-user"></i>
              <span class="caret"></span>
            </a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo e(route('settings')); ?>">Настройки</a></li>
              </ul>
          </li>
          <?php else: ?>
            <?php echo $__env->yieldPushContent('navbar-item-guest'); ?>
          <?php endif; ?>
          <li>
            <a href="<?php echo e(route('about')); ?>">О сайте</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- End Navigation -->

  <!-- Begin page content -->
  <div class="row page-content">

      
      <?php echo $__env->yieldContent('content', ''); ?>

  </div>
  <!-- End Content-->

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <p class="text-muted">
        &laquo;Bubble&raquo; <span class="small">принадлежит Новикову Максиму. Copyright © Максим Новиков.</span>
      </p>
    </div>

    <!-- templates -->
    <div class="container">
      <?php echo $__env->yieldPushContent('templates'); ?>
    </div>

    <!-- templates -->
    <div id="runtime-templates" class="container"></div>

    <script type="text/javascript">
      // authenticated user
      try {
        <?php if(Auth::check()): ?>
          var userAuth = <?php echo Auth::user()->info->toJson(); ?>;
        <?php else: ?>
          var userAuth = null;
        <?php endif; ?>

        // profile user
        <?php if(isset($userCurrent)): ?>
          var userCurrent = <?php echo $userCurrent->info->toJson(); ?>;
        <?php else: ?>
          var userCurrent = null;
        <?php endif; ?>
      }
      catch(e) {
        throw new Error( "exception when loading authenticated/current user object" );
      }

      // get object -> convert to observable
      window.userAuth = userAuth != null ? ko.mapping.fromJS( userAuth ) : null;
      window.userCurrent = userCurrent != null ? ko.mapping.fromJS( userCurrent ) : null;

      // main app
      window.Undone = {
        _inited: false,
        // device
        _mobile: false,

        debug: true,
        userAuth: userAuth,
        userCurrent: userCurrent,

        maps: [],

        // detect user device
        isMobile: function(update) {
          if (update) {
            this._mobile = isMobile();
          }
          return this._mobile;
        },

        // init app
        init: function() {
          if (this._inited) return true;
              this._inited = true;

          this.isMobile(true);
        },
      };

      Undone.init();
    </script>

    <?php echo $__env->yieldPushContent('js-bottom'); ?>
    
  </footer>
  <!-- End Footer -->

</body>
</html>
