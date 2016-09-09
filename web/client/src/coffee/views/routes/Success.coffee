define ['common/View', 'jquery'], (view) ->
  class view extends view
    template: '
      <h2><%=name%></h2><i class="close"></i>
      <% for(i in routes) { var route = routes[i];%>
      <div class="route">
        <div class="info">
          <span class="col start"><%=route.number%></span>
          <span class="col time">
            <small class="start"><%=route.from_time%></small>
            <small class="finish"><%=route.to_time%></small>
          </span>
          <% if(route.transfer) { %>
          <span class="col branch"><%=route.transfer.route.number%></span>
          <span class="col time">
            <small class="start"><%=route.transfer.route.from_time%></small>
            <small class="finish"><%=route.transfer.route.to_time%></small>
          </span>
          <% } %>
          <span class="col finish">&nbsp;</span>
        </div>
        <div class="timeline">
            <small><%=route.summary_time%></small><hr />
        </div>
      </div>
      <% } %>
    '