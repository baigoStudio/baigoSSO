<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

/*-------------管理员模型-------------*/
class MODEL_ADMIN {

    public $obj_db;
    public $adminInput;
    public $loginInput;
    public $adminIds;
    public $arr_status  = array('enable', 'disable'); //状态
    public $arr_type    = array('normal', 'super'); //类型

    function __construct() { //构造函数
        $this->obj_db = $GLOBALS['obj_db']; //设置数据库对象
    }


    /** 创建表 在安装或升级时调用
     * mdl_create function.
     *
     * @access public
     * @return void
     */
    function mdl_create_table() {
        $_str_status    = implode('\',\'', $this->arr_status);
        $_str_type      = implode('\',\'', $this->arr_type);

        $_arr_adminCreate = array(
            'admin_id'          => 'int NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'admin_name'        => 'varchar(30) NOT NULL COMMENT \'用户名\'',
            'admin_note'        => 'varchar(30) NOT NULL COMMENT \'备注\'',
            'admin_nick'        => 'varchar(30) NOT NULL COMMENT \'昵称\'',
            'admin_status'      => 'enum(\'' . $_str_status . '\') NOT NULL COMMENT \'状态\'',
            'admin_type'        => 'enum(\'' . $_str_type . '\') NOT NULL COMMENT \'类型\'',
            'admin_allow'       => 'varchar(3000) NOT NULL COMMENT \'权限\'',
            'admin_time'        => 'int NOT NULL COMMENT \'注册时间\'',
        );

        $_num_db = $this->obj_db->create_table(BG_DB_TABLE . 'admin', $_arr_adminCreate, 'admin_id', '管理员');

        if ($_num_db > 0) {
            $_str_rcode = 'y020105'; //更新成功
        } else {
            $_str_rcode = 'x020105'; //更新成功
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
        $_arr_colRows = $this->obj_db->show_columns(BG_DB_TABLE . 'admin');

        $_arr_col = array();

        if (!fn_isEmpty($_arr_colRows)) {
            foreach ($_arr_colRows as $_key=>$_value) {
                $_arr_col[] = $_value['Field'];
            }
        }

        return $_arr_col;
    }


    /** 修改表 升级时调用
     * mdl_alter_table function.
     *
     * @access public
     * @return void
     */
    function mdl_alter_table() {
        $_str_status    = implode('\',\'', $this->arr_status);
        $_str_type     = implode('\',\'', $this->arr_type);

        $_arr_col     = $this->mdl_column();
        $_arr_alter   = array();

        if (!in_array('admin_nick', $_arr_col)) {
            $_arr_alter['admin_nick'] = array('ADD', 'varchar(30) NOT NULL COMMENT \'昵称\'');
        }

        if (in_array('admin_id', $_arr_col)) {
            $_arr_alter['admin_id'] = array('CHANGE', 'int NOT NULL AUTO_INCREMENT COMMENT \'ID\'', 'admin_id');
        }

        if (in_array('admin_status', $_arr_col)) {
            $_arr_alter['admin_status'] = array('CHANGE', 'enum(\'' . $_str_status . '\') NOT NULL COMMENT \'状态\'', 'admin_status');
        }

        if (!in_array('admin_type', $_arr_col)) {
            $_arr_alter['admin_type'] = array('ADD', 'enum(\'' . $_str_type . '\') NOT NULL COMMENT \'状态\'');
        }

        $_str_rcode = 'y020111';

        if (!fn_isEmpty($_arr_alter)) {
            $_reselt = $this->obj_db->alter_table(BG_DB_TABLE . 'admin', $_arr_alter);

            if (!fn_isEmpty($_reselt)) {
                $_str_rcode = 'y020106';

                $_arr_adminData = array(
                    'admin_status' => $this->arr_status[0],
                );
                $this->obj_db->update(BG_DB_TABLE . 'admin', $_arr_adminData, 'LENGTH(`admin_status`)<1'); //将 admin_status 字段为空的记录，更新为默认值

                $_arr_adminData = array(
                    'admin_type' => $this->arr_type[0],
                );
                $this->obj_db->update(BG_DB_TABLE . 'admin', $_arr_adminData, 'LENGTH(`admin_type`)<1'); //将 admin_type 字段为空的记录，更新为默认值
            }
        }

        return array(
            'rcode' => $_str_rcode,
        );
    }


    /** 管理员创建、编辑提交
     * mdl_submit function.
     *
     * @access public
     * @param string $str_adminPass (default: '')
     * @param string $str_adminRand (default: '')
     * @return void
     */
    function mdl_submit($arr_adminSubmit = array()) {

        $_arr_adminRow  = $this->mdl_read($arr_adminSubmit['admin_id']);

        $_arr_adminData = array();

        if (isset($arr_adminSubmit['admin_type']) && !fn_isEmpty(isset($arr_adminSubmit['admin_type']))) {
            $_arr_adminData['admin_type'] = $arr_adminSubmit['admin_type'];
        } else if (isset($this->adminInput['admin_type'])) {
            $_arr_adminData['admin_type'] = $this->adminInput['admin_type'];
        }

        if (isset($arr_adminSubmit['admin_status']) && !fn_isEmpty(isset($arr_adminSubmit['admin_status']))) {
            $_arr_adminData['admin_status'] = $arr_adminSubmit['admin_status'];
        } else if (isset($this->adminInput['admin_status'])) {
            $_arr_adminData['admin_status'] = $this->adminInput['admin_status'];
        }

        if (isset($arr_adminSubmit['admin_note']) && !fn_isEmpty(isset($arr_adminSubmit['admin_note']))) {
            $_arr_adminData['admin_note'] = $arr_adminSubmit['admin_note'];
        } else if (isset($this->adminInput['admin_note'])) {
            $_arr_adminData['admin_note'] = $this->adminInput['admin_note'];
        }

        if (isset($arr_adminSubmit['admin_nick']) && !fn_isEmpty(isset($arr_adminSubmit['admin_nick']))) {
            $_arr_adminData['admin_nick'] = $arr_adminSubmit['admin_nick'];
        } else if (isset($this->adminInput['admin_nick'])) {
            $_arr_adminData['admin_nick'] = $this->adminInput['admin_nick'];
        }

        if (isset($arr_adminSubmit['admin_allow']) && !fn_isEmpty(isset($arr_adminSubmit['admin_allow']))) {
            $_arr_adminData['admin_allow'] = $arr_adminSubmit['admin_allow'];
        } else if (isset($this->adminInput['admin_allow'])) {
            $_arr_adminData['admin_allow'] = $this->adminInput['admin_allow'];
        }

        if ($_arr_adminRow['rcode'] == 'x020102') {
            $_arr_insert = array(
                'admin_id'      => $arr_adminSubmit['admin_id'],
                'admin_name'    => $arr_adminSubmit['admin_name'],
                'admin_time'    => time(),
            );

            $_arr_data = array_merge($_arr_adminData, $_arr_insert);
            $_num_adminId = $this->obj_db->insert(BG_DB_TABLE . 'admin', $_arr_data); //更新数据
            if ($_num_adminId >= 0) {
                $_str_rcode = 'y020101'; //更新成功
            } else {
                return array(
                    'rcode' => 'x020101', //更新失败
                );
            }
        } else {
            $_num_adminId    = $arr_adminSubmit['admin_id'];
            $_num_db      = $this->obj_db->update(BG_DB_TABLE . 'admin', $_arr_adminData, '`admin_id`=' . $_num_adminId); //更新数据
            if ($_num_db > 0) {
                $_str_rcode = 'y020103'; //更新成功
            } else {
                return array(
                    'rcode' => 'x020103', //更新失败
                );

            }
        }

        return array(
            'admin_id'   => $_num_adminId,
            'rcode'      => $_str_rcode, //成功
        );
    }


    /** 编辑状态
     * mdl_status function.
     *
     * @access public
     * @param mixed $str_status
     * @return void
     */
    function mdl_status($str_status) {
        $_str_adminId = implode(',', $this->adminIds['admin_ids']);

        $_arr_adminUpdate = array(
            'admin_status' => $str_status,
        );

        $_num_db = $this->obj_db->update(BG_DB_TABLE . 'admin', $_arr_adminUpdate, '`admin_id` IN (' . $_str_adminId . ')'); //删除数据

        //如影响行数大于0则返回成功
        if ($_num_db > 0) {
            $_str_rcode = 'y020103'; //成功
        } else {
            $_str_rcode = 'x020103'; //失败
        }

        return array(
            'rcode' => $_str_rcode,
        );
    }


    /** 读取
     * mdl_read function.
     *
     * @access public
     * @param mixed $str_admin
     * @param string $str_by (default: 'admin_id')
     * @param int $num_notId (default: 0)
     * @return void
     */
    function mdl_read($str_admin, $str_by = 'admin_id', $num_notId = 0) {
        $_arr_adminSelect = array(
            'admin_id',
            'admin_name',
            'admin_note',
            'admin_nick',
            'admin_allow',
            'admin_status',
            'admin_type',
            'admin_time',
        );

        if (is_numeric($str_admin)) {
            $_str_sqlWhere = $str_by . '=' . $str_admin; //如果读取值为数字
        } else {
            $_str_sqlWhere = $str_by . '=\'' . $str_admin . '\'';
        }

        if ($num_notId > 0) {
            $_str_sqlWhere .= ' AND `admin_id`<>' . $num_notId;
        }

        $_arr_adminRows = $this->obj_db->select(BG_DB_TABLE . 'admin', $_arr_adminSelect, $_str_sqlWhere, '', '', 1, 0); //检查本地表是否存在记录

        if (isset($_arr_adminRows[0])) { //用户名不存在则返回错误
            $_arr_adminRow = $_arr_adminRows[0];
        } else {
            return array(
                'rcode' => 'x020102', //不存在记录
            );
        }

        if (isset($_arr_adminRow['admin_allow'])) {
            $_arr_adminRow['admin_allow'] = fn_jsonDecode($_arr_adminRow['admin_allow'], 'no'); //json 解码
        } else {
            $_arr_adminRow['admin_allow'] = array();
        }

        $_arr_adminRow['rcode']   = 'y020102';

        return $_arr_adminRow;

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
        $_arr_adminSelect = array(
            'admin_id',
            'admin_name',
            'admin_note',
            'admin_nick',
            'admin_status',
            'admin_type',
        );

        $_str_sqlWhere = $this->sql_process($arr_search);

        $_arr_order = array(
            array('admin_id', 'DESC'),
        );

        $_arr_adminRows = $this->obj_db->select(BG_DB_TABLE . 'admin', $_arr_adminSelect, $_str_sqlWhere, '', $_arr_order, $num_no, $num_except); //查询数据

        return $_arr_adminRows;
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

        $_num_adminCount = $this->obj_db->count(BG_DB_TABLE . 'admin', $_str_sqlWhere); //查询数据

        return $_num_adminCount;
    }


    /** 删除
     * mdl_del function.
     *
     * @access public
     * @return void
     */
    function mdl_del() {
        $_str_adminId = implode(',', $this->adminIds['admin_ids']);

        $_num_db = $this->obj_db->delete(BG_DB_TABLE . 'admin', '`admin_id` IN (' . $_str_adminId . ')'); //删除数据

        //如车影响行数小于0则返回错误
        if ($_num_db > 0) {
            $_str_rcode = 'y020104'; //成功
        } else {
            $_str_rcode = 'x020104'; //失败
        }

        return array(
            'rcode' => $_str_rcode,
        );
    }


    /** 登录验证
     * input_login function.
     *
     * @access public
     * @return void
     */
    function input_login() {
        if (!fn_seccode()) { //验证码
            return array(
                'rcode'     => 'x030205',
            );
        }

        if (!fn_token('chk')) { //令牌
            return array(
                'rcode'     => 'x030206',
            );
        }

        $_arr_adminName = fn_validate(fn_post('admin_name'), 1, 30, 'str', 'strDigit');
        switch ($_arr_adminName['status']) {
            case 'too_short':
                return array(
                    'rcode'     => 'x010201',
                );
            break;

            case 'too_long':
                return array(
                    'rcode'     => 'x010202',
                );
            break;

            case 'format_err':
                return array(
                    'rcode'     => 'x010203',
                );
            break;

            case 'ok':
                $this->loginInput['admin_name'] = $_arr_adminName['str'];
            break;

        }

        $_arr_adminPass = fn_validate(fn_post('admin_pass'), 1, 0);
        switch ($_arr_adminPass['status']) {
            case 'too_short':
                return array(
                    'rcode'     => 'x010212',
                );
            break;

            case 'ok':
                $this->loginInput['admin_pass'] = $_arr_adminPass['str'];
            break;

        }

        $this->loginInput['rcode']  = 'ok';

        return $this->loginInput;
    }


    /** 创建、编辑表单验证
     * input_submit function.
     *
     * @access public
     * @return void
     */
    function input_submit() {
        if (!fn_token('chk')) { //令牌
            return array(
                'rcode' => 'x030206',
            );
        }

        $this->adminInput['admin_id'] = fn_getSafe(fn_post('admin_id'), 'int', 0);

        if ($this->adminInput['admin_id'] > 0) {
            //检验用户是否存在
            $_arr_adminRow = $this->mdl_read($this->adminInput['admin_id']);
            if ($_arr_adminRow['rcode'] != 'y020102') {
                return $_arr_adminRow;
            }
        }

        $_arr_adminName = fn_validate(fn_post('admin_name'), 1, 30);
        switch ($_arr_adminName['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x010201',
                );
            break;

            case 'too_long':
                return array(
                    'rcode' => 'x010202',
                );
            break;

            case 'ok':
                $this->adminInput['admin_name'] = $_arr_adminName['str'];
            break;
        }

        $_arr_adminNote = fn_validate(fn_post('admin_note'), 0, 30);
        switch ($_arr_adminNote['status']) {
            case 'too_long':
                return array(
                    'rcode' => 'x020203',
                );
            break;

            case 'ok':
                $this->adminInput['admin_note'] = $_arr_adminNote['str'];
            break;
        }

        $_arr_adminStatus = fn_validate(fn_post('admin_status'), 1, 0);
        switch ($_arr_adminStatus['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x020202',
                );
            break;

            case 'ok':
                $this->adminInput['admin_status'] = $_arr_adminStatus['str'];
            break;

        }

        $_arr_adminType = fn_validate(fn_post('admin_type'), 1, 0);
        switch ($_arr_adminType['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x020201',
                );
            break;

            case 'ok':
                $this->adminInput['admin_type'] = $_arr_adminType['str'];
            break;

        }

        $_arr_adminNick = fn_validate(fn_post('admin_nick'), 0, 30);
        switch ($_arr_adminNick['status']) {
            case 'too_long':
                return array(
                    'rcode' => 'x020204',
                );
            break;

            case 'ok':
                $this->adminInput['admin_nick'] = $_arr_adminNick['str'];
            break;
        }

        $this->adminInput['admin_allow'] = fn_jsonEncode(fn_post('admin_allow'), 'no');

        $this->adminInput['rcode']       = 'ok';

        return $this->adminInput;
    }


    /** 选择管理员
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

        $_arr_adminIds = fn_post('admin_ids');

        if (fn_isEmpty($_arr_adminIds)) {
            $_str_rcode = 'x030202';
        } else {
            foreach ($_arr_adminIds as $_key=>$_value) {
                $_arr_adminIds[$_key] = fn_getSafe($_value, 'int', 0);
            }
            $_str_rcode = 'ok';
        }

        $this->adminIds = array(
            'rcode'      => $_str_rcode,
            'admin_ids'  => array_filter(array_unique($_arr_adminIds)),
        );

        return $this->adminIds;
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
            $_str_sqlWhere .= ' AND (`admin_name` LIKE \'%' . $arr_search['key'] . '%\' OR `admin_note` LIKE \'%' . $arr_search['key'] . '%\' OR `admin_nick` LIKE \'%' . $arr_search['key'] . '%\')';
        }

        if (isset($arr_search['status']) && !fn_isEmpty($arr_search['status'])) {
            $_str_sqlWhere .= ' AND `admin_status`=\'' . $arr_search['status'] . '\'';
        }

        if (isset($arr_search['type']) && !fn_isEmpty($arr_search['type'])) {
            $_str_sqlWhere .= ' AND `admin_type`=\'' . $arr_search['type'] . '\'';
        }

        return $_str_sqlWhere;
    }
}
