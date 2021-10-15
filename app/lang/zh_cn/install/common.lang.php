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
  'Installer'                             => '安装程序',
  'Error'                                 => '错误',
  'Need to execute the installer'         => '需要执行安装程序',
  'Need to execute the upgrader'          => '需要执行升级程序',
  'System already installed'              => '系统已安装',
  'PDO (The PHP Data Objects) for MySQL'  => 'PDO (PHP 数据对象) MySQL 驱动',
  'GD Library (Image Processing and GD)'  => 'GD 库 (图像处理和 GD)',
  'MBString (Multibyte String)'           => 'MBString (多字节字符串)',
  'cURL (Client URL Library)'             => 'cURL (Client URL)',
  'Missing PHP extensions'                => '缺少必要的 PHP 扩展',
  'Close'                                 => '关闭',
  'OK'                                    => '确定',
  'Cancel'                                => '取消',
  'Confirm'                               => '确定',
  'Submitting'                            => '正在提交 ...',
  'Complete'                              => '完成',

  'x030402' => '<h5>如需重新安装，请执行如下步骤：</h5>
    <ol>
      <li>删除 {:path_installed} 文件</li>
      <li>重新运行 <a href="{:route_install}">{:route_install}</a></li>
    </ol>',
);
