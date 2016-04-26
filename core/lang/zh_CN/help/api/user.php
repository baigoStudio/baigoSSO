<?php
return "<a name=\"top\"></a>
    <ul class=\"list-inline\">
        <li>
            <a href=\"#reg\">用户注册</a>
        </li>
        <li>
            <a href=\"#nomail\">重发激活邮件</a>
        </li>
        <li>
            <a href=\"#login\">用户登录</a>
        </li>
        <li>
            <a href=\"#read\">读取用户数据</a>
        </li>
        <li>
            <a href=\"#edit\">编辑用户资料</a>
        </li>
        <li>
            <a href=\"#mailbox\">更换邮箱</a>
        </li>
        <li>
            <a href=\"#forgot\">找回密码</a>
        </li>
        <li>
            <a href=\"#refresh\">刷新访问口令</a>
        </li>
        <li>
            <a href=\"#del\">删除用户</a>
        </li>
        <li>
            <a href=\"#chkname\">检查用户名</a>
        </li>
        <li>
            <a href=\"#chkmail\">检查邮箱</a>
        </li>
    </ul>

    <p>&nbsp;</p>

    <a name=\"reg\"></a>
    <h3>用户注册</h3>

    <p class=\"text-success\">接口说明</p>
    <p>本接口用于新用户的注册。用户名、密码、邮箱、昵称为一个用户在 baigo SSO 的基本数据，提交后 baigo SSO 会按照 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=opt#reg\">注册设置</a> 检测用户名和邮箱的格式是否正确合法。</p>

    <p class=\"text-success\">URL</p>
    <p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=user</span></p>

    <p class=\"text-success\">HTTP 请求方式</p>
    <p>POST</p>

    <p class=\"text-success\">返回格式</p>
    <p>JSON</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">接口参数</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th class=\"text-nowrap\">必须</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">act_post</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>接口调用动作，值只能为 reg。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_id</td>
                        <td class=\"text-nowrap\">int</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>应用的 APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_key</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>应用的 APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_name</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>用户名</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_pass</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>密码，必须用 <mark>MD5</mark> 加密后传输。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_mail</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\"> </td>
                        <td>视注册设置情况而定。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=opt#reg\">注册设置</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_nick</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">false</td>
                        <td>昵称</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_contact</td>
                        <td class=\"text-nowrap\">array</td>
                        <td class=\"text-nowrap\">false</td>
                        <td>联系方式，详情查看 <a href=\"#contact\">联系方式格式</a>。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <p>
        本接口返回加密字符串，真正内容需要调用密文接口进行解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。
    </p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">返回结果</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">alert</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>返回代码，详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=alert\">返回代码</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">code</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>加密字符串，需要解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">key</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>解密码，配合加密字符串使用，用于解码。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">prd_sso_ver</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>baigo SSO 版本号。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">prd_sso_pub</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>baigo SSO 版本发布时间，格式为年月日。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">解密后的结果</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">Base64</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">user_id</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>用户 ID</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_access_token</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>访问口令。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_access_expire</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>访问口令过期时间，Unix 时间戳。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_refresh_token</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>刷新口令。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_refresh_expire</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>刷新口令过期时间，Unix 时间戳。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <h4>返回结果示例</h4>
    <p>
<pre><code class=\"language-javascript\">{
    &quot;user_id&quot;: &quot;MTA=&quot; //用户 ID
}</code></pre>
    </p>

    <p>&nbsp;</p>
    <div class=\"text-right\">
        <a href=\"#top\">
            <span class=\"glyphicon glyphicon-chevron-up\"></span>
            top
        </a>
    </div>
    <hr>
    <p>&nbsp;</p>

    <a name=\"nomail\"></a>
    <h3>重发激活邮件</h3>

    <p class=\"text-success\">接口说明</p>
    <p>本接口用于用户注册时重发激活邮件</p>

    <p class=\"text-success\">URL</p>
    <p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=user</span></p>

    <p class=\"text-success\">HTTP 请求方式</p>
    <p>POST</p>

    <p class=\"text-success\">返回格式</p>
    <p>JSON</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">接口参数</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th class=\"text-nowrap\">必须</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">act_post</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>接口调用动作，值只能为 mailbox。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_id</td>
                        <td class=\"text-nowrap\">int</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>应用的 APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_key</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>应用的 APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_id</td>
                        <td class=\"text-nowrap\">int</td>
                        <td class=\"text-nowrap\"> </td>
                        <td rowspan=\"3\">
                            <p>user_id、user_name、user_mail 三选一，优先级按顺序排列</p>
                            <p>其中是否允许邮箱登录，视注册设置情况而定。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=opt#reg\">注册设置</a>。</p>
                        </td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_name</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\"> </td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_mail</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\"> </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <p>
        本接口返回加密字符串，真正内容需要调用密文接口进行解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。
    </p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">返回结果</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">alert</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>返回代码，详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=alert\">返回代码</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">code</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>加密字符串，需要解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">key</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>解密码，配合加密字符串使用，用于解码。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">prd_sso_ver</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>baigo SSO 版本号。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">prd_sso_pub</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>baigo SSO 版本发布时间，格式为年月日。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">解密后的结果</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">Base64</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">user_id</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>用户 ID</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_name</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">tring</td>
                        <td>用户名</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">verify_id</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>验证 ID</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">verify_token</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>验证口令</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <h4>返回结果示例</h4>
    <p>
<pre><code class=\"language-javascript\">{
    &quot;user_id&quot;: &quot;MTA=&quot; //用户 ID
    &quot;user_name&quot;: &quot;MTA=&quot; //用户名
    &quot;verify_id&quot;: &quot;MTA=&quot; //验证 ID
    &quot;verify_token&quot;: &quot;MTA=&quot; //验证口令
}</code></pre>
    </p>

    <p>&nbsp;</p>
    <div class=\"text-right\">
        <a href=\"#top\">
            <span class=\"glyphicon glyphicon-chevron-up\"></span>
            top
        </a>
    </div>
    <hr>
    <p>&nbsp;</p>

    <a name=\"login\"></a>
    <h3>用户登录</h3>

    <p class=\"text-success\">接口说明</p>
    <p>本接口用于用户的登录验证。</p>

    <p class=\"text-success\">URL</p>
    <p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=user</span></p>

    <p class=\"text-success\">HTTP 请求方式</p>
    <p>POST</p>

    <p class=\"text-success\">返回格式</p>
    <p>JSON</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">接口参数</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th class=\"text-nowrap\">必须</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">act_post</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>接口调用动作，值只能为 login。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_id</td>
                        <td class=\"text-nowrap\">int</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>应用的 APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_key</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>应用的 APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_id</td>
                        <td class=\"text-nowrap\">int</td>
                        <td class=\"text-nowrap\"> </td>
                        <td rowspan=\"3\">
                            <p>user_id、user_name、user_mail 三选一，优先级按顺序排列</p>
                            <p>其中是否允许邮箱登录，视注册设置情况而定。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=opt#reg\">注册设置</a>。</p>
                        </td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_name</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\"> </td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_mail</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\"> </td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_pass</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>密码，必须用 <mark>MD5</mark> 加密后传输。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <p>
        本接口返回加密字符串，真正内容需要调用密文接口进行解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。
    </p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">返回结果</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">alert</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>返回代码，详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=alert\">返回代码</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">code</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>加密字符串，需要解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">key</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>解密码，配合加密字符串使用，用于解码。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">prd_sso_ver</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>baigo SSO 版本号。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">prd_sso_pub</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>baigo SSO 版本发布时间，格式为年月日。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">解密后的结果</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">Base64</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">user_id</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>用户 ID</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_name</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>用户名</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_mail</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>邮箱</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_nick</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>昵称</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_status</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>用户状态，可能的值为 enable（激活）、wait（待审）、disable（禁用）。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_time</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>注册时间，Unix 时间戳。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_time_login</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>最后登录时间，Unix 时间戳。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_ip</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>最后登录 IP 地址</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_access_token</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>访问口令。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_access_expire</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>访问口令过期时间，Unix 时间戳。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_refresh_token</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>刷新口令。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_refresh_expire</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>刷新口令过期时间，Unix 时间戳。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <h4>返回结果示例</h4>
    <p>
<pre><code class=\"language-javascript\">{
    &quot;user_id&quot;: &quot;MTA=&quot;, //用户 ID
    &quot;user_name&quot;: &quot;Zm9uZQ==&quot;, //用户名
    &quot;user_mail&quot;: &quot;Zm9uZUBiYWlnby5uZXQ=&quot;, //邮箱
    &quot;user_nick&quot;: &quot;Zm9uZQ==&quot;, //昵称
    &quot;user_time&quot;: &quot;MTM5NDQxNzg3Mg==&quot;, //注册时间
    &quot;user_time_login&quot;: &quot;MTQ0NjUyNTM1MA==&quot;, //最后登录时间
    &quot;user_ip&quot;: &quot;MTIxLjE5OS4xMS4xNjM=&quot; //最后登录 IP
}</code></pre>
    </p>

    <p>&nbsp;</p>
    <div class=\"text-right\">
        <a href=\"#top\">
            <span class=\"glyphicon glyphicon-chevron-up\"></span>
            top
        </a>
    </div>
    <hr>
    <p>&nbsp;</p>

    <a name=\"read\"></a>
    <h3>读取用户数据</h3>

    <p class=\"text-success\">接口说明</p>
    <p>本接口用于读取已注册用户的信息。</p>

    <p class=\"text-success\">URL</p>
    <p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=user</span></p>

    <p class=\"text-success\">HTTP 请求方式</p>
    <p>GET</p>

    <p class=\"text-success\">返回格式</p>
    <p>JSON</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">接口参数</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th class=\"text-nowrap\">必须</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">act_get</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>接口调用动作，值只能为 read。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_id</td>
                        <td class=\"text-nowrap\">int</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>应用的 APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_key</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>应用的 APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_id</td>
                        <td class=\"text-nowrap\">int</td>
                        <td class=\"text-nowrap\"> </td>
                        <td rowspan=\"3\">
                            <p>user_id、user_name、user_mail 三选一，优先级按顺序排列</p>
                            <p>其中是否允许邮箱登录，视注册设置情况而定。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=opt#reg\">注册设置</a>。</p>
                        </td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_name</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\"> </td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_mail</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\"> </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <p>
        本接口返回加密字符串，真正内容需要调用密文接口进行解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。
    </p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">返回结果</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">alert</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>返回代码，详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=alert\">返回代码</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">code</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>加密字符串，需要解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">key</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>解密码，配合加密字符串使用，用于解码。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">prd_sso_ver</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>baigo SSO 版本号。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">prd_sso_pub</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>baigo SSO 版本发布时间，格式为年月日。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">解密后的结果</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">Base64</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">user_id</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>用户 ID</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_name</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>用户名</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_mail</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>邮箱</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_contact</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">array</td>
                        <td>联系方式，详情查看 <a href=\"#contact\">联系方式格式</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_nick</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>昵称</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_status</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>用户状态，可能的值为 enable（启用）、wait（待审）、disable（禁用）。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_time</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>注册时间，Unix 时间戳。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_time_login</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>最后登录时间，Unix 时间戳。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_ip</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>最后登录 IP 地址</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_access_token</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>访问口令。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_access_expire</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>访问口令过期时间，Unix 时间戳。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_refresh_token</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>刷新口令。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_refresh_expire</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>刷新口令过期时间，Unix 时间戳。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <h4>返回结果示例</h4>
    <p>
<pre><code class=\"language-javascript\">{
    &quot;user_id&quot;: &quot;MTA=&quot;, //用户 ID
    &quot;user_name&quot;: &quot;Zm9uZQ==&quot;, //用户名
    &quot;user_mail&quot;: &quot;Zm9uZUBiYWlnby5uZXQ=&quot;, //邮箱
    &quot;user_nick&quot;: &quot;Zm9uZQ==&quot;, //昵称
    &quot;user_time&quot;: &quot;MTM5NDQxNzg3Mg==&quot;, //注册时间
    &quot;user_time_login&quot;: &quot;MTQ0NjUyNTM1MA==&quot;, //最后登录时间
    &quot;user_ip&quot;: &quot;MTIxLjE5OS4xMS4xNjM=&quot; //最后登录 IP
}</code></pre>
    </p>

    <p>&nbsp;</p>
    <div class=\"text-right\">
        <a href=\"#top\">
            <span class=\"glyphicon glyphicon-chevron-up\"></span>
            top
        </a>
    </div>
    <hr>
    <p>&nbsp;</p>

    <a name=\"edit\"></a>
    <h3>编辑用户资料</h3>

    <p class=\"text-success\">接口说明</p>
    <p>本接口用于编辑已注册用户的信息。</p>

    <p class=\"text-success\">URL</p>
    <p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=user</span></p>

    <p class=\"text-success\">HTTP 请求方式</p>
    <p>POST</p>

    <p class=\"text-success\">返回格式</p>
    <p>JSON</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">接口参数</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th class=\"text-nowrap\">必须</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">act_post</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>接口调用动作，值只能为 edit。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_id</td>
                        <td class=\"text-nowrap\">int</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>应用的 APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_key</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>应用的 APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_id</td>
                        <td class=\"text-nowrap\">int</td>
                        <td class=\"text-nowrap\"> </td>
                        <td rowspan=\"3\">
                            <p>user_id、user_name、user_mail 三选一，优先级按顺序排列</p>
                            <p>其中是否允许邮箱登录，视注册设置情况而定。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=opt#reg\">注册设置</a>。</p>
                        </td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_name</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\"> </td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_mail</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\"> </td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_contact</td>
                        <td class=\"text-nowrap\">array</td>
                        <td class=\"text-nowrap\">false</td>
                        <td>联系方式，仅在需要修改时传递该参数，详情查看 <a href=\"#contact\">联系方式格式</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_check_pass</td>
                        <td class=\"text-nowrap\">bool</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>
                            <p>是否需要验证原密码，true 为验证原密码，false 为忽略原密码。</p>
                            <p>如果此参数为 true，则不受应用授权的限制，详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#belong\">授权用户</a>。</p>
                        </td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_pass</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\"> </td>
                        <td>密码，根据 user_check_pass 参数而定，必须用 <mark>MD5</mark> 加密后传输。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_pass_new</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">false</td>
                        <td>新密码，仅在需要修改密码时传输该参数。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_mail_new</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">false</td>
                        <td>新邮箱，仅在需要修改邮箱时传输该参数，此处修改无需验证。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_nick</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">false</td>
                        <td>昵称，仅在需要修改时传递该参数。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <p>
        本接口返回加密字符串，真正内容需要调用密文接口进行解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。
    </p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">返回结果</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">alert</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>返回代码，详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=alert\">返回代码</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">code</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>加密字符串，需要解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">key</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>解密码，配合加密字符串使用，用于解码。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">prd_sso_ver</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>baigo SSO 版本号。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">prd_sso_pub</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>baigo SSO 版本发布时间，格式为年月日。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">解密后的结果</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">Base64</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">user_id</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>用户 ID</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_name</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">tring</td>
                        <td>用户名</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_nick</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">tring</td>
                        <td>昵称</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_access_token</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>访问口令。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_access_expire</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>访问口令过期时间，Unix 时间戳。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_refresh_token</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>刷新口令。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_refresh_expire</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>刷新口令过期时间，Unix 时间戳。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <h4>返回结果示例</h4>
    <p>
<pre><code class=\"language-javascript\">{
    &quot;user_id&quot;: &quot;MTA=&quot; //用户 ID
    &quot;user_name&quot;: &quot;MTA=&quot; //用户名
    &quot;user_nick&quot;: &quot;MTA=&quot; //昵称
}</code></pre>
    </p>

    <p>&nbsp;</p>
    <div class=\"text-right\">
        <a href=\"#top\">
            <span class=\"glyphicon glyphicon-chevron-up\"></span>
            top
        </a>
    </div>
    <hr>
    <p>&nbsp;</p>

    <a name=\"mailbox\"></a>
    <h3>更换邮箱</h3>

    <p class=\"text-success\">接口说明</p>
    <p>本接口用于修改已注册用户的邮箱，根据系统的设置，可能需要通过邮件进行验证，详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=opt#reg\">注册设置</a>。</p>

    <p class=\"text-success\">URL</p>
    <p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=user</span></p>

    <p class=\"text-success\">HTTP 请求方式</p>
    <p>POST</p>

    <p class=\"text-success\">返回格式</p>
    <p>JSON</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">接口参数</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th class=\"text-nowrap\">必须</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">act_post</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>接口调用动作，值只能为 mailbox。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_id</td>
                        <td class=\"text-nowrap\">int</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>应用的 APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_key</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>应用的 APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_id</td>
                        <td class=\"text-nowrap\">int</td>
                        <td class=\"text-nowrap\"> </td>
                        <td rowspan=\"3\">
                            <p>user_id、user_name、user_mail 三选一，优先级按顺序排列</p>
                            <p>其中是否允许邮箱登录，视注册设置情况而定。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=opt#reg\">注册设置</a>。</p>
                        </td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_name</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\"> </td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_mail</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\"> </td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_check_pass</td>
                        <td class=\"text-nowrap\">bool</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>
                            <p>是否需要验证原密码，true 为验证原密码，false 为忽略原密码。</p>
                            <p>如果此参数为 true，则不受应用授权的限制，详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#belong\">授权用户</a>。</p>
                        </td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_pass</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\"> </td>
                        <td>密码，根据 user_check_pass 参数而定，必须用 <mark>MD5</mark> 加密后传输。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_mail_new</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>新邮箱</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <p>
        本接口返回加密字符串，真正内容需要调用密文接口进行解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。
    </p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">返回结果</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">alert</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>返回代码，详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=alert\">返回代码</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">code</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>加密字符串，需要解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">key</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>解密码，配合加密字符串使用，用于解码。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">prd_sso_ver</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>baigo SSO 版本号。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">prd_sso_pub</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>baigo SSO 版本发布时间，格式为年月日。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">解密后的结果（系统设置为需要验证邮箱）详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=opt#reg\">注册设置</a></div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">Base64</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">user_id</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>用户 ID</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_name</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">tring</td>
                        <td>用户名</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">verify_id</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>验证 ID</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">verify_token</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>验证口令</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <h4>返回结果示例</h4>
    <p>
<pre><code class=\"language-javascript\">{
    &quot;user_id&quot;: &quot;MTA=&quot; //用户 ID
    &quot;user_name&quot;: &quot;MTA=&quot; //用户名
    &quot;verify_id&quot;: &quot;MTA=&quot; //验证 ID
    &quot;verify_token&quot;: &quot;MTA=&quot; //验证口令
}</code></pre>
    </p>

    <p>&nbsp;</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">解密后的结果（系统设置为无须验证邮箱）详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=opt#reg\">注册设置</a></div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">Base64</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">user_id</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>用户 ID</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_name</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">tring</td>
                        <td>用户名</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_mail</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">tring</td>
                        <td>邮箱</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <h4>返回结果示例</h4>
    <p>
<pre><code class=\"language-javascript\">{
    &quot;user_id&quot;: &quot;MTA=&quot; //用户 ID
    &quot;user_name&quot;: &quot;MTA=&quot; //用户名
    &quot;user_mail&quot;: &quot;MTA=&quot; //邮箱
}</code></pre>
    </p>

    <p>&nbsp;</p>
    <div class=\"text-right\">
        <a href=\"#top\">
            <span class=\"glyphicon glyphicon-chevron-up\"></span>
            top
        </a>
    </div>
    <hr>
    <p>&nbsp;</p>

    <a name=\"forgot\"></a>
    <h3>找回密码</h3>

    <p class=\"text-success\">接口说明</p>
    <p>本接口用于已注册用户找回密码，需要通过邮件进行验证。</p>

    <p class=\"text-success\">URL</p>
    <p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=user</span></p>

    <p class=\"text-success\">HTTP 请求方式</p>
    <p>POST</p>

    <p class=\"text-success\">返回格式</p>
    <p>JSON</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">接口参数</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th class=\"text-nowrap\">必须</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">act_post</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>接口调用动作，值只能为 forgot。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_id</td>
                        <td class=\"text-nowrap\">int</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>应用的 APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_key</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>应用的 APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_id</td>
                        <td class=\"text-nowrap\">int</td>
                        <td class=\"text-nowrap\"> </td>
                        <td rowspan=\"3\">
                            <p>user_id、user_name、user_mail 三选一，优先级按顺序排列</p>
                            <p>其中是否允许邮箱登录，视注册设置情况而定。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=opt#reg\">注册设置</a>。</p>
                        </td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_name</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\"> </td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_mail</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\"> </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <p>
        本接口返回加密字符串，真正内容需要调用密文接口进行解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。
    </p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">返回结果</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">alert</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>返回代码，详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=alert\">返回代码</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">code</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>加密字符串，需要解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">key</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>解密码，配合加密字符串使用，用于解码。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">prd_sso_ver</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>baigo SSO 版本号。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">prd_sso_pub</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>baigo SSO 版本发布时间，格式为年月日。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">解密后的结果</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">Base64</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">user_id</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>用户 ID</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_name</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">tring</td>
                        <td>用户名</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">verify_id</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>验证 ID</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">verify_token</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>验证口令</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <h4>返回结果示例</h4>
    <p>
<pre><code class=\"language-javascript\">{
    &quot;user_id&quot;: &quot;MTA=&quot; //用户 ID
    &quot;user_name&quot;: &quot;MTA=&quot; //用户名
    &quot;verify_id&quot;: &quot;MTA=&quot; //验证 ID
    &quot;verify_token&quot;: &quot;MTA=&quot; //验证口令
}</code></pre>
    </p>

    <p>&nbsp;</p>
    <div class=\"text-right\">
        <a href=\"#top\">
            <span class=\"glyphicon glyphicon-chevron-up\"></span>
            top
        </a>
    </div>
    <hr>
    <p>&nbsp;</p>

    <a name=\"refresh\"></a>
    <h3>刷新访问口令</h3>

    <p class=\"text-success\">接口说明</p>
    <p>本接口用于刷新用户的访问口令。</p>

    <p class=\"text-success\">URL</p>
    <p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=user</span></p>

    <p class=\"text-success\">HTTP 请求方式</p>
    <p>POST</p>

    <p class=\"text-success\">返回格式</p>
    <p>JSON</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">接口参数</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th class=\"text-nowrap\">必须</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">act_post</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>接口调用动作，值只能为 refresh。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_id</td>
                        <td class=\"text-nowrap\">int</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>应用的 APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_key</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>应用的 APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_id</td>
                        <td class=\"text-nowrap\">int</td>
                        <td class=\"text-nowrap\"> </td>
                        <td rowspan=\"3\">
                            <p>user_id、user_name、user_mail 三选一，优先级按顺序排列</p>
                            <p>其中是否允许邮箱登录，视注册设置情况而定。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=opt#reg\">注册设置</a>。</p>
                        </td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_name</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\"> </td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_mail</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\"> </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <p>
        本接口返回加密字符串，真正内容需要调用密文接口进行解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。
    </p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">返回结果</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">alert</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>返回代码，详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=alert\">返回代码</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">code</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>加密字符串，需要解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">key</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>解密码，配合加密字符串使用，用于解码。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">prd_sso_ver</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>baigo SSO 版本号。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">prd_sso_pub</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>baigo SSO 版本发布时间，格式为年月日。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">解密后的结果</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">Base64</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">user_id</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>用户 ID</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_access_token</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>访问口令。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_access_expire</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>访问口令过期时间，Unix 时间戳。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <h4>返回结果示例</h4>
    <p>
<pre><code class=\"language-javascript\">{
    &quot;user_id&quot;: &quot;MTA=&quot; //用户 ID
    &quot;user_name&quot;: &quot;MTA=&quot; //用户名
    &quot;user_access_token&quot;: &quot;MTA=&quot; //访问口令
    &quot;user_access_expire&quot;: &quot;MTA=&quot; //访问口令过期时间
}</code></pre>
    </p>

    <p>&nbsp;</p>
    <div class=\"text-right\">
        <a href=\"#top\">
            <span class=\"glyphicon glyphicon-chevron-up\"></span>
            top
        </a>
    </div>
    <hr>
    <p>&nbsp;</p>

    <a name=\"del\"></a>
    <h3>删除用户</h3>

    <p class=\"text-success\">接口说明</p>
    <p>本接口用于删除已注册用户。</p>

    <p class=\"text-success\">URL</p>
    <p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=user</span></p>

    <p class=\"text-success\">HTTP 请求方式</p>
    <p>POST</p>

    <p class=\"text-success\">返回格式</p>
    <p>JSON</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">接口参数</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th class=\"text-nowrap\">必须</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">act_post</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>接口调用动作，值只能为 del。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_id</td>
                        <td class=\"text-nowrap\">int</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>应用的 APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_key</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>应用的 APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_ids</td>
                        <td class=\"text-nowrap\">array</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>准备删除的用户 ID 数组</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <p>
        本接口返回加密字符串，真正内容需要调用密文接口进行解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。
    </p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">返回结果</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">alert</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>返回代码，详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=alert\">返回代码</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">code</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>加密字符串，需要解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">key</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>解密码，配合加密字符串使用，用于解码。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">prd_sso_ver</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>baigo SSO 版本号。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">prd_sso_pub</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>baigo SSO 版本发布时间，格式为年月日。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">解密后的结果</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">Base64</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">alert</td>
                        <td class=\"text-nowrap\">false</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>返回代码，详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=alert\">返回代码</a>。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <h4>返回结果示例</h4>
    <p>
<pre><code class=\"language-javascript\">{
    &quot;alert&quot;: &quot;y010102&quot; //返回代码
}</code></pre>
    </p>

    <p>&nbsp;</p>
    <div class=\"text-right\">
        <a href=\"#top\">
            <span class=\"glyphicon glyphicon-chevron-up\"></span>
            top
        </a>
    </div>
    <hr>
    <p>&nbsp;</p>

    <a name=\"chkname\"></a>
    <h3>检查用户名</h3>

    <p class=\"text-success\">接口说明</p>
    <p>本接口用于检查用户名是否可以注册。</p>

    <p class=\"text-success\">URL</p>
    <p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=user</span></p>

    <p class=\"text-success\">HTTP 请求方式</p>
    <p>GET</p>

    <p class=\"text-success\">返回格式</p>
    <p>JSON</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">接口参数</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th class=\"text-nowrap\">必须</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">act_get</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>接口调用动作，值只能为 chkname。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_id</td>
                        <td class=\"text-nowrap\">int</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>应用的 APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_key</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>应用的 APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_name</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>用户名</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">返回结果</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">alert</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>返回代码，详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=alert\">返回代码</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">prd_sso_ver</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>baigo SSO 版本号。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">prd_sso_pub</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>baigo SSO 版本发布时间，格式为年月日。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <h4>返回结果示例</h4>
    <p>
<pre><code class=\"language-javascript\">{
    &quot;alert&quot;: &quot;y010102&quot; //返回代码
}</code></pre>
    </p>

    <p>&nbsp;</p>
    <div class=\"text-right\">
        <a href=\"#top\">
            <span class=\"glyphicon glyphicon-chevron-up\"></span>
            top
        </a>
    </div>
    <hr>
    <p>&nbsp;</p>

    <a name=\"chkmail\"></a>
    <h3>检查邮箱</h3>

    <p class=\"text-success\">接口说明</p>
    <p>本接口用于检查邮箱是否可以注册。</p>

    <p class=\"text-success\">URL</p>
    <p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=user</span></p>

    <p class=\"text-success\">HTTP 请求方式</p>
    <p>GET</p>

    <p class=\"text-success\">返回格式</p>
    <p>JSON</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">接口参数</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th class=\"text-nowrap\">必须</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">act_get</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>接口调用动作，值只能为 chkmail。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_id</td>
                        <td class=\"text-nowrap\">int</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>应用的 APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_key</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>应用的 APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_mail</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>邮箱</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">not_id</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">false</td>
                        <td>排除用户 ID，此参数用户排除不需要验证的用户 ID，一般用在编辑用户资料前的验证。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">返回结果</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th>具体描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">alert</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>返回代码，详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=alert\">返回代码</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">prd_sso_ver</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>baigo SSO 版本号。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">prd_sso_pub</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>baigo SSO 版本发布时间，格式为年月日。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <h4>返回结果示例</h4>
    <p>
<pre><code class=\"language-javascript\">{
    &quot;alert&quot;: &quot;y010102&quot; //返回代码
}</code></pre>
    </p>

    <p>&nbsp;</p>
    <div class=\"text-right\">
        <a href=\"#top\">
            <span class=\"glyphicon glyphicon-chevron-up\"></span>
            top
        </a>
    </div>
    <hr>

    <p>&nbsp;</p>

    <a name=\"contact\"></a>
    <h3>联系方式格式</h3>
<pre><code class=\"language-php\">array(
    \"tel\" => array( //可自定义
        \"key\"     => \"电话\",
        \"value\"   => \"0574-88888888\"
    ),
    \"addr\" => array(
        \"key\"     => \"地址\",
        \"value\"   => \"浙江省宁波市\"
    )
);
</code></pre>
    </p>

    <p>&nbsp;</p>
    <div class=\"text-right\">
        <a href=\"#top\">
            <span class=\"glyphicon glyphicon-chevron-up\"></span>
            top
        </a>
    </div>";