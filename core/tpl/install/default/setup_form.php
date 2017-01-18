<?php
if (fn_isEmpty($this->tplData["act"])) {
    $this->tplData["act"] = "base";
}

$cfg = array(
    "sub_title"     => $this->opt[$this->tplData["act"]]["title"],
    "mod_help"      => "setup",
    "act_help"      => $this->tplData["act"],
    "pathInclude"   => BG_PATH_TPLSYS . "install/default/include/",
); ?>

<?php include(BG_PATH_TPLSYS . "console/default/include/function.php"); ?>
<?php include($cfg["pathInclude"] . "setup_head.php"); ?>

    <form name="setup_form" id="setup_form">
        <input type="hidden" name="<?php echo $this->common["tokenRow"]["name_session"]; ?>" value="<?php echo $this->common["tokenRow"]["token"]; ?>">
        <input type="hidden" name="act" value="<?php echo $this->tplData["act"]; ?>">

        <?php
        $_tplRows       = array();
        $_timezoneRows  = array();
        $_timezoneType  = "";
        $_timezoneJson  = "";

        if ($this->tplData["act"] == "base") {
            $_tplRows       = $this->tplData["tplRows"];
            $_timezoneRows  = $this->tplData["timezoneRows"];
            $_timezoneType  = $this->tplData["timezoneType"];
            $_timezoneJson  = $this->tplData["timezoneJson"];
        }

        $arr_form = opt_form_process($this->opt[$this->tplData["act"]]["list"], $_tplRows, $_timezoneRows, $_timezoneType, $_timezoneJson, $this->lang, $this->rcode);

        echo $arr_form["form"];
        ?>

        <hr class="bg-panel-hr">

        <div class="bg-submit-box"></div>

        <div class="form-group clearfix">
            <div class="pull-left">
                <div class="btn-group">
                    <a href="<?php echo BG_URL_INSTALL; ?>index.php?mod=setup&act=<?php echo $this->tplData["setup_step"]["prev"]; ?>" class="btn btn-default"><?php echo $this->lang["btn"]["stepPrev"]; ?></a>
                    <?php include($cfg["pathInclude"] . "setup_drop.php"); ?>
                    <a href="<?php echo BG_URL_INSTALL; ?>index.php?mod=setup&act=<?php echo $this->tplData["setup_step"]["next"]; ?>" class="btn btn-default"><?php echo $this->lang["btn"]["skip"]; ?></a>
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
    <?php echo $arr_form["json"]; ?>

    var opts_submit_form = {
        ajax_url: "<?php echo BG_URL_INSTALL; ?>request.php?mod=setup",
        msg_text: {
            submitting: "<?php echo $this->lang["label"]["submitting"]; ?>"
        },
        jump: {
            url: "<?php echo BG_URL_INSTALL; ?>index.php?mod=setup&act=<?php echo $this->tplData["setup_step"]["next"]; ?>",
            text: "<?php echo $this->lang["href"]["jumping"]; ?>"
        }
    };

    $(document).ready(function(){
        var obj_validator_form    = $("#setup_form").baigoValidator(opts_validator_form);
        var obj_submit_form       = $("#setup_form").baigoSubmit(opts_submit_form);
        $(".bg-submit").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
        <?php if ($this->tplData["act"] == "base") { ?>
            $("#timezone_type").change(function(){
                var _type = $(this).val();
                var _str_appent;
                $.each(_timezoneJson[_type].sub, function(_key, _value){
                    _str_appent += "<option";
                    if (_key == "<?php echo BG_SITE_TIMEZONE; ?>") {
                        _str_appent += " selected";
                    }
                    _str_appent += " value='" + _key + "'>" + _value + "</option>";
                });
                $("#opt_base_BG_SITE_TIMEZONE").empty();
                $("#opt_base_BG_SITE_TIMEZONE").append(_str_appent);
            });
        <?php } ?>
    });
    </script>

<?php include($cfg["pathInclude"] . "html_foot.php"); ?>
