define(['jquery', 'underscore'], function($, _) {
  var AjaxQueue;
  return AjaxQueue = (function() {
    var AjaxQueuePrivate, instance;

    function AjaxQueue() {}

    instance = null;

    AjaxQueue.prototype.instance = function() {
      return instance != null ? instance : instance = new AjaxQueuePrivate;
    };

    AjaxQueue.prototype.add = function(params) {
      if (params == null) {
        params = {};
      }
      return this.instance().add(params);
    };

    AjaxQueue.prototype.run = function(callback) {
      if (callback == null) {
        callback = function() {};
      }
      return this.instance().run(callback);
    };

    AjaxQueuePrivate = (function() {
      var requests;

      function AjaxQueuePrivate() {}

      requests = [];

      AjaxQueuePrivate.prototype.add = function(params) {
        if (params == null) {
          params = {};
        }
        requests.push(params);
        return this;
      };

      AjaxQueuePrivate.prototype.run = function(callback) {
        var queue, request, results;
        if (callback == null) {
          callback = function() {};
        }
        queue = _.clone(requests);
        requests = [];
        results = [];
        while (request = queue.pop()) {
          request.complete = function(response, status) {
            if (queue.length === 0) {
              return callback();
            }
          };
          results.push($.ajax(request));
        }
        return results;
      };

      return AjaxQueuePrivate;

    })();

    return AjaxQueue;

  })();
});

//# sourceMappingURL=../sourcemaps/components/AjaxQueue.js.map
