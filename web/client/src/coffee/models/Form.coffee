define ['jquery'], () ->
  class form
    form: null
    attr:
      from:      null
      to:        null
      date:      null
      transfers: []
    rules:
      empty:   (e) ->
        if e.val()? and e.val() isnt ''
          e.parents('.form-group').removeClass 'has-error' 
          return true
        else
          e.parents('.form-group').addClass 'has-error'
          return false
          
      compare: (e, c) ->
        if e.val() isnt c.val()
          c.parents('.form-group').removeClass 'has-error'
          return true
        else
          c.parents('.form-group').addClass 'has-error'
          return false
      
    constructor: (e = $('form')) ->
      self = this
      self.attr.from = if e.find('#from-station').length > 0 then e.find('#from-station') else null
      self.attr.to   = if e.find('#to-station').length   > 0 then e.find('#to-station')   else null
      self.attr.date = if e.find('#has-date').is(':checked') then e.find('#select-time')  else e.find('#current-time')
      self.attr.transfers = []
      
      e.find('#transfer-stations').find('input:checked').each () ->
        self.attr.transfers.push $(this)
      
      return @
    
    requestData: () ->
      self = this
      if self.validate is false
        return false

      data = []

      data.push
        from: self.attr.from.val()
        to:   self.attr.to.val()
        date: self.attr.date.val()
        
      for transfer in self.attr.transfers
        data.push
          from:     self.attr.from.val()
          to:       self.attr.to.val()
          date:     self.attr.date.val()
          transfer: transfer.val()
          
      return data
    
    validate: () ->    
      if @attr.from isnt null and @attr.to isnt null and @attr.date isnt null and $.isArray(@attr.transfers) is true
        if @rules.empty(@attr.from) is false
          return false
          
        if @rules.empty(@attr.to) is false
          return false
        
        if @rules.empty(@attr.date) is false
          return false
        
        if @rules.compare(@attr.from, @attr.to) is false
          return false
          
        for transfer in @attr.transfers
          if @rules.compare(@attr.from, transfer) is false
            return false
            
          if @rules.compare(@attr.to, transfer) is false
            return false
          
        return true
      else
        alert 'Invalid Form Data'