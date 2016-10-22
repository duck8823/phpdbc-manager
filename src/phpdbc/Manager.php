<?php
/**
 * Created by IntelliJ IDEA.
 * User: maeda
 * Date: 9/18/2016
 * Time: 7:58 PM
 */

namespace phpdbc;

use PDO;
use ReflectionClass;
use ReflectionProperty;

class Manager {

	private $db;

	function __construct($driver, $datasource, $user, $password) {
		$this->db = new PDO("$driver:$datasource", $user, $password);
	}

	function from($entity) {
		return new FromCase($this->db, $entity);
	}

	function drop($entity) {
		return new Executable($this->db, sprintf("DROP TABLE IF EXISTS %s", $entity));
	}

	function create($entity) {
		$columns = [];
		$refcls = new ReflectionClass($entity);
		$props = $refcls->getProperties(ReflectionProperty::IS_PUBLIC);

		foreach ($props as $prop) {
			$type = $prop->getValue($entity);
			if (!in_array($type, ["INTEGER", "TEXT", "BOOLEAN"])) {
				throw new PhpdbcException(sprintf("次の型は対応していません. :%s", $type));
			}
			array_push($columns, sprintf("%s %s", $prop->getName(), $type));
		}
		return new Executable($this->db, sprintf("CREATE TABLE %s (%s)", $refcls->getName(), join(", ", $columns)));
	}

	function insert($data) {
		$refcls = new ReflectionClass($data);
		return new Executable($this->db, sprintf("INSERT INTO %s %s", $refcls->getName(), $this->_createSentence($data)));
	}

	private static function _createSentence($data) {
		$columns = [];
		$values = [];
		$refcls = new ReflectionClass($data);
		foreach ($refcls->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
			array_push($columns, $prop->getName());
			$value = $prop->getValue($data);
			if (gettype($value) == 'boolean') {
				$value = $value ? 'TRUE' : 'FALSE';
			}
			array_push($values, $value != null ? sprintf("'%s'", $value) : "''");
		}
		return sprintf("(%s) VALUES (%s)", join(", ", $columns), join(", ", $values));
	}
}