<?php
/**
 * Created by IntelliJ IDEA.
 * User: maeda
 * Date: 10/16/2016
 * Time: 11:49 PM
 */

namespace phpdbc;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use ReflectionObject;

class Operator {

	const EQUAL = ['=', true];
	const NOT_EQUAL = ['<>', true];
	const LIKE = ['LIKE', true];
	const IS_NULL = ['IS NULL', false];
	const IS_NOT_NULL = ['IS NOT NULL', false];

	private $scalar;
	private $hasValue;

	function __construct($args) {
		if (!in_array($args, (new ReflectionObject($this))->getConstants(), true)) {
			throw new InvalidArgumentException();
		}
		$this->scalar =  $args[0];
		$this->hasValue = $args[1];
	}

	final static function __callStatic($label, $args) {
		$class = get_called_class();
		$const = constant("$class::$label");
		return new $class($const);
	}

	final function __toString() {
		return (string) $this->scalar;
	}

	function hasValue() {
		return (boolean) $this->hasValue;
	}
}