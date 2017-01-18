/*
v2.0 jQuery baigoSubmit plugin 表单提交插件
(c) 2013 baigo studio - http://www.baigo.net/
License: http://www.opensource.org/licenses/mit-license.php
*/
(function($){
    $.fn.baigoSubmit = function(options) {
        "use strict";
        if (this.length < 1) {
            return this;
        }

        if (this.length > 1){
            this.each(function(){
                $(this).baigoSubmit(options);
            });
            return this;
        }

        var thisForm    = $(this); //定义表单对象
        var el          = this;
        var _str_conn   = "?";

        var defaults = {
            class_content: {
                submitting: "alert-info",
                err: "alert-danger",
                success: "alert-success",
            },
            class_icon: {
                submitting: "glyphicon glyphicon-refresh bg-spin",
                err: "glyphicon glyphicon-remove-sign",
                success: "glyphicon glyphicon-ok-sign"
            },
            msg_text: {
                submitting: "Submitting ...",
                err: "Error"
            },
            selector: {
                content: ".bg-content",
                icon: ".bg-icon",
                msg: ".bg-msg",
                submit_btn: ".bg-submit",
                empty_input: ":password"
            },
            confirm: {
                selector: "",
                val: "",
                msg: ""
            },
            box: {
                selector: ".bg-submit-box",
                tpl: "<div class=\"alert alert-dismissible bg-content\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button><span class=\"bg-icon\"></span>&nbsp;<span class=\"bg-msg\"></span></div>",
                delay: 5000
            },
            jump: {
                url: "",
                text: "",
                icon: "glyphicon glyphicon-refresh bg-spin",
                delay: 3000
            }
        };

        var opts = $.extend(true, defaults, options);

        var _obj_selector = opts.box.selector;
        var _obj_box = $(_obj_selector);
        var _rcode_status, _class, _icon, _str_rcode, _msg_html;

        _obj_box.html(opts.box.tpl);

        $(_obj_selector + " " + opts.selector.content).addClass(opts.class_content.submitting);
        $(_obj_selector + " " + opts.selector.icon).addClass(opts.class_icon.submitting);
        $(_obj_selector + " " + opts.selector.msg).html(opts.msg_text.submitting);

        //调用弹出框
        var callBox = function(_action, _rcode, _msg) {
            switch(_action) {
                case "pre":
                    $(_obj_selector + " " + opts.selector.content).removeClass(opts.class_content.submitting + " " + opts.class_content.err + " " + opts.class_content.success);
                    $(_obj_selector + " " + opts.selector.content).addClass(opts.class_content.submitting);

                    $(_obj_selector + " " + opts.selector.icon).removeClass(opts.class_icon.submitting + " " + opts.class_icon.err + " " + opts.class_icon.success);
                    $(_obj_selector + " " + opts.selector.icon).addClass(opts.class_icon.submitting);

                    $(_obj_selector + " " + opts.selector.msg).html(opts.msg_text.submitting);

                    _obj_box.slideDown();

                    $(opts.selector.submit_btn).attr("disabled", true);
                break;

                case "err":
                    $(_obj_selector + " " + opts.selector.content).removeClass(opts.class_content.submitting + " " + opts.class_content.err + " " + opts.class_content.success);
                    $(_obj_selector + " " + opts.selector.content).addClass(opts.class_content.err);

                    $(_obj_selector + " " + opts.selector.icon).removeClass(opts.class_icon.submitting + " " + opts.class_icon.err + " " + opts.class_icon.success);

                    $(_obj_selector + " " + opts.selector.icon).addClass(opts.class_icon.err);

                    $(_obj_selector + " " + opts.selector.msg).html(opts.msg_text.err + "&nbsp;status:&nbsp;" + _msg);
                break;

                case "success":
                    if (typeof _rcode == "undefined" || _rcode == null) {
                        _rcode = "x";
                    }

                    _rcode_status  = _rcode.substr(0, 1);

                    switch (_rcode_status) {
                        case "x":
                            _class      = opts.class_content.err;
                            _icon       = opts.class_icon.err;
                            _str_rcode  = _rcode;
                        break;

                        case "y":
                            _class      = opts.class_content.success;
                            _icon       = opts.class_icon.success;
                            _str_rcode  = "";
                        break;
                    }

                    _msg_html = _str_rcode;

                    if (typeof _msg != "undefined" && _msg.length > 0) {
                        _msg_html = _msg + "&nbsp;" + _str_rcode;
                    }

                    if (_rcode_status == "y" && typeof opts.jump.url != "undefined" && opts.jump.url.length > 0 && typeof opts.jump.text != "undefined" && opts.jump.text.length > 0) {
                        _msg_html = _msg_html + "&nbsp;<a class=\"alert-link\" href=\"" + opts.jump.url + "\">" + opts.jump.text + "</a>";
                        _icon = opts.jump.icon;
                    }

                    $(_obj_selector + " " + opts.selector.content).removeClass(opts.class_content.submitting + " " + opts.class_content.err + " " + opts.class_content.success);
                    $(_obj_selector + " " + opts.selector.content).addClass(_class);

                    $(_obj_selector + " " + opts.selector.icon).removeClass(opts.class_icon.submitting + " " + opts.class_icon.err + " " + opts.class_icon.success);

                    $(_obj_selector + " " + opts.selector.icon).addClass(_icon);

                    $(_obj_selector + " " + opts.selector.msg).html(_msg_html);

                    if (_rcode_status == "y") {
                        $(opts.selector.empty_input).val("");

                        if (typeof opts.jump.url != "undefined" && opts.jump.url.length > 0) {
                            setTimeout(jumpGo, opts.jump.delay);
                        }
                    }
                break;

                default:
                    _obj_box.slideUp();

                    $(opts.selector.submit_btn).removeAttr("disabled");
                break;
            }
        };

        var jumpGo = function() {
            window.location.href = opts.jump.url;
        }

        //确认消息
        var formConfirm = function() {
            if (typeof opts.confirm.selector == "undefined" || opts.confirm.selector == null) {
                return true;
            } else {
                var _form_action = $(opts.confirm.selector).val();
                if (_form_action == opts.confirm.val) {
                    if (confirm(opts.confirm.msg)) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return true;
                }
            }
        };

        //ajax 提交
        el.formSubmit = function() {
            if (formConfirm()) {
                if (opts.ajax_url.indexOf("?")) {
                    _str_conn = "&";
                } else {
                    _str_conn = "?";
                }
                $.ajax({
                    url: opts.ajax_url + _str_conn + "a=" + Math.random(), //url
                    //async: false, //设置为同步
                    type: "post",
                    dataType: "json", //数据格式为json
                    data: $(thisForm).serialize(),
                    beforeSend: function(){
                        callBox("pre"); //输出正在提交
                    }, //输出消息
                    error: function(_result){
                        callBox("err", "", _result.status); //输出消息
                        setTimeout(callBox, opts.box.delay);
                    },
                    success: function(_result){ //读取返回结果
                        callBox("success", _result.rcode, _result.msg); //输出消息
                        setTimeout(callBox, opts.box.delay);
                    }
                });
            }
        };

        return this;
    };

})(jQuery);