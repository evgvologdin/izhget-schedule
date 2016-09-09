define ['views/form/Modal'], (view) ->  
  class view extends view
    template: '
      <div class="select">
        <input class="select" readonly="readonly" type="text" />
        <div class="<%=wrapper%> hide">
          <div class="modal">
            <div class="header">
              <input class="search" type="text" />
            </div>
            <div class="section">
              <ul class="items"></ul>
              <ul class="not-fount hide">
                <li class="text-center">
                  <em>Нет результатов</em>
                </li>
              </ul>
            </div>
            <div class="footer">
              <button class="cancel danger" type="button">Отмена</button>
            </div>
          </div>
        </div>
      </div>
    '
      
    render: (select, stations, autoselect = true) ->
      self      = this
      wrap      = super()
      select    = select
      block     = wrap.find ".#{@wrapper}"
      modal     = wrap.find '.modal'
      items     = wrap.find '.items'
      input     = wrap.find '.select'
      search    = wrap.find '.search'
      cancel    = wrap.find '.cancel'
      message   = wrap.find '.not-fount'
      
      nextinput = () ->
        select.parents('.form-group').nextAll().find('input:visible').first().focus()
      previnput = () ->
        select.parents('.form-group').prevAll().find('input:visible').last().focus()

      # Render select option
      for station in stations
        option   = $ '<option />'
        option.html station.getAttr 'name'
        option.val  station.getAttr 'id'
        option.attr 'selected': if parseInt(select.attr('data-value')) is station.getAttr('id') then true else false
        select.append option

        listitem = $ '<li />'
        listitem.html station.getAttr 'name'
        listitem.attr 'data-value': station.getAttr 'id'
        listitem.on   'click', (e) ->
          select.val $(this).attr 'data-value'
          select.trigger 'change'
          self.hide()
        items.append listitem

      # Change select    
      select.on 'change', () ->
        value = @value
        $(this).find('option').each () ->
          if @value is value
            input.val @innerHTML
      select.trigger 'change'

      # Append to page    
      select.addClass 'hide'
      select.after wrap
      select.prependTo wrap
      select.trigger 'change'
    
      # Show modal
      input.on 'focus click', () ->
        self.show () ->
          search.val ''
          setTimeout () -> search.focus()
    
      # Hide modal
      cancel.on 'keypress click', (e) -> 
        console.log nextinput
        nextinput() if e.type is 'keypress' and e.keyCode is 13
        self.hide()
         
      # Search items
      search.on 'focus input keydown', (e) ->
        # keybord nav down
        if e.type is 'keydown' and e.keyCode is 40
          if items.find('.focus').length is 0 
            items.children().not('.hide').first().addClass 'focus'
          else
            items.find('.focus').removeClass('focus').nextAll().not('.hide').first().addClass 'focus'
            
        # keybord nav up
        if e.type is 'keydown' and e.keyCode is 38
          if items.find('.focus').length is 0 
            items.children().not('.hide').last().addClass 'focus'
          else
            items.find('.focus').removeClass('focus').prevAll().not('.hide').first().addClass 'focus'

        # keybord nav scroll
        if e.type is 'keydown' and (e.keyCode is 40 or e.keyCode is 38)
          focus  = items.find '.focus'
          if focus.length isnt 0
            parent = items.parent()
            parent.scrollTop focus.outerHeight() * focus.index()

        # keybord nav select
        if e.type is 'keydown' and e.keyCode is 13
          e.preventDefault()
          focus = items.find '.focus'
          if focus.length isnt 0
            focus.trigger('click')
            nextinput()
            
        # keybord nav shift+tab
        if e.type is 'keydown' and e.shiftKey and e.keyCode is 9
          previnput()
      
        # search
        value = @value.toLowerCase()
        items.find('li').each () ->
          if @innerHTML.toLowerCase().search(value) is -1
            $(this).addClass 'hide'
            $(this).removeClass 'focus'
            $(this).removeClass 'show'
          else
            $(this).addClass 'show'
            $(this).removeClass 'hide'

        if items.find('.show').length is 0
          items.addClass 'hide'
          message.removeClass 'hide'
        else
          items.removeClass 'hide'
          message.addClass 'hide'
          
      # AutoSelect station by user coordinates 
      if 'geolocation' of navigator and autoselect isnt false and select.val().length is 0
        navigator.geolocation.getCurrentPosition (position) ->
          
          distance = false
          minindex = false

          for station in stations
            coords = station.getAttr 'coords'
            if $.isArray(coords) is false or coords.length is 0
              continue

            for coord in coords
              if distance is false and minindex is false
                minindex  = station.getAttr 'id'
                distance  = Math.pow coord[0] - position.coords.latitude,  2
                distance += Math.pow coord[1] - position.coords.longitude, 2
              else
                check  = Math.pow coord[0] - position.coords.latitude,  2
                check += Math.pow coord[1] - position.coords.longitude, 2

                if check <= distance
                  minindex  = station.getAttr 'id'
                  distance = check

          if minindex isnt false
            select.val(minindex).trigger 'change'
      
      return @