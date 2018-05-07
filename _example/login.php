<?php
define(BG_SSO_URL, 'http://www.domain.com/api/api.php'); //SSO 地址
define(BG_SSO_APPID, 1); //APP ID
define(BG_SSO_APPKEY, ''); //APP KEY

require('func.php');
require('notify.class.php');
require('sso.class.php');
require('sync.class.php');
require('sso.class.php');
require('crypt.class.php');
require('sign.class.php');

$obj_sso = new CLASS_SSO();

$str_userName = fn_post('user_name');
$str_userPass = fn_post('user_pass');

$arr_userSso = $obj_sso->sso_login($str_userName, $str_userPass); //调用登录方法

/* 开始会话等操作  */

$sync = $obj_sso->sso_sync_login(); //调用同步方法
?>
<!DOCTYPE html>
<html lang="zh">
<head>

    <meta charset="utf-8">
    <title>login</title>

    <!--jQuery 库-->
    <script src="jquery.min.js" type="text/javascript"></script>

</head>
<body>

    <script type="text/javascript">
    $(document).ready(function(){
        <?php $_count = 1;
        foreach ($sync['urlRows'] as $key=>$value) { ?>
            $.ajax({
                url: "<?php echo $value; ?>", //url
                type: "get", //方法
                dataType: "jsonp", //数据格式为 jsonp 支持跨域提交
                async: false, //设置为同步
                complete: function(){ //读取返回结果
                    <?php if ($_count >= count($sync['urlRows'])) { ?> //如果是最后一个 URL，访问完毕后跳转
                        window.location.href = "http://www.abc.com"; //跳转
                    <?php } ?>
                }
            });
        <?php $_count++;
        } ?>
    });
    </script>

</body>
</html>
