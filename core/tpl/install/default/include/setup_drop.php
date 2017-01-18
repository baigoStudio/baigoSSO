                    <div class="btn-group dropup">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <?php echo $this->lang["btn"]["jump"]; ?>
                            <span class="caret"></span>
                        </button>

                        <ul class="dropdown-menu">
                            <li<?php if ($GLOBALS["act"] == "ext") { ?> class="active"<?php } ?>><a href="<?php echo BG_URL_INSTALL; ?>index.php?mod=setup&act=ext"><?php echo $this->lang["page"]["setupExt"]; ?></a></li>
                            <li class="divider"></li>

                            <li<?php if ($GLOBALS["act"] == "dbconfig") { ?> class="active"<?php } ?>><a href="<?php echo BG_URL_INSTALL; ?>index.php?mod=setup&act=dbconfig"><?php echo $this->lang["page"]["setupDbConfig"]; ?></a></li>
                            <li<?php if ($GLOBALS["act"] == "dbtable") { ?> class="active"<?php } ?>><a href="<?php echo BG_URL_INSTALL; ?>index.php?mod=setup&act=dbtable"><?php echo $this->lang["page"]["setupDbTable"]; ?></a></li>
                            <li class="divider"></li>

                            <?php foreach ($this->opt as $key_opt=>$value_opt) { ?>
                                <li<?php if ($GLOBALS["act"] == $key_opt) { ?> class="active"<?php } ?>><a href="<?php echo BG_URL_INSTALL; ?>index.php?mod=setup&act=<?php echo $key_opt; ?>"><?php echo $value_opt["title"]; ?></a>
                                </li>
                            <?php } ?>

                            <li class="divider"></li>

                            <li<?php if ($GLOBALS["act"] == "admin") { ?> class="active"<?php } ?>><a href="<?php echo BG_URL_INSTALL; ?>index.php?mod=setup&act=admin"><?php echo $this->lang["page"]["setupAdmin"]; ?></a></li>
                            <li<?php if ($GLOBALS["act"] == "over") { ?> class="active"<?php } ?>><a href="<?php echo BG_URL_INSTALL; ?>index.php?mod=setup&act=over"><?php echo $this->lang["page"]["setupOver"]; ?></a></li>
                        </ul>
                    </div>
