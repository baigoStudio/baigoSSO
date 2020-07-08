<?php $cfg = array(
    'title'         => $lang->get('Installer'),
    'btn'           => $lang->get('Add'),
    'sub_title'     => $lang->get('Add administrator'),
    'active'        => 'admin',
    'pathInclude'   => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'index_head' . GK_EXT_TPL); ?>

    <form name="admin_form" id="admin_form" autocomplete="off" action="<?php echo $route_install; ?>index/admin-submit/">
        <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">
        <div class="alert alert-warning">
            <span class="fas fa-exclamation-triangle"></span>
            <?php echo $lang->get('This step will add a new user as a super administrator with all permissions.'); ?>
        </div>

        <div class="form-group">
            <label><?php echo $lang->get('Username'); ?> <span class="text-danger">*</span></label>
            <input type="text" name="admin_name" id="admin_name" class="form-control">
            <small class="form-text" id="msg_admin_name"></small>
        </div>

        <div class="form-group">
            <label><?php echo $lang->get('Password'); ?> <span class="text-danger">*</span></label>
            <input type="password" name="admin_pass" id="admin_pass" class="form-control">
            <small class="form-text" id="msg_admin_pass"></small>
        </div>

        <div class="form-group">
            <label><?php echo $lang->get('Confirm password'); ?> <span class="text-danger">*</span></label>
            <input type="password" name="admin_pass_confirm" id="admin_pass_confirm" class="form-control">
            <small class="form-text" id="msg_admin_pass_confirm"></small>
        </div>

        <div class="form-group">
            <label><?php echo $lang->get('Email'); ?></label>
            <input type="text" name="admin_mail" id="admin_mail" class="form-control">
            <small class="form-text" id="msg_admin_mail"></small>
        </div>

        <div class="form-group">
            <label><?php echo $lang->get('Nickname'); ?></label>
            <input type="text" name="admin_nick" id="admin_nick" class="form-control">
            <small class="form-text" id="msg_admin_nick"></small>
        </div>

        <?php include($cfg['pathInclude'] . 'install_btn' . GK_EXT_TPL) ?>
    </form>

<?php include($cfg['pathInclude'] . 'install_foot' . GK_EXT_TPL); ?>

    <script type="text/javascript">
    var opts_validate_form = {
        rules: {
            admin_name: {
                length: '1,30',
                format: 'alpha_dash',
                ajax: {
                    url: '<?php echo $route_install; ?>index/admin-check/'
                }
            },
            admin_pass: {
                require: true
            },
            admin_pass_confirm: {
                confirm: true
            },
            admin_mail: {
                max: 30,
                format: 'email'
            },
            admin_nick: {
                max: 30
            }
        },
        attr_names: {
            admin_name: '<?php echo $lang->get('Username'); ?>',
            admin_pass: '<?php echo $lang->get('Password'); ?>',
            admin_pass_confirm: '<?php echo $lang->get('Confirm password'); ?>',
            admin_mail: '<?php echo $lang->get('Email'); ?>',
            admin_nick: '<?php echo $lang->get('Nickname'); ?>'
        },
        type_msg: {
            require: '<?php echo $lang->get('{:attr} require'); ?>',
            length: '<?php echo $lang->get('Size of {:attr} must be {:rule}'); ?>',
            max: '<?php echo $lang->get('Max size of {:attr} must be {:rule}'); ?>',
            confirm: '<?php echo $lang->get('{:attr} out of accord with {:confirm}'); ?>'
        },
        format_msg: {
            alpha_dash: '<?php echo $lang->get('{:attr} must be alpha-numeric, dash, underscore'); ?>',
            email: '<?php echo $lang->get('{:attr} not a valid email address'); ?>'
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
            submitting: '<?php echo $lang->get('Submitting'); ?>'
        },
        jump: {
            url: '<?php echo $route_install; ?>index/over/',
            text: '<?php echo $lang->get('Redirecting'); ?>'
        }
    };

    $(document).ready(function(){
        var obj_validate_form    = $('#admin_form').baigoValidate(opts_validate_form);
        var obj_submit_form       = $('#admin_form').baigoSubmit(opts_submit_form);
        $('#admin_form').submit(function(){
            if (obj_validate_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);