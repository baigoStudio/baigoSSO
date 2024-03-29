/*
v2.0.0 jQuery baigoClear plugin 表单 ajax 清理插件
(c) 2013 baigo studio - https://www.baigo.net/
License: http://www.opensource.org/licenses/mit-license.php
*/
;(function($) {
    $.fn.baigoClear = function(options) {
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
            selector: {
                content: '.bg-clear',
                progress: '.bg-progress',
                msg: '.bg-msg',
                icon: '.bg-icon',
                msg_content: '.bg-msg-content'
            },
            msg: {
                loading: 'Loading...',
                complete: 'Complete',
                err: 'Error'
            },
            class_msg: {
                submitting: 'alert alert-info',
                err: 'alert alert-danger',
                success: 'alert alert-success'
            },
            class_icon: {
                submitting: 'spinner-grow spinner-grow-sm',
                err: 'fas fa-times-circle',
                success: 'fas fa-check-circle'
            },
            tpl: '<div class="bg-clear my-3 collapse">' +
                '<div class="mb-3">' +
                    '<div class="bg-progress progress">' +
                        '<div class="progress-bar progress-bar-info progress-bar-striped active"></div>' +
                    '</div>' +
                '</div>' +
                '<div>' +
                    '<div class="bg-msg">' +
                        '<span class="bg-icon"></span> ' +
                        '<span class="bg-msg-content"></span> ' +
                    '</div>' +
                '</div>' +
            '</div>'
        };

        var opts = $.extend(true, opts_default, options);

        var content_selector   = opts.selector.content;

        if (obj_form.find(content_selector).length < 1) {
            obj_form.append(opts.tpl);
        }

        var process = {
            output: function(msg, status_class, icon_class) {
                obj_form.find(content_selector).collapse('show');

                obj_form.find(opts.selector.icon).removeClass(opts.class_icon.submitting + ' ' + opts.class_icon.err + ' ' + opts.class_icon.success);
                obj_form.find(opts.selector.icon).addClass(icon_class);

                obj_form.find(opts.selector.msg_content).text(msg);

                obj_form.find(opts.selector.msg).removeClass(opts.class_msg.submitting + ' ' + opts.class_msg.err + ' ' + opts.class_msg.success);
                obj_form.find(opts.selector.msg).addClass(status_class);
            },
            submitFunc: function(page, min_id, max_id) {
                var _str_conn;
                var _msg_result;
                var _progress_width;
                var _count = 0;
                var _form_data = $(obj_form).serializeArray();

                if (typeof page == 'undefined') {
                    page = 1;
                }
                if (typeof min_id == 'undefined') {
                    min_id = 0;
                }
                if (typeof max_id == 'undefined') {
                    max_id = 0;
                }

                _form_data.push(
                    {
                        name: 'page',
                        value: page
                    },
                    {
                        name: 'min_id',
                        value: min_id
                    },
                    {
                        name: 'max_id',
                        value: max_id
                    }
                );

                if (typeof opts.ajax_url == 'undefined' || opts.ajax_url == '') {
                    opts.ajax_url = obj_form.attr('action');
                }

                if (opts.ajax_url.indexOf('?') > 0) {
                    _str_conn = '&';
                } else {
                    _str_conn = '?';
                }

                $.ajax({
                    url: opts.ajax_url + _str_conn + new Date().getTime() + 'at' + Math.random(), //url
                    type: 'post',
                    dataType: 'json',
                    data: _form_data,
                    timeout: opts.timeout,
                    error: function(result){
                        process.output(result.status + ' ' + result.statusText, opts.class_msg.err, opts.class_icon.err);
                    },
                    success: function(result) { //读取返回结果
                        if (typeof result.count == 'undefined') {
                            result.count = 0;
                        }

                        if (_count < 1) {
                            _count = result.count;
                        }

                        _progress_width = parseInt(page / _count * 100 + '%');

                        if (_progress_width > 100) {
                            _progress_width = 100;
                        }

                        switch (result.status) {
                            case 'err':
                                if (typeof result.msg != 'undefined') {
                                    _msg_result = result.msg;
                                } else {
                                    _msg_result = opts.msg.err;
                                }
                                process.output(_msg_result, opts.class_msg.err,opts.class_icon.err);
                                _count  = 0;
                                page    = 1;
                            break;

                            case 'complete':
                                if (typeof result.msg != 'undefined') {
                                    _msg_result = result.msg;
                                } else {
                                    _msg_result = opts.msg.complete;
                                }
                                process.output(_msg_result, opts.class_msg.success, opts.class_icon.success);
                                obj_form.find(opts.selector.progress + ' .progress-bar').text('100%');
                                obj_form.find(opts.selector.progress + ' .progress-bar').css('width', '100%');
                                setTimeout(function(){
                                    obj_form.find(opts.selector.progress).slideUp();
                                }, 3000);
                                _count  = 0;
                                page    = 1;
                            break;

                            case 'next':
                                if (typeof result.msg != 'undefined') {
                                    _msg_result = result.msg;
                                } else {
                                    _msg_result = opts.msg.loading;
                                }
                                process.output(_msg_result, opts.class_msg.submitting, opts.class_icon.submitting);
                                _progress_width = 20;
                                obj_form.find(opts.selector.progress + ' .progress-bar').text(_progress_width + '%');
                                obj_form.find(opts.selector.progress + ' .progress-bar').css('min-width', '20%');
                                obj_form.find(opts.selector.progress + ' .progress-bar').css('width', _progress_width + '%');
                                obj_form.find(opts.selector.progress).slideDown();
                                process.submitFunc(1, 0, result.max_id);
                            break;

                            default:
                                if (typeof result.msg != 'undefined') {
                                    _msg_result = result.msg;
                                } else {
                                    _msg_result = opts.msg.loading;
                                }
                                process.output(_msg_result, opts.class_msg.submitting, opts.class_icon.submitting);
                                obj_form.find(opts.selector.progress + ' .progress-bar').text(_progress_width + '%');
                                obj_form.find(opts.selector.progress + ' .progress-bar').css('min-width', '20%');
                                obj_form.find(opts.selector.progress + ' .progress-bar').css('width', _progress_width + '%');
                                obj_form.find(opts.selector.progress).slideDown();
                                process.submitFunc(page, 0, result.max_id);
                            break;
                        }
                    }
                });
                page++;
            }
        };

        var el = {
            clearSubmit: function(url) {
                if (typeof url != 'undefined') {
                    opts.ajax_url = url;
                }

                process.submitFunc();
            },
            ajaxUrl: function(url) {
                if (typeof url != 'undefined') {
                    opts.ajax_url = url;
                }

                opts.ajax_url = url;
            }
        };

        return el;
    };
})(jQuery);