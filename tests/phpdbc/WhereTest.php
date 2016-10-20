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
		$where = new Where('id', 1, Operator::EQUAL());
		$this->assertInstanceOf(Where::class, $where);

		$actual = $where->toClause();
		$this->assertEquals("WHERE id = 1", $actual);
	}

	function testToClause() {
		try {
			new Where(null, 1, Operator::EQUAL());
			$this->fail('should throw Exception.');
		} catch (PhpdbcException $ignore){
		}

		try {
			new Where(null, null, Operator::EQUAL());
			$this->fail('should throw Exception.');
		} catch (PhpdbcException $ignore){
		}

		$actual = (new Where('name', 'name', Operator::LIKE()))->toClause();
		$this->assertEquals("WHERE name LIKE '%name%'", $actual);

		$actual = (new Where('name', Operator::IS_NULL()))->toClause();
		$this->assertEquals("WHERE name IS NULL", $actual);

		$actual = (new Where('name', Operator::IS_NOT_NULL()))->toClause();
		$this->assertEquals("WHERE name IS NOT NULL", $actual);
	}
}