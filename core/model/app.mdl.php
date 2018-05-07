<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

/*-------------应用模型-------------*/
class MODEL_APP {

    public $obj_db;
    public $appInput;
    public $appIds;
    public $arr_status  = array('enable', 'disable'); //状态
    public $arr_sync    = array('on', 'off'); //是否同步

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
        $_str_sync      = implode('\',\'', $this->arr_sync);

        $_arr_appCreate = array(
            'app_id'            => 'smallint NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'app_name'          => 'varchar(30) NOT NULL COMMENT \'应用名\'',
            'app_key'           => 'char(64) NOT NULL COMMENT \'通信密钥\'',
            'app_secret'        => 'char(16) NOT NULL COMMENT \'密文密钥\'',
            'app_url_notify'    => 'varchar(3000) NOT NULL COMMENT \'通知接口 URL\'',
            'app_url_sync'      => 'varchar(3000) NOT NULL COMMENT \'同步登录通知 URL\'',
            'app_status'        => 'enum(\'' . $_str_status . '\') NOT NULL COMMENT \'状态\'',
            'app_note'          => 'varchar(30) NOT NULL COMMENT \'备注\'',
            'app_time'          => 'int NOT NULL COMMENT \'创建时间\'',
            'app_ip_allow'      => 'varchar(1000) NOT NULL COMMENT \'允许调用 IP 地址\'',
            'app_ip_bad'        => 'varchar(1000) NOT NULL COMMENT \'禁止 IP\'',
            'app_sync'          => 'enum(\'' . $_str_sync . '\') NOT NULL COMMENT \'是否同步\'',
            'app_allow'         => 'varchar(3000) NOT NULL COMMENT \'权限\'',
        );

        $_num_db = $this->obj_db->create_table(BG_DB_TABLE . 'app', $_arr_appCreate, 'app_id', '应用');

        if ($_num_db > 0) {
            $_str_rcode = 'y050105'; //更新成功
        } else {
            $_str_rcode = 'x050105'; //更新成功
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
        $_arr_colRows = $this->obj_db->show_columns(BG_DB_TABLE . 'app');

        $_arr_col = array();

        if (!fn_isEmpty($_arr_colRows)) {
            foreach ($_arr_colRows as $_key=>$_value) {
                $_arr_col[] = $_value['Field'];
            }
        }

        return $_arr_col;
    }


    /** 修改表
     * mdl_alter_table function.
     *
     * @access public
     * @return void
     */
    function mdl_alter_table() {
        $_str_status    = implode('\',\'', $this->arr_status);
        $_str_sync      = implode('\',\'', $this->arr_sync);

        $_arr_col     = $this->mdl_column();
        $_arr_alter   = array();

        if (in_array('app_id', $_arr_col)) {
            $_arr_alter['app_id'] = array('CHANGE', 'smallint NOT NULL AUTO_INCREMENT COMMENT \'ID\'', 'app_id');
        }

        if (in_array('app_status', $_arr_col)) {
            $_arr_alter['app_status'] = array('CHANGE', 'enum(\'' . $_str_status . '\') NOT NULL COMMENT \'状态\'', 'app_status');
        }

        if (in_array('app_sync', $_arr_col)) {
            $_arr_alter['app_sync'] = array('CHANGE', 'enum(\'' . $_str_sync . '\') NOT NULL COMMENT \'状态\'', 'app_sync');
        }

        if (in_array('app_key', $_arr_col)) {
            $_arr_alter['app_key'] = array('CHANGE', 'char(64) NOT NULL COMMENT \'通信密钥\'', 'app_key');
        }

        if (!in_array('app_secret', $_arr_col)) {
            $_arr_alter['app_secret'] = array('ADD', 'char(16) NOT NULL COMMENT \'密文密钥\'');
        }

        if (in_array('app_notice', $_arr_col)) {
            $_arr_alter['app_notice'] = array('CHANGE', 'varchar(3000) NOT NULL COMMENT \'通知接口 URL\'', 'app_url_notify');
        } else if (in_array('app_url_notice', $_arr_col)) {
            $_arr_alter['app_url_notice'] = array('CHANGE', 'varchar(3000) NOT NULL COMMENT \'通知接口 URL\'', 'app_url_notify');
        } else if (!in_array('app_url_notify', $_arr_col)) {
            $_arr_alter['app_url_notify'] = array('ADD', 'varchar(3000) NOT NULL COMMENT \'通知接口 URL\'');
        }

        if (!in_array('app_url_sync', $_arr_col)) {
            $_arr_alter['app_url_sync'] = array('ADD', 'varchar(3000) NOT NULL COMMENT \'同步登录通知 URL\'');
        }

        //print_r($_arr_alter);

        $_arr_appData = array(
            'app_url_sync' => 'app_url_notify',
        );
        $this->obj_db->update(BG_DB_TABLE . 'app', $_arr_appData, 'LENGTH(`app_url_sync`) < 1', true); //更新数据

        $_str_rcode = 'y050111';

        if (!fn_isEmpty($_arr_alter)) {
            $_reselt = $this->obj_db->alter_table(BG_DB_TABLE . 'app', $_arr_alter);

            if (!fn_isEmpty($_reselt)) {
                $_str_rcode = 'y050106';

                $_arr_appData = array(
                    'app_status' => $this->arr_status[0],
                );
                $this->obj_db->update(BG_DB_TABLE . 'app', $_arr_appData, 'LENGTH(`app_status`) < 1'); //更新数据

                $_arr_appData = array(
                    'app_sync' => $this->arr_sync[0],
                );
                $this->obj_db->update(BG_DB_TABLE . 'app', $_arr_appData, 'LENGTH(`app_sync`) < 1'); //更新数据
            }
        }

        return array(
            'rcode' => $_str_rcode,
        );
    }


    /** 重置 app key
     * mdl_reset function.
     *
     * @access public
     * @param mixed $num_appId
     * @return void
     */
    function mdl_reset($num_appId) {
        $_arr_appData = array(
            'app_key'       => fn_rand(64),
            'app_secret'    => fn_rand(16),
        );

        $_num_db = $this->obj_db->update(BG_DB_TABLE . 'app', $_arr_appData, '`app_id`=' . $num_appId); //更新数据

        if ($_num_db > 0) {
            $_str_rcode = 'y050103'; //更新成功
        } else {
            return array(
                'rcode' => 'x050103', //更新失败
            );
        }

        return array(
            'app_id'    => $num_appId,
            'rcode'     => $_str_rcode, //成功
        );
    }


    /** 提交
     * mdl_submit function.
     *
     * @access public
     * @return void
     */
    function mdl_submit($str_appAllow = '') {
        $_str_appKey    = '';
        $_str_appSecret = '';

        $_arr_appData = array(
            'app_name'          => $this->appInput['app_name'],
            'app_url_notify'    => $this->appInput['app_url_notify'],
            'app_url_sync'      => $this->appInput['app_url_sync'],
            'app_note'          => $this->appInput['app_note'],
            'app_status'        => $this->appInput['app_status'],
            'app_ip_allow'      => $this->appInput['app_ip_allow'],
            'app_ip_bad'        => $this->appInput['app_ip_bad'],
            'app_sync'          => $this->appInput['app_sync'],
        );

        if (!fn_isEmpty($str_appAllow)) {
            $_arr_appData['app_allow'] = $str_appAllow;
        } else if (isset($this->appInput['app_allow']) && !fn_isEmpty($this->appInput['app_allow'])) {
            $_arr_appData['app_allow'] = $this->appInput['app_allow'];
        }

        if ($this->appInput['app_id'] < 1) {
            $_str_appKey    = fn_rand(64);
            $_str_appSecret = fn_rand(16);
            $_arr_insert = array(
                'app_key'       => $_str_appKey,
                'app_secret'    => $_str_appSecret,
                'app_time'      => time(),
            );
            $_arr_data = array_merge($_arr_appData, $_arr_insert);

            $_num_appId = $this->obj_db->insert(BG_DB_TABLE . 'app', $_arr_data); //更新数据
            if ($_num_appId > 0) {
                $_str_rcode = 'y050101'; //更新成功
            } else {
                return array(
                    'rcode' => 'x050101', //更新失败
                );
            }
        } else {
            $_num_appId  = $this->appInput['app_id'];
            $_num_db     = $this->obj_db->update(BG_DB_TABLE . 'app', $_arr_appData, '`app_id`=' . $_num_appId); //更新数据
            if ($_num_db > 0) {
                $_str_rcode = 'y050103'; //更新成功
            } else {
                return array(
                    'rcode' => 'x050103', //更新失败
                );
            }
        }

        return array(
            'app_id'        => $_num_appId,
            'app_key'       => fn_baigoCrypt($_str_appKey, $this->appInput['app_name']),
            'app_secret'    => $_str_appSecret,
            'rcode'         => $_str_rcode, //成功
        );
    }


    /** 更改状态
     * mdl_status function.
     *
     * @access public
     * @param mixed $str_status
     * @return void
     */
    function mdl_status($str_status) {
        $_str_appId = implode(',', $this->appIds['app_ids']);

        $_arr_appUpdate = array(
            'app_status' => $str_status,
        );

        $_num_db = $this->obj_db->update(BG_DB_TABLE . 'app', $_arr_appUpdate, '`app_id` IN (' . $_str_appId . ')'); //删除数据

        //如影响行数大于0则返回成功
        if ($_num_db > 0) {
            $_str_rcode = 'y050103'; //成功
        } else {
            $_str_rcode = 'x050103'; //失败
        }

        return array(
            'rcode' => $_str_rcode,
        );
    }


    /** 读取
     * mdl_read function.
     *
     * @access public
     * @param mixed $str_app
     * @param string $str_by (default: 'app_id')
     * @param int $num_notId (default: 0)
     * @return void
     */
    function mdl_read($str_app, $str_by = 'app_id', $num_notId = 0) {
        $_arr_appSelect = array(
            'app_id',
            'app_name',
            'app_url_notify',
            'app_url_sync',
            'app_key',
            'app_secret',
            'app_note',
            'app_status',
            'app_time',
            'app_ip_allow',
            'app_ip_bad',
            'app_sync',
            'app_allow',
        );

        if (is_numeric($str_app)) {
            $_str_sqlWhere = $str_by . '=' . $str_app;
        } else {
            $_str_sqlWhere = $str_by . '=\'' . $str_app . '\'';
        }

        if ($num_notId > 0) {
            $_str_sqlWhere .= ' AND `app_id`<>' . $num_notId;
        }

        $_arr_appRows = $this->obj_db->select(BG_DB_TABLE . 'app', $_arr_appSelect, $_str_sqlWhere, '', '', 1, 0); //检查本地表是否存在记录

        if (isset($_arr_appRows[0])) { //用户名不存在则返回错误
            $_arr_appRow = $_arr_appRows[0];
        } else {
            return array(
                'rcode' => 'x050102', //不存在记录
            );
        }

        if (isset($_arr_appRow['app_allow'])) {
            $_arr_appRow['app_allow'] = fn_jsonDecode($_arr_appRow['app_allow']);
        } else {
            $_arr_appRow['app_allow'] = array();
        }

        if (fn_isEmpty($_arr_appRow['app_url_sync'])) {
            $_arr_appRow['app_url_sync'] = $_arr_appRow['app_url_notify'];
        }

        $_arr_appRow['app_url_notify']  = fn_htmlcode($_arr_appRow['app_url_notify'], 'decode', 'url');
        $_arr_appRow['app_url_sync']    = fn_htmlcode($_arr_appRow['app_url_sync'], 'decode', 'url');

        $_arr_appRow['rcode'] = 'y050102';

        return $_arr_appRow;
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
        $_arr_appSelect = array(
            'app_id',
            'app_key',
            'app_secret',
            'app_name',
            'app_url_notify',
            'app_url_sync',
            'app_note',
            'app_status',
            'app_time',
        );

        $_str_sqlWhere = $this->sql_process($arr_search);

        $_arr_order = array(
            array('app_id', 'DESC'),
        );

        $_arr_appRows = $this->obj_db->select(BG_DB_TABLE . 'app', $_arr_appSelect, $_str_sqlWhere, '', $_arr_order, $num_no, $num_except); //查询数据

        return $_arr_appRows;
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

        $_num_appCount = $this->obj_db->count(BG_DB_TABLE . 'app', $_str_sqlWhere); //查询数据

        return $_num_appCount;
    }


    /** 删除
     * mdl_del function.
     *
     * @access public
     * @return void
     */
    function mdl_del() {
        $_str_appId = implode(',', $this->appIds['app_ids']);

        $_num_db = $this->obj_db->delete(BG_DB_TABLE . 'app', '`app_id` IN (' . $_str_appId . ')'); //删除数据

        //如车影响行数小于0则返回错误
        if ($_num_db > 0) {
            $_str_rcode = 'y050104'; //成功
        } else {
            $_str_rcode = 'x050104'; //失败
        }

        return array(
            'rcode' => $_str_rcode,
        );
    }


    /** 读取 app 信息
     * input_api function.
     *
     * @access public
     * @return void
     */
    function input_api($str_method = 'get') {
        if ($str_method == 'post') {
            $_num_appId     = fn_post('app_id');
            $_str_appKey    = fn_post('app_key');
            $_tm_time       = fn_post('time');
            $_str_sign      = fn_post('sign');
            $_str_code      = fn_post('code');
        } else {
            $_num_appId     = fn_get('app_id');
            $_str_appKey    = fn_get('app_key');
            $_tm_time       = fn_get('time');
            $_str_sign      = fn_get('sign');
            $_str_code      = fn_get('code');
        }

        $_arr_appId = fn_validate($_num_appId, 1, 0, 'str', 'int');
        switch ($_arr_appId['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x050203',
                );
            break;

            case 'format_err':
                return array(
                    'rcode' => 'x050204',
                );
            break;

            case 'ok':
                $_arr_inputApi['app_id'] = $_arr_appId['str'];
            break;
        }

        $_arr_appKey = fn_validate($_str_appKey, 32, 32);
        switch ($_arr_appKey['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x050214',
                );
            break;

            case 'too_long':
                return array(
                    'rcode' => 'x050215',
                );
            break;

            /*case 'format_err':
                return array(
                    'rcode' => 'x050216',
                );
            break;*/

            case 'ok':
                $_arr_inputApi['app_key'] = $_arr_appKey['str'];
            break;
        }

        $_arr_time = fn_validate($_tm_time, 1, 0, 'str', 'int');
        switch ($_arr_time['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x050224',
                );
            break;

            case 'format_err':
                return array(
                    'rcode' => 'x050225',
                );
            break;

            case 'ok':
                $_arr_inputApi['time'] = $_arr_time['str'];
            break;
        }

        $_tm_diff = $_arr_inputApi['time'] - time();

        if ($_tm_diff > 1800 || $_tm_diff < -1800) {
            return array(
                'rcode' => 'x050227',
            );
        }

        $_arr_sign = fn_validate($_str_sign, 32, 32);
        switch ($_arr_appId['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x050226',
                );
            break;

            case 'too_long':
                return array(
                    'rcode' => 'x050228',
                );
            break;

            case 'ok':
                $_arr_inputApi['sign'] = $_arr_sign['str'];
            break;
        }

        $_arr_code = fn_validate($_str_code, 1, 0);
        switch ($_arr_appId['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x050229',
                );
            break;

            case 'ok':
                $_arr_inputApi['code'] = $_arr_code['str'];
            break;
        }

        $_arr_inputApi['rcode'] = 'ok';

        return $_arr_inputApi;
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

        $this->appInput['app_id'] = fn_getSafe(fn_post('app_id'), 'int', 0);

        if ($this->appInput['app_id'] > 0) {
            //检查用户是否存在
            $_arr_appRow = $this->mdl_read($this->appInput['app_id']);
            if ($_arr_appRow['rcode'] != 'y050102') {
                return $_arr_appRow;
            }
        }

        $_arr_appName = fn_validate(fn_post('app_name'), 1, 30);
        switch ($_arr_appName['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x050201',
                );
            break;

            case 'too_long':
                return array(
                    'rcode' => 'x050202',
                );
            break;

            case 'ok':
                $this->appInput['app_name'] = $_arr_appName['str'];
            break;

        }


        $_arr_appUrlNotify = fn_validate(fn_post('app_url_notify'), 1, 3000);
        switch ($_arr_appUrlNotify['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x050207',
                );
            break;

            case 'too_long':
                return array(
                    'rcode' => 'x050208',
                );
            break;

            case 'format_err':
                return array(
                    'rcode' => 'x050209',
                );
            break;

            case 'ok':
                $this->appInput['app_url_notify'] = $_arr_appUrlNotify['str'];
            break;
        }


        $_arr_appUrlSync = fn_validate(fn_post('app_url_sync'), 1, 3000);
        switch ($_arr_appUrlSync['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x050219',
                );
            break;

            case 'too_long':
                return array(
                    'rcode' => 'x050220',
                );
            break;

            case 'format_err':
                return array(
                    'rcode' => 'x050221',
                );
            break;

            case 'ok':
                $this->appInput['app_url_sync'] = $_arr_appUrlSync['str'];
            break;
        }


        $_arr_appNote = fn_validate(fn_post('app_note'), 0, 30);
        switch ($_arr_appNote['status']) {
            case 'too_long':
                return array(
                    'rcode' => 'x050205',
                );
            break;

            case 'ok':
                $this->appInput['app_note'] = $_arr_appNote['str'];
            break;

        }

        $_arr_appStatus = fn_validate(fn_post('app_status'), 1, 0);
        switch ($_arr_appStatus['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x050206',
                );
            break;

            case 'ok':
                $this->appInput['app_status'] = $_arr_appStatus['str'];
            break;
        }

        $_arr_appIpAllow = fn_validate(fn_post('app_ip_allow'), 0, 3000);
        switch ($_arr_appIpAllow['status']) {
            case 'too_long':
                return array(
                    'rcode' => 'x050210',
                );
            break;

            case 'ok':
                $this->appInput['app_ip_allow'] = $_arr_appIpAllow['str'];
            break;
        }

        $_arr_appIpBad = fn_validate(fn_post('app_ip_bad'), 0, 3000);
        switch ($_arr_appIpBad['status']) {
            case 'too_long':
                return array(
                    'rcode' => 'x050211',
                );
            break;

            case 'ok':
                $this->appInput['app_ip_bad'] = $_arr_appIpBad['str'];
            break;
        }

        $_arr_appSync = fn_validate(fn_post('app_sync'), 1, 0);
        switch ($_arr_appSync['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x050218',
                );
            break;

            case 'ok':
                $this->appInput['app_sync'] = $_arr_appSync['str'];
            break;
        }

        $this->appInput['app_allow'] = fn_jsonEncode(fn_post('app_allow'));

        $this->appInput['rcode']     = 'ok';

        return $this->appInput;
    }


    /**
     * input_setup function.
     *
     * @access public
     * @return void
     */
    function input_setup() {
        $_arr_appName = fn_validate(fn_post('app_name'), 1, 30);
        switch ($_arr_appName['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x050201',
                );
            break;

            case 'too_long':
                return array(
                    'rcode' => 'x050202',
                );
            break;

            case 'ok':
                $this->appInput['app_name'] = $_arr_appName['str'];
            break;

        }

        $_arr_appUrlNotify = fn_validate(fn_post('app_url_notify'), 1, 3000);
        switch ($_arr_appUrlNotify['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x050207',
                );
            break;

            case 'too_long':
                return array(
                    'rcode' => 'x050208',
                );
            break;

            case 'format_err':
                return array(
                    'rcode' => 'x050209',
                );
            break;

            case 'ok':
                $this->appInput['app_url_notify'] = fn_htmlcode($_arr_appUrlNotify['str'], 'decode', 'url');
            break;
        }

        $_arr_appUrlSync = fn_validate(fn_post('app_url_sync'), 1, 3000);
        switch ($_arr_appUrlSync['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x050219',
                );
            break;

            case 'too_long':
                return array(
                    'rcode' => 'x050220',
                );
            break;

            case 'format_err':
                return array(
                    'rcode' => 'x050221',
                );
            break;

            case 'ok':
                $this->appInput['app_url_sync'] = fn_htmlcode($_arr_appUrlSync['str'], 'decode', 'url');
            break;
        }

        $this->appInput['app_allow']    = fn_post('app_allow');

        $this->appInput['app_note']     = $this->appInput['app_name'];
        $this->appInput['app_status']   = 'enable';
        $this->appInput['app_ip_allow'] = '';
        $this->appInput['app_ip_bad']   = '';
        $this->appInput['app_sync']     = 'on';
        $this->appInput['app_id']       = 0;

        $this->appInput['rcode']        = 'ok';

        return $this->appInput;
    }


    /** 选择 app
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

        $_arr_appIds = fn_post('app_ids');

        if (fn_isEmpty($_arr_appIds)) {
            $_str_rcode = 'x030202';
        } else {
            foreach ($_arr_appIds as $_key=>$_value) {
                $_arr_appIds[$_key] = fn_getSafe($_value, 'int', 0);
            }
            $_str_rcode = 'ok';
        }

        $this->appIds = array(
            'rcode'     => $_str_rcode,
            'app_ids'   => array_filter(array_unique($_arr_appIds)),
        );

        return $this->appIds;
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
            $_str_sqlWhere .= ' AND (`app_name` LIKE \'%' . $arr_search['key'] . '%\' OR `app_note` LIKE \'%' . $arr_search['key'] . '%\')';
        }

        if (isset($arr_search['status']) && !fn_isEmpty($arr_search['status'])) {
            $_str_sqlWhere .= ' AND `app_status`=\'' . $arr_search['status'] . '\'';
        }

        if (isset($arr_search['sync']) && !fn_isEmpty($arr_search['sync'])) {
            $_str_sqlWhere .= ' AND `app_sync`=\'' . $arr_search['sync'] . '\'';
        }

        if (isset($arr_search['has_notify'])) {
            $_str_sqlWhere .= ' AND LENGTH(`app_url_notify`)>0';
        }

        if (isset($arr_search['has_sync'])) {
            $_str_sqlWhere .= ' AND LENGTH(`app_url_sync`)>0';
        }

        if (isset($arr_search['not_ids']) && !fn_isEmpty($arr_search['not_ids'])) {
            $_str_appIds     = implode(',', $arr_search['not_ids']);
            $_str_sqlWhere  .= ' AND `app_id` NOT IN (' . $_str_appIds . ')';
        }

        return $_str_sqlWhere;
    }
}
