## 登录接口

用户登录接口

`4.0` 新增

----------

### 登录

用户登录接口

##### URL

http://server/index.php/api/login/login

##### HTTP 请求方式

POST

##### 加密参数

参数进行 JSON 编码，然后将 JSON 字符串进行加密。然后把加密的结果作为 `公共请求参数` 的 code 参数。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| user_id | int | true | 优先级为 user_id &gt; user_name &gt; user_mail，三选一。 |
| user_name | string | | |
| user_mail | string | | 是否允许邮箱登录，视注册设置情况而定。 |
| user_pass | string | true | 密码，必须用 MD5 加密后传输。 |
| timestamp | int | true | UNIX 时间戳 |

##### 返回结果是否加密

是

##### 解密后的结果

| 名称 | 类型 | 描述 | 备注 |
| - | - | - | - |
| user_id | int | 用户 ID |
| user_name | string | 用户名 | |
| user_mail | string | 邮箱 | |
| user_nick | string | 昵称 | |
| user_status | string | 用户状态 | |
| user_time | int | 注册时间| UNIX 时间戳 |
| user_time_login | int | 最后登录时间 | UNIX 时间戳 |
| user_access_token | string | 访问口令| |
| user_access_expire | int | 访问口令过期时间 | UNIX 时间戳 |
| user_refresh_token | string | 刷新口令 | |
| user_refresh_expire | int | 刷新口令过期时间| UNIX 时间戳 |
| timestamp | int | UNIX 时间戳 | |

解密结果示例

``` javascript
{
    "user_id": "1",
    "user_name": "baigo",
    "user_mail": "baigo@baigo.net",
    "user_nick": "nickname",
    "user_status": "wait",
    "user_time": "1550198497",
    "user_time_login": "1550198497",
    "user_access_token": "0VHBRPQUICBKGVWXTBDQBHVEPWK",
    "user_access_expire": "1550198497",
    "user_refresh_token": "0VHBRPQUICBKGVWXTBDQBHVEPWK",
    "user_refresh_expire": "1550198497",
    "timestamp": "1550198497"
}
```
