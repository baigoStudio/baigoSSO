<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

/*-------------用户模型-------------*/
class MODEL_USER {

    public $obj_db;
    public $userInput;
    public $userIds;
    public $arr_status  = array('enable', 'wait', 'disable');

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
        $_str_status = implode('\',\'', $this->arr_status);

        $_arr_userCreate = array(
            'user_id'               => 'int NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'user_name'             => 'varchar(30) NOT NULL COMMENT \'用户名\'',
            'user_mail'             => 'varchar(300) NOT NULL COMMENT \'邮箱\'',
            'user_contact'          => 'varchar(3000) NOT NULL COMMENT \'联系方式\'',
            'user_extend'           => 'varchar(3000) NOT NULL COMMENT \'扩展字段\'',
            'user_pass'             => 'char(32) NOT NULL COMMENT \'密码\'',
            'user_nick'             => 'varchar(30) NOT NULL COMMENT \'昵称\'',
            'user_status'           => 'enum(\'' . $_str_status . '\') NOT NULL COMMENT \'状态\'',
            'user_note'             => 'varchar(30) NOT NULL COMMENT \'备注\'',
            'user_time'             => 'int NOT NULL COMMENT \'创建时间\'',
            'user_time_login'       => 'int NOT NULL COMMENT \'登录时间\'',
            'user_ip'               => 'varchar(15) NOT NULL COMMENT \'最后 IP 地址\'',
            'user_access_token'     => 'char(32) NOT NULL COMMENT \'访问口令\'',
            'user_access_expire'    => 'int NOT NULL COMMENT \'访问过期时间\'',
            'user_refresh_token'    => 'char(32) NOT NULL COMMENT \'刷新口令\'',
            'user_refresh_expire'   => 'int NOT NULL COMMENT \'刷新过期时间\'',
            'user_crypt_type'       => 'tinyint(1) NOT NULL COMMENT \'加密类型\'',
            'user_app_id'           => 'smallint NOT NULL COMMENT \'来源 APP ID\'',
        );

        for ($_iii = 1; $_iii <= 3; $_iii++) {
            $_arr_userCreate['user_sec_ques_' . $_iii] = 'varchar(300) NOT NULL COMMENT \'密保问题 ' . $_iii . '\'';
            $_arr_userCreate['user_sec_answ_' . $_iii] = 'varchar(32) NOT NULL COMMENT \'密保答案 ' . $_iii . '\'';
        }

        $_num_db = $this->obj_db->create_table(BG_DB_TABLE . 'user', $_arr_userCreate, 'user_id', '用户');

        if ($_num_db > 0) {
            $_str_rcode = 'y010105'; //更新成功
        } else {
            $_str_rcode = 'x010105'; //更新成功
        }

        return array(
            'rcode' => $_str_rcode, //更新成功
        );
    }


    /** 修改表
     * mdl_alter_table function.
     *
     * @access public
     * @return void
     */
    function mdl_alter_table() {
        foreach ($this->arr_status as $_key=>$_value) {
            $this->arr_status[] = $_key;
        }
        $_str_status = implode('\',\'', $this->arr_status);

        $_arr_col     = $this->mdl_column();
        $_arr_alter   = array();

        if (in_array('user_status', $_arr_col)) {
            $_arr_alter['user_status'] = array('CHANGE', 'enum(\'' . $_str_status . '\') NOT NULL COMMENT \'状态\'', 'user_status');
        }

        if (in_array('user_pass', $_arr_col)) {
            $_arr_alter['user_pass'] = array('CHANGE', 'char(32) NOT NULL COMMENT \'密码\'', 'user_pass');
        }

        if (in_array('user_token', $_arr_col)) {
            $_arr_alter['user_token'] = array('CHANGE', 'char(32) NOT NULL COMMENT \'访问口令\'', 'user_access_token');
        } else if (!in_array('user_access_token', $_arr_col)) {
            $_arr_alter['user_access_token'] = array('ADD', 'char(32) NOT NULL COMMENT \'访问口令\'');
        }

        if (in_array('user_token_expire', $_arr_col)) {
            $_arr_alter['user_token_expire'] = array('CHANGE', 'int NOT NULL COMMENT \'访问过期时间\'', 'user_access_expire');
        } else if (!in_array('user_access_expire', $_arr_col)) {
            $_arr_alter['user_access_expire'] = array('ADD', 'int NOT NULL COMMENT \'访问过期时间\'');
        }

        if (!in_array('user_refresh_token', $_arr_col)) {
            $_arr_alter['user_refresh_token'] = array('ADD', 'char(32) NOT NULL COMMENT \'刷新口令\'');
        }

        if (!in_array('user_refresh_expire', $_arr_col)) {
            $_arr_alter['user_refresh_expire'] = array('ADD', 'int NOT NULL COMMENT \'刷新过期时间\'');
        }

        if (!in_array('user_contact', $_arr_col)) {
            $_arr_alter['user_contact'] = array('ADD', 'varchar(3000) NOT NULL COMMENT \'联系方式\'');
        }

        if (!in_array('user_extend', $_arr_col)) {
            $_arr_alter['user_extend'] = array('ADD', 'varchar(3000) NOT NULL COMMENT \'联系方式\'');
        }

        if (in_array('user_is_upgrade', $_arr_col)) {
            $_arr_alter['user_is_upgrade'] = array('CHANGE', 'tinyint(1) NOT NULL COMMENT \'加密类型\'', 'user_crypt_type');
        } else if (!in_array('user_crypt_type', $_arr_col)) {
            $_arr_alter['user_crypt_type'] = array('ADD', 'tinyint(1) NOT NULL COMMENT \'加密类型\'');
        }

        for ($_iii = 1; $_iii <= 3; $_iii++) {
            if (!in_array('user_sec_ques_' . $_iii, $_arr_col)) {
                $_arr_alter['user_sec_ques_' . $_iii] = array('ADD', 'varchar(300) NOT NULL COMMENT \'密保问题 ' . $_iii . '\'');
            }

            if (!in_array('user_sec_answ_' . $_iii, $_arr_col)) {
                $_arr_alter['user_sec_answ_' . $_iii] = array('ADD', 'varchar(32) NOT NULL COMMENT \'密保答案 ' . $_iii . '\'');
            }
        }

        if (!in_array('user_app_id', $_arr_col)) {
            $_arr_alter['user_app_id'] = array('ADD', 'smallint NOT NULL COMMENT \'来源 APP ID\'');
        }

        $_str_rcode = 'y010111';

        if (!fn_isEmpty($_arr_alter)) {
            $_reselt = $this->obj_db->alter_table(BG_DB_TABLE . 'user', $_arr_alter);

            if (!fn_isEmpty($_reselt)) {
                $_str_rcode = 'y010106';
                $_arr_userData = array(
                    'user_status' => $this->arr_status[0],
                );
                $this->obj_db->update(BG_DB_TABLE . 'user', $_arr_userData, 'LENGTH(`user_status`) < 1'); //更新数据
            }
        }

        return array(
            'rcode' => $_str_rcode,
        );
    }


    /** 创建视图
     * mdl_create_view function.
     *
     * @access public
     * @return void
     */
    function mdl_create_view() {
        $_arr_userCreat = array(
            array('user_id',            BG_DB_TABLE . 'user'),
            array('user_name',          BG_DB_TABLE . 'user'),
            array('user_mail',          BG_DB_TABLE . 'user'),
            array('user_nick',          BG_DB_TABLE . 'user'),
            array('user_note',          BG_DB_TABLE . 'user'),
            array('user_status',        BG_DB_TABLE . 'user'),
            array('user_time',          BG_DB_TABLE . 'user'),
            array('user_time_login',    BG_DB_TABLE . 'user'),
            array('user_ip',            BG_DB_TABLE . 'user'),
            array('belong_app_id',      BG_DB_TABLE . 'belong'),
        );

        $_str_sqlJoin = 'LEFT JOIN `' . BG_DB_TABLE . 'belong` ON (`' . BG_DB_TABLE . 'user`.`user_id`=`' . BG_DB_TABLE . 'belong`.`belong_user_id`)';

        $_num_db = $this->obj_db->create_view(BG_DB_TABLE . 'user_view', $_arr_userCreat, BG_DB_TABLE . 'user', $_str_sqlJoin);

        if ($_num_db > 0) {
            $_str_rcode = 'y010108'; //更新成功
        } else {
            $_str_rcode = 'x010108'; //更新成功
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
        $_arr_colRows = $this->obj_db->show_columns(BG_DB_TABLE . 'user');

        $_arr_col = array();

        if (!fn_isEmpty($_arr_colRows)) {
            foreach ($_arr_colRows as $_key=>$_value) {
                $_arr_col[] = $_value['Field'];
            }
        }

        return $_arr_col;
    }


    /** 提交
     * mdl_submit function.
     *
     * @access public
     * @param string $str_userPass (default: '')
     * @return void
     */
    function mdl_submit($arr_userSubmit = array()) {
        $_arr_userData = array(
            'user_name'             => $this->userInput['user_name'],
            'user_mail'             => $this->userInput['user_mail'],
            'user_crypt_type'       => 2,
        );

        if (isset($arr_userSubmit['user_status']) && fn_isEmpty($arr_userSubmit['user_status'])) {
            $_arr_userData['user_status'] = $arr_userSubmit['user_status'];
        } else if (isset($this->userInput['user_status'])) {
            $_arr_userData['user_status'] = $this->userInput['user_status'];
        }

        if (isset($this->userInput['user_nick'])) {
            $_arr_userData['user_nick'] = $this->userInput['user_nick'];
        }

        if (isset($this->userInput['user_note'])) {
            $_arr_userData['user_note'] = $this->userInput['user_note'];
        }

        if (isset($this->userInput['user_contact'])) {
            $_arr_userData['user_contact'] = $this->userInput['user_contact'];
        }

        if (isset($this->userInput['user_extend'])) {
            $_arr_userData['user_extend'] = $this->userInput['user_extend'];
        }

        if ($this->userInput['user_id'] < 1) {
            $_arr_insert = array(
                'user_pass'         => $arr_userSubmit['user_pass'],
                'user_time'         => time(),
                'user_time_login'   => time(),
                'user_ip'           => fn_getIp(),
                'user_app_id'       => $arr_userSubmit['user_app_id'],
            );
            $_arr_data   = array_merge($_arr_userData, $_arr_insert);
            $_num_userId = $this->obj_db->insert(BG_DB_TABLE . 'user', $_arr_data); //更新数据
            if ($_num_userId > 0) {
                $_str_rcode = 'y010101'; //更新成功
            } else {
                return array(
                    'rcode' => 'x010101', //更新失败
                );
            }
        } else {
            if (isset($arr_userSubmit['user_pass']) && !fn_isEmpty($arr_userSubmit['user_pass'])) {
                $_arr_userData['user_pass'] = $arr_userSubmit['user_pass']; //如果密码为空，则不修改
            }
            $_num_userId = $this->userInput['user_id'];
            $_num_db  = $this->obj_db->update(BG_DB_TABLE . 'user', $_arr_userData, '`user_id`=' . $_num_userId); //更新数据
            if ($_num_db > 0) {
                $_str_rcode = 'y010103'; //更新成功
            } else {
                return array(
                    'rcode' => 'x010103', //更新失败
                );

            }
        }

        return array(
            'rcode' => $_str_rcode, //成功
        );
    }


    /** 更新状态
     * mdl_status function.
     *
     * @access public
     * @param mixed $str_status
     * @return void
     */
    function mdl_status($str_status) {
        $_str_userId = implode(',', $this->userIds['user_ids']);

        $_arr_userUpdate = array(
            'user_status' => $str_status,
        );

        $_num_db = $this->obj_db->update(BG_DB_TABLE . 'user', $_arr_userUpdate, '`user_id` IN (' . $_str_userId . ')'); //删除数据

        //如影响行数大于0则返回成功
        if ($_num_db > 0) {
            $_str_rcode = 'y010103'; //成功
        } else {
            $_str_rcode = 'x010103'; //失败
        }

        return array(
            'rcode' => $_str_rcode,
        );
    }


    /** 读取
     * mdl_read function.
     *
     * @access public
     * @param mixed $str_user
     * @param string $str_by (default: 'user_id')
     * @param int $num_notId (default: 0)
     * @return void
     */
    function mdl_read($str_user, $str_by = 'user_id', $num_notId = 0) {
        $_arr_col = $this->mdl_column();

        $_arr_userSelect = array(
            'user_id',
            'user_name',
            'user_pass',
            'user_mail',
            'user_contact',
            'user_extend',
            'user_nick',
            'user_note',
            'user_status',
            'user_time',
            'user_time_login',
            'user_ip',
            'user_access_token',
            'user_access_expire',
            'user_refresh_token',
            'user_refresh_expire',
            'user_crypt_type',
            'user_app_id',
        );

        for ($_iii = 1; $_iii <= 3; $_iii++) {
            $_arr_userSelect[] = 'user_sec_ques_' . $_iii;
            $_arr_userSelect[] = 'user_sec_answ_' . $_iii;
        }

        if (in_array('user_rand', $_arr_col)) {
            $_arr_userSelect[] = 'user_rand';
        }

        if (is_numeric($str_user)) {
            $_str_sqlWhere = $str_by . '=' . $str_user;
        } else {
            $_str_sqlWhere = $str_by . '=\'' . $str_user . '\'';
        }

        if ($num_notId > 0) {
            $_str_sqlWhere .= ' AND `user_id`<>' . $num_notId;
        }

        $_arr_userRows    = $this->obj_db->select(BG_DB_TABLE . 'user', $_arr_userSelect, $_str_sqlWhere, '', '', 1, 0); //检查本地表是否存在记录

        if (isset($_arr_userRows[0])) { //用户名不存在则返回错误
            $_arr_userRow = $_arr_userRows[0];
        } else {
            return array(
                'rcode' => 'x010102', //不存在记录
            );
        }

        $_arr_userRow['user_contact']   = fn_jsonDecode($_arr_userRow['user_contact'], 'decode');
        $_arr_userRow['user_extend']    = fn_jsonDecode($_arr_userRow['user_extend'], 'decode');

        $_arr_userRow['rcode']          = 'y010102';

        return $_arr_userRow;
    }


    /** 从视图列出
     * mdl_list_view function.
     *
     * @access public
     * @param array $arr_search (default: array())
     * @return void
     */
    function mdl_list_view($arr_search = array()) {
        $_arr_userSelect = array(
            'user_id',
            'user_name',
            'user_mail',
            'user_nick',
            'user_note',
            'user_status',
            'user_time',
            'user_time_login',
            'user_ip',
        );

        $_str_sqlWhere = '1';

        if (isset($arr_search['key']) && !fn_isEmpty($arr_search['key'])) {
            $_str_sqlWhere .= ' AND (`user_name` LIKE \'%' . $arr_search['key'] . '%\' OR `user_nick` LIKE \'%' . $arr_search['key'] . '%\' OR `user_note` LIKE \'%' . $arr_search['key'] . '%\')';
        }

        if (isset($arr_search['app_id']) && $arr_search['app_id'] > 0) {
            $_str_sqlWhere .= ' AND `belong_app_id`=' . $arr_search['app_id'];
        }

        $_arr_order = array(
            array('user_id', 'DESC'),
        );

        $_arr_userRows = $this->obj_db->select(BG_DB_TABLE . 'user_view', $_arr_userSelect, $_str_sqlWhere, '', $_arr_order); //查询数据

        return $_arr_userRows;
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
        $_arr_userSelect = array(
            'user_id',
            'user_name',
            'user_mail',
            'user_nick',
            'user_note',
            'user_status',
            'user_time',
            'user_time_login',
            'user_ip',
        );

        $_str_sqlWhere = $this->sql_process($arr_search);

        $_arr_order = array(
            array('user_id', 'DESC'),
        );

        $_arr_userRows = $this->obj_db->select(BG_DB_TABLE . 'user', $_arr_userSelect, $_str_sqlWhere, '', $_arr_order, $num_no, $num_except); //查询数据

        return $_arr_userRows;
    }


    /** 计数
     * mdl_count function.
     *
     * @access public
     * @param array $arr_search (default: array())
     * @return void
     */
    function mdl_count($arr_search = array()) {
        $_str_sqlWhere = '1';

        $_str_sqlWhere = $this->sql_process($arr_search);

        $_num_userCount = $this->obj_db->count(BG_DB_TABLE . 'user', $_str_sqlWhere); //查询数据

        return $_num_userCount;
    }


     /** 删除
     * mdl_del function.
     *
     * @access public
     * @param mixed $_arr_userIds
     * @return void
     */
    function mdl_del($_arr_userIds) {
        $_str_userId  = implode(',', $_arr_userIds);
        $_num_db   = $this->obj_db->delete(BG_DB_TABLE . 'user', '`user_id` IN (' . $_str_userId . ')'); //删除数据

        //如车影响行数小于0则返回错误
        if ($_num_db > 0) {
            $_str_rcode = 'y010104'; //成功
            $this->obj_db->delete(BG_DB_TABLE . 'belong', '`belong_user_id` IN (' . $_str_userId . ')'); //删除数据
        } else {
            $_str_rcode = 'x010104'; //失败
        }

        return array(
            'rcode' => $_str_rcode,
        );
    }


    /** 激活用户
     * mdl_confirm function.
     *
     * @access public
     * @param mixed $num_userId
     * @return void
     */
    function mdl_confirm($num_userId) {
        $_arr_userData = array();

        $_arr_userData['user_status'] = 'enable';

        if (!fn_isEmpty($_arr_userData)) {
            $_num_db   = $this->obj_db->update(BG_DB_TABLE . 'user', $_arr_userData, '`user_id`=' . $num_userId); //更新数据
        }

        if ($_num_db > 0) {
            $_str_rcode = 'y010409'; //更新成功
        } else {
            return array(
                'rcode' => 'x010409', //更新失败
            );
        }

        return array(
            'rcode'      => $_str_rcode, //成功
        );
    }



    /** 以 get 或 post 方式读取
     * input_user function.
     *
     * @access public
     * @param string $str_method (default: 'get')
     * @return void
     */
    function input_user($str_method = 'get') {
        if ($str_method == 'post') {
            if (isset($_POST['user_id'])) {
                $_arr_userInput['user_by']     = 'user_id';
                $_arr_userChk                = $this->chk_user_id(fn_post('user_id'));
            } else if (isset($_POST['user_name'])) {
                $_arr_userInput['user_by']     = 'user_name';
                $_arr_userChk                = $this->chk_user_name(fn_post('user_name'));
            } else if (BG_LOGIN_MAIL == 'on') {
                $_arr_userInput['user_by']     = 'user_mail';
                $_arr_userChk                = $this->chk_user_mail(fn_post('user_mail'));
            } else {
                $_arr_userChk['rcode'] = 'x010227';
            }
        } else {
            if (isset($_GET['user_id'])) {
                $_arr_userInput['user_by']     = 'user_id';
                $_arr_userChk                = $this->chk_user_id(fn_get('user_id'));
            } else if (isset($_GET['user_name'])) {
                $_arr_userInput['user_by']     = 'user_name';
                $_arr_userChk                = $this->chk_user_name(fn_get('user_name'));
            } else if (BG_LOGIN_MAIL == 'on') {
                $_arr_userInput['user_by']     = 'user_mail';
                $_arr_userChk                = $this->chk_user_mail(fn_get('user_mail'));
            } else {
                $_arr_userChk['rcode'] = 'x010227';
            }
        }

        if ($_arr_userChk['rcode'] != 'ok') {
            return $_arr_userChk;
        }

        switch ($_arr_userInput['user_by']) {
            case 'user_id':
                $_arr_userInput['user_str']    = $_arr_userChk['user_id'];
            break;

            case 'user_name':
                $_arr_userInput['user_str']    = $_arr_userChk['user_name'];
            break;

            default:
                $_arr_userInput['user_str']    = $_arr_userChk['user_mail'];
            break;
        }

        $_arr_userInput['rcode'] = 'ok';

        return $_arr_userInput;
    }


    /** 表单验证用户名
     * input_name function.
     *
     * @access public
     * @return void
     */
    function input_name() {
        $_arr_userName = $this->chk_user_name(fn_get('user_name'));
        if ($_arr_userName['rcode'] != 'ok') {
            return $_arr_userName;
        }

        return array(
            'user_name'  => $_arr_userName['user_name'],
            'rcode'      => 'ok',
        );
    }


    /** 表单验证邮箱
     * input_mail function.
     *
     * @access public
     * @return void
     */
    function input_mail() {
        $_num_notId   = fn_getSafe(fn_get('not_id'), 'int', 0);

        $_arr_userMail = $this->chk_user_mail(fn_get('user_mail'));
        if ($_arr_userMail['rcode'] != 'ok') {
            return $_arr_userMail;
        }

        return array(
            'not_id'     => $_num_notId,
            'user_mail'  => $_arr_userMail['user_mail'],
            'rcode'      => 'ok',
        );
    }


    /** 表单验证
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

        $this->userInput['user_id'] = fn_getSafe(fn_post('user_id'), 'int', 0);

        if ($this->userInput['user_id'] > 0) {
            //检查用户是否存在
            $_arr_userRow = $this->mdl_read($this->userInput['user_id']);
            if ($_arr_userRow['rcode'] != 'y010102') {
                return $_arr_userRow;
            }
        }

        $_arr_userName = $this->chk_user_name(fn_post('user_name'));
        if ($_arr_userName['rcode'] != 'ok') {
            return $_arr_userName;
        }
        $this->userInput['user_name'] = $_arr_userName['user_name'];

        //检验用户名是否重复
        $_arr_userRowChk = $this->mdl_read($this->userInput['user_name'], 'user_name', $this->userInput['user_id']);
        if ($_arr_userRowChk['rcode'] == 'y010102') {
            return array(
                'rcode' => 'x010205',
            );
        }

        $_arr_userMail = $this->chk_user_mail(fn_post('user_mail'));
        if ($_arr_userMail['rcode'] != 'ok') {
            return $_arr_userMail;
        }
        $this->userInput['user_mail'] = $_arr_userMail['user_mail'];

        if ((BG_REG_ONEMAIL == 'false' || BG_LOGIN_MAIL == 'on') && !fn_isEmpty($_arr_userMail['user_mail'])) {
            $_arr_userRowChk = $this->mdl_read($_arr_userMail['user_mail'], 'user_mail', $this->userInput['user_id']); //检查邮箱
            if ($_arr_userRowChk['rcode'] == 'y010102') {
                return array(
                    'rcode' => 'x010211',
                );
            }
        }

        $_arr_userNick = $this->chk_user_nick(fn_post('user_nick'));
        if ($_arr_userNick['rcode'] != 'ok') {
            return $_arr_userNick;
        }
        $this->userInput['user_nick'] = $_arr_userNick['user_nick'];

        $_arr_userNote = $this->chk_user_note(fn_post('user_note'));
        if ($_arr_userNote['rcode'] != 'ok') {
            return $_arr_userNote;
        }
        $this->userInput['user_note'] = $_arr_userNote['user_note'];

        $_arr_userStatus = fn_validate(fn_post('user_status'), 1, 0);
        switch ($_arr_userStatus['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x010216',
                );
            break;

            case 'ok':
                $this->userInput['user_status'] = $_arr_userStatus['str'];
            break;
        }


        $_arr_userContact = fn_post('user_contact');
        $this->userInput['user_contact'] = fn_jsonEncode($_arr_userContact, 'encode');

        $_arr_userExtend  = fn_post('user_extend');
        $this->userInput['user_extend'] = fn_jsonEncode($_arr_userExtend, 'encode');

        $this->userInput['rcode'] = 'ok';

        return $this->userInput;
    }


    /** 选择
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

        $_arr_userIds = fn_post('user_ids');

        if (fn_isEmpty($_arr_userIds)) {
            $_str_rcode = 'x030202';
        } else {
            foreach ($_arr_userIds as $_key=>$_value) {
                $_arr_userIds[$_key] = fn_getSafe($_value, 'int', 0);
            }
            $_str_rcode = 'ok';
        }

        $this->userIds = array(
            'rcode'      => $_str_rcode,
            'user_ids'   => array_filter(array_unique($_arr_userIds)),
        );

        return $this->userIds;
    }


    /** 验证用户 ID
     * chk_user_id function.
     *
     * @param mixed $num_id
     * @return void
     */
    function chk_user_id($num_id) {
        $_arr_userId = fn_validate($num_id, 1, 0, 'str', 'int');

        switch ($_arr_userId['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x010217',
                );
            break;

            case 'format_err':
                return array(
                    'rcode' => 'x010218',
                );
            break;

            case 'ok':
                $_num_userId = $_arr_userId['str'];
            break;
        }

        return array(
            'user_id'   => $_num_userId,
            'rcode'     => 'ok',
        );
    }


    /** 验证用户名
     * chk_user_name function.
     *
     * @access public
     * @param mixed $str_user
     * @return void
     */
    function chk_user_name($str_name) {
        $_arr_userName = fn_validate($str_name, 1, 30, 'str', 'strDigit');

        switch ($_arr_userName['status']) {
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

            case 'format_err':
                return array(
                    'rcode' => 'x010203',
                );
            break;

            case 'ok':
                $_str_userName = $_arr_userName['str'];

                if (defined('BG_BAD_NAME') && !fn_isEmpty(BG_BAD_NAME)) {
                    if (fn_regChk($_str_userName, BG_BAD_NAME, true)) {
                        return array(
                            'rcode' => 'x010204',
                        );
                    }
                }
            break;
        }

        return array(
            'user_name'  => $_str_userName,
            'rcode'      => 'ok',
        );
    }


    /** 验证邮箱
     * chk_user_mail function.
     *
     * @access public
     * @param mixed $str_mail
     * @param mixed $num_mailMin
     * @return void
     */
    function chk_user_mail($str_mail, $num_min = 0) {
        if (BG_REG_NEEDMAIL == 'on' || BG_LOGIN_MAIL == 'on' || $num_min > 0) {
            $_num_mailMin = 1;
        } else {
            $_num_mailMin = 0;
        }

        $_arr_userMail = fn_validate($str_mail, $_num_mailMin, 300, 'str', 'email');

        switch ($_arr_userMail['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x010206',
                );
            break;

            case 'too_long':
                return array(
                    'rcode' => 'x010207',
                );
            break;

            case 'format_err':
                return array(
                    'rcode' => 'x010208',
                );
            break;

            case 'ok':
                $_str_userMail = $_arr_userMail['str'];

                if (defined('BG_ACC_MAIL') && !fn_isEmpty(BG_ACC_MAIL) && fn_isEmpty($_str_userMail)) {
                    if (!fn_regChk($_str_userMail, BG_ACC_MAIL)) {
                        return array(
                            'rcode' => 'x010209',
                        );
                    }
                } else if (defined('BG_BAD_MAIL') && !fn_isEmpty(BG_BAD_MAIL) && fn_isEmpty($_str_userMail)) {
                    if (fn_regChk($_str_userMail, BG_BAD_MAIL)) {
                        return array(
                            'rcode' => 'x010210',
                        );
                    }
                }
            break;
        }

        return array(
            'user_mail'  => $_str_userMail,
            'rcode'      => 'ok',
        );
    }


    /** 验证密码
     * chk_user_pass function.
     *
     * @access public
     * @param mixed $str_pass
     * @return void
     */
    function chk_user_pass($str_pass) {
        $_arr_userPass = fn_validate($str_pass, 1, 0);
        switch ($_arr_userPass['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x010212',
                );
            break;

            case 'ok':
                $_str_userPass = $_arr_userPass['str'];
            break;
        }

        return array(
            'user_pass'  => $_str_userPass,
            'rcode'      => 'ok',
        );
    }


    /** 验证昵称
     * chk_user_nick function.
     *
     * @access public
     * @param mixed $str_nick
     * @return void
     */
    function chk_user_nick($str_nick) {
        $_arr_userNick = fn_validate($str_nick, 0, 30);
        switch ($_arr_userNick['status']) {
            case 'too_long':
                return array(
                    'rcode' => 'x010214',
                );
            break;

            case 'ok':
                $_str_userNick = $_arr_userNick['str'];
            break;

        }

        return array(
            'user_nick'  => $_str_userNick,
            'rcode'      => 'ok',
        );
    }


    /** 验证备注
     * chk_user_note function.
     *
     * @access public
     * @param mixed $str_note
     * @return void
     */
    function chk_user_note($str_note) {
        $_arr_userNote = fn_validate($str_note, 0, 30);
        switch ($_arr_userNote['status']) {
            case 'too_long':
                return array(
                    'rcode' => 'x010215',
                );
            break;

            case 'ok':
                $_str_userNote = $_arr_userNote['str'];
            break;

        }

        return array(
            'user_note'  => $_str_userNote,
            'rcode'      => 'ok',
        );
    }


    /** 列出及统计 SQL 处理
     * sql_process function.
     *
     * @param array $arr_search (default: array())
     * @return void
     */
    function sql_process($arr_search = array()) {
        $_str_sqlWhere = '1';

        if (isset($arr_search['key']) && !fn_isEmpty($arr_search['key'])) {
            $_str_sqlWhere .= ' AND (`user_name` LIKE \'%' . $arr_search['key'] . '%\' OR `user_name` LIKE \'%' . $arr_search['key'] . '%\' OR `user_mail` LIKE \'%' . $arr_search['key'] . '%\' OR `user_note` LIKE \'%' . $arr_search['key'] . '%\')';
        }

        if (isset($arr_search['key_name']) && !fn_isEmpty($arr_search['key_name'])) {
            $_str_sqlWhere .= ' AND `user_name` LIKE \'%' . $arr_search['key_name'] . '%\'';
        }

        if (isset($arr_search['key_mail']) && !fn_isEmpty($arr_search['key_mail'])) {
            $_str_sqlWhere .= ' AND `user_mail` LIKE \'%' . $arr_search['key_mail'] . '%\'';
        }

        if (isset($arr_search['min_id']) && $arr_search['min_id'] > 0) {
            $_str_sqlWhere .= ' AND `user_id`>' . $arr_search['min_id'];
        }

        if (isset($arr_search['max_id']) && $arr_search['max_id'] > 0) {
            $_str_sqlWhere .= ' AND `user_id`<' . $arr_search['max_id'];
        }

        if (isset($arr_search['begin_time']) && $arr_search['begin_time'] > 0) {
            $_str_sqlWhere .= ' AND `user_time`>' . $arr_search['begin_time'];
        }

        if (isset($arr_search['end_time']) && $arr_search['end_time'] > 0) {
            $_str_sqlWhere .= ' AND `user_time`<' . $arr_search['end_time'];
        }

        if (isset($arr_search['begin_login']) && $arr_search['begin_login'] > 0) {
            $_str_sqlWhere .= ' AND `user_time_login`>' . $arr_search['begin_login'];
        }

        if (isset($arr_search['end_login']) && $arr_search['end_login'] > 0) {
            $_str_sqlWhere .= ' AND `user_time_login`<' . $arr_search['end_login'];
        }

        if (isset($arr_search['status']) && !fn_isEmpty($arr_search['status'])) {
            $_str_sqlWhere .= ' AND `user_status`=\'' . $arr_search['status'] . '\'';
        }

        if (isset($arr_search['user_mail']) && !fn_isEmpty($arr_search['user_mail'])) {
            $_str_sqlWhere .= ' AND `user_mail`=\'' . $arr_search['user_mail'] . '\'';
        }

        if (isset($arr_search['user_names']) && !fn_isEmpty($arr_search['user_names'])) {
            $_str_userNames    = implode('\',\'', $arr_search['user_names']);
            $_str_sqlWhere .= ' AND `user_name` IN (\'' . $_str_userNames . '\')';

        }

        return $_str_sqlWhere;
    }
}
