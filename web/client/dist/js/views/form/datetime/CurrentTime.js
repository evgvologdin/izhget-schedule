define(['moment', 'inputmask'], function(moment) {
  var view;
  return view = (function() {
    function view() {}

    view.prototype.render = function(format) {
      var current, interval, maskinput;
      maskinput = format.replace(/\w/g, 9);
      current = document.getElementById('current-time');
      return interval = setInterval(function() {
        var error, error1;
        try {
          return current.value = moment().format(format);
        } catch (error1) {
          error = error1;
          clearInterval(interval);
          return console.log(error);
        }
      }, 500);
    };

    return view;

  })();
});

//# sourceMappingURL=../../../sourcemaps/views/form/datetime/CurrentTime.js.map
