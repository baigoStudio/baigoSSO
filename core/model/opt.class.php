<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

/*-------------设置项模型-------------*/
class MODEL_OPT {

    public $obj_dir;
    public $arr_const;
    public $dbconfigInput;

    function __construct() { //构造函数
        $this->obj_dir = new CLASS_DIR();
    }

    /** 处理常量并生成配置文件
     * mdl_const function.
     *
     * @access public
     * @param mixed $str_type
     * @return void
     */
    function mdl_const($str_type) {
        if (!fn_token('chk')) { //令牌
            return array(
                'rcode' => 'x030206',
            );
        }

        $_str_content = '<?php' . PHP_EOL;
        foreach ($this->arr_const[$str_type] as $_key=>$_value) {
            if (is_numeric($_value)) {
                $_str_content .= 'define(\'' . $_key . '\', ' . $_value . ');' . PHP_EOL;
            } else {
                $_str_content .= 'define(\'' . $_key . '\', \'' . rtrim(str_ireplace(PHP_EOL, '|', $_value), '/\\') . '\');' . PHP_EOL;
            }
        }

        if ($str_type == 'base') {
            $_str_content .= 'define(\'BG_SITE_SSIN\', \'' . fn_rand(6) . '\');' . PHP_EOL;
            //$_str_content .= 'define(\'BG_SITE_TPL\', \'default\');' . PHP_EOL;
        }

        $_str_content = str_ireplace('||', '', $_str_content);

        $_num_size    = $this->obj_dir->put_file(BG_PATH_CONFIG . 'opt_' . $str_type . '.inc.php', $_str_content);

        if ($_num_size > 0) {
            $_str_rcode = 'y030405';
        } else {
            $_str_rcode = 'x030405';
        }

        return array(
            'rcode' => $_str_rcode,
        );
    }


    /** 生成已安装文件
     * mdl_over function.
     *
     * @access public
     * @return void
     */
    function mdl_over() {
        if (!fn_token('chk')) { //令牌
            return array(
                'rcode' => 'x030206',
            );
        }

        $_str_content = '<?php' . PHP_EOL;
        $_str_content .= 'define(\'BG_INSTALL_VER\', \'' . PRD_SSO_VER . '\');' . PHP_EOL;
        $_str_content .= 'define(\'BG_INSTALL_PUB\', ' . PRD_SSO_PUB . ');' . PHP_EOL;
        $_str_content .= 'define(\'BG_INSTALL_TIME\', ' . time() . ');' . PHP_EOL;

        $_num_size = $this->obj_dir->put_file(BG_PATH_CONFIG . 'installed.php', $_str_content);
        if ($_num_size > 0) {
            $_str_rcode = 'y030405';
        } else {
            $_str_rcode = 'x030405';
        }

        return array(
            'rcode' => $_str_rcode,
        );
    }


    /** 生成数据库配置文件
     * mdl_dbconfig function.
     *
     * @access public
     * @return void
     */
    function mdl_dbconfig() {
        $_str_content = '<?php' . PHP_EOL;
        $_str_content .= 'define(\'BG_DB_HOST\', \'' . $this->dbconfigInput['db_host'] . '\');' . PHP_EOL;
        $_str_content .= 'define(\'BG_DB_NAME\', \'' . $this->dbconfigInput['db_name'] . '\');' . PHP_EOL;
        $_str_content .= 'define(\'BG_DB_PORT\', \'' . $this->dbconfigInput['db_port'] . '\');' . PHP_EOL;
        $_str_content .= 'define(\'BG_DB_USER\', \'' . $this->dbconfigInput['db_user'] . '\');' . PHP_EOL;
        $_str_content .= 'define(\'BG_DB_PASS\', \'' . $this->dbconfigInput['db_pass'] . '\');' . PHP_EOL;
        $_str_content .= 'define(\'BG_DB_CHARSET\', \'' . $this->dbconfigInput['db_charset'] . '\');' . PHP_EOL;
        $_str_content .= 'define(\'BG_DB_TABLE\', \'' . $this->dbconfigInput['db_table'] . '\');' . PHP_EOL;

        $_num_size = $this->obj_dir->put_file(BG_PATH_CONFIG . 'opt_dbconfig.inc.php', $_str_content);
        if ($_num_size > 0) {
            $_str_rcode = 'y030404';
        } else {
            $_str_rcode = 'x030404';
        }

        return array(
            'rcode' => $_str_rcode,
        );
    }


    /** 数据库配置表单验证
     * input_dbconfig function.
     *
     * @access public
     * @param bool $is_token (default: true)
     * @return void
     */
    function input_dbconfig($is_token = true) {
        if ($is_token) {
            if (!fn_token('chk')) { //令牌
                return array(
                    'rcode' => 'x030206',
                );
            }
        }

        $_arr_dbHost = fn_validate(fn_post('db_host'), 1, 900);
        switch ($_arr_dbHost['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x040204',
                );
            break;

            case 'too_long':
                return array(
                    'rcode' => 'x040205',
                );
            break;

            case 'ok':
                $this->dbconfigInput['db_host'] = $_arr_dbHost['str'];
            break;
        }

        $_arr_dbName = fn_validate(fn_post('db_name'), 1, 900);
        switch ($_arr_dbName['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x040206',
                );
            break;

            case 'too_long':
                return array(
                    'rcode' => 'x040207',
                );
            break;

            case 'ok':
                $this->dbconfigInput['db_name'] = $_arr_dbName['str'];
            break;
        }

        $_arr_dbPort = fn_validate(fn_post('db_port'), 1, 900);
        switch ($_arr_dbPort['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x040208',
                );
            break;

            case 'too_long':
                return array(
                    'rcode' => 'x040209',
                );
            break;

            case 'ok':
                $this->dbconfigInput['db_port'] = $_arr_dbPort['str'];
            break;
        }

        $_arr_dbUser = fn_validate(fn_post('db_user'), 1, 900);
        switch ($_arr_dbUser['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x040210',
                );
            break;

            case 'too_long':
                return array(
                    'rcode' => 'x040211',
                );
            break;

            case 'ok':
                $this->dbconfigInput['db_user'] = $_arr_dbUser['str'];
            break;
        }

        $_arr_dbPass = fn_validate(fn_post('db_pass'), 1, 900);
        switch ($_arr_dbPass['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x040212',
                );
            break;

            case 'too_long':
                return array(
                    'rcode' => 'x040213',
                );
            break;

            case 'ok':
                $this->dbconfigInput['db_pass'] = $_arr_dbPass['str'];
            break;
        }

        $_arr_dbCharset = fn_validate(fn_post('db_charset'), 1, 900);
        switch ($_arr_dbCharset['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x040214',
                );
            break;

            case 'too_long':
                return array(
                    'rcode' => 'x040215',
                );
            break;

            case 'ok':
                $this->dbconfigInput['db_charset'] = $_arr_dbCharset['str'];
            break;
        }

        $_arr_dbtable = fn_validate(fn_post('db_table'), 1, 900);
        switch ($_arr_dbtable['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x040216',
                );
            break;

            case 'too_long':
                return array(
                    'rcode' => 'x040217',
                );
            break;

            case 'ok':
                $this->dbconfigInput['db_table'] = $_arr_dbtable['str'];
            break;
        }

        $this->dbconfigInput['rcode'] = 'ok';

        return $this->dbconfigInput;
    }


    /** 表单输入处理
     * input_const function.
     *
     * @access public
     * @param mixed $str_type
     * @return void
     */
    function input_const($str_type, $is_token = true) {
        if ($is_token) {
            if (!fn_token('chk')) { //令牌
                return array(
                    'rcode' => 'x030206',
                );
            }
        }

        $this->arr_const = fn_post('opt');

        return $this->arr_const[$str_type];
    }


    function chk_ver($is_check = false, $method = 'auto') {
        if (!file_exists(BG_PATH_CACHE . 'sys' . DS . 'latest_ver.json')) {
            $this->ver_process($method);
        }

        $_str_ver = file_get_contents(BG_PATH_CACHE . 'sys' . DS . 'latest_ver.json');
        $_arr_ver = json_decode($_str_ver, true);

        if ($is_check || !$_arr_ver || !isset($_arr_ver['time']) || $_arr_ver['time'] - time() > 30 * 86400 || isset($_arr_ver['error'])) {
            $this->ver_process($method);
            $_str_ver = file_get_contents(BG_PATH_CACHE . 'sys' . DS . 'latest_ver.json');
            $_arr_ver = json_decode($_str_ver, true);
        }

        if (isset($_arr_ver['prd_pub'])) {
            $_arr_ver['prd_pub'] = strtotime($_arr_ver['prd_pub']);
        }

        return $_arr_ver;
    }


    function ver_process($method = 'auto') {
        $_arr_data = array(
            'name'      => 'baigo SSO',
            'ver'       => PRD_SSO_VER,
            'referer'   => fn_forward(fn_server('SERVER_NAME') . BG_URL_ROOT),
            'method'    => $method,
        );

        $_str_ver = fn_http(PRD_VER_CHECK, $_arr_data, 'get');

        $this->obj_dir->put_file(BG_PATH_CACHE . 'sys' . DS . 'latest_ver.json', $_str_ver['ret']);
    }
}
