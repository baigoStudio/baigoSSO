<?php $cfg = array(
    'title'          => $lang->get('Register'),
    'active'         => 'reg',
    'baigoValidate'  => 'true',
    'baigoSubmit'    => 'true',
    'captchaReload'  => 'true',
    'pathInclude'    => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'personal_head' . GK_EXT_TPL); ?>

    <div class="card">
        <div class="card-body">
            <form name="reg_form" id="reg_form" action='<?php echo $route_personal; ?>reg/submit/'>
                <input type="hidden" name="__token__" value="<?php echo $token; ?>">

                <div class="form-group">
                    <label><?php echo $lang->get('Username'); ?></label>
                    <input type="text" name="user_name" id="user_name" class="form-control">
                    <small class="form-text" id="msg_user_name"></small>
                </div>

                <div class="form-group">
                    <label><?php echo $lang->get('Password'); ?></label>
                    <input type="password" name="user_pass" id="user_pass" class="form-control">
                    <small class="form-text" id="msg_user_pass"></small>
                </div>

                <div class="form-group">
                    <label><?php echo $lang->get('Confirm password'); ?></label>
                    <input type="password" name="user_pass_confirm" id="user_pass_confirm" class="form-control">
                    <small class="form-text" id="msg_user_pass_confirm"></small>
                </div>

                <div class="form-group">
                    <label><?php echo $lang->get('Mailbox'); ?></label>
                    <input type="text" name="user_mail" id="user_mail" class="form-control">
                    <small class="form-text" id="msg_user_mail"></small>
                </div>

                <div class="form-group">
                    <label><?php echo $lang->get('Captcha'); ?></label>
                    <div class="input-group">
                        <input type="text" name="captcha" id="captcha" class="form-control">
                        <div class="input-group-append">
                            <img src="<?php echo $route_misc; ?>captcha/" class="bg-captcha-img" alt="<?php echo $lang->get('Captcha'); ?>">
                        </div>
                    </div>
                    <small class="form-text" id="msg_captcha"></small>
                </div>

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
            user_name: {
                length: '1,30',
                format: 'alpha_dash',
                ajax: {
                    key: 'user_name',
                    url: '<?php echo $route_personal; ?>reg/chkname/'
                }
            },
            user_pass: {
                require: true
            },
            user_pass_confirm: {
               confirm: 'user_pass'
            },
            user_mail: {
                length: '0,300',
                format: 'email',
                ajax: {
                    key: 'user_mail',
                    url: '<?php echo $route_personal; ?>reg/chkmail/'
                }
            },
            captcha: {
                length: '4,4',
                format: 'alpha_number',
                ajax: {
                    key: 'captcha',
                    url: '<?php echo $route_misc; ?>captcha/check/'
                }
            }
        },
        attr_names: {
            user_name: '<?php echo $lang->get('Username'); ?>',
            user_pass: '<?php echo $lang->get('Password'); ?>',
            user_pass_confirm: '<?php echo $lang->get('Confirm password'); ?>',
            user_mail: '<?php echo $lang->get('Mailbox'); ?>',
            captcha: '<?php echo $lang->get('Captcha'); ?>'
        },
        type_msg: {
            require: '<?php echo $lang->get('{:attr} require'); ?>',
            confirm: '<?php echo $lang->get('{:attr} out of accord with {:confirm}'); ?>',
            length: '<?php echo $lang->get('Size of {:attr} must be {:rule}'); ?>'
        },
        format_msg: {
            alpha_dash: '<?php echo $lang->get('{:attr} must be alpha-numeric, dash, underscore'); ?>',
            alpha_number: '<?php echo $lang->get('{:attr} must be alpha-numeric'); ?>',
            email: '<?php echo $lang->get('{:attr} not a valid email address'); ?>'
        },
        msg: {
            loading: '<?php echo $lang->get('Loading'); ?>',
            ajax_err: '<?php echo $lang->get('Server side error'); ?>'
        },
        box: {
            msg: '<?php echo $lang->get('Input error'); ?>'
        }
    };

    var opts_submit = {
        msg_text: {
            submitting: '<?php echo $lang->get('Submitting'); ?>'
        },
        modal: {
            btn_text: {
                close: '<?php echo $lang->get('Close'); ?>',
                ok: '<?php echo $lang->get('OK'); ?>'
            }
        }
    };


    $(document).ready(function(){
        var obj_validate_form  = $('#reg_form').baigoValidate(opts_validate_form);
        var obj_submit_form    = $('#reg_form').baigoSubmit(opts_submit);

        $('#reg_form').submit(function(){
            if (obj_validate_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);