## 站内短信接口

对站内短信进行各种操作

----------

### 发送短信

本接口用于向指定用户发送站内短信

##### URL

http://server/index.php/api/pm/send

##### HTTP 请求方式

POST

##### 加密参数

参数进行 JSON 编码，然后将 JSON 字符串进行加密。然后把加密的结果作为 `公共请求参数` 的 code 参数。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| user_id | int | true | 优先级为 user_id &gt; user_name &gt; user_mail，三选一。 |
| user_name | string | | |
| user_mail | string | | 是否允许邮箱登录，视注册设置情况而定。 |
| user_access_token | string | true | 访问口令，必须用 MD5 加密后传输。 |
| pm_to | string | true | 接收者用户名，多个接收者请用 <kbd>,</kbd> 分隔。 |
| pm_title | string | false | 短信标题 |
| pm_content | string | true | 短信内容 |
| timestamp | int | true | UNIX 时间戳 |

##### 返回结果是否加密

否

返回结果示例

``` javascript
{
    "rcode": "y110101" //返回代码
    "msg": "发送成功",
    "prd_sso_ver": "1.1.1",
    "prd_sso_pub": 20150923
}
```

----------

### 检查未读短信

本接口用于检查是否有未读短信

##### URL

http://server/index.php/api/pm/check

##### HTTP 请求方式

GET

##### 加密参数

参数进行 JSON 编码，然后将 JSON 字符串进行加密。然后把加密的结果作为 `公共请求参数` 的 code 参数。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| user_id | int | true | 优先级为 user_id &gt; user_name &gt; user_mail，三选一。 |
| user_name | string | | |
| user_mail | string | | 是否允许邮箱登录，视注册设置情况而定。 |
| user_access_token | string | true | 访问口令，必须用 MD5 加密后传输。 |
| timestamp | int | true | UNIX 时间戳 |

##### 返回结果是否加密

否

返回结果示例

``` javascript
{
    "pm_count": "10" //未读短信数
    "prd_sso_ver": "1.1.1",
    "prd_sso_pub": 20150923
}
```

----------

### 列出短信

本接口用于列出当前用户的所有短信

##### URL

http://server/index.php/api/pm/lists

##### HTTP 请求方式

GET

##### 加密参数

参数进行 JSON 编码，然后将 JSON 字符串进行加密。然后把加密的结果作为 `公共请求参数` 的 code 参数。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| user_id | int | true | 优先级为 user_id &gt; user_name &gt; user_mail，三选一。 |
| user_name | string | | |
| user_mail | string | | 是否允许邮箱登录，视注册设置情况而定。 |
| user_access_token | string | true | 访问口令，必须用 MD5 加密后传输。 |
| pm_type | string | true | 短信类型，in（收件箱）、out（已发送）。 |
| pm_status | string | false | 短信状态，wait（未读）、read（已读）。 |
| pm_ids | string | false | 短信 ID，多个 ID 请用 <kbd>,</kbd> 分隔。 |
| perpage | int | false | 每页列出短信数。 |
| key | string | false | 关键词 |
| timestamp | int | true | UNIX 时间戳 |

##### 返回结果是否加密

是

解密后的结果

| 键名 | 类型 | 描述 | 备注 |
| - | - | - | - |
| pmRows | array | 短信列表 | 符合搜索条件的所有短信 |
| pageRow | array | 分页参数 | |
| timestamp | int | UNIX 时间戳 | |

----------

### 读取短信

本接口用于读取指定的短信

##### URL

http://server/index.php/api/pm/read

##### HTTP 请求方式

GET

##### 加密参数

参数进行 JSON 编码，然后将 JSON 字符串进行加密。然后把加密的结果作为 `公共请求参数` 的 code 参数。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| user_id | int | true | 优先级为 user_id &gt; user_name &gt; user_mail，三选一。 |
| user_name | string | | |
| user_mail | string | | 是否允许邮箱登录，视注册设置情况而定。 |
| user_access_token | string | true | 访问口令，必须用 MD5 加密后传输。 |
| pm_id | int | true | 短信 ID |
| timestamp | int | true | UNIX 时间戳 |

##### 解密后的结果

| 键名 | 类型 | 描述 | 备注 |
| - | - | - | - |
| pm_id | int | 短信 ID | |
| pm_send_id | int | 已发送短信 ID | 此字段只有已发送短信具备，用来定义发出的短信 ID。 |
| pm_title | string | 标题 | |
| pm_content | string | 内容 | |
| pm_from | int | 发送者用户 ID | |
| pm_to | int | 接收者用户 ID | |
| pm_status | string | 短信状态 | 可能的值为 wait（未读）、read（已读） |
| pm_time | int | 发送时间 | UNIX 时间戳 |
| fromUser | array | 发送者用户信息 | |
| toUser | array | 接收者用户信息 | |
| timestamp | int | UNIX 时间戳 | |

##### 返回结果是否加密

是

解密结果示例

``` javascript
{
    "pm_id": "1", //短信 ID
    "pm_title": "test", //标题
    "pm_content": "test", //内容
    "pm_from": "1", //发送者用户 ID
    "pm_to": "1", //接收者用户 ID
    "pm_status": "read" //短信状态
    "pm_time": "1550198497", //发送时间
    "fromUser": { //发送者用户信息
        "user_id": "1"
        "user_name": "baigo"
    },
    "toUser": { //接收者用户信息
        "user_id": "2"
        "user_name": "test"
    },
}
```

----------

### 更改短信状态

本接口用于更改短信的阅读状态

##### URL

http://server/index.php/api/pm/status

##### HTTP 请求方式

POST

##### 加密参数

参数进行 JSON 编码，然后将 JSON 字符串进行加密。然后把加密的结果作为 `公共请求参数` 的 code 参数。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| user_id | int | true | 优先级为 user_id &gt; user_name &gt; user_mail，三选一。 |
| user_name | string | | |
| user_mail | string | | 是否允许邮箱登录，视注册设置情况而定。 |
| user_access_token | string | true | 访问口令，必须用 MD5 加密后传输。 |
| pm_ids | string | true | 准备更改状态的短信 ID，多个 ID 请用 <kbd>,</kbd> 分隔。 |
| pm_status | string | true | 准备更改的短信状态，wait（未读）、read（已读）。 |
| timestamp | int | true | UNIX 时间戳 |

##### 返回结果是否加密

否

返回结果示例

``` javascript
{
    "rcode": "y110103" //返回代码
    "msg": "更改状态成功",
    "prd_sso_ver": "1.1.1",
    "prd_sso_pub": 20150923
}
```

----------

### 撤回短信

本接口用于撤回已发送但尚未阅读的短信

##### URL

http://server/index.php/api/pm/revoke

##### HTTP 请求方式

POST

##### 加密参数

参数进行 JSON 编码，然后将 JSON 字符串进行加密。然后把加密的结果作为 `公共请求参数` 的 code 参数。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| user_id | int | true | 优先级为 user_id &gt; user_name &gt; user_mail，三选一。 |
| user_name | string | | |
| user_mail | string | | 是否允许邮箱登录，视注册设置情况而定。 |
| user_access_token | string | true | 访问口令，必须用 MD5 加密后传输。 |
| pm_ids | string | true | 准备撤回的短信 ID，多个 ID 请用 <kbd>,</kbd> 分隔，此处的短信 ID 可从已发送短信的 pm_send_id 字段获得。 |
| timestamp | int | true | UNIX 时间戳 |

##### 返回结果是否加密

否

返回结果示例

``` javascript
{
    "rcode": "y110104" //返回代码
    "msg": "撤回成功",
    "prd_sso_ver": "1.1.1",
    "prd_sso_pub": 20150923
}
```

----------

### 删除短信

本接口用于删除短信

##### URL

http://server/index.php/api/pm/delete

##### HTTP 请求方式

POST

##### 加密参数

参数进行 JSON 编码，然后将 JSON 字符串进行加密。然后把加密的结果作为 `公共请求参数` 的 code 参数。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| user_id | int | true | 优先级为 user_id &gt; user_name &gt; user_mail，三选一。 |
| user_name | string | | |
| user_mail | string | | 是否允许邮箱登录，视注册设置情况而定。 |
| user_access_token | string | true | 访问口令，必须用 MD5 加密后传输。 |
| pm_ids | string | true | 准备删除的短信 ID，多个 ID 请用 <kbd>,</kbd> 分隔。 |
| timestamp | int | true | UNIX 时间戳 |

##### 返回结果是否加密

否

返回结果示例

``` javascript
{
    "rcode": "y110104" //返回代码
    "msg": "删除成功",
    "prd_sso_ver": "1.1.1",
    "prd_sso_pub": 20150923
}
```
