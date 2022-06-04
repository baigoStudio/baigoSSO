/*
v1.1.0 jQuery baigoDialog plugin 对话框插件
(c) 2013 baigo studio - https://www.baigo.net/
License: http://www.opensource.org/licenses/mit-license.php
*/
;{
  jQuery.baigoDialog = function(options) {
    'use strict';
    if (this.length < 1) {
      return this;
    }

    var opts_default = {
      selector: {
        modal: '.bg-confirm-modal',
        msg: '.bg-msg',
        cancel: '.bg-cancel',
        confirm: '.bg-confirm',
        ok: '.bg-ok',
        footer: '.bg-footer',
        group_confirm: '.bg-group-confirm',
        group_input: '.bg-group-input',
        input_label: '.bg-input-label',
        input_control: '.bg-input-control'
      },
      btn_text: {
        cancel: 'Cancel',
        confirm: 'Confirm',
        ok: 'OK'
      },
      tpl: '<div class="modal bg-confirm-modal">' +
        '<div class="modal-dialog modal-dialog-centered">' +
          '<div class="modal-content">' +
            '<div class="modal-body">' +
              '<div class="bg-msg"></div>' +
            '</div>' +
            '<div class="modal-footer bg-footer"></div>' +
          '</div>' +
        '</div>' +
      '</div>',
      btn_tpl: {
        alert: '<button type="button" class="btn btn-primary btn-sm bg-ok" data-dismiss="modal">OK</button>',
        confirm: '<button type="button" class="btn btn-outline-secondary btn-sm bg-cancel bg-group-confirm" data-act="cancel">Cancel</button> <button type="button" class="btn btn-primary btn-sm bg-confirm bg-group-confirm" data-act="confirm">Confirm</button>',
        input: '<button type="button" class="btn btn-outline-secondary btn-sm bg-cancel bg-group-input" data-act="cancel">Cancel</button> <button type="button" class="btn btn-primary btn-sm bg-confirm bg-group-input" data-act="ok">OK</button>'
      },
      input_tpl: {
        text: '<div class="form-group" id="{:id}"><label class="bg-input-label">{:label}</label><input type="text" class="form-control bg-input-control" id="{:id_input}"></div>',
        textarea: '<div class="form-group" id="{:id}"><label class="bg-input-label">{:label}</label><textarea class="form-control bg-input-control" id="{:id_input}"></textarea></div>',
        select: '<div class="form-group" id="{:id}"><label class="bg-input-label">{:label}</label><select class="form-control bg-input-control" id="{:id_input}">{:items}</select></div>',
        select_input: '<div class="form-group" id="{:id}"><label class="bg-input-label">{:label}</label><div class="input-group"><input type="text" class="form-control bg-input-control" id="{:id_input}"><span class="input-group-append"><button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">Please select</button><div class="dropdown-menu">{:items}</div></span></div></div>',
        radio: '<div class="form-group" id="{:id}"><label class="bg-input-label">{:label}</label><div class="bg-input-box">{:items}</div></div>',
        'switch': '<div class="custom-control custom-switch" id="{:id}"><input type="checkbox" value="1" class="custom-control-input" id="{:id_input}"><label for="{:id_input}" class="custom-control-label">{:label}</label></div>'
      },
      item_tpl: {
        select: '<option {:selected} value="{:value}">{:label}</option>',
        select_input: '<button class="dropdown-item bg-select-input" data-value="{:value}" data-target="{:target}" type="button">{:label}</button>',
        radio: '<div class="form-check{:form-check-inline}"><input type="radio" value="{:value}" name="{:name}" id="{:id}" {:checked} class="form-check-input"><label for="{:id}" class="form-check-label">{:label}</label>{:note}</div>'
      }
    };

    var opts = $.extend(true, opts_default, options);
    var selector_modal = opts.selector.modal;

    if ($(selector_modal).length < 1) {
      $('body').append(opts.tpl);
    }

    $(selector_modal).on('click', '.bg-select-input', function(){
      var _val    = $(this).data('value');
      var _target = $(this).data('target');

      $(_target).val(_val);
    });

    var process = {
      modalShow: function(msg) {
        if (typeof msg == 'string' && msg.length > 0) {
          $(selector_modal + ' ' + opts.selector.msg).text(msg);

          var _option = { backdrop: 'static', show: true };

          $(selector_modal).modal(_option);
        }
      },
      inputShow: function(input) {
        var _input_length = process.len(input);

        if (typeof input != 'undefined' && _input_length > 0) {
          var _input_html = '';

          if (typeof input == 'object') {
            $.each(input, function(_key, _value){
              if (typeof _value == 'object') {
                if (typeof _value.id == 'undefined') {
                  _value.id = _key;
                }

                if (typeof _value.type == 'undefined') {
                  _value.type = 'text';
                }

                if (typeof _value.label == 'undefined') {
                  _value.label = _value.id;
                }

                _input_html += process.inputTpl(_value.type, _value.id, _value.label);

                if (typeof _value.items == 'object') {
                  var _radio_tpl = process.itemTpl(_value.id, _value.type, _value.items);
                  _input_html = process.replaceAll(_input_html, '{:items}', _radio_tpl);
                }
              } else if (typeof _value == 'string') {
                _input_html += process.inputTpl('text', _value, _value);
              }
            });
          } else if (typeof input == 'string') {
            _input_html = process.inputTpl('text', input, input);
          }

          if (_input_html.length > 0) {
            var _option = { backdrop: 'static', show: true };

            $(selector_modal + ' ' + opts.selector.msg).html(_input_html);
            $(selector_modal).modal(_option);
          }
        }
      },
      modalHide: function() {
        $(selector_modal).modal('hide');
      },
      inputTpl: function(type, id, label) {
        var _tpl_return = '';

        if (typeof opts.input_tpl[type] == 'undefined') {
          _tpl_return = opts.input_tpl.text;
        } else {
          _tpl_return = opts.input_tpl[type];
        }

        _tpl_return = process.replaceAll(_tpl_return, '{:id}', id);
        _tpl_return = process.replaceAll(_tpl_return, '{:label}', label);
        _tpl_return = process.replaceAll(_tpl_return, '{:id_input}', id + '_input');

        return _tpl_return;
      },
      itemTpl: function(id, type, items) {
        var _tpl_return   = '';
        var _item_tpl     = '';
        var _item_replace = {};

        $.each(items, function(_key, _value){
          if (typeof opts.item_tpl[type] != 'undefined') {
            _item_tpl              = opts.item_tpl[type];
            _item_replace.id       = id + '_' + _key;
            _item_replace.value    = _key;
            _item_replace.text     = _key;
            _item_replace.selected = '';
            _item_replace.checked  = '';

            if (typeof _value == 'object') {
              if (typeof _value.value == 'string') {
                _item_replace.value = _value.value;
              }

              if (typeof _value.text == 'string') {
                _item_replace.text = _value.text;
              }

              if (typeof _value.note == 'string') {
                _item_replace.note = _value.note;
              }

              if (typeof _value.selected != 'undefined' && _value.selected == true) {
                _item_replace.selected = 'selected';
              }

              if (typeof _value.checked != 'undefined' && _value.checked == true) {
                _item_replace.checked = 'checked';
              }
            } else if (typeof _value == 'string') {
              _item_replace.value  = _value;
              _item_replace.text   = _value;
            }

            _item_tpl = process.replaceAll(_item_tpl, '{:id}', _item_replace.id);
            _item_tpl = process.replaceAll(_item_tpl, '{:name}', id + '_input');
            _item_tpl = process.replaceAll(_item_tpl, '{:target}', '#' + id + '_input');
            _item_tpl = process.replaceAll(_item_tpl, '{:value}', _item_replace.value);
            _item_tpl = process.replaceAll(_item_tpl, '{:label}', _item_replace.text);
            _item_tpl = process.replaceAll(_item_tpl, '{:selected}', _item_replace.selected);
            _item_tpl = process.replaceAll(_item_tpl, '{:checked}', _item_replace.checked);

            if (typeof _item_replace.note == 'string') {
              _item_tpl = process.replaceAll(_item_tpl, '{:form-check-inline}', '');
              _item_tpl = process.replaceAll(_item_tpl, '{:note}', '<small class="form-text">' + _item_replace.note + '</small>');
            } else {
              _item_tpl = process.replaceAll(_item_tpl, '{:form-check-inline}', ' form-check-inline');
              _item_tpl = process.replaceAll(_item_tpl, '{:note}', '');
            }

            _tpl_return += _item_tpl;
          }
        });

        return _tpl_return;
      },
      replaceAll: function (string, search, replace) {
        var _regex = new RegExp(search, 'g');
        return string.replace(_regex, replace);
      },
      len: function(obj) {
        var _input_length = 0;

        if (typeof obj.length == 'undefined') {
          var _keys = Object.keys(obj);

          _input_length = _keys.length;
        } else {
          _input_length = obj.length;
        }

        return _input_length;
      }
    };

    var el = {
      alert: function(msg) {
        $(selector_modal + ' ' + opts.selector.footer).html(opts.btn_tpl.alert);
        $(selector_modal + ' ' + opts.selector.ok).text(opts.btn_text.ok);

        process.modalShow(msg);

        $(selector_modal + ' ' + opts.selector.ok).click(function() {
          process.modalHide();
        });
      },
      confirm: function(msg, callback) {
        if (typeof msg == 'string' && msg.length > 0) {
          $(selector_modal + ' ' + opts.selector.footer).html(opts.btn_tpl.confirm);
          $(selector_modal + ' ' + opts.selector.cancel).text(opts.btn_text.cancel);
          $(selector_modal + ' ' + opts.selector.confirm).text(opts.btn_text.confirm);

          process.modalShow(msg);

          if (callback && callback instanceof Function) {
            $(selector_modal + ' ' + opts.selector.group_confirm).off('click');
            $(selector_modal + ' ' + opts.selector.group_confirm).click(function() {
              if ($(this).data('act') == 'confirm') {
                callback(true);
              } else {
                callback(false);
              }

              process.modalHide();
            });
          }
        }
      },
      input: function(input, callback) {
        if (typeof input != 'undefined') {
          $(selector_modal + ' ' + opts.selector.footer).html(opts.btn_tpl.input);
          $(selector_modal + ' ' + opts.selector.cancel).text(opts.btn_text.cancel);
          $(selector_modal + ' ' + opts.selector.ok).text(opts.btn_text.ok);

          process.inputShow(input);

          if (callback && callback instanceof Function) {
            $(selector_modal + ' ' + opts.selector.group_input).off('click');
            $(selector_modal + ' ' + opts.selector.group_input).click(function(){
              var _input_length = process.len(input);

              if ($(this).data('act') == 'ok' && _input_length > 0) {
                if (typeof input == 'object') {
                  var _return = {};
                  $.each(input, function(_key, _value){
                    if (typeof _value == 'object') {
                      if (typeof _value.id == 'undefined') {
                        _value.id = _key;
                      }

                      if (typeof _value.type == 'undefined') {
                        _value.type = 'text';
                      }

                      switch (_value.type) {
                        case 'radio':
                          if (typeof _value.id != 'undefined') {
                            _return[_key] = $(selector_modal + ' :radio:checked').val();
                          }
                        break;

                        case 'switch':
                          if (typeof _value.id != 'undefined') {
                            var _checked = $(selector_modal + ' :checkbox:checked').val();

                            if (typeof _checked != 'undefined') {
                              _return[_key] = _checked;
                            }
                          }
                        break;

                        default:
                          if (typeof _value.id != 'undefined') {
                            _return[_key] = $(selector_modal + ' #' + _value.id + '_input').val();
                          }
                        break;
                      }
                    } else if (typeof _value == 'string') {
                      _return[_key] = $(selector_modal + ' #' + _value + '_input').val();
                    }
                  });
                } else {
                  var _return = $(selector_modal + ' ' + opts.selector.input_control).val();
                }

                callback('ok', _return);
              } else {
                callback('cancel');
              }

              process.modalHide();
            });
          }
        }
      }
    };

    return el;
  }
};
