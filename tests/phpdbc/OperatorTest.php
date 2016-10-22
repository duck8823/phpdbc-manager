<?php
/**
 * Created by IntelliJ IDEA.
 * User: maeda
 * Date: 10/17/2016
 * Time: 12:16 AM
 */

use PHPUnit\Framework\TestCase;

use phpdbc\Operator;

class OperatorTest extends TestCase {

	function testConstruct() {
		try {
			new Operator(['HOGE', null]);
			$this->fail('should throw Exception.');
		} catch (InvalidArgumentException $ignore) {
		}
	}

	function testOperator() {
		$this->assertEquals('=', Operator::EQUAL());
		$this->assertEquals('<>', Operator::NOT_EQUAL());
		$this->assertEquals('LIKE', Operator::LIKE());
	}
}