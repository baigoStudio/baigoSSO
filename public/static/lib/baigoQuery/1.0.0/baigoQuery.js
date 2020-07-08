/*
v1.0.0 jQuery baigoQuery plugin 表单提交插件
(c) 2013 baigo studio - http://www.baigo.net/
License: http://www.opensource.org/licenses/mit-license.php
*/
;(function($){
    $.fn.baigoQuery = function(options) {
        'use strict';
        if (this.length < 1) {
            return this;
        }

        var obj_form   = $(this); //定义表单对象

        obj_form.submit(function(evt){
            evt.preventDefault();
        });

        var opts_default = {
            action: '',
            separator: '/',
            equal: '/'
        };

        var opts = $.extend(true, opts_default, options);

        var process = {
            serialize: function() {
                var _arr_param = $(obj_form).serializeArray();
                var _str_param = '';

                $.each(_arr_param, function(_key, _value){
                    if (_value.value.length > 0) {
                        _str_param += _value.name + opts.equal + _value.value + opts.separator;
                    }
                });

                _str_param = process.trim(_str_param, opts.separator, 'right');

                //console.log(_str_param);

                return _str_param;
            },
            trim: function(str, chr, type) {
                var _str_return = '';

                if (typeof type == 'undefined') {
                    type = '';
                }

                if (typeof chr != 'undefined') {
                    switch (type) {
                        case 'left':
                            _str_return = str.replace(new RegExp('^\\' + chr + '+', 'g'), '');
                        break;

                        case 'right':
                            _str_return = str.replace(new RegExp('\\' + chr + '+$', 'g'), '');
                        break;

                        default:
                            _str_return = str.replace(new RegExp('^\\' + chr + '+|\\' + chr + '+$', 'g'), '');
                        break;
                    }
                } else {
                    _str_return = str.replace(/^\s+|\s+$/g, '');
                }

                return _str_return;
            }
        };

        //ajax 提交
        var el = {
            formSubmit: function() {
                if (typeof opts.action == 'undefined' || opts.action == '') {
                    opts.action = obj_form.attr('action');
                }

                var _str_param  = process.serialize();
                var _str_action = process.trim(opts.action, opts.separator, 'right') + opts.separator;

                //console.log(_str_action + _str_param);

                window.location.href = _str_action + _str_param;
            }
        };

        return el;
    };

})(jQuery);