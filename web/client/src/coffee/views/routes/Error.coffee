define ['common/View', 'jquery'], (view) ->
  class view extends view
    template: '<h2><%=name%></h2><i class="close"></i><div class="message"><%=message%></div>'