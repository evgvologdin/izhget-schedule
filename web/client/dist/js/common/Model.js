define(['jquery', 'underscore', 'components/AjaxQueue'], function($, _, queue) {
  var model;
  return model = (function() {
    function model() {}

    model.prototype.attributes = [];

    model.prototype.loadUrl = null;

    model.prototype.setAttr = function(index, value) {
      return this.attributes[index] = value;
    };

    model.prototype.getAttr = function(index) {
      if (this.attributes[index] != null) {
        return this.attributes[index];
      }
    };

    model.prototype.setAttrs = function(value) {
      return this.attributes = value;
    };

    model.prototype.getAttrs = function() {
      return this.attributes;
    };

    model.prototype.loadOne = function(params, later) {
      var request, self;
      if (params == null) {
        params = {};
      }
      if (later == null) {
        later = false;
      }
      self = this;
      request = {
        url: 'url' in params ? params.url : this.loadUrl,
        data: 'data' in params ? params.data : void 0,
        type: 'type' in params ? params.type : 'post',
        dataType: 'json',
        context: this,
        success: function(response, status) {
          var load;
          load = _.clone(self);
          load.setAttrs($.isArray(response) === true ? response[0] : response);
          if ('success' in params) {
            return params.success(load);
          }
        },
        error: function(response, status) {
          if ('error' in params) {
            return params.error(response, status);
          }
        }
      };
      if (later === true) {
        return queue.prototype.add(request);
      } else {
        return $.ajax(request);
      }
    };

    model.prototype.loadAll = function(params, later) {
      var request, self;
      if (params == null) {
        params = {};
      }
      if (later == null) {
        later = false;
      }
      self = this;
      request = {
        url: 'url' in params ? params.url : this.loadUrl,
        data: 'data' in params ? params.data : void 0,
        type: 'type' in params ? params.type : 'post',
        dataType: 'json',
        success: function(response, status) {
          var attrs, data, i, len, load;
          load = [];
          if ($.isArray(response)) {
            for (i = 0, len = response.length; i < len; i++) {
              attrs = response[i];
              data = _.clone(self);
              data.setAttrs(attrs);
              load.push(data);
            }
          }
          if ('success' in params) {
            return params.success(load);
          }
        },
        error: function(response, status) {
          if ('error' in params) {
            return params.error(response, status);
          }
        }
      };
      if (later === true) {
        return queue.prototype.add(request);
      } else {
        return $.ajax(request);
      }
    };

    return model;

  })();
});

//# sourceMappingURL=../sourcemaps/common/Model.js.map
