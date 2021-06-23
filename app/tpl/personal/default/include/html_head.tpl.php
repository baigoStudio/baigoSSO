<?php use ginkgo\Plugin; ?>

<!DOCTYPE html>
<html lang="<?php echo $lang->getCurrent(); ?>">
<head>
    <?php Plugin::listen('action_personal_head_before'); //后台界面头部之前 ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="expires" content="-1">
    <title>
        <?php if (isset($cfg['title'])) {
            echo $cfg['title'];
        }

        if (isset($config['var_extra']['base']['site_name'])) {
            echo ' - ', $config['var_extra']['base']['site_name'];
        } ?>
    </title>

    <!--jQuery 库-->
    <script src="{:DIR_STATIC}lib/jquery/1.11.1/jquery.min.js" type="text/javascript"></script>
    <link href="{:DIR_STATIC}lib/bootstrap/4.5.2/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="{:DIR_STATIC}lib/font-awesome/5.9.0/css/fontawesome.min.css" type="text/css" rel="stylesheet">
    <link href="{:DIR_STATIC}lib/font-awesome/5.9.0/css/solid.min.css" type="text/css" rel="stylesheet">

    <link href="{:DIR_STATIC}css/common.css" type="text/css" rel="stylesheet">
    <link href="{:DIR_STATIC}sso/css/personal.css" type="text/css" rel="stylesheet">

    <?php Plugin::listen('action_personal_head_after'); //后台界面头部之后 ?>
</head>
<body class="bg-light">

    <?php if (!isset($cfg['no_loading'])) { ?>
        <div id="loading_mask" class="position-fixed bg-loading">
            <a href="javascript:window.location.reload();" class="text-decoration-none stretched-link">
                <div class="d-flex justify-content-center">
                    <div class="alert alert-light mt-5 shadow">
                        <div class="d-flex align-items-center">
                            <div class="spinner-grow mr-2"></div>
                            <div>Loading...</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    <?php } ?>
