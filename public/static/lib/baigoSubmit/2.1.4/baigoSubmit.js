/*
v2.1.4 jQuery baigoSubmit plugin 表单提交插件
(c) 2013 baigo studio - http://www.baigo.net/
License: http://www.opensource.org/licenses/mit-license.php
*/
;(function($){
  $.fn.baigoSubmit = function(options) {
    'use strict';
    if (this.length < 1) {
      return this;
    }

    var obj_form = $(this); //定义表单对象

    obj_form.submit(function(evt){
      evt.preventDefault();
    });

    var opts_default = {
      ajax_url: '',
      timeout: 30000,
      class_msg: {
        submitting: 'bg-msg-submitting',
        err: 'bg-msg-err',
        success: 'bg-msg-success'
      },
      msg_text: {
        submitting: 'Submitting ...',
        err: 'Error'
      },
      selector: {
        submit_btn: '[type="submit"]',
        empty_input: ':password',
        jump_link: '.bg-jump'
      },
      modal: {
        selector: {
          modal: '.bg-submit-modal',
          msg: '.bg-msg',
          extra: '.bg-extra',
          close: '.bg-close',
          ok: '.bg-ok'
        },
        btn_text: {
          close: 'Close',
          ok: 'OK'
        },
        tpl: '<div class="modal fade bg-submit-modal">' +
          '<div class="modal-dialog modal-dialog-centered">' +
            '<div class="modal-content">' +
              '<div class="modal-body">' +
                '<div class="bg-msg mb-2"></div>' +
                '<div class="bg-extra mb-2"></div>' +
                '<div class="bg-jump"></div>' +
              '</div>' +
              '<div class="modal-footer">' +
                '<button type="button" class="btn btn-outline-secondary btn-sm bg-close" data-dismiss="modal">Close</button>' +
                '<a href="#" class="btn btn-primary btn-sm bg-ok">OK</a>' +
              '</div>' +
            '</div>' +
          '</div>' +
        '</div>',
        delay: 5000
      },
      replaces: '',
      jump: {
        url: '',
        text: '',
        attach_key: '',
        separator: '',
        equal: '',
        delay: 3000
      }
    };

    var opts = $.extend(true, opts_default, options);

    var selector   = opts.modal.selector;

    //console.log(selector.modal);

    if ($(selector.modal).length < 1) {
      $('body').append(opts.modal.tpl);
    }

    $(selector.modal + ' ' + selector.msg).addClass(opts.class_msg.submitting);
    $(selector.modal + ' ' + selector.msg).html(opts.msg_text.submitting);

    $(selector.modal + ' ' + selector.close).text(opts.modal.btn_text.close);
    $(selector.modal + ' ' + selector.ok).text(opts.modal.btn_text.ok);
    $(selector.modal + ' ' + selector.ok).hide();

    var process = {
      jumpUrl: function(attach_value) {
        var _jump_url = '';

        if (opts.jump.url.length > 0 && opts.jump.text.length > 0) {
          var _str_separator  = '';
          var _str_equal      = '';

          _jump_url       = opts.jump.url;

          if (opts.jump.attach_key.length > 0 && typeof attach_value != 'undefined') {
            if (typeof opts.jump.separator != 'undefined' && opts.jump.separator.length > 0) {
              _str_separator = opts.jump.separator;
            } else {
              if (_jump_url.indexOf('?')) {
                _str_separator = '&';
              } else {
                _str_separator = '?';
              }
            }

            if (typeof opts.jump.equal != 'undefined' && opts.jump.equal.length > 0) {
              _str_equal = opts.jump.equal;
            } else {
              _str_equal = '=';
            }

            _jump_url += _str_separator + opts.jump.attach_key + _str_equal + attach_value;
          }
        }

        return _jump_url;
      },
      output: function(status, rcode, msg, attach_value, extra) { //调用弹出框

        //console.log(status);

        switch(status) {
          case 'pre':
            process.modalOutput('submitting', 'submitting', opts.msg_text.submitting);

            $(selector.modal).modal('show');

            obj_form.find(opts.selector.submit_btn).attr('disabled', true);
          break;

          case 'err':
            process.modalOutput('err', 'err', opts.msg_text.err + '! ' + msg);
          break;

          case 'success':
            var _status, _icon, _str_rcode, _rcode_status;
            var _str_rcode  = '';
            var _jump_url   = '';
            var _msg_html   = '<span>';

            if (typeof rcode == 'undefined' || rcode === null) {
              rcode = 'x';
            }

            _rcode_status  = rcode.substr(0, 1);

            if (_rcode_status == 'y') {
              _status     = 'success';
              _icon       = 'success';
              _jump_url   = process.jumpUrl(attach_value);

              if (_jump_url.length > 0) {
                _icon = 'submitting';
              }
            } else {
              _status     = 'err';
              _icon       = 'err';
              _str_rcode  = ' ' + rcode;
            }

            if (typeof msg != 'undefined') {
              _msg_html = msg + _str_rcode;
            }

            _msg_html += '</span>';

            process.modalOutput(_status, _icon, _msg_html, extra);

            if (_str_rcode.length < 1) {
              obj_form.find(opts.selector.empty_input).val('');

              if (_jump_url.length > 0) {
                el.jump(_jump_url, opts.jump.text);
              }
            }
          break;

          default:
            $(selector.modal).modal('hide');

            obj_form.find(opts.selector.submit_btn).removeAttr('disabled');
          break;
        }
      },
      modalOutput: function(status, icon, msg, extra) {
        $(selector.modal + ' ' + selector.msg).removeClass(opts.class_msg.submitting + ' ' + opts.class_msg.err + ' ' + opts.class_msg.success);

        if (typeof opts.class_msg[status] != 'undefined') {
          $(selector.modal + ' ' + selector.msg).addClass(opts.class_msg[status]);
        }

        if (typeof msg != 'undefined' && msg.length > 0) {
          $(selector.modal + ' ' + selector.msg).html(msg);
        }

        if (typeof extra != 'undefined' && extra.length > 0) {
          $(selector.modal + ' ' + selector.extra).html(extra);
        }
      },
      replaceResult: function(result) {
        if (typeof opts.replaces == 'object') {
          $.each(opts.replaces, function(key, value){
            if (typeof value == 'object') {
              var _param = key;

              if (typeof value.param != 'undefined' && value.param.length > 0) {
                _param = value.param;
              }

              if (typeof result[_param] != 'undefined') {
                var _selector   = '#' + key;
                var _attr       = 'value';

                if (typeof value.selector != 'undefined' && value.selector.length > 0) {
                  _selector = value.selector;
                }

                if (typeof value.attr != 'undefined' && value.attr.length > 0) {
                  _attr = value.attr;
                }

                $(_selector).attr(_attr, result[_param]);
              }
            } else if (typeof value == 'string') {
              if (typeof result[value] != 'undefined') {
                $('#' + value).val(result[value]);
              }
            }
          });
        } else if (typeof opts.replaces == 'string') {
          if (typeof result[opts.replaces] != 'undefined') {
            $('#' + opts.replaces).val(result[opts.replaces]);
          }
        }
      },
      submitFunc: function(callback) {
        var _str_separator;

        if (typeof opts.ajax_url == 'undefined' || opts.ajax_url == '') {
          opts.ajax_url = obj_form.attr('action');
        }

        if (opts.ajax_url.indexOf('?') > 0) {
          _str_separator = '&';
        } else {
          _str_separator = '?';
        }

        $.ajax({
          url: opts.ajax_url + _str_separator + new Date().getTime() + 'at' + Math.random(), //url
          //async: false, //设置为同步
          type: 'post',
          dataType: 'json', //数据格式为json
          data: $(obj_form).serialize(),
          timeout: opts.timeout,
          beforeSend: function(){
            process.output('pre'); //输出正在提交
          }, //输出消息
          error: function(result){
            process.output('err', '', result.status + ' ' + result.statusText); //输出消息
            setTimeout(function(){
              process.output();
            }, opts.modal.delay);
          },
          success: function(result){ //读取返回结果
            var _attach_value;

            if (typeof result[opts.jump.attach_key] != 'undefined') {
              _attach_value = result[opts.jump.attach_key];
            }

            process.replaceResult(result);

            if (typeof result.rcode == 'undefined') {
              result.rcode = 'x';
            }

            if (typeof result.msg == 'undefined' && result.err_message != 'undefined') {
              result.msg = result.err_message;
            }

            if (typeof result.msg == 'undefined') {
              result.msg = 'Unknow';
            }

            if (typeof result.extra == 'undefined') {
              result.extra = '';
            }

            process.output('success', result.rcode, result.msg, _attach_value, result.extra); //输出消息

            if (callback && callback instanceof Function) {
              callback(result);
            }

            setTimeout(function(){
              process.output();
            }, opts.modal.delay);
          }
        });
      }
    };

    //ajax 提交
    var el = {
      formSubmit: function(url, callback) {
        if (typeof url != 'undefined' && url !== false) {
          opts.ajax_url = url;
        }

        process.submitFunc(function(result){
          if (callback && callback instanceof Function) {
            callback(result);
          }
        });
      },
      jump: function(url, link_text) {
        if (typeof url != 'undefined' && url.length > 0) {
          $(selector.modal + ' ' + selector.ok).attr('href', url);
          $(selector.modal + ' ' + selector.ok).show();

          if (typeof link_text == 'undefined' || link_text.length < 1) {
            link_text = url;
          }

          $(opts.selector.jump_link).html('<a href="' + url + '">' + link_text + '</a>');

          setTimeout(function(){
            window.location.href = url;
          }, opts.jump.delay);
        }
      },
      ajaxUrl: function(url) {
        if (typeof url != 'undefined') {
          opts.ajax_url = url;
        }
      }
    };

    return el;
  };

})(jQuery);
