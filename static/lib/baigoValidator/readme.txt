v2.0.3 jQuery baigoValidator plugin 表单验证插件
(c) 2016 baigo studio - http://www.baigo.net/jquery/baigovalidator.html
License: http://www.opensource.org/licenses/mit-license.php

1 文件
    1.1 baigoValidator.js 验证插件
    1.2 baigoValidator.min.js 验证插件压缩版
    1.3 baigoValidator.css CSS 样式
    1.4 status_x.png 验证失败时的图标
    1.5 status_y.png 验证成功时的图标
    1.6 loading.gif 正在验证图标
    1.7 readme.txt 使用说明
    1.8 ajax.json ajax 验证示例

2 用法
    2.1 引入文件
        2.1.1 引入 jQuery 库 <script src="jquery.min.js" type="text/javascript"></script> 此为 jQuery 库，请自行下载 http://www.jquery.com/download/
        2.1.2 引入 baigoValidator 样式表 <link href="baigoValidator.css" type="text/css" rel="stylesheet">
        2.1.3 引入 baigoValidator 插件 <script src="baigoValidator.js" type="text/javascript"></script>

    2.2 初始化 baigoValidator，如：
        obj_form = $("#cate_form").baigoValidator(opts_validate);
        opts_validate 为配置对象，类型为 JSON，名称可以自定义

    2.3 配置需要验证的字段，如：
        var opts_validate = {
            "name": { //一级对象名
                len: { min: 1, max: 100 },
                validate: { format: "text" },
                msg: { too_long: "太长" }
            },
            "no": {
                len: { min: 4, max: 4 },
                validate: { format: "int" },
                msg: { too_long: "太长" }
            },
            "email": {
                len: { min: 1, max: 0 },
                validate: { format: "email" },
                msg: { too_short: "太短" }
            },
            "digit": {
                len: { min: 1, max: 0 },
                validate: { format: "int" },
                msg: { too_short: "太短" }
            },
            "checkbox": {
                len: { min: 1, max: 0 },
                validate: { type: "checkbox" },
                msg: { too_few: "太少" }
            },
            "user_name": {
                len: { min: 1, max: 0 },
                validate: { type: "ajax" },
                msg: { too_few: "太少" }
                ajax: { url: "http://www.baigo.net/ajax/", key: "user_name", "type" : "str" }
            }
        }
        对象详细说明请查看 3

    2.4 定义需要验证表单的 data-validate 属性，其值为一级对象名，如 data-validate 属性为空，则一级对象名为该表单的 id，当 validator.type 为 checkbox 和 radio 时，该属性不能为空值，此处需与 3.1 中的定义一致，如：
        <input type="text" id="name" data-validate="name">
        <input type="checkbox" id="check" data-validate="check">

    2.5 触发验证，如：
        var obj_form = $("#cate_form").baigoValidator(opts_validate);
        $("#cate_form").submit(function(){
            obj_form.verify();
        });

3 配置详细说明 opts_validate：验证配置，为 json 对象（名称可以自定义）
    3.1 配置的一级对象需与 2.4 中 data-validate 属性对应：
    3.2 validate：验证类型
        3.2.1 selector：表单选择器名，此对象的值必须为待验证的表单的选择器名称，建议使用 id，如未定义此对象，则默认将待验证的表单 id 作为选择器
        3.2.2 type：类型
            str：字符串
            digit：数字
            checkbox：复选框
            radio：单选框
            select：下拉框
            confirm: 确认
            ajax：AJAX 验证

        3.2.3 format：格式
            validator.type 为 str 时：
                text：普通文字
                email：Email 地址
                date：日期（2012-11-12）
                time：时间（17:32:12）
                datetime：日期时间（2012-11-12 17:32:12）
                int：整数
                digit：数字（可带小数点）
                url：网址
                alphabetDigit：字母和数字
                strDigit：中文字母数字下划线连字符
                alias: 别名（字母数字下划线连字符）
                ip：IP 地址
            validator.type 为 digit checkbox radio 和 select 时可忽略

        3.2.4 group：表单组选择器名，建议使用 id。此参数主要为配合 Bootstrap 的验证样式，如使用此参数，将为相对应的表单组附加样式类。页面中必须使用 Bootstrap，详细请参考 Bootstrap 中文网站关于表单验证样式的信息 http://v3.bootcss.com/css/#forms-control-validation。

        3.2.5 target：目标选择器，建议使用 id
            当 validator.type 为 confirm 时，必须指定本对象，以便验证两个表单值是否一样

    3.3 len：规定长度或大小，0 为无限制
        validator.type 为 str 时：
            min：最短
            max：最长
        validator.type 为 checkbox radio 和 select 时：
            min：最少
            max：最多
        validator.type 为 digit 时：
            min：最小
            max：最大

    3.4 msg：提示信息
        selector：显示信息的选择器名，建议使用 id
        too_short：太短
        too_long：太长
        too_few：太少
        too_many：太多
        too_small：太小
        too_big：太大
        format_err：格式错误
        not_match：确认不匹配
        ajax_err：ajax 地址出错
        ajaxIng：AJAX 正在查询
        ok：验证通过

    3.5 ajax：AJAX
        url：AJAX 请求地址
        key：查询关键词，既传给 AJAX 程序的内容，其值为表单值
        type：类型
            str：字符串
            digit：数字
        attach：附加查询串
        attach_selectors: 附加表单选择器（多个）
        attach_keys: 附加查询关键词（多个），暨传给 AJAX 程序的内容，其值为 attach_selectors 表单值，顺序必须与 attach_selectors 对应，插件会将两个参数转换为 attach_keys[0]=attach_selectors[0]值&attach_keys[1]=attach_selectors[1]值 的形式附加至 AJAX 查询串。

4 ajax 返回内容必须为 json，如：
    { "msg": "ok" } 成功
    { "msg": "验证失败" } 具体提示信息

