<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

return array(
    'var_default' => array(
        'site_name'             => 'ginkgo Framework', //站点名称
        'timezone'              => 'Asia/Shanghai', //默认时区
        'perpage'               => 30, //默认每页记录数
        'pergroup'              => 10, //分页每组页数
        'return_type'           => 'html', //默认 返回类型
        'return_type_ajax'      => 'json', //默认 ajax 返回类型
        'jsonp_callback'        => '', //默认 jsonp 处理方法
        'jsonp_callback_param'  => '', //默认 jsonp 请求参数
    ),
    'lang' => array( //语言
        'switch'    => false, //语言开关
        'default'   => 'zh_CN', //默认语言
    ),
    'debug' => array( //调试
        'dump'  => false, //输出调试信息 false 关闭, trace 输出 Trace
        'tag'   => 'div', //调试信息包含在标签内
        'class' => 'container p-5', //调试信息包含标签的 css 类名
    ),
    'log' => array( //日志
        'file_size' => 2 * GK_MB, //日志大小限制
    ),
    'tpl' => array( //模板
        'type'      => 'php', //默认模板驱动
        'path'      => '', //默认模板路径
        'suffix'    => '', //模板后缀 (默认 .tpl.php)
    ),
    'tpl_sys' => array( //模板
        'path'      => '', //默认模板路径
        'suffix'    => '', //模板后缀 (默认 .tpl.php)
    ),
    'exception_page' => array( ),
    'session' => array( //会话
        'autostart'     => false, //自动开始
        'name'          => '', //Session ID 名称
        'type'          => 'file', //类型 (可选 db,file)
        'path'          => '', //保存路径 (默认为 /runtime/session)
        'prefix'        => 'ginkgo_', //前缀
        'cookie_domain' => '', //cookie 域名
        'life_time'     => 20 * GK_MINUTE, // session 生存时间
    ),
    'cookie' => array( //cookie
        'prefix'    => '', // cookie 名称前缀
        'expire'    => 0, // cookie 保存时间
        'path'      => '/', // cookie 保存路径
        'domain'    => '', // cookie 有效域名
        'secure'    => false, //  cookie 启用安全传输
        'httponly'  => true, // httponly 设置
        'setcookie' => true, // 是否使用 setcookie
    ),
    'auth' => array(
        'session_expire'    => 20 * GK_MINUTE,
        'remember_expire'   => 30 * GK_DAY,
    ),
    'cache' => array( //缓存
        'type'          => 'file', //类型 (可选 file)
        'prefix'        => 'ginkgo', //前缀
        'life_time'     => 24 * GK_HOUR, // cache 生存时间 0 为永久保存
    ),
    'route' => array( //路由
        'route_type'    => '', //路由模式 (可选 normal, noBaseFile)
        'url_suffix'    => '', //URL 后缀
        'default_mod'   => '', //默认模块 (默认为 index)
        'default_ctrl'  => '', //默认控制器 (默认为 index)
        'default_act'   => '', //默认动作 (默认为 index)
        'route_rule'    => array( //路由规则
            /*'index/article/index' => 'index/article/show', //静态例子 规则 => 地址
            array('article/:year/:month/:id', 'index/article/index'), //动态例子 array(规则, 地址)
            array('/^cate[\/\S+]+\/(\d+)+\S*$/i', 'index/cate/index', 'id'),*/ //正则例子 array(规则, 地址, 参数)
        ),
    ),
    'image' => array( //图片扩展名及 mime
        'gif' => array(
            'image/gif',
        ),
        'jpg' => array(
            'image/jpeg',
            'image/pjpeg'
        ),
        'jpeg' => array(
            'image/jpeg',
            'image/pjpeg'
        ),
        'jpe' => array(
            'image/jpeg',
            'image/pjpeg'
        ),
        'png' => array(
            'image/png',
            'image/x-png'
        ),
        'bmp' => array(
            'image/bmp',
            'image/x-bmp',
            'image/x-bitmap',
            'image/x-xbitmap',
            'image/x-win-bitmap',
            'image/x-windows-bmp',
            'image/ms-bmp',
            'image/x-ms-bmp',
            'application/bmp',
            'application/x-bmp',
            'application/x-win-bitmap'
        ),
    ),
    'config_extra' => array( //扩展配置
        'upload'    => true,
        'ftp'       => true,
        'smtp'      => true,
    ),
    'var_extra' => array( //扩展配置默认值
        'upload' => array(
            'limit_size'    => 200, //上传尺寸
            'limit_unit'    => 'kb', //尺寸单位
            'limit_count'   => 10, //单次上传限制
            'url_prefix'    => 'http://' . $_SERVER['SERVER_NAME'],
        ),
        'ftp' => array(
            'host'   => '', //ftp 分发
            'port'   => 21,
            'user'   => '',
            'pass'   => '',
            'path'   => '',
            'pasv'   => 'off',
        ),
        'smtp' => array(
            'host'          => '',
            'secure'        => 'off',
            'port'          => 25,
            'auth'          => 'true',
            'user'          => '',
            'pass'          => '',
            'from_addr'     => 'root@localhost',
            'from_name'     => 'root',
            'reply_addr'    => 'root@localhost',
            'reply_name'    => 'root',
            'debug'         => '0',
        ),
    ),
    'func_extra' => array(), //扩展函数
    'plugin' => array(), //插件
    'dbconfig' => array( //数据库
        'type'      => 'mysql',
        'host'      => '',
        'name'      => '',
        'user'      => '',
        'pass'      => '',
        'charset'   => 'utf8',
        'prefix'    => 'ginkgo_',
        'debug'     => false,
        'port'      => 3306,
    ),
);
