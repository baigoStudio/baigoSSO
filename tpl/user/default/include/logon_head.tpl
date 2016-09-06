<!DOCTYPE html>
<html lang="{$config.lang|truncate:2:''}">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>{$cfg.title} - {$smarty.const.BG_SITE_NAME}</title>

    <!--jQuery 库-->
    <script src="{$smarty.const.BG_URL_STATIC}js/jquery.min.js" type="text/javascript"></script>
    <link href="{$smarty.const.BG_URL_STATIC}js/bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="{$smarty.const.BG_URL_STATIC}js/baigoValidator/baigoValidator.css" type="text/css" rel="stylesheet">
    <link href="{$smarty.const.BG_URL_STATIC}js/baigoSubmit/baigoSubmit.css" type="text/css" rel="stylesheet">
    <link href="{$smarty.const.BG_URL_STATIC}admin/{$smarty.const.BG_DEFAULT_UI}/css/admin_logon.css" type="text/css" rel="stylesheet">

</head>
<body>

    <div class="container global">

        <h3>{$smarty.const.BG_SITE_NAME}</h3>

        <div class="panel panel-success">
            <div class="panel-heading">
                <h4>
                    {$cfg.title}
                </h4>
            </div>

            <div class="panel-body">