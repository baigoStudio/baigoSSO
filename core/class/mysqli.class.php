<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

/*-------------数据库类-------------*/
class CLASS_MYSQLI {

    public $obj_mysqli;
    private $db_host;
    private $db_port;
    private $db_user;
    private $db_pass;
    public $db_name;
    private $db_charset;
    private $db_debug;
    public $db_rs;

    function __construct($cfg_host) {
        if (isset($cfg_host["host"])) {
            $this->db_host       = $cfg_host["host"];
        } else {
            $this->db_host       = "localhost";
        }

        if (isset($cfg_host["port"])) {
            $this->db_port       = $cfg_host["port"];
        } else {
            $this->db_port       = "3306";
        }

        if (isset($cfg_host["user"])) {
            $this->db_user       = $cfg_host["user"];
        } else {
            $this->db_user       = "root";
        }

        if (isset($cfg_host["pass"])) {
            $this->db_pass       = $cfg_host["pass"];
        } else {
            $this->db_pass       = "";
        }

        if (isset($cfg_host["name"])) {
            $this->db_name       = $cfg_host["name"];
        } else {
            $this->db_name       = "";
        }

        if (isset($cfg_host["charset"])) {
            $this->db_charset    = $cfg_host["charset"];
        } else {
            $this->db_charset    = "utf8";
        }

        if (isset($cfg_host["debug"])) {
            $this->db_debug      = $cfg_host["debug"];
        } else {
            $this->db_debug      = false;
        }
    }

    function connect() {
        $this->obj_mysqli = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name, $this->db_port);
        if ($this->obj_mysqli) {
            //$this->obj_mysqli->set_charset($this->db_charset);
            $this->obj_mysqli->query("SET NAMES " . $this->db_charset);
            return true;
        } else {
            return false;
        }
    }

    function select_db() {
        $_is_select  = $this->obj_mysqli->select_db($this->db_name);

        return $_is_select;
    }

    function query($sql) {
        $query = $this->obj_mysqli->query($sql);
        if ($query) {
            $this->db_rs = $query;
            return $query;
        } else {
            return false;
        }
    }

    function fetch_row($rs = "") {
        $rs = $rs ? $rs : $this->db_rs;
        return $rs->fetch_row();
    }

    function fetch_assoc($rs = "") {
        $rs = $rs ? $rs : $this->db_rs;
        return $rs->fetch_assoc();
    }

    function affected_rows() {
        return $this->obj_mysqli->affected_rows;
    }

    function insert_id() {
        $_obj_temp    = $this->query("SELECT LAST_INSERT_ID()");
        $this->db_rs  = $this->fetch_row($_obj_temp);
        return $this->db_rs[0];
    }

    function close() {
        $this->obj_mysqli->close();
    }

    function create_table($table, $data, $primary, $comment = "", $engine = "MyISAM") {
        $sql      = "CREATE TABLE IF NOT EXISTS `" . $table . "` (";
        $values   = array();
        foreach ($data as $key => $value) {
            $values[] = "`" . $key . "` " . $value;
        }
        $sql         .= implode(",", $values);
        $sql         .= ", PRIMARY KEY (`" . $primary . "`)) ENGINE=" . $engine . "  DEFAULT CHARSET=" . $this->db_charset . " COMMENT='" . $comment . "' AUTO_INCREMENT=1";
        $this->db_rs  = $this->query($sql);
        return $this->db_rs;
    }

    function create_index($index, $table, $data, $type = "BTREE", $is_exists = false) {
        if ($is_exists) {
            $sql = "DROP INDEX `" . $index . "` ON `" . $table . "`";
            $this->query($sql);
        }

        $sql      = "CREATE INDEX `" . $index . "` ON `" . $table . "` (";
        $values   = array();
        foreach ($data as $key=>$value) {
            $values[] = "`" . $value . "` ";
        }
        $sql         .= implode(",", $values);
        $sql         .= ") USING " . $type . "";
        $this->db_rs  = $this->query($sql);
        return $this->db_rs;
    }

    function create_view($view, $data, $table, $join) {
        $sql      = "CREATE OR REPLACE VIEW `" . $view . "` AS SELECT ";
        $values   = array();
        foreach ($data as $key=>$value) {
            $_str_view = "`" . $value[1] . "`.`" . $value[0] . "`";
            if (isset($value[2])) {
                $_str_view .= " AS `" . $value[2] . "`";
            } else {
                $_str_view .= " AS `" . $value[0] . "`";
            }
            $values[] = $_str_view;
        }
        $sql         .= implode(",", $values);
        $sql         .= " FROM `" . $table . "` " . $join;
        //print_r($sql);
        $this->db_rs  = $this->query($sql);
        return $this->db_rs;
    }

    function copy_table($table, $table_src, $data, $primary, $comment = "", $engine = "MyISAM") {
        $sql  = "CREATE TABLE IF NOT EXISTS `" . $table . "` (";

        $values   = array();
        foreach ($data as $key => $value) {
            $values[] = "`" . $key . "` " . $value;
        }
        $sql     .= implode(",", $values);

        $sql     .= ", PRIMARY KEY (`" . $primary . "`)) ENGINE=" . $engine . " DEFAULT CHARSET=" . $this->db_charset . " COMMENT='" . $comment . "' ";
        $sql     .= " SELECT ";

        $values   = array();
        foreach ($data as $key => $value) {
            $values[] = "`" . $key . "`";
        }
        $sql .= implode(",", $values);

        $sql .= " FROM `" . $table_src . "` WHERE 1=1";

        $this->db_rs  = $this->query($sql);
        return $this->db_rs;
    }

    function alert_table($table, $data = false, $rename = false) {
        $sql      = "ALTER TABLE `" . $table . "` ";
        if ($rename) {
            $sql .= " RENAME TO `" . $rename . "`";
        }
        if ($data) {
            $values  = array();
            foreach ($data as $key => $value) {
                switch ($value[0]) {
                    case "ADD":
                        $values[] = $value[0] . " COLUMN `" . $key . "` " . $value[1];
                    break;
                    case "DROP":
                        $values[] = $value[0] . " COLUMN `" . $key . "`";
                    break;
                    case "DROP PRIMARY KEY":
                        $values[] = $value[0];
                    break;
                    case "ADD PRIMARY KEY":
                        $values[] = $value[0] . " (`" . $value[1] . "`)";
                    break;
                    case "CHANGE":
                        $values[] = $value[0] . " COLUMN `" . $key . "` `" . $value[2] . "` " . $value[1];
                    break;
                }
            }
            $sql         .= implode(",", $values);
        }
        $this->db_rs = $this->query($sql);
        return $this->db_rs;
    }

    function insert($table, $data) {
        $sql      = "INSERT INTO `" . $table . "` SET ";
        $values   = array();
        foreach ($data as $key => $value) {
            $values[] = "`" . $key . "`='" . $value . "'";
        }
        $sql         .= implode(",", $values);
        $this->db_rs  = $this->query($sql);
        if (!$this->db_rs) {
            return false;
        }
        return $this->insert_id();
    }

    function update($table, $data, $where = "", $field = false) {
        $sql      = "UPDATE `" . $table . "` SET ";
        $values   = array();
        foreach ($data as $key => $value) {
            if ($field) {
                $values[] = "`" . $key . "`=" . $value;
            } else {
                $values[] = "`" . $key . "`='" . $value . "'";
            }
        }
        $sql         .= implode(",", $values);
        $sql          = ($where == "") ? $sql : $sql . " WHERE " . $where;
        $this->db_rs  = $this->query($sql);
        if (!$this->db_rs) {
            return false;
        }
        return $this->affected_rows();
    }

    function delete($table, $where) {
        $sql          = "DELETE FROM `" . $table . "` WHERE " . $where;
        $this->db_rs  = $this->query($sql);
        if (!$this->db_rs) {
            return false;
        }
        return $this->affected_rows();
    }

    function count($table, $where = "", $distinct = "") {
        $sql = "SELECT";
        if ($distinct) {
            $sql .= " COUNT(DISTINCT `" . implode("`,`", $distinct) . "`) FROM `" . $table . "`";
        } else {
            $sql .= " COUNT(*) FROM `" . $table . "`";
        }
        if ($where) {
            $sql .= " WHERE " . $where;
        }
        //print_r($sql);
        $this->db_rs  = $this->query($sql);
        if (!$this->db_rs) {
            return false;
        }
        $obj_temp     = $this->fetch_row($this->db_rs);
        return $obj_temp[0];
    }

    function select($table, $data = "", $where = "", $group = "", $order = "", $length = 0, $start = 0, $distinct = "", $field = false) {
        $sql = "SELECT";
        if ($data) {
            if ($field) {
                $sql .= " " . implode(",", $data);
            } else {
                $sql .= " `" . implode("`,`", $data) . "`";
            }
        } else {
            $sql .= " *";
        }
        if ($distinct) {
            $sql .= ", COUNT(DISTINCT `" . implode(",", $distinct) . "`)";
        }
        $sql .= " FROM `" . $table . "`";
        if ($where) {
            $sql .= " WHERE " . $where;
        }
        if ($group) {
            $sql .= " GROUP BY " . $group;
        }
        if ($order) {
            $sql .= " ORDER BY " . $order;
        }
        if ($length > 0) {
            $sql .= " LIMIT " . $start . ", " . $length;
        }

        //print_r($sql);
        /*if ($field) {
        print_r($sql . "\n");
        }*/

        $this->db_rs  = $this->query($sql);
        if (!$this->db_rs) {
            return false;
        }
        $obj          = array();

        while ($obj_temp = $this->fetch_assoc($this->db_rs)) {
            $obj[] = $obj_temp;
            unset($obj_temp);
        }
        return $obj;
    }

    function show_columns($table) {
        $sql = "SHOW COLUMNS FROM `" . $table . "`";
        $this->db_rs  = $this->query($sql);
        if (!$this->db_rs) {
            return false;
        }
        $obj          = array();

        while ($obj_temp = $this->fetch_assoc($this->db_rs)) {
            $obj[] = $obj_temp;
            unset($obj_temp);
        }
        return $obj;
    }


    function show_index($table) {
        $sql = "SHOW INDEX FROM `" . $table . "`";
        $this->db_rs  = $this->query($sql);
        if (!$this->db_rs) {
            return false;
        }
        $obj          = array();

        while ($obj_temp = $this->fetch_assoc($this->db_rs)) {
            $obj[] = $obj_temp;
            unset($obj_temp);
        }
        return $obj;
    }


    function show_tables() {
        $sql = "SHOW TABLES FROM `" . $this->db_name . "`";
        $this->db_rs  = $this->query($sql);
        if (!$this->db_rs) {
            return false;
        }
        $obj          = array();

        while ($obj_temp = $this->fetch_assoc($this->db_rs)) {
            $obj[] = $obj_temp;
            unset($obj_temp);
        }
        return $obj;
    }


    function show_databases() {
        $sql = "SHOW DATABASES";
        $this->db_rs  = $this->query($sql);
        if (!$this->db_rs) {
            return false;
        }
        $obj          = array();

        while ($obj_temp = $this->fetch_assoc($this->db_rs)) {
            $obj[] = $obj_temp;
            unset($obj_temp);
        }
        return $obj;
    }


    function __destruct() {
        if ($this->obj_mysqli) {
            //$this->close();
            //unset($this->obj_mysqli);
        }
    }
}
