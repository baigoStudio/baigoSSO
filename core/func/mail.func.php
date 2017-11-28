<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

fn_include(BG_PATH_LIB . 'PHPMailer' . DS . 'PHPMailerAutoload.php'); //载入 PHPMailer 类

function fn_mailSend($str_mailTo, $str_subject, $str_content) {

    $_obj_mail = new PHPMailer(true); //初始化

    switch (BG_SMTP_TYPE) {
        case 'phpmail':
            $_obj_mail->isMail(); //使用 phpmail 函数
        break;

        case 'sendmail':
            $_obj_mail->isSendmail();
        break;

        case 'qmail':
            $_obj_mail->isQmail();
        break;

        default:
            $_obj_mail->isSMTP(); //使用 SMTP
        break;
    }

    switch (BG_SMTP_SEC) {
        case 'tls':
            $_str_sectype = 'tls';
        break;

        case 'ssl':
            $_str_sectype = 'ssl';
        break;

        default:
            $_str_sectype = '';
        break;
    }

    $_obj_mail->SMTPSecure = $_str_sectype;

    switch (BG_SMTP_AUTHTYPE) {
        case 'plain':
            $_str_authtype = 'PLAIN';
        break;

        case 'cram-md5':
            $_str_authtype = 'CRAM-MD5';
        break;

        case 'xoauth2':
            $_str_authtype = 'XOAUTH2';
        break;

        default:
            $_str_authtype = 'LOGIN';
        break;
    }

    $_obj_mail->AuthType = $_str_authtype;

    $_obj_mail->isHTML(); //发送 HTML

    $_obj_mail->SMTPDebug   = 0; //SMTP 调试开关 0 关闭，1 客户端消息, 2 客户端与服务端消息
    $_obj_mail->Debugoutput = 'html'; //SMTP 调试信息类型
    $_obj_mail->CharSet     = 'UTF-8'; //邮件编码
    $_obj_mail->Host        = BG_SMTP_HOST; //主机
    $_obj_mail->Port        = BG_SMTP_PORT; //端口
    $_obj_mail->Timeout     = 60;

    if (BG_SMTP_AUTH == 'true') {
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

    $_obj_mail->AltBody     = strip_tags($str_content); //HTML 无法显示时的替代内容
    //$_obj_mail->addAttachment('images/phpmailer_mini.png'); //附件

    $_is_succ = $_obj_mail->send();

    //print_r($_obj_mail->ErrorInfo);

    $_obj_mail->smtpClose();

    return $_is_succ;
}
