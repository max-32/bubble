$(function() {




  //
  // page DOM
  //

  //
  // all
  //

  // resizable textarea
  autosize(document.querySelectorAll('textarea'));

  // lock hiding dropdown menu after clicking it
  $('#search-you-container ul.dropdown-menu').on('click', function(event) {
    event.stopPropagation();
  });

  // expand map
  $('.modal .expand-map-icon i').click(function() {
    if ('false' == $(this).closest('.modal-lg').attr('data-expanded')) {
      $(this).closest('.modal-lg').css({width: "94%"});
      $(this).closest('.modal-lg').attr('data-expanded', 'true');
    } else {
      $(this).closest('.modal-lg').css({width: ""});
      $(this).closest('.modal-lg').attr('data-expanded', 'false');
    }
  });

  //
  // map box
  //

});


//
// View Models
//

// comment View Model
function Comment_VM() {
  var self = this;

  self.selected = ko.observable('');
}

// checkbox View Model
function Checkbox_VM() {
  var self = this;

  self.selected = ko.observable(true);
}

// gender View Model
function Gender_VM(params) {
  var self = this;

  // available genders: Display text: value
  self.all = ko.observableArray(params.collection),

  // selected item
  self.selected = ko.observable(undefined),

  // add item
  self.add = function(elementObject) {
    self.all.push(elementObject);
    $('.selectpicker').selectpicker('refresh'); // update bootstrap select
  },

  // remove item
  self.remove = function(elementObject) {
    self.all.remove(elementObject);
    $('.selectpicker').selectpicker('refresh'); // update bootstrap select
  }
}

// ages View Model
function Age_VM(params) {
  var self = this;
  var initValue = params.initValue;
  var sliderSelector = params.domNode;
  
  // main vars
  self.selected = ko.observable(0);

  // allow two-way updates
  // ex. ...ages.to(20) will update DOM (slider)
  self.selected.subscribe(function(value) {
    sliderSelector.val(value).change();
  });

  // init rabgeslider plugin
  sliderSelector.rangeslider({
    polyfill : false,
    onInit: function() {
      self.selected(initValue);
    },
    onSlide: function(position, value) {
      self.selected(value);
    },
  });
}

// date View Model
function Date_VM() {
  var self = this;
  var inited = false;
  var pickerObject = null;
  var doNotNotify = false;

  // selected date
  self.selected = ko.observable(null),

  self.selected.subscribe(function(value) {
    doNotNotify = true; // prevent circle events
    self.pickerObject.set('select', value.value, {format: value.format});
    doNotNotify = false; // prevent circle events
  });

  // init picker only on click
  self.onClick = function(root, event) {
    if ( ! inited) {
      init(event.target);
      inited = true;
    }
  }

  // init function
  function init(element) {
    // init date picker
    var inputDate = $(element).pickadate({
      // call opening manually
      editable: true,
      // blur on close
      onClose: function() {
        $(element).blur();
      },
      onSet: function() {
        if (doNotNotify) return;

        var format = 'yyyy-mm-dd';
        var value = this.get('select', format);

        if (empty(value)) {
          self.selected(''); return;
        }

        self.selected({
          value: value,
          format: format
        });
      }
    });

    // get object
    self.pickerObject = inputDate.pickadate('picker');

    // open picker manually
    inputDate.on('click', function(event) {
      if (self.pickerObject.get('open')) {
          self.pickerObject.close();
      } else {
          self.pickerObject.open();
      }
      event.stopPropagation();
    });
  }
}

// time View Model
function Time_VM() {
  var self = this;
  var inited = false;
  var pickerObject = null;
  var doNotNotify = false;

  self.selected = ko.observable(null),

  self.selected.subscribe(function(value) {
    doNotNotify = true; // prevent circle events
    self.pickerObject.set('select', value.value, {format: value.format});
    doNotNotify = false; // prevent circle events
  });

  // init picker only on click
  self.onClick = function(root, event) {
    if ( ! inited) {
      init(event.target);
      inited = true;
    }
  }

  function init(element) {
    // init time picker
    var inputTime = $(element).pickatime({
      // call opening manually
      editable: true,
      // blur on close
      onClose: function() {
        $(element).blur();
      },
      onSet: function() {
        if (doNotNotify) return;
        
        var format = 'HH:i';
        var value = this.get('select', format);

        if (empty(value)) {
          self.selected(''); return;
        }

        self.selected({
          value: value,
          format: format,
        });
      },
      format: 'HH:i',
    });

    // get object
    self.pickerObject = inputTime.pickatime('picker');

    // open picker manually
    inputTime.on('click', function(event) {
      if (self.pickerObject.get('open')) {
          self.pickerObject.close();
      } else {
          self.pickerObject.open();
      }
      event.stopPropagation();
    });
  }
}