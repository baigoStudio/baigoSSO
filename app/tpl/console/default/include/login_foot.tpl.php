                </div>
            </div>

            <div class="mt-3 text-center">
                <?php if (isset($cfg['active']) && $cfg['active'] == 'login') { ?>
                    <a href="<?php echo $url_personal; ?>forgot/" target="_blank"><?php echo $lang->get('Forgot password'); ?></a>
                <?php } else { ?>
                    <a href="<?php echo $route_console; ?>login/"><?php echo $lang->get('Login now'); ?></a>
                <?php }

                if (isset($cfg['active']) && $cfg['active'] == 'login') { ?>
                    <form name="clear_form" id="clear_form" action="<?php echo $route_console; ?>cookie/clear/" class="mt-3 text-center">
                        <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">
                        <button type="submit" class="btn btn-link">
                            <?php echo $lang->get('Clear cookie'); ?>
                        </button>
                    </form>
                <?php }

                if (isset($config['var_extra']['base']['site_name'])) { ?>
                    <hr>
                    <div>
                        <?php echo $config['var_extra']['base']['site_name']; ?>
                    </div>
                <?php } ?>
            </div>

            <?php if (!isset($ui_ctrl['copyright']) || $ui_ctrl['copyright'] === 'on') { ?>
                <div class="mt-5">
                    <hr>
                    <div class="text-center">
                        <span class="d-none d-lg-inline-block">Powered by</span>
                        <a href="<?php echo PRD_SSO_URL; ?>" target="_blank"><?php echo PRD_SSO_NAME; ?></a>
                        <?php echo PRD_SSO_VER; ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php if (isset($cfg['active']) && $cfg['active'] == 'login') { ?>
        <script type="text/javascript">
        var opts_submit_clear = {
            msg_text: {
                submitting: '<?php echo $lang->get('Clearing'); ?>'
            },
            modal: {
                btn_text: {
                    close: '<?php echo $lang->get('Close'); ?>',
                    ok: '<?php echo $lang->get('OK'); ?>'
                }
            }
        };

        $(document).ready(function(){
            var obj_submit_clear = $('#clear_form').baigoSubmit(opts_submit_clear);

            $('#clear_form').submit(function(){
                obj_submit_clear.formSubmit();
            });
        });
        </script>
    <?php } ?>
