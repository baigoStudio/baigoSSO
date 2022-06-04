/*
v1.0.1 jQuery baigoDialog plugin 对话框插件
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
            input_tpl: '<div class="form-group"><label class="bg-input-label"></label><input type="text" class="form-control bg-input-control"></div>'
        };

        var opts = $.extend(true, opts_default, options);
        var selector_modal = opts.selector.modal;

        if ($(selector_modal).length < 1) {
            $('body').append(opts.tpl);
        }

        var process = {
            modalShow: function(msg) {
                if (typeof msg != 'undefined' && msg.length > 0) {
                    $(selector_modal + ' ' + opts.selector.msg).text(msg);

                    var _option = { backdrop: 'static', show: true };

                    $(selector_modal).modal(_option);
                }
            },
            inputShow: function(input) {
                if (typeof input != 'undefined' && input.length > 0) {
                    if (typeof input == 'object') {
                        var _input_html  = '';
                        $.each(input, function(_key, _value){
                            _input_html += opts.input_tpl;
                        });

                        $(selector_modal + ' ' + opts.selector.msg).html(_input_html);

                        $.each(input, function(_key, _value){
                            $(selector_modal + ' ' + opts.selector.input_label + ':eq(' + _key + ')').text(_value);
                        });
                    } else if (typeof input == 'string') {
                        $(selector_modal + ' ' + opts.selector.msg).html(opts.input_tpl);
                        $(selector_modal + ' ' + opts.selector.input_label).text(input);
                    }

                    var _option = { backdrop: 'static', show: true };

                    $(selector_modal).modal(_option);
                }
            },
            modalHide: function() {
                $(selector_modal).modal('hide');
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
                if (typeof msg != 'undefined' && msg.length > 0) {
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
                if (typeof input != 'undefined' && input.length > 0) {
                    $(selector_modal + ' ' + opts.selector.footer).html(opts.btn_tpl.input);
                    $(selector_modal + ' ' + opts.selector.cancel).text(opts.btn_text.cancel);
                    $(selector_modal + ' ' + opts.selector.ok).text(opts.btn_text.ok);

                    process.inputShow(input);

                    if (callback && callback instanceof Function) {
                        $(selector_modal + ' ' + opts.selector.group_input).off('click');
                        $(selector_modal + ' ' + opts.selector.group_input).click(function() {
                            if ($(this).data('act') == 'ok') {
                                if (typeof input == 'object') {
                                    var _return = [];
                                    $.each(input, function(_key, _value){
                                        _return[_key] = $(selector_modal + ' ' + opts.selector.input_control + ':eq(' + _key + ')').val();
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
