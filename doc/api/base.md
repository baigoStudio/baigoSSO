## 基本信息接口

获取系统基本信息的接口

`4.0` 新增

----------

### 站内短信

本接口用于获取站内短信的类型值和状态值

##### URL

http://server/index.php/api/base/pm

##### HTTP 请求方式

GET

##### 加密参数

参数进行 JSON 编码，然后将 JSON 字符串进行加密。然后把加密的结果作为 `公共请求参数` 的 code 参数。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| timestamp | int | true | UNIX 时间戳 |

##### 返回结果

是否加密：否

| 名称 | 类型 | 描述 |
| - | - | - |
| status | array | 状态 |
| type | array | 类型 |
| prd_sso_ver | string | baigo SSO 版本号。 |
| prd_sso_pub | string | baigo SSO 版本发布时间，格式为年月日。 |

返回结果示例

``` javascript
{
    "staus": {
        "wait": "未读",
        "read": "已读"
    },
    "type": {
        "in": "收件箱",
        "out": "已发送"
    },
    "prd_sso_ver": "1.1.1",
    "prd_sso_pub": 20150923
}
```

----------

### 读取 URL

本接口用于获取忘记密码、注册等相关 URL

##### URL

http://server/index.php/api/base/urls

##### HTTP 请求方式

GET

##### 加密参数

参数进行 JSON 编码，然后将 JSON 字符串进行加密。然后把加密的结果作为 `公共请求参数` 的 code 参数。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| timestamp | int | true | UNIX 时间戳 |

##### 返回结果

是否加密：否

| 名称 | 类型 | 描述 |
| - | - | - |
| url_forgot | string | 忘记密码的 URL |
| url_nomail | string | 重发激活邮件的 URL |
| prd_sso_ver | string | baigo SSO 版本号。 |
| prd_sso_pub | string | baigo SSO 版本发布时间，格式为年月日。 |

返回结果示例

``` javascript
{
    "url_forgot": "http://server/index.php/personal/forgot",
    "url_nomail": "http://server/index.php/personal/reg/nomail",
    "prd_sso_ver": "1.1.1",
    "prd_sso_pub": 20150923
}
```