<?php
return "<h3>通知概述</h3>
    <p>系统在执行一些特定操作的时候，会根据应用的设置，向指定的“通知接口 URL”推送通知，“通知接口 URL”接收到通知以后，可以根据实际情况，在本地执行一些必要的程序。系统会在“通知接口 URL”上附加 <code>mod=notify</code> 参数，以便接受通知的系统识别。</p>

    <p>&nbsp;</p>

    <h4>通知的验证</h4>
    <p>系统在推送通知时，会在“通知接口 URL”中附加签名数据，签名是将通知参数中的所有参数（包括公共通知参数，但不含签名）按照一定规律组合以后生成的，同时系统也会附带签名值，以供验证，详情请查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=signature#verify\">签名接口</a>，确认签名正确的情况下，进行下一步操作。</p>

    <p>&nbsp;</p>
    <div class=\"text-right\">
        <a href=\"#top\">
            <span class=\"glyphicon glyphicon-chevron-up\"></span>
            top
        </a>
    </div>
    <hr>
    <p>&nbsp;</p>

    <h3>公共通知参数</h3>

    <p>公共通知参数是指向所有接口发起通知时都传递的参数。</p>
    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">公共通知参数</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th class=\"text-nowrap\">必须</th>
                        <th>描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">app_id</td>
                        <td class=\"text-nowrap\">int</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>应用的 APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">app_key</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>应用的 APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">time</td>
                        <td class=\"text-nowrap\">int</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>Unix 时间戳，baigo SSO 允许 +-30 分钟以内的时差，为了防止时区设置不同导致的时差，请开发者将应用的时区设置为为与 baigo SSO 一致，关于时区设置，请查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=opt#base\">系统设置</a>。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">signature</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>签名，签名是将通知参数中的所有参数（包括公共通知参数，但不含签名）按照一定规律组合以后生成的，同时系统也会附带签名值，以供验证，详情请查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=signature#verify\">签名接口</a>。</td>
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

    <a name=\"test\"></a>
    <h3>通知测试</h3>

    <p class=\"text-success\">接口说明</p>
    <p>本接口用于测试通知接口是否正常。</p>

    <p class=\"text-success\">HTTP 请求方式</p>
    <p>GET</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">通知参数</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th>描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">act_get</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>通知动作，值为 test。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">echostr</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>输出字符串，直接将该字符串输出便可确认该应用的通知接口正常。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <h4>通知参数示例</h4>
    <p>PHP 通过 <code>print_r(\$_GET);</code></p>

    <p>
<pre><code class=\"language-php\">array(
    &quot;act_get&quot;   =&gt; &quot;test&quot;, //通知动作
    &quot;app_id&quot;    =&gt; 1, //APP ID
    &quot;app_key&quot;   =&gt; &quot;sdferi84hkdfufdsERTeugroe7treie&quot;, //APP KEY
    &quot;time&quot;      =&gt; 1446527788, //Unix 时间戳
    &quot;echostr&quot;   =&gt; &quot;i84hksdfuw98&amp;*Ijiiwer&quot; //输出字符串
    &quot;signature&quot; =&gt; &quot;sdfdsfsdrewerwugroe7treie&quot;, //签名
);</code></pre>
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

    <a name=\"reg\"></a>
    <h3>用户注册</h3>

    <p class=\"text-success\">接口说明</p>
    <p>本接口用于通知各应用有新用户注册成功。</p>

    <p class=\"text-success\">HTTP 请求方式</p>
    <p>POST</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">通知参数</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th>描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">act_post</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>通知动作，值为 reg。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">code</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>加密字符串，需要解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <h4>通知参数示例</h4>
    <p>PHP 通过 <code>print_r(\$_POST);</code></p>

    <p>
<pre><code class=\"language-php\">array(
    &quot;mod&quot;         =&gt; &quot;notify&quot;, //组件名称
    &quot;act_get&quot;     =&gt; &quot;reg&quot;, //通知动作
    &quot;app_id&quot;      =&gt; 1, //APP ID
    &quot;app_key&quot;     =&gt; &quot;sdferi84hkdfufdsERTeugroe7treie&quot;, //APP KEY
    &quot;time&quot;        =&gt; 1446527788, //Unix 时间戳
    &quot;code&quot;        =&gt; &quot;CSMEIFh7AHYBOFIlXQwAaQE0UXENawF2WUxXUQNFVD4Ac1R%2BUSUFdQgnBmYMcARbAT5RMlprBjZdJQdvBSRQXgkPBEhYZAAnAXFSdV0mAHMBNVEhDQ4BOVliV2wDbFQhAGtUcFElBSwIdgZ2DHEEYQEiUQxaaAY6XWQHPgUkUD0JegRbWFkATwE3UnVdfwAiASVRIA00ASZZXFdxA2lUbgA0VHBRPQUiCBkGVwxTBDQBHVEpWkMGYF1KBxEFR1A1CRcEU1gzADgBf1J7XXEAdQEjUTYNIwELWXdXbANtVGYADlQ%2BUWgFZwg9Bm0MIAQ%2BAXJRHlpSBgJdNwcYBXxQQglrBE9YSgBIAWhSGV0kAD0BbVFxDX0Bdll2V3YDZVRxAA5UO1F3BSIIbgYhDE8EUAEZUStaSgY5XUIHYAVJUFQJbAR6WEMAVgFpUi9dHQBqAR1Rbg1zASk%3D&quot;, //加密字符串
    &quot;signature&quot;   =&gt; &quot;sdfdsfsdrewerwugroe7treie&quot;, //签名
);</code></pre>
    </p>

    <p>&nbsp;</p>

    <p>
        baigo SSO 所有通知接口均推送加密字符串，真正内容需要调用密文接口进行解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。
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
                        <th>描述</th>
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
    &quot;user_nick&quot;: &quot;Zm9uZQ==&quot; //昵称
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
    <p>本接口用于通知各应用用户信息编辑成功。</p>

    <p class=\"text-success\">HTTP 请求方式</p>
    <p>POST</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">通知参数</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th>描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">act_post</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>通知动作，值为 edit。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">code</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>加密字符串，需要解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <h4>通知参数示例</h4>
    <p>PHP 通过 <code>print_r(\$_POST);</code></p>

    <p>
<pre><code class=\"language-php\">array(
    &quot;mod&quot;         =&gt; &quot;notify&quot;, //组件名称
    &quot;act_get&quot;     =&gt; &quot;edit&quot;, //通知动作
    &quot;app_id&quot;      =&gt; 1, //APP ID
    &quot;app_key&quot;     =&gt; &quot;sdferi84hkdfufdsERTeugroe7treie&quot;, //APP KEY
    &quot;time&quot;        =&gt; 1446527788, //Unix 时间戳
    &quot;code&quot;        =&gt; &quot;CSMEIFh7AHYBOFIlXQwAaQE0UXENawF2WUxXUQNFVD4Ac1R%2BUSUFdQgnBmYMcARbAT5RMlprBjZdJQdvBSRQXgkPBEhYZAAnAXFSdV0mAHMBNVEhDQ4BOVliV2wDbFQhAGtUcFElBSwIdgZ2DHEEYQEiUQxaaAY6XWQHPgUkUD0JegRbWFkATwE3UnVdfwAiASVRIA00ASZZXFdxA2lUbgA0VHBRPQUiCBkGVwxTBDQBHVEpWkMGYF1KBxEFR1A1CRcEU1gzADgBf1J7XXEAdQEjUTYNIwELWXdXbANtVGYADlQ%2BUWgFZwg9Bm0MIAQ%2BAXJRHlpSBgJdNwcYBXxQQglrBE9YSgBIAWhSGV0kAD0BbVFxDX0Bdll2V3YDZVRxAA5UO1F3BSIIbgYhDE8EUAEZUStaSgY5XUIHYAVJUFQJbAR6WEMAVgFpUi9dHQBqAR1Rbg1zASk%3D&quot;, //加密字符串
    &quot;signature&quot;   =&gt; &quot;sdfdsfsdrewerwugroe7treie&quot;, //签名
);</code></pre>
    </p>

    <p>&nbsp;</p>

    <p>
        baigo SSO 所有通知接口均推送加密字符串，真正内容需要调用密文接口进行解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。
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
                        <th>描述</th>
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
    &quot;user_nick&quot;: &quot;Zm9uZQ==&quot; //昵称
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

    <p class=\"text-success\">HTTP 请求方式</p>
    <p>POST</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">通知参数</div>
        <div class=\"table-responsive\">
            <table class=\"table table-bordered\">
                <thead>
                    <tr>
                        <th class=\"text-nowrap\">名称</th>
                        <th class=\"text-nowrap\">类型</th>
                        <th>描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">act_post</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>通知动作，值为 del。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">code</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>加密字符串，需要解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <h4>通知参数示例</h4>
    <p>PHP 通过 <code>print_r(\$_POST);</code></p>

    <p>
<pre><code class=\"language-php\">array(
    &quot;mod&quot;         =&gt; &quot;notify&quot;, //组件名称
    &quot;act_get&quot;     =&gt; &quot;del&quot;, //通知动作
    &quot;app_id&quot;      =&gt; 1, //APP ID
    &quot;app_key&quot;     =&gt; &quot;sdferi84hkdfufdsERTeugroe7treie&quot;, //APP KEY
    &quot;time&quot;        =&gt; 1446527788, //Unix 时间戳
    &quot;code&quot;        =&gt; &quot;CSMEIFh7AHYBOFIlXQwAaQE0UXENawF2WUxXUQNFVD4Ac1R%2BUSUFdQgnBmYMcARbAT5RMlprBjZdJQdvBSRQXgkPBEhYZAAnAXFSdV0mAHMBNVEhDQ4BOVliV2wDbFQhAGtUcFElBSwIdgZ2DHEEYQEiUQxaaAY6XWQHPgUkUD0JegRbWFkATwE3UnVdfwAiASVRIA00ASZZXFdxA2lUbgA0VHBRPQUiCBkGVwxTBDQBHVEpWkMGYF1KBxEFR1A1CRcEU1gzADgBf1J7XXEAdQEjUTYNIwELWXdXbANtVGYADlQ%2BUWgFZwg9Bm0MIAQ%2BAXJRHlpSBgJdNwcYBXxQQglrBE9YSgBIAWhSGV0kAD0BbVFxDX0Bdll2V3YDZVRxAA5UO1F3BSIIbgYhDE8EUAEZUStaSgY5XUIHYAVJUFQJbAR6WEMAVgFpUi9dHQBqAR1Rbg1zASk%3D&quot;, //加密字符串
    &quot;signature&quot;   =&gt; &quot;sdfdsfsdrewerwugroe7treie&quot;, //签名
);</code></pre>
    </p>

    <p>&nbsp;</p>

    <p>
        baigo SSO 所有通知接口均推送加密字符串，真正内容需要调用密文接口进行解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。
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
                        <th>描述</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=\"text-nowrap\">user_ids</td>
                        <td class=\"text-nowrap\">false</td>
                        <td class=\"text-nowrap\">array</td>
                        <td>用户 ID 数组</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>&nbsp;</p>

    <h4>返回结果示例</h4>
    <p>
<pre><code class=\"language-javascript\">{
    &quot;user_ids&quot;: [ 1, 2, 3 ] //用户 ID
}</code></pre>
    </p>";