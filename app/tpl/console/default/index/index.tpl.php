<?php function status_process($str_status, $echo = '') {
    switch ($str_status) {
        case 'wait':
            $_str_css = 'warning';
        break;

        case 'enable':
        case 'read':
            $_str_css = 'success';
        break;

        case 'on':
            $_str_css = 'info';
        break;

        default:
            $_str_css = 'secondary';
        break;
    } ?>
    <span class="badge badge-pill badge-<?php echo $_str_css; ?>">
        <?php echo $echo; ?>
    </span>
<?php }

$cfg = array(
    'title'             => $lang->get('Dashboard', 'console.common'),
    'menu_active'       => 'dashboard',
    'pathInclude'       => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'console_head' . GK_EXT_TPL); ?>

    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><?php echo $lang->get('Shortcut', 'console.common'); ?></span>
            <span>
                <a href="<?php echo $route_console; ?>index/setting/">
                    <span class="fas fa-wrench"></span>
                    <?php echo $lang->get('Settings'); ?>
                </a>
            </span>
        </div>
        <div class="card-body">
            <?php foreach ($adminLogged['admin_shortcut'] as $key_m=>$value_m) { ?>
                <a class="btn btn-primary m-2" href="<?php echo $route_console, $value_m['ctrl']; ?>/<?php echo $value_m['act']; ?>/">
                    <?php echo $value_m['title']; ?>
                </a>
            <?php } ?>
        </div>
    </div>

    <div class="card-columns">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><?php echo $lang->get('User'); ?></span>
                <span><?php echo $lang->get('Count'); ?></span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>
                        <?php echo $lang->get('Total'); ?>
                    </span>
                    <span class="badge badge-pill badge-info">
                        <?php echo $user_count['total']; ?>
                    </span>
                </li>
                <?php foreach ($status_user as $key=>$value) { ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <?php echo $lang->get($value); ?>
                        </span>
                        <?php status_process($value, $user_count[$value]); ?>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><?php echo $lang->get('App'); ?></span>
                <span><?php echo $lang->get('Count'); ?></span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>
                        <?php echo $lang->get('Total'); ?>
                    </span>
                    <span class="badge badge-pill badge-info">
                        <?php echo $app_count['total']; ?>
                    </span>
                </li>
                <?php foreach ($status_app as $key=>$value) { ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <?php echo $lang->get($value); ?>
                        </span>
                        <?php status_process($value, $app_count[$value]); ?>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><?php echo $lang->get('Private message'); ?></span>
                <span><?php echo $lang->get('Count'); ?></span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>
                        <?php echo $lang->get('Total'); ?>
                    </span>
                    <span class="badge badge-pill badge-info">
                        <?php echo $pm_count['total']; ?>
                    </span>
                </li>
                <?php foreach ($status_pm as $key=>$value) { ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <?php echo $lang->get($value); ?>
                        </span>
                        <?php status_process($value, $pm_count[$value]); ?>
                    </li>
                <?php }

                foreach ($type_pm as $key=>$value) { ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <?php echo $lang->get($value); ?>
                        </span>
                        <span class="badge badge-pill badge-info">
                            <?php echo $pm_count[$value]; ?>
                        </span>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><?php echo $lang->get('Administrator'); ?></span>
                <span><?php echo $lang->get('Count'); ?></span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>
                        <?php echo $lang->get('Total'); ?>
                    </span>
                    <span class="badge badge-pill badge-info">
                        <?php echo $admin_count['total']; ?>
                    </span>
                </li>
                <?php foreach ($status_admin as $key=>$value) { ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <?php echo $lang->get($value); ?>
                        </span>
                        <?php status_process($value, $admin_count[$value]); ?>
                    </li>
                <?php }

                foreach ($type_admin as $key=>$value) { ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <?php echo $lang->get($value); ?>
                        </span>
                        <span class="badge badge-pill badge-info">
                            <?php echo $admin_count[$value]; ?>
                        </span>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>

<?php include($cfg['pathInclude'] . 'console_foot' . GK_EXT_TPL);
include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);