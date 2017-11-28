                <script type="text/javascript">
                var opts_validator_form = {
                    db_host: {
                        len: { min: 1, max: 900 },
                        validate: { type: "str", format: "text", group: "#group_db_host" },
                        msg: { selector: "#msg_db_host", too_short: "<?php echo $this->lang['rcode']['x040204']; ?>", too_long: "<?php echo $this->lang['rcode']['x040205']; ?>" }
                    },
                    db_name: {
                        len: { min: 1, max: 900 },
                        validate: { type: "str", format: "text", group: "#group_db_name" },
                        msg: { selector: "#msg_db_name", too_short: "<?php echo $this->lang['rcode']['x040206']; ?>", too_long: "<?php echo $this->lang['rcode']['x040207']; ?>" }
                    },
                    db_port: {
                        len: { min: 1, max: 900 },
                        validate: { type: "str", format: "text", group: "#group_db_port" },
                        msg: { selector: "#msg_db_port", too_short: "<?php echo $this->lang['rcode']['x040208']; ?>", too_long: "<?php echo $this->lang['rcode']['x040209']; ?>" }
                    },
                    db_user: {
                        len: { min: 1, max: 900 },
                        validate: { type: "str", format: "text", group: "#group_db_user" },
                        msg: { selector: "#msg_db_user", too_short: "<?php echo $this->lang['rcode']['x040210']; ?>", too_long: "<?php echo $this->lang['rcode']['x040211']; ?>" }
                    },
                    db_pass: {
                        len: { min: 1, max: 900 },
                        validate: { type: "str", format: "text", group: "#group_db_pass" },
                        msg: { selector: "#msg_db_pass", too_short: "<?php echo $this->lang['rcode']['x040212']; ?>", too_long: "<?php echo $this->lang['rcode']['x040213']; ?>" }
                    },
                    db_charset: {
                        len: { min: 1, max: 900 },
                        validate: { type: "str", format: "text", group: "#group_db_charset" },
                        msg: { selector: "#msg_db_charset", too_short: "<?php echo $this->lang['rcode']['x040214']; ?>", too_long: "<?php echo $this->lang['rcode']['x040215']; ?>" }
                    },
                    db_table: {
                        len: { min: 1, max: 900 },
                        validate: { type: "str", format: "text", group: "#group_db_table" },
                        msg: { selector: "#msg_db_table", too_short: "<?php echo $this->lang['rcode']['x040216']; ?>", too_long: "<?php echo $this->lang['rcode']['x040217']; ?>" }
                    }
                };
                </script>

                <div class="form-group">
                    <div id="group_db_host">
                        <label class="control-label"><?php echo $this->lang['mod']['label']['dbHost']; ?><span id="msg_db_host">*</span></label>
                        <input type="text" value="<?php echo BG_DB_HOST; ?>" name="db_host" id="db_host" data-validate class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <div id="group_db_name">
                        <label class="control-label"><?php echo $this->lang['mod']['label']['dbName']; ?><span id="msg_db_name">*</span></label>
                        <input type="text" value="<?php echo BG_DB_NAME; ?>" name="db_name" id="db_name" data-validate class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <div id="group_db_port">
                        <label class="control-label"><?php echo $this->lang['mod']['label']['dbPort']; ?><span id="msg_db_port">*</span></label>
                        <input type="text" value="<?php echo BG_DB_PORT; ?>" name="db_port" id="db_port" data-validate class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <div id="group_db_user">
                        <label class="control-label"><?php echo $this->lang['mod']['label']['dbUser']; ?><span id="msg_db_user">*</span></label>
                        <input type="text" value="<?php echo BG_DB_USER; ?>" name="db_user" id="db_user" data-validate class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <div id="group_db_pass">
                        <label class="control-label"><?php echo $this->lang['mod']['label']['dbPass']; ?><span id="msg_db_pass">*</span></label>
                        <input type="text" value="<?php echo BG_DB_PASS; ?>" name="db_pass" id="db_pass" data-validate class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <div id="group_db_charset">
                        <label class="control-label"><?php echo $this->lang['mod']['label']['dbCharset']; ?><span id="msg_db_charset">*</span></label>
                        <input type="text" value="<?php echo BG_DB_CHARSET; ?>" name="db_charset" id="db_charset" data-validate class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <div id="group_db_table">
                        <label class="control-label"><?php echo $this->lang['mod']['label']['dbtable']; ?><span id="msg_db_table">*</span></label>
                        <input type="text" value="<?php echo BG_DB_TABLE; ?>" name="db_table" id="db_table" data-validate class="form-control">
                    </div>
                </div>
