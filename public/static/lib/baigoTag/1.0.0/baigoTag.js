/*
v1.0.0 jQuery baigoTag plugin tag 输入插件
(c) 2013 baigo studio - http://www.baigo.net/
License: http://www.opensource.org/licenses/mit-license.php
*/
;(function ($) {
    $.fn.baigoTag = function(options) {
        'use strict';
        if (this.length < 1) {
            return this;
        }

        var obj_input       = $(this);
        var tag_list        = [];
        var confirmKeys     = [];

        var opts_default = {
            maxTags: 5,
            minChar: 0,
            addOnBlur: true,
            confirmKeycodes: [13, 186, 188],
            class_name: {
                tag_list: 'bg-tag-list',
                tag_item: 'bg-tag-item'
            },
            selector: {
                tag_list: '.bg-tag-list',
                tag_item: '.bg-tag-item'
            },
            tpl: {
                tag_list: '<div class="bg-tag-list"></div>'
            },
            remote: {
                url: '',
                wildcard: '%KEY'
            },
            highlight: true
        }

        var opts = $.extend(true, opts_default, options);

        var tagsData = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.whitespace,
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: opts.remote
        });

        tagsData.initialize();

        var datasets = {
            source: tagsData.ttAdapter()
        };

        var opts_typeahead = {
            highlight: opts.highlight
        };

        var src_input_name = obj_input.attr('name');
        var src_input_id   = obj_input.attr('id');

        if (typeof src_input_id == 'undefined') {
            src_input_id = src_input_name;
        }

        var hidden_field = '<input type="hidden" name="' + src_input_name + '_hidden" id="' + src_input_id + '_hidden">'

        if ($('#' + src_input_id + '_hidden').length < 1) {
            obj_input.before(hidden_field);
        }

        if ($(opts.selector.tag_list).length < 1) {
            obj_input.before(opts.tpl.tag_list);
        }

        if (typeof datasets.source != 'undefined') {
            var _obj_typeahead = obj_input.typeahead(opts_typeahead, datasets);
            _obj_typeahead.bind('typeahead:select', function(ev, suggestion) {
                process.pushTag(suggestion);
            });
        }

        if (opts.addOnBlur) {
            obj_input.blur(function(){
                var tag = $(this).val();
                process.pushTag(tag);
            });
        }

        obj_input.keyup(function(event){
            var tag = $(this).val();
            if (opts.minChar > 0 && tag.length >= opts.minChar) {
                process.pushTag(tag);
            }

            if ($.inArray(event.keyCode, opts.confirmKeycodes) >= 0) {
                confirmKeys.push(event.key);
                process.pushTag(tag);
            }
        });

        var process = {
            indexData: function(val) {
                for (var i = 0; i < tag_list.length; i++) {
                    if (tag_list[i] == val) {
                        return i;
                    }
                }

                return -1;
            },
            deleteData: function(val) {
                var _index = process.indexData(val);
                if (_index > -1) {
                    tag_list.splice(_index, 1);
                }
            },
            putVal: function() {
                $('#' + src_input_id + '_hidden').val(tag_list.toString());
            },
            inputEmpty: function() {
                obj_input.val('');
                if (typeof _obj_typeahead != 'undefined') {
                    obj_input.typeahead('val', '');
                }
            },
            pushTag: function(tag_name) {
                if (typeof tag_name === 'string') {
                    tag_name = process.valProcess(tag_name);
                    if (tag_name.length > 0 && tag_list.length < opts.maxTags && $.inArray(tag_name, tag_list) < 0) {
                        var _tag_item = '<div class="' + opts.class_name.tag_item + '" id="tag_' + tag_name.replace(/ /g, '_') + '">' +
                            tag_name +
                            '<span data-act="remove" data-tag="' + tag_name.replace(/ /g, '_') + '">' +
                                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm121.6 313.1c4.7 4.7 4.7 12.3 0 17L338 377.6c-4.7 4.7-12.3 4.7-17 0L256 312l-65.1 65.6c-4.7 4.7-12.3 4.7-17 0L134.4 338c-4.7-4.7-4.7-12.3 0-17l65.6-65-65.6-65.1c-4.7-4.7-4.7-12.3 0-17l39.6-39.6c4.7-4.7 12.3-4.7 17 0l65 65.7 65.1-65.6c4.7-4.7 12.3-4.7 17 0l39.6 39.6c4.7 4.7 4.7 12.3 0 17L312 256l65.6 65.1z"/></svg>' +
                            '</span>' +
                        '</div>';

                        $(opts.selector.tag_list).append(_tag_item);

                        tag_list.push(tag_name);
                        process.putVal();
                        process.inputEmpty();
                    }
                }
            },
            valProcess: function(str) {
                if (typeof str == 'undefined') {
                    return '';
                } else {
                    str = str.trim();
                    str = str.replace(/<\/?[^>]*>/g, ''); //去除HTML
                    str = str.replace(/\n[\s||]*\r/g, '\n'); //去除多余空行

                    for (var i = 0; i < confirmKeys.length; i++) {
                        str = str.replace(confirmKeys[i], ''); //去除分隔键
                    }

                    return str;
                }
            }
        }

        var el = {
            add: function(tag_name) {
                if ($.isArray(tag_name)) {
                    $.each(tag_name, function(key, value){
                        process.pushTag(value);
                    });
                } else {
                    process.pushTag(tag_name);
                }
            },
            remove: function(tag_name) {
                $('#tag_' + tag_name).remove();
                process.deleteData(tag_name);
                process.putVal();
            },
            getTags: function() {
                return tag_list;
            }
        };

        $(opts.selector.tag_list).on('click', '[data-act="remove"]', function(){
            var tag = $(this).data('tag');
            //console.log(tag);
            el.remove(tag);
        });

        return el;
    };
})(jQuery);
