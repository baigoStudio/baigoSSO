## 验证

此处的模板适用于需要通过发送验证邮件来确认用户操作的界面

----------

##### 确认注册

用户注册以后，系统会发送一封验证邮件，验证邮件中有一个验证链接，用于验证邮箱是否合法正确，此模板便是显示验证链接的界面。

位置：./verify/confirm.tpl.php

| 变量名 | 类型 | 描述 |
| - | - | - |
| $userRow | array | 用户信息 |
| $verifyRow | array | 验证信息信息 |

变量结构：

``` php
$userRows = array(
  'user_id'       => '1', // ID
  'user_name'     => 'baigo', // 用户名
  'user_mail'     => 'baigo@baigo.net', // 邮箱
  'user_nick'     => 'nickname', // 昵称
  'user_status'   => 'wait', // 状态
);

$verifyRow = array(
  'verify_id'     => '1', // ID
  'verify_token'  => 'fwegweerIUreljiErl', // 验证口令
  'verify_mail'   => 'baigo@baigo.net', // 邮箱
);
```

----------

##### 更换邮箱

用户提请更换邮箱以后，系统会发送一封验证邮件，验证邮件中有一个验证链接，用于验证邮箱是否合法正确，此模板便是显示验证链接的界面。

位置：./verify/mailbox.tpl.php

| 变量名 | 类型 | 描述 |
| - | - | - |
| $userRow | array | 用户信息 |
| $verifyRow | array | 验证信息信息 |

变量结构：

``` php
$userRows = array(
  'user_id'       => '1', // ID
  'user_name'     => 'baigo', // 用户名
  'user_mail'     => 'baigo@baigo.net', // 邮箱
  'user_nick'     => 'nickname', // 昵称
  'user_status'   => 'wait', // 状态
);

$verifyRow = array(
  'verify_id'     => '1', // ID
  'verify_token'  => 'fwegweerIUreljiErl', // 验证口令
  'verify_mail'   => 'baigo@baigo.net', // 要更换的邮箱
);
```

----------

##### 通过邮件修改密码

用户提请通过邮件修改密码以后，系统会发送一封验证邮件，验证邮件中有一个验证链接，用于验证邮箱是否合法正确，此模板便是显示验证链接的界面。

位置：./verify/mailbox.tpl.php

| 变量名 | 类型 | 描述 |
| - | - | - |
| $userRow | array | 用户信息 |
| $verifyRow | array | 验证信息信息 |

变量结构：

``` php
$userRows = array(
  'user_id'       => '1', // ID
  'user_name'     => 'baigo', // 用户名
  'user_mail'     => 'baigo@baigo.net', // 邮箱
  'user_nick'     => 'nickname', // 昵称
  'user_status'   => 'wait', // 状态
);

$verifyRow = array(
  'verify_id'     => '1', // ID
  'verify_token'  => 'fwegweerIUreljiErl', // 验证口令
  'verify_mail'   => 'baigo@baigo.net', // 邮箱
);
```
