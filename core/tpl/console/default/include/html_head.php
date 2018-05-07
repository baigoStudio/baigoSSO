<?php header("Content-Type: text/html; charset=utf-8"); ?>
<!DOCTYPE html>
<html lang="<?php echo substr($this->config['lang'], 0, 2); ?>">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="expires" content="-1">
    <title>
        <?php if (isset($cfg['title']) && !fn_isEmpty($cfg['title'])) {
            echo $cfg['title'], ' - ';
        }
        echo $this->lang['common']['page']['console'], ' - ', BG_SITE_NAME; ?>
    </title>

    <!--jQuery 库-->
    <script src="<?php echo BG_URL_STATIC; ?>lib/jquery/1.11.1/jquery.min.js" type="text/javascript"></script>
    <link href="<?php echo BG_URL_STATIC; ?>lib/bootstrap/4.0.0/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="<?php echo BG_URL_STATIC; ?>lib/iconic/1.1.0/css/open-iconic-bootstrap.min.css" type="text/css" rel="stylesheet">

    <?php if (isset($cfg['baigoValidator'])) { ?>
        <!--表单验证 js-->
        <link href="<?php echo BG_URL_STATIC; ?>lib/baigoValidator/2.2.5/baigoValidator.css" type="text/css" rel="stylesheet">
    <?php }

    if (isset($cfg['baigoSubmit'])) { ?>
        <!--表单 ajax 提交 js-->
        <link href="<?php echo BG_URL_STATIC; ?>lib/baigoSubmit/2.0.5/baigoSubmit.css" type="text/css" rel="stylesheet">
    <?php }

    if (isset($cfg['upload'])) { ?>
        <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
        <link href="<?php echo BG_URL_STATIC; ?>lib/webuploader/0.1.5/webuploader.css" type="text/css" rel="stylesheet">
    <?php }

    if (isset($cfg['datetimepicker'])) { ?>
        <link href="<?php echo BG_URL_STATIC; ?>lib/datetimepicker/2.3.0/jquery.datetimepicker.css" type="text/css" rel="stylesheet">
    <?php } ?>

    <link href="<?php echo BG_URL_STATIC; ?>css/common.css" type="text/css" rel="stylesheet">
    <link href="<?php echo BG_URL_STATIC; ?>console/<?php echo BG_DEFAULT_UI; ?>/css/console.css" type="text/css" rel="stylesheet">

</head>
<body>
