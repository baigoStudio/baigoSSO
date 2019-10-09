/*
v2.0.0 jQuery baigoCheckall plugin 表单全选插件
(c) 2016 baigo studio - http://www.baigo.net/jquery/baigocheckall.html
License: http://www.opensource.org/licenses/mit-license.php
*/
;(function($) {
    $.fn.baigoCheckall = function() {
        'use strict';
        if (this.length < 1) {
            return this;
        }

        var obj_form   = $(this);

        var process = {
            //处理父对象
            parent: function(_parent) {
                var _parent_num = obj_form.find('#' + _parent).length; //获取父对象数量
                if (_parent_num > 0) { //如果有父对象
                    var _brother_num = obj_form.find('[data-parent="' + _parent + '"]').length; //根据 parent 获取兄弟对象数
                    var _brother_checked_num = obj_form.find('[data-parent="' + _parent + '"]:checked').length; //根据 parent 获取兄弟对象选中数
                    if (_brother_num > 0 && _brother_checked_num > 0) { //如果有兄弟对象被选中
                        if (_brother_num > 0 && _brother_checked_num < _brother_num) { //如果有兄弟对象且兄弟对象选中数小于实际数，则设置父对象未选中
                            obj_form.find('#' + _parent).prop('indeterminate', true);
                            obj_form.find('#' + _parent).prop('checked', false);
                        } else {
                            obj_form.find('#' + _parent).prop('indeterminate', false);
                            obj_form.find('#' + _parent).prop('checked', true);
                        }
                    } else {
                        obj_form.find('#' + _parent).prop('indeterminate', false);
                        obj_form.find('#' + _parent).prop('checked', false);
                    }
                    var _parent_this = obj_form.find('#' + _parent).data('parent'); //根据该父对象的 parent 获取爷对象
                    process.parent(_parent_this); //设置爷对象
                }
            },

            //处理子对象
            child: function(_child) {
                var _child_obj = obj_form.find('[data-parent="' + _child + '"]'); //获取子对象
                var _checked = obj_form.find('#' + _child).prop('checked'); //获取父对象的选中状态
                if (_child_obj) { //如果有子对象
                    $.each(_child_obj, function() { //遍历
                        var _disabled = $(this).attr('disabled');
                        if (!_checked || _disabled) { //根据父对象的选中状态，设置子对象的选中状态
                            $(this).prop('checked', false);
                        } else {
                            $(this).prop('checked', true);
                        }
                        var _child_this = $(this).attr('id'); //根据该子对象的 id 获取孙对象
                        process.child(_child_this); //设置孙对象
                    });
                }
            }
        }

        obj_form.find('input:checkbox').click(function(){
            var _child  = $(this).attr('id'); //根据 id 设置子对象
            var _parent = $(this).data('parent'); //根据 data-parent 设置父对象
            process.child(_child); //设置子对象
            process.parent(_parent); //设置父对象
        });
    };
})(jQuery);