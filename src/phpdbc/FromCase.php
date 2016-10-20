<?php
/**
 * Created by IntelliJ IDEA.
 * User: maeda
 * Date: 9/30/2016
 * Time: 4:37 AM
 */

namespace phpdbc;

use ReflectionClass;
use ReflectionProperty;

class FromCase {

	private $db;
	private $entity;
	private $where;

	function __construct($db, $entity) {
		$this->db = $db;
		$this->entity = $entity;
		$this->where = new Where();
	}

	function where($where) {
		$this->where = $where;
		return $this;
	}

	function list() {
		$results = [];
		$refcls = new ReflectionClass($this->entity);
		$columns = [];
		foreach ($refcls->getProperties(ReflectionProperty::IS_PUBLIC) as $prop) {
			array_push($columns, $prop->getName());
		}
		$sth = $this->db->prepare(sprintf("SELECT %s FROM %s %s", join(", ", $columns), $this->entity, $this->where->toClause()));
		$sth->execute();
		foreach ($sth->fetchAll() as $result) {
			$values = [];
			foreach ($columns as $column) {
				array_push($values, $result[$column]);
			}
			array_push($results, $refcls->newInstanceArgs($values));
		}
		return $results;
	}

	function singleResult() {
		$result = self::list();
		if (count($result) > 1) {
			throw new PhpdbcException('結果が一意でありません.');
		}
		return $result[0];
	}

	function delete() {
		return new Executable($this->db, sprintf("DELETE FROM %s %s", $this->entity, $this->where->toClause()));
	}
}