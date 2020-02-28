<?php $cfg = array(
    'title'             => $lang->get('Administrator', 'console.common') . ' &raquo; ' . $lang->get('Authorization'),
    'menu_active'       => 'admin',
    'sub_active'        => 'auth',
    'baigoValidate'    => 'true',
    'baigoSubmit'       => 'true',
    'baigoCheckall'     => 'true',
    'pathInclude'       => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'console_head' . GK_EXT_TPL); ?>

    <nav class="nav mb-3">
        <a href="<?php echo $route_console; ?>admin/" class="nav-link">
            <span class="fas fa-chevron-left"></span>
            <?php echo $lang->get('Back'); ?>
        </a>
    </nav>

    <form name="admin_form" id="admin_form" autocomplete="off" action="<?php echo $route_console; ?>auth/submit/">
        <input type="hidden" name="__token__" value="<?php echo $token; ?>">

        <div class="row">
            <div class="col-xl-9">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="form-group">
                            <label><?php echo $lang->get('Username'); ?> <span class="text-danger">*</span></label>
                            <input type="text" name="admin_name" id="admin_name" class="form-control">
                            <small class="form-text" id="msg_admin_name"></small>
                        </div>

                        <div class="form-group">
                            <label><?php echo $lang->get('Permission'); ?> <span class="text-danger">*</span></label>

                            <?php include($cfg['pathInclude'] . 'allow_list' . GK_EXT_TPL); ?>
                            <small class="form-text" id="msg_admin_allow"></small>
                        </div>

                        <div class="form-group">
                            <label><?php echo $lang->get('Note'); ?></label>
                            <input type="text" name="admin_note" id="admin_note" class="form-control">
                            <small class="form-text" id="msg_admin_note"></small>
                        </div>

                        <div class="bg-validate-box"></div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <?php echo $lang->get('Save'); ?>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-xl-3">
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="form-group">
                            <label><?php echo $lang->get('Type'); ?> <span class="text-danger">*</span></label>
                            <?php foreach ($type as $key=>$value) { ?>
                                <div class="form-check">
                                    <input type="radio" name="admin_type" id="admin_type_<?php echo $value; ?>" value="<?php echo $value; ?>" <?php if ($adminRow['admin_type'] == $value) { ?>checked<?php } ?> class="form-check-input">
                                    <label for="admin_type_<?php echo $value; ?>" class="form-check-label">
                                        <?php echo $lang->get($value); ?>
                                    </label>
                                </div>
                            <?php } ?>
                            <small class="form-text" id="msg_admin_type"></small>
                        </div>

                        <div class="form-group">
                            <label><?php echo $lang->get('Status'); ?> <span class="text-danger">*</span></label>
                            <?php foreach ($status as $key=>$value) { ?>
                                <div class="form-check">
                                    <input type="radio" name="admin_status" id="admin_status_<?php echo $value; ?>" value="<?php echo $value; ?>" <?php if ($adminRow['admin_status'] == $value) { ?>checked<?php } ?> class="form-check-input">
                                    <label for="admin_status_<?php echo $value; ?>" class="form-check-label">
                                        <?php echo $lang->get($value); ?>
                                    </label>
                                </div>
                            <?php } ?>
                            <small class="form-text" id="msg_admin_status"></small>
                        </div>

                        <div class="form-group">
                            <label><?php echo $lang->get('Personal permission'); ?></label>
                            <?php foreach ($config['console']['profile'] as $key=>$value) { ?>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="admin_allow_profile[<?php echo $key; ?>]" id="admin_allow_profile_<?php echo $key; ?>" value="1" class="custom-control-input">
                                    <label for="admin_allow_profile_<?php echo $key; ?>" class="custom-control-label">
                                        <?php echo $lang->get('Not allowed to edit'), '&nbsp;', $lang->get($value['title'], 'console.common'); ?>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <?php echo $lang->get('Save'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

<?php include($cfg['pathInclude'] . 'console_foot' . GK_EXT_TPL); ?>

    <script type="text/javascript">
    var opts_validate_form = {
        rules: {
            admin_name: {
                length: '1,30',
                format: 'alpha_dash',
                ajax: {
                    url: '<?php echo $route_console; ?>auth/check/'
                }
            },
            admin_note: {
                max: 30
            },
            admin_allow: {
                checkbox: '1'
            },
            admin_type: {
                require: true
            },
            admin_status: {
                require: true
            }
        },
        attr_names: {
            admin_name: '<?php echo $lang->get('Username'); ?>',
            admin_note: '<?php echo $lang->get('Note'); ?>',
            admin_type: '<?php echo $lang->get('Type'); ?>',
            admin_allow: '<?php echo $lang->get('Permission'); ?>',
            admin_status: '<?php echo $lang->get('Status'); ?>'
        },
        selector_types: {
            admin_allow: 'validate',
            admin_type: 'name',
            admin_status: 'name'
        },
        type_msg: {
            require: '<?php echo $lang->get('{:attr} require'); ?>',
            max: '<?php echo $lang->get('Max size of {:attr} must be {:rule}'); ?>',
            length: '<?php echo $lang->get('Size of {:attr} must be {:rule}'); ?>',
            checkbox: '<?php echo $lang->get('Choose at least one {:attr}'); ?>'
        },
        format_msg: {
            alpha_dash: '<?php echo $lang->get('{:attr} must be alpha-numeric, dash, underscore'); ?>'
        },
        msg: {
            loading: '<?php echo $lang->get('Loading'); ?>',
            ajax_err: '<?php echo $lang->get('Server side error'); ?>'
        },
        box: {
            msg: '<?php echo $lang->get('Input error'); ?>'
        }
    };

    var opts_submit_form = {
        modal: {
            btn_text: {
                close: '<?php echo $lang->get('Close'); ?>',
                ok: '<?php echo $lang->get('OK'); ?>'
            }
        },
        msg_text: {
            submitting: '<?php echo $lang->get('Saving'); ?>'
        }
    };

    $(document).ready(function(){
        var obj_validate_form  = $('#admin_form').baigoValidate(opts_validate_form);
        var obj_submit_form     = $('#admin_form').baigoSubmit(opts_submit_form);

        $('#admin_form').submit(function(){
            if (obj_validate_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
        $('#admin_form').baigoCheckall();
    });
    </script>
<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);