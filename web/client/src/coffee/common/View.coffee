define ['jquery', 'underscore'], ($, _) ->
  class view
    template: ''
      
    render: (data = null) ->
      temp = _.template(@template)
      temp(data)
      
  return view