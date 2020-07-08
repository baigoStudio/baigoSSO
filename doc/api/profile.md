## 个人接口

由当前登录的用户操作自己的各种信息

----------

### 刷新访问口令

本接口用于刷新用户的访问口令

##### URL

http://server/index.php/api/profile/token

##### HTTP 请求方式

POST

##### 加密参数

参数进行 JSON 编码，然后将 JSON 字符串进行加密。然后把加密的结果作为 `公共请求参数` 的 code 参数。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| user_id | int | true | 优先级为 user_id &gt; user_name &gt; user_mail，三选一。 |
| user_name | string | | |
| user_mail | string | | 是否允许邮箱登录，视注册设置情况而定。 |
| user_refresh_token | string | true | 刷新口令。 |
| timestamp | int | true | UNIX 时间戳 |

##### 返回结果是否加密

是

##### 解密后的结果

| 名称 | 类型 | 描述 | 备注 |
| - | - | - | - |
| user_id | int | 用户 ID | |
| user_access_token | string | 访问口令 | |
| user_access_expire | int | 访问口令过期时间 | UNIX 时间戳 |
| timestamp | int | UNIX 时间戳 | |

解密结果示例

``` javascript
{
    "user_id": "1",
    "user_access_token": "0VHBRPQUICBKGVWXTBDQBHVEPWK",
    "user_access_expire": "1550198497",
    "timestamp": "1550198497"
}
```

----------

### 编辑个人资料

本接口用于修改当前用户的信息

`2.0` 新增

##### URL

http://server/index.php/api/profile/info

##### HTTP 请求方式

POST

##### 加密参数

参数进行 JSON 编码，然后将 JSON 字符串进行加密。然后把加密的结果作为 `公共请求参数` 的 code 参数。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| user_id | int | true | 优先级为 user_id &gt; user_name &gt; user_mail，三选一。 |
| user_name | string | | |
| user_mail | string | | 是否允许邮箱登录，视注册设置情况而定。 |
| user_pass | string | true | 密码，必须用 MD5 加密后传输 |
| user_nick | string | false | 昵称，仅在需要修改时传递该参数。 |
| user_contact | string | false | 联系方式，仅在需要修改时传递该参数。 |
| user_extend | string | false | 扩展字段，仅在需要修改时传递该参数。 |
| timestamp | int | true | UNIX 时间戳 |

加密参数示例

``` php
// 加密参数
$_arr_crypt = array(
    'user_name' => 'baigo',
    'user_pass' => md5('123456'),
    'user_contact' => array(
        'tel' => array(
            'key'   => '电话',
            'value' => '0574-88888888'
        ),
        'addr' => array(
            'key'   => '地址',
            'value' => '浙江省宁波市'
        )
    ),
    'user_extend'   => array(
        'test' => array(
            'key'   => '名称',
            'value' => '值'
        )
    ),
    'timestamp' => time(),
);

$_str_crypt   = Json::encode($_arr_crypt); // 编码
$_arr_encrypt = Crypt::encrypt($_str_crypt, $_app_key, $_app_secret); // 加密

// 公共参数
$_arr_data = array(
    'app_id'    => $_app_id,
    'app_key'   => $_app_key,
    'code'      => $_arr_encrypt['encrypt'], // 加密参数
    'sign'      => Sign::make($_str_crypt, $_app_key . $_app_secret), // 签名
);
```

##### 返回结果是否加密

否

返回结果示例

``` javascript
{
    "rcode": "y010103",
    "msg": "编辑个人资料成功",
    "prd_sso_ver": "1.1.1",
    "prd_sso_pub": 20150923
}
```

----------

### 修改密码

本接口用于修改当前用户的密码

`2.0` 新增

##### URL

http://server/index.php/api/profile/pass

##### HTTP 请求方式

POST

##### 加密参数

参数进行 JSON 编码，然后将 JSON 字符串进行加密。然后把加密的结果作为 `公共请求参数` 的 code 参数。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| user_id | int | true | 优先级为 user_id &gt; user_name &gt; user_mail，三选一。 |
| user_name | string | | |
| user_mail | string | | 是否允许邮箱登录，视注册设置情况而定。 |
| user_pass | string | true | 密码，必须用 MD5 加密后传输 |
| user_pass_new | string | true | 新密码，必须用 MD5 加密后传输。 |
| timestamp | int | true | UNIX 时间戳 |

##### 返回结果是否加密

否

返回结果示例

``` javascript
{
    "rcode": "y010103",
    "msg": "修改密码成功",
    "prd_sso_ver": "1.1.1",
    "prd_sso_pub": 20150923
}
```

----------

### 修改密保问题

本接口用于修改当前用户的密保问题

`2.0` 新增

##### URL

http://server/index.php/api/profile/secqa

##### HTTP 请求方式

POST

##### 加密参数

参数进行 JSON 编码，然后将 JSON 字符串进行加密。然后把加密的结果作为 `公共请求参数` 的 code 参数。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| user_id | int | true | 优先级为 user_id &gt; user_name &gt; user_mail，三选一。 |
| user_name | string | | |
| user_mail | string | | 是否允许邮箱登录，视注册设置情况而定。 |
| user_pass | string | true | 密码，必须用 MD5 加密后传输 |
| user_sec_ques | array | true | 密保问题，个数请根据 `用户读取` 接口的返回确定。 |
| user_sec_answ | array | true | 密保答案进行 JSON 编码后，将 JSON 字符串用 MD5 加密后传输。 |
| timestamp | int | true | UNIX 时间戳 |

加密参数示例

``` php
// 加密参数
$_arr_crypt = array(
    'user_name' => 'baigo',
    'user_pass' => md5('123456'),
    'user_sec_ques'   => array(
        '您祖母叫什么名字？',
        '您的家乡是哪里？',
        '您的生日是什么时候？',
    ),
    'user_sec_ques'   => array(
        '祖母名字',
        '宁波',
        '2014-05-06',
    ),
    'timestamp' => time(),
);

$_str_crypt   = Json::encode($_arr_crypt); // 编码
$_arr_encrypt = Crypt::encrypt($_str_crypt, $_app_key, $_app_secret); // 加密

// 公共参数
$_arr_data = array(
    'app_id'    => $_app_id,
    'app_key'   => $_app_key,
    'code'      => $_arr_encrypt['encrypt'], // 加密参数
    'sign'      => Sign::make($_str_crypt, $_app_key . $_app_secret), // 签名
);
```

##### 返回结果是否加密

否

返回结果示例

``` javascript
{
    "rcode": "y010103",
    "msg": "修改密保问题成功",
    "prd_sso_ver": "1.1.1",
    "prd_sso_pub": 20150923
}
```

----------

### 更换邮箱

本接口用于更换当前用户的邮箱

##### URL

http://server/index.php/api/profile/mailbox

##### HTTP 请求方式

POST

##### 加密参数

参数进行 JSON 编码，然后将 JSON 字符串进行加密。然后把加密的结果作为 `公共请求参数` 的 code 参数。

| 名称 | 类型 | 必需 | 描述 |
| - | - | - | - |
| user_id | int | true | 优先级为 user_id &gt; user_name &gt; user_mail，三选一。 |
| user_name | string | | |
| user_mail | string | | 是否允许邮箱登录，视注册设置情况而定。 |
| user_pass | string | true | 密码，必须用 MD5 加密后传输 |
| user_mail_new | string | true | 新邮箱 |
| timestamp | int | true | UNIX 时间戳 |

##### 返回结果是否加密

否

返回结果示例

``` javascript
{
    "rcode": "y010405",
    "msg": "更换邮箱成功",
    "prd_sso_ver": "1.1.1",
    "prd_sso_pub": 20150923
}
```
