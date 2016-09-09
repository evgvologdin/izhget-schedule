define(['jquery'], function() {
  var form;
  return form = (function() {
    form.prototype.form = null;

    form.prototype.attr = {
      from: null,
      to: null,
      date: null,
      transfers: []
    };

    form.prototype.rules = {
      empty: function(e) {
        if ((e.val() != null) && e.val() !== '') {
          e.parents('.form-group').removeClass('has-error');
          return true;
        } else {
          e.parents('.form-group').addClass('has-error');
          return false;
        }
      },
      compare: function(e, c) {
        if (e.val() !== c.val()) {
          c.parents('.form-group').removeClass('has-error');
          return true;
        } else {
          c.parents('.form-group').addClass('has-error');
          return false;
        }
      }
    };

    function form(e) {
      var self;
      if (e == null) {
        e = $('form');
      }
      self = this;
      self.attr.from = e.find('#from-station').length > 0 ? e.find('#from-station') : null;
      self.attr.to = e.find('#to-station').length > 0 ? e.find('#to-station') : null;
      self.attr.date = e.find('#has-date').is(':checked') ? e.find('#select-time') : e.find('#current-time');
      self.attr.transfers = [];
      e.find('#transfer-stations').find('input:checked').each(function() {
        return self.attr.transfers.push($(this));
      });
      return this;
    }

    form.prototype.requestData = function() {
      var data, i, len, ref, self, transfer;
      self = this;
      if (self.validate === false) {
        return false;
      }
      data = [];
      data.push({
        from: self.attr.from.val(),
        to: self.attr.to.val(),
        date: self.attr.date.val()
      });
      ref = self.attr.transfers;
      for (i = 0, len = ref.length; i < len; i++) {
        transfer = ref[i];
        data.push({
          from: self.attr.from.val(),
          to: self.attr.to.val(),
          date: self.attr.date.val(),
          transfer: transfer.val()
        });
      }
      return data;
    };

    form.prototype.validate = function() {
      var i, len, ref, transfer;
      if (this.attr.from !== null && this.attr.to !== null && this.attr.date !== null && $.isArray(this.attr.transfers) === true) {
        if (this.rules.empty(this.attr.from) === false) {
          return false;
        }
        if (this.rules.empty(this.attr.to) === false) {
          return false;
        }
        if (this.rules.empty(this.attr.date) === false) {
          return false;
        }
        if (this.rules.compare(this.attr.from, this.attr.to) === false) {
          return false;
        }
        ref = this.attr.transfers;
        for (i = 0, len = ref.length; i < len; i++) {
          transfer = ref[i];
          if (this.rules.compare(this.attr.from, transfer) === false) {
            return false;
          }
          if (this.rules.compare(this.attr.to, transfer) === false) {
            return false;
          }
        }
        return true;
      } else {
        return alert('Invalid Form Data');
      }
    };

    return form;

  })();
});

//# sourceMappingURL=../sourcemaps/models/Form.js.map
