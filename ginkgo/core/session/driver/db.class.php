<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo\session\driver;

use PDO;
use ginkgo\Func;
use ginkgo\Config;
use ginkgo\Exception;
use ginkgo\session\Driver;
use ginkgo\Db as Db_Base;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

// 数据库类型的会话驱动
class Db extends Driver {

    private $obj_db; // 数据库实例

    /** 构造函数
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct($config = array()) {
        parent::__construct($config);

        if (isset($this->config['dbconfig'])) {
            $this->obj_db = Db_Base::connect($this->config['dbconfig']);
        } else  {
            $this->obj_db = Db_Base::connect();
        }

        $_arr_tableRows = $this->showTables();

        if (!in_array($this->obj_db->config['prefix'] . 'session', $_arr_tableRows)) {
            $this->createTable();
        }
    }


    /** 开启会话
     * open function.
     *
     * @access public
     * @param mixed $save_path
     * @param mixed $session_name
     * @return void
     */
    public function open($save_path, $session_name) {
        // get session-lifetime
        if ($this->config['life_time'] <= 1) {
            $this->config['life_time'] = get_cfg_var('session.gc_maxlifetime');
        }

        return true;
    }


    /** 关闭会话
     * close function.
     *
     * @access public
     * @return void
     */
    public function close() {
        // $this->gc(ini_get('session.gc_maxlifetime'));

        return true;
    }


    /** 读取会话
     * read function.
     *
     * @access public
     * @param mixed $session_id
     * @return 会话数据
     */
    public function read($session_id) {
        //print_r('read');
        $_arr_sessionRow['session_data'] = '';

        $_arr_sessionRow = $this->readProcess($session_id, GK_NOW);

        return $_arr_sessionRow['session_data'];
    }


    /** 写入会话
     * write function.
     *
     * @access public
     * @param mixed $session_id
     * @param mixed $session_data
     * @return void
     */
    public function write($session_id, $session_data) {
        //print_r('write');
        $_status = false;

        $_tm_expire = GK_NOW + $this->config['life_time'];
        // is a session with this id in the database?

        $_arr_sessionData = array(
            'session_data'      => $session_data,
            'session_expire'    => $_tm_expire,
        );

        $_arr_sessionRow = $this->readProcess($session_id);

        if ($_arr_sessionRow) {
            $_num_db  = $this->obj_db->table('session')->where('session_id', '=', $session_id)->update($_arr_sessionData);

            if ($_num_db > 0) { //数据库更新是否成功
                $_status = true;
            }
        } else { // if no session-data was found,
            $_arr_sessionData['session_id'] = $session_id;

            $_num_db  = $this->obj_db->table('session')->insert($_arr_sessionData);

            if ($_num_sessionId > 0) { //数据库插入是否成功
                $_status = true;
            }
        }
        // an unknown error occured
        return $_status;
    }


    /** 销毁会话
     * destroy function.
     *
     * @access public
     * @param mixed $session_id
     * @return void
     */
    public function destroy($session_id) {
        $_status = false;

        // delete session-data
        $_num_db = $this->obj_db->table('session')->where('session_id', '=', $session_id)->delete();

        //如车影响行数小于0则返回错误
        if ($_num_db > 0) {
            $_status = true;
        }

        return $_status;
    }


    /** 清理会话
     * gc function.
     *
     * @access public
     * @param mixed $ssin_max_lifetime
     * @return void
     */
    public function gc($ssin_max_lifetime) {
        $_status = false;

        $_num_db = $this->obj_db->table('session')->where('session_expire', '<', GK_NOW)->delete();

        if ($_num_db > 0) {
            $_status = true;
        }

        return $_status;
    }


    /** 读取处理
     * readProcess function.
     *
     * @access private
     * @param mixed $session_id
     * @param int $expire (default: 0)
     * @return 数据记录
     */
    private function readProcess($session_id, $expire = 0) {
        $_arr_sessionSelect = array(
            'session_id',
            'session_data',
            'session_expire',
        );

        $_arr_where[]  = array('session_id', '=', $session_id);

        if ($expire > 0) {
            $_arr_where[]  = array('session_expire', '>', $expire);
        }

        $_arr_sessionRow = $this->obj_db->table('session')->where($_arr_where)->find($_arr_sessionSelect);

        return $_arr_sessionRow;
    }


    /** 创建表
     * createTable function.
     *
     * @access private
     * @return void
     */
    private function createTable() {
        $_str_sql    = 'CREATE TABLE IF NOT EXISTS `' . $this->obj_db->config['prefix'] . 'session` (';
        $_str_sql   .= '`session_id` varchar(255) NOT NULL DEFAULT \'\' COMMENT \'ID\',';
        $_str_sql   .= '`session_data` text NOT NULL DEFAULT \'\' COMMENT \'SESSION 数据\',';
        $_str_sql   .= '`session_expire` int NOT NULL DEFAULT 0 COMMENT \'SESSION 过期时间\',';
        $_str_sql   .= ' PRIMARY KEY (`session_id`)';
        $_str_sql   .= ') ENGINE=InnoDB DEFAULT CHARSET=' . $this->obj_db->config['charset'] . ' COMMENT=\'SESSION\' AUTO_INCREMENT=1 COLLATE utf8_general_ci';

        //print_r($_str_sql);

        $_num_count  = $this->obj_db->exec($_str_sql);

        if ($_num_count === false) {
            throw new Exception('Create session table failed', 500);
        }
    }


    /** 列出表
     * showTables function.
     *
     * @access private
     * @return void
     */
    private function showTables() {
        $_str_sql = 'SHOW TABLES FROM `' . $this->obj_db->config['name'] . '`';

        $_query_result  = $this->obj_db->query($_str_sql);

        $_arr_tables    = $this->obj_db->getResult(true, PDO::FETCH_NUM);

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
}
