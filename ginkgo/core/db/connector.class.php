<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo\db;

use PDO;
use ginkgo\Config;
use ginkgo\Func;
use ginkgo\Log;
use ginkgo\Exception;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------数据库连接器类-------------*/
abstract class Connector {

    public $dbconfig;
    public $obj_builder;

    protected static $instance;
    protected $obj_pdo;
    protected $obj_result;
    protected $isConnect;
    protected $mid;

    private $configDebug;
    private $isConfig;

    protected $_table       = '';
    protected $_tableTemp   = '';
    protected $_field       = '';
    protected $_force       = '';
    protected $_join        = '';
    protected $_distinct    = false;
    protected $_where       = '';
    protected $_whereOr     = array();
    protected $_whereAnd    = array();
    protected $_group       = '';
    protected $_order       = '';
    protected $_limit       = '';
    protected $_bind        = array();
    protected $_fetchSql    = false;

    protected $paramType = array(
        'bool'  => PDO::PARAM_BOOL,
        'int'   => PDO::PARAM_INT,
        'str'   => PDO::PARAM_STR,
    );


    protected function __construct($dbconfig = array()) {
        $this->config($dbconfig);
    }

    protected function __clone() {

    }

    public static function instance($dbconfig = array()) {
        if (Func::isEmpty(static::$instance)) {
            static::$instance = new static($dbconfig);
        }
        return static::$instance;
    }

    function config($dbconfig = array()) {
        $this->dbconfig = $dbconfig;

        $this->configProcess();

        if (Func::isEmpty($this->dbconfig['type'])) {
            $this->dbconfig['type'] = 'mysql';
        }

        $_class = 'ginkgo\\db\\builder\\' . Func::ucwords($this->dbconfig['type'], '_');

        if (class_exists($_class)) {
            $this->obj_builder = $_class::instance($this->dbconfig);
        } else {
            $_obj_excpt = new Exception('SQL Builder not found', 500);

            $_obj_excpt->setData('err_detail', $_class);

            throw $_obj_excpt;
        }
    }


    function connect() {
        try {
            if (Func::isEmpty($this->dbconfig['type'])) {
                $this->dbconfig['type'] = 'mysql';
            }

            $_str_dsn = $this->dsnProcess();

            $this->obj_pdo = new PDO($_str_dsn, $this->dbconfig['user'], $this->dbconfig['pass']);
        } catch (PDOException $excpt) {
            $_obj_excpt = new Exception('Can not connect to database', 500);
            if ($this->dbconfig['debug'] === true || $this->dbconfig['debug'] == 'true') {
                $_obj_excpt->setData('err_detail', $excpt->getMessage());
            }

            throw $_obj_excpt;
        }

        //$this->obj_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $this->obj_pdo->exec('SET NAMES ' . $this->dbconfig['charset']);

        $this->configDebug = Config::get('dump', 'debug');

        $this->isConnect = true;
    }


    function exec($sql) {
        if (Func::isEmpty($this->isConnect)) {
            $this->connect();
        }

        if ($this->configDebug === 'trace') {
            Log::record($sql, 'sql');
        }

        return $this->obj_pdo->exec($sql);
    }


    function query($sql) {
        if (Func::isEmpty($this->isConnect)) {
            $this->connect();
        }

        if ($this->configDebug === 'trace') {
            Log::record($sql, 'sql');
        }

        $_obj_result = $this->obj_pdo->query($sql);

        //print_r($sql);

        $this->obj_result = $_obj_result;

        return $_obj_result;
    }


    function lastInsertId() {
        return $this->obj_pdo->lastInsertId();
    }


    function prepare($sql, $bind = array(), $value = '', $type = '') {
        if (Func::isEmpty($this->isConnect)) {
            $this->connect();
        }

        $_obj_result = $this->obj_pdo->prepare($sql);

        $this->obj_result = $_obj_result;

        if (!Func::isEmpty($bind)) {
            $this->bind($bind, $value, $type);
        }

        $this->resetSql();

        return $_obj_result;
    }


    function execute($bind = array(), $value = '', $type = '') {
        if (!Func::isEmpty($bind)) {
            $this->bind($bind, $value, $type);
        }

        $this->resetSql();

        if ($this->configDebug === 'trace') {
            ob_start();
            ob_implicit_flush(0);

            $this->obj_result->debugDumpParams();

            $_str_content = ob_get_clean();

            Log::record($_str_content, 'sql');
        }

        return $this->obj_result->execute();
    }


    function distinct($bool = false) {
        $this->_distinct = $bool;

        return $this;
    }


    function fetchSql($bool = true) {
        $this->_fetchSql = $bool;

        return $this;
    }


    function getRowCount() {
        return $this->obj_result->rowCount();
    }


    function getRow() {
        $_num_return = 0;

        $_arr_result = $this->obj_result->fetch(PDO::FETCH_NUM);

        if (isset($_arr_result[0])) {
            $_num_return = $_arr_result[0];
        }

        return $_num_return;
    }


    function getResult($all = true, $type = PDO::FETCH_ASSOC) {
        if ($all) {
            $_mix_return = $this->obj_result->fetchAll($type);
        } else {
            $_mix_return = $this->obj_result->fetch($type);
        }

        return $_mix_return;
    }


    function setModel($model = '') {
        if (Func::isEmpty($model)) {
            $model = get_class($this);
        }

        $this->mid = md5($model);

        /*print_r($model);
        print_r('<br>');
        print_r($this->mid);
        print_r('<br>');*/
    }


    function setTable($table) {
        $this->_table[$this->mid] = $this->obj_builder->table(strtolower($table));
    }


    function getTable() {
        $_str_table = '';

        if (isset($this->_tableTemp[$this->mid]) && !Func::isEmpty($this->_tableTemp[$this->mid])) {
            $_str_table = $this->_tableTemp[$this->mid];
        } else if (isset($this->_table[$this->mid]) && !Func::isEmpty($this->_table[$this->mid])) {
            $_str_table = $this->_table[$this->mid];
        }

        return $_str_table;
    }


    function bind($bind, $value = '', $type = '') {
        if (is_array($bind)) {
            if (isset($bind[0])) {
                if (is_array($bind[0])) {
                    foreach ($bind as $_key => $_value) {
                        if (is_array($_value)) {
                            if (isset($_value[0]) && is_scalar($_value[0])) {
                                if (!isset($_value[1])) {
                                    $_value[1] = '';
                                }

                                if (!isset($_value[2])) {
                                    $_value[2] = '';
                                }

                                $result = $this->bindProcess($_value[0], $_value[1], $_value[2]);
                            }
                        }
                    }
                } else if (is_scalar($bind[0])) {
                    if (!isset($bind[1])) {
                        $bind[1] = '';
                    }

                    if (!isset($bind[2])) {
                        $bind[2] = '';
                    }

                    $result = $this->bindProcess($bind[0], $bind[1], $bind[2]);
                }
            }
        } else if (is_scalar($bind)) {
            $result = $this->bindProcess($bind, $value, $type);
        }

        $this->resetSql();

        return $this;
    }


    private function bindProcess($param, $value, $type = '') {
        //print_r($param);

        $result = false;
        $type   = strtolower($type);

        if (!Func::isEmpty($param) && is_scalar($param)) {
            $_num_type = $this->getType($value, $type);

            if ($_num_type == PDO::PARAM_STR && Func::isEmpty($value)) {
                $value = 0;
            }

            if (is_numeric($param)) {
                $_mix_param = $param;
            } else {
                $_mix_param = ':' . $param;
            }

            /*print_r($_mix_param);
            print_r(' => ');
            print_r($value);
            print_r(', type: ');
            print_r($_num_type);
            print_r('<br>');
            print_r(PHP_EOL);*/

            $result = $this->obj_result->bindValue($_mix_param, $value, $_num_type);

            if (!$result) {
                $_obj_excpt = new Exception('Error occurred when binding parameters', 500);

                $_obj_excpt->setData('err_detail', $param);

                throw $_obj_excpt;
            }
        }

        return $result;
    }


    protected function fetchBind($sql, $bind, $value = '', $type = '') {
        if (is_array($bind)) {
            if (isset($bind[0])) {
                if (is_array($bind[0])) {
                    foreach ($bind as $_key => $_value) {
                        if (is_array($_value)) {
                            if (isset($_value[0]) && is_scalar($_value[0])) {
                                if (!isset($_value[1])) {
                                    $_value[1] = '';
                                }

                                if (!isset($_value[2])) {
                                    $_value[2] = '';
                                }

                                $sql = $this->fetchBindProcess($sql, $_value[0], $_value[1], $_value[2]);
                            }
                        }
                    }
                } else if (is_scalar($bind[0])) {
                    if (!isset($bind[1])) {
                        $bind[1] = '';
                    }

                    if (!isset($bind[2])) {
                        $bind[2] = '';
                    }

                    $sql = $this->fetchBindProcess($sql, $bind[0], $bind[1], $bind[2]);
                }
            }
        } else if (is_scalar($bind)) {
            $sql = $this->fetchBindProcess($sql, $bind, $value, $type);
        }

        $this->resetSql();

        return $sql;
    }


    private function fetchBindProcess($sql, $param, $value, $type = '') {
        $type   = strtolower($type);

        if (!Func::isEmpty($param)) {
            $_num_type = $this->getType($value, $type);

            if ($_num_type !== PDO::PARAM_STR && Func::isEmpty($value)) {
                $value = 0;
            }

            if ($_num_type === PDO::PARAM_STR) {
                $_str_value = '\'' . $value . '\'';
            } else {
                $_str_value = $value;
            }

            if (!is_numeric($param)) {
                //$sql = substr_replace($sql, $_str_value, strpos($sql, '?'), 1);
                $param = ':' . $param;
            }

            $sql = str_ireplace($param, $_str_value, $sql);
        }

        return $sql;
    }


    private function getType($value, $type = '') {
        $_num_type = $this->paramType['str'];

        if (Func::isEmpty($type)) {
            if (is_bool($value)) {
                $_str_type = 'bool';
            } else if (is_numeric($value)) {
                $_str_type = 'int';
            } else if (is_string($value)) {
                $_str_type = 'str';
            }

            if (isset($this->paramType[$_str_type])) {
                $_num_type = $this->paramType[$_str_type];
            }
        } else {
            if (isset($this->paramType[$type])) {
                $_num_type = $this->paramType[$type];
            }
        }

        return $_num_type;
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


    private function dsnProcess() {
        $_str_dsn = $this->dbconfig['type'] . ':host=' . $this->dbconfig['host'];

        if (isset($this->dbconfig['port']) && !Func::isEmpty($this->dbconfig['port'])) {
            $_str_dsn .= ';port=' . $this->dbconfig['port'];
        }

        $_str_dsn .= ';dbname=' . $this->dbconfig['name'];

        return $_str_dsn;
    }

    function resetSql() {
        $this->_tableTemp   = '';
        $this->_field       = '';
        $this->_force       = '';
        $this->_join        = '';
        $this->_distinct    = false;
        $this->_where       = '';
        $this->_group       = '';
        $this->_order       = '';
        $this->_limit       = '';
        $this->_bind        = '';
        $this->_whereOr     = array();
        $this->_whereAnd    = array();
        $this->_fetchSql    = false;
    }

    function __destruct() {
        if ($this->obj_pdo) {
            //$this->closeDb();
            //unset($this->obj_pdo);
        }
    }
}
