var extend = function(child, parent) { for (var key in parent) { if (hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
  hasProp = {}.hasOwnProperty;

define(['common/View', 'jquery'], function(view) {
  return view = (function(superClass) {
    extend(view, superClass);

    function view() {
      return view.__super__.constructor.apply(this, arguments);
    }

    view.prototype.template = '<h2><%=name%></h2><i class="close"></i> <% for(i in routes) { var route = routes[i];%> <div class="route"> <div class="info"> <span class="col start"><%=route.number%></span> <span class="col time"> <small class="start"><%=route.from_time%></small> <small class="finish"><%=route.to_time%></small> </span> <% if(route.transfer) { %> <span class="col branch"><%=route.transfer.route.number%></span> <span class="col time"> <small class="start"><%=route.transfer.route.from_time%></small> <small class="finish"><%=route.transfer.route.to_time%></small> </span> <% } %> <span class="col finish">&nbsp;</span> </div> <div class="timeline"> <small><%=route.summary_time%></small><hr /> </div> </div> <% } %>';

    return view;

  })(view);
});

//# sourceMappingURL=../../sourcemaps/views/routes/Success.js.map
