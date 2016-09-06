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
        <?php foreach ($sync["urlRows"] as $key=>$value) { ?>
            $.ajax({
                url: "<?php echo $value; ?>", //url
                type: "get", //方法
                dataType: "jsonp", //数据格式为 jsonp 支持跨域提交
                async: false, //设置为同步
                complete: function(){ //读取返回结果
                    <?php if (end($sync["urlRows"]) == $value) { ?> //如果是最后一个 URL，访问完毕后跳转
                        window.location.href = "http://www.abc.com"; //跳转
                    <?php } ?>
                }
            });
        <?php } ?>
    });
    </script>

</body>
</html>
