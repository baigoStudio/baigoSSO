<?php $cfg = array(
    "sub_title"     => $this->lang["page"]["setupDbConfig"],
    "mod_help"      => "upgrade",
    "act_help"      => "dbconfig",
    "pathInclude"   => BG_PATH_TPLSYS . "install/default/include/",
); ?>
<?php include($cfg["pathInclude"] . "upgrade_head.php"); ?>

    <form name="upgrade_dbconfig" id="upgrade_dbconfig">
        <input type="hidden" name="<?php echo $this->common["tokenRow"]["name_session"]; ?>" value="<?php echo $this->common["tokenRow"]["token"]; ?>">
        <input type="hidden" name="act" value="dbconfig">

        <?php include(BG_PATH_TPLSYS . "console/default/include/dbconfig.php"); ?>

        <hr class="bg-panel-hr">

        <div class="bg-submit-box"></div>

        <div class="form-group clearfix">
            <div class="pull-left">
                <div class="btn-group">
                    <a href="<?php echo BG_URL_INSTALL; ?>index.php?mod=upgrade&act=ext" class="btn btn-default"><?php echo $this->lang["btn"]["stepPrev"]; ?></a>
                    <?php include($cfg["pathInclude"] . "upgrade_drop.php"); ?>
                    <a href="<?php echo BG_URL_INSTALL; ?>index.php?mod=upgrade&act=dbtable" class="btn btn-default"><?php echo $this->lang["btn"]["skip"]; ?></a>
                </div>
            </div>

            <div class="pull-right">
                <button type="button" class="btn btn-primary bg-submit">
                    <?php echo $this->lang["btn"]["save"]; ?>
                </button>
            </div>
        </div>
    </form>

<?php include($cfg["pathInclude"] . "install_foot.php"); ?>

    <script type="text/javascript">
    var opts_submit_form = {
        ajax_url: "<?php echo BG_URL_INSTALL; ?>request.php?mod=upgrade",
        msg_text: {
            submitting: "<?php echo $this->lang["label"]["submitting"]; ?>"
        },
        jump: {
            url: "<?php echo BG_URL_INSTALL; ?>index.php?mod=upgrade&act=dbtable",
            text: "<?php echo $this->lang["href"]["jumping"]; ?>"
        }
    };

    $(document).ready(function(){
        var obj_validator_form    = $("#upgrade_dbconfig").baigoValidator(opts_validator_form);
        var obj_submit_form       = $("#upgrade_dbconfig").baigoSubmit(opts_submit_form);
        $(".bg-submit").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
    });
    </script>

<?php include($cfg["pathInclude"] . "html_foot.php"); ?>
