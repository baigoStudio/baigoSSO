## 安装接口

“生成安全码”必须在第一步调用，“完成安装”必须在最后一步调用。

> 注意：本接口仅在安装 SSO 时有效，安装成功后将自动失效。

----------

### 生成安全码

本接口用于生成一个安装期间使用的安全码，本接口必须在第一步调用。

> 注意：此接口的加密参数与签名参数较为特殊，请特别注意。

##### URL

http://server/index.php/api/install/security

##### HTTP 请求方式

POST
 
##### 请求参数

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| code | string | true | 加密参数，将需要的参数进行 JSON 编码。 |
| sign | string | true | 签名，将加密参数加密前的 JSON 字符串进行签名。签名时，要把 key 作为盐。  |
| key | string | true | 通信密钥，随机生成，长度 32 位。 |

##### 加密参数

参数进行 JSON 编码，然后把结果作为 `请求参数` 的 code 参数。因为应用尚未取得安全码，所以无法对参数进行加密。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| secret | string | true | 安全码，随机生成，长度 16 位。 |
| timestamp | int | true | UNIX 时间戳 |

##### 签名参数

将加密参数加密前的 JSON 字符串进行签名，然后把结果作为 `请求参数` 的 sign 参数。因为应用尚未生成安全码，所以只需要把 key 作为签名的盐。

> 注意：此接口的签名参数只需要把 key 作为签名的盐。

##### 返回结果是否加密

否

返回结果示例

``` javascript
{
    "rcode": "y030401",
    "msg": "安全码生成成功",
    "prd_sso_ver": "1.1.1",
    "prd_sso_pub": 20150923
}
```

----------

### 公共请求参数

安装接口的公共请求参数与其他接口不同，请特别留意！

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| code | string | true | 加密参数，将需要的参数进行 JSON 编码，然后将 JSON 字符串进行加密。加密时，要把 key 作为 key 参数，secret 作为 iv 参数。 |
| sign | string | true | 签名，将加密参数加密前的 JSON 字符串进行签名。签名时，要把 key 和 secret 连接作为盐。  |
| key | string | true | 通信密钥，作用类似于其他接口的 App Key。 |

----------

### 数据库设置

本接口用于设置数据库

##### URL

http://server/index.php/api/install/dbconfig

##### HTTP 请求方式

POST
 
##### 加密参数

参数进行 JSON 编码，然后将 JSON 字符串进行加密。然后把加密的结果作为 `公共请求参数` 的 code 参数。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| type | string | true | 数据库服务器 |
| host | string | true | 数据库服务器 |
| port | string | true | 服务器端口 |
| name | string | true | 数据库名称 |
| user | string | true | 数据库用户名 |
| pass | string | true | 数据库密码 |
| charset | string | true | 数据库字符编码 |
| prefix | string | true | 数据表前缀 |
| timestamp | int | true | UNIX 时间戳 |
 
##### 返回结果是否加密

否
 
返回结果示例

``` javascript
{
    "rcode": "y010102",
    "msg": "数据库设置成功",
    "prd_sso_ver": "1.1.1", //SSO 版本号
    "prd_sso_pub": 20150923, //SSO 版本发布时间
}
``` 

----------

### 创建数据

本接口用于创建数据表和基础数据

##### URL

http://server/index.php/api/install/data

##### HTTP 请求方式

POST
 
##### 加密参数

参数进行 JSON 编码，然后将 JSON 字符串进行加密。然后把加密的结果作为 `公共请求参数` 的 code 参数。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| timestamp | int | true | UNIX 时间戳 |
 
##### 返回结果是否加密

否

返回结果示例

``` javascript
{
    "rcode": "y010102" //返回代码
    "msg": "创建数据成功",
    "prd_sso_ver": "1.1.1", //SSO 版本号
    "prd_sso_pub": 20150923, //SSO 版本发布时间
}
``` 

----------

### 创建管理员

本接口用于创建管理员。

##### URL

http://server/index.php/api/install/admin

##### HTTP 请求方式

POST

##### 加密参数

参数进行 JSON 编码，然后将 JSON 字符串进行加密。然后把加密的结果作为 `公共请求参数` 的 code 参数。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| admin_name | string | true | 管理员用户名 |
| admin_pass | string | true | 密码，必须用 MD5 加密后传输。 |
| timestamp | int | true | UNIX 时间戳 |
 
##### 返回结果是否加密

是

##### 解密后的结果

| 名称 | 类型 | 描述 |
| - | - | - |
| admin_id | int | 用户 ID |
| admin_name | string | 用户名 |
| admin_status | string | 状态 |
| timestamp | int | UNIX 时间戳 |

解密结果示例

``` javascript
{
    "admin_id": "1",
    "admin_name": "baigo",
    "admin_status": "wait",
    "timestamp": "1550198497"
}
```
  
----------

### 读取安装状态

本接口用于读取已安装的信息，包括数据库设置、已创建的数据、已创建的管理员等，主要用于完成安装前的信息确认。

##### URL

http://server/index.php/api/install/get_status

##### HTTP 请求方式

GET

##### 加密参数

参数进行 JSON 编码，然后将 JSON 字符串进行加密。然后把加密的结果作为 `公共请求参数` 的 code 参数。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| timestamp | int | true | UNIX 时间戳 |
 
##### 返回结果是否加密

是

##### 解密后的结果

| 名称 | 类型 | 描述 |
| - | - | - |
| dbconfig | array | 数据库配置信息 |
| data | array | 已创建的数据信息 |
| admin | array | 最后创建的管理员信息 |
| timestamp | int | UNIX 时间戳 |

解密结果示例

``` javascript
{
    "dbconfig": {
        "type": "mysql",
        "host": "127.0.0.1",
        "name": "baigo",
        "user": "root",
        "pass": "",
        "port": "",
        "charset": "utf8",
        "prefix": "sso_"
    },
    "data": {
        "table": {
            "sso_admin": {
                "rcode": "x030401",
                "msg": "创建成功",
                "status": "y",
            },
            "sso_app"{
                "rcode": "x030401",
                "msg": "创建成功",
                "status": "y",
            },
            "sso_app_belong"{
                "rcode": "x030401",
                "msg": "创建成功",
                "status": "y",
            },
            "sso_pm"{
                "rcode": "x030401",
                "msg": "创建成功",
                "status": "y",
            },
            "sso_user"{
                "rcode": "x030401",
                "msg": "创建成功",
                "status": "y",
            },
            "sso_verify"{
                "rcode": "x030401",
                "msg": "创建成功",
                "status": "y",
            }
        },
        "view": {
            "sso_user_app_view"{
                "rcode": "x030401",
                "msg": "创建成功",
                "status": "y",
            }
        }
    },
    "admin": {
        "0": {
            "admin_id": "1",
            "admin_name": "baigo",
            "admin_status": "wait"
        }
    },
    "timestamp": "1550198497"
}
```

----------

### 完成安装

本接口用于通知系统安装已完成，本接口必须在最后一步调用，本接口调用成功后，安装接口将全部失效。

##### URL

http://server/index.php/api/install/over

##### HTTP 请求方式

POST

##### 加密参数

参数进行 JSON 编码，然后将 JSON 字符串进行加密。然后把加密的结果作为 `公共请求参数` 的 code 参数。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| app_name | string | true | 应用名称 |
| app_url_notify | string | true | 通知 URL |
| app_url_sync | string | true | 同步 URL |
| timestamp | int | true | UNIX 时间戳 |

##### 返回结果是否加密

是

##### 解密后的结果

| 名称 | 类型 | 描述 |
| - | - | - |
| base_url | string | API 接口的基本 URL |
| app_id | int | 调用 API 接口所需的 App ID |
| app_key | string | 调用 API 接口所需的 App Key |
| app_secret | string | 解密所需的 App Secret |
| timestamp | int | UNIX 时间戳 |

解密结果示例

``` javascript
{
    "base_url": "http://server/index.php/api",
    "app_id": "1",
    "app_key": "sfewrw8084382h2r9fdsw9ey5whfDISORwegds",
    "app_secret": "sfewrw8084382h2r9fdsw9",
}
```
