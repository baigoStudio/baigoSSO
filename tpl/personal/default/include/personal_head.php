<?php header("Content-Type: text/html; charset=utf-8"); ?>
<!DOCTYPE html>
<html lang="<?php echo substr($this->config['lang'], 0, 2); ?>">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?php echo $cfg['title']; ?> - <?php echo BG_SITE_NAME; ?></title>

    <!--jQuery 库-->
    <script src="<?php echo BG_URL_STATIC; ?>lib/jquery/1.11.1/jquery.min.js" type="text/javascript"></script>
    <link href="<?php echo BG_URL_STATIC; ?>lib/bootstrap/4.0.0/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="<?php echo BG_URL_STATIC; ?>lib/iconic/1.1.0/css/open-iconic-bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="<?php echo BG_URL_STATIC; ?>lib/baigoValidator/2.2.5/baigoValidator.css" type="text/css" rel="stylesheet">
    <link href="<?php echo BG_URL_STATIC; ?>lib/baigoSubmit/2.0.5/baigoSubmit.css" type="text/css" rel="stylesheet">

    <link href="<?php echo BG_URL_STATIC; ?>css/common.css" type="text/css" rel="stylesheet">
    <link href="<?php echo BG_URL_STATIC; ?>personal/<?php echo BG_SITE_TPL; ?>/css/personal.css" type="text/css" rel="stylesheet">

</head>
<body>

    <div class="container">
        <div class="bg-card-md">
        <h3><?php echo BG_SITE_NAME; ?></h3>
            <div class="card">
                <div class="card-header bg-card-header">
                    <img class="img-fluid mx-auto d-block" src="<?php echo BG_URL_STATIC; ?>personal/<?php echo BG_SITE_TPL; ?>/image/logo.png">
                </div>

                <div class="card-body">
                    <h4>
                        <?php echo $cfg['title']; ?>
                    </h4>
                    <hr>