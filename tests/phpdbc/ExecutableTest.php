<?php
/**
 * Created by IntelliJ IDEA.
 * User: maeda
 * Date: 10/16/2016
 * Time: 8:37 AM
 */


use PHPUnit\Framework\TestCase;

use Phpdbc;
use phpdbc\PhpdbcException;

class ExecutableTest extends TestCase {

	function testExecute() {
		$manager = Phpdbc::connect("sqlite", "test.db");

		Phpdbc::struct('Fail', ['id', 'fail']);

		try {
			$manager->create(Fail::class)->execute();
			$this->fail('should throw Exception.');
		} catch (Exception $ignore) {
		}

		Phpdbc::struct('Success', ['id', 'name']);

		$manager->drop(Success::class)->execute();
		$manager->create(new Success('INTEGER', 'TEXT'))->execute();

		try {
			$manager->create(new Success('INTEGER', 'TEXT'))->execute();
			$this->fail('should throw PhpdbcException.');
		} catch (PhpdbcException $ignore) {
		}
	}

	function testGetSql() {
		$manager = Phpdbc::connect("sqlite", "test.db");

		Phpdbc::struct('Hoge', ['id', 'name']);

		$actual = $manager->create(new Hoge('INTEGER', 'TEXT'))->getSql();
		$expect = "CREATE TABLE Hoge (id INTEGER, name TEXT)";
		$this->assertEquals($expect, $actual);
	}
}