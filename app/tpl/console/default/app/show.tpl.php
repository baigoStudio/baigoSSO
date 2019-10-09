<?php $cfg = array(
    'title'             => $lang->get('Application', 'console.common') . ' &raquo; ' . $lang->get('Show'),
    'menu_active'       => 'app',
    'sub_active'        => 'index',
    'baigoSubmit'       => 'true',
    'baigoDialog'       => 'true',
    'pathInclude'       => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'console_head' . GK_EXT_TPL); ?>

    <nav class="nav mb-3">
        <a href="<?php echo $route_console; ?>app/" class="nav-link">
            <span class="fas fa-chevron-left"></span>
            <?php echo $lang->get('Back'); ?>
        </a>
    </nav>

    <form name="app_form" id="app_form" action="<?php echo $route_console; ?>app/reset/">
        <input type="hidden" name="__token__" value="<?php echo $token; ?>">
        <input type="hidden" name="app_id" value="<?php echo $appRow['app_id']; ?>">

        <div class="row">
            <div class="col-xl-9">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="form-group">
                            <label><?php echo $lang->get('Application name'); ?></label>
                            <div class="form-text"><?php echo $appRow['app_name']; ?></div>
                        </div>

                        <div class="form-group">
                            <label><?php echo $lang->get('Base url of API'); ?></label>
                            <div><?php echo $url_api; ?></div>
                        </div>

                        <div class="form-group">
                            <label><?php echo $lang->get('App ID'); ?></label>
                            <div class="form-text"><?php echo $appRow['app_id']; ?></div>
                        </div>

                        <div class="form-group">
                            <label><?php echo $lang->get('App Key'); ?></label>
                            <input type="text" value="<?php echo $appRow['app_key']; ?>" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <label><?php echo $lang->get('App Secret'); ?></label>
                            <input type="text" value="<?php echo $appRow['app_secret']; ?>" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <span class="fas fa-redo-alt"></span>
                                <?php echo $lang->get('Reset App Key & App Secret'); ?>
                            </button>
                            <small class="form-text"><?php echo $lang->get('If you suspect that the App Key or App Secret has been compromised, you can reset them.'); ?></small>
                        </div>

                        <div class="form-group">
                            <label><?php echo $lang->get('URL of notifications'); ?></label>
                            <div class="form-text"><?php echo $appRow['app_url_notify']; ?></div>
                        </div>

                        <div class="form-group">
                            <label><?php echo $lang->get('URL of sync login notifications'); ?></label>
                            <div class="form-text"><?php echo $appRow['app_url_sync']; ?></div>
                        </div>

                        <div class="form-group">
                            <label><?php echo $lang->get('License'); ?></label>
                            <dl>
                                <?php foreach ($allowRows as $key_m=>$value_m) { ?>
                                    <dt>
                                        <?php echo $lang->get($value_m['title']); ?>
                                    </dt>
                                    <dd>
                                        <?php foreach ($value_m['allow'] as $key_s=>$value_s) { ?>
                                            <span>
                                                <span class="fas fa-<?php if (isset($appRow['app_allow'][$key_m][$key_s])) { ?>check-circle text-success<?php } else { ?>times-circle text-danger<?php } ?>"></span>

                                                <?php echo $lang->get($value_s); ?>
                                            </span>
                                        <?php } ?>
                                    </dd>
                                <?php } ?>
                            </dl>
                        </div>

                        <div class="form-group">
                            <label><?php echo $lang->get('Allowed IPs'); ?></label>
                            <div class="form-text"><pre><?php echo $appRow['app_ip_allow']; ?></pre></div>
                        </div>

                        <div class="form-group">
                            <label><?php echo $lang->get('Banned IPs'); ?></label>
                            <div class="form-text"><pre><?php echo $appRow['app_ip_bad']; ?></pre></div>
                        </div>

                        <div class="form-group">
                            <label><?php echo $lang->get('Note'); ?></label>
                            <div class="form-text"><?php echo $appRow['app_note']; ?></div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-info bg-notify">
                                <span class="fas fa-flag-checkered"></span>
                                <?php echo $lang->get('Notification test'); ?>
                            </button>

                            <a href="<?php echo $route_console; ?>app/form/id/<?php echo $appRow['app_id']; ?>/">
                                <span class="fas fa-edit"></span>
                                <?php echo $lang->get('Edit'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3">
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="form-group">
                            <label><?php echo $lang->get('ID'); ?></label>
                            <div class="form-text"><?php echo $appRow['app_id']; ?></div>
                        </div>

                        <div class="form-group">
                            <label><?php echo $lang->get('Status'); ?></label>
                            <div class="form-text"><?php $str_status = $appRow['app_status'];
                            include($cfg['pathInclude'] . 'status_process' . GK_EXT_TPL); ?></div>
                        </div>

                        <div class="form-group">
                            <label><?php echo $lang->get('Sync status'); ?></label>
                            <div class="form-text"><?php $str_status = $appRow['app_sync'];
                            include($cfg['pathInclude'] . 'status_process' . GK_EXT_TPL); ?></div>
                        </div>

                        <div class="form-group">
                            <label><?php echo $lang->get('Parameter'); ?></label>
                            <?php foreach ($appRow['app_param'] as $_key=>$_value) { ?>
                                <div class="input-group mb-2">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text"><?php if (isset($_value['key'])) { echo $_value['key']; } ?></span>
                                    </span>
                                    <input type="text" value="<?php if (isset($_value['value'])) { echo $_value['value']; } ?>" class="form-control" readonly>
                                </div>
                            <?php } ?>
                            <small class="form-text"><?php echo $lang->get('These parameters will be transmitted with the notification, such as: <code>key_1=value_1&key_2=value_2</code>'); ?></small>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="<?php echo $route_console; ?>app/form/id/<?php echo $appRow['app_id']; ?>/">
                            <span class="fas fa-edit"></span>
                            <?php echo $lang->get('Edit'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form id="app_notify" name="app_notify" action="<?php echo $route_console; ?>app/notify/">
        <input type="hidden" name="app_id" value="<?php echo $appRow['app_id']; ?>">
        <input type="hidden" name="__token__" value="<?php echo $token; ?>">
    </form>

<?php include($cfg['pathInclude'] . 'console_foot' . GK_EXT_TPL); ?>

    <script type="text/javascript">
    var opts_dialog = {
        btn_text: {
            cancel: '<?php echo $lang->get('Cancel'); ?>',
            confirm: '<?php echo $lang->get('Confirm'); ?>'
        }
    };

    var opts_submit_reset = {
        modal: {
            btn_text: {
                close: '<?php echo $lang->get('Close'); ?>',
                ok: '<?php echo $lang->get('Ok'); ?>'
            }
        },
        msg_text: {
            submitting: '<?php echo $lang->get('Submitting'); ?>'
        }
    };

    var opts_submit_notify = {
        modal: {
            btn_text: {
                close: '<?php echo $lang->get('Close'); ?>',
                ok: '<?php echo $lang->get('Ok'); ?>'
            }
        },
        msg_text: {
            submitting: '<?php echo $lang->get('Submitting'); ?>'
        }
    };

    $(document).ready(function(){
        var obj_submit_notify  = $('#app_notify').baigoSubmit(opts_submit_notify);

        $('.bg-notify').click(function(){
            obj_submit_notify.formSubmit();
        });

        var obj_dialog       = $.baigoDialog(opts_dialog);
        var obj_submit_reset = $('#app_form').baigoSubmit(opts_submit_reset);

        $('#app_form').submit(function(){
            obj_dialog.confirm('<?php echo $lang->get('Are you sure to reset?'); ?>', function(result){
                if (result) {
                    obj_submit_reset.formSubmit();
                }
            });
        });
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);