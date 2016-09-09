'use strict';

requirejs.config
  baseUrl: "client",
  paths:
    jquery:      'src/vendor/bower/jquery/dist/jquery',
    inputmask:   'src/vendor/bower/jquery.inputmask/dist/jquery.inputmask.bundle',
    underscore:  'src/vendor/bower/underscore/underscore',
    moment:      'src/vendor/bower/momentjs/moment',
    moment_ru:   'src/vendor/bower/momentjs/locale/ru',
    common:      'dist/js/common',
    components:  'dist/js/components',
    models:      'dist/js/models',
    views:       'dist/js/views'
    
  shim:
    inputmask:
      dest: ['jquery']
      

require ['jquery', 'views/Layout', 'views/Routes', 'models/Form'], ($, layout, routes, model) ->
  $(document).ready () ->
    layout::render()
    
  $('#get-routes').on 'submit', (e) ->
    e.preventDefault()
    form = new model $(this)

    if form.validate() is true
      routes::render form