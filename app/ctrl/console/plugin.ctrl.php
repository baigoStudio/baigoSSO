<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\console;

use app\classes\console\Ctrl;
use ginkgo\Loader;
use ginkgo\Config;
use ginkgo\File;
use ginkgo\Func;
use ginkgo\Json;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Plugin extends Ctrl {

    protected function c_init($param = array()) {
        parent::c_init();

        $this->obj_file         = File::instance();

        $this->mdl_plugin  = Loader::model('Plugin');

        $this->configPlugin = Config::get('plugin');
    }


    function index() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!isset($this->adminLogged['admin_allow']['plugin']['browse']) && !$this->isSuper) { //判断权限
            return $this->error('You do not have permission', 'x190301');
        }

        $_arr_searchParam = array(
            'key'       => array('str', ''),
            'status'    => array('str', ''),
        );

        $_arr_search = $this->obj_request->param($_arr_searchParam);

        //print_r($_arr_search);

        $_arr_pluginRows    = $this->obj_file->dirList(GK_PATH_PLUGIN);
        $_arr_pluginDisable = array();

        foreach ($_arr_pluginRows as $_key=>$_value) {
            if (isset($this->configPlugin[$_value['name']])) {
                $_arr_pluginRows[$_key] = array_replace_recursive($_value, $this->configPlugin[$_value['name']]);
                $_arr_pluginRows[$_key]['plugin_status']    = 'enable';
            } else {
                $_arr_pluginRows[$_key]['plugin_status']    = 'wait';
                $_arr_pluginRows[$_key]['plugin_note']      = '';
            }

            $_arr_pluginConfig = $this->check($_value['name']);

            if (isset($_arr_pluginConfig['rcode'])) {
                $_arr_pluginRows[$_key]['plugin_status'] = 'error';
                $_arr_pluginRows[$_key]['plugin_note']   = $_arr_pluginConfig['msg'];
                $_arr_pluginRows[$_key]['plugin_config'] = array(
                    'name' => $_value['name'],
                );

                $_arr_pluginDisable[] = $_value['name'];
            } else {
                $_arr_pluginRows[$_key]['plugin_config'] = $_arr_pluginConfig;
            }

            $_str_optsPath   = GK_PATH_PLUGIN . $_value['name'] . DS . 'opts.json';

            $_mix_pluginOpts = Func::isFile($_str_optsPath);

            if ($_mix_pluginOpts) {
                $_str_pluginOpts  = $this->obj_file->fileRead($_str_optsPath);
                $_mix_pluginOpts  = Json::decode($_str_pluginOpts);

                if (Func::isEmpty($_mix_pluginOpts)) {
                    $_mix_pluginOpts = false;
                }
            }

            $_arr_pluginRows[$_key]['plugin_opts'] = $_mix_pluginOpts;

            if ($_arr_search['status'] == 'installable' && (isset($this->configPlugin[$_value['name']]) || $_arr_pluginRows[$_key]['plugin_status'] != 'wait')) {
                unset($_arr_pluginRows[$_key]);
            }
        }

        //print_r($_arr_pluginDisable);

        $this->pluginDisable($_arr_pluginDisable);

        //print_r($_arr_pluginRows);

        $_arr_tplData = array(
            'search'        => $_arr_search,
            'pluginRows'    => $_arr_pluginRows,
            'token'         => $this->obj_request->token(),
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        //print_r($_arr_pluginRows);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function show() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!isset($this->adminLogged['admin_allow']['plugin']['browse']) && !$this->isSuper) { //判断权限
            return $this->error('You do not have permission', 'x190301');
        }

        $_str_pluginDir = '';

        if (isset($this->param['dir'])) {
            $_str_pluginDir = $this->obj_request->input($this->param['dir'], 'str', '');
        }

        if (Func::isEmpty($_str_pluginDir)) {
            return $this->error('Missing directory param', 'x190202');
        }

        if (isset($this->configPlugin[$_str_pluginDir])) {
            $_arr_pluginRow = $this->configPlugin[$_str_pluginDir];

            $_arr_pluginRow['plugin_status'] = 'enable';
        } else {
            $_arr_pluginRow = array(
                'plugin_status'    => 'wait',
                'plugin_note'      => '',
            );
        }

        $_arr_pluginConfig = $this->check($_str_pluginDir);

        if (isset($_arr_pluginConfig['rcode'])) {
            return $this->error($_arr_pluginConfig['msg'], $_arr_pluginConfig['rcode']);
        }

        $_arr_pluginRow['plugin_config'] = $_arr_pluginConfig;
        $_arr_pluginRow['plugin_dir']    = $_str_pluginDir;

        $_str_optsPath   = GK_PATH_PLUGIN . $_str_pluginDir . DS . 'opts.json';

        $_mix_pluginOpts = Func::isFile($_str_optsPath);

        if ($_mix_pluginOpts) {
            $_str_pluginOpts  = $this->obj_file->fileRead($_str_optsPath);
            $_mix_pluginOpts  = Json::decode($_str_pluginOpts);

            if (Func::isEmpty($_mix_pluginOpts)) {
                $_mix_pluginOpts = false;
            }
        }

        $_arr_tplData = array(
            'pluginOpts'    => $_mix_pluginOpts,
            'pluginRow'     => $_arr_pluginRow,
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        //print_r($_arr_pluginRows);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function form() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_str_pluginDir = '';

        if (isset($this->param['dir'])) {
            $_str_pluginDir = $this->obj_request->input($this->param['dir'], 'str', '');
        }


        if (Func::isEmpty($_str_pluginDir)) {
            return $this->error('Missing directory param', 'x190203');
        }

        if (isset($this->configPlugin[$_str_pluginDir])) {
            if (!isset($this->adminLogged['admin_allow']['plugin']['edit']) && !$this->isSuper) { //判断权限
                return $this->error('You do not have permission', 'x190303');
            }

            $_arr_pluginRow = $this->configPlugin[$_str_pluginDir];

            $_arr_pluginRow['plugin_status'] = 'enable';
        } else {
            if (!isset($this->adminLogged['admin_allow']['plugin']['install']) && !$this->isSuper) { //判断权限
                return $this->error('You do not have permission', 'x190302');
            }

            $_arr_pluginRow = array(
                'plugin_note'   => '',
                'plugin_status' => 'wait',
            );
        }

        $_arr_pluginConfig = $this->check($_str_pluginDir);

        if (isset($_arr_pluginConfig['rcode'])) {
            return $this->error($_arr_pluginConfig['msg'], $_arr_pluginConfig['rcode']);
        }

        $_arr_pluginRow['plugin_config'] = $_arr_pluginConfig;
        $_arr_pluginRow['plugin_dir']    = $_str_pluginDir;

        $_str_optsPath   = GK_PATH_PLUGIN . $_str_pluginDir . DS . 'opts.json';

        $_mix_pluginOpts = Func::isFile($_str_optsPath);

        if ($_mix_pluginOpts) {
            $_str_pluginOpts  = $this->obj_file->fileRead($_str_optsPath);
            $_mix_pluginOpts  = Json::decode($_str_pluginOpts);

            if (Func::isEmpty($_mix_pluginOpts)) {
                $_mix_pluginOpts = false;
            }
        }

        $_arr_tplData = array(
            'pluginOpts'    => $_mix_pluginOpts,
            'pluginRow'     => $_arr_pluginRow,
            'token'         => $this->obj_request->token(),
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        //print_r($_arr_pluginRows);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function submit() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isAjaxPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        $_arr_inputSubmit = $this->mdl_plugin->inputSubmit();

        if ($_arr_inputSubmit['rcode'] != 'y190201') {
            return $this->fetchJson($_arr_inputSubmit['msg'], $_arr_inputSubmit['rcode']);
        }

        $_arr_pluginConfig = $this->check($_arr_inputSubmit['plugin_dir']);

        if (isset($_arr_pluginConfig['rcode'])) {
            return $this->fetchJson($_arr_pluginConfig['msg'], $_arr_pluginConfig['rcode']);
        }

        $_arr_submitResult   = $this->mdl_plugin->submit();

        return $this->fetchJson($_arr_submitResult['msg'], $_arr_submitResult['rcode']);
    }


    function opts() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_str_pluginDir = '';

        if (isset($this->param['dir'])) {
            $_str_pluginDir = $this->obj_request->input($this->param['dir'], 'str', '');
        }


        if (!isset($this->adminLogged['admin_allow']['plugin']['option']) && !$this->isSuper) { //判断权限
            return $this->error('You do not have permission', 'x190301');
        }

        if (Func::isEmpty($_str_pluginDir)) {
            return $this->error('Missing directory param', 'x190203');
        }

        if (!isset($this->configPlugin[$_str_pluginDir])) {
            return $this->error('Plugin not found', 'x190102');
        }

        $_arr_pluginRow = $this->configPlugin[$_str_pluginDir];

        $_arr_pluginConfig = $this->check($_str_pluginDir);

        if (isset($_arr_pluginConfig['rcode'])) {
            return $this->error($_arr_pluginConfig['msg'], $_arr_pluginConfig['rcode']);
        }

        $_arr_pluginRow['plugin_config'] = $_arr_pluginConfig;

        $_str_optsPath = GK_PATH_PLUGIN . $_str_pluginDir . DS . 'opts.json';

        if (!Func::isFile($_str_optsPath)) {
            return $this->error('There are no options to set', 'x190401');
        }

        $_str_pluginOpts  = $this->obj_file->fileRead($_str_optsPath);

        $_arr_pluginOpts  = Json::decode($_str_pluginOpts);

        if (Func::isEmpty($_arr_pluginOpts)) {
            return $this->error('There are no options to set', 'x190401');
        }

        $_str_optsVarPath = GK_PATH_PLUGIN . $_str_pluginDir . DS . 'opts_var.json';

        $_arr_optsVar  = array();

        if (Func::isFile($_str_optsVarPath)) {
            $_str_optsVar  = $this->obj_file->fileRead($_str_optsVarPath);
            $_arr_optsVar  = Json::decode($_str_optsVar);
        }

        foreach ($_arr_pluginOpts as $_key=>$_value) {
            if (!isset($_arr_optsVar[$_key])) {
                $_arr_optsVar[$_key] = '';
            }

            if (!isset($_value['title'])) {
                $_arr_pluginOpts[$_key]['title'] = $_key;
            }
        }

        $_arr_pluginRow['plugin_dir']    = $_str_pluginDir;
        $_arr_pluginRow['plugin_status'] = 'enable';

        $_arr_tplData = array(
            'optsVar'       => $_arr_optsVar,
            'pluginOpts'    => $_arr_pluginOpts,
            'pluginRow'     => $_arr_pluginRow,
            'token'         => $this->obj_request->token(),
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        //print_r($_arr_pluginRows);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function optsSubmit() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isAjaxPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        $_arr_inputOpts = $this->mdl_plugin->inputOpts();

        if ($_arr_inputOpts['rcode'] != 'y190201') {
            return $this->fetchJson($_arr_inputOpts['msg'], $_arr_inputOpts['rcode']);
        }

        if (!isset($this->adminLogged['admin_allow']['plugin']['option']) && !$this->isSuper) {
            return $this->fetchJson('You do not have permission', 'x190303');
        }

        //print_r($_arr_pluginRow);

        if (!isset($this->configPlugin[$_arr_inputOpts['plugin_dir']])) {
            return $this->fetchJson('Plugin not found', 'x190102');
        }

        $_arr_pluginConfig = $this->check($_arr_inputOpts['plugin_dir']);

        if (isset($_arr_pluginConfig['rcode'])) {
            return $this->fetchJson($_arr_pluginConfig['msg'], $_arr_pluginConfig['rcode']);
        }

        $_arr_optsResult   = $this->mdl_plugin->opts();

        return $this->fetchJson($_arr_optsResult['msg'], $_arr_optsResult['rcode']);
    }


    function uninstall() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isAjaxPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        if (!isset($this->adminLogged['admin_allow']['plugin']['uninstall']) && !$this->isSuper) { //判断权限
            return $this->fetchJson('You do not have permission', 'x190304');
        }

        $_arr_inputUninstall = $this->mdl_plugin->inputUninstall();

        if ($_arr_inputUninstall['rcode'] != 'y190201') {
            return $this->fetchJson($_arr_inputUninstall['msg'], $_arr_inputUninstall['rcode']);
        }

        $_arr_uninstallResult = $this->mdl_plugin->uninstall();

        $_arr_langReplace = array(
            'count' => $_arr_uninstallResult['count'],
        );

        return $this->fetchJson($_arr_uninstallResult['msg'], $_arr_uninstallResult['rcode'], '', $_arr_langReplace);
    }


    private function check($str_name) {
        $_str_configPath = GK_PATH_PLUGIN . $str_name . DS . 'config.json';

        if (Func::isFile($_str_configPath)) {
            $_str_pluginConfig  = $this->obj_file->fileRead($_str_configPath);
            $_arr_pluginConfig  = Json::decode($_str_pluginConfig);
        } else {
            $_arr_pluginConfig = array();
        }

        if (!isset($_arr_pluginConfig['class']) || Func::isEmpty($_arr_pluginConfig['class'])) {
            $_arr_pluginConfig['class'] = $str_name;
        }

        if (!Func::isFile(GK_PATH_PLUGIN . $str_name . DS . $_arr_pluginConfig['class'] . GK_EXT_CLASS)) {
            return array(
                'msg'   => 'Missing required files',
                'rcode' => 'x190102',
            );
        }

        if (!isset($_arr_pluginConfig['name']) || Func::isEmpty($_arr_pluginConfig['class'])) {
            $_arr_pluginConfig['name'] = $str_name;
        }

        return $_arr_pluginConfig;
    }


    private function pluginDisable($arr_pluginDisable) {
        //print_r($arr_pluginDisable);
        if (!Func::isEmpty($arr_pluginDisable)) {
            $this->mdl_plugin->inputUninstall['plugin_dirs'] = Func::arrayFilter($arr_pluginDisable);
            $this->mdl_plugin->uninstall();
        }
    }
}
