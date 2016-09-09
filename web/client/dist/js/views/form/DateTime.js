define(['moment', 'views/form/datetime/CurrentTime', 'views/form/datetime/SelectTime'], function(moment, currentTime, selectTime) {
  var view;
  return view = (function() {
    function view() {}

    view.prototype.format = 'DD.MM.YYYY HH:mm';

    view.prototype.render = function() {
      var format, has_date, select_time;
      format = this.format;
      has_date = document.getElementById('has-date');
      select_time = document.getElementById('select-time-block');
      has_date.onchange = function() {
        if (has_date.checked) {
          $(select_time).find('input').val(moment().format(format));
          return $(select_time).removeClass('hide');
        } else {
          return $(select_time).addClass('hide');
        }
      };
      has_date.onchange();
      (new currentTime).render(format);
      return (new selectTime).render(format);
    };

    return view;

  })();
});

//# sourceMappingURL=../../sourcemaps/views/form/DateTime.js.map
