<!DOCTYPE html>
<html lang="<?php echo $lang->getCurrent(); ?>">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta http-equiv="pragma" content="no-cache">
  <meta http-equiv="cache-control" content="no-cache">
  <meta http-equiv="expires" content="-1">
  <title>
    <?php if (isset($http_status) && !empty($http_status)) {
      echo $lang->get($http_status);
    } else {
      echo $lang->get('Error');
    } ?>
  </title>

  <style type="text/css">
  *,
  *::before,
  *::after {
    box-sizing: border-box;
  }

  html {
    font-family: sans-serif;
    line-height: 1.15;
    -webkit-text-size-adjust: 100%;
    -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
  }

  body {
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    text-align: left;
    background-color: #fff;
  }

  hr {
    box-sizing: content-box;
    height: 0;
    overflow: visible;
    margin-top: 1rem;
    margin-bottom: 1rem;
    border: 0;
    border-top: 1px solid #f1b0b7;
  }

  h1, h3, h4 {
    font-weight: 500;
    line-height: 1.2;
    margin: 0 0 0.5rem 0;
  }

  h1 {
    font-size: 2.5rem;
  }

  h3 {
    font-size: 4.5rem;
    font-weight: 300;
    line-height: 1.2;
  }

  h4 {
    font-size: 1.5rem;
  }

  dl {
    margin: 0 0 1rem 0;
  }

  dt {
    font-weight: 700;
  }

  dd {
    margin: 0 0 .5rem 0;
  }

  a {
    color: #007bff;
    text-decoration: none;
    background-color: transparent;
  }

  a:hover {
    color: #0056b3;
    text-decoration: underline;
  }

  svg {
    overflow: hidden;
    vertical-align: middle;
  }

  .container {
    width: 100%;
    padding: 0 15px;
    margin: 3rem auto !important;
  }

  @media (min-width: 576px) {
    .container {
      max-width: 540px;
    }
  }

  @media (min-width: 768px) {
    .container {
      max-width: 720px;
    }
  }

  @media (min-width: 992px) {
    .container {
      max-width: 960px;
    }
  }

  @media (min-width: 1200px) {
    .container {
      max-width: 1140px;
    }
  }

  .text-center {
    text-align: center !important;
  }

  .alert {
    position: relative;
    padding: 2rem !important;
    margin-bottom: 1rem;
    border: 1px solid #f5c6cb;
    border-radius: 0.25rem;
    color: #721c24;
    background-color: #f8d7da;
  }

  .lead {
    font-size: 1.25rem;
    font-weight: 300;
  }

  .text-break {
    word-break: break-all !important;
    overflow-wrap: break-word !important;
  }
  </style>
</head>
<body>
  <div class="container">
