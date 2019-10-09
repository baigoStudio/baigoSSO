<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------数据库类-------------*/
abstract class Model {

    protected $connection;
    protected $obj_request;
    protected $obj_builder;
    protected $dbconfig;
    protected $table;
    protected $className;

    private $obj_db;

    function __construct($dbconfig = array()) {
        $this->obj_request  = Request::instance();

        $this->config($dbconfig);

        $this->obj_db       = Db::connect($this->dbconfig);

        $this->obj_builder  = $this->obj_db->obj_builder;

        $this->m_init();
    }


    protected function m_init() {

    }

    protected function config($dbconfig) {
        $_arr_dbconfig = Config::get('dbconfig');

        if (!Func::isEmpty($this->connection)) {
            $this->dbconfig = $this->connection;
        } else if (!Func::isEmpty($dbconfig)) {
            $this->dbconfig = $dbconfig;
        } else {
            $this->dbconfig = $_arr_dbconfig;
        }

        $this->configProcess();
    }


    private function configProcess() {
        isset($this->dbconfig['type']) or $this->dbconfig['type'] = 'mysql';
        isset($this->dbconfig['host']) or $this->dbconfig['host'] = 'localhost';
        isset($this->dbconfig['name']) or $this->dbconfig['name'] = '';
        isset($this->dbconfig['user']) or $this->dbconfig['user'] = '';
        isset($this->dbconfig['pass']) or $this->dbconfig['pass'] = '';
        isset($this->dbconfig['charset']) or $this->dbconfig['charset'] = 'utf8';
        isset($this->dbconfig['prefix']) or $this->dbconfig['prefix'] = '';
        isset($this->dbconfig['debug']) or $this->dbconfig['debug'] = false;
        isset($this->dbconfig['port']) or $this->dbconfig['port'] = 3306;
    }


    protected function validate($data, $validate = '', $scene = false, $only = array(), $remove = array(), $append = array()) {
        if (Func::isEmpty($validate)) {
            $validate   = $this->realClassProcess();
            $_vld       = Loader::validate($validate);

            if (!Func::isEmpty($scene)) {
                $_vld->scene($scene);
            }
        } else {
            if (is_array($validate)) {
                $_vld = Validate::instance();
                $_vld->rule($validate);
            } else if (is_string($validate)) {
                $_vld = Loader::validate($validate);

                if (!Func::isEmpty($scene)) {
                    $_vld->scene($scene);
                }
            }
        }

        if (!Func::isEmpty($only)) {
            $_vld->only($only);
        }

        if (!Func::isEmpty($remove)) {
            $_vld->remove($remove);
        }

        if (!Func::isEmpty($append)) {
            $_vld->append($append);
        }

        if ($_vld->verify($data)) {
            $_mix_return = true;
        } else {
            $_mix_return = $_vld->getMessage();
        }

        return $_mix_return;
    }


    public function __call($method, $params) {
        if (method_exists($this->obj_db, $method)) {
            $_table = $this->realClassProcess();

            $this->obj_db->setModel($this->className);

            if (Func::isEmpty($this->table)) {
                $this->obj_db->setTable($_table);
            } else {
                $this->obj_db->setTable($this->table);
            }

            return call_user_func_array(array($this->obj_db, $method), $params);
        } else {
            $_obj_excpt = new Exception('Method not found', 500);
            $_obj_excpt->setData('err_detail', __CLASS__ . '->' . $method);

            throw $_obj_excpt;
        }
    }


    private function realClassProcess() {
        $_class     = get_class($this);
        //print_r($_class);
        $_arr_class = explode('\\', $_class);
        $_table     = end($_arr_class);

        $this->className = $_class;

        return strtolower($_table);
    }
}
