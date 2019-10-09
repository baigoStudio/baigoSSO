## 通用

----------

##### 出错信息

用来显示用户操作以后成功与否的信息。

位置：./common/error.tpl.php

| 变量名 | 类型 | 描述 |
| - | - | - |
| $rcode | string | 返回代码 |
| $msg | string | 消息内容 |

实例：

``` php
<h3 class="text-danger">
    <?php echo $lang->get($msg); ?>
</h3>

<div class="text-danger">
    <?php echo $msg; ?>
</div>

<div class="text-danger lead">
    <?php echo $rcode; ?>
</div>
```
