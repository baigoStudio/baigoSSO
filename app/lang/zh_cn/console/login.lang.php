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
    'Access denied'                     => '拒绝访问',
    'Console'                           => '管理后台',
    'Input error'                       => '输入错误，请检查！',
    'Token'                             => '表单令牌',
    'Login'                             => '登录',
    'Username'                          => '用户名', //用户名
    'Password'                          => '密码', //密码
    'Close'                             => '关闭',
    'OK'                                => '确定',
    'Clear cookie'                      => '清除 Cookie',
    'Clearing'                          => '正在清除 ...',
    'Captcha'                           => '验证码',
    'Captcha is incorrect'              => '验证码错误',
    'Form token is incorrect'           => '表单令牌错误',
    'Administrator not found'           => '管理员不存在',
    'Administrator is disabled'         => '管理员被禁用',
    'User not found'                    => '用户不存在',
    'User is disabled'                  => '用户被禁用',
    'Password is incorrect'             => '密码错误',
    'Logging in'                        => '正在登录 ...',
    'Remember me'                       => '记住我',
    'Refresh'                           => '换一个',
    'Forgot password'                   => '忘记密码',
    'Redirecting'                       => '正在跳转 ...',
    'Redirect immediately'              => '立刻跳转',
    'Login successful'                  => '登录成功',
    'Loading'                           => '正在验证',
    'Server side error'                 => '服务器错误',
    '{:attr} require'                   => '{:attr} 是必需的',
    'Size of {:attr} must be {:rule}'   => '{:attr} 的长度必须在 {:rule} 之间',
    '{:attr} must be alpha-numeric'     => '{:attr} 必须为数字和字母',
);
