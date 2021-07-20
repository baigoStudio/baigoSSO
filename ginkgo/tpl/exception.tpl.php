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

    <script src="{:DIR_STATIC}lib/jquery/1.11.1/jquery.min.js" type="text/javascript"></script>
    <link href="{:DIR_STATIC}lib/bootstrap/4.5.2/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="{:DIR_STATIC}css/fw.css" type="text/css" rel="stylesheet">
</head>
<body>

    <div class="container my-5">
        <div class="alert alert-danger p-5">
            <h1 class="text-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512" class="img-fluid gk-icon-lg"><path fill="#721c24" d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm0 448c-110.3 0-200-89.7-200-200S137.7 56 248 56s200 89.7 200 200-89.7 200-200 200zm-80-216c17.7 0 32-14.3 32-32s-14.3-32-32-32-32 14.3-32 32 14.3 32 32 32zm160-64c-17.7 0-32 14.3-32 32s14.3 32 32 32 32-14.3 32-32-14.3-32-32-32zm-80 128c-40.2 0-78 17.7-103.8 48.6-8.5 10.2-7.1 25.3 3.1 33.8 10.2 8.4 25.3 7.1 33.8-3.1 16.6-19.9 41-31.4 66.9-31.4s50.3 11.4 66.9 31.4c8.1 9.7 23.1 11.9 33.8 3.1 10.2-8.5 11.5-23.6 3.1-33.8C326 321.7 288.2 304 248 304z"/></svg>
            </h1>
            <h3 class="display-3 text-center">
                <?php if (isset($http_status) && !empty($http_status)) {
                    echo $lang->get($http_status);
                } else {
                    echo $lang->get('Error');
                } ?>
            </h3>
            <?php if (isset($status_code) && !empty($status_code)) { ?>
                <h4 class="text-center">
                    <?php echo $status_code; ?>
                </h4>
            <?php } ?>
            <hr>
            <dl>
                <?php if (isset($err_message) && !empty($err_message)) { ?>
                    <dt><?php echo $lang->get('Error message'); ?></dt>
                    <dd>
                        <div class="lead text-break"><?php echo $lang->get($err_message); ?></div>

                        <?php if (isset($err_detail) && !empty($err_detail)) { ?>
                            <div class="text-break"><?php echo $lang->get($err_detail); ?></div>
                        <?php } ?>
                    </dd>
                <?php }

                if (isset($err_file) && !empty($err_file)) { ?>
                    <dt><?php echo $lang->get('Error file'); ?></dt>
                    <dd class="lead text-break">
                        <?php echo $err_file; ?>
                    </dd>
                <?php }

                if (isset($err_line) && !empty($err_line)) { ?>
                    <dt><?php echo $lang->get('Line number'); ?></dt>
                    <dd class="lead text-break">
                        <?php echo $err_line; ?>
                    </dd>
                <?php }

                if (isset($err_type) && !empty($err_type)) { ?>
                    <dt><?php echo $lang->get('Error type'); ?></dt>
                    <dd class="lead text-break">
                        <?php echo $err_type; ?>
                    </dd>
                <?php }

                if (isset($http_status) && !empty($http_status)) { ?>
                    <dt><?php echo $lang->get('Http status'); ?></dt>
                    <dd class="lead text-break">
                        <?php echo $http_status; ?>
                    </dd>
                <?php } ?>
            </dl>

        </div>
    </div>

    <script src="{:DIR_STATIC}lib/bootstrap/4.5.2/js/bootstrap.bundle.min.js" type="text/javascript"></script>

</body>
</html>
