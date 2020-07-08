## 用户管理

此处可以对用户进行编辑、删除、变更状态等操作。

----------

##### 批量导入

`1.1.2` 起支持批量导入功能。

可以将需要导入的用户用 Excel 进行编辑，Excel 的第一行必须为字段名，如：用户名、密码、昵称等，同时建议 Excel 文件做成 3 列，用户名、密码为必需，并且必须为经过 MD5 加密后的字符。

下面是一个 Excel 文件例子

| 用户名 | 密码 | 昵称 |
| - | - | - |
| baigo | e10adc3949ba59abbe56e057f20f883e | baigo |
| username | e10adc3949ba59abbe56e057f20f883e | username |
| ginkgo | e10adc3949ba59abbe56e057f20f883e | ginkgo |

baigo SSO 在批量导入界面提供 MD5 加密工具，将密码填入第一行的密码栏，点击生成加密结果，第二行的加密结果栏会显示加密字符串，然后将此字符串填入 Excel 的密码列。

Excel 文件制作完成后，请将 Excel 另存为 CSV 文件，如果可能，建议保存为 utf-8 编码。

> CSV 全称：逗号分隔值（Comma-Separated Values，CSV，有时也称为字符分隔值，因为分隔字符也可以不是逗号），其文件以纯文本形式存储表格数据（数字和文本）。请查看 [互动百科](http://www.baike.com/wiki/CSV)

