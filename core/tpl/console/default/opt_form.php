<?php $cfg = array(
    'title'          => $this->lang['common']['page']['opt'] . ' &raquo; ' . $this->lang['opt'][$this->tplData['act']]['title'],
    'menu_active'    => "opt",
    'sub_active'     => $this->tplData['act'],
    'baigoValidator' => 'true',
    'baigoSubmit'    => 'true',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
    'str_url'        => BG_URL_CONSOLE . 'index.php?m=opt&a=' . $this->tplData['act'],
);

include($cfg['pathInclude'] . 'function.php');
include($cfg['pathInclude'] . 'console_head.php'); ?>

    <ul class="nav nav-pills mb-3">
        <li class="nav-item">
            <a href="<?php echo BG_URL_HELP; ?>index.php?m=console&a=opt#<?php echo $this->tplData['act']; ?>" target="_blank" class="nav-link">
                <span class="badge badge-pill badge-primary">
                    <span class="oi oi-question-mark"></span>
                </span>
                <?php echo $this->lang['mod']['href']['help']; ?>
            </a>
        </li>
    </ul>

    <form name="opt_form" id="opt_form">

        <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">
        <input type="hidden" name="a" value="<?php echo $this->tplData['act']; ?>">

        <div class="card">
            <div class="card-body">
                <?php
                $_tplRows       = array();
                $_timezoneLang  = array();
                $_timezoneRows  = array();
                $_timezoneType  = '';

                if ($this->tplData['act'] == 'base') {
                    $_tplRows       = $this->tplData['tplRows'];
                    $_timezoneLang  = $this->lang['timezone'];
                    $_timezoneRows  = $this->tplData['timezoneRows'];
                    $_timezoneType  = $this->tplData['timezoneType'];
                }

                $arr_form = opt_form_process($this->opt[$this->tplData['act']]['list'], $this->lang['opt'][$this->tplData['act']]['list'], $_tplRows, $_timezoneRows, $_timezoneLang, $_timezoneType, $this->lang['mod']['label'], $this->lang['rcode']); ?>

                <div class="bg-submit-box"></div>
                <div class="bg-validator-box"></div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-primary bg-submit">
                    <?php echo $this->lang['mod']['btn']['save']; ?>
                </button>
            </div>
        </div>
    </form>

<?php include($cfg['pathInclude'] . 'console_foot.php'); ?>

    <script type="text/javascript">
    <?php echo $arr_form['json']; ?>

    var opts_submit_form = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>index.php?m=opt&c=request",
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        }
    };

    var options_validator_form = {
        msg_global:{
            msg: "<?php echo $this->lang['common']['label']['errInput']; ?>"
        }
    };

    $(document).ready(function(){
        var obj_validator_form    = $("#opt_form").baigoValidator(opts_validator_form, options_validator_form);
        var obj_submit_form       = $("#opt_form").baigoSubmit(opts_submit_form);
        $(".bg-submit").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
        <?php if ($this->tplData['act'] == 'base') { ?>
            var _timezoneRowsJson = <?php echo $this->tplData['timezoneRowsJson']; ?>;
            var _timezoneLangJson = <?php echo $this->lang['timezoneJson']; ?>;

            $("#timezone_type").change(function(){
                var _type = $(this).val();
                var _str_appent;
                $.each(_timezoneRowsJson[_type].sub, function(_key, _value){
                    _str_appent += "<option";
                    if (_key == "<?php echo BG_SITE_TIMEZONE; ?>") {
                        _str_appent += " selected";
                    }
                    _str_appent += " value='" + _key + "'>";
                    if (typeof _timezoneLangJson[_type].sub[_key] != "undefined") {
                        _str_appent += _timezoneLangJson[_type].sub[_key];
                    } else {
                        _str_appent += _value;
                    }
                    _str_appent += "</option>";
                });
                $("#opt_base_BG_SITE_TIMEZONE").html(_str_appent);
            });
        <?php } ?>
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot.php');
