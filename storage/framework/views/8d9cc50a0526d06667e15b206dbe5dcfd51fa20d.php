<?php $__env->startPush('css-head'); ?>
<!-- range slyder styles -->
<link rel="stylesheet" href="/rangeslider/rangeslider.css">
<!-- bootstrap select styles -->
<link rel="stylesheet" href="/bootstrap-select/dist/css/bootstrap-select.css">
<!-- date picker styles -->
<link rel="stylesheet" href="/dateTimePickerAndroid/pickadate.js-3.5.6/lib/themes/classic.css">
<link rel="stylesheet" href="/dateTimePickerAndroid/pickadate.js-3.5.6/lib/themes/classic.time.css">
<link rel="stylesheet" href="/dateTimePickerAndroid/pickadate.js-3.5.6/lib/themes/classic.date.css">
<!-- page style -->
<link rel="stylesheet" href="/css/search-create.css">
<?php $__env->stopPush(); ?>



<?php $__env->startPush('js-head'); ?>
<!-- resizable textarea plugin -->
<script type="text/javascript" src="/js/plugins/autosize/dist/autosize.min.js"></script>
<!-- range slider plugin -->
<script type="text/javascript" src="/rangeSlider/rangeslider.min.js"></script>
<!-- select input fields plugin -->
<script src="/bootstrap-select/dist/js/bootstrap-select.js"></script>
<!-- date and time picker plugin -->
<script type="text/javascript" src="/dateTimePickerAndroid/pickadate.js-3.5.6/lib/picker.js"></script>
<script type="text/javascript" src="/dateTimePickerAndroid/pickadate.js-3.5.6/lib/picker.time.js"></script>
<script type="text/javascript" src="/dateTimePickerAndroid/pickadate.js-3.5.6/lib/picker.date.js"></script>
<script type="text/javascript" src="/dateTimePickerAndroid/pickadate.js-3.5.6/lib/translations/ru_RU.js"></script>
<!-- page js -->
<script type="text/javascript" src="/pages/search-create-dom.js?222"></script>
<script type="text/javascript" src="/pages/search-create-map.js?111"></script>
<!-- map functions -->
<script type="text/javascript" src="/js/include/map/google-maps-functions.js?111"></script>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('js-bottom'); ?>
<script type="text/javascript">
$(function() {

  googleMapsLoaded(function(google)
  {
    // define styles for features
    var customStyles = {
      fillColor: '#3182bd',
      strokeColor: '#3182bd',
      strokeOpacity: 1,
      strokeWeight: 2,
      fillOpacity: .09,
      clickable: true,
      editable: true,
      zIndex: 1
    };

    // define map options
    var options =
    {
      center: new google.maps.LatLng(53.53824731, 33.69611538),
      zoom: 14,
      mapTypeControl: false,
      streetViewControl: false,
    };

    // create maps
    var mapOne = initOsmMap(google, options, 'map-modal-template');
    var mapTwo = initOsmMap(google, options, 'map-modal-template2');

    // creating draw manager on map
    var mapOneDrawingManager = initOsmMapDrawingManager(google, mapOne);


    // iterate over created features
    mapOne.data.forEach(function(feature) {
      var overlay = null;

      if (overlay = feature.getProperty('override')) {
        log( feature.getProperty('overrideType') );
      }
    });

    // feature added event
    mapOne.data.addListener('addfeature', function(event) {
      var overlay = null;

      if (overlay = event.feature.getProperty('override')) {
        log( event.feature.getProperty('overrideType') + ' was added on map!');
      }
    });


    // register map
    Undone.maps.push(mapOne);

  });

});

$(function() {

  // ko page
  // main View Model object
  // All the sub-models to be injected via that object
  window.page =
  {
    // auth user
    // on updates the whole object can be replaced with new one, so it should stay "observable"
    user: ko.observable( Undone.userAuth ),

    // search form view
    searchForm: {
      // ages sub VM
      age: {
        // different instances for each input
        to: new Age_VM({
            initValue: 22,
            domNode: $('#age-to'),
        }),
        // different instances for each input
        from: new Age_VM({
            initValue: 18,
            domNode: $('#age-from'),
        }),
      },
      // genders sub VM
      gender: new Gender_VM({
          collection: [
            {text: 'Мужской', value: 'М'},
            {text: 'Женский', value: 'Ж'},
          ]
      }),
      // date sub VM
      date: new Date_VM(),
      // time sub VM
      time: {
        // different instances for each input
        to: new Time_VM(),
        // different instances for each input
        from: new Time_VM(),
      },
      // comment
      comment: new Comment_VM(),
      // visibility of a sender
      senderVisibility: new Checkbox_VM(),
    }

  };

  // apply ko binds
  ko.applyBindings( page, document.getElementById('body') );

});
</script>
<?php $__env->stopPush(); ?>



<?php $__env->startSection('content'); ?>
<div class="container">
  <div class="row">

      <!-- left col -->
      <div class="col-md-8 col-sm-8 col-xs-12" id="search-you-container">
          <div class="widget-header">
              
          </div>
          <div class="widget-body">
                  <form role="form">
                      <!-- Select gender -->
                      <div class="form-group">
                        <span class="input-icon">
                            <label for="user-sex">Укажите пол:</label>
                            <span class="input-icon icon-right">
                              <select
                                    data-bind="
                                        options: searchForm.gender.all,
                                        optionsText: 'text',
                                        optionsValue: 'value',
                                        value: searchForm.gender.selected,
                                        optionsCaption: 'Выберите пол...'"
                                    id="user-sex"
                                    class="selectpicker show-tick form-control">
                              </select>
                            </span>
                        </span>
                      </div>

                      <!-- Select date range -->
                      <div class="form-group row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label for="user-time">Дата:</label>
                            <span class="input-icon icon-right">
                                <input
                                    data-bind="click: searchForm.date.onClick.bind(this)"
                                    class="form-control"
                                >
                                <i class="fa fa-calendar colorSky"></i>
                            </span>
                        </div>
                      </div>

                      <!-- Select time range -->
                      <div class="form-group row">
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <label for="user-time">Время от:</label>
                              <span class="input-icon icon-right">
                                <input
                                    data-bind="click: searchForm.time.from.onClick.bind(this)"
                                    class="show-tick form-control"
                                >
                                <i class="fa fa-clock-o colorSky"></i>
                              </span>
                          </div>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <label for="user-time">Время до:</label>
                              <span class="input-icon icon-right">
                                <input
                                    data-bind="click: searchForm.time.to.onClick.bind(this)"
                                    class="show-tick form-control"
                                >
                                <i class="fa fa-clock-o colorSky"></i>
                              </span>                              
                          </div>
                      </div>

                      <!-- Select age -->
                      <div class="form-group row">
                          <div class="col-md-6 col-sm-6 col-xs-12 age-slider-block">
                              <label >Возраст от
                                (<span data-bind="text: searchForm.age.from.selected"></span>):</label>
                              <input
                                  id="age-from"
                                  type="range"
                                  min="16"
                                  max="50"
                                  step="2"
                                  value="18"
                              >
                          </div>
                          <div class="col-md-6 col-sm-6 col-xs-12 age-slider-block">
                              <label >Возраст до
                                (<span data-bind="text: searchForm.age.to.selected"></span>):</label>
                              <input
                                  id="age-to"
                                  type="range"
                                  min="16"
                                  max="50"
                                  step="2"
                                  value="22"
                              >                     
                          </div>
                      </div>

                      <!-- Select date range -->
                      <div class="form-group row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label for="user-time">Комментарий:</label>
                            <textarea
                                  data-bind="textInput: searchForm.comment.selected"
                                  class="user-input-textarea"
                                  spellcheck="false"
                                  placeholder="Одежда, яркие черты..."
                            ></textarea>
                        </div>
                      </div>

                      <br>
                      <!-- send button -->
                      <div class="btn-group dropup" style="width:100%;">
                        <a style="position:absolute; z-index:10; border-right:1px solid #fff;" class="btn btn-azure dropdown-toggle" data-toggle="dropdown" href="#">
                          <i class="fa fa-angle-up"></i>
                        </a>
                        <a class="btn btn-azure" style="width:100%;">Отправить</a>
                        <ul class="dropdown-menu">
                            <li>
                              <div class="checkbox" style="padding:0; margin:0;">
                                <label>
                                    <input data-bind="checked: searchForm.senderVisibility.selected" type="checkbox">
                                    <span class="text">анонимно</span>
                                </label>
                              </div>
                            </li>
                        </ul>
                      </div>
                  </form>
            </div>
      </div>

      <!-- right col -->
      <div class="col-md-4 col-sm-4 col-xs-12">
          <div class="widget-header">
              
          </div>
          <div class="widget-body">
              <!-- maps button -->
              <div class="btn-group dropup" style="width:100%;">
                <a style="position:absolute; z-index:10; border-right:1px solid #fff;" class="btn btn-azure dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
                  <i class="fa fa-angle-up"></i>
                </a>
                <a
                  class="btn btn-azure"
                  style="width:100%;"
                  data-toggle="modal"
                  data-target="#map-modal-template"
                >
                  Карта # 1
                </a>
                  
              </div>

              <!-- maps button -->
              <div class="btn-group dropup" style="width:100%;">
                <a style="position:absolute; z-index:10; border-right:1px solid #fff;" class="btn btn-azure dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
                  <i class="fa fa-angle-up"></i>
                </a>
                <a
                  class="btn btn-azure"
                  style="width:100%;"
                  data-toggle="modal"
                  data-target="#map-modal-template2"
                >
                  Карта # 2
                </a>
                  
              </div>
          </div>
      </div>

  </div>
  <!-- end row -->
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('templates'); ?>

<!-- Modal -->
<div id="map-modal-template" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg" data-expanded="false">

<!-- Modal content-->
<div class="modal-content">
  <iframe name="map-modal-template" width="100%" height="100%" style="position:absolute;z-index:-10"></iframe>
  <div class="modal-header" style="white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 style="display:inline-block; margin-right:20px;" class="expand-map-icon">
      <i class="fa fa-expand" aria-hidden="true" style="cursor:pointer; display:inline-block;"></i>
    </h4>
    <h4 style="display:inline;" class="modal-title">
      Карта (Google + <a href="http://openstreetmap.ru/about/org" target="_blank">Open Street Map</a>)
    </h4>
  </div>

  <div class="modal-body">
    <div class="row">
      <div class="col-md-12">
        <div class="map-canvas"></div>
      </div>
    </div>
  </div>

  <div class="modal-footer"></div>
</div>

</div>
</div>

<!-- Modal -->
<div id="map-modal-template2" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg" data-expanded="false">

<!-- Modal content-->
<div class="modal-content">
  <iframe name="map-modal-template2" width="100%" height="100%" style="position:absolute;z-index:-10"></iframe>
  <div class="modal-header" style="white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 style="display:inline-block; margin-right:20px;" class="expand-map-icon">
      <i class="fa fa-expand" aria-hidden="true" style="cursor:pointer; display:inline-block;"></i>
    </h4>
    <h4 style="display:inline;" class="modal-title">
      Карта (Google + <a href="http://openstreetmap.ru/about/org" target="_blank">Open Street Map</a>)
    </h4>
  </div>

  <div class="modal-body">
    <div class="row">
      <div class="col-md-12">
        <div class="map-canvas"></div>
      </div>
    </div>
  </div>

  <div class="modal-footer"></div>
</div>

</div>
</div>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('js-bottom'); ?>
<script type="text/javascript">
$(function() {
  // 
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layout/main_bubble', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>