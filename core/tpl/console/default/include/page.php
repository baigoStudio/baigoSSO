    <ul class="pagination pagination-sm">
        <?php if ($this->tplData['pageRow']['page'] > 1) { ?>
            <li>
                <a href="<?php echo $cfg['str_url']; ?>&page=1" title="<?php echo $this->lang['common']['href']['pageFirst']; ?>"><?php echo $this->lang['common']['href']['pageFirst']; ?></a>
            </li>
        <?php }

        if ($this->tplData['pageRow']['p'] * 10 > 0) { ?>
            <li>
                <a href="<?php echo $cfg['str_url']; ?>&page=<?php echo $this->tplData['pageRow']['p'] * 10; ?>" title="<?php echo $this->lang['common']['href']['pagePrevList']; ?>">...</a>
            </li>
        <?php } ?>

        <li class="<?php if ($this->tplData['pageRow']['page'] <= 1) { ?> disabled<?php } ?>">
            <?php if ($this->tplData['pageRow']['page'] <= 1) { ?>
                <span title="<?php echo $this->lang['common']['href']['pagePrev']; ?>"><span class="glyphicon glyphicon-menu-left"></span></span>
            <?php } else { ?>
                <a href="<?php echo $cfg['str_url']; ?>&page=<?php echo $this->tplData['pageRow']['page'] - 1; ?>" title="<?php echo $this->lang['common']['href']['pagePrev']; ?>"><span class="glyphicon glyphicon-menu-left"></span></a>
            <?php } ?>
        </li>

        <?php for ($iii = $this->tplData['pageRow']['begin']; $iii <= $this->tplData['pageRow']['end']; $iii++) { ?>
            <li<?php if ($iii == $this->tplData['pageRow']['page']) { ?> class="active"<?php } ?>>
                <?php if ($iii == $this->tplData['pageRow']['page']) { ?>
                    <span><?php echo $iii; ?></span>
                <?php } else { ?>
                    <a href="<?php echo $cfg['str_url']; ?>&page=<?php echo $iii; ?>" title="<?php echo $iii; ?>"><?php echo $iii; ?></a>
                <?php } ?>
            </li>
        <?php } ?>

        <li<?php if ($this->tplData['pageRow']['page'] >= $this->tplData['pageRow']['total']) { ?> class="disabled"<?php } ?>>
            <?php if ($this->tplData['pageRow']['page'] >= $this->tplData['pageRow']['total']) { ?>
                <span title="<?php echo $this->lang['common']['href']['pageNext']; ?>"><span class="glyphicon glyphicon-menu-right"></span></span>
            <?php } else { ?>
                <a href="<?php echo $cfg['str_url']; ?>&page=<?php echo $this->tplData['pageRow']['page'] + 1; ?>" title="<?php echo $this->lang['common']['href']['pageNext']; ?>"><span class="glyphicon glyphicon-menu-right"></span></a>
            <?php } ?>
        </li>

        <?php if ($this->tplData['pageRow']['end'] < $this->tplData['pageRow']['total']) { ?>
            <li>
                <a href="<?php echo $cfg['str_url']; ?>&page=<?php echo $iii; ?>" title="<?php echo $this->lang['common']['href']['pageNextList']; ?>">...</a>
            </li>
        <?php }

        if ($this->tplData['pageRow']['page'] < $this->tplData['pageRow']['total']) { ?>
            <li>
                <a href="<?php echo $cfg['str_url']; ?>&page=<?php echo $this->tplData['pageRow']['total']; ?>" title="<?php echo $this->lang['common']['href']['pageLast']; ?>"><?php echo $this->lang['common']['href']['pageLast']; ?></a>
            </li>
        <?php } ?>
    </ul>