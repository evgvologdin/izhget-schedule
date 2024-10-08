var extend = function(child, parent) { for (var key in parent) { if (hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
  hasProp = {}.hasOwnProperty;

define(['common/Model'], function(model) {
  return model = (function(superClass) {
    extend(model, superClass);

    function model() {
      return model.__super__.constructor.apply(this, arguments);
    }

    model.prototype.loadUrl = '/api/stations';

    return model;

  })(model);
});

//# sourceMappingURL=../sourcemaps/models/Stations.js.map
