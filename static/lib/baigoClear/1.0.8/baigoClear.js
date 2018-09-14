/*
v1.0.8 jQuery baigoClear plugin 表单 ajax 清理插件
(c) 2013 baigo studio - http://www.baigo.net/
License: http://www.opensource.org/licenses/mit-license.php
*/
;(function($) {
    $.fn.baigoClear = function(options) {
        "use strict";
        if (this.length < 1) {
            return this;
        }
        // support mutltiple elements
        if (this.length > 1) {
            this.each(function() {
                $(this).baigoClear(options);
            });
            return this;
        }

        var thisForm = $(this); //定义表单对象
        var el = this;

        var _str_msg;
        var _form_action;
        var formData;
        var _str_msgResult;
        var _width;

        var defaults = {
            selector: {
                progress: ".baigoClear",
                msg: ".baigoClearMsg"
            },
            msg: {
                loading: "Loading...",
                complete: "Complete",
                err: "Error"
            }
        };

        var opts = $.extend(true, defaults, options);

        var appendMsg = function(_status, _msg, _icon) {
            $(opts.selector.msg).empty();

            _str_msg = "<div class=\"alert alert-" + _status + "\">" +
                "<span class=\"oi oi-" + _icon + "\"></span> " +
                _msg +
            "</div>";

            $(opts.selector.msg).html(_str_msg);
        };
            //确认消息
        var clearConfirm = function() {
            if (typeof opts.confirm.selector == "undefined") {
                return true;
            } else {
                _form_action = $(opts.confirm.selector).val();
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

        var _count = 0;


        var clearAjax = function(_page, _min_id, _max_id) {
            //alert(_page);
            formData = $(thisForm).serializeArray();
            formData.push({
                name: "page",
                value: _page
            });
            formData.push({
                name: "min_id",
                value: _min_id
            });
            formData.push({
                name: "max_id",
                value: _max_id
            });
            $.ajax({
                url: opts.ajax_url,
                type: "post",
                dataType: "json",
                data: formData,
                success: function(_result) { //读取返回结果
                    if (_count < 1) {
                        _count = _result.count;
                    }
                    _width = parseInt(_page / _count * 100 + "%");
                    if (_width > 100) {
                        _width = 100;
                    }
                    switch (_result.status) {
                        case "err":
                            if (typeof _result.msg != "undefined") {
                                _str_msgResult = _result.msg;
                            } else {
                                _str_msgResult = opts.msg.err;
                            }
                            appendMsg("danger", _str_msgResult, "circle-x");
                            _count  = 0;
                            _page   = 1;
                        break;
                        case "complete":
                            if (typeof _result.msg != "undefined") {
                                _str_msgResult = _result.msg;
                            } else {
                                _str_msgResult = opts.msg.complete;
                            }
                            appendMsg("success", _str_msgResult, "circle-check");
                            $(opts.selector.progress + " .progress-bar").text("100%");
                            $(opts.selector.progress + " .progress-bar").css("width", "100%");
                            setTimeout(function(){
                                $(opts.selector.progress).slideUp();
                            }, 3000);
                            _count  = 0;
                            _page   = 1;
                        break;
                        case "next":
                            if (typeof _result.msg != "undefined") {
                                _str_msgResult = _result.msg;
                            } else {
                                _str_msgResult = opts.msg.loading;
                            }
                            appendMsg("info", _str_msgResult, "repeat bg-spin");
                            _width = 20;
                            $(opts.selector.progress + " .progress-bar").text(_width + "%");
                            $(opts.selector.progress + " .progress-bar").css("min-width", "20%");
                            $(opts.selector.progress + " .progress-bar").css("width", _width + "%");
                            $(opts.selector.progress).slideDown();
                            clearAjax(1, 0, _result.max_id);
                        break;
                        default:
                            if (typeof _result.msg != "undefined") {
                                _str_msgResult = _result.msg;
                            } else {
                                _str_msgResult = opts.msg.loading;
                            }
                            appendMsg("info", _str_msgResult, "repeat bg-spin");
                            $(opts.selector.progress + " .progress-bar").text(_width + "%");
                            $(opts.selector.progress + " .progress-bar").css("min-width", "20%");
                            $(opts.selector.progress + " .progress-bar").css("width", _width + "%");
                            $(opts.selector.progress).slideDown();
                            clearAjax(_page, 0, _result.max_id);
                        break;
                    }
                }
            });
            _page++;
        };
        //ajax提交
        el.clearSubmit = function() {
            if (clearConfirm()) {
                clearAjax(1, 0);
            }
        };
        return this;
    };
})(jQuery);