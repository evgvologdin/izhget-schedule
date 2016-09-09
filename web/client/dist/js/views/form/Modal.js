var extend = function(child, parent) { for (var key in parent) { if (hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
  hasProp = {}.hasOwnProperty;

define(['common/View', 'jquery'], function(view) {
  return view = (function(superClass) {
    extend(view, superClass);

    function view() {
      return view.__super__.constructor.apply(this, arguments);
    }

    view.prototype.wrapper = 'modal-wrap';

    view.prototype.template = '<div class="<%=wrapper%> hide"></div>';

    view.prototype.modal = '';

    view.prototype.render = function() {
      var self, wrapper;
      self = this;
      this.modal = $(view.__super__.render.call(this, {
        wrapper: this.wrapper
      }));
      wrapper = this.modal.find("." + this.wrapper).length === 0 ? this.modal : this.modal.find("." + this.wrapper);
      $(document).on('mouseup keyup', function(e) {
        if (e.type === 'keyup' && e.keyCode === 9 && $(e.target).closest(wrapper).length === 0) {
          self.hide();
        }
        if (e.type === 'mouseup' && $(e.target).closest(wrapper).length === 0) {
          return self.hide();
        }
      });
      return this.modal;
    };

    view.prototype.show = function(c) {
      var self;
      if (c == null) {
        c = function() {};
      }
      self = this;
      setTimeout(function() {
        return self.modal.find("." + self.wrapper).removeClass('hide');
      });
      return c();
    };

    view.prototype.hide = function(c) {
      if (c == null) {
        c = function() {};
      }
      this.modal.find("." + this.wrapper).addClass('hide');
      return c();
    };

    return view;

  })(view);
});

//# sourceMappingURL=../../sourcemaps/views/form/Modal.js.map
