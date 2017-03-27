<?php $__env->startPush('css-head'); ?>
<link href="assets/css/edit_profile.css" rel="stylesheet">
<style type="text/css">
  .input-width-100 {
    width: 100% !important;
  }
</style>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('js-bottom'); ?>
<script type="text/javascript" src="/js/plugins/autosize/dist/autosize.min.js"></script>
<script type="text/javascript">
  autosize(document.querySelectorAll('textarea'));
</script>
<script type="text/javascript">
// ko
$(function() {

  // !!!
  // developing
  // !!!

  // settings right box VM
  function settingsRight_VM()
  {
    // save changes
    this.save = function() {
      var self = this;
      var settingsUser = self.settings.user;

      //
      // 1. prepare User object
      //

      // get user sex value ['М', 'Ж', '']
      settingsUser().sex( $("select[name='user-sex'] option:selected").val() );

      deb('saving user profile');
      deb('changed client object:');
      deb(ko.mapping.toJS(settingsUser()));

      //
      // 2. send updated data to server
      //

      $.ajax
      ({
        url: 'profile/edit',
        data: ko.mapping.toJS(settingsUser()),
        method: 'put'
      })
      .done(function (response) {
        deb('server response (ok):');
        deb(response);

        updatePage(response);
      })
      .fail(function (response) {
        deb('server response (fail):');
        deb(response);
      });

      //
      // 3. update page
      //

      function updatePage(response) {
        if ( ! empty(response.changed)) {
          // apply changes to the page
          settingsUser( ko.mapping.fromJS(response.user) );
          self.user( settingsUser() );

          self.settings.showMessage(true);
          window.scrollTo(0,0);
        } else {
          // nothing was updated or changed
        }
      }
    };

  }

  // settings left box VM
  function settingsLeft_VM()
  {
    // to do
  }


  // ko page
  // main View Model object
  // All the sub-models to be injected via that object
  window.page =
  {
    // auth user
    // on updates the whole object can be replaced with new one, so it should stay "observable"
    user: ko.observable( userAuth ),

    // setting box
    settings: {
      // user copy object
      // the changes (during editing profile) are applied to this object
      user: ko.observable( ko.mapping.fromJS(ko.mapping.toJS(userAuth)) ),
      // right box
      right: new settingsRight_VM,
      // left box
      left: new settingsLeft_VM,
      // show 'profile updated' message
      showMessage: ko.observable(false),
    },

  };

  // apply ko binds
  ko.applyBindings( page, document.getElementById('body') );

});
</script>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>

<!-- Begin page content -->
<div class="container page-content edit-profile">
<div class="row">

    <div class="col-md-10 col-md-offset-1">

      <!-- NAV TABS -->
      <ul class="nav nav-tabs nav-tabs-custom-colored tabs-iconized">
        <li class="active">
          <a href="#profile-tab" data-toggle="tab" aria-expanded="true">
            <i class="fa fa-user"></i>&nbsp;
            Профиль</a>
        </li>
        <li class="">
          <a href="#settings-tab" data-toggle="tab" aria-expanded="false">
            <i class="fa fa-gear"></i>&nbsp;
            Настройки</a>
        </li>
      </ul>
      <!-- END NAV TABS -->

      <div class="tab-content profile-page">

        <div class="page-updated-message-box" data-bind="visible: settings.showMessage()">
          <span>Профиль обновлен успешно.</span>
        </div>

        <!-- PROFILE TAB CONTENT -->
        <div class="tab-pane profile active" id="profile-tab">
          <div class="row">
            <div class="col-md-3">
              <div class="user-info-left">
                <img src="<?php echo e($user->info->photo); ?>" alt="Аватар" style="width:110px; height:110px;">
                <h2 data-bind="text: user().fname() + ' ' + user().lname()"></h2>
                
                <form action="">
                <div class="contact">
                  <p>
                    <span class="file-input btn btn-azure btn-file">
                      Сменить аватар <input class="upload-avatar-input" type="file" name="files">
                    </span>
                  </p>
                  <p>
                    <!-- The file input field used as target for the file upload widget -->
                    <span class="file-input btn btn-azure btn-file">
                      Сменить обои <input class="upload-avatar-input" type="file" name="files" disabled="disabled">
                    </span>

                    <!-- The global progress bar -->
                    <div class="progress">
                      <div class="progress-bar progress-bar-success"></div>
                    </div>
                  </p>
                </div>
                </form>
              </div>
            </div>
            <div class="col-md-9">
              <div class="user-info-right">
                
                <div id="basic-info" class="basic-info">
                  <h3 style="margin-top:12px;"><i class="fa fa-hashtag"></i> Основная информация</h3>
                  <p class="data-row">
                    <span class="data-name">Имя</span>
                    <span class="data-value">
                      <input data-bind="value: settings.user().fname" class="user-input input-width-100">
                    </span>
                  </p>
                  <p class="data-row">
                    <span class="data-name">Фамилия</span>
                    <span class="data-value">
                      <input data-bind="value: settings.user().lname" class="user-input input-width-100">
                    </span>
                  </p>
                  <p class="data-row">
                    <span class="data-name">Отчество</span>
                    <span class="data-value">
                      <input data-bind="value: settings.user().mname" class="user-input input-width-100">
                    </span>
                  </p>
                  <p class="data-row">
                    <span class="data-name">Дата рождения</span>
                    <span class="data-value">
                      <input id="user-dob" data-bind="value: settings.user().dob" class="user-input input-width-100">
                    </span>
                  </p>
                  <p class="data-row">
                    <span class="data-name">Пол</span>
                    <span class="data-value">
                      <select name="user-sex" data-bind="selectedOptions: settings.user().sex()">
                         <option value="" selected="selected">(Не указано)</option>
                         <option value="М">М</option>
                         <option value="Ж">Ж</option>
                      </select>
                    </span>
                  </p>
                </div>

                <div id="contact-info" class="contact_info">
                  <h3><i class="fa fa-hashtag"></i> Контактная информация</h3>
                  <p class="data-row">
                    <span class="data-name">Номер телефона</span>
                    <span class="data-value">
                      <input data-bind="value: settings.user().phone" class="user-input input-width-100">
                    </span>
                  </p>
                </div>

                <div class="about">
                  <h3><i class="fa fa-hashtag"></i> Обо мне</h3>
                  <span class="data-value" style="padding-bottom:20px;">
                    <textarea data-bind="value: settings.user().about" class="user-input user-input-text"></textarea>
                  </span>
                </div>

                <br>
                <button type="submit" class="btn btn-default btn-save" data-bind="click: settings.right.save.bind($root)">
                  Сохранить
                </button>

                
              </div>
            </div>
          </div>
        </div>
        <!-- END PROFILE TAB CONTENT -->
    
        <!-- SETTINGS TAB CONTENT -->
        <div class="tab-pane settings" id="settings-tab">
          <form class="form-horizontal" role="form">
            <fieldset>
              <h3 style="margin-top:12px;"><i class="fa fa-hashtag"></i> Приватность</h3>
              <div class="checkbox">
                <label>
                    <input type="checkbox" class="colored-blue" checked="checked">
                    <span class="text">*Click*</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                    <input type="checkbox" class="colored-blue" checked="checked">
                    <span class="text">Отображать дату рождения</span>
                </label>
              </div>
              <div class="checkbox">
                <label>
                    <input type="checkbox" class="colored-blue" checked="checked">
                    <span class="text">Показывать статус "online"</span>
                </label>
              </div>
            </fieldset>
          </form>

          <br>
          <button type="submit" class="btn btn-default btn-save" data-bind="click: settings.right.save.bind($root)">
            Сохранить
          </button>
        </div>
        <!-- END SETTINGS TAB CONTENT -->
      </div>
    </div>    
  </div>
</div>
<?php $__env->stopSection(); ?>




<?php $__env->startPush('js-bottom'); ?>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="js/jquery/fileupload/js/vendor/jquery.ui.widget.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="js/jquery/fileupload/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="js/jquery/fileupload/js/jquery.fileupload.js"></script>

<script type="text/javascript">
$(function() {

  // set up uploads
  $('.upload-avatar-input').fileupload
  ({
      type: 'POST',
      url: 'upload/img',
      dataType: 'json',
      singleFileUploads: true,
      limitMultiFileUploads: 1,
      limitConcurrentUploads: 1,
      done: function (e, data) {
        // to do
        log(data);
      },
      progressall: function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
          $('.progress-bar').css('width', progress + '%');
      }
  })
    .prop('disabled', !$.support.fileInput)
    .parent().addClass($.support.fileInput ? undefined : 'disabled');

});
</script>






<script type="text/javascript">
$(function() {
  // 
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layout/main_bubble', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>