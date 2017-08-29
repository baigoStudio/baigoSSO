                    <div class="btn-group dropup">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <?php echo $this->lang['mod']['btn']["jump"]; ?>
                            <span class="caret"></span>
                        </button>

                        <ul class="dropdown-menu">
                            <li<?php if ($GLOBALS['act'] == "ext") { ?> class="active"<?php } ?>><a href="<?php echo BG_URL_INSTALL; ?>index.php?mod=setup&act=ext"><?php echo $this->lang['mod']['page']['ext']; ?></a></li>
                            <li class="divider"></li>

                            <li<?php if ($GLOBALS['act'] == "dbconfig") { ?> class="active"<?php } ?>><a href="<?php echo BG_URL_INSTALL; ?>index.php?mod=setup&act=dbconfig"><?php echo $this->lang['common']['page']['dbConfig']; ?></a></li>
                            <li<?php if ($GLOBALS['act'] == "dbtable") { ?> class="active"<?php } ?>><a href="<?php echo BG_URL_INSTALL; ?>index.php?mod=setup&act=dbtable"><?php echo $this->lang['mod']['page']["dbTable"]; ?></a></li>
                            <li class="divider"></li>

                            <?php foreach ($this->opt as $key_opt=>$value_opt) { ?>
                                <li<?php if ($GLOBALS['act'] == $key_opt) { ?> class="active"<?php } ?>>
                                    <a href="<?php echo BG_URL_INSTALL; ?>index.php?mod=setup&act=<?php echo $key_opt; ?>">
                                        <?php if (isset($this->lang['opt'][$key_opt]['title'])) {
                                            echo $this->lang['opt'][$key_opt]['title'];
                                        } else {
                                            echo $key_opt;
                                        } ?>
                                    </a>
                                </li>
                            <?php } ?>

                            <li class="divider"></li>

                            <li<?php if ($GLOBALS['act'] == "admin") { ?> class="active"<?php } ?>><a href="<?php echo BG_URL_INSTALL; ?>index.php?mod=setup&act=admin"><?php echo $this->lang['mod']['page']['admin']; ?></a></li>
                            <li class="divider"></li>

                            <li<?php if ($GLOBALS['act'] == "over") { ?> class="active"<?php } ?>><a href="<?php echo BG_URL_INSTALL; ?>index.php?mod=setup&act=over"><?php echo $this->lang['mod']['page']["over"]; ?></a></li>
                        </ul>
                    </div>
