'use strict';
requirejs.config({
  baseUrl: "client",
  paths: {
    jquery: 'src/vendor/bower/jquery/dist/jquery',
    inputmask: 'src/vendor/bower/jquery.inputmask/dist/jquery.inputmask.bundle',
    underscore: 'src/vendor/bower/underscore/underscore',
    moment: 'src/vendor/bower/momentjs/moment',
    moment_ru: 'src/vendor/bower/momentjs/locale/ru',
    common: 'dist/js/common',
    components: 'dist/js/components',
    models: 'dist/js/models',
    views: 'dist/js/views'
  },
  shim: {
    inputmask: {
      dest: ['jquery']
    }
  }
});

require(['jquery', 'views/Layout', 'views/Routes', 'models/Form'], function($, layout, routes, model) {
  $(document).ready(function() {
    return layout.prototype.render();
  });
  return $('#get-routes').on('submit', function(e) {
    var form;
    e.preventDefault();
    form = new model($(this));
    if (form.validate() === true) {
      return routes.prototype.render(form);
    }
  });
});

//# sourceMappingURL=sourcemaps/app.js.map
