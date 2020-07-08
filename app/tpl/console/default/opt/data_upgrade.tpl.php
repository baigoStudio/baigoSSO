        <div class="table-responsive">
            <?php foreach ($config_upgrade as $key=>$value) { ?>
                <table class="table mb-5">
                    <thead>
                        <tr>
                            <th class="border-top-0"><?php echo $lang->get($value['title']); ?></th>
                            <th class="border-top-0 text-right"><?php echo $lang->get('Status'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($value['lists'] as $key_data=>$value_data) { ?>
                            <tr>
                                <td>
                                    <?php echo $value_data; ?>
                                </td>
                                <td id="<?php echo $key; ?>_<?php echo $value_data; ?>" class="text-right text-nowrap">
                                    <div class="text-info">
                                        <span class="spinner-grow spinner-grow-sm"></span>
                                        <small>
                                            <?php echo $lang->get('Submitting'); ?>
                                        </small>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>

    <script type="text/javascript">
    $(document).ready(function(){
        <?php foreach ($config_upgrade as $key=>$value) {
            foreach ($value['lists'] as $key_data=>$value_data) { ?>
                $.ajax({
                    url: '<?php echo $route_console; ?>opt/data-submit/?' + new Date().getTime() + 'at' + Math.random(), //url
                    //async: false, //设置为同步
                    type: 'post',
                    dataType: 'json',
                    data: {
                        type: '<?php echo $key; ?>',
                        model: '<?php echo $value_data; ?>',
                        <?php echo $token['name']; ?>: '<?php echo $token['value']; ?>'
                    },
                    timeout: 30000,
                    error: function (result) {
                        $('#<?php echo $key; ?>_<?php echo $value_data; ?> div').attr('class', 'text-danger');
                        $('#<?php echo $key; ?>_<?php echo $value_data; ?> span').attr('class', 'fas fa-times-circle');
                        $('#<?php echo $key; ?>_<?php echo $value_data; ?> small').text(result.statusText);
                    },
                    success: function(_result){ //读取返回结果
                        _rcode_status  = _result.rcode.substr(0, 1);

                        switch (_rcode_status) {
                            case 'y':
                                _class  = 'text-success';
                                _icon   = 'fas fa-check-circle';
                            break;

                            default:
                                _class  = 'text-danger';
                                _icon   = 'fas fa-times-circle';
                            break;
                        }

                        $('#<?php echo $key; ?>_<?php echo $value_data; ?> div').attr('class', _class);
                        $('#<?php echo $key; ?>_<?php echo $value_data; ?> span').attr('class', _icon);
                        $('#<?php echo $key; ?>_<?php echo $value_data; ?> small').text(_result.msg);
                    }
                });
            <?php }
        } ?>
    });
    </script>
