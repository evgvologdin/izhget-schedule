define(['jquery', 'underscore'], function($, _) {
  var view;
  view = (function() {
    function view() {}

    view.prototype.template = '';

    view.prototype.render = function(data) {
      var temp;
      if (data == null) {
        data = null;
      }
      temp = _.template(this.template);
      return temp(data);
    };

    return view;

  })();
  return view;
});

//# sourceMappingURL=../sourcemaps/common/View.js.map
