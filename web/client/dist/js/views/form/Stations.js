var extend = function(child, parent) { for (var key in parent) { if (hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
  hasProp = {}.hasOwnProperty;

define(['views/form/Modal'], function(view) {
  return view = (function(superClass) {
    extend(view, superClass);

    function view() {
      return view.__super__.constructor.apply(this, arguments);
    }

    view.prototype.template = '<div class="select"> <input class="select" readonly="readonly" type="text" /> <div class="<%=wrapper%> hide"> <div class="modal"> <div class="header"> <input class="search" type="text" /> </div> <div class="section"> <ul class="items"></ul> <ul class="not-fount hide"> <li class="text-center"> <em>Нет результатов</em> </li> </ul> </div> <div class="footer"> <button class="cancel danger" type="button">Отмена</button> </div> </div> </div> </div>';

    view.prototype.render = function(select, stations, autoselect) {
      var block, cancel, i, input, items, len, listitem, message, modal, nextinput, option, previnput, search, self, station, wrap;
      if (autoselect == null) {
        autoselect = true;
      }
      self = this;
      wrap = view.__super__.render.call(this);
      select = select;
      block = wrap.find("." + this.wrapper);
      modal = wrap.find('.modal');
      items = wrap.find('.items');
      input = wrap.find('.select');
      search = wrap.find('.search');
      cancel = wrap.find('.cancel');
      message = wrap.find('.not-fount');
      nextinput = function() {
        return select.parents('.form-group').nextAll().find('input:visible').first().focus();
      };
      previnput = function() {
        return select.parents('.form-group').prevAll().find('input:visible').last().focus();
      };
      for (i = 0, len = stations.length; i < len; i++) {
        station = stations[i];
        option = $('<option />');
        option.html(station.getAttr('name'));
        option.val(station.getAttr('id'));
        option.attr({
          'selected': parseInt(select.attr('data-value')) === station.getAttr('id') ? true : false
        });
        select.append(option);
        listitem = $('<li />');
        listitem.html(station.getAttr('name'));
        listitem.attr({
          'data-value': station.getAttr('id')
        });
        listitem.on('click', function(e) {
          select.val($(this).attr('data-value'));
          select.trigger('change');
          return self.hide();
        });
        items.append(listitem);
      }
      select.on('change', function() {
        var value;
        value = this.value;
        return $(this).find('option').each(function() {
          if (this.value === value) {
            return input.val(this.innerHTML);
          }
        });
      });
      select.trigger('change');
      select.addClass('hide');
      select.after(wrap);
      select.prependTo(wrap);
      select.trigger('change');
      input.on('focus click', function() {
        return self.show(function() {
          search.val('');
          return setTimeout(function() {
            return search.focus();
          });
        });
      });
      cancel.on('keypress click', function(e) {
        console.log(nextinput);
        if (e.type === 'keypress' && e.keyCode === 13) {
          nextinput();
        }
        return self.hide();
      });
      search.on('focus input keydown', function(e) {
        var focus, parent, value;
        if (e.type === 'keydown' && e.keyCode === 40) {
          if (items.find('.focus').length === 0) {
            items.children().not('.hide').first().addClass('focus');
          } else {
            items.find('.focus').removeClass('focus').nextAll().not('.hide').first().addClass('focus');
          }
        }
        if (e.type === 'keydown' && e.keyCode === 38) {
          if (items.find('.focus').length === 0) {
            items.children().not('.hide').last().addClass('focus');
          } else {
            items.find('.focus').removeClass('focus').prevAll().not('.hide').first().addClass('focus');
          }
        }
        if (e.type === 'keydown' && (e.keyCode === 40 || e.keyCode === 38)) {
          focus = items.find('.focus');
          if (focus.length !== 0) {
            parent = items.parent();
            parent.scrollTop(focus.outerHeight() * focus.index());
          }
        }
        if (e.type === 'keydown' && e.keyCode === 13) {
          e.preventDefault();
          focus = items.find('.focus');
          if (focus.length !== 0) {
            focus.trigger('click');
            nextinput();
          }
        }
        if (e.type === 'keydown' && e.shiftKey && e.keyCode === 9) {
          previnput();
        }
        value = this.value.toLowerCase();
        items.find('li').each(function() {
          if (this.innerHTML.toLowerCase().search(value) === -1) {
            $(this).addClass('hide');
            $(this).removeClass('focus');
            return $(this).removeClass('show');
          } else {
            $(this).addClass('show');
            return $(this).removeClass('hide');
          }
        });
        if (items.find('.show').length === 0) {
          items.addClass('hide');
          return message.removeClass('hide');
        } else {
          items.removeClass('hide');
          return message.addClass('hide');
        }
      });
      if ('geolocation' in navigator && autoselect !== false && select.val().length === 0) {
        navigator.geolocation.getCurrentPosition(function(position) {
          var check, coord, coords, distance, j, k, len1, len2, minindex;
          distance = false;
          minindex = false;
          for (j = 0, len1 = stations.length; j < len1; j++) {
            station = stations[j];
            coords = station.getAttr('coords');
            if ($.isArray(coords) === false || coords.length === 0) {
              continue;
            }
            for (k = 0, len2 = coords.length; k < len2; k++) {
              coord = coords[k];
              if (distance === false && minindex === false) {
                minindex = station.getAttr('id');
                distance = Math.pow(coord[0] - position.coords.latitude, 2);
                distance += Math.pow(coord[1] - position.coords.longitude, 2);
              } else {
                check = Math.pow(coord[0] - position.coords.latitude, 2);
                check += Math.pow(coord[1] - position.coords.longitude, 2);
                if (check <= distance) {
                  minindex = station.getAttr('id');
                  distance = check;
                }
              }
            }
          }
          if (minindex !== false) {
            return select.val(minindex).trigger('change');
          }
        });
      }
      return this;
    };

    return view;

  })(view);
});

//# sourceMappingURL=../../sourcemaps/views/form/Stations.js.map
