define [
  'common/View', 
  'components/AjaxQueue',
  'models/Stations',
  'views/form/Stations', 
  'views/form/DateTime'
], (view, AjaxQueue, stationsModel, stationsView, dateTime) ->
  class view extends view  
    render: () ->
    
      # Render selects
      stationsModel::loadAll success: (models) ->
        $('#from-station, #to-station').each () ->
          (new stationsView).render $(this), models
      , true

      # Datetime select render
      (new dateTime).render()
      
      # View mode
      routes        = document.getElementById 'routes-list'
      button        = document.getElementById 'submit-button'
      content       = document.getElementById 'content'
      interval_mode = setInterval () ->
        if $(routes).find('>.loading').length > 0
          $(button).addClass 'loading'
        else
          $(button).removeClass 'loading'

        if $(routes).find('>.block').length > 0
          $(content).addClass  'view-max-mode'
          $(content).removeClass 'view-min-mode'
        else
          $(content).addClass  'view-min-mode'
          $(content).removeClass 'view-max-mode'
      , 50
      
      AjaxQueue::run () ->
        $('body').removeClass 'wait'
      
      return @