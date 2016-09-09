define [
  'moment', 
  'views/form/datetime/CurrentTime', 
  'views/form/datetime/SelectTime'
], (moment, currentTime, selectTime) ->
  class view
    format: 'DD.MM.YYYY HH:mm' 
    
    render: () ->
      format      = @format
      has_date    = document.getElementById 'has-date'
      select_time = document.getElementById 'select-time-block'
      
      has_date.onchange = () ->
        if has_date.checked
          $(select_time).find('input').val moment().format(format)
          $(select_time).removeClass 'hide'
        else
          $(select_time).addClass 'hide'
      has_date.onchange()
      
      # render CurrentTime
      (new currentTime).render(format)
      
      # render SelectTime
      (new selectTime).render(format)
      