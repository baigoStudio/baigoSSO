# 安装方法

1. 将下载到的程序包解压，然后将所有文件上传到服务器，假设网站 URL 为 `http://server` 上传到根目录 /，以下说明均以此为例；
2. 建议 public 目录作为公开访问目录，其它都是公开目录之外，当然必须修改 `public/index.php` 中的相关路径。如果没法做到这点，请记得设置目录的访问权限或者添加目录的保护文件。
3. 用浏览器打开 `http://server/index.php/install/` 按提示操作。

----------

# 升级方法

1. 升级前请务必 备份数据库 如果您在最初部署 baigo CMS 时，采用了特殊的部署方法，修改了 `./config/config.inc.php` 文件，请同时备份该文件；
2. 将下载到的程序包解压，然后将所有文件上传到服务器，假设网站 URL 为 `http://server` 上传到根目录 /，以下说明均以此为例；
3. 登录后台，即用浏览器打开 `http://server/index.php/install/` 系统将自动跳转到升级界面。或者直接用浏览器打开 `http://server/index.php/install/upgrade/` 按提示操作。

----------

# 管理后台

http://server/index.php/console/

----------

# 在线帮助

<http://doc.baigo.net/sso/>

----------

# 范例程序

位于 `./_example 目录下`，范例程序未作安全验证，建议开发者自行做好防注入、放跨站等安全验证，范例程序仅仅用做示范，没有经过严格测试，请开发者自行根据文档进行测试和应用

login.php 同步登录调用例子
func.php 公用函数
sign.class.php 签名类
crypt.class.php 加解密类
notify.class.php 通知类，主要配合 sync.class.php 用
sso.class.php 单点登录类，配置好 SSO URL、APP ID 和 APP KEY，就可以直接初始化对象，然后调用类里面的方法，详情请查看程序内的注释
sync.class.php 同步类，配置好 SSO URL、APP ID 和 APP KEY，就可以直接初始化对象，然后调用类里面的方法，详情请查看程序内的注释
