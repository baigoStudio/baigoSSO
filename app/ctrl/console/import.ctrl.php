<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\console;

use app\classes\console\Ctrl;
use ginkgo\Loader;
use ginkgo\File;
use ginkgo\Func;
use ginkgo\Config;
use ginkgo\Upload;
use ginkgo\Html;
use ginkgo\Arrays;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

class Import extends Ctrl {

    protected function c_init($param = array()) {
        parent::c_init();

        $this->obj_upload       = Upload::instance();
        $this->mdl_import       = Loader::model('Import');

        $_str_configCharset = BG_PATH_CONFIG . 'console' . DS . 'charset' . GK_EXT_INC;
        $this->charsetRows  = Config::load($_str_configCharset, 'charset', 'console');

        $_str_current       = $this->obj_lang->getCurrent();
        $_str_langCharset   = GK_APP_LANG . $_str_current . DS . 'console' . DS . 'charset' . GK_EXT_LANG;

        $this->obj_lang->load($_str_langCharset, 'console.charset');

        $_arr_charsetOften              = array_keys($this->charsetRows['often']['lists']);
        $_arr_charsetList               = array_keys($this->charsetRows['lists']['lists']);

        $this->mdl_import->charsetKeys  = Arrays::filter(array_replace_recursive($_arr_charsetOften, $_arr_charsetList));

        $this->csvPath = $this->mdl_import->csvPath;
    }


    function index() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!isset($this->adminAllow['user']['import']) && !$this->isSuper) { //判断权限
            return $this->error('You do not have permission', 'x010305');
        }

        $_str_charset = $this->obj_request->get('charset', 'txt', 'UTF-8');
        $_str_charset = Html::decode($_str_charset, 'url');

        //print_r($_str_charset);

        $_arr_csvRows = $this->mdl_import->preview($_str_charset);

        $_arr_tplData = array(
            'charset'       => $_str_charset,
            'charsetRows'   => $this->charsetRows,
            'csvRows'       => $_arr_csvRows,
            'token'         => $this->obj_request->token(),
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function preview() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!isset($this->adminAllow['user']['import']) && !$this->isSuper) { //判断权限
            return $this->error('You do not have permission', 'x010305');
        }

        $_str_charset = '';

        if (isset($this->param['charset'])) {
            $_str_charset = $this->obj_request->input($this->param['charset'], 'txt', '');
        }

        if (Func::isEmpty($_str_charset)) {
            $_str_charset = 'UTF-8';
        }

        $_str_charset = Html::decode($_str_charset, 'url');

        //print_r($_str_charset);

        $_arr_csvRows = $this->mdl_import->preview($_str_charset, 0);

        $_arr_tplData = array(
            'charset'       => $_str_charset,
            'csvRows'       => $_arr_csvRows,
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function charset() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_arr_tplData = array(
            'charsetRows'   => $this->charsetRows,
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

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

        if (!isset($this->adminAllow['user']['import']) && !$this->isSuper) {
            return $this->fetchJson('You do not have permission', 'x010305');
        }

        $_arr_inputSubmit = $this->mdl_import->inputSubmit();

        if ($_arr_inputSubmit['rcode'] != 'y010201') {
            return $this->fetchJson($_arr_inputSubmit['msg'], $_arr_inputSubmit['rcode']);
        }

        $_arr_submitResult = $this->mdl_import->submit();

        $_arr_langReplace = array(
            'count' => $_arr_submitResult['count'],
        );

        return $this->fetchJson($_arr_submitResult['msg'], $_arr_submitResult['rcode'], 200, $_arr_langReplace);
    }


    function upload() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->obj_request->isPost()) {
            return $this->fetchJson('Access denied', '', 405);
        }

        if (!isset($this->adminAllow['user']['import']) && !$this->isSuper) { //判断权限
            return $this->fetchJson('You do not have permission', 'x010305');
        }

        $_arr_csvMime = array(
            'csv' => array(
                'text/x-comma-separated-values',
                'text/comma-separated-values',
                'application/octet-stream',
                'application/vnd.ms-excel',
                'application/x-csv',
                'text/x-csv',
                'text/csv',
                'application/csv',
                'application/excel',
                'application/vnd.msexcel',
                'text/plain',
            ),
        );

        $this->obj_upload->setMime($_arr_csvMime);

        if (!$this->obj_upload->create('csv_files')) {
            $_str_error         = $this->obj_upload->getError();
            return $this->fetchJson($_str_error, 'x010403');
        }

        if (!$this->obj_upload->move($this->mdl_import->csvPrefix, $this->mdl_import->csvName)) {
            $_str_error         = $this->obj_upload->getError();

            return $this->fetchJson($_str_error, 'x010403');
        }

        return $this->fetchJson('CSV file uploaded successfully', 'y010403');
    }


    function delete() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isAjaxPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        if (!isset($this->adminAllow['user']['import']) && !$this->isSuper) { //判断权限
            return $this->fetchJson('You do not have permission', 'x010305');
        }

        $_arr_inputDelete = $this->mdl_import->inputCommon();

        if ($_arr_inputDelete['rcode'] != 'y010201') {
            return $this->fetchJson($_arr_inputDelete['msg'], $_arr_inputDelete['rcode']);
        }

        if (!File::instance()->fileDelete($this->csvPath)) {
            return $this->fetchJson('No file have been deleted', 'x010408');
        }

        return $this->fetchJson('Delete CSV file successfully', 'y010408');
    }
}
