<?php
/**
 * Created by IntelliJ IDEA.
 * User: maeda
 * Date: 9/30/2016
 * Time: 4:46 AM
 */

namespace phpdbc;


use Doctrine\Instantiator\Exception\InvalidArgumentException;

class Where {

	private $column;
	private $value;
	private $operator;

	function __construct() {
		$column = null;
		$value = null;
		$operator = null;
		switch (func_num_args()) {
			case 0:
				break;
			case 2:
				$column = func_get_arg(0);
				$operator = func_get_arg(1);
				break;
			case 3:
				$column = func_get_arg(0);
				$value = func_get_arg(1);
				$operator = func_get_arg(2);
				break;
			default:
				throw new InvalidArgumentException();
		}
		if (($column != null && $operator == null) || ($column == null && $operator != null) || ($column == null && $operator == null && $value != null)) {
			throw new PhpdbcException();
		}

		if ($column != null && gettype($column) != 'string' ) {
			throw new InvalidArgumentException('column should be string type: ' . gettype($column));
		} elseif ($operator != null && (is_array($operator) || get_class($operator) != Operator::class)) {
			throw new InvalidArgumentException('operator should be Operator type: ' . gettype($operator));
		}
		$this->column = $column;
		$this->value = $value;
		$this->operator = $operator;
	}

	function toClause() {
		if ($this->column == null && $this->value == null && $this->operator == null) {
			return "";
		}
		$value = $this->value;
		if (!$this->operator->hasValue()) {
			return sprintf("WHERE %s %s", $this->column, $this->operator);
		} elseif (gettype($value) == 'string' && $this->operator == Operator::LIKE()) {
			$value = "'%$value%'";
		} else {
			$value = "'$value'";
		}
		return sprintf("WHERE %s %s %s", $this->column, $this->operator, $value);
	}
}