define ['jquery', 'underscore'], ($, _) ->
  class AjaxQueue
    instance = null
    
    instance: () ->
      instance ?= new AjaxQueuePrivate
      
    add: (params = {}) ->
      @instance().add params

    run: (callback = () ->) ->
      @instance().run callback

    class AjaxQueuePrivate
      requests = []
      
      add: (params = {}) ->
        requests.push params
        return @
        
      run: (callback = () ->) ->
        queue    = _.clone requests
        requests = []
      
        while request = queue.pop()
          request.complete = (response, status) ->
            callback() if queue.length is 0
            
          $.ajax request

          
        
      