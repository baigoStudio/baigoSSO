## 分页

在所有需要用到分页的地方，都有该参数，如：短信列表，对象名一般为 pageRow。

| 键名 | 描述 | 备注 |
| - | - | - |
| page | 当前页码	 | |
| total | 总页数 | |
| first | 首页 | 值为 false 表示没有这个按钮，可用于判断是否显示。 |
| final | 尾页 | |
| prev | 上一页 | |
| next | 下一页 | |
| group_begin | 分组起始页码 | 页数过多时，需要将分页按钮分成若干组，系统默认是 10 页一组。 |
| group_end | 分组结束页码 | |
| group_prev | 上一组 | |
| group_next | 下一组 | |

返回结果示例

``` javascript
{
    "page": "1",
    "total": "35",
    "first": "1",
    "final": "35",
    "prev": false,
    "next": "2",
    "group_begin": "1",
    "group_end": "10",
    "group_prev": false,
    "group_next": "11"
}
```

分页处理示例代码：

``` php
<ul class="pagination">
    <?php if ($pageRow['first']) { ?>
        <li class="page-item">
            <a href="pm.php?page=<?php echo $pageRow['first']; ?>" title="首页" class="page-link">首页</a>
        </li>
    <?php }

    if ($pageRow['group_prev']) { ?>
        <li class="page-item d-none d-lg-block">
            <a href="pm.php?page=<?php echo $pageRow['group_prev']; ?>" title="上十页" class="page-link">...</a>
        </li>
    <?php } ?>

    <li class="page-item<?php if (!$pageRow['prev']) { ?> disabled<?php } ?>">
        <?php if ($pageRow['prev']) { ?>
            <a href="pm.php?page=<?php echo $pageRow['prev']; ?>" title="上页" class="page-link">&lt;</a>
        <?php } else { ?>
            <span title="上页" class="page-link">&lt;</span>
        <?php } ?>
    </li>

    <?php for ($iii = $pageRow['group_begin']; $iii <= $pageRow['group_end']; ++$iii) { ?>
        <li class="page-item<?php if ($iii == $pageRow['page']) { ?> active<?php } ?> d-none d-lg-block">
            <?php if ($iii == $pageRow['page']) { ?>
                <span class="page-link"><?php echo $iii; ?></span>
            <?php } else { ?>
                <a href="pm.php?page=<?php echo $iii; ?>/" title="<?php echo $iii; ?>" class="page-link"><?php echo $iii; ?></a>
            <?php } ?>
        </li>
    <?php } ?>

    <li class="page-item<?php if (!$pageRow['next']) { ?> disabled<?php } ?>">
        <?php if ($pageRow['next']) { ?>
            <a href="pm.php?page=<?php echo $pageRow['next']; ?>" title="下页" class="page-link">&gt;</a>
        <?php } else { ?>
            <span title="下页" class="page-link">&gt;</span>
        <?php } ?>
    </li>

    <?php if ($pageRow['group_next']) { ?>
        <li class="page-item d-none d-lg-block">
            <a href="pm.php?page=<?php echo $pageRow['group_next']; ?>" title="下十页" class="page-link">...</a>
        </li>
    <?php }

    if ($pageRow['final']) { ?>
        <li class="page-item">
            <a href="pm.php?page=<?php echo $pageRow['final']; ?>" title="尾页" class="page-link">尾页</a>
        </li>
    <?php } ?>
</ul>
```
