<?php $cfg = array(
    'title'          => $this->lang['consoleMod']['user']['main']['title'],
    'menu_active'    => 'user',
    'sub_active'     => 'list',
    'baigoCheckall'  => 'true',
    'baigoValidator' => 'true',
    'baigoSubmit'    => 'true',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
    'str_url'        => BG_URL_CONSOLE . 'index.php?m=user&a=list&' . $this->tplData['query']
);

include($cfg['pathInclude'] . 'function.php');
include($cfg['pathInclude'] . 'console_head.php'); ?>

    <div class="mb-3 clearfix">
        <div class="float-left">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=user&a=form" class="nav-link">
                        <span class="oi oi-plus"></span>
                        <?php echo $this->lang['mod']['href']['add']; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=user&a=import" class="nav-link">
                        <span class="oi oi-cloud-upload"></span>
                        <?php echo $this->lang['mod']['href']['import']; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo BG_URL_HELP; ?>index.php?m=console&a=user" target="_blank" class="nav-link">
                        <span class="badge badge-pill badge-primary">
                            <span class="oi oi-question-mark"></span>
                        </span>
                        <?php echo $this->lang['mod']['href']['help']; ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="float-right">
            <form name="user_search" id="user_search" action="<?php echo BG_URL_CONSOLE; ?>index.php" method="get">
                <input type="hidden" name="m" value="user">
                <input type="hidden" name="a" value="list">
                <div class="input-group">
                    <select name="status" class="custom-select">
                        <option value=""><?php echo $this->lang['mod']['option']['allStatus']; ?></option>
                        <?php foreach ($this->tplData['status'] as $key=>$value) { ?>
                            <option <?php if ($this->tplData['search']['status'] == $value) { ?>selected<?php } ?> value="<?php echo $value; ?>">
                                <?php if (isset($this->lang['mod']['status'][$value])) {
                                    echo $this->lang['mod']['status'][$value];
                                } else {
                                    echo $value;
                                } ?>
                            </option>
                        <?php } ?>
                    </select>
                    <input type="text" name="key" value="<?php echo $this->tplData['search']['key']; ?>" placeholder="<?php echo $this->lang['mod']['label']['key']; ?>" class="form-control">
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-secondary">
                            <span class="oi oi-magnifying-glass"></span>
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>

    <form name="user_list" id="user_list">
        <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">

        <div class="table-responsive">
            <table class="table table-striped table-hover border">
                <thead>
                    <tr>
                        <th class="text-nowrap bg-td-xs">
                            <div class="form-check">
                                <label for="chk_all" class="form-check-label">
                                    <input type="checkbox" name="chk_all" id="chk_all" data-parent="first" class="form-check-input">
                                    <?php echo $this->lang['mod']['label']['all']; ?>
                                </label>
                            </div>
                        </th>
                        <th class="text-nowrap bg-td-xs"><?php echo $this->lang['mod']['label']['id']; ?></th>
                        <th><?php echo $this->lang['mod']['label']['user']; ?></th>
                        <th class="text-nowrap bg-td-md"><?php echo $this->lang['mod']['label']['status']; ?> / <?php echo $this->lang['mod']['label']['note']; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->tplData['userRows'] as $key=>$value) { ?>
                        <tr>
                            <td class="text-nowrap bg-td-xs"><input type="checkbox" name="user_ids[]" value="<?php echo $value['user_id']; ?>" id="user_id_<?php echo $value['user_id']; ?>" data-validate="user_id" data-parent="chk_all"></td>
                            <td class="text-nowrap bg-td-xs"><?php echo $value['user_id']; ?></td>
                            <td>
                                <ul class="list-unstyled">
                                    <li>
                                        <?php echo $value['user_name'];
                                        if (!fn_isEmpty($value['user_nick'])) {
                                            echo ' [ ', $value['user_nick'], ' ]';
                                        } ?>
                                    </li>
                                    <li>
                                        <ul class="bg-nav-line">
                                            <li>
                                                <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=user&a=show&user_id=<?php echo $value['user_id']; ?>"><?php echo $this->lang['mod']['href']['show']; ?></a>
                                            </li>
                                            <li>
                                                <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=user&a=form&user_id=<?php echo $value['user_id']; ?>"><?php echo $this->lang['mod']['href']['edit']; ?></a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </td>
                            <td class="text-nowrap bg-td-sm">
                                <ul class="list-unstyled">
                                    <li>
                                        <?php user_status_process($value['user_status'], $this->lang['mod']['status']); ?>
                                    </li>
                                    <li><?php echo $value['user_note']; ?></li>
                                </ul>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <span class="form-text" id="msg_user_id"></span>
            <div class="bg-submit-box"></div>
        </div>

        <div class="mt-3 clearfix">
            <div class="float-left">
                <div class="input-group">
                    <select name="a" id="a" data-validate class="custom-select">
                        <option value=""><?php echo $this->lang['mod']['option']['batch']; ?></option>
                        <?php foreach ($this->tplData['status'] as $key=>$value) {?>
                            <option value="<?php echo $value; ?>">
                                <?php if (isset($this->lang['mod']['status'][$value])) {
                                    echo $this->lang['mod']['status'][$value];
                                } else {
                                    echo $value;
                                } ?>
                            </option>
                        <?php } ?>
                        <option value="del"><?php echo $this->lang['mod']['option']['del']; ?></option>
                    </select>
                    <span class="input-group-append">
                        <button type="button" class="btn btn-primary bg-submit"><?php echo $this->lang['mod']['btn']['submit']; ?></button>
                    </span>
                </div>
                <span class="form-text" id="msg_a"></span>
            </div>
            <div class="float-right">
                <?php include($cfg['pathInclude'] . 'page.php'); ?>
            </div>
        </div>

    </form>

<?php include($cfg['pathInclude'] . 'console_foot.php'); ?>

    <script type="text/javascript">
    var opts_validator_list = {
        user_id: {
            len: { min: 1, max: 0 },
            validate: { selector: "[data-validate='user_id']", type: "checkbox" },
            msg: { too_few: "<?php echo $this->lang['rcode']['x030202']; ?>" }
        },
        a: {
            len: { min: 1, max: 0 },
            validate: { type: "select" },
            msg: { too_few: "<?php echo $this->lang['rcode']['x030203']; ?>" }
        }
    };

    var opts_submit_list = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>index.php?m=user&c=request",
        confirm: {
            selector: "#a",
            val: "del",
            msg: "<?php echo $this->lang['mod']['confirm']['del']; ?>"
        },
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        }
    };

    $(document).ready(function(){
        var obj_validate_list = $("#user_list").baigoValidator(opts_validator_list);
        var obj_submit_list   = $("#user_list").baigoSubmit(opts_submit_list);
        $(".bg-submit").click(function(){
            if (obj_validate_list.verify()) {
                obj_submit_list.formSubmit();
            }
        });
        $("#user_list").baigoCheckall();
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot.php');
