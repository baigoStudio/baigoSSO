<?php $cfg = array(
    'title'          => $lang->get('Reset password'),
    'baigoValidate' => 'true',
    'baigoSubmit'    => 'true',
    'captchaReload'  => 'true',
    'pathInclude'    => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'personal_head' . GK_EXT_TPL); ?>

    <div class="card">
        <div class="card-body">
            <form name="forgot_form" id="forgot_form" action="<?php echo $route_personal; ?>verify/pass-submit/">
                <input type="hidden" name="verify_id" value="<?php echo $verifyRow['verify_id']; ?>">
                <input type="hidden" name="verify_token" value="<?php echo $verifyRow['verify_token']; ?>">
                <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">

                <div class="form-group">
                    <label><?php echo $lang->get('Username'); ?></label>
                    <input type="text" value="<?php echo $userRow['user_name']; ?>" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label><?php echo $lang->get('New password'); ?> <span class="text-danger">*</span></label>
                    <input type="password" name="user_pass_new" id="user_pass_new" placeholder="<?php echo $lang->get('New password'); ?>" class="form-control">
                    <small class="form-text" id="msg_user_pass_new"></small>
                </div>

                <div class="form-group">
                    <label><?php echo $lang->get('Confirm password'); ?> <span class="text-danger">*</span></label>
                    <input type="password" name="user_pass_confirm" id="user_pass_confirm" placeholder="<?php echo $lang->get('Confirm password'); ?>" class="form-control">
                    <small class="form-text" id="msg_user_pass_confirm"></small>
                </div>

                <div class="form-group">
                    <label><?php echo $lang->get('Captcha'); ?> <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" name="captcha" id="captcha" class="form-control">
                        <div class="input-group-append">
                            <img src="<?php echo $route_misc; ?>captcha/" class="bg-captcha-img" alt="<?php echo $lang->get('Captcha'); ?>">
                        </div>
                    </div>
                    <small class="form-text" id="msg_captcha"></small>
                </div>

                <div class="bg-validate-box"></div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success btn-block">
                        <?php echo $lang->get('Apply'); ?>
                    </button>
                </div>

            </form>
        </div>
    </div>

<?php include($cfg['pathInclude'] . 'personal_foot' . GK_EXT_TPL); ?>

    <script type="text/javascript">
    var opts_validate_form = {
        rules: {
            user_pass_new: {
               require: true
            },
            user_pass_confirm: {
               confirm: 'user_pass_new'
            },
            captcha: {
                length: '4,4',
                format: 'alpha_number',
                ajax: {
                    url: '<?php echo $route_misc; ?>captcha/check/'
                }
            }
        },
        attr_names: {
            user_pass_new: '<?php echo $lang->get('New password'); ?>',
            user_pass_confirm: '<?php echo $lang->get('Confirm password'); ?>',
            captcha: '<?php echo $lang->get('Captcha'); ?>'
        },
        type_msg: {
            require: '<?php echo $lang->get('{:attr} require'); ?>',
            confirm: '<?php echo $lang->get('{:attr} out of accord with {:confirm}'); ?>',
            length: '<?php echo $lang->get('Size of {:attr} must be {:rule}'); ?>'
        },
        format_msg: {
            alpha_number: '<?php echo $lang->get('{:attr} must be alpha-numeric'); ?>'
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
        msg_text: {
            submitting: '<?php echo $lang->get('Logging in'); ?>'
        },
        modal: {
            btn_text: {
                close: '<?php echo $lang->get('Close'); ?>',
                ok: '<?php echo $lang->get('OK'); ?>'
            }
        }
    };

    $(document).ready(function(){
        var obj_validate_form  = $('#forgot_form').baigoValidate(opts_validate_form);
        var obj_submit_form    = $('#forgot_form').baigoSubmit(opts_submit_form);

        $('#forgot_form').submit(function(){
            if (obj_validate_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);