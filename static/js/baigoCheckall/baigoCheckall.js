/*
v1.0 jQuery baigoCheckall plugin 表单全选插件
(c) 2016 baigo studio - http://www.baigo.net/jquery/baigocheckall.html
License: http://www.opensource.org/licenses/mit-license.php
*/
(function($) {
    $.fn.baigoCheckall = function() {
        "use strict";
        var thisForm = $(this);
        $(thisForm).find(":checkbox").click(function() {
            var _child  = $(this).attr("id"); //根据id设置子对象
            var _parent = $(this).data("parent"); //根据data-parent设置父对象
            child_check(_child); //设置子对象
            parent_check(_parent); //设置父对象
        });
        //设置父对象
        var parent_check = function(_parent) {
            var _parent_num = $(thisForm).find("#" + _parent).size(); //获取父对象数量
            if (_parent_num > 0) { //如果有父对象
                var _brother_num = $(thisForm).find("[data-parent=" + _parent + "]").size(); //根据parent获取兄弟对象数
                var _brother_checked_num = $(thisForm).find("[data-parent=" + _parent + "]:checked").size(); //根据parent获取兄弟对象选中数
                if (_brother_num > 0 && _brother_checked_num < _brother_num) { //如果有兄弟对象且兄弟对象选中数小于实际数，则设置父对象未选中
                    $(thisForm).find("#" + _parent).removeAttr("checked");
                } else {
                    $(thisForm).find("#" + _parent).prop("checked", "checked");
                }
                var _parent_this = $(thisForm).find("#" + _parent).data("parent"); //根据该父对象的parent获取爷对象
                parent_check(_parent_this); //设置爷对象
            }
        };
        //设置子对象
        var child_check = function(_child) {
            var _child_obj = $(thisForm).find("[data-parent=" + _child + "]"); //获取子对象
            var _checked = $(thisForm).find("#" + _child).prop("checked"); //获取父对象的选中状态
            if (_child_obj) { //如果有子对象
                _child_obj.each(function() { //遍历
                    var _disabled = $(this).attr("disabled");
                    if (_checked) { //根据父对象的选中状态，设置子对象的选中状态
                        if (_disabled) {
                            $(this).removeAttr("checked");
                        } else {
                            $(this).prop("checked", "checked");
                        }
                    } else {
                        $(this).removeAttr("checked");
                    }
                    var _child_this = $(this).attr("id"); //根据该子对象的id获取孙对象
                    child_check(_child_this); //设置孙对象
                });
            }
        };
    };
})(jQuery);