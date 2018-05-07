    <div class="form-group">
        <?php foreach ($this->tplData['db_rcode'] as $key=>$value) {
            if ($value['status'] == 'y') {
                $str_css   = 'text-success';
                $str_icon  = 'circle-check';
            } else {
                $str_css   = 'text-danger';
                $str_icon  = 'circle-x';
            } ?>
            <p class="<?php echo $str_css; ?>">
                <span class="oi oi-<?php echo $str_icon; ?>"></span>
                <?php echo $this->lang['rcode'][$value['rcode']]; ?>
                &nbsp;&nbsp;
                [ <?php echo $value['rcode']; ?> ]
            </p>
        <?php } ?>
    </div>