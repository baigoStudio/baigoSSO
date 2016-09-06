<?php
return "<h3>同步概述</h3>
    <p>如果当前应用程序在 SSO 中设置允许同步登录，那么在调用本接口时，系统会通知其他设置了同步登录的应用程序登录，以达到一点登录，所有网站登录的目的。下图是同步流程示意图。</p>
    <p>
        <a href=\"{images}sync_process.jpg\" target=\"_blank\"><img src=\"{images}sync_process.jpg\" class=\"img-responsive thumbnail\"></a>
    </p>

    <p>&nbsp;</p>

    <h3>公共请求参数</h3>
    <p>公共请求参数是指向所有接口发起请求时都必须传入的参数。</p>
    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">公共请求参数</div>
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
                        <td>签名，将请求参数中的所有参数（包括公共请求参数，但不含签名）按照一定规律组合以后生成的，详情请查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=signature#verify\">签名接口</a>。</td>
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

    <a name=\"login\"></a>
    <h3>同步登录</h3>

    <p class=\"text-success\">接口说明</p>
    <p>本接口使得用户在一点登录以后，在所有网站同时登录的目的。</p>

    <p class=\"text-success\">URL</p>
    <p class=\"text-primary\">http://www.domain.com/api/api.php?mod=sync</p>

    <p class=\"text-success\">HTTP 请求方式</p>
    <p>POST</p>

    <p class=\"text-success\">返回格式</p>
    <p>JSON</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">请求参数</div>
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
                        <td class=\"text-nowrap\">act_get</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>接口调用动作，值只能为 login。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_id</td>
                        <td class=\"text-nowrap\">int</td>
                        <td rowspan=\"3\">true</td>
                        <td rowspan=\"3\">
                            <p>三选一，优先级为 user_id &gt; user_name &gt; user_mail</p>
                            <p>其中是否允许邮箱登录，视注册设置情况而定。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=opt#reg\">注册设置</a>。</p>
                        </td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_name</td>
                        <td class=\"text-nowrap\">string</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_mail</td>
                        <td class=\"text-nowrap\">string</td>
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
                        <th>描述</th>
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
                        <td>同步通知 URL，遍历返回的数组并进行 Base64 解码得到调用 URL，然后用 GET 方式访问该 URL，推荐使用 AJAX 同步方式调用，待 URL 访问完毕时，进行下一步动作，如：页面跳转等。系统会在“同步接口 URL”上附加 <code>mod=sync</code> 参数，以便接受通知的系统识别。</td>
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
        &quot;http://abc.com/api/api.php?mod=sync&amp;act_get=login&amp;time=1446527788&amp;signature=sdfdsfsdrewerwugroe7treie&amp;code=CSMEIFh7AHYBOFIlXQwAaQE0UXENawF2WUxXUbg1zASk%3D&amp;key=tLUwyt&quot;
        ,
        &quot;http://123.cn/api/api.php?mod=sync&amp;act_get=login&amp;time=1446527788&amp;&amp;signature=sdfdsfsdrewerwugroe7treie&amp;code=CSMEIFh7AHYBOFIlXQwAaQE0UXENawF2WUxXUbg1zASk%3D&amp;key=tLUwyt&quot;
    ]
}</code></pre>
    </p>

    <p>&nbsp;</p>

    <a name=\"jsonp_exp\"></a>
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

    <a name=\"logout\"></a>
    <h3>同步登出</h3>

    <p class=\"text-success\">接口说明</p>
    <p>本接口使得用户在一点登出以后，在所有网站同时登出的目的。把返回的 HTML 输出在页面中即可完成对其它应用程序的通知。</p>

    <p class=\"text-success\">URL</p>
    <p class=\"text-primary\">http://www.domain.com/api/api.php?mod=sync</p>

    <p class=\"text-success\">HTTP 请求方式</p>
    <p>POST</p>

    <p class=\"text-success\">返回格式</p>
    <p>JSON</p>

    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">请求参数</div>
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
                        <td class=\"text-nowrap\">act_get</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>接口调用动作，值只能为 logout。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_id</td>
                        <td class=\"text-nowrap\">int</td>
                        <td rowspan=\"3\">true</td>
                        <td rowspan=\"3\">
                            <p>三选一，优先级为 user_id &gt; user_name &gt; user_mail</p>
                            <p>其中是否允许邮箱登录，视注册设置情况而定。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=opt#reg\">注册设置</a>。</p>
                        </td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_name</td>
                        <td class=\"text-nowrap\">string</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">user_mail</td>
                        <td class=\"text-nowrap\">string</td>
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
                        <th>描述</th>
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
                        <td>同步通知 URL，遍历返回的数组并进行 Base64 解码得到调用 URL，然后用 GET 方式访问该 URL，推荐使用 AJAX 同步方式调用，待 URL 访问完毕时，进行下一步动作，如：页面跳转等。系统会在“同步接口 URL”上附加 <code>mod=sync</code> 参数，以便接受通知的系统识别。</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>";