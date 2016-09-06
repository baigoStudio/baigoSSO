<?php
return "<h3>API 概述</h3>
    <p>
        各种应用整合 baigo SSO 都是通过 API 接口实现的，您可以在各类应用程序中使用该接口，通过发起 HTTP 请求方式调用 baigo SSO 服务，返回 JSON 数据。
    </p>
    <p>
        使用 API 接口，您需先在 baigo SSO 创建应用，创建成功后会给出 APP ID 和 APP KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。
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

    <h3>应用的验证</h3>
    <p>
        baigo SSO 的所有 API 接口均需要验证应用以及验证应用的权限。详情请查看具体接口说明。
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

    <h3>API 调用示例</h3>

    <p>
<pre><code class=\"language-php\">class CLASS_SSO {

    \$arr_data = array(
        &quot;app_id&quot;     =&gt; BG_SSO_APPID, //APP ID
        &quot;app_key&quot;    =&gt; BG_SSO_APPKEY, //APP KEY
    );

    /** 登录
     * sso_login function.
     *
     * @access public
     * @param mixed \$str_userName 用户名
     * @param mixed \$str_userPass 密码
     * @return 解码后数组 登录结果
     */
    function sso_login(\$str_userName, \$str_userPass) {
        \$_arr_sso = array(
            &quot;act_post&quot;   =&gt; &quot;login&quot;, //动作
            &quot;user_name&quot;  =&gt; \$str_userName, //用户名
            &quot;user_pass&quot;  =&gt; md5(\$str_userPass), //密码，需要 md5 加密
        );

        \$_arr_ssoData = array_merge(\$this-&gt;arr_data, \$_arr_sso);
        \$_arr_ssoData[&quot;signature&quot;] = \$this->sso_signature(\$_arr_ssoData);
        \$_arr_get     = \$this->fn_http(BG_SSO_URL . &quot;?mod=user&quot;, \$_arr_ssoData, &quot;post&quot;); //&#25552;&#20132;
        \$_arr_result  = \$this-&gt;result_process(\$_arr_get);

        if (\$_arr_result[&quot;alert&quot;] != &quot;y010401&quot;) {
            return \$_arr_result; //&#36820;&#22238;&#38169;&#35823;&#20449;&#24687;
        }

        \$_arr_decode          = \$this-&gt;sso_decode(\$_arr_result[&quot;code&quot;], \$_arr_result[&quot;key&quot;]); //&#35299;&#30721;
        \$_arr_decode[&quot;alert&quot;] = \$_arr_result[&quot;alert&quot;];

        return \$_arr_decode;
    }


    /** 返回结果处理
     * result_process function.
     *
     * @access private
     * @return void
     */
    private function result_process(\$arr_get) {
        if (!isset(\$arr_get[&quot;ret&quot;])) {
            \$_arr_result = array(
                &quot;alert&quot; =&gt; &quot;x030110&quot;
            );
            return \$_arr_result;
        }

        \$_arr_result = json_decode(\$arr_get[&quot;ret&quot;], true);
        if (!isset(\$_arr_result[&quot;alert&quot;])) {
            \$_arr_result = array(
                &quot;alert&quot; =&gt; &quot;x030110&quot;
            );
            return \$_arr_result;
        }

        return \$_arr_result;
    }


    /** 签名
     * sso_signature function.
     *
     * @access public
     * @param mixed \$arr_params
     * @return void
     */
    function sso_signature(\$arr_params) {
        \$_arr_sso = array(
            &quot;act_post&quot;  => &quot;signature&quot;, //方法
            &quot;params&quot;    => \$arr_params,
        );

        \$_arr_ssoData     = array_merge(\$this->arr_data, \$_arr_sso); //合并数组
        \$_arr_get         = fn_http(BG_SSO_URL . &quot;?mod=signature&quot;, \$_arr_ssoData, &quot;post&quot;); //提交

        return fn_jsonDecode(\$_arr_get[&quot;ret&quot;], &quot;no&quot;);
    }


    /** 解码
     * sso_decode function.
     *
     * @access public
     * @return void
     */
    function sso_decode(\$str_code, \$str_key) {
        \$_arr_sso = array(
            &quot;act_post&quot;   =&gt; &quot;decode&quot;, //&#26041;&#27861;
            &quot;code&quot;       =&gt; \$str_code, //&#21152;&#23494;&#20018;
            &quot;key&quot;        =&gt; \$str_key, //&#35299;&#30721;&#31192;&#38053;
        );

        \$_arr_ssoData     = array_merge(\$this-&gt;arr_data, \$_arr_sso); //&#21512;&#24182;&#25968;&#32452;
        \$_arr_get         = \$this->fn_http(BG_SSO_URL . &quot;?mod=code&quot;, \$_arr_ssoData, &quot;get&quot;); //&#25552;&#20132;

        return \$this->fn_jsonDecode(\$_arr_get[&quot;ret&quot;], &quot;decode&quot;);
    }


    //http
    function fn_http(\$str_url, \$arr_data, \$str_method = &quot;get&quot;) {

        \$_obj_http = curl_init();
        \$_str_data = http_build_query(\$arr_data);

        \$_arr_headers = array(
            &quot;Content-Type: application/x-www-form-urlencoded&quot;,
        );

        if (\$_arr_headers) {
            curl_setopt(\$_obj_http, CURLOPT_HTTPHEADER, \$_arr_headers);
        }

        if (\$str_method == &quot;post&quot;) {
            curl_setopt(\$_obj_http, CURLOPT_POST, true);
            curl_setopt(\$_obj_http, CURLOPT_POSTFIELDS, \$_str_data);
            curl_setopt(\$_obj_http, CURLOPT_URL, \$str_url);
        } else {
            if (stristr(\$str_url, &quot;?&quot;)) {
                \$_str_conn = &quot;&amp;&quot;;
            } else {
                \$_str_conn = &quot;?&quot;;
            }
            curl_setopt(\$_obj_http, CURLOPT_URL, \$str_url . \$_str_conn . \$_str_data);
        }

        curl_setopt(\$_obj_http, CURLOPT_RETURNTRANSFER, true);

        \$_obj_ret = curl_exec(\$_obj_http);

        \$_arr_return = array(
            &quot;ret&quot;     =&gt; \$_obj_ret,
            &quot;err&quot;     =&gt; curl_error(\$_obj_http),
            &quot;errno&quot;   =&gt; curl_errno(\$_obj_http),
        );

        curl_close(\$_obj_http);

        return \$_arr_return;
    }


    /** JSON 解码 (值可解码自 base64)
     * fn_jsonDecode function.
     *
     * @access public
     * @param string \$str_json
     * @param string \$method
     * @return void
     */
    function fn_jsonDecode(\$str_json = &quot;&quot;, \$method = &quot;&quot;) {
        if (isset(\$str_json)) {
            \$arr_json = json_decode(\$str_json, true); //json解码
            \$arr_json = \$this->fn_eachArray(\$arr_json, \$method);
        } else {
            \$arr_json = array();
        }
        return \$arr_json;
    }


    /** 遍历数组，并进行 base64 解码编码
     * fn_eachArray function.
     *
     * @access public
     * @param mixed \$arr
     * @param string \$method (default: &quot;encode&quot;)
     * @return void
     */
    function fn_eachArray(\$arr, \$method = &quot;encode&quot;) {
        \$_is_magic = get_magic_quotes_gpc();
        if (is_array(\$arr)) {
            foreach (\$arr as \$_key=&gt;\$_value) {
                if (is_array(\$_value)) {
                    \$arr[\$_key] = \$this->fn_eachArray(\$_value, \$method);
                } else {
                    switch (\$method) {
                        case &quot;encode&quot;:
                            if (!\$_is_magic) {
                                \$_str = addslashes(\$_value);
                            } else {
                                \$_str = \$_value;
                            }
                            \$arr[\$_key] = base64_encode(\$_str);
                        break;

                        case &quot;decode&quot;:
                            \$_str = base64_decode(\$_value);
                        break;

                        default:
                            if (!\$_is_magic) {
                                \$_str = addslashes(\$_value);
                            } else {
                                \$_str = \$_value;
                            }
                            \$arr[\$_key] = \$_str;
                        break;
                    }
                }
            }
        } else {
            \$arr = array();
        }
        return \$arr;
    }
}

\$obj_sso       = new CLASS_SSO(); //初始化对象
\$user_name     = \$_POST[&quot;user_name&quot;]; //表单获取用户名
\$user_pass     = \$_POST[&quot;user_pass&quot;]; //表单获取密码
\$arr_userRow   = \$obj_sso-&gt;sso_login(\$user_name, \$user_pass); //调用登录接口</code></pre>
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

    <h3>返回结果</h3>
    <p>
        baigo SSO 大部分 API 接口返回加密字符串，详情请查看具体接口，真正内容需要调用密文接口进行解密。解密后部分结果为 <mark>Base64 编码</mark>，需要进行 <mark>Base64 解码</mark>，请查看具体的接口。
    </p>
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
                        <td class=\"text-nowrap\">code</td>
                        <td class=\"text-nowrap\">string</td>
                        <td>加密字符串，需要解密。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
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
    &quot;prd_sso_ver&quot;: &quot;1.1.1&quot;, //SSO 版本号
    &quot;prd_sso_pub&quot;: 20150923, //SSO 版本发布时间
    &quot;code&quot;: &quot;CSMEIFh7AHYBOFIlXQwAaQE0UXENawF2WUxXUQNFVD4Ac1R%2BUSUFdQgnBmYMcARbAT5RMlprBjZdJQdvBSRQXgkPBEhYZAAnAXFSdV0mAHMBNVEhDQ4BOVliV2wDbFQhAGtUcFElBSwIdgZ2DHEEYQEiUQxaaAY6XWQHPgUkUD0JegRbWFkATwE3UnVdfwAiASVRIA00ASZZXFdxA2lUbgA0VHBRPQUiCBkGVwxTBDQBHVEpWkMGYF1KBxEFR1A1CRcEU1gzADgBf1J7XXEAdQEjUTYNIwELWXdXbANtVGYADlQ%2BUWgFZwg9Bm0MIAQ%2BAXJRHlpSBgJdNwcYBXxQQglrBE9YSgBIAWhSGV0kAD0BbVFxDX0Bdll2V3YDZVRxAA5UO1F3BSIIbgYhDE8EUAEZUStaSgY5XUIHYAVJUFQJbAR6WEMAVgFpUi9dHQBqAR1Rbg1zASk%3D&quot;, //加密字符串
    &quot;alert&quot;: &quot;y010102&quot; //返回代码
}</code></pre>
    </p>";
