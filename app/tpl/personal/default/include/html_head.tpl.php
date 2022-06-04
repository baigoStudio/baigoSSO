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

  <link href="{:DIR_STATIC}image/favicon.png" rel="shortcut icon">
  <link href="{:DIR_STATIC}lib/bootstrap/4.6.0/css/bootstrap.min.css" type="text/css" rel="stylesheet">

  <?php if (isset($cfg['baigoSubmit'])) { ?>
    <link rel="stylesheet" href="{:DIR_STATIC}lib/baigoSubmit/2.1.4/baigoSubmit.css" type="text/css" rel="stylesheet">
  <?php }

  if (isset($cfg['baigoValidate'])) { ?>
    <link rel="stylesheet" href="{:DIR_STATIC}lib/baigoValidate/3.1.1/baigoValidate.css" type="text/css" rel="stylesheet">
  <?php } ?>

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
