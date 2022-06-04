## 忘记密码

----------

##### 第一步：输入用户名

主要用来显示找回密码的第一步：输入用户名，如果用户输入有误，将会传递 `$msg` 变量。

位置：./forgot/index.tpl.php

| 变量名 | 类型 | 描述 |
| - | - | - |
| $msg | string | 消息内容 |

实例：

``` php
<h3 class="text-danger">
  <?php echo $lang->get($msg); ?>
</h3>

<div class="text-danger">
  <?php echo $msg; ?>
</div>
```

----------

##### 第二步：找回密码

主要用来显示找回密码的第二步：找回密码。

位置：./forgot/confirm.tpl.php

| 变量名 | 类型 | 描述 |
| - | - | - |
| $userRow | array | 用户信息 |

变量结构：

``` php
$userRows = array(
  'user_id'       => '1', // ID
  'user_name'     => 'baigo', // 用户名
  'user_mail'     => 'baigo@baigo.net', // 邮箱
  'user_nick'     => 'nickname', // 昵称
  'user_status'   => 'wait', // 状态
);
```
