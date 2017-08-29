                    <?php foreach ($this->profile as $_key=>$_value) { ?>
                        <li<?php if (isset($cfg['sub_active']) && $cfg['sub_active'] == $_key) { ?> class="active"<?php } ?>>
                            <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=profile&act=<?php echo $_key; ?>">
                                <?php if (isset($this->lang['common']['profile'][$_key]['icon'])) { ?>
                                    <span class="glyphicon glyphicon-<?php echo $this->lang['common']['profile'][$_key]['icon']; ?>"></span>
                                <?php }

                                if (isset($this->lang['common']['profile'][$_key]['title'])) {
                                    echo $this->lang['common']['profile'][$_key]['title'];
                                } else {
                                    echo $_value['title'];
                                } ?>
                            </a>
                        </li>
                    <?php } ?>
