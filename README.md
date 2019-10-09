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
