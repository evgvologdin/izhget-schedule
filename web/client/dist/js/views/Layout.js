var extend = function(child, parent) { for (var key in parent) { if (hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
  hasProp = {}.hasOwnProperty;

define(['common/View', 'components/AjaxQueue', 'models/Stations', 'views/form/Stations', 'views/form/DateTime'], function(view, AjaxQueue, stationsModel, stationsView, dateTime) {
  return view = (function(superClass) {
    extend(view, superClass);

    function view() {
      return view.__super__.constructor.apply(this, arguments);
    }

    view.prototype.render = function() {
      var button, content, interval_mode, routes;
      stationsModel.prototype.loadAll({
        success: function(models) {
          return $('#from-station, #to-station').each(function() {
            return (new stationsView).render($(this), models);
          });
        }
      }, true);
      (new dateTime).render();
      routes = document.getElementById('routes-list');
      button = document.getElementById('submit-button');
      content = document.getElementById('content');
      interval_mode = setInterval(function() {
        if ($(routes).find('>.loading').length > 0) {
          $(button).addClass('loading');
        } else {
          $(button).removeClass('loading');
        }
        if ($(routes).find('>.block').length > 0) {
          $(content).addClass('view-max-mode');
          return $(content).removeClass('view-min-mode');
        } else {
          $(content).addClass('view-min-mode');
          return $(content).removeClass('view-max-mode');
        }
      }, 50);
      AjaxQueue.prototype.run(function() {
        return $('body').removeClass('wait');
      });
      return this;
    };

    return view;

  })(view);
});

//# sourceMappingURL=../sourcemaps/views/Layout.js.map
