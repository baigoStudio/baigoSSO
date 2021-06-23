## 通知概述

系统在执行一些特定操作的时候，会根据应用的设置，向指定的“通知接口 URL”推送通知，“通知接口 URL”接收到通知以后，可以根据实际情况，在本地执行一些必要的程序。
 
----------

### 通知的验证

系统在推送通知时，都会携带 App ID 和 App Key 参数，要记得验证此参数是否与自己的应用相同。

同时，通知会将关键数据加密，并在“通知接口 URL”中附加签名数据，签名机制可有效防止数据被篡改，详情请查看 [API 接口 -> 公共请求参数](../api/common.md#sign)。

----------

### 通知参数示例

``` php
array(
    'code'          => 'CSMEIFh7AHYBOFIlXQwAaQE0UXENawF2WUxXUQNFVD4Ac1R%2BUSUFdQgnBmYMcARb', //加密参数
    'sign'          => '0VHBRPQUICBKGVWXTBDQBHVEPWK', //签名
    'app_id'        => '1.1.1', //SSO 版本号
    'prd_sso_pub'   => 20150923, //SSO 版本发布时间
);
```