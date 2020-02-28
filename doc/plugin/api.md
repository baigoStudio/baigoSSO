## API 接口钩子

| 名称 | 类型 | 描述 | 位置 |
| - | - | - | - |
| action_api_init | action | API 初始化时触发 | 控制器 |
| [action_api_pm_status](#action_api_pm_status) | action | 变更短信状态时触发 | 控制器 |
| [action_api_pm_revoke](#action_api_pm_revoke) | action | 撤回短信时触发 | 控制器 |
| [action_api_pm_delete](#action_api_pm_delete) | action | 删除短信时触发 | 控制器 |
| [filter_api_pm_lists](#filter_api_pm_lists) | filter | 列出短信时过滤 | 控制器 |
| [filter_api_pm_read](#filter_api_pm_read) | filter | 读取短信时过滤 | 控制器 |
| [filter_api_pm_send](#filter_api_pm_send) | filter | 发送短信时过滤 | 模型 |

----------

<span id="action_api_pm_status"></span>

##### action_api_pm_status

* 返回

    | 参数 | 类型 | 描述 |
    | - | - | - |
    | pm_ids | array | 被变更的短信 ID |
    | pm_status | array | 状态 |
    
----------

<span id="action_api_pm_revoke"></span>

##### action_api_pm_revoke

* 返回

    | 参数 | 类型 | 描述 |
    | - | - | - |
    | pm_ids | array | 被撤回的短信 ID |
    
----------

<span id="filter_api_pm_lists"></span>

##### filter_api_pm_lists

* 返回

    | 参数 | 类型 | 描述 |
    | - | - | - |
    | pmRows | array | 短信列表 |
    | pageRow | array | 分页参数 |

* 回传参数

    | 参数 | 类型 | 描述 |
    | - | - | - |
    | pmRows | array | 短信列表 |
    | pageRow | array | 分页参数 |

----------

<span id="filter_api_pm_read"></span>

##### filter_api_pm_read

* 返回

    | 参数 | 类型 | 描述 |
    | - | - | - |
    | pm_id | int | 短信 ID |
    | pm_title | string | 标题 |
    | pm_content | string | 内容 |
    | pm_time | int | 发送时间，UNIX 时间戳 |
    | fromUser | array | 发送者用户信息 |
    | toUser | array | 接收者用户信息 |

* 回传参数

    | 参数 | 类型 | 描述 |
    | - | - | - |
    | pm_id | int | 短信 ID |
    | pm_title | string | 标题 |
    | pm_content | string | 内容 |
    | pm_time | int | 发送时间，UNIX 时间戳 |
    | fromUser | array | 发送者用户信息 |
    | toUser | array | 接收者用户信息 |

----------

<span id="filter_api_pm_send"></span>

##### filter_api_pm_send

* 返回

    | 参数 | 类型 | 描述 |
    | - | - | - |
    | pm_title | string | 标题 |
    | pm_content | string | 内容 |
    | pm_time | int | 发送时间，UNIX 时间戳 |
    | pm_from | int | 发送者用户 ID |
    | pm_to | int | 接收者用户 ID |
    | pm_status | string | 短信状态 |

* 回传参数

    | 参数 | 类型 | 描述 |
    | - | - | - |
    | pm_title | string | 标题 |
    | pm_content | string | 内容 |
    | pm_time | int | 发送时间，UNIX 时间戳 |
    | pm_from | int | 发送者用户 ID |
    | pm_to | int | 接收者用户 ID |
    | pm_status | string | 短信状态 |

        