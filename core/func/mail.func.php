<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
    exit("Access Denied");
}

include_once(BG_PATH_PHPMAILER . "PHPMailerAutoload.php"); //载入 PHPMailer 类

//fn_mailSend("iwee@iwee.cn", "测试邮件", "<b><a href=\"http://www.iwee.cn\">link</a> test</b>", "test");

function fn_mailSend($str_mailTo, $str_subject, $str_content, $str_text = "") {

    $_obj_mail              = new PHPMailer; //初始化
    $_obj_mail->isSMTP(); //使用 SMTP
    $_obj_mail->isHTML(); //发送 HTML

    $_obj_mail->SMTPDebug   = 0; //SMTP 调试开关 0 关闭，1 客户端消息, 2 客户端与服务端消息
    $_obj_mail->Debugoutput = "html"; //SMTP 调试信息类型
    $_obj_mail->CharSet     = "UTF-8"; //邮件编码
    $_obj_mail->Host        = BG_SMTP_HOST; //主机
    $_obj_mail->Port        = BG_SMTP_PORT; //端口

    if (BG_SMTP_AUTH == "true") {
        $_is_auth = true;
    } else {
        $_is_auth = false;
    }
    $_obj_mail->SMTPAuth    = $_is_auth; //是否验证
    $_obj_mail->Username    = BG_SMTP_USER; //用户名
    $_obj_mail->Password    = BG_SMTP_PASS; //密码

    $_obj_mail->setFrom(BG_SMTP_FROM); //来自地址
    $_obj_mail->addReplyTo(BG_SMTP_REPLY); //回复地址
    $_obj_mail->addAddress($str_mailTo); //发送至

    $_obj_mail->Subject     = $str_subject; //主题

    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    $_obj_mail->msgHTML($str_content); //内容

    $_obj_mail->AltBody     = $str_text; //HTML 无法显示时的替代内容
    //$_obj_mail->addAttachment("images/phpmailer_mini.png"); //附件

    return $_obj_mail->send();
}
