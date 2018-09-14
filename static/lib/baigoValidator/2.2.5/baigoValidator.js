/*
v2.2.5 jQuery baigoValidator plugin 表单验证插件
(c) 2017 baigo studio - http://www.baigo.net/jquery/baigovalidator.html
License: http://www.opensource.org/licenses/mit-license.php
*/
;(function($) {
    $.fn.baigoValidator = function(fileds, options) {
        "use strict";
        if (this.length < 1) {
            return this;
        }

        // support mutltiple elements
        if (this.length > 1) {
            this.each(function(){
                $(this).baigoValidator(fileds);
            });
            return this;
        }

        var thisForm   = $(this); //定义表单对象
        var el         = this;
        var _status; //定义状态
        var _err_count; //定义错误数
        var _numLenth;
        var _iii;
        var _chkCode;
        var _reg;
        var _status_leng;
        var _ajax;
        var _ajaxData;
        var _str_attachs;
        var _set_data;
        var _len;
        var _validate;
        var _validate_selector;
        var _validate_obj;
        var _msg;
        var _result;
        var _num;
        var _str;
        var _str_target;

        var defaults = {
            class_name: {
                success: "text-success",
                err: "text-danger",
                loading: "text-info"
            },
            class_attach: {
                success: "is-valid",
                err: "is-invalid"
            },
            msg_global: {
                selector: {
                    parent: ".bg-validator-box",
                    content: ".bg-content",
                    icon: ".bg-icon",
                    msg: ".bg-msg",
                },
                msg: "Error",
                class_err: "alert-danger",
                class_icon: "oi oi-circle-x",
                tpl: "<div class=\"alert alert-dismissible bg-content\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button><span class=\"bg-icon\"></span>&nbsp;<span class=\"bg-msg\"></span></div>",
                delay: 5000
            }
        };

        var opts = $.extend(true, defaults, options);

        /*------输出消息------
        @_id 消息ID
        @_msg 消息内容
        */
        var outPut = function(_msg_selector, _group_selector, _msg, _class, _attach_class) {
            $(_msg_selector).empty(); //先清空提示框
            $(_msg_selector).append(_msg); //填充消息内容

            $(_msg_selector).removeClass(opts.class_name.success + " " + opts.class_name.err + " " + opts.class_name.loading); //移除可能样式
            $(_msg_selector).addClass(_class); //替换样式

            $(_msg_selector).show(); //显示消息

            if (typeof _group_selector != "undefined" && _group_selector.length > 0) {
                $(_group_selector).removeClass(opts.class_attach.success + " " + opts.class_attach.err); //移除可能样式
                $(_group_selector).addClass(_attach_class); //定义样式
            }
        };

        /*------取得长度（字节数）------
        @_str 需验证字符串

        返回字节数
        */
        var getLength = function(_str) {
            _numLenth = 0;
            if (_str) {
                for (_iii = 0; _iii < _str.length; _iii++) {
                    _chkCode = _str.charCodeAt(_iii);
                    if (_chkCode < 0x007f) {
                        _numLenth++;
                    } else if (_chkCode <= 0x07ff) {
                        _numLenth += 2;
                    } else if (_chkCode <= 0xffff) {
                        _numLenth += 3;
                    } else{
                        _numLenth += 4;
                    }
                }
            }
            return _numLenth;
        };

        /*------验证长度------
        @_str 需验证字符串
        @_len(min, max) 数组，(最小长度, 最大长度) 0 为不限制

        返回字符
        too_short 太短
        too_long 太长
        ok 正常
        */
        var verifyLeng = function(_str, _len) {
            if (_len.min > 0 && getLength(_str) < _len.min) {
                _status = "too_short"; //如果定义最小长度，且短于，则返回太短
            } else if (_len.max > 0 && getLength(_str) > _len.max) {
                _status = "too_long"; //如果定义最大长度，且长于，则返回太长
            } else {
                _status = "ok"; //返回正确
            }
            return _status;
        };

        /*------验证格式------
        @_str 需验证字符串
        @_format 格式，text 为任意
        */
        var verifyReg = function(_str, _format) {
            switch(_format) {
                case "date":
                    _reg = /^[0-9]{4}-(((0?[13578]|(10|12))-(0?[1-9]|[1-2][0-9]|3[0-1]))|(0?2-(0[1-9]|[1-2][0-9]))|((0?[469]|11)-(0[1-9]|[1-2][0-9]|30)))$/; //日期
                break;
                case "time":
                    _reg = /^(([1-9]{1})|([0-1][0-9])|([1-2][0-3])):([0-5][0-9])(:([0-5][0-9]))?$/;
                break;
                case "datetime": //日期时间
                    _reg = /^[0-9]{4}-(((0?[13578]|(10|12))-(0?[1-9]|[1-2][0-9]|3[0-1]))|(0?2-(0[1-9]|[1-2][0-9]))|((0?[469]|11)-(0[1-9]|[1-2][0-9]|30)))\s(([1-9]{1})|([0-1][0-9])|([1-2][0-3])):([0-5][0-9])(:([0-5][0-9]))?$/;
                break;
                case "int":
                    _reg = /^(\+|-)?\d*$/; //整数
                break;
                case "digit":
                    _reg = /^(\+|-)?\d*(\.\d+)*$/; //数值，可以包含小数点
                break;
                case "email":
                    _reg = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/; //Email
                break;
                case "url":
                    _reg = /^((http|ftp|https):\/\/)(([a-zA-Z0-9\._-]+\.[a-zA-Z]{2,6})|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,4})*(\/[a-zA-Z0-9\&%_\.\/-~-]*)?$/; //URL地址
                break;
                case "alphabetDigit":
                    _reg = /^[a-zA-Z\d-_]*$/; //数字英文字母
                break;
                case "strDigit":
                    _reg = /^[\u4e00-\u9fa5a-zA-Z\d-_]*$/; // /^[\\\u4e00-\\\u9fa5|\\\uf900-\\\ufa2d|\w]*$/" 字母中文数字下划线 /
                break;
                case "alias":
                    _reg = /^[a-zA-Z\d-_]*$/; // /^[\\\u4e00-\\\u9fa5|\\\uf900-\\\ufa2d|\w]*$/" 字母中文数字下划线
                break;
                default:
                    _reg = ""; //默认
                break;
            }
            if (_str && _format != "text") { //如果值不为空，且格式不为text则验证
                if (_reg.test(_str)) {
                    return true; //验证通过，返回正确
                } else {
                    return false; //验证失败，返回错误
                }
            } else {
                return true; //如果为text，直接返回正确
            }
        };

        /*------验证是否为字符串------
        @_str 需验证的字符串
        @_len(min, max) 数组，(最小长度, 最大长度) 0 为不限制
        @_format 格式
        */
        var isText = function(_str, _len, _format) {
            _status_leng = verifyLeng(_str, _len);
            if (_status_leng != "ok") {
                _status = _status_leng; //如验证长度出错，直接返回错误
            } else {
                if (verifyReg(_str, _format)) {
                    _status = "ok"; //格式验证成功，返回正确
                } else {
                    _status = "format_err"; //格式验证失败，返回错误
                }
            }
            return _status;
        };

        /*------验证是否相同------
        @_str 需验证的字符串
        @_str_target 需验证的目标字符串
        @_len(min, max) 数组，(最小长度, 最大长度) 0 为不限制
        */
        var isConfirm = function(_str, _str_target, _len) {
            _status_leng = verifyLeng(_str, _len);
            if (_status_leng != "ok") {
                _status = _status_leng; //如验证长度出错，直接返回错误
            } else {
                if (_str == _str_target) {
                    _status = "ok"; //验证成功，返回正确
                } else {
                    _status = "not_match"; //验证失败
                }
            }
            return _status;
        };

        /*------验证数字------
        @_num 需验证的个数
        @_len(min, max) 数组，(最小个数, 最大个数) 0 为不限制
        */
        var isDigit = function(_num, _len) {
            if (isNaN(_num)) {
                _status = "format_err"; //格式验证失败，返回错误
            } else {
                if (_len.min > 0 && _num < _len.min) {
                    _status = "too_small"; //如果定义最小数，且小于，则返回太小
                } else if (_len.max > 0 && _num > _len.max) {
                    _status = "too_big"; //如果定义最大数，且大于，则返回太大
                } else {
                    _status = "ok"; //返回正确
                }
            }
            return _status;
        };

        /*------验证个数------
        @_num 需验证的个数
        @_len(min, max) 数组，(最小个数, 最大个数) 0 为不限制
        */
        var isNum = function(_num, _len) {
            if (_len.min > 0 && _num < _len.min) {
                _status = "too_few"; //如果定义最小个数，且少于，则返回太少
            } else if (_len.max > 0 && _num > _len.max) {
                _status = "too_many"; //如果定义最大个数，且多于，则返回太多
            } else {
                _status = "ok"; //返回正确
            }
            return _status;
        };

        /*------ajax 验证------
        @_str 需验证的字符串
        @_len(min, max) 数组，(最小长度, 最大长度) 0 为不限制
        @_format 格式
        @_ajax(url, key) 数组，(URL, 关键词)
        */
        var isAjax = function(_str, _len, _validate, _ajax, _msg, _validate_selector) {
            switch (_ajax.type) {
                case "digit":
                    _status = isDigit(_str, _len); //验证字符串
                break;
                default:
                    _status = isText(_str, _len, _validate.format); //验证字符串
                break;
            }

            if (_status != "ok") {

                return _status;

            } else {

                _ajaxData = _ajax.key + "=" + _str + "&" + new Date().getTime() + "at" + Math.random();

                if (_ajax.attach) {
                    _ajaxData = _ajaxData + "&" + _ajax.attach;
                }
                if (_ajax.attach_selectors && _ajax.attach_keys) {
                    $.each(_ajax.attach_selectors, function(_index, _selector) {
                        _str_attachs    = $(_selector).val();
                        _ajaxData = _ajaxData + "&" + _ajax.attach_keys[_index] + "=" + _str_attachs;
                    });
                }

                $.ajax({
                    url: _ajax.url, //url
                    //async: false, //设置为同步
                    dataType: "json", //数据格式为json
                    data: _ajaxData,
                    beforeSend: function(){
                        outPut(_msg.selector, _validate.group, _msg.ajaxIng, opts.class_name.loading, opts.class_attach.err); //输出消息
                    },
                    error: function(_result){
                        outPut(_msg.selector, _validate.group, _msg.ajax_err + " status: " + _result.status, opts.class_name.err, opts.class_attach.err); //输出消息
                    },
                    success: function(_result) { //读取返回结果
                        switch (_result.msg) {
                            case "ok": //匹配验证错误
                                outPut(_msg.selector,  _validate.group, "", opts.class_name.success, opts.class_attach.success); //输出消息
                            break;

                            default: //数据库验证错误
                                outPut(_msg.selector,  _validate.group, _result.msg, opts.class_name.err, opts.class_attach.err); //输出消息
                            break;
                        }
                        _status = _result.msg;
                    }
                });

                return _status;
            }
        };

        var verifyStr = function(_validate_obj) {
            _set_data   = fileds[_validate_obj]; //获取验证设置
            _len        = _set_data.len; //长度
            _validate   = _set_data.validate; //格式
            _msg        = _set_data.msg; //消息
            if (typeof _validate.selector == "undefined" || _validate.selector == null) { //如果 validate 对象没有定义选择器，则默认将一级对象名作为 id 选择器
                _validate_selector = "#" + _validate_obj;
            } else {
                _validate_selector = _validate.selector; //待验证选择器
            }

            if (typeof _validate.group == "undefined" || _validate.group == null) { //如果 validate 对象没有定义 group，则默认将 validate 的选择器作为 group 的选择器
                _validate.group = _validate_selector;
            }

            if (typeof _msg.selector == "undefined" || _msg.selector == null) { //如果 validate 对象没有定义 group，则默认将 validate 的选择器作为 group 的选择器
                _msg.selector = "#msg_" + _validate_obj;
            }

            switch (_validate.type) {
                case "digit":
                    _str    = $(_validate_selector).val(); //获取表单值
                    _status = isDigit(_str, _len); //验证字符串
                break;
                case "radio":
                case "checkbox":
                    _num    = $(_validate_selector + ":checked").length; //获取表单选中数
                    _status = isNum(_num, _len); //验证个数
                break;
                case "select":
                    if (_len.min > 1) { //如最少大于 1 为多选，则验证个数
                        _num    = $(_validate_selector + " :selected").length; //获取表单选中数
                        _status = isNum(_num, _len); //验证个数
                    } else { //单选则验证值
                        _str    = $(_validate_selector).val(); //获取表单值
                        _status = isText(_str, _len, "text"); //验证字符串
                        if (_status == "too_short") {
                            _status = "too_few";
                        }
                    }
                break;
                case "ajax":
                    _ajax   = _set_data.ajax; //ajax
                    _str    = $(_validate_selector).val(); //获取表单值
                    _status = isAjax(_str, _len, _validate, _ajax, _msg, _validate_selector); //ajax验证
                break;
                case "confirm":
                    _str_target = $(_validate.target).val(); //获取表单值
                    _str        = $(_validate_selector).val(); //获取表单值
                    _status     = isConfirm(_str, _str_target, _len); //验证字符串
                break;
                default:
                    _str    = $(_validate_selector).val(); //获取表单值
                    _status = isText(_str, _len, _validate.format); //验证字符串
                break;
            }

            switch (_status) {
                case "ok":
                    outPut(_msg.selector, _validate.group, "", opts.class_name.success, opts.class_attach.success); //输出消息
                    _result = true;
                break;
                default:
                    outPut(_msg.selector,  _validate.group, _msg[_status], opts.class_name.err, opts.class_attach.err); //输出消息
                    _result = false;
                break;
            }
            return _result;
        };

        el.verify = function(){
            _err_count = 0;
            $(thisForm).find("[data-validate]:visible").each(function(){
                _validate_obj = $(this).data("validate");
                if (_validate_obj.length < 1) {
                    _validate_obj = $(this).attr("id");
                }
                if (!verifyStr(_validate_obj)) {
                    _err_count++;
                }
            });

            var _selector_global = opts.msg_global.selector;
            var _obj_global      = $(_selector_global.parent);

            _obj_global.html(opts.msg_global.tpl);

            $(_selector_global.parent + " " + _selector_global.content).addClass(opts.msg_global.class_err);
            $(_selector_global.parent + " " + _selector_global.icon).addClass(opts.msg_global.class_icon);
            $(_selector_global.parent + " " + _selector_global.msg).html(opts.msg_global.msg);

            if (_err_count > 0) {
                _obj_global.slideDown();
                return false;
            } else {
                _obj_global.slideUp();
                return true;
            }
        };

        $(thisForm).find("[data-validate]:visible").change(function(){
            _validate_obj = $(this).data("validate");
            if (_validate_obj.length < 1) {
                _validate_obj = $(this).attr("id");
            }
            verifyStr(_validate_obj);
        });

        return this;
    };
})(jQuery);