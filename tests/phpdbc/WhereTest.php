<?php
/**
 * Created by IntelliJ IDEA.
 * User: maeda
 * Date: 10/17/2016
 * Time: 12:05 AM
 */

use PHPUnit\Framework\TestCase;

use phpdbc\Where;
use phpdbc\Operator;
use phpdbc\PhpdbcException;

class WhereTest extends TestCase {

	function testConstructor() {
		$where = new Where('id', 1, Operator::EQUAL);
		$this->assertInstanceOf(Where::class, $where);

		$actual = $where->__toString();
		$this->assertEquals("WHERE id = 1", $actual);
	}

	function testToString() {
		try {
			new Where(null, 1, Operator::EQUAL);
			$this->fail('should throw Exception.');
		} catch (PhpdbcException $ignore){
		}

		try {
			new Where(null, null, Operator::EQUAL);
			$this->fail('should throw Exception.');
		} catch (PhpdbcException $ignore){
		}

		$actual = (new Where('name', 'name', Operator::LIKE))->__toString();
		$this->assertEquals("WHERE name LIKE '%name%'", $actual);
	}
}