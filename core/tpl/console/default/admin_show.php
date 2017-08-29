<?php $cfg = array(
    'title'          => $this->lang['consoleMod']['admin']['main']['title'] . ' &raquo; ' . $this->lang['mod']['page']['show'],
    'menu_active'    => 'admin',
    'sub_active'     => 'list',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
);

include($cfg['pathInclude'] . 'console_head.php'); ?>

    <div class="form-group">
        <ul class="nav nav-pills bg-nav-pills">
            <li>
                <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=admin&act=list">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <?php echo $this->lang['mod']['href']['back']; ?>
                </a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="form-group">
                        <label class="control-label"><?php echo $this->lang['mod']['label']['username']; ?></label>
                        <div class="form-control-static"><?php echo $this->tplData['adminRow']['admin_name']; ?></div>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php echo $this->lang['mod']['label']["nick"]; ?></label>
                        <div class="form-control-static"><?php echo $this->tplData['adminRow']['admin_nick']; ?></div>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php echo $this->lang['mod']['label']['allow']; ?></label>
                        <dl class="bg-dl">
                            <?php foreach ($this->consoleMod as $key_m=>$value_m) {
                                if (isset($value_m['allow']) && $value_m['allow']) { ?>
                                    <dt>
                                        <?php if (isset($this->lang['consoleMod'][$key_m]['main']['title'])) {
                                            echo $this->lang['consoleMod'][$key_m]['main']['title'];
                                        } else {
                                            echo $value_m['main']['title'];
                                        } ?>
                                    </dt>
                                    <dd>
                                        <ul class="list-inline">
                                            <?php foreach ($value_m['allow'] as $key_s=>$value_s) { ?>
                                                <li>
                                                    <span class="glyphicon glyphicon-<?php if (isset($this->tplData['adminRow']['admin_allow'][$key_m][$key_s])) { ?>ok-sign text-success<?php } else { ?>remove-sign text-danger<?php } ?>"></span>
                                                    <?php if (isset($this->lang['consoleMod'][$key_m]['allow'][$key_s])) {
                                                        echo $this->lang['consoleMod'][$key_m]['allow'][$key_s];
                                                    } else {
                                                        echo $value_s;
                                                    } ?>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </dd>
                                <?php }
                            } ?>
                            <dt><?php echo $this->lang['mod']['label']['opt']; ?></dt>
                            <dd>
                                <ul class="list-inline">
                                    <?php foreach ($this->opt as $key_s=>$value_s) { ?>
                                        <li>
                                            <span class="glyphicon glyphicon-<?php if (isset($this->tplData['adminRow']['admin_allow']['opt'][$key_s])) { ?>ok-sign text-success<?php } else { ?>remove-sign text-danger<?php } ?>"></span>
                                            <?php if (isset($this->lang['opt'][$key_s]['title'])) {
                                                echo $this->lang['opt'][$key_s]['title'];
                                            } else {
                                                echo $value_s['title'];
                                            } ?>
                                        </li>
                                    <?php } ?>
                                    <li>
                                        <span class="glyphicon glyphicon-<?php if (isset($this->tplData['adminRow']['admin_allow']['opt']["dbconfig"])) { ?>ok-sign text-success<?php } else { ?>remove-sign text-danger<?php } ?>"></span>
                                        <?php echo $this->lang['common']['page']['dbConfig']; ?>
                                    </li>
                                    <li>
                                        <span class="glyphicon glyphicon-<?php if (isset($this->tplData['adminRow']['admin_allow']['opt']["chkver"])) { ?>ok-sign text-success<?php } else { ?>remove-sign text-danger<?php } ?>"></span>
                                        <?php echo $this->lang['common']['page']['chkver']; ?>
                                    </li>
                                </ul>
                            </dd>
                        </dl>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php echo $this->lang['mod']['label']['note']; ?></label>
                        <div class="form-control-static"><?php echo $this->tplData['adminRow']['admin_note']; ?></div>
                    </div>

                    <div class="form-group">
                        <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=admin&act=form&admin_id=<?php echo $this->tplData['adminRow']['admin_id']; ?>">
                            <span class="glyphicon glyphicon-edit"></span>
                            <?php echo $this->lang['mod']['href']['edit']; ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="well">
                <div class="form-group">
                    <label class="control-label"><?php echo $this->lang['mod']['label']['id']; ?></label>
                    <div class="form-control-static"><?php echo $this->tplData['adminRow']['admin_id']; ?></div>
                </div>

                <div class="form-group">
                    <label class="control-label"><?php echo $this->lang['mod']['label']['type']; ?></label>
                    <div class="form-control-static"><?php echo $this->lang['mod']['type'][$this->tplData['adminRow']['admin_type']]; ?></div>
                </div>

                <?php if ($this->tplData['adminRow']['admin_status'] == 'enable') {
                    $css_status = 'success';
                } else {
                    $css_status = 'default';
                } ?>

                <div class="form-group">
                    <label class="control-label"><?php echo $this->lang['mod']['label']['status']; ?></label>
                    <div class="form-control-static">
                        <span class="label label-<?php echo $css_status; ?> bg-label"><?php echo $this->lang['mod']['status'][$this->tplData['adminRow']['admin_status']]; ?></span>
                    </div>
                </div>

                <div class="form-group">
                    <?php foreach ($this->profile as $_key=>$_value) {
                        if (isset($this->tplData['adminRow']['admin_allow'][$_key])) { ?>
                        <div>
                            <span class="label label-danger bg-label">
                                <?php echo $this->lang['mod']['label']['forbidModi'];
                                if (isset($this->lang['common']['profile'][$key]['title'])) {
                                    echo $this->lang['common']['profile'][$key]['title'];
                                } else {
                                    echo $value['title'];
                                } ?>
                            </span>
                        </div>
                        <?php }
                    } ?>
                </div>

                <div class="form-group">
                    <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=admin&act=form&admin_id=<?php echo $this->tplData['adminRow']['admin_id']; ?>">
                        <span class="glyphicon glyphicon-edit"></span>
                        <?php echo $this->lang['mod']['href']['edit']; ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

<?php include($cfg['pathInclude'] . 'console_foot.php');
include($cfg['pathInclude'] . 'html_foot.php'); ?>
