## 开发规范

----------

##### 目录和文件
1. 目录使用小写和下划线
2. 类库以 `.class.php` 结尾
3. 控制器以 `.ctrl.php` 结尾
4. 模型以 `.mdl.php` 结尾
5. 验证器以 `.vld.php` 结尾
6. 函数以 `.func.php` 结尾
7. 配置文件以 `.inc.php` 结尾
8. 语言文件以 `.lang.php` 结尾
9. 模板以 `.tpl.php` 结尾

----------

##### 类、函数和属性命名
1. 类的命名采用驼峰法和下划线（首字母大写），例如 `User`、`User_Type`
2. 方法的命名使用驼峰法（首字母小写），例如 `getUserName`
3. 属性的命名使用驼峰法（首字母小写），例如 `tableName`、`instance`
4. 函数的命名使用小写字母和下划线（小写字母开头）的方式，例如 `get_client_ip`

----------

##### 命名空间
1. 请勿以 \ 开头
2. 应用内的类库统一使用 app 开头，后面跟类库类型，具体如下：

> * 控制器 app\ctrl 和模块命名，例如：`namespace app\ctrl\console`
> * 模型以 app\model 开头，例如：`namespace app\model`
> * 应用类库以 app\classes 开头，例如：`namespace app\classes`
> * 验证器以 app\validate 开头，例如：`namespace app\validate`
> * 分层控制器、分层模型和分层验证器由开发者在上述命名空间下自行定义，例如：`namespace app\ctrl\console\general`

3. 扩展以 extend 开头，后面跟类库类型，具体如下：

> * 插件以 extend\plugin 开头，例如：`namespace extend\plugin`
> * 其他类库 extend\类库名 开头，例如：`namespace extend\类库名`

4. 系统内核以 ginkgo 开头，例如：`namespace ginkgo`

> 请注意本手册中的示例代码为了简洁，如无指定类库的命名空间，都表示指的是 ginkgo 命名空间，例如下面的代码：

``` php
Config::get('hello');
```

请自行添加 `use ginkgo\Config` 或者使用

``` php
ginkgo\Config::get('hello');
```

----------

##### 实例命名
1. 实例名称以类型和下划线驼峰法（首字母小写）的方式，例如：`obj_response`、`ctrl_user`、`mdl_appBelong`
2. 类实例以 obj\_ 开头
3. 控制器实例以 ctrl\_ 开头
4. 模型实例以 mdl\_ 开头
5. 验证器实例以 vld\_ 开头

----------

##### 常量和配置
1. 常量以大写字母和下划线命名，例如 `GK_PATH` 和 `GK_PATH_APP`
2. 配置参数以小写字母和下划线命名，例如 `url_route_on` 和 `url_convert`

----------

##### 数据表和字段
1. 数据表和字段采用小写加下划线方式命名，并注意字段名不要以下划线开头，例如 `ginkgo_user` 表和 `user_name` 字段，不建议使用驼峰和中文作为数据表字段命名
