define ['moment', 'inputmask'], (moment) ->
  class view
    render: (format) ->
      maskinput = format.replace /\w/g, 9
      current   = document.getElementById 'current-time'
      interval  = setInterval () ->
        try
          current.value = moment().format(format)
        catch error
          clearInterval interval
          console.log error
      , 500