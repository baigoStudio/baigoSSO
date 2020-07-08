<?php $cfg = array(
    'title'          => $lang->get('Forgot password'),
    'active'         => 'forgot',
    'baigoValidate'  => 'true',
    'baigoSubmit'    => 'true',
    'captchaReload'  => 'true',
    'pathInclude'    => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'personal_head' . GK_EXT_TPL); ?>

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a href="#mail" data-toggle="tab" class="nav-link active">
                        <?php echo $lang->get('By email'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#secqa" data-toggle="tab" class="nav-link">
                        <?php echo $lang->get('By security question'); ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active" id="mail">
                    <form name="forgot_mail" id="forgot_mail" action='<?php echo $route_personal; ?>forgot/bymail/'>
                        <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">

                        <div class="form-group">
                            <label><?php echo $lang->get('Username'); ?></label>
                            <input type="text" name="user_name" id="user_name" value="<?php echo $userRow['user_name']; ?>" class="form-control" readonly>
                        </div>

                        <?php if (empty($userRow['user_mail'])) { ?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <span class="fas fa-times-circle"></span>
                                <?php echo $lang->get('You have not reserve mailbox!'); ?>
                            </div>
                        <?php } else { ?>
                            <div class="alert alert-warning">
                                <span class="fas fa-exclamation-triangle"></span>
                                <?php echo $lang->get('System will send a confirmation email to the mailbox you reserved.'); ?>
                            </div>

                            <div class="form-group">
                                <label><?php echo $lang->get('Captcha'); ?> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="captcha_mail" id="captcha_mail" class="form-control">
                                    <div class="input-group-append">
                                        <img src="<?php echo $route_misc; ?>captcha/index/id/captcha_mail/" id="captcha_mail_img" class="bg-captcha-img" data-id="captcha_mail" alt="<?php echo $lang->get('Captcha'); ?>">
                                    </div>
                                </div>
                                <small class="form-text" id="msg_captcha_mail"></small>
                            </div>

                            <div class="bg-validate-box bg-validate-box-mail mt-3"></div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success">
                                    <?php echo $lang->get('Apply'); ?>
                                </button>
                            </div>
                        <?php } ?>
                    </form>
                </div>
                <div class="tab-pane" id="secqa">
                    <form name="forgot_secqa" id="forgot_secqa" action='<?php echo $route_personal; ?>forgot/bysecqa/'>
                        <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">

                        <div class="form-group">
                            <label><?php echo $lang->get('Username'); ?></label>
                            <input type="text" name="user_name" id="user_name" value="<?php echo $userRow['user_name']; ?>" class="form-control" readonly>
                        </div>

                        <?php if (empty($userRow['user_sec_ques'])) { ?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <span class="fas fa-times-circle"></span>
                                <?php echo $lang->get('You have not set a secret question!'); ?>
                            </div>
                        <?php } else {
                            for ($_iii = 1; $_iii <= $config['var_default']['count_secqa']; $_iii++) { ?>
                                <div class="form-group">
                                    <label><?php echo $userRow['user_sec_ques'][$_iii]; ?> <span class="text-danger">*</span></label>
                                    <input type="text" name="user_sec_answ[<?php echo $_iii; ?>]" id="user_sec_answ_<?php echo $_iii; ?>" placeholder="<?php echo $lang->get('Answer'); ?>" class="form-control">
                                    <small class="form-text" id="msg_user_sec_answ_<?php echo $_iii; ?>"></small>
                                </div>
                            <?php } ?>

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
                                    <input type="text" name="captcha_secqa" id="captcha_secqa" class="form-control">
                                    <div class="input-group-append">
                                        <img src="<?php echo $route_misc; ?>captcha/index/id/captcha_secqa/" id="captcha_secqa_img" class="bg-captcha-img" data-id="captcha_secqa" alt="<?php echo $lang->get('Captcha'); ?>">
                                    </div>
                                </div>
                                <small class="form-text" id="msg_captcha_secqa"></small>
                            </div>

                            <div class="bg-validate-box bg-validate-box-secqa mt-3"></div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success"><?php echo $lang->get('Apply'); ?></button>
                            </div>
                        <?php } ?>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php include($cfg['pathInclude'] . 'personal_foot' . GK_EXT_TPL); ?>
    <script type="text/javascript">
    var opts_validate_mail = {
        rules: {
            captcha_mail: {
                length: '4,4',
                format: 'alpha_number',
                ajax: {
                    key: 'captcha',
                    url: '<?php echo $route_misc; ?>captcha/check/id/captcha_mail/'
                }
            }
        },
        attr_names: {
            captcha_mail: '<?php echo $lang->get('Captcha'); ?>'
        },
        type_msg: {
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


    var opts_validate_secqa = {
        rules: {
            <?php for ($_iii = 1; $_iii <= $config['var_default']['count_secqa']; $_iii++) { ?>
                user_sec_answ_<?php echo $_iii; ?>: {
                    require: true
                },
            <?php } ?>
            user_pass_new: {
                require: true
            },
            user_pass_confirm: {
                confirm: 'user_pass_new'
            },
            captcha_secqa: {
                length: '4,4',
                format: 'alpha_number',
                ajax: {
                    key: 'captcha',
                    url: '<?php echo $route_misc; ?>captcha/check/id/captcha_secqa/'
                }
            }
        },
        attr_names: {
            <?php for ($_iii = 1; $_iii <= $config['var_default']['count_secqa']; $_iii++) { ?>
                user_sec_answ_<?php echo $_iii; ?>: '<?php echo $lang->get('Answer'); ?>',
            <?php } ?>
            user_pass_new: '<?php echo $lang->get('New password'); ?>',
            user_pass_confirm: '<?php echo $lang->get('Confirm password'); ?>',
            captcha_secqa: '<?php echo $lang->get('Captcha'); ?>'
        },
        type_msg: {
            require: '<?php echo $lang->get('{:attr} require'); ?>',
            length: '<?php echo $lang->get('Size of {:attr} must be {:rule}'); ?>',
            confirm: '<?php echo $lang->get('{:attr} out of accord with {:confirm}'); ?>'
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
        var obj_validate_mail  = $('#forgot_mail').baigoValidate(opts_validate_mail);
        var obj_submit_mail    = $('#forgot_mail').baigoSubmit(opts_submit);

        $('#forgot_mail').submit(function(){
            if (obj_validate_mail.verify()) {
                obj_submit_mail.formSubmit();
            }
        });

        var obj_validate_secqa  = $('#forgot_secqa').baigoValidate(opts_validate_secqa);
        var obj_submit_secqa    = $('#forgot_secqa').baigoSubmit(opts_submit);

        $('#forgot_secqa').submit(function(){
            if (obj_validate_secqa.verify()) {
                obj_submit_secqa.formSubmit();
            }
        });
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);