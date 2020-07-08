/*
v3.0.0 jQuery baigoValidate plugin 表单验证插件
(c) 2017 baigo studio - http://www.baigo.net/jquery/baigovalidate.html
License: http://www.opensource.org/licenses/mit-license.php
*/
;(function($) {
    $.fn.baigoValidate = function(options) {
        'use strict';
        if (this.length < 1) {
            return this;
        }

        var obj_form    = $(this);
        var form_data   = {};
        var message     = {};
        var err_count   = 0;

        var alias = {
            '>': 'gt',
            '>=': 'egt',
            '<': 'lt',
            '<=': 'elt',
            '=': 'eq',
            'same': 'eq',
            '!=': 'neq',
            '<>': 'neq'
        };

        var opts_default = {
            timeout: 30000,
            delimiter: ' - ',
            type_msg: {
                require: '{:attr} require',
                confirm: '{:attr} out of accord with {:confirm}',
                different: '{:attr} cannot be same with {:different}',
                accepted: '{:attr} must be yes, on or 1',
                'in': '{:attr} must be in {:rule}',
                not_in: '{:attr} be notin {:rule}',
                between: '{:attr} must between {:rule}',
                not_between: '{:attr} cannot between {:rule}',
                length: 'Size of {:attr} must be {:rule}',
                min: 'Min size of {:attr} must be {:rule}',
                max: 'Max size of {:attr} must be {:rule}',
                after: '{:attr} cannot be less than {:rule}',
                before: '{:attr} cannot exceed {:rule}',
                expire: '{:attr} not within {:rule}',
                egt: '{:attr} must greater than or equal {:rule}',
                gt: '{:attr} must greater than {:rule}',
                elt: '{:attr} must less than or equal {:rule}',
                lt: '{:attr} must less than {:rule}',
                eq: '{:attr} must equal {:rule}',
                neq: '{:attr} cannot be same with {:rule}',
                regex: '{:attr} not conform to the rules',
                format: '{:attr} not conform format of {:rule}',
                date_format: '{:attr} must be date format of {:rule}',
                time_format: '{:attr} must be time format of {:rule}',
                date_time_format: '{:attr} must be datetime format of {:rule}',
                checkbox: 'Count of {:attr} must be {:rule}',
                radio: 'Count of {:attr} must be {:rule}',
                select: 'Count of {:attr} must be {:rule}',
                ajax: ''
            },
            format_msg: {
                number: '{:attr} must be numeric',
                'int': '{:attr} must be integer',
                'float': '{:attr} must be float',
                bool: '{:attr} must be bool',
                email: '{:attr} not a valid email address',
                date: '{:attr} not a valid date',
                time: '{:attr} not a valid time',
                date_time: '{:attr} not a valid datetime',
                alpha: '{:attr} must be alpha',
                alpha_number: '{:attr} must be alpha-numeric',
                alpha_dash: '{:attr} must be alpha-numeric, dash, underscore',
                chs: '{:attr} must be chinese',
                chs_alpha: '{:attr} must be chinese or alpha',
                chs_alpha_number: '{:attr} must be chinese, alpha-numeric',
                chs_dash: '{:attr} must be chinese, alpha-numeric, underscore, dash',
                url: '{:attr} not a valid url',
                ip: '{:attr} not a valid ip',
                ip_v4: '{:attr} not a valid ip',
                ip_v6: '{:attr} not a valid ip'
            },
            field_selector: {
                prefix_msg: '#msg_',
                prefix_group: '#group_'
            },
            class_name: {
                input: {
                    success: 'is-valid',
                    err: 'is-invalid'
                },
                msg: {
                    success: 'valid-feedback',
                    err: 'invalid-feedback',
                    loading: 'text-info'
                },
                attach: {
                    success: 'text-success',
                    err: 'text-danger'
                }
            },
            msg: {
                loading: 'Loading',
                ajax_err: 'Error'
            },
            box: {
                selector: {
                    box: '.bg-validate-box',
                    content: '.bg-content',
                    icon: '.bg-icon',
                    msg: '.bg-msg'
                },
                class_name: 'alert-danger',
                class_icon: 'fas fa-times-circle',
                msg: 'Input error',
                tpl: '<div class="alert alert-dismissible bg-content"><button type="button" class="close" data-dismiss="alert">&times;</button><span class="bg-icon"></span> <span class="bg-msg"></span></div>',
                delay: 5000
            },
            selector_types: {},
            rules: {},
            attr_names: {}
        };

        var opts = $.extend(true, opts_default, options);

        var process = {
            formatDate: function (fmt, time) { //author: meizz
                var _obj_date = new Date();
                _obj_date.setTime(time);

                var o = {
                    'M+': _obj_date.getMonth() + 1, //月份
                    'd+': _obj_date.getDate(), //日
                    'H+': _obj_date.getHours(), //小时
                    'm+': _obj_date.getMinutes(), //分
                    's+': _obj_date.getSeconds(), //秒
                    'q+': Math.floor((_obj_date.getMonth() + 3) / 3), //季度
                    'S': _obj_date.getMilliseconds() //毫秒
                };

                if (/(y+)/.test(fmt)) {
                    fmt = fmt.replace(RegExp.$1, (_obj_date.getFullYear() + '').substr(4 - RegExp.$1.length));
                }

                for (var k in o) {
                    if (new RegExp('(' + k + ')').test(fmt)) {
                        fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (('00' + o[k]).substr(('' + o[k]).length)));
                    }
                }

                return fmt;
            },
            parseRule: function(arr_rule){
                //console.log(arr_rule);

                var _arr_ruleReturn = {};

                $.each(arr_rule, function(key, value){
                    //console.log(key);

                    if (typeof alias[key] != 'undefined') {
                        key = alias[key];
                    }

                    if (typeof opts.type_msg[key] != 'undefined') {
                        //console.log(value);

                        switch (key) {
                            case 'require':
                                if (value === true || value === 'true') {
                                    _arr_ruleReturn[key] = {
                                        type: key,
                                        rule: value
                                    };
                                }
                            break;

                            case 'length':
                            case 'between':
                            case 'not_between':
                            case 'expire':
                                if (value.indexOf(',') > 0) {
                                    _arr_ruleReturn[key] = {
                                        type: key,
                                        rule: value
                                    };
                                }
                            break;

                            case 'min':
                            case 'max':
                            case 'in':
                            case 'not_in':
                            case 'egt':
                            case 'gt':
                            case 'elt':
                            case 'lt':
                            case 'eq':
                            case 'neq':
                            case 'before':
                            case 'after':
                            case 'regex':
                                if (value != null) {
                                    _arr_ruleReturn[key] = {
                                        type: key,
                                        rule: value
                                    };
                                }
                            break;

                            case 'date_format':
                            case 'time_format':
                            case 'date_time_format':
                                var _str_format = process.getDateRule(key);

                                if (value != null) {
                                    _str_format = value;
                                }

                                _arr_ruleReturn[key] = {
                                    type: key,
                                    rule: _str_format
                                };
                            break;

                            case 'format':
                                //console.log(opts.format_msg[value]);
                                if (typeof opts.format_msg[value] != 'undefined') {
                                    _arr_ruleReturn[key] = {
                                        type: key,
                                        rule: value
                                    };

                                    //console.log(value);
                                }
                            break;

                            case 'ajax':
                                if (typeof value.url != 'undefined') {
                                    _arr_ruleReturn[key] = {
                                        type: key,
                                        rule: value
                                    };

                                    //console.log(value);
                                }
                            break;

                            default:
                                var _rule;

                                if (typeof value == 'string') {
                                    _rule = value;
                                } else {
                                    _rule = null;
                                }

                                _arr_ruleReturn[key] = {
                                    type: key,
                                    rule: _rule
                                };

                                //console.log(key);
                                //console.log(_arr_ruleReturn[key]);
                            break;
                        }

                        //console.log(key);
                    }
                });

                //console.log(_arr_ruleReturn);

                return _arr_ruleReturn;
            },
            getDateRule: function(type){
                var _str_format = '';

                switch (type) {
                    case 'date_format':
                        _str_format = 'yyyy-MM-dd';
                    break;

                    case 'time_format':
                        _str_format = 'H:i:s';
                    break;

                    case 'date_time_format':
                        _str_format = 'yyyy-MM-dd H:i:s';
                    break;
                }

                return _str_format;
            },
            check: function(str_value, arr_rule, str_key) {
                //var err_count = 0;

                //console.log(arr_rule);

                if (typeof arr_rule == 'object') {
                    var _arr_rule  = process.parseRule(arr_rule);
                    var _err_count = 0;

                    $.each(_arr_rule, function(_key_item, _value_item){
                        if (typeof _value_item.type != 'undefined' && typeof _value_item.rule != 'undefined') {
                            if (!process.checkItem(str_value, _value_item, str_key)) {
                                err_count++;
                                _err_count++;
                            }
                        }
                    });
                }

                //console.log(err_count);

                if (_err_count > 0) {
                    //console.log(str_key);
                    //console.log(_str_msg);

                    if (typeof message[str_key] != 'undefined') {
                        var _str_msg = message[str_key];
                    }

                    process.output(str_key, _str_msg, opts.class_name.input.err, opts.class_name.msg.err, opts.class_name.attach.err);
                } else {
                    process.output(str_key, '', opts.class_name.input.success, opts.class_name.msg.success, opts.class_name.attach.success);
                }

                //return err_count;
            },
            checkItem: function(str_value, arr_rule, str_key) {
                var _bool_return = false;
                var _obj_reg;

                switch (arr_rule.type) {
                    case 'require':
                        _bool_return = el.min(str_value, 1);
                    break;

                    case 'length':
                        _bool_return = el.leng(str_value, arr_rule.rule);
                    break;

                    case 'between':
                    case 'not_between':
                    case 'expire':
                    case 'in':
                    case 'not_in':
                    case 'egt':
                    case 'gt':
                    case 'elt':
                    case 'lt':
                    case 'eq':
                    case 'neq':
                    case 'before':
                    case 'after':
                    case 'regex':
                    case 'min':
                    case 'max':
                        _bool_return = el[process.toHump(arr_rule.type)](str_value, arr_rule.rule);
                    break;

                    case 'bool':
                        var _array   = [true, 'true', 0, 1, '0', '1'];
                        _bool_return = process.inArray(str_value, _array);
                    break;

                    case 'accepted':
                        var _array   = ['1', 'on', 'yes'];
                        _bool_return = process.inArray(str_value, _array);
                    break;

                    case 'date_format':
                    case 'time_format':
                    case 'date_time_format':
                        _bool_return = el.dateFormat(str_value, arr_rule.rule);
                    break;

                    case 'confirm':
                        if (typeof arr_rule.rule == 'string') {
                            arr_rule.rule = arr_rule.rule;
                        } else {
                            arr_rule.rule = str_key.replace('_confirm', '');
                        }

                        //console.log(arr_rule.rule);

                        _bool_return = el.confirm(str_value, arr_rule.rule);
                    break;


                    case 'different':
                        if (typeof arr_rule.rule == 'string') {
                            arr_rule.rule = arr_rule.rule;
                        } else {
                            arr_rule.rule = str_key.replace('_different', '');
                        }

                        _bool_return = el.confirm(str_value, arr_rule.rule, true);
                    break;

                    case 'ajax':
                        el.ajaxCheck(str_value, str_key, arr_rule.rule);
                        _bool_return = true;
                    break;

                    case 'checkbox':
                    case 'radio':
                        //console.log(arr_rule);
                        _bool_return = el.checkForm(str_key, arr_rule.rule);
                    break;

                    case 'select':
                        _bool_return = el.selectForm(str_key, arr_rule.rule);
                    break;

                    case 'format':
                        switch (arr_rule.rule) {
                            case 'date':
                            case 'time':
                            case 'date_time':
                                var _value = str_value.replace(/-/g, '/');

                                if (!isNaN(Date.parse(_value))) {
                                    _bool_return = true;
                                }
                            break;

                            case 'alpha':
                                _obj_reg = /^[A-Za-z]+$/;
                                _bool_return = el.regex(str_value, _obj_reg);
                            break;

                            case 'alpha_number':
                                _obj_reg = /^[A-Za-z0-9]+$/;
                                _bool_return = el.regex(str_value, _obj_reg);
                            break;

                            case 'alpha_dash':
                                // 只允许字母、数字和下划线 破折号
                                _obj_reg = /^[A-Za-z0-9_\-]+$/;
                                _bool_return = el.regex(str_value, _obj_reg);
                            break;

                            case 'chs':
                                // 只允许汉字
                                _obj_reg = /^[\u4e00-\u9fa5]+$/;
                                _bool_return = el.regex(str_value, _obj_reg);
                            break;

                            case 'chs_alpha':
                                // 只允许汉字、字母
                                _obj_reg = /^[\u4e00-\u9fa5A-Za-z]+$/;
                                _bool_return = el.regex(str_value, _obj_reg);
                            break;

                            case 'chs_alpha_number':
                                // 只允许汉字、字母和数字
                                _obj_reg = /^[\u4e00-\u9fa5A-Za-z0-9]+$/;
                                _bool_return = el.regex(str_value, _obj_reg);
                            break;

                            case 'chs_dash':
                                // 只允许汉字、字母、数字和下划线_及破折号-
                                _obj_reg = /^[\u4e00-\u9fa5A-Za-z0-9\_\-]+$/;
                                _bool_return = el.regex(str_value, _obj_reg);
                            break;

                            case 'number':
                                _bool_return = process.isNumber(str_value);
                            break;

                            case 'int':
                                _obj_reg = /^[\+\-]?[0-9]+$/;
                                _bool_return = el.regex(str_value, _obj_reg);
                            break;

                            case 'float':
                                _obj_reg = /^[\+\-]?[0-9]+\.[0-9]+$/;
                                _bool_return = el.regex(str_value, _obj_reg);
                            break;

                            case 'email':
                                _obj_reg = /^([A-Za-z0-9][\.\_\-]?)+\@([A-Za-z0-9][\.\-]?)+[A-Za-z]{2,4}$/;
                                _bool_return = el.regex(str_value, _obj_reg);
                            break;

                            case 'ip':
                            case 'ip_v4':
                                // 是否为IP地址
                                _obj_reg = /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
                                _bool_return = el.regex(str_value, _obj_reg);
                            break;

                            case 'ip_v6':
                                // 是否为IPV6地址
                                _obj_reg = /^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$/;
                                _bool_return = el.regex(str_value, _obj_reg);
                            break;

                            case 'url':
                                // 是否为一个URL地址
                                _obj_reg = /^((https?|ftp|file):)?\/\/[\-A-Za-z0-9+&@#\/%?\=~\_|!:,\.;]+[\-A-Za-z0-9+&@#\/%\=~\_|]$/;
                                _bool_return = el.regex(str_value, _obj_reg);
                            break;
                        }
                    break;
                }

                //console.log(str_key);
                //console.log(_bool_return);

                if (!_bool_return) {
                    var _str_msg        = 'unknown';
                    var _str_field      = str_key;
                    var _str_confirm    = str_key;
                    var _str_different  = str_key;

                    //console.log(arr_rule.type);

                    if (arr_rule.type == 'format') {
                        if (typeof opts.format_msg[arr_rule.rule] != 'undefined') {
                            _str_msg = opts.format_msg[arr_rule.rule];
                        }
                    } else {
                        if (typeof opts.type_msg[arr_rule.type] != 'undefined') {
                            _str_msg = opts.type_msg[arr_rule.type];
                        }
                    }

                    if (typeof opts.attr_names[str_key] != 'undefined') {
                        _str_field = opts.attr_names[str_key];
                    }

                    if (typeof opts.attr_names[arr_rule.rule] != 'undefined') {
                        _str_confirm   = opts.attr_names[arr_rule.rule];
                        _str_different = opts.attr_names[arr_rule.rule];
                    }

                    switch (arr_rule.type) {
                        case 'length':
                        case 'between':
                        case 'not_between':
                            arr_rule.rule = arr_rule.rule.replace(',', opts.delimiter);
                        break;
                    }

                    _str_msg = _str_msg.replace('{:attr}', _str_field);
                    _str_msg = _str_msg.replace('{:rule}', arr_rule.rule);
                    _str_msg = _str_msg.replace('{:confirm}', _str_confirm);
                    _str_msg = _str_msg.replace('{:different}', _str_different);

                    message[str_key] = _str_msg;

                    //console.log(str_key);
                    //console.log(_str_msg);
                }

                return _bool_return;
            },
            getLeng: function(str_value) {
                var _numLenth  = 0;
                if (typeof str_value != 'undefined') {
                    for (var _iii = 0; _iii < str_value.length; _iii++) {
                        var _chkCode = str_value.charCodeAt(_iii);
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
            },
            isNumber: function(str_value) {
                if (str_value.length < 1) {
                    return true;
                }

                str_value = Number(str_value);

                //console.log(str_value);

                if (typeof str_value == 'number' && !isNaN(str_value)) {
                    return true;
                } else {
                    return false;
                }
            },
            inArray: function(str_value, arr_rule) {
                if (str_value.length < 1) {
                    return true;
                }

                if (arr_rule.rule.indexOf(str_value) >= 0) {
                    return true;
                } else {
                    return false;
                }
            },
            output: function(key, msg, class_input, class_msg, class_attach) {
                //console.log(_type);

                var _selector   = process.getSelector(key);
                var _tag        = $(_selector).prop('tagName');
                var _type       = $(_selector).attr('type');

                if (typeof _tag != 'undefined') {
                    _tag = _tag.toLowerCase();
                }

                if (typeof _type != 'undefined') {
                    _type = _type.toLowerCase();
                }

                $(_selector).removeClass(opts.class_name.input.success + ' ' + opts.class_name.input.err);

                $(_selector + ':not(select,:radio:not(:checked),:checkbox:not(:checked))').addClass(class_input);

                $(opts.field_selector.prefix_msg + key).text(msg); //填充消息

                $(opts.field_selector.prefix_msg + key).removeClass(opts.class_name.msg.success + ' ' + opts.class_name.msg.err + ' ' + opts.class_name.msg.loading);
                $(opts.field_selector.prefix_msg + key).addClass(class_msg);

                $(opts.field_selector.prefix_msg + key).show(); //显示消息

                $(opts.field_selector.prefix_group + key).removeClass(opts.class_name.attach.success + ' ' + opts.class_name.attach.err);
                $(opts.field_selector.prefix_group + key).addClass(class_attach);
            },
            getSelector: function(key) {
                var _selector = '';
                var _type;

                if (typeof opts.selector_types[key] == 'undefined') {
                    _type = 'id';
                } else {
                    _type = opts.selector_types[key];
                }

                //console.log(selector_type);

                switch (_type) {
                    case 'validate':
                        _selector = '[data-validate=' + key + ']';
                    break;

                    case 'name':
                        _selector = '[name=' + key + ']';
                    break;

                    case 'class':
                        _selector = '.' + key;
                    break;

                    default:
                        _selector = '#' + key;
                    break;
                }

                return _selector;
            },
            getData: function() {
                form_data = {};
                err_count = 0;
                var _arr_data = {};

                var _arr_serialize = obj_form.serializeArray();

                $.each(_arr_serialize, function(i, field){
                    //console.log(field.value);
                    _arr_data[field.name] = field.value;
                });

                //console.log(_arr_data);

                $.each(opts.rules, function(key, value){
                    if (_arr_data[key] != 'undefined') {
                        form_data[key] = _arr_data[key];
                    } else {
                        var _selector  = process.getSelector(key);

                        form_data[key] = $(_selector).val();
                    }
                });

                //console.log(form_data);
            },
            toHump: function(str) { // 下划线转换驼峰
                return str.replace(/\_(\w)/g, function(all, letter){
                    return letter.toUpperCase();
                });
            },
            toLine: function(str) { // 驼峰转换下划线
                return str.replace(/([A-Z])/g, '_$1').toLowerCase();
            }

        };

        var el = {
            verify: function(field){
                process.getData();
                var _status = true;

                //console.log(form_data);

                if (typeof field == 'undefined') {
                    $.each(opts.rules, function(key, value){
                        if (typeof value != 'undefined') {
                            if (typeof form_data[key] == 'undefined') {
                                form_data[key] = '';
                            }
                            process.check(form_data[key], value, key);
                        }
                    });

                    if (err_count > 0) {
                        _status = false;
                    }

                    var _obj_selector   = opts.box.selector.box;
                    var _obj_box        = $(_obj_selector);

                    if (_obj_box.length > 0) {
                        _obj_box.hide();
                        _obj_box.empty();

                        if (err_count > 0) {
                            _obj_box.html(opts.box.tpl);

                            $(_obj_selector + ' ' + opts.box.selector.content).addClass(opts.box.class_name);
                            $(_obj_selector + ' ' + opts.box.selector.icon).addClass(opts.box.class_icon);
                            $(_obj_selector + ' ' + opts.box.selector.msg).html(opts.box.msg);
                            _obj_box.slideDown(function(){
                                setTimeout(function(){
                                    _obj_box.slideUp();
                                }, opts.box.delay);
                            });
                        } else {
                            _obj_box.slideUp();
                        }
                    }
                } else {
                    if (typeof opts.rules[field] != 'undefined') {
                        process.check(form_data[field], opts.rules[field], field);
                    }
                }

                //console.log(err_count);

                return _status;
            },
            setRules: function(rules) {
                opts.rules = rules;
            },
            leng: function(value, rule){
                var _status   = true;
                var _arr_rule = [];

                if (rule.indexOf(',') > 0) {
                    _arr_rule = rule.split(',');
                } else {
                    _arr_rule[0] = rule;
                    _arr_rule[1] = 0;
                }

                //console.log(process.getLeng(value));

                var _min = _arr_rule[0];
                var _max = _arr_rule[1];

                if (_min > 0 && process.getLeng(value) < _min) {
                    _status = false; //如果定义最小长度，且短于，则返回太短
                } else if (_max > 0 && process.getLeng(value) > _max) {
                    _status = false; //如果定义最大长度，且长于，则返回太长
                }

                return _status;
            },
            checkForm: function(key, rule){
                var _selector = process.getSelector(key);

                var _status   = true;
                var _arr_rule = [];

                if (rule.indexOf(',') > 0) {
                    _arr_rule = rule.split(',');
                } else {
                    _arr_rule[0] = rule;
                    _arr_rule[1] = 0;
                }

                //console.log(value.length);

                var _min = _arr_rule[0];
                var _max = _arr_rule[1];

                if (_min > 0 && $(_selector + ':checked').length < _min) {
                    _status = false; //如果定义最小长度，且短于，则返回太短
                } else if (_max > 0 && $(_selector + ':checked').length > _max) {
                    _status = false; //如果定义最大长度，且长于，则返回太长
                }

                return _status;
            },
            selectForm: function(key, rule){
                var _selector = process.getSelector(str_key);

                var _status   = true;
                var _arr_rule = [];

                if (rule.indexOf(',') > 0) {
                    _arr_rule = rule.split(',');
                } else {
                    _arr_rule[0] = rule;
                    _arr_rule[1] = 0;
                }

                //console.log(value.length);

                var _min = _arr_rule[0];
                var _max = _arr_rule[1];

                if (_min > 0 && $(_selector + ' option:selected').length < _min) {
                    _status = false; //如果定义最小长度，且短于，则返回太短
                } else if (_max > 0 && $(_selector + ' option:selected').length > _max) {
                    _status = false; //如果定义最大长度，且长于，则返回太长
                }

                return _status;
            },
            min: function(value, rule){
                var _status   = true;

                if (rule > 0 && process.getLeng(value) < rule) {
                    _status = false; //如果定义最小长度，且短于，则返回太短
                }

                return _status;
            },
            max: function(value, rule){
                var _status   = true;

                if (rule > 0 && process.getLeng(value) > rule) {
                    _status = false; //如果定义最大长度，且长于，则返回太长
                }

                return _status;
            },
            dateFormat: function(value, rule){
                if (value.length < 1) {
                    return true;
                }

                var _value = value.replace(/-/g, '/');
                var _time  = Date.parse(value);
                var _str   = process.formatDate(_time);

                //console.log('jisuan: ' + _str);

                return _str == value;
            },
            regex: function(value, rule) {
                if (value.length < 1) {
                    return true;
                }

                var _obj_rule;

                if (typeof rule == 'object') {
                    _obj_rule = rule;
                } else {
                    if (rule.indexOf('^') < 0) {
                        rule = '^' + rule;
                    }
                    if (rule.indexOf('$') < 0) {
                        rule = rule + '$';
                    }

                    //console.log(rule);

                    _obj_rule = new RegExp(rule);
                }

                var _obj_keys = Object.keys(_obj_rule);

                //console.log(_obj_rule);

                return _obj_rule.test(value);
            },
            expire: function(value, rule) {
                if (value.length < 1) {
                    return true;
                }

                var _arr_rule = rule.split(',');
                var _obj_date = new Date();
                var _now      = _obj_date.getTime()

                var start   = _arr_rule[0];
                var end     = _arr_rule[1];

                if (process.isNumber(start)) {
                    start = Date.parse(start);
                }

                if (process.isNumber(end)) {
                    end = Date.parse(end);
                }

                return _now >= start && _now <= end;
            },
            after: function(value, rule) {
                if (value.length < 1) {
                    return true;
                }

                if (process.isNumber(value)) {
                    value = Date.parse(value);
                }

                if (process.isNumber(rule)) {
                    rule = Date.parse(rule);
                }

                return value >= rule;
            },
            before: function(value, rule) {
                if (value.length < 1) {
                    return true;
                }

                if (process.isNumber(value)) {
                    value = Date.parse(value);
                }

                if (process.isNumber(rule)) {
                    rule = Date.parse(rule);
                }

                return value <= rule;
            } ,
            'in': function(value, rule) {
                if (value.length < 1) {
                    return true;
                }

                var _arr_rule = [];

                if (rule.indexOf(',') > 0) {
                    _arr_rule = rule.split(',');
                } else {
                    _arr_rule[0] = rule;
                }

                return process.inArray(value, _arr_rule);
            },
            notIn: function(value, rule) {
                if (value.length < 1) {
                    return true;
                }

                var _arr_rule = [];

                if (rule.indexOf(',') > 0) {
                    _arr_rule = rule.split(',');
                } else {
                    _arr_rule[0] = rule;
                }

                return !process.inArray(value, _arr_rule);
            },
            between: function(value, rule) {
                if (value.length < 1) {
                    return true;
                }

                var _arr_rule = rule.split(',');

                var min   = Number(_arr_rule[0]);
                var max   = Number(_arr_rule[1]);
                value = Number(value);

                return value >= min && value <= max;
            },
            notBetween: function(value, rule) {
                if (value.length < 1) {
                    return true;
                }

                var _arr_rule = rule.split(',');

                var min   = Number(_arr_rule[0]);
                var max   = Number(_arr_rule[1]);
                value = Number(value);

                return value < min || value > max;
            },
            egt: function(value, rule) {
                if (value.length < 1) {
                    return true;
                }

                value = Number(value);
                rule  = Number(rule);

                return value >= rule;
            },
            gt: function(value, rule) {
                if (value.length < 1) {
                    return true;
                }

                value = Number(value);
                rule  = Number(rule);

                return value > rule;
            },
            elt: function(value, rule) {
                if (value.length < 1) {
                    return true;
                }

                value = Number(value);
                rule  = Number(rule);

                return value <= rule;
            },
            lt: function(value, rule) {
                if (value.length < 1) {
                    return true;
                }

                value = Number(value);
                rule  = Number(rule);

                return value < rule;
            },
            eq: function(value, rule) {
                if (value.length < 1) {
                    return true;
                }

                return value == rule;
            },
            neq: function(value, rule) {
                if (value.length < 1) {
                    return true;
                }

                return value != rule;
            },
            getMessage: function(){
                return message;
            },
            confirm: function(value, rule, is_different){
                if (typeof is_different != 'undefined' && is_different === true) {
                    return form_data[rule] != value;
                } else {
                    return form_data[rule] == value;
                }
            },
            ajaxCheck: function(value, key, rule){
                //console.log(error[key]);

                if (value.length < 1) {
                    return;
                }

                //console.log(err_count);

                if (err_count > 0) {
                    return;
                }

                var _str_key;

                if (typeof rule.key != 'undefined') {
                    _str_key = rule.key;
                } else {
                    _str_key = key;
                }

                var _ajaxData = _str_key + '=' + value;

                if (typeof rule.attach != 'undefined') {
                    if (typeof rule.attach.selectors != 'undefined' && typeof rule.attach.keys != 'undefined') {
                        $.each(rule.attach.selectors, function(index, _selector) {
                            if (typeof rule.attach.keys[index] != 'undefined') {
                                _ajaxData += '&' + rule.attach.keys[index] + '=' + $(_selector).val();
                            }
                        });
                    }
                }

                _ajaxData += '&' + new Date().getTime() + 'at' + Math.random();

                $.ajax({
                    url: rule.url, //url
                    //async: false, //设置为同步
                    dataType: 'json', //数据格式为json
                    data: _ajaxData,
                    timeout: opts.timeout,
                    beforeSend: function(){
                        process.output(key, opts.msg.loading, '', opts.class_name.msg.loading);
                    },
                    error: function(result){
                        //console.log(result);
                        process.output(key, opts.msg.ajax_err + '! ' + result.status + ' ' + result.statusText, opts.class_name.input.err, opts.class_name.msg.err, opts.class_name.attach.err);
                    },
                    success: function(result) { //读取返回结果
                        //console.log(result);
                        if (typeof result.error != 'undefined') {
                            process.output(key, result.error, opts.class_name.input.err, opts.class_name.msg.err, opts.class_name.attach.err);
                        } else if (typeof result.msg != 'undefined') {
                            process.output(key, result.msg, opts.class_name.input.success, opts.class_name.msg.success, opts.class_name.attach.success);
                        }
                    }
                });
            }
        };

        obj_form.find('*').change(function(){
            var _input_key  = '';
            var _input_tag  = $(this).prop('tagName');

            if (typeof _input_tag != 'undefined') {
                _input_tag = _input_tag.toLowerCase();
            } else {
                _input_tag = 'input';
            }

            var _validate   = $(this).data('validate');
            var _id         = $(this).attr('id');
            var _name       = $(this).attr('name');
            var _class      = $(this).attr('class');

            //console.log(_input_tag);

            switch (_input_tag) {
                case 'select':
                case 'textarea':
                    if (typeof _validate != 'undefined' && _validate.length > 0) {
                        _input_key   = _validate;
                    } else if (typeof _id != 'undefined' && _id.length > 0) {
                        _input_key   = _id;
                    } else if (typeof _name != 'undefined' && _name.length > 0) {
                        _input_key   = _name;
                    }
                break;

                default:
                    var _input_type = $(this).attr('type');

                    if (typeof _input_type == 'undefined') {
                        _input_type = 'text';
                    }

                    _input_type = _input_type.toLowerCase();

                    //console.log(_input_type);

                    switch (_input_type) {
                        case 'checkbox':
                        case 'radio':
                            if (typeof _validate != 'undefined' && _validate.length > 0) {
                                _input_key   = _validate;
                            } else if (typeof _name != 'undefined' && _name.length > 0) {
                                _input_key   = _name;
                            } else if (typeof _class != 'undefined' && _class.length > 0) {
                                _input_key   = _class;
                            }
                        break;

                        default:
                            //console.log(_name);

                            if (typeof _validate != 'undefined' && _validate.length > 0) {
                                _input_key   = _validate;
                            } else if (typeof _id != 'undefined' && _id.length > 0) {
                                _input_key   = _id;
                            } else if (typeof _name != 'undefined' && _name.length > 0) {
                                _input_key   = _name;
                            }
                        break;
                    }
                break;
            }

            //console.log(_input_key);

            el.verify(_input_key);
        });

        return el;
    };
})(jQuery);