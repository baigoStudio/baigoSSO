<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

/*-------------数据库类-------------*/
class CLASS_MYSQL {

	private $db_host;
	private $db_user;
	private $db_pass;
	public $db_name;
	private $db_charset;
	private $db_debug;
	public $db_rs;
	public $db_link;
	public $db_select;

	function __construct($cfg_host) {
		if (isset($cfg_host["host"])) {
			$this->db_host       = $cfg_host["host"];
		} else {
			$this->db_host       = "localhost";
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

		$this->connect();
		$this->select_db();
		unset($this->db_host);
		unset($this->db_user);
		unset($this->db_pass);
		unset($this->db_charset);
		unset($this->db_debug);
	}

	function connect($db_host = "", $db_user = "", $db_pass = "", $db_charset = "") {
		$db_host          = $db_host ? $db_host : $this->db_host;
		$db_user          = $db_user ? $db_user : $this->db_user;
		$db_pass          = $db_pass ? $db_pass : $this->db_pass;
		$db_charset       = $db_charset ? $db_charset : $this->db_charset;
		$this->db_link    = mysql_connect($db_host, $db_user, $db_pass);

		if ($this->db_link) {
			mysql_set_charset($db_charset);
			//mysql_query("SET NAMES " . $db_charset);
			return $this->db_link;
		} else {
			return $this->halt("Can't connect database");
		}
	}

	function select_db($db_name = "", $db_link = "") {
		$db_name          = $db_name ? $db_name : $this->db_name;
		$db_link          = $db_link ? $db_link : $this->db_link;
		$this->db_select  = mysql_select_db($db_name, $db_link);

		if ($this->db_select) {
			return $this->db_select;
		} else {
			return $this->halt("Database not exist");
		}
	}

	function query($sql) {
		$query = mysql_query($sql, $this->db_link);
		if ($query) {
			$this->db_rs = $query;
			return $query;
		} else {
			return $this->halt($sql . " Query error");
		}
	}

	function fetch_object($rs = "") {
		$rs = $rs ? $rs : $this->db_rs;
		return mysql_fetch_object($rs);
	}

	function fetch_array($rs = "", $result_type = MYSQL_ASSOC) {
		$rs = $rs ? $rs : $this->db_rs;
		return mysql_fetch_array($rs, $result_type);
	}

	function fetch_row($rs = "") {
		$rs = $rs ? $rs : $this->db_rs;
		return mysql_fetch_row($rs);
	}

	function fetch_assoc($rs = "") {
		$rs = $rs ? $rs : $this->db_rs;
		return mysql_fetch_assoc($rs);
	}

	function num_rows($rs = "") {
		$rs = $rs ? $rs :$this->db_rs;
		return mysql_num_rows($rs);
	}

	function affected_rows() {
		return mysql_affected_rows();
	}

	function insert_id() {
		$this->db_rs = $this->fetch_row($this->query("SELECT LAST_INSERT_ID()"));
		return $this->db_rs[0];
	}

	function error() {
		return mysql_error();
	}

	function errno() {
		return mysql_errno();
	}

	function halt($message) {
		if(isset($this->db_debug)) {
			echo "<div>" . $message . "</div>";
			echo "<div>Error: " . $this->error() . "</div>";
			echo "<div>Error number: " . $this->errno() . "</div>";
		}
		exit;
	}

	function close() {
		mysql_close($this->db_link);
	}

	function client_encoding() {
		return mysql_client_encoding();
	}

	function data_seek($row_number = 1, $rs = "") {
		$rs = $rs ? $rs : $this->db_rs;
		return mysql_data_seek($rs, $row_number);
	}

	function fetch_lengths($rs = "") {
		$rs = $rs ? $rs : $this->db_rs;
		return mysql_fetch_lengths($rs);
	}

	function field_flags($field_offset = 1, $rs = "") {
		$rs = $rs ? $rs : $this->rs;
		return mysql_field_flags($rs, $field_offset);
	}

	function field_len($field_offset = 1, $rs = "") {
		$rs = $rs ? $rs : $this->db_rs;
		return mysql_field_len($rs, $field_offset);
	}

	function field_name($field_index, $rs = "") {
		$rs = $rs ? $rs : $this->db_rs;
		return mysql_field_name($rs, $field_index);
	}

	function field_seek($field_offset = 1, $rs = "") {
		$rs = $rs ? $rs : $this->db_rs;
		return mysql_field_seek($rs, $field_offset);
	}

	function field_type($field_offset = 1, $rs = "") {
		$rs = $rs ? $rs : $this->db_rs;
		return mysql_field_type($rs, $field_offset);
	}

	function get_client_info() {
		return mysql_get_client_info();
	}

	function get_host_info() {
		return mysql_get_host_info();
	}

	function get_proto_info() {
		return mysql_get_proto_info();
	}

	function get_server_info() {
		return mysql_get_server_info();
	}

	function info() {
		return mysql_info();
	}

	function back_structure($table) {
		$this->rs         = $this->query("SHOW CREATE TABLE " . $table);
		$array_structute  = $this->fetch_row($this->rs);
		return $array_structute[1];
	}

	function create_table($table, $data, $primary, $comment = "", $engine = "MyISAM", $charset = "utf8") {
		$sql      = "CREATE TABLE IF NOT EXISTS `" . $table . "` (";
		$values   = array();
		foreach ($data as $key => $value) {
			$values[] = "`" . $key . "` " . $value;
		}
		$sql         .= implode(",", $values);
		$sql         .= ", PRIMARY KEY (`" . $primary . "`)) ENGINE=" . $engine . "  DEFAULT CHARSET=" . $charset . " COMMENT='" . $comment . "' AUTO_INCREMENT=1";
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
		foreach ($data as $value) {
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
		foreach ($data as $key => $value) {
			$values[] = "`" . $value . "`.`" . $key . "` AS `" . $key . "`";
		}
		$sql         .= implode(",", $values);
		$sql         .= " FROM `" . $table . "` " . $join;
		//print_r($sql);
		$this->db_rs  = $this->query($sql);
		return $this->db_rs;
	}

	function copy_table($table, $table_src, $data, $primary, $comment = "", $engine = "MyISAM", $charset = "utf8") {
		$sql  = "CREATE TABLE IF NOT EXISTS `" . $table . "` (";

		$values   = array();
		foreach ($data as $key => $value) {
			$values[] = "`" . $key . "` " . $value;
		}
		$sql     .= implode(",", $values);

		$sql     .= ", PRIMARY KEY (`" . $primary . "`)) ENGINE=" . $engine . " DEFAULT CHARSET=" . $charset . " COMMENT='" . $comment . "' ";
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
		return $this->insert_id();
	}

	function update($table, $data, $condition = "", $field = false) {
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
		$sql          = ($condition == "") ? $sql : $sql . " WHERE " . $condition;
		$this->db_rs  = $this->query($sql);
		return $this->affected_rows();
	}

	function delete($table, $condition) {
		$sql          = "DELETE FROM `" . $table . "` WHERE " . $condition;
		$this->db_rs  = $this->query($sql);
		return $this->affected_rows();
	}

	function count($table, $condition = "", $distinct = "") {
		$sql = "SELECT";
		if ($distinct) {
			$sql .= " COUNT(DISTINCT `" . implode("`,`", $distinct) . "`) FROM `" . $table . "`";
		} else {
			$sql .= " COUNT(*) FROM `" . $table . "`";
		}
		if ($condition) {
			$sql .= " WHERE " . $condition;
		}
		//print_r($sql);
		$this->db_rs  = $this->query($sql);
		$obj_temp     = $this->fetch_row($this->db_rs);
		return $obj_temp[0];
	}

	function select_obj($table, $data = "", $condition = "", $length = 0, $start = 0, $distinct = "", $field = false) {
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
		if ($condition) {
			$sql .= " WHERE " . $condition;
		}
		if ($length > 0) {
			$sql .= " LIMIT " . $start . ", " . $length;
		}
		$this->db_rs  = $this->query($sql);
		$obj          = array();

		while ($obj_temp = $this->fetch_object($this->db_rs)) {
			$obj[] = $obj_temp;
			unset($obj_temp);
		}
		return $obj;
	}

	function select_array($table, $data = "", $condition = "", $length = 0, $start = 0, $distinct = "", $field = false) {
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
		if ($condition) {
			$sql .= " WHERE " . $condition;
		}
		if ($length > 0) {
			$sql .= " LIMIT " . $start . ", " . $length;
		}

		//print_r($sql);
		/*if ($field) {
		print_r($sql . "\n");
		}*/

		$this->db_rs  = $this->query($sql);
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
		$obj          = array();

		while ($obj_temp = $this->fetch_assoc($this->db_rs)) {
			$obj[] = $obj_temp;
			unset($obj_temp);
		}
		return $obj;
	}


	function __destruct() {
		if ($this->db_link) {
			//$this->close();
			//unset($this->db_link);
		}
	}
}
