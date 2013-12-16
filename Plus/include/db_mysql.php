<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

class dbstuff {
	var $querynum = 0;
	//function dbstuff() { global $fp; $fp = fopen("./dblog.txt", "w"); }

	function connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect = 0) {
		if($pconnect) {
			if(!@mysql_pconnect($dbhost, $dbuser, $dbpw)) {
				$this->halt('Can not connect to MySQL server');
			}
		} else {
			if(!@mysql_connect($dbhost, $dbuser, $dbpw)) {
				$this->halt('Can not connect to MySQL server');
			}
		}

		mysql_select_db($dbname);
	}

	function select_db($dbname) {
		return mysql_select_db($dbname);
	}

	function fetch_array($query, $result_type = MYSQL_ASSOC) {
		return mysql_fetch_array($query, $result_type);
	}

	function query($sql, $silence = 0) {
		//echo "|$sql|<br>"; //debug
		//@fwrite($GLOBALS[fp], $sql."\n"); //debug
		$query = mysql_query($sql);
		if(!$query && !$silence) {
			$this->halt('MySQL Query Error', $sql);
		}
		$this->querynum++;
		return $query;
	}

	function unbuffered_query($sql, $silence = 0) {
		$func_unbuffered_query = @function_exists('mysql_unbuffered_query') ? 'mysql_unbuffered_query' : 'mysql_query';
		$query = $func_unbuffered_query($sql);
		if(!$query && !$silence) {
			$this->halt('MySQL Query Error', $sql);
		}
		$this->querynum++;
		return $query;
	}

	function affected_rows() {
		return mysql_affected_rows();
	}

	function error() {
		return mysql_error();
	}

	function errno() {
		return mysql_errno();
	}

	function result($query, $row) {
		$query = @mysql_result($query, $row);
		return $query;
	}

	function num_rows($query) {
		$query = mysql_num_rows($query);
		return $query;
	}

	function num_fields($query) {
		return mysql_num_fields($query);
	}

	function free_result($query) {
		return mysql_free_result($query);
	}

	function insert_id() {
		$id = mysql_insert_id();
		return $id;
	}

	function fetch_row($query) {
		$query = mysql_fetch_row($query);
		return $query;
	}

	function close() {
		return mysql_close();
	}

	function halt($message = '', $sql = '') {
		require './include/db_mysql_error.php';
	}
}
?>