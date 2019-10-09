## API 概述

各种应用整合 baigo SSO 都是通过 API 接口实现的，您可以在各类应用程序中使用该接口，通过发起 HTTP 请求方式调用 baigo SSO 服务，返回 JSON 数据。

----------

##### API 调用示例

本文档的所有的示例都是在 ginkgo 框架基础之上建立的，关于 ginkgo 框架的详情请查看 [ginkgo 框架文档](//doc.baigo.net/ginkgo/)。以下为完整的调用登录接口的示例：

``` php
use ginkgo/Json;
use ginkgo/Crypt;
use ginkgo/Sign;
use ginkgo/Http;

$_time_deviation    = 300; //超时范围 (秒)
$_app_id            = 1; //APP ID
$_app_key           = 'e10adc3949ba59abbe56e057f20f883e'; //App Key
$_app_secret        = 'e10adc3949ba59ab'; //App Secret

$_arr_crypt = array(
    'user_name' => 'baigo',
    'user_pass' => md5('123456'),
    'user_ip'   => '127.0.0.1',
    'timestamp' => time(),
);

$_str_crypt   = Json::encode($_arr_crypt); //编码
$_arr_encrypt = Crypt::encrypt($_str_crypt, $_app_key, $_app_secret); //加密

if (isset($_arr_encrypt['error'])) { //加密出错
    return $_arr_encrypt;
}

$_arr_data = array(
    'app_id'    => $_app_id,
    'app_key'   => $_app_key,
    'code'      => $_arr_encrypt['encrypt'],
    'sign'      => Sign::make($_str_crypt, $_app_key . $_app_secret),
);

$_arr_get = Http::instance()->request('http://server/index.php/api/login/login/', $_arr_data, 'post'); //请求

$_arr_decrypt = Crypt::decrypt($_arr_get['code'], $_app_key, $_app_secret); //解密

if (isset($_arr_decrypt['error'])) { //解密出错
    return $_arr_decrypt;
}

if (!Sign::check($_arr_decrypt['decrypt'], $str_sign, $_app_key . $_app_secret)) {
    return 'Signature is incorrect'; //签名错误
}

$_arr_return = Json::decode($_arr_decrypt['decrypt']); //解码

if (!isset($_arr_return['timestamp'])) {
    return 'Timestamp out of range'; //缺少时间戳
}

if ($_arr_return['timestamp'] > GK_NOW + $_time_deviation || $_arr_return['timestamp'] < GK_NOW - $_time_deviation) {
    return 'Timestamp out of range'; //超时
}

print_r($_arr_return);
```

----------

##### 返回结果

baigo SSO 大部分 API 接口返回加密参数，真正内容需要解密，详情请查看具体接口。

| 参数名 | 描述 |
| - | - |
| code | 加密参数，需要解密。|
| sign | 签名 |
| rcode | 返回代码 |
| msg | 消息 |
| prd_sso_ver | baigo SSO 版本号。 |
| prd_sso_pub | baigo SSO 版本发布时间，格式为年月日。 |

返回结果示例

``` javascript
{
    "code": "CSMEIFh7AHYBOFIlXQwAaQE0UXENawF2WUxXUQNFVD4Ac1R%2BUSUFdQgnBmYMcARb", //加密参数
    "sign": "0VHBRPQUICBKGVWXTBDQBHVEPWK", //签名
    "rcode": "y010102" //返回代码
    "msg": "登录成功",
    "prd_sso_ver": "1.1.1", //SSO 版本号
    "prd_sso_pub": 20150923, //SSO 版本发布时间
}
```