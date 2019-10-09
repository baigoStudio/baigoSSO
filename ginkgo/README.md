# ginkgo

##### Less is more

ginkgo 是一个免费开源的轻量级 PHP 开发框架。其演化自 baigo 的几款开源 PHP 系统。以简单简单、易用为设计、开发的宗旨，符合网络标准。安装部署简单、使用简单。遵循 Apache2 开源许可协议发布，意味着可以免费使用 ginkgo，甚至允许把基于 ginkgo 开发的应用开源或商业发布。ginkgo 支持 composer。

ginkgo 采用 MVC（Model-View-Controller）模式开发，各个模块相对独立，为系统升级提供提供便利。

----------

##### ginkgo 的环境要求如下

> * PHP >= 5.3.0
> * PDO PHP Extension
> * GD PHP Extension
> * MBstring PHP Extension
> * cURL PHP Extension

----------

##### 目录结构

    project 应用部署目录
    +-- app                   应用目录（可设置）
    |  +-- classes            类库目录
    |  |  +-- module1         模块1（示例）
    |  |  +-- module2         模块2（示例）
    |  |  +--  ...            更多模块
    |  |
    |  +-- config             配置目录
    |  |  +-- module1         模块1（示例）
    |  |  +-- module2         模块2（示例）
    |  |  +--  ...            更多模块
    |  |
    |  +-- ctrl               控制器目录
    |  |  +-- module1         模块1（示例）
    |  |  +-- module2         模块2（示例）
    |  |  +--  ...            更多模块
    |  |
    |  +-- lang               语言目录
    |  |  +-- module1         模块1（示例）
    |  |  +-- module2         模块2（示例）
    |  |  +--  ...            更多模块
    |  |
    |  +-- model              数据模型目录
    |  |  +-- module1         模块1（示例）
    |  |  +-- module2         模块2（示例）
    |  |  +--  ...            更多模块
    |  |
    |  +-- tpl                模板目录
    |  |  +-- module1         模块1（示例）
    |  |  |  +-- default      default 模板（示例）
    |  |  |  +-- test         test 模板（示例）
    |  |  |
    |  |  +-- module2         模块2（示例）
    |  |  +--  ...            更多模块
    |  |
    |  +-- validate           验证器目录
    |  |  +-- module1         模块1（示例）
    |  |  +-- module2         模块2（示例）
    |  |  +--  ...            更多模块
    |  |
    |  +-- common.php         公共文件
    |
    +-- ginkgo                框架系统目录
    |  +-- lang               语言包目录
    |  +-- core               框架内核目录
    |  +-- tpl                系统模板目录
    |  +-- base.php           框架基本引导文件
    |  +-- boot.php           框架引导文件
    |  +-- const.php          常量定义文件
    |  +-- convention.php     默认配置文件
    |  +-- CHANGELOG.md       更新日志
    |  +-- LICENSE.txt        授权说明文件
    |  +-- SPECIFICATION.md   开发规范
    |  +-- README.md          README 文件    
    |
    +-- extend                扩展目录（可定义）
    |  +-- plugin             插件目录
    |  +--  ...               更多类库
    |
    +-- public                web 部署目录（公开访问目录）
    |  +-- static             静态资源存放目录（css、js、image）
    |  +-- index.php          入口文件
    |  +-- .htaccess          用于 apache 的重写
    |
    +-- runtime               运行时目录（可写、可设置）
    +-- vendor                第三方类库目录（Composer）
    +-- composer.json         composer 定义文件

建议 public 目录作为公开访问目录，其它都是公开目录之外，当然必须修改 `public/index.php` 中的相关路径。如果没法做到这点，请记得设置目录的访问权限或者添加目录的保护文件。

框架自带了一个完整的应用目录结构和默认的入口文件，开发人员可以在这个基础之上灵活运用。

> 如果是 mac 或者 linux 环境，请确保 runtime 目录有可写权限
