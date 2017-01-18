<?php
if (fn_isEmpty($GLOBALS["act"])) {
    $GLOBALS["act"] = "base";
}

$cfg = array(
    "title"          => $this->lang["page"]["opt"] . " &raquo; " . $this->opt[$GLOBALS["act"]]["title"],
    "menu_active"    => "opt",
    "sub_active"     => $GLOBALS["act"],
    "baigoValidator" => "true",
    "baigoSubmit"    => "true",
    "pathInclude"    => BG_PATH_TPLSYS . "console/default/include/",
    "str_url"        => BG_URL_CONSOLE . "index.php?mod=opt&act=" . $GLOBALS["act"],
); ?>

<?php include($cfg["pathInclude"] . "function.php"); ?>
<?php include($cfg["pathInclude"] . "console_head.php"); ?>

    <div class="form-group">
        <ul class="nav nav-pills bg-nav-pills">
            <li>
                <a href="<?php echo BG_URL_HELP; ?>index.php?mod=console&act=opt#<?php echo $GLOBALS["act"]; ?>" target="_blank">
                    <span class="glyphicon glyphicon-question-sign"></span>
                    <?php echo $this->lang["href"]["help"]; ?>
                </a>
            </li>
        </ul>
    </div>

    <form name="opt_form" id="opt_form">

        <input type="hidden" name="<?php echo $this->common["tokenRow"]["name_session"]; ?>" value="<?php echo $this->common["tokenRow"]["token"]; ?>">
        <input type="hidden" name="act" value="<?php echo $GLOBALS["act"]; ?>">

        <div class="panel panel-default">
            <div class="panel-body">
                <?php
                $_tplRows       = array();
                $_timezoneRows  = array();
                $_timezoneType  = "";
                $_timezoneJson  = "";

                if ($GLOBALS["act"] == "base") {
                    $_tplRows       = $this->tplData["tplRows"];
                    $_timezoneRows  = $this->tplData["timezoneRows"];
                    $_timezoneType  = $this->tplData["timezoneType"];
                    $_timezoneJson  = $this->tplData["timezoneJson"];
                }

                $arr_form = opt_form_process($this->opt[$GLOBALS["act"]]["list"], $_tplRows, $_timezoneRows, $_timezoneType, $_timezoneJson, $this->lang, $this->rcode);

                echo $arr_form["form"];
                ?>

                <div class="bg-submit-box"></div>
            </div>
            <div class="panel-footer">
                <button type="button" class="btn btn-primary bg-submit">
                    <?php echo $this->lang["btn"]["save"]; ?>
                </button>
            </div>
        </div>
    </form>

<?php include($cfg["pathInclude"] . "console_foot.php"); ?>

    <script type="text/javascript">
    <?php echo $arr_form["json"]; ?>

    var opts_submit_form = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>request.php?mod=opt",
        msg_text: {
            submitting: "<?php echo $this->lang["label"]["submitting"]; ?>"
        }
    };

    $(document).ready(function(){
        var obj_validator_form    = $("#opt_form").baigoValidator(opts_validator_form);
        var obj_submit_form       = $("#opt_form").baigoSubmit(opts_submit_form);
        $(".bg-submit").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
        <?php if ($GLOBALS["act"] == "base") { ?>
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
