## 用户接口

对系统中的用户进行各种操作

----------

### 读取用户数据

本接口用于读取已注册用户的信息。

##### URL

http://server/index.php/api/user/read

##### HTTP 请求方式

GET

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
| user_id | int | 用户 ID | |
| user_name | string | 用户名 | |
| user_mail | string | 邮箱 | |
| user_nick | string | 昵称 | |
| user_status | string | 用户状态 | |
| user_sec_ques | array | 密保问题 |
| user_time | int | 注册时间 | UNIX 时间戳。 |
| user_time_login | int | 最后登录时间 | UNIX 时间戳。 |
| user_ip | string | 最后登录 IP 地址 | |
| user_contact | array | 联系方式 | |
| user_extend | array | 扩展字段 | |
| timestamp | int | UNIX 时间戳 | |

解密结果示例

``` javascript
{
    "user_id": "1",
    "user_name": "baigo",
    "user_mail": "baigo@baigo.net",
    "user_nick": "nickname",
    "user_status": "wait",
    "user_sec_ques": {
        "1": "您祖母叫什么名字？",
        "2": "您的家乡是哪里？",
        "3": "您的生日是什么时候？"
    },
    "user_time": "1550198497",
    "user_time_login": "1550198497",
    "user_ip": "127.0.0.1",
    "user_contact": {
        "tel": {
            "key": "电话",
            "value": "0574-88888888"
        },
        "addr": {
            "key": "地址",
            "value": "浙江省宁波市"
        }
    },
    "user_extend": {
        "test": {
            "key": "名称",
            "value": "值"
        }
    },
    "timestamp": "1550198497"
}
```

----------

### 编辑用户资料

本接口用于编辑用户信息。

##### URL

http://server/index.php/api/user/edit

##### HTTP 请求方式

POST

##### 加密参数

参数进行 JSON 编码，然后将 JSON 字符串进行加密。然后把加密的结果作为 `公共请求参数` 的 code 参数。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| user_id | int | true | 优先级为 user_id &gt; user_name &gt; user_mail，三选一。 |
| user_name | string | | |
| user_mail | string | | 是否允许邮箱登录，视注册设置情况而定。 |
| user_pass | string | false | 密码，仅在需要修改密码时传输该参数，必须用 MD5 加密后传输。 |
| user_mail_new | string | false | 新邮箱，仅在需要修改邮箱时传输该参数。 |
| user_nick | string | false | 昵称，仅在需要修改时传递该参数。 |
| user_contact | array | false | 联系方式，仅在需要修改时传递该参数。 |
| user_extend | array | false | 扩展字段，仅在需要修改时传递该参数。 |
| timestamp | int | true | UNIX 时间戳 |

##### 返回结果是否加密

否

返回结果示例

``` javascript
{
    "rcode": "y010103",
    "msg": "编辑用户资料成功",
    "prd_sso_ver": "1.1.1",
    "prd_sso_pub": 20150923
}
```

----------

### 已迁移接口

| 原接口 | 迁移至 | 新 URL | 版本 |
| - | - | - | - |
| 用户注册 | 注册接口 | http://server/index.php/api/reg/reg | `4.0` 新增 |
| 检查用户名 | 注册接口 | http://server/index.php/api/reg/chkname | `4.0` 新增 |
| 检查邮箱 | 注册接口 | http://server/index.php/api/reg/chkmail | `4.0` 新增 |
| 用户登录 | 登录接口 | http://server/index.php/api/login/login | `4.0` 新增 |
| 刷新访问口令 | 个人接口 | http://server/index.php/api/profile/token | `4.0` 新增 |
| 更换邮箱 | 个人接口 | http://server/index.php/api/profile/mailbox | `4.0` 新增 |

----------

### 已废弃接口

| 原接口 | 替代方案 | 版本 |
| - | - | - |
| 找回密码 | 直接访问 http://server/index.php/personal/forgot，也可通过 `基本信息接口` 获得该 URL | `4.0` 新增 |
| 重发激活邮件 | 直接访问 http://server/index.php/personal/reg/nomail，也可通过 `基本信息接口` 获得该 URL | `4.0` 新增 |
| 删除用户 | 不再提供 | `4.0` 新增 |
