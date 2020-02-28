<?php $cfg = array(
    'title'          => $lang->get('Login'),
    'active'         => 'login',
    'baigoValidate' => 'true',
    'baigoSubmit'    => 'true',
    'captchaReload'  => 'true',
    'tooltip'        => 'true',
    'pathInclude'    => $path_tpl . 'include' . DS,
);

//print_r($path_tpl);

include($cfg['pathInclude'] . 'login_head' . GK_EXT_TPL); ?>

    <form name="login_form" id="login_form" action="<?php echo $route_console; ?>login/submit/">
        <input type="hidden" name="__token__" value="<?php echo $token; ?>">

        <div class="form-group">
            <label><?php echo $lang->get('Username'); ?></label>
            <input type="text" name="admin_name" id="admin_name" class="form-control">
            <small class="form-text" id="msg_admin_name"></small>
        </div>

        <div class="form-group">
            <label><?php echo $lang->get('Password'); ?></label>
            <input type="password" name="admin_pass" id="admin_pass" class="form-control">
            <small class="form-text" id="msg_admin_pass"></small>
        </div>

        <div class="form-group">
            <label><?php echo $lang->get('Captcha'); ?></label>
            <div class="input-group">
                <input type="text" name="captcha" id="captcha" class="form-control">
                <div class="input-group-append">
                    <img src="<?php echo $route_misc; ?>captcha/" class="bg-captcha-img" alt="<?php echo $lang->get('Refresh'); ?>">
                </div>
            </div>

            <small class="form-text" id="msg_captcha"></small>
        </div>

        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" name="admin_remember" id="admin_remember" value="remember" class="custom-control-input">
                <label for="admin_remember" class="custom-control-label" data-toggle="tooltip" data-placement="right" title="<?php echo $lang->get('Do not enable on public computers'); ?>">
                    <?php echo $lang->get('Remember me'); ?>
                </label>
            </div>
        </div>

        <div class="bg-validate-box"></div>

        <button type="submit" class="btn btn-success btn-block">
            <?php echo $lang->get('Login'); ?>
        </button>

    </form>

<?php include($cfg['pathInclude'] . 'login_foot' . GK_EXT_TPL); ?>

    <script type="text/javascript">
    var opts_validate_form = {
        rules: {
            admin_name: {
                require: true,
                format: 'alpha_dash'
            },
            admin_pass: {
                require: true
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
            admin_name: '<?php echo $lang->get('Username'); ?>',
            admin_pass: '<?php echo $lang->get('Password'); ?>',
            captcha: '<?php echo $lang->get('Captcha'); ?>'
        },
        type_msg: {
            require: '<?php echo $lang->get('{:attr} require'); ?>',
            length: '<?php echo $lang->get('Size of {:attr} must be {:rule}'); ?>'
        },
        format_msg: {
            alpha_number: '<?php echo $lang->get('{:attr} must be alpha-numeric'); ?>',
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
        msg_text: {
            submitting: '<?php echo $lang->get('Logging in'); ?>'
        },
        modal: {
            btn_text: {
                close: '<?php echo $lang->get('Close'); ?>',
                ok: '<?php echo $lang->get('OK'); ?>'
            }
        },
        jump: {
            url: '<?php echo $forward; ?>',
            text: '<?php echo $lang->get('Redirecting'); ?>'
        }
    };

    $(document).ready(function(){
        var obj_validate_form  = $('#login_form').baigoValidate(opts_validate_form);
        var obj_submit_form    = $('#login_form').baigoSubmit(opts_submit_form);

        $('#login_form').submit(function(){
            if (obj_validate_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);