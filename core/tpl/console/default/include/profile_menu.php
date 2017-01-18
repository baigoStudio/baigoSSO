        <?php foreach ($this->type["profile"] as $_key=>$_value) { ?>
            <li<?php if (isset($cfg["sub_active"]) && $cfg["sub_active"] == $_key) { ?> class="active"<?php } ?>>
                <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=profile&act=<?php echo $_key; ?>">
                    <span class="glyphicon glyphicon-<?php echo $_value["icon"]; ?>"></span>
                    <?php echo $_value["title"]; ?>
                </a>
            </li>
        <?php } ?>
