<?php $cfg = array(
    'title'             => $lang->get('User management', 'console.common') . ' &raquo; ' . $lang->get('Import', 'console.common'),
    'menu_active'       => 'user',
    'sub_active'        => 'import',
    'baigoSubmit'       => 'true',
    'baigoDialog'       => 'true',
    'upload'            => 'true',
    'md5'               => 'true',
    'pathInclude'       => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'console_head' . GK_EXT_TPL); ?>

    <div class="card-group mb-5">
        <div class="card">
            <div class="card-body">
                <?php if (!empty($csvRows)) { ?>
                    <form name="form_convert" id="form_convert" class="mb-3" action="<?php echo $route_console; ?>import/submit/">
                        <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">
                        <input type="hidden" name="charset" value="<?php echo $charset; ?>">

                        <table>
                            <thead>
                                <tr>
                                    <th class="p-2"><?php echo $lang->get('Source'); ?></th>
                                    <th class="p-2">&nbsp;</th>
                                    <th class="p-2"><?php echo $lang->get('Import as'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($csvRows[0]) && !empty($csvRows[0])) {
                                    foreach ($csvRows[0] as $key=>$value) {
                                        if (!empty($value)) { ?>
                                            <tr>
                                                <td class="p-2"><?php echo $value; ?></td>
                                                <td class="p-2">
                                                    <span class="fas fa-arrow-right"></span>
                                                </td>
                                                <td class="p-2">
                                                    <select name="user_convert[<?php echo $key; ?>]" class="form-control">
                                                        <option <?php if ($key < 1) { ?>selected<?php } ?> value="user_name"><?php echo $lang->get('Username'); ?></option>
                                                        <option <?php if ($key == 1) { ?>selected<?php } ?> value="user_pass"><?php echo $lang->get('Password'); ?></option>
                                                        <option <?php if ($key == 2) { ?>selected<?php } ?> value="user_nick"><?php echo $lang->get('Nickname'); ?></option>
                                                        <option <?php if ($key > 2) { ?>selected<?php } ?> value="ignore"><?php echo $lang->get('Ignore'); ?></option>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php }
                                    }
                                } ?>
                            </tbody>
                        </table>

                        <button type="submit" class="btn btn-primary"><?php echo $lang->get('Import'); ?></button>
                    </form>
                <?php } ?>
            </div>
        </div>

        <div class="card bg-light">
            <div class="card-body">
                <div class="alert alert-warning">
                    <span class="fas fa-exclamation-triangle"></span>
                    <?php echo $lang->get('The first line of the CSV file must be a field name. It is recommended to use three columns. The password column must be encrypted with MD5. For the encryption tool, please see the next item. After uploading the CSV file, please refresh this page to preview, click here <a href="javascript:location.reload();" class="alert-link">Refresh</a>. After the import is successful, it is highly recommended to delete the CSV file.'); ?>
                </div>

                <form name="form_upload" id="form_upload">
                    <div class="form-group">
                        <button type="button" id="upload_select" class="btn btn-success text-white fileinput-button">
                            <span class="fas fa-cloud-upload-alt"></span>
                            <?php echo $lang->get('Upload CSV file'); ?>
                        </button>
                    </div>

                    <div id="csv_upload"></div>
                </form>
            </div>

            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <form name="form_delete" id="form_delete" action="<?php echo $route_console; ?>import/delete/">
                        <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">
                                <span class="fas fa-trash-alt"></span>
                                <?php echo $lang->get('Delete CSV file'); ?>
                            </button>
                        </div>
                    </form>
                </li>
                <li class="list-group-item">
                    <div class="form-group">
                        <label><?php echo $lang->get('MD5 Encryption tool'); ?></label>
                        <input type="text" id="pass_src" class="form-control" placeholder="<?php echo $lang->get('Password'); ?>">
                    </div>
                    <div class="form-group">
                        <label><?php echo $lang->get('Encryption result'); ?></label>
                        <input type="text" id="pass_result" class="form-control">
                    </div>
                    <div class="form-group">
                        <button type="button" id="md5_do" class="btn btn-primary">
                            <span class="fas fa-lock"></span>
                            <?php echo $lang->get('Encryption'); ?>
                        </button>
                    </div>
                </li>
                <li class="list-group-item">
                    <form id="form_preview" name="form_preview" action="<?php echo $route_console; ?>import/index/">
                        <div class="form-group">
                            <label><?php echo $lang->get('CSV file charset encoding'); ?></label>
                            <div class="input-group">
                                <input type="text" name="charset" id="charset" value="<?php echo $charset; ?>" class="form-control" placeholder="UTF-8">
                                <select id="charset_opt" class="custom-select">
                                    <option value=""><?php echo $lang->get('Common charset'); ?></option>
                                    <?php foreach ($charsetRows as $key=>$value) { ?>
                                        <optgroup label="<?php echo $lang->get($value['title'], 'console.charset'); ?>">
                                            <?php foreach ($value['lists'] as $key_sub=>$value_sub) { ?>
                                                <option <?php if ($charset == $key_sub) { ?>selected<?php } ?> value="<?php echo $key_sub; ?>">
                                                    <?php echo $lang->get($key_sub), ' - ', $lang->get($value_sub['title'], 'console.charset'); ?>
                                                </option>
                                            <?php } ?>
                                        </optgroup>
                                    <?php } ?>
                                </select>
                                <span class="input-group-append">
                                    <a href="#charset_list_modal" class="btn btn-warning" data-toggle="modal">
                                        <span class="fas fa-question-circle"></span>
                                    </a>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary"><?php echo $lang->get('Apply'); ?></button>
                                <a class="btn btn-outline-secondary" href="#charset_list_modal" data-toggle="modal">
                                    <?php echo $lang->get('Charset encoding help'); ?>
                                </a>
                            </div>
                        </div>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <h4><?php echo $lang->get('Preview'); ?></h4>
    <table class="table table-striped table-hover table-bordered">
        <thead>
            <tr>
                <?php if (isset($csvRows[0]) && !empty($csvRows[0])) {
                    foreach ($csvRows[0] as $key=>$value) { ?>
                        <th><?php echo $value; ?></th>
                    <?php }
                } ?>
            </tr>
        </thead>
        <tbody id="preview_table">
            <?php if (isset($csvRows) && !empty($csvRows)) {
                foreach ($csvRows as $key=>$value) {
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

    <div id="preview_loading"></div>

    <button class="btn btn-outline-secondary" id="preview_all">
        <span class="fas fa-plus"></span>
        <?php echo $lang->get('More'); ?>
    </button>

    <div class="modal fade" id="charset_list_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            </div>
        </div>
    </div>


<?php include($cfg['pathInclude'] . 'console_foot' . GK_EXT_TPL); ?>

    <script type="text/javascript">
    var opts_dialog = {
        btn_text: {
            cancel: '<?php echo $lang->get('Cancel'); ?>',
            confirm: '<?php echo $lang->get('Confirm'); ?>',
            ok: '<?php echo $lang->get('OK'); ?>'
        }
    };

    var opts_submit = {
        modal: {
            btn_text: {
                close: '<?php echo $lang->get('Close'); ?>',
                ok: '<?php echo $lang->get('OK'); ?>'
            }
        },
        msg_text: {
            submitting: '<?php echo $lang->get('Submitting'); ?>'
        }
    };

    function previewProcess() {
        $('#preview_loading').html('<div class="alert alert-info">' +
            '<span class="spinner-grow spinner-grow-sm mr-3"></span>' +
            '<span class="mr-3"><?php echo $lang->get('Loading'); ?></span>' +
            '<span class="bg-loaded"></span>' +
        '</div>');
    }

    function queuedProcess(_class, _icon, _msg) {
        $('#csv_upload .bg-alert').removeClass('alert-info alert-danger alert-success');
        $('#csv_upload .bg-alert').addClass(_class);
        $('#csv_upload .bg-alert .bg-icon').removeClass('spinner-grow spinner-grow-sm fas fa-times-circle fa-check-circle fa-cloud-upload-alt');
        $('#csv_upload .bg-alert .bg-icon').addClass(_icon);
        $('#csv_upload .bg-alert .bg-msg').html(_msg);
    }

    function sizeFormat(size) {
        var _units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for (var _i = 0; size >= 1024 && _i < 4; _i++) {
            size /= 1024
        };

        return size.toFixed(2) + ' ' + _units[_i];
    }

    $(document).ready(function(){
        $('#charset_list_modal').on('shown.bs.modal',function(event){
            $('#charset_list_modal .modal-content').load('<?php echo $route_console; ?>import/charset/');
    	}).on('hidden.bs.modal', function(){
        	$('#charset_list_modal .modal-content').empty();
    	});

        var obj_dialog = $.baigoDialog(opts_dialog);

    	$('#preview_all').click(function(){
            $.ajax({
                url: '<?php echo $route_console; ?>import/preview/charset/<?php echo $charset; ?>/?' + new Date().getTime() + 'at' + Math.random(),
                dataType: 'html',
                beforeSend: function(){
                    previewProcess();
                },
                xhr: function() { //ajax进度条
                    var xhr = $.ajaxSettings.xhr();

                    xhr.onprogress = function(evt){
                        //console.log(evt.loaded);
                        $('#preview_loading .bg-loaded').text(sizeFormat(evt.loaded));
                    };
                    return xhr;
                },
                success: function(_result){
                    $('#preview_table').html(_result);
                    $('#preview_loading').hide();
                }
            });
    	});

        if (!WebUploader.Uploader.support()) {
            obj_dialog.alert('<?php echo $lang->get('Uploading requires HTML5 support, please upgrade your browser'); ?>');
        }

        var obj_wu = new WebUploader.Uploader({
            //附加表单数据
            formData: {
                <?php echo $token['name']; ?>: '<?php echo $token['value']; ?>'
            },
            server: '<?php echo $route_console; ?>import/upload/',
            pick: {
                id: '#upload_select', //选择按钮
                multiple: false
            },
            auto: true, //自动上传
            fileVal: 'csv_files', //设置 file 域的 name
            //允许的扩展名
            accept: {
                title: 'file',
                extensions: 'csv'
            },
            resize: false //不压缩 image
        });

        obj_wu.on('fileQueued', function(file){
            $('#csv_upload').html('<div class="alert alert-info bg-alert">' +
                '<h4>' + file.name + '</h4>' +
                '<p class="bg-content">' +
                    '<span class="spinner-grow spinner-grow-sm mr-3"></span>' +
                    '<span class="bg-msg"><?php echo $lang->get('Submitting'); ?></span>' +
                '</p>' +
            '</div>' +
            '<div class="progress bg-progress">' +
                '<div class="progress-bar progress-bar-striped progress-bar-animated bg-info" style="width: 10%"></div>'+
            '</div>');

            $('#csv_upload .bg-progress').hide();
        });

        obj_wu.on('error', function(error, file){
            switch(error) {
                case 'Q_TYPE_DENIED':
                    obj_dialog.alert('<?php echo $lang->get('Upload CSV files only'); ?> "' + file.name + '"');
                break;
            }
        });

        obj_wu.on('uploadProgress', function(file, percentage){
            queuedProcess('alert-danger', 'spinner-grow spinner-grow-sm', '<?php echo $lang->get('Submitting'); ?>');

            $('#csv_upload .bg-progress').show();
            $('#csv_upload .bg-progress .progress-bar').text(percentage * 100 + '%');
            $('#csv_upload .bg-progress .progress-bar').css('width', percentage * 100 + '%');
        });

        obj_wu.on('uploadSuccess', function(file, result){
            var _str_msg;
            if (result.rcode == 'y010403') {
                queuedProcess('alert-success', 'fas fa-check-circle', '<?php echo $lang->get('CSV file uploaded successfully'); ?>');
            } else {
                if (typeof result.msg == 'undefined') {
                    _str_msg = '<?php echo $lang->get('Server side error'); ?>';
                } else {
                    _str_msg = result.msg;
                }
                queuedProcess('alert-danger', 'fas fa-times-circle', _str_msg);
            }
        });

        obj_wu.on('uploadError', function(file, result){
            queuedProcess('alert-danger', 'fas fa-times-circle', 'Error&nbsp;status:&nbsp;' + result);
        });

        obj_wu.on('uploadComplete', function(file){
            $('#csv_upload .bg-progress').slideUp('slow');

            setTimeout(function(){
                $('#csv_upload .bg-alert').slideUp('slow');
            }, 5000);
        });

        var obj_submit_convert = $('#form_convert').baigoSubmit(opts_submit);
        $('#form_convert').submit(function(){
            obj_submit_convert.formSubmit();
        });

        var obj_submit_del  = $('#form_delete').baigoSubmit(opts_submit);
        $('#form_delete').submit(function(){
            obj_dialog.confirm('<?php echo $lang->get('Are you sure to delete?'); ?>', function(result){
                if (result) {
                    obj_submit_del.formSubmit();
                }
            });
        });

        $('#md5_do').click(function(){
            var _src = $('#pass_src').val();
            $('#pass_result').val($.md5(_src));
        });

        $('#pass_src').bind('change keyup',function(){
            var _src = $(this).val();
            $('#pass_result').val($.md5(_src));
        });

        $('#charset_opt').change(function(){
            var _charset_val = $(this).val();
            $('#charset').val(_charset_val);
        });
    });
    </script>
<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);