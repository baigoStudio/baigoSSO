<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------------------通用-------------------------*/
return array(
  'Error'                         => '错误',
  'Fatal error'                   => '致命错误',
  'Framework error'               => '框架错误',
  'Error details'                 => '错误详情',
  'Error type'                    => '错误类型',
  'Error message'                 => '错误信息',
  'Error file'                    => '错误所在文件',
  'Http status'                   => 'Http 状态',
  'Line number'                   => '行号',
  'Base'                          => '基本',
  'Debug backtrace'               => '调试回溯',
  'Include files'                 => '包含文件',
  'Config file not found'         => '配置文件不存在',
  'Controller not found'          => '控制器不存在',
  'Action not found'              => '动作不存在',
  'Model not found'               => '模型不存在',
  'Validator not found'           => '验证器不存在',
  'Class not found'               => '类不存在',
  'Method not found'              => '方法不存在',
  'View driver not found'         => '视图驱动不存在',
  'Unsupported database type'     => '不支持的数据库类型',
  'Create session table failed'   => '创建会话数据表失败',
  'SQL Builder not found'         => 'SQL 生成器不存在',
  'Not a valid route'             => '路由非法',
  'Rule of route not found'       => '路由规则未定义',
  'Can not connect to database'   => '无法连接数据库服务器',
  'Can not select database'       => '无法选择数据库',
  'File not found'                => '文件不存在',
  'Template not found'            => '模板不存在',
  'None'                          => '无',
  'runtime'                       => '运行时间',
  'memory'                        => '内存',
  'included'                      => '载入文件数',
  'config'                        => '配置文件数',
  'message'                       => '消息',
  'file'                          => '文件',
  'line'                          => '行号',
  'type'                          => '类型',

  E_ERROR                         => '致命错误',
  E_CORE_ERROR                    => '内核错误',
  E_COMPILE_ERROR                 => '致命编译时错误',
  E_USER_ERROR                    => '用户错误',
  E_RECOVERABLE_ERROR             => '可被捕捉的致命错误',

  E_PARSE                         => '解析错误（语法错误）',

  E_WARNING                       => '警告（非致命错误）',
  E_CORE_WARNING                  => '内核通知（非致命错误）',
  E_COMPILE_WARNING               => '编译时警告（非致命错误）',
  E_USER_WARNING                  => '用户警告',

  E_NOTICE                        => '通知',
  E_USER_NOTICE                   => '用户通知',

  E_STRICT                        => '运行时通知',

  100                             => '继续',
  101                             => '转换协议',

  200                             => '正常',
  201                             => '已创建',
  202                             => '接受',
  203                             => '非官方信息',
  204                             => '无内容',
  205                             => '重置内容',
  206                             => '局部内容',

  300                             => '多重选择',
  301                             => 'Moved Permanently',
  302                             => '找到',
  303                             => '参见其他信息',
  304                             => '未修改',
  305                             => '使用代理',
  307                             => '临时重定向',

  400                             => '错误请求',
  401                             => '未授权',
  402                             => 'Payment Required',
  403                             => '禁止',
  404                             => '未找到',
  405                             => '方法未允许',
  406                             => 'Not Acceptable',
  407                             => 'Proxy Authentication Required',
  408                             => 'Request Time-out',
  409                             => 'Conflict',
  410                             => 'Gone',
  411                             => 'Length Required',
  412                             => 'Precondition Failed',
  413                             => 'Request Entity Too Large',
  414                             => 'Request-URI Too Large',
  415                             => 'Unsupported Media Type',
  416                             => 'Requested range not satisfiable',
  417                             => 'Expectation Failed',

  500                             => 'Internal Server Error',
  501                             => 'Not Implemented',
  502                             => 'Bad Gateway',
  503                             => 'Service Unavailable',
  504                             => 'Gateway Time-out',
  505                             => 'HTTP Version not supported',
);
