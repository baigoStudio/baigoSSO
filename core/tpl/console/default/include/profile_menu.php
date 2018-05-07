                <ul class="nav nav-pills mb-3">
                    <?php foreach ($this->profile as $_key=>$_value) { ?>
                        <li class="nav-item">
                            <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=profile&a=<?php echo $_key; ?>" class="nav-link<?php if (isset($cfg['sub_active']) && $cfg['sub_active'] == $_key) { ?> active<?php } ?>">
                                <?php if (isset($this->lang['common']['profile'][$_key]['icon'])) { ?>
                                    <span class="oi oi-<?php echo $this->lang['common']['profile'][$_key]['icon']; ?>"></span>
                                <?php }

                                if (isset($this->lang['common']['profile'][$_key]['title'])) {
                                    echo $this->lang['common']['profile'][$_key]['title'];
                                } else {
                                    echo $_value['title'];
                                } ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
