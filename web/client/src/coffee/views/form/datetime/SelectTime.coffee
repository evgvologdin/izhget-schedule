define ['views/form/Modal', 'moment', 'moment_ru', 'inputmask'], (view, moment) ->
  class view extends view
    template: '
      <div class="datetime">
        <div class="modal-wrap hide">
          <div class="modal">
            <div class="header">
              <input class="change" type="datetime">
            </div>
            <div class="section">
              <ul class="items">
                <li class="item" data-format="DD" data-type="day" data-step="1"></li>
                <li class="item" data-format="MMM" data-type="month" data-step="1"></li>
                <li class="item" data-format="YYYY" data-type="year" data-step="1"></li>
                <li class="item" data-format="HH" data-type="hour" data-step="1"></li>
                <li class="delimiter">:</li>
                <li class="item" data-format="mm" data-type="minutes" data-step="10"></li>
              </ul>
            </div>
            <div class="footer">
              <button class="choose" type="button">Ок</button>
              <button class="cancel danger" type="button">Отмена</button>
            </div>
          </div>
        </div>
      </div>
    '
    
    render: (format) ->
      self      = this
      wrap      = super()
      select    = $('#select-time')
      block     = wrap.find ".#{@wrapper}"
      modal     = wrap.find '.modal'
      items     = wrap.find '.item'
      change    = wrap.find '.change'
      cancel    = wrap.find '.cancel'
      choose    = wrap.find '.choose'
      message   = wrap.find '.not-fount'
      format    = format
      maskinput = format.replace /\w/g, 9
      datetime  = moment()
      
      nextinput = () ->
        select.parents('#select-time-block').nextAll().find('input:visible').first().focus()
      previnput = () ->
        select.parents('#select-time-block').prevAll().find('input:visible').last().focus()

      # Show modal
      select.on 'focus click', () ->
        if moment(select.val(), format).format() isnt 'Invalid date'
          datetime = moment(select.val(), format)

        change.val datetime.format format
        change.trigger 'setdata'
        
        self.show () -> setTimeout () -> change.focus()
            
      # Hide modal
      cancel.on 'click keypress', (e) -> 
        nextinput() if e.type is 'keypress' and e.keyCode is 13
        self.hide()
            
      # Chose time and hide modal
      choose.on 'click keypress', (e) ->
        if moment(change.val(), format).format() isnt 'Invalid date'
          datetime = moment(change.val(), format)
        select.val datetime.format format
        
        nextinput() if e.type is 'keypress' and e.keyCode is 13
        self.hide()
          
      change.on 'input keydown setdata', (e) -> 
        if moment(@value, format).format() isnt 'Invalid date'
          datetime = moment(@value, format)
          
        # keybord nav select
        if e.type is 'keydown' and e.keyCode is 13
          e.preventDefault()
          nextinput()
          choose.trigger 'click'
          
        # keybord nav shift+tab
        if e.type is 'keydown' and e.shiftKey and e.keyCode is 9
          previnput()
          
        items.each () ->
          item = $(this)
          
          if item.find('.value').length > 0
            item.find('.value').text datetime.format item.attr 'data-format'
            
          else
            plus  = $('<button class="plus"  type="button">+</button>')
            minus = $('<button class="minus" type="button">&minus;</button>')
            value = $('<span class="value" />').text datetime.format item.attr 'data-format'

            plus.on 'click', () ->
              rem = 0
              if moment(change.val(), format).format(item.attr('data-format')).match /\d+/
                rem = moment(change.val(), format).format(item.attr('data-format')) % parseInt(item.attr('data-step'))
              add = parseInt(item.attr('data-step')) - rem
              datetime.add add, item.attr('data-type')
              change.val datetime.format format
              change.trigger 'setdata'

            minus.on 'click', () ->
              rem = 0
              if moment(change.val(), format).format(item.attr('data-format')).match /\d+/
                rem = moment(change.val(), format).format(item.attr('data-format')) % parseInt(item.attr('data-step'))
              add = if rem > 0 then rem else parseInt(item.attr('data-step'))
              datetime.add 0 - add, item.attr('data-type')
              change.val datetime.format format
              change.trigger 'setdata'

            item.append(plus).append(value).append(minus)
      
            
      select.inputmask maskinput
      change.inputmask maskinput
      select.attr 'readonly': true
      select.after wrap
      select.prependTo wrap