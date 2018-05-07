                    <div class="btn-group dropup">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                            <?php echo $this->lang['mod']['btn']['jump']; ?>
                        </button>

                        <div class="dropdown-menu">
                            <a class="dropdown-item<?php if ($GLOBALS['route']['bg_act'] == 'phplib') { ?> active<?php } ?>" href="<?php echo BG_URL_INSTALL; ?>index.php?m=upgrade&a=ext"><?php echo $this->lang['mod']['page']['phplib']; ?></a>
                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item<?php if ($GLOBALS['route']['bg_act'] == 'dbconfig') { ?> active<?php } ?>" href="<?php echo BG_URL_INSTALL; ?>index.php?m=upgrade&a=dbconfig"><?php echo $this->lang['common']['page']['dbconfig']; ?></a>
                            <a class="dropdown-item<?php if ($GLOBALS['route']['bg_act'] == 'dbtable') { ?> active<?php } ?>" href="<?php echo BG_URL_INSTALL; ?>index.php?m=upgrade&a=dbtable"><?php echo $this->lang['mod']['page']['dbtable']; ?></a>
                            <div class="dropdown-divider"></div>

                            <?php foreach ($this->opt as $key_opt=>$value_opt) { ?>
                                <a class="dropdown-item<?php if ($GLOBALS['route']['bg_act'] == $key_opt) { ?> active<?php } ?>" href="<?php echo BG_URL_INSTALL; ?>index.php?m=upgrade&a=<?php echo $key_opt; ?>">
                                    <?php if (isset($this->lang['opt'][$key_opt]['title'])) {
                                        echo $this->lang['opt'][$key_opt]['title'];
                                    } else {
                                        echo $value_opt['title'];
                                    } ?>
                                </a>
                            <?php } ?>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item<?php if ($GLOBALS['route']['bg_act'] == "admin") { ?> active<?php } ?>" href="<?php echo BG_URL_INSTALL; ?>index.php?m=upgrade&a=admin"><?php echo $this->lang['mod']['page']['admin']; ?></a>
                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item<?php if ($GLOBALS['route']['bg_act'] == "over") { ?> active<?php } ?>" href="<?php echo BG_URL_INSTALL; ?>index.php?m=upgrade&a=over"><?php echo $this->lang['mod']['page']['over']; ?></a>
                        </div>
                    </div>
