范例程序

位于 ./_example 目录下，范例程序未作安全验证，建议开发者自行做好防注入、放跨站等安全验证，范例程序仅仅用做示范，没有经过严格测试，请开发者自行根据文档进行测试和应用

login.php        同步登录调用例子
func.php         公用函数
notify.class.php 通知类，主要配合 sync.class.php 用
sso.class.php    单点登录类，配置好 SSO URL、APP ID 和 APP KEY，就可以直接初始化对象，然后调用类里面的方法，详情请查看程序内的注释
sync.class.php   同步类，配置好 SSO URL、APP ID 和 APP KEY，就可以直接初始化对象，然后调用类里面的方法，详情请查看程序内的注释
crypt.class.php  加密解密类
sign.class.php   签名类

---------------------------------------------------------------------

在线帮助

http://demo.baigo.net/sso/help/