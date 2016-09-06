<?php
return "<h3>公共请求参数</h3>
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

    <h3>生成签名</h3>

    <p class=\"text-success\">接口说明</p>
    <p>本接口用于生成签名。</p>

    <p class=\"text-success\">URL</p>
    <p class=\"text-primary\">http://www.domain.com/api/api.php?mod=signature</p>

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
                        <td class=\"text-nowrap\">act_post</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>接口调用动作，值只能为 signature。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">params</td>
                        <td class=\"text-nowrap\">array</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>待生成签名的数组</td>
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
                        <th>描述</th>
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
                        <td>签名，<mark>注意：此处为待验证签名！</mark></td>
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
    <p class=\"text-primary\">http://www.domain.com/api/api.php?mod=signature</p>

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
                        <td class=\"text-nowrap\">act_post</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>接口调用动作，值只能为 verify。</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">params</td>
                        <td class=\"text-nowrap\">array</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>签名前数组</td>
                    </tr>
                    <tr>
                        <td class=\"text-nowrap\">signature</td>
                        <td class=\"text-nowrap\">string</td>
                        <td class=\"text-nowrap\">true</td>
                        <td>签名</td>
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
                        <th>描述</th>
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
    &quot;alert&quot;: &quot;x050224&quot; //返回代码
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