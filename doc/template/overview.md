## 模板概述

baigo SSO 使用 PHP 作为模板。

用户模板位于 ./app/tpl/personal 目录下，一套模板单独一个目录，如默认模板 ./app/tpl/personal/default，以下文档全部以此为基础。注：模板目录必须使用 英文 与 数字，不能使用中文、符号等。

模板目录结构说明

    +-- default
    |   +-- forgot                    忘记密码
    |   |   +-- index.tpl.php         输入用户名
    |   |   +-- confirm.tpl.php       找回密码
    |   |
    |   +-- reg                       注册
    |   |   +-- nomail.tpl.php        重发激活邮件
    |   |
    |   +-- verify                    验证
    |   |   +-- confirm.tpl.php       确认注册
    |   |   +-- mailbox.tpl.php       确认更好邮箱
    |   |   +-- pass.tpl.php          确认更换密码
    |   |
    |   +-- common                    通用
    |   |   +-- error.tpl.php         出错信息
    |   |
    |   +-- include                   include
    |      +-- html_head.tpl.php      HTML 头部
    |      +-- html_foot.tpl.php      HTML 底部
    |      +-- personal_head.tpl.php  头部
    |      +-- personal_foot.tpl.php  底部
    |   
    +--  ...

----------

##### 变量输出

在模板中主要以输出变量的方式来显示内容，比如：

``` php
Hello, <?php echo $name; ?>！
```

运行的时候会显示： Hello, baigo！

输出根据变量类型有所区别，刚才输出的是字符串，下面是一个数组的例子：

``` php
Name：<?php echo $data['name']; ?>
Email：<?php echo $data['email']; ?>
```

> 具体变量和类型请查看具体文档

----------

##### 通用变量

以下变量为所有模板中都可输出

| 名称 | 类型 | 描述 | 备注 |
| - | - | - | - |
| $path_tpl | string | 当前模板所在的目录 | |
| $dir_root | string | 根目录 | |
| $dir_static | string | 静态文件目录 | |
| $route_root | string | 根路径 | |
| $route_personal | string | 个人模块路径 | |
| $route_misc | string | 杂项模块路径 | 通常用于显示验证码 |
| $token | string | 表单令牌 | 所有需要提交的表单都必须用 hidden 域包含这个变量，hidden 表单的名称必须为 `__token__`。 |
| $lang | object | 语言对象 |  |
| $request | object | 请求对象 |  |
| $config | array | 配置数组 |  |

----------

##### 语言变量

语言变量的输出使用 `Lang` 对象，模板中已内置，可以直接使用 `$lang`，例如：

``` php
<?php echo $lang->get('page_error'); ?>
<?php echo $lang->get('var_error'; ?>
```

----------

##### 系统变量

系统变量的输出使用 `Request` 对象，模板中已内置，可以直接使用 `$request`，例如：

``` php
<?php echo $request->server('script_name'); ?> // 输出 $_SERVER['SCRIPT_NAME'] 变量
<?php echo $request->session('user_id'); ?> // 输出 $_SESSION['user_id'] 变量
<?php echo $request->get('page'); ?> // 输出 $_GET['page'] 变量
<?php echo $request->cookie('name'); ?>  // 输出 $_COOKIE['name'] 变量
```

支持输出 `$_SERVER`、`$_POST`、`$_GET`、`$_REQUEST`、`$_SESSION` 和 `$_COOKIE` 变量，详情请查看 ginkgo 文档的 [请求 -> 输入变量](//doc.baigo.net/ginkgo/request/input)

----------

##### 配置输出

输出配置参数使用：

``` php
<?php echo $config['route']['default_mod']; ?>
<?php echo $config['route']['default_ctrl']; ?>
```

----------

##### 输出替换

在模板中以 `{:变量}` 形式的字符将会被替换。

以下为默认的输出替换

| 名称 | 描述 |
| - | - | - | - |
| {\:URL_BASE} | 当前 URL 地址，不含 QUERY_STRING，包含域名。 |
| {\:URL_ROOT} | 当前 URL 根目录，包含域名。 |
| {\:DIR_STATIC} | 静态文件目录 |
| {\:ROUTE_ROOT} | 根路径 |
| {\:ROUTE_PAGE} | 分页用的基本路径 |

----------

##### 常量输出

还可以输出常量

``` php
<?php echo PHP_VERSION; ?>
<?php echo GK_PATH_APP; ?>
```

----------

##### 包含

在任何模板内，均可以用 `include("模板路径")` 的方式来包含并执行文件，您可以在模板目录下，建立一个目录，如 inc，用来统一存放被包含的模板，如：`include("inc/head.tpl.php")`。
