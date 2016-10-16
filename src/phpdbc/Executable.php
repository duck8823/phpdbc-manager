<?php
/**
 * Created by IntelliJ IDEA.
 * User: maeda
 * Date: 10/10/2016
 * Time: 11:55 AM
 */

namespace phpdbc;


class Executable {

	private $db;
	private $sql;

	function __construct($db, $sql) {
		$this->db = $db;
		$this->sql = $sql;
	}

	function execute() {
		if (gettype($this->db->exec($this->sql)) != 'integer') {
			throw new PhpdbcException('SQLの実行に失敗しました: ' . $this->sql);
		}
	}

	public function getSql() {
		return $this->sql;
	}
}