<?php
return "<a name=\"top\"></a>
    <a name=\"signature\"></a>
    <h3>生成签名</h3>

    <p class=\"text-success\">接口说明</p>
    <p>本接口用于生成签名。</p>

    <p class=\"text-success\">URL</p>
    <p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=signature</span></p>

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
                        <td>接口调用动作，值只能为 signature。</td>
                    </tr>
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
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>Unix 时间戳</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">random</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>随机字符串</td>
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
                        <td class=\"text-nowrap\">signature</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>签名字符串</td>
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
    &quot;prd_sso_ver&quot;: &quot;1.1.1&quot;, //SSO 版本
    &quot;prd_sso_pub&quot;: 20150923, //SSO 版本发布时间
    &quot;signature&quot;: &quot;883b6a93e4cd2e9c42485c1a4e6217c20926c155&quot;, //签名
    &quot;alert&quot;: &quot;y050404&quot; //返回代码
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

    <a name=\"verify\"></a>
    <h3>验证签名</h3>

    <p class=\"text-success\">接口说明</p>
    <p>本接口用于验证签名的正确性。</p>

    <p class=\"text-success\">URL</p>
    <p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=signature</span></p>

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
                        <td>接口调用动作，值只能为 verify。</td>
                    </tr>
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
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>Unix 时间戳</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">random</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>随机字符串</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">signature</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>签名字符串</td>
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
    &quot;prd_sso_ver&quot;: &quot;1.1.1&quot;, //SSO 版本
    &quot;prd_sso_pub&quot;: 20150923, //SSO 版本发布时间
    &quot;alert&quot;: &quot;x090201&quot; //返回代码
}</code></pre>
    </p>

    <p>&nbsp;</p>
    <div class=\"text-right\">
        <a href=\"#top\">
            <span class=\"glyphicon glyphicon-chevron-up\"></span>
            top
        </a>
    </div>
    <hr>";