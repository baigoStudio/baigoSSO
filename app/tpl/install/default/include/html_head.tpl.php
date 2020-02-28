<!DOCTYPE html>
<html lang="<?php echo $lang->getCurrent(); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>
        <?php if (isset($cfg['sub_title'])) {
            echo $cfg['sub_title'], ' - ';
        }

        if (isset($cfg['title'])) {
            echo $cfg['title'];
        } ?>
    </title>

    <!--jQuery 库-->
    <script src="{:DIR_STATIC}lib/jquery/1.11.1/jquery.min.js" type="text/javascript"></script>

    <!--bootstrap-->
    <link href="{:DIR_STATIC}lib/bootstrap/4.3.1/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="{:DIR_STATIC}lib/font-awesome/5.9.0/css/fontawesome.min.css" type="text/css" rel="stylesheet">
    <link href="{:DIR_STATIC}lib/font-awesome/5.9.0/css/solid.min.css" type="text/css" rel="stylesheet">

    <link href="{:DIR_STATIC}css/common.css" type="text/css" rel="stylesheet">
    <link href="{:DIR_STATIC}sso/css/install.css" type="text/css" rel="stylesheet">
</head>

<body>

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
