<?php
/**
 * Created by IntelliJ IDEA.
 * User: maeda
 * Date: 9/30/2016
 * Time: 4:46 AM
 */

namespace phpdbc;


class Where {

	private $column;
	private $value;
	private $operator;

	function __construct($column = null, $value = null, $operator = null) {
		if ($column != null && gettype($column) != 'string') {
			throw new \Exception("カラム名はstring型でなければなりません.");
		}
		if (($column != null && $operator == null) || ($column == null && $operator != null) || ($column == null && $operator == null && $value != null)) {
			throw new \Exception();
		}
		$this->column = $column;
		$this->value = $value;
		$this->operator = $operator;
	}

	function __toString() {
		if ($this->column == null && $this->value == null && $this->operator == null) {
			return "";
		}
		$value = $this->value;
		if ($value == null) {
			$value = "NULL";
		} elseif (gettype($value) == 'string' && $this->operator == Operator::LIKE) {
			$value = "'%$value%'";
		} elseif (gettype($value) == 'string') {
			$value = "'$value'";
		}
		return sprintf("WHERE %s %s %s", $this->column, $this->operator, $value);
	}
}

final class Operator {
	const EQUAL = '=';
	const NOT_EQUAL = '<>';
	const LIKE = 'LIKE';
}