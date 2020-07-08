/*
v2.1.0 jQuery baigoSubmit plugin 表单提交插件
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
            class_content: {
                submitting: 'text-info',
                err: 'text-danger',
                success: 'text-success'
            },
            class_icon: {
                submitting: 'spinner-grow spinner-grow-sm',
                err: 'fas fa-times-circle',
                success: 'fas fa-check-circle'
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
                    content: '.bg-content',
                    icon: '.bg-icon',
                    msg: '.bg-msg',
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
                            '<div class="modal-body d-flex justify-content-between align-items-end">' +
                                '<p class="lead bg-content">' +
                                    '<span class="bg-icon mr-2"></span>' +
                                    '<span class="bg-msg"></span>' +
                                '</p>' +
                                '<p class="bg-jump">' +
                                '</p>' +
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
            jump: {
                url: '',
                text: '',
                icon: 'spinner-grow spinner-grow-sm',
                delay: 3000
            }
        };

        var opts = $.extend(true, opts_default, options);

        var modal_selector   = opts.modal.selector.modal;

        //console.log(modal_selector);

        if ($(modal_selector).length < 1) {
            $('body').append(opts.modal.tpl);
        }

        //console.log(_obj_modal);

        $(modal_selector + ' ' + opts.modal.selector.content).addClass(opts.class_content.submitting);
        $(modal_selector + ' ' + opts.modal.selector.icon).addClass(opts.class_icon.submitting);
        $(modal_selector + ' ' + opts.modal.selector.msg).html(opts.msg_text.submitting);
        $(modal_selector + ' ' + opts.modal.selector.close).text(opts.modal.btn_text.close);
        $(modal_selector + ' ' + opts.modal.selector.ok).text(opts.modal.btn_text.ok);
        $(modal_selector + ' ' + opts.modal.selector.ok).hide();

        var process = {
            //调用弹出框
            output: function(_action, _rcode, _msg, _attach_value) {

                //console.log(_action);

                switch(_action) {
                    case 'pre':
                        $(modal_selector + ' ' + opts.modal.selector.content).removeClass(opts.class_content.submitting + ' ' + opts.class_content.err + ' ' + opts.class_content.success);
                        $(modal_selector + ' ' + opts.modal.selector.content).addClass(opts.class_content.submitting);

                        $(modal_selector + ' ' + opts.modal.selector.icon).removeClass(opts.class_icon.submitting + ' ' + opts.class_icon.err + ' ' + opts.class_icon.success);
                        $(modal_selector + ' ' + opts.modal.selector.icon).addClass(opts.class_icon.submitting);

                        $(modal_selector + ' ' + opts.modal.selector.msg).html(opts.msg_text.submitting);

                        $(modal_selector).modal('show');

                        obj_form.find(opts.selector.submit_btn).attr('disabled', true);
                    break;

                    case 'err':
                        $(modal_selector + ' ' + opts.modal.selector.content).removeClass(opts.class_content.submitting + ' ' + opts.class_content.err + ' ' + opts.class_content.success);
                        $(modal_selector + ' ' + opts.modal.selector.content).addClass(opts.class_content.err);

                        $(modal_selector + ' ' + opts.modal.selector.icon).removeClass(opts.class_icon.submitting + ' ' + opts.class_icon.err + ' ' + opts.class_icon.success);

                        $(modal_selector + ' ' + opts.modal.selector.icon).addClass(opts.class_icon.err);

                        $(modal_selector + ' ' + opts.modal.selector.msg).html(opts.msg_text.err + '! ' + _msg);
                    break;

                    case 'success':
                        var _class, _icon, _str_rcode, _rcode_status;
                        var _msg_html = '<span>';

                        if (typeof _rcode == 'undefined' || _rcode === null) {
                            _rcode = 'x';
                        }

                        _rcode_status  = _rcode.substr(0, 1);

                        if (_rcode_status == 'y') {
                            _class      = opts.class_content.success;
                            _icon       = opts.class_icon.success;
                            _str_rcode  = '';
                        } else {
                            _class      = opts.class_content.err;
                            _icon       = opts.class_icon.err;
                            _str_rcode  = ' ' + _rcode;
                        }

                        if (typeof _msg != 'undefined') {
                            _msg_html = _msg + _str_rcode;
                        }

                        _msg_html += '</span>';

                        //console.log(opts.jump);

                        if (_rcode_status == 'y' && opts.jump.url.length > 0 && opts.jump.text.length > 0) {
                            var _jump_url = opts.jump.url;

                            if (typeof opts.jump.attach_key != 'undefined' && typeof _attach_value != 'undefined') {
                                if (_jump_url.indexOf('?')) {
                                    _str_conn = '&';
                                } else {
                                    _str_conn = '?';
                                }

                                _jump_url = _jump_url + _str_conn + opts.jump.attach_key + '=' + _attach_value;
                            }

                            $(modal_selector + ' ' + opts.modal.selector.ok).attr('href', _jump_url);
                            $(modal_selector + ' ' + opts.modal.selector.ok).show();

                            var _msg_jump = '<a href="' + _jump_url + '">' + opts.jump.text + '</a>';

                            $(opts.selector.jump_link).html(_msg_jump);

                            _icon = opts.jump.icon;
                        }

                        $(modal_selector + ' ' + opts.modal.selector.content).removeClass(opts.class_content.submitting + ' ' + opts.class_content.err + ' ' + opts.class_content.success);
                        $(modal_selector + ' ' + opts.modal.selector.content).addClass(_class);
                        $(modal_selector + ' ' + opts.modal.selector.icon).removeClass(opts.class_icon.submitting + ' ' + opts.class_icon.err + ' ' + opts.class_icon.success);
                        $(modal_selector + ' ' + opts.modal.selector.icon).addClass(_icon);
                        $(modal_selector + ' ' + opts.modal.selector.msg).html(_msg_html);

                        if (_str_rcode.length < 1) {
                            obj_form.find(opts.selector.empty_input).val('');

                            if (typeof opts.jump.url != 'undefined' && opts.jump.url.length > 0) {
                                setTimeout(function(){
                                    window.location.href = _jump_url;
                                }, opts.jump.delay);
                            }
                        }
                    break;

                    default:
                        $(modal_selector).modal('hide');

                        obj_form.find(opts.selector.submit_btn).removeAttr('disabled');
                    break;
                }
            },
            submitFunc: function() {
                var _str_conn;

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
                        var result_attach_value;

                        if (typeof opts.jump.attach_key != 'undefined' && typeof result[opts.jump.attach_key] != 'undefined') {
                            result_attach_value = result[opts.jump.attach_key];
                        }

                        //console.log(result);

                        if (typeof result.msg == 'undefined' && result.err_message != 'undefined') {
                            result.msg = result.err_message;
                        }

                        process.output('success', result.rcode, result.msg, result_attach_value); //输出消息
                        setTimeout(function(){
                            process.output();
                        }, opts.modal.delay);
                    }
                });
            }
        };

        //ajax 提交
        var el = {
            formSubmit: function(url) {
                if (typeof url != 'undefined') {
                    opts.ajax_url = url;
                }

                process.submitFunc();
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