## 公共请求参数

公共请求参数是指向所有接口发起请求时都必须传入的参数。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| code | string | true | 加密参数，将需要的参数进行 JSON 编码，然后将 JSON 字符串进行加密。加密时，要把 App Key 作为 key 参数，App Secret 作为 iv 参数。 |
| sign | string | true | 签名，将加密参数加密前的 JSON 字符串进行签名。签名时，要把 App Key 和 App Secret 连接作为盐。  |
| app_id | int | true | 应用的 App ID，后台创建应用时生成。 |
| app_key | string | true | 应用的 App Key，后台创建应用时生成。 |

----------

##### 加密 / 解密

API 接口的大部分请求参数需要加密以后传输，大部分返回结果也都是加密的，加密机制可有效防止数据泄漏。

`3.0` 起取消密文接口，开发者可以利用 ginkgo 框架的 ginkgo/Crypt 类进行处理，关于此类请查看 ginkgo 文档的 [杂项 -> 加密](//doc.baigo.net/ginkgo/misc/crypt)。

----------

<span id="sign"></a>

##### 签名

API 接口的请求参数和返回结果都包含签名，签名机制可有效防止数据被篡改，无论如何，必须要验证签名，以保证接收到的信息未被篡改。

`3.0` 起取消签名接口，开发者可以利用 ginkgo 框架的 ginkgo/Sign 进行处理，关于此类请查看 ginkgo 文档的 [杂项 -> 签名](//doc.baigo.net/ginkgo/misc/sign)。

----------

以下为加密 / 解密和签名的示例：

``` php
use ginkgo/Crypt;
use ginkgo/Sign;

$_app_key       = 'e10adc3949ba59abbe56e057f20f883e'; //App Key
$_app_secret    = 'e10adc3949ba59ab'; //App Secret

$_arr_encrypt   = Crypt::encrypt('{"user_id":"1"}', $_app_key, $_app_secret); //加密
$_sign          = Sign::make('{"user_id":"1"}', $_app_key . $_app_secret); //签名
$_arr_decrypt   = Crypt::decrypt($_arr_encrypt['encrypt'], $_app_key, $_app_secret); //解密
$_bool          = Sign::check($_arr_decrypt['decrypt'], $_sign, $_app_key . $_app_secret)) { //验证签名
```
