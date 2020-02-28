## 后台钩子

| 名称 | 类型 | 描述 | 位置 |
| - | - | - | - |
| action_console_init | action | 后台初始化时触发 | 控制器 |
| [action_console_pm_status](#action_console_pm_status) | action | 变更短信状态时触发 | 控制器 |
| [action_console_pm_delete](#action_console_pm_delete) | action | 删除短信时触发 | 控制器 |
| [action_console_user_status](#action_console_user_status) | action | 变更用户状态时触发 | 控制器 |
| [action_console_user_delete](#action_console_user_delete) | action | 删除用户时触发 | 控制器 |
| action_console_head_before | action | 后台界面头部之前 | 模板 |
| action_console_head_after | action | 后台界面头部之后  | 模板 |
| action_console_navbar_before | action | 后台界面导航条之前 | 模板 |
| action_console_navbar_after | action | 后台界面导航条之后 | 模板 |
| action_console_menu_before | action | 后台界面菜单之前 | 模板 |
| action_console_menu_plugin | action | 后台界面插件菜单中 | 模板 |
| action_console_menu_end | action | 后台界面菜单末尾 | 模板 |
| action_console_menu_after | action | 后台界面菜单之后 | 模板 |
| action_console_foot_before | action | 后台界面底部之前 | 模板 |
| action_console_foot_after | action | 后台界面底部之后 | 模板 |

----------

<span id="action_console_pm_status"></span>

##### action_console_pm_status

* 返回

    | 参数 | 类型 | 描述 |
    | - | - | - |
    | pm_ids | array | 被变更的短信 ID |
    | pm_status | array | 状态 |
    
----------

<span id="action_console_pm_delete"></span>

##### action_console_pm_delete

* 返回

    | 参数 | 类型 | 描述 |
    | - | - | - |
    | pm_ids | array | 被删除的短信 ID |

----------

<span id="action_console_user_status"></span>

##### action_console_user_status

* 返回

    | 参数 | 类型 | 描述 |
    | - | - | - |
    | user_ids | array | 被变更的用户 ID |
    | user_status | array | 状态 |
    
----------

<span id="action_console_user_delete"></span>

##### action_console_user_delete

* 返回

    | 参数 | 类型 | 描述 |
    | - | - | - |
    | user_ids | array | 被删除的用户 ID |
