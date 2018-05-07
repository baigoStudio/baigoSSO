    <ul class="pagination">
        <?php if ($this->tplData['pageRow']['page'] > 1) { ?>
            <li class="page-item">
                <a href="<?php echo $cfg['str_url']; ?>&page=1" title="<?php echo $this->lang['common']['href']['pageFirst']; ?>" class="page-link"><?php echo $this->lang['common']['href']['pageFirst']; ?></a>
            </li>
        <?php }

        if ($this->tplData['pageRow']['p'] * 10 > 0) { ?>
            <li class="page-item">
                <a href="<?php echo $cfg['str_url']; ?>&page=<?php echo $this->tplData['pageRow']['p'] * 10; ?>" title="<?php echo $this->lang['common']['href']['pagePrevList']; ?>" class="page-link">...</a>
            </li>
        <?php } ?>

        <li class="page-item<?php if ($this->tplData['pageRow']['page'] <= 1) { ?> disabled<?php } ?>">
            <?php if ($this->tplData['pageRow']['page'] <= 1) { ?>
                <span title="<?php echo $this->lang['common']['href']['pagePrev']; ?>" class="page-link"><span class="oi oi-chevron-left"></span></span>
            <?php } else { ?>
                <a href="<?php echo $cfg['str_url']; ?>&page=<?php echo $this->tplData['pageRow']['page'] - 1; ?>" title="<?php echo $this->lang['common']['href']['pagePrev']; ?>" class="page-link"><span class="oi oi-chevron-left"></span></a>
            <?php } ?>
        </li>

        <?php for ($iii = $this->tplData['pageRow']['begin']; $iii <= $this->tplData['pageRow']['end']; $iii++) { ?>
            <li class="page-item<?php if ($iii == $this->tplData['pageRow']['page']) { ?> active<?php } ?>">
                <?php if ($iii == $this->tplData['pageRow']['page']) { ?>
                    <span class="page-link"><?php echo $iii; ?></span>
                <?php } else { ?>
                    <a href="<?php echo $cfg['str_url']; ?>&page=<?php echo $iii; ?>" title="<?php echo $iii; ?>" class="page-link"><?php echo $iii; ?></a>
                <?php } ?>
            </li>
        <?php } ?>

        <li class="page-item<?php if ($this->tplData['pageRow']['page'] >= $this->tplData['pageRow']['total']) { ?> disabled<?php } ?>">
            <?php if ($this->tplData['pageRow']['page'] >= $this->tplData['pageRow']['total']) { ?>
                <span title="<?php echo $this->lang['common']['href']['pageNext']; ?>" class="page-link"><span class="oi oi-chevron-right"></span></span>
            <?php } else { ?>
                <a href="<?php echo $cfg['str_url']; ?>&page=<?php echo $this->tplData['pageRow']['page'] + 1; ?>" title="<?php echo $this->lang['common']['href']['pageNext']; ?>" class="page-link"><span class="oi oi-chevron-right"></span></a>
            <?php } ?>
        </li>

        <?php if ($this->tplData['pageRow']['end'] < $this->tplData['pageRow']['total']) { ?>
            <li class="page-item">
                <a href="<?php echo $cfg['str_url']; ?>&page=<?php echo $iii; ?>" title="<?php echo $this->lang['common']['href']['pageNextList']; ?>" class="page-link">...</a>
            </li>
        <?php }

        if ($this->tplData['pageRow']['page'] < $this->tplData['pageRow']['total']) { ?>
            <li class="page-item">
                <a href="<?php echo $cfg['str_url']; ?>&page=<?php echo $this->tplData['pageRow']['total']; ?>" title="<?php echo $this->lang['common']['href']['pageLast']; ?>" class="page-link"><?php echo $this->lang['common']['href']['pageLast']; ?></a>
            </li>
        <?php } ?>
    </ul>