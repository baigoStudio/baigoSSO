<?php $cfg = array(
    'title'          => $this->lang['consoleMod']['admin']['main']['title'] . ' &raquo; ' . $this->lang['mod']['page']['show'],
    'menu_active'    => 'admin',
    'sub_active'     => 'list',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
);

include($cfg['pathInclude'] . 'function.php');
include($cfg['pathInclude'] . 'console_head.php'); ?>

    <ul class="nav nav-pills mb-3">
        <li class="nav-item">
            <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=admin&a=list" class="nav-link">
                <span class="oi oi-chevron-left"></span>
                <?php echo $this->lang['common']['href']['back']; ?>
            </a>
        </li>
    </ul>

    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label><?php echo $this->lang['mod']['label']['username']; ?></label>
                        <div class="form-text"><?php echo $this->tplData['adminRow']['admin_name']; ?></div>
                    </div>

                    <div class="form-group">
                        <label><?php echo $this->lang['mod']['label']['nick']; ?></label>
                        <div class="form-text"><?php echo $this->tplData['adminRow']['admin_nick']; ?></div>
                    </div>

                    <div class="form-group">
                        <label><?php echo $this->lang['mod']['label']['allow']; ?></label>

                        <?php allow_list($this->consoleMod, $this->lang['consoleMod'], $this->opt, $this->lang['opt'], $this->lang['mod']['label'], $this->lang['common']['page'], $this->tplData['adminRow']['admin_allow'], $this->tplData['adminRow']['admin_type'], false); ?>
                    </div>

                    <div class="form-group">
                        <label><?php echo $this->lang['mod']['label']['note']; ?></label>
                        <div class="form-text"><?php echo $this->tplData['adminRow']['admin_note']; ?></div>
                    </div>

                    <div class="form-group">
                        <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=admin&a=form&admin_id=<?php echo $this->tplData['adminRow']['admin_id']; ?>">
                            <span class="oi oi-pencil"></span>
                            <?php echo $this->lang['mod']['href']['edit']; ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body">
                    <div class="form-group">
                        <label><?php echo $this->lang['mod']['label']['id']; ?></label>
                        <div class="form-text"><?php echo $this->tplData['adminRow']['admin_id']; ?></div>
                    </div>

                    <div class="form-group">
                        <label><?php echo $this->lang['mod']['label']['type']; ?></label>
                        <div class="form-text"><?php echo $this->lang['mod']['type'][$this->tplData['adminRow']['admin_type']]; ?></div>
                    </div>

                    <div class="form-group">
                        <label><?php echo $this->lang['mod']['label']['status']; ?></label>
                        <div class="form-text">
                            <?php admin_status_process($this->tplData['adminRow']['admin_status'], $this->lang['mod']['status']); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php foreach ($this->profile as $key=>$value) {
                            if (isset($this->tplData['adminRow']['admin_allow'][$key])) { ?>
                            <div>
                                <span class="badge badge-danger">
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
                        <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=admin&a=form&admin_id=<?php echo $this->tplData['adminRow']['admin_id']; ?>">
                            <span class="oi oi-pencil"></span>
                            <?php echo $this->lang['mod']['href']['edit']; ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include($cfg['pathInclude'] . 'console_foot.php');
include($cfg['pathInclude'] . 'html_foot.php');
