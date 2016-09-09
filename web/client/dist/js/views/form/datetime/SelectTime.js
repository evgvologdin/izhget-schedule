var extend = function(child, parent) { for (var key in parent) { if (hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
  hasProp = {}.hasOwnProperty;

define(['views/form/Modal', 'moment', 'moment_ru', 'inputmask'], function(view, moment) {
  return view = (function(superClass) {
    extend(view, superClass);

    function view() {
      return view.__super__.constructor.apply(this, arguments);
    }

    view.prototype.template = '<div class="datetime"> <div class="modal-wrap hide"> <div class="modal"> <div class="header"> <input class="change" type="datetime"> </div> <div class="section"> <ul class="items"> <li class="item" data-format="DD" data-type="day" data-step="1"></li> <li class="item" data-format="MMM" data-type="month" data-step="1"></li> <li class="item" data-format="YYYY" data-type="year" data-step="1"></li> <li class="item" data-format="HH" data-type="hour" data-step="1"></li> <li class="delimiter">:</li> <li class="item" data-format="mm" data-type="minutes" data-step="10"></li> </ul> </div> <div class="footer"> <button class="choose" type="button">Ок</button> <button class="cancel danger" type="button">Отмена</button> </div> </div> </div> </div>';

    view.prototype.render = function(format) {
      var block, cancel, change, choose, datetime, items, maskinput, message, modal, nextinput, previnput, select, self, wrap;
      self = this;
      wrap = view.__super__.render.call(this);
      select = $('#select-time');
      block = wrap.find("." + this.wrapper);
      modal = wrap.find('.modal');
      items = wrap.find('.item');
      change = wrap.find('.change');
      cancel = wrap.find('.cancel');
      choose = wrap.find('.choose');
      message = wrap.find('.not-fount');
      format = format;
      maskinput = format.replace(/\w/g, 9);
      datetime = moment();
      nextinput = function() {
        return select.parents('#select-time-block').nextAll().find('input:visible').first().focus();
      };
      previnput = function() {
        return select.parents('#select-time-block').prevAll().find('input:visible').last().focus();
      };
      select.on('focus click', function() {
        if (moment(select.val(), format).format() !== 'Invalid date') {
          datetime = moment(select.val(), format);
        }
        change.val(datetime.format(format));
        change.trigger('setdata');
        return self.show(function() {
          return setTimeout(function() {
            return change.focus();
          });
        });
      });
      cancel.on('click keypress', function(e) {
        if (e.type === 'keypress' && e.keyCode === 13) {
          nextinput();
        }
        return self.hide();
      });
      choose.on('click keypress', function(e) {
        if (moment(change.val(), format).format() !== 'Invalid date') {
          datetime = moment(change.val(), format);
        }
        select.val(datetime.format(format));
        if (e.type === 'keypress' && e.keyCode === 13) {
          nextinput();
        }
        return self.hide();
      });
      change.on('input keydown setdata', function(e) {
        if (moment(this.value, format).format() !== 'Invalid date') {
          datetime = moment(this.value, format);
        }
        if (e.type === 'keydown' && e.keyCode === 13) {
          e.preventDefault();
          nextinput();
          choose.trigger('click');
        }
        if (e.type === 'keydown' && e.shiftKey && e.keyCode === 9) {
          previnput();
        }
        return items.each(function() {
          var item, minus, plus, value;
          item = $(this);
          if (item.find('.value').length > 0) {
            return item.find('.value').text(datetime.format(item.attr('data-format')));
          } else {
            plus = $('<button class="plus"  type="button">+</button>');
            minus = $('<button class="minus" type="button">&minus;</button>');
            value = $('<span class="value" />').text(datetime.format(item.attr('data-format')));
            plus.on('click', function() {
              var add, rem;
              rem = 0;
              if (moment(change.val(), format).format(item.attr('data-format')).match(/\d+/)) {
                rem = moment(change.val(), format).format(item.attr('data-format')) % parseInt(item.attr('data-step'));
              }
              add = parseInt(item.attr('data-step')) - rem;
              datetime.add(add, item.attr('data-type'));
              change.val(datetime.format(format));
              return change.trigger('setdata');
            });
            minus.on('click', function() {
              var add, rem;
              rem = 0;
              if (moment(change.val(), format).format(item.attr('data-format')).match(/\d+/)) {
                rem = moment(change.val(), format).format(item.attr('data-format')) % parseInt(item.attr('data-step'));
              }
              add = rem > 0 ? rem : parseInt(item.attr('data-step'));
              datetime.add(0 - add, item.attr('data-type'));
              change.val(datetime.format(format));
              return change.trigger('setdata');
            });
            return item.append(plus).append(value).append(minus);
          }
        });
      });
      select.inputmask(maskinput);
      change.inputmask(maskinput);
      select.attr({
        'readonly': true
      });
      select.after(wrap);
      return select.prependTo(wrap);
    };

    return view;

  })(view);
});

//# sourceMappingURL=../../../sourcemaps/views/form/datetime/SelectTime.js.map
