<?php
/**
 * Created by IntelliJ IDEA.
 * User: maeda
 * Date: 10/16/2016
 * Time: 12:16 PM
 */

use PHPUnit\Framework\TestCase;

use phpdbc\Where;
use phpdbc\Operator;
use phpdbc\PhpdbcException;


Phpdbc::struct('Test', ['id', 'name']);

class FromCaseTest extends TestCase {

	function testList() {
		$manager = Phpdbc::connect('sqlite', 'test.db');
		$manager->drop(Test::class)->execute();
		$manager->create(new Test('INTEGER', 'TEXT'))->execute();

		$manager->insert(new Test(1, 'name_1'))->execute();
		$manager->insert(new Test(2, 'name_2'))->execute();

		$actual = $manager->from(Test::class)->list();
		$expect = [new Test(1, 'name_1'), new Test(2, 'name_2')];

		$this->assertEquals($expect, $actual);
	}

	function testSingleResult() {
		$manager = Phpdbc::connect('sqlite', 'test.db');
		$manager->drop(Test::class)->execute();
		$manager->create(new Test('INTEGER', 'TEXT'))->execute();

		$manager->insert(new Test(1, 'name_1'))->execute();
		$manager->insert(new Test(2, 'name_2'))->execute();

		$actual = $manager->from(Test::class)->where(new Where('id', 1, Operator::EQUAL))->singleResult();
		$expect = new Test(1, 'name_1');

		$this->assertEquals($expect, $actual);

		try {
			$manager->from(Test::class)->singleResult();
		} catch (PhpdbcException $ignore) {
		}
	}

	function testDelete() {
		$manager = Phpdbc::connect('sqlite', 'test.db');
		$manager->drop(Test::class)->execute();
		$manager->create(new Test('INTEGER', 'TEXT'))->execute();

		$manager->insert(new Test(1, 'name_1'))->execute();
		$manager->insert(new Test(2, 'name_2'))->execute();

		$manager->from(Test::class)->where(new Where('id', 1, Operator::EQUAL))->delete()->execute();

		$actual = $manager->from(Test::class)->singleResult();
		$expect = new Test(2, 'name_2');

		$this->assertEquals($expect, $actual);
	}

}