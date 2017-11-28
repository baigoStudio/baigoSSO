<?php $cfg = array(
    'title'          => $this->lang['common']['page']['opt'] . ' &raquo; ' . $this->lang['opt'][$this->tplData['act']]['title'],
    'menu_active'    => "opt",
    'sub_active'     => $this->tplData['act'],
    'baigoValidator' => 'true',
    'baigoSubmit'    => 'true',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
    'str_url'        => BG_URL_CONSOLE . 'index.php?mod=opt&act=' . $this->tplData['act'],
);

include($cfg['pathInclude'] . 'function.php');
include($cfg['pathInclude'] . 'console_head.php'); ?>

    <div class="form-group">
        <ul class="nav nav-pills bg-nav-pills">
            <li>
                <a href="<?php echo BG_URL_HELP; ?>index.php?mod=console&act=opt#<?php echo $this->tplData['act']; ?>" target="_blank">
                    <span class="glyphicon glyphicon-question-sign"></span>
                    <?php echo $this->lang['mod']['href']['help']; ?>
                </a>
            </li>
        </ul>
    </div>

    <form name="opt_form" id="opt_form">

        <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">
        <input type="hidden" name="act" value="<?php echo $this->tplData['act']; ?>">

        <div class="panel panel-default">
            <div class="panel-body">
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
            </div>
            <div class="panel-footer">
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
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>request.php?mod=opt",
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
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

<?php include($cfg['pathInclude'] . 'html_foot.php'); ?>
