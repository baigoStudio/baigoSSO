/*
v0.0.9 jQuery baigoSubmit plugin 表单全选插件
(c) 2013 baigo studio - http://www.baigo.net/baigoSubmit/
License: http://www.opensource.org/licenses/mit-license.php
*/

(function($){
	$.fn.baigoSubmit = function (options) {

		if(this.length == 0) {
			return this;
		}

		// support mutltiple elements
		if(this.length > 1){
			this.each(function(){
				$(this).baigoSubmit(options);
			});
			return this;
		}

    	var thisForm = $(this); //定义表单对象
		var el = this;

		var defaults = {
			width: 350,
			height: 220,
			class_ok: "baigoSubmit_y",
			class_err: "baigoSubmit_x",
			class_loading: "baigoSubmit_loading",
			btn_url: "",
			btn_text: "OK",
			btn_close: "Close",
		};

		var opts = $.extend(defaults, options);

		//调用弹出框
		var callModal = function () {
			$("#ajax_box").modal("show")
		}

		var boxAppend = function () {
			$("body").append("<div class=\"modal fade\" id=\"ajax_box\">" +
				"<div class=\"modal-dialog\">" +
					 "<div class=\"modal-content\">" +
						"<div class=\"modal-body\">" +
							"<h4 id=\"box_msg\"></h4>" +
							"<div id=\"box_alert\"></div>" +
						"</div>" +
						"<div class=\"modal-footer\">" +
							"<a href=\"" + opts.btn_url + "\" id=\"btn_jump\" class=\"btn btn-primary\" target=\"_top\">" + opts.btn_text + "</a>" +
							"<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">" + opts.btn_close + "</button>" +
						"</li>" +
					"</div>" +
				"</div>" +
			"</div>");
		}

		//确认消息
		var formConfirm = function () {
			if (typeof opts.confirm_id == "undefined") {
				return true;
			} else {
				var _form_action = $("#" + opts.confirm_id).val();
				if (_form_action == opts.confirm_val) {
					if (confirm(opts.confirm_msg)) {
						return true;
					} else {
						return false;
					}
				} else {
					return true;
				}
			}
		}

		//ajax提交
		el.formSubmit = function () {
			boxAppend();
			if (formConfirm()) {
				$.ajax({
					url: opts.ajax_url, //url
					//async: false, //设置为同步
					type: "post",
					dataType: "json", //数据格式为json
					data: $(thisForm).serialize(),
					beforeSend: function(){
						$("#btn_jump").hide();
						$("#box_msg").empty();
						$("#box_msg").attr("class", opts.class_loading);
						$("#box_msg").append("loading ..."); //填充消息内容
						callModal(); //输出消息
					}, //输出消息
					success: function(_result){ //读取返回结果
						var _image_pre = _result.alert.substr(0, 1);
						if (_image_pre == "x") {
							var _class = opts.class_err;
							$("#btn_jump").hide();
						} else {
							var _class = opts.class_ok;
							$("#btn_jump").show();
						}
						$("#box_msg").empty();
						$("#box_msg").attr("class", _class);
						$("#box_msg").append(_result.msg); //填充消息内容
						$("#box_alert").empty();
						$("#box_alert").append(_result.alert);
						callModal(); //输出消息
					}
				});
			}
		}

		return this;
	}

})(jQuery);