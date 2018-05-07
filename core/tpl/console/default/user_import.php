<?php $cfg = array(
    'title'          => $this->lang['consoleMod']['user']['main']['title'] . ' &raquo; ' . $this->lang['consoleMod']['user']['sub']['import'],
    'menu_active'    => 'user',
    'sub_active'     => "import",
    'baigoCheckall'  => 'true',
    'baigoValidator' => 'true',
    'baigoSubmit'    => 'true',
    "upload"         => 'true',
    "md5"            => 'true',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
    'str_url'        => BG_URL_CONSOLE . 'index.php?m=user',
);

include($cfg['pathInclude'] . 'console_head.php'); ?>

    <ul class="nav nav-pills mb-3">
        <li class="nav-item">
            <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=user&a=list" class="nav-link">
                <span class="oi oi-chevron-left"></span>
                <?php echo $this->lang['common']['href']['back']; ?>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo BG_URL_HELP; ?>index.php?m=console&a=user" target="_blank" class="nav-link">
                <span class="badge badge-pill badge-primary">
                    <span class="oi oi-question-mark"></span>
                </span>
                <?php echo $this->lang['mod']['href']['help']; ?>
            </a>
        </li>
    </ul>

    <div class="row">
        <div class="col-md-6">
            <?php if (!fn_isEmpty($this->tplData['csvRows'])) { ?>
                <form name="csv_convert" id="csv_convert">
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">
                            <input type="hidden" name="a" value="convert">
                            <input type="hidden" name="charset" value="<?php echo $this->tplData['charset']; ?>">

                            <table>
                                <thead>
                                    <tr>
                                        <th class="p-2"><?php echo $this->lang['mod']['label']['source']; ?></th>
                                        <th class="p-2">&nbsp;</th>
                                        <th class="p-2"><?php echo $this->lang['mod']['label']['convert']; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($this->tplData['csvRows'][0]) && !fn_isEmpty($this->tplData['csvRows'][0])) {
                                        foreach ($this->tplData['csvRows'][0] as $key=>$value) {
                                            if (!fn_isEmpty($value)) { ?>
                                                <tr>
                                                    <td class="p-2"><?php echo $value; ?></td>
                                                    <td class="p-2">
                                                        <span class="oi oi-arrow-right"></span>
                                                    </td>
                                                    <td class="p-2">
                                                        <select name="user_convert[<?php echo $key; ?>]" class="form-control">
                                                            <option <?php if ($key < 1) { ?>selected<?php } ?> value="user_name"><?php echo $this->lang['mod']['label']['username']; ?></option>
                                                            <option <?php if ($key == 1) { ?>selected<?php } ?> value="user_pass"><?php echo $this->lang['mod']['label']['password']; ?></option>
                                                            <option <?php if ($key == 2) { ?>selected<?php } ?> value="user_nick"><?php echo $this->lang['mod']['label']['nick']; ?></option>
                                                            <option value="abort"><?php echo $this->lang['mod']['option']['abort']; ?></option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            <?php }
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer">
                            <div class="bg-submit-box bg-submit-box-convert"></div>
                            <button type="button" class="btn btn-primary bg-submit"><?php echo $this->lang['mod']['btn']['convert']; ?></button>
                        </div>
                    </div>
                </form>
            <?php } ?>
        </div>
        <div class="col-md-6">
            <div class="card bg-light">
                <div class="card-body">
                    <div class="alert alert-warning">
                        <span class="oi oi-warning"></span>
                        <?php echo $this->lang['mod']['text']['refreshImport']; ?>
                    </div>

                    <form name="csv_import" id="csv_import">
                        <div class="form-group">
                            <a id="upload_select" class="btn btn-success text-white fileinput-button">
                                <span class="oi oi-upload"></span>
                                <?php echo $this->lang['mod']['btn']['uploadCsv']; ?>
                            </a>
                        </div>

                        <div id="csv_upload"></div>
                    </form>

                    <hr class="bg-card-hr">

                    <form name="csv_del" id="csv_del">
                        <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">
                        <input type="hidden" name="a" value="csvDel">
                        <div class="bg-submit-box bg-submit-box-del"></div>
                        <div class="form-group">
                            <button class="btn btn-primary bg-submit-del" type="button">
                                <span class="oi oi-trash"></span>
                                <?php echo $this->lang['mod']['btn']['delCsv']; ?>
                            </button>
                        </div>
                    </form>

                    <hr class="bg-card-hr">

                    <div class="form-group">
                        <label><?php echo $this->lang['mod']['label']['md5tool']; ?></label>
                        <input type="text" id="pass_src" class="form-control" placeholder="<?php echo $this->lang['mod']['label']['password']; ?>">
                    </div>
                    <div class="form-group">
                        <label><?php echo $this->lang['mod']['label']['md5result']; ?></label>
                        <input type="text" id="pass_result" class="form-control">
                    </div>
                    <div class="form-group">
                        <button type="button" id="md5_do" class="btn btn-primary">
                            <span class="oi oi-lock"></span>
                            <?php echo $this->lang['mod']['btn']['md5gen']; ?>
                        </button>
                    </div>

                    <hr class="bg-card-hr">

                    <form id="form_preview" name="form_preview" action="<?php echo BG_URL_CONSOLE; ?>index.php">
                        <input type="hidden" name="m" value="user">
                        <input type="hidden" name="a" value="import">

                        <div class="form-group">
                            <label><?php echo $this->lang['mod']['label']['charsetSrc']; ?></label>
                            <select name="charset" id="charset" class="form-control">
                                <?php foreach ($this->tplData['charsetRows'] as $key=>$value) { ?>
                                    <optgroup label="<?php echo $value['title']; ?>">
                                        <?php foreach ($value['list'] as $key_sub=>$value_sub) { ?>
                                            <option <?php if ($this->tplData['charset'] == $key_sub) { ?>selected<?php } ?> value="<?php echo $key_sub; ?>">
                                                <?php echo $value_sub['title'], ' ', $key_sub; ?>
                                            </option>
                                        <?php } ?>
                                    </optgroup>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary"><?php echo $this->lang['mod']['btn']['submit']; ?></button>
                                <a class="btn btn-outline-secondary" href="#charset_list_modal" data-toggle="modal">
                                    <?php echo $this->lang['mod']['btn']['charset']; ?>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <h3><?php echo $this->lang['mod']['label']['preview']; ?></h3>
        <table class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <?php if (isset($this->tplData['csvRows'][0]) && !fn_isEmpty($this->tplData['csvRows'][0])) {
                        foreach ($this->tplData['csvRows'][0] as $key=>$value) { ?>
                            <th><?php echo $value; ?></th>
                        <?php }
                    } ?>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($this->tplData['csvRows']) && !fn_isEmpty($this->tplData['csvRows'])) {
                    foreach ($this->tplData['csvRows'] as $key=>$value) {
                        if ($key > 0) { ?>
                            <tr>
                                <?php foreach ($value as $key_s=>$value_s) { ?>
                                    <td><?php echo $value_s; ?></td>
                                <?php } ?>
                            </tr>
                        <?php }
                    }
                } ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="charset_list_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><?php echo $this->lang['mod']['label']['charset']; ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <?php foreach ($this->tplData['charsetRows'] as $key=>$value) { ?>
                            <thead>
                                <tr>
                                    <th class="text-nowrap"><?php echo $value['title']; ?></th>
                                    <th class="text-nowrap"><?php echo $this->lang['mod']['label']['name']; ?></th>
                                    <th><?php echo $this->lang['mod']['label']['note']; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($value['list'] as $key_sub=>$value_sub) { ?>
                                    <tr>
                                        <td class="text-nowrap"><?php echo $key_sub; ?></td>
                                        <td class="text-nowrap">
                                            <?php echo $value_sub['title'];
                                            if (isset($value_sub['often'])) { ?><code>*</code><?php } ?>
                                        </td>
                                        <td><?php echo $value_sub['note']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        <?php } ?>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <?php echo $this->lang['common']['btn']['close']; ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

<?php include($cfg['pathInclude'] . 'console_foot.php'); ?>

    <script type="text/javascript">
    var opts_submit_convert = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>index.php?m=user&c=request",
        box: {
            selector: ".bg-submit-box-convert"
        },
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        }
    };

    var opts_submit_del = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>index.php?m=user&c=request",
        box: {
            selector: ".bg-submit-box-del"
        },
        selector: {
            submit_btn: ".bg-submit-del"
        },
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        }
    };

    function alert_process(_class, _icon, _msg) {
        $("#csv_upload .bg-alert").removeClass("alert-info alert-danger alert-success");
        $("#csv_upload .bg-alert").addClass(_class);
        $("#csv_upload .bg-alert i").removeClass("glyphicon-refresh glyphicon-circle-x glyphicon-circle-check glyphicon-upload bg-spin");
        $("#csv_upload .bg-alert i").addClass(_icon);
        $("#csv_upload .bg-alert span").html(_msg);
    }

    $(document).ready(function(){
        if (!WebUploader.Uploader.support()) {
            alert("<?php echo $this->lang['mod']['label']['needH5']; ?>");
        }

        var obj_wu = new WebUploader.Uploader({
            //附加表单数据
            formData: {
                <?php echo $this->common['tokenRow']['name_session']; ?>: "<?php echo $this->common['tokenRow']['token']; ?>",
                a: "import"
            },
            server: "<?php echo BG_URL_CONSOLE; ?>index.php?m=user&c=request", //文件接收服务端
            pick: {
                id: "#upload_select", //选择按钮
                multiple: false
            },
            auto: true, //自动上传
            fileVal: "csv_files", //设置 file 域的 name
            //允许的扩展名
            accept: {
                title: "file",
                extensions: "csv"
            },
            resize: false //不压缩 image
        });

        obj_wu.on("fileQueued", function(file){
            $("#csv_upload").html("<div class=\"alert alert-info alert-dismissible bg-alert\">" +
                "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" +
                "<h4>" + file.name + "</h4>" +
                "<p class=\"bg-alert-text\">" +
                    "<i class=\"oi oi-refresh bg-spin\"></i>" +
                    " <span></span>" +
                "</p>" +
            "</div>");
            "<div class=\"progress bg-progress\">" +
                "<div class=\"progress-bar progress-bar-info progress-bar-striped active\" style=\"width: 10%\"></div>"+
            "</div>" +
            $("#csv_upload .bg-progress").hide();
        });

        obj_wu.on("error", function(error, file){
            switch(error) {
                case "Q_TYPE_DENIED":
                    alert(file.name + " <?php echo $this->lang['rcode']['x010219']; ?>");
                break;
            }
        });

        obj_wu.on("uploadProgress", function(file, percentage){
            alert_process("alert-danger", "glyphicon-circle-x", "<?php echo $this->lang['mod']['label']['uploading']; ?>");

            $("#csv_upload .bg-progress").show();
            $("#csv_upload .bg-progress .progress-bar").text(percentage * 100 + "%");
            $("#csv_upload .bg-progress .progress-bar").css("width", percentage * 100 + "%");
        });

        obj_wu.on("uploadSuccess", function(file, result){
            var _str_msg;
            if (result.rcode == "y010403") {
                alert_process("alert-success", "glyphicon-circle-check", "<?php echo $this->lang['mod']['label']['uploadSucc']; ?>");
            } else {
                if (typeof result.msg == "undefined") {
                    _str_msg = "<?php echo $this->lang['mod']['label']['returnErr']; ?>";
                } else {
                    _str_msg = result.msg;
                }
                alert_process("alert-danger", "glyphicon-circle-x", _str_msg);
            }
        });

        obj_wu.on("uploadError", function(file, result){
            alert_process("alert-danger", "glyphicon-circle-x", "Error&nbsp;status:&nbsp;" + result);
        });

        obj_wu.on("uploadComplete", function(file){
            $("#csv_upload .bg-progress").slideUp("slow");

            setTimeout(function(){
                $("#csv_upload .bg-alert").slideUp("slow");
            }, 5000);
        });

        var obj_submit_convert = $("#csv_convert").baigoSubmit(opts_submit_convert);
        $(".bg-submit").click(function(){
            obj_submit_convert.formSubmit();
        });
        var obj_submit_del = $("#csv_del").baigoSubmit(opts_submit_del);
        $(".bg-submit-del").click(function(){
            obj_submit_del.formSubmit();
        });
        $("#md5_do").click(function(){
            var _src = $("#pass_src").val();
            $("#pass_result").val($.md5(_src));
        });
        $("#pass_src").bind("change keyup",function(){
            var _src = $(this).val();
            $("#pass_result").val($.md5(_src));
        });
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot.php');
