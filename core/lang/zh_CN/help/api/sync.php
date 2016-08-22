<?php
return "<h3>同步概述</h3>
    <p>如果当前应用程序在 SSO 中设置允许同步登录，那么在调用本接口时，系统会通知其他设置了同步登录的应用程序登录，以达到一点登录，所有网站登录的目的。下图是同步流程示意图。</p>
    <p>
        <a href=\"{images}sync_process.jpg\" target=\"_blank\"><img src=\"{images}sync_process.jpg\" class=\"img-responsive thumbnail\"></a>
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
    <h3>同步登录</h3>

    <p class=\"text-success\">接口说明</p>
    <p>本接口使得用户在一点登录以后，在所有网站同时登录的目的。</p>

    <p class=\"text-success\">URL</p>
    <p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=sync</span></p>

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
                        <td class=\"text-nowrap\">act_get</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>接口调用动作，值只能为 login。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_id</td>
                        <td class=\"text-nowrap\">int</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_key</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
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

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">返回结果</div>
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
                    <tr>
                        <td class=\"text-nowrap\">urlRows</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">array</td>
                        <td>同步调用 URL，遍历返回的数组并进行 Base64 解码得到调用 URL，然后用 GET 方式访问该 URL，推荐使用 AJAX 同步方式调用，待 URL 访问完毕时，进行下一步动作，如：页面跳转等。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <h4>返回结果示例</h4>
    <p>
<pre><code class=\"language-javascript\">{
    &quot;alert&quot;: &quot;y100401&quot;,
    &quot;urlRows&quot;: [ //通知 URL
        &quot;http://abc.com/api/api.php?mod=sync&amp;act_get=login&amp;time=1446527788&amp;random=sdfwerwer&amp;signature=sdfdsfsdrewerwugroe7treie&amp;code=CSMEIFh7AHYBOFIlXQwAaQE0UXENawF2WUxXUbg1zASk%3D&amp;key=tLUwyt&quot;
        ,
        &quot;http://123.cn/api/api.php?mod=sync&amp;act_get=login&amp;time=1446527788&amp;random=sdfwerwer&amp;signature=sdfdsfsdrewerwugroe7treie&amp;code=CSMEIFh7AHYBOFIlXQwAaQE0UXENawF2WUxXUbg1zASk%3D&amp;key=tLUwyt&quot;
    ]
}</code></pre>
    </p>

    <p>&nbsp;</p>

    <h4>调用 URL 示例 AJAX + JSONP 方式</h4>
    <p>
        由于 AJAX 方式提交数据会涉及到跨域问题，因此 dataType 设置为 JSONP。关于 JSONP 详见 <a href=\"http://www.baike.com/wiki/JSONP\" target=\"_blank\">互动百科</a>。AJAX 方式的优点是可以获知调用的状态，以便与判断同步是否成功，缺点是存在跨域问题，在部分浏览器可能存在兼容性问题。
    </p>

    <p>
<pre><code class=\"language-php\">&lt;script type=&quot;text/javascript&quot;&gt;
$(document).ready(function(){
    &lt;?php foreach (\$sync[&quot;urlRows&quot;] as \$key=&gt;\$value) { ?&gt;
        \$.ajax({
            url: &quot;&lt;?php echo \$value; ?&gt;&quot;, //url
            type: &quot;get&quot;, //方法
            dataType: &quot;jsonp&quot;, //数据格式为 jsonp 支持跨域提交
            async: false, //设置为同步
            complete: function(){ //读取返回结果
                &lt;?php if (end(\$sync[&quot;urlRows&quot;]) == \$value) { ?&gt; //如果是最后一个 URL，访问完毕后跳转
                    window.location.href = &quot;http://www.abc.com&quot;; //跳转
                &lt;?php } ?&gt;
            }
        });
    &lt;?php } ?&gt;
});
&lt;/script&gt;</code></pre>
    </p>

    <p>&nbsp;</p>

    <h4>调用 URL 示例 SCRIPT 方式</h4>
    <p>
        开发者也可以根据实际情况选择 SCRIPT 方式或者 IFRAME 方式，这两种方式的优点是无跨域问题，缺点是无法获知调用的状态，无法判断同步是否成功。
    </p>

<pre><code class=\"language-php\">&lt;?php foreach (\$sync[&quot;urlRows&quot;] as \$key=&gt;\$value) { ?&gt;
    &lt;script type=&quot;text/javascript&quot; src=&quot;&lt;?php echo \$value; ?&gt;&quot;&gt;&lt;/script&gt;
&lt;?php } ?&gt;</code></pre>

    <p>&nbsp;</p>
    <div class=\"text-right\">
        <a href=\"#top\">
            <span class=\"glyphicon glyphicon-chevron-up\"></span>
            top
        </a>
    </div>
    <hr>
    <p>&nbsp;</p>

    <a name=\"login_notice\"></a>
    <h3>同步登录通知</h3>

    <p class=\"text-success\">接口说明</p>
    <p>应用调用同步接口以后，将会直接访问“同步接口 URL”，即上一步通过 AJAX 提交的例子。</p>

    <p class=\"text-success\">HTTP 请求方式</p>
    <p>GET</p>

    <p class=\"text-success\">通知的验证</p>
    <p>系统在推送通知时，会附带用于验证的数据。其中 APP ID 与 APP KEY 需要解密后才能得到，得到后请与当前应用进行对比验证，time、random、signature 用于验证签名，详情请查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=signature#verify\">签名接口</a>。</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">数据结构</div>
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
                        <td class=\"text-nowrap\">mod</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>通知组件名称，值为 sync。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">act_get</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>通知动作，值为 login。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">time</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>Unix 时间戳</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">random</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>随机字符串</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">signature</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>签名字符串</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">code</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>加密字符串，需要解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">callback</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>前端通过 JSONP 提交数据时的回调函数</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <h4>通知数据示例</h4>
    <p>PHP 通过 <code>print_r(\$_GET);</code>，得到数据后，请验证签名是否正确，然后进行下一步操作。</p>

    <p>
<pre><code class=\"language-php\">array(
    &quot;mod&quot;         =&gt; &quot;sync&quot;, //组件名称
    &quot;act_get&quot;     =&gt; &quot;login&quot;, //通知动作
    &quot;time&quot;        =&gt; 1446527788, //Unix 时间戳
    &quot;random&quot;      =&gt; &quot;sdfwerwer&quot;, //随机字符串
    &quot;signature&quot;   =&gt; &quot;sdfdsfsdrewerwugroe7treie&quot;, //签名
    &quot;code&quot;        =&gt; &quot;CSMEIFh7AHYBOFIlXQwAaQE0UXENawF2WUxXUQNFVD4Ac1R%2BUSUFdQgnBmYMcARbAT5RMlprBjZdJQdvBSRQXgkPBEhYZAAnAXFSdV0mAHMBNVEhDQ4BOVliV2wDbFQhAGtUcFElBSwIdgZ2DHEEYQEiUQxaaAY6XWQHPgUkUD0JegRbWFkATwE3UnVdfwAiASVRIA00ASZZXFdxA2lUbgA0VHBRPQUiCBkGVwxTBDQBHVEpWkMGYF1KBxEFR1A1CRcEU1gzADgBf1J7XXEAdQEjUTYNIwELWXdXbANtVGYADlQ%2BUWgFZwg9Bm0MIAQ%2BAXJRHlpSBgJdNwcYBXxQQglrBE9YSgBIAWhSGV0kAD0BbVFxDX0Bdll2V3YDZVRxAA5UO1F3BSIIbgYhDE8EUAEZUStaSgY5XUIHYAVJUFQJbAR6WEMAVgFpUi9dHQBqAR1Rbg1zASk%3D&quot;, //加密字符串
);</code></pre>
    </p>

    <p>&nbsp;</p>

    <p>
        baigo SSO 所有通知接口均推送加密字符串，真正内容需要调用密文接口进行解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。“同步接口 URL”接收到通知以后，可以根据实际情况，在本地执行一些必要的程序。最后需要输出回调函数，并将数据通过回调函数的参数传回。
    </p>

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
                        <td>用户邮箱</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_id</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_key</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <h4>返回结果示例</h4>
    <p>得到结果后，请验证 APP ID 与 APP KEY 是否正确，然后进行下一步动作，如：生成会话，标记用户已登录等。</p>

    <p>
<pre><code class=\"language-javascript\">{
    &quot;user_id&quot;: &quot;MTA=&quot;, //用户 ID
    &quot;user_name&quot;: &quot;Zm9uZQ==&quot;, //用户名
    &quot;app_id&quot;: &quot;MTA=&quot;, //APP ID
    &quot;app_key&quot;: &quot;sdfwerwer&quot;; //APP KEY
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

    <a name=\"logout\"></a>
    <h3>同步登出</h3>

    <p class=\"text-success\">接口说明</p>
    <p>本接口使得用户在一点登出以后，在所有网站同时登出的目的。把返回的 HTML 输出在页面中即可完成对其它应用程序的通知。</p>

    <p class=\"text-success\">URL</p>
    <p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=sync</span></p>

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
                        <td class=\"text-nowrap\">act_get</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>接口调用动作，值只能为 logout。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_id</td>
                        <td class=\"text-nowrap\">int</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_key</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
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

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">返回结果</div>
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
                    <tr>
                        <td class=\"text-nowrap\">urlRows</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">array</td>
                        <td>同步调用 URL，遍历返回的数组并进行 Base64 解码得到调用 URL，然后用 GET 方式访问该 URL，推荐使用同步 AJAX 方式调用，待 URL 方式完毕时，进行下一步动作，如：页面跳转等。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>
    <div class=\"text-right\">
        <a href=\"#top\">
            <span class=\"glyphicon glyphicon-chevron-up\"></span>
            top
        </a>
    </div>
    <hr>
    <p>&nbsp;</p>

    <a name=\"logout_notice\"></a>
    <h3>同步登出通知</h3>

    <p class=\"text-success\">接口说明</p>
    <p>应用调用同步接口以后，将会直接访问“同步接口 URL”。</p>

    <p class=\"text-success\">HTTP 请求方式</p>
    <p>GET</p>

    <p class=\"text-success\">通知的验证</p>
    <p>系统在推送通知时，会附带用于验证的数据。其中 APP ID 与 APP KEY 需要解密后才能得到，得到后请与当前应用进行对比验证，time、random、signature 用于验证签名，详情请查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=signature#verify\">签名接口</a>。</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">数据结构</div>
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
                        <td class=\"text-nowrap\">mod</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>通知组件名称，值为 sync。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">act_get</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>通知动作，值为 logout。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">time</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>Unix 时间戳</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">random</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>随机字符串</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">signature</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>签名字符串</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">code</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>加密字符串，需要解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">callback</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>前端通过 JSONP 提交数据时的回调函数</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>
        baigo SSO 所有通知接口均推送加密字符串，真正内容需要调用密文接口进行解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。“同步接口 URL”接收到通知以后，可以根据实际情况，在本地执行一些必要的程序。最后需要输出回调函数，并将数据通过回调函数的参数传回。
    </p>

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
                        <td>用户邮箱</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_id</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">int</td>
                        <td>APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_key</td>
                        <td class=\"text-nowrap\">true</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>";