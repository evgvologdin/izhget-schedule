var extend = function(child, parent) { for (var key in parent) { if (hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
  hasProp = {}.hasOwnProperty;

define(['common/View', 'models/Routes', 'views/routes/Loading', 'views/routes/Success', 'views/routes/Error'], function(view, routes, loading, success, error) {
  return view = (function(superClass) {
    extend(view, superClass);

    function view() {
      return view.__super__.constructor.apply(this, arguments);
    }

    view.prototype.template = '<article class="block"><i class="close"></i></article>';

    view.prototype.render = function(form) {
      var block, i, len, ref, request, results, wrap;
      if (form == null) {
        form = require('models/Form');
      }
      $(document).on('click', '.close', function() {
        return $(this).parent().remove();
      });
      block = $(view.__super__.render.call(this));
      wrap = $('#routes-list');
      wrap.children().remove();
      ref = form.requestData();
      results = [];
      for (i = 0, len = ref.length; i < len; i++) {
        request = ref[i];
        results.push((function(request) {
          var route;
          route = block.clone();
          route.addClass('loading');
          route.html(loading.prototype.render({
            message: 'Подождите. Идет загрузка рейсов'
          }));
          wrap.append(route);
          return routes.prototype.loadOne({
            data: request,
            success: function(response) {
              if ('yaCounter32439430' in window) {
                yaCounter32439430.hit("/?" + ($('#get-routes').serialize()));
              }
              if (response.getAttr('routes') === false) {
                return route.removeClass('loading').addClass('error').html(error.prototype.render({
                  name: response.getAttr('name'),
                  message: 'Ошибка. Нет рейсов на выбранное время'
                }));
              } else {
                return route.removeClass('loading').html(success.prototype.render(response.getAttrs()));
              }
            },
            error: function(response) {
              return route.removeClass('loading').addClass('error').html(error.prototype.render({
                name: 'Ошибка. Сервер недоступен',
                message: ''
              }));
            }
          });
        })(request));
      }
      return results;
    };

    return view;

  })(view);
});

//# sourceMappingURL=../sourcemaps/views/Routes.js.map
