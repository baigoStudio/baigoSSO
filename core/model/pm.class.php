<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

/*-------------短消息模型-------------*/
class MODEL_PM {

    public $obj_db;
    public $pmInput;
    public $pmIds;
    public $arr_status  = array('wait', 'read'); //状态
    public $arr_type    = array('out', 'in'); //类型

    function __construct() { //构造函数
        $this->obj_db = $GLOBALS['obj_db']; //设置数据库对象
    }


    /** 创建表
     * mdl_create function.
     *
     * @access public
     * @return void
     */
    function mdl_create_table() {
        $_str_status    = implode('\',\'', $this->arr_status);
        $_str_type      = implode('\',\'', $this->arr_type);

        $_arr_pmCreate = array(
            'pm_id'         => 'int NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'pm_send_id'    => 'int NOT NULL COMMENT \'发出 ID\'', //已发送短信的目标 ID
            'pm_to'         => 'int NOT NULL COMMENT \'收件用户 ID\'',
            'pm_from'       => 'int NOT NULL COMMENT \'发件用户 ID\'',
            'pm_title'      => 'varchar(90) NOT NULL COMMENT \'标题\'',
            'pm_content'    => 'varchar(900) NOT NULL COMMENT \'随机串\'',
            'pm_status'     => 'enum(\'' . $_str_status . '\') NOT NULL COMMENT \'状态\'',
            'pm_type'       => 'enum(\'' . $_str_type . '\') NOT NULL COMMENT \'类型\'',
            'pm_time'       => 'int NOT NULL COMMENT \'创建时间\'',
        );

        $_num_db = $this->obj_db->create_table(BG_DB_TABLE . 'pm', $_arr_pmCreate, 'pm_id', '短消息');

        if ($_num_db > 0) {
            $_str_rcode = 'y110105'; //更新成功
        } else {
            $_str_rcode = 'x110105'; //更新成功
        }

        return array(
            'rcode' => $_str_rcode, //更新成功
        );
    }


    /** 列出字段
     * mdl_column function.
     *
     * @access public
     * @return void
     */
    function mdl_column() {
        $_arr_colRows = $this->obj_db->show_columns(BG_DB_TABLE . 'pm');

        $_arr_col = array();

        if (!fn_isEmpty($_arr_colRows)) {
            foreach ($_arr_colRows as $_key=>$_value) {
                $_arr_col[] = $_value['Field'];
            }
        }

        return $_arr_col;
    }


    /** 短消息创建、编辑提交
     * mdl_submit function.
     *
     * @access public
     * @param mixed $num_pmTo
     * @param mixed $num_pmFrom
     * @return void
     */
    function mdl_submit($num_pmTo, $num_pmFrom) {
        $_arr_pmData = array(
            'pm_to'        => $num_pmTo,
            'pm_from'      => $num_pmFrom,
            'pm_title'     => $this->pmInput['pm_title'],
            'pm_content'   => $this->pmInput['pm_content'],
            'pm_type'      => 'in',
            'pm_status'    => 'wait',
            'pm_time'      => time(),
        );

        $_num_pmId = $this->obj_db->insert(BG_DB_TABLE . 'pm', $_arr_pmData); //更新数据
        if ($_num_pmId > 0) {
            $_str_rcode = 'y110101'; //更新成功
        } else {
            return array(
                'rcode' => 'x110101', //更新失败
            );
        }

        if ($num_pmFrom > 1) { //如果为非系统消息，在发件箱保存副本
            $_arr_pmData['pm_send_id']  = $_num_pmId;
            $_arr_pmData['pm_type']     = 'out';
            $_arr_pmData['pm_status']   = 'read';
            $this->obj_db->insert(BG_DB_TABLE . 'pm', $_arr_pmData); //更新数据
        }

        return array(
            'pm_id' => $_num_pmId,
            'rcode' => $_str_rcode, //成功
        );
    }


    /** 编辑状态
     * mdl_status function.
     *
     * @access public
     * @param mixed $str_status
     * @return void
     */
    function mdl_status($str_status, $num_userId = 0) {
        $_str_pmIds      = implode(',', $this->pmIds['pm_ids']);
        $_str_sqlWhere  = '`pm_id` IN (' . $_str_pmIds . ')';

        $_arr_pmUpdate = array(
            'pm_status' => $str_status,
        );

        if ($num_userId > 0) {
            $_str_sqlWhere .= ' AND `pm_to`=' . $num_userId . ' AND `pm_type`=\'in\'';
        }

        $_num_db = $this->obj_db->update(BG_DB_TABLE . 'pm', $_arr_pmUpdate, $_str_sqlWhere); //删除数据

        //如影响行数大于0则返回成功
        if ($_num_db > 0) {
            $_str_rcode = 'y110103'; //成功
        } else {
            $_str_rcode = 'x110103'; //失败
        }

        return array(
            'rcode' => $_str_rcode,
        );
    }


    /** 读取
     * mdl_read function.
     *
     * @access public
     * @param mixed $str_pm
     * @param string $str_by (default: 'pm_id')
     * @param int $num_notId (default: 0)
     * @return void
     */
    function mdl_read($str_pm, $str_by = 'pm_id', $num_notId = 0) {
        $_arr_pmSelect = array(
            'pm_id',
            'pm_send_id',
            'pm_to',
            'pm_from',
            'pm_title',
            'pm_content',
            'pm_type',
            'pm_status',
            'pm_time',
        );

        if (is_numeric($str_pm)) {
            $_str_sqlWhere = $str_by . '=' . $str_pm;
        } else {
            $_str_sqlWhere = $str_by . '=\'' . $str_pm . '\'';
        }

        if ($num_notId > 0) {
            $_str_sqlWhere .= ' AND `pm_id`<>' . $num_notId;
        }

        $_arr_pmRows = $this->obj_db->select(BG_DB_TABLE . 'pm', $_arr_pmSelect, $_str_sqlWhere, '', '', 1, 0); //检查本地表是否存在记录

        if (isset($_arr_pmRows[0])) { //用户名不存在则返回错误
            $_arr_pmRow = $_arr_pmRows[0];
        } else {
            return array(
                'rcode' => 'x110102', //不存在记录
            );
        }

        $_arr_pmRow['rcode']   = 'y110102';

        return $_arr_pmRow;

    }


    /** 列出
     * mdl_list function.
     *
     * @access public
     * @param mixed $num_no
     * @param int $num_except (default: 0)
     * @param array $arr_search (default: array())
     * @return void
     */
    function mdl_list($num_no, $num_except = 0, $arr_search = array()) {
        $_arr_pmSelect = array(
            'pm_id',
            'pm_send_id',
            'pm_to',
            'pm_from',
            'pm_title',
            'pm_type',
            'pm_status',
            'pm_time',
        );

        $_str_sqlWhere = $this->sql_process($arr_search);

        $_arr_order = array(
            array('pm_id', 'DESC'),
        );

        $_arr_pmRows = $this->obj_db->select(BG_DB_TABLE . 'pm', $_arr_pmSelect, $_str_sqlWhere, '', $_arr_order, $num_no, $num_except); //查询数据

        return $_arr_pmRows;
    }


    /** 计数
     * mdl_count function.
     *
     * @access public
     * @param array $arr_search (default: array())
     * @return void
     */
    function mdl_count($arr_search = array()) {
        $_str_sqlWhere = $this->sql_process($arr_search);

        $_num_pmCount = $this->obj_db->count(BG_DB_TABLE . 'pm', $_str_sqlWhere); //查询数据

        return $_num_pmCount;
    }


    /** 删除
     * mdl_del function.
     *
     * @access public
     * @return void
     */
    function mdl_del($num_userId = 0, $is_revoke = false) {
        $_str_pmIds     = implode(',', $this->pmIds['pm_ids']);
        $_str_sqlWhere  = '`pm_id` IN (' . $_str_pmIds . ')';

        if ($num_userId > 0) {
            if ($is_revoke) {
                $_str_sqlWhere .= ' AND `pm_from`=' . $num_userId . ' AND `pm_type`=\'in\' AND `pm_status`=\'wait\'';
            } else {
                $_str_sqlWhere .= ' AND ((`pm_from`=' . $num_userId . ' AND `pm_type`=\'out\') OR (`pm_to`=' . $num_userId . ' AND `pm_type`=\'in\'))';
            }
        }

        //print_r($_str_sqlWhere);

        $_num_db = $this->obj_db->delete(BG_DB_TABLE . 'pm', $_str_sqlWhere); //删除数据

        //如车影响行数小于0则返回错误
        if ($_num_db > 0) {
            if ($is_revoke) {
                $_str_rcode = 'y110109'; //撤回
            } else {
                $_str_rcode = 'y110104'; //成功
            }
        } else {
            if ($is_revoke) {
                $_str_rcode = 'x110109'; //撤回
            } else {
                $_str_rcode = 'x110104'; //失败
            }
        }

        return array(
            'rcode' => $_str_rcode,
        );
    }


    /** 创建、编辑表单验证
     * input_submit function.
     *
     * @access public
     * @return void
     */
    function input_bulk() {
        if (!fn_token('chk')) { //令牌
            return array(
                'rcode' => 'x030206',
            );
        }

        $_arr_pmTitle = fn_validate(fn_post('pm_title'), 0, 90);
        switch ($_arr_pmTitle['status']) {
            case 'too_long':
                return array(
                    'rcode' => 'x110202',
                );
            break;

            case 'ok':
                $this->pmInput['pm_title'] = $_arr_pmTitle['str'];
            break;
        }

        $_arr_pmContent = fn_validate(fn_post('pm_content'), 1, 900);
        switch ($_arr_pmContent['status']) {
            case 'too_long':
                return array(
                    'rcode' => 'x110203',
                );
            break;

            case 'ok':
                $this->pmInput['pm_content'] = $_arr_pmContent['str'];
            break;
        }

        if (fn_isEmpty($this->pmInput['pm_title'])) {
            $this->pmInput['pm_title'] = fn_substr_utf8($this->pmInput['pm_content'], 0, 30);
        }

        $_arr_pmBulkType = fn_validate(fn_post('pm_bulk_type'), 1, 0);
        switch ($_arr_pmBulkType['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x110204',
                );
            break;

            case 'ok':
                $this->pmInput['pm_bulk_type'] = $_arr_pmBulkType['str'];
            break;
        }

        switch ($this->pmInput['pm_bulk_type']) {
            case 'bulkUsers':
                $_arr_pmToUsers = fn_validate(fn_post('pm_to_users'), 1, 0);
                switch ($_arr_pmToUsers['status']) {
                    case 'too_short':
                        return array(
                            'rcode' => 'x110205',
                        );
                    break;

                    case 'ok':
                        $this->pmInput['pm_to_users'] = $_arr_pmToUsers['str'];
                    break;
                }
            break;

            case 'bulkKeyName':
                $_arr_pmKeyName = fn_validate(fn_post('pm_to_key_name'), 1, 0);
                switch ($_arr_pmKeyName['status']) {
                    case 'too_short':
                        return array(
                            'rcode' => 'x110206',
                        );
                    break;

                    case 'ok':
                        $this->pmInput['pm_to_key_name'] = $_arr_pmKeyName['str'];
                    break;
                }
            break;

            case 'bulkKeyMail':
                $_arr_pmKeyMail = fn_validate(fn_post('pm_to_key_mail'), 1, 0);
                switch ($_arr_pmKeyMail['status']) {
                    case 'too_short':
                        return array(
                            'rcode' => 'x110207',
                        );
                    break;

                    case 'ok':
                        $this->pmInput['pm_to_key_mail'] = $_arr_pmKeyMail['str'];
                    break;
                }
            break;

            case 'bulkRangeId':
                $_arr_pmBeginId = fn_validate(fn_post('pm_to_min_id'), 1, 0, 'str', 'int');
                switch ($_arr_pmBeginId['status']) {
                    case 'too_short':
                        return array(
                            'rcode' => 'x110208',
                        );
                    break;

                    case 'format_err':
                        return array(
                            'rcode' => 'x110209',
                        );
                    break;

                    case 'ok':
                        $this->pmInput['pm_to_min_id'] = $_arr_pmBeginId['str'];
                    break;
                }

                $_arr_pmEndId = fn_validate(fn_post('pm_to_max_id'), 1, 0, 'str', 'int');
                switch ($_arr_pmEndId['status']) {
                    case 'too_short':
                        return array(
                            'rcode' => 'x110210',
                        );
                    break;

                    case 'format_err':
                        return array(
                            'rcode' => 'x110209',
                        );
                    break;

                    case 'ok':
                        $this->pmInput['pm_to_max_id'] = $_arr_pmEndId['str'];
                    break;
                }
            break;

            case 'bulkRangeTime':
                $_arr_pmBeginTime = fn_validate(fn_post('pm_to_begin_time'), 1, 0, 'str', 'datetime');
                switch ($_arr_pmBeginTime['status']) {
                    case 'too_short':
                        return array(
                            'rcode' => 'x110212',
                        );
                    break;

                    case 'format_err':
                        return array(
                            'rcode' => 'x110213',
                        );
                    break;

                    case 'ok':
                        $this->pmInput['pm_to_begin_time'] = fn_strtotime($_arr_pmBeginTime['str']);
                    break;
                }

                $_arr_pmEndTime = fn_validate(fn_post('pm_to_end_time'), 1, 0, 'str', 'datetime');
                switch ($_arr_pmEndTime['status']) {
                    case 'too_short':
                        return array(
                            'rcode' => 'x110214',
                        );
                    break;

                    case 'format_err':
                        return array(
                            'rcode' => 'x110213',
                        );
                    break;

                    case 'ok':
                        $this->pmInput['pm_to_end_time'] = fn_strtotime($_arr_pmEndTime['str']);
                    break;
                }
            break;

            case 'bulkRangeLogin':
                $_arr_pmBeginLogin = fn_validate(fn_post('pm_to_begin_login'), 1, 0, 'str', 'datetime');
                switch ($_arr_pmBeginLogin['status']) {
                    case 'too_short':
                        return array(
                            'rcode' => 'x110215',
                        );
                    break;

                    case 'format_err':
                        return array(
                            'rcode' => 'x110216',
                        );
                    break;

                    case 'ok':
                        $this->pmInput['pm_to_begin_login'] = fn_strtotime($_arr_pmBeginLogin['str']);
                    break;
                }

                $_arr_pmEndLogin = fn_validate(fn_post('pm_to_end_login'), 1, 0, 'str', 'datetime');
                switch ($_arr_pmEndLogin['status']) {
                    case 'too_short':
                        return array(
                            'rcode' => 'x110217',
                        );
                    break;

                    case 'format_err':
                        return array(
                            'rcode' => 'x110216',
                        );
                    break;

                    case 'ok':
                        $this->pmInput['pm_to_end_login'] = fn_strtotime($_arr_pmEndLogin['str']);
                    break;
                }
            break;
        }

        $this->pmInput['rcode'] = 'ok';

        return $this->pmInput;
    }


    /** 发送表单验证
     * input_send function.
     *
     * @access public
     * @return void
     */
    function input_send() {
        if (!fn_token('chk')) { //令牌
            return array(
                'rcode' => 'x030206',
            );
        }

        $_arr_pmTitle = fn_validate(fn_post('pm_title'), 0, 90);
        switch ($_arr_pmTitle['status']) {
            case 'too_long':
                return array(
                    'rcode' => 'x110202',
                );
            break;

            case 'ok':
                $this->pmInput['pm_title'] = $_arr_pmTitle['str'];
            break;
        }

        $_arr_pmContent = fn_validate(fn_post('pm_content'), 1, 900);
        switch ($_arr_pmContent['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x110201',
                );
            break;

            case 'too_long':
                return array(
                    'rcode' => 'x110203',
                );
            break;

            case 'ok':
                $this->pmInput['pm_content'] = $_arr_pmContent['str'];
            break;
        }

        if (fn_isEmpty($this->pmInput['pm_title'])) {
            $this->pmInput['pm_title'] = fn_substr_utf8($this->pmInput['pm_content'], 0, 30);
        }

        $_arr_pmTo = fn_validate(fn_post('pm_to'), 1, 0);
        switch ($_arr_pmTo['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x110205',
                );
            break;

            case 'ok':
                $this->pmInput['pm_to'] = $_arr_pmTo['str'];
            break;
        }

        $this->pmInput['rcode']    = 'ok';

        return $this->pmInput;
    }


    /** 选择短消息
     * input_ids function.
     *
     * @access public
     * @return void
     */
    function input_ids() {
        if (!fn_token('chk')) { //令牌
            return array(
                'rcode' => 'x030206',
            );
        }

        $_arr_pmIds = fn_post('pm_ids');

        if (fn_isEmpty($_arr_pmIds)) {
            $_str_rcode = 'x030202';
        } else {
            foreach ($_arr_pmIds as $_key=>$_value) {
                $_arr_pmIds[$_key] = fn_getSafe($_value, 'int', 0);
            }
            $_str_rcode = 'ok';
        }

        $this->pmIds = array(
            'rcode'     => $_str_rcode,
            'pm_ids'    => array_filter(array_unique($_arr_pmIds)),
        );

        return $this->pmIds;
    }


    function input_ids_api() {
        $_str_pmIds = fn_getSafe(fn_post('pm_ids'), 'txt', '');
        $_arr_pmIds = array();

        if (fn_isEmpty($_str_pmIds)) {
            return array(
                'rcode' => 'x110211',
            );
        } else {
            if (stristr($_str_pmIds, '|')) {
                $_arr_pmIds = explode('|', $_str_pmIds);
            } else {
                $_arr_pmIds = array($_str_pmIds);
            }
        }

        if (fn_isEmpty($_arr_pmIds)) {
            $_str_rcode = 'x110211';
        } else {
            foreach ($_arr_pmIds as $_key=>$_value) {
                $_arr_pmIds[$_key] = fn_getSafe($_value, 'int', 0);
            }
            $_str_rcode = 'ok';
        }

        $this->pmIds = array(
            'rcode'     => 'ok',
            'str_pmIds' => $_str_pmIds,
            'pm_ids'    => array_filter(array_unique($_arr_pmIds)),
        );

        return $this->pmIds;
    }

    /** 列出及统计 SQL 处理
     * sql_process function.
     *
     * @access private
     * @param array $arr_search (default: array())
     * @return void
     */
    private function sql_process($arr_search = array()) {
        $_str_sqlWhere = '1';

        if (isset($arr_search['key']) && !fn_isEmpty($arr_search['key'])) {
            $_str_sqlWhere .= ' AND (`pm_title` LIKE \'%' . $arr_search['key'] . '%\' OR `pm_content` LIKE \'%' . $arr_search['key'] . '%\')';
        }

        if (isset($arr_search['status']) && !fn_isEmpty($arr_search['status'])) {
            $_str_sqlWhere .= ' AND `pm_status`=\'' . $arr_search['status'] . '\'';
        }

        if (isset($arr_search['type']) && !fn_isEmpty($arr_search['type'])) {
            $_str_sqlWhere .= ' AND `pm_type`=\'' . $arr_search['type'] . '\'';
        }

        if (isset($arr_search['pm_from']) && $arr_search['pm_from'] > 0) {
            $_str_sqlWhere .= ' AND `pm_from`=' . $arr_search['pm_from'];
        }

        if (isset($arr_search['pm_to']) && $arr_search['pm_to'] > 0) {
            $_str_sqlWhere .= ' AND `pm_to`=' . $arr_search['pm_to'];
        }

        if (isset($arr_search['pm_ids']) && !fn_isEmpty($arr_search['pm_ids'])) {
            $_str_pmIds      = implode(',', $arr_search['pm_ids']);
            $_str_sqlWhere .= ' AND `pm_in` (' . $_str_pmIds . ')';
        }

        return $_str_sqlWhere;
    }
}
