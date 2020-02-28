## API 接口钩子

| 名称 | 类型 | 描述 | 位置 |
| - | - | - | - |
| action_api_init | action | API 初始化时触发 | 控制器 |
| [filter_api_pm_send](#filter_api_pm_send) | filter | 发送短信时过滤 | 模型 |
| [filter_api_pm_read](#filter_api_pm_read) | filter | 读取短信时过滤 | 控制器 |
| [filter_api_pm_lists](#filter_api_pm_lists) | filter | 列出短信时过滤 | 控制器 |
| [action_api_pm_status](#action_api_pm_status) | action | 变更短信状态时过滤 | 控制器 |
| [action_api_pm_revoke](#action_api_pm_revoke) | action | 撤回短信时过滤 | 控制器 |
| [action_api_pm_delete](#action_api_pm_delete) | action | 删除短信时过滤 | 控制器 |

----------

<span id="filter_api_pm_send"></span>

##### filter_api_pm_send

* 返回、回传参数

    详情请参考 [API 接口 -> 短信](../api/pm.md)

----------

<span id="filter_api_pm_read"></span>

##### filter_api_pm_read

* 返回、回传参数

    详情请参考 [API 接口 -> 短信](../api/pm.md)

----------

<span id="filter_api_pm_lists"></span>

##### filter_api_pm_lists

* 返回、回传参数

    详情请参考 [API 接口 -> 短信](../api/pm.md#lists)

----------

<span id="action_api_pm_status"></span>

##### filter_api_pm_status

* 返回

    | 参数 | 类型 | 描述 |
    | - | - | - |
    | pm_ids | array | 被变更的短信 ID |
    | pm_status | array | 状态 |

----------

<span id="filter_api_pm_revoke"></span>

##### filter_api_pm_revoke

* 返回

    | 参数 | 类型 | 描述 |
    | - | - | - |
    | pm_ids | array | 被撤回的短信 ID |

----------

<span id="filter_api_pm_delete"></span>

##### filter_api_pm_delete

* 返回、回传参数

    详情请参考 [API 接口 -> 短信](../api/pm.md#lists)
