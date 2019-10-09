
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
