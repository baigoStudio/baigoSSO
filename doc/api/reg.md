## 注册接口

向系统注册新用户的接口

`4.0` 新增

----------

### 用户注册

本接口用于新用户的注册。提交后 baigo SSO 会检测用户名和邮箱是否正确合法。

##### URL

http://server/index.php/api/reg/reg

##### HTTP 请求方式

POST

##### 加密参数

参数进行 JSON 编码，然后将 JSON 字符串进行加密。然后把加密的结果作为 `公共请求参数` 的 code 参数。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| user_name | string | true | 用户名 |
| user_pass | string | true | 密码，必须用 MD5 加密后传输。 |
| user_mail | string |  | 视注册设置情况而定。|
| user_nick | string | false | 昵称 |
| user_contact | string | false | 联系方式。 |
| user_extend | string | false | 扩展字段。 |
| user_ip | string | false | 用户当前 IP 地址 |
| timestamp | int | true | UNIX 时间戳 |

##### 返回结果是否加密

是

##### 解密后的结果

| 名称 | 类型 | 描述 |
| - | - | - |
| user_id | int | 用户 ID |
| user_name | string | 用户名 |
| user_status | string | 状态 |
| timestamp | int | UNIX 时间戳 |

解密结果示例

``` javascript
{
    "user_id": "1",
    "user_name": "baigo",
    "user_status": "wait",
    "timestamp": "1550198497"
}
```

----------

### 检查用户名

本接口用于检查用户名是否可以注册。

##### URL

http://server/index.php/api/reg/chkname

##### HTTP 请求方式

GET

##### 加密参数

参数进行 JSON 编码，然后将 JSON 字符串进行加密。然后把加密的结果作为 `公共请求参数` 的 code 参数。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| user_name | string | true | 用户名 |
| timestamp | int | true | UNIX 时间戳 |

##### 返回结果

是否加密：否

| 名称 | 类型 | 描述 |
| - | - | - |
| rcode | string | 返回代码。 |
| prd_sso_ver | string | baigo SSO 版本号。 |
| prd_sso_pub | string | baigo SSO 版本发布时间，格式为年月日。 |

返回结果示例

``` javascript
{
    "rcode": "y010102",
    "msg": "用户名已存在",
    "prd_sso_ver": "1.1.1",
    "prd_sso_pub": 20150923
}
```

----------

### 检查邮箱

本接口用于检查邮箱是否可以注册。

##### URL

http://server/index.php/api/reg/chkmail

##### HTTP 请求方式

GET

##### 加密参数

参数进行 JSON 编码，然后将 JSON 字符串进行加密。然后把加密的结果作为 `公共请求参数` 的 code 参数。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| user_mail | string | true | 邮箱 |
| timestamp | int | true | UNIX 时间戳 |

##### 返回结果

是否加密：否

| 名称 | 类型 | 描述 |
| - | - | - |
| rcode | string | 返回代码。 |
| prd_sso_ver | string | baigo SSO 版本号。 |
| prd_sso_pub | string | baigo SSO 版本发布时间，格式为年月日。 |

返回结果示例

``` javascript
{
    "rcode": "y010102",
    "msg": "邮箱已存在",
    "prd_sso_ver": "1.1.1",
    "prd_sso_pub": 20150923
}
```

