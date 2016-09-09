var extend = function(child, parent) { for (var key in parent) { if (hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
  hasProp = {}.hasOwnProperty;

define(['common/Model'], function(model) {
  return model = (function(superClass) {
    extend(model, superClass);

    function model() {
      return model.__super__.constructor.apply(this, arguments);
    }

    model.prototype.loadOne = function(params, later) {
      if (params == null) {
        params = {};
      }
      if (later == null) {
        later = false;
      }
      params.url = 'transfer' in params.data ? '/api/transfer-routes' : '/api/routes';
      return model.__super__.loadOne.call(this, params, later);
    };

    return model;

  })(model);
});

//# sourceMappingURL=../sourcemaps/models/Routes.js.map
