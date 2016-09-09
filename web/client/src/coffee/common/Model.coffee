define ['jquery', 'underscore', 'components/AjaxQueue'], ($, _, queue) ->
  class model
    attributes: []
    loadUrl:    null
    
    setAttr: (index, value) ->
      @attributes[index] = value
      
    getAttr: (index) ->
      return if @attributes[index]? then @attributes[index]
      
    setAttrs: (value) ->
      @attributes = value
        
    getAttrs: () ->
      return @attributes
    
    loadOne: (params = {}, later = false) ->
      self    = this
      request = 
        url:      if 'url'  of params     then params.url      else @loadUrl
        data:     if 'data' of params     then params.data
        type:     if 'type' of params     then params.type     else 'post'
        dataType: 'json'
        context:  this
        success:  (response, status) ->
          load = _.clone self
          load.setAttrs if $.isArray(response) is true then response[0] else response
          params.success(load) if 'success' of params
        error:    (response, status) ->
          params.error(response, status) if 'error' of params
        
      if later is true
        queue::add request
      else
        $.ajax request
        
    loadAll: (params = {}, later = false) ->
      self    = this
      request = 
        url:      if 'url'  of params     then params.url      else @loadUrl
        data:     if 'data' of params     then params.data
        type:     if 'type' of params     then params.type     else 'post'
        dataType: 'json'
        success:  (response, status) ->
          load = []
          if $.isArray(response)
            for attrs in response
              data = _.clone self
              data.setAttrs attrs
              load.push data
          params.success(load) if 'success' of params
        error:    (response, status) ->
          params.error(response, status) if 'error' of params

      if later is true
        queue::add request
      else
        $.ajax request