<?php $cfg = array(
    'title'             => $lang->get('Profile', 'console.common') . ' &raquo; ' . $lang->get('Mailbox', 'console.common'),
    'menu_active'       => 'profile',
    'sub_active'        => 'mailbox',
    'baigoValidate'    => 'true',
    'baigoSubmit'       => 'true',
    'pathInclude'       => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'console_head' . GK_EXT_TPL); ?>

    <form name="profile_form" id="profile_form" action="<?php echo $route_console; ?>profile/mailbox-submit/">
        <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">

        <div class="row">
            <div class="col-xl-9">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="form-group">
                            <label><?php echo $lang->get('Username'); ?></label>
                            <input type="text" value="<?php echo $adminLogged['admin_name']; ?>" readonly class="form-control">
                        </div>

                        <div class="form-group">
                            <label><?php echo $lang->get('Password'); ?> <span class="text-danger">*</span></label>
                            <input type="password" name="admin_pass" id="admin_pass" class="form-control">
                            <small class="form-text" id="msg_admin_pass"></small>
                        </div>

                        <div class="form-group">
                            <label><?php echo $lang->get('Old mailbox'); ?></label>
                            <input type="text" value="<?php echo $userRow['user_mail']; ?>" readonly class="form-control">
                        </div>

                        <div class="form-group">
                            <label><?php echo $lang->get('New mailbox'); ?> <span class="text-danger">*</span></label>
                            <input type="text" name="admin_mail_new" id="admin_mail_new" class="form-control">
                            <small class="form-text" id="msg_admin_mail_new"></small>
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

            <?php include($cfg['pathInclude'] . 'profile_side' . GK_EXT_TPL); ?>
        </div>
    </form>

<?php include($cfg['pathInclude'] . 'console_foot' . GK_EXT_TPL); ?>

    <script type="text/javascript">
    var opts_validate_form = {
        rules: {
            admin_pass: {
                require: true
            },
            admin_mail_new: {
                length: '1,300',
                format: 'email'
            }
        },
        attr_names: {
            admin_pass: '<?php echo $lang->get('Password'); ?>',
            admin_mail_new: '<?php echo $lang->get('New mailbox'); ?>'
        },
        type_msg: {
            require: '<?php echo $lang->get('{:attr} require'); ?>',
            length: '<?php echo $lang->get('Size of {:attr} must be {:rule}'); ?>'
        },
        format_msg: {
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
            submitting: '<?php echo $lang->get('Saving'); ?>'
        }
    };

    $(document).ready(function(){
        var obj_validate_form  = $('#profile_form').baigoValidate(opts_validate_form);
        var obj_submit_form     = $('#profile_form').baigoSubmit(opts_submit_form);

        $('#profile_form').submit(function(){
            if (obj_validate_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);