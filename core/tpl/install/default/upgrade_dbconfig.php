<?php $cfg = array(
    'title'         => $this->lang['mod']['page']['upgrade'] . ' &raquo; ' . $this->lang['common']['page']['dbconfig'],
    "sub_title"     => $this->lang['common']['page']['dbconfig'],
    "mod_help"      => 'upgrade',
    "act_help"      => 'dbconfig',
    "pathInclude"   => BG_PATH_TPLSYS . 'install' . DS . 'default' . DS . 'include' . DS,
);

include($cfg['pathInclude'] . 'upgrade_head.php'); ?>

    <form name="upgrade_dbconfig" id="upgrade_dbconfig">
        <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">
        <input type="hidden" name="a" value="dbconfig">

        <div class="card-body">
            <?php include(BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS . 'dbconfig.php'); ?>

            <div class="bg-submit-box"></div>
            <div class="bg-validator-box mt-3"></div>
        </div>

        <div class="card-footer">
            <div class="btn-toolbar justify-content-between">
                <div class="btn-group">
                    <a href="<?php echo BG_URL_INSTALL; ?>index.php?m=upgrade&a=ext" class="btn btn-outline-secondary"><?php echo $this->lang['mod']['btn']['prev']; ?></a>
                    <?php include($cfg['pathInclude'] . 'upgrade_drop.php'); ?>
                    <a href="<?php echo BG_URL_INSTALL; ?>index.php?m=upgrade&a=dbtable" class="btn btn-secondary"><?php echo $this->lang['mod']['btn']['skip']; ?></a>
                </div>

                <div class="btn-group">
                    <button type="button" class="btn btn-primary bg-submit">
                        <?php echo $this->lang['mod']['btn']['save']; ?>
                    </button>
                </div>
            </div>
        </div>
    </form>

<?php include($cfg['pathInclude'] . 'install_foot.php'); ?>

    <script type="text/javascript">
    var opts_submit_form = {
        ajax_url: "<?php echo BG_URL_INSTALL; ?>index.php?m=upgrade&c=request",
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        },
        jump: {
            url: "<?php echo BG_URL_INSTALL; ?>index.php?m=upgrade&a=dbtable",
            text: "<?php echo $this->lang['mod']['href']['jumping']; ?>"
        }
    };

    var options_validator_form = {
        msg_global:{
            msg: "<?php echo $this->lang['common']['label']['errInput']; ?>"
        }
    };

    $(document).ready(function(){
        var obj_validator_form    = $("#upgrade_dbconfig").baigoValidator(opts_validator_form, options_validator_form);
        var obj_submit_form       = $("#upgrade_dbconfig").baigoSubmit(opts_submit_form);
        $(".bg-submit").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot.php');
