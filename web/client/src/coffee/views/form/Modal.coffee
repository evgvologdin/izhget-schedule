define ['common/View', 'jquery'], (view) ->
  class view extends view
    wrapper:  'modal-wrap'
    template: '<div class="<%=wrapper%> hide"></div>'
    modal:    ''
    
    render: () ->
      self    = this
      @modal  = $ super wrapper: @wrapper
      wrapper = if @modal.find(".#{@wrapper}").length is 0 then @modal else @modal.find(".#{@wrapper}")
      
      $(document).on 'mouseup keyup', (e) ->
        if e.type is 'keyup' and e.keyCode is 9 and $(e.target).closest(wrapper).length is 0
          self.hide()
      
        if e.type is 'mouseup' and $(e.target).closest(wrapper).length is 0
          self.hide()
      
      return @modal 
      
    show: (c = () ->) ->
      self = this
      setTimeout () ->
        self.modal.find(".#{self.wrapper}").removeClass 'hide'
      c()
      
    hide: (c = () ->) ->
      @modal.find(".#{@wrapper}").addClass 'hide'
      c()