## 系统通知

系统通知与同步登录通知的区别是：系统通知是由 SSO 服务器直接发起，而同步登录通知则通常是根据同步登录接口返回的结果，由访问者浏览器发起。

系统会在“通知接口 URL”上附加相关参数，以便接收通知的系统识别。

### 公共通知参数

公共通知参数是指向所有通知发起时都传递的参数。

| 参数名 | 类型 | 描述 | 备注 |
| - | - | - | - | - |
| code | string | 加密参数 | 需要将该参数进行解密，解密时，要把 App Key 作为 key 参数，App Secret 作为 iv 参数。 |
| sign | string | 签名 | 将通知参数中的加密参数解密，得到一个 JSON 对象，直接将此对象进行签名验证，验证签名时，要把 App Key 和 App Secret 连接作为盐。 |
| app_id | int | 应用的 App ID | 后台创建应用时生成。 |
| app_key | string | 应用的 App Key | 后台创建应用时生成。 |
  
----------

### 通知测试

本接口用于测试通知接口是否正常。

##### HTTP 请求方式

POST

##### URL 附加参数

m=sso&c=notify&a=test

##### 加密参数

| 参数名 | 类型 | 描述 | 备注 |
| - | - | - | - |
| echostr | string | 输出字符串 | 直接将该字符串输出便可确认该应用的通知接口正常 |
| timestamp | int | UNIX 时间戳 | |

解密结果示例

``` javascript
{
    "echostr": "sdferi84hkdfufdsERTeugroe7treie",
    "timestamp": "1550198497"
}
```
 
----------

### 用户注册

本接口用于通知各应用有新用户注册成功。

##### HTTP 请求方式

POST

##### URL 附加参数

m=sso&c=notify&a=reg

##### 加密参数

| 参数名 | 类型 | 描述 |
| - | - | - |
| user_id | int | 用户 ID |
| user_name | string | 用户名 |
| user_mail | string | 邮箱 |
| user_nick | string | 昵称 |
| user_contact | array | 联系方式 |
| user_extend | array | 扩展信息 |
| timestamp | int | UNIX 时间戳 |

解密结果示例

``` javascript
{
    "user_id": "1", //用户 ID
    "user_name": "baigo", //用户名
    "user_mail": "baigo@baigo.net", //邮箱
    "user_nick": "nickname" //昵称
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

本接口用于通知各应用用户信息编辑成功。

##### HTTP 请求方式

POST

##### URL 附加参数

m=sso&c=notify&a=edit

##### 加密参数

| 参数名 | 类型 | 描述 |
| - | - | - |
| user_id | true | int | 用户 ID |
| user_name | true | string | 用户名 |
| user_mail | true | string | 邮箱 |
| user_nick | true | string | 昵称 |
| user_contact | true | array | 联系方式 |
| user_extend | true | array | 扩展信息 |
| timestamp | int | UNIX 时间戳 |

解密结果示例

``` javascript
{
    "user_id": "1", //用户 ID
    "user_name": "baigo", //用户名
    "user_mail": "baigo@baigo.net", //邮箱
    "user_nick": "nickname" //昵称
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

### 修改个人信息

本接口用于通知各应用用户信息编辑成功。

##### HTTP 请求方式

POST

##### URL 附加参数

m=sso&c=notify&a=info

##### 加密参数

| 参数名 | 类型 | 描述 |
| - | - | - |
| user_id | true | int | 用户 ID |
| user_nick | true | string | 昵称 |
| user_contact | true | array | 联系方式 |
| user_extend | true | array | 扩展信息 |
| timestamp | int | UNIX 时间戳 |

解密结果示例

``` javascript
{
    "user_id": "1", //用户 ID
    "user_nick": "nickname" //昵称
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

### 更换邮箱

本接口用于通知各应用用户信息编辑成功。

##### HTTP 请求方式

POST

##### URL 附加参数

m=sso&c=notify&a=mailbox

##### 加密参数

| 参数名 | 类型 | 描述 |
| - | - | - |
| user_id | true | int | 用户 ID |
| user_mail | true | string | 邮箱 |
| timestamp | int | UNIX 时间戳 |

解密结果示例

``` javascript
{
    "user_id": "1", //用户 ID
    "user_mail": "baigo@baigo.net", //邮箱
    "timestamp": "1550198497"
}
```
 
----------

### 删除用户

| 原接口 | 替代方案 | 版本 |
| - | - | - |
| 删除用户 | 不再提供 | `4.0` 新增 |
