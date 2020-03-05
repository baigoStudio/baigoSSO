<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\api;

use app\classes\Ctrl;
use PDO;
use ginkgo\Loader;
use ginkgo\Config;
use ginkgo\Func;
use ginkgo\Json;
use ginkgo\Sign;
use ginkgo\Crypt;
use ginkgo\Db;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------管理员控制器-------------*/
class Install extends Ctrl {

    protected $decryptRow;
    protected $version;

    private $security = array(
        'key'       => '',
        'secret'    => '',
    );

    protected function c_init($param = array()) {
        parent::c_init();

        $_arr_version = array(
            'prd_sso_ver' => PRD_SSO_VER,
            'prd_sso_pub' => PRD_SSO_PUB,
        );

        $this->version = $_arr_version;

        $this->dbconfig     = Config::get('dbconfig');

        $this->mdl_opt      = Loader::model('Opt');
    }


    function security() {
        $_mix_init = $this->init(false);

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        $_arr_inputSecurity = $this->mdl_opt->inputSecurity($this->decryptRow);

        if ($_arr_inputSecurity['rcode'] != 'y030201') {
            return $this->fetchJson($_arr_inputSecurity['msg'], $_arr_inputSecurity['rcode']);
        }

        $_arr_securityResult = $this->mdl_opt->security();

        $_arr_tpl = array_replace_recursive($this->version, $_arr_securityResult);

        $_arr_tpl['msg'] = $this->obj_lang->get($_arr_tpl['msg']);

        return $this->json($_arr_tpl);
    }


    function getStatus() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_arr_inputStatus = $this->mdl_opt->inputTimestamp($this->decryptRow);

        if ($_arr_inputStatus['rcode'] != 'y030201') {
            return $this->fetchJson($_arr_inputStatus['msg'], $_arr_inputStatus['rcode']);
        }

        $_arr_dataRows = array();

        $_arr_tableInstalled = $this->showTables();

        $_arr_configData = Config::get('index', 'install.data');

        foreach ($_arr_configData as $_key=>$_value) {
            foreach ($_value['lists'] as $_key_data=>$_value_data) {
                $_str_name = strtolower($this->dbconfig['prefix'] . $_value_data);

                if (in_array($_str_name, $_arr_tableInstalled)) {
                    switch ($_key) {
                        case 'view':
                            $_arr_status = array(
                                'rcode'     => 'y030401',
                                'status'    => 'y',
                                'msg'       => $this->obj_lang->get('Create view successfully'),
                            );
                        break;

                        default:
                            $_arr_status = array(
                                'rcode'     => 'y030401',
                                'status'    => 'y',
                                'msg'       => $this->obj_lang->get('Create table successfully'),
                            );
                        break;
                    }

                    $_arr_dataRows[$_key][$_str_name] = $_arr_status;
                } else {
                    switch ($_key) {
                        case 'view':
                            $_arr_status = array(
                                'rcode'     => 'x030401',
                                'status'    => 'x',
                                'msg'       => $this->obj_lang->get('Create view failed'),
                            );
                        break;

                        default:
                            $_arr_status = array(
                                'rcode'     => 'x030401',
                                'status'    => 'x',
                                'msg'       => $this->obj_lang->get('Create table failed'),
                            );
                        break;
                    }

                    $_arr_dataRows[$_key][$_str_name] = $_arr_status;
                }
            }
        }

        $_mdl_admin = Loader::model('Admin');

        $_arr_admin = $_mdl_admin->lists(1);

        //print_r($_arr_dataRows);

        $_arr_src = array(
            'data'      => $_arr_dataRows,
            'dbconfig'  => Config::get('dbconfig'),
            'admin'     => $_arr_admin,
            'timestamp' => GK_NOW,
        );

        $_str_src       = Json::encode($_arr_src);

        $_str_sign      = Sign::make($_str_src, $this->security['key'] . $this->security['secret']);

        $_str_encrypt   = Crypt::encrypt($_str_src, $this->security['key'], $this->security['secret']);

        if ($_str_encrypt === false) {
            $_str_error = Crypt::getError();
            return $this->fetchJson($_str_error, 'x030408', 200, '', 'api.common');
        }

        $_arr_data = array(
            'rcode' => 'y030402',
            //'msg'   => $this->obj_lang->get($_arr_submitResult['msg']),
            'code'  => $_str_encrypt,
            'sign'  => $_str_sign,
        );

        $_arr_tpl = array_replace_recursive($this->version, $_arr_data);

        return $this->json($_arr_tpl);
    }


    function dbconfig() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        $_arr_inputDbconfig = $this->mdl_opt->inputDbconfig($this->decryptRow);

        if ($_arr_inputDbconfig['rcode'] != 'y030201') {
            return $this->fetchJson($_arr_inputDbconfig['msg'], $_arr_inputDbconfig['rcode']);
        }

        $_arr_dbconfigResult = $this->mdl_opt->dbconfig();

        $_arr_tpl = array_replace_recursive($this->version, $_arr_dbconfigResult);

        $_arr_tpl['msg'] = $this->obj_lang->get($_arr_tpl['msg']);

        return $this->json($_arr_tpl);
    }


    function data() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        $_arr_inputData = $this->mdl_opt->inputTimestamp($this->decryptRow);

        if ($_arr_inputData['rcode'] != 'y030201') {
            return $this->fetchJson($_arr_inputData['msg'], $_arr_inputData['rcode']);
        }

        $_arr_configData = Config::get('index', 'install.data');

        foreach ($_arr_configData as $_key=>$_value) {
            foreach ($_value['lists'] as $_key_data=>$_value_data) {
                switch ($_key) {
                    case 'view':
                        $_arr_dataResult = $this->createView($_value_data);
                    break;

                    default:
                        $_arr_dataResult = $this->createTable($_value_data);
                    break;
                }

                $_str_status = substr($_arr_dataResult['rcode'], 0, 1);

                if ($_str_status == 'x') {
                    return $this->fetchJson($_arr_dataResult['msg'], $_arr_dataResult['rcode']);
                }
            }
        }

        $_arr_return = array(
            'rcode' => 'y030401',
            'msg'   => 'Create data successfully',
        );

        $_arr_tpl = array_replace_recursive($this->version, $_arr_return);

        $_arr_tpl['msg'] = $this->obj_lang->get($_arr_tpl['msg']);

        return $this->json($_arr_tpl);
    }


    function admin() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        $_mdl_user  = Loader::model('Reg');
        $_mdl_admin = Loader::model('Admin');

        $_arr_inputSubmit  = $_mdl_admin->inputSubmit($this->decryptRow);

        if ($_arr_inputSubmit['rcode'] != 'y020201') {
            return $this->fetchJson($_arr_inputSubmit['msg'], $_arr_inputSubmit['rcode']);
        }

        //检验用户名是否重复
        $_arr_userRow = $_mdl_user->check($_arr_inputSubmit['admin_name'], 'user_name');

        if ($_arr_userRow['rcode'] == 'y010102') {
            return $this->fetchJson('User already exists', 'x010404');
        }

        $_str_rand                          = Func::rand();

        $_mdl_user->inputReg['user_name']   = $_arr_inputSubmit['admin_name'];
        $_mdl_user->inputReg['user_mail']   = $_arr_inputSubmit['admin_mail'];
        $_mdl_user->inputReg['user_pass']   = Crypt::crypt($_arr_inputSubmit['admin_pass'], $_str_rand, true);
        $_mdl_user->inputReg['user_rand']   = $_str_rand;
        $_mdl_user->inputReg['user_status'] = 'enable';
        $_mdl_user->inputReg['user_nick']   = $_arr_inputSubmit['admin_name'];
        $_mdl_user->inputReg['user_note']   = $_arr_inputSubmit['admin_name'];

        $_arr_regResult     = $_mdl_user->reg();

        if ($_arr_regResult['rcode'] != 'y010101') {
            return $this->fetchJson($_arr_regResult['msg'], $_arr_regResult['rcode']);
        }

        $_mdl_admin->inputSubmit['admin_id'] = $_arr_regResult['user_id'];

        $_arr_submitResult                   = $_mdl_admin->submit();

        $_arr_submitResult['timestamp'] = GK_NOW;

        $_str_src       = Json::encode($_arr_submitResult);

        $_str_sign      = Sign::make($_str_src, $this->security['key'] . $this->security['secret']);

        $_str_encrypt   = Crypt::encrypt($_str_src, $this->security['key'], $this->security['secret']);

        if ($_str_encrypt === false) {
            $_str_error = Crypt::getError();
            return $this->fetchJson($_str_error, 'x030408', 200, '', 'api.common');
        }

        $_arr_data = array(
            'rcode' => $_arr_submitResult['rcode'],
            'msg'   => $this->obj_lang->get($_arr_submitResult['msg']),
            'code'  => $_str_encrypt,
            'sign'  => $_str_sign,
        );

        $_arr_tpl = array_replace_recursive($this->version, $_arr_data);

        return $this->json($_arr_tpl);
    }


    function over() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        $_mdl_app   = Loader::model('App');

        $_arr_inputSubmit = $_mdl_app->inputSubmit($this->decryptRow);

        if ($_arr_inputSubmit['rcode'] != 'y050201') {
            return $this->fetchJson($_arr_inputSubmit['msg'], $_arr_inputSubmit['rcode']);
        }

        $_arr_submitResult  = $_mdl_app->submit();

        if ($_arr_submitResult['rcode'] != 'y050101') {
            return $this->fetchJson($_arr_submitResult['msg'], $_arr_submitResult['rcode']);
        }

        //file_put_contents('encode.txt', json_encode($_arr_submitResult));

        $_arr_overResult = $this->mdl_opt->over();

        if ($_arr_overResult['rcode'] != 'y030401') {
            return $this->fetchJson($_arr_overResult['msg'], $_arr_overResult['rcode']);
        }

        $_arr_submitResult['timestamp'] = GK_NOW;

        $_arr_submitResult['base_url']  = $this->obj_request->baseUrl(true) . '/api/';

        $_str_src       = Json::encode($_arr_submitResult);

        $_str_sign      = Sign::make($_str_src, $this->security['key'] . $this->security['secret']);

        $_str_encrypt   = Crypt::encrypt($_str_src, $this->security['key'], $this->security['secret']);

        if ($_str_encrypt === false) {
            $_str_error = Crypt::getError();
            return $this->fetchJson($_str_error, 'x030408', 200, '', 'api.common');
        }

        $_arr_data = array(
            'rcode' => $_arr_submitResult['rcode'],
            'msg'   => $this->obj_lang->get($_arr_submitResult['msg']),
            'code'  => $_str_encrypt,
            'sign'  => $_str_sign,
        );

        $_arr_tpl = array_replace_recursive($this->version, $_arr_data);

        return $this->json($_arr_tpl);
    }


    protected function init($chk_security = true) {
        $_arr_chkResult = $this->chkInstall();

        if (!Func::isEmpty($_arr_chkResult['rcode'])) {
            return $_arr_chkResult;
        }

        $_arr_phpResult = $this->chkPhplib();

        if (!Func::isEmpty($_arr_phpResult['rcode'])) {
            return $_arr_phpResult;
        }

        $_arr_inputCommon = $this->mdl_opt->inputCommon();

        if ($_arr_inputCommon['rcode'] != 'y030201') {
            return array(
                'rcode' => $_arr_inputCommon['rcode'],
                'msg'   => $this->obj_lang->get($_arr_inputCommon['msg'], 'api.common'),
            );
        }

        if ($chk_security) {
            if ($_arr_inputCommon['key'] != $this->security['key']) {
                return array(
                    'rcode' => 'x030201',
                    'msg'   => $this->obj_lang->get('Security is incorrect', 'api.common'),
                );
            }

            $_str_decrypt = Crypt::decrypt($_arr_inputCommon['code'], $this->security['key'], $this->security['secret']);  //解密数据

            if ($_str_decrypt === false) {
                $_str_error = Crypt::getError();
                return array(
                    'rcode' => 'x030407',
                    'msg'   => $this->obj_lang->get($_str_error, 'api.common'),
                );
            }

            //print_r($_arr_inputCommon);

            if (!Sign::check($_str_decrypt, $_arr_inputCommon['sign'], $this->security['key'] . $this->security['secret'])) {
                return array(
                    'rcode' => 'x030406',
                    'msg'   => $this->obj_lang->get('Signature is incorrect', 'api.common'),
                );
            }

            $_arr_decryptRow = Json::decode($_str_decrypt);
        } else {
            if (!Sign::check($_arr_inputCommon['code'], $_arr_inputCommon['sign'], $_arr_inputCommon['key'])) {
                return array(
                    'rcode' => 'x030406',
                    'msg'   => $this->obj_lang->get('Signature is incorrect', 'api.common'),
                );
            }

            $_arr_decryptRow = Json::decode($_arr_inputCommon['code']);
        }

        $this->decryptRow   = $_arr_decryptRow;

        return true;
    }


    protected function configProcess() {
        parent::configProcess();

        $_str_configInstall  = BG_PATH_CONFIG . 'install' . DS . 'common' . GK_EXT_INC;
        Config::load($_str_configInstall, 'install');

        $_str_configPhplib   = BG_PATH_CONFIG . 'install' . DS . 'phplib' . GK_EXT_INC;
        $this->phplib        = Config::load($_str_configPhplib, 'phplib');

        $_str_configSecurity = GK_PATH_TEMP . 'security' . GK_EXT_INC;

        if (Func::isFile($_str_configSecurity)) {
            $_arr_security  = Loader::load($_str_configSecurity);

            $this->security = array_replace_recursive($this->security, $_arr_security);
        }
    }


    private function chkInstall() {
        $_str_rcode = '';

        $_str_rcode     = '';
        $_str_jump      = '';
        $_str_msg       = '';

        $_str_configInstalled   = GK_APP_CONFIG . 'installed' . GK_EXT_INC;

        if (Func::isFile($_str_configInstalled)) { //如果新文件存在
            $_arr_installed = Config::load($_str_configInstalled, 'installed');
            $_str_rcode     = 'x030402';
            $_str_msg       = 'SSO Already installed';

            if (isset($_arr_installed['prd_installed_pub']) && PRD_SSO_PUB > $_arr_installed['prd_installed_pub']) { //如果小于当前版本
                $_str_rcode     = 'x030404';
                $_str_msg       = 'SSO Need to execute the upgrader';
            }
        }

        if (!Func::isEmpty($_str_rcode)) {
            return array(
                'rcode' => $_str_rcode,
                'msg'   => $this->obj_lang->get($_str_msg, 'api.common'),
            );
        }
    }


    private function chkPhplib() {
        $_str_rcode     = '';
        $_str_msg       = '';

        $_arr_phplibInstalled = get_loaded_extensions();
        $_arr_phplibInstalled = array_map('strtolower', $_arr_phplibInstalled);

        $_num_errCount  = 0;

        foreach ($this->phplib as $_key=>$_value) {
            if (!in_array($_key, $_arr_phplibInstalled)) {
                ++$_num_errCount;
            }
        }

        if ($_num_errCount > 0) {
            $_str_rcode     = 'x030405';
            $_str_msg       = 'Missing PHP extensions';
        }

        return array(
            'rcode' => $_str_rcode,
            'msg'   => $this->obj_lang->get($_str_msg, 'api.common'),
        );
    }


    private function showTables() {
        $_str_sql = 'SHOW TABLES FROM `' . $this->dbconfig['name'] . '`';

        $_query_result  = Db::query($_str_sql);

        $_arr_tables    = Db::getResult(true, PDO::FETCH_NUM);

        $_arr_return    = array();

        if (!Func::isEmpty($_arr_tables)) {
            foreach ($_arr_tables as $_key=>$_value) {
                if (isset($_value[0])) {
                    $_arr_return[] = $_value[0];
                }
            }
        }

        return $_arr_return;
    }


    protected function createTable($table) {
        $_mdl_table         = Loader::model($table, '', 'install');
        $_arr_creatResult   = $_mdl_table->createTable();

       return array(
            'rcode'   => $_arr_creatResult['rcode'],
            'msg'     => $_arr_creatResult['msg'],
        );
    }


    protected function createView($view) {
        $_mdl_view          = Loader::model($view, '', 'install');
        $_arr_creatResult   = $_mdl_view->createView();

        return array(
            'rcode'   => $_arr_creatResult['rcode'],
            'msg'     => $_arr_creatResult['msg'],
        );
    }
}
