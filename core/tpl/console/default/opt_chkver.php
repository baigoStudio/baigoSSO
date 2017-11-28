<?php $cfg = array(
    'title'          => $this->lang['common']['page']['opt'] . ' &raquo; ' . $this->lang['common']['page']['chkver'],
    'menu_active'    => "opt",
    'sub_active'     => "chkver",
    'baigoValidator' => 'true',
    'baigoSubmit'    => 'true',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
    'str_url'        => BG_URL_CONSOLE . 'index.php?mod=opt&act=chkver',
);

include($cfg['pathInclude'] . 'console_head.php'); ?>

    <div class="form-group">
        <ul class="nav nav-pills bg-nav-pills">
            <li>
                <a href="<?php echo BG_URL_HELP; ?>index.php?mod=console&act=opt#chkver" target="_blank">
                    <span class="glyphicon glyphicon-question-sign"></span>
                    <?php echo $this->lang['mod']['href']['help']; ?>
                </a>
            </li>
        </ul>
    </div>

    <form name="opt_chkver" id="opt_chkver">
        <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">
        <input type="hidden" name="act" value="chkver">
        <div class="bg-submit-box"></div>

        <div class="form-group">
            <button type="button" class="btn btn-info bg-submit">
                <span class="glyphicon glyphicon-repeat"></span>
                <?php echo $this->lang['mod']['btn']['chkver']; ?>
            </button>
        </div>
    </form>

    <?php if (isset($this->tplData['latest_ver']['prd_pub']) && $this->tplData['installed_pub'] < $this->tplData['latest_ver']['prd_pub']) { ?>
        <div class="alert alert-warning">
            <span class="glyphicon glyphicon-warning-sign"></span>
            <?php echo $this->lang['mod']['text']['haveNewVer']; ?>
        </div>
    <?php } else { ?>
        <div class="alert alert-success">
            <span class="glyphicon glyphicon-heart"></span>
            <?php echo $this->lang['mod']['text']['isNewVer']; ?>
        </div>
    <?php } ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo $this->lang['mod']['label']['installVer']; ?>
        </div>
        <table class="table">
            <tbody>
                <tr>
                    <td class="nowrap bg-td-lg"><?php echo $this->lang['mod']['label']['installVer']; ?></td>
                    <td><?php echo BG_INSTALL_VER; ?></td>
                </tr>
                <tr>
                    <td class="nowrap bg-td-lg"><?php echo $this->lang['mod']['label']['pubTime']; ?></td>
                    <td><?php echo date(BG_SITE_DATE, $this->tplData['installed_pub']); ?></td>
                </tr>
                <tr>
                    <td class="nowrap bg-td-lg"><?php echo $this->lang['mod']['label']['installTime']; ?></td>
                    <td><?php echo date(BG_SITE_DATE . ' ' . BG_SITE_TIMESHORT, BG_INSTALL_TIME); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <?php if (isset($this->tplData['latest_ver']['prd_pub']) && $this->tplData['installed_pub'] < $this->tplData['latest_ver']['prd_pub']) { ?>
        <div class="panel panel-warning">
            <div class="panel-heading">
                <?php echo $this->lang['mod']['label']['latestVer']; ?>
            </div>
            <table class="table">
                <tbody>
                    <tr>
                        <td class="nowrap bg-td-lg"><?php echo $this->lang['mod']['label']['latestVer']; ?></td>
                        <td><?php echo $this->tplData['latest_ver']['prd_ver']; ?></td>
                    </tr>
                    <tr>
                        <td class="nowrap bg-td-lg"><?php echo $this->lang['mod']['label']['pubTime']; ?></td>
                        <td><?php echo date(BG_SITE_DATE, $this->tplData['latest_ver']['prd_pub']); ?></td>
                    </tr>
                    <tr>
                        <td class="nowrap bg-td-lg"><?php echo $this->lang['mod']['label']['announcement']; ?></td>
                        <td><a href="<?php echo $this->tplData['latest_ver']['prd_announcement']; ?>" target="_blank"><?php echo $this->tplData['latest_ver']['prd_announcement']; ?></a></td>
                    </tr>
                    <tr>
                        <td class="nowrap bg-td-lg"><?php echo $this->lang['mod']['label']['downloadUrl']; ?></td>
                        <td><a href="<?php echo $this->tplData['latest_ver']['prd_download']; ?>" target="_blank"><?php echo $this->tplData['latest_ver']['prd_download']; ?></a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php }

include($cfg['pathInclude'] . 'console_foot.php'); ?>

    <script type="text/javascript">
    var opts_submit_form = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>request.php?mod=opt",
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        }
    };

    $(document).ready(function(){
        var obj_submit_form       = $("#opt_chkver").baigoSubmit(opts_submit_form);
        $(".bg-submit").click(function(){
            obj_submit_form.formSubmit();
        });
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot.php'); ?>
