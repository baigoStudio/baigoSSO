/*
v0.1.1 jQuery baigoUbb plugin UBB 编辑器
(c) 2013 baigo studio - http://www.baigo.net/
License: http://www.opensource.org/licenses/mit-license.php
*/
;(function($) {
    $.fn.baigoUbb = function(options) {
        'use strict';
        if (this.length < 1) {
            return this;
        }

        var obj_field     = $(this);
        var obj_selection = obj_field[0];

        var opts_default = {
            menu_selector: '.bg-ubb-menu',
            menu_text: {
                color: 'Color',
                bgcolor: 'Background color',
                heading: 'Heading'
            },
            format: {
                strong: {
                    text: 'Bold',
                    icon: 'fas fa-bold'
                },
                i: {
                    text: 'Italic',
                    icon: 'fas fa-italic'
                },
                u: {
                    text: 'Underline',
                    icon: 'fas fa-underline'
                }
            },
            color: {
                aqua: {
                    text: 'Aqua',
                    ext: '#333'
                },
                black: 'Black',
                blue: 'Blue',
                fuchsia: 'Fuchsia',
                gray: 'Gray',
                green: 'Green',
                lime: {
                    text: 'Lime',
                    ext: '#333'
                },
                maroon: 'Maroon',
                navy: 'Navy',
                olive: 'Olive',
                purple: 'Purple',
                red: 'Red',
                silver: 'Silver',
                teal: 'Teal',
                white: {
                    text: 'White',
                    ext: '#333'
                },
                yellow: {
                    text: 'Yellow',
                    ext: '#333'
                }
            },
            bgcolor: {
                aqua: {
                    text: 'Aqua',
                    ext: '#000'
                },
                black: 'Black',
                blue: 'Blue',
                fuchsia: 'Fuchsia',
                gray: 'Gray',
                green: 'Green',
                lime: {
                    text: 'Lime',
                    ext: '#000'
                },
                maroon: 'Maroon',
                navy: 'Navy',
                olive: 'Olive',
                purple: 'Purple',
                red: 'Red',
                silver: 'Silver',
                teal: 'Teal',
                white: {
                    text: 'White',
                    ext: '#000'
                },
                yellow: {
                    text: 'Yellow',
                    ext: '#000'
                }
            },
            heading: {
                h1: 'Heading 1',
                h2: 'Heading 2',
                h3: 'Heading 3',
                h4: 'Heading 4',
                h5: 'Heading 5',
                h6: 'Heading 6'
            },
            dialog: {
                img: {
                    text: 'Image',
                    icon: 'fas fa-image',
                    ext: ['src', 'text']
                },
                url: {
                    text: 'Link',
                    icon: 'fas fa-link',
                    ext: ['href', 'text']
                }
            },
            single: {
                hr: {
                    text: 'Horizontal rule',
                    icon: 'fas fa-minus'
                },
                br: {
                    text: 'Break line',
                    icon: 'fas fa-paragraph'
                }
            }
        };

        var opts = $.extend(true, opts_default, options);

        var menu_tpl = '<div class="bg-ubb-menu mb-2">' +
            '<div class="btn-toolbar">' +
                '<div class="btn-group btn-group-sm mr-2 bg-ubb-format"></div>' +
                '<div class="btn-group btn-group-sm mr-2">' +
                    '<div class="btn-group btn-group-sm">' +
                        '<button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown">' +
                             opts.menu_text.heading +
                        '</button>' +
                        '<div class="dropdown-menu bg-ubb-heading"></div>' +
                    '</div>' +
                    '<div class="btn-group btn-group-sm">' +
                        '<button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown">' +
                             opts.menu_text.color +
                        '</button>' +
                        '<div class="dropdown-menu bg-ubb-color"></div>' +
                    '</div>' +
                    '<div class="btn-group btn-group-sm">' +
                        '<button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown">' +
                             opts.menu_text.bgcolor +
                        '</button>' +
                        '<div class="dropdown-menu bg-ubb-bgcolor"></div>' +
                    '</div>' +
                '</div>' +
                '<div class="btn-group btn-group-sm mr-2 bg-ubb-dialog"></div>' +
                '<div class="btn-group btn-group-sm bg-ubb-single"></div>' +
            '</div>' +
        '</div>';

        if ($(opts.menu_selector).length < 1) {
            obj_field.before(menu_tpl);
        }

        var process = {
            addMenu: function(menus, selector, type) {
                var menu_html = '';

                $.each(menus, function(_key, _value){
                    var _show_text  = '';
                    var _show_str   = '';
                    var _show_icon  = '';
                    var _ext_param  = '';

                    if (typeof _value == 'string') {
                        _show_text = _value;
                    } else if (typeof _value == 'object') {
                        if (typeof _value.text == 'string') {
                            _show_text = _value.text;
                        }

                        if (typeof _value.icon == 'string') {
                            _show_str = '<span class="' + _value.icon + '"></span>';
                        } else {
                            _show_str = _show_text;
                        }

                        if (typeof _value.ext == 'string') {
                            _ext_param = _value.ext;
                        }
                    }

                    menu_html += '<button type="button"';

                    switch (type) {
                        case 'heading':
                            _show_str = '<' + _key + '>' + _show_text + '</' + _key + '>';
                            menu_html += ' class="dropdown-item bg-pair" data-tag="' + _key + '"';
                        break;

                        case 'color':
                            _show_str = '<span style="color:' + _key + ';';

                            if (_ext_param.length > 0) {
                                _show_str += 'background-color:' + _ext_param + ';';
                            }

                            _show_str += '">' + _show_text + '</span>';

                            menu_html += ' class="dropdown-item bg-tag" data-tag="color" data-value="' + _key + '"';
                        break;

                        case 'bgcolor':
                            if (_ext_param.length < 1) {
                                _ext_param = 'white';
                            }

                            _show_str = '<span style="background-color:' + _key + ';color:' + _ext_param + ';">' + _show_text + '</span>';

                            menu_html += ' class="dropdown-item bg-tag" data-tag="bgcolor" data-value="' + _key + '"';
                        break;

                        case 'format':
                            menu_html += ' class="btn btn-outline-secondary bg-pair" data-tag="' + _key + '"';
                        break;

                        default:
                            menu_html += ' class="btn btn-outline-secondary bg-' + type + '" data-tag="' + _key + '"';
                        break;
                    }

                    menu_html += ' title="' + _show_text + '">' + _show_str + '</button>';
                });

                $(opts.menu_selector + ' ' + selector).html(menu_html);
            }
        };

        var el = {
            addSingle: function(tag) {
                if (typeof tag != 'undefined' && tag.length > 0) {
                    var _len     = obj_selection.value.length;
                    var _begin   = obj_selection.selectionStart;
                    var _end     = obj_selection.selectionEnd;

                    obj_selection.value = obj_selection.value.substring(0, _begin) + '[' + tag + ']' + obj_selection.value.substring(_end, _len);
                }
            },
            addPair: function(tag) {
                if (typeof tag != 'undefined' && tag.length > 0) {
                    var _len     = obj_selection.value.length;
                    var _begin   = obj_selection.selectionStart;
                    var _end     = obj_selection.selectionEnd;

                    var _select  = obj_selection.value.substring(_begin, _end);

                    if (_select.length > 0) {
                        var _replace = '[' + tag + ']' + _select + '[/' + tag + ']';

                        obj_selection.value = obj_selection.value.substring(0, _begin) + _replace + obj_selection.value.substring(_end, _len);
                    }
                }
            },
            addTag: function(tag, value) {
                if (typeof tag != 'undefined' && tag.length > 0) {
                    var _len     = obj_selection.value.length;
                    var _begin   = obj_selection.selectionStart;
                    var _end     = obj_selection.selectionEnd;

                    var _select  = obj_selection.value.substring(_begin, _end);

                    if (_select.length > 0) {
                        var _replace = '[' + tag;

                        if (typeof value != 'undefined' && value.length > 0 && typeof value == 'string') {
                            _replace += '=' + value + ']';
                        } else {
                            _replace += ']';
                        }

                        _replace += _select + '[/' + tag + ']';

                        obj_selection.value = obj_selection.value.substring(0, _begin) + _replace + obj_selection.value.substring(_end, _len);
                    }
                }
            },
            insertTag: function(tag, value) {
                var _len     = obj_selection.value.length;
                var _begin   = obj_selection.selectionStart;

                if (typeof tag != 'undefined' && tag.length > 0 && typeof value != 'undefined' && value.length > 0) {
                    var _insert = '[' + tag;

                    if (typeof value == 'object') {
                        if (typeof value[0] != 'undefined' && value[0].length > 0 && typeof value[0] == 'string' && typeof value[1] != 'undefined' && value[1].length > 0 && typeof value[1] == 'string') {
                            _insert += '=' + value[0] + ']' + value[1];
                        } else if (typeof value[0] != 'undefined' && value[0].length > 0 && typeof value[0] == 'string') {
                            _insert += ']' + value[0];
                        }
                    } else if (typeof value == 'string') {
                        _insert += ']' + value;
                    }

                    _insert += '[/' + tag + ']';

                    obj_selection.value = obj_selection.value.substring(0, _begin) + _insert + obj_selection.value.substring(_begin, _len);
                }
            }
        };

        process.addMenu(opts.format, '.bg-ubb-format', 'format');
        process.addMenu(opts.heading, '.bg-ubb-heading', 'heading');
        process.addMenu(opts.color, '.bg-ubb-color', 'color');
        process.addMenu(opts.bgcolor, '.bg-ubb-bgcolor', 'bgcolor');
        process.addMenu(opts.dialog, '.bg-ubb-dialog', 'dialog');
        process.addMenu(opts.single, '.bg-ubb-single', 'single');

        obj_field.keyup(function(event){
            if (event.keyCode == 13) {
                el.addSingle('br');
            }
        });

        $(opts.menu_selector).on('click', '.bg-pair', function(){
            var _tag = $(this).data('tag');
            el.addPair(_tag);
        });

        $(opts.menu_selector).on('click', '.bg-single', function(){
            var _tag = $(this).data('tag');
            el.addSingle(_tag);
        });

        $(opts.menu_selector).on('click', '.bg-tag', function(){
            var _tag   = $(this).data('tag');
            var _value = $(this).data('value');
            el.addTag(_tag, _value);
        });

        $(opts.menu_selector).on('click', '.bg-dialog', function(){
            var _tag    = $(this).data('tag');
            var _input  = _tag;

            if (typeof opts.dialog[_tag] != 'undefined') {
                if (typeof opts.dialog[_tag] == 'string') {
                    _input = opts.dialog[_tag];
                } else if (typeof opts.dialog[_tag] == 'object') {
                    if (typeof opts.dialog[_tag].ext != 'undefined') {
                        _input = opts.dialog[_tag].ext;
                    } else if (typeof opts.dialog[_tag].text != 'undefined') {
                        _input = opts.dialog[_tag].text;
                    }
                }
            }

            var _obj_dialog = $.baigoDialog();

            _obj_dialog.input(_input, function(act, value){
                if (typeof act != 'undefined' && act == 'ok') {
                    el.insertTag(_tag, value);
                }
            });
        });

        if (typeof options == 'string' && typeof el[options] != 'undefined') {
            el[options].apply(this, Array.prototype.slice.call(arguments, 1));
        }

        return el;
    };
})(jQuery);
