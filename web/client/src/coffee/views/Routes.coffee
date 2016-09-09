define [
  'common/View', 
  'models/Routes', 
  'views/routes/Loading',
  'views/routes/Success',
  'views/routes/Error'
], (view, routes, loading, success, error) ->
  class view extends view
    template: '<article class="block"><i class="close"></i></article>'

    render: (form = require 'models/Form') ->
      $(document).on 'click', '.close', () ->
        $(this).parent().remove()

      block   = $ super()
      wrap    = $('#routes-list')
      wrap.children().remove()
      
      for request in form.requestData()
        do(request) ->         
          route = block.clone()
          route.addClass 'loading'
          route.html loading::render
            message: 'Подождите. Идет загрузка рейсов'
          wrap.append route
          
          routes::loadOne
            data: request
            success: (response) ->
              if 'yaCounter32439430' of window
                yaCounter32439430.hit "/?#{$('#get-routes').serialize()}"

              if response.getAttr('routes') is false
                route.removeClass('loading').addClass('error').html error::render
                  name:    response.getAttr 'name'
                  message: 'Ошибка. Нет рейсов на выбранное время'
              else
                route.removeClass('loading').html success::render response.getAttrs()
            error: (response) ->
              route.removeClass('loading').addClass('error').html error::render
                name:    'Ошибка. Сервер недоступен'
                message: ''
