## 手动安装 / 升级

当用户利用本系统提供的安装、升级程序遇到困难或者希望自己手动安装和升级时，请参照本文档。

本系统的所有配置文件保存于 `./app/config` 文件夹，安装和升级过程主要是修改 `./app/config/dbconfig.inc.php` 文件。配置说明如下：

```php
return array(
    'type'    => 'mysql', // 数据库类型
    'host'    => '127.0.0.1', // 服务器地址
    'name'    => 'baigo', // 数据库名
    'user'    => 'root', // 数据库用户名
    'pass'    => '', // 数据库密码
    'port'    => '', // 数据库连接端口
    'charset' => 'utf8', // 数据库编码默认采用 utf8
    'prefix'  => 'sso_', // 数据库表前缀
);
```

只能提供手动修改数据库配置，其他程序还是需要执行，详情如下表：

| 路径 | 描述 |
| - | - |
| http://server/index.php/install | PHP 扩展验证 |
| http://server/index.php/install/index/data | 创建数据表 |
| http://server/index.php/install/index/admin | 创建管理员 |
| http://server/index.php/install/index/over | 安装完毕 |
| http://server/index.php/install/upgrade | PHP 扩展验证 |
| http://server/index.php/install/upgrade/data | 升级数据表 |
| http://server/index.php/install/upgrade/admin | 创建管理员 |
| http://server/index.php/install/upgrade/over | 升级完毕 |