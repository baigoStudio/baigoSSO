## 高级部署

高级部署方式仅适合具备一定 PHP 开发经验，且熟悉网站部署方式的高级用户，初学者不推荐；

高级部署方式可能需要更高的服务器权限，请确认您拥有足够的权限。更详细的部署方式可以参考 [ginkgo 文档](https://doc.baigo.net/ginkgo/deploy/overview)

----------

##### 上传文件

将下载到的程序包解压，然后将所有文件上传到服务器，假设网站 URL 为 http://server 项目部署目录为 /project，/project/public 目录作为公开访问目录。

----------

##### 目录结构说明

以下为默认目录结构

    +--  ...
    |
    +-- project   应用部署目录
    |   +-- app                    应用目录
    |   +-- ginkgo                 框架目录
    |   +-- extend                 扩展目录
    |   +-- public                 web 部署目录（公开访问目录）
    |   |   +-- static             静态资源存放目录（css、js、image）
    |   |   +-- index.php          入口文件
    |   |   +-- .htaccess          用于 apache 的重写
    |
    |   +-- runtime                运行时目录（可写、可设置）
    |   +-- vendor                 第三方类库目录（Composer）
    |   +-- composer.json          composer 定义文件
    |
    +--  ...


假设服务目录要更改成如下结构，移动了框架目录 `ginkgo` 和 `extend` 目录

    +--  ...
    |
    +-- ginkgo    框架目录
    +-- extend    扩展目录
    +-- project   应用部署目录
    |   +-- app                    应用目录
    |   +-- public                 web 部署目录（公开访问目录）
    |   |   +-- static             静态资源存放目录（css、js、image）
    |   |   +-- index.php          入口文件
    |   |   +-- .htaccess          用于 apache 的重写
    |   |
    |   +-- runtime                运行时目录（可写、可设置）
    |   +-- vendor                 第三方类库目录（Composer）
    |   +-- composer.json          composer 定义文件
    |
    +--  ...

----------

##### 修改入口文件

入口文件位于 `/project/public/index.php` 建议以如下方式修改：

```php
define('GK_PATH_FW', '/ginkgo/'); //框架目录
define('GK_PATH_EXTEND', '/extend/'); //扩展目录
```

----------

##### 执行安装程序

打开 http://server/index.php/install 按照安装文档的说明进行安装，升级过程类似。

