/*
v1.0.1 jQuery baigoValidator plugin 表单验证插件
(c) 2015 baigo studio - http://www.baigo.net/jQueryPlugins/baigoValidator/
License: http://www.opensource.org/licenses/mit-license.php
*/

(function($){
	$.fn.baigoValidator = function (fileds, options) {

		if(this.length == 0) {
			return this;
		}

		// support mutltiple elements
		if(this.length > 1){
			this.each(function(){
				$(this).baigoValidator(fileds);
			});
			return this;
		}

    	var _err       = 0; //定义错误数
    	var thisForm   = $(this); //定义表单对象
		var el         = this;
		var _status; //定义状态

		var defaults = {
			class_ok: "baigoValidator_y",
			class_err: "baigoValidator_x",
			class_loading: "baigoValidator_loading"
		};

		var opts = $.extend(defaults, options);

		/*------取得长度（字节数）------
		@_str 需验证字符串

		返回字节数
		*/
		var getLength = function (_str) {
			var _numLenth = 0;
			if (_str) {
				for (var i = 0; i < _str.length; i++) {
					_chkCode = _str.charCodeAt(i);
					if (_chkCode < 0x007f) {
			            _numLenth++;
					} else if (_chkCode <= 0x07ff) {
			            _numLenth += 2;
			        } else if (_chkCode <= 0xffff) {
			            _numLenth += 3;
			        } else{
						_numLenth += 4;
					}
				}
			}
			return _numLenth;
		}

		/*------验证长度------
		@_str 需验证字符串
		@_length(min, max) 数组，(最小长度, 最大长度) 0 为不限制

		返回字符
		too_short 太短
		too_long 太长
		ok 正常
		*/
		var validateLeng = function (_str, _length) {
			if(_length.min > 0 && getLength(_str) < _length.min){
				_status = "too_short"; //如果定义最小长度，且短于，则返回太短
			} else if(_length.max > 0 && getLength(_str) > _length.max){
				_status = "too_long"; //如果定义最大长度，且长于，则返回太长
			} else {
				_status = "ok"; //返回正确
			}
			return _status;
		}

		/*------验证格式------
		@_str 需验证字符串
		@_format 格式，text 为任意
		*/
		var validateReg = function (_str, _format) {
			switch(_format) {
				case "date":
					var _reg = /^[0-9]{4}-(((0?[13578]|(10|12))-(0?[1-9]|[1-2][0-9]|3[0-1]))|(0?2-(0[1-9]|[1-2][0-9]))|((0?[469]|11)-(0[1-9]|[1-2][0-9]|30)))$/; //日期
				break;
				case "time":
					var _reg = /^(([1-9]{1})|([0-1][0-9])|([1-2][0-3])):([0-5][0-9])(:([0-5][0-9]))?$/;
				break;
				case "datetime": //日期时间
					var _reg = /^[0-9]{4}-(((0?[13578]|(10|12))-(0?[1-9]|[1-2][0-9]|3[0-1]))|(0?2-(0[1-9]|[1-2][0-9]))|((0?[469]|11)-(0[1-9]|[1-2][0-9]|30)))\s(([1-9]{1})|([0-1][0-9])|([1-2][0-3])):([0-5][0-9])(:([0-5][0-9]))?$/;
				break;
				case "int":
					var _reg = /^([+-]?)\d*$/; //整数
				break;
				case "digit":
					var _reg = /^([+-]?)\d*\.?\d+$/; //数值，可以包含小数点
				break;
				case "email":
					var _reg = /^\w{0,}(\.)?(\w+)@\w+(\.\w+).*$/; //Email
				break;
				case "url":
					var _reg = /^http[s]?:\/\/(\w|-)+(\.(\w|-)+).*$/; //URL地址
				break;
				case "alphabetDigit":
					var _reg = /^[a-z|A-Z|\d]*$/; //字母和数字
				break;
				case "strDigit":
					var _reg = /^[\u4e00-\u9fa5|\uf900-\ufa2d|\w]*$/; //字母中文数字下划线减号
				break;
				case "ip":
					var _reg = /^(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])$/; //IP 地址
				break;
				default:
					var _reg = ""; //默认
				break;
			}
			if (_str && _format != "text") { //如果值不为空，且格式不为text则验证
				if(_reg.test(_str)) {
					return true; //验证通过，返回正确
				} else {
					return false; //验证失败，返回错误
				}
			} else {
				return true; //如果为text，直接返回正确
			}
		}

		/*------验证是否为字符串------
		@_str 需验证的字符串
		@_length(min, max) 数组，(最小长度, 最大长度) 0 为不限制
		@_format 格式
		*/
		var isText = function (_str, _length, _format) {
			var _status_leng = validateLeng(_str, _length);
			if(_status_leng != "ok") {
				_status = _status_leng; //如验证长度出错，直接返回错误
			} else {
				if(validateReg(_str, _format)) {
					_status = "ok"; //格式验证成功，返回正确
				} else {
					_status = "format_err"; //格式验证失败，返回错误
				}
			}
			return _status;
		}

		/*------验证是否相同------
		@_str 需验证的字符串
		@_str_target 需验证的目标字符串
		@_length(min, max) 数组，(最小长度, 最大长度) 0 为不限制
		*/
		var isConfirm = function (_str, _str_target, _length) {
			var _status_leng = validateLeng(_str, _length);
			if(_status_leng != "ok") {
				_status = _status_leng; //如验证长度出错，直接返回错误
			} else {
				if(_str == _str_target) {
					_status = "ok"; //验证成功，返回正确
				} else {
					_status = "not_match"; //验证失败
				}
			}
			return _status;
		}

		/*------验证数字------
		@_num 需验证的个数
		@_length(min, max) 数组，(最小个数, 最大个数) 0 为不限制
		*/
		var isDigit = function (_num, _length, _format) {
			if(validateReg(_num, _format)) {
				if(_length.min > 0 && _num < _length.min){
					_status = "too_small"; //如果定义最小数，且小于，则返回太小
				} else if(_length.max > 0 && _num > _length.max){
					_status = "too_big"; //如果定义最大数，且大于，则返回太大
				} else {
					_status = "ok"; //返回正确
				}
			} else {
				_status = "format_err"; //格式验证失败，返回错误
			}
			return _status;
		}

		/*------验证个数------
		@_num 需验证的个数
		@_length(min, max) 数组，(最小个数, 最大个数) 0 为不限制
		*/
		var isNum = function (_num, _length) {
			if(_length.min > 0 && _num < _length.min){
				_status = "too_few"; //如果定义最小个数，且少于，则返回太少
			} else if(_length.max > 0 && _num > _length.max){
				_status = "too_many"; //如果定义最大个数，且多于，则返回太多
			} else {
				_status = "ok"; //返回正确
			}
			return _status;
		}

		/*------ajax 验证------
		@_str 需验证的字符串
		@_length(min, max) 数组，(最小长度, 最大长度) 0 为不限制
		@_format 格式
		@_ajax(url, key) 数组，(URL, 关键词)
		*/
		var isAjax = function (_str, _length, _validate, _ajax, _msg, _validate_id) {
			switch (_ajax.type) {
				case "digit":
					_status = isDigit(_str, _length, _validate.format); //验证字符串
				break;
				default:
					_status = isText(_str, _length, _validate.format); //验证字符串
				break;
			}

			if (_status != "ok") {

				return _status;

			} else {

				_ajaxData = _ajax.key + "=" + _str + "&a=" + Math.random();
				if (_ajax.attach) {
					_ajaxData = _ajaxData + "&" + _ajax.attach;
				}
				if (_ajax.attach_selectors && _ajax.attach_keys) {
					$.each(_ajax.attach_selectors, function(_index, _selector){
						var _str_attachs    = $(_selector).val();
						_ajaxData = _ajaxData + "&" + _ajax.attach_keys[_index] + "=" + _str_attachs;
					});
				}

				$.ajax({
					url: _ajax.url, //url
					//async: false, //设置为同步
					dataType: "json", //数据格式为json
					data: _ajaxData,
					beforeSend: function(){
						outPut(_msg.id, _msg.ajaxIng, opts.class_loading, "has-warning");
					}, //输出消息
					success: function(_result){ //读取返回结果
						switch (_result.re) {
							case "ok": //匹配验证错误
								outPut(_msg.id,  _validate.group, "&nbsp;", opts.class_ok, "has-success"); //输出消息
							break;

							default: //数据库验证错误
								$("#" + _validate_id).focus(); //焦点
								outPut(_msg.id,  _validate.group, _result.re, opts.class_err, "has-error"); //输出消息
							break;
						}
						_status = _result.re
					}
				});

				return _status;
			}
		}

		/*------输出消息------
		@_id 消息ID
		@_msg 消息内容
		*/
		var outPut = function (_msg_id, _grou_id, _msg, _class, _bootcss) {
			if (_msg_id) {
				$("#" + _msg_id).empty(); //先清空提示框
				$("#" + _msg_id).append(_msg); //填充消息内容
				$("#" + _msg_id).attr("class", _class); //替换样式
				$("#" + _msg_id).show(); //显示消息
			}
			if (_grou_id) {
				$("#" + _grou_id).attr("class", _bootcss); //定义样式
			}
		}

		var validateStr = function (_validate_id) {
			var _set_data    = fileds[_validate_id]; //获取验证设置
			//alert(_set_data.validate.type);
			var _length      = _set_data.length; //长度
			var _validate    = _set_data.validate; //格式
			var _msg         = _set_data.msg; //消息
			var _result;
			//alert(_validate.type);
			switch (_validate.type) {
				case "digit":
					var _str   = $("#" + _validate_id).val(); //获取表单值
					_status    = isDigit(_str, _length, _validate.format); //验证字符串
				break;
				case "radio":
				case "checkbox":
					var _num   = $("[group='" + _validate_id + "']:checked").size(); //获取表单选中数
					_status    = isNum(_num, _length); //验证个数
				break;
				case "select":
					if (_length.min > 1) { //如最少大于 1 为多选，则验证个数
						var _num  = $("#" + _validate_id + " :selected").size(); //获取表单选中数
						_status   = isNum(_num, _length); //验证个数
					} else { //单选则验证值
						var _str  = $("#" + _validate_id).val(); //获取表单值
						_status   = isText(_str, _length, "text"); //验证字符串
						if (_status == "too_short") {
							_status = "too_few";
						}
					}
				break;
				case "ajax":
					var _ajax  = _set_data.ajax; //ajax
					var _str   = $("#" + _validate_id).val(); //获取表单值
					_status    = isAjax(_str, _length, _validate, _ajax, _msg, _validate_id); //ajax验证
				break;
				case "confirm":
					var _str           = $("#" + _validate_id).val(); //获取表单值
					var _str_target    = $("#" + _validate.target).val(); //获取表单值
					_status            = isConfirm(_str, _str_target, _length); //验证字符串
				break;
				default:
					var _str   = $("#" + _validate_id).val(); //获取表单值
					_status    = isText(_str, _length, _validate.format); //验证字符串
				break;
			}
			switch (_status) {
				case "ok":
					outPut(_msg.id, _validate.group, "&nbsp;", opts.class_ok, "has-success"); //输出消息
					_result = true;
				break;
				default:
					$("#" + _validate_id).focus(); //焦点
					outPut(_msg.id,  _validate.group, _msg[_status], opts.class_err, "has-error"); //输出消息
					_result = false;
				break;
			}
			return _result;
		}

		el.validateSubmit = function () {
			_err = 0;
			$(thisForm).find(".validate:visible").each(function(){
	        	//_err = 0; //定义错误数
				var _group  = $(this).attr("group");
				var _id     = $(this).attr("id");
				if (typeof _group == "undefined") {
					_validate_id = _id;
				} else {
					_validate_id = _group;
				}
				//alert(_validate_id);
				if (!validateStr(_validate_id)) {
					_err++;
				}
			});

			if (_err > 0) {
				return false;
			} else {
				return true;
			}
		}

		$(thisForm).find(".validate:visible").change(function(){
			var _group   = $(this).attr("group");
			var _id      = $(this).attr("id");
			if (typeof _group == "undefined") {
				_validate_id = _id;
			} else {
				_validate_id = _group;
			}
			validateStr(_validate_id);
		});

		return this;
	}
})(jQuery);