<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\console;

use app\model\Opt as Opt_Base;
use ginkgo\Config;
use ginkgo\Func;
use ginkgo\Http;
use ginkgo\Html;
use ginkgo\Loader;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------设置项模型-------------*/
class Opt extends Opt_Base {

    function __construct() { //构造函数
        parent::__construct();

        $this->config = Config::get();

        $this->pathLatest = GK_PATH_DATA . 'latest' . GK_EXT_INC;
    }


    function submit() {
        $_arr_opt = array();

        foreach ($this->config['console']['opt'][$this->inputSubmit['act']]['lists'] as $_key=>$_value) {
            if (isset($this->inputSubmit[$_key])) {
                $_arr_opt[$_key] = Html::decode($this->inputSubmit[$_key], 'url');
            }
        }

        if ($this->inputSubmit['act'] == 'base') {
            $_arr_opt['site_timezone']   = Html::decode($this->inputSubmit['site_timezone'], 'url');
            $_arr_opt['site_tpl']        = Html::decode($this->inputSubmit['site_tpl'], 'url');
        }

        $_num_size = Config::write(GK_APP_CONFIG . 'extra_' . $this->inputSubmit['act'] . GK_EXT_INC, $_arr_opt);

        if ($_num_size > 0) {
            $_str_rcode = 'y030401';
            $_str_msg   = 'Set successfully';
        } else {
            $_str_rcode = 'x030401';
            $_str_msg   = 'Set failed';
        }

        return array(
            'rcode' => $_str_rcode,
            'msg'   => $_str_msg,
        );
    }


    function mailtpl() {
        $_arr_opt = array();

        $_arr_config = Config::get('mailtpl', 'console');

        foreach ($_arr_config as $_key=>$_value) {
            foreach ($_value['lists'] as $_key_list=>$_value_list) {
                $_arr_opt[$_key . '_' . $_key_list] = Html::decode($this->inputMailtpl[$_key . '_' . $_key_list], 'url');
            }
        }

        $_num_size = Config::write(GK_APP_CONFIG . 'extra_mailtpl' . GK_EXT_INC, $_arr_opt);

        if ($_num_size > 0) {
            $_str_rcode = 'y030401';
            $_str_msg   = 'Set successfully';
        } else {
            $_str_rcode = 'x030401';
            $_str_msg   = 'Set failed';
        }

        return array(
            'rcode' => $_str_rcode,
            'msg'   => $_str_msg,
        );
    }


    function smtp() {
        $_arr_opt = array(
            'host'          => $this->inputSmtp['host'],
            'port'          => $this->inputSmtp['port'],
            'user'          => $this->inputSmtp['user'],
            'pass'          => $this->inputSmtp['pass'],
            'secure'        => $this->inputSmtp['secure'],
            'auth'          => $this->inputSmtp['auth'],
            'from_addr'     => $this->inputSmtp['from_addr'],
            'from_name'     => $this->inputSmtp['from_name'],
            'reply_addr'    => $this->inputSmtp['reply_addr'],
            'reply_name'    => $this->inputSmtp['reply_name'],
            'debug'         => $this->inputSmtp['debug'],
        );

        $_num_size   = Config::write(GK_APP_CONFIG . 'extra_smtp' . GK_EXT_INC, $_arr_opt);

        if ($_num_size > 0) {
            $_str_rcode = 'y030401';
            $_str_msg   = 'Set successfully';
        } else {
            $_str_rcode = 'x030401';
            $_str_msg   = 'Set failed';
        }

        return array(
            'rcode' => $_str_rcode,
            'msg'   => $_str_msg,
        );
    }


    function latest($method = 'auto') {
        $_arr_data = array(
            'name'      => 'baigo SSO',
            'ver'       => PRD_SSO_VER,
            'referer'   => $this->obj_request->root(true),
            'method'    => $method,
        );

        $_arr_ver = Http::instance()->request(PRD_VER_CHECK, $_arr_data, 'post');

        $_num_size   = Config::write($this->pathLatest, $_arr_ver);

        if ($_num_size > 0) {
            $_str_rcode = 'y040402';
            $_str_msg   = 'Check for updates successful';
        } else {
            $_str_rcode = 'x040402';
            $_str_msg   = 'Check for updates failed';
        }

        return array(
            'rcode' => $_str_rcode,
            'msg'   => $_str_msg,
        );
    }

    function chkver() {
        if (!Func::isFile($this->pathLatest)) {
            $this->latest();
        }

        $_arr_ver = Loader::load($this->pathLatest);

        if (Func::isEmpty($_arr_ver) || !isset($_arr_ver['time']) || $_arr_ver['time'] - GK_NOW > 30 * GK_DAY) {
            $this->latest();
            $_arr_ver = Loader::load($this->pathLatest);
        }

        return $_arr_ver;
    }


    function inputSmtp() {
        $_arr_inputParam = array(
            'host'          => array('txt', 'localhost'),
            'port'          => array('num', 25),
            'secure'        => array('txt', ''),
            'auth'          => array('txt', 'true'),
            'user'          => array('txt', ''),
            'pass'          => array('txt', ''),
            'from_addr'     => array('txt', 'root@localhost'),
            'from_name'     => array('txt', ''),
            'reply_addr'    => array('txt', 'root@localhost'),
            'reply_name'    => array('txt', ''),
            'debug'         => array('num', 0),
            '__token__'     => array('txt', ''),
        );

        $_arr_inputSmtp = $this->obj_request->post($_arr_inputParam);

        $_is_vld = $this->vld_opt->scene('smtp')->verify($_arr_inputSmtp);

        if ($_is_vld !== true) {
            $_arr_message = $this->vld_opt->getMessage();
            return array(
                'rcode' => 'x030201',
                'msg'   => end($_arr_message),
            );
        }

        $_arr_inputSmtp['rcode'] = 'y030201';

        $this->inputSmtp = $_arr_inputSmtp;

        return $_arr_inputSmtp;
    }


    function inputSubmit() {
        $_arr_inputParam = array(
            '__token__' => array('txt', ''),
            'act'       => array('txt', ''),
        );

        $_str_act = $this->obj_request->post('act');

        foreach ($this->config['console']['opt'][$_str_act]['lists'] as $_key=>$_value) {
            $_arr_inputParam[$_key] = array('txt', '');
        }

        if ($_str_act == 'base') {
            $_arr_inputParam['site_timezone']   = array('txt', '');
            $_arr_inputParam['site_tpl']        = array('txt', '');
        }

        $_arr_inputSubmit = $this->obj_request->post($_arr_inputParam);

        $_is_vld = $this->vld_opt->scene($_str_act)->verify($_arr_inputSubmit);

        if ($_is_vld !== true) {
            $_arr_message = $this->vld_opt->getMessage();
            return array(
                'rcode' => 'x030201',
                'msg'   => end($_arr_message),
            );
        }

        $_arr_inputSubmit['rcode'] = 'y030201';

        $this->inputSubmit = $_arr_inputSubmit;

        return $_arr_inputSubmit;
    }


    function inputMailtpl() {
        $_arr_inputParam = array(
            '__token__' => array('txt', ''),
        );

        $_arr_config = Config::get('mailtpl', 'console');

        foreach ($_arr_config as $_key=>$_value) {
            foreach ($_value['lists'] as $_key_list=>$_value_list) {
                $_arr_inputParam[$_key . '_' . $_key_list] = array('txt', '');
            }
        }

        $_arr_inputMailtpl = $this->obj_request->post($_arr_inputParam);

        $_is_vld = $this->vld_opt->scene('mailtpl')->verify($_arr_inputMailtpl);

        if ($_is_vld !== true) {
            $_arr_message = $this->vld_opt->getMessage();
            return array(
                'rcode' => 'x030201',
                'msg'   => end($_arr_message),
            );
        }

        $_arr_inputMailtpl['rcode'] = 'y030201';

        $this->inputMailtpl = $_arr_inputMailtpl;

        return $_arr_inputMailtpl;
    }


    function inputData() {
        $_arr_inputParam = array(
            'type'      => array('str', ''),
            'model'     => array('str', ''),
            '__token__' => array('str', ''),
        );

        $_arr_inputData = $this->obj_request->post($_arr_inputParam);

        $_is_vld = $this->vld_opt->scene('data')->verify($_arr_inputData);

        if ($_is_vld !== true) {
            $_arr_message = $this->vld_opt->getMessage();
            return array(
                'rcode' => 'x030201',
                'msg'   => end($_arr_message),
            );
        }

        $_arr_inputData['rcode'] = 'y030201';

        $this->inputData = $_arr_inputData;

        return $_arr_inputData;
    }
}
